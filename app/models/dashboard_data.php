<?php
require_once __DIR__ . '/config.php';

function get_configuracion(): array {
    return db()->query("SELECT * FROM configuracion WHERE id=1")->fetch_assoc() ?? [];
}

function get_ingreso_total(string $start, string $end, array $cfg): array {
    $sueldoQuincenal = (float)($cfg['sueldo_quincenal'] ?? 0);
    $quincenasMes = (int)($cfg['quincenas_mes'] ?? 2);
    $autoIngreso = (int)($cfg['ingreso_automatico'] ?? 1);

    $ingresoNomina = ($autoIngreso === 1)
        ? ($sueldoQuincenal * $quincenasMes)
        : 0;

    $stmt = db()->prepare("SELECT COALESCE(SUM(monto),0) AS total FROM ingresos_extras WHERE fecha BETWEEN ? AND ?");
    $stmt->bind_param("ss", $start, $end);
    $stmt->execute();
    $ingresoExtras = (float)$stmt->get_result()->fetch_assoc()['total'];

    return [
        'nomina' => $ingresoNomina,
        'extras' => $ingresoExtras,
        'total' => $ingresoNomina + $ingresoExtras,
        'auto_ingreso' => $autoIngreso,
        'sueldo_quincenal' => $sueldoQuincenal,
        'quincenas_mes' => $quincenasMes,
    ];
}

function get_gastos_dashboard(string $start, string $end, float $ingreso, array $cfg): array {
    $tope = (float)($cfg['tope_libre'] ?? 0);

    $fijos = (float)db()->query("SELECT COALESCE(SUM(monto),0) AS total FROM gastos_fijos WHERE activo=1")->fetch_assoc()['total'];
    $deudasPago = (float)db()->query("SELECT COALESCE(SUM(pago_mensual),0) AS total FROM deudas WHERE activo=1")->fetch_assoc()['total'];

    $stmt = db()->prepare("SELECT COALESCE(SUM(monto),0) AS total FROM gastos_libres WHERE fecha BETWEEN ? AND ?");
    $stmt->bind_param("ss", $start, $end);
    $stmt->execute();
    $libres = (float)$stmt->get_result()->fetch_assoc()['total'];

    $topePct = ($tope > 0)
        ? max(0, min(100, ($libres / $tope) * 100))
        : 0;

    return [
        'fijos' => $fijos,
        'deudas_pago' => $deudasPago,
        'libres' => $libres,
        'tope' => $tope,
        'tope_pct' => $topePct,
        'total_comprometido' => $fijos + $deudasPago,
        'libre_disponible' => $ingreso - $fijos - $deudasPago - $libres,
    ];
}

function get_bbva_dashboard(array $cfg): array {
    $bbvaCorte = (int)($cfg['bbva_corte_dia'] ?? 6);
    $bbvaLimite = (int)($cfg['bbva_limite_dia'] ?? 26);
    $cycle = bbva_cycle($bbvaCorte, $bbvaLimite);

    $stmt = db()->prepare("SELECT COALESCE(SUM(monto),0) AS total FROM gastos_libres WHERE metodo='TDC BBVA' AND fecha BETWEEN ? AND ?");
    $stmt->bind_param("ss", $cycle['cycle_start'], $cycle['cycle_end_cut']);
    $stmt->execute();
    $bbvaLibresCiclo = (float)$stmt->get_result()->fetch_assoc()['total'];

    $bbvaFijos = (float)db()->query("SELECT COALESCE(SUM(monto),0) AS total FROM gastos_fijos WHERE activo=1 AND metodo='TDC BBVA'")->fetch_assoc()['total'];

    $cargosReal = (float)db()->query("
        SELECT COALESCE(SUM(monto),0) AS total
        FROM tarjeta_bbva_movimientos
        WHERE tipo IN ('cargo','ajuste')
    ")->fetch_assoc()['total'];

    $pagosReal = (float)db()->query("
        SELECT COALESCE(SUM(monto),0) AS total
        FROM tarjeta_bbva_movimientos
        WHERE tipo='pago'
    ")->fetch_assoc()['total'];

    return [
        'cycle_start' => $cycle['cycle_start'],
        'cycle_end_cut' => $cycle['cycle_end_cut'],
        'due_date' => $cycle['due_date'],
        'libres_ciclo' => $bbvaLibresCiclo,
        'fijos' => $bbvaFijos,
        'proximo_pago_estimado' => $bbvaLibresCiclo + $bbvaFijos,
        'saldo_real' => $cargosReal - $pagosReal,
    ];
}

function get_chart_data(string $start, string $end, array $cfg): array {
    $stmt = db()->prepare("SELECT categoria, SUM(monto) AS total FROM gastos_libres WHERE fecha BETWEEN ? AND ? GROUP BY categoria ORDER BY total DESC");
    $stmt->bind_param("ss", $start, $end);
    $stmt->execute();
    $catRows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $stmt = db()->prepare("SELECT metodo, SUM(monto) AS total FROM gastos_libres WHERE fecha BETWEEN ? AND ? GROUP BY metodo ORDER BY total DESC");
    $stmt->bind_param("ss", $start, $end);
    $stmt->execute();
    $metRows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $gastosBase = get_gastos_dashboard($start, $end, 0, $cfg);
    $months = [];
    $incomeSeries = [];
    $spendSeries = [];

    for ($i = 5; $i >= 0; $i--) {
        $dt = (new DateTime('first day of this month'))->modify("-{$i} month");
        $ym = $dt->format('Y-m');
        $monthStart = month_start($ym);
        $monthEnd = month_end($ym);

        $ingreso = get_ingreso_total($monthStart, $monthEnd, $cfg);

        $stmt = db()->prepare("SELECT COALESCE(SUM(monto),0) AS total FROM gastos_libres WHERE fecha BETWEEN ? AND ?");
        $stmt->bind_param("ss", $monthStart, $monthEnd);
        $stmt->execute();
        $libresMes = (float)$stmt->get_result()->fetch_assoc()['total'];

        $months[] = $dt->format('M Y');
        $incomeSeries[] = $ingreso['total'];
        $spendSeries[] = $gastosBase['fijos'] + $gastosBase['deudas_pago'] + $libresMes;
    }

    return [
        'categoria' => [
            'labels' => array_map(static fn(array $row): string => $row['categoria'], $catRows),
            'data' => array_map(static fn(array $row): float => (float)$row['total'], $catRows),
        ],
        'metodo' => [
            'labels' => array_map(static fn(array $row): string => $row['metodo'], $metRows),
            'data' => array_map(static fn(array $row): float => (float)$row['total'], $metRows),
        ],
        'serie' => [
            'labels' => $months,
            'income' => $incomeSeries,
            'spend' => $spendSeries,
        ],
    ];
}

function get_dashboard_data(?string $ym = null): array {
    $ym = $ym ?: current_ym();
    $start = month_start($ym);
    $end = month_end($ym);
    $cfg = get_configuracion();
    $ingreso = get_ingreso_total($start, $end, $cfg);
    $gastos = get_gastos_dashboard($start, $end, $ingreso['total'], $cfg);
    $bbva = get_bbva_dashboard($cfg);

    return [
        'periodo' => [
            'ym' => $ym,
            'start' => $start,
            'end' => $end,
        ],
        'resumen' => [
            'ingreso' => $ingreso['total'],
            'fijos' => $gastos['fijos'],
            'deudas_pago' => $gastos['deudas_pago'],
            'libres' => $gastos['libres'],
            'tope' => $gastos['tope'],
            'tope_pct' => $gastos['tope_pct'],
            'libre_disponible' => $gastos['libre_disponible'],
        ],
        'bbva' => $bbva,
        'charts' => get_chart_data($start, $end, $cfg),
    ];
}

if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    header('Content-Type: application/json; charset=utf-8');

    try {
        echo json_encode([
            'ok' => true,
            'data' => get_dashboard_data($_GET['ym'] ?? null),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'ok' => false,
            'message' => 'No se pudo cargar el dashboard.',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}

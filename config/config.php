<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'finanzas_bao');


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function db() : mysqli {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $conn->set_charset('utf8mb4');
    }
    return $conn;
}
function h($str) { return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8'); }
function money($n) { return number_format((float)$n, 2); }
function current_ym(): string { return date('Y-m'); }
function month_start(?string $ym=null): string { if(!$ym) $ym=current_ym(); return $ym.'-01'; }
function month_end(?string $ym=null): string { if(!$ym) $ym=current_ym(); $dt=DateTime::createFromFormat('Y-m-d',$ym.'-01'); return $dt->format('Y-m-t'); }

function bbva_cycle(int $corte_dia, int $limite_dia, ?DateTime $today=null): array {
    $today = $today ?: new DateTime('today');
    $y = (int)$today->format('Y');
    $m = (int)$today->format('m');
    $d = (int)$today->format('d');

    // Próximo corte
    $cut = new DateTime(sprintf('%04d-%02d-01', $y, $m));
    $cut->setDate((int)$cut->format('Y'), (int)$cut->format('m'), min($corte_dia, (int)$cut->format('t')));

    if ($d > $corte_dia) {
        $cut = (new DateTime(sprintf('%04d-%02d-01', $y, $m)))->modify('+1 month');
        $cut->setDate((int)$cut->format('Y'), (int)$cut->format('m'), min($corte_dia, (int)$cut->format('t')));
    }

    $prevCut = (clone $cut)->modify('-1 month');
    $prevCut->setDate((int)$prevCut->format('Y'), (int)$prevCut->format('m'), min($corte_dia, (int)$prevCut->format('t')));
    $cycleStart = (clone $prevCut)->modify('+1 day');

    $due = new DateTime($cut->format('Y-m-01'));
    $due->setDate((int)$due->format('Y'), (int)$due->format('m'), min($limite_dia, (int)$due->format('t')));

    return [
        'cycle_start' => $cycleStart->format('Y-m-d'),
        'cycle_end_cut' => $cut->format('Y-m-d'),
        'due_date' => $due->format('Y-m-d'),
    ];
}
?>
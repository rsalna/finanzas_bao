<?php
require_once __DIR__ . '/../config.php';

$msg='';
$cfg = db()->query("SELECT * FROM configuracion WHERE id=1")->fetch_assoc();
$tope = (float)($cfg['tope_libre'] ?? 0);
$bbvaCorte = (int)($cfg['bbva_corte_dia'] ?? 6);
$bbvaLimite = (int)($cfg['bbva_limite_dia'] ?? 26);

$sueldoQuincenal = (float)($cfg['sueldo_quincenal'] ?? 4791.22);
$quincenasMes    = (int)($cfg['quincenas_mes'] ?? 2);
$autoIngreso     = (int)($cfg['ingreso_automatico'] ?? 1);


if($_SERVER['REQUEST_METHOD']==='POST'){
  $tope = (float)($_POST['tope_libre'] ?? 0);
  $bbvaCorte = max(1, min(31, (int)($_POST['bbva_corte_dia'] ?? 6)));
  $bbvaLimite = max(1, min(31, (int)($_POST['bbva_limite_dia'] ?? 26)));
  
  $sueldoQuincenal = (float)($_POST['sueldo_quincenal'] ?? 0);
  $quincenasMes    = max(1, min(4, (int)($_POST['quincenas_mes'] ?? 2)));
  $autoIngreso     = isset($_POST['ingreso_automatico']) ? 1 : 0;

$stmt=db()->prepare("UPDATE configuracion 
  SET tope_libre=?, bbva_corte_dia=?, bbva_limite_dia=?, sueldo_quincenal=?, quincenas_mes=?, ingreso_automatico=? 
  WHERE id=1");
$stmt->bind_param("diidii", $tope, $bbvaCorte, $bbvaLimite, $sueldoQuincenal, $quincenasMes, $autoIngreso);
  $stmt->execute();
  $msg="✅ Configuración actualizada.";
}

$pageTitle="Tope & BBVA";
include __DIR__ . '/../includes/header.php';
$cycle = bbva_cycle($bbvaCorte, $bbvaLimite);
?>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card card-kpi p-3">
            <h3 class="mb-1">Configuración</h3>
            <div class="text-muted mb-3">Tope de gastos libres y fechas de tu tarjeta BBVA.</div>

            <?php if($msg): ?><div class="alert alert-success"><?= h($msg) ?></div><?php endif; ?>

            <form method="post" class="vstack gap-3">
                <div>
                    <label class="form-label fw-semibold">Tope mensual de gastos libres</label>
                    <input class="form-control" name="tope_libre" type="number" step="0.01" min="0"
                        value="<?= h($tope) ?>">
                    <div class="text-muted small mt-1">Si pones 0, el sistema no aplica límite.</div>
                </div>

                <hr class="my-1">

                <div>
                    <label class="form-label fw-semibold">Tarjeta BBVA</label>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Día de corte</label>
                            <input class="form-control" name="bbva_corte_dia" type="number" min="1" max="31"
                                value="<?= h($bbvaCorte) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha límite de pago</label>
                            <input class="form-control" name="bbva_limite_dia" type="number" min="1" max="31"
                                value="<?= h($bbvaLimite) ?>">
                        </div>
                    </div>
                    <div class="text-muted small mt-2">Ejemplo: corte 6, límite 26.</div>
                </div>
                <hr class="my-1">

                <div>
                    <label class="form-label fw-semibold">Nómina automática</label>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Sueldo quincenal</label>
                            <input class="form-control" name="sueldo_quincenal" type="number" step="0.01" min="0"
                                value="<?= h($sueldoQuincenal) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Quincenas por mes</label>
                            <input class="form-control" name="quincenas_mes" type="number" min="1" max="4"
                                value="<?= h($quincenasMes) ?>">
                        </div>
                    </div>

                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="ingreso_automatico"
                            id="ingreso_automatico" <?= $autoIngreso ? 'checked' : '' ?>>
                        <label class="form-check-label" for="ingreso_automatico">
                            Activar ingreso automático
                        </label>
                    </div>

                    <div class="text-muted small mt-2">
                        Si está activo, el ingreso del mes se calcula como: sueldo_quincenal × quincenas_mes.
                        Los “sobres” se suman aparte.
                    </div>
                </div>
                <button class="btn btn-dark">Guardar</button>
            </form>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-kpi p-3">
            <h6 class="mb-2">Ciclo calculado (referencia)</h6>
            <div class="text-muted small">Inicio de ciclo</div>
            <div class="fw-semibold"><?= h($cycle['cycle_start']) ?></div>
            <div class="text-muted small mt-2">Próximo corte</div>
            <div class="fw-semibold"><?= h($cycle['cycle_end_cut']) ?></div>
            <div class="text-muted small mt-2">Fecha límite de pago</div>
            <div class="fw-semibold"><?= h($cycle['due_date']) ?></div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
<?php
require_once __DIR__ . '/../config.php';

$ym = $_GET['ym'] ?? current_ym();
$start = month_start($ym);
$end   = month_end($ym);

$cfg = db()->query("SELECT * FROM configuracion WHERE id=1")->fetch_assoc() ?? [];
$sueldo = (float)($cfg['sueldo_quincenal'] ?? 0);
$quincenas = (int)($cfg['quincenas_mes'] ?? 2);
$auto = (int)($cfg['ingreso_automatico'] ?? 1);

$nominaMes = ($auto === 1) ? ($sueldo * $quincenas) : 0;

if($_SERVER['REQUEST_METHOD']==='POST'){
  $monto = (float)($_POST['monto'] ?? 0);
  $nota  = trim($_POST['nota'] ?? '');
  $fecha = $_POST['fecha'] ?? date('Y-m-d');

  if($monto > 0){
    $stmt = db()->prepare("INSERT INTO ingresos_extras(monto,nota,fecha) VALUES (?,?,?)");
    $stmt->bind_param("dss", $monto, $nota, $fecha);
    $stmt->execute();
  }
  header("Location: ingresos.php?ym=".urlencode($ym)); exit;
}

if(isset($_GET['del'])){
  $id = (int)$_GET['del'];
  db()->query("DELETE FROM ingresos_extras WHERE id={$id}");
  header("Location: ingresos.php?ym=".urlencode($ym)); exit;
}

$stmt = db()->prepare("SELECT * FROM ingresos_extras WHERE fecha BETWEEN ? AND ? ORDER BY fecha DESC, id DESC");
$stmt->bind_param("ss", $start, $end);
$stmt->execute();
$extras = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt = db()->prepare("SELECT COALESCE(SUM(monto),0) AS total FROM ingresos_extras WHERE fecha BETWEEN ? AND ?");
$stmt->bind_param("ss", $start, $end);
$stmt->execute();
$extrasTotal = (float)$stmt->get_result()->fetch_assoc()['total'];

$totalMes = $nominaMes + $extrasTotal;

$pageTitle="Ingresos";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
  <div>
    <h3 class="mb-0">Ingresos</h3>
    <div class="text-muted">Nómina automática + extras (sobres).</div>
  </div>
  <form class="d-flex gap-2" method="get">
    <input type="month" class="form-control" name="ym" value="<?= h($ym) ?>">
    <button class="btn btn-dark">Ver</button>
  </form>
</div>

<div class="row g-3">
  <div class="col-lg-5">
    <div class="card card-kpi p-3">
      <div class="kpi-label">Total del mes</div>
      <div class="kpi-value">$<?= money($totalMes) ?></div>

      <hr>

      <div class="d-flex justify-content-between">
        <div class="text-muted small">Nómina automática</div>
        <div class="fw-semibold">$<?= money($nominaMes) ?></div>
      </div>
      <div class="d-flex justify-content-between">
        <div class="text-muted small">Extras (sobres)</div>
        <div class="fw-semibold">$<?= money($extrasTotal) ?></div>
      </div>

      <div class="mt-2">
        <a class="btn btn-outline-dark btn-sm" href="/finanzas_bao/modules/configuracion.php">
          Editar sueldo quincenal
        </a>
      </div>

      <hr>

      <h6 class="mb-2">Agregar extra (sobre)</h6>
      <form method="post" class="vstack gap-2">
        <input class="form-control" name="monto" type="number" step="0.01" min="0" placeholder="Monto del sobre" required>
        <input class="form-control" name="nota" placeholder="Nota (opcional)">
        <input class="form-control" name="fecha" type="date" value="<?= h(date('Y-m-d')) ?>">
        <button class="btn btn-dark">Guardar extra</button>
      </form>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="card card-kpi p-0 overflow-hidden">
      <div class="table-responsive">
        <table class="table mb-0 table-striped align-middle">
          <thead class="table-light"><tr>
            <th>Fecha</th><th>Nota</th><th class="text-end">Monto</th><th class="text-end">Acciones</th>
          </tr></thead>
          <tbody>
          <?php if(!$extras): ?>
            <tr><td colspan="4" class="text-center text-muted py-4">Sin extras en este mes.</td></tr>
          <?php else: foreach($extras as $x): ?>
            <tr>
              <td><?= h($x['fecha']) ?></td>
              <td><?= h($x['nota']) ?></td>
              <td class="text-end">$<?= money($x['monto']) ?></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-danger" href="?ym=<?= h($ym) ?>&del=<?= (int)$x['id'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
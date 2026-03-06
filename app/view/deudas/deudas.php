<?php
require_once __DIR__ . '/../config.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
  $nombre=trim($_POST['nombre']??'');
  $total=(float)($_POST['total_deuda']??0);
  $pago=(float)($_POST['pago_mensual']??0);
  $saldo=(float)($_POST['saldo_actual']??$total);
  $activo=isset($_POST['activo'])?1:0;

  if($nombre!=='' && $total>0 && $pago>0){
    $stmt=db()->prepare("INSERT INTO deudas(nombre,total_deuda,pago_mensual,saldo_actual,activo) VALUES(?,?,?,?,?)");
    $stmt->bind_param("sdddi",$nombre,$total,$pago,$saldo,$activo);
    $stmt->execute();
  }
  header("Location: deudas.php"); exit;
}

if(isset($_GET['toggle'])){
  $id=(int)$_GET['toggle'];
  db()->query("UPDATE deudas SET activo=IF(activo=1,0,1) WHERE id={$id}");
  header("Location: deudas.php"); exit;
}
if(isset($_GET['pagar'])){
  $id=(int)$_GET['pagar'];
  db()->query("UPDATE deudas SET saldo_actual=GREATEST(0, saldo_actual - pago_mensual) WHERE id={$id}");
  header("Location: deudas.php"); exit;
}
if(isset($_GET['del'])){
  $id=(int)$_GET['del'];
  db()->query("DELETE FROM deudas WHERE id={$id}");
  header("Location: deudas.php"); exit;
}

$rows=db()->query("SELECT * FROM deudas ORDER BY activo DESC, nombre ASC")->fetch_all(MYSQLI_ASSOC);

$pageTitle="Deudas";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div><h3 class="mb-0">Deudas</h3><div class="text-muted">Total, pago mensual y saldo.</div></div>
</div>

<div class="row g-3">
  <div class="col-lg-5">
    <div class="card card-kpi p-3">
      <h6 class="mb-3">Agregar deuda</h6>
      <form method="post" class="vstack gap-2">
        <input class="form-control" name="nombre" placeholder="Nombre (ej. Suburbia)" required>
        <input class="form-control" name="total_deuda" type="number" step="0.01" min="0" placeholder="Total deuda" required>
        <input class="form-control" name="pago_mensual" type="number" step="0.01" min="0" placeholder="Pago mensual" required>
        <input class="form-control" name="saldo_actual" type="number" step="0.01" min="0" placeholder="Saldo actual (opcional)">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="activo" id="activo" checked>
          <label class="form-check-label" for="activo">Activa</label>
        </div>
        <button class="btn btn-dark">Guardar</button>
      </form>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="card card-kpi p-0 overflow-hidden">
      <div class="table-responsive">
        <table class="table mb-0 table-striped align-middle">
          <thead class="table-light"><tr>
            <th>Nombre</th><th class="text-end">Total</th><th class="text-end">Pago mensual</th><th class="text-end">Saldo</th><th>Estado</th><th class="text-end">Acciones</th>
          </tr></thead>
          <tbody>
          <?php if(!$rows): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">Sin registros.</td></tr>
          <?php else: foreach($rows as $r):
            $pct=($r['total_deuda']>0)?max(0,min(100,(1-($r['saldo_actual']/$r['total_deuda']))*100)):0;
          ?>
            <tr>
              <td><?= h($r['nombre']) ?>
                <div class="small text-muted">
                  <div class="progress mt-1" style="height:6px;"><div class="progress-bar" style="width: <?= $pct ?>%"></div></div>
                  <span><?= (int)$pct ?>% pagado</span>
                </div>
              </td>
              <td class="text-end">$<?= money($r['total_deuda']) ?></td>
              <td class="text-end">$<?= money($r['pago_mensual']) ?></td>
              <td class="text-end">$<?= money($r['saldo_actual']) ?></td>
              <td><?= ((int)$r['activo']===1)?'<span class="badge text-bg-success">Activa</span>':'<span class="badge text-bg-secondary">Inactiva</span>' ?></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="?pagar=<?= (int)$r['id'] ?>">Aplicar pago</a>
                <a class="btn btn-sm btn-outline-primary" href="?toggle=<?= (int)$r['id'] ?>">Activar/Desactivar</a>
                <a class="btn btn-sm btn-outline-danger" href="?del=<?= (int)$r['id'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="text-muted small mt-2">“Aplicar pago” descuenta el pago mensual del saldo.</div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

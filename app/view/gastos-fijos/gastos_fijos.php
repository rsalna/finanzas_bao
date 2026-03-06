<?php
require_once __DIR__ . '/../config.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
  $nombre=trim($_POST['nombre']??'');
  $monto=(float)($_POST['monto']??0);
  $metodo=trim($_POST['metodo']??'');
  $activo=isset($_POST['activo'])?1:0;

  if($nombre!=='' && $monto>0 && $metodo!==''){
    $stmt=db()->prepare("INSERT INTO gastos_fijos(nombre,monto,metodo,activo) VALUES(?,?,?,?)");
    $stmt->bind_param("sdsi",$nombre,$monto,$metodo,$activo);
    $stmt->execute();
  }
  header("Location: gastos_fijos.php"); exit;
}

if(isset($_GET['toggle'])){
  $id=(int)$_GET['toggle'];
  db()->query("UPDATE gastos_fijos SET activo=IF(activo=1,0,1) WHERE id={$id}");
  header("Location: gastos_fijos.php"); exit;
}
if(isset($_GET['del'])){
  $id=(int)$_GET['del'];
  db()->query("DELETE FROM gastos_fijos WHERE id={$id}");
  header("Location: gastos_fijos.php"); exit;
}

$rows=db()->query("SELECT * FROM gastos_fijos ORDER BY activo DESC, nombre ASC")->fetch_all(MYSQLI_ASSOC);

$pageTitle="Gastos fijos";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div><h3 class="mb-0">Gastos fijos</h3><div class="text-muted">Suscripciones y servicios mensuales.</div></div>
</div>

<div class="row g-3">
  <div class="col-lg-5">
    <div class="card card-kpi p-3">
      <h6 class="mb-3">Agregar gasto fijo</h6>
      <form method="post" class="vstack gap-2">
        <input class="form-control" name="nombre" placeholder="Nombre (ej. Netflix)" required>
        <input class="form-control" name="monto" type="number" step="0.01" min="0" placeholder="Monto mensual" required>
        <select class="form-select" name="metodo" required>
          <option value="">Método de pago</option>
          <option>TDC BBVA</option><option>TDC Nu</option><option>Débito BBVA</option><option>Efectivo</option><option>Transferencia</option>
        </select>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="activo" id="activo" checked>
          <label class="form-check-label" for="activo">Activo</label>
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
            <th>Nombre</th><th>Método</th><th class="text-end">Monto</th><th>Estado</th><th class="text-end">Acciones</th>
          </tr></thead>
          <tbody>
          <?php if(!$rows): ?>
            <tr><td colspan="5" class="text-center text-muted py-4">Sin registros.</td></tr>
          <?php else: foreach($rows as $r): ?>
            <tr>
              <td><?= h($r['nombre']) ?></td>
              <td><?= h($r['metodo']) ?></td>
              <td class="text-end">$<?= money($r['monto']) ?></td>
              <td><?= ((int)$r['activo']===1)?'<span class="badge text-bg-success">Activo</span>':'<span class="badge text-bg-secondary">Inactivo</span>' ?></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="?toggle=<?= (int)$r['id'] ?>">Activar/Desactivar</a>
                <a class="btn btn-sm btn-outline-danger" href="?del=<?= (int)$r['id'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
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

<?php
require_once __DIR__ . '/../config.php';

$ym = $_GET['ym'] ?? current_ym();
$start = month_start($ym);
$end   = month_end($ym);

$cfg = db()->query("SELECT * FROM configuracion WHERE id=1")->fetch_assoc();
$tope = (float)($cfg['tope_libre'] ?? 0);

$stmt=db()->prepare("SELECT COALESCE(SUM(monto),0) AS total FROM gastos_libres WHERE fecha BETWEEN ? AND ?");
$stmt->bind_param("ss",$start,$end); $stmt->execute();
$totalMes=(float)$stmt->get_result()->fetch_assoc()['total'];

$err='';

if($_SERVER['REQUEST_METHOD']==='POST'){
  $desc=trim($_POST['descripcion']??'');
  $cat=trim($_POST['categoria']??'General');
  $monto=(float)($_POST['monto']??0);
  $metodo=trim($_POST['metodo']??'');
  $fecha=$_POST['fecha']??date('Y-m-d');

  if($desc==='' || $monto<=0 || $metodo===''){
    $err="Completa descripción, monto y método.";
  } else {
    $fechaYM=substr($fecha,0,7);
    if($tope>0 && $fechaYM===$ym && ($totalMes+$monto)>$tope){
      $err="⚠️ Ese gasto excede tu TOPE mensual. Ajusta el tope o baja el monto.";
    } else {
      $stmt=db()->prepare("INSERT INTO gastos_libres(descripcion,categoria,monto,metodo,fecha) VALUES(?,?,?,?,?)");
      $stmt->bind_param("ssdss",$desc,$cat,$monto,$metodo,$fecha);
      $stmt->execute();
      if($metodo === 'TDC BBVA'){
          $stmt2 = db()->prepare("INSERT INTO tarjeta_bbva_movimientos (tipo,monto,descripcion,fecha) VALUES ('cargo',?,?,?)");
          $stmt2->bind_param("dss", $monto, $desc, $fecha);
          $stmt2->execute();
      }
      header("Location: gastos_libres.php?ym=".urlencode($ym)); exit;
    }
  }
}

if(isset($_GET['del'])){
  $id=(int)$_GET['del'];
  db()->query("DELETE FROM gastos_libres WHERE id={$id}");
  header("Location: gastos_libres.php?ym=".urlencode($ym)); exit;
}

$stmt=db()->prepare("SELECT * FROM gastos_libres WHERE fecha BETWEEN ? AND ? ORDER BY fecha DESC, id DESC");
$stmt->bind_param("ss",$start,$end); $stmt->execute();
$rows=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$topePct=($tope>0)?max(0,min(100,($totalMes/$tope)*100)):0;

$pageTitle="Gastos libres";
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div>
        <h3 class="mb-0">Gastos libres</h3>
        <div class="text-muted">Registros variables del mes (con tope).</div>
    </div>
    <form class="d-flex gap-2" method="get">
        <input type="month" class="form-control" name="ym" value="<?= h($ym) ?>">
        <button class="btn btn-dark">Ver</button>
    </form>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card card-kpi p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label">Total del mes</div>
                    <div class="kpi-value">$<?= money($totalMes) ?></div>
                </div>
                <span class="badge badge-soft">Tope: $<?= money($tope) ?></span>
            </div>
            <?php if($tope>0): ?>
            <div class="mt-2">
                <div class="progress">
                    <div class="progress-bar <?= ($topePct>=100?'bg-danger':($topePct>=80?'bg-warning':'')) ?>"
                        style="width: <?= $topePct ?>%"></div>
                </div>
                <div class="small text-muted mt-1"><?= (int)$topePct ?>% usado</div>
            </div>
            <?php endif; ?>

            <hr>
            <h6 class="mb-2">Agregar gasto libre</h6>
            <?php if($err): ?><div class="alert alert-warning"><?= h($err) ?></div><?php endif; ?>

            <form method="post" class="vstack gap-2">
                <input class="form-control" name="descripcion" placeholder="Descripción (ej. comida, uber)" required>
                <input class="form-control" name="categoria" placeholder="Categoría (ej. Comida, Transporte)"
                    value="General">
                <input class="form-control" name="monto" type="number" step="0.01" min="0" placeholder="Monto" required>
                <select class="form-select" name="metodo" required>
                    <option value="">Método de pago</option>
                    <option>TDC BBVA</option>
                    <option>TDC Nu</option>
                    <option>Débito BBVA</option>
                    <option>Efectivo</option>
                    <option>Transferencia</option>
                </select>
                <input class="form-control" name="fecha" type="date" value="<?= h(date('Y-m-d')) ?>">
                <button class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card card-kpi p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table mb-0 table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Método</th>
                            <th class="text-end">Monto</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$rows): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Sin registros en este mes.</td>
                        </tr>
                        <?php else: foreach($rows as $r): ?>
                        <tr>
                            <td><?= h($r['fecha']) ?></td>
                            <td><?= h($r['descripcion']) ?></td>
                            <td><?= h($r['categoria']) ?></td>
                            <td><?= h($r['metodo']) ?></td>
                            <td class="text-end">$<?= money($r['monto']) ?></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-danger"
                                    href="?ym=<?= h($ym) ?>&del=<?= (int)$r['id'] ?>"
                                    onclick="return confirm('¿Eliminar?')">Eliminar</a>
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
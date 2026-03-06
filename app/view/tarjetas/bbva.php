<?php
require_once __DIR__ . '/../config.php';

$cycle = bbva_cycle(
    (int)(db()->query("SELECT bbva_corte_dia FROM configuracion WHERE id=1")->fetch_assoc()['bbva_corte_dia']),
    (int)(db()->query("SELECT bbva_limite_dia FROM configuracion WHERE id=1")->fetch_assoc()['bbva_limite_dia'])
);

if($_SERVER['REQUEST_METHOD']==='POST'){
    $monto = (float)$_POST['monto'];
    $fecha = $_POST['fecha'];
    $desc  = trim($_POST['descripcion']);

    if($monto > 0){
        $stmt = db()->prepare("INSERT INTO tarjeta_bbva_movimientos (tipo,monto,descripcion,fecha) VALUES ('pago',?,?,?)");
        $stmt->bind_param("dss",$monto,$desc,$fecha);
        $stmt->execute();
    }
}

$cargos = db()->query("SELECT COALESCE(SUM(monto),0) AS total FROM tarjeta_bbva_movimientos WHERE tipo IN ('cargo','ajuste')")->fetch_assoc()['total'];
$pagos  = db()->query("SELECT COALESCE(SUM(monto),0) AS total FROM tarjeta_bbva_movimientos WHERE tipo='pago'")->fetch_assoc()['total'];

$saldoActual = $cargos - $pagos;

$movs = db()->query("SELECT * FROM tarjeta_bbva_movimientos ORDER BY fecha DESC, id DESC")->fetch_all(MYSQLI_ASSOC);

$pageTitle="Estado BBVA";
include __DIR__ . '/../includes/header.php';
?>

<div class="card p-4 mb-4">
    <h3>Estado real BBVA</h3>
    <div class="fs-4 fw-bold <?= ($saldoActual>0?'text-danger':'text-success') ?>">
        Saldo actual: $<?= money($saldoActual) ?>
    </div>
</div>

<div class="card p-4 mb-4">
    <h5>Registrar pago</h5>
    <form method="post" class="row g-2">
        <div class="col-md-4">
            <input class="form-control" type="number" step="0.01" name="monto" placeholder="Monto pago" required>
        </div>
        <div class="col-md-4">
            <input class="form-control" type="date" name="fecha" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-4">
            <input class="form-control" name="descripcion" placeholder="Descripción">
        </div>
        <div class="col-12 mt-2">
            <button class="btn btn-dark">Registrar pago</button>
        </div>
    </form>
</div>

<div class="card p-4">
    <h5>Historial</h5>
    <table class="table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th class="text-end">Monto</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($movs as $m): ?>
            <tr>
                <td><?= h($m['fecha']) ?></td>
                <td><?= h($m['tipo']) ?></td>
                <td><?= h($m['descripcion']) ?></td>
                <td class="text-end">$<?= money($m['monto']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
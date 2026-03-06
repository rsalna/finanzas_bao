<?php if (!isset($pageTitle)) $pageTitle = "Finanzas"; ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($pageTitle) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/finanzas_bao/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="/finanzas_bao/index.php">Ahorros 💳</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/finanzas_bao/index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="/finanzas_bao/modules/gastos_fijos.php">Gastos fijos</a></li>
        <li class="nav-item"><a class="nav-link" href="/finanzas_bao/modules/deudas.php">Deudas</a></li>
        <li class="nav-item"><a class="nav-link" href="/finanzas_bao/modules/gastos_libres.php">Gastos libres</a></li>
        <li class="nav-item"><a class="nav-link" href="/finanzas_bao/modules/ingresos.php">Ingresos</a></li>
        <li class="nav-item"><a class="nav-link" href="/finanzas_bao/modules/configuracion.php">Tope & BBVA</a></li>
      </ul>
    </div>
  </div>
</nav>
<main class="container my-4">

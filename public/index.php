<?php

require_once __DIR__ . "/../app/controllers/AuthController.php";
require_once __DIR__ . "/../app/controllers/DashboardController.php";
require_once __DIR__ . "/../app/controllers/GastosFijosController.php";

$url = trim($_GET['url'] ?? 'auth', '/');

$parts = explode("/", $url);

$controller = $parts[0] ?? "auth";
$action     = $parts[1] ?? "index";

switch ($controller) {

    case "auth":
        $c = new AuthController();

        if ($action == "loginUser") {
            $c->login();
            exit;
        }

        if ($action == "register") {
            $c->register();
            exit;
        }

        require __DIR__ . "/../app/view/auth/index.php";
        break;

    case "dashboard":
        require __DIR__ . "/../app/view/dashboard/index.php";
        break;

    case "gastos-fijos":
        require __DIR__ . "/../app/view/gastos-fijos/index.php";
        break;

    default:
        require __DIR__ . "/../app/view/404.php";
}

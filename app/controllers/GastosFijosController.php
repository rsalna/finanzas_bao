<?php
// session_start();
require_once __DIR__ . "/../models/GastoFijo.php";

class GastosFijosController
{

    public function guardarDatos()
    {
        $nombre  = trim($_POST['nombre'] ?? '');
        $monto   = (float) ($_POST['monto'] ?? 0);
        $metodo  = trim($_POST['metodo'] ?? '');
        $activo  = isset($_POST['activo']) ? 1 : 0;
        $idClien = $_SESSION['clid'];
        if (GastoFijo::crearGasto($nombre, $monto, $metodo, $activo, $idClien)) {
            echo json_encode(["status" => "success", "redirect" => "/finanzas_bao/gastos-fijos"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    }

    public function mostrarDatos()
    {
        $idCli  = $_SESSION['clid'];
        $result = GastoFijo::traerGastos($idCli);
        $gastos = [];
        while ($row = $result->fetch_assoc()) {
            $gastos[] = $row;
        }
        echo json_encode($gastos);
    }

    public function actiDescGasto()
    {
        $id = (int) $_POST['toggle'];
        $idCli = $_SESSION['clid'];
        if (GastoFijo::actuaGast($id, $idCli)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    }
    public function elimiGastoF()
    {
        $idCli = $_SESSION['clid'];
        $id    = (int) $_GET['del'];
        if (GastoFijo::elimiGast($id, $idCli)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    }

}

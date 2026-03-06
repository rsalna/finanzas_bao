<?php

require_once __DIR__ . "/../../core/Database.php";

class Tarjetas
{

    public static function insertPago($monto, $desc, $fecha)
    {

        $conn = Database::connect();

        $stmt = $conn->prepare(
            "INSERT INTO tarjeta_bbva_movimientos (tipo,monto,descripcion,fecha) VALUES ('pago',?,?,?)"
        );
        $stmt->bind_param("dss", $monto, $desc, $fecha);

        return $stmt->execute();
    }
}

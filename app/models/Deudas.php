<?php

require_once __DIR__ . "/../../core/Database.php";

class Deudas
{

    public static function crearDeuda($nombre, $total, $pago, $saldo, $activo)
    {

        $conn = Database::connect();

        $stmt = $conn->prepare(
            "INSERT INTO deudas(nombre,total_deuda,pago_mensual,saldo_actual,activo) VALUES(?,?,?,?,?)"
        );

        $stmt->bind_param("sdddi", $nombre, $total, $pago, $saldo, $activo);

        return $stmt->execute();
    }
}

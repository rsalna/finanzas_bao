<?php

require_once __DIR__ . "/../../core/Database.php";

class GastoFijo
{

    public static function gastosFijos($nombre,$monto,$metodo,$activo)
    {

        $conn = Database::connect();

        $stmt = $conn->prepare(
            "INSERT INTO gastos_fijos(nombre,monto,metodo,activo) VALUES(?,?,?,?)"
        );

        $stmt->bind_param("ssss", $nombre, $monto, $metodo, $activo);

        return $stmt->execute();
    }
}

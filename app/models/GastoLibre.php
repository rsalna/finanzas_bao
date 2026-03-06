<?php

require_once __DIR__ . "/../../core/Database.php";

class GastoLibre
{

    public static function crear($desc, $cat, $monto, $metodo, $fecha)
    {

        $conn = Database::connect();

        $conn->begin_transaction();

        try {

            $stmt = $conn->prepare(
                "INSERT INTO gastos_libres
                (descripcion,categoria,monto,metodo,fecha)
                VALUES (?,?,?,?,?)"
            );

            $stmt->bind_param("ssdss", $desc, $cat, $monto, $metodo, $fecha);

            $stmt->execute();

            if ($metodo === 'TDC BBVA') {

                $stmt2 = $conn->prepare(
                    "INSERT INTO tarjeta_bbva_movimientos
                    (tipo,monto,descripcion,fecha)
                    VALUES ('cargo',?,?,?)"
                );

                $stmt2->bind_param("dss", $monto, $desc, $fecha);
                $stmt2->execute();
            }

            $conn->commit();

            return true;
        } catch (Exception $e) {

            $conn->rollback();

            return false;
        }
    }
}

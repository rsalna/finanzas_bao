<?php

require_once __DIR__ . "/../../core/Database.php";

class TopeTarjeta
{

    public static function crearTope($tope, $bbvaCorte, $bbvaLimite, $sueldoQuincenal, $quincenasMes, $autoIngreso, $userid)
    {

        $conn = Database::connect();

        $stmt = $conn->prepare(
            "UPDATE configuracion 
            SET tope_libre=?, bbva_corte_dia=?, bbva_limite_dia=?, sueldo_quincenal=?, quincenas_mes=?, ingreso_automatico=? 
            WHERE id=?"
        );

        $stmt->bind_param("diidiii", $tope, $bbvaCorte, $bbvaLimite, $sueldoQuincenal, $quincenasMes, $autoIngreso, $userid);

        return $stmt->execute();
    }
}

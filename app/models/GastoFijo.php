    <?php

        require_once __DIR__ . "/../../core/Database.php";

        class GastoFijo
        {

            public static function crearGasto($nombre, $monto, $metodo, $activo, $idClien)
            {
                $conn = Database::connect();
                $stmt = $conn->prepare("INSERT INTO gastos_fijos(nombre,monto,metodo,activo,idClieGF) VALUES(?,?,?,?,?)");
                $stmt->bind_param("sdsii", $nombre, $monto, $metodo, $activo, $idClien);
                return $stmt->execute();
            }
            public static function traerGastos($id)
            {
                $con  = Database::connect();
                $stmt = $con->prepare("SELECT * FROM gastos_fijos WHERE idClieGF =?  ORDER BY activo DESC, nombre ASC");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                return $stmt->get_result();
            }
            public static function actuaGast($id, $idCli)
            {
                $con = Database::connect();
                $stmt = $con->prepare("UPDATE gastos_fijos  SET activo = IF(activo=1,0,1) WHERE id=? AND idClie=?");
                $stmt->bind_param("ii", $id, $idCli);
                return $stmt->execute();
            }
            public static function elimiGast($id, $idCli)
            {
                $con = Database::connect();
                $stmt = $con->prepare("DELETE FROM gastos_fijos WHERE id=? AND idClie=?");
                $stmt->bind_param("ii", $id, $idCli);
                return $stmt->execute();
            }
    }

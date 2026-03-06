<?php

class Database {

    private static $host = "localhost";
    private static $user = "root";
    private static $pass = "";
    private static $db   = "finanzas_bao";

    public static function connect(){

        $conn = new mysqli(
            self::$host,
            self::$user,
            self::$pass,
            self::$db
        );

        if($conn->connect_error){
            die("Error de conexión: " . $conn->connect_error);
        }

        return $conn;
    }

}
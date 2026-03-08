<?php

require_once __DIR__ . "/../../core/Database.php";

class User
{

    public static function register($username, $password, $correo)
    {

        $conn = Database::connect();

        $stmt = $conn->prepare(
            "INSERT INTO users(username,password,correo) VALUES(?,?,?)"
        );

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bind_param("sss", $username, $hash, $correo);

        return $stmt->execute();
    }

    public static function login($username, $password)
    {
    
        $conn = Database::connect();

        $stmt = $conn->prepare(
            "SELECT * FROM users WHERE correo = ?"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['pass'])) {
                // echo 'si concuerda la contraseña '.$passwords.' - '.$user['pass'];
                return $user;
                // }else{
                // echo 'no concuerda la contraseña '.$passwords.' - '.$user['pass'];
            }
        }

        return false;
    }
}

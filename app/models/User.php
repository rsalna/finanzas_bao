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

    public static function login($username, $passwords)
    {

        $conn = Database::connect();

        $stmt = $conn->prepare(
            "SELECT * FROM users WHERE username = ?"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($passwords, $user['password'])) {
                return $user;
            }
        }

        return false;
    }
}

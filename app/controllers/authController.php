<?php
require_once __DIR__ . "/../models/User.php";
session_start();
class AuthController
{
    public function showLogin()
    {
        require_once __DIR__ . "/../view/auth/login.php";
    }

    public function register()
    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $correo   = $_POST["correo"];

        if (User::register($username, $password, $correo)) {
            header("Location: login");
        } else {
            echo "Error";
        }
    }

    public function login()
    {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $user = User::login($username, $password);

        if ($user) {

            $_SESSION['clid'] = $user['id'];
            $_SESSION['user'] = $user['nombre'];
            // echo $user;
            echo json_encode([
                "status"   => "success",
                "redirect" => "/finanzas_bao/dashboard",
            ]);
        } else {
            echo json_encode([
                "status" => "error",
            ]);
        }
    }
}

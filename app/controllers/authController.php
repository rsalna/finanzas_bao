<?php
require_once "../models/User.php";

class AuthController
{

    public function register()
    {

        $username = $_POST["username"];
        $password = $_POST["password"];
        $correo   = $_POST["correo"];

        if (User::register($username, $password, $correo)) {
            header("Location: login.php");
        } else {
            echo "Error";
        }
    }
}

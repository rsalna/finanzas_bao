<?php

class DashboardController
{

    public function dashboard()
    {

        // session_start();

        if (!isset($_SESSION['user'])) {
            header("Location: /finanzas_bao/");
            exit;
        }

        require_once __DIR__ . "/../view/dashboard/index.php";
    }

}
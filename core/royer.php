<?php

class Router {

    public static function route(){

        $url = $_GET['url'] ?? 'dashboard';

        $url = explode("/", $url);

        $controller = ucfirst($url[0]) . "Controller";
        $method = $url[1] ?? "index";

        $controllerFile = "../app/controllers/" . $controller . ".php";

        if(file_exists($controllerFile)){

            require_once $controllerFile;

            $controller = new $controller();

            if(method_exists($controller, $method)){
                $controller->$method();
            } else {
                echo "Método no encontrado";
            }

        } else {

            echo "Controller no encontrado";

        }

    }

}
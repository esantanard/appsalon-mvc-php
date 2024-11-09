<?php

namespace Controller;

use Model\Servicio;
use MVC\Router;

class ServicioController{
    public static function index(Router $router){
        session_start();
        isAdmin();

        $servicios = Servicio::all();

        $router->render('/servicios/index', [
            'nombreyapellido' => $_SESSION['nombreyapellido'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router){
        session_start();
        isAdmin();

        $servicio = new Servicio;

        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
                header("Location: /servicios");
            }

        }

        $router->render('/servicios/crear', [
            'nombreyapellido' => $_SESSION['nombreyapellido'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router){
        session_start();
        isAdmin();
       
        if(!is_numeric($_GET['id'])) header("Location: /servicios");

        $servicio = Servicio::find($_GET['id']);

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();
            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('/servicios/actualizar', [
            'nombreyapellido' => $_SESSION['nombreyapellido'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header ("Location: /servicios");
        }
    }
}
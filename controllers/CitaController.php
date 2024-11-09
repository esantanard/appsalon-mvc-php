<?php

namespace Controller;
use MVC\Router;

class CitaController {
    public static function index( Router $router ) {
        session_start();
        isAuth();
        $router->render('cita/index', [
            'nombreyapellido' => $_SESSION['nombreyapellido'],
            'id' => $_SESSION['id']
        ]);
    }
}
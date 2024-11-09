<?php

namespace Controller;

use Model\AdminCita;
use MVC\Router;

class AdminController {
    public static function index(Router $router){
        session_start();
        isAdmin();
        
        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        $fechas = explode('-', $fecha);
        
        if(!checkdate($fechas[1], $fechas[2], $fechas[0])){
            header("Location: /404");
        }

        $consulta = "SELECT citas.id, citas.hora, CONCAT ( usuarios.nombre, ' ', usuarios.apellido ) AS cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre AS servicio, servicios.precio ";
        $consulta .= " FROM citas ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON usuarios.id=citas.usuarioId ";
        $consulta .= " LEFT OUTER JOIN citasservicios ";
        $consulta .= " ON citasservicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasservicios.servicioId";
        $consulta .= " WHERE fecha = ' $fecha ' ";

        $citas = AdminCita::SqlPlano($consulta);
//debuguear($citas);

        $router->render('admin/index', [
            'nombreyapellido' => $_SESSION['nombreyapellido'],
            'citas' => $citas,
            'fecha'=> $fecha
        ]);
    }
}
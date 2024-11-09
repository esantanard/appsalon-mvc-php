<?php

namespace Controller;

use Model\Cita;
use Model\CitaServicios;
use Model\Servicio;

class APIController{
    public static function index(){

        $servicio = Servicio::all();
//Plugin : Jsonview
        echo json_encode($servicio);
    }

    public static function guardar(){
//Almacena la cita y devuelve el ID
        // $respuesta = [
        //     'datos' => $_POST
        // ];
        $cita = new Cita($_POST);
        // $respuesta = [
        //     'cita' => $cita
        // ];
        $resultado = $cita->guardar();
        
        $id = $resultado['id'];

        //Separar un string en JS split en PHP explode
        $idServicios = explode(",", $_POST['servicios']);

        //Almacena los servicios con el ID de la cita
        foreach($idServicios as $idServicio){
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaservicio = new CitaServicios($args);
            $citaservicio->guardar();
        }
        /*Almacena la cita  y los servicios*/
        // $respuesta = [
        //     'resultado' => $resultad;
        // ];
        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $id = $_POST['id'];
            $cita = Cita::find($id);
            
            $cita->eliminar();

            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}
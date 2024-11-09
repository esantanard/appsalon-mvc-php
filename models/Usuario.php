<?php

namespace Model;
use Model\ActiveRecord;

class Usuario extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'email' , 'apellido', 'nombre', 'telefono', 'admin', 'confirmado', 'token', 'password'];

    public $id;
    public $email;
    public $apellido;
    public $nombre;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;
    public $password;

    public function __construct($args=[]){
        $this->id =  $args['id'] ?? null;
        $this->email =  $args['email'] ?? '';
        $this->apellido =  $args['apellido'] ?? '';
        $this->nombre =  $args['nombre'] ?? '';
        $this->telefono =  $args['telefono'] ?? '';
        $this->admin =  $args['admin'] ?? 0;
        $this->confirmado =  $args['confirmado'] ?? 0;
        $this->token =  $args['token'] ?? '';
        $this->password =  $args['password'] ?? '';
    }

    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] = "Nombre es obligatorio";
        }

        if(!$this->apellido){
            self::$alertas['error'][] = "Apellido es obligatorio";
        }

        if(!$this->telefono){
            self::$alertas['error'][] = "Telefono es obligatorio";
        }

        if(!$this->email){
            self::$alertas['error'][] = "Email es obligatorio";
        }

        if(!$this->password){
            self::$alertas['error'][] = "Password es obligatorio";
        }

        if(strlen($this->password) < 6){
            self::$alertas['error'][] = "Password debe contener al menos 6 caracteres";
        }

        return self::$alertas;
    }

    public function existeUsuario(){
        //revisa si el usuario exite
        //Como estamos dentro del modelo se usa $this. si estuvieramos en el controlador fuera la instancia de $usuario
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        //debuguear($query);
        $resultado = self::$db->query($query);
        //debuguear($resultado);
        if($resultado->num_rows){
            self::$alertas['error'][] = "El usuario ya esta registrado";
        }

        return $resultado;
    }

    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);   
    }

    public function crearToken(){
        $this->token = uniqid();
    }

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = "El email es obligatorio";
        }
        if(!$this->password){
            self::$alertas['error'][] = "El password es obligatorio";
        }
        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = "El email es obligatorio";
        }
        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = "El password es obligatorio";
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = "El password debe ser mayor a 6 caracteres";
        }
        return self::$alertas;
    }

    public function comprobarPassAndConfirm($password){
        $resultado = password_verify($password, $this->password);
        //debuguear($this);

        if(!$resultado || !$this->confirmado){
            self::setAlerta('error', "Password incorrecto o correo no confirmado");
        }else{
            return true;
        }
    }


}
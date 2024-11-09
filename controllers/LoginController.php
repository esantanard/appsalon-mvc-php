<?php 

namespace Controller;

use Model\Usuario;
use MVC\Router;
use Classes\Email;

class LoginController{
    public static function login(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();
            
            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);
                if($usuario){
                    if($usuario->comprobarPassAndConfirm($auth->password)){
                        // Autenticar el usuario
                        session_start();
                        
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombreyapellido'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header("Location: /admin");
                            
                        }else{
                            header("Location: /cita");
                        }
                    }
                }else{
                    Usuario::setAlerta("error", "Usuario no encontrado");
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('/auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        session_start();
        $_SESSION = [];
        header("Location: /");
    }

    public static function olvide(Router $router){
        
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            //Verificar que el email exita
            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1"){
                    $usuario->crearToken();
                    $usuario->guardar();

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->apellido,  $usuario->token);
                    $email->enviarInstrucciones();

                    Usuario::setAlerta('exito', 'Revisa tu email');
                }else{
                    Usuario::setAlerta("error", "El usuario no exite o no esta confirmado");      
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('/auth/olvide', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){
        $error = false;
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', "Token no valido");
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD']==="POST"){
            $pass = new Usuario($_POST);
            $alertas = $pass->validarPassword();

            if(empty($alertas)){
                $usuario->password = null;
                $usuario->password = $pass->password;
                $usuario->hashPassword();
                $usuario->token = null;
           
                $resultado = $usuario->guardar();

                if($resultado){
                    header("Location: /");
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('/auth/recuperar-password', [
            'alertas'=>$alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
        
        $usuario = new Usuario($_POST);
        $alertas = [];

        if($_SERVER['REQUEST_METHOD']==="POST"){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)){
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    //Hashear el password
                    $usuario->hashPassword();

                    //Generar un token
                    $usuario->crearToken();

                    //Enviar email verificacion
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->apellido, $usuario->token);
                    $email->enviarConfirmacion();

                    //Crear el usuario
                    $resultado = $usuario->guardar();

                    if($resultado){
                        header("Location: /mensaje");
                    }

                   // debuguear($usuario);
                }
            }
        }

        $router->render('/auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas'=>$alertas
        ]);
    }
    public static function mensaje(Router $router){
        $router->render('/auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas=[];
        ///sanitizar por si alguien coloca un comaando en la url
        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);
        
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
        }else{
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta("exito", "Cuenta comprobada correctamente");
        }

        //Obtener alertas
        $alertas = Usuario::getAlertas();

        $router->render('/auth/confirmar-cuenta', [
            'alertas'=>$alertas
        ]);
    }

}


<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $nombre;
    public $apellido;
    public $token;

    public function __construct($email, $nombre, $apellido, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->token = $token;

    }

    public function enviarConfirmacion(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '141ab9e6e9becd';
        $mail->Password = 'b73f0b7687dabf';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('joe@example.net', 'Appsalon.com');
        $mail->Subject = 'Confirma tu cuenta';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>" . $this->nombre . " " . $this->apellido . "</strong></p>";
        $contenido .= "<p>Confirma tu cuenta en el siguiente enlace: </p>"; 
        $contenido .= "<a href='". $_ENV['APP_URL'] . "/confirmar-cuenta?token=". $this->token ."'>" .  $_ENV['APP_URL'] . "/confirmar-cuenta?token=". $this->token . "</a>"; 
        $contenido .= "<p>Si no solicitaste esta cuenta puedes ignorar el mensaje </p>"; 
        $contenido .= "</html>";

        $mail->Body = $contenido;

        $mail->send();
    }

    public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host =  $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('joe@example.net', 'Appsalon.com');
        $mail->Subject = 'Reestablece tu password';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>" . $this->nombre . " " . $this->apellido . "</strong></p>";
        $contenido .= "<p>Has solicitado reestablecer tu password </p>"; 
        $contenido .= "<p>Haz click en el siguiente enlace: </p>"; 
        $contenido .= "<a href='". $_ENV['APP_URL'] . "/recuperar?token=". $this->token ."'>" .  $_ENV['APP_URL'] . "/recuperar?token=". $this->token . "</a>"; 
        $contenido .= "<p>Si no solicitaste este cambio puedes ignorar el mensaje </p>"; 
        $contenido .= "</html>";

        $mail->Body = $contenido;

        $mail->send();
    }
}
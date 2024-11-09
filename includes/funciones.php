<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//Revisa ultimo registro
function esUltimo($actual, $proximo) : bool {
    if($actual !== $proximo){
        return true;
    }
    return false;
}


//Funcion que revisa si usuario esta autenticado
function isAuth() : void{
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

function isAdmin() : void{
    if(!isset($_SESSION['admin'])){
        $_SESSION = [];
        header('Location: /');
    }
}
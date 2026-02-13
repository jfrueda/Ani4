<?php

function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}

function esEmailValido($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("El correo electrónico no es válido.");
    }
    
    /*
    $dominio = substr(strrchr($email, "@"), 1);
    
    if (!checkdnsrr($dominio, "MX") && trim($dominio) != 'supersalud.gov.co') {
        throw new Exception("El dominio del correo electrónico ".$dominio." no tiene un registro MX válido.");
    }
    */

    return true;
}

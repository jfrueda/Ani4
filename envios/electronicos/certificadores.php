<?php

$certificadores = [
    'generico' => function($mailer, $email) {
        $mailer->AddAddress(strtolower($email));
        return $mailer;
    },
    'certicamara' => function($mailer, $email) {
        $mailer->AddAddress(strtolower($email) . '.rpost.biz');
        return $mailer;
    }
];

function aplicarCertificador($mailer, $email, $tipo = 'generico') {
    global $certificadores;
    if (!isset($certificadores[$tipo])) {
        throw new Exception("Certificador '$tipo' no soportado");
    }
    
    return $certificadores[$tipo]($mailer, $email);
}
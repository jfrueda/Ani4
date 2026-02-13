<?php
session_start();
$ruta_raiz = "..";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";

$db = new ConnectionHandler($ruta_raiz);
$numero_expediente = $_GET['expediente'];
$anexos_expediente = $db->conn->getRow("
    SELECT 
        COUNT(ID) AS total,
        SUM(exp_anex_tamano) AS peso 
    FROM sgd_exp_anexos
    WHERE 
        exp_anex_borrado = 'N' AND
        exp_anex_nomb_archivo LIKE ?
    ", $numero_expediente.'%');
    
$radicados = $db->conn->getRow("
    SELECT
        COUNT(DISTINCT(se.radi_nume_radi)) as total,
        ROUND(SUM(an.anex_tamano) * 1024) as peso
    FROM
        sgd_exp_expediente se
        JOIN radicado r on r.radi_nume_radi = se.radi_nume_radi
        LEFT JOIN anexos an ON an.anex_radi_nume = se.radi_nume_radi 
    WHERE
        se.sgd_exp_estado != 2 and 
        se.sgd_exp_numero = ? and 
        r.is_borrador = ?
    ", [$numero_expediente, 'f']);

$borradores = $db->conn->getRow("
    SELECT
        COUNT(DISTINCT(se.radi_nume_radi)) as total,
        ROUND(SUM(an.anex_tamano) * 1024) as peso
    FROM
        sgd_exp_expediente se
        JOIN radicado r on r.radi_nume_radi = se.radi_nume_radi
        LEFT JOIN anexos an ON an.anex_radi_nume = se.radi_nume_radi 
    WHERE
        se.sgd_exp_estado != 2 and
        se.sgd_exp_numero = ? and 
        r.is_borrador = ?
    ", [$numero_expediente, 't']);

$data = [
    'anexos_expediente' => intval($anexos_expediente['TOTAL']),
    'anexos_expediente_peso' => intval($anexos_expediente['PESO']) ?? 0,
    'radicados_expediente' => intval($radicados['TOTAL']),
    'radicados_expediente_peso' => intval($radicados['PESO']) ?? 0,
    'borradores_expediente' => intval($borradores['TOTAL']),
    'borradores_expediente_peso' => intval($borradores['PESO']) ?? 0
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);
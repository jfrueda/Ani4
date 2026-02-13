<?php
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$ruta_raiz = "..";
if (!$_SESSION['dependencia']) {
    header("Location: $ruta_raiz/cerrar_session.php");
}

require "$ruta_raiz/vendor/autoload.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once "$ruta_raiz/expediente/expediente.class.php";
include_once "$ruta_raiz/processConfig.php";
include_once "$ruta_raiz/expediente/exp-indice-util.php";
use setasign\Fpdi\Fpdi;

$exp = $_GET['exp'];
$db = new ConnectionHandler($ruta_raiz);
$expClass = new expediente($ruta_raiz);
$ln = $digitosDependencia;

$indice = $db->conn->getOne('SELECT indice_electronico FROM sgd_sexp_secexpedientes WHERE sgd_exp_numero = ?', [$exp]);

$keys = [
    'ID' => 'Id',
    'NOMBRE_DOCUMENTO' => 'Nombre_Documento',
    'TIPOLOGIA_DOCUMENTAL' => 'Tipologia_Documental',
    'FECHA_CREACION_DOCUMENTO' => 'Fecha_Creacion_Documento',
    'FECHA_INCORPORACION_EXPEDIENTE' => 'Fecha_Incorporacion_Expediente',
    'VALOR_HUELLA' => 'Valor_Huella',
    'FUNCION_RESUMEN' => 'Funcion_Resumen',
    'ORDEN_DOCUMENTO_EXPEDIENTE' => 'Orden_Documento_Expediente',
    'PAGINA_INICIO' => 'Pagina_Inicio',
    'PAGINA_FIN' => 'Pagina_Fin',
    'FORMATO' => 'Formato',
    'TAMANO' => 'Tamano',
    'ORIGEN' => 'Origen',
];

$radicados = $db->conn->getAll('SELECT radi_nume_radi FROM sgd_exp_expediente WHERE sgd_exp_numero = ?', [$exp]);
$documentoData = [];

$anexos_expediente = $db->conn->getAll(
    "SELECT
        sea.id as Id,
        sea.exp_anex_desc as Nombre_Documento,
        stt.sgd_tpr_descrip as Tipologia_Documental,
        sea.exp_anex_radi_fech as Fecha_Creacion_Documento,
        sea.exp_anex_radi_fech as Fecha_Incorporacion_Expediente,
        sea.exp_anex_hash as Valor_Huella,
        'MD5' as Funcion_Resumen,
        sea.exp_consecutivo as Orden_Documento_Expediente,
        'a' as Pagina_Inicio,
        'b' as Pagina_Fin,
        'c' as Formato,
        'd' as Tamano,
        'Digitalizado' as Origen,
        '' as CODIGO,
        sea.exp_anex_nomb_archivo as ARCHIVO
    FROM sgd_exp_anexos sea
        LEFT JOIN sgd_tpr_tpdcumento stt ON sea.exp_tpdoc = stt.sgd_tpr_codigo
    WHERE sea.exp_numero = ?"
    , [$exp]
);

foreach ($anexos_expediente as $anexo) {
    $documentoData[] = array_merge($anexo, ['TIPO' => ANEXO_EXPEDIENTE]);
}

foreach($radicados as $radicado) {
    $imagen = $db->conn->getRow(
        "SELECT
            r.id as Id,
            r.radi_nume_radi as Nombre_Documento,
            stt.sgd_tpr_descrip as Tipologia_Documental,
            r.radi_fech_radi as Fecha_Creacion_Documento,
            see.sgd_exp_fech as Fecha_Incorporacion_Expediente,
            a.anex_hash as Valor_Huella,
            'MD5' as Funcion_Resumen,
            '' as Orden_Documento_Expediente,
            'a' as Pagina_Inicio,
            'b' as Pagina_Fin,
            'c' as Formato,
            'd' as Tamano,
            'Digitalizado' as Origen,
            '' as CODIGO,
            r.radi_path as ARCHIVO
        FROM radicado r
            LEFT JOIN anexos a ON regexp_replace(r.radi_path, '^.*/', '') = a.anex_nomb_archivo
            LEFT JOIN sgd_tpr_tpdcumento stt ON r.tdoc_codi = stt.sgd_tpr_codigo
            LEFT JOIN sgd_exp_expediente see ON see.radi_nume_radi = r.radi_nume_radi AND see.sgd_exp_numero = ?
        WHERE r.radi_nume_radi = ?
        AND (r.radi_path is not null and r.radi_path <> '')",
        [$exp, $radicado['RADI_NUME_RADI']]
    );

    if ($imagen) {
        $documentoData[] = array_merge($imagen, ['TIPO' => IMAGEN_RADICADO]);
    }

    $anexos = $db->conn->getAll(
        "SELECT 
            a.id as Id,
            a.anex_desc as Nombre_Documento,
            stt.sgd_tpr_descrip as Tipologia_Documental,
            a.anex_fech_anex as Fecha_Creacion_Documento,
            see.sgd_exp_fech as Fecha_Incorporacion_Expediente,
            a.anex_hash as Valor_Huella,
            'MD5' as Funcion_Resumen,
            a.anex_numero as Orden_Documento_Expediente,
            'a' as Pagina_Inicio,
            'b' as Pagina_Fin,
            'c' as Formato,
            'd' as Tamano,
            (CASE WHEN a.anex_solo_lect = 'N' THEN 'Digitalizado' ELSE 'Electrónico' END) as Origen,
            a.anex_codigo as CODIGO,
            a.anex_nomb_archivo as ARCHIVO
        FROM sgd_exp_expediente see 
            LEFT JOIN anexos a ON a.anex_radi_nume = see.radi_nume_radi
            LEFT JOIN radicado r ON a.anex_radi_nume = r.radi_nume_radi
            LEFT JOIN sgd_tpr_tpdcumento stt ON r.tdoc_codi = stt.sgd_tpr_codigo
        WHERE
            see.sgd_exp_numero = ? AND
            r.radi_nume_radi = ? AND
            a.anex_borrado = 'N'
        ORDER BY id, Fecha_Creacion_Documento
        ",
        [$exp, $radicado['RADI_NUME_RADI']]
    );

    foreach ($anexos as $anexo) {
        $documentoData[] = array_merge($anexo, ['TIPO' => ANEXO_RADICADO]);
    }
}

$contadores = [
    'pdf' => function($path) {
        $pdf = new Fpdi();
        $total_paginas = $pdf->setSourceFile($path);
        return $total_paginas;
    }
];

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<TipoDocumentoFoliado xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"></TipoDocumentoFoliado>');

$orden = 1;
$pagina_inicio = 1;

foreach ($documentoData as $data) {
    try {
        $documento = $xml->addChild('DocumentoIndizado');
        $temp = [];

        if($data['TIPO'] == ANEXO_RADICADO) {
            $path = __DIR__.'/../bodega/'.substr($data['CODIGO'], 0, 4).'/'.intval(substr($data['CODIGO'], 4, $ln)).'/docs/'.$data['ARCHIVO'];
        }

        if($data['TIPO'] == ANEXO_EXPEDIENTE) {
            $path = __DIR__.'/../bodega/'.substr($data['ARCHIVO'], 0, 4).'/'.intval(substr($data['ARCHIVO'], 4, $ln)).'/docs/'.$data['ARCHIVO'];
        }

        if($data['TIPO'] == IMAGEN_RADICADO) {
            $path = __DIR__.'/../bodega/'.$data['ARCHIVO'];
        }

        $file_info = pathinfo($path);
        $file_size = filesize($path) / (1024 * 1024);
        $extension = $file_info['extension'];
        
        $pagina_fin = ($pagina_inicio + $total_paginas);
        $data['FECHA_CREACION_DOCUMENTO'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', substr($data['FECHA_CREACION_DOCUMENTO'], 0, 19))->format('Y/m/d H:i');
        $data['FECHA_INCORPORACION_EXPEDIENTE'] = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', substr($data['FECHA_INCORPORACION_EXPEDIENTE'], 0, 19))->format('Y/m/d H:i');
        $data['PAGINA_INICIO'] = $pagina_inicio == 0 ? 1 : $pagina_inicio;
        $data['PAGINA_FIN'] = $pagina_fin;
        $data['ORDEN_DOCUMENTO_EXPEDIENTE'] = $orden;
        $data['FORMATO'] = strtoupper($extension);
        $data['TAMANO'] = round($file_size, 2).' MB';        
        if ($data['VALOR_HUELLA'] == '')
        {
            $data['VALOR_HUELLA'] = hash_file('sha256', $path);
        }

        foreach ($data as $key => $value) {
            if(array_key_exists($key, $keys))
            {
                $documento->addChild($keys[$key], $value);
            }
        }

        $orden += 1;
        $pagina_inicio = $pagina_fin + 1;
    } catch(\Exception $e) {
        echo $path.'<br>';
    }
}

$dom = dom_import_simplexml($xml)->ownerDocument;
$dom->formatOutput = true;

header('Content-Type: application/xml');
header('Content-Disposition: attachment; filename="'.$exp.'.xml"');
echo $dom->saveXML();
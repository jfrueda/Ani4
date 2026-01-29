<?php
session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

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
$usua_codi = $_SESSION['codusuario'];
$usua_doc = $_SESSION['usua_doc'];
$usua_depe = $_SESSION['dependencia'];

$indice = $db->conn->getOne('SELECT indice_electronico FROM sgd_sexp_secexpedientes WHERE sgd_exp_numero = ?', [$exp]);

if($indice && false) {
    header('Content-type: application/json');
    echo json_encode([
        'indice' => $indice
    ]);
    exit;
}

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

$orden = 1;
$pagina_inico = 1;
$documentos = [];

foreach ($documentoData as $data) {
    try {
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
        if(array_key_exists($extension, $contadores))
        {
            $total_paginas = $contadores[$extension]($path);
        } else {
            $total_paginas = 1;
        }

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
                if(!in_array($key, ['VALOR_HUELLA', 'NOMBRE_DOCUMENTO']))
                {
                    $temp[$key] = strlen($data[$key]) > 19 ? substr($data[$key], 0, 19).'...' : $data[$key];
                } else {
                    $temp[$key] = strlen($data[$key]) > 28 ? substr($data[$key], 0, 28).'...' : $data[$key];
                }
            }
        }
        $orden += 1;
        $pagina_inicio = $pagina_fin + 1;

        $documentos[] = $temp;
    } catch(\Exception $e) {
        echo $path.'<br>';
    }
}

$pdf = new FPDF('L', 'mm', 'A4');
$pdf->SetMargins(5, 5, 5, 5);
$pdf->SetAutoPageBreak(false);

function addPageWithData($pdf, $data, $paginas) {
    global $exp;
    $pdf->AddPage();

    $pdf->Image('./indice_electronico/header.png', 22, 10, 250, 17);
    $pdf->Image('./indice_electronico/separador.png', 5, 35, 287, 0.3);
    $pdf->Ln(40);

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetX(100);
    $pdf->Cell(0, 5, utf8_decode('INDICE ELECTRÓNICO EXPEDIENTE '.$exp), 0, 1, 'L');
    
    $pdf->SetX(100);
    $pdf->Cell(0, 5, utf8_decode('FECHA INDICE CONTENIDO '.date('d-m-Y H:i')), 0, 1, 'L');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 6);
    $header = array(
        'ID', 
        'Nombre_Documento', 
        'Tipologia_Documental', 
        'Fecha_Creacion_Docume', 
        'Fecha_Incorporacion_Exp', 
        'Valor_Huella', 
        'Funcion_Resumen', 
        'Orden_Documento_Exp', 
        'Pagina_Inicio', 
        'Pagina_Fin', 
        'Formato', 
        'Tamaño', 
        'Origen'
    );
    $columnWidths = array(
        15, 
        35, 
        25, 
        28, 
        28, 
        35, 
        20, 
        25, 
        15, 
        15, 
        15, 
        15, 
        15
    ); 
    foreach ($header as $i => $col) {
        $pdf->Cell($columnWidths[$i], 8, utf8_decode($col), 1, 0, 'C');
    }
    $pdf->Ln();
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->SetFont('Arial', '', 6);
    
    foreach ($data as $row) {
        $i = 0;
        foreach ($row as $col) {
            $pdf->Cell($columnWidths[$i], 6, utf8_decode($col), 1);
            $i++;
        }
        $pdf->Ln();
    }

    // Agregar espacio para la firma
    $pdf->Ln(10);

    // Pie de página
    $pdf->SetFont('Arial', '', 10);
    $position = 37;
    $pdf->Image('./indice_electronico/separador.png', 5, $pdf->GetPageHeight() - ($position +2), 287, 0.3);
    $pdf->Image('./indice_electronico/separador.png', $pdf->GetPageWidth()-44, $pdf->GetPageHeight() - ($position), 0.2, 25);
    $pdf->SetXY(5, $pdf->GetPageHeight() - $position);
    $pdf->Cell(0, 5, utf8_decode('Página ' . $pdf->PageNo() . ' de ' .$paginas), 0, 0, 'L');
    $pdf->SetXY(5, $pdf->GetPageHeight() - ($position - 7)); 
    $pdf->Cell(0, 5, utf8_decode('Carrera 68 A N.º 24 B - 10, Torre 3 - Pisos 4, 9 y 10 | PBX +57 601 744 2000 - Bogotá D.C.'), 0, 0, 'L');
    $pdf->SetXY(5, $pdf->GetPageHeight() - ($position - 14)); 
    $pdf->Cell(0, 5, utf8_decode('www.supersalud.gov.co'), 0, 0, 'L');
    $pdf->SetXY(5, $pdf->GetPageHeight() - ($position - 21)); 
    $pdf->Cell(0, 5, utf8_decode('DIFT17'), 0, 0, 'L');
    $pdf->Image('./indice_electronico/sgs_2.png',  $pdf->GetPageWidth()-40, $pdf->GetPageHeight()-40, 35);
}

// Dividir los datos en grupos de 9 registros por página
$recordsPerPage = 10;
$dataChunks = array_chunk($documentos, $recordsPerPage);

// Agregar páginas con datos
foreach ($dataChunks as $dataChunk) {
    addPageWithData($pdf, $dataChunk, count($dataChunks));
}

// Salida del PDF
$ruta = __DIR__.'/../bodega/indices_electronicos/';
$output_filename = $exp.'.pdf';
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$pdf->Output($ruta.$output_filename, 'F');

try {
    $file_to_sign = $ABSOL_PATH.'/bodega/indices_electronicos/'.$output_filename;
    $firmasd = $ABSOL_PATH.'/bodega/firmas/';
    $P12_FILE =  $firmasd . 'server.p12';
    $clave = $P12_PASS;
    $commandFirmado='java -jar '.$ABSOL_PATH.'/include/jsignpdf/JSignPdf.jar '.$file_to_sign.' -kst PKCS12 -ksf '.$P12_FILE.' -ksp '.$clave.' --font-size 7 -r \'Firmado en SuperArgo\' -V -llx 0 -lly 0 -urx 550 -ury 27 -ta PASSWORD -ts ' . $tsUrlTimeStamp . ' -tsu ' . $tsuUserTimeStamp . ' -tsp ' . $tspPasswordTimeStamp . ' 2>&1';
    $out = null;
    $ret = null;
    $inf = exec($commandFirmado,$out,$ret);
    if ($ret != 0) {
        $out = implode(PHP_EOL, $out);
        error_log(date(DATE_ATOM)." ".basename(__FILE__)." ($ret) $radicado_p > $nurad: $out Clave de firma digital erronea sin estampa intento 2\n",3,"$ABSOL_PATH/bodega/jsignpdf.log");
    }elseif ($inf=="INFO  Finished: Creating of signature failed."){
        error_log(date(DATE_ATOM)." ".basename(__FILE__)." Finished: Creating of signature failed. intento 2 sin estampa\n",3,"$ABSOL_PATH/bodega/jsignpdf.log");
    }

    rename($exp.'_signed.pdf',$ABSOL_PATH.'/bodega/indices_electronicos/'.$exp.'.pdf');
    $indice_e_filename = $exp.'.pdf';
} catch (\Exception $e) {
}
$update = $db->conn->Execute("UPDATE sgd_sexp_secexpedientes SET indice_electronico = '".$indice_e_filename."' WHERE sgd_exp_numero = ?", [$exp]);
$ttr_codigo = $db->conn->getOne("SELECT sgd_ttr_codigo FROM sgd_ttr_transaccion WHERE sgd_ttr_descrip = 'Generación y firma del indice electrónico'");
$expClass->insertarHistoricoExp($exp, '0', $usua_depe, $usua_codi, 'Indice electrónico generado', $ttr_codigo, '0');
header('Content-type: application/json');
echo json_encode([
    'indice' => $indice_e_filename,
    'output' => $output_filename
]);
exit;
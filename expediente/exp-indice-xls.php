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
use setasign\Fpdi\Fpdi;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$exp = $_GET['exp'];
$db = new ConnectionHandler($ruta_raiz);
$expClass = new expediente($ruta_raiz);
$ln = $digitosDependencia;

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

$documentos = $db->conn->getAll(
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
        a.anex_borrado = 'N'
    ORDER BY id, Fecha_Creacion_Documento
    ",
    [$exp]
);

function convertidor($path) {
    $zip = new \PhpOffice\PhpWord\Shared\ZipArchive();
    $zip->open($path);
    $xml = new \DOMDocument();
    $xml->loadXML($zip->getFromName("docProps/app.xml"));
    echo 'paginas: '.$xml->getElementsByTagName('Pages')->item(0)->nodeValue;
    return $filename;
}

$contadores = [
    'pdf' => function($path) {
        $pdf = new Fpdi();
        $total_paginas = $pdf->setSourceFile($path);
        return $total_paginas;
    }
];

$final_data = [];
foreach ($documentos as $documentoData) {
    $path = __DIR__.'/../bodega/'.substr($documentoData['CODIGO'], 0, 4).'/'.intval(substr($documentoData['CODIGO'], 4, $ln)).'/docs/'.$documentoData['ARCHIVO'];
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
    $documentoData['PAGINA_INICIO'] = $pagina_inicio == 0 ? 1 : $pagina_inicio;
    $documentoData['PAGINA_FIN'] = $pagina_fin;
    $documentoData['FORMATO'] = strtoupper($extension);
    $documentoData['TAMANO'] = round($file_size, 2).' MB';
    $pagina_inicio = $pagina_fin + 1;
    $final_data[] = $documentoData;
}


// Crear una instancia de Spreadsheet
$spreadsheet = new Spreadsheet();

// Agregar una hoja de cálculo
$sheet = $spreadsheet->getActiveSheet();

// Encabezados de columna
$columnHeaders = array_keys($final_data[0]);
$columnIndex = 1;
foreach ($columnHeaders as $header) {
    $sheet->setCellValueByColumnAndRow($columnIndex++, 1, $header);
}

// Llenar los datos
$rowIndex = 2;
foreach ($final_data as $row) {
    $columnIndex = 1;
    foreach ($row as $value) {
        $sheet->setCellValueByColumnAndRow($columnIndex++, $rowIndex, $value);
    }
    $rowIndex++;
}

// Crear un objeto Writer
$writer = new Xlsx($spreadsheet);

// Establecer las cabeceras HTTP para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$exp.'.xlsx"');
header('Cache-Control: max-age=0');

// Guardar el archivo en la salida (stream) en lugar de un archivo en disco
$writer->save('php://output');

// Finalizar el script
exit;
<?php

session_start();

$ruta_raiz = "../..";
require_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once "$ruta_raiz/processConfig.php";
require_once "$ruta_raiz/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$db = new ConnectionHandler($ruta_raiz);

// Obtener parámetros de paginación y filtro desde POST
$dependencia = isset($_GET['dependencia']) ? intval($_GET['dependencia']) : 99999;
$adscritas = isset($_GET['adscritas']) ? intval($_GET['adscritas']) : 0;
$serie = isset($_GET['serie']) ? intval($_GET['serie']) : 0;
$subserie = isset($_GET['subserie']) ? intval($_GET['subserie']) : 0;
$fecha_inicial = isset($_GET['fecha_inicial']) ? $_GET['fecha_inicial'] : '';
$fecha_final = isset($_GET['fecha_final']) ? $_GET['fecha_final'] : '';

// Construir filtro de dependencia
$where = 'WHERE 1 = 1';
$params = [];
if ($dependencia != 99999) {
    if ($adscritas == 1)
    {
        $where .= ' AND (d.depe_codi = ? OR d.depe_codi_territorial = ?)';
        $params[] = $dependencia;
        $params[] = $dependencia;
    } else {
        $where .= ' AND d.depe_codi = ?';
        $params[] = $dependencia;
    }
}

if ($serie != 0) {
    if ($subserie != 0) {
        $where .= ' AND (sss.sgd_srd_codigo = ? AND ssd.id = ?)';
        $params[] = $serie;
        $params[] = $subserie;
    } else {
        $where .= ' AND sss.sgd_srd_codigo = ?';
        $params[] = $serie;
    }
}

if ($fecha_inicial != '') {
    if ($fecha_final != '') {
        $where .= ' AND scc.sgd_sexp_fech BETWEEN ? AND ?';
        $params[] = $fecha_inicial.' 00:00:00';
        $params[] = $fecha_final. ' 23:59:59';
    } else {
        $where .= ' AND scc.sgd_sexp_fech BETWEEN ? AND ?';
        $params[] = $fecha_inicial.' 00:00:00';
        $params[] = date('Y-m-d'). '23:59:59';
    }
}

// Consulta principal sin paginación (para Excel exportar todo)
$query = "
    SELECT
        scc.sgd_sexp_ano AS \"Año\",
        dp.depe_codi AS \"Código Dependencia Antecesora de la creadora\",
        dp.depe_nomb AS \"Nombre Dependencia Antecesora de la creadora\",
        d.depe_codi AS \"Código Dependencia creadora\",
        d.depe_nomb AS \"Dependencia creadora\",
        sss.sgd_srd_codigo AS \"Código Serie Documental\",
        sss.sgd_srd_descrip AS \"Nombre Serie Documental\",
        ssd.sgd_sbrd_codigo AS \"Código Subserie Documental\",
        ssd.sgd_sbrd_descrip AS \"Nombre Subserie Documental\",
        scc.sgd_exp_numero AS \"Número de expediente\",
        scc.sgd_sexp_fech AS \"Fecha de creacion\",
        scc.sgd_sexp_parexp1 AS \"Nombre del expediente\",
        r.usua_nomb AS \"Responsable del expediente\",
        c.usua_nomb AS \"Creador del expediente\",
        CASE
            WHEN scc.sgd_sexp_estado = 2 THEN
            'Anulado' 
            WHEN scc.sgd_sexp_estado = 1 THEN
            'Cerrado' ELSE 'Abierto' 
        END AS \"Estado\",
        CASE
            WHEN scc.sgd_sexp_estado = 1 THEN
            ( SELECT MAX ( shh.sgd_hfld_fech ) FROM sgd_hfld_histflujodoc shh WHERE scc.sgd_exp_numero = shh.sgd_exp_numero AND shh.sgd_ttr_codigo = 58 ) ELSE NULL 
        END AS \"Fecha de cierre\",
        scc.sgd_sexp_parexp1,
        scc.sgd_sexp_parexp2,
        scc.sgd_sexp_parexp3,
        scc.sgd_sexp_parexp4,
        scc.sgd_sexp_parexp5,
        scc.sgd_sexp_parexp6,
        scc.sgd_sexp_parexp7,
        scc.sgd_sexp_parexp8,
        scc.sgd_sexp_parexp9,
        scc.sgd_sexp_parexp10
    FROM
        sgd_sexp_secexpedientes scc
        LEFT JOIN dependencia d ON scc.depe_codi = d.depe_codi
        LEFT JOIN dependencia dp ON d.depe_codi_padre = dp.depe_codi
        LEFT JOIN sgd_srd_seriesrd sss ON scc.sgd_srd_codigo = sss.sgd_srd_codigo
        LEFT JOIN sgd_sbrd_subserierd ssd ON (scc.sgd_srd_codigo = ssd.sgd_srd_codigo and scc.sgd_sbrd_codigo = ssd.sgd_sbrd_codigo)
        LEFT JOIN usuario r ON scc.usua_doc_responsable = r.usua_doc
        LEFT JOIN usuario C ON scc.usua_doc = C.usua_doc
        $where
    ORDER BY scc.sgd_sexp_fech ASC
";

$rows = $db->conn->GetAll($query, $params);

// Crear el archivo Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Escribir encabezados
if (!empty($rows)) {
    $headers = array_keys($rows[0]);
    $col = 1;
    foreach ($headers as $header) {
        $sheet->setCellValueByColumnAndRow($col, 1, ucfirst(strtolower($header)));
        $col++;
    }

    // Escribir datos
    $rowNum = 2;
    foreach ($rows as $row) {
        $col = 1;
        foreach ($row as $value) {
            $sheet->setCellValueByColumnAndRow($col, $rowNum, mb_strtoupper($value ?? '', 'UTF-8'));
            $col++;
        }
        $rowNum++;
    }
}

// Encabezados para descarga Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="expedientes_export.xlsx"');
header('Cache-Control: max-age=0');

// Guardar el archivo Excel en la salida
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
<?php

session_start();

$ruta_raiz = "../..";
require_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once "$ruta_raiz/processConfig.php";
require_once "$ruta_raiz/vendor/autoload.php";

$db = new ConnectionHandler($ruta_raiz);

// Obtener parámetros de paginación y filtro desde POST
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$dependencia = isset($_POST['dependencia']) ? intval($_POST['dependencia']) : 99999;
$adscritas = isset($_POST['adscritas']) ? intval($_POST['adscritas']) : 0;
$serie = isset($_POST['serie']) ? intval($_POST['serie']) : 0;
$subserie = isset($_POST['subserie']) ? intval($_POST['subserie']) : 0;
$fecha_inicial = isset($_POST['fecha_inicial']) ? $_POST['fecha_inicial'] : '';
$fecha_final = isset($_POST['fecha_final']) ? $_POST['fecha_final'] : '';

$orderColumnIndex = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
$orderDir = isset($_POST['order'][0]['dir']) && in_array(strtolower($_POST['order'][0]['dir']), ['asc', 'desc']) ? strtoupper($_POST['order'][0]['dir']) : 'ASC';

$columns = [
    0 => 'scc.sgd_sexp_ano',
    1 => 'dp.depe_codi',
    2 => 'dp.depe_nomb',
    3 => 'd.depe_codi',
    4 => 'd.depe_nomb',
    5 => 'sss.sgd_srd_codigo',
    6 => 'sss.sgd_srd_descrip',
    7 => 'ssd.sgd_sbrd_codigo',
    8 => 'ssd.sgd_sbrd_descrip',
    9 => 'scc.sgd_exp_numero',
    10 => 'scc.sgd_sexp_fech',
    11 => 'scc.sgd_sexp_parexp1',
    12 => 'r.usua_nomb',
    13 => 'c.usua_nomb',
    14 => 'scc.sgd_sexp_estado',
    15 => '',
];

$orderBy = 'scc.sgd_sexp_ano DESC';
if (isset($columns[$orderColumnIndex]) && $columns[$orderColumnIndex] !== '') {
    $orderBy = $columns[$orderColumnIndex] . ' ' . $orderDir;
}

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

// Contar total de registros filtrados
$countQuery = "
    SELECT COUNT(*) AS total
    FROM sgd_sexp_secexpedientes scc
    JOIN dependencia d ON scc.depe_codi = d.depe_codi
    JOIN dependencia dp ON d.depe_codi_padre = dp.depe_codi
    JOIN sgd_srd_seriesrd sss ON scc.sgd_srd_codigo = sss.sgd_srd_codigo
    JOIN sgd_sbrd_subserierd ssd ON (scc.sgd_srd_codigo = ssd.sgd_srd_codigo and scc.sgd_sbrd_codigo = ssd.sgd_sbrd_codigo)
    JOIN usuario r ON scc.usua_doc_responsable = r.usua_doc
    JOIN usuario C ON scc.usua_doc = C.usua_doc
    $where
";
$totalFiltered = $db->conn->GetOne($countQuery, $params);

// Consulta principal con paginación y filtro
$query = "
    SELECT
        scc.sgd_sexp_ano AS \"Anio\",
        dp.depe_codi AS \"Codigo Dependencia Antecesora de la creadora\",
        dp.depe_nomb AS \"Nombre Dependencia Antecesora de la creadora\",
        d.depe_codi AS \"Codigo Dependencia creadora\",
        d.depe_nomb AS \"Dependencia creadora\",
        sss.sgd_srd_codigo AS \"Codigo Serie Documental\",
        sss.sgd_srd_descrip AS \"Nombre Serie Documental\",
        ssd.sgd_sbrd_codigo AS \"Codigo Subserie Documental\",
        ssd.sgd_sbrd_descrip AS \"Nombre Subserie Documental\",
        scc.sgd_exp_numero AS \"Numero de expediente\",
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
    ORDER BY $orderBy
    OFFSET ? LIMIT ?
";
$params[] = $start;
$params[] = $length;

// Obtener los datos paginados
$rows = $db->conn->GetAll($query, $params);

// Devolver datos en formato compatible con DataTables
echo json_encode([
    "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 1,
    "recordsTotal" => intval($totalFiltered),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $rows
]);
exit;
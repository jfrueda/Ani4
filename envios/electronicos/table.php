<?php

session_start();
$ruta_raiz = __DIR__.'/../../';

include_once "$ruta_raiz/processConfig.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once "$ruta_raiz/envios/electronicos/query_envios.php";

if ($_SESSION['envios_general'] == 1)
{
    $dependencias_por_defecto = explode(',', $dependencias_envio_general);
} else if ($_SESSION['envios_dependencia'] == 1) {
    $dependencias_por_defecto = [$_SESSION['dependencia']];
} else {
    die('No tiene permisos para acceder a esta página');
}

$db = new ConnectionHandler("$ruta_raiz");
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;

$orderColumnIndex = $_POST['order'][0]['column'] ?? 2;
$orderDirection = $_POST['order'][0]['dir'] ?? 'DESC';

$columns = [
    0 => 'RADICADO_SALIDA',
    1 => 'RADICADO_PADRE',
    2 => 'FECHA_RADICADO',
];

$orderColumn = $columns[$orderColumnIndex] ?? null;


$filters = [
    'radicados' => $_POST['radicados'] ?? '',
    'dependencia' => isset($_POST['dependencia']) ? implode(',', $_POST['dependencia']) : implode(',', $dependencias_por_defecto),
    'usuario' => isset($_POST['usuario']) ? implode(',', $_POST['usuario']) : '',
];

$queries = getEnviosQuery($filters, $length, $start, $orderColumn, $orderDirection);
$totalRecords = $db->conn->getOne($queries['count']);
$data = $db->conn->getAll($queries['data']);

header('Content-Type: application/json; charset=utf-8');

$response = [
    "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data" => $data
];

echo json_encode($response);
exit;
    
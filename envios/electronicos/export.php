<?php

session_start();
$ruta_raiz = __DIR__.'/../../';

include_once "$ruta_raiz/processConfig.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once "$ruta_raiz/envios/electronicos/query_envios.php";
include_once "$ruta_raiz/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

$db = new ConnectionHandler("$ruta_raiz");

$filters = [
    'radicados' => $_POST['radicados'] ?? '',
    'dependencia' => $_POST['dependencia'] ?? '',
    'usuario' => $_POST['usuario']
];

$queries = getEnviosQuery($filters);
$data = $db->conn->getAll($queries['data']);

// Crear nuevo Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$headers = [
    'ID', 'RADICADO SALIDA', 'RADICADO PADRE', 'FECHA RADICADO',
    'DESCRIPCIÓN', 'FECHA IMPRESIÓN', 'GENERADO POR',
    'CERTIFICADO', 'EMAILS'
];

$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

$row = 2;
foreach ($data as $record) {
    $col = 'A';
    $fields = array_slice($record, 0, count($headers));

    foreach ($fields as $index => $value) {
        // Posiciones 1 y 2 corresponden a RADICADO SALIDA y RADICADO PADRE
        if ($index === 'RADICADO_SALIDA' || $index === 'RADICADO_PADRE') {
            $sheet->setCellValueExplicit($col . $row, (string)$value, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $row, $value);
        }
        $col++;
    }

    $row++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_envios.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

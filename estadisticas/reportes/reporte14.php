<?php

session_start();

$ruta_raiz = "../..";
require_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once "$ruta_raiz/processConfig.php";
require_once "$ruta_raiz/vendor/autoload.php";

$formato = $_GET['formato'];

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$db = new ConnectionHandler($ruta_raiz);

$datos = $db->conn->getAll("
    SELECT
        u.depe_codi as Código,
        d.depe_nomb as Dependencia,
        u.usua_nomb as Usuario
    FROM
        autm_membresias am
        JOIN usuario u ON am.autu_id = u.id and u.depe_codi > 9999
        JOIN dependencia d ON u.depe_codi = d.depe_codi
    WHERE
        am.autg_id = 2
    ORDER BY u.depe_codi
");

if ($formato == 'xls')
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = ['Código', 'Dependencia', 'Usuario'];
    $sheet->fromArray($headers, NULL, 'A1');

    $sheet->fromArray($datos, NULL, 'A2');

    foreach (range('A', $sheet->getHighestColumn()) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $filename = "reporte_jefes_de_area.xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos);
}

exit;

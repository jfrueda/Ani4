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

$exp = $_GET['exp'];
$db = new ConnectionHandler($ruta_raiz);
$expClass = new expediente($ruta_raiz);
$ln = $digitosDependencia;

$indice = $db->conn->getOne('SELECT indice_electronico FROM sgd_sexp_secexpedientes WHERE sgd_exp_numero = ?', [$exp]);
$filename = $ABSOL_PATH.'/bodega/indices_electronicos/'.$indice;

//Check the file exists or not
if(file_exists($filename)) {

    //Define header information
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: 0");
    header('Content-Disposition: attachment; filename="'.basename($filename).'"');
    header('Content-Length: ' . filesize($filename));
    header('Pragma: public');

    //Clear system output buffer
    flush();

    //Read the size of the file
    readfile($filename);

    //Terminate from the script
    die();
} else {
    echo "No existe el archivo";
    die();
}
?>
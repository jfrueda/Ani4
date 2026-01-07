<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
set_time_limit(10000);
$ruta_raiz = "..";
require_once($ruta_raiz."/include/db/ConnectionHandler.php");
require_once($ruta_raiz."/processConfig.php");


$db = new ConnectionHandler($ruta_raiz);
$zipArchive = new ZipArchive();
$zipFile = "zipFile.zip";
$file_url = $ruta_raiz . "/bodega/tmp/" . $zipFile;


$depeRadica   = $_GET['depeRadica'];
$fechaInicio   = $_GET['fechaInicio'];
$fechaFinal   = $_GET['fechaFinal'];

$fechaInicio =  gmdate("Y-m-d\ H:i:s", $fechaInicio);
$fechaFinal =  gmdate("Y-m-d\ H:i:s", $fechaFinal);

$zipArchive = new ZipArchive;
$res = $zipArchive->open($file_url, ZipArchive::CREATE);
if ($res === TRUE) {
   
    
    $sql = "select radi_path from public.radicado where 
    radi_fech_radi >= '" . $fechaInicio . "' and
    radi_fech_radi <= '" . $fechaFinal . "' and
    radi_depe_radi = " . $depeRadica . " and
    radi_path is not NULL";
    $rs = $db->query($sql);   
    while($rs && !$rs->EOF){
        $file = $ruta_raiz . '/bodega/' . $rs->fields["RADI_PATH"];
        if (is_file($file)) {
            if ($file != '' && $file != '.' && $file != '..') {
                $zipArchive->addFile($file);
            }
        } 
        $rs->MoveNext();
    }    
    $zipArchive->close();
    
    //Define header information
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: 0");
    header('Content-Disposition: attachment; filename="'.basename($file_url).'"');
    header('Content-Length: ' . filesize($file_url));
    header('Pragma: public');
    
    //Clear system output buffer
    flush();
    
    //Read the size of the file
    readfile($file_url);
    
    if (file_exists($file_url)) {
        unlink($file_url);
    } 
    
    //Terminate from the script
    //die();

    echo 'ok';
} else {
    echo 'falló';
}


?>
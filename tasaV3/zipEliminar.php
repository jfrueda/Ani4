<?php

$ruta_raiz = "..";
$zipFile = "zipFile.zip";
$file_url = $ruta_raiz . "/bodega/tmp/" . $zipFile;
if (file_exists($file_url)) {
    unlink($file_url);
} 

?>
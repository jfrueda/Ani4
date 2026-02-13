<?php

session_start();
$ruta_raiz = ".";
include_once "$ruta_raiz/processConfig.php";

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler("$ruta_raiz");

$rad_salida = $_GET['rad_salida'] ?? '20259300100001031';
$direcciones_orginales = $db->conn->getAll("SELECT * FROM sgd_dir_drecciones WHERE RADI_NUME_RADI = $rad_salida");
$id_anexo = $db->conn->getOne("SELECT id from anexos where radi_nume_salida = $rad_salida");

foreach($direcciones_orginales as $direccion)
{
    $id_dir_original = $direccion['ID'];
    $rs_sgd_ciu_codigo = $direccion['SGD_CIU_CODIGO'] ? "SGD_CIU_CODIGO = ".$direccion['SGD_CIU_CODIGO'] : "SGD_CIU_CODIGO IS NULL";
    $rs_sgd_oem_codigo = $direccion['SGD_OEM_CODIGO'] ? "SGD_OEM_CODIGO = ".$direccion['SGD_OEM_CODIGO'] : "SGD_OEM_CODIGO IS NULL";
    $id_nuevo = $db->conn->getOne("SELECT id FROM SGD_DIR_DRECCIONES WHERE RADI_NUME_RADI = $rad_salida AND $rs_sgd_ciu_codigo AND $rs_sgd_oem_codigo ORDER BY id LIMIT 1");
    //echo "<br>SELECT SGD_DIR_CODIGO FROM SGD_DIR_DRECCIONES WHERE RADI_NUME_RADI = $rad_salida AND SGD_CIU_CODIGO = $rs_sgd_ciu_codigo AND SGD_OEM_CODIGO = $rs_sgd_oem_codigo ORDER BY id LIMIT 1";
    //echo "<br>SELECT id from anexos where radi_nume_salida = $rad_salida";
    echo $id_nuevo;
    //$dbv->conn->execute("UPDATE sgd_rad_envios SET id_direccion = $id_nuevo, estado = 1 WHERE id_anexo = $id_anexo AND id_direccion = $id_dir_original");
    
}
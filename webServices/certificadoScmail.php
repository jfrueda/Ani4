<?php
$ruta_raiz = "..";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once("$ruta_raiz/processConfig.php");

require_once "Scmail.php";

$db = new ConnectionHandler("$ruta_raiz");  
$scm = new Scmail($db);

header("Content-Type:application/json");
$headers=getallheaders();

if($headers['Token']==$token_scmail)
{
    $json = file_get_contents('php://input');
    $data = json_decode($json);

    $radicado=explode('Radicado',$data->asunto);


    $pdf=$scm->getpdf($data->urlImagen,$data->uuid,$CONTENT_PATH);

    $obs=$data->descripcion."-".$data->estadoEnvio;

    $regenv=$scm->insertregenvio($data->uuid,$data->emailDestino,str_replace("T",' ',$data->fechaEstado),$radicado[1],$obs);

    $inslog=$scm->insertlog(
        $radicado[1]
        ,$data->estadoEnvio
        ,$data->asunto
        ,$data->descripcion
        ,$data->urlImagen
        ,str_replace("T",' ',$data->fechaEstado)
        ,$data->emailDestino
        ,$data->emailRemite
        ,$data->uuid
    );

    if($inslog && $pdf && $regenv)
        $result = [ 'estado' => 1, 'msg' => 'consumo exitoso' ];
    else
        $result = [ 'estado' => 0, 'msg' => 'error insert log' ];
        

}
else
{
    $result = [ 'estado' => 0, 'msg' => 'credenciales invalidas' ];
}
echo json_encode( $result);
?>

<?php
// -----------------------------------------------------------------
// Cabeceras de aceptación Formulario, json y origenes desconocidos
// -----------------------------------------------------------------
header('Content-Type:application/x-www-form-urlencoded');
header('Content-Type:application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:*');
header('Access-Control-Allow-Headers:');

$postData = json_decode(file_get_contents('php://input'),true);
$validador = $postData['condi'];
//require_once($_SERVER['DOCUMENT_ROOT'].'/'.$postData['path'].'/include/db/ConnectionHandler.php');
require_once('./../../include/db/ConnectionHandler.php');
$db = new ConnectionHandler('./../../');

$documentoUsr = $postData['userDoc'];

switch ($validador) {
    case 'valExp':
        $sql = "SELECT count(*) as EXP from sgd_sexp_secexpedientes where usua_doc_responsable = '{$documentoUsr}' and (sgd_sexp_estado = 0 or sgd_sexp_estado is null)";
        $rs = $db->conn->Execute($sql);
        /*$exp = [];

        foreach ($rs as $key => $value) {
            $exp[] = $value['SGD_EXP_NUMERO'];
        }*/

        if(!empty($rs->fields['EXP'])){

            return print(json_encode([
                            "resp"=>'failed',
                            "expedientes"=> $rs->fields['EXP'],
                            "seleccionado"=>$postData['select']
                        ]));
            
        }else{
            return print(json_encode([
                "resp"=>'succes'
            ]));
        }
        break;
    
    default:
        # code...
        break;
}




?>
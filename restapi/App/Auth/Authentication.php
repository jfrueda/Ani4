<?php 

namespace App\Auth;

use App\Lib\ADOdb;
use App\Lib\ManagementError;
use App\Lib\Auth;

/**
 * @OA\Schema(
 * )
 */
class Authentication

{

    public function token($req, $res)
    {
        $data = json_decode($req->reqMethod, true);

        $token = new Auth;
        $db = new ADOdb();
        $usuario = strtoupper($data['login']);
        $password = $data['password'];
        $cryptKey = SUBSTR(md5($password),1,26);
        // $cryptKey = "HASHBYTES('SHA2_256',{$password})";
        $query = "SELECT * FROM usuario WHERE usua_login='$usuario' AND usua_pasw='$cryptKey'";
          $rs = $db->conn->GetArray($query);
            $count=count($rs);

        // Cuarto, verifica si la consulta ha devuelto algun resultado
        if ($count == 1) 
        {
            // Si encontro una fila, devuelve verdadero
              //echo json_encode($cryptKey);
              $ruta_raiz= "..";
             // include_once "../session_orfeo.php";
              $_SESSION["userapi"]   = $usuario;
              $_SESSION["userdoc"]   = $rs[0]['USUA_DOC'];
              $_SESSION["usercodi"]  = $rs[0]['USUA_CODI'];
              $_SESSION["codi_depe"] = $rs[0]['DEPE_CODI'];
              $_SESSION["nivel_seg"] = $rs[0]['CODI_NIVEL'];

             $genToken = $token->auth($usuario , 'rol', 'application');
             if(!$genToken){
                http_response_code(404);
                echo json_encode([
                      'message' => 'Could not generate token, Contact support',
                      ]);
             } else {
                http_response_code(200);
                echo json_encode($genToken);
              }
        } 
        else 
        {
          // Si no encontro ninguna fila, devuelve falso
           http_response_code(404);
           echo json_encode([
                          "responseCode" => 403,
                          "responseMessage" => "Token Invalido"
                        ]);
        }
    } 
}

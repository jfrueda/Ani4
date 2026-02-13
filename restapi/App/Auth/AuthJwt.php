<?php

namespace App\Auth;

use App\Lib\ADOdb;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Lib\SignPdf;

/**
 * @OA\Info(title="Api para firma electronica", version="1.0")
*/

class AuthJwt extends ADOdb
{
/**
 * @OA\Post(
 *     path="/restapi/authJwt/",
 * 	   summary="Generar Token",
 * 	   tags={"Traer Token"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="login",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string"
 *                 ),
 *                 example={"login": "USUARIO SUPER ARGO", "password": "PASSWORD SUPER ARGO"}
 *             )
 *         )
 *     ),
 *     @OA\Response(response="200", description="Genera token Con duración de 5 minutos")
 * )
 */

	public function generateToken($req, $res)
	{

		$data = json_decode($req->reqMethod, true);
		$usuario = strtoupper($data['login']);
		$password = SUBSTR(md5($data['password']),1,26);

		$query = $this->conn->prepare("SELECT * FROM usuario WHERE usua_login=? AND usua_pasw=?");

		$bindVariables = ['user' => $usuario, 'pass' => $password];
		$rs = $this->conn->execute($query, $bindVariables);

		if (!$rs->EOF) {
			$id = $rs->fields['ID'];
		}

		/**************************************************/
		//Empieza JWT
		/**************************************************/

		//$now = strtotime('now') + 3600;
		$now = strtotime('now') + 300;
		$payload = [
			'exp'=> $now,
			'data' => $id,
		];

		$res = (isset($payload['data'])) 
			? ['Token' => JWT::encode($payload, $_ENV['SECRETJWT'], 'HS256'),'Expired' => date('Y-m-d H:i:s', $payload['exp'])]
			: [http_response_code(401), "mensaje"=>'No se ha realizado la autenticación'];

		echo json_encode($res);
		return ;
	}

	public function tokenDesencript($req, $res)
	{
		$dataJson = json_decode($req->reqMethod, true);

		$headers = apache_request_headers();
		$authorization = explode(" ", $headers['Authorization']);
		try
		{
			$decoded = JWT::decode($authorization[1], new Key($_ENV['SECRETJWT'], 'HS256'));
			$proccesPdf = new SignPdf();
			//var_dump($decoded->data);die();

			$query = $this->conn->prepare("SELECT * FROM usuario WHERE id=?");
			$bindVariables = ['id' => $decoded->data];

			$rs = $this->conn->execute($query, $bindVariables);

			return (!$rs->EOF) ? $proccesPdf->proccesPdf($dataJson['MimeType'], $dataJson['archivoB64']) : false;

		}
		catch (Firebase\JWT\ExpiredException $e) 
		{
			// Token expirado, manejar según sea necesario
			echo json_encode([
				'error' => 'Token expirado'
			]);
			return;
		}
		catch (Exception $e) 
		{
			// Otro tipo de excepción
			echo json_encode([
				'error' => $e->getMessage()
			]);
			return;
		}

	}
}
?>
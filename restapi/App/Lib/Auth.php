<?php namespace App\Lib;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth
{
  protected $key = '923480239s42@#$@#';

	public function auth($usuario, $rol, $application)
    {
        try
        {
            $issueDate = time();
            $expirationDate = time() + 3600; // 1hour
            $payload = array(
                "iss" => "https://argo.minenergia.gov.co",
                "aud" => "https://argo.minenergia.gov.co",
                "iat" => $issueDate,
                "exp" => $expirationDate,
                "userName" => $usuario,
                "rol"      => $rol,
                "Application"      => $application,
            );
      
            $jwtGeneratedToken = JWT::encode($payload, $this->key, 'HS256');
    
            return [
                'token' => $jwtGeneratedToken,
                'expires' => $expirationDate,
            ];
        }
        catch(Execption $e)
        {
            echo $e->getMessage();
        }

    }

    public function authValid()
	{
        try
        {
            $headers = apache_request_headers();
            if(isset($headers['Authorization']))
            {
                $token = str_ireplace('Bearer ', '', $headers['Authorization']);
                $decoded = JWT::decode($token , new Key($this->key, 'HS256'));
                $serverName = "https://argo.minenergia.gov.co";

               if ($decoded->iss !== $serverName ||
                    $decoded->exp <= time())
                {
                    header('HTTP/1.1 401 Unauthorized');
                    echo json_encode(array(
                        "responseCode" => 403,
                        "responseMessage" => "Token Invalido"));
                        exit;
                 } else return true ;
            } else {
                header('HTTP/1.1 401 Unauthorized');
                echo json_encode(array(
                    "responseCode" => 403,
                    "responseMessage" => "Token Invalido"));
                     exit;
            }
        }
        catch(Exception $e)
        {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(array(
                "responseCode" => 403,
                "responseMessage" => "Token Invalido"));
                 exit;
        }
    }

    
}
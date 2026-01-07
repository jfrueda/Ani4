<?php

namespace App\Lib;

use App\Lib\ADOdb;
/**
 * @OA\Info(title="Api para firma electronica", version="1.0")
*/
class SignPdf extends ADOdb
{

/**
 * @OA\Post(
 *     path="/restapi/encriptPdf/",
 *     summary="Traer Imagen Firmada",
 *     tags={"Traer PDF Firmado"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="MimeType",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="archivoB64",
 *                     type="string"
 *                 ),
 *                 example={"MimeType":"pdf", "archivoB64": "CADENA EN BASE 64 DE UN PDF"}
 *             )
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="Authorization",
 *         in="header",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             default="Bearer YOUR_ACCESS_TOKEN",
 *         ),
 *         description="Token de acceso Bearer"
 *     ),
 *     @OA\Response(response="200", description="Recibe el token de autenticación, el Archivo En Base 64 lo procesa y devuelve firmado")
 * )
 */
	public function proccesPdf($mime, $b64)
	{
		if($mime != 'pdf')
		{
			echo json_encode(['mensaje' => 'El mime permitida es pdf']);
			return;
		}
		else
		{

			$loader = new \Twig\Loader\FilesystemLoader($_SERVER['DOCUMENT_ROOT'].$_ENV['prefix'].'restapi/App/Radicador/Resources/SQLs');
			$twig = new \Twig\Environment($loader, [
				'cache' => false,
			]);

			$archProcess64 = base64_decode($b64);
			//var_dump($archProcess64);

			$routeBodega = $_SERVER['DOCUMENT_ROOT'].$_ENV['RUTA_BODEGA'];
			$mensajes = [];

			if(is_dir($routeBodega) && is_writable($routeBodega))
			{
				//var_dump($routeBodega.'prueba1.pdf');die();

				$sql = "SELECT id FROM sgd_firma_api_log ORDER BY id DESC LIMIT 1";
				$rs = $this->conn->Execute($sql);

				$consecutivo = (!$rs->EOF) ? $rs->fields['ID'] +=1 : 1;

				file_put_contents($routeBodega.'imagen-'.$consecutivo.'-'.date('Y-m-d-H:i').'.pdf', $archProcess64);
				//var_dump(file_put_contents($routeBodega.'archivo-'.date('Y-m-d-H:m:s').'.pdf', $archProcess64));

				$full_path = $routeBodega.'imagen-'.$consecutivo.'-'.date('Y-m-d-H:i').'.pdf';
				//$P12_FILE=$routeBodega.'SUPERINTENDENCIA_NACIONAL_DE_SALUD.p12';
				$P12_FILE=$_SERVER['DOCUMENT_ROOT'].$_ENV['P12_FILE'];

        $ABSOL_PATH = $_SERVER['DOCUMENT_ROOT'].$_ENV['prefix'];
        $commandFirmado='java -jar '.$ABSOL_PATH.'/include/jsignpdf/JSignPdf.jar '.$full_path.' -kst PKCS12 -ksf '.$P12_FILE.' -ka "superintendencia nacional de salud" -ksp '.$_ENV['CLAVE'].' --font-size 7 -r \'Firmado al Radicar en SuperArgo\' -V -llx 0 -lly 0 -urx 550 -ury 27 -d '.$routeBodega;

        if (!empty($_ENV['tsUrlTimeStamp'])) {
            $commandFirmadoTS = "$commandFirmado -ta PASSWORD -ts {$_ENV['tsUrlTimeStamp']} -tsu {$_ENV['tsuUserTimeStamp']} -tsp {$_ENV['tspPasswordTimeStamp']} 2>&1";
        }
        $commandFirmado .= ' 2>&1';

        $out = null;
        $ret = null;
        $cmd = $commandFirmadoTS ?? $commandFirmado;
        $inf = exec($cmd,$out,$ret);

        // si falla la ejecución de jsign guardar error en bodega/jsignpdf.log
        if ($ret != 0) {
            $out = implode(PHP_EOL, $out);
            error_log(date(DATE_ATOM)." ".basename(__FILE__)." ($ret) $out\n",3,"$ABSOL_PATH/bodega/jsignpdf.log");
        }

				$archFirmado = $routeBodega . 'imagen-' . $consecutivo . '-' . date('Y-m-d-H:i') . '_signed.pdf';
				$sql = $twig->render('insertLogApiFirma.sql', [
					'paths'=> $archFirmado,
				]);

				$this->conn->Execute($sql);

				if($inf == 'INFO Finished: Signature succesfully created.')
				{

						$commandDel = 'rm -rf ' . $full_path;
						exec($commandDel, $out, $ret);

						if(is_file($archFirmado))
						{
							array_push($mensajes, ['ArchivoFirmado' => base64_encode(file_get_contents($archFirmado))]);
						}
						else
						{
							array_push($mensajes, ['mensaje' => 'Error en la creación o firmado del archivo']);
						}
					}
			}
			else
			{
				array_push($mensajes, ['mensaje' => 'Directorio Inexistente']);
			}
		}

		echo json_encode($mensajes);

		return;
	}
}

?>

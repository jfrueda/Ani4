<?php 
/*
* @moudle anexos
* @author stygh
*/
namespace App\anexos;

use App\Lib\ADOdb;

class Anexos
{
	protected $db;

	public function __construct()
	{
		$this->db = new ADOdb();
		//session_start();
	}

	public function getAnexCode($radicado, $key)
	{
		$radAnex = str_pad("{$radicado}",18,"0");
		$radi = ($radAnex + $key);
		return  $radi +=1;
	}

	public function getUser($codeUsr, $dep)
	{
		$sql = "SELECT * FROM usuario WHERE usua_codi = {$codeUsr} AND depe_codi = {$dep}";
		$res = $this->db->conn->Execute($sql);
		foreach($res as $value)
		{
			$usuario = $value['USUA_LOGIN'];
		}

		return $usuario;
	}

	public function getTipoAnexo($extension)
	{
		// $sql = "SELECT * FROM ANEXOS_TIPO LIMIT 1 WHERE anex_tipo_ext = '{$extension}'";
		$sql = "SELECT * FROM ANEXOS_TIPO  WHERE anex_tipo_ext like '%{$extension}%' LIMIT 1";
		$rs = $this->db->conn->Execute($sql);

		foreach($rs as $value)
		{
			$codTipAnex = $value['ANEX_TIPO_CODI'];
		}

		return $codTipAnex;
	}

	public function setEml($twig,$anio, $dep, $mime, $radicado, $codUser, $asunto, $usuario)
	{
		$radAnex = str_pad("{$radicado}",18,"0");
		$ruta_bodega = $_SERVER['DOCUMENT_ROOT'].$_ENV['prefix']."/bodega/{$anio}/{$dep}/docs/{$radAnex}.eml";
		$saveEml = file_put_contents($ruta_bodega, $mime);
		$pesoEml = filesize($ruta_bodega);
		//echo json_encode($pesoEml);

		$sqlAnex = $twig->render('insertarAnexos.sql',[
									'anexRad' => $radicado,
									'anxCode' =>$radAnex,
									'anexExt' =>$this->getTipoAnexo('eml'),
									//'usuario' => $this->getUser($codUser, $dep),
									'usuario' => $usuario,
									'anexDesc' => $asunto,
									'nombArch' => "{$radAnex}.eml",
									'size' =>(!empty($size) ? $size : $pesoEml),
									'anexNum' => 1
							]);
		
		$rsAnex = $this->db->conn->execute($sqlAnex);
		return $rsAnex->EOF;
		//echo json_encode(array("archEMl" => $saveEml));
		//return;
	}

	public function setAnex($twig, $anexos, $radicado, $asunto, $dep, $codUser, $cuentaOrg, $mime)
	{
		$anio = date('Y');

		//$sqlUsr = "SELECT usua_login FROM usuario u WHERE usua_codi = {$codUser} and depe_codi = {$dep}";
		$sqlUsr = "SELECT usua_login FROM usuario u WHERE usua_login = upper('admon')";
		$rs = $this->db->conn->Execute($sqlUsr);
		$usuario = $rs->fields['USUA_LOGIN'];

		$archEml = $this-> setEml($twig, $anio, $dep, $mime, $radicado, $codUser, $asunto,$usuario);

		// echo json_encode($anexos);return;

		$created = [];
		$cnt = 2;

		foreach($anexos as $key =>$value)
		{

			$directorio = $_SERVER['DOCUMENT_ROOT'].$_ENV['prefix']."/bodega/{$anio}/{$dep}/docs/";
			if(is_readable($directorio))
			{
				$name = explode(".",$value['name']);
				$extension = $name[1];
				// echo json_encode($this->getTipoAnexo($value));
				// return;
				$archbs64_anex = (!empty($value['contentBytes'])) ? $value['contentBytes'] : $value['bs64'];
				//echo json_encode($archbs64_anex); return;
				$size = $value['size'];

				$anex = base64_decode($archbs64_anex, true);
				$anexCodRad = $this->getAnexCode($radicado, $key);
				$ruta_bodega = $_SERVER['DOCUMENT_ROOT'].$_ENV['prefix']."/bodega/{$anio}/{$dep}/docs/{$anexCodRad}.{$extension}";
				//$verif_arch = file_exists($ruta_bodega);
				//$path_bd = "/{$año}/{$dep}/docs/{$anexCodRad}.{$extension}";

					$savePdf = file_put_contents($ruta_bodega, $anex);
					//$sql = "UPDATE RADICADO SET RADI_PATH = '{$path_bd}' WHERE RADI_NUME_RADI = {$radicado}";
					//$rs = $this->db->conn->execute($sql);

					$sqlAnex = $twig->render('insertarAnexos.sql',[
							'anexRad' => $radicado,
							'anxCode' =>$anexCodRad,
							'anexExt' =>$this->getTipoAnexo($extension),
							//'usuario' => $this->getUser($codUser, $dep),
							'usuario' => $usuario,
							'anexDesc' => $asunto,
							'nombArch' => "{$anexCodRad}.{$extension}",
							'size' =>(!empty($size) ? $size : '0000'),
							'anexNum' => $cnt++
					]);
					//echo json_encode($sqlAnex);
					$rsAnex = $this->db->conn->execute($sqlAnex);
					$created[$anexCodRad] = $rsAnex->EOF;
			}
			else
			{
				echo json_encode([
					"MENSAJE"=>"DIRECTORIO INACCESIBLE POR FAVOR REVISAR LA RUTA O LOS PERMISOS DE LA CARPETA",
					"DIRECTORIO"=>$directorio
				]);
				die();
			}
		}

		if(!empty($created))
		{
			return true;
		}
	}
}



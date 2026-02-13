<?php
// -----------------------------------------------------------------
// Cabeceras de aceptación Formulario, json y origenes desconocidos
// -----------------------------------------------------------------
header('Content-Type:application/x-www-form-urlencoded');
header('Content-Type:application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:*');
header('Access-Control-Allow-Headers:');
// ----------------------------------------------------------------
// Recibe los datos para el formato de intercambio json y
// los convierte en array para su previa manipulación
// ----------------------------------------------------------------
ini_set('display_errors', 1);
$ruta_raiz = './../';
require_once ("{$ruta_raiz}include/db/ConnectionHandler.php");

$postData = json_decode(file_get_contents('php://input'),true);


class Acuses 
{
	protected $bd;

	function __construct()
	{
		$this->bd = new ConnectionHandler("./../");
		$this->$valProcces = $sql;
	}

	public function valExistRad($radicado)
	{
		$sql = "SELECT r.radi_nume_radi, a.anex_estado FROM RADICADO r 
				INNER JOIN ANEXOS a on r.radi_nume_radi = a.radi_nume_salida
				WHERE r.radi_nume_radi ={$radicado} LIMIT 1";

        $rs = $this->bd->conn->execute($sql);

        echo json_encode([
        	"radicado"=>$rs->fields['RADI_NUME_RADI'],
        	"estadoAnex"=>$rs->fields['ANEX_ESTADO'],
        	"response"=>$rs->EOF
    	]);


	}

	public function setData($acuse,$emails,$fecha,$hora,$radicado,$tpenvio){

		$sql = "INSERT INTO SGD_RENV_REGENVIO(
            id,
            sgd_renv_pais,
            sgd_renv_cantidad,
            sgd_renv_depto,
            sgd_renv_mpio,
            sgd_renv_dir,
            sgd_dir_tipo,
            sgd_renv_mail,
            sgd_renv_codigo,
            sgd_renv_fech,
            radi_nume_sal,
            sgd_fenv_codigo,
            sgd_renv_nombre,
            sgd_renv_observa,
            usua_doc,
            depe_codi 
        )VALUES(
            (select max(id) + 1 from sgd_renv_regenvio),
            'COLOMBIA',
			1,
            'D.C.',
            'BOGOTÁ',
            '<a target=\"_blank\" href=\"{$acuse}\">Certificación del envio de correo</a>',
			1,
            '{$emails}',
            (select max(sgd_renv_codigo) + 1 from sgd_renv_regenvio),
            '{$fecha} {$hora}',
            '{$radicado}',
			 {$tpenvio},
            '{$radicado} - {$emails}',
            'Certificado de entrega {$radicado}-ENVIADO',
            10153900001,
            93004
        )";

        $rs = $this->bd->conn->execute($sql);

        if($rs->EOF){

			return true;
        }

	}

	public function getData($radicado,$fecha,$hora,$emails,$archB64,$tpenvio){

		$sql = "SELECT conf_valor FROM sgd_config WHERE conf_nombre = 'CONTENT_PATH'";
		$rs = $this->bd->conn->execute($sql);

		$rutaSavePdfBod = $rs->fields['CONF_VALOR'].'acuses/';
		$archRadIni = "{$rutaSavePdfBod}{$radicado}.pdf";
		$indice = 0;
		$permisos = is_writable($rutaSavePdfBod);

		if(!is_writable($rutaSavePdfBod) || !is_dir($rutaSavePdfBod)){
			$insertReg = false;
			echo json_encode([
				"result"=>$insertReg,
				"Mensaje"=>"la carpeta [acuses] de la bodega no existe o no posee permisos suficientes para guardar archivos"
			]);
			return;
		}

	    // Si no existe el archivo original, lo crea por primera vez
	    if (!file_exists($archRadIni)) {
	        
	        $archivoPDF = base64_decode($archB64);
	        file_put_contents($archRadIni, $archivoPDF);
	        $insertReg = $this->setData("bodega/acuses/{$radicado}.pdf",$emails,$fecha,$hora,$radicado,$tpenvio);

	    } else {
	        // Si existe, buscamos el siguiente índice disponible
	        $indice = 1;
	        while (file_exists("{$rutaSavePdfBod}{$radicado}_{$indice}.pdf")) {
	            $indice++;
	        }

	        // Nombre único encontrado, guardamos el archivo
	        $archRadMod = "{$rutaSavePdfBod}{$radicado}_{$indice}.pdf";
	        $archivoPDF = base64_decode($archB64);
	        file_put_contents($archRadMod, $archivoPDF);
	        $insertReg = $this->setData("bodega/acuses/{$radicado}_{$indice}.pdf",$emails,$fecha,$hora,$radicado,$tpenvio);
	    }


		echo json_encode([
			"result"=>$insertReg,
		]);
		return;

	}
}

$objGet = new Acuses();
if($postData['validar'] == true){
	$objGet->valExistRad($postData['radicado']);
}else{

	$objGet->getData($postData['radicado'],$postData['fecha'],$postData['hora'],$postData['emails'],$postData['archB64'],$postData['tpenvio']);
}
?>
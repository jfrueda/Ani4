<?php

$ruta_raiz = (!$ruta_raiz) ? "../.." : $ruta_raiz;

require_once($ruta_raiz."/vendor/autoload.php");
require_once("./logs/logsAcuses.php");

use PhpOffice\PhpSpreadsheet\IOFactory;

class Acuses{

	protected $urlServTraza;
	protected $urlCertSmail;
	protected $token;
	protected $log;

	public function __construct(){

		include_once('./confAcuses.php');

		$this->urlServTraza = URLTRAZA;
		$this->urlCertSmail = URLCERTSMAIL;
		$this->token = X_SCKEY_TOKEN;
		$this->log = new logsAcuses('../..');
	}

	public function request($urlService)
	{

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "{$urlService}",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    "X-SCKEY-TOKEN: {$this->token}"
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

	public function sendDataCertificado($estadoEnvio, $asunto, $descripcion, $urlImagen, $fechaEstado, $emailDestino, $emailRemite, $uuid, $repApiScmail = '')
	{
		
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->urlCertSmail,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => '{
						"estadoEnvio": "' . $estadoEnvio . '",
						"asunto": "'.$asunto.'",
						"descripcion": "' . $descripcion . '",
						"urlImagen": "'.$urlImagen.'",
						"fechaEstado": "'.$fechaEstado.'",
						"emailDestino": "' . $emailDestino . '",
						"emailRemite": "' . $emailRemite . '",
						"uuid": "' . $uuid . '"
					}',
			CURLOPT_HTTPHEADER => array(
				'token: fiO4)sM89gjhGsm',
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);
		
		curl_close($curl);
		//var_dump($rs);
		$rs = json_decode($response, true);
		
		if($rs["estado"] == 1)
		{
			$resp = array(
				"ESTADO_ENVIO" => $estadoEnvio,
				"ASUNTO" => $asunto,
				"DESCRIPCION" => $descripcion,
				"URL_IMAGEN" => $urlImagen,
				"FECHA_ESTADO" => $fechaEstado,
				"EMAIL_DESTINO" => $emailDestino,
				"EMAIL_REMITE" => $emailRemite,
				"UUID" => $uuid,
				"ESTADO_DEL_CONSUMO" => $rs["estado"],
				"MENSAJE_DEL_CONSUMO" => $rs["msg"]
			);
			
			echo "<hr>";
			echo "<pre>";
			print_r($resp);
			//echo json_encode($resp, JSON_PRETTY_PRINT);
		}
		else
		{
			$errApi = ($repApiScmail != 200) ? 'Falla Por Api Scmail Software Colombia - GET' : 'Falla Por Api De Orfeo scmail - POST';
	
			$data = array(
				"ASUNTO" => $asunto,
				"DESCRIPCION" => $descripcion,
				"EMAIL_REMITE" => $emailRemite,
				"UUID" => $uuid,
				"ESTADO_DEL_CONSUMO" => $rs["estado"],
				"MENSAJE_DEL_CONSUMO" => $rs["msg"],
				"ERROR_API" => $errApi
			);
			$this->log->failureRecord($data);
		}
	}

	public function getDataXls()
	{
		
		$excelFile = './data.xlsx';
		$spreadsheet = IOFactory::load($excelFile);
		$worksheet = $spreadsheet->getActiveSheet();

		$columnData = [];

		foreach ($worksheet->getRowIterator() as $row) 
		{
			$cellA = $worksheet->getCell('A' . $row->getRowIndex());
			$cellB = $worksheet->getCell('B' . $row->getRowIndex());
			$cellD = $worksheet->getCell('D' . $row->getRowIndex());
			$cellE = $worksheet->getCell('E' . $row->getRowIndex());
			$cellF = $worksheet->getCell('F' . $row->getRowIndex());
			$cellH = $worksheet->getCell('H' . $row->getRowIndex());

			$columnData[] = array(
									"UIID"=>$cellA->getValue(),
									"FECHA"=>$cellB->getValue(),
									"DESTINATARIO"=>$cellD->getValue(),
									"DESCRIPT"=>$cellE->getValue(),
									"REMITENTE"=>$cellF->getValue(),
									"ASUNTO"=>$cellH->getValue(),

									);
		}
		//return $columnData;
		//var_dump($columnData);
		// Imprimir los valores de la columna A
		foreach ($columnData as $value) 
		{

			$recurso = "{$this->urlServTraza}{$value["UIID"]}";
			$response = $this->request($recurso);

			$objResponse = json_decode($response, true);

			$acusesUrl = [];
			$acusesEstatus = [];
			
			foreach($objResponse as $value1)
			{
				foreach($value1 as $value2)
				{
					//var_dump($value2);
					$acusesEstatus[] = $value2["emailStatus"];
					$acusesUrl[] = $value2["reportUrl"];
				}
			}

			$this->sendDataCertificado(
											$acusesEstatus[0], 
											$value['ASUNTO'],
											$value['DESCRIPT'],
											$acusesUrl[0],
											$value['FECHA'],
											$value['DESTINATARIO'],
											$value['REMITENTE'],
											$value['UIID'],
											$objResponse["statusCode"]
										);
		}
	}
}

$objAcus = new Acuses();
echo "<h3 style='text-align: center;'>Resultado Cargue De Acuses.</h3>";
echo '<a href="./logs/verDataFallida.php" target="_blank">Verificar si hay registros No Cargados</a>'; 
//if($objAcus->errorReg() ! false) echo $objAcus->errorReg();
$objAcus->getDataXls();
echo "<hr>";
?>
<?php 

if (!isset($ruta_raiz)) $ruta_raiz = __DIR__.'/..';
include_once $ruta_raiz."/include/db/ConnectionHandler.php";
//require_once('connection.php');
require_once('restclient.php');

$rest = new Restclient();
if(!$db || !is_object($db)) {
    $db = new ConnectionHandler("$ruta_raiz");
}

$usuario = $db->conn->getOne('SELECT conf_valor FROM sgd_config WHERE conf_nombre = ?', ['usuarioApiCorreoCertificado']);
$password = $db->conn->getOne('SELECT conf_valor FROM sgd_config WHERE conf_nombre = ?', ['passwordApiCorreoCertificado']);
$correo = $db->conn->getOne('SELECT conf_valor FROM sgd_config WHERE conf_nombre = ?', ['correoSaliente']);

$params = array(
	'grant_type' => "password",
	'username' => $usuario,
	'password' => $password
);

$token = $rest->login($params);
$fecha=date('Y')."-".date('m')."-".date('d');
$startDate = $_GET['startDate'] ?? $argv[1] ?? date('Y-m-d');
$endDate   = $_GET['endDate'] ?? $argv[2] ?? date('Y-m-d');

$contador = 0;
$procesados = 0;
$actualizados = 0;

if(isset($token)) 
{
	$response = $rest->messageStatus($token,$startDate.'T00:00:01',$endDate.'T23:59:59',$correo);
	if(isset($response)) 
	{
		foreach ($response as $key=>$row)
		{
			foreach($row as $value)
			{
				$contador ++;
				$trackingId = $value['MessageId'];
				$subject = $value['Subject'];
				$customerTrackingId = str_replace('"','',explode('radicado',strtolower($value['Subject'])));
				$senderName = $value['RecipientAddress'];
				$senderAddress = $value['SenderAddress'];
				$date = str_replace('/', '-',$value['DateSentUTC']).'T'.str_replace(' ','',$value['TimeSentUTC']);
				$status = $value['DeliveryStatus'];
				$sql = "INSERT INTO records(trackingid, customertrackingid, sendername, senderaddress, date_, status_) VALUES ('".$trackingId."',".trim($customerTrackingId[1]).",'".$senderName."','".$senderAddress."', '".$date."', '".$status."')";
				$db->query($sql);

				$address = $value['RecipientAddress'];
				$deliveryStatus = $value['DeliveryStatus'];
				$deliveryDetail = $value['DeliveryStatus'];
				//$deliveredDate = $value['DateDelieveredUTC'];
				$deliveredDate = $value['DateDeliveredLocal'];
				$openedDate = $value['DateOpenedUTC'];

				$sql1 = "INSERT INTO records_recipients_details(address_, delivery_status, delivery_detail, delivered_date, opened_date, fk_record) VALUES ('".$address ."', '".$deliveryStatus."', '".$deliveryDetail."', '".$deliveredDate."', '".$openedDate."', (select max(id) from records))";

				$db->query($sql1);
				
				/******************************************************
				 * trae comprimido */
				$curl = curl_init();
				
				curl_setopt_array($curl, array(
					CURLOPT_URL => 'https://webapi.r1.rpost.net/api/v1/Receipt/'.$trackingId,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'GET',
					CURLOPT_HTTPHEADER => array(
						'Authorization: Bearer '.$token
					),
				));
				
				$response = curl_exec($curl);
				$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

				if ($response === false || $http_code != '200') {
					$error = curl_error($curl);
					error_log(date(DATE_ATOM)." {$trackingId} ($http_code) $error\n", 3 , "./../bodega/certicamara.log");
					continue;
				}

				curl_close($curl);

				if(is_dir('./../bodega/cert/'))
				{
					file_put_contents("./../bodega/cert/{$trackingId}.zip", $response);

					/******************************************************/
					/*$link="<a  href=\"certicamara/trackingId.php?t=".$trackingId."\">Descargar certificado de entrega</a>";*/
					$link="<a  href=\"./../2/bodega/cert/{$trackingId}.zip\">Descargar certificado de entrega</a>";
				}
				else
				{
					echo "ERROR NO EXISTE LA CARPETA CERT DENTRO DE LA BODEGA POR FAVOR CREELA Y VUELVA A EJECUTAR, PERO REVISE EL PROCESO PORUQ YA ALIMENTO LAS TABLAS RECORDS y RECORDS_RECIPIENTS_DETAILS";
					die();
				}

				$email=$senderName;
				$dateF=str_replace('T',' ',$deliveredDate);
				$customerTrackingIdNew = trim($customerTrackingId[1]);
				$sql_un="SELECT count(*) k FROM sgd_renv_regenvio WHERE radi_nume_sal=".$customerTrackingIdNew." AND sgd_renv_nombre='".$address."'";
				$rs_un=$db->query($sql_un);
				
				$deliveryStatust=$deliveryStatus;

				$pos=strpos($deliveryStatus,"Delivery Failed");
				if($pos !== false)
					$deliveryStatust="Entrega fallida";

				$pos=strpos($deliveryStatus,"Delivered to Mailbox" );
				if($pos !== false)
					$deliveryStatust="Entregado al buzon";

				$pos=strpos($deliveryStatus, "Delivered to Mail Server");
				if($pos !== false)
					$deliveryStatust="Entregado al servidor de correo";

				$pos=strpos($deliveryStatus,"Delivered and Opened" );
				if($pos !== false)
					$deliveryStatust="Entregado y Abierto";

				$pos=strpos($deliveryStatus,"Opened" );
				if($pos !== false)
					$deliveryStatust="Abierto";

				$pos=strpos($deliveryStatus,"Sent" );
				if($pos !== false)
					$deliveryStatust="Enviado";

				$customerTrackingIdNew =trim($customerTrackingId[1]);
				$dateFNew = trim($dateF);
				$date_e=explode("/",$dateFNew);
				$datei = (in_array($deliveryStatust, ["Entrega fallida", "Enviado"])) ? date('Y-m-d') : $date_e[0]."-".$date_e[1]."-".$date_e[2];

				if($rs_un->fields['K'] == 0)
				{
					$isql = "INSERT INTO SGD_RENV_REGENVIO(
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
					sgd_renv_observa
					)VALUES(
					(select max(id) + 1 from sgd_renv_regenvio),
					'COLOMBIA',
					1,
					'D.C.',
					'BOGOTÁ',
					'$link',
					1,
					'$email',
					(select max(sgd_renv_codigo) + 1 from sgd_renv_regenvio),
					'$datei',
					'$customerTrackingIdNew',
					106,
					'$address',
					'$deliveryStatust'
					)";

					$db->conn->query($isql);
					$procesados ++;
				} else {
					$sql = "SELECT 
								sgd_renv_observa 
							FROM 
								sgd_renv_regenvio 
							WHERE 
								radi_nume_sal = ? AND 
								sgd_renv_nombre = ? AND
								sgd_renv_dir LIKE '<a  href=%'";
					
					$currentDeliveryStatus = $db->conn->getOne($sql, [
						$customerTrackingIdNew,
						$address
					]);

					if ($currentDeliveryStatus != $deliveryStatust) {
						$sql = "UPDATE 
									sgd_renv_regenvio 
								SET 
									sgd_renv_observa = ?,
									sgd_renv_fech = ? 
								WHERE 
									radi_nume_sal = ? AND
									sgd_renv_nombre = ? AND
									sgd_renv_dir LIKE '<a  href=%'";

						$db->conn->Execute($sql, [
							$deliveryStatust,
							$datei,
							$customerTrackingIdNew,
							$address
						]);
						$actualizados ++;
					}
				}
				echo "$contador $procesados $actualizados $customerTrackingIdNew $address $subject\r\n";
			}
		}
	}
}

echo "$contador certificados generados, $procesados certificados procesados, $actualizados registros actualizados\r\n";
?>
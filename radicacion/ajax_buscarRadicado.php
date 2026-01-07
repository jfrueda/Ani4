<?php
session_start();
define('ADODB_ASSOC_CASE', 1);
$ruta_raiz = "..";
$ADODB_COUNTRECS = false;
include_once("$ruta_raiz/processConfig.php");
include_once("$ruta_raiz/include/db/ConnectionHandler.php");


$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

switch($_POST['servicio'])
{
	case 'modificar_valido':
		header('Content-Type: application/json');

//		echo $_POST['dependencia'];
//		echo $_POST['codUsua'];

		$sqlVal = 'SELECT RADI_USUA_ACTU, radi_depe_actu,sgd_eanu_codigo FROM radicado WHERE radi_nume_radi = '.$_POST['radicado'];
		$rs = $db->conn->execute($sqlVal);

		foreach($rs as $value)
		{
			$dep = $value['RADI_DEPE_ACTU'];
			$codUs = $value['RADI_USUA_ACTU'];
			$enau = $value['SGD_EANU_CODIGO'];
		}

		if($dep == 999 && $codUs == 15 || $enau <> null)
		{

			echo json_encode(['total' => "finalizado"]);
			return;
		}
		else
		{
			$q = 'SELECT COUNT(*) as total FROM radicado WHERE radi_nume_radi = '.$_POST['radicado'];
			$total = $db->conn->getOne($q);
			echo json_encode(['total' => $total]);
		}

	break;
	default:
		header('Content-Type: application/json'); 
		echo json_encode([]);
	break;
}
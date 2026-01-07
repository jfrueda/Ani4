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
$postData = json_decode(file_get_contents('php://input'),true);

$validador = $postData['condicion'];
include_once __DIR__.'/dataRepPz.php';
$back = new DataRepPz();

switch ($validador) {
	case 'usuarios':
		$back->getUsuaios($postData['dep']);
		break;

	case 'getData':
		$back->getData(
			$postData['codiUsa'],
			$postData['codiDoc'],
			$postData['depend']
		);
		break;
	default:
		# code...
		break;
}

?>
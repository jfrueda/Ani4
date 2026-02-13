<?php
 session_start();
 
 $krd            = $_SESSION["krd"];
 $dependencia    = $_SESSION["dependencia"];
 $usua_doc       = $_SESSION["usua_doc"];
 $codusuario     = $_SESSION["codusuario"];


 foreach ($_GET as $key => $valor)   ${$key} = $valor;
 foreach ($_POST as $key => $valor)   ${$key} = $valor;
 $ruta_raiz = "..";
 include_once "$ruta_raiz/include/db/ConnectionHandler.php";
 $db = new ConnectionHandler("$ruta_raiz");	
 define('ADODB_FETCH_ASSOC',2);
 $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;


 foreach ($_POST['radicado'] as $row)
 {
 		$isqlu = "update anexos
			set
			anex_estado=2,
			sgd_deve_fech= now(),
			sgd_deve_codigo = 99
		  where radi_nume_salida=".$row;
	    $rs = $db->conn->Execute($isqlu);

		$record_id = $db->conn->getRow("SELECT id FROM anexos where radi_nume_salida=$row");
		$db->conn->Execute("UPDATE sgd_rad_envios SET estado = 0, devuelto = 't' WHERE id = $record_id");

		$datos = $db->conn->getAll('SELECT usua_codi_dest, depe_codi_dest FROM hist_eventos WHERE radi_nume_radi = '.$row.' and sgd_ttr_codigo = 2 LIMIT 1');
		$usua_codi_dest = $datos[0]['USUA_CODI_DEST'];
        $depe_codi_dest = $datos[0]['DEPE_CODI_DEST'];
		$isql_hl= "insert
		into hist_eventos(DEPE_CODI, HIST_FECH, USUA_CODI, RADI_NUME_RADI, HIST_OBSE, USUA_CODI_DEST, DEPE_CODI_DEST, USUA_DOC, SGD_TTR_CODIGO)
		values ($dependencia, now() ,$codusuario, $row, 'Devolución (SOBREPASO TIEMPO DE ESPERA).', '$usua_codi_dest', '$depe_codi_dest', '$usua_doc',28)";
		$rs = $db->conn->Execute($isql_hl);

		$lista_radicados.=$row.",";
 }
?>
<html>
<body>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<div class="alert alert-success" role="alert">
  Los siguientes radicados fueron marcados como devueltos: <?=$lista_radicados?>
</div>
<br>
<center>
	<button type="button" class="btn btn-success" onclick="window.location='dev_corresp.php';">Continuar</button>
</center>
</body>
</html>
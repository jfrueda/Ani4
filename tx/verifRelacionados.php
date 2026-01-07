<?


	$radicadoEnHistorico = !empty($verrad) ? $verrad : $fldRADI_NUME_RADI;
	$estaEnHistorico = false;
	$esDestinatario = false;
	$usua_doc = $_SESSION['usua_doc'];
	$codusuario = $_SESSION['codusuario'];
	$dependencia = $_SESSION['dependencia'];

	$sqlverRelacionados = "SELECT
		(SELECT COUNT(*) FROM INFORMADOS WHERE cast(RADI_NUME_RADI as varchar(20)) = '$radicadoEnHistorico' AND USUA_DOC = '$usua_doc') AS total_informado,
		(SELECT COUNT(*) FROM TRAMITECONJUNTO WHERE cast(RADI_NUME_RADI as varchar(20)) = '$radicadoEnHistorico' AND USUA_DOC = '$usua_doc') AS total_conjunto,
		(SELECT COUNT(*) FROM SGD_DIR_DRECCIONES WHERE RADI_NUME_RADI = '$radicadoEnHistorico' AND SGD_DIR_DOC = '$usua_doc' AND RADI_NUME_RADI::text LIKE '%3') AS total_destinatario,
		(SELECT COUNT(*) FROM HIST_EVENTOS WHERE SGD_TTR_CODIGO NOT IN (110)  AND RADI_NUME_RADI = '$radicadoEnHistorico' AND ((USUA_DOC = '$usua_doc') OR (USUA_CODI = '$codusuario' AND DEPE_CODI = '$dependencia') OR (HIST_DOC_DEST = '$usua_doc'))) AS total_historico";

	$rsverRelacionados = $db->conn->Execute($sqlverRelacionados);
	
	if (!$rsverRelacionados->EOF) {
		if ($rsverRelacionados->fields['TOTAL_DESTINATARIO'] > 0) {
			$esDestinatario = true;
		}
		if ($rsverRelacionados->fields['TOTAL_INFORMADO'] > 0 || $rsverRelacionados->fields['TOTAL_HISTORICO'] > 0 || $rsverRelacionados->fields['TOTAL_CONJUNTO'] > 0) {
			$estaEnHistorico = true;
		}
	}

	if ($esDestinatario || $estaEnHistorico) {
		$verradPermisos = "PasaSegExp";
		$tienePermiso = true;
		$verradPermisos = "Full";
		$verImg = "SI";
		$valImg = "SI";
		$noPermisoFlag = 1;
	}
?>

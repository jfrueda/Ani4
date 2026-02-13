<?php
session_start();

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$krd         = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc    = $_SESSION["usua_doc"];
$codusuario  = $_SESSION["codusuario"];
$entidad     = $_SESSION["entidad"];
$indiTRD     = $_SESSION["indiTRD"];
$ruta_raiz   = "..";

include_once("$ruta_raiz/include/db/ConnectionHandler.php");
$db = new ConnectionHandler("$ruta_raiz");

if (!defined('ADODB_FETCH_ASSOC'))	define('ADODB_FETCH_ASSOC', 2);
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$encabezadol = $_SERVER['PHP_SELF'] . "?terminot=$terminot&codusua=$codusua&depende=$depende&ent=$ent";
include("$ruta_raiz/busqueda/common.php");

// Genera dinamicamente los tipos de radicado
$sql = "SELECT SGD_TRAD_CODIGO,SGD_TRAD_DESCR FROM SGD_TRAD_TIPORAD ORDER BY SGD_TRAD_CODIGO";
$ADODB_COUNTRECS = true;
$rs_trad = $db->query($sql);
if ($rs_trad->RecordCount() >= 0) {
	$i = 0;
	$cad = "perm_tp";
	$tipos = "<table width='100%'><tr><td height='26' class='listado2' width='100%'>";
	$cmp = "SGD_TPR_TP";
	while ($arr = $rs_trad->FetchRow()) {
		$tipos .= "&nbsp;" . $arr['SGD_TRAD_DESCR'] . "<input type='checkbox' name='" . $cad . $arr['SGD_TRAD_CODIGO'] . "' id='" . $cad . $arr['SGD_TRAD_CODIGO'] . "' value=1 >" . "&nbsp"; //"&nbsp;".$arr['SGD_TRAD_DESCR']."&nbsp;&nbsp;";
		$ins_cmp .= $cmp . $arr['SGD_TRAD_CODIGO'] . ",";
		(isset($_POST[$cad . $arr['SGD_TRAD_CODIGO']])) ? $ins_vlr .= "1," : $ins_vlr .= "0,";
		(isset($_POST[$cad . $arr['SGD_TRAD_CODIGO']])) ? $vlr = "1" : $vlr = "0";

		$matriz[$i] = strtoupper($arr['SGD_TRAD_DESCR']);
		$matriz1[$i] = $cmp . $arr['SGD_TRAD_CODIGO'];
		$matriz2[$i] = $vlr;
		$i += 1;
	}
	$ins_cmp = substr($ins_cmp, 0, strlen($ins_cmp) - 1);
	$ins_vlr = substr($ins_vlr, 0, strlen($ins_vlr) - 1);
	$tipos .= "</td></tr></table>";
} else $tipos .= "<tr><td align='center'> NO SE HAN GESTIONADO TIPOS DE RADICADOS</td></tr></table>";
$ADODB_COUNTRECS = false;
?>
<html>

<head>
	<title></title>
	<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
	<!--<link rel="stylesheet" href="../estilos/orfeo.css">-->

	<script type="text/javascript">
		function regresar() {
			document.adm_tipodoc.submit();
		}
	</script>
</head>

<body bgcolor="#FFFFFF">
	<div id="spiffycalendar" class="text"></div>

	<!-- ENCABEZADO -->
	<div class="card shadow-sm border-0 mb-3">
		<div class="card-header bg-orfeo text-white text-center ">
			<h4 class="fw-bold mb-0">TIPOS DOCUMENTALES</h4>
		</div>
	</div>

	<form method="post" action="<?= $encabezadol ?>" name="adm_tipodoc">
		<div class="card shadow-sm border-0 mx-auto">
			<div class="card-body">
				<!-- Si existe modificación -->
				<?php if ($_POST['actua_tdoc']) { ?>
					<div class="mb-4 row align-items-center">
						<label class="col-sm-4 col-form-label fw-semibold">Código</label>
						<div class="col-sm-5">
							<input type="text" name="codtdocI" value="<?= $codtdocI ?>" class="form-control" size="11" maxlength="7">
						</div>
						<div class="col-sm-3 text-end">
							<input type="submit" name="modi_tdoc" value="Grabar Modificacion" class="botones_largo btn btn-warning w-100">
						</div>
					</div>
				<?php } ?>

				<!-- Descripción -->
				<div class="mb-3 row">
					<label class="col-sm-4 col-form-label fw-semibold">Descripción</label>
					<div class="col-sm-8">
						<input type="text" name="detatipod" value="<?= $detatipod ?>" class="form-control" size="50" maxlength="75">
					</div>
				</div>

				<!-- Término trámite -->
				<div class="mb-3 row">
					<label class="col-sm-4 col-form-label fw-semibold">Término trámite (días)</label>
					<div class="col-sm-8">
						<input type="text" name="terminot" value="<?= $terminot ?>" class="form-control" size="2" maxlength="2">
					</div>
				</div>

				<!-- Selección tipo de documento -->
				<div class="mt-4">
					<label class="fw-bold text-secondary">Seleccione el tipo de documento</label>
				</div>

				<div class="mt-2 p-3 bg-light rounded border">
					<?= $tipos ?>

					<div class="form-check mt-3">
						<input type="checkbox" name="tpRadica" id="tpRadica"
							<?php if ($tpRadica) {
								echo " checked ";
								$tpRadica = '1';
							} else {
								$tpRadica = '0';
							} ?>>
						<label class="form-check-label ms-1" for="tpRadica">
							Este Tipo se Puede Seleccionar Desde el Formulario de Radicación sin TRD (Temporal...)
						</label>
					</div>
				</div>

				<!-- Botones -->
				<div class="text-center mt-4">
					<input type="submit" name="buscar_dcto" value="Buscar" class="btn btn-primary px-4 me-2">
					<input type="submit" name="insertar_tdoc" value="Insertar" class="btn btn-success px-4 me-2">
					<input type="submit" name="actua_tdoc" value="Modificar" class="btn btn-info px-4 me-2">
					<input type="reset" name="aceptar" id="envia22" value="Cancelar" class="btn btn-danger px-4">
				</div>
			</div>
		</div>

		<?PHP
		$whereBusqueda = "";
		if ($buscar_dcto && $detatipod != '') {
			$detatipod = strtoupper($detatipod);
			$whereBusqueda = " where upper(sgd_tpr_descrip) like '%$detatipod%'";
		}

		//Insertar Datos Tpo Documental
		if ($terminot == '') {
			$terminot = 0;
		}

		if ($_POST['insertar_tdoc'] && $detatipod != '') {
			$detatipod = strtoupper($detatipod);
			$isqlB = "select * from sgd_tpr_tpdcumento where upper(rtrim(sgd_tpr_descrip)) = '$detatipod' ";
			# Selecciona el registro a actualizar
			$rs = $db->query($isqlB); # Executa la busqueda y obtiene el registro a actualizar.
			$radiNumero = $rs->fields["SGD_TPR_CODIGO"];
			if ($radiNumero != '') {
				$mensaje_err = "<HR><center><B><FONT COLOR=RED>El Tipo Documento < $radiNumero $detatipod > YA EXISTE. <BR>  VERIFIQUE LA INFORMACION E INTENTE DE NUEVO</FONT></B></center><HR>";
			} else {
				$isql = "select max(sgd_tpr_codigo) as NUME from sgd_tpr_tpdcumento where sgd_tpr_codigo < 9000";
				$rs = $db->query($isql); # Executa la busqueda y obtiene el Codigo del documento.
				$radiNumero = $rs->fields["NUME"];
				$radiNumero = $radiNumero + 1;

				if ($indiTRD == "SI") {
					$detatipod = strtolower(trim($detatipod));
				}

				$query = "insert into SGD_TPR_TPDCUMENTO(SGD_TPR_CODIGO, SGD_TPR_DESCRIP,SGD_TPR_TERMINO," . $ins_cmp . ",SGD_TPR_RADICA,SGD_TPR_ESTADO)" .
					"VALUES ('$radiNumero','$detatipod','$terminot'," . $ins_vlr . "," . $tpRadica . ",1)";

				$rsIN = $db->conn->query($query);
				if ($rsIN) $mensaje_err = "<HR><center><B><FONT COLOR=RED>Tipo Documental Creado<FONT></B></center><HR>";
				else $mensaje_err = "<HR><center><B><FONT COLOR=RED>Error al crear Tipo Documental</FONT></B></center><HR>";
				$terminot = '';
				$detatipod = '';
		?>
				<script type="text/javascript" language="javascript">
					document.adm_tipodoc.detatipod.value = '';
					document.adm_tipodoc.terminot.value = '';
				</script>
			<?
			}
		}
		//Modificacion Datos Tipo Documental
		$detatipod = strtoupper($detatipod);
		if ($_POST['modi_tdoc'] && ($detatipod != '') && ($codtdocI != 0)) {
			$isqlB = "select * from sgd_tpr_tpdcumento where upper(rtrim(sgd_tpr_descrip)) = '$detatipod' and sgd_tpr_codigo != $codtdocI";
			# Selecciona el registro a actualizar
			$rs = $db->query($isqlB); # Executa la busqueda y obtiene el registro a actualizar.
			$radiNumero = $rs->fields["SGD_TPR_CODIGO"];
			if ($radiNumero != '') {
				$mensaje_err = "<HR><center><B><FONT COLOR=RED>El Tipo Documento < $detatipod > YA EXISTE PARA EL C&Oacute;DIGO < $radiNumero > <BR>  VERIFIQUE LA INFORMACION E INTENTE DE NUEVO</FONT></B></center><HR>";
			} else {
				$conteo = count($matriz);
				for ($j = 0; $j < $conteo; $j++) {
					$cadena .= $matriz1[$j] . "=" . $matriz2[$j] . ",";
				}
				if ($indiTRD == "SI") {
					$detatipod = strtolower(trim($detatipod));
				}
				$detatipod = strtoupper($detatipod);
				$query = "update SGD_TPR_TPDCUMENTO set SGD_TPR_DESCRIP ='$detatipod',$cadena
							SGD_TPR_TERMINO = '$terminot'
							, SGD_TERMINO_REAL = '$terminot'
							,SGD_TPR_ESTADO = 1
							,sgd_tpr_radica=$tpRadica
							where  sgd_tpr_codigo = $codtdocI ";
				$rsIN = $db->conn->query($query);
				$terminot = '';
				$detatipod = '';
				$cadena = '';
				$mensaje_err = "<HR><center><B><FONT COLOR=RED>SE MODIFIC&Oacute; EL TIPO DOCUMENTAL</FONT></B></center><HR>";
			?>
				<script type="text/javascript" language="javascript">
					document.adm_tipodoc.detatipod.value = '';
					document.adm_tipodoc.terminot.value = '';
				</script>
		<?
			}
		}
		echo $mensaje_err;
		include_once "$ruta_raiz/trd/lista_tiposdocu.php";
		?>
	</form>
	<p>
		<?= $mensaje_err ?>
	</p>
</body>

</html>
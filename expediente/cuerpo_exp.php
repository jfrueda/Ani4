<?php

/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright
 * 
 * SIIM2 Models are the data definition of SIIM2 Information System
 * Copyright (C) 2013 Infometrika Ltda.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$ruta_raiz   = "..";
$krd         = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc    = $_SESSION["usua_doc"];
$codusuario  = $_SESSION["codusuario"];
$tpNumRad    = $_SESSION["tpNumRad"];
$tpPerRad    = $_SESSION["tpPerRad"];
$tpDescRad   = $_SESSION["tpDescRad"];
$tpDepeRad   = $_SESSION["tpDepeRad"];
$ln          = $_SESSION["digitosDependencia"];
$lnr         = 11 + $ln;

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

include_once "$ruta_raiz/js/funtionImage.php";
include_once "$ruta_raiz/tx/verLinkArchivo.php";
$verLinkArchivo = new verLinkArchivo($db);

if (!$tipo_archivo) $tipo_archivo = 0;

function fnc_date_calcm($this_date, $num_month)
{
	$my_time = strtotime($this_date); //converts date string to UNIX timestamp
	$timestamp = $my_time - ($num_month * 2678400); //calculates # of days passed ($num_days) * # seconds in a day (86400)
	$return_date = date("Y-m-d", $timestamp);  //puts the UNIX timestamp back into string format
	return $return_date; //exit function and return string
}

function fnc_date_calc($this_date, $num_day)
{
	$my_time = strtotime($this_date); //converts date string to UNIX timestamp
	$timestamp = $my_time + ($num_day * 86400); //calculates # of days passed ($num_days) * # seconds in a day (86400)
	$return_date = date("Y-m-d", $timestamp);  //puts the UNIX timestamp back into string format
	return $return_date; //exit function and return string
}

if ($_GET['excluir'] == 1 && $_GET['radExcluido'] != "" && $_GET['expedienteExcluir'] != "") {
	include "$ruta_raiz/include/query/expediente/queryExcluirRadicado.php";
	$rsExcluirRadicado = $db->conn->query($sqlExcluirRadicado);
}

if (!$fechai) $fechai = fnc_date_calcm(date('Y-m-d'), '1');
?>

<html>

<head>
	<title>Sistema de informaci&oacute;n <?= $entidad_largo ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap core CSS -->
	<?php include_once "../htmlheader.inc.php"; ?>
	<?
	if (!$estado_sal) {
		$estado_sal = 2;
	}
	if (!$estado_sal_max) $estado_sal_max = 3;
	if (!$Buscar) $Buscar = 1;

	if ($dep_sel == 0) $dep_sel = "%";
	$accion_sal = "Marcar como Archivado Fisicamente";
	$pagina_sig = "envio.php";

	$buscar_exp = trim($buscar_exp);
	$buscar_rad = trim($buscar_rad);
	$fechf = fnc_date_calc($fechafi, 1);

	switch ($db->driver) {
		case 'oci8':
			$dependencia_busq1 = " and d.sgd_exp_estado=$tipo_archivo and d.depe_codi like '$dep_sel'  and (upper(d.sgd_exp_numero) like '%$buscar_exp%' and upper(d.RADI_NUME_RADI) like '%$buscar_rad%')";
			break;
		case 'postgres':
			$dependencia_busq1 = " and d.sgd_exp_estado=$tipo_archivo and cast(d.depe_codi as varchar) like '$dep_sel'  and (upper(d.sgd_exp_numero) like '%$buscar_exp%' and upper(cast(d.RADI_NUME_RADI as varchar)) like '%$buscar_rad%')";
			break;
	}
	$dependencia_busq2 = " and d.sgd_exp_estado=$tipo_archivo and cast(d.depe_codi as varchar) like '$dep_sel' and (upper(d.sgd_exp_numero) like '%$buscar_exp%' and upper(cast(d.RADI_NUME_RADI as varchar)) like '%$buscar_rad%') ";

	$dependencia_busq1 .= " and d.sgd_exp_fech <= '$fechf' and d.sgd_exp_fech >= '$fechai' ";
	$dependencia_busq2 .= " and d.sgd_exp_fech <= '$fechf' and d.sgd_exp_fech >= '$fechai'";
	//print_r($GLOBALS);

	$fechah = date("dmy") . "_" . time("h_m_s");
	$encabezado = session_name() . "=" . session_id() . "&buscar_exp=$buscar_exp&buscar_rad=$buscar_rad&krd=$krd&tipo_archivo=$tipo_archivo&nomcarpeta=$nomcarpeta&fechai=$fechai&fechafi=$fechafi";

	$tbbordes = "#CEDFC6";

	$tbfondo = "#FFFFCC";

	if (!$orno) {
		$orno = 1;
	}

	$imagen = "flechadesc.gif";

	?>

	<script>
		function sel_dependencia() {
			document.write("<form name=forma_b_correspondencia action='cuerpo_exp.php?<?= $encabezado ?>'  method=post>");
			depsel = form1.dep_sel.value;
			document.write("<input type=hidden name=depsel value=" + depsel + ">");
			document.write("<input type=hidden name=estado_sal  value=3>");
			document.write("<input type=hidden name=estado_sal_max  value=3>");
			document.write("<input type=hidden name=fechah value='<?= $fechah ?>'>");
			document.write("</form>");
			forma_b_correspondencia.submit();
		}

		function confirmaExcluir(radicado, expediente) {
			confirma = confirm('Confirma que el radicado ' + radicado + ' ya fue excluido fisicamente del expediente ' + expediente + '?');
			if (confirma) {
				document.form1.action = "cuerpo_exp.php?<?= $encabezado ?>&radExcluido=" + radicado + "&expedienteExcluir=" + expediente + "&excluir=1";
				document.form1.submit();
			}
		}
	</script>
</head>

<body>
	<div id="spiffycalendar" class="text"></div>
	<link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
	<script language="JavaScript" src="../js/spiffyCal/spiffyCal_v2_1.js"></script>
	<script>
		// Esta funcion esconde el combo de las dependencia e  inforados Se activan cuando el menu envie una seal de cambio.
		function window_onload() {
			form1.depsel.style.display = '';
			form1.enviara.style.display = '';
			form1.depsel8.style.display = 'none';
			form1.carpper.style.display = 'none';
			setVariables();
			setupDescriptions();
		}

		// Cuando existe una sean de cambio el program ejecuta  esta funcion mostrando el combo seleccionado
		function changedepesel() {
			form1.depsel.style.display = 'none';
			form1.carpper.style.display = 'none';
			form1.depsel8.style.display = 'none';

			if (form1.enviara.value == 10) {
				form1.depsel.style.display = 'none';
				form1.carpper.style.display = '';
				form1.depsel8.style.display = 'none';
			}

			if (form1.enviara.value == 9) {
				form1.depsel.style.display = '';
				form1.carpper.style.display = 'none';
				form1.depsel8.style.display = 'none';
			}

			if (form1.enviara.value == 8) {
				form1.depsel.style.display = 'none';
				form1.depsel8.style.display = '';
				form1.carpper.style.display = 'none';
			}
		}

		//  Funcion que activa el sistema de marcar o desmarcar todos los check  
		function markAll() {
			if (form1.marcartodos.checked == 1)
				for (i = 4; i < form1.elements.length; i++)
					form1.elements[i].checked = 1;
			else
				for (i = 4; i < form1.elements.length; i++)
					form1.elements[i].checked = 0;
		}

		<?php
		function tohtml($strValue)
		{
			return htmlspecialchars($strValue);
		}
		?>
	</script>

	<body>
		<?php

		/**
		 * PARA EL FUNCIONAMIENTO CORRECTO DE ESTA PAGINA SE NECESITAN UNAS VARIABLE QUE DEBEN VENIR
		 * carpeta  "Codigo de la carpeta a abrir"
		 * nomcarpeta "Nombre de la Carpeta"
		 * tipocarpeta "Tipo de Carpeta  (0,1)(Generales,Personales)"
		 * seleccionar todos los checkboxes
		 */
		$img1 = "";
		$img2 = "";
		$img3 = "";
		$img4 = "";
		$img5 = "";
		$img6 = "";
		$img7 = "";
		$img8 = "";
		$img9 = "";

		if ($ordcambio) {
			if ($ascdesc == "DESC") {
				$ascdesc = "";
				$imagen = "flechaasc.gif";
			} else {
				$ascdesc = "DESC";
				$imagen = "flechadesc.gif";
			}
		}
		if ($orno == 1) {
			$order = " d.sgd_exp_numero $ascdesc";
			$img1 = "<img src='../iconos/$imagen' border=0 alt='$data'>";
		}
		if ($orno == 2) {
			$order = " a.radi_nume_radi $ascdesc";
			$img2 = "<img src='../iconos/$imagen' border=0 alt='$data'>";
		}
		if ($orno == 3) {
			$order = " a.radi_fech_radi $ascdesc";
			$img3 = "<img src='../iconos/$imagen' border=0 alt='$data'>";
		}
		if ($orno == 4) {
			$order = " a.ra_asun $ascdesc";
			$img4 = "<img src='../iconos/$imagen' border=0 alt='$data'>";
		}
		if ($orno == 5) {
			$order = " d.sgd_exp_estado $ascdesc,a.radi_nume_radi ";
			$img5 = "<img src='../iconos/$imagen' border=0 alt='$data'>";
		}
		if ($orno == 6) {
			$order = " f.usua_nomb $ascdesc";
			$img6 = "<img src='../iconos/$imagen' border=0 alt='$data'>";
		}
		if ($orno == 7) {
			$order2 = " RADI_USUA_ARCH $ascdesc";
			$order = "a.radi_nume_radi ";
			$img5 = "<img src='../iconos/$imagen' border=0 alt='$data'>";
		}
		if ($orno == 8) {
			$order2 = " SGD_EXP_EDIFICIO $ascdesc";
			$order = "a.radi_nume_radi ";
			$img5 = "<img src='../iconos/$imagen' border=0 alt='$data'>";
		}
		if ($orno == 9) {
			$order = " f.usua_nomb $ascdesc";
			$img9 = "<img src='../iconos/$imagen' border=0 alt='$data'>";
		}
		if ($orno == 11) {
			$order = " d.sgd_exp_fech $ascdesc";
			$img11 = "<img src='../iconos/$imagen' border=0 alt='$data'>";
		}
		if ($orno == 12) {
			$order = " d.sgd_exp_fech_arch $ascdesc";
			$img12 = "<img src='../iconos/$imagen' border=0 alt='$data'>";
		}
		if ($tipo_archivo == 0) {
			$img7 = " <img src='../iconos/flechanoleidos.gif' border=0 alt='$data'> ";
		}
		if ($tipo_archivo == 1) {
			$img7 = " <img src='../iconos/flechanoleidos.gif' border=0 alt='$data'> ";
		}
		if ($tipo_archivo == 2) {
			$img7 = " <img src='../iconos/flechanoleidos.gif' border=0 alt='$data'> ";
		}
		$datosaenviar = "buscar_exp=$buscar_exp&buscar_rad=$buscar_rad&fechaf=$fechaf&tipo_carp=$tipo_carp&ascdesc=$ascdesc&orno=$orno&fechai=$fechai&fechafi=$fechafi";
		$encabezado = session_name() . "=" . session_id() . "&buscar_exp=$buscar_exp&buscar_rad=$buscar_rad&krd=$krd&fechah=$fechah&ascdesc=$ascdesc&dep_sel=$dep_sel&tipo_archivo=$tipo_archivo&nomcarpeta=$nomcarpeta&fechai=$fechai&fechafi=$fechafi&sel=$sel&Buscar=$Buscar&orno=";
		$encabezado2 = session_name() . "=" . session_id() . "&buscar_exp=$buscar_exp&buscar_rad=$buscar_rad&krd=$krd&fechah=$fechah&ascdesc=$ascdesc&dep_sel=$dep_sel&tipo_archivo=$tipo_archivo&nomcarpeta=$nomcarpeta&fechai=$fechai&fechafi=$fechafi&sel=$sel&Buscar=$Buscar&orno=";
		$fechah = date("dmy") . "_" . time("h_m_s");
		$check = 1;
		$fechaf = date("dmy") . "_" . time("hms");
		$numeroa = 0;
		$numero = 0;
		$numeros = 0;
		$numerot = 0;
		$numerop = 0;
		$numeroh = 0;
		?>
		<br>
		<?php
		/** Instruccion que realiza la consulta de radicados segun criterios
		 * Tambien observamos que se encuentra la varialbe $carpetaenviar que maneja la carpeta 11.
		 */
		$limit = "";
		$sqlfecha = $db->conn->SQLDate("d-m-Y H:i A", "a.RADI_FECH_RADI");
		$fecha_hoy = Date("Y-m-d");
		$sqlFechaHoy = $db->conn->DBDate($fecha_hoy);
		include "$ruta_raiz/include/query/expediente/queryCuerpo_exp.php";
		$rs = $db->conn->query($isql);
		$nombusuario = $rs->fields["usua_nomb"];
		$dependencianomb = $rs->fields["depe_nomb"];
		$carpeta = 200;
		$nomcarpeta = "Expedientes";
		include "../envios/paEncabeza.php";
		?>

		<div class="container-fluid">
			<table class="table table-bordered align-middle mb-3">
				<tr>
					<form name="form1"
						action="cuerpo_exp.php?<?= session_name() . '=' . session_id() . '&krd=' . $krd . '&fechah=' . $fechah . '&' . $encabezado . '&' . $orno ?>"
						method="post">

						<!-- Columna izquierda -->
						<td class="p-3">
							<div class="mb-3">
								<label class="form-label fw-semibold">Expediente</label>
								<input type="text"
									name="buscar_exp"
									value="<?= $buscar_exp ?>"
									class="form-control">
							</div>

							<div class="mb-3">
								<label class="form-label fw-semibold">Radicado</label>
								<input type="text"
									name="buscar_rad"
									value="<?= $buscar_rad ?>"
									class="form-control">
							</div>

							<div class="fw-semibold mb-2">
								Fecha Inclusión Expediente
							</div>

							<div class="d-flex flex-wrap align-items-center gap-3">
								<div>
									<small class="text-muted">Inicial</small><br>
									<?php
									if (!$fechai) $fechai = fnc_date_calcm(date('Y-m-d'), '1');
									?>
									<script>
										var dateAvailable1 = new ctlSpiffyCalendarBox(
											"dateAvailable1",
											"form1",
											"fechai",
											"btnDate1",
											"<?= $fechai ?>",
											scBTNMODE_CUSTOMBLUE
										);
									</script>
									<script>
										dateAvailable1.date = "<?= date('Y-m-d'); ?>";
										dateAvailable1.writeControl();
										dateAvailable1.dateFormat = "yyyy-MM-dd";
									</script>
								</div>

								<div>
									<small class="text-muted">Final</small><br>
									<?php
									if (!$fechafi) $fechafi = date('Y-m-d');
									?>
									<script>
										var dateAvailable2 = new ctlSpiffyCalendarBox(
											"dateAvailable2",
											"form1",
											"fechafi",
											"btnDate2",
											"<?= $fechafi ?>",
											scBTNMODE_CUSTOMBLUE
										);
									</script>
									<script>
										dateAvailable2.date = "<?= date('Y-m-d'); ?>";
										dateAvailable2.writeControl();
										dateAvailable2.dateFormat = "yyyy-MM-dd";
									</script>
								</div>
							</div>
						</td>

						<!-- Botón buscar -->
						<td class="text-center p-3" style="width: 20%;">
							<input type="submit"
								value="Buscar"
								name="Buscar"
								class="btn btn-primary w-100">
						</td>

						<!-- Dependencia -->
						<td class="p-3" style="width: 30%;">
							<?php if ($tipo_archivo == 1) { ?>
								<div class="mb-3">
									<a class="link-primary fw-semibold"
										href="../archivo/busqueda_archivo.php?<?= session_name() . '=' . session_id() . '&dep_sel=' . $dep_sel . '&krd=' . $krd . '&fechah=' . $fechah . '&' . $orno . '&adodb_next_page&nomcarpeta&tipo_archivo=' . $tipo_archivo . '&carpeta' ?>">
										Búsqueda Avanzada
									</a>
								</div>
							<?php } ?>

							<label class="form-label fw-semibold">
								Dependencia que pide el archivo del documento
							</label>
							<?php
							error_reporting(0);
							$query = "select depe_nomb,depe_codi from DEPENDENCIA ORDER BY DEPE_NOMB";
							$rs1 = $db->conn->query($query);
							print $rs1->GetMenu2(
								'dep_sel',
								$dep_sel,
								"0:--- TODAS LAS DEPENDENCIAS ---",
								false,
								"",
								"class='form-select'"
							);
							?>
						</td>

					</form>
				</tr>
			</table>

			<table class="table table-bordered align-middle">
				<tr>
					<td class="p-3">
						<span class="fw-semibold me-3">
							Listar por:
						</span>

						<!-- Por Archivar -->
						<a href="cuerpo_exp.php?<?= $encabezado . $orno ?>&tipo_archivo=0"
							class="btn btn-sm <?= ($tipo_archivo == 0) ? 'btn-primary' : 'btn-outline-secondary'; ?> me-2">
							<?php if ($tipo_archivo == 0) echo $img7; ?>
							Por archivar
						</a>

						<!-- Archivados -->
						<a href="cuerpo_exp.php?<?= $encabezado . $orno ?>&tipo_archivo=1"
							class="btn btn-sm <?= ($tipo_archivo == 1) ? 'btn-primary' : 'btn-outline-secondary'; ?> me-2">
							<?php if ($tipo_archivo == 1) echo $img7; ?>
							Archivados
						</a>

						<!-- Por Excluir -->
						<a href="cuerpo_exp.php?<?= $encabezado . $orno ?>&tipo_archivo=2"
							class="btn btn-sm <?= ($tipo_archivo == 2) ? 'btn-primary' : 'btn-outline-secondary'; ?>">
							<?php if ($tipo_archivo == 2) echo $img7; ?>
							Por excluir
						</a>
					</td>
				</tr>
			</table>

			<table class="table table-bordered table-hover align-middle mb-0">
				<thead class="table-light">
					<tr class="text-center">
						<th>
							<a href="cuerpo_exp.php?<?= $encabezado ?>2&ordcambio=1"
								class="text-decoration-none fw-semibold">
								<?= $img2 ?>
								<span class="ms-1">Radicado Entrada</span>
							</a>
						</th>

						<th style="width:18%">
							<a href="cuerpo_exp.php?<?= $encabezado2 ?>3&ordcambio=1"
								class="text-decoration-none fw-semibold">
								<?= $img3 ?>
								<span class="ms-1">Fecha Radicado</span>
							</a>
						</th>

						<th style="width:10%">
							<a href="cuerpo_exp.php?<?= $encabezado2 ?>1&ordcambio=1"
								class="text-decoration-none fw-semibold">
								<?= $img1 ?>
								<span class="ms-1">Expediente</span>
							</a>
						</th>

						<th style="width:10%">
							<a href="cuerpo_exp.php?<?= $encabezado2 ?>11&ordcambio=1"
								class="text-decoration-none fw-semibold">
								<?= $img11 ?>
								<span class="ms-1">Fecha Inclusión</span>
							</a>
						</th>

						<th style="width:20%">
							<a href="cuerpo_exp.php?<?= $encabezado2 ?>4&ordcambio=1"
								class="text-decoration-none fw-semibold">
								<?= $img4 ?>
								<span class="ms-1">Tipo Documental</span>
							</a>
						</th>

						<?php if ($tipo_archivo == 0): ?>
							<th style="width:15%">
								<a href="cuerpo_exp.php?<?= $encabezado2 ?>5&ordcambio=1"
									class="text-decoration-none fw-semibold">
									<?= $img5 ?>
									<span class="ms-1">Archivado?</span>
								</a>
							</th>
						<?php endif; ?>

						<?php if ($tipo_archivo == 1): ?>
							<th style="width:15%">
								<a href="cuerpo_exp.php?<?= $encabezado2 ?>7&ordcambio=1"
									class="text-decoration-none fw-semibold">
									<?= $img5 ?>
									<span class="ms-1">Archivado Por</span>
								</a>
							</th>
						<?php endif; ?>

						<?php if ($tipo_archivo == 2): ?>
							<th style="width:15%">
								<a href="cuerpo_exp.php?<?= $encabezado2 ?>8&ordcambio=1"
									class="text-decoration-none fw-semibold">
									<?= $img5 ?>
									<span class="ms-1">Ubicación</span>
								</a>
							</th>
						<?php endif; ?>

						<?php if ($tipo_archivo == 0): ?>
							<th style="width:15%">
								<a href="cuerpo_exp.php?<?= $encabezado2 ?>8&ordcambio=1"
									class="text-decoration-none fw-semibold">
									<?= $img12 ?>
									<span class="ms-1">Ubicación</span>
								</a>
							</th>
						<?php endif; ?>

						<?php if ($tipo_archivo == 1 || $tipo_archivo == 2): ?>
							<th style="width:15%">
								<a href="cuerpo_exp.php?<?= $encabezado2 ?>12&ordcambio=1"
									class="text-decoration-none fw-semibold">
									<?= $img12 ?>
									<span class="ms-1">Fecha de Archivo</span>
								</a>
							</th>
						<?php endif; ?>

						<?php if ($tipo_archivo == 2): ?>
							<th style="width:10%" class="text-danger fw-semibold">
								EXCLUIR
							</th>
						<?php endif; ?>

					</tr>
				</thead>
				<tbody>
					<?
					if ($Buscar == 'Buscar') {
						// $rs->debug=true;
						$row = array();
						$i = 1;
						$ki = 0;
						// Comienza el ciclo para mostrar los documentos de la carpeta predeterminada.
						$registro = $pagina * 20;
						while (!$rs->EOF) {
							if ($ki >= $registro && $ki < ($registro + 20)) {
								$data = trim($rs->fields["RADI_NUME_RADI"]);
								$numdata =  trim($rs->fields["CARP_CODI"]);
								$plg_codi = $rs->fields["PLG_CODI"];
								$plt_codi = $rs->fields["PLT_CODI"];
								$num_expediente = $rs->fields["SGD_EXP_NUMERO"];
								$imagen_rad = $rs->fields["RADI_PATH"];
								$usuario_actual = $rs->fields["USUA_NOMB"];
								$dependencia_actual = $rs->fields["DEPE_NOMB"];
								$estado = $rs->fields["SGD_EXP_ESTADO"];
								$fecha_archivo = $rs->fields["SGD_EXP_FECH_ARCH"];
								$fecha_clasificacion = $rs->fields["SGD_EXP_FECH"];
								include "$ruta_raiz/include/query/expediente/queryCuerpo_exp.php";
								$rse = $db->query($sqle);
								$estan = $rse->fields["SGD_EXP_ESTANTE"];
								$entre = $rse->fields["SGD_EXP_ENTREPA"];
								$caja = $rse->fields["SGD_EXP_CAJA"];
								$piso = $rse->fields["SGD_EXP_ISLA"];
								$edifi = $rse->fields["SGD_EXP_EDIFICIO"];
								$zona = $rse->fields["SGD_EXP_UFISICA"];
								$carro = $rse->fields["SGD_EXP_CARRO"];
								$usua_arch = $rse->fields["RADI_USUA_ARCH"];
								include "$ruta_raiz/include/query/expediente/queryCuerpo_exp.php";
								$rs1 = $db->conn->Execute($tm);
								$ed = $rs1->fields['SGD_EIT_SIGLA'];
								$rs7 = $db->conn->Execute($tm6);
								$pi = $rs7->fields['SGD_EIT_SIGLA'];

								if ($edifi == "" && $estan == "" && $entre == "" && $caja == "" && $carro == "" && $zona == "") {
									$ubicacion = "";
								} else {
									$ubicacion = $ed . "-" . $pi;
									$rs5 = $db->conn->Execute($tm4);
									if ($zona != "") {
										$zo = $rs5->fields['SGD_EIT_SIGLA'];
										$ubicacion .= "-" . $zo;
									}
									$rs4 = $db->conn->Execute($tm3);
									if ($carro != "") {
										$ca = $rs4->fields['SGD_EIT_SIGLA'];
										$ubicacion .= "-" . $ca;
									}
									$rs2 = $db->conn->Execute($tm1);
									if ($estan != "") {
										$es = $rs2->fields['SGD_EIT_SIGLA'];
										$ubicacion .= "-" . $es;
									}
									$rs3 = $db->conn->Execute($tm2);
									if ($entre != "") {
										$et = $rs3->fields['SGD_EIT_SIGLA'];
										$ubicacion .= "-" . $et;
									}
									$rs6 = $db->conn->Execute($tm5);
									if ($caja != "") {
										$cj = $rs6->fields['SGD_EIT_SIGLA'];
										$ubicacion .= "-" . $cj;
									}
								}
								include "$ruta_raiz/include/query/expediente/queryCuerpo_exp.php";
								$rsd = $db->query($sqlr);
								$tipoDoc = $rsd->fields['SGD_TPR_DESCRIP'];

								if (strlen($data) <= $lnr) {
									//Se trata de un Radicad
									$resulVali = $verLinkArchivo->valPermisoRadi($data);
									$verImg = $resulVali['verImg'];
								} else {
									//Se trata de un anexo
									$resulValiA = $verLinkArchivo->valPermisoAnex($data);
									$verImg = $resulValiA['verImg'];
								}

								/**
								 * Modificado: 22-Septiembre-2006 Supersolidaria
								 * Ajuste para determinar si un radicado hab� sido archivado antes de ser excluido de
								 * un expediente.
								 */
								if ($estado == 0) {
									$estado_nomb = "No";
								} elseif ($estado == 2 && $fecha_archivo != "") {
									$estado_nomb = "Si";
								} elseif ($estado == 2 && $fecha_archivo == "") {
									$estado_nomb = "No";
								} else {
									$estado_nomb = "Si";
								}
								if ($plt_codi == 2) {
									$img_estado = "<img src='../imagenes/docRadicado.gif'  border=0>";
								}
								if ($plt_codi == 3) {
									$img_estado = "<img src='../imagenes/docImpreso.gif'  border=0>";
								}
								if ($plt_codi == 4) {
									$img_estado = "<img src='../imagenes/docEnviado.gif ' border=0>";
								}
								if ($rs->fields["SGD_TPR_CODIGO"] == 9999) {
									if ($plt_codi == 2) {
										$img_estado = "<img src=../imagenes/docRecibido.gif  border=0>";
									}
									if ($plt_codi == 2) {
										$img_estado = "<img src=../imagenes/docRadicado.gif  border=0>";
									}
									if ($plt_codi == 3) {
										$img_estado = "<img src=../imagenes/docImpreso.gif  border=0>";
									}
									if ($plt_codi == 4) {
										$img_estado = "<img src=../imagenes/docEnviado.gif  border=0>";
									}
									$dep_radicado = substr($rs->fields["RADI_NUME_RADI"], 4, 3);
									$ano_radicado = substr($rs->fields["RADI_NUME_RADI"], 0, 4);
									$ref_pdf = "bodega/$ano_radicado/$dep_radicado/docs/$ref_pdf";
									$tipo_sal = "Archivo";
									$ref_pdf_salida = "<a href='../bodega/$ano_radicado/$dep_radicado/docs/$ref_pdf' alt='Radicado de Salida $rad_salida'>$img_estado</a>";
								} else {
									$tipo_sal = "Plantilla";
									$ref_pdf_salida = "<a href='../$ref_pdf' alt='Radicado de Salida $rad_salida'>$img_estado</a>";
								}

								//$ref_pdf_salida = "<a href='imprimir_pdf_frame?".session_name()."=".session_id() . "&ref_pdf=$ref_pdf&numrad=$numrad'>$img_estado </a>";

								if ($data == "") $data = "NULL";
								error_reporting(0);
								$numerot = $row1["num"];
								if ($estado == 0) {
									$leido = "";
								} else {
									$leido = "";
								}
								if ($i == 1) {
									$leido = "listado1";
									$i = 2;
								} else {
									$leido = "listado2";
									$i = 1;
								}
								/**
								 * Modificado: 22-Septiembre-2006 Supersolidaria
								 * Ajuste para identifiar con otro color los radicados excluidos de un expediene.
								 */
								// Por Archivar
								if ($estado == 0) {
									$class = "leidos";
								} elseif ($estado == 1) {
									$class = "no_leidos";
								} elseif ($estado == 2) {
									// Por Excluir
									$class = "porExcluir";
								}
								if ($estado == 2 && $estado_nomb == "Si") {
									/**
									 * Invocado por una funcion javascript (funlinkArchivo(numrad,rutaRaiz))
									 * @author Liliana Gomez Velasquez
									 * @since 10 de noviembre 2009
									 * @category acceso a documentoss
									 */

									if ($rs->fields["RADI_PATH"] <>  "") {
										if ($verImg == "SI") {
											$urlimagen = "<a class=\"vinculos\" href=\"#2\" onclick=\"funlinkArchivo('$data','$ruta_raiz');\">$data</span></a>";
										} else {
											$urlimagen = "<a class='vinculos' href='javascript:noPermiso()' > $data</span></a>";
										}
									} else {
										$urlimagen = "<a><span class='" . $class . "'>$data</span></a>";
									}
					?>
									<tr class='<?= $leido ?>'>
										<?
										$radi_tipo_deri = $rs->fields["RADI_TIPO_DERI"];
										$radi_nume_deri = $rs->fields["RADI_NUME_DERI"];
										?>
										<td class='<?= $leido ?>' align="right" width="12%">
											<span class='<?php print $class; ?>'><?= $urlimagen ?></span>
											<?
											//		$isql3 ="select to_char(HIST_FECH,'DD/MM/YY HH12:MI:SSam')as HIST_FECH,HIST_FECH AS HIST_FECH1,HIST_OBSE from hist_eventos where radi_nume_radi='$data' order by HIST_FECH1 desc ";
											$radi_nomb = $rs->fields["NOMBRES"];
											?>
										</td>
										<td class='<?= $leido ?>' width="10%" align="center"><? $ruta_raiz = ".."; ?>
											<span class='<?php print $class; ?>'>
												<a href='../verradicado.php?<?= $encabezado . "&num_expediente=$num_expediente&verrad=$data&carpeta_per=0&carpeta=8&nombcarpeta=Expedientes" ?>'>
													<span class='<?php print $class; ?>'>
														<?= $rs->fields["FECHA"] ?>
													</span>
												</a>
											</span>
										</td>
										<td class='<?= $leido ?>' width="18%">
											<span class='<?php print $class; ?>'>
												<? if ($verImg == "SI") { ?>
													<a href='../verradicado.php?<?= $encabezado . "&num_expediente=$num_expediente&verrad=$data&carpeta_per=0&carpeta=8&nombcarpeta=Expedientes" ?>'> </a>
											</span>
										<? } else {
													echo "variable" ?>
											<a class='vinculos' href='javascript:noPermiso()'> </a></span>
										<? } ?>
										<span class='<?php print $class; ?>'>
											<?= $num_expediente ?></span>
										</td>
										<td class='<?= $leido ?>' width="20%"> <span class='<?php print $class; ?>'><?= $fecha_clasificacion ?></span></td>
										<td class='<?= $leido ?>' width="20%"> <span class='<?php print $class; ?>'><?= $tipoDoc ?> </span>
										</td>
										<td class='<?= $leido ?>' width="20%">
											<span class='<?php print $class; ?>'>
												<?= $ubicacion ?>
											</span>
										</td>
										<td class='<?= $leido ?>' width="15%" align="center">
											<center>
												<a href='../archivo/datos_expediente.php?<?= $encabezado . "&num_expediente=$num_expediente&ent=1&nurad=$data" ?>' class='vinculos'>
													<span class='<?php print $class; ?>'><?= $fecha_archivo ?></span>
												</a>
											</center>
										</td>
										<td class='<?= $leido ?>' width="20%">
											<span class='<?php print $class; ?>'>
												<div align="center">
													<!-- <a href="cuerpo_exp.php?radExcluido=<?php print $data; ?>&expedienteExcluir=<?php print $num_expediente; ?>&excluir=1"> -->
													<a href="javascript:confirmaExcluir( '<?php print $data; ?>', '<?php print $num_expediente; ?>' );">
														<img src="<?php print $ruta_raiz; ?>/iconos/rad_excluido.png" border="0" height="14" width="25">
													</a>
												</div>
											</span>
										</td>
										<?php
										if ($check <= 20) {
											$check = $check + 1;
										}
									} elseif ($estado == 1) {
										if ($rs->fields["RADI_PATH"] <>  "") {
											if ($verImg == "SI") {
												$urlimagen = "<a class=\"vinculos\" href=\"#2\" onclick=\"funlinkArchivo('$data','$ruta_raiz');\">$data</span></a>";
											} else {
												$urlimagen = "<a class='vinculos' href='javascript:noPermiso()' > $data</span></a>";
											}
										} else {
											$urlimagen = "<a><span class='" . $class . "'>$data</span></a>";
										}
										?>
									<tr class='<?= $leido ?>'>
										<?
										$radi_tipo_deri = $rs->fields["RADI_TIPO_DERI"];
										$radi_nume_deri = $rs->fields["RADI_NUME_DERI"];
										?>
										<td class='<?= $leido ?>' align="right" width="12%"><span class='<?php print $class; ?>'><?= $urlimagen ?></span>
											<? $radi_nomb = $rs->fields["NOMBRES"]; ?>
										</td>
										<td class='<?= $leido ?>' width="10%" align="center"><? $ruta_raiz = ".."; ?>
											<span class='<?php print $class; ?>'>
												<? if ($verImg == "SI") { ?>
													<a href='../verradicado.php?<?= $encabezado . "&num_expediente=$num_expediente&verrad=$data&carpeta_per=0&carpeta=8&nombcarpeta=Expedientes" ?>'>
													<? } else {
													echo "variable" ?>
														<a class='vinculos' href='javascript:noPermiso()'>
														<? } ?>
														<span class='<?php print $class; ?>'>
															<?= $rs->fields["FECHA"] ?>
														</span>
														</a>
											</span>
										</td>
										<td class='<?= $leido ?>' width="18%">
											<span class='<?php print $class; ?>'>
												<? if ($verImg == "SI") { ?>
													<a href='../verradicado.php?<?= $encabezado . "&num_expediente=$num_expediente&verrad=$data&carpeta_per=0&carpeta=8&nombcarpeta=Expedientes" ?>'> </a></span>
										<? } else {
													echo "variable" ?>
											<a class='vinculos' href='javascript:noPermiso()'> </a></span>
										<? } ?>
										<span class='<?php print $class; ?>'>
											<?= $num_expediente ?>
										</span>
										</td>
										<td class='<?= $leido ?>' width="20%"><span class='<?php print $class; ?>'><?= $fecha_clasificacion ?></span></td>
										<td class='<?= $leido ?>' width="20%"><span class='<?php print $class; ?>'><?= $tipoDoc ?> </span></td>
										<td class='<?= $leido ?>' width="20%">
											<span class='<?php print $class; ?>'>
												<?= $usua_arch ?>
											</span>
										</td>
										<td class='<?= $leido ?>' width="15%" align="center">
											<center>
												<a href='../archivo/datos_expediente.php?<?= $encabezado . "&num_expediente=$num_expediente&ent=1&nurad=$data&car=$car" ?>' class='vinculos'>
													<span class='<?php print $class; ?>'><?= $fecha_archivo ?></span>
												</a>
											</center>
										</td>
										<?php
										if ($check <= 20) {
											$check = $check + 1;
										}
									} elseif ($estado == 0) {
										if ($rs->fields["RADI_PATH"] <>  "") {
											if ($verImg == "SI") {
												$urlimagen = "<a class=\"vinculos\" href=\"#2\" onclick=\"funlinkArchivo('$data','$ruta_raiz');\">$data</span></a>";
											} else {
												$urlimagen = "<a class='vinculos' href='javascript:noPermiso()' > $data</span></a>";
											}
										} else {
											$urlimagen = "<a><span class='" . $class . "'>$data</span></a>";
										}
										?>
									<tr class='<?= $leido ?>'>
										<?
										$radi_tipo_deri = $rs->fields["RADI_TIPO_DERI"];
										$radi_nume_deri = $rs->fields["RADI_NUME_DERI"];
										?>
										<td class='<?= $leido ?>' align="right" width="12%"><span class='<?php print $class; ?>'><?= $urlimagen ?></span>
											<?
											$radi_nomb = $rs->fields["NOMBRES"];
											?>
										</td>
										<td class='<?= $leido ?>' width="10%" align="center"><? $ruta_raiz = ".."; ?>
											<span class='<?php print $class; ?>'>
												<? if ($verImg == "SI") { ?>
													<a href='../verradicado.php?<?= $encabezado . "&num_expediente=$num_expediente&verrad=$data&carpeta_per=0&carpeta=8&nombcarpeta=Expedientes" ?>'>
													<? } else { ?>
														<a class='vinculos' href='javascript:noPermiso()'>
														<? } ?>
														<span class='<?php print $class; ?>'>
															<?= $rs->fields["FECHA"] ?>
														</span>
														</a>
											</span>
										</td>
										<td class='<?= $leido ?>' width="18%">
											<span class='<?php print $class; ?>'>
												<? if ($verImg == "SI") { ?>
													<a href='../verradicado.php?<?= $encabezado . "&num_expediente=$num_expediente&verrad=$data&carpeta_per=0&carpeta=8&nombcarpeta=Expedientes" ?>'> </a></span>
										<? } else { ?>
											<a class='vinculos' href='javascript:noPermiso()'> </a></span>
										<? } ?>
										<span class='<?php print $class; ?>'>
											<?= $num_expediente ?>
										</span>
										</td>
										<td class='<?= $leido ?>' width="20%"> <span class='<?php print $class; ?>'><?= $fecha_clasificacion ?></span></td>
										<td class='<?= $leido ?>' width="20%"> <span class='<?php print $class; ?>'><?= $tipoDoc ?> </span></td>
										<td class='<?= $leido ?>' width="15%" align="center">
											<center>
												<a href='../archivo/datos_expediente.php?<?= $encabezado . "&num_expediente=$num_expediente&ent=1&nurad=$data" ?>' class='vinculos'>
													<span class='<?php print $class; ?>'><?= $estado_nomb ?></span>
												</a>
											</center>
										</td>
										<td class='<?= $leido ?>' width="20%">
											<span class='<?php print $class; ?>'>
												<?= $ubicacion ?> </span>
										</td>
									<?
										if ($check <= 20) {
											$check = $check + 1;
										}
									}
									?>
									</tr>
							<?
							}
							$ki = $ki + 1;
							$rs->MoveNext();
						}
							?>
				</tbody>
			</table>

			<table class="table table-bordered align-middle w-100 margin-botton-table">
				<tr>
					<td class="text-center p-3">

						<?php
						// Se calcula el numero de páginas
						$rs = $db->query($isqlCount);
						$numerot = $rs->fields["CONTADOR"];
						$paginas = ($numerot / 20);

						if (intval($paginas) <= $paginas) {
							$paginas = $paginas;
						} else {
							$paginas = $paginas - 1;
						}
						?>

						<nav aria-label="Paginación de resultados">
							<ul class="pagination justify-content-center mb-0">

								<?php
								// Se imprime el número de páginas
								for ($ii = 0; $ii < $paginas; $ii++) {

									$isActive = ($pagina == $ii) ? 'active' : '';
									$aria     = ($pagina == $ii) ? 'aria-current="page"' : '';
								?>
									<li class="page-item <?= $isActive; ?>">
										<a class="page-link"
											<?= $aria; ?>
											href="cuerpo_exp.php?pagina=<?= $ii; ?>&<?= $encabezado2 . $orno; ?>">
											<?= ($ii + 1); ?>
										</a>
									</li>
								<?php
								}
								?>
							</ul>
						</nav>
						<input type="hidden" name="check" value="<?= $check; ?>">
					<?php
					}
					?>
					</td>
				</tr>
			</table>
		</div>
	</body>

</html>
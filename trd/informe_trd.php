<?
session_start();
/*
 * Lista Subseries documentales
 * @autor Jairo Losada
 * @fecha 2009/06 Modificacion Variables Globales.
 */
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;
$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
/*************************************************************************************/
/* ORFEO GPL:Sistema de Gestion Documental		http://www.orfeogpl.org	     */
/*	Idea Original de la SUPERINTENDENCIA DE SERVICIOS PUBLICOS DOMICILIARIOS     */
/*				COLOMBIA TEL. (57) (1) 6913005  orfeogpl@gmail.com   */
/* ===========================                                                       */
/*                                                                                   */
/* Este programa es software libre. usted puede redistribuirlo y/o modificarlo       */
/* bajo los terminos de la licencia GNU General Public publicada por                 */
/* la "Free Software Foundation"; Licencia version 2. 			             */
/*                                                                                   */
/* Copyright (c) 2005 por :	  	  	                                 f    */
/* SSPS "Superintendencia de Servicios Publicos Domiciliarios"                       */
/*   Jairo Hernan Losada  jlosada@gmail.com                Desarrollador             */
/*   Sixto Angel Pinz�n L�pez --- angel.pinzon@gmail.com   Desarrollador             */
/* C.R.A.  "COMISION DE REGULACION DE AGUAS Y SANEAMIENTO AMBIENTAL"                 */ 
/*   Liliana Gomez        lgomezv@gmail.com                Desarrolladora            */
/*   Lucia Ojeda          lojedaster@gmail.com             Desarrolladora            */
/* D.N.P. "Departamento Nacional de Planeaci�n"                                      */
/*   Hollman Ladino       hollmanlp@gmail.com                Desarrollador             */
/*                                                                                   */
/* Colocar desde esta lInea las Modificaciones Realizadas Luego de la Version 3.5    */
/*  Nombre Desarrollador   Correo     Fecha   Modificacion                           */
/*************************************************************************************/
error_reporting(7);
$anoActual = date("Y");
if (!$fecha_busq) $fecha_busq = date("Y-m-d");
if (!$fecha_busq2) $fecha_busq2 = date("Y-m-d");
$ruta_raiz = "..";
if (!$_SESSION['dependencia'] and !$_SESSION['depe_codi_territorial'])	include "../rec_session.php";
$entidad = $_SESSION["entidad"];
$indiTRD = $_SESSION["indiTRD"];
#echo "indicador " . $indiTRD;
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler("$ruta_raiz");
//$db->conn->debug=true;
if (!defined('ADODB_FETCH_ASSOC'))	define('ADODB_FETCH_ASSOC', 2);
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
?>

<head>
	<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
	<!--<link rel="stylesheet" href="../estilos/orfeo.css">-->
	<link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
</head>

<body>
	<div id="spiffycalendar" class="text"></div>

	<!-- Encabezado -->
	<div class="card shadow-sm border-0 my-4">
		<div class="card-header bg-orfeo text-white text-center py-3">
			<h5 class="m-0">Informe Tablas de Retención Documental</h5>
		</div>

		<div class="card-body">
			<form name="inf_trd"
				action="../trd/informe_trd.php?<?= session_name() . '=' . session_id() . "&krd=$krd&fecha_h=$fechah" ?>"
				method="post">

				<!-- Dependencia -->
				<div class="mb-4">
					<label class="form-label fw-semibold">Dependencia</label>
					<div>
						<?php
						error_reporting(7);
						$ss_RADI_DEPE_ACTUDisplayValue = "--- TODAS LAS DEPENDENCIAS ---";
						$valor = 0;
						include "$ruta_raiz/include/query/devolucion/querydependencia.php";

						$sqlD = "select $sqlConcat ,depe_codi 
                            from dependencia 
                            where depe_codi >= 10000 and depe_estado = 1
                            order by depe_codi";

						$rsDep = $db->conn->Execute($sqlD);

						print $rsDep->GetMenu2(
							"dep_sel",
							"$dep_sel",
							"$valor:$ss_RADI_DEPE_ACTUDisplayValue",
							false,
							0,
							"onChange='submit();' class=\"form-select\""
						);
						?>
					</div>
				</div>

				<!-- Botón generar -->
				<div class="text-center">
					<input type="submit" name="generar_informe" value='Generar Informe' class="btn btn-primary px-4">
				</div>

				<div class="margin-botton-table">
					<?php
					if ($_POST['generar_informe']) {

						if ($_POST['dep_sel'] == 0) {
							$where_depe = '';
						} else {
							if ($entidad == "SSPD") {

								if ($dep_sel == "527" || $dep_sel == "810" || $dep_sel == "820" || $dep_sel == "830" || $dep_sel == "840" || $dep_sel == "850") {
									$where_depe = " AND ( ( m.depe_codi = " . $_POST['dep_sel'] . "  
                                                AND m.SGD_SRD_CODIGO = 15) OR (m.depe_codi = " . $_POST['dep_sel'] . " 
                                                AND m.SGD_SRD_CODIGO <> 15))";
								} else {
									$where_depe = " AND m.depe_codi = " . $_POST['dep_sel'] . "  AND m.SGD_SRD_CODIGO <> 15 ";
								}
							} else {
								$where_depe = " and m.depe_codi = " . $_POST['dep_sel'];
							}
						}

						$generar_informe = 'generar_informe';
						error_reporting(7);
						$guion = "' '";

						include "$ruta_raiz/include/query/trd/queryinforme_trd.php";

						$order_isql = " order by m.depe_codi, m.sgd_srd_codigo, m.sgd_sbrd_codigo, m.sgd_tpr_codigo ";

						$query_t = $query . $where_depe . $order_isql;

						$ruta_raiz = "..";

						$rs = $db->query($query_t);

						echo "<hr>";
					?>
						<div class="table-responsive">
							<table class="table table-bordered table-striped align-middle">

								<?php
								$nSRD_ant = "";
								$nSBRD_ant = "";
								$openTR = "";
								?>

								<thead class="table-primary text-center">
									<tr>
										<th colspan="3">Código</th>
										<th rowspan="2">Series y Tipos Documentales</th>
										<th colspan="2">Retención (Años)</th>
										<th colspan="4">Disposición Final</th>

										<?php if ($indiTRD != "SI") { ?>
											<th colspan="3">Soporte</th>
											<th rowspan="2" style="width:30%">Procedimiento</th>
										<?php } else { ?>
											<th rowspan="2" style="width:50%">Procedimiento</th>
										<?php } ?>
									</tr>

									<tr class="fw-semibold">
										<th>D</th>
										<th>S</th>
										<th>Sb</th>
										<th>AG</th>
										<th>AC</th>
										<th>CT</th>
										<th>S</th>
										<th>D/M</th>
										<th>E</th>

										<?php if ($indiTRD != "SI") { ?>
											<th>P</th>
											<th>EL</th>
											<th>O</th>
										<?php } ?>
									</tr>
								</thead>

								<tbody>
									<?php
									while (!$rs->EOF) {

										$nSRD = strtoupper($rs->fields['SGD_SRD_DESCRIP']);
										$depTDR = $rs->fields['DEPE_CODI'];
										$nSBRD = $rs->fields['SGD_SBRD_DESCRIP'];
										$cSRD = $rs->fields['SGD_SRD_CODIGO'];
										$cSBRD = $rs->fields['SGD_SBRD_CODIGO'];
										$nTDoc = ucfirst($db->fullLower($rs->fields['SGD_TPR_DESCRIP']));

										if ($nSRD != $nSRD_ant) {
											if ($openTR == "Si") {
												echo $colFinales;
											}
											echo "<tr class='table-light'>
                                                <td>$depTDR</td>
                                                <td>$cSRD</td>
                                                <td></td>
                                                <td colspan='11'>$pSRD</td>
                                            </tr>";
											$openTR = "No";
										}

										if ($nSBRD == $nSBRD_ant) {
											echo "<font size=2>- $nTDoc</font><br>";
										} else {

											if ($openTR == "Si") {
												echo $colFinales;
											}

											$conservCT = $conservS = $conservI = $conservE = "&nbsp;";

											if ($indiTRD != "SI") {
												$soporteP = $soporteEl = $soporteO = "&nbsp;";
											}

											$conserv = strtoupper(substr(trim($rs->fields['DISPOSICION']), 0, 1));
											$soporte = strtoupper(substr(trim($rs->fields['SGD_SBRD_SOPORTE']), 0, 1));

											if ($conserv == "C") $conservCT = "X";
											if ($conserv == "M") $conservS = "X";
											if ($conserv == "I") $conservI = "X";
											if ($conserv == "E") $conservE = "X";

											if ($soporte == "P") $soporteP = "X";
											if ($soporte == "E") $soporteEl = "X";
											if ($soporte == "O") $soporteO = "X";

											$tiemag = $rs->fields['SGD_SBRD_TIEMAG'];
											$tiemac = $rs->fields['SGD_SBRD_TIEMAC'];
											$nObservacion = $rs->fields['SGD_SBRD_PROCEDI'];

											echo "<tr class='align-top'>
                                            <td>$depTDR</td>
                                            <td>$cSRD</td>
                                            <td>$cSBRD</td>
                                            <td><b>$nSBRD</b><br>- $nTDoc</td>";

											$conservacion =
												"<td>$tiemag</td>
                                            <td>$tiemac</td>
                                            <td>$conservCT</td>
                                            <td>$conservS</td>
                                            <td>$conservI</td>
                                            <td>$conservE</td>";

											if ($indiTRD != "SI") {
												$soporte =
													"<td>$soporteP</td>
                                                <td>$soporteEl</td>
                                                <td>$soporteO</td>";
											} else {
												$soporte = "";
											}

											$colFinales =
												"$conservacion
                                            $soporte
                                            <td>$nObservacion</td>";

											$openTR = "Si";
										}

										$nSRD_ant = $nSRD;
										$nSBRD_ant = $nSBRD;

										$rs->MoveNext();
									}
									if ($openTR == "Si") echo $colFinales;
									?>
								</tbody>
							</table>
						</div>
					<?php } ?>

					<hr>
					<?php
					$xsql = serialize($query_t);
					$_SESSION['xsql'] = $xsql;
					echo "<a style='border:0px' href='../adodb/adodb-doc.inc.php?" . session_name() . "=" . session_id() . "' target='_blank'><img src='../adodb/compfile.png' width='40' heigth='    40' border='0' ></a>";
					echo "<a href='../adodb/adodb-xls.inc.php?" . session_name() . "=" . session_id() . "' target='_blank'><img src='../adodb/spreadsheet.png' width='40' heigth='40' border='0'></a>";
					?>
					<hr>
				</div>
			</form>
		</div>
	</div>
</body>

</html>
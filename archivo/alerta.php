<?
session_start();
$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
if (!$ruta_raiz) $ruta_raiz = "..";
extract($_REQUEST);
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
$db = new ConnectionHandler("$ruta_raiz");
$encabezadol = "$PHP_SELF?" . session_name() . "=" . session_id() . "&num_exp=$num_exp";

$db = new ConnectionHandler("$ruta_raiz");
$encabezado = session_name() . "=" . session_id() . "&krd=$krd&nomcarpeta=$nomcarpetai&dep_sel=$dep_sel&codserie=$codserie&tsub=$tsub";
$flds_desde_ano = $s_desde_ano;
/*
 * Modificacion acceso a documentos
 * @author Liliana Gomez Velasquez
 * @since octubre 7 2009
 */
include_once "$ruta_raiz/tx/verLinkArchivo.php";
$verLinkArchivo = new verLinkArchivo($db);

?>
<html>

<head>
	<title>Alerta Archivo</title>
	<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
	<link rel="stylesheet" href="../estilos/orfeo.css">
	<link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
	<script language="JavaScript" src="../js/spiffyCal/spiffyCal_v2_1.js">
	</script>
	<style>
		#spiffycalendar {
			z-index: 1;
		}
	</style>
</head>

<body bgcolor="#FFFFFF">
	<div id="spiffycalendar" class="text"></div>

	<?php include_once "$ruta_raiz/js/funtionImage.php"; ?>

	<form name=alerta action="alerta.php?<?= $encabezado ?>" method='get'>
		<?
		function fnc_date_calc($this_date, $num_years)
		{
			$my_time = strtotime($this_date); //converts date string to UNIX timestamp
			$timestamp = $my_time + ($num_years * 31557600); //calculates # of years passed ($num_years) * # seconds in a day (31557600)
			$return_date = date("Y-m-d", $timestamp);  //puts the UNIX timestamp back into string format
			return $return_date; //exit function and return string
		} //end of function

		function fnc_date_calcd($this_date, $num_days)
		{
			$my_time = strtotime($this_date); //converts date string to UNIX timestamp
			$timestamp = $my_time + ($num_days * 86400); //calculates # of days passed ($num_days) * # seconds in a day (86400)
			$return_date = date("Y-m-d", $timestamp);  //puts the UNIX timestamp back into string format
			return $return_date; //exit function and return string
		} //end of function

		if (!$exp_fechaIni) {
			$exp_fechaIni = fnc_date_calcd(date("Y-m-d"), "-15");
		}
		if (!$exp_fechaFin) {
			$exp_fechaFin = date("Y-m-d");
		}
		?>

		<div class="container my-4">
			<div class="row justify-content-center">
				<div class="col-12">

					<div class="card shadow-sm">
						<div class="card-header bg-orfeo text-center fw-bold">
							<h2 class="text-white">
								LOS SIGUIENTES RADICADOS COMENZARON EL TIEMPO EN ARCHIVO DE GESTIÓN
							</h2>
						</div>

						<div class="card-body">
							<table class="table table-bordered align-middle mb-0 borde_tab">
								<tbody>
									<tr>
										<td class="titulos5 fw-semibold text-end" style="width:25%">Dependencia</td>
										<td colspan="3">
											<?php
											$query = "select depe_nomb,depe_codi from DEPENDENCIA ORDER BY DEPE_NOMB";
											$rs1 = $db->conn->query($query);
											print $rs1->GetMenu2(
												'dep_sel',
												$dep_sel,
												"0:--- TODAS LAS DEPENDENCIAS ---",
												false,
												"",
												"class='select form-select'"
											);
											?>
										</td>
									</tr>

									<tr>
										<td class="titulos5 fw-semibold text-end">Serie</td>
										<td colspan="3">
											<?php
											if (!$tdoc) $tdoc = 0;
											if (!$codserie) $codserie = 0;
											if (!$tsub) $tsub = 0;
											$fechah = date("dmy") . " " . time("h_m_s");
											$fecha_hoy = Date("Y-m-d");
											$sqlFechaHoy = $db->conn->DBDate($fecha_hoy);
											$check = 1;
											$fechaf = date("dmy") . "_" . time("hms");
											$num_car = 4;
											$nomb_varc = "s.sgd_srd_codigo";
											$nomb_varde = "s.sgd_srd_descrip";
											include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";
											include "$ruta_raiz/include/query/archivo/queryInventario.php";

											$rsD = $db->conn->query($querySerie);
											$comentarioDev = "Muestra las Series Docuementales";
											include "$ruta_raiz/include/tx/ComentarioTx.php";

											print $rsD->GetMenu2(
												"codserie",
												$codserie,
												"0:-- Seleccione --",
												false,
												"",
												"onChange='submit()' class='select form-select'"
											);
											?>
										</td>
									</tr>

									<tr>
										<td class="titulos5 fw-semibold text-end">Subserie</td>
										<td colspan="3">
											<?php
											$nomb_varc = "su.sgd_sbrd_codigo";
											$nomb_varde = "su.sgd_sbrd_descrip";
											include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";
											include "$ruta_raiz/include/query/archivo/queryInventario.php";

											$rsSub = $db->conn->query($querySub);
											include "$ruta_raiz/include/tx/ComentarioTx.php";

											print $rsSub->GetMenu2(
												"tsub",
												$tsub,
												"0:-- Seleccione --",
												false,
												"",
												"onChange='submit()' class='select form-select'"
											);

											if (!$codiSRD) {
												$codiSRD = $codserie;
												$codiSBRD = $tsub;
											}
											if ($codiSRD < 10 && $codiSRD != 0) $codiSRD = "0" . $codiSRD;
											if ($codiSBRD < 10 && $codiSBRD != 0) $codiSBRD = "0" . $codiSBRD;
											if ($dep_sel == 0) $dep_sel2 = "";
											else $dep_sel2 = $dep_sel;
											if ($codiSRD == 0) $codiSRD2 = "";
											else $codiSRD2 = $codiSRD;
											if ($codiSBRD == 0) $codiSBRD2 = "";
											else $codiSBRD2 = $codiSBRD;
											$expe = $dep_sel2 . $codiSRD2 . $codiSBRD2;
											?>
										</td>
									</tr>

									<tr>
										<td class="titulos5 fw-semibold text-end">Fecha Inicial</td>
										<td>
											<script>
												var dateAvailable1 = new ctlSpiffyCalendarBox(
													"dateAvailable1",
													"alerta",
													"exp_fechaIni",
													"btnDate1",
													"<?= $exp_fechaIni ?>",
													scBTNMODE_CUSTOMBLUE
												);
												dateAvailable1.writeControl();
												dateAvailable1.dateFormat = "yyyy-MM-dd";
											</script>
										</td>

										<td class="titulos5 fw-semibold text-end">Fecha Final</td>
										<td>
											<script>
												var dateAvailable2 = new ctlSpiffyCalendarBox(
													"dateAvailable2",
													"alerta",
													"exp_fechaFin",
													"btnDate2",
													"<?= $exp_fechaFin ?>",
													scBTNMODE_CUSTOMBLUE
												);
												dateAvailable2.writeControl();
												dateAvailable2.dateFormat = "yyyy-MM-dd";
											</script>
										</td>
									</tr>

									<tr>
										<td colspan="2" class="text-end">
											<input type="submit" name="Traer" value="Traer" class="btn btn-primary px-4 botones_funcion">
										</td>
										<td colspan="2" class="text-start">
											<input name="Regresar" type="button" class="btn btn-outline-secondary px-4 botones_funcion" onClick="window.back();" value="Regresar">
										</td>
									</tr>

									<?
									if ($Traer) {
									?>
										<tr>
											<TD class=titulos2 align="center" colspan="3">
												LOS SIGUIENTES RADICADOS COMENZARON EL TIEMPO EN ARCHIVO CENTRAL HOY:
											</td>
										</tr>
										<tr>
											<td class="titulos3" align="center">Expediente</td>
											<td class="titulos3" align="center">Radicado</td>
											<td class="titulos3" align="center">Fecha Fin</td>
										</tr>
										<?
										$rs = $db->query($query);
										$cont = 0;
										while (!$rs->EOF) {
											$exp = $rs->fields["SGD_EXP_NUMERO"];
											$rad = $rs->fields["RADI_NUME_RADI"];
											$fechfin = $rs->fields["SGD_EXP_FECHFIN"];
											$arch = $rs->fields["SGD_EXP_ARCHIVO"];
											$rete = $rs->fields["SGD_EXP_RETE"];

											if ($fechfin != "" && $arch == 2 && $rete == 1) {
												$srd = $rs->fields["SGD_SRD_CODIGO"];
												$sbrd = $rs->fields["SGD_SBRD_CODIGO"];
												$rss = $db->query($query2);
												if (!$rss->EOF) {
													$tiemc = $rss->fields["SGD_SBRD_TIEMAC"];
													$tiemg = $rss->fields["SGD_SBRD_TIEMAG"];
													$fechaIni = date('Y-m-d');
													$time = fnc_date_calc($fechfin, $tiemg);
													$time2 = fnc_date_calc($time, $tiemc);

													if ($time <= $fechaIni && $fechaIni <= $time2) {
														include "$ruta_raiz/include/query/archivo/queryAlerta.php";
														$resulVali = $verLinkArchivo->valPermisoRadi($rad);
														$valImg = $resulVali['verImg'];
														$pathImagen = $resulVali['pathImagen'];
														$rsr = $db->query($query3);
														$path = $rsr->fields['RADI_PATH'];
										?>
														<tr>
															<td class=leidos2 align="center">
																<b><a href='datos_expediente.php?<?= $encabezado . "&num_expediente=$exp&nurad=$rad" ?>' class='vinculos'><?= $exp ?></b>
															</td>
															<?
															if ($valImg == "SI") {
																echo "<td class=leidos2 align=center><a class=\"vinculos\" href=\"#2\" onclick=\"funlinkArchivo('$rad','$ruta_raiz');\">$rad</a> </b></td>";
															} else {
																echo "<td class=leidos2 align=center><a class=\"vinculos\" href=\"#2\" onclick=\"javascript:noPermiso();\">$rad</a> </b></td>";
															}
															?>
															<td class="leidos2" align="center"> <?= $fechfin ?></td>
														</tr>
											<?
														$cont++;
													}

													if ($time <= $fechaIni && $fechaIni <= $time2) {
														$rsp = $db->query($quer);
													}
													if ($fechaIni >= $time2) {
														$rsp = $db->query($quer2);
													}
												}
											}
											$rs->MoveNext();
										}
										if ($cont == 0) {
											?>
											<tr>
												<td class=leidos2 align="center">No se encontraron Radicados Proximos a pasar a Archivo Historico
											<? }
									}
											?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

</html>
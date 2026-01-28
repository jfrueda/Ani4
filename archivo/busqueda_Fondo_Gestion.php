<?
$krdOld = $krd;

session_start();

foreach ($_GET as $key => $valor) {
	${$key} = $valor;
}
foreach ($_POST as $key => $valor) {
	${$key} = $valor;
}
foreach ($_SESSION as $key => $valor) {
	${$key} = $valor;
}

if (!$krd) $krd = $krdOld;
if (!$ruta_raiz) $ruta_raiz = "..";

include "$ruta_raiz/rec_session.php";

include_once("$ruta_raiz/include/db/ConnectionHandler.php");

$db = new ConnectionHandler("$ruta_raiz");
$db2 = new ConnectionHandler("$ruta_raiz");
$encabezadol = "$PHP_SELF?" . session_name() . "=" . session_id() . "&dependencia=$dependencia&krd=$krd&sel=$sel";
$encabezado = session_name() . "=" . session_id() . "&krd=$krd&tipo_archivo=1&nomcarpeta=$nomcarpeta";

function fnc_date_calcy($this_date, $num_years)
{
	$my_time = strtotime($this_date); //converts date string to UNIX timestamp
	$timestamp = $my_time + ($num_years * 86400); //calculates # of days passed ($num_days) * # seconds in a day (86400)
	$return_date = date("Y-m-d", $timestamp);  //puts the UNIX timestamp back into string format
	return $return_date; //exit function and return string
}

function fnc_date_calcm($this_date, $num_month)
{
	$my_time = strtotime($this_date); //converts date string to UNIX timestamp
	$timestamp = $my_time - ($num_month * 2678400); //calculates # of days passed ($num_days) * # seconds in a day (86400)
	$return_date = date("Y-m-d", $timestamp);  //puts the UNIX timestamp back into string format
	return $return_date; //exit function and return string
}
?>
<html>

<head>
	<title>Busqueda Archivo Fondo Gestion</title>
	<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
	<link rel="stylesheet" href="../estilos/orfeo.css">
	<link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
	<script language="JavaScript" src="../js/spiffyCal/spiffyCal_v2_1.js">
	</script>
</head>

<body bgcolor="#FFFFFF">
	<div id="spiffycalendar" class="text"></div>

	<form name=busqueda_central action="<?= $encabezadol ?>" method='post' action='busqueda_central.php?<?= session_name() ?>=<?= trim(session_id()) ?>krd=<?= $krd ?>'>
		<table border="0" width="90%" cellpadding="0" class="table table-bordered align-middle shadow-sm borde_tab mx-auto">
			<thead class="table-primary text-center">
				<tr>
					<td class="fw-bold fs-5" colspan="4">
						BUSQUEDA ARCHIVO FONDO GESTION
					</td>
				</tr>
			</thead>

			<?php
			$item1 = "CARRO";
			$item3 = "CARA";
			$item4 = "CUERPO";
			$item5 = "ENTREPANO";
			$item6 = "CAJA";
			?>

			<tbody class="table-light">
				<!-- SERIE / SUBSERIE -->
				<tr>
					<td class="fw-semibold text-start">SERIE</td>
					<td>
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
						$querySerie = "select distinct ($sqlConcat) as detalle, s.sgd_srd_codigo from sgd_mrd_matrird m,
									sgd_srd_seriesrd s where s.sgd_srd_codigo = m.sgd_srd_codigo and " . $sqlFechaHoy . " between s.sgd_srd_fechini
									and s.sgd_srd_fechfin order by detalle ";
						$rsD = $db->conn->query($querySerie);
						$comentarioDev = "Muestra las Series Docuementales";
						include "$ruta_raiz/include/tx/ComentarioTx.php";
						print $rsD->GetMenu2(
							"codserie",
							$codserie,
							"0:-- Seleccione --",
							false,
							"",
							"onChange='submit()' class='form-select form-select-sm select'"
						);
						?>
					</td>
					<td class="fw-semibold text-start">SUBSERIE</td>
					<td>
						<?php
						$nomb_varc = "su.sgd_sbrd_codigo";
						$nomb_varde = "su.sgd_sbrd_descrip";
						include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";
						$querySub = "select distinct ($sqlConcat) as detalle, su.sgd_sbrd_codigo from sgd_mrd_matrird m,
								sgd_sbrd_subserierd su where m.sgd_srd_codigo = '$codserie' and su.sgd_srd_codigo = '$codserie'
								and su.sgd_sbrd_codigo = m.sgd_sbrd_codigo and " . $sqlFechaHoy . " between su.sgd_sbrd_fechini
								and su.sgd_sbrd_fechfin order by detalle ";
						$rsSub = $db->conn->query($querySub);
						include "$ruta_raiz/include/tx/ComentarioTx.php";
						print $rsSub->GetMenu2(
							"tsub",
							$tsub,
							"0:-- Seleccione --",
							false,
							"",
							"class='form-select form-select-sm select'"
						);

						if (!$codiSRD) {
							$codiSRD = $codserie;
							$codiSBRD = $tsub;
						}
						?>
					</td>
				</tr>

				<!-- TIPO -->
				<tr>
					<td class="fw-semibold">TIPO</td>
					<td>
						<?php
						$queryt = "select sgd_pexp_descrip,sgd_pexp_codigo from sgd_pexp_procexpedientes where sgd_srd_codigo like'" . $codserie . "' order by sgd_pexp_descrip";
						$rsTip = $db->conn->Execute($queryt);
						print $rsTip->GetMenu2(
							"tip",
							$tip,
							"0:-- Seleccione --",
							false,
							"",
							"class='form-select form-select-sm select'"
						);
						?>
					</td>
					<?
					switch ($codserie) {
						case '100':
							$titu = "NRO DE EXPEDIENTE";
							$titu2 = "TITULO";
							$titu3 = "NRO CONSECUTIVO";
							$titu4 = "A&Ntilde;O";
							$titu5 = "";
							break;
						case '200':
							$titu = "NRO DE EXPEDIENTE";
							$titu2 = "QUERELLADO";
							$titu3 = "QUERELLANTE";
							$titu4 = "A&Ntilde;O";
							$titu5 = "AUTO DE ARCHIVO";
							break;
						case '300':
							$titu = "NRO CONTRATO";
							$titu2 = "CONTRATISTA";
							$titu3 = "OBJETO CONTRACTUAL";
							$titu4 = "VIGENCIA";
							$titu5 = "ACTA DE LIQUIDACION";
							break;
						default:
							$titu = "NRO DE DOCUMENTO ";
							$titu2 = "TITULO";
							$titu3 = "REMITENTE";
							$titu4 = "A&Ntilde;O";
							$titu5 = "";
							break;
					}
					?>
					<td class="fw-semibold"><?= $titu5 ?></td>
					<td>
						<input type="text" name="fechaa" value="<?= $fechaa ?>" class="form-control form-control-sm tex_area">
					</td>
				</tr>

				<!-- CAMPOS DE BUSQUEDA -->
				<tr>
					<td class="fw-semibold"><?= $titu ?></td>
					<td>
						<input type="text" name="buscar_orden" value="<?= $buscar_orden ?>" class="form-control form-control-sm tex_area">
					</td>
					<td class="fw-semibold">RADICADO</td>
					<td>
						<input type="text" name="buscar_rad" value="<?= $buscar_rad ?>" class="form-control form-control-sm tex_area">
					</td>
				</tr>

				<tr>
					<td class="fw-semibold"><?= $titu2 ?></td>
					<td>
						<input type="text" name="buscar_deman" value="<?= $buscar_deman ?>" class="form-control form-control-sm tex_area">
					</td>
					<td class="fw-semibold"><?= $titu3 ?></td>
					<td>
						<input type="text" name="buscar_demant" value="<?= $buscar_demant ?>" class="form-control form-control-sm tex_area">
					</td>
				</tr>

				<tr>
					<td width="20%" class="titulos2 align-middle">
						FECHA INICIAL<br>
						<small class="text-muted">Desde</small><br>
						<?
						if ($sep == 1) $datoss = "checked";
						else $datoss = "";
						?>
						<input name="sep" type="checkbox" class="form-check-input ms-1" value="1" <?= $datoss ?>>
						<span class="ms-4 text-muted">Hasta</span>
					</td>

					<td width="20%" class="titulos2">
						<div class="mb-2">
							<script language="javascript">
								<?
								if (!$fechaIni) $fechaIni = fnc_date_calcm(date('Y-m-d'), '1');
								if (!$fechaInif) $fechaInif = date('Y-m-d');
								?>
								var dateAvailable1 = new ctlSpiffyCalendarBox("dateAvailable1", "busqueda_central", "fechaIni", "btnDate1", "<?= $fechaIni ?>", scBTNMODE_CUSTOMBLUE);
								dateAvailable1.date = "<?= date('Y-m-d'); ?>";
								dateAvailable1.writeControl();
								dateAvailable1.dateFormat = "yyyy-MM-dd";
							</script>
						</div>

						<script language="javascript">
							var dateAvailable2 = new ctlSpiffyCalendarBox("dateAvailable2", "busqueda_central", "fechaInif", "btnDate2", "<?= $fechaInif ?>", scBTNMODE_CUSTOMBLUE);
							dateAvailable2.date = "<?= date('Y-m-d'); ?>";
							dateAvailable2.writeControl();
							dateAvailable2.dateFormat = "yyyy-MM-dd";
						</script>
					</td>

					<td width="20%" class="titulos2 align-middle">
						FECHA FINAL<br>
						<small class="text-muted">Desde</small><br>
						<?
						if ($sep2 == 1) $datoss2 = "checked";
						else $datoss2 = "";
						?>
						<input name="sep2" type="checkbox" class="form-check-input ms-1" value="1" <?= $datoss2 ?>>
						<span class="ms-4 text-muted">Hasta</span>
					</td>

					<td width="20%" class="titulos2">
						<div class="mb-2">
							<script language="javascript">
								<?
								if (!$fechaIni2) $fechaIni2 = fnc_date_calcm(date('Y-m-d'), '1');
								if (!$fechaInif2) $fechaInif2 = date('Y-m-d');
								?>
								var dateAvailable3 = new ctlSpiffyCalendarBox("dateAvailable3", "busqueda_central", "fechaIni2", "btnDate3", "<?= $fechaIni2 ?>", scBTNMODE_CUSTOMBLUE);
								dateAvailable3.date = "<?= date('Y-m-d'); ?>";
								dateAvailable3.writeControl();
								dateAvailable3.dateFormat = "yyyy-MM-dd";
							</script>
						</div>

						<script language="javascript">
							var dateAvailable4 = new ctlSpiffyCalendarBox("dateAvailable4", "busqueda_central", "fechaInif2", "btnDate4", "<?= $fechaInif2 ?>", scBTNMODE_CUSTOMBLUE);
							dateAvailable4.date = "<?= date('Y-m-d'); ?>";
							dateAvailable4.writeControl();
							dateAvailable4.dateFormat = "yyyy-MM-dd";
						</script>
					</td>
				</tr>

				<tr>
					<td class="titulos2">ANEXO</td>
					<td class="titulos2">
						<input type="text" name="anexo" value="<?= $anexo ?>" class="form-control form-control-sm">
						<?
						switch ($codserie) {
							case '300':
								$proc = 1;
								break;
							default:
								$proc = 2;
								break;
						}
						?>
					</td>

					<td class="titulos2">DEPENDENCIA</td>
					<td class="titulos2">
						<?
						$conD = $db->conn->Concat("d.DEPE_CODI", "'-'", "d.DEPE_NOMB");
						$sql5 = "select distinct($conD) as detalle,d.DEPE_CODI from DEPENDENCIA d ";
						if ($codserie != '0') $sql5 .= " , SGD_MRD_MATRIRD m where m.depe_codi=d.depe_codi and m.sgd_srd_codigo='$codserie'";
						$sql5 .= " order by d.DEPE_CODI";
						$rs = $db->conn->Execute($sql5);
						print $rs->GetMenu2('depen', $depen, true, false, "", "class='form-select form-select-sm'");
						?>
					</td>
				</tr>

				<tr>
					<td class="titulos2"><?= $titu4 ?></td>
					<td class="titulos2">
						<select class="form-select form-select-sm" name="buscar_ano">
							<?
							$agnoactual = Date('Y');
							for ($i = 1986; $i <= $agnoactual; $i++) {
								if ($i == $buscar_ano) $option = "<option selected value=\"$buscar_ano\">$buscar_ano</option>";
								elseif ($i == 1986) $option = "<option value=\"\">TODOS</option>";
								else $option = "<option value=\"$i\">$i</option>";
								echo $option;
							}
							?>
						</select>
						<input type="hidden" name="yea" value="<?= $buscar_ano ?>">
					</td>

					<td class="titulos2">DOCUMENTO DE IDENTIDAD</td>
					<td class="titulos2">
						<input type="text" name="buscar_docu" value="<?= $buscar_docu ?>" class="form-control form-control-sm">
					</td>
				</tr>

				<tr>
					<td class="titulos2">ZONA</td>
					<td class="titulos2">
						<input type="text" name="buscar_zona" value="<?= $buscar_zona ?>" class="form-control form-control-sm" maxlength="3">
					</td>

					<td class="titulos2"><?= $item1 ?></td>
					<td class="titulos2">
						<input type="text" name="buscar_carro" value="<?= $buscar_carro ?>" class="form-control form-control-sm" maxlength="3">
					</td>
				</tr>

				<tr>
					<td class="titulos2 align-middle"><?= $item3 ?></td>
					<?
					if ($buscar_cara == "A") $sec1 = "checked";
					elseif ($buscar_cara == "B") $sec2 = "checked";
					else $sec3 = "checked";
					?>
					<td class="titulos2">
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="buscar_cara" value="A" <?= $sec1 ?>>
							<label class="form-check-label">A</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="buscar_cara" value="B" <?= $sec2 ?>>
							<label class="form-check-label">B</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="buscar_cara" value="" <?= $sec3 ?>>
							<label class="form-check-label">Ninguno</label>
						</div>
					</td>

					<td class="titulos2"><?= $item4 ?></td>
					<td class="titulos2">
						<input type="text" name="buscar_estante" value="<?= $buscar_estante ?>" class="form-control form-control-sm" maxlength="5">
					</td>
				</tr>

				<tr>
					<td class="titulos2"><?= $item5 ?></td>
					<td class="titulos2">
						<input type="text" name="buscar_entre" value="<?= $buscar_entre ?>" class="form-control form-control-sm" maxlength="5">
					</td>

					<td class="titulos2"><?= $item6 ?></td>
					<td class="titulos2">
						<input type="text" name="buscar_caja" value="<?= $buscar_caja ?>" class="form-control form-control-sm" maxlength="5">
					</td>
				</tr>

				<tr>
					<td class="titulos2">INDICADORES DE DETERIORO</td>
					<td class="titulos2">
						<?
						switch ($buscar_inder) {
							case 1:
								$sele1 = "selected";
								break;
							case 2:
								$sele2 = "selected";
								break;
							case 3:
								$sele3 = "selected";
								break;
							case 4:
								$sele4 = "selected";
								break;
							case 5:
								$sele5 = "selected";
								break;
						}
						?>
						<select class="form-select form-select-sm" name="buscar_inder">
							<option value="0" <?= $sele0 ?>>Ninguno</option>
							<option value="1" <?= $sele1 ?>>Biológicos: Hongos</option>
							<option value="2" <?= $sele2 ?>>Biológicos: Roedores</option>
							<option value="3" <?= $sele3 ?>>Biológicos: Insectos</option>
							<option value="4" <?= $sele4 ?>>Decoloración Soporte</option>
							<option value="5" <?= $sele5 ?>>Desgarros</option>
						</select>
					</td>

					<td class="titulos2">MATERIALES INSERTADOS</td>
					<td class="titulos2">
						<?
						switch ($buscar_mata) {
							case 1:
								$sele7 = "selected";
								break;
							case 2:
								$sele8 = "selected";
								break;
							case 3:
								$sele9 = "selected";
								break;
							case 4:
								$sele10 = "selected";
								break;
							case 5:
								$sele11 = "selected";
								break;
							case 6:
								$sele12 = "selected";
								break;
							case 7:
								$sele13 = "selected";
								break;
							case 8:
								$sele14 = "selected";
								break;
							case 9:
								$sele15 = "selected";
								break;
							case 10:
								$sele16 = "selected";
								break;
							case 11:
								$sele17 = "selected";
								break;
							case 12:
								$sele18 = "selected";
								break;
							case 13:
								$sele19 = "selected";
								break;
							case 14:
								$sele20 = "selected";
								break;
							case 15:
								$sele21 = "selected";
								break;
							case 16:
								$sele22 = "selected";
								break;
							case 17:
								$sele23 = "selected";
								break;
							case 18:
								$sele24 = "selected";
								break;
							case 19:
								$sele25 = "selected";
								break;
							case 20:
								$sele26 = "selected";
								break;
							case 21:
								$sele27 = "selected";
								break;
						}
						?>
						<select class="form-select form-select-sm" name="buscar_mata">
							<option value="0">Ninguno</option>
							<option value="1" <?= $sele7 ?>>Metálico</option>
							<option value="2" <?= $sele8 ?>>Post-it</option>
							<option value="3" <?= $sele9 ?>>Planos</option>
							<option value="4" <?= $sele10 ?>>Fotografía</option>
							<option value="5" <?= $sele11 ?>>Soporte Óptico</option>
							<option value="6" <?= $sele12 ?>>Soporte Magnético</option>
							<option value="7" <?= $sele13 ?>>Metalico y Post-it</option>
							<option value="8" <?= $sele14 ?>>Metalico y Planos </option>
							<option value="9" <?= $sele15 ?>>Metalico y Fotografia </option>
							<option value="10" <?= $sele16 ?>>Metalico y Soporte Optico </option>
							<option value="11" <?= $sele17 ?>>Metalico y Soporte Magnetico </option>
							<option value="12" <?= $sele18 ?>>Post-it y Planos</option>
							<option value="13" <?= $sele19 ?>>Post-it y Fotografia</option>
							<option value="14" <?= $sele20 ?>>Post-it y Soporte Optico</option>
							<option value="15" <?= $sele21 ?>>Post-it y Soporte Magnetico</option>
							<option value="16" <?= $sele22 ?>>Planos y Fotografia</option>
							<option value="17" <?= $sele23 ?>>Planos y Soporte Optico</option>
							<option value="18" <?= $sele24 ?>>Planos y Soporte Magnetico</option>
							<option value="19" <?= $sele25 ?>>Fotografia y Soporte Optico</option>
							<option value="20" <?= $sele26 ?>>Fotografia y Soporte Magnetico</option>
							<option value="21" <?= $sele27 ?>>Soporte Óptico y Magnético</option>
						</select>
					</td>
				</tr>

				<tr>
					<td class="titulos2 text-end">PRÉSTAMO</td>
					<?
					if ($presta == 1) $de = "checked";
					else $de = "";
					?>
					<td class="titulos2">
						<input class="form-check-input" type="checkbox" name="presta" value="1" <?= $de ?>>
					</td>

					<td class="titulos2">
						FECHA RADICADO<br>
						<small class="text-muted">Desde</small>
						<?
						if ($sep3 == 1) $datoss3 = "checked";
						else $datoss3 = "";
						?>
						<input name="sep3" type="checkbox" class="form-check-input ms-1" value="1" <?= $datoss3 ?>>
						<span class="ms-4 text-muted">Hasta</span>
					</td>

					<td class="titulos2">
						<script language="javascript">
							<?
							if (!$fechaIni3) $fechaIni3 = fnc_date_calcm(date('Y-m-d'), '1');
							if (!$fechaInif3) $fechaInif3 = date('Y-m-d');
							?>
							var dateAvailable5 = new ctlSpiffyCalendarBox("dateAvailable5", "busqueda_central", "fechaIni3", "btnDate5", "<?= $fechaIni3 ?>", scBTNMODE_CUSTOMBLUE);
							dateAvailable5.date = "<?= date('Y-m-d'); ?>";
							dateAvailable5.writeControl();
							dateAvailable5.dateFormat = "yyyy-MM-dd";
						</script>
						<script language="javascript">
							var dateAvailable6 = new ctlSpiffyCalendarBox("dateAvailable6", "busqueda_central", "fechaInif3", "btnDate6", "<?= $fechaInif3 ?>", scBTNMODE_CUSTOMBLUE);
							dateAvailable6.date = "<?= date('Y-m-d'); ?>";
							dateAvailable6.writeControl();
							dateAvailable6.dateFormat = "yyyy-MM-dd";
						</script>
					</td>
				</tr>

				<tr>
					<td colspan="2" class="text-end">
						<input type=submit value=Buscar name=Buscar class="botones">&nbsp;
					</td>
					<td colspan="2">
						<a href="archivo.php?<?= session_name() ?>=<?= trim(session_id()) ?>krd=<?= $krd ?>" class="btn btn-secondary btn-sm">
							<input name='Regresar' align="middle" type="button" class="botones" id="envia22" value="Regresar">
						</a>
					</td>
				</tr>
			</tbody>
		</table>
		<br>
		<?
		if ($Buscar) {
			include "$ruta_raiz/include/query/archivo/queryBusqueda_central.php";
			$dbg = $db->conn->Execute($sqla);
			if (!$dbg->EOF) $usua_perm_archi = $dbg->fields['USUA_ADMIN_ARCHIVO'];
			switch ($codiSRD) {
				case '100':
					$it1 = "NRO DE EXPEDIENTE";
					$it2 = "TITULO";
					$it3 = "NRO CONSECUTIVO";
					$it4 = "A&Ntilde;O";
					$it5 = "";
					break;
				case '200':
					$it1 = "NRO DE EXPEDIENTE";
					$it2 = "QUERELLADO";
					$it3 = "QUERELLANTE";
					$it4 = "A&Ntilde;O";
					$it5 = "AUTO DE ARCHIVO";
					break;
				case '300':
					$it1 = "NRO CONTRATO";
					$it2 = "CONTRATISTA";
					$it3 = "OBJETO CONTRACTUAL";
					$it4 = "VIGENCIA";
					$it5 = "ACTA DE LIQUIDACION";
					break;
				default:
					$it1 = "NRO DE DOCUMENTO ";
					$it2 = "TITULO";
					$it3 = "REMITENTE";
					$it4 = "A&Ntilde;O";
					$it5 = "";
					break;
			}
		?>
			<table border=0 width 100% cellpadding="1" class="borde_tab">
				<thead class="table-primary text-center">
					<tr>
						<TD class=titulos5>RADICADO</TD>
						<TD class=titulos5>FECHA RADICADO</TD>
						<TD class=titulos5><?= $it1 ?></TD>
						<TD class=titulos5>FECHA INICIAL</TD>
						<TD class=titulos5>FECHA FINAL</TD>
						<TD class=titulos5><?= $it4 ?></TD>
						<TD class=titulos5>DEPENDENCIA</TD>
						<TD class=titulos5><?= $it2 ?></TD>
						<TD class=titulos5><?= $it3 ?></TD>
						<TD class=titulos5>DOCUMENTO DE IDENTIDAD</TD>
						<TD class=titulos5>SERIE</TD>
						<TD class=titulos5>SUBSERIE</TD>
						<TD class=titulos5>TIPO</TD>
						<TD class=titulos5>FOLIOS</TD>
						<TD class=titulos5>ZONA</TD>
						<TD class=titulos5>CARRO</TD>
						<TD class=titulos5>CARA</TD>
						<TD class=titulos5>ESTANTE</TD>
						<TD class=titulos5>ENTREPA&Ntilde;O</TD>
						<TD class=titulos5>CAJA</TD>
						<TD class=titulos5>UNIDAD DOCUMENTAL</TD>
						<TD class=titulos5>NRO CARPETA</TD>
						<TD class=titulos5>ANEXO</TD>
						<TD class=titulos5>
							<p>INDICADORES DE DETERIORO</p>
						</TD>
						<TD class=titulos5>
							<p>MATERIAL AGREGADO</p>
						</TD>
						<TD class=titulos5><?= $it5 ?></TD>
						<TD class=titulos5>PRESTAMO</TD>
						<TD class=titulos5>FUNCIONARIO PRESTAMO</TD>
						<TD class=titulos5>FECHA ENTREGA PRESTAMO</TD>
					</tr>
				</thead>

				<?
				if ($buscar_ano != "") {
					$x = "SGD_ARCHIVO_YEAR LIKE '$buscar_ano'";
					$a = "and";
				} else {
					$x = "";
					$a = "";
				}
				if ($buscar_rad != "") {
					$r = "SGD_ARCHIVO_RAD LIKE '%$buscar_rad%G'";
					$b = "and";
				} else {
					$r = "";
					$b = "";
				}
				if ($codserie != '0') {
					$srds = "SGD_ARCHIVO_SRD LIKE '$codserie'";
					$c = "and";
				} else {
					$srds = "";
					$c = "";
				}
				if ($codiSBRD != '0') {
					$sbrds = "SGD_ARCHIVO_SBRD LIKE '$codiSBRD'";
					$d = "and";
				} else {
					$sbrds = "";
					$d = "";
				}
				if ($buscar_zona != "") {
					$bzon = strtoupper($buscar_zona);
					$zon = "SGD_ARCHIVO_ZONA LIKE '$bzon'";
					$f = "and";
				} else {
					$zon = "";
					$f = "";
				}
				if ($buscar_carro != "") {
					if ($item1 == "ESTANTE") $carro = "SGD_ARCHIVO_ESTANTE LIKE '$buscar_carro'";
					elseif ($item1 == "CARRO") $carro = "SGD_ARCHIVO_CARRO LIKE '$buscar_carro'";
					$g = "and";
				} else {
					$carro = "";
					$g = "";
				}
				if ($buscar_cara != "") {
					if ($item3 == "ENTREPANO") $cara = "SGD_ARCHIVO_ENTREPANO LIKE '$buscar_cara'";
					elseif ($item3 == "CARA") $cara = "SGD_ARCHIVO_CARA LIKE '$buscar_cara'";
					$i = "and";
				} else {
					$cara = "";
					$i = "";
				}
				if ($buscar_estante != "") {
					if ($item4 == "ESTANTE") $estan = "SGD_ARCHIVO_ESTANTE LIKE '$buscar_estante'";
					elseif ($item4 == "CAJA") $estan = "SGD_ARCHIVO_CAJA LIKE '$buscar_estante'";
					$h = "and";
				} else {
					$estan = "";
					$h = "";
				}
				if ($buscar_entre != "") {
					$entre = "SGD_ARCHIVO_ENTREPANO LIKE '$buscar_entre'";
					$v = "and";
				} else {
					$entre = "";
					$v = "";
				}
				if ($buscar_caja != "") {
					$caja = "SGD_ARCHIVO_CAJA LIKE '$buscar_caja'";
					$t = "and";
				} else {
					$caja = "";
					$s = "";
				}
				if ($sep == '1') {
					if ($fechaIni == $fechaInif) $fecha = "SGD_ARCHIVO_FECHAI like '$fechaIni'";
					else {
						$time = fnc_date_calcy($fechaInif, '1');
						$fecha = "SGD_ARCHIVO_FECHAI <= '$time' and SGD_ARCHIVO_FECHAI >= '$fechaIni'";
					}
					$j = "and";
				} else {
					$fecha = "";
					$j = "";
				}
				if ($sep2 == '1') {
					if ($fechaIni2 == $fechaInif2) $fecha2 = "SGD_ARCHIVO_FECHAF like '$fechaIni2'";
					else {
						$time2 = fnc_date_calcy($fechaInif2, '1');
						$fecha2 = "SGD_ARCHIVO_FECHAF <= '$time2' and SGD_ARCHIVO_FECHAF >= '$fechaIni2'";
					}
					$w = "and";
				} else {
					$fecha2 = "";
					$w = "";
				}
				if ($sep3 == '1') {
					if ($fechaIni3 == $fechaInif3) $fecha3 = "SGD_ARCHIVO_FECH like '$fechaIni3'";
					else {
						$time3 = fnc_date_calcy($fechaInif3, '1');
						$fecha3 = "SGD_ARCHIVO_FECH <= '$time3' and SGD_ARCHIVO_FECH >= '$fechaIni3'";
					}
					$wq = "and";
				} else {
					$fecha3 = "";
					$wq = "";
				}
				if ($buscar_orden != "") {
					$orden = "SGD_ARCHIVO_ORDEN LIKE '%$buscar_orden%'";
					$k = "and";
				} else {
					$orden = "";
					$k = "";
				}
				if ($depen != "") {
					$depe = "SGD_ARCHIVO_DEPE LIKE '$depen' ";
					$l = "and";
				} else {
					$depe = "";
					$l = "";
				}
				if ($buscar_deman != "") {
					$dem = strtoupper($buscar_deman);
					$deman = "SGD_ARCHIVO_DEMANDADO LIKE '%$dem%'";
					$n = "and";
				} else {
					$deman = "";
					$n = "";
				}
				if ($buscar_demant != "") {
					$demt = strtoupper($buscar_demant);
					$demant = "SGD_ARCHIVO_DEMANDANTE LIKE '%$demt%'";
					$m = "and";
				} else {
					$demant = "";
					$m = "";
				}
				if ($buscar_docu != "") {
					$docu = "SGD_ARCHIVO_CC_DEMANDANTE LIKE '%$buscar_docu%'";
					$o = "and";
				} else {
					$docu = "";
					$o = "";
				}
				if ($buscar_inder != '0') {
					$inder = "SGD_ARCHIVO_INDER LIKE '$buscar_inder'";
					$p = "and";
				} else {
					$inder = "";
					$p = "";
				}
				if ($buscar_mata != '0') {
					$mata = "SGD_ARCHIVO_MATA LIKE '$buscar_mata'";
					$q = "and";
				} else {
					$mata = "";
					$q = "";
				}
				if ($buscar_ano != "") $orde = " order by sgd_archivo_year";
				else $orde = " order by sgd_archivo_fech";
				if ($presta != "") {
					$pst = "SGD_ARCHIVO_PRESTAMO=$presta ";
					$pt = "and";
				} else {
					$pst = "";
					$pt = "";
				}
				if ($fechaa != "") {
					$fea = "SGD_ARCHIVO_FECHAA=$fechaa ";
					$fta = "and";
				} else {
					$fea = "";
					$fta = "";
				}
				if ($tip != "0") {
					$ti = "SGD_ARCHIVO_PROC=$tip ";
					$tic = "and";
				} else {
					$ti = "";
					$tic = "";
				}
				if ($anexo != "") {
					$anex = "SGD_ARCHIVO_ANEXO like '%$anexo%' ";
					$an = "and";
				} else {
					$anex = "";
					$an = "";
				}

				$at = $buscar_orden . $buscar_rad . $buscar_ano . $buscar_caja . $buscar_estante . $buscar_entrepa . $buscar_zona . $buscar_deman . $fecha . $depe . $buscar_demant .
					$buscar_docu . $buscar_ufisica . $codserie . $codiSBRD . $buscar_proc . $buscar_inder;
				$cont = 0;

				include("$ruta_raiz/include/query/archivo/queryBusqueda_gestion.php");
				//$db->conn->debug=true;
				$rs = $db->conn->Execute($sql);
				while (!$rs->EOF) {
					$orden1 = $rs->fields['SGD_ARCHIVO_ORDEN'];
					$sbrd = $rs->fields['SGD_ARCHIVO_SBRD'];
					$srd = $rs->fields['SGD_ARCHIVO_SRD'];
					$demandado = $rs->fields['SGD_ARCHIVO_DEMANDADO'];
					$demandante = $rs->fields['SGD_ARCHIVO_DEMANDANTE'];
					$cc = $rs->fields['SGD_ARCHIVO_CC_DEMANDANTE'];
					$indet = $rs->fields['SGD_ARCHIVO_INDER'];
					$mata1 = $rs->fields['SGD_ARCHIVO_MATA'];
					$fechi = $rs->fields['SGD_ARCHIVO_FECHAI'];
					$fechf = $rs->fields['SGD_ARCHIVO_FECHAF'];
					$year = $rs->fields['SGD_ARCHIVO_YEAR'];
					$caja1 = $rs->fields['SGD_ARCHIVO_CAJA'];
					$carro1 = $rs->fields['SGD_ARCHIVO_CARRO'];
					$cara1 = $rs->fields['SGD_ARCHIVO_CARA'];
					$radi = $rs->fields["SGD_ARCHIVO_RAD"];
					$estante1 = $rs->fields['SGD_ARCHIVO_ESTANTE'];
					$unidoc = $rs->fields['SGD_ARCHIVO_UNIDOCU'];
					$dependencia = $rs->fields['SGD_ARCHIVO_DEPE'];
					$entrepa1 = $rs->fields['SGD_ARCHIVO_ENTREPANO'];
					$folio = $rs->fields['SGD_ARCHIVO_FOLIOS'];
					$path = $rs->fields['SGD_ARCHIVO_PATH'];
					$zona1 = $rs->fields['SGD_ARCHIVO_ZONA'];
					$anexo = $rs->fields['SGD_ARCHIVO_ANEXO'];
					$pres = $rs->fields['SGD_ARCHIVO_PRESTAMO'];
					$funprest = $rs->fields['SGD_ARCHIVO_FUNPREST'];
					$fprestf = $rs->fields['SGD_ARCHIVO_FECHPRESTF'];
					$fechaR = $rs->fields['FECHR'];
					if ($pres == 1) $prest = "SI";
					else $prest = "NO";
					$fecaa = $rs->fields['SGD_ARCHIVO_FECHAA'];
					$procc = $rs->fields['SGD_ARCHIVO_PROC'];
					$ncarp = $rs->fields['SGD_ARCHIVO_NCARP'];
					if ($procc != 0) {
						$wet = $db->conn->Execute("select sgd_pexp_descrip from sgd_pexp_procexpedientes where sgd_pexp_codigo like'" . $procc . "'");
						$proce = $wet->fields['SGD_PEXP_DESCRIP'];
					}

					switch ($indet) {
						case 0:
							$indete = "Ninguno";
							break;
						case 1:
							$indete = "Biologicos: Hongos";
							break;
						case 2:
							$indete = "Biologicos: Roedores";
							break;
						case 3:
							$indete = "Biologicos: Insectos";
							break;
						case 4:
							$indete = "Decoloracion Soporte";
							break;
						case 5:
							$indete = "Desgarros";
							break;
					}

					switch ($mata1) {
						case '0':
							$mata2 = "Ninguno";
							break;
						case '1':
							$mata2 = "Metalico";
							break;
						case '2':
							$mata2 = "Post-it";
							break;
						case '3':
							$mata2 = "Planos";
							break;
						case '4':
							$mata2 = "Fotografia";
							break;
						case '5':
							$mata2 = "Soporte Optico";
							break;
						case '6':
							$mata2 = "Soporte Magnetico";
							break;
						case '7':
							$mata2 = "Metalico y Post-it ";
							break;
						case '8':
							$mata2 = "Metalico y Planos ";
							break;
						case '9':
							$mata2 = "Metalico y Fotografia ";
							break;
						case '10':
							$mata2 = "Metalico y Soporte Optico ";
							break;
						case '11':
							$mata2 = "Metalico y Soporte Magnetico ";
							break;
						case '12':
							$mata2 = "Post-it y Planos";
							break;
						case '13':
							$mata2 = "Post-it y Fotografia";
							break;
						case '14':
							$mata2 = "Post-it y Soporte Optico";
							break;
						case '15':
							$mata2 = "Post-it y Soporte Magnetico";
							break;
						case '16':
							$mata2 = "Planos y Fotografia";
							break;
						case '17':
							$mata2 = "Planos y Soporte Optico";
							break;
						case '18':
							$mata2 = "Planos y Soporte Magnetico";
							break;
						case '19':
							$mata2 = "Fotografia y Soporte Optico";
							break;
						case '20':
							$mata2 = "Fotografia y Soporte Magnetico";
							break;
						case '21':
							$mata2 = "Soporte Optico y Soporte Magnetico";
							break;
						case '22':
							$mata2 = "Metalico, Post-it y Planos ";
							break;
						case '23':
							$mata2 = "Metalico, Post-it y Fotografia ";
							break;
						case '24':
							$mata2 = "Metalico, Post-it y Soporte Magnetico ";
							break;
						case '25':
							$mata2 = "Post-it, Planos y Fotografia";
							break;
						case '26':
							$mata2 = "Post-it, Planos y Soporte Optico";
							break;
						case '27':
							$mata2 = "Post-it, Planos y Soporte Magnetico";
							break;
						case '28':
							$mata2 = "Planos, Fotografia y Soporte Optico";
							break;
						case '29':
							$mata2 = "Planos, Fotografia y Soporte Magnetico";
							break;
						case '30':
							$mata2 = "Metalico, Fotografia y Soporte Optico";
							break;
						case '31':
							$mata2 = "Metalico, Planos y Soporte Optico";
							break;
						case '32':
							$mata2 = "Metalico, Fotografia y Soporte Magnetico";
							break;
						case '33':
							$mata2 = "Metalico, Planos y Soporte Magnetico";
							break;
						case '34':
							$mata2 = "Post-it, Fotografia y Soporte Optico";
							break;
						case '35':
							$mata2 = "Post-it, Fotografia y Soporte Magnetico";
							break;
						case '36':
							$mata2 = "Metalico, Soporte Optico y Soporte Magnetico";
							break;
						case '37':
							$mata2 = "Post-it, Soporte Optico y Soporte Magnetico";
							break;
						case '38':
							$mata2 = "Planos, Soporte Optico y Soporte Magnetico";
							break;
						case '39':
							$mata2 = "Fotografia, Soporte Optico y Soporte Magnetico ";
							break;
						case '40':
							$mata2 = "Metalico, Post-it y Soporte Optico";
							break;
						case '41':
							$mata2 = "Metalico, Planos y Fotografia";
							break;
						case '42':
							$mata2 = "Metalico, Fotografia y Soporte Optico";
							break;
					}

					switch ($tipo) {
						case 1:
							$tipo1 = "Querella";
							break;
						case 2:
							$tipo1 = "Otros";
							break;
					}
				?>
					<tr>
						<td class=leidos2 align="center">
							<?

							if ($usua_perm_archi >= 4) {
							?>
								<a href='insertar_Fondo_Gestion.php?<?= session_name() . "=" . session_id() . "&krd=$krd&fechah=$fechah&$orno&adodb_next_page&edi=1&rad=$radi" ?>'>
								<? } ?>
								<?= $radi ?></a>
						</td>
						<td class=titulos5 align="center"><b><a href='verHistoricoArch.php?<?= session_name() . "=" . session_id() . "&krd=$krd&fechah=$fechah&$orno&adodb_next_page&rad=$radi&tip=2" ?>'><?= $fechaR ?></b></td>
						<td class=leidos2 align="center"><b><?= $orden1 ?></b></td>
						<td class=titulos5 align="center"><b><span class=leidos2> <?= $fechi ?></b></td>
						<td class=leidos2 align="center"> <b><span class=leidos2><?= $fechf ?></b></td>
						<td class=titulos5 align="center"><b><span class=leidos2><?= $year ?></b></td>
						<td class=leidos2 align="center"><b><span class=leidos2><?= $dependencia ?></b></td>
						<td class=titulos5 align="center"><b><span class=leidos2><?= $demandado ?></b></td>
						<td class=leidos2 align="center"><b><span class=leidos2><?= $demandante ?></b></td>
						<td class=titulos5 align="center"><b><span class=leidos2><?= $cc ?></b></td>
						<td class=leidos2 align="center"><b><span class=leidos2><?= $srd ?></b></td>
						<td class=titulos5 align="center"><b><span class=leidos2><?= $sbrd ?></b></td>
						<td class=leidos2 align="center"><b><span class=leidos2><?= $proce ?></b></td>
						<td class=titulos5 align="center"><b><span class=leidos2><?= $folio ?></b></td>
						<td class=leidos2 align="center"><b><span class=leidos2><?= $zona1 ?></b></td>
						<td class=titulos5 align="center"><b><span class=leidos2><?= $carro1 ?></b></td>
						<td class=leidos2 align="center"><b><span class=leidos2><?= $cara1 ?></b></td>
						<td class=titulos5 align="center"><b><span class=leidos2><?= $estante1 ?></b></td>
						<td class=leidos2 align="center"><b><span class=leidos2><?= $entrepa1 ?></b></td>
						<td class=titulos5 align="center"><b><span class=leidos2><?= $caja1 ?></b></td>
						<td class=leidos2 align="center"><b><span class=leidos2><?= $unidoc ?></b></td>
						<td class=titulos5 align="center"><b><span class=leidos2><?= $ncarp ?></b></td>
						<td class=titulos5 align="center"><b><span class=leidos2><?= $anexo ?></b></td>
						<td class=leidos2 align="center"><b><span class=leidos2><?= $indete ?></b></td>
						<td class=titulos5 align="center"><b><span class=leidos2><?= $mata2 ?></b></td>
						<td class=titulos5 align="center"><b><span class=leidos2><?= $fecaa ?></b></td>
						<td class=leidos2 align="center"><b><span class=leidos2><?= $prest ?></b></td>
						<td class=titulos5 align="center"><b><span class=leidos2><?= $funprest ?></b></td>
						<td class=leidos2 align="center"><b><span class=leidos2><?= $fprestf ?></b></td>

				<?
					$cont++;
					$rs->MoveNext();
				}
			} ?>
			</table>
			<br>
			<center><?= $cont ?> Archivos Encontrados</center>
	</form>
</body>

</html>
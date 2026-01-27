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

if (!$krd) {
	$krd = $krdOld;
}

if (!$ruta_raiz) {
	$ruta_raiz = "..";
}

include "$ruta_raiz/rec_session.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";

$db = new ConnectionHandler("$ruta_raiz");
$db2 = new ConnectionHandler("$ruta_raiz");
$encabezadol = "$PHP_SELF?" . session_name() . "=" . session_id() . "&dependencia=$dependencia&krd=$krd&sel=$sel";
$encabezado = session_name() . "=" . session_id() . "&krd=$krd&tipo_archivo=1&nomcarpeta=$nomcarpeta";
//$db->conn->debug = true;

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
	<title>Busqueda Archivo Central</title>
	<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
	<link rel="stylesheet" href="../estilos/orfeo.css">
	<link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
	<script language="JavaScript" src="../js/spiffyCal/spiffyCal_v2_1.js">
	</script>
</head>

<body bgcolor="#FFFFFF">
	<div id="spiffycalendar" class="text"></div>

	<form name=busqueda_central action="<?= $encabezadol ?>" method='post' action='busqueda_central.php?<?= session_name() ?>=<?= trim(session_id()) ?>krd=<?= $krd ?>'>
		<table class="table table-bordered table-striped w-90 mx-auto">
			<thead class="table-primary">
				<tr>
					<th colspan="4" class="text-center">
						BUSQUEDA ARCHIVO CENTRAL
					</th>
				</tr>
			</thead>

			<?php
			$item1 = "CARRO";
			$item3 = "CARA";
			$item4 = "CUERPO";
			$item5 = "ENTREPANO";
			$item6 = "CAJA";
			?>

			<tbody>
				<tr class="align-middle">
					<td class="fw-semibold">SERIE</td>
					<td>
						<?php
						if (!$tdoc) $tdoc = 0;
						if (!$codserie) $codserie = 0;
						if (!$tsub) $tsub = 0;

						$fecha_hoy = Date("Y-m-d");
						$sqlFechaHoy = $db->conn->DBDate($fecha_hoy);

						$nomb_varc = "s.sgd_srd_codigo";
						$nomb_varde = "s.sgd_srd_descrip";

						include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";

						$querySerie = "select distinct ($sqlConcat) as detalle, s.sgd_srd_codigo
                               from sgd_mrd_matrird m, sgd_srd_seriesrd s
                               where s.sgd_srd_codigo = m.sgd_srd_codigo
                               and $sqlFechaHoy between s.sgd_srd_fechini and s.sgd_srd_fechfin
                               order by detalle";

						$rsD = $db->conn->query($querySerie);
						$comentarioDev = "Muestra las Series Docuementales";
						include "$ruta_raiz/include/tx/ComentarioTx.php";
						print $rsD->GetMenu2(
							"codserie",
							$codserie,
							"0:-- Seleccione --",
							false,
							"",
							"onChange='submit()' class='form-select'"
						);
						?>
					</td>

					<td class="fw-semibold">SUBSERIE</td>
					<td>
						<?php
						$nomb_varc = "su.sgd_sbrd_codigo";
						$nomb_varde = "su.sgd_sbrd_descrip";

						include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";

						$querySub = "select distinct ($sqlConcat) as detalle, su.sgd_sbrd_codigo
                             from sgd_mrd_matrird m, sgd_sbrd_subserierd su
                             where m.sgd_srd_codigo = '$codserie'
                             and su.sgd_srd_codigo = '$codserie'
                             and su.sgd_sbrd_codigo = m.sgd_sbrd_codigo
                             and $sqlFechaHoy between su.sgd_sbrd_fechini and su.sgd_sbrd_fechfin
                             order by detalle";

						$rsSub = $db->conn->query($querySub);
						include "$ruta_raiz/include/tx/ComentarioTx.php";
						print $rsSub->GetMenu2(
							"tsub",
							$tsub,
							"0:-- Seleccione --",
							false,
							"",
							"class='form-select'"
						);

						if (!$codiSRD) {
							$codiSRD = $codserie;
							$codiSBRD = $tsub;
						}
						?>
					</td>
				</tr>

				<tr class="align-middle">
					<td class="fw-semibold">TIPO</td>
					<td>
						<?php
						$queryt = "select sgd_pexp_descrip, sgd_pexp_codigo
                           from sgd_pexp_procexpedientes
                           where sgd_srd_codigo = $codserie
                           order by sgd_pexp_descrip";

						$rsTip = $db->conn->Execute($queryt);
						print $rsTip->GetMenu2(
							"tip",
							$tip,
							"0:-- Seleccione --",
							false,
							"",
							"class='form-select'"
						);
						?>
					</td>

					<?
					switch ($codserie) {
						case '1':
							$titu = "NRO DE EXPEDIENTE";
							$titu2 = "TITULO";
							$titu3 = "NRO CONSECUTIVO";
							$titu4 = "A&Ntilde;O";
							$titu5 = "";
							break;
						case '2':
							$titu = "NRO DE EXPEDIENTE";
							$titu2 = "QUERELLADO";
							$titu3 = "QUERELLANTE";
							$titu4 = "A&Ntilde;O";
							$titu5 = "AUTO DE ARCHIVO";
							break;
						case '3':
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
						<input type="text" name="fechaa" value="<?= $fechaa ?>" class="form-control">
					</td>
				</tr>

				<tr class="align-middle">
					<td class="fw-semibold"><?= $titu ?></td>
					<td>
						<input type="text" name="buscar_orden" value="<?= $buscar_orden ?>" class="form-control">
					</td>

					<td class="fw-semibold">RADICADO</td>
					<td>
						<input type="text" name="buscar_rad" value="<?= $buscar_rad ?>" class="form-control">
					</td>
				</tr>

				<tr class="align-middle">
					<td class="fw-semibold"><?= $titu2 ?></td>
					<td>
						<input type="text" name="buscar_deman" value="<?= $buscar_deman ?>" class="form-control">
					</td>

					<td class="fw-semibold"><?= $titu3 ?></td>
					<td>
						<input type="text" name="buscar_demant" value="<?= $buscar_demant ?>" class="form-control">
					</td>
				</tr>

				<tr class="align-middle">
					<td class="fw-semibold">
						FECHA INICIAL<br>
						<small class="text-muted">Desde</small>
						<?php
						$datoss = ($sep == 1) ? "checked" : "";
						?>
						<div class="form-check mt-1">
							<input class="form-check-input" type="checkbox" name="sep" value="1" <?= $datoss ?>>
							<label class="form-check-label">Activar</label>
						</div>
						<small class="text-muted">Hasta</small>
					</td>

					<td>
						<script language="javascript">
							<?php
							if (!$fechaIni) $fechaIni = fnc_date_calcm(date('Y-m-d'), '1');
							if (!$fechaInif) $fechaInif = date('Y-m-d');
							?>
							var dateAvailable1 = new ctlSpiffyCalendarBox(
								"dateAvailable1", "busqueda_central", "fechaIni", "btnDate1",
								"<?= $fechaIni ?>", scBTNMODE_CUSTOMBLUE
							);
							dateAvailable1.date = "<?= date('Y-m-d'); ?>";
							dateAvailable1.writeControl();
							dateAvailable1.dateFormat = "yyyy-MM-dd";
						</script>
						<br>
						<script language="javascript">
							var dateAvailable2 = new ctlSpiffyCalendarBox(
								"dateAvailable2", "busqueda_central", "fechaInif", "btnDate2",
								"<?= $fechaInif ?>", scBTNMODE_CUSTOMBLUE
							);
							dateAvailable2.date = "<?= date('Y-m-d'); ?>";
							dateAvailable2.writeControl();
							dateAvailable2.dateFormat = "yyyy-MM-dd";
						</script>
					</td>

					<td class="fw-semibold">
						FECHA FINAL<br>
						<small class="text-muted">Desde</small>
						<?php $datoss2 = ($sep2 == 1) ? "checked" : ""; ?>
						<div class="form-check mt-1">
							<input class="form-check-input" type="checkbox" name="sep2" value="1" <?= $datoss2 ?>>
							<label class="form-check-label">Activar</label>
						</div>
						<small class="text-muted">Hasta</small>
					</td>

					<td>
						<script language="javascript">
							<?php
							if (!$fechaIni2) $fechaIni2 = fnc_date_calcm(date('Y-m-d'), '1');
							if (!$fechaInif2) $fechaInif2 = date('Y-m-d');
							?>
							var dateAvailable3 = new ctlSpiffyCalendarBox(
								"dateAvailable3", "busqueda_central", "fechaIni2", "btnDate3",
								"<?= $fechaIni2 ?>", scBTNMODE_CUSTOMBLUE
							);
							dateAvailable3.date = "<?= date('Y-m-d'); ?>";
							dateAvailable3.writeControl();
							dateAvailable3.dateFormat = "yyyy-MM-dd";
						</script>
						<br>
						<script language="javascript">
							var dateAvailable4 = new ctlSpiffyCalendarBox(
								"dateAvailable4", "busqueda_central", "fechaInif2", "btnDate4",
								"<?= $fechaInif2 ?>", scBTNMODE_CUSTOMBLUE
							);
							dateAvailable4.date = "<?= date('Y-m-d'); ?>";
							dateAvailable4.writeControl();
							dateAvailable4.dateFormat = "yyyy-MM-dd";
						</script>
					</td>
				</tr>

				<tr class="align-middle">
					<td class="fw-semibold">OBSERVACIONES</td>
					<td>
						<input type="text" name="anexo" value="<?= $anexo ?>" class="form-control">
						<?php
						$proc = ($codserie == '300') ? 1 : 2;
						?>
					</td>

					<td class="fw-semibold">DEPENDENCIA</td>
					<td>
						<?php
						$conD = $db->conn->Concat("d.DEPE_CODI", "'-'", "d.DEPE_NOMB");
						$sql5 = "select distinct($conD) as detalle,d.DEPE_CODI from DEPENDENCIA d";
						if ($codserie != '0') {
							$sql5 .= " , SGD_MRD_MATRIRD m where m.depe_codi=d.depe_codi and m.sgd_srd_codigo='$codserie'";
						}
						$sql5 .= " order by d.DEPE_CODI";
						$rs = $db->conn->Execute($sql5);

						print $rs->GetMenu2('depen', $depen, true, false, "", "class='form-select'");
						?>
					</td>
				</tr>

				<tr class="align-middle">
					<td class="fw-semibold"><?= $titu4 ?></td>
					<td>
						<select class="form-select" name="buscar_ano">
							<?php
							$agnoactual = Date('Y');
							for ($i = 1975; $i <= $agnoactual; $i++) {
								if ($i == $buscar_ano) {
									echo "<option selected value='$buscar_ano'>$buscar_ano</option>";
								} elseif ($i == 1975) {
									echo "<option value=''>TODOS</option>";
								} else {
									echo "<option value='$i'>$i</option>";
								}
							}
							?>
						</select>
						<input type="hidden" name="yea" value="<?= $buscar_ano ?>">
					</td>

					<td class="fw-semibold">DOCUMENTO DE IDENTIDAD</td>
					<td>
						<input type="text" name="buscar_docu" value="<?= $buscar_docu ?>" class="form-control">
					</td>
				</tr>

				<tr class="align-middle">
					<td class="fw-semibold">ZONA</td>
					<td>
						<input type="text" name="buscar_zona" value="<?= $buscar_zona ?>" class="form-control" maxlength="3">
					</td>

					<td class="fw-semibold"><?= $item1 ?></td>
					<td>
						<input type="text" name="buscar_carro" value="<?= $buscar_carro ?>" class="form-control" maxlength="3">
					</td>
				</tr>

				<tr class="align-middle">
					<td class="fw-semibold"><?= $item3 ?></td>
					<?php
					$sec1 = ($buscar_cara == "A") ? "checked" : "";
					$sec2 = ($buscar_cara == "B") ? "checked" : "";
					$sec3 = (!$buscar_cara) ? "checked" : "";
					?>
					<td>
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

					<td class="fw-semibold"><?= $item4 ?></td>
					<td>
						<input type="text" name="buscar_estante" value="<?= $buscar_estante ?>" class="form-control" maxlength="5">
					</td>
				</tr>

				<tr class="align-middle">
					<td class="fw-semibold"><?= $item5 ?></td>
					<td>
						<input type="text"
							name="buscar_entre"
							value="<?= $buscar_entre ?>"
							class="form-control"
							maxlength="5">
					</td>

					<td class="fw-semibold"><?= $item6 ?></td>
					<td>
						<input type="text"
							name="buscar_caja"
							value="<?= $buscar_caja ?>"
							class="form-control"
							maxlength="5">
					</td>
				</tr>

				<tr class="align-middle">
					<td class="fw-semibold">INDICADORES DE DETERIORO</td>
					<td>
						<?php
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
							case 6:
								$sele48 = "selected";
								break;
							case 7:
								$sele49 = "selected";
								break;
							case 8:
								$sele50 = "selected";
								break;
							case 9:
								$sele51 = "selected";
								break;
							case 10:
								$sele52 = "selected";
								break;
							case 11:
								$sele53 = "selected";
								break;
							case 12:
								$sele54 = "selected";
								break;
							case 13:
								$sele55 = "selected";
								break;
							case 14:
								$sele56 = "selected";
								break;
							case 15:
								$sele57 = "selected";
								break;
							case 16:
								$sele58 = "selected";
								break;
							case 17:
								$sele59 = "selected";
								break;
							case 18:
								$sele60 = "selected";
								break;
							case 19:
								$sele61 = "selected";
								break;
							case 20:
								$sele62 = "selected";
								break;
							case 21:
								$sele63 = "selected";
								break;
							default:
								$sele0 = "selected";
								break;
						}
						?>
						<select class="form-select" name="buscar_inder">
							<option value="0" <?= $sele0 ?>>Ninguno</option>
							<option value="1" <?= $sele1 ?>>Biológicos: Hongos</option>
							<option value="2" <?= $sele2 ?>>Biológicos: Roedores</option>
							<option value="3" <?= $sele3 ?>>Biológicos: Insectos</option>
							<option value="4" <?= $sele4 ?>>Decoloración Soporte</option>
							<option value="5" <?= $sele5 ?>>Desgarros</option>
							<option value="6" <?= $sele48 ?>>Hongos + Roedores</option>
							<option value="7" <?= $sele49 ?>>Hongos + Insectos</option>
							<option value="8" <?= $sele50 ?>>Hongos + Decoloración</option>
							<option value="9" <?= $sele51 ?>>Hongos + Desgarros</option>
							<option value="10" <?= $sele52 ?>>Roedores + Insectos</option>
							<option value="11" <?= $sele53 ?>>Roedores + Decoloración</option>
							<option value="12" <?= $sele54 ?>>Roedores + Desgarros</option>
							<option value="13" <?= $sele55 ?>>Insectos + Decoloración</option>
							<option value="14" <?= $sele56 ?>>Insectos + Desgarros</option>
							<option value="15" <?= $sele57 ?>>Decoloración + Desgarros</option>
							<option value="16" <?= $sele58 ?>>Hongos + Roedores + Insectos</option>
							<option value="17" <?= $sele59 ?>>Hongos + Roedores + Decoloración</option>
							<option value="18" <?= $sele60 ?>>Hongos + Roedores + Desgarros</option>
							<option value="19" <?= $sele61 ?>>Hongos + Insectos + Decoloración</option>
							<option value="20" <?= $sele62 ?>>Hongos + Insectos + Desgarros</option>
							<option value="21" <?= $sele63 ?>>Hongos + Decoloración + Desgarros</option>
						</select>
					</td>

					<td class="fw-semibold">MATERIALES INSERTADOS</td>
					<td>
						<?php
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
							case 22:
								$sele28 = "selected";
								break;
							case 23:
								$sele29 = "selected";
								break;
							case 24:
								$sele30 = "selected";
								break;
							case 25:
								$sele31 = "selected";
								break;
							case 26:
								$sele32 = "selected";
								break;
							case 27:
								$sele33 = "selected";
								break;
							case 28:
								$sele34 = "selected";
								break;
							case 29:
								$sele35 = "selected";
								break;
							case 30:
								$sele36 = "selected";
								break;
							case 31:
								$sele37 = "selected";
								break;
							case 32:
								$sele38 = "selected";
								break;
							case 33:
								$sele39 = "selected";
								break;
							case 34:
								$sele40 = "selected";
								break;
							case 35:
								$sele41 = "selected";
								break;
							case 36:
								$sele42 = "selected";
								break;
							case 37:
								$sele43 = "selected";
								break;
							case 38:
								$sele44 = "selected";
								break;
							case 39:
								$sele45 = "selected";
								break;
							case 40:
								$sele46 = "selected";
								break;
							case 41:
								$sele47 = "selected";
								break;
							default:
								$sele64 = "selected";
								break;
						}
						?>
						<select class="form-select" name="buscar_mata">
							<option value="0" <?= $sele64 ?>>Ninguno</option>
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
							<option value="21" <?= $sele27 ?>>Soporte Optico y Soporte Magnetico</option>
							<option value="22" <?= $sele28 ?>>Metalico, Post-it y Planos</option>
							<option value="23" <?= $sele29 ?>>Metalico, Post-it y Fotografia</option>
							<option value="24" <?= $sele30 ?>>Metalico, Post-it y Soporte Optico</option>
							<option value="25" <?= $sele31 ?>>Metalico, Post-it y Soporte Magnetico</option>
							<option value="26" <?= $sele32 ?>>Metalico, Planos y Fotografia</option>
							<option value="27" <?= $sele33 ?>>Metalico, Planos y Soporte Optico</option>
							<option value="28" <?= $sele34 ?>>Metalico, Planos y Soporte Magnetico</option>
							<option value="29" <?= $sele35 ?>>Metalico, Fotografia y Soporte Optico</option>
							<option value="30" <?= $sele36 ?>>Metalico, Fotografia y Soporte Magnetico</option>
							<option value="31" <?= $sele37 ?>>Metalico, Soporte Optico y Soporte Magnetico</option>
							<option value="32" <?= $sele38 ?>>Post-it, Planos y Fotografia</option>
							<option value="33" <?= $sele39 ?>>Post-it, Planos y Soporte Optico</option>
							<option value="34" <?= $sele40 ?>>Post-it, Planos y Soporte Magnetico</option>
							<option value="35" <?= $sele41 ?>>Post-it, Fotografia y Soporte Optico</option>
							<option value="36" <?= $sele42 ?>>Post-it, Fotografia y Soporte Magnetico</option>
							<option value="37" <?= $sele43 ?>>Post-it, Soporte Optico y Soporte Magnetico</option>
							<option value="38" <?= $sele44 ?>>Planos, Fotografia y Soporte Optico</option>
							<option value="39" <?= $sele45 ?>>Planos, Fotografia y Soporte Magnetico</option>
							<option value="40" <?= $sele46 ?>>Planos, Soporte Optico y Soporte Magnetico</option>
							<option value="41" <?= $sele47 ?>>Fotografia, Soporte Optico y Soporte Magnetico</option>
						</select>
					</td>
				</tr>

				<tr class="align-middle">
					<td class="fw-semibold text-end">
						PRÉSTAMO
					</td>

					<?php
					if ($presta == 1) {
						$de = "checked";
					} else {
						$de = "";
					}
					?>

					<td class="text-start">
						<div class="form-check">
							<input class="form-check-input"
								type="checkbox"
								name="presta"
								value="1"
								<?= $de ?>>
						</div>
					</td>

					<td class="fw-semibold text-start">
						FECHA RADICADO<br>
						<div class="mt-1">
							Desde
							<?php
							if ($sep3 == 1) {
								$datoss3 = "checked";
							} else {
								$datoss3 = "";
							}
							?>
							<input name="sep3"
								type="checkbox"
								class="form-check-input ms-2"
								value="1"
								<?= $datoss3 ?>>
							<span class="ms-4">Hasta</span>
						</div>
					</td>

					<td>
						<?php
						if (!$fechaIni3) $fechaIni3 = fnc_date_calcm(date('Y-m-d'), '1');
						if (!$fechaInif3) $fechaInif3 = date('Y-m-d');
						?>

						<script language="javascript">
							var dateAvailable5 = new ctlSpiffyCalendarBox(
								"dateAvailable5",
								"busqueda_central",
								"fechaIni3",
								"btnDate5",
								"<?= $fechaIni3 ?>",
								scBTNMODE_CUSTOMBLUE
							);
							dateAvailable5.date = "<?= date('Y-m-d'); ?>";
							dateAvailable5.writeControl();
							dateAvailable5.dateFormat = "yyyy-MM-dd";
						</script>

						<div class="mt-2"></div>

						<script language="javascript">
							var dateAvailable6 = new ctlSpiffyCalendarBox(
								"dateAvailable6",
								"busqueda_central",
								"fechaInif3",
								"btnDate6",
								"<?= $fechaInif3 ?>",
								scBTNMODE_CUSTOMBLUE
							);
							dateAvailable6.date = "<?= date('Y-m-d'); ?>";
							dateAvailable6.writeControl();
							dateAvailable6.dateFormat = "yyyy-MM-dd";
						</script>
					</td>
				</tr>

				<tr>
					<td colspan="2" class="text-end pt-3">
						<input type="submit"
							value="Buscar"
							name="Buscar"
							class="btn btn-primary">
					</td>

					<td colspan="2" class="text-start pt-3">
						<a href="archivo.php?<?= session_name() ?>=<?= trim(session_id()) ?>krd=<?= $krd ?>">
							<input type="button"
								name="Regresar"
								id="envia22"
								value="Regresar"
								class="btn btn-outline-secondary">
						</a>
					</td>
				</tr>
			</tbody>
		</table>

		<br>

		<?
		if ($Buscar) {
			include("$ruta_raiz/include/query/archivo/queryBusqueda_central.php");
			$dbg = $db->conn->Execute($sqla);
			if (!$dbg->EOF) $usua_perm_archi = $dbg->fields['USUA_ADMIN_ARCHIVO'];
			switch ($codiSRD) {
				case '1':
					$it1 = "NRO DE EXPEDIENTE";
					$it2 = "TITULO";
					$it3 = "NRO CONSECUTIVO";
					$it4 = "A&Ntilde;O";
					$it5 = "";
					break;
				case '2':
					$it1 = "NRO DE EXPEDIENTE";
					$it2 = "QUERELLADO";
					$it3 = "QUERELLANTE";
					$it4 = "A&Ntilde;O";
					$it5 = "AUTO DE ARCHIVO";
					break;
				case '3':
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
					$it5 = "AUTO";
					break;
			}
		?>
			<table border=0 width 100% cellpadding="1" class="borde_tab">
				<tr>
					<TD class=titulos5>RADICADO
					<TD class=titulos5>FECHA RADICADO
					<TD class=titulos5><?= $it1 ?>
					<TD class=titulos5>FECHA INICIAL
					<TD class=titulos5>FECHA FINAL
					<TD class=titulos5><?= $it4 ?>
					<TD class=titulos5>DEPENDENCIA
					<TD class=titulos5><?= $it2 ?>
					<TD class=titulos5><?= $it3 ?>
					<TD class=titulos5>DOCUMENTO DE IDENTIDAD <?= $it2 ?>
						<?
						if ($codiSRD == '200') $tp = "DOCUMENTO DE IDENTIDAD " . $it3;
						else $tp = "";
						?>
					<TD class=titulos5>DOCUMENTO DE IDENTIDAD QUERELLANTE
					<TD class=titulos5>DIRECCION
					<TD class=titulos5>SERIE
					<TD class=titulos5>SUBSERIE
					<TD class=titulos5>TIPO
					<TD class=titulos5>FOLIOS
					<TD class=titulos5>ZONA
					<TD class=titulos5>CARRO
					<TD class=titulos5>CARA
					<TD class=titulos5>CUERPO
					<TD class=titulos5>ENTREPA&Ntilde;O
					<TD class=titulos5>CAJA
					<TD class=titulos5>UNIDAD DOCUMENTAL
					<TD class=titulos5>NRO CARPETA
					<TD class=titulos5>OBSERVACIONES
					<TD class=titulos5>
						<p>INDICADORES DE DETERIORO</p>
					<TD class=titulos5>
						<p>MATERIAL AGREGADO</p>
					<TD class=titulos5><?= $it5 ?>
					<TD class=titulos5>PRESTAMO
					<TD class=titulos5>FUNCIONARIO PRESTAMO
					<TD class=titulos5>FECHA ENTREGA PRESTAMO
				</tr>

				<?
				if ($buscar_ano != "") {
					$x = "SGD_ARCHIVO_YEAR LIKE '$buscar_ano'";
					$a = "and";
				} else {
					$x = "";
					$a = "";
				}
				if ($buscar_rad != "") {
					$r = "SGD_ARCHIVO_RAD LIKE '%$buscar_rad%'";
					$b = "and";
				} else {
					$r = "";
					$b = "";
				}
				if ($codserie != '0') {
					$srds = "SGD_ARCHIVO_SRD LIKE '$codiSRD'";
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
					$docu = "(SGD_ARCHIVO_CC_DEMANDANTE LIKE '%$buscar_docu%' or SGD_ARCHIVO_DOCU2 LIKE '%$buscar_docu%')";
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
					$fea = "SGD_ARCHIVO_FECHAA like '$fechaa' ";
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
				$pru = $c . $d . $ef . $b . $a . $f . $g . $i . $h . $v . $t . $k . $l . $j . $w . $wq . $n . $m . $o . $p . $q . $pt . $fea . $tic . $an;
				if ($pru != "") {
					$de = $db->conn->Execute("select depe_codi from usuario where usua_login like '$krd'");
					$depek = $de->fields['DEPE_CODI'];
					include("$ruta_raiz/include/query/archivo/queryBusqueda_central.php");
					//$db->conn->debug=true;
					$rs = $db->conn->Execute($sql);
					while (!$rs->EOF) {
						$orden1 = $rs->fields['NRO_ORDEN'];
						$sbrd = $rs->fields['SUBSERIE'];
						$srd = $rs->fields['SERIE'];
						$demandado = $rs->fields['QUERELLADO_O_OBJETO'];
						$demandante = $rs->fields['QUERELLANTE_O_CONTRATISTA'];
						$cc = $rs->fields['DOCUMENTO_DE_IDENTIDAD'];
						$docu2 = $rs->fields['DOCUMENTO_QUERELLADO'];
						$indet = $rs->fields['INDICADORES_DE_DETERIORO'];
						$mata1 = $rs->fields['MATERIAL_INSERTADO'];
						$fechi = $rs->fields['FECHA_INICIAL'];
						$fechf = $rs->fields['FECHA_FINAL'];
						$year = $rs->fields['VIGENCIA'];
						$caja1 = $rs->fields['CAJA'];
						$caja2 = $rs->fields['CAJA_HASTA'];
						$carro1 = $rs->fields['CARRO'];
						$cara1 = $rs->fields['CARA'];
						$radi = $rs->fields["RADICADO"];
						$estante1 = $rs->fields['ESTANTE'];
						$unidoc = $rs->fields['UNIDAD_DOCUMENTAL'];
						$dependencia = $rs->fields['DEPENDENCIA'];
						$entrepa1 = $rs->fields['ENTREPANO'];
						$folio = $rs->fields['FOLIOS'];
						//$path=$rs->fields['SGD_ARCHIVO_PATH'];
						$zona1 = $rs->fields['ZONA'];
						$anexo = $rs->fields['OBSERVACIONES'];
						$pres = $rs->fields['PRESTAMO'];
						$funprest = $rs->fields['FUNCIONARIO_PRESTAMO'];
						$fprestf = $rs->fields['FECHA_ENTREGA_PRESTAMO'];
						$fechaR = $rs->fields['FECHA_RADICADO'];
						$fecaa = $rs->fields['AUTO'];
						$procc = $rs->fields['TIPO'];
						$ncarp = $rs->fields['NRO_CARPETAS'];
						$dir = $rs->fields['DIRECCION'];

						if ($procc != 0) {
							$wet = $db->conn->Execute("select sgd_pexp_descrip from sgd_pexp_procexpedientes where sgd_pexp_codigo like'" . $procc . "'");
							$proce = $wet->fields['SGD_PEXP_DESCRIP'];
						}
						if ($pres == 1) $prest = "SI";
						else $prest = "NO";

						switch ($indet) {
							case '0':
								$indete = "Ninguno";
								break;
							case '1':
								$indete = "Biologicos: Hongos";
								break;
							case '2':
								$indete = "Biologicos: Roedores";
								break;
							case '3':
								$indete = "Biologicos: Insectos";
								break;
							case '4':
								$indete = "Decoloracion Soporte";
								break;
							case '5':
								$indete = "Desgarros";
								break;
							case '6':
								$indete = "Biologicos: Hongos y Biologicos: Roedores";
								break;
							case '7':
								$indete = "Biologicos: Hongos y Biologicos: Insectos";
								break;
							case '8':
								$indete = "Biologicos: Hongos y Decoloracion Soporte";
								break;
							case '9':
								$indete = "Biologicos: Hongos y Desgarros";
								break;
							case '10':
								$indete = "Biologicos: Roedores y Biologicos: Insectos";
								break;
							case '11':
								$indete = "Biologicos: Roedores y Decoloracion Soporte";
								break;
							case '12':
								$indete = "Biologicos: Roedores y Desgarros";
								break;
							case '13':
								$indete = "Biologicos: Insectos y Decoloracion Soporte";
								break;
							case '14':
								$indete = "Biologicos: Insectos y Desgarros";
								break;
							case '15':
								$indete = "Decoloracion Soporte y Desgarros";
								break;
							case '16':
								$indete = "Biologicos: Hongos, Biologicos: Roedores y Biologicos: Insectos";
								break;
							case '17':
								$indete = "Biologicos: Hongos, Biologicos: Roedores y Decoloracion Soporte";
								break;
							case '18':
								$indete = "Biologicos: Hongos, Biologicos: Roedores y Desgarros";
								break;
							case '19':
								$indete = "Biologicos: Hongos, Biologicos: Insectos y Decoloracion Soporte";
								break;
							case '20':
								$indete = "Biologicos: Hongos, Biologicos: Insectos y Desgarros";
								break;
							case '21':
								$indete = "Biologicos: Hongos, Decoloracion Soporte y Desgarros";
								break;
							case '22':
								$indete = "Biologicos: Roedores, Biologicos: Insectos y Decoloracion Soporte";
								break;
							case '23':
								$indete = "Biologicos: Roedores, Biologicos: Insectos y Desgarros";
								break;
							case '24':
								$indete = "Biologicos: Roedores, Decoloracion Soporte y Desgarros";
								break;
							case '25':
								$indete = "Biologicos: Insectos, Decoloracion Soporte y Desgarros";
								break;
							case '26':
								$indete = "Biologicos: Hongos, Biologicos: Roedores, Biologicos: Insectos y Decoloracion Soporte";
								break;
							case '27':
								$indete = "Biologicos: Hongos, Biologicos: Roedores, Biologicos: Insectos y Desgarros";
								break;
							case '28':
								$indete = "Biologicos: Hongos, Biologicos: Roedores, Decoloracion Soporte y Desgarros";
								break;
							case '29':
								$indete = "Biologicos: Hongos, Biologicos: Insectos, Decoloracion Soporte y Desgarros";
								break;
							case '30':
								$indete = "Biologicos: Roedores, Biologicos: Insectos, Decoloracion Soporte y Desgarros";
								break;
							case '31':
								$indete = "Biologicos: Hongos, Biologicos: Roedores, Biologicos: Insectos, Decoloracion Soporte y Desgarros";
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
								$mata2 = "Metalico, Post-it y Planos";
								break;
							case '23':
								$mata2 = "Metalico, Post-it y Fotografia";
								break;
							case '24':
								$mata2 = "Metalico, Post-it y Soporte Optico";
								break;
							case '25':
								$mata2 = "Metalico, Post-it y Soporte Magnetico";
								break;
							case '26':
								$mata2 = "Metalico, Planos y Fotografia";
								break;
							case '27':
								$mata2 = "Metalico, Planos y Soporte Optico";
								break;
							case '28':
								$mata2 = "Metalico, Planos y Soporte Magnetico";
								break;
							case '29':
								$mata2 = "Metalico, Fotografia y Soporte Optico";
								break;
							case '30':
								$mata2 = "Metalico, Fotografia y Soporte Magnetico";
								break;
							case '31':
								$mata2 = "Metalico, Soporte Optico y Soporte Magnetico";
								break;
							case '32':
								$mata2 = "Post-it, Planos y Fotografia";
								break;
							case '33':
								$mata2 = "Post-it, Planos y Soporte Optico";
								break;
							case '34':
								$mata2 = "Post-it, Planos y Soporte Magnetico";
								break;
							case '35':
								$mata2 = "Post-it, Fotografia y Soporte Optico";
								break;
							case '36':
								$mata2 = "Post-it, Fotografia y Soporte Magnetico";
								break;
							case '37':
								$mata2 = "Post-it, Soporte Optico y Soporte Magnetico";
								break;
							case '38':
								$mata2 = "Planos, Fotografia y Soporte Optico";
								break;
							case '39':
								$mata2 = "Planos, Fotografia y Soporte Magnetico";
								break;
							case '40':
								$mata2 = "Planos, Soporte Optico y Soporte Magnetico";
								break;
							case '41':
								$mata2 = "Fotografia, Soporte Optico y Soporte Magnetico";
								break;
						}
				?>
						<tr>
							<td class=leidos2 align="center">
								<?
								$rs2 = $db->conn->Execute("select DEPE_CODI from sgd_archivo_central where sgd_archivo_rad like '$radi'");
								$depen = $rs2->fields['DEPE_CODI'];
								if ($usua_perm_archi >= 3 and ($depek == $depen or $depek == '623')) {
								?>
									<a href='insertar_central.php?<?= session_name() . "=" . session_id() . "&krd=$krd&fechah=$fechah&$orno&adodb_next_page&edi=1&rad=$radi" ?>'>
									<? } ?>
									<?= $radi ?></a>
							</td>
							<td class=titulos5 align="center"><b><a href='verHistoricoArch.php?<?= session_name() . "=" . session_id() . "&krd=$krd&fechah=$fechah&$orno&adodb_next_page&rad=$radi" ?>'><?= $fechaR ?></b></td>
							<td class=leidos2 align="center"><b><?= $orden1 ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2> <?= $fechi ?></b></td>
							<td class=leidos2 align="center"> <b><span class=leidos2><?= $fechf ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><?= $year ?></b></td>
							<td class=leidos2 align="center"><b><span class=leidos2><?= $dependencia ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><?= $demandado ?></b></td>
							<td class=leidos2 align="center"><b><span class=leidos2><?= $demandante ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><?= $cc ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><?= $docu2 ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><?= $dir ?></b></td>
							<td class=leidos2 align="center"><b><span class=leidos2><?= $srd ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><?= $sbrd ?></b></td>
							<td class=leidos2 align="center"><b><span class=leidos2><?= $proce ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><?= $folio ?></b></td>
							<td class=leidos2 align="center"><b><span class=leidos2><?= $zona1 ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><?= $carro1 ?></b></td>
							<td class=leidos2 align="center"><b><span class=leidos2><?= $cara1 ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><?= $estante1 ?></b></td>
							<td class=leidos2 align="center"><b><span class=leidos2><?= $entrepa1 ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><? echo $caja1;
																						if ($caja2 != "") echo " a la " . $caja2; ?></b></td>
							<td class=leidos2 align="center"><b><span class=leidos2><?= $unidoc ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><?= $ncarp ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><?= $anexo ?></b></td>
							<td class=leidos2 align="center"><b><span class=leidos2><?= $indete ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><?= $mata2 ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><?= $fecaa ?></b></td>
							<td class=leidos2 align="center"><b><span class=leidos2><?= $prest ?></b></td>
							<td class=titulos5 align="center"><b><span class=leidos2><?= $funprest ?></b></td>
							<td class=leidos2 align="center"><b><span class=leidos2><?= $fprestf ?></b></td>
						</tr>
				<?
						$cont++;
						$rs->MoveNext();
					}

					include_once('../adodb/toexport.inc.php');

					$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

					$rs = $db->query($sql);

					$archivoCSV = $ruta_raiz . "/bodega/tmp/B_$krd.xls";

					$fp = fopen($archivoCSV, "w");
					if ($fp) {
						fwrite($fp, iconv("UTF-8", "ISO-8859-1", rs2csv($rs)));
						fclose($fp);
					}
					require_once('../adodb/excel.inc.php');
					//$tit=array("RADICADO","FECHA_RADICADO","NRO_ORDEN","FECHA_INICIAL","FECHA_FINAL","VIGENCIA","DEPENDENCIA","QUERELLANTE_O_CONTRATISTA","QUERELLADO_O_OBJETO","DOCUMENTO_DE_IDENTIDAD","DOCUMENTO_QUERELLADO","DIRECCION","SERIE","SUBSERIE","TIPO","FOLIOS","ZONA","CARRO","CARA","ESTANTE","ENTREPANO","CAJA","CAJA_HASTA","UNIDAD_DOCUMENTAL","NRO_CARPETAS","OBSERVACIONES","INDICADORES_DE_DETERIORO","MATERIAL_INSERTADO","AUTO","PRESTAMO");
					$tit = array("NRO_ORDEN", "SERIE", "SUBSERIE", "TIPO", "FOLIOS", "CAJA", "NRO_CARPETAS");
					$gerar = new sql2excel($tit, $sql, $db); //using $db pointer by default
				} else echo "DEBE SELECCIONAR O LLENAR ALGUNA OPCION";
				?>
			</table>
			<br>
			<center>
				<?= $cont ?> Archivos Encontrados<br>
				<a href="<?php print $archivoCSV; ?>" class="botones_largo">VER ARCHIVO</a>
			</center>
		<?
		}
		?>
	</form>
</body>

</html>
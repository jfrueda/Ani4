<?php
/*
CARLOS BARRERO - carlosabc81@gmail.com
 Mejora reasiganción y ajax de cargue de archivos
 */
session_start();
$ruta_raiz = "..";
require_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once "$ruta_raiz/include/tx/Historico.php";
include_once "$ruta_raiz/class_control/TipoDocumental.php";
include "$ruta_raiz/include/tx/Tx.php";
include "$ruta_raiz/htmlheader.inc.php";

if (!$db) {
	$db = new ConnectionHandler($ruta_raiz);
}

$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

if ($_POST['reasigna']) {
	$usuo = explode("-", $_POST['usuario']);
	$usud = explode("-", $_POST['usuario2']);

	//$db->conn->debug=true;
	if ($_POST['todos'] != 1) {
		$radicados = explode(",", trim($_POST['radicado']));
	} else {
		//funcion para traer los todos los radicados del usuario origen
		$radicados = rad_all($usuo[0], $usuo[1], $db);
		//valida si los radicados pertenecen al usuario origen
		$validacion = valida($radicados, $usuo[1], $usuo[0], $db);
		if ($validacion == 0) {
			$tx = new Tx($db);
			//$tx->reasignar($radicados, $usuo[2],$usud[1],$usuo[1],$usud[0],$usuo[0],'si','REASIGNACION 	AUTOMATICA',202,0);
			$tx->reasignar($radicados, $usuo[2], $usud[1], $usuo[1], $usud[0], $usuo[0], 'si', "Reasignación masiva: " . $_POST['obs'], 9, 0);
		} else {
			echo "<center><h2><font color='#FF0000'>Error en el radicado no. " . $validacion . " este radicado no pertenece al usuario origen. La transacci&oacute;n no se pudo completar.</h2></font></center>";
		}
	}
}

function nombre_usuario($codigo, $dependencia, $db)
{
	$sqln = "SELECT USUA_NOMB FROM USUARIO WHERE USUA_CODI=" . $codigo . " AND DEPE_CODI=" . $dependencia;
	$rs_n = $db->conn->Execute($sqln);
	$nombre = $rs_n->fields['USUA_NOMB'];
	echo $nombre;
}

function nombre_dependencia($codep, $db)
{
	$sqldep = "SELECT DEPE_NOMB FROM DEPENDENCIA WHERE DEPE_CODI=" . $codep;
	$rs_dep = $db->conn->Execute($sqldep);
	echo $rs_dep->fields['DEPE_NOMB'];
}

function rad_all($codigo, $dependencia, $db)
{
	//$sql_all="SELECT DISTINCT RADI_NUME_RADI FROM HIST_EVENTOS WHERE DEPE_CODI=".$dependencia." AND USUA_CODI=".$codigo;
	$sql_all = "SELECT RADI_NUME_RADI FROM RADICADO WHERE RADI_DEPE_ACTU=" . $dependencia . " AND RADI_USUA_ACTU=" . $codigo;
	$rs_all = $db->conn->Execute($sql_all);
	while (!$rs_all->EOF) {
		$rad[] = $rs_all->fields['RADI_NUME_RADI'];
		$rs_all->MoveNext();
	}
	return $rad;
}

function valida($radicados, $dependencia, $codigo, $db)
{
	$respuesta = 0;
	foreach ($radicados as $row) {
		$sql_val = "SELECT  COUNT(*) CONT 
                  FROM  RADICADO
                  WHERE RADI_NUME_RADI=" . $row . " AND
                        RADI_DEPE_ACTU=" . $dependencia . " AND
                        RADI_USUA_ACTU=" . $codigo;
		//			echo $sql_val;
		$rs_val = $db->conn->query($sql_val);
		$total = $rs_val->fields['CONT'];
		if ($total < 1) {
			$respuesta = $row;
			break;
		}
	}
	return $respuesta;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<script>
		function deshabilita(opcion) {
			if (opcion == true)
				document.reasignacion.radicado.disabled = true;
			else
				document.reasignacion.radicado.disabled = false;
		}
	</script>
	<link rel="stylesheet" href="<?= $ruta_raiz ?>/estilos/orfeo.css" type="text/css">
	<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
</head>

<body>
	<form method="post" action="rea_aut_rad.php" name="reasignacion">
		<div class="container-fluid mb-1">
			<div class="row justify-content-center">
				<div class="col-12 ">
					<div class="card shadow ">
						<div class="card-header bg-orfeo text-white text-center py-3">
							<h5 class="mb-0 fw-bold">REASIGNACION AUTOMATICA DE RADICADOS</h5>
						</div>

						<div class="card-body bg-light p-4">
							<div class="mb-3 row align-items-center">
								<label for="usuario" class="col-sm-4 col-form-label fw-bold text-secondary">USUARIO ORIGEN</label>
								<div class="col-sm-8">
									<select id='usuario' name="usuario" class="form-select shadow-sm" onchange='getRads();'>
										<?php
										$sql_usu = "SELECT USUA_NOMB, USUA_CODI, DEPE_CODI, USUA_LOGIN FROM USUARIO where usua_esta='1' ORDER BY USUA_NOMB ";
										$rs_usu = $db->conn->Execute($sql_usu);
										while (!$rs_usu->EOF) {
										?>
											<option value="<?= $rs_usu->fields['USUA_CODI'] . "-" . $rs_usu->fields['DEPE_CODI'] . "-" . $rs_usu->fields['USUA_LOGIN']; ?>"><?= strtoupper($rs_usu->fields['USUA_NOMB']) ?></option>
										<?
											$rs_usu->MoveNext();
										}
										?>
									</select>
								</div>
							</div>
							<div class="mb-3 row align-items-center">
								<label class="col-sm-4 col-form-label fw-bold text-secondary">USUARIO DESTINO</label>
								<div class="col-sm-8">
									<select name="usuario2" class="form-select shadow-sm">
										<?
										$sql_usu = "SELECT USUA_NOMB, USUA_CODI, DEPE_CODI, USUA_LOGIN FROM USUARIO where usua_esta='1' ORDER BY USUA_NOMB";
										$rs_usu = $db->conn->Execute($sql_usu);
										while (!$rs_usu->EOF) {
										?>
											<option value="<?= $rs_usu->fields['USUA_CODI'] . "-" . $rs_usu->fields['DEPE_CODI'] . "-" . $rs_usu->fields['USUA_LOGIN']; ?>"><?= strtoupper($rs_usu->fields['USUA_NOMB']) ?></option>
										<?
											$rs_usu->MoveNext();
										}
										?>
									</select>
								</div>
							</div>
							<hr class="text-muted">
							<div class="mb-3 row align-items-center">
								<label class="col-sm-4 form-check-label fw-bold text-secondary">TODOS LOS RADICADOS</label>
								<div class="col-sm-8">
									<div class="form-check form-switch fs-5">
										<input name="todos" type="checkbox" id="todos" value="1" class="form-check-input shadow-sm" onclick="deshabilita(this.checked);" />
									</div>
								</div>
							</div>
							<div class="mb-3">
								<label for="radicado" class="form-label fw-bold text-secondary">No. RADICADO(S) <small class="text-muted fw-normal">(Separados por -)</small></label>
								<textarea name="radicado" id="radicado" class="form-control shadow-sm" rows="2"></textarea>
							</div>
							<div class="mb-4">
								<label for="obs" class="form-label fw-bold text-secondary">Observaciones</label>
								<textarea name="obs" id="obs" class="form-control shadow-sm" rows="3" placeholder="Escriba el motivo de la reasignación..."></textarea>
							</div>
						</div>
						<div class="card-footer bg-white py-3 text-center border-top-0">
							<input type="submit" name="Submit" value="Asignar" class="btn btn-success px-5 fw-bold shadow border-2" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="reasigna" value="reasigna" />
	</form>

	<? if (($_POST['reasigna']) && ($validacion == 0)) { ?>
		<br />
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col-12 ">
					<div class="card shadow border-0">
						<div class="card-header bg-success text-white text-center py-3">
							<h5 class="mb-0 fw-bold">
								<i class="fa fa-check-circle me-2"></i>ACCION REQUERIDA COMPLETADA
							</h5>
						</div>

						<div class="card-body p-0">
							<ul class="list-group list-group-flush">
								<li class="list-group-item d-flex align-items-start p-3">
									<div class="fw-bold text-secondary text-end w-40 me-3">ACCION REQUERIDA :</div>
									<div class="text-dark">REASIGNACION AUTOMATICA</div>
								</li>

								<li class="list-group-item d-flex align-items-start p-3 bg-light">
									<div class="fw-bold text-secondary text-end w-40 me-3">RADICADOS INVOLUCRADOS :</div>
									<div class="text-primary fw-bold">
										<?php
										foreach ($radicados as $value) {
											echo '<span class="badge bg-white text-primary border border-primary mb-1">' . $value . '</span><br>';
										}
										?>
									</div>
								</li>

								<li class="list-group-item d-flex align-items-start p-3">
									<div class="fw-bold text-secondary text-end w-40 me-3">USUARIO DESTINO :</div>
									<div class="text-dark"><?php nombre_usuario($usud[0], $usud[1], $db); ?></div>
								</li>

								<li class="list-group-item d-flex align-items-start p-3 bg-light">
									<div class="fw-bold text-secondary text-end w-40 me-3">FECHA Y HORA :</div>
									<div class="text-dark"><i class="fa fa-calendar-o me-1"></i> <?= date("m-d-Y  H:i:s") ?></div>
								</li>

								<li class="list-group-item d-flex align-items-start p-3">
									<div class="fw-bold text-secondary text-end w-40 me-3">USUARIO ORIGEN :</div>
									<div class="text-dark"><?php nombre_usuario($usuo[0], $usuo[1], $db); ?></div>
								</li>

								<li class="list-group-item d-flex align-items-start p-3 bg-light">
									<div class="fw-bold text-secondary text-end w-40 me-3">DEPENDENCIA ORIGEN :</div>
									<div class="text-dark"><?php nombre_dependencia($usuo[1], $db) ?></div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	<? } ?>

	<div class="container-fluid margin-botton-table">
		<div class="row justify-content-center">
			<div class="col-12">
				<div class="card shadow-sm ">
					<div class="card-header bg-orfeo text-white text-center py-2">
						<h6 class="mb-0 fw-bold">LISTADO DE RADICADOS</h6>
					</div>

					<div class="card-body bg-light">
						<div class="row g-3">
							<div class="col-md-2 text-md-end">
								<label for="urads" class="form-label fw-bold text-secondary mt-2">
									Rads del usuario:
								</label>
							</div>

							<div class="col-md-10">
								<div class="input-group shadow-sm">
									<span class="input-group-text bg-white border-end-0">
										<i class="fa fa-file-text-o text-muted"></i>
									</span>
									<textarea
										name="urads"
										id="urads"
										class="form-control border-start-0"
										rows="15"
										style="font-family: 'Courier New', Courier, monospace; font-size: 0.9rem; resize: vertical;"
										placeholder="Los números de radicado aparecerán aquí..."><?= isset($urads) ? $urads : '' ?></textarea>
								</div>
								<div class="form-text mt-2 italic text-muted small">
									<i class="fa fa-info-circle me-1"></i> Visualización de registros cargados actualmente en el sistema.
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		function getRads() {
			$.post("requestRadUser.php", {
					usua_login: $("#usuario").val()
				},
				function(data, status) {
					// Reemplazar las comas por saltos de línea para mostrar uno por línea
					var radicados = data.split(',').join('\n');
					$("#urads").val(radicados);
				});
		}

		// Ejecutar getRads() al cargar la página
		$(document).ready(function() {
			getRads();
		});
	</script>
</body>

</html>
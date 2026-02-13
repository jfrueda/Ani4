<?php

/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

SIIM2 Models are the data definition of SIIM2 Information System
Copyright (C) 2013 Infometrika Ltda.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();

$ruta_raiz = "..";
if (!$_SESSION['dependencia']) {
	header("Location: $ruta_raiz/cerrar_session.php");
}

$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc              = $_SESSION["usua_doc"];
$codusuario            = $_SESSION["codusuario"];
$tip3Nombre            = $_SESSION["tip3Nombre"];
$tip3desc              = $_SESSION["tip3desc"];
$tip3img               = $_SESSION["tip3img"];
$usua_perm_estadistica = $_SESSION["usua_perm_estadistica"];
foreach ($_GET as $key => $valor) {
	${$key} = $valor;
}
foreach ($_POST as $key => $valor) {
	${$key} = $valor;
}

$nomcarpeta = $_GET["carpeta"];
$tipo_carpt = $_GET["tipo_carpt"];
if ($_GET["orderNo"]) {
	$orderNo = $_GET["orderNo"];
}
if ($_GET["orderTipo"]) {
	$orderTipo = $_GET["orderTipo"];
}
if ($_GET["tipoEstadistica"]) {
	$tipoEstadistica = $_GET["tipoEstadistica"];
}
if ($_GET["genDetalle"]) {
	$genDetalle = $_GET["genDetalle"];
}
if ($_GET["dependencia_busq"]) {
	$dependencia_busq = $_GET["dependencia_busq"];
}
if ($_GET["fecha_ini"]) {
	$fecha_ini = $_GET["fecha_ini"];
}
if ($_GET["fecha_fin"]) {
	$fecha_fin = $_GET["fecha_fin"];
}
if ($_GET["codus"]) {
	$codus = $_GET["codus"];
}
if ($_GET["tipoRadicado"]) {
	$tipoRadicado = $_GET["tipoRadicado"];
}
if ($_GET["codUs"]) {
	$codUs = $_GET["codUs"];
}
if ($_GET["fecSel"]) {
	$fecSel = $_GET["fecSel"];
}
if ($_GET["genDetalle"]) {
	$genDetalle = $_GET["genDetalle"];
}
if ($_GET["generarOrfeo"]) {
	$generarOrfeo = $_GET["generarOrfeo"];
}

$ruta_raiz = "..";

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include "$ruta_raiz/class_control/usuario.php";

error_reporting(7);

if (!$tipoEstadistica) {
	$tipoEstadistica = 1;
}
if (!$dependencia_busq) {
	$dependencia_busq = $dependencia;
}

/** DEFINICION DE VARIABLES ESTADISTICA
 *	var $tituloE String array  Almacena el titulo de la Estadistica Actual
 * var $subtituloE String array  Contiene el subtitulo de la estadistica
 * var $helpE String Almacena array Almacena la descripcion de la Estadistica.
 */

$tituloE[1] = "PROCESOS - ESTADO GENERAL DE PROCESOS";
$tituloE[2] = "PROCESOS DETALLE FLUJO RADICADOS (INSTRUCTIVO 42)";
$subtituloE[1] = "ORFEO - Generada el: " . date("Y/m/d H:i:s") . "\n Parametros de Fecha: Entre $fecha_ini y $fecha_fin";

$helpE[1] = "Este reporte genera la cantidad de procesos asignados a cada usuario.  Ademas muestas una discriminaci&oacute;n por estado de los radicados.";
$helpE[2] = "";


$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
$objUsuario = new Usuario($db);

$ano_ini = date("Y");
$mes_ini = substr("00" . (date("m") - 1), -2);

if ($mes_ini == 0) {
	$ano_ini == $ano_ini - 1;
	$mes_ini = "12";
}
$dia_ini = date("d");
if (!$fecha_ini) {
	$fecha_ini = "$ano_ini/$mes_ini/$dia_ini";
}
$fecha_busq = date("Y/m/d");
if (!$fecha_fin) {
	$fecha_fin = $fecha_busq;
}

?>
<html>

<head>
	<title>..:: Caliope ::..</title>
	<?
	include_once "$ruta_raiz/htmlheader.inc.php";
	?>
</head>

<body topmargin="0" style="overflow-x:scroll">
	<form name="formulario" method="post" action='vistaFormProc.php?<?= session_name() . "=" . trim(session_id()) . "&krd=$krd&fechah=$fechah" ?>' class='container-fluid mt-4'>
		<div class="card shadow-sm border-0">
			<div class="card-header bg-orfeo text-white py-3">
				<h5 class="mb-0 d-flex align-items-center">
					<i class="bi bi-graph-up-arrow me-2"></i>
					<a href='vistaFormConsulta.php?<?= session_name() . "=" . trim(session_id()) . "&krd=$krd&fechah=$fechah" ?>' class="text-white text-decoration-none border-bottom border-white-50">
						Estadísticas
					</a>
					<span class="mx-2 opacity-75">/</span> PROCESOS
				</h5>
			</div>

			<div class="card-body p-4">
				<div class="alert alert-info border-0 shadow-sm mb-4">
					<div class="d-flex">
						<div class="me-2"><i class="bi bi-info-circle-fill"></i></div>
						<div class="small fw-medium"><?= $helpE[$tipoEstadistica] ?></div>
					</div>
				</div>

				<div class="row g-4">
					<div class="col-md-6">
						<div class="form-floating">
							<select name="tipoEstadistica" id="tipoEstadistica" class="form-select fw-semibold" onChange="formulario.submit();">
								<?php
								foreach ($tituloE as $key => $value) {
									$selectE = ($tipoEstadistica == $key) ? " selected " : ""; ?>
									<option value="<?= $key ?>" <?= $selectE ?>><?= $tituloE[$key] ?></option>
								<?php } ?>
							</select>
							<label for="tipoEstadistica" class="text-muted">Tipo de Consulta / Estadística</label>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-floating">
							<select name="dependencia_busq" id="dependencia_busq" class="form-select" onChange="formulario.submit();">
								<?php
								if ($usua_perm_estadistica > 1) {
									$datoss = ($dependencia_busq == 99999) ? " selected " : "";
								?>
									<option value="99999" <?= $datoss ?>>-- Todas las Dependencias --</option>
								<?php
								}

								$whereDepSelect = " DEPE_CODI = $dependencia ";
								if ($usua_perm_estadistica == 1) {
									$whereDepSelect = " $whereDepSelect or depe_codi_padre = $dependencia ";
								}
								if ($usua_perm_estadistica == 2) {
									$isqlus = "select a.DEPE_CODI,a.DEPE_NOMB,a.DEPE_CODI_PADRE from DEPENDENCIA a ORDER BY a.DEPE_NOMB";
								} else {
									$isqlus = "select a.DEPE_CODI,a.DEPE_NOMB,a.DEPE_CODI_PADRE from DEPENDENCIA a where $whereDepSelect ";
								}
								$rs1 = $db->query($isqlus);

								do {
									$codigo = $rs1->fields["DEPE_CODI"];
									$vecDeps[] = $codigo;
									$depnombre = $rs1->fields["DEPE_NOMB"];
									$datoss = ($dependencia_busq == $codigo) ? " selected " : "";
									echo "<option value=$codigo $datoss>$depnombre</option>";
									$rs1->MoveNext();
								} while (!$rs1->EOF);
								?>
							</select>
							<label for="dependencia_busq" class="text-muted">Dependencia</label>
						</div>
					</div>

					<?php
					if ($dependencia_busq != 99999) {
						$whereDependencia = " AND b.DEPE_CODI=$dependencia_busq ";
						$whereDependenciaU = " AND u.DEPE_CODI=$dependencia_busq ";
					}
					?>

					<?php if ($tipoEstadistica >= 1 && $tipoEstadistica <= 17) { ?>
						<div class="col-md-8">
							<div class="input-group">
								<div class="form-floating flex-grow-1">
									<select name="codus" id="codus" class="form-select border-end-0 rounded-start" onChange="formulario.submit();">
										<?php if ($usua_perm_estadistica > 0) { ?>
											<option value="0">-- AGRUPAR POR TODOS LOS USUARIOS --</option>
										<?php }
										$whereUsSelect = (!isset($_POST['usActivos'])) ? " u.USUA_ESTA = '1' " : "";
										$whereUsSelect = ($usua_perm_estadistica < 1) ?
											(($whereUsSelect != "") ? $whereUsSelect . " AND u.USUA_LOGIN='$krd' " : " u.USUA_LOGIN='$krd' ") : $whereUsSelect;

										if ($dependencia_busq != 99999) {
											$whereUsSelect = ($whereUsSelect == "") ? substr($whereDependenciaU, 4) : $whereUsSelect . $whereDependenciaU;
											$isqlus = "select u.USUA_NOMB,u.USUA_CODI,u.USUA_ESTA from USUARIO u where $whereUsSelect order by u.USUA_NOMB";
											$rs1 = $db->query($isqlus);
											while (!$rs1->EOF) {
												$codigo = $rs1->fields["USUA_CODI"];
												$vecDeps[] = $codigo;
												$usNombre = $rs1->fields["USUA_NOMB"];
												$datoss = ($codus == $codigo) ? " selected " : "";
												echo "<option value=$codigo $datoss>$usNombre</option>";
												$rs1->MoveNext();
											}
										}
										?>
									</select>
									<label for="codus">Usuario Responsable</label>
								</div>
								<div class="input-group-text bg-white p-3 border-start-0 rounded-end">
									<div class="form-check form-switch mb-0">
										<?php $datoss = isset($usActivos) && ($usActivos) ? " checked " : ""; ?>
										<input class="form-check-input" type="checkbox" name="usActivos" id="usActivos" <?= $datoss ?> onChange="formulario.submit();">
										<label class="form-check-label small text-muted ms-1" for="usActivos">Inactivos</label>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-floating">
								<select name="codAno" id="codAno" class="form-select" onChange="formulario.submit();">
									<option value='0'>-- Ver Consolidados --</option>
									<?php
									for ($iAno = $ano_ini; ($iAno >= ($ano_ini - 10)); $iAno--) {
										$datoss = ($codAno == $iAno) ? " selected " : "";
									?>
										<option value="<?= $iAno ?>" <?= $datoss ?>><?= $iAno ?></option>
									<?php } ?>
								</select>
								<label for="codAno">Año de Creación</label>
							</div>
						</div>
					<?php } ?>

					<div class="col-12">
						<div class="input-group">
							<span class="input-group-text bg-light text-muted small"><i class="bi bi-gear-fill me-2"></i> Tipo Proceso</span>
							<?php
							$sqlprocesos = "SELECT SGD_PEXP_DESCRIP,SGD_PEXP_CODIGO FROM SGD_PEXP_PROCEXPEDIENTES";
							$rs1 = $db->query($sqlprocesos);
							$valor = "";
							$default_str = isset($_POST['codProceso']) ? $_POST['codProceso'] : "";
							$nmenu = "codProceso";
							$itemBlanco = " -- Agrupar por Todos los Procesos -- ";
							echo $rs1->GetMenu2($nmenu, $default_str, $blank1stItem = "$valor:$itemBlanco", false, '0', ' class="form-select"');
							$codAno = isset($_POST['codAno']) && ($_POST['codAno'] != '0') ? $_POST['codAno'] : "";
							?>
						</div>
					</div>
				</div>
			</div>

			<div class="card-footer bg-light p-3 border-top">
				<div class="d-flex gap-2 justify-content-center">
					<input name="Submit" type="submit" class="btn btn-outline-secondary px-4 py-2 shadow-sm" value="Limpiar">
					<input type="submit" class="btn btn-primary px-5 py-2 shadow-sm fw-bold" value="Generar" name="generarOrfeo">
				</div>
			</div>
		</div>
	</form>

	<?
	$fecha_ini = isset($fecha_ini) ? $fecha_ini : "";
	$fecha_fin = isset($fecha_fin) ? $fecha_fin : "";
	$tipoDocumento = isset($tipoDocumento) ? $tipoDocumento : "";
	$codus = isset($codus) ? $codus : "";
	$tipoRadicado = isset($tipoRadicado) ? $tipoRadicado : "";
	$fechaf = isset($fechaf) ? $fechaf : "";
	$datosaenviar = urlencode("fechaf=$fechaf&tipoEstadistica=$tipoEstadistica&codus=$codus&krd=$krd&dependencia_busq=$dependencia_busq&ruta_raiz=$ruta_raiz&fecha_ini=$fecha_ini&fecha_fin=$fecha_fin&tipoRadicado=$tipoRadicado&tipoDocumento=$tipoDocumento&fechaano=$codAno");
	if (isset($_POST['generarOrfeo']) && $_POST['generarOrfeo'] === 'Generar') {
		include "genEstadisticaProc.php";
	}
	?>
</body>

</html>
<?php
// Enable error reporting for debugging


/**
 * @author Cesar Augusto <aurigadl@gmail.com>
 * @author Jairo Losada  <jlosada@gmail.com>
 * @author Correlibre.org // Tomado de version orginal realizada por JL en SSPD, modificado.
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 *
 * @copyleft
 * OrfeoGpl Models are the data definition of OrfeoGpl Information System
 * Copyright (C) 2014
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Fou@copyrightndation, either version 3 of the License, or
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
define('CIRC_INTERNA', 4);
define('CIRC_EXTERNA', 5);
define('RESOLUCION', 6);
define('AUTO', 7);
$ruta_raiz = ".";

if (!$_SESSION['dependencia']) header("Location: $ruta_raiz/cerrar_session.php");

include_once $ruta_raiz . "/include/tx/sanitize.php";
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$krd                =	$_SESSION["krd"];
$dependencia        =	$_SESSION["dependencia"];
$usua_doc           =	$_SESSION["usua_doc"];
$codusuario         =	$_SESSION["codusuario"];
$tip3Nombre         =	$_SESSION["tip3Nombre"];
$tip3desc           =	$_SESSION["tip3desc"];
$tip3img            =	$_SESSION["tip3img"];
$tpNumRad           =	$_SESSION["tpNumRad"];
$tpPerRad           =	$_SESSION["tpPerRad"];
$tpDescRad          =	$_SESSION["tpDescRad"];
$usuaPermExpediente =	$_SESSION["usuaPermExpediente"];
$nomcarpeta 		=	$_GET["nomcarpeta"];
$verradicado 		=	$_GET['verrad'];
$key 				=	$_GET['key'] ?? '';

if (!isset($ent))         $ent           = substr($verradicado, -1);
if (!isset($menu_ver_tmp)) $menu_ver_tmp  = $menu_ver_tmpOld;
if (!isset($menu_ver))     $menu_ver      = $menu_ver_Old;
if (!isset($menu_ver))     $menu_ver      = 3;
if ($menu_ver_tmp)	 $menu_ver      = $menu_ver_tmp;

if (!defined('ADODB_ASSOC_CASE')) define('ADODB_ASSOC_CASE', 1);
include_once "./processConfig.php";
include_once "./include/db/ConnectionHandler.php";
include_once("$ruta_raiz/include/crypt/Crypt.php");

if ($verradicado)	$verrad = $verradicado;
if (!$ruta_raiz)	$ruta_raiz = ".";
$numrad = $verrad;

$db = new ConnectionHandler('.');

$db->conn->SetFetchMode(3);

if ($carpeta == 8) {
	$info = 8;
	$nombcarpeta = "Informados";
}
include_once "$ruta_raiz/processConfig.php";
include_once "$ruta_raiz/class_control/Radicado.php";

$objRadicado = new Radicado($db);
$objRadicado->radicado_codigo($verradicado);
$path = $objRadicado->getRadi_path();

include_once "$ruta_raiz/tx/verLinkArchivo.php";
$verLinkArchivo = new verLinkArchivo($db);

$sqlIndicarBorrador = "SELECT is_borrador from radicado where radi_nume_radi = $verrad";
$rssqlIndicarBorrador = $db->conn->Execute($sqlIndicarBorrador);
$isBorrador = $rssqlIndicarBorrador->fields['IS_BORRADOR'];

/** verificacion si el radicado se encuentra en el usuario Actual*/
include "$ruta_raiz/tx/verifSession.php";

/* Segunda verificacion del radicado*/
$SeguridadRadicado = $objRadicado->getSpubCodigoRad();
$RadiDepeActuRAd   = $objRadicado->getRadiDepeActuRad();
$RadiUsuaActuRad   = $objRadicado->getRadiUsuaActuRad();

if (
	$ent == CIRC_INTERNA || $ent == CIRC_EXTERNA ||
	$ent == RESOLUCION || $ent == AUTO
) {
	$esNotificacion = true;
} else {
	$esNotificacion = false;
}

if ($ent == CIRC_INTERNA || $ent == CIRC_EXTERNA) {
	$esNotificacionCircular = true;
} else {
	$esNotificacionCircular = false;
}

?>
<html>

<head>
	<title>.: Modulo total :.</title>
	<?php include_once "htmlheader.inc.php"; ?>
	<!-- seleccionar todos los checkboxes-->

	<?PHP
	$resulVali = $verLinkArchivo->valPermisoRadi($numrad);

	$verImg = $resulVali['verImg'];
	$infolog = base64_encode(json_encode($resulVali['info']));

	/** verificacion si el radicado se encuentra en el usuario Actual*/
	include "$ruta_raiz/tx/verifRelacionados.php";
	require_once "$ruta_raiz/include/tx/RadicadoFilter.php";
	$radicadoFilter = new RadicadoFilter($db);
	//Se agrega variable de sesssion para ver desde el permiso o rol
	$acceso_por_expediente = false;

	if ($key != '') {
		$key = encrypt_decrypt('decrypt', $key, $radi_pass);
		$acceso_por_expediente = $objRadicado->validarLlaveRadicado($key);
	}

	if (
		($_SESSION["perm_rad_reser"] >= 1 && in_array($dependencia, $_SESSION['arrFitDep'])) ||
		($_SESSION['perm_cons_rad_cal'] >= 1) ||
		$acceso_por_expediente
	) {
		$verImg = 'SI';
	}

	if ($radicadoFilter->isDependenciaInFilter($verradicado, $_SESSION["dependencia"])) {
		$verImg = 'SI';
	}

	if ($verImg != "SI") {
		$msj = "NO tiene permiso para acceder a los datos del Radicado $numrad.";
		die('<div class="alert alert-warning alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<span class="glyphicon glyphicon-info-sign"></span> <strong>Upps !</strong> ' . $msj . ' </div>' .
			"<script>console.info('{$infolog}')</script>");
	}

	?>
	<?php if (!$acceso_por_expediente): ?>
		<?php if ($SeguridadRadicado == 0): ?>
			<div class="alert alert-success alert-dismissible  show shadow-sm rounded-3" role="alert">
				<span class="fw-bold me-2">🟢 Público:</span>
				Radicado público. Disponible para todos los usuarios con acceso al sistema.
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
			</div>

		<?php elseif ($SeguridadRadicado == 1): ?>
			<div class="alert alert-warning alert-dismissible  show shadow-sm rounded-3" role="alert">
				<span class="fw-bold me-2">🟡 Reservado:</span>
				Acceso exclusivo para usuarios y dependencias que hayan intervenido en el histórico del radicado.
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
			</div>

		<?php elseif ($SeguridadRadicado == 2): ?>
			<div class="alert alert-danger alert-dismissible  show shadow-sm rounded-3" role="alert">
				<span class="fw-bold me-2">🔴 Clasificado:</span>
				Solo los usuarios directamente involucrados en el histórico del radicado pueden consultarlo.
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<?php
		echo $objRadicado->obtenerMensajeRadicadoDesdeExpediente($key);
		?>
	<?php endif; ?>

	<?php include_once "$ruta_raiz/js/funtionImage.php"; ?>
	<script language="javascript">
		function datosbasicos() {
			window.location = 'radicacion/NEW.PHP?<?= "nurad=$verrad&fechah=$fechah&ent=$ent&Buscar=Buscar Radicado&carpeta=$carpeta&nomcarpeta=$nomcarpeta"; ?>';
		}

		function mostrar(nombreCapa) {
			document.getElementById(nombreCapa).style.display = "";
		}

		function ocultar(nombreCapa) {
			if (document.getElementById(nombreCapa) != null)
				document.getElementById(nombreCapa).style.display = "none";
		}
		var contadorVentanas = 0
		<?PHP

		if ($dependencia == 900) $verradPermisos = "Full";
		if ($carpeta == 8 || $carpeta == 66)  $verradPermisos = "Full";

		if ($verradPermisos == "Full" or $datoVer == "985") {
			if ($datoVer == "985") {
		?>

				function window_onload() {
					<? if ($verradPermisos == "Full" or $datoVer == "985") { ?>
						window_onload2();
					<?  } ?>
				}
			<?
			}
		} else {
			?>

			function changedepesel(xx) {}
		<?
		}
		?>

		function ver_tipodocuTRD(codserie, tsub) {
			<?php
			//echo "ver_tipodocumental(); ";
			if (trim($numrad)) {
				$isqlDepR = "SELECT RADI_DEPE_ACTU,RADI_USUA_ACTU from radicado WHERE RADI_NUME_RADI = '$numrad'";
				$rsDepR = $db->conn->Execute($isqlDepR);
				$coddepe = $rsDepR->fields['RADI_DEPE_ACTU'];
				$codusua = $rsDepR->fields['RADI_USUA_ACTU'];
				$medio_recepcion = $rsDepR->fields['MREC_CODI'];
			}
			$ind_ProcAnex = "N";
			?>
			window.open("./radicacion/tipificar_documento.php?nurad=<?= $verrad ?>&ind_ProcAnex=<?= $ind_ProcAnex ?>&codusua=<?= $codusua ?>&coddepe=<?= $coddepe ?>&codusuario=<?= $codusuario ?>&dependencia=<?= $dependencia ?>&tsub=" + tsub + "&codserie=" + codserie, "Tipificacion_Documento", "height=600,width=850,scrollbars=yes");
		}

		function ver_temas() {
			window.open("./tipo_documento.php?verrad=<?= $verrad ?>", "Temas", "height=350,width=450,scrollbars=yes");
		}
	</script>
	<script src="tooltips/jquery-ui.js"></script>
	<link rel="stylesheet" href="tooltips/tool.css">
	<script src="tooltips/tool.js"></script>
	<link rel="stylesheet" href="estilos/cogeinsas.css">
</head>

<body>
	<?PHP
	$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
	$fechah = date("dmy_h_m_s") . " " . time();
	$check = 1;
	$numeroa = 0;
	$numero = 0;
	$numeros = 0;
	$numerot = 0;
	$numerop = 0;
	$numeroh = 0;
	include_once("ver_datosrad.php");

	$_SESSION['dir_doc_us1'] = $cc_documento_us1;
	$_SESSION['dir_doc_us2'] = $cc_documento_us2;

	?>
	<div class="card shadow-sm mb-4">
		<div class="card-body py-3">
			<div class="row align-items-center">
				<!-- Columna: Número de Radicado -->
				<div class="col-md-6 mb-2 mb-md-0">
					<?php
					$sql_jefe = "SELECT u.usua_codi,u.depe_codi 
                                FROM usuario u, autm_membresias a
                                WHERE u.id=a.autu_id
                                and a.autg_id=2
                                and u.depe_codi=" . $dependencia;
					$rs_jefe = $db->conn->Execute($sql_jefe);

					$sql_radicado = "SELECT COUNT(*) k FROM radicado
                                WHERE radi_depe_actu=" . $rs_jefe->fields['DEPE_CODI'] . " 
                                AND radi_usua_actu=" . $rs_jefe->fields['USUA_CODI'] . " 
                                AND radi_nume_radi=" . $verrad;
					$rs_radicado = $db->conn->Execute($sql_radicado);

					$sql_rol = "SELECT autg_id FROM autm_membresias WHERE autg_id=37 AND autu_id=" . $_SESSION['usua_id'];
					$rs_rol = $db->conn->query($sql_rol);
					if ($rs_rol->fields['AUTG_ID'] == 37 && $rs_radicado->fields['K'] == 1) {
						$verradPermisos = "Full";
					}

					if ($krd) {
						$isql = "select * From usuario where USUA_LOGIN ='$krd' 
                            and USUA_SESION='" . substr(session_id(), 0, 29) . "' ";
						$rs = $db->conn->query($isql);
						if (($krd)) {
					?>
							<small class="text-muted d-block">DOCUMENTO N.</small>

							<h5 class="mb-0 fw-bold">
								<?php
								if ($mostrar_opc_envio == 0 and $carpeta != 8 and !$agendado and $verradPermisos == "Full") {
									$ent = substr($verrad, -1);
									echo "
                                    <a title='Modificar Documento'
                                       href='./radicacion/NEW.php?nurad=$verrad&Buscar=BuscarDocModUS&Submit3=ModificarDocumentos&Buscar1=BuscarOrfeo78956jkgf'
                                       class='text-decoration-none pa-3'>
                                       $verrad
                                       <img src='img/icono_modificar_radicado.png' title='Modificar'>
                                    </a>";
								} else {
									echo $verrad;
								}

								if ($tpPerRad[2] == 1 or $tpPerRad[2] == 3) {
									$varEnvio  = session_name() . "=" . session_id() . "&nurad=$verrad&ent=$ent";
									echo "
                                    <a href=\"javascript:void(0);\" 
                                       onClick=\"window.open('./radicacion/stickerWeb/index.php?$varEnvio&alineacion=Center',
                                       'sticker$verrad',
                                       'menubar=0,resizable=0,scrollbars=0,width=450,height=180,toolbar=0,location=0');\" 
                                       class='ms-2'>
                                       <img src='img/icono_radicar.png'>
                                    </a>";
								}

								if ($numExpediente && $_GET['expIncluido'][0] == "") {
									echo "<div class='mt-1 text-primary fw-semibold small'>
                                        PERTENECIENTE AL EXPEDIENTE No. " .
										($_SESSION['numExpedienteSelected'] != "" ?
											$_SESSION['numExpedienteSelected'] :
											$numExpediente) .
										"</div>";
								} elseif ($_GET['expIncluido'][0] != "") {
									echo "<div class='mt-1 text-primary fw-semibold small'>
                                        PERTENECIENTE AL EXPEDIENTE No. " . $_GET['expIncluido'][0] .
										"</div>";
									$_SESSION['numExpedienteSelected'] = $_GET['expIncluido'][0];
								}
								?>
							</h5>
							<?php
							?>
				</div>

				<!-- Columna: Acciones -->
				<div class="col-md-6 text-md-end">
					<a class="btn btn-outline-primary btn-sm me-2"
						href='./solicitar/Reservas.php?radicado=<?= "$verrad" ?>'>
						Solicitudes
					</a>

					<a class="btn btn-primary btn-sm"
						href='./solicitar/Reservar.php?radicado=<?= "$verrad&sAction=insert" ?>&numExpediente=<?= $numExpediente ?>'>
						Solicitar Físico
					</a>
				</div>
			</div>
		</div>
	</div>

	<?
							$datosaenviar = "fechaf=$fechaf&mostrar_opc_envio=$mostrar_opc_envio&tipo_carp=$tipo_carp&carpeta=$carpeta&nomcarpeta=$nomcarpeta&datoVer=$datoVer&ascdesc=$ascdesc&orno=$orno";
	?>
	<form name="form1" id="form1" action="<?= $ruta_raiz ?>/tx/formEnvio.php?" method="GET" class="smart-form">
		<?php
							if ($verradPermisos == "Full" && !($carpeta == 66 || $carpeta == 8)) {
							}
		?>
		<?
							if ($isBorrador == 't') {
		?>
			<div class="alert alert-warning" role="alert">
				<strong>EL DOCUMENTO ESTA EN MODO <u>BORRADOR</u></strong>
			</div>
			<div class="alert alert-warning" role="alert">
				LOS BORRADORES <a href="#" class="alert-link"><u>NO SON VALIDOS PARA TRÁMITE.</u></a>
			</div>
		<?
							}
		?>
		<input type='hidden' name='<?= session_name() ?>' value='<?= session_id() ?>'>
		<input type=hidden name=enviara value='9'>
		<input type=hidden name=codTx value=''>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input id="<?= $verradicado ?>" type="checkbox" value="CHKANULAR" name="checkValue[<?= $verradicado ?>]" checked>
		<?
							echo "<input type='hidden' name='fechah' value='$fechah'>";
							// Modificado Infom�trika 22-Julio-2009
							// Compatibilidad con register_globals = Off.
							print "<input type='hidden' name='verrad' value='" . $verrad . "'>";
							if ($flag == 2) {
								echo "<CENTER>NO SE HA PODIDO REALIZAR LA CONSULTA<CENTER>";
							} else {
								$row = array();
								$row1 = array();
								if ($info) {
									$row["INFO_LEIDO"] = 1;
									$row1["DEPE_CODI"] = $dependencia;
									$row1["USUA_CODI"] = $codusuario;
									$row1["RADI_NUME_RADI"] = $verrad;
									$rs = $db->update("informados", $row, $row1);
								} elseif (($leido != "no" or !$leido) and $datoVer != 985) {
									$row["RADI_LEIDO"] = 1;
									$row1["radi_depe_actu"] = $dependencia;
									$row1["radi_usua_actu"] = $codusuario;
									$row1["radi_nume_radi"] = $verrad;
									$rs = $db->update("radicado", $row, $row1);
								}
							}

							include_once("ver_datosrad.php");
							include("ver_datosgeo.php");
							$tipo_documento .= "<input type=hidden name=menu_ver value='$menu_ver'>";
							$hdatos = "leido=$leido&nomcarpeta=" . $nomcarpeta . "&tipo_carp=$tipo_carp&carpeta=$carpeta&verrad=$verrad&datoVer=$datoVer&fechah=fechah&menu_ver_tmp=";
						} else {
		?>
	</form>

	<form name="form11" action="enviar.php" method="GET">
		<input type="hidden" name="depsel">$nomRemDes
		<input type="hidden" name="depsel8">
		<input type="hidden" name="carpper">
		<CENTER>
			<span class='titulosError'>SU SESION HA TERMINADO O HA SIDO INICIADA EN OTRO EQUIPO</span>
		</CENTER>
	<?php
						}
					} else {
	?>
	<center>
		<b>
			<span class="eerrores">NO TIENE AUTORIZACION PARA INGRESAR</span>
			<BR>
			<span class="eerrores">
				<a href="login.php" target="_parent">Por Favor intente validarse de nuevo. Presione aca!
				</a>
			</span>
		<?PHP
					}
					//************************************************************************************************************************//
					//Filtro para ver con permiso y que los usuarios que lo tienen lo puedan modificar
					//************************************************************************************************************************//
					if (($_SESSION["perm_rad_reser"] == NULL || $depe_actu == $dependencia) && $codusuario == $usuacodi || $SeguridadRadicado == 0 || $tieneAsignacion == true || ($_SESSION["USUA_TRAMITADOR"] == true && $depe_actu == $dependencia)) {
						echo "<div class='actions2'>";
						include_once("$ruta_raiz/tx/txOrfeo.php");
						echo "</div>";
						$verradPermisos = 'Full';
					} else {
						$verradPermisos = 'Otro';
					}
		?>
	</form>

	<!-- row -->
	<input type=hidden name=reftab id=reftab>

	<div class="well well-sm well-light">
		<div class="card shadow-sm border-0 mb-3">
			<div class="card-header bg-white border-bottom-0 pb-0" id="tabs">
				<ul class="nav nav-tabs" id="tabs" role="tablist" style="border-bottom: 2px solid #dee2e6;">
					<li class="nav-item">
						<a href="#tabs-d" class="nav-link active" role="tab">Información del Radicado</a>
					</li>
					<li class="nav-item">
						<a href="#tabs-b" class="nav-link" role="tab">Histórico</a>
					</li>
					<li class="nav-item">
						<a href="#tabs-c" class="nav-link" role="tab">Documentos Anexos</a>
					</li>
					<li class="nav-item">
						<a href="#tabs-a" class="nav-link" role="tab">Expediente</a>
					</li>
				</ul>

				<div class="tab-content p-2">
					<div id="tabs-d" class="tab-pane " role="tabpanel">
						<?php include "lista_general.php"; ?>
					</div>

					<div id="tabs-b" class="tab-pane" role="tabpanel">
						<div class="text-center py-4">
							<img src="img/ajax-loader.gif" width="70" alt="Cargando...">
						</div>
					</div>

					<div id="tabs-c" class="tab-pane" role="tabpanel">
						<?php if ($recargartab): ?>
							<?php include "./lista_anexos.php"; ?>
						<?php else: ?>
							<div class="text-center py-4">
								<img src="img/ajax-loader.gif" width="70" alt="Cargando...">
							</div>
						<?php endif; ?>
					</div>

					<div id="tabs-a" class="tab-pane" role="tabpanel">
						<div class="text-center py-4">
							<img src="img/ajax-loader.gif" width="70" alt="Cargando...">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		function globalCargarPagina(pagina, nombreDiv) {
			$.fn.cargarPagina(pagina, nombreDiv);
		}

		$(document).ready(function() {

			$.fn.cargarPagina = function(pagina, nombreDiv) {
				$.post(pagina, {
					verradicado: "<?= $verradicado ?>",
					verradPermisos: "<?= $verradPermisos ?>",
					permRespuesta: "<?= $permRespuesta ?>",
					origenVerradicado: true
				}, function(data) {
					$('#' + nombreDiv).html(data);
				})
			};

			function cargarPagina(pagina, nombreDiv) {
				$.post(pagina, {
					verradicado: "<?= $verradicado ?>",
					verradPermisos: "<?= $verradPermisos ?>",
					permRespuesta: "<?= $permRespuesta ?>",
					origenVerradicado: true
				}, function(data) {
					$('#' + nombreDiv).html(data);
				});
			}
			// DO NOT REMOVE : GLOBAL FUNCTIONS!
			$('#tabs').tabs();
			$("#tabs").on("tabsactivate", function(event, ui) {
				window.location.href = ui.newTab.find('a.ui-tabs-anchor').attr('href');
				if ($(ui.newTab).attr('aria-controls') == 'tabs-b') cargarPagina('./ver_historico.php', 'tabs-b');
				if ($(ui.newTab).attr('aria-controls') == 'tabs-c') cargarPagina('./lista_anexos.php', 'tabs-c');
				if ($(ui.newTab).attr('aria-controls') == 'tabs-a') cargarPagina('./expediente/lista_expedientes.php', 'tabs-a');
				console.log(window.location.href);
			});

			var tabTitle = $("#tab_title"),
				tabContent = $("#tab_content"),
				tabTemplate = "<li style='position:relative;'> <span class='air air-top-left delete-tab' style='top:7px; left:7px;'><button class='btn btn-xs font-xs btn-default hover-transparent'><i class='fa fa-times'></i></button></span></span><a href='#{href}'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; #{label}</a></li>",
				tabCounter = 2;

			$('#chkr, #depsel, #carpper, #Enviar').hide();
			cargarPagina('./expediente/lista_expedientes.php', 'tabs-a');
		});
	</script>
</body>

</html>
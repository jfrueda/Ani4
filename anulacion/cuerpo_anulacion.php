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
$verrad = "";
$ruta_raiz = "..";

if (!$_SESSION['dependencia'])
	header("Location: $ruta_raiz/cerrar_session.php");

if (!$dep_sel) $dep_sel = $_SESSION['dependencia'];
$depeBuscada = $dep_sel;
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;
$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
$adodb_next_page = $_GET["adodb_next_page"];
?>
<html>

<head>
	<title>Anulacion de Radicados</title>
	<link rel="stylesheet" href="../estilos/orfeo.css">
	<link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
	<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
</head>

<body onLoad="window_onload();">
	<div id="spiffycalendar" class="text"></div>
	<?php
	$ruta_raiz = "..";
	include_once "$ruta_raiz/include/db/ConnectionHandler.php";
	$db = new ConnectionHandler("$ruta_raiz");
	/**
	 * Generamos el encabezado que envia las variable a la paginas siguientes.
	 * Por problemas en las sesiones enviamos el usuario.
	 * @$encabezado  Incluye las variables que deben enviarse a la singuiente pagina.
	 * @$linkPagina  Link en caso de recarga de esta pagina.
	 */
	switch ($tpAnulacion) {
		case 1:
			$whereTpAnulacion = " and (
			b.SGD_EANU_CODIGO = 9
			or (b.SGD_EANU_CODIGO <> 2
			or b.SGD_EANU_CODIGO IS NULL)
			)";
			$nomcarpeta    = "Solicitud de Anulacion de Radicados";
			$nombreCarpeta = "Solicitud de Anulacion de Radicados";
			$accion_sal    = "Solicitar Anulacion";
			$textSubmit = "Solicitar Anulacion";
			break;
		case 2:
			$whereTpAnulacion = " AND b.SGD_EANU_CODIGO = 2 ";
			$nomcarpeta    =  "Radicados para Anular";
			$nombreCarpeta = "Radicados para Anular";
			$accion_sal    = "";
			$textSubmit = "";
			break;
		case 3:
			$whereTpAnulacion = " and b.SGD_EANU_CODIGO = 9 ";
			$nomcarpeta    = "Radicados Anulados";
			$nombreCarpeta = "Radicados Anulados";
			$accion_sal    = "Ver Reporte";
			$textSubmit = "Ver Reporte";
			break;
	}

	$encabezado = "" . session_name() . "=" . session_id() . "&filtroSelect=$filtroSelect&accion_sal=$accion_sal&dep_sel=$dep_sel&tpAnulacion=$tpAnulacion&orderNo=";
	$linkPagina = "$PHP_SELF?$encabezado&accion_sal=$accion_sal&orderTipo=$orderTipo&orderNo=$orderNo";
	$carpeta    = "xx";

	//include "../envios/paEncabeza.php";

	$pagina_actual = "../anulacion/cuerpo_anulacion.php";
	$varBuscada = "radi_nume_radi";
	include "../envios/paBuscar.php";
	// if (!$busqRadicados)
	// 	die;
	$pagina_sig = "../anulacion/solAnulacion.php";
	//$swListar = "no";
	$accion_sal = "Solicitar Anulacion";
	//include "../envios/paOpciones.php";

	$whereFiltro = $dependencia_busq2;
	if ($busqRadicados) {
		$busqRadicados = trim($busqRadicados);
		$busqRadicadosTmp = "";
		$textElements = preg_split('/\s*,\s*/', $busqRadicados, -1, PREG_SPLIT_NO_EMPTY);
		foreach ($textElements as $item) {
			$item = trim($item);
			if ($item !== "") {
				$busqRadicadosTmp .= " b.radi_nume_radi = '$item' or";
			}
		}
		if (substr($busqRadicadosTmp, -2) == "or") {
			$busqRadicadosTmp = substr($busqRadicadosTmp, 0, strlen($busqRadicadosTmp) - 2);
		}
		if (trim($busqRadicadosTmp)) {
			$whereFiltro .= " and ( $busqRadicadosTmp ) ";
		}
	}
	/**  GENERACION LISTADO DE RADICADOS
	 *  Aqui utilizamos la clase adodb para generar el listado de los radicados
	 *  Esta clase cuenta con una adaptacion a las clases utiilzadas de orfeo.
	 *  el archivo original es adodb-pager.inc.php la modificada es adodb-paginacion.inc.php
	 */

	if ($orderNo == 98 or $orderNo == 99) {
		$order = 1;
		if ($orderNo == 98)   $orderTipo = "desc";
		if ($orderNo == 99)   $orderTipo = "";
	} else {
		if (!$orderNo)  $orderNo = 3;
		$order = $orderNo + 1;

		if ($orden_cambio == 1) {
			(!$orderTipo) ? $orderTipo = "desc" : $orderTipo = "";
		}
	}
	$sqlFecha = $db->conn->SQLDate("d-m-Y H:i A", "b.RADI_FECH_RADI");
	?>
	<form
		name="formEnviar"
		method="post"
		action="../anulacion/solAnulacion.php?<?= session_name() . '=' . session_id() ?>&tpAnulacion=<?= $tpAnulacion ?>&depeBuscada=<?= $depeBuscada ?>&estado_sal_max=<?= $estado_sal_max ?>&pagina_sig=<?= $pagina_sig ?>&dep_sel=<?= $dep_sel ?>&nomcarpeta=<?= $nomcarpeta ?>&orderTipo=<?= $orderTipo ?>&orderNo=<?= $orderNo ?>">

		<?php
		$encabezado = "" . session_name() . "=" . session_id() . "&depeBuscada=$depeBuscada&accion_sal=$accion_sal&filtroSelect=$filtroSelect&dep_sel=$dep_sel&tpAnulacion=$tpAnulacion&nomcarpeta=$nomcarpeta&orderTipo=$orderTipo&orderNo=";
		$linkPagina = $_SERVER['PHP_SELF'] . "?$encabezado&orderTipo=$orderTipo&orderNo=$orderNo";
		?>

		<div class="container-fluid my-4">

			<!-- TÍTULO -->
			
			<!-- ALERTA -->
			<div class="alert alert-warning shadow-sm rounded-3">
				<strong>⚠ Importante:</strong>
				Si el radicado se envío a un externo o a una dependencia diferente a la productora, no se podrá solicitar la anulación" asi mismo, si el usuario la consulta y tiene las anteriores condiciones, el sistema no le muestra ningún resultado en la búsqueda.
			</div>

			<!-- TARJETA CONTENEDORA -->
			<div class="card shadow-lg border-0">
				<div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
					<span><i class="bi bi-table me-2"></i> Listado de Radicados</span>
					<span class="badge bg-light text-dark">Seleccione para anular</span>
				</div>

				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-hover align-middle mb-0">
							<thead class="table-light">
								<tr class="text-center">
									<th>Radicado</th>
									<th>Fecha</th>
									<th>Asunto</th>
									<th>Remitente</th>
									<th>Días</th>
									<th>Enviado por</th>
									<th>Observación</th>
									<th>
										<input type="checkbox" onclick="markAll();" id="checkAll">
									</th>
								</tr>
							</thead>

							<tbody class="table-group-divider">

								<?php
								include "$ruta_raiz/include/query/anulacion/querycuerpo_anulacion.php";
								$rs = $db->conn->Execute($isql);

								while (!$rs->EOF) {

									$numeroRadicado = $rs->fields["IMG_NUMERO RADICADO"];
									$numeroRadPrev  = $numeroRadicado;
									$fechaRadicado  = $rs->fields["FECHA RADICADO"];
									$asuntoRadicado = $rs->fields["DESCRIPCION"];
									$remitente      = $rs->fields["REMITENTE"];
									$dias           = $rs->fields["DIAS RESTANTES"];
									$enviadoPor     = $rs->fields["ENVIADO POR"];
									$radiPath       = $rs->fields["HID_RADI_PATH"];
									$dependenciaActual = $rs->fields["RADI_DEPE_ACTU"];

									$linkVerRadicado = "$ruta_raiz/verradicado.php?verrad=$numeroRadicado&" . session_name() . "=" . session_id() . "&nomcarpeta=$nomcarpeta#tabs-a";
									$linkImagen      = "$ruta_raiz/bodega/$radiPath";
								?>

									<tr class="text-center">
										<td>
											<a href="#" class="fw-semibold text-decoration-none" onclick="funlinkArchivo(<?= $numeroRadicado ?>, '..')">
												<?= $numeroRadicado ?>
											</a>
										</td>

										<td>
											<a href="<?= $linkVerRadicado ?>" class="text-decoration-none">
												<?= $fechaRadicado ?>
											</a>
										</td>

										<td class="text-start"><?= $asuntoRadicado ?></td>
										<td><?= $remitente ?></td>
										<td>
											<span class="badge bg-info"><?= $dias ?></span>
										</td>
										<td><?= $enviadoPor ?></td>

										<?php if ($dependencia != $dependenciaActual) { ?>
											<td>
												<span class="badge bg-warning text-dark">
													No se puede solicitar anulación por que el radicado se encuentra en una dependencia diferente.
												</span>
											</td>
											<td></td>
										<?php } else { ?>
											<td></td>
											<td>
												<input
													class="form-check-input"
													type="checkbox"
													name="checkValue[<?= $numeroRadicado ?>]"
													value="CHKANULAR"
													id="<?= $numeroRadicado ?>">
											</td>
										<?php } ?>

									</tr>

								<?php
									$rs->MoveNext();
								}
								?>
							</tbody>
						</table>
					</div>
				</div>

				<!-- FOOTER -->
				<div class="card-footer bg-light d-flex justify-content-end gap-2">
					<button type="submit" class="btn btn-danger btn-lg">
						<i class="bi bi-trash3 me-1"></i> Solicitar Anulación
					</button>
				</div>
			</div>
		</div>
	</form>

	<script type="text/javascript">
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		pageSetUp();

		// PAGE RELATED SCRIPTS

		loadDataTableScripts();

		function loadDataTableScripts() {

			// loadScript("js/plugin/datatables/jquery.dataTables-cust.min.js", dt_2);

			function dt_2() {
				loadScript("js/plugin/datatables/ColReorder.min.js", dt_3);
			}

			function dt_3() {
				loadScript("js/plugin/datatables/FixedColumns.min.js", dt_4);
			}

			function dt_4() {
				loadScript("js/plugin/datatables/ColVis.min.js", dt_5);
			}

			function dt_5() {
				loadScript("js/plugin/datatables/ZeroClipboard.js", dt_6);
			}

			function dt_6() {
				loadScript("js/plugin/datatables/media/js/TableTools.min.js", dt_7);
			}

			function dt_7() {
				loadScript("js/plugin/datatables/DT_bootstrap.js", runDataTables);
			}

		}

		function runDataTables() {

			/*
			 * BASIC
			 */
			$('#dt_basic').dataTable({
				"sPaginationType": "bootstrap_full"
			});

			/* END BASIC */

			/* Add the events etc before DataTables hides a column */
			$("#datatable_fixed_column thead input").keyup(function() {
				oTable.fnFilter(this.value, oTable.oApi._fnVisibleToColumnIndex(oTable.fnSettings(), $("thead input").index(this)));
			});

			$("#datatable_fixed_column thead input").each(function(i) {
				this.initVal = this.value;
			});
			$("#datatable_fixed_column thead input").focus(function() {
				if (this.className == "search_init") {
					this.className = "";
					this.value = "";
				}
			});
			$("#datatable_fixed_column thead input").blur(function(i) {
				if (this.value == "") {
					this.className = "search_init";
					this.value = this.initVal;
				}
			});


			var oTable = $('#datatable_fixed_column').dataTable({
				"sDom": "<'dt-top-row'><'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
				//"sDom" : "t<'row dt-wrapper'<'col-sm-6'i><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'>>",
				"oLanguage": {
					"sSearch": "Search all columns:"
				},
				"bSortCellsTop": true
			});



			/*
			 * COL ORDER
			 */
			$('#datatable_col_reorder').dataTable({
				"sPaginationType": "bootstrap",
				"sDom": "R<'dt-top-row'Clf>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
				"fnInitComplete": function(oSettings, json) {
					$('.ColVis_Button').addClass('btn btn-default btn-sm').html('Columns <i class="icon-arrow-down"></i>');
				}
			});

			/* END COL ORDER */

			/* TABLE TOOLS */
			$('#datatable_tabletools').dataTable({
				"sDom": "<'dt-top-row'Tlf>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
				"oTableTools": {
					"aButtons": ["copy", "print", {
						"sExtends": "collection",
						"sButtonText": 'Save <span class="caret" />',
						"aButtons": ["csv", "xls", "pdf"]
					}],
					"sSwfPath": "js/plugin/datatables/media/swf/copy_csv_xls_pdf.swf"
				},
				"fnInitComplete": function(oSettings, json) {
					$(this).closest('#dt_table_tools_wrapper').find('.DTTT.btn-group').addClass('table_tools_group').children('a.btn').each(function() {
						$(this).addClass('btn-sm btn-default');
					});
				}
			});

			/* END TABLE TOOLS */

		}
	</script>
</body>

</html>
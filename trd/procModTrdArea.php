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
$ruta_raiz = "..";
if (!$_SESSION['dependencia']) include "$ruta_raiz/rec_session.php";
if (!$coddepe) $coddepe = $dependencia;
if (!$tsub) $tsub = 0;
if (!$codserie) $codserie = 0;
if (!$idSerie) $idSerie = 0;
if (!$idSubSerie) $idSubSerie = 0;
$fecha_fin = date("Y/m/d");
$where_fecha = "";
//error_reporting(7);
?>
<html>

<head>
	<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
	<link rel="stylesheet" type="text/css" href="js/spiffyCal/spiffyCal_v2_1.css">
	<!--<link rel="stylesheet" href="../estilos/orfeo.css">-->
</head>

<body bgcolor="#FFFFFF" topmargin="0">
	<div id="spiffycalendar" class="text"></div>
	<?
	$ruta_raiz = "..";
	include_once "$ruta_raiz/include/db/ConnectionHandler.php";
	$db = new ConnectionHandler("$ruta_raiz");

	$encabezado = "" . session_name() . "=" . session_id() . "&krd=$krd&filtroSelect=$filtroSelect&accion_sal=$accion_sal&dependencia=$dependencia&tpAnulacion=$tpAnulacion&orderNo=";
	$linkPagina = "$PHP_SELF?$encabezado&accion_sal=$accion_sal&orderTipo=$orderTipo&orderNo=$orderNo";
	/*  GENERACION LISTADO DE RADICADOS
	 *  Aqui utilizamos la clase adodb para generar el listado de los radicados
	 *  Esta clase cDEPENDENCIAuenta con una adaptacion a las clases utiilzadas de orfeo.
	 *  el archivo original es adodb-pager.inc.php la modificada es adodb-paginacion.inc.php
  */
	error_reporting(7);

	?>
	<form name="formEnviar"
		action="../trd/procModTrdArea.php?<?= session_name() . '=' . session_id() . "&krd=$krd" ?>&estado_sal=<?= $estado_sal ?>&estado_sal_max=<?= $estado_sal_max ?>&pagina_sig=<?= $pagina_sig ?>&dep_sel=<?= $dep_sel ?>&nomcarpeta=<?= $nomcarpeta ?>&orderNo=<?= $orderNo ?>"
		method="post">

		<?
		if ($activar_trda) {
			$valCambio = '1';
		}
		if ($desactivar_trda) {
			$valCambio = '0';
		}

		// Debug: Mostrar la consulta SQL y el número de filas
		echo "<!-- Debug: SQL = $sql -->";
		$rsDep = $db->conn->Execute($sql);
		if (!$rsDep) {
			echo "<!-- Debug: Error in query: " . $db->conn->ErrorMsg() . " -->";
		} else {
			echo "<!-- Debug: Rows in rsDep = " . $rsDep->RecordCount() . " -->";
		}

		if ($desactivar_trda) {
			if ($idSerie != 0) {
				$var_where = " and sgd_srd_id = '$idSerie'";
				if ($idSubSerie != 0) {
					$var_where = $var_where . " and sgd_sbrd_id = '$idSubSerie'";
					if ($tdoc != 0) {
						$var_where = $var_where . " and sgd_tpr_codigo = '$tdoc'";
					}
				}
				$bien = true;
				if ($bien) {
					$ayer = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));
					$isqlActi = "update SGD_MRD_MATRIRD set SGD_MRD_ESTA='$valCambio' , sgd_mrd_fechfin = '$ayer' " .
						"where depe_codi = '$coddepe'" . $var_where;
					$bien = $db->query($isqlActi);
				}
				if ($bien) {
					$mensaje = "Modificado el Estado de la Relacion segun los parametros seleccionados<br> ";
					$db->conn->CommitTrans();
				} else {
					$mensaje = "No fue posible Activar la Relacion segun los parametros</br>";
					$db->conn->RollbackTrans();
				}
			} else {
				echo "<script>alert('Debe seleccionar por lo menos la Serie');</script>";
			}
		}

		if ($activar_trda) {
			if ($idSerie != 0) {
				$var_where = " and sgd_srd_id = '$idSerie'";
				if ($idSubSerie != 0) {
					$var_where = $var_where . " and sgd_sbrd_id = '$idSubSerie'";
					if ($tdoc != 0) {
						$var_where = $var_where . " and sgd_tpr_codigo = '$tdoc'";
					}
				}
				$bien = true;
				if ($bien) {
					$ayer = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));
					$isqlActi = "update SGD_MRD_MATRIRD set SGD_MRD_ESTA='$valCambio' , sgd_mrd_fechfin = '2040-12-31 23:00:00' " .
						"where depe_codi = '$coddepe'" . $var_where;
					$bien = $db->query($isqlActi);
				}
				if ($bien) {
					$mensaje = "Modificado el Estado de la Relacion segun los parametros seleccionados<br> ";
					$db->conn->CommitTrans();
				} else {
					$mensaje = "No fue posible Activar la Relacion segun los parametros</br>";
					$db->conn->RollbackTrans();
				}
			} else {
				echo "<script>alert('Debe seleccionar por lo menos la Serie');</script>";
			}
		}
		?>
		<!-- Encabezado -->
		<div class="card shadow-sm border-0 mb-4">
			<div class="card-header bg-orfeo text-white">
				<h5 class="mb-0 text-center">Modificación Relación TRD</h5>
			</div>

			<div class="card-body">

				<!-- DEPENDENCIA -->
				<div class="mb-4">
					<label class="form-label fw-semibold">Dependencia</label>

					<?php
					include_once "$ruta_raiz/include/query/envios/queryPaencabeza.php";
					$sqlConcat = $db->conn->Concat(
						$db->conn->substr . "($conversion,1,5) ",
						"'-'",
						$db->conn->substr . "(depe_nomb,1,30) "
					);

					$sql = "select $sqlConcat ,depe_codi 
				            from dependencia 
				            where  depe_estado=1
				            order by depe_codi";

					$rsDep = $db->conn->Execute($sql);

					if (!$depeBuscada) $depeBuscada = $dependencia;

					print $rsDep->GetMenu2(
						"coddepe",
						"$coddepe",
						false,
						false,
						0,
						"onChange='submit();' class='form-select'"
					);
					?>
				</div>

				<div class="row g-3">

					<!-- SERIE -->
					<div class="col-md-6">
						<label class="form-label fw-semibold">Serie</label>
						<?php
						include "$ruta_raiz/trd/actu_matritrd.php";
						if (!$codserie) $codserie = 0;

						$fecha_hoy = Date("Y-m-d");
						$sqlFechaHoy = $db->conn->DBDate($fecha_hoy);

						$nomb_varc = "s.sgd_srd_codigo";
						$nomb_varde = "s.sgd_srd_descrip";
						include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";

						$querySerie = "select distinct ($sqlConcat) as detalle,id, s.sgd_srd_codigo 
						from sgd_srd_seriesrd s, sgd_mrd_matrird m 
						where s.id = m.sgd_srd_id 
						and m.depe_codi = '$coddepe'
						order by detalle";

						$rsD = $db->conn->query($querySerie);

						include "$ruta_raiz/include/tx/ComentarioTx.php";

						print $rsD->GetMenu2(
							"idSerie",
							$idSerie,
							"0:-- Seleccione --",
							false,
							"",
							"onChange='submit()' class=\"form-select\""
						);
						?>
					</div>

					<!-- SUBSERIE -->
					<div class="col-md-6">
						<label class="form-label fw-semibold">Subserie</label>
						<?php
						$nomb_varc = "su.sgd_sbrd_codigo";
						$nomb_varde = "su.sgd_sbrd_descrip";
						include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";

						$querySub = "select distinct ($sqlConcat) as detalle, su.id, su.sgd_sbrd_codigo 
									from sgd_sbrd_subserierd su, sgd_mrd_matrird m , sgd_srd_seriesrd s
									where su.sgd_srd_id = '$idSerie'
									and $sqlFechaHoy between su.sgd_sbrd_fechini and su.sgd_sbrd_fechfin
									and m.depe_codi = '$coddepe'
									and s.id = m.sgd_srd_id
									order by detalle";

						$rsSub = $db->conn->query($querySub);

						include "$ruta_raiz/include/tx/ComentarioTx.php";

						print $rsSub->GetMenu2(
							"idSubSerie",
							$idSubSerie,
							"0:-- Todas las subseries documentales --",
							false,
							"",
							"onChange='submit()' class='form-select'"
						);
						?>
					</div>

					<!-- TIPO DOCUMENTAL -->
					<div class="col-md-12">
						<label class="form-label fw-semibold">Tipo Documental</label>
						<?php
						$nomb_varc = "t.sgd_tpr_codigo";
						$nomb_varde = "t.sgd_tpr_descrip";
						include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";

						$queryTipDcto = "select distinct ($sqlConcat) as detalle, t.sgd_tpr_codigo 
										from sgd_tpr_tpdcumento t, sgd_mrd_matrird m , sgd_sbrd_subserierd su
										where m.depe_codi = '$coddepe'
										and m.sgd_srd_id = '$idSerie'
										and m.sgd_sbrd_id = '$idSubSerie'
										and m.sgd_tpr_codigo = t.sgd_tpr_codigo
										and $sqlFechaHoy between su.sgd_sbrd_fechini and su.sgd_sbrd_fechfin
										and su.sgd_srd_codigo = m.sgd_srd_codigo 
										and su.sgd_sbrd_codigo = m.sgd_sbrd_codigo
										order by detalle";

						$rsTipDcto = $db->conn->query($queryTipDcto);

						include "$ruta_raiz/include/tx/ComentarioTx.php";

						print $rsTipDcto->GetMenu2(
							"tdoc",
							$tdoc,
							"0:-- Todos los tipos documentales --",
							false,
							"",
							"onChange='submit()' class='form-select'"
						);
						?>
					</div>

				</div>

				<!-- BOTONES -->
				<div class="text-center mt-4">
					<input type="submit" name="activar_trda" value="Activar" class="btn btn-primary px-4">
					<input type="submit" name="desactivar_trda" value="Desactivar" class="btn btn-danger px-4 ms-2">
				</div>
			</div>
		</div>

		<!-- MENSAJE -->
		<?php if ($mensaje): ?>
			<div class="alert alert-info text-center fw-semibold"><?= $mensaje ?></div>
		<?php endif; ?>
	</form>
</body>

</html>
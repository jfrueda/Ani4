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
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$krd                = $_SESSION["krd"];
$dependencia        = $_SESSION["dependencia"];
$usua_doc           = $_SESSION["usua_doc"];
$codusuario         = $_SESSION["codusuario"];
$tip3Nombre         = $_SESSION["tip3Nombre"];
$tip3desc           = $_SESSION["tip3desc"];
$tip3img            = $_SESSION["tip3img"];
$tpNumRad           = $_SESSION["tpNumRad"];
$tpPerRad           = $_SESSION["tpPerRad"];
$tpDescRad          = $_SESSION["tpDescRad"];
$tip3Nombre         = $_SESSION["(sgd_cerrado <> 1 or sgd_cerrado is null)tip3Nombre"];
$tpDepeRad          = $_SESSION["tpDepeRad"];
$usuaPermExpediente = $_SESSION["usuaPermExpediente"];

$dir_doc_us1 = $_SESSION['dir_doc_us1'];
$dir_doc_us2 = $_SESSION['dir_doc_us2'];
$ruta_raiz = "..";

if (!$nurad) {
	$nurad = $rad;
}

include_once("$ruta_raiz/include/db/ConnectionHandler.php");
$db = new ConnectionHandler("$ruta_raiz");
//$db->conn->debug = true;
include_once("$ruta_raiz/include/tx/Historico.php");
include_once("$ruta_raiz/include/tx/Expediente.php");

$encabezado = "?" . session_name() . "=" . session_id() . "&opcionExp=$opcionExp&numeroExpediente=$numeroExpediente&nurad=$nurad&coddepe=$coddepe&codusua=$codusua&depende=$depende&ent=$ent&tdoc=$tdoc&codiTRDModi=$codiTRDModi&codiTRDEli=$codiTRDEli&codserie=$codserie&tsub=$tsub&ind_ProcAnex=$ind_ProcAnex";
$expediente = new Expediente($db);

// Inserta el radicado en el expediente
if ($funExpediente == "INSERT_EXP") {
	// Consulta si el radicado est� incluido en el expediente.
	$arrExpedientes = $expediente->expedientesRadicado($nurad);
	/* Si el radicado esta incluido en el expediente digitado por el usuario.
     * != No identico no se puede poner !== por que la funcion array_search
     * tambien arroja 0 o "" vacio al ver que un expediente no se encuentra
     */
	$arrExpedientes[] = "1";
	foreach ($arrExpedientes as $line_num => $line) {
		if ($line == $_POST['numeroExpediente']) {
			print '<center><hr><font color="red">El radicado ya est&aacute; incluido en el expediente.</font><hr></center>';
		} else {
			$resultadoExp = $expediente->insertar_expediente($_REQUEST['numeroExpediente'], $_REQUEST['nurad'], $dependencia, $codusuario, $usua_doc);
			//    		  $resultadoExp = $expediente->insertar_expediente( $_POST['numeroExpediente'], $_GET['nurad'], $dependencia, $codusuario, $usua_doc );
			if ($resultadoExp == 1) {
				$observa = "Incluir radicado en Expediente";
				include_once "$ruta_raiz/include/tx/Historico.php";
				$radicados[] = $_GET['nurad'];
				$tipoTx = 53;
				$Historico = new Historico($db);
				$Historico->insertarHistoricoExp($_POST['numeroExpediente'], $radicados, $dependencia, $codusuario, $observa, $tipoTx, 0);

?>
				<script language="JavaScript">
					//alert ('Inserción realizada. !');
					window.opener.$.fn.cargarPagina('expediente/lista_expedientes.php', 'tabs-a');
					window.close();
				</script>
			<?php
			} else {
				//if (!$fast){
				//      print '<hr><font color=red>No se anexo este radicado al expediente. Verifique que el numero del expediente exista e intente de nuevo.</font><hr>';
				//}else{
			?>
				<script language="JavaScript">
					//alert (' ! Inserción realizada. !');
					window.opener.$.fn.cargarPagina('expediente/lista_expedientes.php', 'tabs-a');
					window.close();
				</script>
<?			//}
			}
		}
	}
}
?>
<html>

<head>
	<title>Incluir en Expediente</title>
	<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
	<script language="JavaScript">
		function validarNumExpediente() {
			numExpediente = document.getElementById('numeroExpediente').value;

			// Valida que se haya digitado el nombre del expediente
			// a�o dependencia serie subserie consecutivo E
			if (numExpediente.length != 0 && numExpediente != "") {
				insertarExpedienteVal = true;
			} else if (numExpediente.length == 0 || numExpediente == "") {
				alert("Error. Debe especificar el nombre de un expediente.");
				document.getElementById('numeroExpediente').focus();
				insertarExpedienteVal = false;
			}

			if (insertarExpedienteVal == true) {
				document.insExp.submit();
			}
		}

		function confirmaIncluir() {
			document.getElementById('funExpediente').value = "INSERT_EXP";
			document.insExpConfirm.submit();
		}

		function cambia_Exp(expId, expNo) {
			var exp_id = document.getElementById(expId);
			numExp = exp_id.value;
			var exp_no = document.getElementById(expNo);
			exp_no.value = numExp;
			document.insExp.numeroExpediente.focus();
		}
	</script>
</head>

<body onload="document.insExp.numeroExpediente?.focus();">
	<div class="container py-4">
		<div class="row justify-content-center">
			<div class="col-sm-12">
				<div class="card shadow-sm border-0">
					<div class="card-body">
						<h5 class="text-center mb-3 fw-bold text-primary">
							Búsqueda de Expediente
						</h5>
						<p class="text-center text-muted small mb-4">
							Ingrese el número de expediente o parámetro de búsqueda.
						</p>
						<form method="post" action="?nurad=<?= $nurad ?>" name="insExpBus">
							<!-- Campo de búsqueda -->
							<div class="mb-3">
								<label for="criterio" class="form-label fw-semibold">
									Número de Expediente
								</label>
								<input
									type="text"
									name="criterio"
									id="criterio"
									value="<?= trim($_POST['criterio']) ?>"
									class="form-control form-control-lg"
									placeholder="Ej: 12345-2024">
							</div>

							<!-- Botón Buscar -->
							<div class="d-grid mt-4">
								<input name="btnBuscaExp" type="submit" class="btn btn-primary btn-xs" id="btnBuscaExp" value="Buscar">
							</div>
						</form>
					</div>
				</div>
				<div class="card shadow-sm border-0 my-4">
					<div class="card-body">
						<?
					//$dependencia = '230';
						/************************************************/
					/* Ejecucion de los QUERYS respectivos para		*/
					/* filtrar los expedientes coincidentes			*/
						/************************************************/
						//$db->conn->debug = true;
						$btnBuscaExp = TRIM($_POST['btnBuscaExp']);
						if ($btnBuscaExp) {
							$criterio = trim(strtoupper($_POST['criterio']));
							if (!$criterio) {
								$criterio = "_nada_";
							}

							$sql_rad = "SELECT * FROM SGD_SEXP_SECEXPEDIENTES
								WHERE (upper(SGD_SEXP_PAREXP1) LIKE '%$criterio%' 
								OR upper(SGD_SEXP_PAREXP2) iLIKE '%$criterio%' 
								OR upper(SGD_SEXP_PAREXP3) iLIKE '%$criterio%'  
								OR upper(SGD_SEXP_PAREXP4) iLIKE '%$criterio%'  
								OR upper(SGD_SEXP_PAREXP5) iLIKE '%$criterio%' 
								OR SGD_EXP_NUMERO iLIKE '%$criterio%')
								and (sgd_sexp_estado not in  (1,2) or sgd_sexp_estado is null)
								";
						} elseif (!$numeroExpediente) {
							$sql_rad = "SELECT * FROM RADICADO r, BODEGA_EMPRESAS b
										WHERE b.IDENTIFICADOR_EMPRESA = r.EESP_CODI
											AND r.RADI_NUME_RADI = '$nurad'";
							$sql_rad = "SELECT distinct 
									sexp.sgd_exp_numero, sexp.SGD_SEXP_FECH, sexp.SGD_SEXP_FECH,dir.SGD_DIR_DOC,dir.SGD_DIR_NOMREMDES
									, sexp.USUA_DOC_RESPONSABLE,sexp.SGD_SEXP_PAREXP1,  sexp.SGD_SEXP_PAREXP2,  sexp.SGD_SEXP_PAREXP3,  sexp.SGD_SEXP_PAREXP4,  sexp.SGD_SEXP_PAREXP5
													from
														sgd_dir_drecciones dir, sgd_exp_expediente exp, SGD_SEXP_SECEXPEDIENTES SEXP
														,radicado r
												where dir.radi_nume_radi=exp.radi_nume_radi
											and exp.sgd_exp_numero=sexp.sgd_exp_numero
											and (sgd_sexp_estado not in  (1,2) or sgd_sexp_estado is null)
												and r.radi_nume_radi=dir.radi_nume_radi
												and dir.sgd_dir_tipo=1
								and sexp.depe_codi = $dependencia
												and
												(	dir.sgd_dir_doc like '%$dir_doc_us1%'  ";
							if ($dir_doc_us1) {
								$sql_rad .= " or cast(r.eesp_codi as varchar(15)) like '%$dir_doc_us1%'";
								$sql_rad .= " or sexp.SGD_SEXP_PAREXP1 like '%$dir_doc_us1%'";
								$sql_rad .= " or sexp.SGD_SEXP_PAREXP2 like '%$dir_doc_us1%'";
								$sql_rad .= " or sexp.SGD_SEXP_PAREXP3 like '%$dir_doc_us1%'";
								$sql_rad .= " or sexp.SGD_SEXP_PAREXP4 like '%$dir_doc_us1%' ";
							}
							if ($dir_doc_us2) {
								$sql_rad .= " or dir.sgd_dir_doc like '%$dir_doc_us2%'";
							}

							switch ($db->driver) {
								case 'postgres':
									$sql_rad .= " AND date_part('year', SEXP.sgd_sexp_fech) = date_part('year', now()) ";
									$limit = "limit 10";
									break;
								case 'oracle':
									$rownum = " and rownum<=10";
									break;
								case 'oci8':
								case 'oci805':
								case 'ocipo':
									$rownum = " and rownum<=10";
									break;
								default:
									$sql_rad .= " ";
							}


							$sql_rad .= "$rownum ) order by sgd_exp_numero desc $limit";
							//$db->conn->debug = true;
							$rs_rad = $db->query($sql_rad);

							if (!$rs_rad->EOF) {
								$sgd_sexp_parexp1 = $rs_rad->fields['NIT_DE_LA_EMPRESA'];
								//		$sgd_sexp_parexp2 = $rs_rad->fields['SIGLA_DE_LA_EMPRESA'];
								if (!$sgd_sexp_parexp1) {
									$sgd_sexp_parexp1 = "_nada_";
								}

								$sql_Fin = "SELECT * FROM SGD_SEXP_SECEXPEDIENTES
									WHERE $rownum $limit"; // OR SGD_SEXP_PAREXP2 LIKE '%$sgd_sexp_parexp2%'";
							}
						}

						if ($sql_rad) $rs_Fin = $db->conn->query($sql_rad);
						if ($rs_Fin):
							if (!$btnBuscaExp): ?>
								<p class="text-muted small mb-2">
									<strong>Expedientes que coinciden con documento del remitente (Últimos 10)</strong>
								</p>
							<?php endif; ?>

							<div class="table-responsive shadow-sm rounded-3 mb-4">
								<table class="table table-hover table-striped align-middle">
									<?php if (!$rs_Fin->EOF): ?>
										<thead class="table-primary">
											<tr class="text-center small">
												<th align="left">Fecha</th>
												<th align="left">Expediente</th>
												<th align="left">Documento</th>
												<th align="left">Nombre Rel Radicado</th>
												<th align="left">Parámetro Exp.</th>
												<th align="left">Responsable</th>
												<th align="left">Acción</th>
											</tr>
										</thead>
										<tbody>
											<?php
											while (!$rs_Fin->EOF):
												$exp_Fecha = substr($rs_Fin->fields['SGD_SEXP_FECH'], 0, 11);
												$exp_No    = $rs_Fin->fields['SGD_EXP_NUMERO'];
												$exp_Nit   = $rs_Fin->fields['SGD_DIR_DOC'];
												$exp_P02   = $rs_Fin->fields['SGD_DIR_NOMREMDES'];
												$exp_P03   = $rs_Fin->fields['SGD_SEXP_PAREXP1'] . " / " . $rs_Fin->fields['SGD_SEXP_PAREXP2'] . " / " . $rs_Fin->fields['SGD_SEXP_PAREXP3'] . " / " . $rs_Fin->fields['SGD_SEXP_PAREXP4'] . " / " . $rs_Fin->fields['SGD_SEXP_PAREXP5'];
												$exp_Usu   = $rs_Fin->fields['USUA_DOC_RESPONSABLE'];

												$sql_Usu = "SELECT * FROM USUARIO WHERE USUA_DOC = '$exp_Usu'";
												$rs_Usu = $db->conn->query($sql_Usu);
												$usu_Log = $rs_Usu->fields['USUA_LOGIN'];
											?>
												<tr class="align-middle">
													<td><?= $exp_Fecha ?></td>
													<td><?= $exp_No ?></td>
													<td><?= $exp_Nit ?></td>
													<td><?= $exp_P02 ?></td>
													<td><?= $exp_P03 ?></td>
													<td><?= $usu_Log ?></td>
													<td>
														<input type="hidden" id="<?= $exp_No ?>" value="<?= $exp_No ?>">
														<a href="#" class="link-primary fw-semibold small"
															onclick="cambia_Exp('<?= $exp_No ?>', 'numeroExpediente');">
															Seleccionar
														</a>
													</td>
												</tr>
											<?php
												$rs_Fin->MoveNext();
											endwhile;
											?>
										</tbody>
									<?php else: ?>
										<tr>
											<td colspan="7" class="text-center p-3 text-muted">
												<em>No hay expedientes relacionados…</em>
											</td>
										</tr>
									<?php endif; ?>
								</table>
							</div>

							<form method="post" action="<?php print $encabezado; ?>" name="insExp" class="smart-form">
								<div class="card shadow-sm mb-4">
									<div class="card-header bg-primary text-white py-2">
										<strong class="small">INCLUIR EN EL EXPEDIENTE</strong>
									</div>
									<div class="card-body">
										<div class="mb-3">
											<label for="numeroExpediente" class="form-label fw-semibold small">
												Nombre del Expediente
											</label>
											<input type="text" name="numeroExpediente" id="numeroExpediente" value="<?php print $_POST['numeroExpediente']; ?>" class="form-control">
										</div>

										<div class="d-flex justify-content-between">
											<input name="btnIncluirExp" type="button" class="btn btn-primary btn-sm" id="btnIncluirExp" onclick="validarNumExpediente();" value="Incluir en Exp">
											<input name="btnCerrar" type="button" class="btn btn-secondary btn-sm" id="btnCerrar" onclick="window.opener.$.fn.cargarPagina('expediente/lista_expedientes.php','tabs-a'); window.close();" value="Cerrar">
										</div>
									</div>
								</div>
							</form>
					</div>
				</div>
			</div>

			<?php endif;
						// Consulta si existe o no el expediente.
						if ($expediente->existeExpediente($_POST['numeroExpediente']) !== 0) {
							$numeroExpediente = $_POST['numeroExpediente'];
							$sql_cexp = "SELECT sgd_sexp_estado FROM SGD_SEXP_SECEXPEDIENTES where sgd_exp_numero = '$numeroExpediente'";
							$rs_Cexp = $db->conn->query($sql_cexp);
							$exp_cerrado = intval($rs_Cexp->fields['SGD_SEXP_ESTADO']);

							if ($exp_cerrado >= 1) {
			?>
				<div class="card shadow-sm border-0 my-4">
					<div class="card-body">
						<div class="table-responsive my-3">
							<div class="card border-danger shadow-sm">
								<div class="card-header bg-danger text-white text-center fw-bold">
									No se puede incluir el radicado en este expediente
								</div>

								<div class="card-body">

									<h5 class="text-center fw-bold mb-3">
										<?php print $numeroExpediente; ?>
									</h5>

									<div class="alert alert-warning" role="alert">
										<strong>Advertencia:</strong> Este expediente está <strong>Cerrado</strong>.
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>

			<?
							} else {
			?>
				<form method="post" action="<?php print $encabezado; ?>" name="insExpConfirm" class="smart-form">

					<input type="hidden" name="funExpediente" id="funExpediente" value="">
					<input type="hidden" name="confirmaIncluirExp" id="confirmaIncluirExp" value="">
					<input type="hidden" name="numeroExpediente" id="numeroExpediente" value="<?php print $_POST['numeroExpediente']; ?>">

					<!-- CARD PRINCIPAL -->
					<div class="card shadow-sm my-3 border-primary">
						<div class="card-header bg-primary text-white fw-bold text-center">
							Confirmación de Inclusión en Expediente
						</div>

						<div class="card-body">

							<p class="text-center mb-2 fw-semibold">
								¿Está seguro de incluir este radicado en el expediente?
							</p>

							<h5 class="text-center fw-bold text-primary mb-4">
								<?php print $numeroExpediente; ?>
							</h5>

							<div class="alert alert-warning" role="alert">
								<strong>Recuerde:</strong> No podrá modificar el número de expediente.
								Si existe un error, deberá excluir este radicado más adelante y, de ser necesario,
								solicitar su anulación. Además, al colocar un nombre de expediente, en Archivo se crea
								una carpeta física donde se incluirán los documentos correspondientes.
							</div>

						</div>
					</div>

					<!-- BOTONES -->
					<div class="d-flex justify-content-center gap-3">

						<?php if (!$btnConfirmar) { ?>
							<input
								name="btnConfirmar"
								type="button"
								onClick="confirmaIncluir();"
								class="btn btn-success btn-sm px-4"
								value="Confirmar">
						<?php } ?>

						<input
							name="cerrar"
							type="button"
							class="btn btn-secondary btn-sm px-4"
							id="envia22"
							onClick="window.opener.$.fn.cargarPagina('expediente/lista_expedientes.php','tabs-a'); window.close();"
							value="Cerrar">

					</div>
				<? }
						} else if ($_POST['numeroExpediente'] != "" && ($expediente->existeExpediente($_POST['numeroExpediente']) === 0)) { ?>
				<script language="JavaScript">
					alert("Error. El nombre del Expediente en el que desea incluir este radicado \n\r no existe en el sistema. Por favor verifique e intente de nuevo.");
				</script>
			<?php } ?>
				</form>
		</div>
	</div>
</body>

</html>
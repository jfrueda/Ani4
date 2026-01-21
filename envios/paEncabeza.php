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
error_reporting(7);
if (isset($nomcarpeta))
	$nomcarpetaOLD = $nomcarpeta;
else {
	$nomcarpetaOLD = "";
	$nomcarpeta = "";
}

if (!isset($_GET['carpeta'])) {
	$carpeta = "0";
	$nomcarpeta = "Entrada";
}

if (!isset($pagina_actual))
	$pagina_actual = "";

if (!isset($estado_sal_max))
	$estado_sal_max = "";

if (!isset($pagina_sig))
	$pagina_sig = "";

if (!isset($dep_sel))
	$dep_sel = "";

$accion  = $pagina_actual . '?' . session_name() . '=' . session_id() .
	'&estado_sal_max=' . $estado_sal_max . '&pagina_sig=' .
	$pagina_sig . '&dep_sel=' . $dep_sel . '&nomcarpeta=' .
	$nomcarpeta . 'method=GET';


if (isset($_GET['nomcarpeta']))
	$getNombreCarpeta = $_GET['nomcarpeta'];
else
	$getNombreCarpeta = "";


?>
<div class="container-fluid my-4">
	<form name="formboton" class="smart-form" action="<?= $accion ?>">
		<!-- Título -->
		<div class="card shadow border-0">
			<div class="card-header bg-orfeo text-white d-flex align-items-center gap-2">
				<i class="fa fa-search fs-5"></i>
				<h5 class="fw-semibold">
					Listado de <?= $getNombreCarpeta ?>
				</h5>
			</div>

			<!-- CARD PRINCIPAL -->
			<div class="card shadow-sm border-0">
				<div class="card-body">
					<table class="table table-bordered align-middle">
						<tr>
							<td class="fw-semibold text-secondary" style="width: 35%;">
								Listado de <?= $getNombreCarpeta ?>
							</td>

							<?php
							if (!isset($swBusqDep)) $swBusqDep = false;

							if (!$swBusqDep) { ?>
								<td class="bg-light">
									<?= $_SESSION['depe_nomb'] ?>
								</td>

							<?php } else { ?>

								<input type="hidden" name="<?= session_name() ?>" value="<?= session_id() ?>">
								<input type="hidden" name="estado_sal" value="<?= $estado_sal ?>">
								<input type="hidden" name="estado_sal_max" value="<?= $estado_sal_max ?>">

								<td>
									<label class="form-label fw-semibold">
										Dependencia
										<i class="fa fa-question-circle ms-1" title="Seleccione la dependencia que realiza la marcación como enviado."></i>
									</label>

									<div class="input-group">
										<?php
										include_once "$ruta_raiz/include/query/envios/queryPaencabeza.php";
										$sqlConcat = $db->conn->Concat($conversion, "'-'", depe_nomb);

										if ($_SESSION['dependencia'] <> 900 && $_SESSION['dependencia'] <> 93004) {
											$sql = "select CAST(depe_codi as varchar(5))||'-'||depe_nomb, depe_codi 
                                                        from dependencia 
                                                        where depe_estado = 1 
                                                        and cast(depe_codi as varchar) like '" . substr($_SESSION['dependencia'], 0, 2) . "%' 
                                                        AND length(cast(depe_codi as varchar)) = 5";
										} else {
											$sql = "select $sqlConcat, depe_codi 
                                                        from dependencia 
                                                        where depe_estado = 1 
                                                        order by depe_codi";
										}

										$rsDep = $db->conn->Execute($sql);

										if (!$depeBuscada) $depeBuscada = $dependencia;

										if ($_SESSION['dependencia'] <> 900 && $_SESSION['dependencia'] <> 93004) {
											print $rsDep->GetMenu2(
												"dep_sel",
												"$dep_sel",
												false,
												false,
												0,
												" onChange='submit();' class=\"form-select\""
											);
										} else {
											print $rsDep->GetMenu2(
												"dep_sel",
												"$dep_sel",
												"9999:TODAS LAS DEPENDENCIAS",
												false,
												0,
												" onChange='submit();' class=\"form-select\""
											);
										}
										?>
									</div>
								</td>
							<?php } ?>
						</tr>

						<!-- Segunda fila Tabla -->
						<tr>
							<td class="bg-light"></td>
							<td>
								<?php if ($porEnviar == 1) { ?>
									<label class="form-label fw-semibold">
										Usuario
										<i class="fa fa-question-circle ms-1"
											title="Seleccione el usuario que realizó la marcación como enviado."></i>
									</label>
									<?php
									if (!isset($usuaCodiEnvio)) $usuaCodiEnvio = "9999";
									if (!$depeBuscada) $depeBuscada = $dependencia;

									$sql = "select usua_nomb, usua_codi 
													from usuario 
													where usua_esta = '1' and depe_codi = $depeBuscada
													order by usua_nomb";

									$rsDep = $db->conn->Execute($sql);

									print $rsDep->GetMenu2(
										"usuaCodiEnvio",
										"$usuaCodiEnvio",
										"9999:TODOS LOS USUARIOS",
										false,
										0,
										" onChange='submit();' class=\"form-select\""
									);
									?>
								<?php } ?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</form>
</div>
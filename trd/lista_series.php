<?php
session_start();
/*
 * Lista Subseries documentales
 * @autor Jairo Losada SuperSOlidaria 
 * @fecha 2009/06 Modificacion Variables Globales.
 */
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;
$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
if (!$ruta_raiz) $ruta_raiz = "..";
$sqlFechaDocto =  $db->conn->SQLDate("Y-m-D H:i:s A", "mf.sgd_rdf_fech");
$sqlSubstDescS =  $db->conn->substr . "(SGD_SRD_DESCRIP, 0, 40)";
$sqlFechaD = $db->conn->SQLDate("Y-m-d H:i A", "SGD_SRD_FECHINI");
$sqlFechaH = $db->conn->SQLDate("Y-m-d H:i A", "SGD_SRD_FECHFIN");
$isqlC = 'select ID,
			  SGD_SRD_CODIGO          AS "CODIGO",
			' . $sqlSubstDescS .  '    AS "SERIE",
			' . $sqlFechaD . ' 			  as "DESDE",
			' . $sqlFechaH . ' 			  as "HASTA" 
			from 
				SGD_SRD_SERIESRD
				' . $whereBusqueda . '
			order by  ' . $sqlSubstDescS;
error_reporting(7);
?>

<!-- Tabla modernizada -->
<div class="card shadow-sm border-0">
	<div class="card-header text-center py-3">
		<h4 class="text-success m-0">SERIES DOCUMENTALES</h4>
	</div>

	<div class="card-header bg-orfeo text-white">
		<h6 class="mb-0">Listado de Series Documentales</h6>
	</div>

	<div class="card-body p-0 margin-botton-table">
		<div class="table-responsive">
			<table class="table table-bordered table-hover align-middle mb-0 smart-form">
				<thead class="table-light">
					<tr>
						<th class="text-center">ID</th>
						<th class="text-center">CÓDIGO</th>
						<th class="text-center">DESCRIPCIÓN</th>
						<th class="text-center">DESDE</th>
						<th class="text-center">HASTA</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$rsC = $db->query($isqlC);
					while (!$rsC->EOF) {
						$codserie  = $rsC->fields["CODIGO"];
						$idSerie   = $rsC->fields["ID"];
						$dserie    = $rsC->fields["SERIE"];
						$fini      = substr($rsC->fields["DESDE"], 0, 10);
						$ffin      = substr($rsC->fields["HASTA"], 0, 10);
					?>
						<tr class="paginacion">
							<td class="text-center">
								<small><?= $idSerie ?></small>
								<i class="fa fa-pencil text-primary ms-2"
									style="cursor:pointer"
									title="Modificar (<?= $codserie ?>) <?= $dserie ?>"
									onclick="modificarSerie(<?= $idSerie ?>,<?= $codserie ?>,'<?= $dserie ?>','<?= $fini ?>','<?= $ffin ?>');">
								</i>
							</td>

							<td class="text-center"><?= $codserie ?></td>
							<td><?= $dserie ?></td>
							<td class="text-center"><?= $fini ?></td>
							<td class="text-center"><?= $ffin ?></td>
						</tr>
					<?php
						$rsC->MoveNext();
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	function modificarSerie(idSerie, codSerie, dSerie, fInicio, fFin) {
		$('#idSerieGrb').val(idSerie);
		$('#idSerieGrbLabel').html(idSerie);
		$('#codserieI').val(codSerie);
		$('#detaserie').val(dSerie);
		$('#fecha_busq').val(fInicio);
		$('#fecha_busq2').val(fFin);
	}
</script>
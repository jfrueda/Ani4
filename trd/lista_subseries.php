<?php
session_start();
/**
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
if (!$ruta_raiz) $ruta_raiz = "..";
$sqlFechaDocto =  $db->conn->SQLDate("Y-m-D H:i:s A", "mf.sgd_rdf_fech");
$sqlSubstDescS =  $db->conn->substr . "(SGD_SBRD_DESCRIP, 0, 40)";
$sqlFechaD = $db->conn->SQLDate("Y-m-d H:i A", "SGD_SBRD_FECHINI");
$sqlFechaH = $db->conn->SQLDate("Y-m-d H:i A", "SGD_SBRD_FECHFIN");
$isqlC = 'select 
				SGD_SBRD_CODIGO          AS "CODIGO",
			' . $sqlSubstDescS .  '    AS "SUBSERIE",
			' . $sqlFechaD . ' 			  as "DESDE",
			' . $sqlFechaH . ' 			  as "HASTA" 
			from 
				SGD_SBRD_SUBSERIERD
			where
					SGD_SRD_CODIGO = ' . $codserie .
	$whereBusqueda . '
			order by  ' . $sqlSubstDescS;
error_reporting(7);
?>
<div class="card shadow-sm border-0">
	<div class="card-header bg-primary text-white text-center py-3">
		<h5 class="mb-0">SUBSERIES DOCUMENTALES</h5>
	</div>
</div>

<div class="table-responsive margin-botton-table">
	<table class="table table-hover align-middle shadow-sm">
		<thead class="table-light">
			<tr>
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
				$tsub       = $rsC->fields["CODIGO"];
				$dsubserie  = $rsC->fields["SUBSERIE"];
				$fini       = $rsC->fields["DESDE"];
				$ffin       = $rsC->fields["HASTA"];
			?>
				<tr>
					<td class="text-center fw-semibold"><?= $tsub ?></td>
					<td><?= $dsubserie ?></td>
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
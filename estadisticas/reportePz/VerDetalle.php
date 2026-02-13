<?php

foreach ($_GET as $key => $valor)    $$key = $valor;
foreach ($_POST as $key => $valor)    $$key = $valor;
foreach ($_SESSION as $key => $valor)    $$key = $valor;

include_once __DIR__.'/dataRepPz.php';
$obj= new DataRepPz();

$resQuery = $obj->getDetalle($codiUsa, $codiDoc, $depend, $tp_rad);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Detalle Paz Y salvos</title>
	<link rel="stylesheet" href="./style.css?<?=hash_file('md5','./style.css')?>">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.min.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/buttons/3.0.0/css/buttons.bootstrap5.min.css" rel="stylesheet">
	<!--fontawensome-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
		  integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" 
		  crossorigin="anonymous" 
		  referrerpolicy="no-referrer" />
</head>
<body>
	<h2>Detalle para el reporte paz y salvo <br> usuario <b><em><?=$nombUsua?></em></b></h2>

<!--------------------------------------- Tabla Para radicados de Entrada ----------------------------------------->
<?php 	if($resQuery["resp"] == "general"): ?>
	<div class="tabla-container-rad my-5">
		<table id="dttable">
			<thead>
				<tr>
					<th >Número de radicado</th>
					<th >Fecha de radicado</th>
					<th >usuario</th>
					<th >Dependencia</th>
					<th >Asunto</th>
					<th >Descripcion anexos</th>
					<th >TRD</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($resQuery["query"] as $clave => $valor):?>
			<tr>
				<td><span>'<?=$valor['RADICADO']?></span></td>
				<td><?=$valor['FECHA_RAD']?></td>
				<td><?=$valor['USUARIO']?></td>
				<td><?=$valor['DEPENDENCIA']?></td>
				<td><?=$valor['ASUNTO']?></td>
				<td><?=$valor['ANEXOS_DESC']?></td>
				<td><?=$valor['TRD']?></td>
			</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif; ?>

<!--------------------------------------- Tabla Para radicados de Entrada ----------------------------------------->
	<?php 	if($resQuery["resp"] == "entrada"): ?>
		<div class="tabla-container-rad my-5">
			<table id="dttable">
				<thead>
					<tr>
						<th >Número de radicado</th>
						<th >Fecha de radicado</th>
						<th >usuario</th>
						<th >Dependencia</th>
						<th >Asunto</th>
						<th >Descripcion anexos</th>
						<th >TRD</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($resQuery["query"] as $clave => $valor):?>
				<tr>
					<td><span>'<?=$valor['RADICADO']?></span></td>
					<td><?=$valor['FECHA_RAD']?></td>
					<td><?=$valor['USUARIO']?></td>
					<td><?=$valor['DEPENDENCIA']?></td>
					<td><?=$valor['ASUNTO']?></td>
					<td><?=$valor['ANEXOS_DESC']?></td>
					<td><?=$valor['TRD']?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
<!--------------------------------------- Tabla Para radicados de Salida ----------------------------------------->
	<?php if($resQuery["resp"] == "salida"): ?>
		<div class="tabla-container-rad my-5">
			<table id="dttable">
				<thead>
					<tr>
						<th >Número de radicado</th>
						<th >Fecha de radicado</th>
						<th >usuario</th>
						<th >Dependencia</th>
						<th >Asunto</th>
						<th >Descripcion anexos</th>
						<th >TRD</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($resQuery["query"] as $clave => $valor): ?>
				<tr>
					<td><span>'<?=$valor['RADICADO']?></span></td>
					<td><?=$valor['FECHA_RAD']?></td>
					<td><?=$valor['USUARIO']?></td>
					<td><?=$valor['DEPENDENCIA']?></td>
					<td><?=$valor['ASUNTO']?></td>
					<td><?=$valor['ANEXOS_DESC']?></td>
					<td><?=$valor['TRD']?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
<!--------------------------------------- Tabla Para Resolucion ----------------------------------------->
	<?php if($resQuery["resp"] == "resolucion"): ?>
		<div class="tabla-container-rad my-5">
			<table id="dttable">
				<thead>
					<tr>
						<th >Número de radicado</th>
						<th >Fecha de radicado</th>
						<th >usuario</th>
						<th >Dependencia</th>
						<th >Asunto</th>
						<th >Descripcion anexos</th>
						<th >TRD</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($resQuery["query"] as $clave => $valor): ?>
				<tr>
					<td><span>'<?=$valor['RADICADO']?></span></td>
					<td><?=$valor['FECHA_RAD']?></td>
					<td><?=$valor['USUARIO']?></td>
					<td><?=$valor['DEPENDENCIA']?></td>
					<td><?=$valor['ASUNTO']?></td>
					<td><?=$valor['ANEXOS_DESC']?></td>
					<td><?=$valor['TRD']?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
<!--------------------------------------- Tabla Para Circular interna ----------------------------------------->
	<?php if($resQuery["resp"] == "cir_int"): ?>
		<div class="tabla-container-rad my-5">
			<table id="dttable">
				<thead>
					<tr>
						<th >Número de radicado</th>
						<th >Fecha de radicado</th>
						<th >usuario</th>
						<th >Dependencia</th>
						<th >Asunto</th>
						<th >Descripcion anexos</th>
						<th >TRD</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($resQuery["query"] as $clave => $valor): ?>
				<tr>
					<td><span>'<?=$valor['RADICADO']?></span></td>
					<td><?=$valor['FECHA_RAD']?></td>
					<td><?=$valor['USUARIO']?></td>
					<td><?=$valor['DEPENDENCIA']?></td>
					<td><?=$valor['ASUNTO']?></td>
					<td><?=$valor['ANEXOS_DESC']?></td>
					<td><?=$valor['TRD']?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
	<!--------------------------------------- Tabla Para Circular externa ----------------------------------------->
	<?php if($resQuery["resp"] == "cir_ext"): ?>
		<br>
		<div class="tabla-container-rad my-5">
			<table id="dttable">
				<thead>
					<tr>
						<th >Número de radicado</th>
						<th >Fecha de radicado</th>
						<th >usuario</th>
						<th >Dependencia</th>
						<th >Asunto</th>
						<th >Descripcion anexos</th>
						<th >TRD</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($resQuery["query"] as $clave => $valor): ?>
				<tr>
					<td><span>'<?=$valor['RADICADO']?></span></td>
					<td><?=$valor['FECHA_RAD']?></td>
					<td><?=$valor['USUARIO']?></td>
					<td><?=$valor['DEPENDENCIA']?></td>
					<td><?=$valor['ASUNTO']?></td>
					<td><?=$valor['ANEXOS_DESC']?></td>
					<td><?=$valor['TRD']?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
	<!--------------------------------------- Tabla Para Circular Autos ----------------------------------------->
	<?php if($resQuery["resp"] == "autos"): ?>
		<br>
		<div class="tabla-container-rad my-5">
			<table id="dttable">
				<thead>
					<tr>
						<th >Número de radicado</th>
						<th >Fecha de radicado</th>
						<th >usuario</th>
						<th >Dependencia</th>
						<th >Asunto</th>
						<th >Descripcion anexos</th>
						<th >TRD</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($resQuery["query"] as $clave => $valor): ?>
				<tr>
					<td><span>'<?=$valor['RADICADO']?></span></td>
					<td><?=$valor['FECHA_RAD']?></td>
					<td><?=$valor['USUARIO']?></td>
					<td><?=$valor['DEPENDENCIA']?></td>
					<td><?=$valor['ASUNTO']?></td>
					<td><?=$valor['ANEXOS_DESC']?></td>
					<td><?=$valor['TRD']?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
	<!--------------------------------------- Tabla Para Circular Vo.Bo ----------------------------------------->
	<?php if($resQuery["resp"] == "vobo"): ?>
		<br>
		<div class="tabla-container-rad my-5">
			<table id="dttable">
				<thead>
					<tr>
						<th >Número de radicado</th>
						<th >Fecha de radicado</th>
						<th >usuario</th>
						<th >Dependencia</th>
						<th >Asunto</th>
						<th >Descripcion anexos</th>
						<th >TRD</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($resQuery["query"] as $clave => $valor): ?>
				<tr>
					<td><span>'<?=$valor['RADICADO']?></span></td>
					<td><?=$valor['FECHA_RAD']?></td>
					<td><?=$valor['USUARIO']?></td>
					<td><?=$valor['DEPENDENCIA']?></td>
					<td><?=$valor['ASUNTO']?></td>
					<td><?=$valor['ANEXOS_DESC']?></td>
					<td><?=$valor['TRD']?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
	<!--------------------------------------- Tabla Para Circular devueltos ----------------------------------------->
	<?php if($resQuery["resp"] == "devueltos"): ?>
		<br>
		<div class="tabla-container-rad my-5">
			<table id="dttable">
				<thead>
					<tr>
						<th >Número de radicado</th>
						<th >Fecha de radicado</th>
						<th >usuario</th>
						<th >Dependencia</th>
						<th >Asunto</th>
						<th >Descripcion anexos</th>
						<th >TRD</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($resQuery["query"] as $clave => $valor): ?>
				<tr>
					<td><span>'<?=$valor['RADICADO']?></span></td>
					<td><?=$valor['FECHA_RAD']?></td>
					<td><?=$valor['USUARIO']?></td>
					<td><?=$valor['DEPENDENCIA']?></td>
					<td><?=$valor['ASUNTO']?></td>
					<td><?=$valor['ANEXOS_DESC']?></td>
					<td><?=$valor['TRD']?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
	<!--------------------------------------- Tabla Para Circular jefe_area ----------------------------------------->
	<?php if($resQuery["resp"] == "jefe_area"): ?>
		<br>
		<div class="tabla-container-rad my-5">
			<table id="dttable">
				<thead>
					<tr>
						<th >Número de radicado</th>
						<th >Fecha de radicado</th>
						<th >usuario</th>
						<th >Dependencia</th>
						<th >Asunto</th>
						<th >Descripcion anexos</th>
						<th >TRD</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($resQuery["query"] as $clave => $valor): ?>
				<tr>
					<td><span>'<?=$valor['RADICADO']?></span></td>
					<td><?=$valor['FECHA_RAD']?></td>
					<td><?=$valor['USUARIO']?></td>
					<td><?=$valor['DEPENDENCIA']?></td>
					<td><?=$valor['ASUNTO']?></td>
					<td><?=$valor['ANEXOS_DESC']?></td>
					<td><?=$valor['TRD']?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
	<!--------------------------------------- Tabla Para Circular memo_multip ----------------------------------------->
	<?php if($resQuery["resp"] == "memo_multip"): ?>
		<br>
		<div class="tabla-container-rad my-5">
			<table id="dttable">
				<thead>
					<tr>
						<th >Número de radicado</th>
						<th >Fecha de radicado</th>
						<th >usuario</th>
						<th >Dependencia</th>
						<th >Asunto</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($resQuery["query"] as $clave => $valor): ?>
				<tr>
					<td><span>'<?=$valor['RADI_NUME_RADI']?></span></td>
					<td><?=$valor['RADI_FECH_RADI']?></td>
					<td><?=strtoupper($valor['USUA_NOMB'])?></td>
					<td><?=$valor['DEPE_NOMB']?></td>
					<td><?=$valor['RA_ASUN']?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
<!--------------------------------------- Tabla Para Borradores ----------------------------------------->
	<?php if($resQuery["resp"] == "borradores"): ?>
		<br>
		<div class="tabla-container-rad my-5">
			<table id="dttable">
				<thead>
					<tr>
						<th >Número de radicado</th>
						<th >Fecha de radicado</th>
						<th >usuario</th>
						<th >Dependencia</th>
						<th >Asunto</th>
						<th >Descripcion anexos</th>
						<th >TRD</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($resQuery["query"] as $clave => $valor): ?>
				<tr>
					<td><span>'<?=$valor['RADICADO']?></span></td>
					<td><?=$valor['FECHA_RAD']?></td>
					<td><?=$valor['USUARIO']?></td>
					<td><?=$valor['DEPENDENCIA']?></td>
					<td><?=$valor['ASUNTO']?></td>
					<td><?=$valor['ANEXOS_DESC']?></td>
					<td><?=$valor['TRD']?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
<!--------------------------------------- Tabla Para memorando ----------------------------------------->
	<?php if($resQuery["resp"] == "memorando"): ?>
		<br>
		<div class="tabla-container-rad my-5">
			<table id="dttable">
				<thead>
					<tr>
						<th >Número de radicado</th>
						<th >Fecha de radicado</th>
						<th >usuario</th>
						<th >Dependencia</th>
						<th >Asunto</th>
						<th >Descripcion anexos</th>
						<th >TRD</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($resQuery["query"] as $clave => $valor): ?>
				<tr>
					<td><span>'<?=$valor['RADICADO']?></span></td>
					<td><?=$valor['FECHA_RAD']?></td>
					<td><?=$valor['USUARIO']?></td>
					<td><?=$valor['DEPENDENCIA']?></td>
					<td><?=$valor['ASUNTO']?></td>
					<td><?=$valor['ANEXOS_DESC']?></td>
					<td><?=$valor['TRD']?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>

	<!--------------------------------------- Tabla Para Informados ----------------------------------------->
	<?php if($resQuery["resp"] == "informados"): ?>
		<br>
		<div class="tabla-container-rad my-5">
			<table id="dttable">
				<thead>
					<tr>
						<th >Número de radicado</th>
						<th >usuario informado</th>
						<th >Dependencia</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($resQuery["query"] as $clave => $valor): ?>
				<tr>
					<td><span>'<?=$valor['RADICADO']?></span></td>
					<td><?=$valor['USUARIO']?></td>
					<td><?=$valor['DEPENDENCIA']?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>

		<!--------------------------------------- Tabla Para expedientes ----------------------------------------->
	<?php if($resQuery["resp"] == "expedientes"): ?>
		<br>
		<div class="tabla-container-rad my-5">
			<table id="dttable">
				<thead>
					<tr>
						<th >Número de expediente</th>
						<th >Fecha de expediente</th>
						<th >año de expediente</th>
						<th >usuario responsable</th>
						<th >Dependencia</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($resQuery["query"] as $clave => $valor): ?>
				<tr>
					<td><span>'<?=$valor['NUMERO_EXP']?></span></td>
					<td><?=$valor['FECHA_EXP']?></td>
					<td><?=$valor['ANIO_EXP']?></td>
					<td><?=$valor['NOMB_RESP']?></td>
					<td><?=$valor['DEPENDENCIA']?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>


	<div class="container-btn">
		<!--<a href="./descExcelRep.php?codiUsa=<?=$codiUsa?>&codiDoc=<?=$codiDoc?>&depend=<?=$depend?>&tp_rad=<?=$tp_rad?>"><img width="48" height="48" src="https://img.icons8.com/doodle/48/microsoft-excel-2019.png" alt="Descargar reporte" border="0" title='Descargar reporte'></a>-->

		<!--<img width="50" height="50" src="https://img.icons8.com/bubbles/50/microsoft-excel-2019.png" alt="microsoft-excel-2019"/>-->

		<button type="button" id='newRep'>Generar otro reporte</button>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.bootstrap5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.print.min.js"></script>

	<script>
		$(document).ready(function () {
			$('#dttable').DataTable({
				responsive: true,
				dom: 'Bftilp',
				buttons: [
							{
								extend: "copyHtml5",
								text: "<i class='fas fa-copy'></i>",
								titleAttr: 'Copiar',
								className: 'btn btn-gray'
							},
							{
								extend: "csvHtml5",
								text:"<i class='fa-sharp fa-solid fa-file-csv'></i>",
								titleAttr:'Exportar a csv',
								className: 'btn btn-info',
							},
							{
								extend: "excelHtml5",
								text:"<i class='fa-regular fa-file-excel'></i>",
								titleAttr:'Exportar a Excel',
								className: 'btn btn-success',
							},
							{
								extend: "pdfHtml5",
								text:"<i class='fa-regular fa-file-pdf'></i>",
								titleAttr:'Exportar a pdf',
								className: 'btn btn-danger',
							}
					]
			});
		});

		var url = window.location.href;

		var url = window.location.href;
		var partesURL = url.split("/");
		var subdominio = partesURL[3];
		//console.log("Subdominio: " + subdominio);

	let btnNewRep = document.getElementById('newRep');
	btnNewRep.addEventListener('click',()=>{window.location.replace(`/${subdominio}/index_frames.php`);})
	//btnNewRep.addEventListener('click',()=>{window.location.replace('./ReportePazSalvo.php');})
	</script>
</body>
</html>
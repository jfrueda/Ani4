<?php
include_once __DIR__ . '/dataRepPz.php';
$obj = new DataRepPz();
$ruta_raiz   = "../..";

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Generador de Informes</title>
	<link rel="stylesheet" href="./style.css?<?= hash_file('md5', './style.css') ?>">
	<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
</head>

<body>
	<div class="container-fluid my-4">
		<div class="card shadow border-0 mb-4">
			<div class="card-header bg-primary text-white py-3">
				<h3 class="mb-0">
					<i class="fa fa-file-text-o me-2"></i> Generador de Informes de Paz y Salvo
				</h3>
			</div>

			<div class="card-body">
				<form>
					<!-- DEPENDENCIA -->
					<div class="mb-3">
						<label for="dependencia" class="form-label fw-semibold">Seleccionar Dependencia</label>
						<input
							list="dataListdep"
							id="dependencia"
							name="dependencia"
							autocomplete="off"
							class="form-control"
							placeholder="Escriba la dependencia...">
						<datalist id="dataListdep">
							<?php foreach ($obj->getDependencia() as $value): ?>
								<option value="<?= $value['DEPENDENCIAS'] ?>">
								<?php endforeach; ?>
						</datalist>
					</div>

					<!-- USUARIO -->
					<div class="mb-3">
						<label for="usuario" class="form-label fw-semibold">Seleccionar Usuario</label>
						<input
							list="dataListUsua"
							id="usuario"
							name="usuario"
							disabled
							autocomplete="off"
							class="form-control"
							placeholder="Seleccione una dependencia primero">
						<datalist id="dataListUsua"></datalist>

						<input type="text" name="usrMag" id="magicDataUsr" hidden>
					</div>

					<!-- BOTÓN -->
					<button
						type="button"
						id="btnSend"
						hidden
						class="btn btn-success px-4">
						Generar Informe
					</button>
				</form>
			</div>
		</div>

		<!-- MODAL RESULTADO -->
		<div class="modal" id="myModal" tabindex="-1">
			<div class="modal-dialog modal-xl">
				<div class="modal-content shadow">
					<div class="modal-header bg-primary text-white">
						<h5 class="modal-title" id="titulo"></h5>
						<button type="button" class="btn-close btn-close-white" onclick="cerrarModal()"></button>
					</div>

					<div class="modal-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered align-middle">
								<thead class="table-light">
									<tr>
										<th>Bandeja General</th>
										<th>Bandeja de Entrada</th>
										<th>Bandeja de Salida</th>
										<th>Bandeja de Resoluciones</th>
										<th>Bandeja de Memorando</th>
										<th>Bandeja de Circular Interna</th>
										<th>Bandeja de Circular Externa</th>
										<th>Bandeja de Autos</th>
										<th>Bandeja de Vo.Bo.</th>
										<th>Bandeja de Devueltos</th>
										<th>Bandeja de Jefe de Área</th>
										<th>Bandeja Memorando Múltiple</th>
										<th>Informados</th>
										<th>Expediente</th>
									</tr>
								</thead>
								<tbody id="dataRes"></tbody>
							</table>
						</div>

					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" onclick="cerrarModal()">
							Generar otro reporte
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- MODAL ERROR -->
		<div class="modal" id="ModalError" tabindex="-1">
			<div class="modal-dialog modal-content-err">
				<div class="modal-content shadow border-danger">
					<div class="modal-header bg-danger text-white">
						<h5 class="modal-title">Error</h5>
						<button type="button" class="btn-close btn-close-white" onclick="cerrarModalErr()"></button>
					</div>

					<div class="modal-body">
						<div id="usuaPazSalvo" class="fw-semibold text-danger"></div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-dark" onclick="cerrarModalErr()">
							Generar otro reporte
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- LOADER -->
		<div class="overlay" id="loader-overlay">
			<div class="loader-container">
				<div class="loader"></div>
				<p>Cargando...</p>
			</div>
		</div>

		<div id="informe"></div>
	</div>

	<script src="./reportePz.js?<?= hash_file('md5', './reportePz.js') ?>"></script>
</body>
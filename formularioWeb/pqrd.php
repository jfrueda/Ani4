<?php
	session_start();
	define('ADODB_ASSOC_CASE', 1);
	$ruta_raiz = "..";
	$ADODB_COUNTRECS = false;
	
	include_once("$ruta_raiz/processConfig.php");
	include_once("$ruta_raiz/include/db/ConnectionHandler.php");
    include_once("$ruta_raiz/formularioWeb/solicitudes_sql.php");
	
	$pregunta = '1';
	$now = date('Y-m-d H:i:s');
	$now = date('Y-m-d H:i:s', strtotime($now));
	$inicio_mantenimiento = date('Y-m-d H:i:s', strtotime($fecha_inicio_mantenimiento_formulario_web));
	$fin_mantenimiento = date('Y-m-d H:i:s', strtotime($fecha_fin_mantenimiento_formulario_web));
	if (
		($now >= $inicio_mantenimiento) && ($now <= $fin_mantenimiento) && 
		in_array($pregunta, explode(',', $deshabilitados_mantenimiento_formulario_web)) &&
		(!isset($_GET['testing'])) 
	)
	{
    	header('Location: '.$url_redireccion_mantenimiento_formulario_web);
	}

	$db = new ConnectionHandler($ruta_raiz);
	$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

	//departamentos($db) de solicitudes_sql.php
	$paises = paises($db);
	$departamentos = departamentos($db);
	$municipios = ciudades_tx_all($db);
	$tipos_entidades = tipos_entidades($db);
	$tipos_documentos = tipos_documentos($db);
	$discapacidades = discapacidades($db);
	$medicamentos = medicamentos($db);

	$poblaciones_especiales = [
		'Desplazado',
		'Habitante de calle',
		'Persona con discapacidad',
		'Población carcelaria (Presos)',
		'Trabajador (a) sexual',
		'Violencia de género',
		'Violencia conflicto armado',
		'No Aplica'
	];

	$grupos_etnicos = [
		'Afrocolombiano o Afrodescendiente',
		'Indígena',
		'Mulato',
		'Palanquero (De San Basilio)',
		'Raizal (Del Archipiélago de San Andrés y Providencia)',
		'ROM - Gitano',
		'No Aplica'
	];

	//$api = 'crear api_pqrd en sgd_config, http://40.121.54.187:8083/api/api/';
	$api = $api_pqrd;
?>
<?php include ('header.php') ?>
	<div class="loader">
		<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
	</div>
	<div class="container">
		<div class="row justify-content-between">
			<?php include ('banner.php') ?>
		</div>
		<div class="row justify-content-between">
			<div class="col-sm">
				<p class="fecha">
					<small>Fecha radicación <?= date('d/m/Y H:i') ?></small>
				</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm">
				<p class="lead" style="text-align: justify;">
					Este formulario está destinado para presentar PQRD y Solicitudes de Información relacionados con la prestación de servicios de salud. 
				</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm">
				<div class="alert alert-info" role="alert">
					Los campos con <strong>*</strong> son de diligenciamiento obligatorio
				</div>
			</div>
		</div>
		<form action="pqrd_service.php" id="form-solicitud" enctype="multipart/form-data" method="post">
			<div class="row" id="formulario_afectado">
				<div class="col-sm">
					<div class="form-row">
						<div class="col-md-6 form-group">
							<fieldset>
								<label for="afectado">* ¿Es usted el paciente o afectado? <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione SI, si usted es el paciente o afectado o eleccione NO, si usted va a formular una PQRD en nombre de otra persona"></i></label><br>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="afectado" id="afectado1" value="Si" data-required="true" data-label="Es paciente o afectado">
									<label class="form-check-label" for="afectado1">Si</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="afectado" id="afectado2" value="No" data-required="true">
									<label class="form-check-label" for="afectado2">No</label>
								</div>
							</fieldset>
							<span class="error small">Indique si usted es el paciente o afectado</span>
						</div>
					</div>
					<div class="row">
						<div class="col-sm">
							<h4 class="section-h">Información del paciente o afectado</h4>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-3 form-group">
							<label for="id">* Tipo de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el tipo de identificación"></i></label>
							<select name="tipo_identificacion_afectado" id="tipo_identificacion_afectado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Tipo de identificación del afectado">
								<?php foreach ($tipos_documentos as $tipo) { ?>
									<option value="<?=$tipo['TDID_CODI']?>"><?=$tipo['TDID_DESC']?></option>
								<?php } ?>
							</select>
							<span class="error small">Seleccione el tipo de identificación del afectado</span>
						</div>
						<div class="col-md-3 form-group">
							<label for="id_afectado">* Número de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número del documento de identificación del paciente o afectado. En caso de que sea un menor de edad que no cuente con identificación, digite el número de documento del tutor o de la persona a cargo. Recuerde que si selecciona pasaporte debe ingresar números y letras de lo contrario sólo números."></i></label>
							<input type="number" class="form-control" id="id_afectado" name="id_afectado" autocomplete="no" data-required="true" data-label="Número de identificación del afectado" maxlength="15">
							<span class="error small">Ingrese el número del documento de identificación del paciente o afectado</span>
						</div>
					</div>
					<div class="row">
						<div class="col-sm">
							<hr>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-3 form-group">
							<label for="nombre_afectado_1">* Primer nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer nombre"></i></label>
							<input type="text" class="form-control alpha-only" id="nombre_afectado_1" name="nombre_afectado_1" autocomplete="no" data-required="true" data-label="Primer nombre del afectado">
							<span class="error small">Ingrese el primer nombre del paciente o afectado</span>
						</div>
						<div class="col-md-3 form-group">
							<label for="nombre_afectado_2">Segundo nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo nombre"></i></label>
							<input type="text" class="form-control alpha-only" id="nombre_afectado_2" name="nombre_afectado_2" autocomplete="no">
						</div>
						<div class="col-md-3 form-group">
							<label for="apellidos_afectado_1">* Primer apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer apellido"></i></label>
							<input type="text" class="form-control alpha-only" id="apellidos_afectado_1" name="apellidos_afectado_1" autocomplete="no" data-required="true" data-label="Primer apellido del afectado">
							<span class="error small">Ingrese el primer apellido del paciente o afectado</span>
						</div>
						<div class="col-md-3 form-group">
							<label for="apellidos_afectado_2">Segundo apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo apellido"></i></label>
							<input type="text" class="form-control alpha-only" id="apellidos_afectado_2" name="apellidos_afectado_2" autocomplete="no">
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-3 form-group">
							<label for="fecha_nacimiento">* Fecha nacimiento <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione su fecha de nacimiento"></i></label>
							<input type="text" class="form-control" readonly id="fecha_nacimiento" name="fecha_nacimiento" autocomplete="no" data-required="true" data-label="Fecha de nacimiento del afectado">
							<span class="error small">Seleccione la fecha de nacimiento del paciente o afectado</span>
						</div>
						<div class="col-md-3 form-group">
							<label for="edad">Edad</label>
							<input type="number" class="form-control" id="edad" name="edad" autocomplete="no" readonly>
						</div>
						<div class="col-md-3 form-group">
							<label for="rango_edad">Rango edad</label>
							<input type="text" class="form-control" id="rango_edad" name="rango_edad" autocomplete="no" readonly>
						</div>
						<div class="col-md-3 form-group">
							<label for="sexo1">* Sexo <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione su sexo"></i></label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="sexo" id="sexo1" value="Masculino" data-required="true" data-label="Sexo afectado">
								<label class="form-check-label" for="sexo1">Masculino</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="sexo" id="sexo2" value="Femenino" data-required="true">
								<label class="form-check-label" for="sexo2">Femenino</label>
							</div>
							<span class="error small">Seleccione el sexo del paciente o afectado</span>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-3 form-group">
							<label for="gestante">Madre gestante <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione si es madre gestante"></i></label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" name="gestante" id="gestante" value="Madre gestante">
							</div>
						</div>
						<div class="col-md-3 form-group">
							<label for="poblacion_especial">* Población especial <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione su población especial"></i></label>
							<select name="poblacion_especial" id="poblacion_especial" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Población especial del afectado">
								<?php foreach ($poblaciones_especiales as $poblacion) { ?>
									<option value="<?=$poblacion?>"><?=$poblacion?></option>
								<?php } ?>
							</select>
							<span class="error small">Seleccione si paciente o afectado pertenece a una población especial, de no ser asi seleccione no aplica.</span>
						</div>
						<div id="selector_discapacidades" class="col-md-3 form-group" style="display:none">
							<label for="discapacidad">* Discapacidades <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione tipo discapacidad"></i></label>
							<select name="discapacidad" id="discapacidad" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Discapacidad del afectado">
								<?php foreach ($discapacidades as $discapacidad) { ?>
									<option value="<?=$discapacidad['ID']?>"><?=$discapacidad['NOMBRE']?></option>
								<?php } ?>
							</select>
							<span class="error small">Seleccione la discapacidad del afectado.</span>
						</div>
						<div class="col-md-3 form-group">
							<label for="grupo_etnico">* Grupo étnico <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione su grupo étnico"></i></label>
							<select name="grupo_etnico" id="grupo_etnico" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Grupo étnico del afectado">
								<?php foreach ($grupos_etnicos as $grupo) { ?>
									<option value="<?=$grupo?>"><?=$grupo?></option>
								<?php } ?>
							</select>
							<span class="error small">Seleccione si paciente o afectado pertenece a grupo étnico, de no ser asi seleccione no aplica.</span>
						</div>
					</div>
					<div class="row">
						<div class="col-sm">
							<hr>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-3 form-group">
							<label for="pais_afectado">* País <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el país en el que reside "></i></label>
							<select name="pais_afectado" id="pais_afectado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="País de residencia afectado">
								<?php foreach ($paises as $pais) { ?>
									<option value="<?=$pais['NOMBRE']?>"><?=$pais['NOMBRE']?></option>
								<?php } ?>
							</select>
							<span class="error small">Seleccione el país de residencia del paciente o afectado</span>
						</div> 
						<div class="col-md-3 form-group">
							<label for="departamento_afectado">* Departamento <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el departamento en el que reside"></i></label>
							<select name="departamento_afectado" id="departamento_afectado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Departamento de residencia afectado">
								<?php foreach ($departamentos as $departamento) { ?>
									<option value="<?=$departamento['DPTO_CODI']?>"><?=$departamento['DPTO_NOMB']?></option>
								<?php } ?>
							</select>
							<span class="error small">Seleccione el departamento de residencia del paciente o afectado</span>
						</div>
						<div class="col-md-3 form-group">
							<label for="ciudad_afectado">* Municipio <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el municipio en el que vive"></i></label>
							<select name="ciudad_afectado" id="ciudad_afectado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Ciudad de residencia afectado"></select>
							<div id="ciudad_bar_afectado" class="progress" style="display:none; height:5px;">
								<div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
							</div>
							<small id="help" class="form-text text-muted">*ANM Areas no municipalizadas</small>
							<span class="error small">Seleccione el municipio de residencia del paciente o afectado</span>
						</div>
						<div class="col-md-6 form-group" style="display:none;">
							<label for="provincia_afectado">* Ciudad / Estado / Provincia</label>
							<input type="text" class="form-control" id="provincia_afectado" name="provincia_afectado" autocomplete="no" data-required="true">
							<span class="error small">Ingrese la ciudad, estado y/o provincia de residencia del paciente o afectado</span>
						</div>
						<div class="col-md-3 form-group">
							<label for="direccion_afectado">* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i></label>
							<input type="text" class="form-control" id="direccion_afectado" name="direccion_afectado" autocomplete="no" data-required="true" data-label="Dirección de residencia afectado">
							<span class="error small">Ingrese la dirección del paciente o afectado</span>
						</div>
					</div>
					<div class="row">
						<div class="col-sm">
							<hr>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-3 form-group form-control-celular">
							<label for="celular_afectado">* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<input type="checkbox" data-active="#celular_afectado" aria-label="Checkbox for following text input" checked>
									</div>
								</div>
								<input 
									type="number" 
									class="form-control celular" 
									id="celular_afectado" 
									name="celular_afectado" 
									autocomplete="no" 
									data-required="true" 
									data-regex="(.)\1{6}" 
									data-label-regex="Debe ingresar un número de celular válido para el afectado este debe iniciar con 3 y no contener mas de 6 números repetidos" 
									data-label="Celular del afectado" 
									maxlength="10"
								>
							</div>
							<span class="error small">Ingrese un número de celular válido para el paciente o afectado</span>
						</div>
						<div class="col-md-3 form-group">
							<label for="telefono_afectado">* Teléfono fijo <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono fijo, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<input type="checkbox" data-active="#telefono_afectado" aria-label="Checkbox for following text input" checked>
									</div>
								</div>
								<input 
									type="number" 
									class="form-control" 
									id="telefono_afectado" 
									name="telefono_afectado" 
									autocomplete="no" 
									data-required="true" 
									data-regex="(.)\1{6}" 
									data-label-regex="Debe ingresar un número de teléfono válido para el afectado este no puede contener mas de 6 números repetidos" 
									data-label="Teléfono fijo del afectado"
									maxlength="10"
								>
							</div>
							<span class="error small">Ingrese el número de teléfono del paciente o afectado</span>
						</div>
						<div class="col-md-6">
							<div class="form-row">
								<div class="col-md-6 form-group">
									<label for="correo_afectado">* Correo electrónico <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su correo electrónico, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<div class="input-group-text">
												<input type="checkbox" data-active="#correo_afectado,#dominio_afectado" aria-label="Checkbox for following text input" checked>
											</div>
										</div>
										<input type="text" class="form-control" id="correo_afectado" name="correo_afectado" autocomplete="no" data-required="true" data-label="Correo del afectado">
									</div>
									<span class="error small">Ingrese el correo electrónico del paciente o afectado</span>
								</div>
								<div class="col-md-6 email-component">
									<label for=""><i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el dominio de la lista desplegable si no lo encuentra digítelo"></i>&nbsp;</label>
									<input type="text" class="form-control dominio" id="dominio_afectado" name="dominio_afectado" placeholder="dominio" aria-label="dominio" autocomplete="no" data-required="true" data-label="Dominio del correo del afectado">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row" style="display:none;" id="formulario_peticionario">
				<div class="col-sm">
					<div class="row">
						<div class="col-sm">
							<h4 class="section-h">Información del peticionario</h4>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-6 form-group">
							<label for="tipo">* Tipo remitente <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Persona natural se refiere a un individuo, Persona jurídica a una empresa u organización, Anónimo que no quiere dar a conocer su identidad. Defina si la persona que está formulando la queja es una Persona natural, Persona jurídica o Anónimo"></i></label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="tipo" id="tipo1" value=1 data-required="true" data-label="Tipo remitente del peticionario">
								<label class="form-check-label" for="tipo1">Natural</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="tipo" id="tipo2" value=2 data-required="true">
								<label class="form-check-label" for="tipo2">Jurídica</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="tipo" id="tipo3" value=3 data-required="true">
								<label class="form-check-label" for="tipo3">Anónimo</label>
							</div>
							<span class="error small">Seleccione el tipo de peticionario</span>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-3 form-group" data-natural data-juridico>
							<label for="id">* Tipo de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el tipo de identificación"></i></label>
							<select name="tipo_identificacion_peticionario" id="tipo_identificacion_peticionario" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Tipo de identificación del peticionario">
								<?php foreach ($tipos_documentos as $tipo) { ?>
									<option value="<?=$tipo['TDID_CODI']?>"><?=$tipo['TDID_DESC']?></option>
								<?php } ?>
							</select>
							<span class="error small">Seleccione el tipo de identificación del peticionario</span>
						</div>
						<div class="col-md-3 form-group" data-natural data-juridico>
							<label for="id_peticionario">* Número de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número del documento de identificación del peticionario. En caso de que sea un menor de edad que no cuente con identificación, digite el número de documento del tutor o de la persona a cargo. Recuerde que si selecciona pasaporte debe ingresar números y letras de lo contrario sólo números."></i></label>
							<input type="number" class="form-control" id="id_peticionario" name="id_peticionario" autocomplete="no"  data-required="true" data-label="Número de identificación del peticionario" maxlength="15">
							<span class="error small">Ingrese el número de identificación del peticionario</span>
						</div>
					</div>
					<div class="row">
						<div class="col-sm">
							<hr>
						</div>
					</div>
					<div class="form-row" data-natural>
						<div class="col-md-3 form-group">
							<label for="nombre_peticionario_1">* Primer nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer nombre"></i></label>
							<input type="text" class="form-control alpha-only" id="nombre_peticionario_1" name="nombre_peticionario_1" autocomplete="no" data-required="true" data-label="Primer nombre del peticionario">
							<span class="error small">Ingrese el primer nombre del peticionario</span>
						</div>
						<div class="col-md-3 form-group">
							<label for="nombre_peticionario_2">Segundo nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo nombre"></i></label>
							<input type="text" class="form-control alpha-only" id="nombre_peticionario_2" name="nombre_peticionario_2" autocomplete="no">
						</div>
						<div class="col-md-3 form-group">
							<label for="apellidos_peticionario_1">* Primer apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer apellido"></i></label>
							<input type="text" class="form-control alpha-only" id="apellidos_peticionario_1" name="apellidos_peticionario_1" autocomplete="no" data-required="true" data-label="Primer apellido peticionario">
							<span class="error small">Ingrese el primer apellido del peticionario</span>
						</div>
						<div class="col-md-3 form-group">
							<label for="apellidos_peticionario_2">Segundo apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo apellido"></i></label>
							<input type="text" class="form-control alpha-only" id="apellidos_peticionario_2" name="apellidos_peticionario_2" autocomplete="no">
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-12 form-group" style="display: none;" data-juridico>
							<label for="rs">* Razón social <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la razón social"></i></label>
							<input type="text" class="form-control alpha-only" id="rs" name="rs" autocomplete="no" data-required="true" data-label="Razón social del peticionario">
							<span class="error small">Ingrese la razón social del peticionario</span>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-3 form-group" data-natural data-juridico>
							<label for="pais_peticionario">* País <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el país en el que reside"></i></label>
							<select name="pais_peticionario" id="pais_peticionario" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="País de residencia del peticionario">
								<?php foreach ($paises as $pais) { ?>
									<option value="<?=$pais['NOMBRE']?>"><?=$pais['NOMBRE']?></option>
								<?php } ?>
							</select>
							<span class="error small">Seleccione el país de residencia del peticionario</span>
						</div> 
						<div class="col-md-3 form-group" data-natural data-juridico>
							<label for="departamento_peticionario">* Departamento <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el departamento en el que reside"></i></label>
							<select name="departamento_peticionario" id="departamento_peticionario" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Departamento de residencia del peticionario">
								<?php foreach ($departamentos as $departamento) { ?>
									<option value="<?=$departamento['DPTO_CODI']?>"><?=$departamento['DPTO_NOMB']?></option>
								<?php } ?>
							</select>
							<span class="error small">Seleccione el departamento de residencia del peticionario</span>
						</div>
						<div class="col-md-3 form-group" data-natural data-juridico>
							<label for="ciudad_peticionario">* Municipio <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el municipio en el que vive"></i></label>
							<select name="ciudad_peticionario" id="ciudad_peticionario" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Municipio de residencia del peticionario"></select>
							<div id="ciudad_bar_peticionario" class="progress" style="display:none; height:5px;">
								<div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
							</div>
							<small id="emailHelp" class="form-text text-muted">*ANM Areas no municipalizadas</small>
							<span class="error small">Seleccione el municipio de residencia del peticionario</span>
						</div>
						<div class="col-md-6 form-group" data-natural data-juridico style="display:none;">
							<label for="provincia_peticionario">* Ciudad / Estado / Provincia</label>
							<input type="text" class="form-control" id="provincia_peticionario" name="provincia_peticionario" autocomplete="no" data-required="true" data-label="Ciudad - Estado y/o provincia de residencia del peticionario">
							<span class="error small">Ingrese la ciudad, estado y/o provincia en la que reside el peticionario</span>
						</div>
						<div class="col-md-3 form-group" data-natural data-juridico>
							<label for="direccion_peticionario">* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i></label>
							<input type="text" class="form-control" id="direccion_peticionario" name="direccion_peticionario" autocomplete="no" data-required="true" data-label="Dirección de residencia del peticionario">
							<span class="error small">Ingrese la dirección del peticionario</span>
						</div>
						<div class="col-md-3 form-group" data-juridico style="display:none;">
							<label for="direccion_peticionario_2">* Dirección comercial <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección comercial"></i></label>
							<input type="text" class="form-control" id="direccion_peticionario_2" name="direccion_peticionario_2" autocomplete="no" data-required="true" data-label="Dirección comercial del peticionario">
							<span class="error small">Ingrese la dirección comercial del peticionario</span>
						</div>
					</div>
					<div class="row" data-natural data-juridico>
						<div class="col-sm">
							<hr>
						</div>
					</div>
					<div class="form-row" data-natural data-juridico>
						<div class="col-md-3 form-group">
							<label for="celular_peticionario">* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<input type="checkbox" data-active="#celular_peticionario" aria-label="Checkbox for following text input" checked>
									</div>
								</div>
								<input 
									type="number" 
									class="form-control celular" 
									id="celular_peticionario" 
									name="celular_peticionario" 
									autocomplete="no" 
									data-required="true" 
									data-regex="(.)\1{6}" 
									data-label-regex="Debe ingresar un número de celular válido para el peticionario este debe iniciar con 3 y no contener mas de 6 números repetidos"
									data-label="Celular del peticionario"
									maxlength="10"
								>
							</div>
							<span class="error small">Ingrese el teléfono de contacto principal del peticionario</span>
						</div>
						<div class="col-md-3 form-group" data-natural data-juridico>
							<label for="telefono_peticionario">* Teléfono fijo <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono fijo, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<input type="checkbox" data-active="#telefono_peticionario" aria-label="Checkbox for following text input" checked>
									</div>
								</div>
								<input 
									type="number" 
									class="form-control" 
									id="telefono_peticionario" 
									name="telefono_peticionario" 
									autocomplete="no" 
									data-required="true" 
									data-regex="(.)\1{6}" 
									data-label-regex="Debe ingresar un número de teléfono válido para el peticionario este no puede contener mas de 6 números repetidos" 
									data-label="Teléfono fijo del peticionario" 
									maxlength="10"
								>
							</div>
							<span class="error small">Ingrese el teléfono de contacto secundario del peticionario</span>
						</div>
						<div class="col-md-6" data-natural data-juridico>
							<div class="form-row">
								<div class="col-md-6 form-group">
									<label for="correo_peticionario">* Correo electrónico <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su correo electrónico, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<div class="input-group-text">
												<input type="checkbox" data-active="#correo_peticionario,#dominio_peticionario" aria-label="Checkbox for following text input" checked>
											</div>
										</div>
										<input type="text" class="form-control" id="correo_peticionario" name="correo_peticionario" autocomplete="no" data-required="true" data-label="Correo del peticionario">
									</div>
									<span class="error small">Ingrese el correo electrónico del peticionario</span>
								</div>
								<div class="col-md-6 email-component">
									<label for=""><i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el dominio de la lista desplegable si no lo encuentra digítelo."></i>&nbsp;</label>
									<input type="text" class="form-control dominio" id="dominio_peticionario" name="dominio_peticionario" placeholder="dominio" aria-label="dominio" autocomplete="no" data-required="true" data-label="Dominio del correo del peticionario">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
					<h4 class="section-h">Detalle de la petición</h4>
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
					<div class="alert alert-light" role="alert">
						Permítanos conocer más sobre la entidad frente a la cual quiere formular su PQRD
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-6 form-group">
					<label for="">* Tipo entidad <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el tipo de entidad contra la que quiere formular su PQRD"></i></label>
					<select name="tipo_entidad" id="tipo_entidad" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Tipo de entidad contra la cual quiere formular la PQRD">
						<?php foreach ($tipos_entidades as $tipo) { ?>
							<option value="<?=$tipo['ID']?>"><?=$tipo['NOMBRE_TIPO']?></option>
						<?php } ?>
					</select>
					<span class="error small">Seleccione el tipo de entidad sobre la cual quiere formular su PQRD</span>
				</div>
				<div class="col-md-6 form-group">
					<label for="">* Entidad  <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Lista de vigilados de la Superintendencia de salud."></i></label>
					<select name="entidad" id="entidad" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Entidad contra la cual quiere formular la PQRD"></select>
					<div id="entidad_bar" class="progress" style="display:none; height:5px;">
						<div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
					</div>
					<span class="error small">Seleccione la entidad sobre la cual quiere formular su PQRD</span>
				</div>
				<div class="col-md-3 form-group" data-natural data-juridico data-anonimo>
					<label for="departamento_afiliacion">* Departamento de afiliación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el departamento en el que reside"></i></label>
					<select name="departamento_afiliacion" id="departamento_afiliacion" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Departamento de afiliación de la entidad contra la cual quiere formular la PQRD">
						<?php foreach ($departamentos as $departamento) { ?>
							<option value="<?=$departamento['DPTO_CODI']?>"><?=$departamento['DPTO_NOMB']?></option>
						<?php } ?>
					</select>
					<span class="error small">Seleccione el departamento de afiliación a la entidad</span>
				</div>
				<div class="col-md-3 form-group" data-natural data-juridico data-anonimo>
					<label for="ciudad_afiliacion">* Municipio de afiliación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el municipio de afiliación"></i></label>
					<select name="ciudad_afiliacion" id="ciudad_afiliacion" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Municipio de afiliación de la entidad contra la cual quiere formular la PQRD"></select>
					<div id="ciudad_bar_afiliacion" class="progress" style="display:none; height:5px;">
						<div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
					</div>
					<small id="emailHelp" class="form-text text-muted">*ANM Areas no municipalizadas</small>
					<span class="error small">Seleccione el municipio de afiliación a la entidad</span>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-12 form-group">
					<fieldset>
						<label for="hechos_relacionados_centro_de_salud">* ¿Los hechos están relacionados con una clínica, hospital, centro de salud, laboratorio clínico u otro prestador de servicios de salud?  <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione SI, si los hechos esta relacionados con una clínica, hospital, centro de salud, laboratorio clínico u otro prestador de servicios de salud de lo contrario seleccione NO"></i></label><br>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="hechos_relacionados_centro_de_salud" id="hechos_relacionados_centro_de_salud1" value="Si" data-required="true" data-label="Hechos relacionados con una clínica, hospital, centro de salud, laboratorio clínico u otro prestador de servicios de salud">
							<label class="form-check-label" for="hechos_relacionados_centro_de_salud1">Si</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="hechos_relacionados_centro_de_salud" id="hechos_relacionados_centro_de_salud2" value="No" data-required="true">
							<label class="form-check-label" for="hechos_relacionados2">No</label>
						</div>
					</fieldset>
					<span class="error small">Indique si los hechos están relacionados con una clínica, hospital, centro de salud, laboratorio clínico u otro prestador de servicios de salud.</span>
				</div>
				<div class="col-md-3 form-group hechos_relacionados_centro_de_salud" style="display:none;">
					<label for="departamento_centro_de_salud">* Departamento <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el departamento donde está ubicada la clínica, hospital centro de salud"></i></label>
					<select name="departamento_centro_de_salud" id="departamento_centro_de_salud" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Departamento donde está ubicada la clínica, hospital centro de salud">
						<?php foreach ($departamentos as $departamento) { ?>
							<option value="<?=$departamento['DPTO_CODI']?>"><?=$departamento['DPTO_NOMB']?></option>
						<?php } ?>
					</select>
					<span class="error small">Seleccione el departamento donde está ubicada la clínica, hospital centro de salud</span>
				</div>
				<div class="col-md-3 form-group hechos_relacionados_centro_de_salud" style="display:none;">
					<label for="ciudad_centro_de_salud">* Municipio <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el municipio donde está ubicada la clínica, hospital o centro de salud"></i></label>
					<select name="ciudad_centro_de_salud" id="ciudad_centro_de_salud" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Municipio donde está ubicada la clínica, hospital o centro de salud"></select>
					<div id="ciudad_centro_de_salud_bar" class="progress" style="display:none; height:5px;">
						<div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
					</div>
					<small id="emailHelp" class="form-text text-muted">*ANM Areas no municipalizadas</small>
					<span class="error small">Seleccione el municipio donde está ubicada la clínica, hospital o centro de salud</span>
				</div>
				<div class="col-md-6 form-group hechos_relacionados_centro_de_salud" style="display:none;">
					<label for="">* Seleccione el nombre de la clínica, hospital o centro de salud  <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el nombre de la clínica, hospital o centro de salud con la que se relacionan los hechos, en caso de no encontrar el nombre que busca seleccione la opción OTRO"></i></label>
					<select name="id_ips" id="id_ips" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Clínica, hospital o centro de salud con la que se relacionan los hechos"></select>
					<div id="id_ips_bar" class="progress" style="display:none; height:5px;">
						<div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
					</div>
					<span class="error small">Seleccione la clínica, hospital o centro de salud con la que se relacionan los hechos</span>
				</div>
				<div class="col-md-12 form-group otro_centro_de_salud" style="display:none;">
					<label for="otro_ips">* Otro <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el nombre de otra clínica, hospital o centro de salud"></i></label>
					<input type="text" class="form-control" id="otro_ips" name="otro_ips" autocomplete="no" data-required="true" data-label="Otra clínica, hospital o centro de salud" maxlength="200">
					<span class="error small">Digite el nombre de otra clínica, hospital o centro de salud</span>
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
					<div class="alert alert-light" role="alert">
						Describa brevemente la situación que nos quiere dar a conocer.
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-12 form-group limited-textarea">
					<label for="comentarios">* Escriba aquí lo que le está sucediendo: <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Describa brevemente y en forma clara su queja.  Recuerde incluir información importante, por ejemplo: diagnóstico o enfermedad que padece la persona, servicio que tiene pendiente que puede ser citas, medicamentos, procedimientos, prestaciones económicas, etc."></i></label>
					<textarea id="comentarios" name="comentarios" class="form-control" rows="5" autocomplete="no" data-required="true" minlenght="10" maxlength="5000" data-label="Descipción de la PQRD"></textarea>
					<span class="size" data-max="5000">0/5000</span>
					<span class="error small">Ingrese el texto que describe lo que esta sucediendo, máximo 5000 caracteres</span>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 form-group">
					<label for="relacionado_entrega_medicamentos">* ¿Los hechos están relacionados con la entrega de medicamentos? <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione una opción"></i></label><br>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="relacionado_entrega_medicamentos" id="relacionado_entrega_medicamentos1" value="Si" data-required="true" data-label="Indique si los hechos están relacionados con la entrega de medicamentos">
						<label class="form-check-label" for="relacionado_entrega_medicamentos">Si</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="relacionado_entrega_medicamentos" id="relacionado_entrega_medicamentos2" value="No" data-required="true">
						<label class="form-check-label" for="relacionado_entrega_medicamentos2">No</label>
					</div>
					<span class="error small">Seleccione una opción</span>
				</div>
			</div>
			<div class="medicamentos" style="display:none;">
				<div class="row">
					<div class="col-md-12 form-group">
						<label for="buscador_medicamentos">Nombre medicamento</label><br>
						<small>
							Seleccione máximo 5 medicamentos, en caso de requerir más medicamentos por favor escribirlos en el campo donde describió lo que está sucediendo. En caso de no encontrar en la lista el medicamento requerido, seleccione la opción “Otro”.
						</small>
						<select name="selector_medicamentos" id="selector_medicamentos" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-label="Agregar medicamentos">
							<?php foreach ($medicamentos as $medicamento) { ?>
								<option value="<?=$medicamento['ID']?>"><?=$medicamento['MEDICAMENTO']?></option>
							<?php } ?>
						</select>
						<span class="error small">Debe agregar al menos 1 medicamento</span>
					</div>
					<div class="col-md-12">
						<input id="agregar_medicamento" type="button" class="btn btn-round btn-middle" value="Agregar medicamento">
					</div>
				</div>
				<div id="medicamentos" class="row">
				</div>
				<div id="requiere_mas_medicamentos" class="row" style="display:none;">
					<div class="col-md-12 form-group">
						<label for="requiere_mas_medicamento">* ¿Requiere más de 5 medicamentos? <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione una opción"></i></label><br>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="requiere_mas_medicamentos" id="requiere_mas_medicamentos1" value="Si" data-required="true" data-label="Indique si requiere más de 5 medicamentos">
							<label class="form-check-label" for="requiere_mas_medicamentos">Si</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="requiere_mas_medicamentos" id="requiere_mas_medicamentos2" value="No" data-required="true">
							<label class="form-check-label" for="requiere_mas_medicamentos2">No</label>
						</div>
						<span class="error small">Seleccione una opción</span>
					</div>
				</div>
			</div>
			<div id="campos_orden_medica">
				<div class="row">
					<div class="col-md-12 form-group">
						<label for="orden_medica">* ¿Tiene orden médica? <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione una opción"></i></label><br>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="orden_medica" id="orden_medica1" value="1" data-required="true" data-label="Tiene orden médica">
							<label class="form-check-label" for="orden_medica">Si</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="orden_medica" id="orden_medica2" value="0" data-required="true">
							<label class="form-check-label" for="orden_medica2">No</label>
						</div>
						<span class="error small">Seleccione una opción</span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 form-group">
						<label for="reclamo_asegurador">* ¿Ya presentó su reclamo o solicitud ante la EPS o entidad responsable de garantizar los servicios de salud? <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione una opción"></i></label><br>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="reclamo_asegurador" id="reclamo_asegurador1" value="1" data-required="true" data-label="Presentó su reclamo o solicitud ante la EPS o entidad responsable de garantizar los servicios de salud">
							<label class="form-check-label" for="reclamo_asegurador">Si</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="reclamo_asegurador" id="reclamo_asegurador2" value="0" data-required="true">
							<label class="form-check-label" for="sexo2">No</label>
						</div>
						<span class="error small">Seleccione una opción</span>
					</div>
				</div>
			</div>
			<div id="archivos" class="row">
				<div class="col-md-12">
					<label for="">Para la radicación de su PQRD no es necesario adjuntar soportes, pero si lo considera necesario seleccione: <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Si desea adjuntar soportes que considere necesarios para su PQRD, seleccione el tipo de adjunto y cargue el documento aquí. Adjuntar archivo máximo disponible hasta 5 Adjuntos - por cada uno 2 M.B. Formatos válidos: (tif, tiff, jpeg, pdf, docx, txt, jpg, gif, xls, xlsx, doc, png, msg, m4a, mp3, mp4.)"></i></label>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<input id="agregar_archivo" type="button" class="btn btn-round btn-middle" value="Adjuntar soportes">
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
					<hr>
				</div>
			</div>
			<div class="form-row">
				<div class="col-sm form-group">
					* Autorizo el envío de información a través de: &nbsp;
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" name="medio[]" id="medio_1" value="Correo electrónico" data-required="true" checked data-label="Medio para recibir información de su PQRD">
						<label class="form-check-label" for="medio_1">Correo electrónico</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" name="medio[]" id="medio_2" value="Dirección de correspondencia" data-required="true">
						<label class="form-check-label" for="medio_2">Dirección de correspondencia</label>
					</div>
					<span class="error small">Seleccione al menos un medio sobre el cual desea recibir información de su PQRD</span>
				</div>
			</div>
			<div class="row">
				<br>
			</div>
			<div class="form-row">
				<div class="col-md-12 form-group" style="text-align: justify">
					Al hacer clic en el botón enviar, usted acepta la remisión de la PQRD a la entidad Superintendencia Nacional de Salud. Sus datos serán recolectados y tratados conforme con la <a href="https://www.supersalud.gov.co/es-co/transparencia-y-acceso-a-la-informacion-publica/informaci%C3%B3n-de-la-entidad/politicas-de-privacidad-y-condiciones-de-uso" target="_blank">Política de Tratamiento de Datos.</a> En la opción <a href="https://www.supersalud.gov.co/es-co/atencion-ciudadano/seguimiento-de-peticiones-quejas-reclamos-y-denuncias">consulta de PQRD</a> podrá verificar el estado de la respuesta.
					<br><br>
					En caso de que la solicitud de información sea de naturaleza de identidad reservada, deberá efectuar el respectivo trámite ante la Procuraduría General de la Nación, haciendo clic en el siguiente link: <a href="https://sedeelectronica.procuraduria.gov.co/PQRDSF/solicitud-de-informacion-con-identificacion-reservada/?typeform=infores" target="_blank">https://sedeelectronica.procuraduria.gov.co/PQRDSF/solicitud-de-informacion-con-identificacion-reservada/?typeform=infores</a>
					<br><br>
					Términos que aplican en la presentación de quejas anónimas, <a href="http://www.suin-juriscol.gov.co/viewDocument.asp?ruta=Leyes/1671809" target="_blank">Ley 962 de 2005 Artículo 81</a>. "Ninguna denuncia o queja anónima podrá promover acción jurisdiccional, penal, disciplinaria, fiscal, o actuación de la autoridad administrativa competente (excepto cuando se acredite, por lo menos sumariamente la veracidad de los hechos denunciados) o cuando se refiera en concreto a hechos o personas claramente identificables."
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<altcha-widget
						id="altcha-widget-0"
						challengeurl="../altcha_challenge.php"
					></altcha-widget>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-12">
					<br>
					<input type="hidden" id="as" name="as" value="1">
					<input type="hidden" id="tipoSolicitud" name="tipoSolicitud" value="Solicitud"/>
					<input type="hidden" id="tipoUsuario" name="tipoUsuario" value=""/>
					<input type="hidden" id="adjuntosSubidos" name="adjuntosSubidos" value=""/>
					<input type="hidden" name="pais" value="170">
					<input type="button" class="btn btn-round btn-high" id="enviar-pqrd" value="Enviar">
					<input type="button" id="borrar" class="btn btn-round btn-middle" value="Borrar">
					<a href="https://www.supersalud.gov.co/es-co/Paginas/Protecci%C3%B3n%20al%20Usuario/pqrd.aspx" class="btn btn-round btn-middle">Volver</a>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-12">
					<br><br>
				</div>
			</div>
		</form>
	</div>

	<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Información</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-round btn-middle" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

	<script type="text/javascript" src="scripts/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="scripts/popper.min.js"></script>
	<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
	<script type="text/javascript" src="scripts/bootstrap-select.js"></script>
	<script type="text/javascript" src="scripts/bootstrap-autocomplete.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.datetimepicker.full.min.js"></script>
	<script type="text/javascript" src="scripts/moment.min.js"></script>
	<script	type="text/javascript" src="scripts/accesibilidad.js"></script>
	<script src="https://cdn.www.gov.co/v2/assets/js/utils.js"></script>
	<script type="text/javascript">
		$(function()
		{
			var service = "<?= $api_pqrd ?>"
			$('[data-toggle="tooltip"]').tooltip()

			$(".alpha-only").on("keydown", function(event){
				// Allow controls such as backspace, tab etc.
				var arr = [8,9,16,17,20,32,35,36,37,38,39,40,45,46,192];

				// Allow letters
				for(var i = 65; i <= 90; i++){
					arr.push(i);
				}

				// Prevent default if not in array
				if(jQuery.inArray(event.which, arr) === -1){
					event.preventDefault();
				}
			});

			$('body').delegate('input[type="number"]', 'keypress', function(event)
			{
				var maxlength = $(this).attr('maxlength');
				var current_length = (""+$(this).val()).length;

				if ((event.which != 8 && event.which != 9) && isNaN(String.fromCharCode(event.which)) || current_length >= maxlength)
				{
					event.preventDefault(); //stop character from entering input
				}
			});

			$('#borrar').on('click', function(e) {
				$("#form-solicitud")[0].reset();
				$('select').each(function(e) {
					$(this).val('').trigger('change');
					$('#ciudad_bar').hide();
					$('#entidad_bar').hide();
				});
			});

			function validarCheckboxGestante() {
				if($('input[name="edad"]').val() != '' && $('input[name="sexo"]').is(':checked')) {
					if(
						$('input[name="sexo"]:checked').val() == 'Femenino' && 
						($('input[name="edad"]').val() * 1 > 8 && $('input[name="edad"]').val() * 1 < 65)
					) {
						$('input[name="gestante"]').prop('disabled', false);
					} else {
						$('input[name="gestante"]').prop('checked', false);
						$('input[name="gestante"]').prop('disabled', true);
					}	
				} else {
                    $('input[name="gestante"]').prop('checked', false);
                    $('input[name="gestante"]').prop('disabled', true);
                }
			}
			
			var fileCountSize = 0;
			// Limite para la cantidad de archivos que se pueden subir.
			var fileCountLimit = 100;
			// Cantidad de archivos subidos.
			var addedFiles = 0;
			// Limite de subida de los archivos, en total.
			var fileLimit = 10*1024*1024;
			// Arregloq ue contiene los archivos subidos.
			var uploader;

			function isEmail(email) {
				var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				return regex.test(email);
			}

			$.datetimepicker.setLocale('es');

			$('#fecha_nacimiento').datetimepicker({
				timepicker:false,
				format:'Y-m-d',
				validateOnBlur: true,
				allowBlank:false,
				maxDate: 0,
				yearStart: 1900
			});

			$('#fecha_nacimiento').on('change', function(e) {
				var edad = moment().diff($(this).val(), 'years');
				$('input[name="edad"]').val(edad).change();
			});

			$('select:not(.ignore)').selectpicker();

			var tipo_identificacion_peticionario_options = $('select[name="tipo_identificacion_peticionario"]').html();

			$('input[name="tipo"]').on('change', function(e) 
			{
				var options_html = $('<div></div>').html(tipo_identificacion_peticionario_options);
				options_html.find('.bs-title-option').remove();									
				var tipo = $('input[name="tipo"]:checked').val();

				var label_celular_peticionario = '* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i>';
				var label_direccion_peticionario = '* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i>';
				var label_telefono_peticionario = '* Teléfono fijo <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono fijo, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i>';
				console.log(tipo);
				switch(tipo){
					case '1':
						$('div[data-juridico]').hide();
						$('div[data-anonimo]').hide();
						$('div[data-natural]').show();
						$('select[name="pais_peticionario"]').trigger('change');
						$('select[name="tipo_identificacion_peticionario"]').html(options_html.html());
						$('select[name="tipo_identificacion_peticionario"]').selectpicker('refresh');
					break;
					case '2':
						$('div[data-natural]').hide();
						$('div[data-anonimo]').hide();
						$('div[data-juridico]').show();
						options_html.html('<option value="4">NIT (Número de identificación Tributaria)</option>');
						$('select[name="tipo_identificacion_peticionario"]').html(options_html.html());
						$('select[name="tipo_identificacion_peticionario"]').selectpicker('refresh');
						$('select[name="pais_peticionario"]').trigger('change');
					break;
					case '3':
						$('div[data-natural]').hide();
						$('div[data-juridico]').hide();
						$('div[data-anonimo]').show();
					break;
					default:
						$('div[data-juridico]').hide();
						$('div[data-anonimo]').hide();
						$('div[data-natural]').show();
						$('select[name="pais_peticionario"]').trigger('change');
						$('select[name="tipo_identificacion_peticionario"]').html(options_html.html());
						$('select[name="tipo_identificacion_peticionario"]').selectpicker('refresh');
					break;
				}

				$('label[for="direccion_peticionario"]').html(label_direccion_peticionario);
				$('label[for="celular_peticionario"]').html(label_celular_peticionario);
				$('label[for="telefono_peticionario"]').html(label_telefono_peticionario);
				$('[data-toggle="tooltip"]').tooltip();
			});

			$('input[name="afectado"]').on('change', function(e) {
				$('input[name="tipo"]').prop('checked', false).trigger('change');
				$('select[name="tipo_identificacion_peticionario"]').val('').trigger('change');
				$('input[name="id_peticionario"]').val('');
				$('input[name="nombre_peticionario_1"]').val('');
				$('input[name="nombre_peticionario_2"]').val('');
				$('input[name="apellidos_peticionario_1"]').val('');
				$('input[name="apellidos_peticionario_2"]').val('');
				$('input[name="apellidos_peticionario_2"]').val('');
				$('input[name="apellidos_peticionario_2"]').val('');
				$('input[name="rs"]').val('');
				$('select[name="pais_peticionario"]').val('').trigger('change');
				$('select[name="departamento_peticionario"]').val('').trigger('change');
				$('select[name="ciudad_peticionario"]').val('').trigger('change');
				$('input[name="provincia_peticionario"]').val('');
				$('input[name="direccion_peticionario"]').val('');
				$('input[name="direccion_peticionario_2"]').val('');
				$('input[name="celular_peticionario"]').val('');
				$('input[name="telefono_peticionario"]').val('');
				$('input[name="correo_peticionario"]').val('');
				$('input[name="dominio_peticionario"]').val('');
				if($(this).val() == 'No')
					$('#formulario_peticionario').show();
				else
					$('#formulario_peticionario').hide();
			});

			$('input[name="sexo"]').on('change', function(e) {
				validarCheckboxGestante();
			});

			$('select[name^="pais_"]').on('change', function(e) {
				var name = $(this).attr("name");
				var persona = name.split('_');

				if($(this).val() !== 'Colombia' && $(this).val() !== '')
				{
					$('select[name="departamento_'+persona[1]+'"]').closest('.form-group').hide();
					$('select[name="ciudad_'+persona[1]+'"]').closest('.form-group').hide();
					$('input[name="provincia_'+persona[1]+'"]').closest('.form-group').show();
				} else {
					$('select[name="departamento_'+persona[1]+'"]').closest('.form-group').show();
					$('select[name="ciudad_'+persona[1]+'"]').closest('.form-group').show();
					$('input[name="provincia_'+persona[1]+'"]').closest('.form-group').hide();
				}
			});
			
			$('input[data-active]').on('change', function(e) {
				var selector = $(this).data('active');
				if($(this).is(':checked'))
				{
					$(selector).each(function(i, element) {
						$(element).prop('disabled', false);
						$(element).attr('data-required', 'true');
					});
				} else {
					$(selector).each(function(i, element) {
						$(element).prop('disabled', true);
						$(element).closest('.form-group').removeClass('has-error');
						$(element).removeClass('is-invalid');
						$(element).removeAttr('data-required');
					});
				}
			});

			$('#tipo_identificacion_afectado').on('change', function(e) {
				var tipo_identificacion = $(this).val();
				$('#id_afectado').val('');
				var pasaporte = 3;
				var menor_sin_identificacion = 6;
				var adulto_sin_identificacion = 12;
				if($.inArray(parseInt(tipo_identificacion), [pasaporte, menor_sin_identificacion, adulto_sin_identificacion]) != -1)
				{
					$('#id_afectado').attr('type', 'text');
				} else {
					$('#id_afectado').attr('type', 'number');
				}

				if(tipo_identificacion == 12)
				{
					$('#id_afectado').prop('readonly', true);
					$('label[for="id_afectado"]').html('Número de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número del documento de identificación del paciente o afectado. En caso de que sea un menor de edad que no cuente con identificación, digite el número de documento del tutor o de la persona a cargo. Recuerde que si selecciona pasaporte debe ingresar números y letras de lo contrario sólo números."></i>');
					$('#id_afectado').removeAttr('data-required');
				} else {
					$('#id_afectado').prop('readonly', false);
					$('label[for="id_afectado"]').html('* Número de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número del documento de identificación del paciente o afectado. En caso de que sea un menor de edad que no cuente con identificación, digite el número de documento del tutor o de la persona a cargo. Recuerde que si selecciona pasaporte debe ingresar números y letras de lo contrario sólo números."></i>');
					$('#id_afectado').attr('data-required', 'true');
				}

				if(tipo_identificacion == 14)
				{
					$('#id_afectado').attr('maxlength', '17');
				} else {
					$('#id_afectado').attr('maxlength', '15');
				}
			});

			$('#tipo_identificacion_peticionario').on('change', function(e) {
				var tipo_identificacion = $(this).val();
				$('#id_peticionario').val('');
				var pasaporte = 3;
				var menor_sin_identificacion = 6;
				var adulto_sin_identificacion = 12;
				if($.inArray(parseInt(tipo_identificacion), [pasaporte, menor_sin_identificacion, adulto_sin_identificacion]) != -1)
				{
					$('#id_peticionario').attr('type', 'text');
				} else {
					$('#id_peticionario').attr('type', 'number');
				}

				if(tipo_identificacion == 12)
				{
					$('#id_peticionario').prop('readonly', true);
					$('label[for="id_peticionario"]').html('Número de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número del documento de identificación del peticionario. En caso de que sea un menor de edad que no cuente con identificación, digite el número de documento del tutor o de la persona a cargo. Recuerde que si selecciona pasaporte debe ingresar números y letras de lo contrario sólo números."></i>');
					$('#id_peticionario').removeAttr('data-required');
				} else {
					$('#id_peticionario').prop('readonly', false);
					$('label[for="id_peticionario"]').html('* Número de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número del documento de identificación del peticionario. En caso de que sea un menor de edad que no cuente con identificación, digite el número de documento del tutor o de la persona a cargo. Recuerde que si selecciona pasaporte debe ingresar números y letras de lo contrario sólo números."></i>');
					$('#id_peticionario').attr('data-required', 'true');
				}

				if(tipo_identificacion == 14)
				{
					$('#id_peticionario').attr('maxlength', '17');
				} else {
					$('#id_peticionario').attr('maxlength', '15');
				}
			});

			$('#departamento_afectado').on('change', function(e) {				
				$('#ciudad_bar_afectado').show();
				var request = $.post(
					'solicitudes_ajax.php',
					{
						servicio: 'ciudades',
						id_depto: $(this).val()
					},
					'json'
				);

				request.done(function(res) {
					var options = '';

					if(res) {
						$.each(res, function(i, e) {
							options += '<option value="'+e.MUNI_CODI+'">'+e.MUNI_NOMB+'</option>'
						});

						$('#ciudad_afectado').html(options);
						$('#ciudad_afectado').selectpicker('refresh');
						$('#ciudad_bar_afectado').hide();
					}
				});

				return request;
			});

			$('#departamento_peticionario').on('change', function(e) {
				$('#ciudad_bar_peticionario').show();

				var request = $.post(
					'solicitudes_ajax.php',
					{
						servicio: 'ciudades',
						id_depto: $(this).val()
					},
					'json'
				);

				request.done(function(res) {
					var options = '';

					if(res) {
						$.each(res, function(i, e) {
							options += '<option value="'+e.MUNI_CODI+'">'+e.MUNI_NOMB+'</option>'
						});

						$('#ciudad_peticionario').html(options);
						$('#ciudad_peticionario').selectpicker('refresh');
						$('#ciudad_bar_peticionario').hide();
					}
				});

				return request;
			});

			$('#departamento_afiliacion').on('change', function(e) {
				$('#ciudad_bar_afiliacion').show();
				var request = $.post(
					'solicitudes_ajax.php',
					{
						servicio: 'ciudades',
						id_depto: $(this).val()
					},
					'json'
				);

				request.done(function(res) {
					var options = '';

					if(res) {
						$.each(res, function(i, e) {
							options += '<option value="'+e.MUNI_CODI+'">'+e.MUNI_NOMB+'</option>'
						});

						$('#ciudad_afiliacion').html(options);
						$('#ciudad_afiliacion').selectpicker('refresh');
						$('#ciudad_bar_afiliacion').hide();
					}
				});

				return request;
			});

			$('#tipo_entidad').on('change', function(e) {
				$('#entidad_bar').show();
				var request = $.post(
					'solicitudes_ajax.php',
					{
						servicio: 'entidades',
						id_tipo: $(this).val()
					},
					'json'
				);

				request.done(function(res) {
					var options = '';

					if(res) {
						$.each(res, function(i, e) {
							options += '<option value="'+e.ID+'">'+e.NOMBRE_EPS+'</option>'
						});

						$('#entidad').html(options);
						$('#entidad').selectpicker('refresh');
						$('#entidad_bar').hide();
					}
				});

				return request;
			});

			$('input[name="hechos_relacionados_centro_de_salud"]').on('change', function(e) {
				if($(this).val() == 'Si')
					$('.hechos_relacionados_centro_de_salud').show();
				else
					$('.hechos_relacionados_centro_de_salud').hide();
			});

			$('input[name="relacionado_entrega_medicamentos"]').on('change', function(e) {
				if($(this).val() == 'Si')
					$('.medicamentos').show();
				else
					$('.medicamentos').hide();

				ajustarComponentesMedicamentos();
			});

			$('#departamento_centro_de_salud').on('change', function(e) {				
				$('#ciudad_centro_de_salud_bar').show();
				var request = $.post(
					'solicitudes_ajax.php',
					{
						servicio: 'ciudades',
						id_depto: $(this).val()
					},
					'json'
				);

				request.done(function(res) {
					var options = '';

					if(res) {
						$.each(res, function(i, e) {
							options += '<option value="'+e.HOMOLOGA_MUNI+'">'+e.MUNI_NOMB+'</option>'
						});

						$('#ciudad_centro_de_salud').html(options);
						$('#ciudad_centro_de_salud').selectpicker('refresh');
						$('#ciudad_centro_de_salud_bar').hide();
					}
				});

				return request;
			});

			$('#ciudad_centro_de_salud').on('change', function(e) {
				var dane = $(this).val();
				var request = $.post(
					'solicitudes_ajax.php',
					{
						servicio: 'ips',
						dane
					},
					'json'
				);

				request.done(function(res) {
					if(res) {
						var options = '';
						$.each(res, function(i, e) {
							options += '<option value="'+e.ID+'">'+e.NOMBRE_EPS+'</option>'
						});

						$('#id_ips').html(options);
						$('#id_ips').selectpicker('refresh');
						$('#id_ips_bar').hide();
					}
				});
			});

			$('#id_ips').on('change', function(e) {
				var otro = 111111;
				var valor = $(this).val();
				if (valor == otro)
				{
					$('.otro_centro_de_salud').show();
				} else {
					$('.otro_centro_de_salud').hide();
					$('.otro_centro_de_salud input').val('');
				}
			});

			$('input[name="edad"]').on('keyup blur change', function(e) {
				var edad = $(this).val() * 1;
				var rango = '';

				if(edad > 62) {
					rango = 'Mayor de 63 años';
				} else if (edad > 49) {
					rango = 'De 50 a 62 años';
				} else if (edad > 37) {
					rango = 'De 38 a 49 años';
				} else if (edad > 29) {
					rango = 'De 30 a 37 años';
				} else if (edad > 24) {
					rango = 'De 25 a 29 años';
				} else if (edad > 17) {
					rango = 'De 18 a 24 años';
				} else if (edad > 12) {
					rango = 'De 13 a 17 años';
				} else if (edad > 5) {
					rango = 'De 6 a 12 años';
				} else if (edad > -1) {
					rango = 'De 0 a 5 años';
				} else {
					rango = '';
				}

				if($(this).val() == '')
				{
					$('input[name="rango_edad"]').val('');	
				} else {
					validarCheckboxGestante();
					$('input[name="rango_edad"]').val(rango);
				}
			});

			$('select[name="poblacion_especial"]').on('change', function(e) {
				$('#selector_discapacidades').hide();
				
				if($(this).val() == 'Persona con discapacidad') {
					$('#selector_discapacidades').show();
				}
			});

			var altcha_widget_0_state = '';

			document.querySelector('#altcha-widget-0').addEventListener('statechange', (ev) => {
				altcha_widget_0_state = ev.detail.state;
				if (ev.detail.state === 'verified') {
					$('#altcha-widget-0').removeClass('altcha-error');
				}
			});

			$('#enviar-pqrd').on('click', function(e) {
				$('#enviar-pqrd').prop('disabled', true);
				var errors = 0;
				var errors_text = '<p>Por favor ingrese la siguiente información: </p><ul>';

				$('input[type="text"], input[type="number"], input[type="radio"], input[type="checkbox"], input[type="file"], textarea, select').each(function(e) {
					
					var isFile = $(this).is('input[type="file"]');
					var label = $(this).data('label');
					var label_regex = $(this).data('label-regex');
					var regex = $(this).data('regex');

					if($(this).is(':visible') && $(this).attr('data-required') == 'true')
					{
						var valor = '';
						var name = $(this).attr('name');
						if($(this).is('input'))
						{
							switch($(this).attr('type'))
							{
								case 'text':
								case 'number':
								case 'file':
									valor = $(this).val();
								break;

								case 'radio':
								case 'checkbox':
									valor = $('input[name="'+name+'"]:checked').val();
									if(!valor) valor = '';
								break;
							}
						}

						if($(this).is('textarea'))
						{
							valor = $(this).val();
						}

						if($(this).is('select'))
						{
							valor = $(this).val();
						}

						checkRegex = regex != undefined;

						if (checkRegex) {

							if($(this).is('.celular'))
								rule = new RegExp(regex).test(valor.trim()) || valor.trim().charAt(0) != 3 || valor.trim().length != 10;
							else
								rule = new RegExp(regex).test(valor.trim()) || valor.trim().length != 10;
						} else {
							rule = false;
						}

						if(valor.trim() == '' || (checkRegex && rule))
						{
							$(this).closest('.form-group').addClass('has-error');
							if(isFile) {
								$(this).closest('div').removeClass('is-valid');
								$(this).closest('div').addClass('is-invalid');
							} else {
								$(this).removeClass('is-valid');
								$(this).addClass('is-invalid');
							}

							errors ++;
							if(valor.trim() == '')
								errors_text += (label != undefined ? '<li>'+label+'</li>' : '');
							if(regex && rule)
								errors_text += (label_regex != undefined ? '<li>'+label_regex+'</li>' : '');
						} else {
							$(this).closest('.form-group').removeClass('has-error');
							if(isFile) {
								$(this).closest('div').removeClass('is-invalid');
								$(this).closest('div').addClass('is-valid');
							} else {
								$(this).removeClass('is-invalid');
								$(this).addClass('is-valid');
							}
						}

						if($(this).is('select'))
						{
							$(this).selectpicker('refresh');
						}
						
					} else {
						if(isFile) 
							$(this).closest('div').removeClass('is-invalid');
						else 
							$(this).removeClass('is-invalid');
					}
				});
				
				if($('#correo_afectado').attr('data-required') == 'true')
				{
					if($('#correo_afectado').is(':visible') && !isEmail($('#correo_afectado').val()+'@'+$('#dominio_afectado').val()))
					{
						$('#correo_afectado').removeClass('is-valid');
						$('#correo_afectado').addClass('is-invalid');
						$('#dominio_afectado').removeClass('is-valid');
						$('#dominio_afectado').addClass('is-invalid');

						errors ++;
                        errors_text += '<li>El correo del afectado es invalido</li>';
					} else {
						$('#correo_afectado').removeClass('is-invalid');
						$('#correo_afectado').addClass('is-valid');
						$('#dominio_afectado').removeClass('is-invalid');
						$('#dominio_afectado').addClass('is-valid');
					}
				}

				if($('#correo_peticionario').attr('data-required') == 'true')
				{
					if($('#correo_peticionario').is(':visible') && !isEmail($('#correo_peticionario').val()+'@'+$('#dominio_peticionario').val()))
					{
						$('#correo_peticionario').removeClass('is-valid');
						$('#correo_peticionario').addClass('is-invalid');
						$('#dominio_peticionario').removeClass('is-valid');
						$('#dominio_peticionario').addClass('is-invalid');

						errors ++;
                        errors_text += '<li>El correo del peticionario es invalido</li>';
					} else {
						$('#correo_peticionario').removeClass('is-invalid');
						$('#correo_peticionario').addClass('is-valid');
						$('#dominio_peticionario').removeClass('is-invalid');
						$('#dominio_peticionario').addClass('is-valid');
					}
				}

				var descripcion = $('#comentarios').val()+'';
				if (descripcion.length < 10)
				{
					errors ++;
					errors_text += '<li>Descripción debe ser de 10 o mas caracteres.</li>';
					$('#comentarios').removeClass('is-valid');
					$('#comentarios').addClass('is-invalid');
				} else {
					$('#comentarios').removeClass('is-invalid');
					$('#comentarios').addClass('is-valid');
				}

				var tipo_id = $('#tipo_identificacion_afectado').val();
				var edad = $('#edad').val();
				var error_edad = false;
				
				if (tipo_id == 6 && edad > 17) {
					errors ++;
					errors_text += '<li>Menor sin identificación es valido solo para menores de 18 años</li>';
					error_edad = true;
				}
				
				if (tipo_id == 12 && edad < 18) {
					errors ++;
					errors_text += '<li>Adulto sin identificación es valido solo para mayores de 17 años</li>';
					error_edad = true;
				}

				if (tipo_id == 0 && edad < 18) {
					errors ++;
					errors_text += '<li>Cedula de ciudadanía es valida solo para mayores de 17 años</li>';
					error_edad = true;
				}

				if (tipo_id == 1 && edad < 7) {
					errors ++;
					errors_text += '<li>Tarjeta de identidad es valida solo para mayores de 7 años</li>';
					error_edad = true;
				}

				if (tipo_id == 9 && edad > 4) {
					errors ++;
					errors_text += '<li>Certificado de nacido vivo es valido solo para menores de 5 años</li>';
					error_edad = true;
				}

				if(error_edad) {
					$('#tipo_identificacion_afectado').closest('div').removeClass('is-valid');
					$('#tipo_identificacion_afectado').closest('div').addClass('is-invalid');
				} else {
					$('#tipo_identificacion_afectado').removeClass('is-valid');
					$('#tipo_identificacion_afectado').addClass('is-invalid');
				}

				if (
					$('input[name="relacionado_entrega_medicamentos"]:checked').val() == 'Si' &&
					$('div[data-type="medicamento"]').length == 0
				) {
					$('#selector_medicamentos').closest('.form-group').addClass('has-error');
					$('#selector_medicamentos').closest('div').removeClass('is-valid');
					$('#selector_medicamentos').closest('div').addClass('is-invalid');
					errors ++;
					errors_text += '<li>Debe agregar al menos 1 medicamento</li>';
				} else {
					if ($('.medicamentos').is(':visible'))
					{
						$('#selector_medicamentos').closest('.form-group').removeClass('has-error');
						$('#selector_medicamentos').closest('div').removeClass('is-invalid');
						$('#selector_medicamentos').closest('div').addClass('is-valid');
					}
				}

				if (altcha_widget_0_state != 'verified')
				{
					$('#altcha-widget-0').addClass('altcha-error');
					errors_text += '<li>Por favor verifique el captcha</li>';
					errors ++;
				} else {
					$('#altcha-widget-0').removeClass('altcha-error');
				}

				errors_text += '</ul>';

				if(errors > 0 || altcha_widget_0_state != 'verified')
				{
					$('#modal .modal-body').html(errors_text);
					$('#modal').modal('show');
					$('#enviar-pqrd').prop('disabled', false);
				} else {
					console.log('ajax revalidar');
					$.ajax({
						type: "POST",
						url: '<?=$api?>valid_formulario_web',
						data: $('#form-solicitud').serialize(), // serializes the form's elements.
						success: function(data)
						{
							if (data.status == 'ok')
							{
								$('#form-solicitud').submit();
								$('.loader').show();
							} else if (data.status == 'error') {
								$.each(data.errors, function(key, value) {
									console.log(key, value)
									$.each(value, function(i, error) {
										console.log(error);
										errors_text += '<li>'+error+'</li>';
									});
								});
								errors_text += '</ul>';
								$('#modal .modal-body').html(errors_text);
								$('#modal').modal('show');
							}
							$('#enviar-pqrd').prop('disabled', false);
						},
						error: function() {
							$('#enviar-pqrd').prop('disabled', false);
						}
					});
				}

				e.preventDefault();
			});

			var dominios = [
				{ value:'gmail.com', text: 'gmail.com'},
				{ value:'googlemail.com', text: 'googlemail.com'},
				{ value:'hotmail.com', text: 'hotmail.com'},
				{ value:'hotmail.es', text: 'hotmail.es'},
				{ value:'live.com', text: 'live.com'},
				{ value:'mac.com', text: 'mac.com'},
				{ value:'facebook.com', text: 'facebook.com'},
				{ value:'outlook.com', text: 'outlook.com'},
				{ value:'yahoo.com', text: 'yahoo.com'},
				{ value:'comcast.net', text: 'comcast.net'},
				{ value:'aol.com', text: 'aol.com'},
				{ value:'sky.com', text: 'sky.com'},
				{ value:'bellsouth.net', text: 'bellsouth.net'},
				{ value:'yahoo.es', text: 'yahoo.es'},
				{ value:'verizon.net', text: 'verizon.net'},
				{ value:'mail.com', text: 'mail.com'},
				{ value:'me.com', text: 'me.com'},
				{ value:'msn.com', text: 'msn.com'}
			];

			$('.dominio').autoComplete({
				minLength: 0,
				noResultsText: '',
				resolver: 'custom',
				events: {
					search: function (qry, callback) {	
						if(qry=='') {
							callback(dominios);
						} else {
							callback(dominios.filter(function(dominio) {
								return dominio.value.indexOf(qry) != -1;
							}))
						}
					}
				}
			});

			$('.dominio').on('focus', function(e) {
				$(this).trigger('keyup');
			});

			$('#comentarios').on('keyup', function(e) {
				var comentarios = $('#comentarios').val();
				$('.size').text(comentarios.length+'/5000');
			});

			$('#comentarios').bind('copy paste cut',function(e) { 
				e.preventDefault(); //disable cut,copy,paste
				//alert('cut,copy & paste options are disabled !!');
			});

			function ajustarComponentesArchivos() {
				$('div[data-type="file"]').each(function(i, e) 
				{
					$(this).attr('data-rel', i);
					$(this).find('select').attr('name', 'tipo_documento['+i+']');
					$(this).find('input[type="file"]').attr('name', 'userfile['+i+']');
					$(this).find('select').selectpicker('refresh');
				});

				$('input[name="as"]').val($('div[data-type="file"]').length);
			}

			var file_template = `
				<div class="col-md-12" data-type="file_template" data-rel="" style="margin-top:10px; display:none;">
					<div class="form-row">
						<div class="col-md-3 form-group">
							<select class="ignore" name="tipo_documento[]" data-live-search="true" data-size="5" title="Seleccionar" data-required="true" data-label="Tipo de archivo">
								<option value="Orden médica">Orden médica</option>
								<option value="Historia clínica">Historia clínica</option>
								<option value="Fórmula médica">Fórmula médica</option>
								<option value="Incapacidad">Incapacidad</option>
								<option value="Orden judicial (fallo, tutela, otro)">Orden judicial (fallo, tutela, otro)</option>
								<option value="Otros">Otros</option>
							</select>
							<span class="error small">Seleccione un tipo de archivo</span>
						</div>
						<div class="col-md-8 form-group" style="padding-top:3px">
							<input name="userfile[]" class="file" type="file" data-required="true" data-label="Archivo adjunto" accept="image/tiff, image/jpeg, application/pdf, application/msword, text/plain, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png, audio/mpeg, video/mp4">
							<span class="error small">Seleccione el archivo que desea cargar</span>
						</div>
						<div class="col-md-1" style="padding-top:3px; text-align: right">
							<button type="button" data-type="remover_archivo" class="btn btn-sm btn-round btn-middle btn-ic"><i class="fa fa-trash"></i></button>
						</div>
						<div class="col-md-12 errors">

						</div>
					</div>
				</div>
			`;

			$('#agregar_archivo').on('click', function(e) {

				if($('div[data-type="file"]').length < 5 )
				{
					var div = $(file_template);
					div.css('display', 'block');
					div.attr('data-type', 'file');
					div.find('select').removeClass('ignore');
					$('#archivos').append(div);
					ajustarComponentesArchivos();
				}
			});

			$('body').delegate('button[data-type="remover_archivo"]', 'click', function() {
				$(this).closest('div[data-type="file"]').remove();
				ajustarComponentesArchivos();
			});

			$('body').delegate('input[type="file"]', 'change', function(e) {
				var mimes = $(this).attr('accept');
				var size = this.files[0].size / 1000;
				if(size > 2048)
				{
					$(this).val(null);
					alert('Solo se permite archivos de máximo 2 MB');
				}
				
				mimes = mimes.replace(/\s/g, '');
				if($.inArray(this.files[0].type, mimes.split(',')) == -1)
				{
					$(this).val(null);
					alert('El archivo seleccionado posee una extensión invalida recuerde que solo se permite la carga de archivos en formato: tif, tiff, jpeg, pdf, docx, txt, jpg, gif, xls, xlsx, doc, png, msg, m4a, mp3, mp4');
				}
			});

			function ajustarComponentesMedicamentos() {
				if ($('div[data-type="medicamento"]').length == 5)
					$('#requiere_mas_medicamentos').show();
				else 
					$('#requiere_mas_medicamentos').hide();

				$('div[data-type="medicamento"]').each(function(i, e) 
				{
					$(this).attr('data-rel', i);
					$(this).find('input[type="medicamento"]').attr('hidden', 'medicamento['+i+']');
				});
			}

			var medicamento_template = `
				<div class="col-md-12" data-type="medicamento_template" data-rel="" style="display:none;">
					<div class="form-row">
						<div class="col-md-11 form-group" style="padding-top:3px">
							<input name="medicamento[]" type="hidden" data-required="true">
							<p class="form-control-static"></p>
						</div>
						<div class="col-md-1" style="padding-top:3px; text-align: right">
							<button type="button" data-type="remover_medicamento" class="btn btn-sm btn-round btn-middle btn-ic"><i class="fa fa-trash"></i></button>
						</div>
					</div>
				</div>
			`;

			$('#agregar_medicamento').on('click', function(e) {
				var option = $('#selector_medicamentos option:selected');
				var exists = false;

				$('input[name="medicamento[]"]').each(function() {
					if ($(this).val() == option.val()) {
						exists = true;
						return false;
					}
				});

				if (exists) {
					alert('El medicamento ya ha sido agregado.');
					return;
				}

				if(
					option.val() != '' && 
					exists == false &&
					$('div[data-type="medicamento"]').length < 5
				) {
					var div = $(medicamento_template);
					div.css('display', 'block');
					div.attr('data-type', 'medicamento');
					div.find('p').text(option.text());
					div.find('input').val(option.val());
					$('#medicamentos').append(div);
					ajustarComponentesMedicamentos();
					$('#selector_medicamentos').val('').change();
				}
			});


			$('body').delegate('button[data-type="remover_medicamento"]', 'click', function() {
				$(this).closest('div[data-type="medicamento"]').remove();
				ajustarComponentesMedicamentos();
			});

			//precarga ADRES
			$('#id_afectado').on('blur', function(e) {
				if($('#id_afectado').val() != '' && $('#tipo_identificacion_afectado').val() != '')
				{
					$('.loader').show();
					
					
					var request = $.get(service+'adres/'+$('#tipo_identificacion_afectado').val()+'/'+$('#id_afectado').val());
					request.done(function(data) {
						$('input[name="nombre_afectado_1"]').val(data.nombre);
						$('input[name="nombre_afectado_2"]').val(data.s_nombre);
						$('input[name="apellidos_afectado_1"]').val(data.apellido);
						$('input[name="apellidos_afectado_2"]').val(data.s_apellido);
						$('select[name="pais_afectado"]').val('Colombia').trigger('change');
						var res_afect = $('select[name="departamento_afectado"]').val(data.departamento_id).triggerHandler('change');
						$('input[name="fecha_nacimiento"]').val(""+(data.fecha_nacimiento ? data.fecha_nacimiento : '').substr(0, 10)).trigger('change');

						var res_eps_tipo = $('select[name="tipo_entidad"]').val(data.eps_tipo).triggerHandler('change');
						res_eps_tipo.done(function(d) {
							$('select[name="entidad"]').val(data.eps_id).trigger('change');
						});

						res_afect.done(function(d) {
							$('select[name="ciudad_afectado"]').val(data.municipio_id).trigger('change');
						});

						var res_afiliacion = $('select[name="departamento_afiliacion"]').val(data.departamento_id).triggerHandler('change');

						res_afiliacion.done(function(d) {
							$('select[name="ciudad_afiliacion"]').val(data.municipio_id).trigger('change');
						});

						if(data.sexo)
						{
							$('input[name="sexo"][value="'+(data.sexo == 1 ? 'Masculino' : 'Femenino')+'"]').trigger('click');
						}
						
						$('.loader').hide();
					});

					request.fail(function(err) {
						$('.loader').hide();
					});

				}
			});

			$('#id_peticionario').on('blur', function(e) {
				if($('#id_peticionario').val() != '' && $('#tipo_identificacion_peticionario').val() != '' && $('input[name="tipo"]:checked').val() == '1')
				{
					console.log('peticionario de ADRES');
					$('.loader').show();
					
					var request = $.get(service+'adres/'+$('#tipo_identificacion_peticionario').val()+'/'+$('#id_peticionario').val());
					request.done(function(data) {
						$('input[name="nombre_peticionario_1"]').val(data.nombre);
						$('input[name="nombre_peticionario_2"]').val(data.s_nombre);
						$('input[name="apellidos_peticionario_1"]').val(data.apellido);
						$('input[name="apellidos_peticionario_2"]').val(data.s_apellido);
						$('select[name="pais_peticionario"]').val('Colombia').trigger('change');
						var res_peticionario = $('select[name="departamento_peticionario"]').val(data.departamento_id).triggerHandler('change');
						res_peticionario.done(function(d) {
							$('select[name="ciudad_peticionario"]').val(data.municipio_id).trigger('change');
						});
						
						$('.loader').hide();
					});

					request.fail(function(err) {
						$('.loader').hide();
					});

				}
			});
		});
	</script>
<?php include ('footer.php') ?>
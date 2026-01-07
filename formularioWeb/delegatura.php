<?php
	session_start();
	define('ADODB_ASSOC_CASE', 1);
	$ruta_raiz = "..";
	$ADODB_COUNTRECS = false;
	
	include_once("$ruta_raiz/processConfig.php");
	include_once("$ruta_raiz/include/db/ConnectionHandler.php");
    include_once("$ruta_raiz/formularioWeb/solicitudes_sql.php");
	
	$pregunta = '3';
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
		'Negro',
		'Palanquero (De San Basilio)',
		'Raizal (Del Archipiélago de San Andrés y Providencia)',
		'ROM- Gitano',
		'No Aplica',
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
					Este formulario es para radicar solicitudes cuando se requiere un trámite o servicio de alguna de las Delegaturas u Oficinas de la Superintendencia Nacional de Salud
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
		<form action="delegatura_service.php" id="form-solicitud" enctype="multipart/form-data" method="post">
			<div class="row"  id="formulario_peticionario">
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
								<input class="form-check-input" type="radio" name="tipo" id="tipo1" value="1" data-required="true" data-label="Tipo peticionario">
								<label class="form-check-label" for="tipo1">Natural</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="tipo" id="tipo2" value="2" data-required="true">
								<label class="form-check-label" for="tipo2">Jurídica</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="tipo" id="tipo4" value="4" data-required="true">
								<label class="form-check-label" for="tipo4">Niños, niñas y adolescentes</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="tipo" id="tipo3" value="3" data-required="true">
								<label class="form-check-label" for="tipo3">Anónimo</label>
							</div>
							<span class="error small">Seleccione el tipo de persona del peticionario</span>
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
							<input type="number" class="form-control" id="id_peticionario" name="id_peticionario" autocomplete="no" data-required="true" data-label="Número de identificación del peticionario" maxlength="15">
							<span class="error small">Ingrese el número de identificación del peticionario</span>
						</div>
					</div>
					<div class="row" data-natural data-juridico>
						<div class="col-sm">
							<hr>
						</div>
					</div>
					<div class="form-row" data-natural>
						<div class="col-md-3 form-group">
							<label for="nombre_peticionario_1">* Primer nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer nombre"></i></label>
							<input type="text" class="form-control alpha-only" id="nombre_peticionario_1" name="nombre_peticionario_1" autocomplete="no" data-required="true"  data-label="Primer nombre del peticionario">
							<span class="error small">Ingrese el primer nombre del peticionario</span>
						</div>
						<div class="col-md-3 form-group">
							<label for="nombre_peticionario_2">Segundo nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo nombre"></i></label>
							<input type="text" class="form-control alpha-only" id="nombre_peticionario_2" name="nombre_peticionario_2" autocomplete="no">
						</div>
						<div class="col-md-3 form-group">
							<label for="apellidos_peticionario_1">* Primer apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer apellido"  data-label="Primer apellido del peticionario"></i></label>
							<input type="text" class="form-control alpha-only" id="apellidos_peticionario_1" name="apellidos_peticionario_1" autocomplete="no" data-required="true">
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
							<label for="pais_peticionario">* País <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el país en el que reside  "></i></label>
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
							<input type="text" class="form-control" id="provincia_peticionario" name="provincia_peticionario" autocomplete="no" data-required="true" data-label="Ciudad, estado y/o provincia de residencia del peticionario">
							<span class="error small">Ingrese la ciudad, estado y/o provincia en la que reside el peticionario</span>
						</div>
						<div class="col-md-3 form-group" data-natural data-juridico>
							<label for="direccion_peticionario">* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i></label>
							<input type="text" class="form-control" id="direccion_peticionario" name="direccion_peticionario" autocomplete="no" data-required="true" data-label="Dirección de residencia del peticionario">
							<span class="error small">Ingrese la dirección del peticionario</span>
						</div>
						<div class="col-md-3 form-group" data-juridico style="display:none;">
							<label for="direccion_peticionario_2">* Dirección comercial <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección comercial"></i></label>
							<input type="text" class="form-control" id="direccion_peticionario_2" name="direccion_peticionario_2" autocomplete="no" data-required="true" data-label="Segunda dirección del peticionario">
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
								<span class="error small">Ingrese el celular del peticionario</span>
							</div>
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
								<span class="error small">Ingrese el teléfono fijo del peticionario</span>
							</div>
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
										<span class="error small">Ingrese el correo electrónico del peticionario</span>
									</div>
								</div>
								<div class="col-md-6 email-component">
									<label for=""><i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el dominio de la lista desplegable si no lo encuentra digítelo"></i>&nbsp;</label>
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
						Describa brevemente la solicitud de información
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-12 form-group limited-textarea">
					<label for="comentarios">* Escriba aquí la solicitud de información: <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la inconformidad o insatisfacción frente a la prestación del servicio de salud"></i></label>
					<textarea id="comentarios" name="comentarios" class="form-control" rows="5" autocomplete="no" data-required="true" data-label="Resumen de la solicitud" minlenght="10" maxlength="5000"></textarea>
					<span class="size" data-max="5000">0/5000</span>
					<span class="error small">Ingrese el texto que describe lo que esta sucediendo, máximo 5000 caracteres</span>
				</div>
			</div>
			<div id="archivos" class="row">
				<div class="col-md-12">
				</div>
			</div>
			<div class="row">
				<div class="col-md-12"><br></div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<input id="agregar_archivo" type="button" class="btn btn-round btn-middle" value="Adjuntar soportes">
					<label for=""><i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Adjunte los soportes que considere pueden servir para su solicitud de información, si estos son muy pesados los puede comprimir. Adjuntar archivo máximo disponible hasta 5 Adjuntos - por cada uno 2 M.B. Formatos válidos: (tif, tiff, jpeg, pdf, docx, txt, jpg, gif, xls, xlsx, doc, png, msg, Zip, m4a, mp3, mp4.)"></i></label>
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
					<hr>
				</div>
			</div>
			<div class="form-row">
				<div class="col-sm form-group" data-natural data-juridico>
					Autorizo el envío de información a través de: &nbsp;
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" name="medio[]" id="medio_1" value="Correo electrónico" data-required="true" data-label="Medio para recibir información de su solicitud" checked>
						<label class="form-check-label" for="medio_1">Correo electrónico</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" name="medio[]" id="medio_2" value="Dirección de correspondencia" data-required="true">
						<label class="form-check-label" for="medio_2">Dirección de correspondencia</label>
					</div>
					<span class="error small">Seleccione al menos un medio sobre el cual desea recibir información de su solicitud</span>
				</div>
			</div>
			<div class="row" data-natural data-juridico>
				<br>
			</div>
			<div class="form-row">
				<div class="col-md-12 form-group" style="text-align: justify">
					Al hacer clic en el botón enviar, usted acepta la remisión de la PQRD a la entidad Superintendencia Nacional de Salud. Sus datos serán recolectados y tratados conforme con la <a href="https://www.supersalud.gov.co/es-co/transparencia-y-acceso-a-la-informacion-publica/informaci%C3%B3n-de-la-entidad/politicas-de-privacidad-y-condiciones-de-uso" target="_blank">Política de Tratamiento de Datos.</a>
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
					<input type="hidden" id="tipoSolicitud" name="tipoSolicitud" value="Trámite y/o servicio ante la Supersalud"/>
					<input type="hidden" id="tipoUsuario" name="tipoUsuario" value=""/>
					<input type="hidden" id="adjuntosSubidos" name="adjuntosSubidos" value=""/>
					<input type="hidden" name="pais" value="170">
					<input type="button" class="btn btn-round btn-high" id="enviar-delegatura" value="Enviar">
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
	<script type="text/javascript" src="scripts/bootstrap-select.min.js"></script>
	<script type="text/javascript" src="scripts/bootstrap-autocomplete.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.datetimepicker.full.min.js"></script>
	<script type="text/javascript" src="scripts/moment.min.js"></script>
	<script	type="text/javascript" src="scripts/accesibilidad.js"></script>
	<script src="https://cdn.www.gov.co/v2/assets/js/utils.js"></script>
	<script type="text/javascript">
		$(function()
		{
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
					console.log($('input[name="sexo"]:checked').val(), $('input[name="edad"]').val());
					if($('input[name="sexo"]:checked').val() == 'Femenino' && $('input[name="edad"]').val() * 1 > 10) {
						$('input[name="gestante"]').prop('disabled', false);
					} else {
						$('input[name="gestante"]').prop('checked', false);
						$('input[name="gestante"]').prop('disabled', true);
					}	
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
			var fileNamesTmpDir  = new Array();
			var uploader;

			function isEmail(email) {
				var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				return regex.test(email);
			}

			$('select').selectpicker();

			
			var tipo_identificacion_peticionario_options = $('select[name="tipo_identificacion_peticionario"]').html();
			

			$('input[name="tipo"]').on('change', function(e) 
			{
				var options_html = $('<div></div>').html(tipo_identificacion_peticionario_options);
				options_html.find('.bs-title-option').remove();
				var tipo = $(this).val();

				var label_celular_peticionario = '* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i>';
				var label_direccion_peticionario = '* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i>';
				var label_telefono_peticionario = '* Teléfono fijo <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono fijo, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i>';
				switch(tipo){
					case '4':
						$('div[data-juridico]').hide();
						$('div[data-anonimo]').hide();
						$('div[data-natural]').show();
						$('select[name="pais_peticionario"]').trigger('change');
						options_html.html(` <option value="6">Menor sin identificación</option>
											<option value="5">Nuip</option>
											<option value="8">Registro civil</option>
											<option value="1">Tarjeta de Identidad</option>
											<option value="3">Pasaporte</option>
											<option value="7">Permiso especial de permanencia</option>`);
						$('select[name="tipo_identificacion_peticionario"]').html(options_html.html());
						$('select[name="tipo_identificacion_peticionario"]').selectpicker('refresh');
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
						$('select[name="pais_peticionario"]').trigger('change');
						options_html.html('<option value="4">NIT (Número de identificación Tributaria)</option>');
						$('select[name="tipo_identificacion_peticionario"]').html(options_html.html());
						$('select[name="tipo_identificacion_peticionario"]').selectpicker('refresh');
					break;
					case '3':
						$('div[data-natural]').hide();
						$('div[data-juridico]').hide();
						$('div[data-anonimo]').show();
					break;
				}

				$('label[for="direccion_peticionario"]').html(label_direccion_peticionario);
				$('label[for="celular_peticionario"]').html(label_celular_peticionario);
				$('label[for="telefono_peticionario"]').html(label_telefono_peticionario);
				$('[data-toggle="tooltip"]').tooltip();
			});

			$('input[name="afectado"]').on('change', function(e) {
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
				})
			});

			var altcha_widget_0_state = '';

			document.querySelector('#altcha-widget-0').addEventListener('statechange', (ev) => {
				altcha_widget_0_state = ev.detail.state;
				if (ev.detail.state === 'verified') {
					$('#altcha-widget-0').removeClass('altcha-error');
				}
			});

			$('#enviar-delegatura').on('click', function(e) {
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
				} else {
					console.log('ajax revalidar');
					$.ajax({
						type: "POST",
						url: '<?=$api?>valid_formulario_delegatura',
						data: $('#form-solicitud').serialize(), // serializes the form's elements.
						success: function(data)
						{
							if (data.status == 'ok')
							{
								$('#form-solicitud').submit()
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

			function ajustarComponentes() {
				$('div[data-type="file"]').each(function(i, e) 
				{
					$(this).attr('data-rel', i);
					$(this).find('input[type="hidden"]').attr('name', 'tipo_documento['+i+']');
					$(this).find('input[type="hidden"]').val('Anexo '+i);
					$(this).find('input[type="file"]').attr('name', 'userfile['+i+']');
					$(this).find('select').selectpicker('refresh');
				});

				$('input[name="as"]').val($('div[data-type="file"]').length);
			}

			var file_template = `
				<div class="col-md-12" data-type="file_template" data-rel="" style="margin-top:10px; display:none;">
					<div class="form-row">
						<div class="col-md-11 form-group" style="padding-top:3px">
							<input name="userfile[]" class="file" type="file" data-required="true" data-label="Archivo adjunto" accept="image/tiff, image/jpeg, application/pdf, application/msword, text/plain, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png, audio/mpeg, video/mp4">
							<span class="error small">Seleccione el archivo que desea cargar</span>
							<input type="hidden" name="tipo_documento[]" />
						</div>
						<div class="col-md-1" style="padding-top:3px; text-align: right">
							<button type="button" data-type="remover" class="btn btn-sm btn-round btn-middle btn-ic"><i class="fa fa-trash"></i></button>
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
					ajustarComponentes();
				}
			});

			$('body').delegate('button[data-type="remover"]', 'click', function() {
				$(this).closest('div[data-type="file"]').remove();
				ajustarComponentes();
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
		});
	</script>
<?php include ('footer.php') ?>
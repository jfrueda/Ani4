<?php
	session_start();
	define('ADODB_ASSOC_CASE', 1);
	$ruta_raiz = "..";
	$ADODB_COUNTRECS = false;
	
	include_once("$ruta_raiz/processConfig.php");
	include_once("$ruta_raiz/include/db/ConnectionHandler.php");
	include_once("./solicitudes_sql.php");
	
	$pregunta = '2';
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
	$tipos_documentos = tipos_documentos($db);
?>
<?php include ('header.php') ?>
	<div class="loader">
		<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
	</div>
	<div class="container">
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
					Este formulario está destinado a poner en conocimiento de la Oficina de Control Disciplinario Interno los presuntos hechos a través de los cuales un funcionario de la Superintendencia Nacional de Salud, en el ejercicio de sus funciones, haya solicitado o recibido beneficios, regalos, donaciones, dinero u otro tipo de prebendas para realizar u omitir las labores a su cargo. Estas conductas pueden constituir actos de corrupción y serán objeto de análisis y trámite por parte de la entidad competente.
				</p>
				<p class="lead" style="text-align: justify;">
					Asimismo, este formulario puede ser utilizado para presentar quejas contra funcionarios de la Supersalud, entendidas como manifestaciones de descontento o inconformidad formuladas por una persona en relación con una conducta que considera irregular por parte de uno o varios servidores públicos en el ejercicio de sus funciones. Dichas conductas podrían constituir faltas disciplinarias, conforme a lo señalado en el Código General Disciplinario.
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
		<form action="solicitudes_denuncias.php" id="form-solicitud" enctype="multipart/form-data" method="post" autocomplete="nope">
			<div class="row">
				<div class="col-sm">
					<h4 class="section-h">Información personal del remitente</h4>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-6 form-group">
					<label for="tipo">* Tipo remitente:</label><br>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="tipo" id="tipo1" value="1" data-required="true" data-label="Tipo remitente">
						<label class="form-check-label" for="tipo1">Natural</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="tipo" id="tipo2" value="2" data-required="true">
						<label class="form-check-label" for="tipo2">Jurídica</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="tipo" id="tipo3" value="3" data-required="true">
						<label class="form-check-label" for="tipo3">Anónimo</label>
					</div>
					<span class="error small">Seleccione el tipo de persona del remitente</span>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-3 form-group" data-natural data-juridico>
					<label for="id">* Tipo de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el tipo de identificación"></i></label>
					<select name="tipo_identificacion" id="tipo_identificacion" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Tipo de identificación del remitente" >
						<?php foreach ($tipos_documentos as $tipo) { ?>
							<option value="<?=$tipo['TDID_CODI']?>"><?=$tipo['TDID_DESC']?></option>
						<?php } ?>
					</select>
					<span class="error small">Seleccione el tipo de identificación del remitente</span>
				</div>
				<div class="col-md-3 form-group" data-natural data-juridico>
					<label for="id">* Número de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número de identificación"></i></label>
					<input type="text" class="form-control" id="id" name="id" autocomplete="no" data-required="true" data-label="Número de identificación del remitente">
					<span class="error small">Ingrese el número de identificación del remitente</span>
				</div>
			</div>
			<div class="row" data-natural data-juridico>
				<div class="col-sm">
					<hr>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-3 form-group" data-natural>
					<label for="nombre_afectado_1">* Primer nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer nombre"></i></label>
					<input type="text" class="form-control alpha-only" id="nombre_afectado_1" name="nombre_afectado_1" autocomplete="no" data-required="true" data-label="Primer nombre del remitente">
					<span class="error small">Ingrese el primer nombre del remitente</span>
				</div>
				<div class="col-md-3 form-group" data-natural>
					<label for="nombre_afectado_2">Segundo nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo nombre"></i></label>
					<input type="text" class="form-control alpha-only" id="nombre_afectado_2" name="nombre_afectado_2" autocomplete="no">
				</div>
				<div class="col-md-3 form-group" data-natural>
					<label for="apellidos_afectado_1">* Primer apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer apellido"></i></label>
					<input type="text" class="form-control alpha-only" id="apellidos_afectado_1" name="apellidos_afectado_1" autocomplete="no" data-required="true" data-label="Primer apellido del remitente">
					<span class="error small">Ingrese el primer apellido del remitente</span>
				</div>
				<div class="col-md-3 form-group" data-natural>
					<label for="apellidos_afectado_2">Segundo apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo apellido"></i></label>
					<input type="text" class="form-control alpha-only" id="apellidos_afectado_2" name="apellidos_afectado_2" autocomplete="no">
				</div>
				<div class="col-md-12 form-group" style="display: none;" data-juridico>
					<label for="rs">* Razón social <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la razón social"></i></label>
					<input type="text" class="form-control alpha-only" id="rs" name="rs" autocomplete="no" data-required="true" data-label="Razón social del remitente">
					<span class="error small">Ingrese la razón social del remitente</span>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-3 form-group" data-natural data-juridico>
					<label for="pais_afectado">* País <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el país en el que reside"></i></label>
					<select name="pais_afectado" id="pais_afectado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="País de residencia del remitente">
						<?php foreach ($paises as $pais) { ?>
							<option value="<?=$pais['NOMBRE']?>"><?=$pais['NOMBRE']?></option>
						<?php } ?>
					</select>
					<span class="error small">Seleccione el país de residencia del remitente</span>
				</div> 
				<div class="col-md-3 form-group" data-natural data-juridico>
					<label for="departamento_afectado">* Departamento <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el departamento en el que reside"></i></label>
					<select name="departamento_afectado" id="departamento_afectado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Departamento de residencia del remitente">
						<?php foreach ($departamentos as $departamento) { ?>
							<option value="<?=$departamento['DPTO_CODI']?>"><?=$departamento['DPTO_NOMB']?></option>
						<?php } ?>
					</select>
					<span class="error small">Seleccione el departamento de residencia del remitente</span>
				</div>
				<div class="col-md-3 form-group" data-natural data-juridico>
					<label for="ciudad_afectado">* Municipio <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el municipio en el que reside"></i></label>
					<select name="ciudad_afectado" id="ciudad_afectado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Municipio de residencia del remitente"></select>
					<div id="ciudad_bar" class="progress" style="display:none; height:5px;">
						<div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
					</div>
					<small id="emailHelp" class="form-text text-muted">*ANM Areas no municipalizadas</small>
					<span class="error small">Seleccione el municipio de residencia del remitente</span>
				</div>
				<div class="col-md-6 form-group" data-natural data-juridico style="display:none;">
					<label for="provincia_afectado">* Ciudad / Estado / Provincia</label>
					<input type="text" class="form-control" id="provincia_afectado" name="provincia_afectado" autocomplete="no" data-required="true" data-label="Ciudad, estado y/o provincia de residencia del remitente">
					<span class="error small">Ingrese la ciudad, estado y/o provincia de residencia del remitente</span>
				</div>
				<div class="col-md-3 form-group" data-natural data-juridico>
					<label for="direccion">* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i></label>
					<input type="text" class="form-control" id="direccion" name="direccion" autocomplete="no" data-required="true" data-label="Dirección de residencia del remitente">
					<span class="error small">Ingrese la dirección del remitente</span>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-6 form-group" data-natural data-juridico>
					<label for="correo">* Correo electrónico <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su correo electrónico"></i></label>
					<input type="text" class="form-control" id="correo" name="correo" autocomplete="no" data-required="true" data-label="Correo electrónico del remitente">
					<span class="error small">Ingrese el correo electrónico del remitente</span>
				</div>
				<div class="col-md-3 form-group" data-natural data-juridico>
					<label for="celular">* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de celular"></i></label>
					<input type="number" class="form-control" id="celular" name="celular" autocomplete="no" data-required="true" data-label="Celular del remitente">
					<span class="error small">Ingrese el celular del remitente</span>
				</div>
				<div class="col-md-3 form-group" data-natural data-juridico>
					<label for="telefono">Teléfono <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono"></i></label>
					<input type="number" class="form-control" id="telefono" name="telefono" autocomplete="no" data-label="Teléfono del remitente">
					<span class="error small">Ingrese el teléfono del remitente</span>
				</div>
			</div>
			<div data-juridico style="display:none;">
				<div class="row">
					<div class="col-sm">
						<h4 class="section-h">Denunciante o Quejoso</h4>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-3 form-group">
						<label for="id">* Tipo de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el tipo de identificación"></i></label>
						<select name="representante_tipo_identificacion" id="representante_tipo_identificacion" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Tipo de identificación del denunciante o quejoso">
							<?php foreach ($tipos_documentos as $tipo) { ?>
								<option value="<?=$tipo['TDID_CODI']?>"><?=$tipo['TDID_DESC']?></option>
							<?php } ?>
						</select>
						<span class="error small">Seleccione el tipo de identificación del denunciante o quejoso</span>
					</div>
					<div class="col-md-3 form-group">
						<label for="id">* Número de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número del documento de identificación del paciente o afectado. En caso de que sea un menor de edad que no cuente con identificación, digite el número de documento del tutor o de la persona a cargo. Recuerde que si selecciona pasaporte debe ingresar números y letras de lo contrario sólo números"></i></label>
						<input type="text" class="form-control" id="representante_id" name="representante_id" autocomplete="no" data-required="true" data-label="Número de identificación del denunciante o quejoso">
						<span class="error small">Ingrese el número de identificación del denunciante o quejoso</span>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-3 form-group">
						<label for="nombre_peticionario_1">* Primer nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer nombre"></i></label>
						<input type="text" class="form-control alpha-only" id="nombre_peticionario_1" name="nombre_peticionario_1" autocomplete="no" data-required="true" data-label="Primer nombre del denunciante o quejoso">
						<span class="error small">Ingrese primer nombre del denunciante o quejoso</span>
					</div>
					<div class="col-md-3 form-group">
						<label for="nombre_peticionario_2">Segundo nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo nombre"></i></label>
						<input type="text" class="form-control alpha-only" id="nombre_peticionario_2" name="nombre_peticionario_2" autocomplete="no">
					</div>
					<div class="col-md-3 form-group">
						<label for="apellidos_peticionario_1">* Primer apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer apellido"></i></label>
						<input type="text" class="form-control alpha-only" id="apellidos_peticionario_1" name="apellidos_peticionario_1" autocomplete="no" data-required="true" data-label="Primer apellido del denunciante o quejoso">
						<span class="error small">Ingrese primer apellido del denunciante o quejoso</span>
					</div>
					<div class="col-md-3 form-group">
						<label for="apellidos_peticionario_2">Segundo apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo apellido"></i></label>
						<input type="text" class="form-control alpha-only" id="apellidos_peticionario_2" name="apellidos_peticionario_2" autocomplete="no">
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-3 form-group" data-natural data-juridico>
						<label for="pais_representante">* País <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el país en el que reside "></i></label>
						<select name="pais_representante" id="pais_representante" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="País de residencia del denunciante o quejoso">
							<?php foreach ($paises as $pais) { ?>
								<option value="<?=$pais['NOMBRE']?>"><?=$pais['NOMBRE']?></option>
							<?php } ?>
						</select>
						<span class="error small">Seleccione el país de residencia del denunciante o quejoso</span>
					</div> 
					<div class="col-md-3 form-group" data-natural data-juridico>
						<label for="">* Departamento <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el departamento en el que reside"></i></label>
						<select name="departamento_representante" id="departamento_representante" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Departamento de residencia del denunciante o quejoso">
							<?php foreach ($departamentos as $departamento) { ?>
								<option value="<?=$departamento['DPTO_CODI']?>"><?=$departamento['DPTO_NOMB']?></option>
							<?php } ?>
						</select>
						<span class="error small">Seleccione el departamento de residencia del denunciante o quejoso</span>
					</div>
					<div class="col-md-3 form-group" data-natural data-juridico>
						<label for="">* Municipio <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el municipio en el que reside"></i></label>
						<select name="ciudad_representante" id="ciudad_representante" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Municipio de residencia del denunciante o quejoso"></select>
						<div id="representante_ciudad_bar" class="progress" style="display:none; height:5px;">
							<div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
						</div>
						<small id="emailHelp" class="form-text text-muted">*ANM Areas no municipalizadas</small>
						<span class="error small">Seleccione el municipio de residencia del denunciante o quejoso</span>
					</div>
					<div class="col-md-6 form-group" data-natural data-juridico style="display:none;">
						<label for="provincia_representante">* Ciudad / Estado / Provincia</label>
						<input type="text" class="form-control" id="provincia_representante" name="provincia_representante" autocomplete="no" data-required="true" data-label="Ciudad, estado y/o provincia de residencia del denunciante o quejoso">
						<span class="error small">Ingrese la ciudad, estado y/o provincia de residencia del denunciante o quejoso</span>
					</div>
					<div class="col-md-3 form-group" data-natural data-juridico>
						<label for="representante_direccion">* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i></label>
						<input type="text" class="form-control" id="representante_direccion" name="representante_direccion" autocomplete="no" data-required="true" data-label="Ciudad, estado y/o provincia de residencia del denunciante o quejoso">
						<span class="error small">Ingrese la dirección del denunciante o quejoso</span>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-6 form-group" data-natural data-juridico data-anonimo>
						<label for="representante_correo">* Correo electrónico <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su correo electrónico"></i></label>
						<input type="text" class="form-control" id="representante_correo" name="representante_correo" autocomplete="no" data-required="true" data-label="Correo electrónico del denunciante o quejoso">
						<span class="error small">Ingrese el correo electrónico del denunciante o quejoso</span>
					</div>
					<div class="col-md-3 form-group" data-natural data-juridico>
						<label for="representante_celular">* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de celular"></i></label>
						<input type="number" class="form-control" id="representante_celular" name="representante_celular" autocomplete="no" data-required="true" data-label="Celular del denunciante o quejoso">
						<span class="error small">Ingrese el celular del denunciante o quejoso</span>
					</div>
					<div class="col-md-3 form-group" data-natural data-juridico>
						<label for="representante_telefono">Teléfono <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono"></i></label>
						<input type="number" class="form-control" id="representante_telefono" name="representante_telefono" autocomplete="no" data-label="Teléfono del denunciante o quejoso">
						<span class="error small">Ingrese el teléfono del denunciante o quejoso</span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
					<h4 class="section-h">Detalle de la Denuncia o Queja</h4>
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
					<div class="alert alert-light" role="alert">
						<strong>Nota:</strong> Describa los detalles del caso y si conoce el nombre del funcionario por favor indíquelo.
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-12 form-group limited-textarea">
					<label for="comentarios">* Resuma brevemente el asunto de esta solicitud: </label>
					<textarea id="comentarios" name="comentarios" class="form-control" rows="5" autocomplete="no" data-required="true" data-label="Resumen del la solicitud" maxlength="5000"></textarea>
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
			<div class="form-row" data-natural data-juridico>
				<div class="col-sm form-group">
					* Autorizo el envío de información a través de: &nbsp;
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" name="medio[]" id="medio_1" value="Correo electrónico" data-required="true" data-label="Medio para recibir información de su solicitud" checked>
						<label class="form-check-label" for="medio_1">Correo electrónico</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="checkbox" name="medio[]" id="medio_2" value="Dirección de correspondencia" data-required="true">
						<label class="form-check-label" for="medio_2">Dirección de correspondencia</label>
					</div>
					<span class="error small">Seleccione al menos un medio sobre el cual desea recibir información de su denuncia</span>
				</div>
			</div>
			<div class="row">
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
					<input type="hidden" name="tipoSolicitud" value="Queja">
					<input type="hidden" id="tipoUsuario" name="tipoUsuario" value=""/>
					<input type="hidden" id="adjuntosSubidos" name="adjuntosSubidos" value=""/>
					<input type="hidden" name="pais" value="170">
					<input type="button" class="btn btn-round btn-high" id="enviar-solicitud" value="Enviar">
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
			$('[data-toggle="tooltip"]').tooltip();

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
				if ((event.which != 8 && event.which != 9) && isNaN(String.fromCharCode(event.which))){
					event.preventDefault(); //stop character from entering input
				}
			});

			$('#borrar').on('click', function(e) {
				$("#form-solicitud")[0].reset();
				$('select').each(function(e) {
					$(this).val('').trigger('change');
					$('#ciudad_bar').hide();
				});
			});

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

			var tipo_identificacion_peticionario_options = $('select[name="tipo_identificacion"]').html();

			$('input[name="tipo"]').on('change', function(e) 
			{
				var options_html = $('<div></div>').html(tipo_identificacion_peticionario_options);
				options_html.find('.bs-title-option').remove();
				var tipo = $(this).val();
				
				$('label[for="pais_afectado"] i').attr('data-original-title', 'Seleccione el país en el que reside');
				$('label[for="departamento_afectado"] i').attr('data-original-title', 'Seleccione el departamento en el que reside');
				$('label[for="ciudad_afectado"] i').attr('data-original-title', 'Seleccione el municipio en el que reside');
				$('label[for="direccion"] i').attr('data-original-title', 'Digite la dirección de su residencia');

				var label_correo = '* Correo electrónico <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su correo electrónico"></i>';
				switch(tipo){
					case '1':
						$('div[data-juridico]').hide();
						$('div[data-anonimo]').hide();
						$('div[data-natural]').show();
						$('select[name="tipo_identificacion"]').html(options_html.html());
						$('select[name="tipo_identificacion"]').selectpicker('refresh');
						$('select[name="pais_afectado"]').trigger('change');
						$('select[name="pais_representante"]').trigger('change');
						$('div[data-anonimo]').removeClass('col-md-12').addClass('col-md-3');
						$('#correo').attr('data-required', 'true');
					break;
					case '2':
						$('div[data-natural]').hide();
						$('div[data-anonimo]').hide();
						$('div[data-juridico]').show();
						options_html.html('<option value="4">NIT (Número de identificación Tributaria)</option>');
						$('select[name="tipo_identificacion"]').html(options_html.html());
						$('select[name="tipo_identificacion"]').selectpicker('refresh');
						$('select[name="pais_afectado"]').trigger('change');
						$('select[name="pais_representante"]').trigger('change');
						$('div[data-anonimo]').removeClass('col-md-12').addClass('col-md-3');

						$('label[for="pais_afectado"] i').attr('data-original-title', 'Seleccione el país de su domicilio');
						$('label[for="departamento_afectado"] i').attr('data-original-title', 'Seleccione el departamento de su domicilio	');
						$('label[for="ciudad_afectado"] i').attr('data-original-title', 'Seleccione el municipio de su domicilio');
						$('label[for="direccion"] i').attr('data-original-title', 'Digite la dirección de su domicilio');
						$('#correo').attr('data-required', 'true');
					break;
					case '3':
						$('div[data-natural]').hide();
						$('div[data-juridico]').hide();
						$('div[data-anonimo]').show();
						label_correo = 'Correo electrónico <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su correo electrónico"></i>';

						$('div[data-anonimo]').removeClass('col-md-3').addClass('col-md-12');
						$('#correo').removeAttr('data-required');
					break;
				}

				$('label[for="correo"]').html(label_correo);
				$('[data-toggle="tooltip"]').tooltip();
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

			$('#departamento_afectado').on('change', function(e) {
				$('#ciudad_bar').show();
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
						$('#ciudad_bar').hide();
					}
				})
			});
			
			$('#departamento_representante').on('change', function(e) {
				$('#representante_ciudad_bar').show();
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

						$('#ciudad_representante').html(options);
						$('#ciudad_representante').selectpicker('refresh');
						$('#representante_ciudad_bar').hide();
					}
				})
			});

			$('#tipo_identificacion').on('change', function(e) {
				var tipo_identificacion = $(this).val();
				$('#id').val('');

				if(tipo_identificacion == 3)
				{
					$('#id').attr('type', 'text');
				} else {
					$('#id').attr('type', 'number');
				}
			});

			$('#representante_tipo_identificacion').on('change', function(e) {
				var representante_tipo_identificacion = $(this).val();
				$('#representante_id').val('');

				if(representante_tipo_identificacion == 3)
				{
					$('#representante_id').attr('type', 'text');
				} else {
					$('#representante_id').attr('type', 'number');
				}
			});

			$('#tipo_entidad').on('change', function(e) {
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
							options += '<option value="'+e.ENT_ID+'">'+e.ENT_NOMB+'</option>'
						});

						$('#entidad').html(options);
						$('#entidad').selectpicker('refresh');
					}
				});
			});

			var altcha_widget_0_state = '';

			document.querySelector('#altcha-widget-0').addEventListener('statechange', (ev) => {
				altcha_widget_0_state = ev.detail.state;
				if (ev.detail.state === 'verified') {
					$('#altcha-widget-0').removeClass('altcha-error');
				}
			});

			$('#enviar-solicitud').on('click', function(e) {
				var errors = 0;
				var errors_text = '<p>Por favor ingrese la siguiente información: </p><ul>';

				$('input[type="text"], input[type="number"], input[type="radio"], input[type="checkbox"], input[type="file"], textarea, select').each(function(e) {
					
					var isFile = $(this).is('input[type="file"]');
					var label = $(this).data('label');

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

						if(valor.trim() == '')
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
							errors_text += (label != undefined ? '<li>'+label+'</li>' : '');
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

					e.preventDefault();
				}else{
					$('#form-solicitud').submit();
					$('.loader').show();
				}
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
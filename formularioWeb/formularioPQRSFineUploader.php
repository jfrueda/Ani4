<?
session_start();
/**
 * Modulo de Formularios Web para atencion a Ciudadanos.
 * @autor Jairo Losada
 * @autor Cesar Gonzalez
 * @fecha 2020/04
 *
 */
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

define('ADODB_ASSOC_CASE', 1);

$ruta_raiz = "..";
$ADODB_COUNTRECS = false;

include_once("$ruta_raiz/processConfig.php");
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
$_SESSION["depeRadicaFormularioWeb"]=$depeRadicaFormularioWeb;  // Es radicado en la Dependencia 900
$_SESSION["usuaRecibeWeb"]=$usuaRecibeWeb; // Usuario que Recibe los Documentos Web
$_SESSION["secRadicaFormularioWeb"]=$secRadicaFormularioWeb; // Osea que usa la Secuencia sec_tp2_900
$_SESSION["idFormulario"] = sha1(microtime(true).mt_rand(10000,90000));
$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

include('./funciones.php');
include('./formulario_sql.php');
include('./captcha/simple-php-captcha.php');
$_SESSION['captcha_formulario'] = captcha();

//TamaNo mAximo del todos los archivos en bytes 10MB = 10(MB)*1024(KB)*1024(B) =  10485760 bytes
$max_file_size  = 10485760;

if(!isset($isFacebook)){
	$isFacebook = 0;
}

$log= "https://upload.wikimedia.org/wikipedia/commons/c/c4/LOGO_UMNG.png";

?>

<!DOCTYPE html>
<html lang="es">
<head>

<title>:: <?=$entidad_largo ?>:: Formulario PQRS</title>

<!-- Meta Tags -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!--Deshabilitar modo de compatiblidad de Internet Explorer-->
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="css/structure2.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/fineuploader.css" type="text/css" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha384-1H217gwSVyLSIfaLxHbE7dRb3v4mYCKbpQvzx0cegeju1MVsGrX5xXxAvs/HgeFs" crossorigin="anonymous"></script>

<!-- Prototype -->
<script type="text/javascript" src="prototype.js"></script>

<!-- FineUploader -->
<script type="text/javascript" src="scripts/jquery.fineuploader-3.0.js"></script>

<!-- Custom Scripts -->
<script type="text/javascript" src="scripts/wufoo.js"></script>
<script type="text/javascript" src="ajax.js"></script>

<!-- Bootstrap 5 JS Bundle (incluye Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script>
    window.onload = createUploader;
</script>

</head>

<body id="public" class="bg-light">

<div class="container my-5">
	<div class="row justify-content-center">
		<div class="col-lg-10 col-xl-9">
			<div class="card shadow-lg border-0">
				<div class="card-body p-4 p-md-5">
					<!-- Logo -->
					<div class="text-center mb-4">
						<img src='<?=$log?>' height='150'  class="" alt="Logo">
					</div>

					<!-- Información -->
					

					<form id="contactoOrfeo" autocomplete="on"
						enctype="multipart/form-data" method="post" action="formulariotx.php"
						name="quejas" class="needs-validation" novalidate>

										<div class="row g-3">
											<!-- Sección: Información del usuario -->
											<div class="col-12">
												<h5 class="border-bottom pb-2 mb-0 text-primary"><i class="bi bi-person-fill me-2"></i>Información del usuario</h5>
											</div>

											<!-- Anónimo (oculto, por defecto No) -->
											<input type="hidden" id="chkAnonimo" name="anonimo" value="0" />
											<input type="hidden" id="tipoDocumento" name="tipoDocumento" value="1" >

											<!-- Número de Identificación -->
											<div class="col-md-6">
												<label for="campo_numid" class="form-label">Documento de identificación <span class="text-danger">*</span></label>
												<input id="campo_numid" name="numid" type="text" class="form-control" value="" maxlength="11" tabindex="4" onkeypress="return alpha(event,numbers+letters)" required />
												<div class="form-text">Solo números o letras</div>
												<div class="invalid-feedback">Por favor ingrese su número de identificación.</div>
											</div>

											<!-- Nombre -->
											<div class="col-md-6">
												<label for="campo_nombre" class="form-label">Usuario <span class="text-danger">*</span></label>
												<input id="campo_nombre" name="nombre_remitente" type="text" class="form-control" value="" tabindex="5" onkeypressS="return alpha(event,letters);" required />
												<div class="invalid-feedback">Por favor ingrese el nombre.</div>
											</div>

											<!-- Apellidos -->
											<div class="col-md-6">
												<label for="campo_apellido" class="form-label">Apellidos <span class="text-danger">*</span></label>
												<input id="campo_apellido" name="apellidos_remitente" type="text" class="form-control" value="" tabindex="6" onkeypress="return alpha(event,letters);" required />
												<div class="invalid-feedback">Por favor ingrese los apellidos.</div>
											</div>

											<!-- País -->
											<div class="col-md-6">
												<label for="slc_pais" class="form-label">País <span class="text-danger">*</span></label>
												<select id="slc_pais" name="pais" class="form-select" tabindex="7" onchange="cambia_pais()" required>
													<?=$pais ?>
												</select>
												<div class="invalid-feedback">Por favor seleccione el país.</div>
											</div>

											<!-- Departamento -->
											<div class="col-md-6">
												<label for="slc_depto" class="form-label">Estado <span class="text-danger">*</span></label>
												<select id="slc_depto" name="depto" class="form-select" tabindex="8" onchange="trae_municipio()" required>
													<option value="0" selected>Seleccione</option>
													<?=$depto ?>
												</select>
												<div class="invalid-feedback">Por favor seleccione el departamento.</div>
											</div>

											<!-- Municipio -->
											<div class="col-md-6">
												<label for="slc_municipio" class="form-label">Provincia <span class="text-danger">*</span></label>
												<div id="div-contenidos">
													<select id="slc_municipio" name="muni" class="form-select" tabindex="9" required>
														<option value="0" selected>Seleccione..</option>
													</select>
												</div>
												<div class="invalid-feedback">Por favor seleccione el municipio.</div>
											</div>

											<!-- Dirección -->
											<div class="col-md-6">
												<label for="campo_direccion" class="form-label">Dirección</label>
												<input id="campo_direccion" name="direccion" type="text" class="form-control" value="" maxlength="150" tabindex="10" onkeypress="return alpha(event,numbers+letters+signs+custom)" />
											</div>

											<!-- Teléfono -->
											<div class="col-md-6">
														<label for="campo_telefono" class="form-label">Número de Teléfono</label>
														<input id="campo_telefono" name="telefono" type="text" class="form-control" value="" maxlength="80" tabindex="11" onkeypress="return alpha(event,numbers+alpha)" />
													</div>

													<!-- Móvil -->
													<div class="col-md-6">
														<label for="campo_celular" class="form-label">Móvil <span class="text-danger">*</span></label>
														<input id="campo_celular" name="celular" type="text" class="form-control" value="" maxlength="15" tabindex="12" onkeypress="return alpha(event,numbers)" required />
														<div class="invalid-feedback">Por favor ingrese el número de celular.</div>
													</div>

													<!-- Medio de contacto preferido -->
													<div class="col-md-6">
														<label for="medioContacto" class="form-label">Medio de contacto preferido</label>
														<select id="medioContacto" name="medioContacto" class="form-select" tabindex="13">
															<option value="" selected>Seleccione</option>
															<option value="Correo electrónico">Correo electrónico</option>
															<option value="Vía telefónica">Vía telefónica</option>
															<option value="Correo escrito">Correo escrito</option>
														</select>
											</div>

											<!-- Email -->
											<div class="col-md-6">
												<label for="campo_email" class="form-label">E-mail <span class="text-danger">*</span></label>
												<input id="campo_email" name="email" type="email" class="form-control" value="" maxlength="50" tabindex="12" required />
												<div class="invalid-feedback">Por favor ingrese un email válido.</div>
											</div>

											<!-- Tipo de Población -->
											<div class="col-md-6">
												<label for="tipoPoblacioen" class="form-label">Tipo de población</label>
												<select id="tipoPoblacioen" name="tipoPoblacion" class="form-select" tabindex="39">
													<?=$temas;?>
													<option value="0" selected>No aplica</option>
													<option value="1">Población Desplazada</option>
													<option value="2">Mujer Gestante</option>
													<option value="3">Niños, Niñas, Adolescentes</option>
													<option value="4">Veterano Fuerza Pública</option>
													<option value="5">Adulto Mayor</option>
												</select>
											</div>

											<!-- Sección: Información de solicitud -->
											<div class="col-12 mt-4">
												<h5 class="border-bottom pb-2 mb-0 text-primary"><i class="bi bi-file-earmark-text-fill me-2"></i>Información de solicitud</h5>
											</div>

											<!-- Tipo de Solicitud -->
											<div class="col-md-6">
												<label for="tipoSolicitud" class="form-label">Tipo de Solicitud <span class="text-danger">*</span></label>
												<select id="tipoSolicitud" name="tipoSolicitud" class="form-select" tabindex="1" required onchange="document.getElementById('tipoSolicitudTexto').value=this.options[this.selectedIndex].text;">
													<option value="0" selected>Seleccione</option>
													<?=$tipo; ?>
													<option value="2">Queja</option>
													<option value="3">Reclamo</option>
													<option value="1">Derecho de petición de consulta</option>
													<option value="1">Derecho de Petición entre Autoridades</option>
													<option value="1">Derecho de Petición</option>
													<option value="1">Derecho de Petición de Información y Documentos</option>
													<option value="4">Sugerencia</option>
													<option value="7">Felicitación</option>
													<option value="8">Denuncia</option>
												</select>
												<input type="hidden" id="tipoSolicitudTexto" name="tipoSolicitudTexto" value="" />
												<div class="invalid-feedback">Por favor seleccione el tipo de petición.</div>
											</div>

											<!-- Tipo de Solicitante -->
											<div class="col-md-6">
												<label for="tipoSolicitante" class="form-label">Tipo de Solicitante <span class="text-danger">*</span></label>
												<select id="tipoSolicitante" name="tipoSolicitante" class="form-select" tabindex="2" required>
													<option value="" selected>Seleccione</option>
													<option value="Aspirante">Aspirante</option>
													<option value="Estudiante">Estudiante</option>
													<option value="Egresado">Egresado</option>
													<option value="Docente">Docente</option>
													<option value="Administrativo">Administrativo</option>
													<option value="Ciudadano">Ciudadano</option>
												</select>
												<div class="invalid-feedback">Por favor seleccione el tipo de solicitante.</div>
											</div>

											<!-- Selecciona el servicio -->
													<div class="col-md-6">
														<label for="servicio" class="form-label">Selecciona el servicio <span class="text-danger">*</span></label>
														<select id="servicio" name="servicio" class="form-select" tabindex="14" required>
															<option value="" selected>Seleccione</option>
															<option value="Académicos">Académicos</option>
															<option value="Administrativos">Administrativos</option>
															<option value="Extensión">Extensión</option>
															<option value="Infraestructura">Infraestructura</option>
															<option value="Institucionales">Institucionales</option>
															<option value="Jurídico y Legal">Jurídico y Legal</option>
															<option value="Tecnológico">Tecnológico</option>
															<option value="Otro">Otro</option>
														</select>
														<div class="invalid-feedback">Por favor seleccione el servicio.</div>
													</div>

													<!-- Sistema al que Aplica -->
													<div class="col-md-6">
														<label for="sistemaAplica" class="form-label">Sistema al que Aplica</label>
														<select id="sistemaAplica" name="sistemaAplica" class="form-select" tabindex="15">
															<option value="" selected>Seleccione</option>
															<option value="Calidad">Calidad</option>
															<option value="Ambiente">Ambiente</option>
															<option value="Seguridad y Salud">Seguridad y Salud</option>
														</select>
													</div>

													<!-- Asunto -->
											<div class="col-12">
												<label for="campo_asunto" class="form-label">Ponle un titulo a tu solicitud <span class="text-danger">*</span></label>
												<input id="campo_asunto" name="asunto" type="text" class="form-control" value="" maxlength="80" tabindex="15" required />
												<div class="invalid-feedback">Por favor ingrese el tema de su petición.</div>
											</div>

											<!-- Comentario -->
											<div class="col-12">
												<label for="campo_comentario" class="form-label">Cuéntanos sobre tu solicitud <span class="text-danger">*</span></label>
												<div class="alert alert-light border mb-2">
													<small><i class="bi bi-info-circle me-1"></i>Para dar mayor agilidad a su solicitud, por favor realizar la descripción de los hechos haciendo referencia al momento, lugar, participantes y móviles entre otros elementos que considere que pueden despejar cualquier duda sobre las circunstancias.</small>
												</div>
												<textarea id="campo_comentario" name="comentario" class="form-control" rows="6" tabindex="16" onkeyup="countChar(this)" placeholder="Escriba aquí..." required></textarea>
												<div class="d-flex justify-content-between mt-1">
													<div class="invalid-feedback">Por favor ingrese su comentario.</div>
													<small id="charNum" class="text-muted"></small>
												</div>
												<input type="hidden" id="adjuntosSubidos" name="adjuntosSubidos" value="" />
											</div>

											<!-- Archivos Adjuntos -->
											<div class="col-12">
												<label class="form-label"><i class="bi bi-paperclip me-2"></i>Adjunto</label>
												<div id="filelimit-fine-uploader" class="border rounded p-3 bg-light" tabindex="17"></div>
												<div id="availabeForUpload"></div>
											</div>

											<!-- Captcha -->
											<div class="col-12">
												<div class="row align-items-center">
													<div class="col-md-6">
														<label for="campo_captcha" class="form-label">Imagen de verificación <span class="text-danger">*</span></label>
														<input id="campo_captcha" name="captcha" type="text" class="form-control" value="" maxlength="5" tabindex="20" onkeypress="return alpha(event,numbers+letters)" placeholder="Digite el código" required />
														<div class="invalid-feedback">Por favor ingrese el código de verificación.</div>
													</div>
													<div class="col-md-6 text-center">
														<?php
														echo '<img id="imgcaptcha" src="' . $_SESSION['captcha_formulario']['image_src'] . '" alt="CAPTCHA" class="img-fluid rounded border mb-2" /><br>';
														echo '<a href="#" onClick="return reloadImg(\'imgcaptcha\');" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-clockwise me-1"></i>Cambiar imagen</a>'
														?>
													</div>
												</div>
											</div>

					<!-- Política de privacidad -->
													<div class="col-12">
														<div class="form-check">
															<input type="checkbox" class="form-check-input" name="politicaPrivacidad" id="politicaPrivacidad" value="1" required />
															<label class="form-check-label" for="politicaPrivacidad">
																Autorizo el almacenamiento, consulta, procesamiento y tratamiento de mis datos personales conforme a su política de privacidad.
															</label>
															<div class="invalid-feedback">Debe aceptar la política de privacidad para continuar.</div>
														</div>
													</div>

													<!-- Campos ocultos -->
											<input type="hidden" name="pqrsFacebook" value="<?=$isFacebook?>" />
											<input type="hidden" name="idFormulario" value="<?=$_SESSION["idFormulario"]?>" />

											<!-- Botones -->
											<div class="col-12 mt-4">
												<div class="d-grid gap-2 d-md-flex justify-content-md-center">
													<button id="saveForm" type="submit" class="btn btn-primary btn-lg px-5" onclick="return valida_form();" tabindex="21">
														<i class="bi bi-send-fill me-2"></i>Enviar
													</button>
													<button name="button" type="button" class="btn btn-secondary btn-lg px-5" onclick="window.close();" tabindex="22">
														<i class="bi bi-x-circle me-2"></i>Cancelar
													</button>
												</div>
											</div>
										</div>

					</form>

				</div>
			</div>
		</div>
	</div>
</div>

</body>
</html>

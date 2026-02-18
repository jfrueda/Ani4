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

    // Event listeners en lugar de onchange inline (accesibilidad: evita JS jump menu)
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('slc_pais').addEventListener('change', function() {
            cambia_pais();
        });
        document.getElementById('slc_depto').addEventListener('change', function() {
            trae_municipio();
        });
        document.getElementById('tipoSolicitud').addEventListener('change', function() {
            document.getElementById('tipoSolicitudTexto').value = this.options[this.selectedIndex].text;
        });
    });
</script>

<!-- Accesibilidad: sobreescrituras de contraste y foco -->
<style>
    /* Contraste WCAG AA mínimo 4.5:1 para texto auxiliar */
    .form-text, .text-muted {
        color: #495057 !important; /* ratio ~7:1 sobre blanco */
    }
    /* Foco visible para navegación por teclado */
    *:focus-visible {
        outline: 3px solid #0d6efd;
        outline-offset: 2px;
    }
    /* Asegurar que los enlaces del footer tengan contraste */
    footer a {
        color: #0d6efd;
    }
</style>

</head>

<body id="public" class="bg-light">

<!-- Enlace saltar al contenido (accesibilidad) -->
<a class="visually-hidden-focusable" href="#main-content">Saltar al contenido principal</a>

<header role="banner">
<div class="container my-5">
	<div class="row justify-content-center">
		<div class="col-lg-10 col-xl-9">
			<div class="card shadow-lg border-0">
				<div class="card-body p-4 p-md-5">
					<!-- Logo -->
					<div class="text-center mb-4">
						<img src='<?=$log?>' height='150' class="" alt="Logo Universidad Militar Nueva Granada - Formulario PQRS">
					</div>
					<h1 class="text-center fs-4 mb-4">SISTEMA DE PQRSDF UNIVERSIDAD MILITAR NUEVA GRANADA</h1>
					<p class="text-center text-black mb-4">Señor Usuario: a través de este formulario usted puede diligenciar las PQRSDF para su debido trámite.</p>

					<!-- Definiciones PQRSDF (collapse sutil) -->
					<div class="text-center mb-3">
						<a href="#definicionesPQRSDF" class="text-decoration-none small text-black" data-bs-toggle="collapse" aria-expanded="false" aria-controls="definicionesPQRSDF">
							<i class="bi bi-info-circle me-1" aria-hidden="true"></i>Definiciones PQRSDF <i class="bi bi-chevron-down small" aria-hidden="true"></i>
						</a>
					</div>
					<div class="collapse mb-3" id="definicionesPQRSDF">
						<div class="card card-body small border-0 bg-light p-3">
							<dl class="row mb-0">
								<dt class="col-sm-3">Petición</dt>
								<dd class="col-sm-9">Toda persona tiene derecho a presentar peticiones respetuosas a las autoridades, en los términos señalados en la ley 1755 de 2015, por motivos de interés general o particular y a obtener pronta resolución completa y de fondo sobre la misma.</dd>

								<dt class="col-sm-3">Queja</dt>
								<dd class="col-sm-9">Es la manifestación de protesta, censura, descontento o inconformidad que formula una persona en la relación con la conducta que considera irregular de uno o varios servidores públicos en desarrollo de sus funciones.</dd>

								<dt class="col-sm-3">Reclamo</dt>
								<dd class="col-sm-9">Es el derecho que tiene toda persona de exigir, reivindicar o demandar una solución, por motivo general o particular, referente a la prestación indebida de un servicio o la falta de atención de una solicitud.</dd>

								<dt class="col-sm-3">Sugerencia</dt>
								<dd class="col-sm-9">Cualquier propuesta que formula un grupo de interés, que tiene como finalidad mejorar la prestación de un servicio en cualquiera de las áreas académicas o administrativas de la Universidad.</dd>

								<dt class="col-sm-3">Denuncia</dt>
								<dd class="col-sm-9">Es la puesta en conocimiento ante una autoridad competente de una conducta posiblemente irregular, para que se adelante la correspondiente investigación y se remitan las correspondientes copias a las entidades competentes.</dd>

								<dt class="col-sm-3">Felicitación</dt>
								<dd class="col-sm-9 mb-0">Expresión de satisfacción de un grupo de interés con relación a la prestación de un servicio.</dd>
							</dl>
						</div>
					</div>

					</div><!-- /.card-body -->
				</div><!-- /.card -->
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</header>

<main id="main-content" role="main">
<div class="container my-3">
	<div class="row justify-content-center">
		<div class="col-lg-10 col-xl-9">
			<div class="card shadow-lg border-0">
				<div class="card-body p-4 p-md-5">
					<form id="contactoOrfeo" autocomplete="on"
						enctype="multipart/form-data" method="post" action="formulariotx.php"
						name="quejas" class="needs-validation" novalidate
						aria-label="Formulario de Peticiones, Quejas, Reclamos y Sugerencias">

										<div class="row g-3">
											<!-- Sección: Información del usuario -->
											<div class="col-12">
												<h2 class="fs-5 border-bottom pb-2 mb-0 text-primary"><i class="bi bi-person-fill me-2" aria-hidden="true"></i>Información del usuario</h2>
											</div>

											<!-- Anónimo (oculto, por defecto No) -->
											<input type="hidden" id="chkAnonimo" name="anonimo" value="0" />
											<input type="hidden" id="tipoDocumento" name="tipoDocumento" value="1" >

											<!-- Número de Identificación -->
											<div class="col-md-6">
										<label for="campo_numid" class="form-label">Documento de identificación <span class="text-danger" aria-hidden="true">*</span></label>
										<input id="campo_numid" name="numid" type="text" class="form-control" value="" maxlength="11" onkeypress="return alpha(event,numbers+letters)" required aria-required="true" aria-describedby="numid-help" />
										<div class="form-text" id="numid-help">Solo números o letras</div>
												<div class="invalid-feedback">Por favor ingrese su número de identificación.</div>
											</div>

											<!-- Nombre -->
											<div class="col-md-6">
										<label for="campo_nombre" class="form-label">Usuario <span class="text-danger" aria-hidden="true">*</span></label>
										<input id="campo_nombre" name="nombre_remitente" type="text" class="form-control" value="" onkeypress="return alpha(event,letters);" required aria-required="true" />
												<div class="invalid-feedback">Por favor ingrese el nombre.</div>
											</div>

											<!-- Apellidos -->
											<div class="col-md-6">
										<label for="campo_apellido" class="form-label">Apellidos <span class="text-danger" aria-hidden="true">*</span></label>
										<input id="campo_apellido" name="apellidos_remitente" type="text" class="form-control" value="" onkeypress="return alpha(event,letters);" required aria-required="true" />
												<div class="invalid-feedback">Por favor ingrese los apellidos.</div>
											</div>

											<!-- País -->
											<div class="col-md-6">
										<label for="slc_pais" class="form-label">País <span class="text-danger" aria-hidden="true">*</span></label>
										<select id="slc_pais" name="pais" class="form-select" required aria-required="true">
													<?=$pais ?>
												</select>
												<div class="invalid-feedback">Por favor seleccione el país.</div>
											</div>

											<!-- Departamento -->
											<div class="col-md-6">
									<label for="slc_depto" class="form-label">Departamento <span class="text-danger" aria-hidden="true">*</span></label>
										<select id="slc_depto" name="depto" class="form-select" required aria-required="true">
													<option value="0" selected>Seleccione</option>
													<?=$depto ?>
												</select>
												<div class="invalid-feedback">Por favor seleccione el departamento.</div>
											</div>

											<!-- Municipio -->
											<div class="col-md-6">
									<label for="slc_municipio" class="form-label">Municipio <span class="text-danger" aria-hidden="true">*</span></label>
										<div id="div-contenidos">
											<select id="slc_municipio" name="muni" class="form-select" required aria-required="true">
														<option value="0" selected>Seleccione..</option>
													</select>
												</div>
												<div class="invalid-feedback">Por favor seleccione el municipio.</div>
											</div>

											<!-- Dirección -->
											<div class="col-md-6">
												<label for="campo_direccion" class="form-label">Dirección</label>
												<input id="campo_direccion" name="direccion" type="text" class="form-control" value="" maxlength="150" onkeypress="return alpha(event,numbers+letters+signs+custom)" />
											</div>

											<!-- Teléfono -->
											<div class="col-md-6">
														<label for="campo_telefono" class="form-label">Número de Teléfono</label>
											<input id="campo_telefono" name="telefono" type="text" class="form-control" value="" maxlength="80" onkeypress="return alpha(event,numbers+alpha)" />
													</div>

													<!-- Móvil -->
													<div class="col-md-6">
											<label for="campo_celular" class="form-label">Móvil <span class="text-danger" aria-hidden="true">*</span></label>
											<input id="campo_celular" name="celular" type="text" class="form-control" value="" maxlength="15" onkeypress="return alpha(event,numbers)" required aria-required="true" />
														<div class="invalid-feedback">Por favor ingrese el número de celular.</div>
													</div>

													<!-- Medio de contacto preferido -->
													<div class="col-md-6">
														<label for="medioContacto" class="form-label">Medio de contacto preferido</label>
														<select id="medioContacto" name="medioContacto" class="form-select">
															<option value="" selected>Seleccione</option>
															<option value="Correo electrónico">Correo electrónico</option>
															<option value="Vía telefónica">Vía telefónica</option>
															<option value="Correo escrito">Correo escrito</option>
														</select>
											</div>

											<!-- Email -->
											<div class="col-md-6">
										<label for="campo_email" class="form-label">E-mail <span class="text-danger" aria-hidden="true">*</span></label>
										<input id="campo_email" name="email" type="email" class="form-control" value="" maxlength="50" required aria-required="true" />
												<div class="invalid-feedback">Por favor ingrese un email válido.</div>
											</div>

											<!-- Tipo de Población -->
											<div class="col-md-6">
												<label for="tipoPoblacion" class="form-label">Tipo de población</label>
												<select id="tipoPoblacion" name="tipoPoblacion" class="form-select">
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
												<h2 class="fs-5 border-bottom pb-2 mb-0 text-primary"><i class="bi bi-file-earmark-text-fill me-2" aria-hidden="true"></i>Información de solicitud</h2>
											</div>

											<!-- Tipo de Solicitud -->
											<div class="col-md-6">
										<label for="tipoSolicitud" class="form-label">Tipo de Solicitud <span class="text-danger" aria-hidden="true">*</span></label>
										<select id="tipoSolicitud" name="tipoSolicitud" class="form-select" required aria-required="true">
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
										<label for="tipoSolicitante" class="form-label">Tipo de Solicitante <span class="text-danger" aria-hidden="true">*</span></label>
										<select id="tipoSolicitante" name="tipoSolicitante" class="form-select" required aria-required="true">
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
											<label for="servicio" class="form-label">Selecciona el servicio <span class="text-danger" aria-hidden="true">*</span></label>
											<select id="servicio" name="servicio" class="form-select" required aria-required="true">
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
														<select id="sistemaAplica" name="sistemaAplica" class="form-select">
															<option value="" selected>Seleccione</option>
															<option value="Calidad">Calidad</option>
															<option value="Ambiente">Ambiente</option>
															<option value="Seguridad y Salud">Seguridad y Salud</option>
														</select>
													</div>

													<!-- Asunto -->
											<div class="col-12">
										<label for="campo_asunto" class="form-label">Ponle un titulo a tu solicitud <span class="text-danger" aria-hidden="true">*</span></label>
										<input id="campo_asunto" name="asunto" type="text" class="form-control" value="" maxlength="80" required aria-required="true" />
												<div class="invalid-feedback">Por favor ingrese el tema de su petición.</div>
											</div>

											<!-- Comentario -->
											<div class="col-12">
												<label for="campo_comentario" class="form-label">Cuéntanos sobre tu solicitud <span class="text-danger" aria-hidden="true">*</span></label>
												
												<textarea id="campo_comentario" name="comentario" class="form-control" rows="6" onkeyup="countChar(this)" placeholder="Escriba aquí..." required aria-required="true"></textarea>
												<div class="d-flex justify-content-between mt-1">
													<div class="invalid-feedback">Por favor ingrese su comentario.</div>
													<small id="charNum" class="text-muted"></small>
												</div>
												<input type="hidden" id="adjuntosSubidos" name="adjuntosSubidos" value="" />
											</div>

											<!-- Archivos Adjuntos -->
											<div class="col-12">
										<label id="label-adjunto" for="fileacc" class="form-label"><i class="bi bi-paperclip me-2" aria-hidden="true"></i>Adjunto</label>
										<input type="hidden" id="fileacc" name="fileacc" />
											<div class="mb-3">
										<div id="filelimit-fine-uploader" class="border rounded p-3 bg-light" role="group" aria-labelledby="label-adjunto"></div>
												<div id="availabeForUpload"></div>
											</div>

											<!-- Captcha -->
											<div class="col-12">
												<div class="row align-items-center">
													<div class="col-md-6">
											<label for="campo_captcha" class="form-label">Imagen de verificación <span class="text-danger" aria-hidden="true">*</span></label>
											<input id="campo_captcha" name="captcha" type="text" class="form-control" value="" maxlength="5" onkeypress="return alpha(event,numbers+letters)" placeholder="Digite el código" required aria-required="true" aria-describedby="captcha-help" />
											<div class="invalid-feedback">Por favor ingrese el código de verificación.</div>
											<small id="captcha-help" class="form-text">Escriba los caracteres que aparecen en la imagen.</small>
													</div>
													<div class="col-md-6 text-center">
														<?php
											echo '<img id="imgcaptcha" src="' . $_SESSION['captcha_formulario']['image_src'] . '" alt="Imagen de verificación CAPTCHA - escriba los caracteres mostrados" class="img-fluid rounded border mb-2" /><br>';
											echo '<a href="#" onClick="return reloadImg(\'imgcaptcha\');" class="btn btn-sm btn-outline-secondary" role="button"><i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i>Cambiar imagen</a>'
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
												<button id="saveForm" type="submit" class="btn btn-primary btn-lg px-5" onclick="return valida_form();">
													<i class="bi bi-send-fill me-2" aria-hidden="true"></i>Enviar
												</button>
												<button name="button" type="button" class="btn btn-secondary btn-lg px-5" onclick="window.close();">
													<i class="bi bi-x-circle me-2" aria-hidden="true"></i>Cancelar
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
</main>

<footer role="contentinfo" class="container mb-5">
	<div class="row justify-content-center">
		<div class="col-lg-10 col-xl-9 text-center">
			<a href="#accesibilidad" class="text-decoration-none fw-semibold" style="color: #1a4e8a;" data-bs-toggle="collapse" aria-expanded="false" aria-controls="accesibilidad">
					<i class="bi bi-universal-access me-1" aria-hidden="true"></i>Declaración de Accesibilidad
				</a>
			<div class="collapse mt-3 text-start" id="accesibilidad">
				<div class="card card-body small" style="color: #212529;">
					<p>La <strong>UNIVERSIDAD MILITAR NUEVA GRANADA</strong> se caracteriza por mantenerse en desarrollo constante; ha obtenido el reconocimiento de Acreditación en alta calidad y siempre se encuentra trabajando para atender las normativas y resoluciones que se establecen de obligatorio cumplimiento. En ese sentido, en cumplimiento al artículo 3 de la Resolución 1519 de 2020, la Universidad realiza revisiones continuas que permiten mejorar la comunicación hacia los diferentes grupos de interés a través del portal web publicado en <a href="https://www.umng.edu.co" target="_blank" rel="noopener noreferrer">https://www.umng.edu.co</a>.</p>
					<p>En virtud de ofrecer contenidos e información accesible, se han adoptado medidas para minimizar las restricciones que puedan ser ocasionadas por diversidades funcionales de los usuarios. Así en la creación, edición y actualización de contenidos se ofrece el mayor grado de accesibilidad ayudando a sortear barreras que pueden limitar el entendimiento de la información que se publica especialmente a las personas en situación de discapacidad.</p>
					<p>La Universidad está trabajando para conseguir un grado adecuado de implementación de acuerdo a los criterios del Anexo 01 de la Resolución 1519 de 2020, respondiendo a una conformidad subjetiva como se menciona a continuación:</p>
					<dl>
						<dt>A. Texto alternativo</dt>
						<dd>El portal dispone del funcionamiento adecuado del texto alternativo con la etiqueta "alt" utilizando el campo de descripción para que sea agregado en la edición de contenidos.</dd>
						<dt>B. Multimedia accesible</dt>
						<dd>El portal web permite cargar todo tipo de contenido audiovisual. Los videos cuentan con subtítulos a través de YouTube. En cuanto a los videos de rendición de cuentas, se está trabajando para que cuenten con lenguaje de señas.</dd>
						<dt>C. Texto legible y ampliable</dt>
						<dd>Se cumple con el criterio en cuanto al texto (mínimo 12 puntos) y el portal cuenta con el módulo de accesibilidad que permite aumentar y disminuir los textos hasta un 200% sin desconfigurar el contenido.</dd>
						<dt>D. Código estructurado y navegación</dt>
						<dd>El portal utiliza etiquetas organizadas permitiendo la navegabilidad con jerarquía en títulos, subtítulos y párrafos e incluye buscador con filtros avanzados.</dd>
						<dt>E. Formularios accesibles</dt>
						<dd>El portal dispone de formularios con canales sensoriales, campos obligatorios marcados con asterisco y colores, cumpliendo los criterios de accesibilidad.</dd>
						<dt>F. Navegación por tabulación</dt>
						<dd>El portal permite la navegación por tabulación en orden adecuado, resaltando la información seleccionada por los diferentes campos y menús.</dd>
						<dt>G. Control de movimientos</dt>
						<dd>El portal permite controlar contenidos con movimiento mediante botones de continuar/pausar eventos.</dd>
						<dt>H. Lenguaje claro</dt>
						<dd>El portal utiliza lenguaje claro en español siguiendo la guía del DAFP, con jerarquía de etiquetas para títulos, subtítulos y párrafos.</dd>
						<dt>I. Documentos accesibles</dt>
						<dd>El portal permite cargar diferentes tipos de archivos accesibles. Los documentos se encuentran en revisión para atender las directrices de accesibilidad.</dd>
					</dl>
				</div>
			</div>
		</div>
	</div>
</footer>

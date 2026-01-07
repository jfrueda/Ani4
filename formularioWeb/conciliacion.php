<?php
	session_start();
	define('ADODB_ASSOC_CASE', 1);
	$ruta_raiz = "..";
	$ADODB_COUNTRECS = false;
	
	include_once("$ruta_raiz/processConfig.php");
	include_once("$ruta_raiz/include/db/ConnectionHandler.php");
    include_once("$ruta_raiz/formularioWeb/solicitudes_sql.php");
	
    $pregunta = '5';
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
		'ROM - Gitano',
		'No Aplica'
	];

    $tipos_demandas = [
        'Cobertura de servicios incluidos del Plan de Beneficios en Salud- PBS',
        'Reconocimiento económico de gastos en salud',
        'Conflictos por multiafiliación dentro del Sistema General de Seguridad Social en Salud- SGSSS',
        'Conflictos por libre elección y movilidad',
        'Conflictos derivados de las devoluciones o glosas a las facturas, reclamaciones y recobros de servicios No PBS',
        'Prestaciones no incluidas en el Plan de Beneficios en Salud- PBS'
    ];

    $subtipos_demandas = [
        'Para glosas',
        'Para recobros NO PBS',
        'Para reclamaciones ante ECAT'
    ];

    $tipo_naturaleza = [
        'Pública',
        'Privada',
        'Mixta'
    ];
?>
<?php include ('header.php') ?>
	<div class="loader">
		<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
	</div>
    <div class="container" style="min-height: 1080px;">
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
					Este formulario es para presentar una solicitud de conciliación y/o cargar documentos a un trámite de conciliación existente. 
				</p>
			</div>
		</div>
        <div class="row">
            <div class="col-sm">
                <h4 class="section-h">Tipo de solicitud</h4>
            </div>
        </div>
        <div class="col-md-12">
            <br>
        </div>
        <div class="row">
            <div class="col-md-6">
                <a href="#" class="link" data-show="#solicitud-conciliacion" style="text-align:center; display:block;">
                    <i class="bi bi-people" style="font-size: 50px;"></i> 
                    <br> 
                    Radicar una solicitud de conciliación
                </a>
            </div>
            <div class="col-md-6">
                <a href="#" class="link" data-show="#carga-archivos" style="text-align:center; display:block;">
                    <i class="bi bi-cloud-upload" style="font-size: 50px;"></i> 
                    <br> 
                    Cargar archivos a una solicitud de conciliación existente.
                </a>
            </div>
        </div>
        <div class="col-md-12" data-version="1.0.1">
            <br>
        </div>
        <form action="conciliacion_back.php" id="form-conciliacion" enctype="multipart/form-data" method="post">
            <div class="row" id="solicitud-conciliacion" style="display:none;">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-info" role="alert">
                                        Los campos con <strong>*</strong> son de diligenciamiento obligatorio
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="row" id="datos_convocante">
                                        <div class="col-sm">
                                            <div class="row">
                                                <div class="col-sm">
                                                    <h4 class="section-h">Información del convocante (Apoderado o representante legal)</h4>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12 form-group">
                                                    <label for="tipo_convocante">* Seleccione el tipo de persona del convocante<i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Persona natural – Usuario se refiere a un individuo, Persona natural – Profesional independiente de salud (vigilado) persona que presta servicios de salud, Persona Jurídica a una empresa u organización. Defina si la persona que está formulando la solicitud de conciliación es una Persona Natural – Usuario, Persona Natural - Profesional independiente de salud (vigilado) o Persona Jurídica"></i></label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="tipo_convocante" id="tipo_convocante_1" value="1" data-required="true" data-label="Tipo de convocante">
                                                        <label class="form-check-label" for="tipo_convocante_1">Natural - usuario</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="tipo_convocante" id="tipo_convocante_2" value="2" data-required="true">
                                                        <label class="form-check-label" for="tipo_convocante_2">Natural - profesional independiente de salud (vigilado)</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="tipo_convocante" id="tipo_convocante_3" value="3" data-required="true">
                                                        <label class="form-check-label" for="tipo_convocante_3">Jurídica</label>
                                                    </div>
                                                    <span class="error small">Seleccione el tipo de persona del convocante</span>
                                                </div>
                                            </div>
                                            <div class="row" style="display: none;" data-juridico>
                                                <div class="col-sm">
                                                    <h4>Tipo de entidad</h4>
                                                </div>
                                            </div>
                                            <div class="form-row" style="display: none;" data-juridico>
                                                <div class="col-md-12 form-group">
                                                    <label for="tipo_entidad">* Seleccione el tipo de entidad</label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="tipo_entidad" id="tipo_entidad_1" value="Pública" data-required="true" data-label="Tipo de convocante">
                                                        <label class="form-check-label" for="tipo_entidad_1">Pública</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="tipo_entidad" id="tipo_entidad_2" value="Privada" data-required="true">
                                                        <label class="form-check-label" for="tipo_entidad_2">Privada</label>
                                                    </div>
                                                    <span class="error small">Seleccione el tipo de entidad del convocante</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <h4>Informenos los datos del convocante</h4>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                                    <label for="id">* Tipo de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el tipo de identificación"></i></label>
                                                    <select name="tipo_identificacion_convocante" id="tipo_identificacion_convocante" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Tipo de identificación">
                                                        <?php foreach ($tipos_documentos as $tipo) { ?>
                                                            <option value="<?=$tipo['TDID_CODI']?>"><?=$tipo['TDID_DESC']?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="error small">Seleccione el tipo de identificación del convocante</span>
                                                </div>
                                                <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                                    <label for="id_convocante">* Número de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número del documento de identificación, recuerde que si selecciona pasaporte debe ingresar números y letras de lo contrario sólo números o si el tipo de identificación es NIT ingréselo sin el guión"></i></label>
                                                    <input type="number" class="form-control" id="id_convocante" name="id_convocante" autocomplete="no"  data-required="true" data-label="Número de identificación">
                                                    <span class="error small">Ingrese el número de identificación del convocante</span>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural data-natural-vigilado>
                                                <div class="col-md-3 form-group">
                                                    <label for="nombre_convocante_1">* Primer nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer nombre"></i></label>
                                                    <input type="text" class="form-control alpha-only" id="nombre_convocante_1" name="nombre_convocante_1" autocomplete="no" data-required="true" data-label="Primer nombre convocante">
                                                    <span class="error small">Ingrese el primer nombre del convocante</span>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label for="nombre_convocante_2">Segundo nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo nombre"></i></label>
                                                    <input type="text" class="form-control alpha-only" id="nombre_convocante_2" name="nombre_convocante_2" autocomplete="no" data-label="Segundo nombre convocante">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label for="apellidos_convocante_1">* Primer apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer apellido"></i></label>
                                                    <input type="text" class="form-control alpha-only" id="apellidos_convocante_1" name="apellidos_convocante_1" autocomplete="no" data-required="true" data-label="Primer apellido convocante">
                                                    <span class="error small">Ingrese el primer apellido del convocante</span>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label for="apellidos_convocante_2">Segundo apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo apellido"></i></label>
                                                    <input type="text" class="form-control alpha-only" id="apellidos_convocante_2" name="apellidos_convocante_2" autocomplete="no" data-label="Segundo apellido convocante">
                                                </div>
                                            </div>
                                            <div class="form-row" style="display: none;" data-juridico>
                                                <div class="col-md-12 form-group">
                                                    <label for="rs_convocante">* Razón social <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la razón social"></i></label>
                                                    <input type="text" class="form-control alpha-only" id="rs" name="rs_convocante" autocomplete="no" data-required="true" data-label="Razón social convocante">
                                                    <span class="error small">Ingrese la razón social del convocante</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <hr>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm" data-natural data-natural-vigilado>
                                                    <h4>¿Dónde vive?</h4>
                                                </div>
                                                <div class="col-sm" style="display: none;" data-juridico>
                                                    <h4>¿Dónde esta ubicado?</h4>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                                    <label for="pais_convocante">* País <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el país en el que reside"></i></label>
                                                    <select name="pais_convocante" id="pais_convocante" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="País convocante">
                                                        <?php foreach ($paises as $pais) { ?>
                                                            <option value="<?=$pais['NOMBRE']?>"><?=$pais['NOMBRE']?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="error small">Seleccione el país de residencia del convocante</span>
                                                </div> 
                                                <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                                    <label for="departamento_convocante">* Departamento <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el departamento en el que reside"></i></label>
                                                    <select name="departamento_convocante" id="departamento_convocante" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Departamento convocante">
                                                        <?php foreach ($departamentos as $departamento) { ?>
                                                            <option value="<?=$departamento['DPTO_CODI']?>"><?=$departamento['DPTO_NOMB']?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="error small">Seleccione el departamento de residencia del convocante</span>
                                                </div>
                                                <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                                    <label for="ciudad_convocante">* Municipio <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el municipio en el que vive"></i></label>
                                                    <select name="ciudad_convocante" id="ciudad_convocante" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Municipio convocante"></select>
                                                    <div id="ciudad_bar_convocante" class="progress" style="display:none; height:5px;">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                                    </div>
                                                    <small id="emailHelp" class="form-text text-muted">*ANM Areas no municipalizadas</small>
                                                    <span class="error small">Seleccione el municipio de residencia del convocante</span>
                                                </div>
                                                <div class="col-md-6 form-group" data-natural data-natural-vigilado data-juridico style="display:none;">
                                                    <label for="provincia_convocante">* Ciudad / Estado / Provincia</label>
                                                    <input type="text" class="form-control" id="provincia_convocante" name="provincia_convocante" autocomplete="no" data-required="true" data-label="Ciudad / Estado / Provincia convocante">
                                                    <span class="error small">Ingrese la ciudad, estado y/o provincia de residencia del convocante</span>
                                                </div>
                                                <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                                    <label for="direccion_convocante">* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i></label>
                                                    <input type="text" class="form-control" id="direccion_convocante" name="direccion_convocante" autocomplete="no" data-required="true" data-label="Dirección convocante">
                                                    <span class="error small">Ingrese la dirección del convocante</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <hr>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <h4>Dejenos sus datos de contacto</h4>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural data-natural-vigilado data-juridico>
                                                <div class="col-md-3 form-group">
                                                    <label for="celular_convocante">* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                                    <input type="number" class="form-control" id="celular_convocante" name="celular_convocante" autocomplete="no" data-required="true" data-label="Celular convocante">
                                                    <span class="error small">Ingrese el celular del convocante</span>
                                                </div>
                                                <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                                    <label for="telefono_convocante">* Teléfono <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono fijo, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                                    <input type="number" class="form-control" id="telefono_convocante" name="telefono_convocante" autocomplete="no" data-required="true" data-label="Teléfono convocante">
                                                    <span class="error small">Ingrese el teléfono del convocante</span>
                                                </div>
                                                <div class="col-md-6" data-natural data-natural-vigilado data-juridico>
                                                    <div class="form-row">
                                                        <div class="col-md-6 form-group">
                                                            <label for="correo_convocante">* Correo electrónico <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su correo electrónico, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                                            <input type="text" class="form-control" id="correo_convocante" name="correo_convocante" autocomplete="no" data-required="true" data-label="Correo convocante">
                                                            <span class="error small">Ingrese el correo electrónico del convocante</span>
                                                        </div>
                                                        <div class="col-md-6 email-component">
                                                            <label for=""><i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el dominio de la lista desplegable si no lo encuentra digítelo."></i>&nbsp;</label>
                                                            <input type="text" class="form-control dominio" id="dominio_convocante" name="dominio_convocante" placeholder="dominio" aria-label="dominio" autocomplete="no" data-required="true" data-label="Dominio correo convocante">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-3">
                                                    <label for="valor_pretension">* Valor de la pretensión <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Ingrese el valor de la pretensión"></i></label>
                                                    <input type="number" class="form-control" id="valor_pretension" name="valor_pretension" autocomplete="no">
                                                    <span class="error small">Ingrese el valor de la pretensión</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="datos_convocado">
                                        <div class="col-sm">
                                            <div class="row">
                                                <div class="col-sm">
                                                    <h4 class="section-h">Información del convocado (Apoderado o representante legal)</h4>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12 form-group">
                                                    <label for="tipo_convocado">* Seleccione el tipo de persona del convocado<i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Persona natural – Usuario se refiere a un individuo, Persona natural – Profesional independiente de salud (vigilado) persona que presta servicios de salud, Persona Jurídica a una empresa u organización. Defina si la persona que está formulando la solicitud de conciliación es una Persona Natural – Usuario, Persona Natural - Profesional independiente de salud (vigilado) o Persona Jurídica"></i></label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="tipo_convocado" id="tipo_convocado_1" value="1" data-required="true" data-label="Tipo de convocado">
                                                        <label class="form-check-label" for="tipo_convocado_1">Natural - usuario</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="tipo_convocado" id="tipo_convocado_2" value="2" data-required="true">
                                                        <label class="form-check-label" for="tipo_convocado_2">Natural - profesional independiente de salud (vigilado)</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="tipo_convocado" id="tipo_convocado_3" value="3" data-required="true">
                                                        <label class="form-check-label" for="tipo_convocado_3">Jurídica</label>
                                                    </div>
                                                    <span class="error small">Seleccione el tipo de persona del convocado</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <h4>Informenos los datos del convocado</h4>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                                    <label for="id">* Tipo de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el tipo de identificación"></i></label>
                                                    <select name="tipo_identificacion_convocado" id="tipo_identificacion_convocado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Tipo de identificación convocado">
                                                        <?php foreach ($tipos_documentos as $tipo) { ?>
                                                            <option value="<?=$tipo['TDID_CODI']?>"><?=$tipo['TDID_DESC']?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="error small">Seleccione el tipo de identificación del convocado</span>
                                                </div>
                                                <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                                    <label for="id_convocado">* Número de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número del documento de identificación, recuerde que si selecciona pasaporte debe ingresar números y letras de lo contrario sólo números o si el tipo de identificación es NIT ingréselo sin el guión."></i></label>
                                                    <input type="number" class="form-control" id="id_convocado" name="id_convocado" autocomplete="no"  data-required="true" data-label="Número de identificación convocado">
                                                    <span class="error small">Ingrese el número de identificación del convocado</span>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural data-natural-vigilado>
                                                <div class="col-md-3 form-group">
                                                    <label for="nombre_convocado_1">* Primer nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer nombre"></i></label>
                                                    <input type="text" class="form-control alpha-only" id="nombre_convocado_1" name="nombre_convocado_1" autocomplete="no" data-required="true" data-label="Primer nombre convocado">
                                                    <span class="error small">Ingrese el primer nombre del convocado</span>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label for="nombre_convocado_2">Segundo nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo nombre"></i></label>
                                                    <input type="text" class="form-control alpha-only" id="nombre_convocado_2" name="nombre_convocado_2" autocomplete="no" data-label="Segundo nombre convocado">
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label for="apellidos_convocado_1">* Primer apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer apellido"></i></label>
                                                    <input type="text" class="form-control alpha-only" id="apellidos_convocado_1" name="apellidos_convocado_1" autocomplete="no" data-required="true" data-label="Primer apellido convocado">
                                                    <span class="error small">Ingrese el primer apellido del convocado</span>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label for="apellidos_convocado_2">Segundo apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo apellido"></i></label>
                                                    <input type="text" class="form-control alpha-only" id="apellidos_convocado_2" name="apellidos_convocado_2" autocomplete="no" data-label="Segundo apellido convocado">
                                                </div>
                                            </div>
                                            <div class="form-row" style="display: none;" data-juridico>
                                                <div class="col-md-12 form-group">
                                                    <label for="rs_convocado">* Razón social <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la razón social"></i></label>
                                                    <input type="text" class="form-control alpha-only" id="rs" name="rs_convocado" autocomplete="no" data-required="true" data-label="Razón social convocado">
                                                    <span class="error small">Ingrese la razón social del convocado</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <hr>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm" data-natural data-natural-vigilado>
                                                    <h4>¿Dónde vive su convocado?</h4>
                                                </div>
                                                <div class="col-sm" style="display: none;" data-juridico>
                                                    <h4>¿Dónde esta ubicado su convocado?</h4>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                                    <label for="pais_convocado">* País <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el país en el que reside"></i></label>
                                                    <select name="pais_convocado" id="pais_convocado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="País convocado">
                                                        <?php foreach ($paises as $pais) { ?>
                                                            <option value="<?=$pais['NOMBRE']?>"><?=$pais['NOMBRE']?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="error small">Seleccione el país de residencia del convocado</span>
                                                </div> 
                                                <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                                    <label for="departamento_convocado">* Departamento <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el departamento en el que reside"></i></label>
                                                    <select name="departamento_convocado" id="departamento_convocado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Departamento convocado">
                                                        <?php foreach ($departamentos as $departamento) { ?>
                                                            <option value="<?=$departamento['DPTO_CODI']?>"><?=$departamento['DPTO_NOMB']?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="error small">Seleccione el departamento de residencia del convocado</span>
                                                </div>
                                                <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                                    <label for="ciudad_convocado">* Municipio <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el municipio en el que vive"></i></label>
                                                    <select name="ciudad_convocado" id="ciudad_convocado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Municipio convocado"></select>
                                                    <div id="ciudad_bar_convocado" class="progress" style="display:none; height:5px;">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                                    </div>
                                                    <small id="emailHelp" class="form-text text-muted">*ANM Areas no municipalizadas</small>
                                                    <span class="error small">Seleccione el municipio de residencia del convocado</span>
                                                </div>
                                                <div class="col-md-6 form-group" data-natural data-natural-vigilado data-juridico style="display:none;">
                                                    <label for="provincia_convocado">* Ciudad / Estado / Provincia</label>
                                                    <input type="text" class="form-control" id="provincia_convocado" name="provincia_convocado" autocomplete="no" data-required="true" data-label="Ciudad / Estado / Provincia convocado">
                                                    <span class="error small">Ingrese la ciudad, estado y/o provincia de residencia del convocado</span>
                                                </div>
                                                <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                                    <label for="direccion_convocado">* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de residencia"></i></label>
                                                    <input type="text" class="form-control" id="direccion_convocado" name="direccion_convocado" autocomplete="no" data-required="true" data-label="Dirección convocado">
                                                    <span class="error small">Ingrese la dirección del convocado</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <hr>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <h4>Dejenos los datos de contacto de su convocado</h4>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural data-natural-vigilado data-juridico>
                                                <div class="col-md-3 form-group">
                                                    <label for="celular_convocado">* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número de celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                                    <input type="number" class="form-control" id="celular_convocado" name="celular_convocado" autocomplete="no" data-required="true" data-label="Celular convocado">
                                                    <span class="error small">Ingrese el celular del convocado</span>
                                                </div>
                                                <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                                    <label for="telefono_convocado">* Teléfono <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono fijo, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                                    <input type="number" class="form-control" id="telefono_convocado" name="telefono_convocado" autocomplete="no" data-required="true" data-label="Teléfono convocado">
                                                    <span class="error small">Ingrese el teléfono del convocado</span>
                                                </div>
                                                <div class="col-md-6" data-natural data-natural-vigilado data-juridico>
                                                    <div class="form-row">
                                                        <div class="col-md-6 form-group">
                                                            <label for="correo_convocado">* Correo electrónico <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su correo electrónico, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                                            <input type="text" class="form-control" id="correo_convocado" name="correo_convocado" autocomplete="no" data-required="true" data-label="Correo convocado">
                                                            <span class="error small">Ingrese el correo electrónico del convocado</span>
                                                        </div>
                                                        <div class="col-md-6 email-component">
                                                            <label for=""><i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el dominio de la lista desplegable si no lo encuentra digítelo."></i>&nbsp;</label>
                                                            <input type="text" class="form-control dominio" id="dominio_convocado" name="dominio_convocado" placeholder="dominio" aria-label="dominio" autocomplete="no" data-required="true" data-label="Dominio correo convocado">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <h4>Escriba una descripción de la solicitud</h4>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label for="tipo_solicitud">* Tipo solicitud <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite brevemente los detalles de la solicitud"></i></label>
                                            <select name="tipo_solicitud" class="form-control" id="tipo_solicitud" data-required="true" title="Seleccionar" data-label="Tipo de solicitud">
                                            </select>
                                            <span class="error small">Seleccione un tipo de solicitud</span>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 form-group limited-textarea">
                                            <label for="comentarios_2">* Describa brevemente los detalles de la solicitud <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite brevemente los detalles de la solicitud"></i></label>
                                            <textarea id="comentarios_2" name="comentarios" class="form-control" rows="5" autocomplete="no" data-required="true" maxlength="5000" data-label="Detalle de la solicitud"></textarea>
                                            <span class="size" data-max="5000">0/5000</span>
					                        <span class="error small">Ingrese el texto que describe los detalles de la solicitud, máximo 5000 caracteres</span>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label for="file_0">* Contenido de su solicitud, adjunte el archivo de la solicitud  <a data-natural href="./formatos/MODELO SOLICITUD DE CONCILIACION 1.docx"><i class="bi bi-file-word"></i> Formato guía</a><a data-natural-vigilado style="display:none;" href="./formatos/MODELO SOLICITUD DE CONCILIACION 2.docx"><i class="bi bi-file-word"></i> Formato guía</a><a data-juridico style="display:none;" href="./formatos/MODELO SOLICITUD DE CONCILIACION 3.docx"><i class="bi bi-file-word"></i> Formato guía</a></label><br>
                                            <input type="file" id="file_0" name="userfile[0]" accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document" data-required="true" data-label="Contenido de la solicitud">
                                            <input type="hidden" name="tipo_documento[0]" value="Contenido de su solicitud, adjunte el archivo de la solicitud">
                                            <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
					                        <span class="error small">Seleccione un archivo</span>
                                        </div>
                                    </div>
                                    <div class="row" data-natural data-natural-vigilado data-juridico style="display:none;">
                                        <div class="col-md-12"><hr></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 anexos" id="anexos_conciliaciones">
                                            <div class="row">
                                                <div class="col-sm">
                                                    <h4>Agregue los anexos o soportes de la solicitud de conciliación</h4>
                                                    <p>
                                                    Donde aparece el icono adjuntar soportes, suba los soportes que considere pueden servir para su denuncia si estos son muy pesados los puede comprimir, puede adjuntar archivos con un tamaño de 20 MB.
                                                    A continuación, se relacionan los requisitos de la solicitud de conciliación que debe anexar.
                                                    Formatos validos: (pdf, xls, xlsx).
                                                    <br><br>
                                                    Los campos marcados con asterisco * deben ser cargados obligatoriamente; y que los que No tienen asterisco, * , no son obligatorios o los puede llevar antes de la audiencia de Conciliación.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural data-natural-vigilado data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_3">* Caratula de solicitud</label><br>
                                                    <input type="file" id="file_3" name="userfile[3]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-required="true" data-label="Caratula de solicitud">
                                                    <input type="hidden" name="tipo_documento[3]" value="Caratula de solicitud">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
					                                <span class="error small">Seleccione un archivo</span>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_1">Traslado</label><br>
                                                    <input type="file" id="file_1" name="userfile[1]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Traslado">
                                                    <input type="hidden" name="tipo_documento[1]" value="Traslado">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural-vigilado style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_4">Carta otorgamiento poder Conciliación extrajudicial en derecho</label><br>
                                                    <input type="file" id="file_4" name="userfile[4]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Carta otorgamiento poder Conciliación extrajudicial en derecho">
                                                    <input type="hidden" name="tipo_documento[4]" value="Carta otorgamiento poder Conciliación extrajudicial en derecho">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural-vigilado style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_5">Documento de representación - Certificado de existencia y representación legal</label><br>
                                                    <input type="file" id="file_5" name="userfile[5]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Carta otorgamiento poder Conciliación extrajudicial en derecho">
                                                    <input type="hidden" name="tipo_documento[5]" value="Carta otorgamiento poder Conciliación extrajudicial en derecho">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_10">Poder a abogado para conciliar</label><br>
                                                    <input type="file" id="file_10" name="userfile[10]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Poder a abogado para conciliar">
                                                    <input type="hidden" name="tipo_documento[10]" value="Poder a abogado para conciliar">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_11">* Documento de representación legal</label><br>
                                                    <input type="file" id="file_11" name="userfile[11]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-required="true" data-label="Carta otorgamiento poder Conciliación extrajudicial en derecho">
                                                    <input type="hidden" name="tipo_documento[11]" value="Carta otorgamiento poder Conciliación extrajudicial en derecho">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                    <span class="error small">Seleccione un archivo</span>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural-vigilado data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_6">* Certificación financiera</label><br>
                                                    <input type="file" id="file_6" name="userfile[6]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-required="true" data-label="Certificación financiera">
                                                    <input type="hidden" name="tipo_documento[6]" value="Certificación financiera">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                    <span class="error small">Seleccione un archivo</span>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_12">* Acta o certificación suscrita por el secretario técnico del comité de defensa judicial y conciliación. (obligatoria para entidades públicas)</label><br>
                                                    <input type="file" id="file_12" name="userfile[12]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-required="true" data-label="Acta o certificación suscrita por el secretario técnico del comité de defensa judicial y conciliación.">
                                                    <input type="hidden" name="tipo_documento[12]" value="Acta o certificación suscrita por el secretario técnico del comité de defensa judicial y conciliación.">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                    <span class="error small">Seleccione un archivo</span>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12">
                                                    <div class="form-group form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="file_12_toggle_required" data-toggle-required="file_12" name="file_12_toggle_required">
                                                        <label class="form-check-label" for="file_12_toggle_required">Declaro bajo la gravedad de juramento no estar obligado a conformarlo y por ende, no tenerlo establecido</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural-vigilado style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_7">* Copia en medio magnético de la facturación pretendía en conciliación</label><br>
                                                    <input type="file" id="file_7" name="userfile[7]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-required="true" data-label="Copia en medio magnético de la facturación pretendía en conciliación">
                                                    <input type="hidden" name="tipo_documento[7]" value="Copia en medio magnético de la facturación pretendía en conciliación">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                    <span class="error small">Seleccione un archivo</span>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural-vigilado style="display:none;">
                                                <div class="col-md-12 form-group" id="certificado_de_habilitacion">
                                                    <label for="file_8">* Copia del certificado de habilitación para prestar servicios de salud</label><br>
                                                    <input type="file" id="file_8" name="userfile[8]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-required="true" data-label="Copia del certificado de habilitación para prestar servicios de salud">
                                                    <input type="hidden" name="tipo_documento[8]" value="Copia del certificado de habilitación para prestar servicios de salud">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                    <span class="error small">Seleccione un archivo</span>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural-vigilado data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_9">* Constancia de traslado</label><br>
                                                    <input type="file" id="file_9" name="userfile[9]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-required="true" data-label="Constancia de traslado">
                                                    <input type="hidden" name="tipo_documento[9]" value="Constancia de traslado">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                    <span class="error small">Seleccione un archivo</span>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_13">* Copia del certificado de habilitación para prestar servicios de salud</label><br>
                                                    <input type="file" id="file_13" name="userfile[13]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-required="true" data-label="Copia del certificado de habilitación para prestar servicios de salud">
                                                    <input type="hidden" name="tipo_documento[13]" value="Copia del certificado de habilitación para prestar servicios de salud">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                    <span class="error small">Seleccione un archivo</span>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_14">Cargue de las facturas en la plataforma pisis</label><br>
                                                    <input type="file" id="file_14" name="userfile[14]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Cargue de las facturas en la plataforma pisis">
                                                    <input type="hidden" name="tipo_documento[14]" value="Cargue de las facturas en la plataforma pisis">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_15">Copia del contrato que respalda la obligación</label><br>
                                                    <input type="file" id="file_15" name="userfile[15]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Copia del contrato que respalda la obligación">
                                                    <input type="hidden" name="tipo_documento[15]" value="Copia del contrato que respalda la obligación">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_16">Copia del acta de liquidación del contrato</label><br>
                                                    <input type="file" id="file_16" name="userfile[16]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Copia del acta de liquidación del contrato">
                                                    <input type="hidden" name="tipo_documento[16]" value="Copia del acta de liquidación del contrato">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_17">Copia en medio magnético de la facturación pretendida en conciliación en formato excel</label><br>
                                                    <input type="file" id="file_17" name="userfile[17]" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" data-label="Copia en medio magnético de la facturación pretendida en conciliación en formato excel">
                                                    <input type="hidden" name="tipo_documento[17]" value="Copia en medio magnético de la facturación pretendida en conciliación en formato excel">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_2">Otros</label><br>
                                                    <input type="file" id="file_2" name="userfile[2]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Otros">
                                                    <input type="hidden" name="tipo_documento[2]" value="Otros">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                <input class="form-check-input" type="checkbox" name="medio[]" id="medio_1" value="Correo electrónico" data-required="true" checked data-label="Medio de envio de información">
                                <label class="form-check-label" for="medio_1">Correo electrónico</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="medio[]" id="medio_2" value="Dirección de correspondencia" data-required="true">
                                <label class="form-check-label" for="medio_2">Dirección de correspondencia</label>
                            </div>
					        <span class="error small">Seleccione al menos un medio sobre el cual desea recibir información de su solicitud</span>
                        </div>
                    </div>
                    <div class="row">
                        <br>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 form-group" style="text-align: justify">
                            Al hacer clic en el botón enviar, usted acepta la remisión de la solicitud de conciliación a la entidad Superintendencia Nacional de Salud. Sus datos serán recolectados y tratados conforme con la <a href="https://www.supersalud.gov.co/es-co/transparencia-y-acceso-a-la-informacion-publica/informaci%C3%B3n-de-la-entidad/politicas-de-privacidad-y-condiciones-de-uso" target="_blank">Política de Tratamiento de Datos.</a>
                            <br><br>
                            En caso de que la solicitud de información sea de naturaleza de identidad reservada, deberá efectuar el respectivo trámite ante la Procuraduría General de la Nación, haciendo clic en el siguiente link: <a href="https://sedeelectronica.procuraduria.gov.co/PQRDSF/solicitud-de-informacion-con-identificacion-reservada/?typeform=infores" target="_blank">https://sedeelectronica.procuraduria.gov.co/PQRDSF/solicitud-de-informacion-con-identificacion-reservada/?typeform=infores</a>
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
                            <input type="hidden" id="tipoSolicitud" name="tipoSolicitud" value="Trámite de conciliación"/>
                            <input type="hidden" id="tipoUsuario" name="tipoUsuario" value=""/>
                            <input type="hidden" id="adjuntosSubidos" name="adjuntosSubidos" value=""/>
                            <input type="hidden" name="pais" value="170">
                            <input type="button" id="enviar-conciliacion" class="btn btn-round btn-high" value="Enviar">
                            <input type="button" id="borrar" class="btn btn-round btn-middle" value="Borrar">
					        <a href="https://www.supersalud.gov.co/es-co/Paginas/Protecci%C3%B3n%20al%20Usuario/pqrd.aspx" class="btn btn-round btn-middle">Volver</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form action="conciliacion_anexo_back.php" id="form-carga" enctype="multipart/form-data" method="post">
            <div class="row" id="carga-archivos" style="display:none;">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-sm">
                                    <div class="alert alert-info" role="alert">
                                        Los campos con <strong>*</strong> son de diligenciamiento obligatorio
                                    </div>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h4 class="section-h">Aquí puedes adjuntar archivos a la solicitud de conciliación existente</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <fieldset>
                                        <label for="calidad_sujeto_procesal">* Identifique la calidad del sujeto procesal <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione la calidad del sujeto procesal"></i></label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="calidad_sujeto_procesal" id="calidad_sujeto_procesal_1" value="Convocante" data-required="true">
                                            <label class="form-check-label" for="calidad_sujeto_procesal_1">Convocante</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="calidad_sujeto_procesal" id="calidad_sujeto_procesal_2" value="Convocado" data-required="true">
                                            <label class="form-check-label" for="calidad_sujeto_procesal_2">Convocado</label>
                                        </div>
                                        <span class="error small">Seleccione la calidad del sujeto procesal</span>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="form-row">
                                
                                <div class="col-md-6 form-group">
                                    <label for="">* Ingrese el número de solicitud de conciliación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número de radicado"></i></label>
                                    <input name="radicado" id="radicado" type="text" class="form-control" data-required="true">
                                    <span class="error small">Ingrese el número de solicitud de conciliación</span>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">&nbsp;</label>
                                    <p style="margin-top: 5px;" class="form-control-static" id="res-expediente"></p>
                                    <input type="hidden" id="expediente" name="expediente">
                                </div>
                            </div>
                            <div id="sujeto" class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h4 class="section-h" data-convocante>Información del convocante</h4>
                                            <h4 class="section-h" data-convocado style="display:none;">Información del convocado</h4>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label for="tipo_sujeto">* Seleccione el tipo de persona <span data-convocante>del convocante </span><span style="display:none;" data-convocado>del convocado </span><i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el tipo de persona."></i></label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_sujeto" id="tipo_sujeto_1" value="1" data-required="true" data-label="Tipo de convocante">
                                                <label class="form-check-label" for="tipo_sujeto_1">Natural - usuario</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_sujeto" id="tipo_sujeto_2" value="2" data-required="true">
                                                <label class="form-check-label" for="tipo_sujeto_2">Natural - profesional independiente de salud (vigilado)</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_sujeto" id="tipo_sujeto_3" value="3" data-required="true">
                                                <label class="form-check-label" for="tipo_sujeto_3">Jurídica</label>
                                            </div>
                                            <span class="error small">Seleccione el tipo de persona del sujeto procesal</span>
                                        </div>
                                    </div>
                                    <div class="form-row" style="display: none;" data-juridico>
                                        <div class="col-md-12 form-group">
                                            <label for="tipo_entidad_sujeto">* Seleccione el tipo de entidad</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_entidad_sujeto" id="tipo_entidad_sujeto_1" value="Pública" data-required="true" data-label="Tipo de convocante">
                                                <label class="form-check-label" for="tipo_entidad_sujeto_1">Pública</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_entidad_sujeto" id="tipo_entidad_sujeto_2" value="Privada" data-required="true">
                                                <label class="form-check-label" for="tipo_entidad_sujeto_2">Privada</label>
                                            </div>
                                            <span class="error small">Seleccione el tipo de entidad del sujeto procesal</span>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                            <label for="id">* Tipo de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el tipo de identificación"></i></label>
                                            <select name="tipo_identificacion_sujeto" id="tipo_identificacion_sujeto" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Tipo de identificación apoderado">
                                                <?php foreach ($tipos_documentos as $tipo) { ?>
                                                    <option value="<?=$tipo['TDID_CODI']?>"><?=$tipo['TDID_DESC']?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="error small">Seleccione el tipo de identificación del sujeto procesal</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                            <label for="id_sujeto">* Número de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número del documento de identificación, recuerde que si selecciona pasaporte debe ingresar números y letras de lo contrario sólo números o si el tipo de identificación es NIT ingréselo sin el guión"></i></label>
                                            <input type="number" class="form-control" id="id_sujeto" name="id_sujeto" autocomplete="no"  data-required="true" data-label="Número de identificación">
                                            <span class="error small">Ingrese el número de identificación del sujeto procesal</span>
                                        </div>
                                    </div>
                                    <div class="form-row" data-natural data-natural-vigilado>
                                        <div class="col-md-3 form-group">
                                            <label for="nombre_sujeto_1">* Primer nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer nombre"></i></label>
                                            <input type="text" class="form-control alpha-only" id="nombre_sujeto_1" name="nombre_sujeto_1" autocomplete="no" data-required="true" data-label="Primer nombre">
                                            <span class="error small">Ingrese el primer nombre del sujeto procesal</span>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="nombre_sujeto_2">Segundo nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo nombre"></i></label>
                                            <input type="text" class="form-control alpha-only" id="nombre_sujeto_2" name="nombre_sujeto_2" autocomplete="no">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="apellidos_sujeto_1">* Primer apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer apellido"></i></label>
                                            <input type="text" class="form-control alpha-only" id="apellidos_sujeto_1" name="apellidos_sujeto_1" autocomplete="no" data-required="true" data-label="Primer apellido">
                                            <span class="error small">Ingrese el primer apellido del sujeto procesal</span>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="apellidos_sujeto_2">Segundo apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo apellido"></i></label>
                                            <input type="text" class="form-control alpha-only" id="apellidos_sujeto_2" name="apellidos_sujeto_2" autocomplete="no">
                                        </div>
                                    </div>
                                    <div class="form-row" style="display: none;" data-juridico>
                                        <div class="col-md-12 form-group">
                                            <label for="rs_sujeto">* Razón social <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la razón social"></i></label>
                                            <input type="text" class="form-control alpha-only" id="rs" name="rs_sujeto" autocomplete="no" data-required="true" data-label="Razón social">
                                            <span class="error small">Ingrese la razón social del sujeto procesal</span>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                            <label for="pais_sujeto">* País <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el país en el que reside"></i></label>
                                            <select name="pais_sujeto" id="pais_sujeto" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="País">
                                                <?php foreach ($paises as $pais) { ?>
                                                    <option value="<?=$pais['NOMBRE']?>"><?=$pais['NOMBRE']?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="error small">Seleccione el país de residencia del sujeto procesal</span>
                                        </div> 
                                        <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                            <label for="departamento_sujeto">* Departamento <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el departamento en el que reside"></i></label>
                                            <select name="departamento_sujeto" id="departamento_sujeto" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Departamento apoderado">
                                                <?php foreach ($departamentos as $departamento) { ?>
                                                    <option value="<?=$departamento['DPTO_CODI']?>"><?=$departamento['DPTO_NOMB']?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="error small">Seleccione el departamento de residencia del sujeto procesal</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                            <label for="ciudad_sujeto">* Municipio <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el municipio en el que vive"></i></label>
                                            <select name="ciudad_sujeto" id="ciudad_sujeto" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Municipio apoderado"></select>
                                            <div id="ciudad_bar_sujeto" class="progress" style="display:none; height:5px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                            </div>
                                            <small id="emailHelp" class="form-text text-muted">*ANM Areas no municipalizadas</small>
                                            <span class="error small">Seleccione el municipio de residencia del sujeto procesal</span>
                                        </div>
                                        <div class="col-md-6 form-group" data-natural data-natural-vigilado data-juridico style="display:none;">
                                            <label for="provincia_sujeto">* Ciudad / Estado / Provincia</label>
                                            <input type="text" class="form-control" id="provincia_sujeto" name="provincia_sujeto" autocomplete="no" data-required="true" data-label="Ciudad / Estado / Provincia apoderado">
                                            <span class="error small">Ingrese la ciudad, estado y/o provincia de residencia del sujeto procesal</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                            <label for="direccion_sujeto">* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i></label>
                                            <input type="text" class="form-control" id="direccion_sujeto" name="direccion_sujeto" autocomplete="no" data-required="true" data-label="Dirección apoderado">
                                            <span class="error small">Ingrese la dirección del sujeto procesal</span>
                                        </div>
                                    </div>
                                    <div class="form-row" data-natural data-natural-vigilado data-juridico>
                                        <div class="col-md-3 form-group">
                                            <label for="celular_sujeto">* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                            <input type="number" class="form-control" id="celular_sujeto" name="celular_sujeto" autocomplete="no" data-required="true" data-label="Celular">
                                            <span class="error small">Ingrese el celular del sujeto procesal</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-natural-vigilado data-juridico>
                                            <label for="telefono_sujeto">* Teléfono fijo <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono fijo o celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                            <input type="number" class="form-control" id="telefono_sujeto" name="telefono_sujeto" autocomplete="no" data-required="true" data-label="Teléfono fijo o celular">
                                            <span class="error small">Ingrese un teléfono fijo o celular adicional del sujeto procesal</span>
                                        </div>
                                        <div class="col-md-6" data-natural data-natural-vigilado data-juridico>
                                            <div class="form-row">
                                                <div class="col-md-6 form-group">
                                                    <label for="correo_sujeto">* Correo electrónico <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su correo electrónico, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                                    <input type="text" class="form-control" id="correo_sujeto" name="correo_sujeto" autocomplete="no" data-required="true" data-label="Correo">
                                                    <span class="error small">Ingrese el correo electrónico del sujeto procesal</span>
                                                </div>
                                                <div class="col-md-6 email-component">
                                                    <label for=""><i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el dominio de la lista desplegable si no lo encuentra digítelo."></i>&nbsp;</label>
                                                    <input type="text" class="form-control dominio" id="dominio_sujeto" name="dominio_sujeto" placeholder="dominio" aria-label="dominio" autocomplete="no" data-required="true" data-label="Dominio correo">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <h4 class="section-h">Describa brevemente que actuación y/o documento va a radicar</h4>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 form-group" data-type="file" data-rel="0">
                                            <label for="tipo_documento[0]">* Seleccione el tipo del documento <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el tipo del documento que va a anexar"></i></label>
                                            <select name="tipo_documento[0]" id="tipo_documento[0]" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true">
                                                <option value="Subsanación">Subsanación</option>
                                                <option value="Respuesta requerimiento">Respuesta requerimiento</option>
                                                <option value="Solicitud de suspensión y/o aplazamiento">Solicitud de suspensión y/o aplazamiento </option>
                                                <option value="Otros">Otros</option>
                                            </select>
                                            <span class="error small">Seleccione un tipo de documento</span>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="file_0">* Anexo <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Para adjuntar documentos haga clic sobre la palabra Examinar. Adjuntar archivo máximo disponible M.B formatos validos: (tif, tiff, jpeg, pdf, docx, txt, jpg, gif, xls, xlsx, doc, png, msg, Zip, m4a, mp3, mp4.)"></i></label><br>
                                            <input type="file" id="file_0" name="userfile[0]" data-required="true" data-label="Anexo" accept="application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/msword, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="margin-top:4px;">
                                            <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                            <span class="error small">Seleccione un archivo</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 anexos">
                                            <div class="form-row" data-natural data-natural-vigilado data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_1">Caratula de solicitud</label><br>
                                                    <input type="file" id="file_1" name="userfile[1]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Caratula de solicitud">
                                                    <input type="hidden" name="tipo_documento[1]" value="Caratula de solicitud">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_2">Traslado</label><br>
                                                    <input type="file" id="file_2" name="userfile[2]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Traslado">
                                                    <input type="hidden" name="tipo_documento[2]" value="Traslado">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_3">Anexos</label><br>
                                                    <input type="file" id="file_3" name="userfile[3]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Anexos">
                                                    <input type="hidden" name="tipo_documento[3]" value="Anexos">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural-vigilado style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_4">Carta otorgamiento poder Conciliación extrajudicial en derecho</label><br>
                                                    <input type="file" id="file_4" name="userfile[4]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Carta otorgamiento poder Conciliación extrajudicial en derecho">
                                                    <input type="hidden" name="tipo_documento[4]" value="Carta otorgamiento poder Conciliación extrajudicial en derecho">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural-vigilado style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_4">Carta otorgamiento poder Conciliación extrajudicial en derecho</label><br>
                                                    <input type="file" id="file_4" name="userfile[4]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Carta otorgamiento poder Conciliación extrajudicial en derecho">
                                                    <input type="hidden" name="tipo_documento[4]" value="Carta otorgamiento poder Conciliación extrajudicial en derecho">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural-vigilado style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_5">Documento de representación – Certificado de existencia y representante legal</label><br>
                                                    <input type="file" id="file_5" name="userfile[5]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Documento de representación – Certificado de existencia y representante legal">
                                                    <input type="hidden" name="tipo_documento[5]" value="Documento de representación – Certificado de existencia y representante legal">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural-vigilado data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_6">Certificación financiera</label><br>
                                                    <input type="file" id="file_6" name="userfile[6]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Certificación financiera">
                                                    <input type="hidden" name="tipo_documento[6]" value="Certificación financiera">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural-vigilado style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_7">Copia en medio magnético de la facturación pretendía en conciliación</label><br>
                                                    <input type="file" id="file_7" name="userfile[7]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Copia en medio magnético de la facturación pretendía en conciliación">
                                                    <input type="hidden" name="tipo_documento[7]" value="Copia en medio magnético de la facturación pretendía en conciliación">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural-vigilado style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_8">Copia del certificado de habilitación para prestar servicios de salud</label><br>
                                                    <input type="file" id="file_8" name="userfile[8]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Copia del certificado de habilitación para prestar servicios de salud">
                                                    <input type="hidden" name="tipo_documento[8]" value="Copia del certificado de habilitación para prestar servicios de salud">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural-vigilado style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_9">Constancia de traslado</label><br>
                                                    <input type="file" id="file_9" name="userfile[9]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Constancia de traslado">
                                                    <input type="hidden" name="tipo_documento[9]" value="Constancia de traslado">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-natural-vigilado style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_10">Constancia de traslado</label><br>
                                                    <input type="file" id="file_10" name="userfile[10]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Constancia de traslado">
                                                    <input type="hidden" name="tipo_documento[10]" value="Constancia de traslado">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_11">Poder a abogado para conciliar</label><br>
                                                    <input type="file" id="file_11" name="userfile[11]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Poder a abogado para conciliar">
                                                    <input type="hidden" name="tipo_documento[11]" value="Poder a abogado para conciliar">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_12">Documento de representación legal</label><br>
                                                    <input type="file" id="file_12" name="userfile[12]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Documento de representación legal">
                                                    <input type="hidden" name="tipo_documento[12]" value="Documento de representación legal">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_13">* Acta o certificación suscrita por el secretario técnico del comité de defensa judicial y conciliación</label><br>
                                                    <input type="file" id="file_13" name="userfile[13]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-required="true" data-label="Acta o certificación suscrita por el secretario técnico del comité de defensa judicial y conciliación">
                                                    <input type="hidden" name="tipo_documento[13]" value="Acta o certificación suscrita por el secretario técnico del comité de defensa judicial y conciliación">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                    <span class="error small">Seleccione un archivo</span>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12">
                                                    <div class="form-group form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="file_13_toggle_required" data-toggle-required="file_13" name="file_13_toggle_required">
                                                        <label class="form-check-label" for="file_13_toggle_required">Declaro bajo la gravedad de juramento no estar obligado a conformarlo y por ende, no tenerlo establecido</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_14">Constancia de traslado</label><br>
                                                    <input type="file" id="file_14" name="userfile[14]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Constancia de traslado">
                                                    <input type="hidden" name="tipo_documento[14]" value="Constancia de traslado">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_15">Copia del certificado de habilitación para prestar servicios de salud</label><br>
                                                    <input type="file" id="file_15" name="userfile[15]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Copia del certificado de habilitación para prestar servicios de salud">
                                                    <input type="hidden" name="tipo_documento[15]" value="Copia del certificado de habilitación para prestar servicios de salud">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_15">Cargue de las facturas en la plataforma pisis</label><br>
                                                    <input type="file" id="file_15" name="userfile[15]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Cargue de las facturas en la plataforma pisis">
                                                    <input type="hidden" name="tipo_documento[15]" value="Cargue de las facturas en la plataforma pisis">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_16">Copia del contrato que respalda la obligación</label><br>
                                                    <input type="file" id="file_16" name="userfile[16]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Copia del contrato que respalda la obligación">
                                                    <input type="hidden" name="tipo_documento[16]" value="Copia del contrato que respalda la obligación">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_17">Copia del acta de liquidación del contrato</label><br>
                                                    <input type="file" id="file_17" name="userfile[17]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Copia del acta de liquidación del contrato">
                                                    <input type="hidden" name="tipo_documento[17]" value="Copia del acta de liquidación del contrato">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_18">Copia en medio magnético de la facturación pretendida en conciliación en formato Excel</label><br>
                                                    <input type="file" id="file_18" name="userfile[18]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Copia en medio magnético de la facturación pretendida en conciliación en formato Excel">
                                                    <input type="hidden" name="tipo_documento[18]" value="Copia en medio magnético de la facturación pretendida en conciliación en formato Excel">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="form-row" data-juridico style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_19">Otros</label><br>
                                                    <input type="file" id="file_19" name="userfile[19]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png" data-label="Otros">
                                                    <input type="hidden" name="tipo_documento[19]" value="Otros">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <altcha-widget
                                                id="altcha-widget-1"
                                                challengeurl="../altcha_challenge.php"
                                            ></altcha-widget>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <br>
                                            <input type="hidden" id="as" name="as" value="1">
                                            <input type="hidden" id="tipoSolicitud" name="tipoSolicitud" value="Demanda jurisdiccional"/>
                                            <input type="hidden" id="tipoUsuario" name="tipoUsuario" value=""/>
                                            <input type="hidden" id="adjuntosSubidos" name="adjuntosSubidos" value=""/>
                                            <input type="hidden" name="pais" value="170">
                                            <input type="button" class="btn btn-round btn-high"  id="enviar-conciliacion-anexo" value="Enviar">
                                            <input type="button" id="borrar-anexos" class="btn btn-round btn-middle" value="Borrar">
					                        <a href="https://www.supersalud.gov.co/es-co/Paginas/Protecci%C3%B3n%20al%20Usuario/pqrd.aspx" class="btn btn-round btn-middle">Volver</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="form-row">
            <div class="col-md-12">
                <br><br>
            </div>
        </div>
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
    
    <div class="modal fade" id="modalAnexos" tabindex="-1" aria-labelledby="modalAnexosLabel" aria-hidden="true">
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
                    <button type="button" class="btn btn-round btn-high" id="submit-jurisdiccional">Enviar</button>
                    <button type="button" class="btn btn-round btn-middle" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modalAnexosConcilaciones" tabindex="-1" aria-labelledby="modalAnexosLabel" aria-hidden="true">
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
                    <button type="button" class="btn btn-round btn-high" id="submit-conciliaciones">Enviar</button>
                    <button type="button" class="btn btn-round btn-middle" data-dismiss="modal">Cancelar</button>
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
    <script>

        $(function() {
            
            $('[data-toggle="tooltip"]').tooltip()

            $(".alpha-only").on("keydown", function(event){
                // Allow controls such as backspace, tab etc.
                var arr = [8,9,16,17,20,32,35,36,37,38,39,40,45,46, 192];

                // Allow letters
                for(var i = 65; i <= 90; i++){
                    arr.push(i);
                }

                // Prevent default if not in array
                if(jQuery.inArray(event.which, arr) === -1){
                    event.preventDefault();
                }
            });

            $('body').delegate('input[type="number"]', 'keypress', function(event){
                if ((event.which != 8 && event.which != 9) && isNaN(String.fromCharCode(event.which))){
                    event.preventDefault(); //stop character from entering input
                }
            });

            $('a.link').on('click', function(e) {
                if(!$(this).hasClass('disabled'))
                {
                    $('#demanda-jurisdiccional').hide();
                    $('a.link').addClass('disabled');
                    $(this).removeClass('disabled');
                    $($(this).data('show')).show();
                }
                e.preventDefault();
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
						$(element).removeAttr('data-required');
					});
				}
			});

            $('input[data-toggle-required]').on('change', function(e) {
                var selector = $(this).data('toggle-required');
                var anexos = $(this).closest('.anexos');
                var label = anexos.find('label[for="'+selector+'"]').text();

                if($(this).is(':checked'))
				{
                    anexos.find('label[for="'+selector+'"]').text(label.substring(2));
                    anexos.find('input[id="'+selector+'"]').removeAttr('data-required');
                } else {
                    anexos.find('label[for="'+selector+'"]').text('* '+label);
                    anexos.find('input[id="'+selector+'"]').attr('data-required', 'true');
                }
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
            
			function isEmail(email) {
				var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				return regex.test(email);
			}

			$('select:not(.ignore)').selectpicker();

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

            $('body').delegate('input[type="file"]', 'change', function(e) {
                if(this.files.length > 0)
                {
                    var size = this.files[0].size / 1000;
                    if(size > 20480)
                    {
                        $(this).val(null);
                        alert('Solo se permite archivos de máximo 20 MB');
                    }

                    const file = this.files[0];
                    var objectURL = URL.createObjectURL(file);
                    var link = $(this).closest('div').find('a.descargar');
                    link.show();

                    link[0].download = file.name; // this name is used when the user downloads the file
                    link[0].href = objectURL;
                } else {
                    var link = $(this).closest('div').find('a.descargar');
                    link.hide();
                }
			});

            //CONCILIACION             
            $('#borrar').on('click', function(e) {
				$("#form-conciliacion")[0].reset();
				$('select').each(function(e) {
					$(this).val('').trigger('change');
                    $('#ciudad_bar_convocante').hide();
                    $('#ciudad_bar_convocado').hide();
				});
			});

            var tipo_identificacion_convocante = $('select[name="tipo_identificacion_convocante"]').html();

            function validarAnexosConciliaciones() {
                var errors = 0;
                var errors_text = '<p>Esta seguro de enviar el formulario sin los siguientes anexos: </p><ul>';
                $('#anexos_conciliaciones input[type="file"]').each(function(i, e) {
                    if($(this).is(':visible')) {
                        var valor = $(this).val();
                        if(valor == '') 
                        {
                            errors ++;
                            var label = '<li>'+$(this).closest('div.form-group').find('label').text()+'</li>';
                            errors_text += label;
                        }
                    }
                });
                errors_text += '</ul>';
                if(errors > 0)
                {
                    $('#modalAnexosConcilaciones .modal-body').html(errors_text);
                    $('#modalAnexosConcilaciones').modal('show');
                } else {
                    $('#form-conciliacion').submit();
                }
            }

            $('input[name="tipo_convocante"]').on('change', function(e) 
            {
                var options_html = $('<div></div>').html(tipo_identificacion_convocante);
                options_html.find('.bs-title-option').remove();
                var tipo = $(this).val();

                var label_celular_convocante = '* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i>';
                var label_direccion_convocante = '* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i>';
                var label_telefono_convocante = '* Teléfono <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono fijo, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i>';

                switch(tipo){
                    case '1':
                        $('#datos_convocante *[data-juridico], #anexos_conciliaciones *[data-juridico]').hide();
                        $('#datos_convocante *[data-natural-vigilado], #anexos_conciliaciones *[data-natural-vigilado]').hide();
                        $('#datos_convocante *[data-natural], #anexos_conciliaciones *[data-natural]').show();
                        $('select[name="pais_convocante"]').trigger('change');
                        $('select[name="tipo_identificacion_convocante"]').html(options_html.html());
                        $('select[name="tipo_identificacion_convocante"]').selectpicker('refresh');
                        
                        options_tipo = '<option value="Reconocimiento económico de gastos en salud">Reconocimiento económico de gastos en salud</option>'+
                                       '<option value="Otro">Otro</option>';

                        $('select[name="tipo_solicitud"]').html(options_tipo);
                        $('select[name="tipo_solicitud"]').selectpicker('refresh');
                    break;
                    case '2':
                        $('#datos_convocante *[data-juridico], #anexos_conciliaciones *[data-juridico]').hide();
                        $('#datos_convocante *[data-natural], #anexos_conciliaciones *[data-natural]').hide();
                        $('#datos_convocante *[data-natural-vigilado], #anexos_conciliaciones *[data-natural-vigilado]').show();
                        $('select[name="pais_convocante"]').trigger('change');
                        $('select[name="tipo_identificacion_convocante"]').html(options_html.html());
                        $('select[name="tipo_identificacion_convocante"]').selectpicker('refresh');
                        
                        options_tipo = '<option value="Conflictos derivados del sistema general de seguridad social en salud">Conflictos derivados del sistema general de seguridad social en salud</option>'+
                                       '<option value="Normalización del flujo de recursos">Normalización del flujo de recursos</option>';

                        $('select[name="tipo_solicitud"]').html(options_tipo);
                        $('select[name="tipo_solicitud"]').selectpicker('refresh');
                    break;
                    case '3':
                        $('#datos_convocante *[data-natural-vigilado], #anexos_conciliaciones *[data-natural-vigilado]').hide();
                        $('#datos_convocante *[data-natural], #anexos_conciliaciones *[data-natural]').hide();
                        $('#datos_convocante *[data-juridico], #anexos_conciliaciones *[data-juridico]').show();
                        $('#datos_convocante *[data-natural]').hide();
                        $('#datos_convocante *[data-natural-vigilado]').hide();
                        $('#datos_convocante *[data-juridico]').show();
                        options_html.html('<option value="4">Nit</option>');
                        $('select[name="tipo_identificacion_convocante"]').html(options_html.html());
                        $('select[name="tipo_identificacion_convocante"]').selectpicker('refresh');
                        $('select[name="pais_convocante"]').trigger('change');

                        options_tipo = '<option value="Conflictos derivados del sistema general de seguridad social en salud - Normalización del flujo de recursos">Conflictos derivados del sistema general de seguridad social en salud - Normalización del flujo de recursos</option>'+
                                       '<option value="Otro">Otro</option>';
                                       
                        $('select[name="tipo_solicitud"]').html(options_tipo);
                        $('select[name="tipo_solicitud"]').selectpicker('refresh');
                    break;
                }
                
                $('label[for="direccion_convocante"]').html(label_direccion_convocante);
                $('label[for="celular_convocante"]').html(label_celular_convocante);
                $('label[for="telefono_convocante"]').html(label_telefono_convocante);
                $('[data-toggle="tooltip"]').tooltip();
            });

            $('input[name="tipo_convocado"]').on('change', function(e) 
            {
                var options_html = $('<div></div>').html(tipo_identificacion_convocante);
                options_html.find('.bs-title-option').remove();
                var tipo = $(this).val();

                var label_celular_convocado = '* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número de celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i>';
                var label_direccion_convocado = '* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de residencia"></i>';
                var label_telefono_convocado = '* Teléfono <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono fijo, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i>';

                switch(tipo){
                    case '1':
                        $('#datos_convocado *[data-juridico]').hide();
                        $('#datos_convocado *[data-natural-vigilado]').hide();
                        $('#datos_convocado *[data-natural]').show();
                        $('select[name="pais_convocado"]').trigger('change');
                        $('select[name="tipo_identificacion_convocado"]').html(options_html.html());
                        $('select[name="tipo_identificacion_convocado"]').selectpicker('refresh');
                        
                        options_tipo = '<option value="Reconocimiento económico de gastos en salud">Reconocimiento económico de gastos en salud</option>'+
                                       '<option value="Otro">Otro</option>';

                        $('select[name="tipo_solicitud"]').html(options_tipo);
                        $('select[name="tipo_solicitud"]').selectpicker('refresh');
                    break;
                    case '2':
                        $('#datos_convocado *[data-juridico]').hide();
                        $('#datos_convocado *[data-natural]').hide();
                        $('#datos_convocado *[data-natural-vigilado]').show();
                        $('select[name="pais_convocado"]').trigger('change');
                        $('select[name="tipo_identificacion_convocado"]').html(options_html.html());
                        $('select[name="tipo_identificacion_convocado"]').selectpicker('refresh');
                        
                        options_tipo = '<option value="Conflictos derivados del sistema general de seguridad social en salud">Conflictos derivados del sistema general de seguridad social en salud</option>'+
                                       '<option value="Normalización del flujo de recursos">Normalización del flujo de recursos</option>';

                        $('select[name="tipo_solicitud"]').html(options_tipo);
                        $('select[name="tipo_solicitud"]').selectpicker('refresh');
                    break;
                    case '3':
                        $('#datos_convocado *[data-natural]').hide();
                        $('#datos_convocado *[data-natural-vigilado]').hide();
                        $('#datos_convocado *[data-juridico]').show();
                        options_html.html('<option value="4">Nit</option>');
                        $('select[name="tipo_identificacion_convocado"]').html(options_html.html());
                        $('select[name="tipo_identificacion_convocado"]').selectpicker('refresh');
                        $('select[name="pais_convocado"]').trigger('change');

                        options_tipo = '<option value="Conflictos derivados del sistema general de seguridad social en salud - Normalización del flujo de recursos">Conflictos derivados del sistema general de seguridad social en salud - Normalización del flujo de recursos</option>';
                                       
                        $('select[name="tipo_solicitud"]').html(options_tipo);
                        $('select[name="tipo_solicitud"]').selectpicker('refresh');
                    break;
                }
                
                $('label[for="direccion_convocado"]').html(label_direccion_convocado);
                $('label[for="celular_convocado"]').html(label_celular_convocado);
                $('label[for="telefono_convocado"]').html(label_telefono_convocado);
                $('[data-toggle="tooltip"]').tooltip();
            });

            $('input[name="tipo_entidad"]').on('change', function(e) {
                var tipo = $(this).val();
                switch(tipo)
                {
                    case 'Privada':
                        $('#anexos_conciliaciones').find('#file_10').closest('div.form-group').find('label').text('Poder a abogado para conciliar');
                        $('#anexos_conciliaciones').find('#file_10').attr('data-required', 'false');
                        
                        $('#anexos_conciliaciones').find('#file_12').closest('div.form-group').find('label').text('Acta o certificación suscrita por el secretario técnico del comité de defensa judicial y conciliación');
                        $('#anexos_conciliaciones').find('#file_12').attr('data-required', 'false');
                    break;
                    case 'Pública':
                        $('#anexos_conciliaciones').find('#file_10').closest('div.form-group').find('label').text('* Poder a abogado para conciliar');
                        $('#anexos_conciliaciones').find('#file_10').attr('data-required', 'true');

                        $('#anexos_conciliaciones').find('#file_12').closest('div.form-group').find('label').text('* Acta o certificación suscrita por el secretario técnico del comité de defensa judicial y conciliación');
                        $('#anexos_conciliaciones').find('#file_12').attr('data-required', 'false');
                    break;
                }
            });

            $('#departamento_convocante').on('change', function(e) {
				$('#ciudad_bar_convocante').show();
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

						$('#ciudad_convocante').html(options);
						$('#ciudad_convocante').selectpicker('refresh');
						$('#ciudad_bar_convocante').hide();
					}
				})
			});

            $('#departamento_convocado').on('change', function(e) {
				$('#ciudad_bar_convocado').show();
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

						$('#ciudad_convocado').html(options);
						$('#ciudad_convocado').selectpicker('refresh');
						$('#ciudad_bar_convocado').hide();
					}
				})
			});

            $('#comentarios_1, #comentarios_2').on('keyup', function(e) {
				var comentarios = $(this).val();

				$(this).closest('div.form-group').find('.size').text(comentarios.length+'/5000');
			});

            var altcha_widget_0_state = '';

			document.querySelector('#altcha-widget-0').addEventListener('statechange', (ev) => {
				altcha_widget_0_state = ev.detail.state;
				if (ev.detail.state === 'verified') {
					$('#altcha-widget-0').removeClass('altcha-error');
				}
			});

            $('#enviar-conciliacion').on('click', function(e) {
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
                        {
							$(this).closest('div').removeClass('is-invalid');
                            $(this).closest('.form-group').removeClass('has-error');
                        } else {
							$(this).removeClass('is-invalid');
                            $(this).closest('.form-group').removeClass('has-error');
                        }
					}
				});
				
				
                if($('#correo_convocante').attr('data-required') == 'true')
				{
					if($('#correo_convocante').is(':visible') && !isEmail($('#correo_convocante').val()+'@'+$('#dominio_convocante').val()))
					{
						$('#correo_convocante').removeClass('is-valid');
						$('#correo_convocante').addClass('is-invalid');
						$('#dominio_convocante').removeClass('is-valid');
						$('#dominio_convocante').addClass('is-invalid');
						errors ++;
                        errors_text += '<li>El correo del convocante es invalido</li>';
					} else {
						$('#correo_convocante').removeClass('is-invalid');
						$('#correo_convocante').addClass('is-valid');
						$('#dominio_convocante').removeClass('is-invalid');
						$('#dominio_convocante').addClass('is-valid');
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
					e.preventDefault();
				} else {
                    validarAnexosConciliaciones();
					e.preventDefault();
				}
			});

            $('#submit-conciliaciones').on('click', function(e) {
                $('#form-conciliacion').submit();
            });

            //CARGA DE ARCHIVOS
            $('#borrar-anexos').on('click', function(e) {
				$("#form-carga")[0].reset();
				$('select').each(function(e) {
					$(this).val('').trigger('change');
                    $('#ciudad_bar_sujeto').hide();
				});
			});

            $('#radicado').on('blur', function(e) {
                if($(this).val() != '')
                {
                    var request = $.post(
                        'solicitudes_ajax.php',
                        {
                            servicio: 'expediente_conciliaciones',
                            radicado: $(this).val()
                        },
                        'json'
                    );

                    request.done(function(res) {
                        if (res.length == 0 || !res)
                        {
                            $('#res-expediente').removeClass('text-success').addClass('text-danger');
                            $('#res-expediente').text('El número de solicitud de conciliación no existe.');
                        } else {
                            $('#res-expediente').removeClass('text-danger').addClass('text-success');
                            $('#res-expediente').text('Número de solicitud de conciliación valido.');
                            $('#expediente').val(res[0]['SGD_EXP_NUMERO']);
                        }
                    })
                } else {
                    $('#res-expediente').removeClass('text-success').removeClass('text-danger');
                    $('#res-expediente').text('');
                }
            });
            
            $('input[name="calidad_sujeto_procesal"]').on('change', function(e) {
                var tipo = $(this).val();

                switch(tipo) {
                    case 'Convocante':
                        $('*[data-convocado]').hide();
                        $('*[data-convocante]').show();
                    break;
                    case 'Convocado':
                        $('*[data-convocante]').hide();
                        $('*[data-convocado]').show();
                    break;
                }
            });

            var tipo_identificacion_sujeto = $('select[name="tipo_identificacion_sujeto"]').html();
            $('input[name="tipo_sujeto"]').on('change', function(e) 
			{
                var options_html = $('<div></div>').html(tipo_identificacion_sujeto);
                options_html.find('.bs-title-option').remove();
                var tipo = $(this).val();

                var label_celular_sujeto = '* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número de celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i>';
                var label_direccion_sujeto = '* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de residencia"></i>';
                var label_telefono_sujeto = '* Teléfono <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono fijo, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i>';

                switch(tipo){
                    case '1':
                        $('#sujeto *[data-juridico]').hide();
                        $('#sujeto *[data-natural-vigilado]').hide();
                        $('#sujeto *[data-natural]').show();
                        $('select[name="pais_sujeto"]').trigger('change');
                        $('select[name="tipo_identificacion_sujeto"]').html(options_html.html());
                        $('select[name="tipo_identificacion_sujeto"]').selectpicker('refresh');
                    break;
                    case '2':
                        $('#sujeto *[data-juridico]').hide();
                        $('#sujeto *[data-natural]').hide();
                        $('#sujeto *[data-natural-vigilado]').show();
                        $('select[name="pais_sujeto"]').trigger('change');
                        $('select[name="tipo_identificacion_sujeto"]').html(options_html.html());
                        $('select[name="tipo_identificacion_sujeto"]').selectpicker('refresh');
                    break;
                    case '3':
                        $('#sujeto *[data-natural]').hide();
                        $('#sujeto *[data-natural-vigilado]').hide();
                        $('#sujeto *[data-juridico]').show();
                        options_html.html('<option value="4">Nit</option>');
                        $('select[name="tipo_identificacion_sujeto"]').html(options_html.html());
                        $('select[name="tipo_identificacion_sujeto"]').selectpicker('refresh');
                        $('select[name="pais_sujeto"]').trigger('change');
                    break;
                }
                
                $('label[for="direccion_sujeto"]').html(label_direccion_sujeto);
                $('label[for="celular_sujeto"]').html(label_celular_sujeto);
                $('label[for="telefono_sujeto"]').html(label_telefono_sujeto);
                $('[data-toggle="tooltip"]').tooltip();
			});

            $('#departamento_sujeto').on('change', function(e) {
				$('#ciudad_bar_sujeto').show();
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

						$('#ciudad_sujeto').html(options);
						$('#ciudad_sujeto').selectpicker('refresh');
						$('#ciudad_bar_sujeto').hide();
					}
				})
			});

            function ajustarComponentes() {
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
						<div class="col-md-11" style="padding-top:3px">
							<input type="hidden" class="ignore" name="tipo_documento[]" value="Anexo">
							<input name="userfile[]" class="file" type="file" data-required="true" accept="image/tiff, image/jpeg, application/pdf, application/msword, text/plain, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png, application/zip, audio/mpeg, video/mp4">
						</div>
						<div class="col-md-1" style="padding-top:3px; text-align: right">
							<button type="button" data-type="remover" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
						</div>
						<div class="col-md-12 errors">

						</div>
					</div>
				</div>
			`;

            $('#agregar_archivo').on('click', function(e) {
                if($('div[data-type="file"]').length < 6 )
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
                var size = this.files[0].size / 1000;
                if(size > 20480)
                {
                    $(this).val(null);
                    alert('Solo se permite archivos de máximo 20 MB');
                }
            });

            var altcha_widget_1_state = '';

			document.querySelector('#altcha-widget-1').addEventListener('statechange', (ev) => {
				altcha_widget_1_state = ev.detail.state;
				if (ev.detail.state === 'verified') {
					$('#altcha-widget-1').removeClass('altcha-error');
				}
			});

            $('#enviar-conciliacion-anexo').on('click', function(e) {
				var errors = 0;
                var errors_text = '<p>Por favor ingrese la siguiente información: </p><ul>';

				$('input[type="text"], input[type="number"], input[type="radio"], input[type="checkbox"], input[type="file"], textarea, select').each(function(e) 
                {					
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
                        {
							$(this).closest('div').removeClass('is-invalid');
                            $(this).closest('.form-group').removeClass('has-error');
						} else {
							$(this).removeClass('is-invalid');
                            $(this).closest('.form-group').removeClass('has-error');
                        }
					}
				});

				if (altcha_widget_1_state != 'verified')
				{
					$('#altcha-widget-1').addClass('altcha-error');
					errors_text += '<li>Por favor verifique el captcha</li>';
					errors ++;
				} else {
					$('#altcha-widget-1').removeClass('altcha-error');
				}

				errors_text += '</ul>';

				if(errors > 0 || altcha_widget_1_state != 'verified' || $('#expediente').val() == '')
				{
                    $('#modal .modal-body').html(errors_text);
					$('#modal').modal('show');
					e.preventDefault();
				} else {
                    $('#form-carga').submit();
					$('.loader').show();
				}
			});
            
			$('#comentarios_2').on('keyup', function(e) {
				var comentarios = $('#comentarios_2').val();
				$('.size').text(comentarios.length+'/50');
			});
        });
    </script>
<?php include ('footer.php') ?>
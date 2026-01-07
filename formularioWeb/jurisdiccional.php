<?php
	session_start();
	define('ADODB_ASSOC_CASE', 1);
	$ruta_raiz = "..";
	$ADODB_COUNTRECS = false;
	
	include_once("$ruta_raiz/processConfig.php");
	include_once("$ruta_raiz/include/db/ConnectionHandler.php");
    include_once("$ruta_raiz/formularioWeb/solicitudes_sql.php");
	
	$pregunta = '4';
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
					Este formulario es para presentar una demanda jurisdiccional y/o cargar documentos a un proceso jurisdiccional existente. 
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
                <a href="#" class="link" data-show="#demanda-jurisdiccional" style="text-align:center; display:block;">
                    <i class="bi bi-journal-text" style="font-size: 50px;"></i> 
                    <br> 
                    Radicar demanda jurisdiccional
                </a>
            </div>
            <div class="col-md-6">
                <a href="#" class="link" data-show="#carga-archivos" style="text-align:center; display:block;">
                    <i class="bi bi-cloud-upload" style="font-size: 50px;"></i> 
                    <br> 
                    Cargar archivos a un expediente jurisdiccional existente.
                </a>
            </div>
        </div>
        <div class="col-md-12" data-version="1.0.1">
            <br>
        </div>
		<form action="jurisdiccional_back.php" id="form-jurisdiccional" enctype="multipart/form-data" method="post">
            <div class="row" id="demanda-jurisdiccional" style="display:none;">
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
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <fieldset>
                                        <label for="apoderado_o_oficioso">* ¿Actúa usted en calidad de apoderado o agente oficioso? <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione SI, si usted es el apoderado o agente oficioso. Seleccione NO, si usted no es el apoderado o agente oficioso"></i></label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="apoderado_o_oficioso" id="apoderado_o_oficioso_1" value="Si" data-required="true" data-label="Calidad de apoderado o agente oficioso">
                                            <label class="form-check-label" for="apoderado_o_oficioso_1">Si</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="apoderado_o_oficioso" id="apoderado_o_oficioso_2" value="No" data-required="true">
                                            <label class="form-check-label" for="apoderado_o_oficioso_2">No</label>
                                        </div>
					                    <span class="error small">Indique si actúa en calidad de apoderado o agente oficioso</span>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row" id="datos_apoderado" style="display:none;">
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h4 class="section-h">Información del apoderado o agente oficioso</h4>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 form-group">
                                            <label for="tipo_apoderado">* Seleccione el tipo de persona <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Persona natural se refiere a un individuo, persona jurídica a una empresa u organización. Defina si la persona que está formulando la demanda es una persona natural o persona jurídica."></i></label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_apoderado" id="tipo_apoderado_1" value="1" data-required="true" data-label="Tipo de persona apoderado">
                                                <label class="form-check-label" for="tipo_apoderado_1">Natural</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_apoderado" id="tipo_apoderado_2" value="2" data-required="true">
                                                <label class="form-check-label" for="tipo_apoderado_2">Jurídica</label>
                                            </div>
					                        <span class="error small">Seleccione el tipo de persona del apoderado o agente oficioso</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <h4 class="section-h">Infórmenos sus datos</h4>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="id">* Tipo de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el tipo de identificación"></i></label>
                                            <select name="tipo_identificacion_apoderado" id="tipo_identificacion_apoderado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Tipo de identificación apoderado">
                                                <?php foreach ($tipos_documentos as $tipo) { ?>
                                                    <option value="<?=$tipo['TDID_CODI']?>"><?=$tipo['TDID_DESC']?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="error small">Seleccione el tipo de identificación del apoderado o agente oficioso</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="id_apoderado">* Número de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número del documento de identificación, recuerde que si selecciona pasaporte debe ingresar números y letras de lo contrario sólo números o si el tipo de identificación es NIT ingréselo sin el guión"></i></label>
                                            <input type="number" class="form-control" id="id_apoderado" name="id_apoderado" autocomplete="no"  data-required="true" data-label="Número de identificación apoderado">
                                            <span class="error small">Ingrese el número de identificación del apoderado o agente oficioso</span>
                                        </div>
                                    </div>
                                    <div class="form-row" data-natural>
                                        <div class="col-md-3 form-group">
                                            <label for="nombre_apoderado_1">* Primer nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer nombre"></i></label>
                                            <input type="text" class="form-control alpha-only" id="nombre_apoderado_1" name="nombre_apoderado_1" autocomplete="no" data-required="true" data-label="Primer nombre apoderado">
                                            <span class="error small">Ingrese el primer nombre del apoderado o agente oficioso</span>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="nombre_apoderado_2">Segundo nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo nombre"></i></label>
                                            <input type="text" class="form-control alpha-only" id="nombre_apoderado_2" name="nombre_apoderado_2" autocomplete="no">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="apellidos_apoderado_1">* Primer apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer apellido"></i></label>
                                            <input type="text" class="form-control alpha-only" id="apellidos_apoderado_1" name="apellidos_apoderado_1" autocomplete="no" data-required="true" data-label="Primer apellido apoderado">
                                            <span class="error small">Ingrese el primer apellido del apoderado o agente oficioso</span>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="apellidos_apoderado_2">Segundo apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo apellido"></i></label>
                                            <input type="text" class="form-control alpha-only" id="apellidos_apoderado_2" name="apellidos_apoderado_2" autocomplete="no">
                                        </div>
                                    </div>
                                    <div class="form-row" style="display: none;" data-juridico>
                                        <div class="col-md-12 form-group">
                                            <label for="rs_apoderado">* Razón social <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la razón social"></i></label>
                                            <input type="text" class="form-control alpha-only" id="rs" name="rs_apoderado" autocomplete="no" data-required="true" data-label="Razón social apoderado">
                                            <span class="error small">Ingrese la razón social del apoderado o agente oficioso</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm" data-natural>
                                            <h4 class="section-h">¿Dónde vive?</h4>
                                        </div>
                                        <div class="col-sm" style="display: none;" data-juridico>
                                            <h4 class="section-h">¿Dónde esta ubicado?</h4>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="pais_apoderado">* País <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el país en el que reside  "></i></label>
                                            <select name="pais_apoderado" id="pais_apoderado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="País apoderado">
                                                <?php foreach ($paises as $pais) { ?>
                                                    <option value="<?=$pais['NOMBRE']?>"><?=$pais['NOMBRE']?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="error small">Seleccione el país de residencia del apoderado o agente oficioso</span>
                                        </div> 
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="departamento_apoderado">* Departamento <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el departamento en el que reside"></i></label>
                                            <select name="departamento_apoderado" id="departamento_apoderado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Departamento apoderado">
                                                <?php foreach ($departamentos as $departamento) { ?>
                                                    <option value="<?=$departamento['DPTO_CODI']?>"><?=$departamento['DPTO_NOMB']?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="error small">Seleccione el departamento de residencia del apoderado o agente oficioso</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="ciudad_apoderado">* Municipio <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el municipio en el que vive"></i></label>
                                            <select name="ciudad_apoderado" id="ciudad_apoderado" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Municipio apoderado"></select>
                                            <div id="ciudad_bar_apoderado" class="progress" style="display:none; height:5px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                            </div>
                                            <small id="emailHelp" class="form-text text-muted">*ANM Areas no municipalizadas</small>
                                            <span class="error small">Seleccione el municipio de residencia del apoderado o agente oficioso</span>
                                        </div>
                                        <div class="col-md-6 form-group" data-natural data-juridico style="display:none;">
                                            <label for="provincia_apoderado">* Ciudad / Estado / Provincia</label>
                                            <input type="text" class="form-control" id="provincia_apoderado" name="provincia_apoderado" autocomplete="no" data-required="true" data-label="Ciudad / Estado / Provincia apoderado">
                                            <span class="error small">Ingrese la ciudad, estado y/o provincia de residencia del apoderado o agente oficioso</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="direccion_apoderado">* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i></label>
                                            <input type="text" class="form-control" id="direccion_apoderado" name="direccion_apoderado" autocomplete="no" data-required="true" data-label="Dirección apoderado">
                                            <span class="error small">Ingrese la dirección del apoderado o agente oficioso</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm" data-natural>
                                            <h4 class="section-h">¿Cómo lo contactamos?</h4>
                                        </div>
                                        <div class="col-sm" style="display: none;" data-juridico>
                                            <h4 class="section-h">Dejenos sus datos de contacto</h4>
                                        </div>
                                    </div>
                                    <div class="form-row" data-natural data-juridico>
                                        <div class="col-md-3 form-group">
                                            <label for="celular_apoderado">* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                            <input type="number" class="form-control" id="celular_apoderado" name="celular_apoderado" autocomplete="no" data-required="true" data-label="Celular apoderado">
                                            <span class="error small">Ingrese el celular del apoderado o agente oficioso</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="telefono_apoderado">* Teléfono fijo o celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono fijo o celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                            <input type="number" class="form-control" id="telefono_apoderado" name="telefono_apoderado" autocomplete="no" data-required="true" data-label="Teléfono fijo o celular apoderado">
                                            <span class="error small">Ingrese un teléfono fijo o celular adicional para el apoderado o agente oficioso</span>
                                        </div>
                                        <div class="col-md-6" data-natural data-juridico>
                                            <div class="form-row">
                                                <div class="col-md-6 form-group">
                                                    <label for="correo_apoderado">* Correo electrónico <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su correo electrónico, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                                    <input type="text" class="form-control" id="correo_apoderado" name="correo_apoderado" autocomplete="no" data-required="true" data-label="Correo apoderado">
                                                    <span class="error small">Ingrese el correo electrónico del apoderado o agente oficioso</span>
                                                </div>
                                                <div class="col-md-6 email-component">
                                                    <label for=""><i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el dominio de la lista desplegable si no lo encuentra digítelo."></i>&nbsp;</label>
                                                    <input type="text" class="form-control dominio" id="dominio_apoderado" name="dominio_apoderado" placeholder="dominio" aria-label="dominio" autocomplete="no" data-required="true" data-label="Dominio correo apoderado">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="datos_demandante">
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h4 class="section-h">Información del demandante</h4>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 form-group">
                                            <label for="tipo_demandante">* Seleccione el tipo de persona del demandante <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Persona natural se refiere a un individuo, persona jurídica a una empresa u organización. Defina si la persona que está formulando la demanda es una persona natural o persona jurídica."></i></label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_demandante" id="tipo_demandante_1" value="1" data-required="true" data-label="Tipo persona demandante">
                                                <label class="form-check-label" for="tipo_demandante_1">Natural</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_demandante" id="tipo_demandante_2" value="2" data-required="true">
                                                <label class="form-check-label" for="tipo_demandante_2">Jurídica</label>
                                            </div>
					                        <span class="error small">Seleccione el tipo de persona del demandante</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <h4 class="section-h">Cuéntenos sobre el demandante</h4>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="id">* Tipo de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el tipo de identificación"></i></label>
                                            <select name="tipo_identificacion_demandante" id="tipo_identificacion_demandante" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Tipo de identificación demandante">
                                                <?php foreach ($tipos_documentos as $tipo) { ?>
                                                    <option value="<?=$tipo['TDID_CODI']?>"><?=$tipo['TDID_DESC']?></option>
                                                <?php } ?>
                                            </select>
					                        <span class="error small">Seleccione el tipo de identificación del demandante</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="id_demandante">* Número de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número del documento de identificación, recuerde que si selecciona pasaporte debe ingresar números y letras de lo contrario sólo números o si el tipo de identificación es NIT ingréselo sin el guión."></i></label>
                                            <input type="number" class="form-control" id="id_demandante" name="id_demandante" autocomplete="no" data-required="true" data-label="Número de identificación demandante">
					                        <span class="error small">Ingrese el número de identificación del demandante</span>
                                        </div>
                                    </div>
                                    <div class="form-row" data-natural>
                                        <div class="col-md-3 form-group">
                                            <label for="nombre_demandante_1">* Primer nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer nombre"></i></label>
                                            <input type="text" class="form-control alpha-only" id="nombre_demandante_1" name="nombre_demandante_1" autocomplete="no" data-required="true" data-label="Primer nombre demandante">
                                            <span class="error small">Ingrese el primer nombre del demandante</span>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="nombre_demandante_2">Segundo nombre <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo nombre"></i></label>
                                            <input type="text" class="form-control alpha-only" id="nombre_demandante_2" name="nombre_demandante_2" autocomplete="no">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="apellidos_demandante_1">* Primer apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su primer apellido"></i></label>
                                            <input type="text" class="form-control alpha-only" id="apellidos_demandante_1" name="apellidos_demandante_1" autocomplete="no" data-required="true" data-label="Primer apellido demandante">
                                            <span class="error small">Ingrese el primer apellido del demandante</span>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="apellidos_demandante_2">Segundo apellido <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su segundo apellido"></i></label>
                                            <input type="text" class="form-control alpha-only" id="apellidos_demandante_2" name="apellidos_demandante_2" autocomplete="no">
                                        </div>
                                    </div>
                                    <div class="form-row" style="display: none;" data-juridico>
                                        <div class="col-md-12 form-group">
                                            <label for="rs_demandante">* Razón social <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la razón social"></i></label>
                                            <input type="text" class="form-control alpha-only" id="rs" name="rs_demandante" autocomplete="no" data-required="true" data-label="Razón social demandante">
                                            <span class="error small">Ingrese la razón social del demandante</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm" data-natural>
                                            <h4 class="section-h">¿Dónde vive el demandante?</h4>
                                        </div>
                                        <div class="col-sm" style="display: none;" data-juridico>
                                            <h4 class="section-h">¿Dónde esta ubicado el demandante?</h4>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="pais_demandante">* País <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el país en el que reside"></i></label>
                                            <select name="pais_demandante" id="pais_demandante" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="País demandante">
                                                <?php foreach ($paises as $pais) { ?>
                                                    <option value="<?=$pais['NOMBRE']?>"><?=$pais['NOMBRE']?></option>
                                                <?php } ?>
                                            </select>
					                        <span class="error small">Seleccione el país de residencia del demandante</span>
                                        </div> 
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="departamento_demandante">* Departamento <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el departamento en el que reside"></i></label>
                                            <select name="departamento_demandante" id="departamento_demandante" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Departamento demandante">
                                                <?php foreach ($departamentos as $departamento) { ?>
                                                    <option value="<?=$departamento['DPTO_CODI']?>"><?=$departamento['DPTO_NOMB']?></option>
                                                <?php } ?>
                                            </select>
					                        <span class="error small">Seleccione el departamento de residencia del demandante</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="ciudad_demandante">* Municipio <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el municipio en el que vive"></i></label>
                                            <select name="ciudad_demandante" id="ciudad_demandante" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Municipio demandante"></select>
                                            <div id="ciudad_bar_demandante" class="progress" style="display:none; height:5px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                            </div>
                                            <small id="emailHelp" class="form-text text-muted">*ANM Areas no municipalizadas</small>
					                        <span class="error small">Seleccione el municipio de residencia del demandante</span>
                                        </div>
                                        <div class="col-md-6 form-group" data-natural data-juridico style="display:none;">
                                            <label for="provincia_demandante">* Ciudad / Estado / Provincia</label>
                                            <input type="text" class="form-control" id="provincia_demandante" name="provincia_demandante" autocomplete="no" data-required="true" data-label="Ciudad / Estado / Provincia demandante">
                                            <span class="error small">Ingrese la ciudad, estado y/o provincia de residencia del demandante</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="direccion_demandante">* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i></label>
                                            <input type="text" class="form-control" id="direccion_demandante" name="direccion_demandante" autocomplete="no" data-required="true" data-label="Dirección demandante">
                                            <span class="error small">Ingrese la dirección del demandante</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm" data-natural>
                                            <h4 class="section-h">¿Cómo contactamos al demandante?</h4>
                                        </div>
                                        <div class="col-sm" style="display: none;" data-juridico>
                                            <h4 class="section-h">Dejenos los datos de contacto del demandante</h4>
                                        </div>
                                    </div>
                                    <div class="form-row" data-natural data-juridico>
                                        <div class="col-md-3 form-group">
                                            <label for="celular_demandante">* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                            <input type="number" class="form-control" id="celular_demandante" name="celular_demandante" autocomplete="no" data-required="true" data-label="Celular demandante">
                                            <span class="error small">Ingrese el celular del demandante</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="telefono_demandante">* Teléfono o celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono fijo o celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                            <input type="number" class="form-control" id="telefono_demandante" name="telefono_demandante" autocomplete="no" data-required="true" data-label="Teléfono o celular demandante">
                                            <span class="error small">Ingrese un teléfono fijo o celular adicional para el demandante</span>
                                        </div>
                                        <div class="col-md-6" data-natural data-juridico>
                                            <div class="form-row">
                                                <div class="col-md-6 form-group">
                                                    <label for="correo_demandante">* Correo electrónico <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su correo electrónico, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                                    <input type="text" class="form-control" id="correo_demandante" name="correo_demandante" autocomplete="no" data-required="true" data-label="Correo demandante">
                                                    <span class="error small">Ingrese el correo electrónico del demandante</span>
                                                </div>
                                                <div class="col-md-6 email-component">
                                                    <label for=""><i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el dominio de la lista desplegable si no lo encuentra digítelo."></i>&nbsp;</label>
                                                    <input type="text" class="form-control dominio" id="dominio_demandante" name="dominio_demandante" placeholder="dominio" aria-label="dominio" autocomplete="no" data-required="true" data-label="Dominio correo demandante">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="datos_caso">
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h4 class="section-h">Descripción del caso</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5>Infórmenos sobre el tipo de pretensión que va a formular</h5>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label for="tipo_demanda">* Tipo de pretensión <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="Seleccione el tipo de pretensión, haciendo clic sobre la flecha"></i></label><button type="button" class="btn btn-link" data-toggle="modal" data-target="#modalPreteniones">Descripción de los tipos de pretensión</button>
                                            <select name="tipo_demanda" id="tipo_demanda" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Tipo de pretensión">
                                                <?php foreach ($tipos_demandas as $tipo) { ?>
                                                    <option value="<?=$tipo?>"><?=$tipo?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="error small">Seleccione el tipo de pretensión que va a formular</span>
                                        </div>
                                        <div id="subtipo" class="col-md-12 form-group" style="display:none;">
                                            <label for="subtipos_demandas">* Caso según tipo de pretensión <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el caso según tipo de prentensión, haciendo clic sobre la flecha"></i></label>
                                            <select name="subtipos_demandas" id="subtipos_demandas" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Caso según tipo de demanda">
                                                <?php foreach ($subtipos_demandas as $subtipo) { ?>
                                                    <option value="<?=$subtipo?>"><?=$subtipo?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="error small">Seleccione el caso según el tipo de pretensión seleccionado</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5>Contenido de su solicitud, adjunte el archivo de la demanda</h5>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 form-group limited-textarea">
                                            <label for="comentarios_1">* Describa brevemente los detalles de la demanda: <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite brevemente"></i></label>
                                            <textarea id="comentarios_1" name="comentarios" class="form-control" rows="5" autocomplete="no" data-required="true" maxlength="5000"></textarea>
                                            <span class="size" data-max="5000">0/5000</span>
					                        <span class="error small">Ingrese el texto que describe los detalles de la demanda, máximo 5000 caracteres</span>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 form-group">
                                            <label for="file_0">* Adjuntar demanda en formato WORD o PDF</label><br>
                                            <input type="file" id="file_1" name="userfile[0]" data-required="true" data-label="Demanda en formato WORD o PDF" accept="application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/msword">
                                            <input type="hidden" name="tipo_documento[0]" value="Demanda en formato WORD">
                                            <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
					                        <span class="error small">Seleccione un archivo</span>
                                        </div>
                                        <div class="col-md-12" data-tipo="Cobertura de servicios incluidos del Plan de Beneficios en Salud- PBS" style="display:none;">
                                            <a href="./formatos/FORMATO COBERTURA PBS.docx"><i class="bi bi-file-word"></i> Formato guía demanda</a>
                                        </div>
                                        <div class="col-md-12" data-tipo="Para glosas" style="display:none;">
                                            <a href="./formatos/FORMATO GLOSAS.docx"><i class="bi bi-file-word"></i> Formato guía demanda</a>
                                        </div>
                                        <div class="col-md-12" data-tipo="Para recobros NO PBS" style="display:none;">
                                            <a href="./formatos/FORMATO GLOSA2.docx"><i class="bi bi-file-word"></i> Formato guía demanda</a>
                                        </div>
                                        <div class="col-md-12" data-tipo="Para reclamaciones ante ECAT" style="display:none;">
                                            <a href="./formatos/FORMATO GLOSA3.docx"><i class="bi bi-file-word"></i> Formato guía demanda</a>
                                        </div>
                                        <div class="col-md-12" data-tipo="Conflictos por libre elección y movilidad" style="display:none;">
                                            <a href="./formatos/FORMATO DE SOLICITUD DE RECONOCIMIENTO ECONÒMICO.docx"><i class="bi bi-file-word"></i> Formato guía demanda</a>
                                        </div>
                                        <div class="col-md-12" data-tipo="Conflictos por multiafiliación dentro del Sistema General de Seguridad Social en Salud- SGSSS" style="display:none;">
                                            <a href="./formatos/FORMATO DE SOLICITUD DE MULTIAFILIACIÒN.docx"><i class="bi bi-file-word"></i> Formato guía demanda</a>
                                        </div>
                                        <div class="col-md-12" data-tipo="Prestaciones no incluidas en el Plan de Beneficios en Salud- PBS" style="display:none;">
                                            <a href="./formatos/FORMATO COBERTURA NO PB1.docx"><i class="bi bi-file-word"></i> Formato guía demanda</a>
                                        </div>
                                        <div class="col-md-12" data-tipo="Reconocimiento económico de gastos en salud" style="display:none;">
                                            <a href="./formatos/FORMATO DE SOLICITUD DE RECONOCIMIENTO ECONÒMIC1.docx"><i class="bi bi-file-word"></i> Formato guía demanda</a>
                                        </div>
                                        <div class="col-md-12">
                                            <br>
                                        </div>
                                    </div>
                                    <div class="row" data-tipo="Para glosas" style="display:none;">
                                        <div class="col-md-12 form-group">
                                            <label for="file_13">Cuadro 1. Trazabilidad para glosas</label>
                                            <small style="color: #f00">Trazabilidad en Excel. Documento indispensable para el análisis de las glosas y/o devoluciones.</small><br>
                                            <input type="file" id="file_13" name="userfile[13]" data-label="Cuadro 1. Trazabilidad para glosas" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                            <input type="hidden" name="tipo_documento[13]" value="Cuadro 1. Trazabilidad para glosas">
                                            <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                        </div>
                                        <div class="col-md-12">
                                            <a href="./formatos/Cuadro 1 - Trazabilidad para glosas.xlsx"><i class="bi bi-file-excel"></i> Formato guía - Trazabilidad para glosas</a>
                                        </div>
                                    </div>
                                    <div class="row" data-tipo="Para recobros NO PBS" style="display:none;">
                                        <div class="col-md-12 form-group">
                                            <label for="file_21">Cuadro 2. Trazabilidad recobros NO PBS</label>
                                            <small style="color: #f00">Trazabilidad en Excel. Documento indispensable para el análisis de trazabilidad y recobros.</small><br>
                                            <input type="file" id="file_21" name="userfile[21]" data-label="Cuadro 2. Trazabilidad recobros NO PBS" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                            <input type="hidden" name="tipo_documento[21]" value="Cuadro 2. Trazabilidad recobros NO PBS">
                                            <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                        </div>
                                        <div class="col-md-12">
                                            <a href="./formatos/Cuadro 2 - Trazabilidad recobros NO PBS.xlsx"><i class="bi bi-file-excel"></i> Formato guía - Trazabilidad recobros NO PBS</a>
                                        </div>
                                    </div>
                                    <div class="row" data-tipo="Para reclamaciones ante ECAT" style="display:none;">
                                        <div class="col-md-12 form-group">
                                            <label for="file_29">Cuadro 3. Trazabilidad para reclamaciones ante ECAT</label>
                                            <small style="color: #f00">Trazabilidad en Excel. Documento indispensable para reclamaciones ante ECAT.</small><br>
                                            <input type="file" id="file_29" name="userfile[29]" data-label="Cuadro 3. Trazabilidad para reclamaciones ante ECAT" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                            <input type="hidden" name="tipo_documento[29]" value="Cuadro 3. Trazabilidad para reclamaciones ante ECAT">
                                            <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                        </div>
                                        <div class="col-md-12">
                                            <a href="./formatos/Cuadro 3 - Trazabilidad para reclamaciones ante ECAT.xlsx"><i class="bi bi-file-excel"></i> Formato guía - Trazabilidad para reclamaciones ante ECAT</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4 class="section-h">Agregue los anexos o soportes de la demanda</h4>
                                        </div>
                                        <div class="col-md-12">
                                            <p style="text-align:justify;">
                                                Donde aparece el icono adjuntar soportes, suba los soportes que considere pueden servir para su denuncia 
                                                si estos son muy pesados los puede comprimir, puede adjuntar con un tamaño de 20 MB. A continuación se relacionan los requisitos de la demanda que debe anexar.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="anexos_demandas">
                                            <div class="row" data-tipo="Cobertura de servicios incluidos del Plan de Beneficios en Salud- PBS" style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_2">Fotocopia de la cédula de ciudadania del solicitante</label><br>
                                                    <input type="file" id="file_2" name="userfile[2]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[2]" value="Fotocopia de la cédula de ciudadania del solicitante">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_3">Copia de la historia clínica y/o prescripción del médico tratante</label><br>
                                                    <input type="file" id="file_3" name="userfile[3]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[3]" value="Copia de la historia clínica y/o prescripción del médico tratante">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_4">Si es posible, copia del formato de negación de servicios de salud y medicamentos</label><br>
                                                    <input type="file" id="file_4" name="userfile[4]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[4]" value="Copia del formato de negación de servicios de salud y medicamentos">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_5">Demás documentos que considere pertinentes y que sirvan de prueba de acuerdo con lo solicitado</label><br>
                                                    <input type="file" id="file_5" name="userfile[5]" accept="image/tiff, image/jpeg, application/pdf, application/msword, text/plain, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png, application/zip">
                                                    <input type="hidden" name="tipo_documento[5]" value="Otros documentos">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="row" data-tipo="Para glosas" style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_6">Si es Persona Jurídica deberá actuar a través de su Representante Legal y acreditar tal calidad con Certificado de Existencia y Representación legal expedido por la entidad competente</label><br>
                                                    <input type="file" id="file_6" name="userfile[6]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[6]" value="Certificado de Existencia y Representación legal" >
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_7">Si es apoderado, poder debidamente conferido</label><br>
                                                    <input type="file" id="file_7" name="userfile[7]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[7]" value="Poder">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_8">Copia legible de las facturas y/o detallado o documento equivalente, con soporte de radicado</label><br>
                                                    <input type="file" id="file_8" name="userfile[8]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[8]" value="Copia legible de las facturas y/o detallado o documento equivalente, con soporte de radicado">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_9">Los soportes de las facturas radicadas, según lo definido en Resolución 003047 de 2009, Anexo 5 y Artículo 21 del Decreto 4747 de 2007</label><br>
                                                    <input type="file" id="file_9" name="userfile[9]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[9]" value="Los soportes de las facturas radicadas, según lo definido en Resolución 003047 de 2009, Anexo 5 y Artículo 21 del Decreto 4747 de 2007">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_11">En eventos de urgencia vital el formato de notificación de la urgencia en caso de no haber contrato de las partes</label><br>
                                                    <input type="file" id="file_11" name="userfile[11]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[11]" value="En eventos de urgencia vital el formato de notificación de la urgencia en caso de no haber contrato de las partes">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_12">Demás documentos que considere pertinentes y que sirvan de prueba de acuerdo con lo solicitado</label><br>
                                                    <input type="file" id="file_12" name="userfile[12]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png, application/zip">
                                                    <input type="hidden" name="tipo_documento[12]" value="Demás documentos que considere pertinentes y que sirvan de prueba de acuerdo con lo solicitado">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="row" data-tipo="Para recobros NO PBS" style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_14">Si es Persona Jurídica deberá actuar a través de su Representante Legal y acreditar tal calidad con Certificado de Existencia y Representación legal expedido por la entidad competente</label><br>
                                                    <input type="file" id="file_14" name="userfile[14]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[14]" value="Certificado de Existencia y Representación legal">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_15">Si es apoderado, poder debidamente conferido</label><br>
                                                    <input type="file" id="file_15" name="userfile[15]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[15]" value="Poder">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_16">Copia legible de las facturas y/o detallado o documento equivalente, con soporte de radicado</label><br>
                                                    <input type="file" id="file_16" name="userfile[16]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[16]" value="Copia legible de las facturas y/o detallado o documento equivalente, con soporte de radicado">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_17">Los soportes de las facturas radicadas, según lo definido en Resolución 003047 de 2017, Anexo 5 y Artículo 21 del Decreto 4747 de 2007</label><br>
                                                    <input type="file" id="file_17" name="userfile[17]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[17]" value="Los soportes de las facturas radicadas, según lo definido en Resolución 003047 de 2009, Anexo 5 y Artículo 21 del Decreto 4747 de 2007">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_19">En eventos de urgencia vital el formato de notificación de la urgencia en caso de no haber contrato de las partes</label><br>
                                                    <input type="file" id="file_19" name="userfile[19]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[19]" value="En eventos de urgencia vital el formato de notificación de la urgencia en caso de no haber contrato de las partes">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_20">Demás documentos que considere pertinentes y que sirvan de prueba de acuerdo con lo solicitado</label><br>
                                                    <input type="file" id="file_20" name="userfile[20]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png, application/zip">
                                                    <input type="hidden" name="tipo_documento[20]" value="Demás documentos que considere pertinentes y que sirvan de prueba de acuerdo con lo solicitado">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="row" data-tipo="Para reclamaciones ante ECAT" style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_22">Si es Persona Jurídica deberá actuar a través de su Representante Legal y acreditar tal calidad con Certificado de Existencia y Representación legal expedido por la entidad competente</label><br>
                                                    <input type="file" id="file_22" name="userfile[22]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[22]" value="Certificado de Existencia y Representación legal">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_23">Si es apoderado, poder debidamente conferido</label><br>
                                                    <input type="file" id="file_23" name="userfile[23]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[23]" value="Poder">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_24">Copia legible de las facturas y/o detallado o documento equivalente, con soporte de radicado</label><br>
                                                    <input type="file" id="file_24" name="userfile[24]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[24]" value="Copia legible de las facturas y/o detallado o documento equivalente, con soporte de radicado">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_25">Los soportes de las facturas radicadas, según lo definido en Resolución 003047 de 2017, Anexo 5 y Artículo 21 del Decreto 4747 de 2007</label><br>
                                                    <input type="file" id="file_25" name="userfile[25]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[25]" value="Los soportes de las facturas radicadas, según lo definido en Resolución 003047 de 2009, Anexo 5 y Artículo 21 del Decreto 4747 de 2007">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_27">En eventos de urgencia vital el formato de notificación de la urgencia en caso de no haber contrato de las partes</label><br>
                                                    <input type="file" id="file_27" name="userfile[27]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[27]" value="En eventos de urgencia vital el formato de notificación de la urgencia en caso de no haber contrato de las partes">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_28">Demás documentos que considere pertinentes y que sirvan de prueba de acuerdo con lo solicitado</label><br>
                                                    <input type="file" id="file_28" name="userfile[28]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png, application/zip">
                                                    <input type="hidden" name="tipo_documento[28]" value="Demás documentos que considere pertinentes y que sirvan de prueba de acuerdo con lo solicitado">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="row" data-tipo="Conflictos por libre elección y movilidad" style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_30">Fotocopia de la cédula de ciudadania del solicitante</label><br>
                                                    <input type="file" id="file_30" name="userfile[30]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[30]" value="Fotocopia de la cédula de ciudadania del solicitante">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_31">Copia de formulario de afiliación</label><br>
                                                    <input type="file" id="file_31" name="userfile[31]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[31]" value="Copia de formulario de afiliación">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_32">Copia de solicitud de traslado (novedad)</label><br>
                                                    <input type="file" id="file_32" name="userfile[32]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[32]" value="Copia de solicitud de traslado (novedad)">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_33">Copia de la respuesta de negación por parte de la EPS</label><br>
                                                    <input type="file" id="file_33" name="userfile[33]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[33]" value="* Copia de la respuesta de negación por parte de la EPS">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_34">Demás documentos que considere pertinentes y que sirvan de prueba de acuerdo con lo solicitado</label><br>
                                                    <input type="file" id="file_34" name="userfile[34]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png, application/zip">
                                                    <input type="hidden" name="tipo_documento[34]" value="Otros documentos">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="row" data-tipo="Conflictos por multiafiliación dentro del Sistema General de Seguridad Social en Salud- SGSSS" style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_35">Fotocopia de la cédula de ciudadania del solicitante</label><br>
                                                    <input type="file" id="file_35" name="userfile[35]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[35]" value="Fotocopia de la cédula de ciudadania del solicitante">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_36">Copia de la solicitud de retiro de la EPS es que se encontraba inicialmente afiliado</label><br>
                                                    <input type="file" id="file_36" name="userfile[36]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[36]" value="Copia de la solicitud de retiro de la EPS es que se encontraba inicialmente afiliado">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_37">Copia del formulario de afiliación de la EPS elegida por el solicitante</label><br>
                                                    <input type="file" id="file_37" name="userfile[37]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[37]" value="Copia del formulario de afiliación de la EPS elegida por el solicitante">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_38">Demás documentos que considere pertinentes y que sirvan de prueba de acuerdo con lo solicitado</label><br>
                                                    <input type="file" id="file_38" name="userfile[38]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png, application/zip">
                                                    <input type="hidden" name="tipo_documento[38]" value="Otros documentos">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="row" data-tipo="Prestaciones no incluidas en el Plan de Beneficios en Salud- PBS" style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_39">Copia de la historia clínica de la patología por la cual reclama</label><br>
                                                    <input type="file" id="file_39" name="userfile[39]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[39]" value="Copia de la historia clínica de la patología por la cual reclama">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_40">Orden, fórmula, o prescripción del médico tratante del: servicio médico, examen, insumos, procedimiento NO POS</label><br>
                                                    <input type="file" id="file_40" name="userfile[40]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[40]" value="Orden, fórmula, o prescripción del médico tratante del: servicio médico, examen, insumos, procedimiento NO POS">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_41">Copia del Formulario Mipres (del servicio médico, examen, insumos, procedimiento NO PBS), en caso de tenerlo</label><br>
                                                    <input type="file" id="file_41" name="userfile[41]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[41]" value="Copia del Formulario Mipres (del servicio médico, examen, insumos, procedimiento NO PBS), en caso de tenerlo">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_42">Acta junta médica, si la hay</label><br>
                                                    <input type="file" id="file_42" name="userfile[42]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[42]" value="Acta junta médica, si la hay">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_43">Copia cédula de ciudadanía</label><br>
                                                    <input type="file" id="file_43" name="userfile[43]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[43]" value="Copia cédula de ciudadanía">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_44">Si es apoderado, poder debidamente conferido</label><br>
                                                    <input type="file" id="file_44" name="userfile[44]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[44]" value="Si es apoderado, poder debidamente conferido">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_45">Demás documentos que considere pertinentes y que sirvan de prueba de acuerdo con lo solicitado</label><br>
                                                    <input type="file" id="file_45" name="userfile[45]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png, application/zip">
                                                    <input type="hidden" name="tipo_documento[45]" value="Otros documentos">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                            </div>
                                            <div class="row" data-tipo="Reconocimiento económico de gastos en salud" style="display:none;">
                                                <div class="col-md-12 form-group">
                                                    <label for="file_46">Fotocopia de la cédula de ciudadania del solicitante</label><br>
                                                    <input type="file" id="file_46" name="userfile[46]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[46]" value="Fotocopia de la cédula de ciudadania del solicitante">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_47">Copia de la historia clínica elaborada por la IPS donde fue atendido, con ocasión de la patología por la cual reclama y/o prescripción del médico tratante</label><br>
                                                    <input type="file" id="file_47" name="userfile[47]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[47]" value="Copia de la historia clínica elaborada por la IPS donde fue atendido, con ocasión de la patología por la cual reclama y/o prescripción del médico tratante">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_48">Copia de la factura (s) donde conste el pago efectuado</label><br>
                                                    <input type="file" id="file_48" name="userfile[48]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[48]" value="Copia de la factura (s) donde conste el pago efectuado">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_48">Pruebas que acrediten la negativa injustificada de atención o si es posible, copia del formato de negación de servicios de salud y medicamentos</label><br>
                                                    <input type="file" id="file_48" name="userfile[48]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[48]" value="Pruebas que acrediten la negativa injustificada de atención o si es posible, copia del formato de negación de servicios de salud y medicamentos">
                                                    <a href="#" class="descargar btn btn-sm btn-link">descargar</a>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="file_1">Reconocimiento económico de gastos en salud y/o otros</label><br>
                                                    <input type="file" id="file_1" name="userfile[1]" accept="image/tiff, image/jpeg, application/pdf, application/msword, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png">
                                                    <input type="hidden" name="tipo_documento[1]" value="Otros documentos">
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
					        <span class="error small">Seleccione al menos un medio sobre el cual desea recibir información de su demanda</span>
                        </div>
                    </div>
                    <div class="row">
                        <br>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 form-group" style="text-align: justify">
                            Al hacer clic el botón enviar, usted acepta la remisión de la demanda jurisdiccional a la entidad Superintendencia Nacional de Salud. Sus datos serán recolectados y tratados conforme con la <a href="https://www.supersalud.gov.co/es-co/transparencia-y-acceso-a-la-informacion-publica/informaci%C3%B3n-de-la-entidad/politicas-de-privacidad-y-condiciones-de-uso" target="_blank">Política de Tratamiento de Datos.</a> En la opción <a href="https://www.supersalud.gov.co/es-co/Paginas/jurisdiccional/consulta-de-procesos-jurisdiccionales.aspx">consulta de proceso jurisdiccional</a> podrá verificar el estado de la respuesta.
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
                            <input type="hidden" id="tipoSolicitud" name="tipoSolicitud" value="Demanda jurisdiccional"/>
                            <input type="hidden" id="tipoUsuario" name="tipoUsuario" value=""/>
                            <input type="hidden" id="adjuntosSubidos" name="adjuntosSubidos" value=""/>
                            <input type="hidden" name="pais" value="170">
                            <input type="button" class="btn btn-round btn-high" id="enviar-jurisdiccional" value="Enviar">
                            <input type="button" id="borrar" class="btn btn-round btn-middle" value="Borrar">
					        <a href="https://www.supersalud.gov.co/es-co/Paginas/Protecci%C3%B3n%20al%20Usuario/pqrd.aspx" class="btn btn-round btn-middle">Volver</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form action="jurisdiccional_anexo_back.php" id="form-carga" enctype="multipart/form-data" method="post">
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
                                            <h4 class="section-h">Aquí puedes adjuntar archivos al proceso jurisdiccional existente</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <fieldset>
                                        <label for="apoderado_o_oficioso">* Identifique la calidad del sujeto procesal <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione la calidad del sujeto procesal"></i></label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="calidad_sujeto_procesal" id="calidad_sujeto_procesal_1" value="Demandante" data-required="true" data-label="Calidad de apoderado o agente oficioso">
                                            <label class="form-check-label" for="calidad_sujeto_procesal_1">Demandante</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="calidad_sujeto_procesal" id="calidad_sujeto_procesal_2" value="Demandado o vinculado" data-required="true">
                                            <label class="form-check-label" for="calidad_sujeto_procesal_2">Demandado o vinculado</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="calidad_sujeto_procesal" id="calidad_sujeto_procesal_3" value="Requerido" data-required="true">
                                            <label class="form-check-label" for="calidad_sujeto_procesal_3">Requerido</label>
                                        </div>
                                        <span class="error small">Seleccione la calidad del sujeto procesal</span>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label for="">* Número de proceso jurisdiccional <small>Ejemplo: J-XXXX-XXXX</small> <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite el número de proceso jurisidiccional"></i></label>
                                    <input name="radicado" id="radicado" type="text" class="form-control" data-required="true">
                                    <span class="error small">Ingrese el número de proceso jurisdiccional</span>
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
                                            <h4 class="section-h" data-demandante>Información del demandante</h4>
                                            <h4 class="section-h" style="display:none;" data-demandado>Información del demandado o vinculado</h4>
                                            <h4 class="section-h" style="display:none;" data-requerido>Información del requerido</h4>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 form-group">
                                            <label for="tipo_sujeto">* Seleccione el tipo de persona <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el tipo de persona."></i></label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_sujeto" id="tipo_sujeto_1" value="1" data-required="true" data-label="Tipo de persona">
                                                <label class="form-check-label" for="tipo_sujeto_1">Natural</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_sujeto" id="tipo_sujeto_2" value="2" data-required="true">
                                                <label class="form-check-label" for="tipo_sujeto_2">Jurídica</label>
                                            </div>
                                            <span class="error small">Seleccione el tipo de persona del sujeto procesal</span>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="id">* Tipo de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el tipo de identificación"></i></label>
                                            <select name="tipo_identificacion_sujeto" id="tipo_identificacion_sujeto" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Tipo de identificación apoderado">
                                                <?php foreach ($tipos_documentos as $tipo) { ?>
                                                    <option value="<?=$tipo['TDID_CODI']?>"><?=$tipo['TDID_DESC']?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="error small">Seleccione el tipo de identificación del sujeto procesal</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="id_sujeto">* Número de identificación <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número del documento de identificación, recuerde que si selecciona pasaporte debe ingresar números y letras de lo contrario sólo números o si el tipo de identificación es NIT ingréselo sin el guión"></i></label>
                                            <input type="number" class="form-control" id="id_sujeto" name="id_sujeto" autocomplete="no"  data-required="true" data-label="Número de identificación">
                                            <span class="error small">Ingrese el número de identificación del sujeto procesal</span>
                                        </div>
                                    </div>
                                    <div class="form-row" data-natural>
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
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="pais_sujeto">* País <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el país en el que reside"></i></label>
                                            <select name="pais_sujeto" id="pais_sujeto" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="País">
                                                <?php foreach ($paises as $pais) { ?>
                                                    <option value="<?=$pais['NOMBRE']?>"><?=$pais['NOMBRE']?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="error small">Seleccione el país de residencia del sujeto procesal</span>
                                        </div> 
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="departamento_sujeto">* Departamento <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el departamento en el que reside"></i></label>
                                            <select name="departamento_sujeto" id="departamento_sujeto" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Departamento apoderado">
                                                <?php foreach ($departamentos as $departamento) { ?>
                                                    <option value="<?=$departamento['DPTO_CODI']?>"><?=$departamento['DPTO_NOMB']?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="error small">Seleccione el departamento de residencia del sujeto procesal</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="ciudad_sujeto">* Municipio <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Seleccione el municipio en el que vive"></i></label>
                                            <select name="ciudad_sujeto" id="ciudad_sujeto" title="Seleccionar" data-live-search="true" data-size="5" class="form-control" data-required="true" data-label="Municipio apoderado"></select>
                                            <div id="ciudad_bar_sujeto" class="progress" style="display:none; height:5px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                            </div>
                                            <small id="emailHelp" class="form-text text-muted">*ANM Areas no municipalizadas</small>
                                            <span class="error small">Seleccione el municipio de residencia del sujeto procesal</span>
                                        </div>
                                        <div class="col-md-6 form-group" data-natural data-juridico style="display:none;">
                                            <label for="provincia_sujeto">* Ciudad / Estado / Provincia</label>
                                            <input type="text" class="form-control" id="provincia_sujeto" name="provincia_sujeto" autocomplete="no" data-required="true" data-label="Ciudad / Estado / Provincia apoderado">
                                            <span class="error small">Ingrese la ciudad, estado y/o provincia de residencia del sujeto procesal</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="direccion_sujeto">* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i></label>
                                            <input type="text" class="form-control" id="direccion_sujeto" name="direccion_sujeto" autocomplete="no" data-required="true" data-label="Dirección apoderado">
                                            <span class="error small">Ingrese la dirección del sujeto procesal</span>
                                        </div>
                                    </div>
                                    <div class="form-row" data-natural data-juridico>
                                        <div class="col-md-3 form-group">
                                            <label for="celular_sujeto">* Celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                            <input type="number" class="form-control" id="celular_sujeto" name="celular_sujeto" autocomplete="no" data-required="true" data-label="Celular">
                                            <span class="error small">Ingrese el celular del sujeto procesal</span>
                                        </div>
                                        <div class="col-md-3 form-group" data-natural data-juridico>
                                            <label for="telefono_sujeto">* Teléfono fijo o celular <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite su número de teléfono fijo o celular, si no cuenta con esta información, seleccione la casilla de selección para deshabilitar el campo"></i></label>
                                            <input type="number" class="form-control" id="telefono_sujeto" name="telefono_sujeto" autocomplete="no" data-required="true" data-label="Teléfono fijo o celular">
                                            <span class="error small">Ingrese un teléfono fijo o celular adicional del sujeto procesal</span>
                                        </div>
                                        <div class="col-md-6" data-natural data-juridico>
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
                                                <option value="Contestación a requerimiento">Contestación a requerimiento</option>
                                                <option value="Impugnación y/o apelación">Impugnación y/o apelación</option>
                                                <option value="Incumplimiento de la sentencia">Incumplimiento de la sentencia</option>
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
                                    <div class="form-row">
                                        <div class="col-md-12 form-group limited-textarea">
                                            <label for="comentarios_2">
                                                * Describa brevemente la actuación y el J-XXXX-XXXX, ejemplo "Contestación proceso jurisdiccional J-2021-9999": 
                                                <i data-demandado class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Describa si se trata de una contestación de demanda, contestación a requerimiento, impugnación y/o apelación"></i>
                                                <i data-demandante style="display: none;" class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Describa si se trata de una contestación a requerimiento, impugnación y/o apelación"></i>
                                                <i data-requerido style="display: none;" class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Describa si se trata de una contestación a requerimiento"></i>
                                            </label>
                                            <textarea id="comentarios_2" name="comentarios" class="form-control" rows="2" autocomplete="no" data-required="true" maxlength="50"></textarea>
                                            <span class="size" data-max="50">0/50</span>
					                        <span class="error small">Ingrese el texto que describe el archivo que esta cargando, máximo 50 caracteres</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <h4 class="section-h">Agregue los anexos o soportes de la actuación</h4>
                        </div>
                    </div>
                    <div id="archivos" class="row">
                        <div class="col-md-12">
                            <label for="">Donde aparece el icono adjuntar soportes, suba los soportes que considere pueden servir para su actuación si estos son muy pesados los puede comprimir, puede adjuntar archivos con un tamaño de 20 MB. <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Formatos validos: (pdf, xls, xlsx, doc, tif, tiff, jpeg, jpg)"></i></label>
                            <br> 
                            <small>Si requiere adjuntar mas archivos, de clic sobre el botón Adjuntar soportes</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"><br></div>
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
                            <input type="button" class="btn btn-round btn-high" id="enviar-jurisdiccional-anexo" value="Enviar">
                            <input type="button" id="borrar-anexos" class="btn btn-round btn-middle" value="Borrar">
					        <a href="https://www.supersalud.gov.co/es-co/Paginas/Protecci%C3%B3n%20al%20Usuario/pqrd.aspx" class="btn btn-round btn-middle">Volver</a>
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
                    <h4 class="section-h" id="modalLabel">Información</h4>
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
                    <h4 class="section-h" id="modalLabel">Información</h4>
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
    
    <div class="modal fade" id="modalPreteniones" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="section-h" id="modalLabel">Tipos de pretensión</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul>
                        <li>
                            <b>Cobertura de servicios incluidos del Plan de Beneficios en Salud- PBS:</b><br>
                            Si su Entidad Promotora de Salud- EPS o entidad que se le asimile le niega los servicios, tecnologías en salud o procedimientos incluidos en el Plan de Beneficios- PBS
                        </li>
                        <li>
                            <b>Reconocimiento económico de gastos en salud:</b><br>
                            Si usted, como usuario o afiliado del SGSSS incurre en algún gasto:<br> - Por la atención de un servicio de urgencia.<br> - Por la atención específica que le haya sido autorizada por la EPS o las entidades que se le asimilen.<br> - Por la imposibilidad, negativa injustificada o negligencia demostrada de la EPS o entidades que se le asimilen.
                        </li>
                        <li>
                            <b>Conflictos por multiafiliación dentro del Sistema General de Seguridad Social en Salud- SGSSS:</b><br>
                            Si usted aparece afiliado en varias EPS o en varios regímenes en el SGSSS, de manera simultánea.
                        </li>
                        <li>
                            <b>Conflictos por libre elección y movilidad:</b><br>
                            Si le niegan la libre elección para afiliarse a la EPS de su interés, o le impiden elegir las Instituciones Prestadoras de Servicios de Salud- IPS dentro de la red conformada por su EPS, o tiene conflictos relacionados con la movilidad dentro del SGSSS.
                        </li>
                        <li>
                            <b>Conflictos derivados de las devoluciones o glosas a las facturas, reclamaciones y recobros de servicios No PBS:</b><br>
                            Si como entidad o institución del SGSSS presenta conflictos derivados de las devoluciones o glosas a las facturas por servicios de salud, recobros por servicios no incluidos en el PBS y reclamaciones ante la ADRES.
                        </li>
                        <li>
                            <b>Prestaciones no incluidas en el Plan de Beneficios en Salud- PBS:</b><br>
                            Si tiene un conflicto con su EPS o entidad que se le asimile, por la garantía de la prestación de los servicios y tecnologías NO incluidas en el PBS, con excepción de aquellos expresamente excluidos de la financiación con recursos públicos asignados a la salud.
                        </li>
                    </ul>
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
                var anexos = $(this).closest('body');
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

            //JURISDICCIONAL
            $('#borrar').on('click', function(e) {
				$("#form-jurisdiccional")[0].reset();
				$('select').each(function(e) {
					$(this).val('').trigger('change');
                    $('#ciudad_bar_apoderado').hide();
                    $('#ciudad_bar_demandante').hide();
				});
			});

            function ocultarArchivosPorTipo() {
                $('div[data-tipo="Cobertura de servicios incluidos del Plan de Beneficios en Salud- PBS"]').hide();
                $('div[data-tipo="Para glosas"]').hide();
                $('div[data-tipo="Para recobros NO PBS"]').hide();
                $('div[data-tipo="Para reclamaciones ante ECAT"]').hide();
                $('div[data-tipo="Conflictos por libre elección y movilidad"]').hide();
                $('div[data-tipo="Conflictos por multiafiliación dentro del Sistema General de Seguridad Social en Salud- SGSSS"]').hide();
                $('div[data-tipo="Prestaciones no incluidas en el Plan de Beneficios en Salud- PBS"]').hide();
                $('div[data-tipo="Reconocimiento económico de gastos en salud"]').hide();
            }

            function validarAnexosDemandas() {
                var errors = 0;
                var errors_text = '<p>Esta seguro de enviar el formulario sin los siguientes anexos: </p><ul>';
                $('#anexos_demandas input[type="file"]').each(function(i, e) {
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
                    $('#modalAnexos .modal-body').html(errors_text);
                    $('#modalAnexos').modal('show');
                } else {
                    $('#form-jurisdiccional').submit();
                }
            }
            
            $('select[name="tipo_demanda"]').on('change', function(e) {
                $('#subtipos_demandas').val('').trigger('change');
                if ($(this).val() == 'Conflictos derivados de las devoluciones o glosas a las facturas, reclamaciones y recobros de servicios No PBS') 
                {
                    $('#subtipo').show();
                } else {
                    $('#subtipo').hide();
                }
                ocultarArchivosPorTipo();
                $('div[data-tipo="'+$(this).val()+'"]').show();
            });

            $('select[name="subtipos_demandas"]').on('change', function(e) {
                ocultarArchivosPorTipo();
                $('div[data-tipo="'+$(this).val()+'"]').show();
            });

            //demandante
            var tipo_identificacion_demandante = $('select[name="tipo_identificacion_demandante"]').html();

            $('input[name="tipo_demandante"]').on('change', function(e) 
			{
                var options_html = $('<div></div>').html(tipo_identificacion_demandante);
				options_html.find('.bs-title-option').remove();
				var tipo = $(this).val();

				var label_direccion_demandante = '* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i>';

				switch(tipo)
                {
					case '1':
						$('#datos_demandante div[data-juridico]').hide();
						$('#datos_demandante div[data-natural]').show();
						$('select[name="pais_demandante"]').trigger('change');
						$('select[name="tipo_identificacion_demandante"]').html(options_html.html());
						$('select[name="tipo_identificacion_demandante"]').selectpicker('refresh');
					break;
					case '2':
						$('#datos_demandante div[data-natural]').hide();
						$('#datos_demandante div[data-juridico]').show();
						options_html.html('<option value="4">Nit</option>');
						$('select[name="tipo_identificacion_demandante"]').html(options_html.html());
						$('select[name="tipo_identificacion_demandante"]').selectpicker('refresh');
						$('select[name="pais_demandante"]').trigger('change');

                        label_direccion_demandante = '* Dirección de notificación judicial <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de notificación judicial"></i>';
					break;
				}
                
                $('label[for="direccion_demandante"]').html(label_direccion_demandante);
				$('[data-toggle="tooltip"]').tooltip();
			});

            $('#departamento_demandante').on('change', function(e) 
            {
				$('#ciudad_bar_demandante').show();
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

						$('#ciudad_demandante').html(options);
						$('#ciudad_demandante').selectpicker('refresh');
						$('#ciudad_bar_demandante').hide();
					}
				})
			});

            //apoderado
            $('input[name="apoderado_o_oficioso"]').on('change', function(e) {
                var agente = $(this).val();

                if(agente == "Si")
                {
                    $('#datos_apoderado').show();
                } else {
                    $('#datos_apoderado').hide();
                }
            });

            var tipo_identificacion_apoderado = $('select[name="tipo_identificacion_apoderado"]').html();
            $('input[name="tipo_apoderado"]').on('change', function(e) 
			{
                var options_html = $('<div></div>').html(tipo_identificacion_apoderado);
				options_html.find('.bs-title-option').remove();
				var tipo = $(this).val();
		
				var label_direccion_apoderado = '* Dirección <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de su residencia"></i>';

                switch(tipo){
					case '1':
						$('#datos_apoderado div[data-juridico]').hide();
						$('#datos_apoderado div[data-natural]').show();
						$('select[name="pais_apoderado"]').trigger('change');
						$('select[name="tipo_identificacion_apoderado"]').html(options_html.html());
						$('select[name="tipo_identificacion_apoderado"]').selectpicker('refresh');
					break;
					case '2':
						$('#datos_apoderado div[data-natural]').hide();
						$('#datos_apoderado div[data-juridico]').show();
						options_html.html('<option value="4">Nit</option>');
						$('select[name="tipo_identificacion_apoderado"]').html(options_html.html());
						$('select[name="tipo_identificacion_apoderado"]').selectpicker('refresh');
						$('select[name="pais_apoderado"]').trigger('change');

                        label_direccion_apoderado = '* Dirección de notificación judicial <i class="bi bi-question-circle" data-toggle="tooltip" data-placement="top" title="Digite la dirección de notificación judicial"></i>';
					break;
				}
                
                $('label[for="direccion_apoderado"]').html(label_direccion_apoderado);
				$('[data-toggle="tooltip"]').tooltip();
			});

            $('#departamento_apoderado').on('change', function(e) {
				$('#ciudad_bar_apoderado').show();
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

						$('#ciudad_apoderado').html(options);
						$('#ciudad_apoderado').selectpicker('refresh');
						$('#ciudad_bar_apoderado').hide();
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

            $('#enviar-jurisdiccional').on('click', function(e) {
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
									valor = $('input[name="'+name+'"]:visible:checked').val();
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
				
				if($('#correo_demandante').attr('data-required') == 'true')
				{
					if($('#correo_demandante').is(':visible') && !isEmail($('#correo_demandante').val()+'@'+$('#dominio_demandante').val()))
					{
						$('#correo_demandante').removeClass('is-valid');
						$('#correo_demandante').addClass('is-invalid');
						$('#dominio_demandante').removeClass('is-valid');
						$('#dominio_demandante').addClass('is-invalid');
						errors ++;
                        errors_text += '<li>El correo del demandante es invalido</li>';
					} else {
						$('#correo_demandante').removeClass('is-invalid');
						$('#correo_demandante').addClass('is-valid');
						$('#dominio_demandante').removeClass('is-invalid');
						$('#dominio_demandante').addClass('is-valid');
					}
				}

				if($('#correo_apoderado').attr('data-required') == 'true')
				{
					if($('#correo_apoderado').is(':visible') && !isEmail($('#correo_apoderado').val()+'@'+$('#dominio_apoderado').val()))
					{
						$('#correo_apoderado').removeClass('is-valid');
						$('#correo_apoderado').addClass('is-invalid');
						$('#dominio_apoderado').removeClass('is-valid');
						$('#dominio_apoderado').addClass('is-invalid');
						errors ++;
                        errors_text += '<li>El correo del apoderado es invalido</li>';
					} else {
						$('#correo_apoderado').removeClass('is-invalid');
						$('#correo_apoderado').addClass('is-valid');
						$('#dominio_apoderado').removeClass('is-invalid');
						$('#dominio_apoderado').addClass('is-valid');
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
				}else{
                    validarAnexosDemandas();
					e.preventDefault();
				}
			});

            $('#submit-jurisdiccional').on('click', function(e) {
                $('#form-jurisdiccional').submit();
            });

			$('#comentarios_1').on('keyup', function(e) {
				var comentarios = $('#comentarios_1').val();
				$('.size').text(comentarios.length+'/5000');
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
                            servicio: 'expediente',
                            radicado: $(this).val()
                        },
                        'json'
                    );

                    request.done(function(res) {
                        if (res.length == 0 || !res)
                        {
                            $('#res-expediente').removeClass('text-success').addClass('text-danger');
                            $('#res-expediente').text('El número de proceso judicial no existe');
                        } else {
                            $('#res-expediente').removeClass('text-danger').addClass('text-success');
                            $('#res-expediente').text('Número de proceso judicial valido');
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
                    case 'Demandante':
                        $('*[data-demandado]').hide();
                        $('*[data-requerido]').hide();
                        $('*[data-demandante]').show();

                        var tipos_documentos_html = $('<div></div>').html('<option value="Contestación a requerimiento">Contestación a requerimiento</option><option value="Impugnación y/o apelación">Impugnación y/o apelación</option><option value="Incumplimiento de la sentencia">Incumplimiento de la sentencia</option><option value="Otros">Otros</option>');
						$('select[name="tipo_documento[0]"]').html(tipos_documentos_html.html());
						$('select[name="tipo_documento[0]"]').selectpicker('refresh');
                    break;
                    case 'Demandado o vinculado':
                        $('*[data-requerido]').hide();
                        $('*[data-demandante]').hide();
                        $('*[data-demandado]').show();
                        
                        var tipos_documentos_html = $('<div></div>').html('<option value="Contestación de demanda">Contestación de demanda</option><option value="Contestación a requerimiento">Contestación a requerimiento</option><option value="Impugnación y/o apelación">Impugnación y/o apelación</option><option value="Otros">Otros</option>');
						$('select[name="tipo_documento[0]"]').html(tipos_documentos_html.html());
						$('select[name="tipo_documento[0]"]').selectpicker('refresh');
                    break;
                    case 'Requerido':
                        $('*[data-demandado]').hide();
                        $('*[data-demandante]').hide();
                        $('*[data-requerido]').show();

                        var tipos_documentos_html = $('<div></div>').html('<option value="Contestación a requerimiento">Contestación a requerimiento</option><option value="Otros">Otros</option>');
						$('select[name="tipo_documento[0]"]').html(tipos_documentos_html.html());
						$('select[name="tipo_documento[0]"]').selectpicker('refresh');
                    break;
                }
            });

            var tipo_identificacion_sujeto = $('select[name="tipo_identificacion_sujeto"]').html();
            $('input[name="tipo_sujeto"]').on('change', function(e) 
			{
                var options_html = $('<div></div>').html(tipo_identificacion_sujeto);
				options_html.find('.bs-title-option').remove();
				var tipo = $(this).val();
		
                switch(tipo){
					case '1':
						$('#sujeto div[data-juridico]').hide();
						$('#sujeto div[data-natural]').show();
						$('select[name="pais_sujeto"]').trigger('change');
						$('select[name="tipo_identificacion_sujeto"]').html(options_html.html());
						$('select[name="tipo_identificacion_sujeto"]').selectpicker('refresh');
					break;
					case '2':
						$('#sujeto div[data-natural]').hide();
						$('#sujeto div[data-juridico]').show();
						options_html.html('<option value="4">Nit</option>');
						$('select[name="tipo_identificacion_sujeto"]').html(options_html.html());
						$('select[name="tipo_identificacion_sujeto"]').selectpicker('refresh');
						$('select[name="pais_sujeto"]').trigger('change');
					break;
				}
                
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
						<div class="col-md-11 form-group" style="padding-top:3px">
							<input type="hidden" class="ignore" name="tipo_documento[]" value="Anexo">
							<input name="userfile[]" class="file" type="file" data-required="true" accept="image/tiff, image/jpeg, application/pdf, application/msword, text/plain, image/gif, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png, application/zip, audio/mpeg, video/mp4">
                            <span class="error small">Seleccione el archivo que desea cargar</span>
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

            $('#enviar-jurisdiccional-anexo').on('click', function(e) {
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
							$(this).closest('div').removeClass('is-invalid');
						else 
							$(this).removeClass('is-invalid');
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
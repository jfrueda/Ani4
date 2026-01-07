<?php
session_start();

$ruta_raiz = "..";
require_once("$ruta_raiz/include/db/ConnectionHandler.php");
require_once("$ruta_raiz/processConfig.php");
require_once('funciones.php');
include_once('./adjuntarArchivos.php');
include_once("$ruta_raiz/include/tx/roles.php");

define(NATURAL, '1');
define(JURIDICO, '2');
define(ANONIMO, '3');
define(NINOS, '4');
define('ADODB_ASSOC_CASE', 2);
$ADODB_COUNTRECS = false;
$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
if($radi_pass != $_POST['radi_pass']) 
{
    header("HTTP/1.1 403 Forbidden");
    exit("Acceso denegado.");
}

//carga de anexos
$uploader = new Uploader($_FILES);
$uploader->FILES = $_FILES;
$adjuntosSubidos = json_decode($adjuntosSubidos);
$uploader->subidos = $adjuntosSubidos;
$uploader->adjuntarArchivos();

//variables
$pais = is_empty($_POST['pais'], 'Colombia');
$anoRad = date("Y");
$dependencia_destino = is_empty($_POST['depe_codi'], 21000);
$usuario_destino = is_empty($_POST['usua_codi'], 11513);
$depedencia_radicacion = 900;
$usuario_radicacion = 1;
$codigo_verificacion = substr(sha1(microtime()), 0 , 5);
$medio_recepcion = is_empty($_POST['mrec_codi'], 3);
$tipo = 275;

//variables anexos 
//$tipo_documento = $_POST['tipo_documento'];

//datos afectado
$afectado = [
    'tipo' => 1,
    'id' => $_POST['afectado_id'],
    'tipo_id' => $_POST['afectado_tipo_id'],
    'pais' => is_empty($_POST['afectado_pais'], 'Colombia'),
    'departamento' => is_empty($_POST['afectado_departamento'], null),
    'municipio' => is_empty($_POST['afectado_municipio'], null),
    'provincia' => is_empty($_POST['afectado_provincia'], ''),
    'direccion' => $_POST['afectado_direccion'],
    'nombres' => strtoupper(trim($_POST['afectado_nombre_1'].' '.$_POST['afectado_nombre_2'])),
    'apellidos' => strtoupper(trim($_POST['afectado_apellido_1'].' '.$_POST['afectado_apellido_2'])),
    'apellido_1' => strtoupper(trim($_POST['afectado_apellido_1'])),
    'apellido_2' => strtoupper(trim($_POST['afectado_apellido_2'])),
    'rason_social' => is_empty($_POST['afectado_rason_social'], ''),
    'celular' => is_empty($_POST['afectado_celular'], ''),
    'telefono' => is_empty($_POST['afectado_telefono'], ''),
    'email' => $_POST['afectado_correo'].'@'.$_POST['afectado_dominio'] != '@' ? $_POST['afectado_correo'].'@'.$_POST['afectado_dominio'] : '',
    'es_peticionario' => $_POST['es_peticionario']
];

$anonimo = [
    'tipo' => ANONIMO,
    'id' => 0,
    'tipo_id' => 0,
    'pais' => 'Colombia',
    'departamento' => 0,
    'municipio' => 0,
    'provincia' => '',
    'direccion' => '',
    'nombres' => 'Anónimo',
    'apellidos' => '',
    'apellido_1' => '',
    'apellido_2' => '',
    'rason_social' => '',
    'celular' => 'No registra',
    'telefono' => 'No registra',
    'email' => 'No registra'
];

//datos para dirección
if($afectado['es_peticionario'] == 'No')
{
    $peticionario = [
        //cuando en delegatura seleccionen niños se cambia el tipo a natural.
        'tipo' => $_POST['peticionario_tipo'] == NINOS ? NATURAL : $_POST['peticionario_tipo'],
        'id' => $_POST['peticionario_id'],
        'tipo_id' => $_POST['peticionario_tipo_id'],
        'pais' => is_empty($_POST['peticionario_pais'], 'Colombia'),
        'departamento' => is_empty($_POST['peticionario_departamento'], null),
        'municipio' => is_empty($_POST['peticionario_municipio'], null),
        'provincia' => is_empty($_POST['peticionario_provincia'], ''),
        'direccion' => $_POST['peticionario_direccion'],
        'nombres' => strtoupper(trim($_POST['peticionario_nombre_1'].' '.$_POST['peticionario_nombre_2'])),
        'apellidos' => strtoupper(trim($_POST['peticionario_apellido_1'].' '.$_POST['peticionario_apellido_2'])),
        'apellido_1' => strtoupper(trim($_POST['peticionario_apellido_1'])),
        'apellido_2' => strtoupper(trim($_POST['peticionario_apellido_2'])),
        'rason_social' => is_empty($_POST['peticionario_rason_social'], ''),
        'celular' => is_empty($_POST['peticionario_celular'], ''),
        'telefono' => is_empty($_POST['peticionario_telefono'], ''),
        'email' => $_POST['peticionario_correo'].'@'.$_POST['peticionario_dominio'] != '@' ? $_POST['peticionario_correo'].'@'.$_POST['peticionario_dominio'] : ''
    ];
} else {
    $peticionario = $afectado;
}

$asunto = $_POST['asunto'];
$entidad_solicitud = $db->conn->getOne("SELECT nombre_tipo FROM sgd_tipo_eps WHERE id = ?", [$_POST['tipo_entidad']]).' - '.$db->conn->getOne("SELECT nombre_eps FROM sgd_eps WHERE id = ?", [$_POST['entidad']]);

$comentarios = "\n\nDetalles del caso: ".$_POST['comentarios'];
$comentarios .= "\n\n¿Los hechos están relacionados con la entrega de medicamentos?: ".($_POST['hechos_relacionados_medicamentos'] == '1' ? 'Si' : 'No');
if ($_POST['hechos_relacionados_medicamentos'] == '1') {
    $ids_medicamentos = explode(',', $_POST['medicamentos_seleccionados']);
    if (count($ids_medicamentos) > 0)
        $comentarios .= "\n";

    foreach($ids_medicamentos as $id_medicamento) {
        $medicamento = $db->conn->getRow("SELECT * FROM pqrd_medicamento WHERE id = ?", [$id_medicamento]);
        $comentarios .= "\n".$medicamento['MEDICAMENTO'];
    }

    if (count($ids_medicamentos) >= 5) {
        $comentarios .= "\n\n¿Requiere más de 5 medicamentos?: ".($_POST['requiere_mas_medicamentos'] == '1' ? 'Si' : 'No');
    }
}
$comentarios .= "\n\n¿Tiene orden médica?: ".($_POST['orden_medica'] == '1' ? 'Si' : 'No');
$comentarios .= "\n\n¿Ya presentó su reclamo o solicitud ante la EPS o entidad responsable de garantizar los servicios de salud?: ".($_POST['reclamo_asegurador'] == '1' ? 'Si' : 'No');

// Si no trae datos de afectado no imprimirlos en el PDF
if ($afectado['id'] != '') 
{
    $comentarios .= "\n\nAfectado: ".$afectado['nombres'].' '.$afectado['apellidos'].
            "\nTipo de identificación: ".$db->conn->getOne("SELECT TDID_DESC FROM tipo_doc_identificacion WHERE tdid_codi = ?", [$afectado['tipo_id']]).
            "\nNúmero de identificación: ".$afectado['id'].
            "\nPaís: ".$afectado['pais'].' '.
            ($afectado['pais'] == "Colombia" ? "\nDepartamento: ".$db->conn->getOne("SELECT DPTO_NOMB FROM departamento WHERE dpto_codi = ?", [$afectado['departamento']]) : $afectado['provincia']).
            ($afectado['pais'] == "Colombia" ? "\nMunicipio: ".$db->conn->getOne("SELECT MUNI_NOMB FROM municipio WHERE muni_codi = ? and dpto_codi = ? ", [$afectado['municipio'], $afectado['departamento']]) : '').
            "\nDirección: ".$afectado['direccion'].
            "\nTeléfono: ".$afectado['telefono'].
            "\nCelular: ".$afectado['celular'].
            "\nCorreo: ".$afectado['email'].
            "";
}

if($afectado['es_peticionario'] == 'No') {

    if($peticionario['tipo'] != ANONIMO)
    {
        $comentarios .= "\n\nPeticionario: ".($peticionario['tipo'] != JURIDICO ? $peticionario['nombres'].' '.$peticionario['apellidos'] : $peticionario['rason_social']).
                "\nTipo de identificación: ".$db->conn->getOne("SELECT TDID_DESC FROM tipo_doc_identificacion WHERE tdid_codi = ?", [$peticionario['tipo_id']]).
                "\nNúmero de identificación: ".$peticionario['id'].
                "\nPaís: ".$peticionario['pais'].
                ($peticionario['pais'] == "Colombia" ? "\nDepartamento: ".$db->conn->getOne("SELECT DPTO_NOMB FROM departamento WHERE dpto_codi = ?", [$peticionario['departamento']]) : $peticionario['provincia']).
                ($peticionario['pais'] == "Colombia" ? "\nMunicipio: ".$db->conn->getOne("SELECT MUNI_NOMB FROM municipio WHERE muni_codi = ? and dpto_codi = ? ", [$peticionario['municipio'], $peticionario['departamento']]) : '').
                "\nDirección: ".$peticionario['direccion'].
                "\nTeléfono: ".$peticionario['telefono'].
                "\nCelular: ".$peticionario['celular'].
                "\nCorreo: ".$peticionario['email'].
                "";
    } else {
        $comentarios .= "\n\nPeticionario: Anónimo";
    }
}

if($entidad_solicitud)
{
    $comentarios .= "\n\nDatos de la entidad denunciada:".
                "\n".$entidad_solicitud;
}

if($_POST['hechos_relacionados_centro_de_salud'] == 'true')
{
    $ips = $db->conn->getOne("SELECT nombre_eps FROM sgd_eps WHERE id = ?", [$_POST['ips_id']]);
    $comentarios .= "\n\nRelacionados con una clínica, hospital o centro de salud: ".
                    "\n".$ips.' '.$_POST['otra_ips'];

    $comentarios .= "\nDepartamento: ".$db->conn->getOne("SELECT DPTO_NOMB FROM departamento WHERE dpto_codi = ?", [$_POST['departamento_ips']]);

    $comentarios .= "\nMunicipio: ".$db->conn->getOne("SELECT MUNI_NOMB FROM municipio WHERE homologa_muni = ?", [$_POST['municipio_ips']]);
}



//TODO Imprimir el grupo de poblacional haciendo la consulta a sgd_tma_temas
//$_SESSION['desc'].= textoPDF("Manifiesto que pertenezco al grupo pblacional: " );
$descripcion = textoPDF($comentarios);
$descripcion .= textoPDF("\n\n".$uploader->listadoImprimible);

$numero = str_pad($db->conn->GenID("SECR_TP2_".$secRadicaFormularioWeb),$digitosSecRad,'0',STR_PAD_LEFT);
$dependenciaCompletada = str_pad($dependencia_destino,$digitosDependencia,'0',STR_PAD_LEFT);

/**
 * $depeRadicaFormularioWeb;  // Es radicado en la Dependencia 900
 * $usuaRecibeWeb ; // Usuario que Recibe los Documentos Web
 * $secRadicaFormularioWeb ;
 **/
$numeroRadicado = date('Y').$dependenciaCompletada.$numero."2";
if ($_POST['log_id'] != '') {
    $db->conn->Execute('UPDATE pqrd_registro_web_log SET radicado = ? WHERE id = ?', [$numeroRadicado, $_POST['log_id']]);
}

// DIRECCIONES
$num_dir=$db->conn->GenID('SEC_DIR_DRECCIONES');
$direccion_table = 'sgd_dir_drecciones';
if($afectado['es_peticionario'] == 'No')
{
    switch($peticionario['tipo'])
    {
        case NATURAL:

            $num_ciu = $db->conn->GenID('SEC_CIU_CIUDADANO');
            $peticionario_table = 'sgd_ciu_ciudadano';
            $peticionario_data = [
                'tdid_codi' => $peticionario['tipo_id'],
                'sgd_ciu_codigo' => $num_ciu,
                'sgd_ciu_nombre' => $peticionario['nombres'],
                'sgd_ciu_direccion' => ($peticionario['provincia'] != '' ? $peticionario['pais'].' '.$peticionario['provincia'].' ' : '').$peticionario['direccion'],
                'sgd_ciu_apell1' => $peticionario['apellido_1'],
                'sgd_ciu_apell2' => $peticionario['apellido_2'],
                'sgd_ciu_telefono' => $peticionario['celular'],
                'sgd_ciu_email' => $peticionario['email'],
                'muni_codi' => $peticionario['municipio'],
                'dpto_codi' => $peticionario['departamento'],
                'sgd_ciu_cedula' => $peticionario['id']
            ];

            $direccion_data = [
                'sgd_dir_codigo' => $num_dir,
                'sgd_dir_tipo' => '1',
                'sgd_oem_codigo' => '0',
                'sgd_ciu_codigo' => $num_ciu,
                'radi_nume_radi' => $numeroRadicado,
                'sgd_esp_codi' => '0',
                'muni_codi' => $peticionario['municipio'],
                'dpto_codi' => $peticionario['departamento'],
                'sgd_dir_direccion' => ($peticionario['provincia'] != '' ? $peticionario['pais'].' '.$peticionario['provincia'].' ' : '').$peticionario['direccion'],
                'sgd_dir_telefono' => $peticionario['celular'],
                'sgd_sec_codigo' => '0',
                'sgd_dir_nombre' => $peticionario['nombres'].' '.$peticionario['apellidos'],
                'sgd_dir_nomremdes' => $peticionario['nombres'].' '.$peticionario['apellidos'],
                'sgd_trd_codigo' => '1',
                'sgd_dir_doc' => $peticionario['id'],
                'sgd_dir_mail' =>  $peticionario['email']
            ];

            $descripcion .= "\n\nAtentamente: ".textoPDF($direccion_data['sgd_dir_nombre']).
                "\n".textoPDF($db->conn->getOne("SELECT TDID_DESC FROM tipo_doc_identificacion WHERE tdid_codi = ?", [$peticionario_data['tdid_codi']]))
                ." ".$peticionario_data['sgd_ciu_cedula'];

            break;
        case ANONIMO:

            $num_ciu = $db->conn->GenID('SEC_CIU_CIUDADANO');
            $peticionario_table = 'sgd_ciu_ciudadano';
            $peticionario_data = [
                'tdid_codi' => $anonimo['tipo_id'],
                'sgd_ciu_codigo' => $num_ciu,
                'sgd_ciu_nombre' => $anonimo['nombres'],
                'sgd_ciu_direccion' => ($anonimo['provincia'] != '' ? $anonimo['pais'].' '.$anonimo['provincia'].' ' : '').$anonimo['direccion'],
                'sgd_ciu_apell1' => $anonimo['apellido_1'],
                'sgd_ciu_apell2' => $anonimo['apellido_2'],
                'sgd_ciu_telefono' => $anonimo['celular'],
                'sgd_ciu_email' => $anonimo['email'],
                'muni_codi' => $anonimo['municipio'],
                'dpto_codi' => $anonimo['departamento'],
                'sgd_ciu_cedula' => $anonimo['id']
            ];

            $direccion_data = [
                'sgd_dir_codigo' => $num_dir,
                'sgd_dir_tipo' => '1',
                'sgd_oem_codigo' => '0',
                'sgd_ciu_codigo' => $num_ciu,
                'radi_nume_radi' => $numeroRadicado,
                'sgd_esp_codi' => '0',
                'muni_codi' => $anonimo['municipio'],
                'dpto_codi' => $anonimo['departamento'],
                'sgd_dir_direccion' => ($anonimo['provincia'] != '' ? $anonimo['pais'].' '.$anonimo['provincia'].' ' : '').$anonimo['direccion'],
                'sgd_dir_telefono' => $anonimo['celular'],
                'sgd_sec_codigo' => '0',
                'sgd_dir_nombre' => $anonimo['nombres'].' '.$anonimo['apellidos'],
                'sgd_dir_nomremdes' => $anonimo['nombres'].' '.$anonimo['apellidos'],
                'sgd_trd_codigo' => '1',
                'sgd_dir_doc' => $anonimo['id'],
                'sgd_dir_mail' =>  $anonimo['email']
            ];

            $descripcion .= "\n\nAtentamente: Anónimo";

            break;
        case JURIDICO:

            $num_oem = $db->conn->GenID('SEC_OEM_OEMPRESAS');
            $peticionario_table = 'sgd_oem_oempresas';
            $peticionario_data = [
                'sgd_oem_codigo' => $num_oem,
                'tdid_codi' => $peticionario['tipo_id'],
                'sgd_oem_oempresa' => $peticionario['rason_social'],
                'sgd_oem_rep_legal' => '',
                'sgd_oem_nit' => $peticionario['id'],
                'sgd_oem_sigla' => '',
                'muni_codi' => $peticionario['municipio'],
                'dpto_codi' => $peticionario['departamento'],
                'sgd_oem_direccion' => ($peticionario['provincia'] != '' ? $peticionario['pais'].' '.$peticionario['provincia'].' ' : '').$peticionario['direccion'],
                'sgd_oem_telefono' => $peticionario['celular'],
                'sgd_oem_email' => $peticionario['email']
            ];

            $direccion_data = [
                'sgd_dir_codigo' => $num_dir,
                'sgd_dir_tipo' => '1',
                'sgd_oem_codigo' => $num_oem,
                'sgd_ciu_codigo' => '0',
                'radi_nume_radi' => $numeroRadicado,
                'sgd_esp_codi' => '0',
                'muni_codi' => $peticionario['municipio'],
                'dpto_codi' => $peticionario['departamento'],
                'sgd_dir_direccion' => ($peticionario['provincia'] != '' ? $peticionario['pais'].' '.$peticionario['provincia'].' ' : '').$peticionario['direccion'],
                'sgd_dir_telefono' => $peticionario['celular'],
                'sgd_sec_codigo' => '0',
                'sgd_dir_nombre' => $peticionario['rason_social'],
                'sgd_dir_nomremdes' => $peticionario['rason_social'],
                'sgd_trd_codigo' => '1',
                'sgd_dir_doc' => $peticionario['id'],
                'sgd_dir_mail' =>  $peticionario['email']
            ];

            $descripcion .= "\n\nAtentamente: ".textoPDF($direccion_data['sgd_dir_nombre']).
                "\n".textoPDF($db->conn->getOne("SELECT TDID_DESC FROM tipo_doc_identificacion WHERE tdid_codi = ?", [$peticionario_data['tdid_codi']]))
                ." ".$peticionario_data['sgd_oem_nit'];

            break;
    }
} else {
    $num_ciu = $db->conn->GenID('SEC_CIU_CIUDADANO');
    $peticionario_table = 'sgd_ciu_ciudadano';
    $peticionario_data = [
        'tdid_codi' => $afectado['tipo_id'],
        'sgd_ciu_codigo' => $num_ciu,
        'sgd_ciu_nombre' => $afectado['nombres'],
        'sgd_ciu_direccion' => ($afectado['provincia'] != '' ? $afectado['pais'].' '.$afectado['provincia'].' ' : '').$afectado['direccion'],
        'sgd_ciu_apell1' => $afectado['apellido_1'],
        'sgd_ciu_apell2' => $afectado['apellido_2'],
        'sgd_ciu_telefono' => $afectado['celular'],
        'sgd_ciu_email' => $afectado['email'],
        'muni_codi' => $afectado['municipio'],
        'dpto_codi' => $afectado['departamento'],
        'sgd_ciu_cedula' => $afectado['id']
    ];

    $direccion_data = [
        'sgd_dir_codigo' => $num_dir,
        'sgd_dir_tipo' => '1',
        'sgd_oem_codigo' => '0',
        'sgd_ciu_codigo' => $num_ciu,
        'radi_nume_radi' => $numeroRadicado,
        'sgd_esp_codi' => '0',
        'muni_codi' => $afectado['municipio'],
        'dpto_codi' => $afectado['departamento'],
        'sgd_dir_direccion' => ($afectado['provincia'] != '' ? $afectado['pais'].' '.$afectado['provincia'].' ' : '').$afectado['direccion'],
        'sgd_dir_telefono' => $afectado['celular'],
        'sgd_sec_codigo' => '0',
        'sgd_dir_nombre' => $afectado['nombres'].' '.$afectado['apellidos'],
        'sgd_dir_nomremdes' => $afectado['nombres'].' '.$afectado['apellidos'],
        'sgd_trd_codigo' => '1',
        'sgd_dir_doc' => $afectado['id'],
        'sgd_dir_mail' =>  $afectado['email']
    ];

    
    $descripcion .= "\n\nAtentamente: ".textoPDF($direccion_data['sgd_dir_nombre']).
            "\n".textoPDF($db->conn->getOne("SELECT TDID_DESC FROM tipo_doc_identificacion WHERE tdid_codi = ?", [$peticionario_data['tdid_codi']]))
            ." ".$peticionario_data['sgd_ciu_cedula'];
}

//RADICADO
$descripcionAnexos = $uploader->tieneArchivos ? count($uploader->subidos) : 0;
$descripcionAnexos .=  " Anexos";
$rutaPdf ="/$anoRad/".intval($dependencia_destino)."/$numeroRadicado".".pdf";

$radicado_table = 'radicado';
$now = $db->conn->getOne('SELECT now()');
$radicado_data = [
    'radi_nume_radi' => $numeroRadicado,
    'radi_fech_radi' => $now,
    'tdoc_codi' => $tipo,
    'mrec_codi' => $medio_recepcion,
    'eesp_codi' => '0',
    'radi_fech_ofic' => $now,
    'radi_pais' => $direccion_data['pais'],
    'muni_codi' => $peticionario_data['muni_codi'],
    'carp_codi' => '0',
    'dpto_codi' => $peticionario_data['dpto_codi'],
    'radi_nume_hoja' => '1',
    'radi_nume_folio' => '0',
    'radi_desc_anex' => $descripcionAnexos,
    'radi_nume_deri' => '',
    'radi_path' => $rutaPdf,
    'radi_usua_actu' => $usuario_destino,
    'radi_depe_actu' => $dependencia_destino,
    'ra_asun' => $asunto,
    'radi_depe_radi' => $depedencia_radicacion,
    'radi_usua_radi' => $usuario_radicacion,
    'codi_nivel' => '3',
    'flag_nivel' => '1',
    'carp_per' => '0',
    'radi_leido' => '0',
    'radi_tipo_deri' => '1',
    'sgd_fld_codigo' => '0',
    'sgd_apli_codi' => '0',
    'sgd_ttr_codigo' => '0',
    'sgd_spub_codigo' => '0',
    'sgd_tma_codigo' => '',
    'sgd_rad_codigoverificacion' => $codigo_verificacion,
    'depe_codi' => $depedencia_radicacion,
    'sgd_trad_codigo' => '2'
];

error_log($radicado_table.' '.json_encode(array_filter($radicado_data)), 0);

if(!$db->conn->autoExecute($radicado_table, array_filter($radicado_data), 'INSERT'))
{
    echo json_encode([
        'status' => 'fail',
        'afectado' => $afectado,
        'radicado' => $radicado_data,
        'peticionario' => $peticionario_data,
        'direccion' => $direccion_data,
        'descripcion' => $descripcion
    ]);
    die;
}

error_log($peticionario_table.' '.json_encode(array_filter($peticionario_data)), 0);

if(!$db->conn->autoExecute($peticionario_table, array_filter($peticionario_data), 'INSERT'))
{
    echo json_encode([
        'status' => 'fail',
        'afectado' => $afectado,
        'radicado' => $radicado_data,
        'peticionario' => $peticionario_data,
        'direccion' => $direccion_data,
        'descripcion' => $descripcion
    ]);
    die;
}

error_log($direccion_table.' '.json_encode(array_filter($direccion_data)), 0);
if(!$db->conn->autoExecute($direccion_table, array_filter($direccion_data), 'INSERT'))
{
    echo json_encode([
        'status' => 'fail',
        'afectado' => $afectado,
        'radicado' => $radicado_data,
        'peticionario' => $peticionario_data,
        'direccion' => $direccion_data,
        'descripcion' => $descripcion
    ]);
    die;
}

$hist_doc_dest = $db->conn->getOne('SELECT usua_doc FROM usuario WHERE usua_codi = ? ',$usuario_destino);

//HISTORICO
$historial_table = "hist_eventos";
$historial_data = [
    'depe_codi' => $depedencia_radicacion,
    'hist_fech' => $now,
    'usua_codi' => $usuario_radicacion,
    'radi_nume_radi' => $numeroRadicado,
    'hist_obse' => 'RADICACION SERVICIO WEB',
    'usua_codi_dest' => $usuario_destino,
    'usua_doc' => '22222222', 
    'sgd_ttr_codigo' => '2',
    'hist_doc_dest' => $hist_doc_dest,
    'depe_codi_dest' => $dependencia_destino
];


error_log($historial_table.' '.json_encode(array_filter($historial_data)), 0);
if(!$db->conn->autoExecute($historial_table, array_filter($historial_data), 'INSERT'))
{
    echo json_encode([
        'status' => 'fail',
        'afectado' => $afectado,
        'radicado' => $radicado_data,
        'peticionario' => $peticionario_data,
        'direccion' => $direccion_data,
        'descripcion' => $descripcion
    ]);
    die;
}

//ANEXOS
$uploader->bodega_dir .= date('Y')."/".$dependencia_destino."/docs";
$uploader->moverArchivoCarpetaBodega($numeroRadicado);

$sql_login = "select usua_login from usuario where usua_codi=".$usuario_destino." and depe_codi=".$dependencia_destino;
$rs_login = $db->conn->getOne($sql_login);

//insertar anexos
$fechaval=valida_fecha($db);
$total_adjuntos = 0;

if($uploader->tieneArchivos)
{
    for($i=0; $i < count($uploader->subidos);$i++)
    {
        if(strlen($uploader->subidos[$i]) == 0){
            continue;
        }
        //$origen = '../bodega/tmp/'.$uploader->subidos[$i];
        //$destino = $directorio.'/docs/'.$uploader->nombreOrfeo[$i];
        //$copy = copy($origen, $destino);
        $total_adjuntos = $total_adjuntos + 1;
        $extension = strtolower(end(explode('.',$uploader->subidos[$i])));
        $sql_tipoAnex = "select anex_tipo_codi from anexos_tipo where anex_tipo_ext = '".$extension ."'";
        $rs_tipoAnexo = $db->conn->getOne($sql_tipoAnex);
        $tipoCodigo = 24;
        if($rs_tipoAnexo){
            $tipoCodigo = $rs_tipoAnexo;
        }else {
            $sql_tipoAnex = "select anex_tipo_codi from anexos_tipo where anex_tipo_ext = '*'";
            $rs_tipoAnexo = $db->conn->getOne($sql_tipoAnex);
            if($rs_tipoAnexo){
                $tipoCodigo = $rs_tipoAnexo;
            }
        }

        $ins_anex="insert into anexos(anex_radi_nume, anex_codigo,anex_tipo,anex_tamano,anex_solo_lect,anex_creador,anex_desc,anex_numero,anex_nomb_archivo,anex_borrado,anex_origen,anex_salida,anex_estado,sgd_rem_destino,sgd_dir_tipo,anex_depe_creador,anex_fech_anex,sgd_apli_codi)
            values(".$numeroRadicado.",".$numeroRadicado.sprintf("%05d",($i+1)).",".$tipoCodigo.",".$uploader->sizes[$i].",'S','".$rs_login."','".is_empty($tipo_documento[$i], "")."',1,'".$uploader->nombreOrfeo[$i]."','N',0,0,0,1,1,".$dependencia_destino.",now(),0)";
        $rs_ins_anex = $db->conn->Execute($ins_anex);
    }
}

//PDF
require('barcode.php');

$sql_depeNomb = "select depe_nomb from dependencia where depe_codi = ". $dependencia_destino;
$rs_depeNomb = $db->conn->Execute($sql_depeNomb);
if(!$rs_depeNomb->EOF){
    $depeNomb = substr($rs_depeNomb->fields["depe_nomb"],0,40);
}

if($direccion_data['dpto_codi'] && $direccion_data['muni_codi'])
{
    $departamento_pdf = $db->conn->getOne("SELECT DPTO_NOMB FROM departamento WHERE dpto_codi = ?", [$direccion_data['dpto_codi']]);
    $municipio_pdf = $db->conn->getOne("SELECT MUNI_NOMB FROM municipio WHERE muni_codi = ?  and dpto_codi = ? ", [$direccion_data['muni_codi'], $direccion_data['dpto_codi']]);    
} else {
    $municipio_pdf = $peticionario['pais'].' '.$peticionario['provincia'];
}


$pdf=new PDF_Code39();
$pdf->SetTitle('Radicado '.$numeroRadicado);
$pdf->SetAuthor($entidad_largo);
$pdf->SetSubject($asunto);
$pdf->AddFont('DejaVuSansCondensed', '', 'DejaVuSansCondensed.php');
$pdf->AddFont('DejaVuSansCondensed-Bold', '', 'DejaVuSansCondensed-Bold.php');
$pdf->SetFont('DejaVuSansCondensed', '', 8);
$pdf->AddPage();

$pdf->Code39(104,25, $numeroRadicado,1,8);
$pdf->Text(130, 37, textoPDF("Radicado N°. ".$numeroRadicado));
//$pdf->Image('../bodega'.$_SESSION["logoEntidad"],20,20,75);
//$pdf->SetFont('Arial','',16);
//$pdf->Text(110,40,textoPDF(textoPDF($entidad_largo)));
$pdf->Text(110,41, textoPDF(date('d')." - ".date('m')." - ".date('Y')." ".date('h:i:s')) . "   Folios: N/A (WEB)   Anexos: ". $total_adjuntos );
$pdf->SetFont('DejaVuSansCondensed', '', 8);
$pdf->Text(110, 45, textoPDF("Destino: ". $dependencia_destino . " " . substr($depeNomb, 0,10) ." - Rem/D: ". $direccion_data['sgd_dir_nombre']));
$pdf->Text(110,48,textoPDF("Consulte el su trámite, en la pagina de la entidad"));
$pdf->Text(135,51,textoPDF("Código de verificación: " . $codigo_verificacion));
//$pdf->Text(110,51,textoPDF(strtoupper($_SESSION['nombre_remitente'])." ".strtoupper($_SESSION['apellidos_remitente'])));
//$pdf->Text(110,55,$_SESSION['cedula']!='0'?$_SESSION['cedula']:$_SESSION['nit']);

$pdf->Text(12,67,textoPDF("Bogotá D.C., ".date('d')." de ".nombremes(date('m'))." de ".date('Y')));
$pdf->Text(12,81,textoPDF("Señores"));
$pdf->SetFont('DejaVuSansCondensed-Bold', '', 8);
$pdf->Text(12,85,textoPDF($entidad_largo));
$pdf->SetFont('DejaVuSansCondensed', '', 8);
$pdf->Text(12,89,textoPDF("Ciudad: ".$municipio_pdf));
$pdf->Text(12,99,textoPDF("Asunto: ".mb_strtoupper($asunto,"utf-8")));
$pdf->SetXY(11,105);
//$pdf->MultiCell(0,4,textoPDF($_SESSION['desc'],0));

$pdf->MultiCell(0,4, $descripcion, 0);

//guarda documento en un SERVIDOR
$pdf->Output("../bodega/$rutaPdf",'F');
//Realizar el conteo de hojas del radicado final//
$conteoPaginas = getNumPagesPdf("../bodega/$rutaPdf");

$sqlu = "UPDATE radicado SET radi_nume_hoja= $conteoPaginas where radi_nume_radi=" . $numeroRadicado;
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
$db->conn->Execute($sqlu);

echo json_encode([
    'status' => 'ok',
    'radicado' => $numeroRadicado
]);
die;
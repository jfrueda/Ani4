<?php
ini_set('log_errors_max_len', '0');
session_start();

$ruta_raiz = "..";
require_once('funciones.php');
require_once("$ruta_raiz/include/db/ConnectionHandler.php");
require_once("$ruta_raiz/processConfig.php");
include_once("$ruta_raiz/altcha_verify.php");
include_once("$ruta_raiz/include/tx/roles.php");
include_once("$ruta_raiz/vendor/autoload.php");
include_once('./adjuntarArchivos.php');

use Ramsey\Uuid\Uuid;

$pregunta = '3';
$now = date('Y-m-d H:i:s');
$now = date('Y-m-d H:i:s', strtotime($now));
$inicio_mantenimiento = date('Y-m-d H:i:s', strtotime($fecha_inicio_mantenimiento_formulario_web));
$fin_mantenimiento = date('Y-m-d H:i:s', strtotime($fecha_fin_mantenimiento_formulario_web));
if (
    ($now >= $inicio_mantenimiento) && ($now <= $fin_mantenimiento) && 
    in_array($pregunta, explode(',', $deshabilitados_mantenimiento_formulario_web))
)
{
    header('Location: '.$url_redireccion_mantenimiento_formulario_web);
    die();
}


if (!verify_captcha($_POST['altcha'])) {
    header("Location: delegatura.php");
    die();
}

define('ADODB_ASSOC_CASE', 2);
$ADODB_COUNTRECS = false;
$db   = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$requestJson = json_encode($_REQUEST);
$log_id = Uuid::uuid4()->toString();
$logSqlInsert = "INSERT INTO pqrd_registro_web_log (id, original, radicado, created_at) VALUES (?, ?, ?, NOW())";
$result = $db->conn->Execute($logSqlInsert, [$log_id, $requestJson, null]);

$uploader = new Uploader($_FILES);
$uploader->FILES = $_FILES;
$adjuntosSubidos = json_decode($adjuntosSubidos);
$uploader->subidos = $adjuntosSubidos;
$uploader->adjuntarArchivos();

//$api = 'crear api_pqrd en sgd_config, http://40.121.54.187:8083/api/api/';
$api = $api_pqrd;

$comentarios = $_POST['comentarios'];

$data = [
    "valuesRegistroPqrd" => [
        "id" => 0,
        "log_id" => $log_id,
        "canal_id" => 7,
        "subcanal_id" => "",
        "regional_id" => "",
        "habeas_data" => true,
        "es_peticionario" => 2,
        "tipo_afectado_id" => null,
        "tipo_peticionario_id" => $_POST['tipo'] == '4' ? '1' : $_POST['tipo'],
        "descripcion" => $comentarios,
        "orden_medica" => '0',
        "reclamo_asegurador" => '0',
        "es_priorizado_riesgo_vida" => '0',
        "caso_dirigido" => 2,
        "pqrd_tipo" => null,
        "riesgo_vida" => null,
        "tipo_vigilado_id" => null,
        "vigilado_id" => null,
        "departamento_vig_id" => null,
        "municipio_vig_id" => null,
        "patologia_id" => null,
        "cie_id" => null,
        "alto_costo_id" => null,
        "motivo_espeficico_id" => [],
        "pqrd_fallo" => null,
        "peticion_respuesta" => null,
        "translado_externo" => 2,
        "entidad_externa_id" => null,
        "translado_interno"=> 2,
        "entidad_interna_id" => null,
        "tipificacion" => null,
        "tipificacion_ids" => null,
        "poblacion_sexo_id" => null,
        "nivel_educativo_id" => null,
        "regimen_id" => null,
        "grupo_etnico_id" => null,
        "poblacion_especial_id" => null,
        "gestacion" => null,
        "juzgado" => null,
        "tutela" => null,
        "fecha_tulela" => null,
        "resumen_fallo" => null,
        "tray_id" => 3,
        "pqrd_tipo" => 3,
        "web" => 1
    ],
    "valuesPqrdAfec" => [
        "tipo_doc_id" => '',
        "numero_doc" => '',
        "nombre" => '',
        "s_nombre" => '',
        "apellido" => '',
        "s_apellido" => '',
        "fecha_nacimiento" => '',
        "edad" => '',
        "celular" => '',
        "telefono" => '',
        "direccion" => '',
        "pais" => '',
        "departamento_id" => '',
        "municipio_id" => '',
        "provincia" => '',
        "correo" => ''
    ],
    "valuesPqrdPet" => [
        "tipo_doc_id" => $_POST['tipo_identificacion_peticionario'],
        "numero_doc" => $_POST['id_peticionario'],
        "nombre" => $_POST['nombre_peticionario_1'],
        "s_nombre" => $_POST['nombre_peticionario_2'],
        "apellido" => $_POST['apellidos_peticionario_1'],
        "s_apellido" => $_POST['apellidos_peticionario_2'],
        "razon_social" => $_POST['rs'],
        "fecha_nacimiento" => null,
        "edad" => null,
        "celular" => is_empty($_POST['celular_peticionario'], ''),
        "telefono" => is_empty($_POST['telefono_peticionario'], ''),
        "direccion" => is_empty($_POST['direccion_peticionario'], '').(is_empty($_POST['direccion_peticionario_2'], '') != '' ? ' / '.$_POST['direccion_peticionario_2'] : ''),
        "pais" => is_empty($_POST['pais_peticionario'], 'Colombia'),
        "departamento_id" => is_empty($_POST['departamento_peticionario'], ''),
        "municipio_id" => is_empty($_POST['ciudad_peticionario'], ''),
        "provincia" => is_empty($_POST['provincia_peticionario'], ''),
        "correo" => $_POST['correo_peticionario'].'@'.$_POST['dominio_peticionario'] != '@' ? $_POST['correo_peticionario'].'@'.$_POST['dominio_peticionario'] : ''
    ],
    "browser" => getBrowserInfo()
];

$client = new \GuzzleHttp\Client();

$auth = $client->post($api.'login', [
    'form_params' => [
        'user' => 'helberth.castro@supersalud.gov.co',
        'password' => '123456',
    ],
]);

$token = json_decode($auth->getBody());

try {
    $response = $client->post($api.'registro-radicados', [
        'headers' => [
            'Authorization' => 'Bearer '.$token->access_token
        ],
        'http_errors' => true,
        'json' => $data
    ]);
    $res = json_decode($response->getBody(), true);
} catch (\Exception $e) {
    echo $e;
}

$res = json_decode($response->getBody(), true);
//var_dump($res);
$anoRad = date("Y");
$numeroRadicado = $res['data']['radicado']['radicado'];
$rutaPdf ="/$anoRad/".substr($numeroRadicado, 4, 5)."/$numeroRadicado".".pdf";

$_SESSION['depeRadicaFormularioWeb'] = '21000';
$_SESSION['usuaRecibeWeb'] = '11513';
$uploader->bodega_dir .= date('Y') . "/" . $_SESSION['depeRadicaFormularioWeb'] . "/docs";
$uploader->moverArchivoCarpetaBodega($numeroRadicado);

//trae usualogin

$sql_login = "select usua_login from usuario where usua_codi=".$_SESSION["usuaRecibeWeb"]." and depe_codi=".$_SESSION["depeRadicaFormularioWeb"];
$rs_login = $db->conn->getOne($sql_login);

//insertar anexos
$fechaval=valida_fecha($db);
$_SESSION['cantidad_adjuntos'] = 0;
$anexos_creados_enpqrd = 1;

if($uploader->tieneArchivos){
    for($i=0; $i < count($uploader->subidos);$i++)
    {
        if(strlen($uploader->subidos[$i]) == 0){
            continue;
        }
        //$origen = '../bodega/tmp/'.$uploader->subidos[$i];
        //$destino = $directorio.'/docs/'.$uploader->nombreOrfeo[$i];
        //$copy = copy($origen, $destino);
        $_SESSION['cantidad_adjuntos'] = $_SESSION['cantidad_adjuntos'] + 1;
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
            values(".$numeroRadicado.",".$numeroRadicado.sprintf("%05d",($i+$anexos_creados_enpqrd)).",".$tipoCodigo.",".$uploader->sizes[$i].",'S','".$rs_login."','".$_POST['tipo_documento'][$i]."',".($i+$anexos_creados_enpqrd).",'".$uploader->nombreOrfeo[$i]."','N',0,0,0,1,1,".$_SESSION["depeRadicaFormularioWeb"].",now(),0)";
        $rs_ins_anex = $db->conn->Execute($ins_anex);
    }
}
?>
<?php include ('header.php') ?>
    <div class="container" style="height: 1080px;">
        <div class="row justify-content-end">
            <div class="col-sm">
                <p class="fecha">
                    <small>Fecha radicación <?= date('d/m/Y H:i') ?></small>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <div class="alert alert-info" role="alert" style="text-align: justify">
                    <h4>Bien!</h4>
                    Su solicitud ha sido registrada de forma exitosa con el radicado Nº. <strong><?=$numeroRadicado?></strong>. Por favor tenga en cuenta estos datos para que realice la consulta del estado a su solicitud.
                    <br><br>
                    Para conocer los costos de reproducción de la información pública que reposa en la Superintendencia Nacional de Salud, 
                    <a href="http://docs.supersalud.gov.co/PortalWeb/planeacion/OtrosDocumentosPlaneacin/Precios%20fotocopiado%20REV%20SG.docx" target="_blank">consulte la Certificación de actualización de precios de reproducción de la información pública vigencia 2021</a>, que se 
                    encuentra establecida según lo dispuesto en el artículo 4 de la <a href="https://docs.supersalud.gov.co/PortalWeb/Juridica/Resoluciones/res%205015%20de%202018.pdf" target="_blank">Resolución número 005015 de 2018</a>.
                </div>
                <p class="lead">
                    <small>
                        Pulse continuar para terminar la solicitud y visualizar el documento en formato PDF. Si desea almacenelo en su disco duro o imprímalo.
                    </small>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <input type="button" name="Submit" value="Continuar" class ="btn btn-success" onclick="window.open('../bodega/<?=$rutaPdf?>')" />
                <a class="btn btn-default" href="https://www.supersalud.gov.co/pqrd.html">Volver</a>
            </div>
        </div>
    </div>
<?php include ('footer.php') ?>
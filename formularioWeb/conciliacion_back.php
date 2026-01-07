<?php
session_start();
/**
 * Modulo de Formularios Web para atencion a Ciudadanos.
 * @autor Carlos Barrero   carlosabc81@gmail.com SuperSolidaria
 * @author Sebastian Ortiz Vasquez 2012
 * @fecha 2009/05
 * @Fundacion CorreLibre.org
 * @licencia GNU/GPL V2
 *
 * Se tiene que modificar el post_max_size, max_file_uploads, upload_max_filesize
 */
$ruta_raiz = "..";

require_once('funciones.php');
require_once("$ruta_raiz/include/db/ConnectionHandler.php");
require_once("$ruta_raiz/processConfig.php");
include_once("$ruta_raiz/altcha_verify.php");
include_once("$ruta_raiz/include/tx/roles.php");
include_once("$ruta_raiz/vendor/autoload.php");
include_once('./adjuntarArchivos.php');

use Ramsey\Uuid\Uuid;

$pregunta = '5';
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

if (!verify_captcha($_POST['altcha']) || invalidateArray($_POST, ['tipo_convocante', 'tipo_identificacion_convocante', 'id_convocante', 'tipo_solicitud'])) {
    header("Location: conciliacion.php");
    die();
}

foreach ($_GET as $key => $valor)   ${$key} = $valor;//iconv("ISO-8859-1","UTF-8",$valor);
foreach ($_POST as $key => $valor)   ${$key} = $valor; //iconv("ISO-8859-1","UTF-8",$valor);

$pais_formulario = $pais;
define('ADODB_ASSOC_CASE', 2);
$ADODB_COUNTRECS = false;

$db   = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$requestJson = json_encode($_REQUEST);
$log_id = Uuid::uuid4()->toString();
$logSqlInsert = "INSERT INTO pqrd_registro_web_log (id, original, radicado, created_at) VALUES (?, ?, ?, NOW())";
$result = $db->conn->Execute($logSqlInsert, [$log_id, $requestJson, null]);

if($logoEntidad){
  $log = "$ruta_raiz/bodega/$logoEntidad";
}else{
  $log = "$ruta_raiz/img/orfeo.png";
}

$errorFormulario = 0;
/*
cambio a favor de recaptcha

if(strcasecmp ($captcha ,$_SESSION['captcha_formulario']['code'] ) != 0 || strcasecmp ($idFormulario ,$_SESSION["idFormulario"] ) != 0){
	$errorFormulario = 1;
}*/
if($errorFormulario==0){
	$uploader = new Uploader($_FILES);
    $uploader->FILES = $_FILES;
	$adjuntosSubidos = json_decode($adjuntosSubidos);
	$uploader->subidos = $adjuntosSubidos;
	$uploader->adjuntarArchivos();
}


$_SESSION['depeRadicaFormularioWeb']=$depeRadicaFormularioWeb;

/* Si el usuario selecciona un grupo, el radicado es direccionado a esta
 * dependencia
 */
$coddepe = '82000';
if($coddepe){
    $rol  = new Roles($db);
    //El grupo numero 2 corresponde a Jefe de grupo
    //en el listado predefinido de perfiles
    if($rol->buscarUsuariosGrupoDepen(2, $coddepe)){
        $_SESSION['depeRadicaFormularioWeb'] = $coddepe;
        $_SESSION['usuaRecibeWeb'] = $rol->users[0];
    }
}

// datos convocante
$num_id = $id_convocante;
$tipoDocumento = $tipo_identificacion_convocante;
$depto = isset($departamento_convocante) && $departamento_convocante != ''  ? $departamento_convocante : '11';
$muni = isset($ciudad_convocante) && $ciudad_convocante != ''  ? $ciudad_convocante : '1';
if($tipo_convocante == '1' || $tipo_convocante == '2')
{
    $tipo_c = $tipo_convocante == '1' ? 'Natural' : 'Natural - profesional independiente de salud (vigilado)';
    $nombre_afectado = trim($nombre_convocante_1.' '.$nombre_convocante_2);
    $apellidos_afectado = trim($apellidos_convocante_1.' '.$apellidos_convocante_2);
} else {
    $tipo_c = 'Jurídica '.$tipo_entidad;
    $nombre_afectado = trim($rs_convocante);
    $apellidos_afectado = '';
}
$email = $correo_convocante.'@'.$dominio_convocante;

if($pais_convocante == 'Colombia')
{
    $depto_afectado = "\nDepartamento: ".$db->conn->getOne("SELECT DPTO_NOMB FROM departamento WHERE dpto_codi = ?", [$departamento_convocante]);
    $muni_afectado = "\nMunicipio: ".$db->conn->getOne("SELECT MUNI_NOMB FROM municipio WHERE muni_codi = ? and dpto_codi = ? ", [$ciudad_convocante, $departamento_convocante]);
} else {
    $depto_afectado = '';
    $muni_afectado = "\nCiudad / Estado / Provincia: ".$provincia_convocante;
}

$tipo_doc_direccion = $db->conn->getOne("SELECT TDID_DESC FROM tipo_doc_identificacion WHERE tdid_codi = ?", [$tipo_identificacion_convocante]);
$nombre_direccion = $nombre_afectado;
$apellido_direccion = $apellidos_afectado;  
$email_direccion = $email;
$depto_direccion = $depto;
$muni_direccion = $muni;
$dir_direccion = $direccion_convocante;
$celular_direccion = $celular_convocante;
$telefono_direccion = $telefono_convocante;

$nombre_afect = "\nConvocante: ".$nombre_afectado.' '.$apellidos_afectado;
$tip_afectado = "\nTipo persona: ".$tipo_c;
$p_afectado = "\nPaís: ".$pais_convocante;
$tel_afectado = "\nTeléfono: ".$telefono_convocante;
$cel_afectado = "\nCelular: ".$celular_convocante;
$email_afectado = "\nCorreo: ".$email;
$doc_afectado = "\nDocumento: ".$tipo_doc_direccion." ".$num_id;
$dir_afectado = "\nDirección: ".$dir_direccion;
$pretension = "\nValor pretensión: ".$valor_pretension;


//comentario con datos del convocante
$asunto = $tipo_solicitud;
$comentario .= "\n\nDetalles del caso: ".$nombre_afect.$tip_afectado.$doc_afectado.$p_afectado.$depto_afectado.$muni_afectado.$dir_afectado.$tel_afectado.$cel_afectado.$email_afectado.$pretension;

//datos del convocado
$num_id_convocado = $id_convocado;
$depto = isset($departamento_convocado) && $departamento_convocado != ''  ? $departamento_convocado : '11';
$muni = isset($ciudad_convocado) && $ciudad_convocado != ''  ? $ciudad_convocado : '1';
if($tipo_convocado == '1' || $tipo_convocado == '2')
{
    $tipo_convocado = $tipo_convocado == '1' ? 'Natural' : 'Natural - profesional independiente de salud (vigilado)';
    $nombre_convocado = trim($nombre_convocado_1.' '.$nombre_convocado_2);
    $apellidos_convocado = trim($apellidos_convocado_1.' '.$apellidos_convocado_2);
} else {
    $tipo_convocado = 'Jurídica';
    $nombre_convocado = trim($rs_convocado);
    $apellidos_convocado = '';
}
$email_convocado = $correo_convocado.'@'.$dominio_convocado;

if($pais_convocado == 'Colombia')
{
    $depto_afectado = "\nDepartamento: ".$db->conn->getOne("SELECT DPTO_NOMB FROM departamento WHERE dpto_codi = ?", [$departamento_convocado]);
    $muni_afectado = "\nMunicipio: ".$db->conn->getOne("SELECT MUNI_NOMB FROM municipio WHERE muni_codi = ? and dpto_codi = ? ", [$ciudad_convocado, $departamento_convocado]);
} else {
    $depto_afectado = '';
    $muni_afectado = "\nCiudad / Estado / Provincia: ".$provincia_convocado;
}

$tipo_doc_convocado = $db->conn->getOne("SELECT TDID_DESC FROM tipo_doc_identificacion WHERE tdid_codi = ?", [$tipo_identificacion_convocado]);

$nombre_convoc = "\nConvocado: ".$nombre_convocado.' '.$apellidos_convocado;
$tip_convoc = "\nTipo persona: ".$tipo_convocado;
$p_convoc = "\nPaís: ".$pais_convocado;
$tel_convoc = "\nTeléfono: ".$telefono_convocado;
$cel_convoc = "\nCelular: ".$celular_convocado;
$email_convoc = "\nCorreo: ".$email_convocado;
$doc_convoc = "\nDocumento: ".$tipo_doc_convocado." ".$num_id_convocado;
$dir_convoc = "\nDirección: ".$direccion_convocado;


$comentario .= "\n\n".$nombre_convoc.$tip_convoc.$doc_convoc.$p_convoc.$depto_convoc.$muni_convoc.$dir_convoc.$tel_convoc.$cel_convoc.$email_convoc;

/*if($tipo_convocante == '3')
{
    $datos_entidad_vigilada = "Datos entidad vigilada:\nTipo de entidad: ".$db->conn->getOne("SELECT * FROM sgd_tipo_eps WHERE id = ?", [$tipo_entidad])."\nEntidad: ".$db->conn->getOne("SELECT * FROM sgd_eps WHERE id = ?", [$entidad])."\nNaturaleza: ".$tipo_naturaleza."\nDepartamento: ".$db->conn->getOne("SELECT DPTO_NOMB FROM departamento WHERE dpto_codi = ?", [$departamento_entidad])."\nMunicipio: ".$db->conn->getOne("SELECT MUNI_NOMB FROM municipio WHERE muni_codi = ? and dpto_codi = ? ", [$ciudad_entidad, $departamento_entidad])."\n¿La entidad a la que representa se encuentra bajo alguna medida especial de vigilancia por la Superintendencia Nacional de Salud? ".($medida_especial == '1' ? "Si" : "No");
    $comentario .= "\n\n".$datos_entidad_vigilada;
}*/

$comentario .= "\n\nDatos de la solicitud:\nTipo solicitud: ".$tipo_solicitud."\nDetalle de la solicitud: ".$comentarios;

$pqrsFacebook = 0;
$autoriza_respuesta = '';

if(isset($medio)) 
{
    $autoriza_respuesta =  "\nAutoriza envio de información a través de: ";
    foreach($medio as $m) {
        $autoriza_respuesta .= $m.' ';
    }

    $comentario .= $autoriza_respuesta;
}

//var_dump([$tipoDocumento, $num_id]); exit;

if($errorFormulario==0){
    if($anonimo == 1){
        //Esto es anónimo
        $_SESSION['nombre_remitente']="Anónimo";
        $_SESSION['apellidos_remitente']="N.N";
        $_SESSION['cedula']=0;
        $_SESSION['nit'] = 0;
        $_SESSION['depto']=0;
        $_SESSION['muni']=0;
        $_SESSION['direccion_remitente']="No registra";
        $_SESSION['telefono_remitente']="No registra";
        $_SESSION['email']=$email_direccion==''?"":$email_direccion;
        $mediorespuesta=$_SESSION['email']==""?3:$mediorespuesta;
        //Puede ser anonima.
        if(!$_SESSION['nombre_remitente']) $_SESSION['nombre_remitente']="Anónimo";
        if(!$_SESSION['cedula']) $_SESSION['cedula']=0;
        if(!$_SESSION['depto']) $_SESSION['depto']=0;
        if(!$_SESSION['muni']) $_SESSION['muni']=0;
        if(!$_SESSION['direccion_remitente']) $_SESSION['direccion_remitente']="No registra";
        if(!$_SESSION['telefono_remitente']) $_SESSION['telefono_remitente']="No registra";
        $_SESSION['email']=$email_direccion==''?"":$email_direccion;

    } else if ($anonimo == 0){
        //No es anónimo
        $_SESSION['nombre_remitente']=$nombre_direccion;
        $_SESSION['apellidos_remitente']=$apellido_direccion;
        if($tipoDocumento == ''){
            //No selecciono tipo de documento
            $_SESSION['cedula'] = 0;
            $_SESSION['nit'] = 0;
        }else if($tipoDocumento==4){
            //Tipo de documento NIT
            $_SESSION['cedula'] = 0 ;
            $_SESSION['nit'] = $num_id!=""?$num_id:0;
        } else{
            //Tipo de documento diferente de NIT
            $_SESSION['cedula']=$num_id;
            $_SESSION['nit'] = 0;
        }

        if($depto_direccion!=0 && ($muni_direccion<1 || $muni_direccion >999)){
            $muni_direccion=1;
        }

        $_SESSION['depto']=$depto_direccion;
        $_SESSION['muni']=$muni_direccion;
        $_SESSION['direccion_remitente']=$dir_direccion==''?"No registra":$dir_direccion;
        $_SESSION['telefono_remitente']=$telefono_direccion;
        $_SESSION['email']=$email_direccion==''?"No registra":$email_direccion;
    }

    if($pqrsFacebook=="1"){
        //Medio de recepción Facebook
        $_SESSION['mrec_codi']=10;
    }else{
        //Medio de recepción Internet
        $_SESSION['mrec_codi']=3;
    }

    $_SESSION['tipo'] = 128;
    $_SESSION['asunto']=$asunto;
    $_SESSION['desc']=textoPDF($comentario);
    //TODO Imprimir el grupo de poblacional haciendo la consulta a sgd_tma_temas
    //$_SESSION['desc'].= textoPDF("Manifiesto que pertenezco al grupo pblacional: " );
    $_SESSION['desc'].= textoPDF("\n\n".$uploader->listadoImprimible);

    //TODO Revisar que hacer con todas estas otras cosas.
    //radicado.eesp_codi
    $_SESSION['codigo_orfeo']="0";

    $_SESSION['sigla']=$_GET['sigla'];
    if(!$_GET['sigla']) $_SESSION['sigla'] = "0";
    $_SESSION['usuario']=1;
    if(!$_SESSION['dependencia']) $_SESSION['dependencia']=900;
    $dependenciaRad = $_SESSION['dependencia'];
    $_SESSION['radicado']=$_GET['radicado'];
    $_SESSION['documento_destino']=$_GET['documento_destino'];
    $numero = str_pad($db->conn->GenID("SECR_TP2_".$secRadicaFormularioWeb),$digitosSecRad,'0',STR_PAD_LEFT);
    $num_dir=$db->conn->GenID('SEC_DIR_DRECCIONES');
    $dependenciaCompletada = str_pad($_SESSION['depeRadicaFormularioWeb'],$digitosDependencia,'0',STR_PAD_LEFT);

    /**
     * $depeRadicaFormularioWeb;  // Es radicado en la Dependencia 900
     * $usuaRecibeWeb ; // Usuario que Recibe los Documentos Web
     * $secRadicaFormularioWeb ;
     ***/
    $numeroRadicado = date('Y').$dependenciaCompletada.$numero."2";
    $db->conn->Execute('UPDATE pqrd_registro_web_log SET radicado = ? WHERE id = ?', [$numeroRadicado, $log_id]);

    if($tipoDocumento != '' && $tipoDocumento != 4 ){
        //inserta ciudadano
        $num_ciu=$db->conn->GenID('SEC_CIU_CIUDADANO');
        $tipdoc= $tipoDocumento/*-1*/;
        $ins_ciu="insert into sgd_ciu_ciudadano values($tipdoc,".$num_ciu.",'".mb_strtoupper($_SESSION['nombre_remitente'],"utf-8")."','".mb_strtoupper($_SESSION['direccion_remitente'],"utf-8")."','".mb_strtoupper($_SESSION['apellidos_remitente'],"utf-8")."','','".$_SESSION['telefono_remitente']."','".$_SESSION['email']."',".$_SESSION['muni'].",".$_SESSION['depto'].",'".$_SESSION['cedula']."')";
        $rs_ins_ciu=$db->conn->Execute($ins_ciu);
        //inserta en sec_dir_drecciones
        $ins_dir="insert into sgd_dir_drecciones(sgd_dir_codigo,sgd_dir_tipo,sgd_oem_codigo,sgd_ciu_codigo,radi_nume_radi,sgd_esp_codi,muni_codi,dpto_codi,sgd_dir_direccion,sgd_dir_telefono,sgd_sec_codigo,sgd_dir_nombre,sgd_dir_nomremdes,sgd_trd_codigo,sgd_dir_doc,sgd_dir_mail)
            values(".$num_dir.",1,0,".$num_ciu.",$numeroRadicado,0,".$_SESSION['muni'].",".$_SESSION['depto'].",'".$_SESSION['direccion_remitente']."','".$_SESSION['telefono_remitente']."',0,'".mb_strtoupper($_SESSION['nombre_remitente'],"utf-8")." ".mb_strtoupper($_SESSION['apellidos_remitente'],"utf-8")."','".mb_strtoupper($_SESSION['nombre_remitente'],"utf-8")." ".mb_strtoupper($_SESSION['apellidos_remitente'],"utf-8")."',1,'".$_SESSION['cedula']."','".$_SESSION['email']."')";

    }else if($tipoDocumento == 4){
        //TODO preguntar como tratar la llave foranea que hay en sgc_dir_direcciones hacia ciu_ciudadano si se trata de una empresa
        $num_oem=$db->conn->GenID('SEC_OEM_OEMPRESAS');
        //insertar empresa en sgc_oem_empresas
        $tipdoc= $tipoDocumento/*-1*/;
        $ins_empresa="insert into sgd_oem_oempresas values($num_oem,$tipdoc,'".mb_strtoupper($_SESSION['nombre_remitente'],"utf-8")." ".mb_strtoupper($_SESSION['apellidos_remitente'],"utf-8")."', '','".$_SESSION['nit']."','',".$_SESSION['muni'].",".$_SESSION['depto'].",'".mb_strtoupper($_SESSION['direccion_remitente'],"utf-8")."','".$_SESSION['telefono_remitente']."')";
        $rs_ins_oem=$db->conn->Execute($ins_empresa);

        if($tipoSolicitud == 'Denuncia')
        {
            $num_ciu=$db->conn->GenID('SEC_CIU_CIUDADANO');
            $ins_ciu="insert into sgd_ciu_ciudadano values($representante_tipo_identificacion,".$num_ciu.",'".mb_strtoupper($representante_nombres,"utf-8")."','".mb_strtoupper($representante_direccion,"utf-8")."','".mb_strtoupper($representante_apellidos,"utf-8")."','','".$representante_telefono."','".$representante_correo."',".$representante_ciudad.",".$representante_departamento.",'".$representante_id."')";
            $rs_ins_ciu=$db->conn->Execute($ins_ciu);
        

            //inserta en sec_dir_drecciones
            $ins_dir="insert into sgd_dir_drecciones(sgd_dir_codigo,sgd_dir_tipo,sgd_oem_codigo,sgd_ciu_codigo,radi_nume_radi,sgd_esp_codi,muni_codi,dpto_codi,sgd_dir_direccion,sgd_dir_telefono,sgd_sec_codigo,sgd_dir_nombre,sgd_dir_nomremdes,sgd_trd_codigo,sgd_dir_doc,sgd_dir_mail)
                values (".$num_dir.",1,".$num_oem.",0,$numeroRadicado,0,".$_SESSION['muni'].",".$_SESSION['depto'].",'".$_SESSION['direccion_remitente']."','".$_SESSION['telefono_remitente']."',0,'".mb_strtoupper($_SESSION['nombre_remitente'],"utf-8")." ".mb_strtoupper($_SESSION['apellidos_remitente'],"utf-8")."','".mb_strtoupper($_SESSION['nombre_remitente'],"utf-8")." ".mb_strtoupper($_SESSION['apellidos_remitente'],"utf-8")."',1,'".$_SESSION['nit']."','".$_SESSION['email']."'), (".++$num_dir.",2,0,".$num_ciu.",".$numeroRadicado.",0,".$representante_ciudad.",".$representante_departamento.",'".$representante_direccion."','".$representante_telefono."',0,'".mb_strtoupper($representante_nombres,"utf-8")." ".mb_strtoupper($representante_apellidos,"utf-8")."','".mb_strtoupper($representante_nombres,"utf-8")." ".mb_strtoupper($representante_apellidos,"utf-8")."',1,'".$representante_id."', '".$representante_correo."')";
        } else {
            $ins_dir="insert into sgd_dir_drecciones(sgd_dir_codigo,sgd_dir_tipo,sgd_oem_codigo,sgd_ciu_codigo,radi_nume_radi,sgd_esp_codi,muni_codi,dpto_codi,sgd_dir_direccion,sgd_dir_telefono,sgd_sec_codigo,sgd_dir_nombre,sgd_dir_nomremdes,sgd_trd_codigo,sgd_dir_doc,sgd_dir_mail)
                values (".$num_dir.",1,".$num_oem.",0,$numeroRadicado,0,".$_SESSION['muni'].",".$_SESSION['depto'].",'".$_SESSION['direccion_remitente']."','".$_SESSION['telefono_remitente']."',0,'".mb_strtoupper($_SESSION['nombre_remitente'],"utf-8")." ".mb_strtoupper($_SESSION['apellidos_remitente'],"utf-8")."','".mb_strtoupper($_SESSION['nombre_remitente'],"utf-8")." ".mb_strtoupper($_SESSION['apellidos_remitente'],"utf-8")."',1,'".$_SESSION['nit']."','".$_SESSION['email']."');";
        }
    }
    else {
        //Anonimo
        $num_ciu=$db->conn->GenID('SEC_CIU_CIUDADANO');
        $tipdoc= 0;
        $ins_ciu="insert into sgd_ciu_ciudadano values($tipdoc,".$num_ciu.",'".mb_strtoupper($_SESSION['nombre_remitente'],"utf-8")."','".mb_strtoupper($_SESSION['direccion_remitente'],"utf-8")."','".mb_strtoupper($_SESSION['apellidos_remitente'],"utf-8")."','','".$_SESSION['telefono_remitente']."','".$_SESSION['email']."',".$_SESSION['muni'].",".$_SESSION['depto'].",'".$_SESSION['cedula']."')";
        $rs_ins_ciu=$db->conn->Execute($ins_ciu);
        //inserta en sec_dir_drecciones
        $ins_dir="insert into sgd_dir_drecciones(sgd_dir_codigo,sgd_dir_tipo,sgd_oem_codigo,sgd_ciu_codigo,radi_nume_radi,sgd_esp_codi,muni_codi,dpto_codi,sgd_dir_direccion,sgd_dir_telefono,sgd_sec_codigo,sgd_dir_nombre,sgd_dir_nomremdes,sgd_trd_codigo,sgd_dir_doc,sgd_dir_mail)
            values(".$num_dir.",1,0,".$num_ciu.",$numeroRadicado,0,".$_SESSION['muni'].",".$_SESSION['depto'].",'".$_SESSION['direccion_remitente']."','".$_SESSION['telefono_remitente']."',0,'".mb_strtoupper($_SESSION['nombre_remitente'],"utf-8")." ".mb_strtoupper($_SESSION['apellidos_remitente'],"utf-8")."','".mb_strtoupper($_SESSION['nombre_remitente'],"utf-8")." ".mb_strtoupper($_SESSION['apellidos_remitente'],"utf-8")."',3,'".$_SESSION['cedula']."','".$_SESSION['email']."')";
    }

    $_SESSION['codigoverificacion'] = substr(sha1(microtime()), 0 , 5);
    $descripcionAnexos = $uploader->tieneArchivos?count($uploader->subidos):0;
    $descripcionAnexos .=  " Anexos";

    //inserta en radicado
    $ins_rad="insert into radicado (radi_nume_radi,radi_fech_radi,tdoc_codi,mrec_codi,eesp_codi,radi_fech_ofic,radi_pais,muni_codi,carp_codi,dpto_codi,radi_nume_hoja,radi_nume_folio,radi_desc_anex,";
    if($_SESSION['radicado']!=NULL)
    {
        $ins_rad.=" radi_nume_deri,";
    }
    $ins_rad.="radi_path,radi_usua_actu,radi_depe_actu,ra_asun,radi_depe_radi,radi_usua_radi,codi_nivel,flag_nivel,carp_per,radi_leido,radi_tipo_deri,sgd_fld_codigo,sgd_apli_codi,sgd_ttr_codigo,sgd_spub_codigo,sgd_tma_codigo,sgd_rad_codigoverificacion,depe_codi,sgd_trad_codigo)
        values ($numeroRadicado, now(),".$_SESSION['tipo'].",".$_SESSION['mrec_codi'].",".$_SESSION['codigo_orfeo'].",
            to_date('".date('d')."/".date('m')."/".date('Y')."','dd/mm/yyyy')
            ,'COLOMBIA'
            ,".$_SESSION['muni']."
            ,0,".$_SESSION['depto']."
            ,1,0
            ,'". $descripcionAnexos ."', ";

    if($_SESSION['radicado']!=NULL){
        $ins_rad.=$_SESSION['radicado'].", ";
    }

    $depeRadicaFormularioWeb =  $_SESSION['depeRadicaFormularioWeb'];
    $anoRad = date("Y");
    if(!$tipoPoblacion) $tipoPoblacion = "0";
    $directorio = '../bodega/'.$anoRad.'/'.intval($depeRadicaFormularioWeb);
    if (!file_exists($directorio)) 
    {
        mkdir($directorio, 0777, true);
    }

    if (!file_exists($directorio.'/docs'))
    {
        mkdir($directorio.'/docs', 0777, true);
    }

    $rutaPdf ="/$anoRad/".intval($depeRadicaFormularioWeb)."/$numeroRadicado".".pdf";
    $ins_rad.="'/$anoRad/".intval($depeRadicaFormularioWeb)."/$numeroRadicado".".pdf'
        ,".$_SESSION['usuaRecibeWeb']."
        ,".$_SESSION['depeRadicaFormularioWeb']."
        ,'".mb_strtoupper($_SESSION['asunto'],"utf-8")."'
        ,".$_SESSION['depeRadicaFormularioWeb'].",1,5,1,0,0,1,0,0,0,0,$tipoPoblacion,'".$_SESSION['codigoverificacion']."',".$_SESSION['depeRadicaFormularioWeb'].",2)";
    if($rs_ins_rad=$db->conn->Execute($ins_rad)){
        $rs_ins_dir=$db->conn->Execute($ins_dir);
    }else{
        die;
    }

    $hist_doc_dest = $db->conn->getOne('SELECT usua_doc FROM usuario WHERE usua_codi = ? and depe_codi = ?',[$_SESSION['usuaRecibeWeb'], $_SESSION['depeRadicaFormularioWeb']]);
    //Inserta historico
    $ins_his="insert into hist_eventos (depe_codi,hist_fech,usua_codi,radi_nume_radi,hist_obse,usua_codi_dest,usua_doc,sgd_ttr_codigo,hist_doc_dest,depe_codi_dest)
        values($dependenciaRad,now(),6,$numeroRadicado,'RADICACION PAGINA WEB',".$_SESSION['usuario'].",'22222222',2,'".$hist_doc_dest."',".$_SESSION['dependencia'].")";
    $rs_ins_his=$db->conn->Execute($ins_his);

    //num radicado completo
    $_SESSION['radcom']=$numeroRadicado;


    $uploader->bodega_dir .= date('Y') . "/" . $_SESSION['depeRadicaFormularioWeb'] . "/docs";
    $uploader->moverArchivoCarpetaBodega($numeroRadicado);
    //var_dump($uploader->subidos);
    //exit;
    //trae usualogin

    $sql_login="select usua_login from usuario where usua_codi=".$_SESSION["usuaRecibeWeb"]." and depe_codi=".$_SESSION["depeRadicaFormularioWeb"];
    $rs_login=$db->conn->getOne($sql_login);

    //insertar anexos
    $fechaval=valida_fecha($db);
    $_SESSION['cantidad_adjuntos'] = 0;
    if($uploader->tieneArchivos){
        //var_dump($uploader->subidos);
        $counter = 0;
        foreach($uploader->subidos as $key => $archivo)
        {
            if(strlen($archivo) == 0){
                continue;
            }
            //echo $key.',';
            $counter ++;
            $_SESSION['cantidad_adjuntos'] = $_SESSION['cantidad_adjuntos'] + 1;
            $extension = strtolower(end(explode('.',$archivo)));
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
                values(".$numeroRadicado.",".$numeroRadicado.sprintf("%05d",($counter)).",".$tipoCodigo.",".$uploader->sizes[$counter - 1].",'S','".$rs_login."','".$tipo_documento[$key]."',1,'".$uploader->nombreOrfeo[$key]."','N',0,0,0,1,1,".$_SESSION["depeRadicaFormularioWeb"].",now(),0)";
                $rs_ins_anex = $db->conn->Execute($ins_anex);
            }
        /*for($i=0; $i < count($uploader->subidos);$i++)
        {
            
            //$origen = '../bodega/tmp/'.$uploader->subidos[$i];
            //$destino = $directorio.'/docs/'.$uploader->nombreOrfeo[$i];
            //$copy = copy($origen, $destino);
            
            

           
        }*/
    }

    require('barcode.php');
    $_SESSION['depeRadicaFormularioWeb']=$depeRadicaFormularioWeb;
    $depeNomb = "";
    $muniNomb = "";
    $deptNomb = "";
    $paisNomb = "";
    $sql_depeNomb = "select depe_nomb from dependencia where depe_codi = ". $_SESSION['depeRadicaFormularioWeb'];
    $rs_depeNomb = $db->conn->Execute($sql_depeNomb);
    if(!$rs_depeNomb->EOF){
        $depeNomb = substr($rs_depeNomb->fields["depe_nomb"],0,40);
    }

    $sql_muniNomb = "select muni_nomb from municipio where muni_codi = ". $_SESSION['muni'] . " and dpto_codi = " . $_SESSION['depto'] ;
    $rs_muniNomb = $db->conn->Execute($sql_muniNomb);
    if(!$rs_muniNomb->EOF){
        $muniNomb = $rs_muniNomb->fields["muni_nomb"];
    }else {
        $muniNomb = "";
    }

    $sql_deptoNomb = "select dpto_nomb from departamento where dpto_codi = ". $_SESSION['depto'] . " and id_pais = 170";
    $rs_deptoNomb = $db->conn->Execute($sql_deptoNomb);
    if(!$rs_deptoNomb->EOF){
        $deptNomb = $rs_deptoNomb->fields["DPTO_NOMB"];
    }else{
        $deptNomb = "";
    }

    $sql_paisNomb = "select nombre_pais from sgd_def_paises where id_pais = ". $pais_formulario;
    $rs_paisNomb = $db->conn->Execute($sql_paisNomb);
    if(!$rs_paisNomb->EOF){
        $paisNomb = $rs_paisNomb->fields["NOMBRE_PAIS"];
    }else{
        $paisNomb = "No Registra";
    }

    $sql_rel_entidad = "INSERT INTO sgd_rad_entidades (radi_nume_radi, ent_id) VALUES (".$_SESSION['radcom'].", ".$entidad.")";
    $db->conn->Execute($sql_rel_entidad);
    $pdf=new PDF_Code39();
    $pdf->SetTitle('Radicado '.$numeroRadicado);
    $pdf->SetAuthor($entidad_largo);
    $pdf->SetSubject($_SESSION['asunto']);
    $pdf->AddFont('DejaVuSansCondensed', '', 'DejaVuSansCondensed.php');
    $pdf->AddFont('DejaVuSansCondensed-Bold', '', 'DejaVuSansCondensed-Bold.php');
    $pdf->SetFont('DejaVuSansCondensed', '', 8);
    $pdf->AddPage();

    $pdf->Code39(110,25,$_SESSION['radcom'],1,8);
    $pdf->Text(130,37,textoPDF("Radicado N°. ".$_SESSION['radcom']));
    //$pdf->Image('../bodega'.$_SESSION["logoEntidad"],20,20,75);
    //$pdf->SetFont('Arial','',16);
    //$pdf->Text(110,40,textoPDF(textoPDF($entidad_largo)));
    $pdf->Text(110,41,textoPDF(date('d')." - ".date('m')." - ".date('Y')." ".date('h:i:s')) . "   Folios: N/A (WEB)   Anexos: ". $_SESSION['cantidad_adjuntos'] );
    $pdf->SetFont('DejaVuSansCondensed', '', 8);
    $pdf->Text(110,45,textoPDF("Destino: ". $depeRadicaFormularioWeb . " " . substr($depeNomb, 0,10) ." - Rem/D: ". substr($_SESSION['nombre_remitente'],0,10)." ".substr($_SESSION['apellidos_remitente'],0,10)));
    $pdf->Text(110,48,textoPDF("Consulte el su trámite, en la pagina de la entidad"));
    $pdf->Text(135,51,textoPDF("Código de verificación: " . $_SESSION['codigoverificacion']));
    //$pdf->Text(110,51,textoPDF(strtoupper($_SESSION['nombre_remitente'])." ".strtoupper($_SESSION['apellidos_remitente'])));
    //$pdf->Text(110,55,$_SESSION['cedula']!='0'?$_SESSION['cedula']:$_SESSION['nit']);

    $pdf->Text(12,67,textoPDF("Bogotá D.C., ".date('d')." de ".nombremes(date('m'))." de ".date('Y')));
    $pdf->Text(12,81,textoPDF("Señores"));
    $pdf->SetFont('DejaVuSansCondensed-Bold', '', 8);
    $pdf->Text(12,85,textoPDF($entidad_largo));
    $pdf->SetFont('DejaVuSansCondensed', '', 8);
    $pdf->Text(12,89,textoPDF("Ciudad"));
    $pdf->Text(12,99,textoPDF("Asunto : ".mb_strtoupper($_SESSION['asunto'],"utf-8")));
    $pdf->SetXY(11,105);
    //$pdf->MultiCell(0,4,textoPDF($_SESSION['desc'],0));
    $_SESSION['desc'] .= "\nAtentamente, ".textoPDF(($_SESSION['nombre_remitente'])." ".$_SESSION['apellidos_remitente']).
                         "\n".textoPDF($tipo_doc_direccion.': '.($_SESSION['cedula'] != '0' ? $_SESSION['cedula'] : $_SESSION['nit'])).
                         "\n".textoPDF($_SESSION['direccion_remitente'] . " " . $muni_direccion . ", ". $depto_direccion . ".").
                         "\n".textoPDF($paisNomb).
                         "\n".textoPDF("Tel. " . $_SESSION['telefono_remitente']).
                         "\n".($_SESSION['email'] != '@' ? textoPDF($_SESSION['email']) : '');

    $pdf->MultiCell(0,4,$_SESSION['desc'],0);
    
    //guarda documento en un SERVIDOR
    $pdf->Output("../bodega/$rutaPdf",'F');
    //Realizar el conteo de hojas del radicado final//
    $conteoPaginas = getNumPagesPdf("../bodega/$rutaPdf");

    $sqlu = "UPDATE radicado SET radi_nume_hoja= $conteoPaginas where radi_nume_radi=" . $_SESSION['radcom'];
    $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
    $db->conn->Execute($sqlu);

    //Envio del correo electronico
    $_SESSION["idFormulario"] = "";
    $validar_radicado = "SELECT * FROM radicado WHERE radi_nume_radi = ?";
    $res = $db->conn->getRow($validar_radicado, [$numeroRadicado]);

    if (trim($res['RA_ASUN']) == '' || 
        trim($res['RA_ASUN']) == 'ADJUNTO PROCESO JURISDICCIONAL:' ||
        trim($res['RA_ASUN']) == 'ADJUNTO SOLICITUD DE CONCILIACIÓN:')
    {
        $archivar = $db->conn->Execute("UPDATE radicado SET radi_depe_actu = ?, radi_usua_actu = ? WHERE radi_nume_radi = ? ", ['999', '15', $numeroRadicado]);
        $errorFormulario = 1;
    } else {
        $errorFormulario = 0;
        $codTx = 1983;
        $tipo_solicitud = 'solicitud de conciliación';
        $mensaje_tipo = "Se ha registrado en el Sistema de Información de la Superintendencia Nacional de Salud la solicitud de conciliación con el número de radicado ".$_SESSION['radcom'].". Por favor conserve el número para consultar el estado de su solicitud.";
        include($ruta_raiz.'/include/mail/GENERAL.mailInformar.php');
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
        <?php if($errorFormulario==0) { ?>
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
        <?php }  else if ($errorFormulario==1) { ?>
            <div class="row">
                <div class="col-sm">
                    <div class="alert alert-danger" role="alert">
                        <h4>Error!</h4>
                        Disculpe, no se pudo procesar su solicitud por favor intentelo nuevamente.
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <form name=back action="javascript:history.go(-1)()" method=post>
                        <input type="button" class="btn btn-default" value="Atrás">
                    </form>
                </div>
            </div>
        <?php } else if($errorFormulario==2) { ?>
            <div class="row">
                <div class="col-sm">
                    <div class="alert alert-danger" role="alert">
                        <h4>Error!</h4>
                        Ocurrió un error en al subida de archivo
                    </div>
                    <p class="lead">
                        <small>
                            <?php echo implode($uploader->messages); ?>
                        </small>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <form name=back action="javascript:history.go(-1)()" method=post>
                        <input type="button" class="btn btn-default" value="Atrás">
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
<?php include ('footer.php') ?>
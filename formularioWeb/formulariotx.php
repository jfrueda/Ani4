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


require_once("$ruta_raiz/include/db/ConnectionHandler.php");
require_once("$ruta_raiz/processConfig.php");
require_once('funciones.php');
include_once('./adjuntarArchivos.php');


foreach ($_GET as $key => $valor)   ${$key} = $valor; //iconv("ISO-8859-1","UTF-8",$valor);
foreach ($_POST as $key => $valor)   ${$key} = $valor; //iconv("ISO-8859-1","UTF-8",$valor);
$pais_formulario = $pais;
define('ADODB_ASSOC_CASE', 2);
$ADODB_COUNTRECS = false;


$db   = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$log = "https://upload.wikimedia.org/wikipedia/commons/c/c4/LOGO_UMNG.png";

session_start();
$errorFormulario = 0;

if (strcasecmp($captcha, $_SESSION['captcha_formulario']['code']) != 0 || strcasecmp($idFormulario, $_SESSION["idFormulario"]) != 0) {
    $errorFormulario = 1;
}
if ($errorFormulario == 0) {
    $uploader = new Uploader($_FILES);
    $uploader->FILES = $_FILES;
    $adjuntosSubidos = json_decode($adjuntosSubidos);
    $uploader->subidos = $adjuntosSubidos;
    $uploader->adjuntarYaSubidos();
}

$_SESSION['depeRadicaFormularioWeb'] = $depeRadicaFormularioWeb;
if ($errorFormulario == 0) {
    if ($anonimo == 1) {
        //Esto es anónimo
        $_SESSION['nombre_remitente'] = "Anónimo";
        $_SESSION['apellidos_remitente'] = "N.N";
        $_SESSION['cedula'] = 0;
        $_SESSION['nit'] = 0;
        $_SESSION['depto'] = 0;
        $_SESSION['muni'] = $muni;
        $_SESSION['direccion_remitente'] = "No registra";
        $_SESSION['telefono_remitente'] = "No registra";
        $_SESSION['email'] = $email == '' ? "" : $email;
        $mediorespuesta = $_SESSION['email'] == "" ? 3 : $mediorespuesta;
        //Puede ser anonima.
        if (!$_SESSION['nombre_remitente']) $_SESSION['nombre_remitente'] = "Anónimo";
        if (!$_SESSION['cedula']) $_SESSION['cedula'] = 0;
        if (!$_SESSION['depto']) $_SESSION['depto'] = 0;
        if (!$_SESSION['muni']) $_SESSION['muni'] = 0;
        if (!$_SESSION['direccion_remitente']) $_SESSION['direccion_remitente'] = "No registra";
        if (!$_SESSION['telefono_remitente']) $_SESSION['telefono_remitente'] = "No registra";
        $_SESSION['email'] = $email == '' ? "" : $email;
    } else if ($anonimo == 0) {
        //No es anónimo
        $_SESSION['nombre_remitente'] = $nombre_remitente;
        $_SESSION['apellidos_remitente'] = $apellidos_remitente;
        if ($tipoDocumento == 0) {
            //No selecciono tipo de documento
            $_SESSION['cedula'] = 0;
            $_SESSION['nit'] = 0;
        } else if ($tipoDocumento == 5) {
            //Tipo de documento NIT
            $_SESSION['cedula'] = 0;
            $_SESSION['nit'] = $numid != "" ? $numid : 0;
        } else {
            //Tipo de documento diferente de NIT
            $_SESSION['cedula'] = $numid;
            $_SESSION['nit'] = 0;
        }

        if ($depto != 0 && ($muni < 1 || $muni > 999)) {
            $muni = 1;
        }

        $_SESSION['depto'] = $depto;
        $_SESSION['muni'] = $muni;
        $_SESSION['direccion_remitente'] = $direccion == '' ? "No registra" : $direccion;
        $_SESSION['telefono_remitente'] = $telefono;
        $_SESSION['email'] = $email == '' ? "No registra" : $email;
    }

    if ($pqrsFacebook == "1") {
        //Medio de recepción Facebook
        $_SESSION['mrec_codi'] = 10;
    } else {
        //Medio de recepción Internet
        $_SESSION['mrec_codi'] = 4;
    }
    $_SESSION['tipo'] = $tipoSolicitud;
    $_SESSION['tipoSolicitudTexto'] = $tipoSolicitudTexto ? $tipoSolicitudTexto : 'No especificado';
    $_SESSION['tipoSolicitante'] = $tipoSolicitante ? $tipoSolicitante : 'No especificado';
    $_SESSION['servicio'] = $servicio ? $servicio : 'No especificado';
    $_SESSION['sistemaAplica'] = $sistemaAplica ? $sistemaAplica : 'No aplica';
    $_SESSION['medioContacto'] = $medioContacto ? $medioContacto : 'No especificado';
    $_SESSION['celular'] = $celular ? $celular : 'No registra';
    $_SESSION['asunto'] = $asunto;
    $_SESSION['desc'] = textoPDF($comentario);
    //TODO Imprimir el grupo de poblacional haciendo la consulta a sgd_tma_temas
    //$_SESSION['desc'].= textoPDF("Manifiesto que pertenezco al grupo pblacional: " );
    $_SESSION['desc'] .= textoPDF("\n\n" . $uploader->listadoImprimible);

    //TODO Revisar que hacer con todas estas otras cosas.
    //radicado.eesp_codi
    $_SESSION['codigo_orfeo'] = "0";

    $_SESSION['sigla'] = $_GET['sigla'];
    if (!$_GET['sigla']) $_SESSION['sigla'] = "0";
    $_SESSION['usuario'] = 1;
    if (!$_SESSION['dependencia']) $_SESSION['dependencia'] = 900;
    $dependenciaRad = $_SESSION['dependencia'];
    $_SESSION['radicado'] = $_GET['radicado'];
    $_SESSION['documento_destino'] = $_GET['documento_destino'];
    $numero_completado = "000000" . $db->conn->GenID("SECR_TP2_" . $secRadicaFormularioWeb);
    $numero = substr($numero_completado, -6);
    $num_dir = $db->conn->GenID('SEC_DIR_DRECCIONES');
    $dependenciaCompletada = "00000" . $_SESSION['depeRadicaFormularioWeb'];

    /**
     * $depeRadicaFormularioWeb;  // Es radicado en la Dependencia 900
     * $usuaRecibeWeb ; // Usuario que Recibe los Documentos Web
     * $secRadicaFormularioWeb ;
     ***/

    $numeroRadicado = date('Y') . substr($dependenciaCompletada, -1 * $digitosDependencia) . $numero . "2";

    if ($tipoDocumento > 0 && $tipoDocumento != 5) {
        //inserta ciudadano
        $num_ciu = $db->conn->GenID('SEC_CIU_CIUDADANO');
        $tipdoc = $tipoDocumento - 1;
        $ins_ciu = "insert into sgd_ciu_ciudadano values($tipdoc," . $num_ciu . ",'" . mb_strtoupper($_SESSION['nombre_remitente'], "utf-8") . "','" . mb_strtoupper($_SESSION['direccion_remitente'], "utf-8") . "','" . mb_strtoupper($_SESSION['apellidos_remitente'], "utf-8") . "','','" . $_SESSION['telefono_remitente'] . "','" . $_SESSION['email'] . "'," . $_SESSION['muni'] . "," . $_SESSION['depto'] . ",'" . $_SESSION['cedula'] . "')";
        $rs_ins_ciu = $db->conn->Execute($ins_ciu);
        //inserta en sec_dir_drecciones
        $ins_dir = "insert into sgd_dir_drecciones(sgd_dir_codigo,sgd_dir_tipo,sgd_oem_codigo,sgd_ciu_codigo,radi_nume_radi,sgd_esp_codi,muni_codi,dpto_codi,sgd_dir_direccion,sgd_dir_telefono,sgd_sec_codigo,sgd_dir_nombre,sgd_dir_nomremdes,sgd_trd_codigo,sgd_dir_doc,sgd_dir_mail)
            values(" . $num_dir . ",1,0," . $num_ciu . ",$numeroRadicado,0," . $_SESSION['muni'] . "," . $_SESSION['depto'] . ",'" . $_SESSION['direccion_remitente'] . "','" . $_SESSION['telefono_remitente'] . "',0,'" . mb_strtoupper($_SESSION['nombre_remitente'], "utf-8") . " " . mb_strtoupper($_SESSION['apellidos_remitente'], "utf-8") . "','" . mb_strtoupper($_SESSION['nombre_remitente'], "utf-8") . " " . mb_strtoupper($_SESSION['apellidos_remitente'], "utf-8") . "',1,'" . $_SESSION['cedula'] . "','" . $_SESSION['email'] . "')";
    } else if ($tipoDocumento == 5) {
        //TODO preguntar como tratar la llave foranea que hay en sgc_dir_direcciones hacia ciu_ciudadano si se trata de una empresa
        $num_oem = $db->conn->GenID('SEC_OEM_EMPRESAS');
        //insertar empresa en sgc_oem_empresas
        $tipdoc = $tipoDocumento - 1;
        $ins_empresa = "insert into sgd_oem_oempresas values($num_oem,$tipdoc,'" . mb_strtoupper($_SESSION['nombre_remitente'], "utf-8") . " " . mb_strtoupper($_SESSION['apellidos_remitente'], "utf-8") . "', '','" . $_SESSION['nit'] . "',''," . $_SESSION['muni'] . "," . $_SESSION['depto'] . ",'" . mb_strtoupper($_SESSION['direccion_remitente'], "utf-8") . "'," . $_SESSION['telefono_remitente'] . ")";
        $rs_ins_oem = $db->conn->Execute($ins_empresa);
        //inserta en sec_dir_drecciones
        $ins_dir = "insert into sgd_dir_drecciones(sgd_dir_codigo,sgd_dir_tipo,sgd_oem_codigo,sgd_ciu_codigo,radi_nume_radi,sgd_esp_codi,muni_codi,dpto_codi,sgd_dir_direccion,sgd_dir_telefono,sgd_sec_codigo,sgd_dir_nombre,sgd_dir_nomremdes,sgd_trd_codigo,sgd_dir_doc,sgd_dir_mail)
            values(" . $num_dir . ",1," . $num_oem . ",0,$numeroRadicado,0," . $_SESSION['muni'] . "," . $_SESSION['depto'] . ",'" . $_SESSION['direccion_remitente'] . "','" . $_SESSION['telefono_remitente'] . "',0,'" . mb_strtoupper($_SESSION['nombre_remitente'], "utf-8") . " " . mb_strtoupper($_SESSION['apellidos_remitente'], "utf-8") . "','" . mb_strtoupper($_SESSION['nombre_remitente'], "utf-8") . " " . mb_strtoupper($_SESSION['apellidos_remitente'], "utf-8") . "',1,'" . $_SESSION['nit'] . "','" . $_SESSION['email'] . "')";
    } else {
        //Anonimo
        $num_ciu = $db->conn->GenID('SEC_CIU_CIUDADANO');
        $tipdoc = $tipoDocumento - 1;
        $ins_ciu = "insert into sgd_ciu_ciudadano values($tipdoc," . $num_ciu . ",'" . mb_strtoupper($_SESSION['nombre_remitente'], "utf-8") . "','" . mb_strtoupper($_SESSION['direccion_remitente'], "utf-8") . "','" . mb_strtoupper($_SESSION['apellidos_remitente'], "utf-8") . "','','" . $_SESSION['telefono_remitente'] . "','" . $_SESSION['email'] . "'," . $_SESSION['muni'] . "," . $_SESSION['depto'] . ",'" . $_SESSION['cedula'] . "')";
        $rs_ins_ciu = $db->conn->Execute($ins_ciu);
        //inserta en sec_dir_drecciones
        $ins_dir = "insert into sgd_dir_drecciones(sgd_dir_codigo,sgd_dir_tipo,sgd_oem_codigo,sgd_ciu_codigo,radi_nume_radi,sgd_esp_codi,muni_codi,dpto_codi,sgd_dir_direccion,sgd_dir_telefono,sgd_sec_codigo,sgd_dir_nombre,sgd_dir_nomremdes,sgd_trd_codigo,sgd_dir_doc,sgd_dir_mail)
            values(" . $num_dir . ",1,0," . $num_ciu . ",$numeroRadicado,0," . $_SESSION['muni'] . "," . $_SESSION['depto'] . ",'" . $_SESSION['direccion_remitente'] . "','" . $_SESSION['telefono_remitente'] . "',0,'" . mb_strtoupper($_SESSION['nombre_remitente'], "utf-8") . " " . mb_strtoupper($_SESSION['apellidos_remitente'], "utf-8") . "','" . mb_strtoupper($_SESSION['nombre_remitente'], "utf-8") . " " . mb_strtoupper($_SESSION['apellidos_remitente'], "utf-8") . "',3,'" . $_SESSION['cedula'] . "','" . $_SESSION['email'] . "')";
    }

    $_SESSION['codigoverificacion'] = substr(sha1(microtime()), 0, 5);
    $descripcionAnexos = $uploader->tieneArchivos ? count($uploader->subidos) : 0;
    $descripcionAnexos .=  " Anexos";

    //inserta en radicado
    $ins_rad = "insert into radicado (radi_nume_radi,radi_fech_radi,tdoc_codi,mrec_codi,eesp_codi,radi_fech_ofic,radi_pais,muni_codi,carp_codi,dpto_codi,radi_nume_hoja,radi_nume_folio,radi_desc_anex,";
    if ($_SESSION['radicado'] != NULL) {
        $ins_rad .= " radi_nume_deri,";
    }
    $ins_rad .= "radi_path,radi_usua_actu,radi_depe_actu,ra_asun,radi_depe_radi,radi_usua_radi,codi_nivel,flag_nivel,carp_per,radi_leido,radi_tipo_deri,sgd_fld_codigo,sgd_apli_codi,sgd_ttr_codigo,sgd_spub_codigo,sgd_tma_codigo,sgd_rad_codigoverificacion,depe_codi,sgd_trad_codigo)
        values ($numeroRadicado, now()," . $_SESSION['tipo'] . "," . $_SESSION['mrec_codi'] . "," . $_SESSION['codigo_orfeo'] . ",
            to_date('" . date('d') . "/" . date('m') . "/" . date('Y') . "','dd/mm/yyyy')
            ,'COLOMBIA'
            ," . $_SESSION['muni'] . "
            ,0," . $_SESSION['depto'] . "
            ,1,0
            ,'" . $descripcionAnexos . "', ";

    if ($_SESSION['radicado'] != NULL) {
        $ins_rad .= $_SESSION['radicado'] . ", ";
    }

    $depeRadicaFormularioWeb =  $_SESSION['depeRadicaFormularioWeb'];
    $anoRad = date("Y");
    if (!$tipoPoblacion) $tipoPoblacion = "0";
    $rutaPdf = "/$anoRad/" . intval($depeRadicaFormularioWeb) . "/$numeroRadicado" . ".pdf";
    $ins_rad .= "'/$anoRad/" . intval($depeRadicaFormularioWeb) . "/$numeroRadicado" . ".pdf'
        ," . $_SESSION['usuaRecibeWeb'] . "
        ," . $_SESSION['depeRadicaFormularioWeb'] . "
        ,'" . mb_strtoupper($_SESSION['asunto'], "utf-8") . "'
        ," . $_SESSION['depeRadicaFormularioWeb'] . ",1,5,1,0,0,1,0,0,0,0,$tipoPoblacion,'" . $_SESSION['codigoverificacion'] . "'," . $_SESSION['depeRadicaFormularioWeb'] . ",2)";

    if ($rs_ins_rad = $db->conn->Execute($ins_rad)) {
        $rs_ins_dir = $db->conn->Execute($ins_dir);
    } else {
        die;
    }
    //Inserta historico
    $ins_his = "insert into hist_eventos (depe_codi,hist_fech,usua_codi,radi_nume_radi,hist_obse,usua_codi_dest,usua_doc,sgd_ttr_codigo,hist_doc_dest,depe_codi_dest)
        values($dependenciaRad,now(),6,$numeroRadicado,'RADICACION PAGINA WEB'," . $_SESSION['usuario'] . ",'22222222',2,'" . $_SESSION['documento_destino'] . "'," . $_SESSION['dependencia'] . ")";
    $rs_ins_his = $db->conn->Execute($ins_his);

    //num radicado completo
    $_SESSION['radcom'] = $numeroRadicado;


    $uploader->bodega_dir .= date('Y') . "/" . $_SESSION['depeRadicaFormularioWeb'] . "/docs/";
    $uploader->moverArchivoCarpetaBodegaYaSubidos($numeroRadicado);


    //trae usualogin
    $sql_login = "select usua_login from usuario where usua_codi=" . $_SESSION["usuaRecibeWeb"] . " and depe_codi=" . $_SESSION["depeRadicaFormularioWeb"];
    $rs_login = $db->conn->Execute($sql_login);

    // Verificar que el usuario existe y tiene login
    $usuarioCreador = 'web';
    if ($rs_login && !$rs_login->EOF && isset($rs_login->fields['USUA_LOGIN'])) {
        $usuarioCreador = $rs_login->fields['USUA_LOGIN'];
    }
    error_log("Usuario creador de anexos: " . $usuarioCreador);


    //insertar anexos
    $fechaval = valida_fecha($db);
    $_SESSION['cantidad_adjuntos'] = 0;
    if ($uploader->tieneArchivos) {
        for ($i = 0; $i < count($uploader->subidos); $i++) {
            if (strlen($uploader->subidos[$i]) == 0) {
                continue;
            }
            $_SESSION['cantidad_adjuntos'] = $_SESSION['cantidad_adjuntos'] + 1;
            $extension = strtolower(end(explode('.', $uploader->subidos[$i])));

            // Buscar tipo de anexo por extensión
            $sql_tipoAnex = "select anex_tipo_codi from anexos_tipo where anex_tipo_ext = '" . $extension . "'";
            $rs_tipoAnexo = $db->conn->Execute($sql_tipoAnex);
            $tipoCodigo = 7; // Valor por defecto: PDF (anex_tipo_codi=7)

            if ($rs_tipoAnexo && !$rs_tipoAnexo->EOF) {
                $tipoCodigo = $rs_tipoAnexo->fields["ANEX_TIPO_CODI"];
            } else {
                // Si no encuentra por extensión, buscar el tipo genérico (*)
                $sql_tipoAnex = "select anex_tipo_codi from anexos_tipo where anex_tipo_ext = '*'";
                $rs_tipoAnexo = $db->conn->Execute($sql_tipoAnex);
                if ($rs_tipoAnexo && !$rs_tipoAnexo->EOF) {
                    $tipoCodigo = $rs_tipoAnexo->fields["ANEX_TIPO_CODI"];
                }
            }

            // Asegurar que tipoCodigo tenga un valor válido
            if (empty($tipoCodigo) || $tipoCodigo == '') {
                $tipoCodigo = 7; // Valor por defecto: PDF si todo falla
            }

            error_log("Extension: " . $extension);
            error_log("Tipo de código anexo: " . $tipoCodigo);
            error_log("Tamaño archivo: " . $uploader->sizes[$i] . " KB");
            error_log("SHA1: " . $uploader->sha1sums[$i]);
            error_log("Nombre Orfeo: " . $uploader->nombreOrfeo[$i]);

            // Preparar la ruta de la carpeta donde está el archivo
            $anoRad = date("Y");
            $carpetaAnexo = $anoRad . "/" . intval($_SESSION['depeRadicaFormularioWeb']) . "/docs/";
            error_log("Carpeta anexo: " . $carpetaAnexo);

            $ins_anex = "insert into anexos(
                anex_radi_nume,
                anex_codigo,
                anex_tipo,
                anex_tamano,
                anex_solo_lect,
                anex_creador,
                anex_desc,
                anex_numero,
                anex_nomb_archivo,
                anex_borrado,
                anex_origen,
                anex_salida,
                anex_estado,
                sgd_rem_destino,
                sgd_dir_tipo,
                anex_depe_creador,
                anex_fech_anex,
                sgd_apli_codi,
                sgd_trad_codigo,
                anex_carpeta,
                anex_hash
            ) values(
                " . $numeroRadicado . ",
                " . $numeroRadicado . sprintf("%05d", ($i + 1)) . ",
                " . $tipoCodigo . ",
                " . $uploader->sizes[$i] . ",
                'S',
                '" . $usuarioCreador . "',
                '" . $uploader->sha1sums[$i] . "',
                1,
                '" . $uploader->nombreOrfeo[$i] . "',
                'N',
                0,
                0,
                0,
                1,
                1,
                " . $_SESSION["depeRadicaFormularioWeb"] . ",
                now(),
                0,
                2,
                '" . $carpetaAnexo . "',
                '" . $uploader->sha1sums[$i] . "'
            )";

            error_log("SQL INSERT ANEXO: " . $ins_anex);

            $rs_ins_anex = $db->conn->Execute($ins_anex);

            if ($rs_ins_anex) {
                error_log("Anexo insertado correctamente: " . $uploader->nombreOrfeo[$i]);
            } else {
                error_log("ERROR al insertar anexo: " . $db->conn->ErrorMsg());
            }
        }
    }


    require('barcode.php');
    $_SESSION['depeRadicaFormularioWeb'] = $depeRadicaFormularioWeb;
    $depeNomb = "";
    $muniNomb = "";
    $deptNomb = "";
    $paisNomb = "";
    $sql_depeNomb = "select depe_nomb from dependencia where depe_codi = " . $_SESSION['depeRadicaFormularioWeb'];
    $rs_depeNomb = $db->conn->Execute($sql_depeNomb);
    if (!$rs_depeNomb->EOF) {
        $depeNomb = substr($rs_depeNomb->fields["DEPE_NOMB"], 0, 40);
    }

    $sql_muniNomb = "select muni_nomb from municipio where muni_codi = " . $_SESSION['muni'] . " and dpto_codi = " . $_SESSION['depto'];
    $rs_muniNomb = $db->conn->Execute($sql_muniNomb);
    if (!$rs_muniNomb->EOF) {
        $muniNomb = $rs_muniNomb->fields["MUNI_NOMB"];
    } else {
        $muniNomb = "";
    }

    $sql_deptoNomb = "select dpto_nomb from departamento where dpto_codi = " . $_SESSION['depto'] . " and id_pais = 170";
    $rs_deptoNomb = $db->conn->Execute($sql_deptoNomb);
    if (!$rs_deptoNomb->EOF) {
        $deptNomb = $rs_deptoNomb->fields["DPTO_NOMB"];
    } else {
        $deptNomb = "";
    }

    $sql_paisNomb = "select nombre_pais from sgd_def_paises where id_pais = " . $pais_formulario;
    $rs_paisNomb = $db->conn->Execute($sql_paisNomb);
    if (!$rs_paisNomb->EOF) {
        $paisNomb = $rs_paisNomb->fields["NOMBRE_PAIS"];
    } else {
        $paisNomb = "No Registra";
    }


    $pdf = new PDF_Code39();
    $pdf->AddPage();

    $pdf->Code39(110, 25, $_SESSION['radcom'], 1, 8);
    $pdf->Text(130, 37, textoPDF("Radicado N°. " . $_SESSION['radcom']));
    //$pdf->Image('../bodega'.$_SESSION["logoEntidad"],20,20,75);
    //$pdf->SetFont('Arial','',16);
    //$pdf->Text(110,40,textoPDF(textoPDF($entidad_largo)));
    $pdf->Text(110, 41, textoPDF(date('d') . " - " . date('m') . " - " . date('Y') . " " . date('h:i:s')) . "   Folios: N/A (WEB)   Anexos: " . $_SESSION['cantidad_adjuntos']);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Text(110, 45, textoPDF("Destino: " . $depeRadicaFormularioWeb . " " . substr($depeNomb, 0, 10) . " - Rem/D: " . substr($_SESSION['nombre_remitente'], 0, 10) . " " . substr($_SESSION['apellidos_remitente'], 0, 10)));
    $pdf->SetFont('Arial', '', 7);
    $pdf->Text(110, 48, textoPDF("Consulte el su trámite, en la pagina de la entidad"));
    $pdf->Text(135, 51, textoPDF("Código de verificación: " . $_SESSION['codigoverificacion']));
    //$pdf->Text(110,51,textoPDF(strtoupper($_SESSION['nombre_remitente'])." ".strtoupper($_SESSION['apellidos_remitente'])));
    //$pdf->Text(110,55,$_SESSION['cedula']!='0'?$_SESSION['cedula']:$_SESSION['nit']);

    $pdf->Text(12, 67, textoPDF("Bogotá D.C., " . date('d') . " de " . nombremes(date('m')) . " de " . date('Y')));
    $pdf->Text(12, 81, textoPDF("Señores"));
    $pdf->SetFont('', 'B');
    $pdf->Text(12, 85, textoPDF($entidad_largo));
    $pdf->SetFont('', '');
    $pdf->Text(12, 89, textoPDF("Ciudad"));
    $pdf->Text(12, 99, textoPDF("Asunto : " . mb_strtoupper($_SESSION['asunto'], "utf-8")));

    // Información adicional del formulario
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Text(12, 106, "Informacion de la solicitud:");
    $pdf->SetFont('Arial', '', 8);
    $pdf->Text(12, 110, textoPDF("Tipo de Peticion: " . $_SESSION['tipoSolicitudTexto']));
    $pdf->Text(12, 114, textoPDF("Tipo de Solicitante: " . $_SESSION['tipoSolicitante']));
    $pdf->Text(12, 118, textoPDF("Servicio: " . $_SESSION['servicio']));
    $pdf->Text(12, 122, textoPDF("Sistema al que Aplica: " . $_SESSION['sistemaAplica']));
    $pdf->Text(12, 126, textoPDF("Medio de contacto preferido: " . $_SESSION['medioContacto']));
    $pdf->Text(12, 130, textoPDF("Celular: " . $_SESSION['celular']));

    $pdf->SetFont('Arial', '', 10);
    $pdf->SetXY(11, 136);
    //$pdf->MultiCell(0,4,textoPDF($_SESSION['desc'],0));
    $pdf->MultiCell(0, 4, $_SESSION['desc'], 0);
    $pdf->Text(12, 236, "Atentamente,");
    $pdf->SetFont('', 'B');
    $pdf->Text(12, 246, textoPDF(($_SESSION['nombre_remitente']) . " " . $_SESSION['apellidos_remitente']));
    $pdf->SetFont('', '');
    $pdf->Text(12, 250, $_SESSION['cedula'] != '0' ? "C.C. " . $_SESSION['cedula'] : "NIT. " . $_SESSION['nit']);
    $pdf->Text(12, 254, textoPDF($_SESSION['direccion_remitente'] . " " . $muniNomb . ", " . $deptNomb . "."));
    $pdf->Text(12, 258, textoPDF($paisNomb));
    $pdf->Text(12, 262, textoPDF("Tel. " . $_SESSION['telefono_remitente'] . "  Cel. " . $_SESSION['celular']));
    $pdf->Text(12, 266, textoPDF($_SESSION['email']));

    //guarda documento en un SERVIDOR
    $pdf->Output("../bodega/$rutaPdf", 'F');

    //Realizar el conteo de hojas del radicado final//
    $conteoPaginas = getNumPagesPdf("../bodega/$rutaPdf");

    $sqlu = "UPDATE radicado SET radi_nume_hoja= $conteoPaginas where radi_nume_radi=" . $_SESSION['radcom'];
    $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
    $db->conn->Execute($sqlu);

    //Envio del correo electronico
    $codTx = 1983;
    include($ruta_raiz . '/include/mail/GENERAL.mailInformar.php');

    $_SESSION["idFormulario"] = "";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--Deshabilitar modo de compatiblidad de Internet Explorer-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?= $entidad_largo ?> - Confirmación de Radicado</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body class="bg-light">

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4 p-md-5">
                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <img src='<?= $log ?>' height='150' class="" alt="Logo">
                        </div>

                        <?php if ($errorFormulario == 0) { ?>
                            <!-- Mensaje de Éxito -->
                            <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-check-circle-fill fs-1 me-3 text-success"></i>
                                    <div>
                                        <h4 class="alert-heading mb-0">¡Solicitud Registrada Exitosamente!</h4>
                                    </div>
                                </div>
                                <hr>
                                <p class="mb-2">Su solicitud ha sido registrada con los siguientes datos:</p>
                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-white rounded">
                                            <small class="text-muted d-block mb-1">Radicado No.</small>
                                            <h5 class="mb-0 text-primary fw-bold"><?= $numeroRadicado ?></h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-white rounded">
                                            <small class="text-muted d-block mb-1">Código de Verificación</small>
                                            <h5 class="mb-0 text-primary fw-bold"><?= $_SESSION['codigoverificacion'] ?></h5>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0"><i class="bi bi-info-circle me-2"></i>Por favor conserve estos datos para consultar el estado de su solicitud.</p>
                            </div>

                            <div class="alert alert-info border-0 mb-4">
                                <i class="bi bi-file-pdf me-2"></i>Puede visualizar, descargar o imprimir el documento en formato PDF haciendo clic en el botón de abajo.
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <button type="button" class="btn btn-primary btn-lg px-5" onclick="window.open('../bodega/<?= $rutaPdf ?>')">
                                    <i class="bi bi-file-earmark-pdf-fill me-2"></i>Ver Documento PDF
                                </button>
                            </div>

                        <?php } else if ($errorFormulario == 1) { ?>
                            <!-- Error de Verificación -->
                            <div class="alert alert-danger border-0 shadow-sm" role="alert">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-exclamation-triangle-fill fs-1 me-3 text-danger"></i>
                                    <div>
                                        <h4 class="alert-heading mb-0">Error en la Verificación</h4>
                                    </div>
                                </div>
                                <hr>
                                <p class="mb-0">Existe un error en su código de verificación o está intentando enviar una petición de nuevo.</p>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="button" class="btn btn-secondary btn-lg" onclick="history.go(-1)">
                                    <i class="bi bi-arrow-left me-2"></i>Volver
                                </button>
                            </div>

                        <?php } else if ($errorFormulario == 2) { ?>
                            <!-- Error de Archivo -->
                            <div class="alert alert-danger border-0 shadow-sm" role="alert">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-exclamation-triangle-fill fs-1 me-3 text-danger"></i>
                                    <div>
                                        <h4 class="alert-heading mb-0">Error en la Carga de Archivos</h4>
                                    </div>
                                </div>
                                <hr>
                                <p class="mb-2">Ocurrió un error al subir los archivos:</p>
                                <div class="bg-light p-3 rounded mt-2">
                                    <small class="text-danger"><?php echo implode($uploader->messages); ?></small>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="button" class="btn btn-secondary btn-lg" onclick="history.go(-1)">
                                    <i class="bi bi-arrow-left me-2"></i>Volver
                                </button>
                            </div>

                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
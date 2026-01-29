<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
ini_set('display_errors', '1');
function generarDocumento($t, $dia, $mes, $anho, $num, $pathAux, $complementDireccion, $tipoDoc) {

    $asunto = '';
    $causal = '';
    if($tipoDoc == 1) {
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Resolucion MC.docx');
        $asunto = 'Por la cual se decretan medidas cautelares';
    } else {
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Resolucion LMC.docx');
        $asunto = 'Por la cual se decreta el levantamiento de medidas cautelares';
    }

    if($t[42] == 1) {
        $causal = 'Que el Banco Agrario de Colombia aportó el título de depósito judicial que garantiza el cumplimiento de la obligación, por lo que no hay lugar a mantener las medidas cautelares tendientes a su cumplimiento.';
    } else {
        $causal = 'Que la obligación base del proceso de cobro, a la fecha no presenta saldo por cobrar en los reportes instituciones, por lo que no hay lugar a mantener las medidas cautelares tendientes a su cumplimiento.';
    }
        
    $templateProcessor->setValue('RA_NOTI_S', $num);
    $templateProcessor->setValue('RA_ASUN', $asunto);
    $templateProcessor->setValue('ENTIDAD_NO', str_replace("&","-",$t[4]));
    $templateProcessor->setValue('NIT_NO', $t[3]);

    $templateProcessor->setValue('OBLIGACION_NO', $t[13] != '' ? $t[13] : '------------');
    $templateProcessor->setValue('OBLIGACION_NO_2', $t[18] != '' ? $t[18] : '------------');
    $templateProcessor->setValue('OBLIGACION_NO_3', $t[23] != '' ? $t[23] : '------------');
    $templateProcessor->setValue('OBLIGACION_NO_4', $t[28] != '' ? $t[28] : '------------');
    $templateProcessor->setValue('OBLIGACION_NO_5', $t[33] != '' ? $t[33] : '------------');


    $templateProcessor->setValue('FECHA_FIRMEZA_NO', $t[14] != '' ?    $t[14] : '------------');
    $templateProcessor->setValue('FECHA_FIRMEZA_NO_2', $t[19] != '' ?  $t[19] : '------------');
    $templateProcessor->setValue('FECHA_FIRMEZA_NO_3', $t[24] != '' ?  $t[24] : '------------');
    $templateProcessor->setValue('FECHA_FIRMEZA_NO_4', $t[29] != '' ?  $t[29] : '------------');
    $templateProcessor->setValue('FECHA_FIRMEZA_NO_5', $t[34] != '' ?  $t[34] : '------------');

    $templateProcessor->setValue('SALDO_CAPITAL',   $t[15] != '' ? '$ ' . number_format($t[15], 0, ',', '.') : '------------');
    $templateProcessor->setValue('SALDO_CAPITAL_2', $t[20] != '' ? '$ ' . number_format($t[20], 0, ',', '.') : '------------');
    $templateProcessor->setValue('SALDO_CAPITAL_3', $t[25] != '' ? '$ ' . number_format($t[25], 0, ',', '.') : '------------');
    $templateProcessor->setValue('SALDO_CAPITAL_4', $t[30] != '' ? '$ ' . number_format($t[30], 0, ',', '.') : '------------');
    $templateProcessor->setValue('SALDO_CAPITAL_5', $t[35] != '' ? '$ ' . number_format($t[35], 0, ',', '.') : '------------');

    $templateProcessor->setValue('VALOR_PREVENTIVA', $t[16] != '' ? '$' . number_format($t[16], 0, ',', '.') : '------------');
    $templateProcessor->setValue('MEDIDA_PREVENTIVA_2', $t[21] != '' ? '$ ' . number_format($t[21], 0, ',', '.') : '------------');
    $templateProcessor->setValue('MEDIDA_PREVENTIVA_3', $t[26] != '' ? '$ ' . number_format($t[26], 0, ',', '.') : '------------');
    $templateProcessor->setValue('MEDIDA_PREVENTIVA_4', $t[31] != '' ? '$ ' . number_format($t[31], 0, ',', '.') : '------------');
    $templateProcessor->setValue('MEDIDA_PREVENTIVA_5', $t[36] != '' ? '$ ' . number_format($t[36], 0, ',', '.') : '------------');

    $templateProcessor->setValue('MULTA', $t[17] != '' ? $t[17] : '------------');
    $templateProcessor->setValue('MULTA_2', $t[22] != '' ? $t[22] : '------------');
    $templateProcessor->setValue('MULTA_3', $t[27] != '' ? $t[27] : '------------');
    $templateProcessor->setValue('MULTA_4', $t[32] != '' ? $t[32] : '------------');
    $templateProcessor->setValue('MULTA_5', $t[37] != '' ? $t[37] : '------------');

    $direccion = $t[5] != '' ? $t[5] : '--';
    $templateProcessor->setValue('DIRECCION_NO', $direccion . ' en ' . $complementDireccion);
    $templateProcessor->setValue('CORREO_NO', $t[6] != '' ? $t[6] : '--');
    $templateProcessor->setValue('NO_DOC', $t[38]);
    $templateProcessor->setValue('DIA_S', $dia);
    $templateProcessor->setValue('MES_S', $mes);
    $templateProcessor->setValue('ANHO_S', $anho);
    $templateProcessor->setValue('EXP_SP', $t[39]);
    $templateProcessor->setValue('RES_EMBARGADORA', $t[40] != '' ?  $t[40] : '');
    $templateProcessor->setValue('FECHA_RES_EMBARGADORA', $t[41] != '' ?  $t[41] : '');
    $templateProcessor->setValue('LOGICA_CAUSAL', $causal);
    $templateProcessor->setValue('LOGICA_CAUSAL_AUX', '');
    $templateProcessor->setValue('USUA_PROYECTO', ' Francisco Alfrod Bojacá – Contratista');
    $templateProcessor->setValue('USUA_APROBO', 'Pedro Luis Rodríguez González – Coordinador Grupo de Cobro Persuasivo y Jurisdicción Coactiva');
    $templateProcessor->setValue('FIRMA', 'Firmado electrónicamente por: Pedro Luis Rodríguez González');

    $templateProcessor->saveAs($pathAux);
    unset($templateProcessor); 
}


$ruta_raiz = "..";
session_start();
require_once($ruta_raiz."/include/db/ConnectionHandler.php");
require_once($ruta_raiz."/processConfig.php");
require_once($ruta_raiz."/include/tx/Radicacion.php");
require_once($ruta_raiz."/include/tx/Historico.php");
require_once($ruta_raiz."/include/tx/usuario.php");
require_once($ruta_raiz."/include/tx/notificacion.php");
require_once($ruta_raiz."/vendor/autoload.php");
require_once($ruta_raiz."/vendor/tmw/fpdm/fpdm.php");
require_once($ruta_raiz."/include/tx/TipoDocumental.php");  
require_once($ruta_raiz."/include/tx/Tx.php"); 
require_once($ruta_raiz."/include/tx/Expediente.php");

if (!$_SESSION['dependencia'])
    header ("Location: $ruta_raiz/cerrar_session.php");

if ($_SESSION["krd"])
    $krd = $_SESSION["krd"];
else
    $krd = "";

$depeRadica   = $_POST['depeRadica'];
$usuRadica   = $_POST['usuRadica'];
$usua_doc_radica   = $_POST['usua_doc_radica']; 
$depeEnvio   = $_POST['depeEnvio'];
$usuEnvio   = $_POST['usuEnvio']; 
$tipoDoc = $_POST['tipoDoc']; 

$krdCreador = 'FRANCISCO.ALFORD';
//$krdCreador = 'IVAN.GUEVARA';

/*$depeRadica   = 900;
$usuRadica    = 1;
$usua_doc_radica = 10153900001;
$depeEnvio    = 900;
$usuEnvio     = 1;
$krdCreador = 'IVAN.GUEVARA'*/


$file = $ABSOL_PATH. 'bodega/tmp/cobroMasiva/logTASAV2.txt';
$file_memory = $ABSOL_PATH. 'bodega/tmp/cobroMasiva/logTASAMEMORYV2.txt';
if(!is_file($file)){    
    $myfile = fopen($file, "w");
    fclose($myfile);
    unset($myfile);
}

if(!is_file($file_memory)){    
    $myfile = fopen($file_memory, "w");
    fclose($myfile);
    unset($myfile);
}

$db = new ConnectionHandler($ruta_raiz);
$fecha = explode(" ", date("d F Y")); 
$_mes = array(
    "January"   => "Enero",
    "February"  => "Febrero",
    "March"     => "Marzo",
    "April"     => "Abril",
    "May"       => "Mayo",
    "June"      => "Junio",
    "July"      => "Julio",
    "August"    => "Agosto",
    "September" => "Septiembre",
    "October"   => "Octubre",
    "November"  => "Noviembre",
    "December"  => "Diciembre"
);  
$dia = $fecha[0];
$mes = $_mes[$fecha[1]];
$anho = $fecha[2];

$asunto = '';
if($tipoDoc == 1) {
    $asunto = 'Por la cual se decretan medidas cautelares';
} else {
    $asunto = 'Por la cual se decreta el levantamiento de medidas cautelares';
}


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

$rutaExcel = $ABSOL_PATH. 'bodega/tmp/cobroMasiva/Tasa.xlsx';
$spreadsheet = $reader->load($rutaExcel);
$sheetData = $spreadsheet->getActiveSheet()->toArray();
$i=1;
unset($sheetData[0]);
$data_from_db=array();
$data_from_db[0]=array();  
$retorno = "";
$contadorGeneral = 0;
foreach ($sheetData as $t) {
    $contadorGeneral++;
    if($t[0] != '') {
        $data_from_db[$i]=array(); 
    } else {
    
       $rad = new Radicacion($db);
       $rad->radiUsuaActu = $usuRadica;
       $rad->radiDepeActu = $depeRadica;
       $rad->dependencia = $depeRadica;
       $rad->dependenciaRadicacion = $depeRadica;
       $rad->radiTipoDeri = 0;
       $rad->radiCuentai  = "";
       $rad->radiFechOfic = $db->sysdate();
       $rad->descAnex     = "";
       $rad->radiDepeRadi = $depeRadica;
       $rad->nofolios      = 0;
       $rad->noanexos      = 0;
       $rad->sgdSpubCodigo = 0;
       $rad->carpCodi      = 6;
       //$rad->raAsun        = "Por el cual se Libra Mandamiento de Pago a la Vigencia " . $t[2] . " a la entidad " . $t[4] . " con " . $t[38] . " N° " . $t[3];
       $rad->raAsun        =  $asunto;
       $rad->guia = "";
       $rad->radi_dato_001 = "";
       $rad->radi_dato_002 = "";
       $rad->esta_fisico = 1;
       $rad->tdocCodi = 484;
       $nurad = $rad->newRadicado(6, null);

       if ($nurad=="-1"){
           $retorno = "Error al generar número radicado";
           //echo "ocurrio un error al generar el número";                    
           $out = "Error al generar número radicado";
           $data_from_db[$i]=array("N° Radicado"=> "-1","Error"=>$out);
           error_log(date(DATE_ATOM)." ".basename(__FILE__)." $out\n ", 3 , $file);
           break;                
       } else {

             
            $radicadosSel[0] = $nurad;
            $hist      = new Historico($db);
            $hist->insertarHistorico( $radicadosSel,
                $depeRadica,
                $usuRadica,
                $depeRadica,
                $usuRadica,
                "Se generó radicado de forma masiva",
                2);  

            $divipola = $t[8] . "";

            if(strlen($divipola) == 4){
                $dept = substr($divipola, 0, -3); 
                $ciudad = substr($divipola, 1, 3); 
                $dept = intval($dept);
                $ciudad = intval($ciudad);
            } else {
                $dept = substr($divipola, 0, -3); 
                $ciudad = substr($divipola, 2, 3); 
                $dept = intval($dept);
                $ciudad = intval($ciudad);  
            }  
            
            $sqlDireccion = 'select mu.muni_nomb, de.dpto_nomb
                                from public.municipio mu 
                                join public.departamento de on de.dpto_codi = mu.dpto_codi	
                                where mu.muni_codi = ' . $ciudad . ' and de.dpto_codi = ' . $dept;

            $rs = $db->query($sqlDireccion);  
            $complementDireccion = '';
            while($rs && !$rs->EOF){
                $complementDireccion = $rs->fields["MUNI_NOMB"] . ' - ' . $rs->fields["DPTO_NOMB"];
                break;
            }                                 

            $usuarioArray = array(
                        "cedula"         => $t[3] . "",
                        "nombre"         => $t[4],
                        "apellido"       => "",
                        "dignatario"     => "",
                        "telef"          => $t[7] != '' ?  $t[7] : '',
                        "direccion"      => $t[5] . "",
                        "email"          => $t[6] . "",
                        "muni"           => "",
                        "muni_tmp"       => $ciudad,
                        "dep"            => "",
                        "dpto_tmp"       => $dept,
                        "pais"           => "",
                        "pais_tmp"       => 170,
                        "cont_tmp"       => 1,
                        "tdid_codi"      => 4,
                        "sgdTrd"         => 2,
                        "id_sgd_dir_dre" => "XX0",
                        "id_table"       => "XX",
                        "sgdDirTipo"     => 1,
                        "medio_envio"    => 2,
                        "dir_tdoc"       => 4
                    );    
            $usuario = new Usuario($db);
            $borrable = true;
            $respons = $usuario->guardarUsuarioRadicado($usuarioArray, $nurad, $borrable);
            if($respons!=1){
                $retorno = "Error al guardar destinatario: " . $nurad;
                //echo "Ocurrio un error en guardando usuario <br>";
                $out = "Error al guardar destinatario: " . $nurad;
                $data_from_db[$i]=array("N° Radicado"=> "-" . $nurad,"Error"=>$out);
                error_log(date(DATE_ATOM)." ".basename(__FILE__)." $out\n ", 3 , $file);
                break;                    
            }

            $notificacion = new Notificacion($db);

            $infoNotificacion['notifica_codi']  = '';
            $infoNotificacion['med_public']     = 1;
            $infoNotificacion['caracter_adtvo'] = 1;
            $infoNotificacion['siad']           = '';
            $infoNotificacion['prioridad']      = false;
            $infoNotificacion['radicado'] = $nurad;

            $respuestaNotificacion = $notificacion->creaEditaNotificacion($infoNotificacion, false);
            if (!$respuestaNotificacion['status']) {
                $retorno = "Error al guardar infor adicional de notificacion: " . $nurad;
                //echo "Ocurrio un error en editarNotificacion <br>";
                $out = "Error al guardar infor adicional de notificacion: " . $nurad;
                $data_from_db[$i]=array("N° Radicado"=> "-" . $nurad,"Error"=>$out);
                error_log(date(DATE_ATOM)." ".basename(__FILE__)." $out\n ", 3 , $file); 
                break;                      
            } 

            $sgd_dir_drecciones_id = $usuario->result["value"];
            $dupla = array("sgd_dir_codigo" => $sgd_dir_drecciones_id, "orden_codi" => 2);
            $rtaOrdenNotificacion = $notificacion->creaEditaOrdenesNotificacion($dupla, false);
            if(!$rtaOrdenNotificacion['status']){
                $retorno = "Error al guardar orden_acto: " . $nurad;
                //echo "Ocurrio un error en orden_acto";
                $out = "Error al guardar orden_acto: " . $nurad;
                $data_from_db[$i]=array("N° Radicado"=> "-" . $nurad,"Error"=>$out);
                error_log(date(DATE_ATOM)." ".basename(__FILE__)." $out\n ", 3 , $file);
                break;                               
            } 

            $anexo = $nurad . "00001";
            $documento = $anexo . ".pdf";
            $path = "/" . $anho . "/". $depeRadica . "/docs/" . $documento;
            $pathAux = $ABSOL_PATH . "bodega/" . $anho . "/". $depeRadica . "/docs/" . $anexo . ".docx";
            $pathFolderAux = $ABSOL_PATH . "bodega/" . $anho . "/". $depeRadica . "/docs/";
            //$pathAux = $ABSOL_PATH . "bodega/tmp/workDir/" . $anexo . ".docx";
            //$pathFolderAux = $ABSOL_PATH . "bodega/tmp/workDir/";


            $sqlInsertRadicado = "INSERT into anexos (sgd_rem_destino, anex_radi_nume, anex_codigo, anex_tipo, anex_tamano, anex_solo_lect, anex_creador, anex_desc, anex_numero, anex_nomb_archivo, anex_borrado, anex_salida, sgd_dir_tipo, anex_depe_creador, sgd_tpr_codigo, anex_fech_anex, sgd_apli_codi, sgd_trad_codigo, sgd_exp_numero, anex_tipo_final, sgd_dir_mail, anex_tipo_envio, anex_adjuntos_rr, idPlantilla, radi_nume_salida, anex_estado) VALUES (1, " . $nurad . ", '" . $anexo . "', 7, 1608, 'N', '" . $krdCreador ."', 'Pdf Respuesta', 1, '" . $documento ."', 'N', 1, 1, " . $depeRadica .", 0, now(), 0, 6, '', 2, 'johans-123@hotmail.com;', 0, '', '100000', " . $nurad . ",'2') ";

            $db->conn->Execute($sqlInsertRadicado);

            $sqlEditarRadicado = "UPDATE radicado SET radi_path = '" . $path ."' WHERE radi_nume_radi = " . $nurad;
            $db->conn->Execute($sqlEditarRadicado);

            $usua_doc = $_SESSION["usua_doc"];

            /*
                Se agrega TRD Automático
            */

            $record = array(); 
            $record["RADI_NUME_RADI"] = $nurad;
            $record["DEPE_CODI"]      = $depeRadica;
            $record["USUA_CODI"]      = $usuRadica;
            $record["USUA_DOC"]       = $usua_doc_radica;    
            $record["SGD_RDF_FECH"]   = $db->conn->OffsetDate(0,$db->conn->sysTimeStamp);
            $record["SGD_MRD_CODIGO"] = 7845;

            $nombTrd = "Resolución";
            $sgdTprCodigo = 484;
            
            $insertSQL = $db->insert("SGD_RDF_RETDOCF", $record, "true");

             $hist->insertarHistorico($radicadosSel,
                $depeRadica ,
                $usuRadica,
                $depeRadica,
                $usuRadica,
                "Se agregó TRD Automático: " . $nombTrd,
                32);   
            
            $trd = new TipoDocumental($db);
            $trd->setFechVenci($nurad,$sgdTprCodigo);

            /*
                *Se agrega histórico de imagen asociada 
            */

            $hist->insertarHistorico($radicadosSel,
                $depeRadica,
                $usuRadica,
                $depeRadica,
                $usuRadica,
                "Imagen asociada masiva OTI/TASA " . $nurad,
                42);            

            $Tx = new Tx($db);
            $usCodDestino = $Tx ->reasignar( $radicadosSel, $krdCreador, $depeEnvio, $depeRadica, $usuEnvio, 
                $usuRadica, "si", "Para firmar y dar trámite", 9, 0); 
            

            /*
                *Se agrega histórico de firma Digital y queda por la doctora CLAUDA secretaria actual
            */  

            $hist->insertarHistorico($radicadosSel,
                $depeEnvio,
                $usuEnvio,
                $depeEnvio,
                $usuEnvio,
                "Firmadada digitalmente la respuesta en PDF No " . $nurad, 40); 

                generarDocumento($t, $dia, $mes, $anho, $nurad,  $pathAux, $complementDireccion, $tipoDoc);
                
                $firmasd = $ABSOL_PATH . 'bodega/firmas/';
                $P12_FILE =  $firmasd . 'server.p12';

                if (!file_exists($P12_FILE)) {
                    $P12_FILE = $firmasd . $usua_doc . '.p12';
                }

                if ($P12_PASS) {
                    $clave = $P12_PASS;
                }   
                

                $tmp_sf = '/tmp/'.microtime(true);
                $commandToPDF="soffice --headless -env:UserInstallation=file://$tmp_sf --convert-to pdf ".$pathAux . ' --outdir ' . $pathFolderAux;
                exec($commandToPDF,$outToPDF,$stateToPDF);
                exec("rm -rf $tmp_sf");
                exec("rm -rf $pathAux");

                if ($stateToPDF!=0){
                
                    $out = "Error al pasar de word a pdf: " . $nurad;
                    $data_from_db[$i]=array("N° Radicado"=> "-" . $nurad,"Error"=>$out);
                    error_log(date(DATE_ATOM)." ".basename(__FILE__)." $out\n ", 3 , $file);  
                    break;                    
                }          

                $commandFirmado='java -jar '.$ABSOL_PATH.'/include/jsignpdf/JSignPdf.jar ' . str_replace('docx','pdf',$pathAux) . ' -kst PKCS12 -ksf ' . $P12_FILE . ' -ksp ' 
                . $clave . ' --font-size 7 -r \'Firmado al Radicar en SuperArgo\' -V -llx 0 -lly 0 -urx 550 -ury 27';

                if ($tsUrlTimeStamp) {
                    $commandFirmadoTS = "$commandFirmado -ta PASSWORD -ts $tsUrlTimeStamp -tsu $tsuUserTimeStamp -tsp $tspPasswordTimeStamp -d $pathFolderAux 2>&1";
                }
                $commandFirmado .= " -d $pathFolderAux 2>&1";
                
                $out = null;
                $ret = null;
                $cmd = $commandFirmadoTS ?? $commandFirmado;
                $inf = exec($cmd,$out,$ret);

                // si falla la ejecución de jsign guardar error en bodega/jsignpdf.log
                if ($ret != 0) {
                    $out = implode(PHP_EOL, $out);
                    error_log(date(DATE_ATOM)." ".basename(__FILE__)." ($ret) $nurad: $out\n",3,"$ABSOL_PATH/bodega/jsignpdf.log");

                    // si falla con estampa usar solo firma
                    if (isset($commandFirmadoTS)) {
                        $out = null;
                        $ret = null;
                        $inf = exec($commandFirmado,$out,$ret);
                        if ($ret != 0) {
                            $out = implode(PHP_EOL, $out);
                            error_log(date(DATE_ATOM)." ".basename(__FILE__)." ($ret) $nurad: $out\n",3,"$ABSOL_PATH/bodega/jsignpdf.log");
                            $retorno = "Error firmando el documento: " . $nurad;
                            $out = "Error firmando el documento: " . $nurad;
                            $data_from_db[$i]=array("N° Radicado"=> "-" . $nurad,"Error"=>$out);
                            break;
                        }
                    }
                    else {
                        $retorno = "Error firmando el documento: " . $nurad;
                        $out = "Error firmando el documento: " . $nurad;
                        $data_from_db[$i]=array("N° Radicado"=> "-" . $nurad,"Error"=>$out);
                        break;
                    }
                }

                $pathAuxPdf = $ABSOL_PATH . "bodega/" . $anho . "/". $depeRadica . "/docs/" . $anexo . ".pdf";
                $pathAuxPdfSigned = $ABSOL_PATH . "bodega/" . $anho . "/". $depeRadica . "/docs/" . $anexo . "_signed.pdf";
                exec("rm -rf $pathAuxPdf");
                //$pathAux = $ABSOL_PATH . "bodega/" . $anho . "/". $depeRadica . "/docs/" . $anexo . ".docx";
                //$pathFolderAux = $ABSOL_PATH . "bodega/" . $anho . "/". $depeRadica . "/docs/";

                rename($pathAuxPdfSigned, $pathAuxPdf);                
            
            /*
            Logica para crear expediente y asociarlo al radicado
            ----------------------------------------------
                *Se agrega a expedientes
            ----------------------------------------------    
            */
            /*$expediente = new Expediente($db);              

            //$codepe = 92005;
            $sgdSrdCodigo = 39;
            $sgdSbrdCodigo = 1;
            $anoExp = date("Y");
            $secExp = $expediente->secExpediente($depeRadica,$sgdSrdCodigo,$sgdSbrdCodigo,$anoExp);

            $trdExp = substr("00".$sgdSrdCodigo,-2) . substr("00".$sgdSbrdCodigo,-2);
            $consecutivoExp = substr("00000".$secExp,-5);
            $numeroExpediente = $anoExp . $depeRadica . $trdExp . $consecutivoExp . 'E';

            //$sexpParexp1 = "COBRO_COACTIVO_" . $t[2] . "_" . $t[3] . "_" . $t[12];
            $sexpParexp1 = "COBRO_COACTIVO_2022_" . $t[3] . "_" . $t[12];
            $sexpParexp2 = $t[3] . "";
            $sexpParexp4 = "SOLICITUD DE LIQUIDACION ADICIONAL DE COBRO" . $t[2] . " " . $t[3] . " " . $t[4] . " " . $t[12];                                   

            $sqlInsertExpediente = "INSERT INTO sgd_sexp_secexpedientes(
                sgd_exp_numero, sgd_srd_codigo, sgd_sbrd_codigo, sgd_sexp_secuencia, depe_codi, usua_doc, sgd_sexp_fech, 
                sgd_fexp_codigo, sgd_sexp_ano, usua_doc_responsable, 
                sgd_sexp_parexp1, sgd_sexp_parexp2, 
                sgd_sexp_parexp3, 
                sgd_sexp_parexp4, sgd_sexp_parexp5, 
                sgd_pexp_codigo, sgd_exp_privado, sgd_sexp_prestamo, sgd_srd_id, sgd_sbrd_id)
                VALUES ('$numeroExpediente', $sgdSrdCodigo, $sgdSbrdCodigo, 0, $depeRadica, '" . $usua_doc_radica . "', CURRENT_TIMESTAMP
                            ,1, $anoExp, '80099109', 
                        '$sexpParexp1',  '$sexpParexp2',  
                        '',
                        '$sexpParexp4', '92006-Germán Darío Pava Cortés', 0, 0, 0, $sgdSrdCodigo, $sgdSbrdCodigo)";

            $db->conn->Execute($sqlInsertExpediente);   

            $fecha_hoy = Date("Y-m-d");
            $sqlFechaHoy=$db->conn->DBDate($fecha_hoy); 

            $asociarExpediente="insert into SGD_EXP_EXPEDIENTE(SGD_EXP_NUMERO   , RADI_NUME_RADI,SGD_EXP_FECH,DEPE_CODI   ,USUA_CODI   ,USUA_DOC ,SGD_EXP_ESTADO, SGD_FEXP_CODIGO )
                VALUES ('$numeroExpediente',$nurad,".$sqlFechaHoy.",
                    $depeRadica ,$usuRadica ,'" . $usua_doc_radica . "',0, 0)";
            $db->conn->Execute($asociarExpediente);             

            $historialExpediente="INSERT INTO sgd_hfld_histflujodoc(
                    sgd_fexp_codigo, sgd_exp_fechflujoant, sgd_hfld_fech, sgd_exp_numero, radi_nume_radi,
                    usua_doc, usua_codi, depe_codi, sgd_ttr_codigo, sgd_fexp_observa, sgd_hfld_observa, 
                    sgd_fars_codigo, sgd_hfld_automatico) values
                    (0,null, CURRENT_TIMESTAMP,'$numeroExpediente' ,$nurad, '" . $usua_doc_radica . "',$usuRadica,$depeRadica, 50, 
                    null,'Creacion Expediente', null,null)";
            $db->conn->Execute($historialExpediente);

            $historialExpediente="INSERT INTO sgd_hfld_histflujodoc(
                    sgd_fexp_codigo, sgd_exp_fechflujoant, sgd_hfld_fech, sgd_exp_numero, radi_nume_radi,
                    usua_doc, usua_codi, depe_codi, sgd_ttr_codigo, sgd_fexp_observa, sgd_hfld_observa, 
                    sgd_fars_codigo, sgd_hfld_automatico) values
                    (0,null, CURRENT_TIMESTAMP,'$numeroExpediente' ,$nurad, '" . $usua_doc_radica . "',$usuRadica,$depeRadica, 53, 
                        null,'Incluir radicado en Expediente', null,null)";
            $db->conn->Execute($historialExpediente);    
            */
  
                    
            $Tx = new Tx($db);
            $usCodDestino = $Tx ->reasignar( $radicadosSel, $krdCreador, $depeRadica, $depeEnvio, $usuRadica, 
                            $usuEnvio, "si", "Para contiunar con el trámite", 9, 0);                         

            /*
            Logica para asociar expediente que viene en el archivo de carga
            ----------------------------------------------
                *Se agrega a expedientes
            ----------------------------------------------    
            */            
            $errorExp = '';
            $sqlValidarExpediente = "SELECT count(*) FROM public.sgd_sexp_secexpedientes WHERE sgd_exp_numero =  '$t[39]'";
            $rsValidaExp = $db->conn->query($sqlValidarExpediente);
            $existeExp = $rsValidaExp->fields["COUNT"];
            
            //Se valida si el expediente existe
            if($existeExp > 0) {

                $sqlExpedienteEstado = "SELECT sgd_sexp_estado FROM public.sgd_sexp_secexpedientes  where sgd_exp_numero = '$t[39]'";
                $rsSqlExpedienteEstado        = $db->conn->Execute($sqlExpedienteEstado);
                $estadoExp      = $rsSqlExpedienteEstado->fields["SGD_SEXP_ESTADO"];        
                
                //Se valida que el expediente este abierto
                if($estadoExp == 0) {

                    $fecha_hoy = Date("Y-m-d");
                    $sqlFechaHoy=$db->conn->DBDate($fecha_hoy);
    
                    $asociarExpediente="insert into SGD_EXP_EXPEDIENTE(SGD_EXP_NUMERO   , RADI_NUME_RADI,SGD_EXP_FECH,DEPE_CODI   ,USUA_CODI   ,USUA_DOC ,SGD_EXP_ESTADO, SGD_FEXP_CODIGO )
                    VALUES ('$t[39]',$nurad,".$sqlFechaHoy.",
                        $depeRadica ,$usuRadica ,'" . $usua_doc_radica . "',0, 0)";
                    $db->conn->Execute($asociarExpediente); 
                    
                    $historialExpediente="INSERT INTO sgd_hfld_histflujodoc(
                        sgd_fexp_codigo, sgd_exp_fechflujoant, sgd_hfld_fech, sgd_exp_numero, radi_nume_radi,
                        usua_doc, usua_codi, depe_codi, sgd_ttr_codigo, sgd_fexp_observa, sgd_hfld_observa, 
                        sgd_fars_codigo, sgd_hfld_automatico) values
                        (0,null, CURRENT_TIMESTAMP,'$t[39]' ,$nurad, '" . $usua_doc_radica . "',$usuRadica,$depeRadica, 53, 
                            null,'Incluir radicado en Expediente', null,null)";
                     $db->conn->Execute($historialExpediente);                  
          
                        
                    $retorno = "Creando con exito: " . $nurad;                       

                } else {
                    $errorExp = 'Creando con exito pero el expediente esta cerrado o anulado';
                    $retorno = "Creando con exito pero el expediente esta cerrado o anulado" . $nurad;
                }

            } else {
                $errorExp = 'Creando con exito pero no existe el expediente';
                $retorno = "Creando con exito pero no existe el expediente" . $nurad;
            }
            
            $data_from_db[$i]=array("N° Radicado"=> "-" . $nurad . "-","Error"=> $errorExp);
            break;              
            
       }

       break;
    }
    $i++;
}

$sheet = $spreadsheet->getActiveSheet();
for($i=0;$i<count($data_from_db);$i++)
{

//set value for indi cell
$row=$data_from_db[$i];

//writing cell index start at 1 not 0
$j=1;

    foreach($row as $x => $x_value) {
        $sheet->setCellValueByColumnAndRow($j,$i+1,$x_value);
        $j=$j+1;
    }

}
$writer = new Xlsx($spreadsheet); 
  
// Save .xlsx file to the files directory 
$writer->save($rutaExcel); 

$contadorSheetData = count($sheetData);

unset($writer);
unset($db);
unset($reader);
unset($rad);
unset($trd);
unset($hist);
unset($usuario);
unset($spreadsheet);
unset($sheet);
unset($sheetData);

error_log(date(DATE_ATOM). " ". memory_get_usage() . " \n", 3 , $file_memory);

if($contadorGeneral == $contadorSheetData) {
    echo $retorno . " *FIN*";
} else {
    echo $retorno . " " . $contadorGeneral . " " . $contadorSheetData;
}
?>

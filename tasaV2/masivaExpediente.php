<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
ini_set('display_errors', '1');

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

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

//$rutaExcel = $ABSOL_PATH. 'bodega/tmp/cobroMasiva/TASA_EXPEDIENTES.xlsx';
$rutaExcel = 'TASA_EXPEDIENTES.xlsx';

$depeRadica = 92005;
$usuRadica   = 2;
$usua_doc_radica = 284729472;
$usua_doc_responsable = 284729472;


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
        $expediente = new Expediente($db);              

        $sgdSrdCodigo = 19;
        $sgdSbrdCodigo = 1;
        $anoExp = date("Y");
        $secExp = $expediente->secExpediente($depeRadica,$sgdSrdCodigo,$sgdSbrdCodigo,$anoExp);

        $trdExp = substr("00".$sgdSrdCodigo,-2) . substr("00".$sgdSbrdCodigo,-2);
        $consecutivoExp = substr("00000".$secExp,-5);
        $numeroExpediente = $anoExp . $depeRadica . $trdExp . $consecutivoExp . 'E';

        $sexpParexp1 =  $t[11] . "";
        $sexpParexp2 = $t[3] . "";
        $sexpParexp4 = $t[14] . "";     
        $sexpParexp3 = $t[13] . "";                              

        $sqlInsertExpediente = "INSERT INTO sgd_sexp_secexpedientes(
            sgd_exp_numero, sgd_srd_codigo, sgd_sbrd_codigo, sgd_sexp_secuencia, depe_codi, usua_doc, sgd_sexp_fech, 
            sgd_fexp_codigo, sgd_sexp_ano, usua_doc_responsable, 
            sgd_sexp_parexp1, sgd_sexp_parexp2, 
            sgd_sexp_parexp3, 
            sgd_sexp_parexp4, sgd_sexp_parexp5, 
            sgd_pexp_codigo, sgd_exp_privado, sgd_sexp_prestamo, sgd_srd_id, sgd_sbrd_id)
            VALUES ('$numeroExpediente', $sgdSrdCodigo, $sgdSbrdCodigo, 0, $depeRadica, '" . $usua_doc_radica . "', CURRENT_TIMESTAMP
                        ,1, $anoExp, '$usua_doc_responsable', 
                    '$sexpParexp1',  '$sexpParexp2',  
                    '$sexpParexp3',
                    '$sexpParexp4', '" . $t[15] . "', 0, 1, 0, $sgdSrdCodigo, $sgdSbrdCodigo)";

        $db->conn->Execute($sqlInsertExpediente);   

        $fecha_hoy = Date("Y-m-d");
        $sqlFechaHoy=$db->conn->DBDate($fecha_hoy); 

        $historialExpediente="INSERT INTO sgd_hfld_histflujodoc(
            sgd_fexp_codigo, sgd_exp_fechflujoant, sgd_hfld_fech, sgd_exp_numero,
            usua_doc, usua_codi, depe_codi, sgd_ttr_codigo, sgd_fexp_observa, sgd_hfld_observa, 
            sgd_fars_codigo, sgd_hfld_automatico,radi_nume_radi) values
            (0,null, CURRENT_TIMESTAMP,'$numeroExpediente', '" . $usua_doc_radica . "',$usuRadica,$depeRadica, 50, 
            null,'Creacion Expediente', null,null,0)";
        $db->conn->Execute($historialExpediente);       

        $retorno = "Creando con exito: " . $numeroExpediente;
        $data_from_db[$i]=array("EXPEDIENTE"=> "" . $numeroExpediente,"Error"=>"");
        $retorno = "Creando con exito: " . $i;
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
if($contadorGeneral == count($sheetData)) {
    echo $retorno . " *FIN*";
} else {
    echo $retorno . " " . $contadorGeneral . " " . count($sheetData);
}

?>
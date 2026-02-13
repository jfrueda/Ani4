<?php

namespace App\Radicador;


$ruta_raiz = __DIR__ . '/../../../';

require_once($ruta_raiz.'include/db/ConnectionHandler.php');
include_once $ruta_raiz.'conversion/roles.php';
include_once $ruta_raiz.'include/tx/AnexoExpediente.php';
include_once $ruta_raiz.'include/tx/TipoDocumental.php';
include_once $ruta_raiz.'include/tx/Radicacion.php';
include_once $ruta_raiz.'include/tx/Metadatos.php';
include_once $ruta_raiz.'class_control/Municipio.php';
include_once $ruta_raiz.'conversion/Conversion.php';
include_once($ruta_raiz.'include/tx/usuarioTutelas.php');
require_once($ruta_raiz."/include/tx/Historico.php");

//mb_internal_encoding('UTF-8');
//mb_http_output('UTF-8');

use App\Lib\ADOdb;
use App\Lib\ManagementError;
use App\Lib\Auth;
use App\Lib\Generica;
use App\anexos\Anexos;
use App\anexos\ImgPrin;
use App\Radicador\DirDirecciones;
use App\historico\Historico;
use App\Radicador\LogRadFromMail;

class registro
{
    protected $anexosb64;
    protected $cuentaOrigen;
    protected $mime;
    protected $nombjuzgado;
    protected $departamento;
    protected $municipio;
    protected $usuaLogin;
    protected $usuaDoc;
    protected $bdRorf;

    public function __construct(){
        $this->bdRorf = new  \ConnectionHandler(__DIR__. '/../../../');
    } 

    public function getInfoUser($depe_code_fin,$user_cod){
        $sql = "SELECT USUA_LOGIN,USUA_DOC,DEPE_CODI,CODI_NIVEL,USUA_CODI,USUA_NOMB FROM USUARIO WHERE usua_codi = {$user_cod} AND depe_codi = {$depe_code_fin}";
        $rs = $this->bdRorf->conn->Execute($sql);

        if($rs->fields['USUA_LOGIN'] || $rs->fields['USUA_DOC']){

            $this->usuaLogin = $rs->fields['USUA_LOGIN'];
            $this->usuaDoc = $rs->fields['USUA_DOC'];
        }
        else{
            $msm = 'Usuario Inesistente';
            return;
        }
    }

    public function getDivipola($departamento = null, $municipio = null){

        $isqlDepSns = "SELECT * FROM departamentos_sns d WHERE nombre_entrante = '{$departamento}'";
        $rsDepSns = $this->bdRorf->conn->Execute($isqlDepSns);
        $departamento = isset($rsDepSns->fields['NOMBRE_ESTANDAR']) ? $rsDepSns->fields['NOMBRE_ESTANDAR'] : $departamento;

        $isqlMunSns = "SELECT * FROM municipios_sns m WHERE nombre_entrante = '{$municipio}'";
        $rsMunSns = $this->bdRorf->conn->Execute($isqlMunSns);
        $municipio = isset($rsMunSns->fields['NOMBRE_ESTANDAR']) ? $rsMunSns->fields['NOMBRE_ESTANDAR'] : $municipio;


        switch($departamento){
            case 'BOGOTÁ, D.C.':
                $isqlDep = "SELECT * FROM departamento d WHERE dpto_nomb ILIKE 'BOGOTÁ%'";
                $rsDep = $this->bdRorf->conn->Execute($isqlDep);
                $isqlMun = "SELECT * FROM municipio m INNER JOIN departamento d ON m.dpto_codi = d.dpto_codi WHERE d.dpto_codi = {$rsDep->fields['DPTO_CODI']} and m.muni_nomb  ILIKE 'BOGOTÁ%'";
                $rsMun = $this->bdRorf->conn->Execute($isqlMun);
            break;
            default:
                $isqlDep = "SELECT * FROM departamento d WHERE dpto_nomb = upper('{$departamento}')";
                $rsDep = $this->bdRorf->conn->Execute($isqlDep);
                $isqlMun = "SELECT * FROM municipio m INNER JOIN departamento d ON m.dpto_codi = d.dpto_codi WHERE d.dpto_codi = {$rsDep->fields['DPTO_CODI']} and m.muni_nomb = upper('{$municipio}')";
                $rsMun = $this->bdRorf->conn->Execute($isqlMun);
            break;
        }

        return ["codDep" => $rsDep->fields['DPTO_CODI'], "nombDep" => $rsDep->fields['DPTO_NOMB'], "codMun" => $rsMun->fields['MUNI_CODI'], "nombMun" => $rsMun->fields['MUNI_NOMB']];
    }

    public function webservice($req, $res)
    {
        $db = new ADOdb();
        $verificacion = new Generica($db);
        //$postData = json_decode(file_get_contents('php://input'),true);
        //$req = $_POST;
        $req = json_decode($req->reqMethod, true);

        // echo json_encode(['llega'=>'radicar']);return;

        $tipoRadicado        = $req['tipoRadicado'];
        $codiDepe            = $req['codiDepe'];
        $depeActual          = $req['depeActual'];
        $codiUsuario         = $req['codiUsuario'];
        $codiUsuarioDestino  = $req['codiUsuarioDestino'];
        $fechaOficio         = $req['fechaOficio'];
        $tipoDocumento       = $req['tipoDocumento'];
        $radiAsociado        = $req['radiAsociado'];
        $descAnexo           = $req['descAnexo'];
        $asunto              = $req['asunto'];
        $nroOficio           = $req['nroOficio'];
        $this->anexosb64     = $req['anexos'];
        $remitente           = $req['remitente'];
        $this->cuentaOrigen  = $req['cuentaorigen'];
        $this->mime          = $req['mime'];
        $this->nombjuzgado   = $req['nombjuzgado'];
        $this->departamento   = $req['departamento'];
        $this->municipio   = $req['municipio'];
        $this->telefono   =  (!$req['telefono'] ? 'N/A':$req['telefono']);
        $this->direccion   = (!$req['direccion'] ? 'NO REGISTRA':$req['direccion']);

        $result =  $this->setRadicado($this->bdRorf,$tipoRadicado,$codiDepe,$depeActual,$codiUsuario,$codiUsuarioDestino,$fechaOficio,$tipoDocumento,
            $radiAsociado,$descAnexo,$asunto,$nroOficio,$remitente,$infoUsr['usua_doc'],$infoUsr['usua_nomb'],$infoUsr['usua_login']
        );

        echo $result;
    }
    private function setRadicado($db,$rad_type,$depe_code,$depe_code_fin,$user_cod,$user_cod_fin,$rad_date,$tipoDocumento,$rad_associated = '',$rad_des_anex,
        $rad_subject,$rad_number_oficio,$remitente,$usua_doc,$usua_nomb,$usua_ante){

        $getMsm = $this->getInfoUser($depe_code_fin,$user_cod_fin);
        $divipolaRef = $this->getDivipola($this->departamento,$this->municipio);

        //die();echo json_encode(array("DepartamentoCodi"=>$depCodi, "MuncipioCodi"=>$muniCodi));die();

        if(!$divipolaRef['codDep'] || !$divipolaRef['codMun']){
            $divipolaRef['codDep'] = 0;
            $divipolaRef['codMun'] = 0;
        }

        $coding = mb_detect_encoding($rad_subject, "UTF-8, ISO-8859-1, EUC-JP, ASCII,JIS");
        $asunto = iconv($coding, 'UTF-8', $rad_subject);
        //echo json_encode(array('Encoding'=>$asunto));
        $rad = new \Radicacion($this->bdRorf);
        $rad->radiUsuaActu = $user_cod_fin;
        $rad->radiDepeActu = $depe_code_fin;
        $rad->dependencia = 16102;
        $rad->dependenciaRadicacion = 16102;
        $rad->radiTipoDeri = 0;
        $rad->radiCuentai  = "";
        $rad->noDigitosRad = 7;
        $rad->noDigitosDep = 5;
        $rad->usuaLogin = "'{$this->usuaLogin}'";
        $rad->usuaDoc = $this->usuaDoc;
        //$rad->radiFechOfic = "2024-09-25 10:51:50.953";
        $rad->usuaCodi = 906240;
        $rad->radiFechOfic = $this->bdRorf->sysdate();
        $rad->descAnex     = $rad_des_anex;
        //$rad->descAnex     = $rad_des_anex;
        $rad->radiDepeRadi = $depe_code;
        $rad->nofolios      = 0;
        $rad->noanexos      = 0;
        $rad->sgdSpubCodigo = 0;
        $rad->carpCodi      = 0;
        $rad->mrecCodi      = 4;
        $rad->raAsun        = $asunto;
        //$rad->raAsun        = $asunto;
        $rad->guia = "";
        $rad->radi_dato_001 = "";
        $rad->radi_dato_002 = "";
        $rad->esta_fisico = 1;
        $rad->tdocCodi = $tipoDocumento;
        $rad->radUsuaAnte = 'GRESSY.ROJAS16102';
        $nurad = $rad->newRadicado(2, null);

        if($nurad == '-1'){
            return json_encode(array('error'   => 'No Se logro realizar la radicación'));
        }else{
             $usuarioArray = array(
                                    "cedula"         => "",
                                    "nombre"         => "'{$this->nombjuzgado}'",
                                    "apellido"       => "''",
                                    "dignatario"     => "'{$this->nombjuzgado}'",
                                    "telef"          => "'{$this->telefono}'",
                                    "direccion"      => "'{$this->direccion}'",
                                    "email"          => "'{$remitente}'",
                                    "muni"           => "",
                                    "muni_tmp"       => $divipolaRef['codMun'],
                                    "dep"            => "",
                                    "dpto_tmp"       => $divipolaRef['codDep'],
                                    "pais"           => "",
                                    "pais_tmp"       => 170,
                                    "cont_tmp"       => 1,
                                    "tdid_codi"      => 4,
                                    "sgdTrd"         => 2,
                                    "id_sgd_dir_dre" => "'XX0'",
                                    "id_table"       => "'XX'",
                                    "sgdDirTipo"     => 1,
                                    "medio_envio"    => 2,
                                    "dir_tdoc"       => 3
                                );
            $usuario = new \Usuario($this->bdRorf);
            $borrable = true;
            $respons = $usuario->guardarUsuarioRadicado($usuarioArray, $nurad, $borrable);

            if($respons != 1){
                return json_encode(array('error'   => 'No Se Ingreso datos en direcciones'));
            }else{

                $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/Resources/SQLs');
                $twig = new \Twig\Environment($loader, [
                    'cache' => false,
                ]);

                $radicadosSel[0] = $nurad;

                $hist      = new \Historico($this->bdRorf);
                $hist->insertarHistorico($radicadosSel,
                16102,
                906240,
                $depe_code_fin,
                $user_cod_fin,
                "Radicación automatica de correo electronico desde la cuenta del ADMINISTRADOR",
                2);

                $anexarArch = new Anexos();
                $anx = $anexarArch->setAnex($twig, $this->anexosb64, $nurad, $rad_subject, $depe_code_fin, $user_cod_fin, $remitente, $this->mime);

                //$historico = new Historico();
                //$his = $historico->setHist($twig, $nurad, $depe_code_fin, $user_cod_fin, $this->cuentaOrigen, $usua_doc);

                $logradFrom = new LogRadFromMail();
                $logradFrom->setLog($twig, $nurad, $this->cuentaOrigen ,$rad_subject, $depe_code_fin, $user_cod_fin, $remitente);

                $res = [
                            'sequence' => $nurad, 
                            'estado' => 'OK', 
                            "anexos" => $anx, 
                            "historico" => $his,
                            "Usuario del sistema"=>($getMsm) ? $getMsm : 'ok'
                        ];

                return json_encode($res);
            }
        }
    }
}
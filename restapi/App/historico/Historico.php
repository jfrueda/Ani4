<?php 
/*
* @moudle anexos
* @author stygh
*/
namespace App\historico;

use App\Lib\ADOdb;

class Historico
{
    protected $db;

    public function __construct()
    {
        $this->db = new ADOdb();
        //session_start();
    }

    public function getRemitente($remOrig)
    {
        $remOrig = explode("@", $remOrig);
        $remOrig = strtoupper(trim($remOrig[0]));
        //$sql = "SELECT * FROM MAIL_DESTINATARIOS md LEFT JOIN usuario us ON md.FK_USUA_LOGIN = us.usua_login WHERE md.FK_USUA_LOGIN = '{$remOrig}'";
        $sql = "SELECT * FROM USUARIO us WHERE us.USUA_LOGIN = '{$remOrig}'";

        $rs = $this->db->conn->Execute($sql);

        foreach($rs as $value)
        {
            $docUsrRad = $value['USUA_DOC'];
        }
        //echo json_encode(array("res"=>$docUsrRad));
        return (!empty($docUsrRad) ? $docUsrRad : '');

    }

    public function setHist($twig, $radicado, $dep, $codUser, $cuentaOrg, $usua_doc)
    {
        $arrStr = ["ASCII","JIS","EUC-JP","UTF-8"];
        $str = "Radicación automatica de correo electronico desde la cuenta del ADMINISTRADOR";

        $codificacion = mb_detect_encoding($str, $arrStr);
        // $texto = iconv($codificacion, 'UTF-8', $str);
        $texto = $str;

        $sqlHist = $twig->render('insertarHistorico.sql',[
            'depe_codi' => $dep,
            'usua_codi' =>$codUser,
            'radi_nume_radi' => $radicado,
            'hist_obse' =>  $texto,
            'usua_codi_dest' => $codUser,
            'usuaDoc'=>$this->getRemitente($cuentaOrg),
            'sgd_ttr_codigo' => 2,
            'docDest' => "{$usua_doc}",
            'depe_codi_dest' =>  $dep,
        ]);
        // echo json_encode(array("query"=>$sqlHist));
        
        $rsHist = $this->db->conn->execute($sqlHist);

        return $rsHist->EOF;

    }
}


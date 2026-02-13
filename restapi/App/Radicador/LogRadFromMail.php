<?php
/*
* @moudle log radicado from mail
* @author stygh
*/
namespace App\Radicador;

use App\Lib\ADOdb;

class LogRadFromMail
{
    protected $db;

    public function __construct()
    {
        $this->db = new Adodb;
    }


    public function getmailId($destinatario)
    {
        $sql = "SELECT * FROM RADICADO_MAIL WHERE MAIL = '{$destinatario}' ";
        $rs = $this->db->conn->Execute($sql);
        return $rs->fields['MAIL_ID'];
    }

    public function setLog($twig, $radicado, $destinatario ,$descripcion, $depe_codi, $usuaCod, $remit)
    {

        $destinatario = strtoupper(trim($destinatario));
        $mailId = $this->getmailId($destinatario);
        
        $sqlLog = $twig->render('insertFromMail.sql',[
            'radicado' => $radicado,
            'mailId' =>$mailId,
            'destinatario' => $destinatario,
            'descripcion' =>$descripcion,
            'dependencia' =>$depe_codi,
            'usuaCodi' => $usuaCod,
            'remitente'=> $remit
        ]);

        $res = $this->db->conn->Execute($sqlLog);
        //echo json_encode($res->EOF);
        return $res->EOF;
    }
}
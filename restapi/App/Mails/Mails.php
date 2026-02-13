<?php

namespace App\Mails;
use App\Lib\ADOdb;
use App\Lib\ManagementError;
use App\Lib\Auth;
/**
 * @OA\Schema(
 * )
 */
class Mails {

    protected $db;

    public function __construct()
    {
        $this->db = new ADOdb();
    }

    public function webservice(){

        $sql = "SELECT R.MAIL, R.MAIL_FOLDER_ID, R.MAIL_TOKEN_ID FROM RADICADO_MAIL R 
                WHERE r.mail_estado = 1";
                
        $rs = $this->db->conn->Execute($sql);

        //echo $sql;

        foreach($rs as $value)
        {
            $rsMail['MAILS'][]=
            [
                'MAIL'          => $value['MAIL'],
                'FOLDER_ID'     => $value['MAIL_FOLDER_ID'],
                'TOKEN_ID'      => $value['MAIL_TOKEN_ID']
            ];
        }
        $json_response = json_encode($rsMail);
        echo $json_response;
    }

    public function mailsWs($req){

        $req = json_decode($req->reqMethod, true);
        //echo $req;
        
        $sql = "SELECT R.MAIL, U.usua_codi, U.depe_codi 
                FROM RADICADO_MAIL R 
                JOIN MAIL_DESTINATARIOS M 
                ON R.MAIL_ID = M.FK_MAIL_ID 
                JOIN USUARIO U ON U.usua_login = M.FK_USUA_LOGIN 	
                WHERE R.mail_estado = 1
                AND R.MAIL = '{$req['mail']}'";

        $rs = $this->db->conn->Execute($sql);

        //echo $sql;

        foreach($rs as $value)
        {
            $rsMail['MAILS'][]=
            [
                'MAIL'          => $value['MAIL'],
                'USUA_CODI'     => $value['USUA_CODI'],
                'DEPE_CODI'     => $value['DEPE_CODI']
            ];
        }
        $json_response = json_encode($rsMail);
        echo $json_response;
    }

}



<?php

class Scmail {

    private $db;
    function __construct($db){
        $this->db = $db;
    }

    function insertlog($radicado,$estado,$asunto,$desc,$url,$fecha,$destino,$remite,$id)
    {

        $sql="
        insert
                into
                acuse_scmail
            (radi_nume_radi,
                    estadoenvio,
                    asunto,
                    descripcion,
                    urlimagen,
                    fechaestado,
                    emaildestino,
                    emailremite,
                    uuid)
            values(".$radicado.",
            '".$estado."',
            '".$asunto."',
            '".$desc."',
            '".$url."',
            '".$fecha."',
            '".$destino."',
            '".$remite."',
            '".$id."')
        ";
        if($this->db->query($sql))
            return true;
        else
            return false;
        
    }

    function getpdf($url,$id,$bodega)
    {
        $file =$bodega.'cert/'.$id.'.pdf';
        $current = file_get_contents($url);
        if(file_put_contents($file, $current))
            return true;
        else
            return false;

    }

    function insertregenvio($id,$email,$dateF,$radicado,$obs)
    {
        $link  = "<a target=\"_blank\" href=\"./bodega/cert/{$id}.pdf\">Certificación del envio de correo</a>";
        $sql = "INSERT INTO SGD_RENV_REGENVIO(
            id,
            sgd_renv_pais,
            sgd_renv_cantidad,
            sgd_renv_depto,
            sgd_renv_mpio,
            sgd_renv_dir,
            sgd_dir_tipo,
            sgd_renv_mail,
            sgd_renv_codigo,
            sgd_renv_fech,
            radi_nume_sal,
            sgd_fenv_codigo,
            sgd_renv_nombre,
            sgd_renv_observa
        )VALUES(
            (select max(id) + 1 from sgd_renv_regenvio),
            'COLOMBIA',
            1,
            'D.C.',
            'BOGOTÁ',
            '$link',
            1,
            '$email',
            (select max(sgd_renv_codigo) + 1 from sgd_renv_regenvio),
            '$dateF',
            '$radicado',
            106,
            '$id - $email',
            '$obs'
        )";
        if($this->db->query($sql))
         return true;
        else
         return false;

    }

}
?>
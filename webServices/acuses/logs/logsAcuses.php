<?php
ini_set('display_errors',1);

class logsAcuses{

    protected $db;

    public function __construct($ruta_raiz = '')
    {
        $ruta_raiz = (!$ruta_raiz) ? "../../.." : $ruta_raiz;
        
        include_once ("{$ruta_raiz}/include/db/ConnectionHandler.php");
        $this->db = new ConnectionHandler($ruta_raiz);
        return $this->db;
    }

    public function failureRecord($data)
    {

        $rad = explode('Radicado',$data['ASUNTO']);
        $fechIngreso = date('Y-m-d');
        print_r($data);
        print_r($fechIngreso);

        $sql = "INSERT INTO logacuses (radicado, estado, email, uuid, tipo_fallo, fecha_registro)
        VALUES ('{$rad[1]}', '{$data['ESTADO_DEL_CONSUMO']}', '{$data['EMAIL_REMITE']}', '{$data['UUID']}', '{$data['ERROR_API']}','{$fechIngreso}')";

        $rs = $this->db->conn->Execute($sql);
        $res = ($rs->EOF == TRUE) ? 'insertado en el log' : 'No se inserto';
    }
    
    public function dataFailed()
    {
        $sql ='SELECT * FROM logacuses';
        $rs = $this->db->conn->Execute($sql);
        //var_dump($rs);
        if($rs != FALSE)
        {
            $dataErr = [];
            foreach($rs as $value)
            {
                $dataErr[] = $value;
            }
            return $dataErr;
        }
    }
}

//$objLog = new logsAcuses();
//$objLog->failureRecord();


?>
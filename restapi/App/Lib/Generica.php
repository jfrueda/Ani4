<?php namespace App\Lib;

class Generica
{

    Private $conexion ;
    public function __construct($db)
	{
		$this->conexion  = $db;
	}

    public function verificaNivel($nivelA, $nroDoc){
      if($nivelA =='radicado') {
            $sql = "SELECT sgd_rad_nivelsegu as Nivel FROM radicado WHERE radi_nume_radi =$nroDoc";
      }

      if($nivelA =='anexo') {
            $sql = "SELECT nivel_seguridad as Nivel FROM anexos_expediente WHERE anex_codigo = '$nroDoc'";
       }
      $rs = $this->conexion->conn->GetArray($sql);
      if ($rs[0]['NIVEL'] <= $_SESSION['nivel_seg'] ) return false;
      else return true ;
    }

    /**
    * Devuelve la información de la serie
    * @access protected
    * @param string $serieName , nombre o parte del nombre de un departamento
    * @return Array
    */
    function lstSerie($idSerie){

            $sql="select SGD_SRD_CODIGO from sgd_srd_seriesrd 
                   where sgd_srd_codigo = $idSerie";
            $rs = $this->conexion->conn->GetArray($sql);
            if (empty($rs[0]['SGD_SRD_CODIGO'])) return true;
            else return false ;
    }

    
    /**
    * Devuelve la información de la serie
    * @access protected
    * @param string $serieName , nombre o parte del nombre de un departamento
    * @return Array
    */
    function lstSubSerie($idSerie, $idSubserie){

      $sql="select SGD_SRD_CODIGO from sgd_sbrd_subserierd 
             where sgd_srd_codigo = $idSerie and sgd_sbrd_codigo = $idSubserie";
      $rs = $this->conexion->conn->GetArray($sql);
      
      if (empty($rs[0]['SGD_SRD_CODIGO'])) return true;
      else return false ;
    }
    
    /**
    * Devuelve la información de la serie
    * @access protected
    * @param string $serieName , nombre o parte del nombre de un departamento
    * @return Array
    */
    function lstTipodcto($idTipodocu){

      $sql = "SELECT sgd_tpr_codigo FROM sgd_tpr_tpdcumento 
           WHERE sgd_tpr_codigo = $idTipodocu ";
      $rs = $this->conexion->conn->GetArray($sql);
      if (empty($rs[0]['SGD_TPR_CODIGO'])) return true;
      else return false ;
    }

    /**
    * Devuelve la información de la serie
    * @access protected
    * @param string $serieName , nombre o parte del nombre de un departamento
    * @return Array
    */
    function lstRadicadoalfresco($nroRadicado){

      $sql = "SELECT num_alfresco FROM radicado 
             WHERE num_alfresco = '$nroRadicado' ";
        $rs = $this->conexion->conn->GetArray($sql);
        if (empty($rs[0]['NUM_ALFRESCO'])) return true;
        else return false ;
    }

   /**
    * Devuelve la información de la serie
    * @access protected
    * @param string $serieName , nombre o parte del nombre de un departamento
    * @return Array
    */
    function lstDependencia($idDependencia){

      $sql = "SELECT depe_codi FROM dependencia 
             WHERE depe_codi = $idDependencia ";
        $rs = $this->conexion->conn->GetArray($sql);
        if (empty($rs[0]['DEPE_CODI'])) return true;
        else return false ;
    }    


    function fieldExists($table, $field, $query) {
      // Inicializamos la variable $where con una cadena vacía
      $where = "";
      // Recorremos el array de parámetros de consulta
      foreach ($query as $key => $value) {
        // Si es el primer parámetro, construimos la cláusula WHERE sin el operador AND
        if ($where == "") {
          $where .= "$key = '$value'";
        }
        // Si no es el primer parámetro, construimos la cláusula WHERE con el operador AND
        else {
          $where .= " AND $key = '$value'";
        }
      }
      // Construimos la consulta SQL
      $sql = "SELECT $field FROM $table WHERE $where";
     // Ejecutamos la consulta y obtenemos el resultado
     //echo print_r($sql);
      $result = $this->conexion->conn->GetArray($sql);
       // Si la consulta no devuelve nada, devolvemos true
    if (empty($result[0][strtoupper($field)])) { return true;   }
      // Si no, devolvemos false y lo encontro
      return false;
    }

}
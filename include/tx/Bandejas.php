<?php
/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author fundacion Correlibre.org  02/2016
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

OrfeoGpl Models are the data definition of OrfeoGpl Information System
Copyright (C) 2013 Infometrika Ltda.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


class Bandejas
{
    /** Aggregations: */

    /** Compositions: */

    /*** Attributes: ***/
    /**
     * Clase que maneja los Historicos de los documentos
     *
     * @param int     Dependencia Dependencia de Territorial que Anula
     * @param number  usuaDoc    Documento de Usuario
     * @param number  depeCodi   Dependencia de Usuario Buscado
     * @db 	Objeto  conexion
     * @access public
     */
    var  $db;
    var  $codUsuario;
    var  $depeCodi;
    var  $usuaDoc;

    function __construct($db)
    {
        /**
         * Constructor de la clase Historico
         * @db variable en la cual se recibe el cursor sobre el cual se esta trabajando.
         *
         */
        $this->db=$db;
    }

    function Bandejas($db)
    {
        /**
         * Constructor de la clase Historico
         * @db variable en la cual se recibe el cursor sobre el cual se esta trabajando.
         *
         */
        $this->db=$db;
    }
    /**
     * Metodo que trae los datos principales de un usuario a partir del codigo y la dependencia
     *
     * @param number $codUsuario
     * @param number $depeCodi
     *
     */
    // Clase qeu trae los valores de las carpetas Generales del sistema
    //
    function getCarpetasGenerales(){

        $iSql = "select c.carp_codi, c.carp_desc carp_desc, NRADS, r.CONTADOR_NOLEIDOS
            from  (SELECT CARP_CODI, COUNT(RADI_NUME_RADI) NRADS, count(CASE  WHEN (radi_leido =0) THEN 1 ELSE NULL END) CONTADOR_NOLEIDOS
            FROM radicado
            where (radi_usua_actu=".$this->codUsuario." and radi_depe_actu=".$this->depeCodi.")
            and carp_per=0
            group by CARP_CODI
        ) r right outer join carpeta c on (c.carp_codi = r.carp_codi)
        order by c.carp_codi";

        $nRads=0;
        $nRadsNoLeidos=0;
        $rs  = $this->db->conn->query($iSql);
        $auxdevueltos = 0;
        $nRadsMultiple =0;

        while(!$rs->EOF){
            $nRads=0;
            $nRadsNoLeidos=0;
            $nRadsMultiple =0;
            $numdata    = trim($rs->fields["CARP_CODI"]);
            
            //Star::multiple
            if($numdata == "0" ){
                
                $usua_doc=$this->usuaDoc;
                $relacionados = 'select COUNT(*) as TOTAL
                from radicado b
                WHERE b.radi_nume_radi is not null
                  and b.SGD_EANU_CODIGO is null
                  and radi_nume_radi::text LIKE \'%3\'
                  and (b.is_borrador is false or  b.radi_firma  is true)
                  and (
                SELECT count(*)
                FROM SGD_DIR_DRECCIONES
                WHERE SGD_DIR_DRECCIONES.radi_nume_radi=b.radi_nume_radi  ) > 1
                  and (
                SELECT count(*)
                FROM SGD_DIR_DRECCIONES
                WHERE SGD_DIR_DRECCIONES.radi_nume_radi=b.radi_nume_radi AND sgd_doc_fun = \''.$usua_doc.'\' ) > 0
                and (
                     SELECT count(t.*) 
                            FROM hist_eventos t
                            WHERE radi_nume_radi =  b.radi_nume_radi and usua_doc = \''.$usua_doc.'\'  and sgd_ttr_codigo in (13,9)
                    ) = 0';
                $rs_multiple  = $this->db->conn->query($relacionados);
                if($rs_multiple) $nRadsMultiple = $rs_multiple->fields["TOTAL"];
            }
            //End::multiple
           
            //$rsCarpDesc = $db->query($sqlCarpDep);
            $desccarpt  = $rs->fields["CARP_DESC"];
            $nRads      = $rs->fields["NRADS"] + $nRadsMultiple;
            $nRadsNoLeidos      = $rs->fields["CONTADOR_NOLEIDOS"];
            if($nRadsNoLeidos>=1) $nRadsNoLeidos = " $nRadsNoLeidos /"; else $nRadsNoLeidos = " $nRadsNoLeidos /";
            if($numdata==0) $numdata = 9998;
            $data       = (empty($descripcionCarpeta))? trim($desccarpt) : $descripcionCarpeta;
            if($desccarpt=='Devueltos'){$auxdevueltos = 1;}
            $carpetaData[$numdata] = "$desccarpt ($nRadsNoLeidos $nRads)";
            $rs->MoveNext();
        }

        return $carpetaData;

    }

    function getCarpetasPersonales(){

        $iSql = "select c.CODI_CARP carp_codi,
            UPPER(c.NOMB_carp) carp_desc,NRADS, r.CONTADOR_NOLEIDOS
            from  (SELECT CARP_CODI, COUNT(RADI_NUME_RADI) NRADS,
            count(CASE  WHEN (radi_leido =0) THEN 1 ELSE NULL END) CONTADOR_NOLEIDOS
            FROM radicado
            where (radi_usua_actu=".$this->codUsuario." and radi_depe_actu=".$this->depeCodi.")
            and carp_per=1
            group by CARP_CODI
        ) r right outer join carpeta_per c on (c.CODI_carp = r.carp_codi)
        WHERE
        c.usua_codi=".$this->codUsuario."   and c.depe_codi=".$this->depeCodi."
        order by c.CODI_CARP";

        $nRads=0;
        $nRadsNoLeidos=0;
        $rs  = $this->db->conn->query($iSql);
        $auxdevueltos = 0;

        while(!$rs->EOF){
            $nRads=0;
            $nRadsNoLeidos=0;
            $numdata    = trim($rs->fields["CARP_CODI"]);
            $desccarpt  = $rs->fields["CARP_DESC"];
            $nRads      = $rs->fields["NRADS"];
            $nRadsNoLeidos      = $rs->fields["CONTADOR_NOLEIDOS"];
            if($nRadsNoLeidos>=1) $nRadsNoLeidos = " $nRadsNoLeidos /"; else $nRadsNoLeidos = " $nRadsNoLeidos /";
            if($numdata==0) $numdata = 9998;
            $data       = (empty($descripcionCarpeta))? trim($desccarpt) : $descripcionCarpeta;
            if($desccarpt=='Devueltos'){$auxdevueltos = 1;}
            $carpetaData[$numdata] = "$desccarpt ($nRadsNoLeidos $nRads)";
            $rs->MoveNext();
        }

        return $carpetaData;

    }


    function getContadorInformados($cod, $dependencia){
        $isql   =" SELECT COUNT(DISTINCT I.radi_nume_radi) AS CONTADOR
            FROM INFORMADOS I
            JOIN RADICADO R ON I.radi_nume_radi = R.radi_nume_radi
            WHERE I.DEPE_CODI=$dependencia
            and I.usua_codi=$cod
            and I.info_conjunto=0
            and R.is_borrador = false";

        $rs1     = $this->db->conn->query($isql);
        $numerot = ($rs1)? $rs1->fields["CONTADOR"] : 0;
        return $numerot;
    }

    function getContadorTramiteConjunto($cod, $dependencia){
        $sqlMemNorm = "SELECT count(*) CNTINF 
            FROM tramiteconjunto i 
            INNER JOIN radicado r2 ON r2.radi_nume_radi = i.radi_nume_radi AND r2.is_borrador = false
            WHERE i.depe_codi = '{$dependencia}' 
            AND i.usua_codi = {$cod} 
            AND i.info_codi IS NOT NULL";
        $rs1 = $this->db->conn->query($sqlMemNorm);
        $numerot = ($rs1) ? $rs1->fields["CNTINF"] : 0;
        return $numerot;
    }

}
?>

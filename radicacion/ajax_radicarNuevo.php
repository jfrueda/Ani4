<?php
/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

 Orfeo - Argo Models are the data definition of Information System
 Copyright (C) 2013 Infometrika Ltda - Correlibre.org.

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

session_start();
define('SALIDA', 1);
define('ENTRADA', 2);
define('MEMORANDO', 3);
define('CIRC_INTERNA', 4);
define('CIRC_EXTERNA', 5);
define('RESOLUCION', 6);
define('AUTO', 7);
$ruta_raiz = "..";
include "$ruta_raiz/include/tx/sanitize.php";

$sendEmail = true; //Enviar correo electronico

if (!$_SESSION['dependencia'])
    header ("Location: $ruta_raiz/cerrar_session.php");

header('Content-Type: application/json');
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
$db     = new ConnectionHandler("$ruta_raiz");
$ADODB_COUNTRECS  = true;
$ADODB_FORCE_TYPE = ADODB_FORCE_NULL;

include("$ruta_raiz/include/tx/Tx.php");
include("$ruta_raiz/include/tx/Radicacion.php");
include("$ruta_raiz/include/tx/usuario.php");
include("$ruta_raiz/include/tx/roles.php");
include("$ruta_raiz/include/tx/notificacion.php");
include("$ruta_raiz/class_control/Municipio.php");

$hist      = new Historico($db);
$classusua = new Usuario($db);
$Tx        = new Tx($db);

$dependencia   = $_SESSION["dependencia"];
$codusuario    = $_SESSION["codusuario"];
$usua_doc      = $_SESSION["usua_doc"];
$tpDepeRad     = $_SESSION["tpDepeRad"];
$adate         = date('Y');

$tpRadicado    = empty($_POST['datorad'])? 0 : $_POST['datorad'];
$tpRadicado    = trim($tpRadicado ,";");

//$tpRadicado    = empty($_POST['radicado_tipo'])? 0 : $_POST['radicado_tipo'];
$cuentai       = $_POST['cuentai'];
$guia          = $_POST['guia'];
$empTrans      = $_POST['empTrans'];
$fecha_gen_doc = $_POST['fecha_gen_doc'];
$usuarios      = $_POST['usuario'];
$asu           = $_POST['asu'];
$med           = $_POST['med'];
$nofolios      = $_POST['nofolios'];
$noanexos      = $_POST['noanexos'];
$otro_us       = $_POST['otro_us'];
$sgdSpubCodigo = $_POST['nivelSeguridad'];

if( strlen($_POST['ane']) == 0) {
    $ane = '   ';
}else{
    $ane = $_POST['ane'];
}

if ($_POST['coddepe']==0) {
    $coddepe   = $_SESSION["dependencia"];
}else{
    $coddepe   = $_POST['coddepe'];
}

$tdoc          = $_POST['tdoc'];
$ent           = $_POST['ent'];
$radicadopadre = $_POST['radicadopadre'];
if(!$radicadopadre){  $radicadopadre = null; }


//Enviados solo si es para modificar
$modificar     = $_POST['modificar'];
$nurad         = $_POST['nurad'];
$radi_dato_001 = $_POST['radi_dato_001'];
$radi_dato_002 = $_POST['radi_dato_002'];

if($modificar == 'true' && !is_null($nurad))
	$modificar     = true;
else
	$modificar     = false;

//Logica de Notificaciones
if ($ent == CIRC_INTERNA || $ent == CIRC_EXTERNA ||
    $ent == RESOLUCION || $ent == AUTO) {
    $esNotificacion = true;
    $notificacion = new Notificacion($db);
    $infoNotificacion['notifica_codi']  = $_POST['notifica_codi'];
    $infoNotificacion['med_public']     = $_POST['med_public'];
    $infoNotificacion['caracter_adtvo'] = $_POST['caracter_adtvo'];
    $infoNotificacion['siad']           = $_POST['siad'];
    $infoNotificacion['prioridad']      = false;
    //$infoNotificacion['prioridad']      = $_POST['prioridad'];
} else {
    $esNotificacion = false;
}

if ($ent == CIRC_INTERNA || $ent == CIRC_EXTERNA) {
  $esNotificacionCircular = true;
  $infoCircular['destinatarios'] = $_POST['destinatarios'];
  $infoCircular['tpRad'] = $ent;
  $infoCircular['destinatarios_codi'] = $_POST['destinatarios_codi'];
} else {
  $esNotificacionCircular = false;
}

//LAS DIRECCIONES DE LAS ENTRADAS SOLO SE PUEDE BORRAR CUANDO SON AGREGADAS POSTERIOR A LA CREACIÓN
$direcciones_borrables = true;

if($ent == ENTRADA && !$modificar) {
    $direcciones_borrables = false;
}

$sqlMedEnvio = 'select mrec_codi from radicado where radi_nume_radi = ' . $nurad;
$rsSqlMedEnvio = $db->conn->query($sqlMedEnvio);  
$medEnvioAnt = $rsSqlMedEnvio->fields['MREC_CODI'];


/**************************************************/
/*********** RADICAR DOCUMENTO  *******************/
/**************************************************/
$rad = new Radicacion($db);

//Si el radicado que se esta realizando es un memorando
//este debe quedar guardado en la bandeja del usuario que
//realiza el radicado por esta razon guardamos el radicado
//con el codigo del usuario que realiza la accion.
if($ent == 2){
    $carp_codi         = 0;

    $rad->radiDepeActu = $coddepe;

    $rol  = new Roles($db);
    //El grupo numero 2 corresponde a Jefe de grupo
    //en el listado predefinido de perfiles
    if($rol->buscarUsuariosGrupoDepen(2, $coddepe)){
        $rad->radiUsuaActu = $rol->users[0];
    }
}else{
    $carp_codi         = $ent;
    $rad->radiUsuaActu = $codusuario;
    $rad->radiDepeActu = $dependencia;
}

$rad->radiTipoDeri = $tpRadicado;
$rad->radiCuentai  = $cuentai;
$rad->guia         = trim(substr($guia,0 ,20));
$rad->empTrans     = $empTrans;
$rad->eespCodi     = $documento_us3;
$rad->mrecCodi     = $med;// "dd/mm/aaaa"
$rad->radiFechOfic =       substr($fecha_gen_doc,6 ,4)
    ."-". substr($fecha_gen_doc,3 ,2)
    ."-". substr($fecha_gen_doc,0 ,2);

if(!$radicadopadre){
    $radicadopadre = null;
}

if(!$ent){
    $radicadopadre = null;
}

$rad->radiNumeDeri  = trim($radicadopadre);
$rad->descAnex      = substr($ane, 0, 99);
$rad->radiDepeRadi  = "'$coddepe'";
$rad->trteCodi      = $tip_rem;
$rad->nofolios      = $nofolios;
$rad->noanexos      = $noanexos;
$rad->sgdSpubCodigo = $sgdSpubCodigo;
$rad->carpCodi      = $carp_codi;
$rad->carPer        = $carp_per;
$rad->trteCodi      = $tip_rem;

if($ent == SALIDA)
    $rad->raAsun        = substr(stripcslashes($asu),0,510);
else
    $rad->raAsun        = substr(stripcslashes($asu),0,350);


$rad->radi_dato_001 = $radi_dato_001;
$rad->radi_dato_002 = $radi_dato_002;

if(isset($_POST['s_firmador'])) {
    $firmador = $_POST['s_firmador'];
    if($firmador != '0-0') {
        $firmador = explode("-", $firmador);
        $rad->usuaFirma = $firmador[0];
        $rad->depeFirma = $firmador[1];    
    } 
} 



if(strlen(trim($aplintegra)) == 0){
    $aplintegra = "0";
}

$rad->sgd_apli_codi = $aplintegra;
	if($nurad){
	    if ($modificar==true){
		$rad->tdocCodi = "noactualizar";
	    }else{
		$rad->tdocCodi = $tdoc;
	    }
	    $resUp=$rad->updateRadicado($nurad);
	    if($resUp[0]){
		    $flagModRad=true;
	    }  
	}else{
	    $rad->tdocCodi = $tdoc;

	    #Lógica para borrador notificaciones
	    if($esNotificacion == true || $ent == MEMORANDO || $ent == SALIDA) {
		$nurad = $rad->newRadicadoBorrador($ent, $tpDepeRad[$ent]);
	    } else {
		$nurad = $rad->newRadicado($ent, $tpDepeRad[$ent]);
	    }   
	}

	if ($nurad=="-1"){
	    $data[] = array("error"   => 'Error al generar y/o guardar el radicado. Comuniquese con el administrador del sistema');
	}else{

/*******************************************************************************************************************************************/
// Inserta Nueva Transacción
/*******************************************************************************************************************************************/
if($ent == 2)
{
    $radicadosSelBoss[0] = $nurad;

        $sqldepBoss = "SELECT *
                        FROM autm_membresias m , usuario u
                        WHERE m.autg_id = 2
                            AND u.usua_esta = '1'
                            AND u.id = m.autu_id
                            AND u.depe_codi = {$coddepe}";

        $rs = $db->conn->Execute($sqldepBoss);

    if(!empty($nurad))
    {
         $histor = $hist->insertarHistorico($radicadosSelBoss,
            $dependencia,
            $codusuario,
            $coddepe,
            $rs->fields['USUA_CODI'],
            "Radicado Asignado al usuario:" .$rs->fields['USUA_NOMB'],
        112,
        'hboss'
        );
    }    
}

/*******************************************************************************************************************************************/

	    $data[] = array("answer"  => $nurad);

	    if ($esNotificacion) { 
		$infoNotificacion['radicado'] = $nurad;
		$respuestaNotificacion = $notificacion->creaEditaNotificacion($infoNotificacion, $modificar);
		if (!$respuestaNotificacion['status']) {
		    $data[] = array("error"  => $respuestaNotificacion['message']);
		} 
	    }


    if ($esNotificacion) { 
        $infoNotificacion['radicado'] = $nurad;
        $respuestaNotificacion = $notificacion->creaEditaNotificacion($infoNotificacion, $modificar);
        if (!$respuestaNotificacion['status']) {
            $data[] = array("error"  => $respuestaNotificacion['message']);
        } 
    }

    // Si es Circular no se necesita guardar registro en ordenacto_dirdreccion porque
    // el destinatario es un grupo de entidades o personas y la orden de dichos actos

    // es siempre Publicar solamente.
    if ($esNotificacionCircular) {
        $response = $notificacion->guardarDestinatarioRadicadoCirculares(
            $infoCircular, 
            $respuestaNotificacion['sgd_notif_codigo'],
            $modificar
        );
        if (!$response['status']) {
            $data[] = array("error"  => $respuestaNotificacion['message']);
        }
    }

$radicadosSel[0] = $nurad;

	if ($modificar){
        $modificarMedEnvio = false;
		$_tipo_tx = 21;
		$obsHist="";
		$labelsR=['RADI_CUENTAI'=>'Referencia', 'MREC_CODI'=>'Medio Recepción/Envío','RADI_FECH_OFIC'=>'Fecha oficio',
			'RA_ASUN'=>'Asunto','RADI_NUME_FOLIO'=>'Folios','RADI_DESC_ANEX'=>'Descripción Anexos',
			'RADI_NUME_ANEXO'=>'No. Anexos','EMP_TRANSPORTADORA'=>' Transportadora','RADI_NUME_GUIA'=>'Guía' ];
		foreach($resUp[1] as $campo => $mod){
           
            if($campo == 'MREC_CODI') {
                $modificarMedEnvio = true;
            }           

			if($campo != 'RADI_NUME_RADI')
				strlen($obsHist)<2?$obsHist="Campos modificados. ".$labelsR[$campo].": $mod.":$obsHist.=" ".$labelsR[$campo].": $mod.";
		}

        $lookSend = '';
        $lookModified = '';
        $flagBorrar = true;
        if($med == 2 || $med == 1 || $med == 10){
            if($medEnvioAnt == 4 || $medEnvioAnt == 7) {
                $lookSend = 'E-mail';
                $lookModified = 'Físico';
            } else {
                $lookSend = 'Físico';
                $lookModified = 'Físico';
                $flagBorrar = false;
            }
        } elseif($med == 4) {
            $lookSend = 'Físico';
            $lookModified = 'E-mail';
        } elseif($med == 7) {
            $lookSend = 'FísicoE-mail';
            $lookModified = 'E-mailFísico';
        }
   
        if($modificarMedEnvio && $lookSend != '' && $medEnvioAnt != $med) {
            $arrayShippingMethod = array();
            //array_push($arrayShippingMethod, "raspberry");

            $queryIdAnexo = "select id, anex_estado from anexos where anex_radi_nume = $nurad and anex_borrado = 'N'";
            $rsQueryIdAnexo = $db->conn->query($queryIdAnexo);
            while(!$rsQueryIdAnexo->EOF){
                
                $estadoActualAnexo = $rsQueryIdAnexo->fields["ANEX_ESTADO"];
                $idActual = $rsQueryIdAnexo->fields["ID"];

                $estadoFlagEnvio = 1;
                if($estadoActualAnexo == 2) {
                    $estadoFlagEnvio = 0;
                }
                if($estadoActualAnexo == 4) {
                    $sqlEstadoActual = "update anexos set anex_estado = 3 where id= " . $idActual;
                    $db->conn->Execute($sqlEstadoActual);                
                }
           
               
                if($med == 1 || $med == 2 || $med == 4 || $med == 10) {
                        if($flagBorrar) {
                            $deleteShippingMethod = "delete from sgd_rad_envios where id in (select unnest(array_agg(id)) from sgd_rad_envios 
                            where id_anexo =  $idActual and tipo = '$lookModified')";
                            $db->conn->Execute($deleteShippingMethod); 
                        }

                        $updateShippingMethod = "update sgd_rad_envios set tipo = '$lookModified', estado = $estadoFlagEnvio  
                        where id_anexo = $idActual and tipo = '$lookSend'";
                        $db->conn->Execute($updateShippingMethod);                       
                        

                } else {

                    $selectShippingMethod = "select id_anexo,id_direccion,certificado, tipo from sgd_rad_envios 
                            where id_anexo = $idActual";

                    $rsSelectShippingMethod = $db->conn->query($selectShippingMethod);
                    while(!$rsSelectShippingMethod->EOF){
                        $tipoAux = $rsSelectShippingMethod->fields["TIPO"] == 'E-mail' ? 'Físico' : 'E-mail';
                        $db->conn->Execute("INSERT INTO sgd_rad_envios (id_anexo, id_direccion, tipo, estado,certificado) VALUES (" . $rsSelectShippingMethod->fields["ID_ANEXO"] . ", " . $rsSelectShippingMethod->fields["ID_DIRECCION"] . ", '" . $tipoAux . "', $estadoFlagEnvio,'" . $rsSelectShippingMethod->fields["CERTIFICADO"] . "')"); 
                        $rsSelectShippingMethod->MoveNext();
                    }                        
                }   
                $rsQueryIdAnexo->MoveNext();
            }  

        }               


	}else{$_tipo_tx = 2;$obsHist='';}


//Start::si tiene referencia insertar en historico
if(!empty($radicadopadre) && $tpRadicado != '1'){
    $hist->insertarHistorico($radicadosSel,
        $dependencia ,
        $codusuario,
        $dependencia,
        $codusuario,
        "Se Asocia a radicado : ".$radicadopadre,
        102);
}
//End::si tiene referencia insertar en historico

/**********************************************************************
 *********** GRABAR DIRECCIONES ***************************************
 **********************************************************************
 * Existen tres tipos distintos de datos de direccion
 *
 * Descripcion
 * (0_0_XX_XX2)
 * primer campo : consecutivo de los usuarios asignados a un radicado
 *                si es nuevo puede ser 0, o si es el primer registro
 *                de los usuarios asignados al radicado.
 *
 * segundo campo: tipo de usuario {usuario: 0, empresa :2, funcionario: 6}
 *
 * tercer campo: codigo con el cual esta grabado en la tabla SGD_DIR_DRECCIONES
 *
 * Cuarto campo; el codigo de identificacion del usuario de la tabla origen.
 *               esta tabla puede ser la de SGD_OEM_CODIGO, SGD_CIU_CODIGO
 *               o USUARIOS
 *
 *
 * 1) Un usuario nuevo (0_0_XX_XX2)(0_0_XX_XX3)....
 *    El usuario nuevo se puede identificar cuando en los datos
 *    de usuario se muestra el siguiente string (0_0_XX_XX2) el
 *    cual denota que no existe un codigo de almacenamiento unido a un
 *    radicado que son las dos primeras equis, las siguietnes son el
 *    codigo que le corresponde al usuario almacenado en la base de datos
 *    ya sea un usuario, funcionario o entidad y esta representado por
 *    las ultimas equis. Como se pueden crear mas de un usuario nuevo se
 *    genera un cosecutivo que es el ultimo digito
 *    ejemplo: (0_0_XX_XX2) las dos xx significan que no esta asociado
 *              a ningun radicado, las ultimas muestran que es un
 *              usuario nuevo y el 2 que es el segundo registro generado

 * 2) Un usuario existente en el sistema, NO asociado a un radicado (0_0_XX_12)
 *    (0_0_XX_16)...
 *
 *
 * 3) Un usuario existen (0_0_123_17) (0_0_327_123)
 *    Al momento de genear un radicado podemos traer usuario del sistema y a su vez
 *    cambiar la informacion que hace parte de este.
 */

//Datos de usuarios

if (!$esNotificacionCircular) {
    $iU =0;
    $dirCodigos=array();
     
    foreach ($usuarios as $clave => $valor) {
        $iU++;

        list($consecutivo, $sgdTrd, $id_sgd_dir_dre, $id_table) = explode('_', $valor);

        $cedula_usuario   = str_replace(' ','',$_POST[$valor."_".cedula]);
        $nombre_usuario   = $_POST[$valor."_".nombre];
        $apellido_usuario = $_POST[$valor."_".apellido];

        //Si es una modificacion de un radicado que incluya un usuario nuevo, se valida que 
        //el usuario realmente no tenga un registro en sgd_ciu_ciudadano y si el registro
        //existe entonces se valida sgd_dir_direcciones tambien. Esto debido a que si el 
        //radicado es modificado inmediatamente despues de haberlo creado en el formulario
        //de pre-radicacion, es decir, sin haber llamado antes radicacion/ajax_buscarUsuario, 
        //el HTML no se ha actualizado interactivamente y entonces este campo viene vacio.
        if ($modificar == true) {
            //$db->log_error ("000-- $nurad ", $sgdTrd.' - '.$id_table);
            
            if($sgdTrd == 0 || $sgdTrd == 6)
            {
                if (!is_numeric($id_table)) {
                    $query_ = " SELECT scc.sgd_ciu_codigo 
                                FROM sgd_ciu_ciudadano scc  
                                INNER JOIN sgd_dir_drecciones sdd
                                ON scc.sgd_ciu_codigo = sdd.sgd_ciu_codigo 
                                WHERE scc.sgd_ciu_cedula = '$cedula_usuario'
                                OR (scc.sgd_ciu_nombre = '$nombre_usuario' 
                                    AND scc.sgd_ciu_apell1 = '$apellido_usuario')
                                AND sdd.radi_nume_radi = $nurad";
                    $query_1 = $query_;
                    $rs = $db->conn->query($query_);
                    if($rs && !$rs->EOF){
                        $id_table = $rs->fields["SGD_CIU_CODIGO"];
                    }
                }
                if (is_numeric($id_table) && !is_numeric($id_sgd_dir_dre)) {
                    $query_ = " SELECT sdd.sgd_dir_codigo 
                                FROM sgd_dir_drecciones sdd  
                                WHERE sdd.sgd_ciu_codigo = $id_table
                                AND sdd.radi_nume_radi = $nurad";
                    $rs = $db->conn->query($query_);
                    if($rs && !$rs->EOF){
                        $id_sgd_dir_dre = $rs->fields["SGD_DIR_CODIGO"];
                    }
                }
            }

            if($sgdTrd == 2)
            {
                if (!is_numeric($id_table)) {
                    $query_ = " SELECT soo.sgd_oem_codigo 
                                FROM sgd_oem_empresas soo  
                                INNER JOIN sgd_dir_drecciones sdd
                                ON soo.sgd_oem_codigo = sdd.sgd_oem_codigo 
                                WHERE soo.sgd_oem_nit = '$cedula_usuario'
                                AND sdd.radi_nume_radi = $nurad";
                    $query_1 = $query_;
                    $rs = $db->conn->query($query_);
                    if($rs && !$rs->EOF){
                        $id_table = $rs->fields["SGD_OEM_CODIGO"];
                    }
                }
                if (is_numeric($id_table) && !is_numeric($id_sgd_dir_dre)) {
                    $query_ = " SELECT sdd.sgd_dir_codigo 
                                FROM sgd_dir_drecciones sdd  
                                WHERE sdd.sgd_oem_codigo = $id_table
                                AND sdd.radi_nume_radi = $nurad";
                    $rs = $db->conn->query($query_);
                    if($rs && !$rs->EOF){
                        $id_sgd_dir_dre = $rs->fields["SGD_DIR_CODIGO"];
                    }
                }
            }

            //$db->log_error ("000-- $nurad ", $id_sgd_dir_dre);
        }

        //OBTENEMOS EL CONTINENTE DINAMICAMENTE
        $_id_pais = $_POST[$valor."_".pais_codigo];
        $query_ = "select p.id_cont from sgd_def_paises p where id_pais = $_id_pais";
        $rs = $db->conn->query($query_);
        while(!$rs->EOF){
            $id_continente    = $rs->fields["ID_CONT"];
            $rs->MoveNext();
        }

        if($_POST[$valor."_".cargo])
            $cargoNue = $_POST[$valor."_".cargo];
        else
            $cargoNue = '';

        /*if(!$_POST[$valor."_".direccion])
            $_POST[$valor."_".direccion]=$_POST[$valor."_".direccion];*/


        $usuarios[$clave] = array(
            "cedula"         => $cedula_usuario,
            "nombre"         => $nombre_usuario,
            "apellido"       => $apellido_usuario,
            "dignatario"     => trim($_POST[$valor."_".dignatario]) == '' ? $nombre_usuario.' '.$apellido_usuario : trim($_POST[$valor."_".dignatario]),
            "telef"          => $_POST[$valor."_".telefono],
            "direccion"      => $_POST[$valor."_".direccion],
            "email"          => $_POST[$valor."_".email],
            "muni"           => $_POST[$valor."_".muni],
            "muni_tmp"       => $_POST[$valor."_".muni_codigo],
            "dep"            => $_POST[$valor."_".dep],
            "dpto_tmp"       => $_POST[$valor."_".dep_codigo],
            "pais"           => $_POST[$valor."_".pais],
            "pais_tmp"       => $_POST[$valor."_".pais_codigo],
            "cont_tmp"       => $id_continente,
            "tdid_codi"      => $_POST[$valor."_".tdid_codi],
            "sgdTrd"         => empty($sgdTrd)? 0 : $sgdTrd ,
            "id_sgd_dir_dre" => $id_sgd_dir_dre,
            "id_table"       => $id_table,
            "sgdDirTipo"     => $iU,
            "cargo"          => $cargoNue,
            "medio_envio"    => $_POST[$valor."_".med_envio],
            "dir_tdoc"    => $_POST[$valor."_".tdid_codi]
        );

        if($ent == 2){
            $query = "select u.USUA_EMAIL
                from usuario u
                where u.USUA_CODI = 1 and u.depe_codi='$coddepe'";
            $rsM=$db->conn->query($query);
            $mailDestino_frm = $rsM->fields["USUA_EMAIL"];
        }

        $respons = $classusua->guardarUsuarioRadicado($usuarios[$clave], $nurad, $direcciones_borrables);

        if($respons!=1){
            //Se revisa si hay mensaje de error guardado en la clase usurio
            foreach ($classusua->result as $keyUsuario => $valueUsuario) {
                if (is_array($valueUsuario)) {
                    if (!empty($valueUsuario['error'])) {
                        $errorMsg = $valueUsuario['error'];
                    }
                } else {
                    if ($keyUsuario == "error") {
                        $errorMsg = $valueUsuario;
                    }
                }
            } 
            if (empty($errorMsg)) {
                $errorMsg = "No se agregó correctamente el destinatario, compruebe datos.";
            } 
            $data[] = array("error" => $errorMsg);
        } else {
            if ($modificar) {
	        $flagModDir=false;
		$dirCodigos[]=$classusua->result['value'];
		    if($classusua->result['state']){
			    if(!$classusua->result['prev']){
				    $obsHist.=" Agregado Rem/Des ".$usuarios[$clave]['cedula'].'.';
				    $flagModDir_T=true;
			    }else{
				    foreach($classusua->result['record'] as $kdata => $vdata){
					    if(!in_array($kdata,['SGD_DIR_CODIGO','RADI_NUME_RADI','SGD_CIU_CODIGO','SGD_OEM_CODIGO'])){
						    $obsHist.=" $kdata: $vdata";
						    $flagModDir=true;
					    }
				    }
				    if($flagModDir){
					    $obsHist.=" Modificado Rem/Des ".$usuarios[$clave]['cedula'].'.';
						$flagModDir_T=true;
				    }
				    }
			    $obsHist.=".";
		    }
	    }


            //Logica de Notificaciones
            if (!empty($_POST[$valor."_".orden])) {
                $sgd_dir_drecciones_id = $classusua->result["value"];
                $orden_acto = $_POST[$valor."_".orden];
                if ($modificar) {
                    $notificacion->borrarOrdenesNotificacion($sgd_dir_drecciones_id);
                }
                foreach ($orden_acto as $orden_id) { 
                    $dupla = array("sgd_dir_codigo" => $sgd_dir_drecciones_id, "orden_codi" => $orden_id);
                    $rtaOrdenNotificacion = $notificacion->creaEditaOrdenesNotificacion($dupla, false);
                    if (!$rtaOrdenNotificacion['status']){
                        $data[] = array("error"  => $rtaOrdenNotificacion['message']);
                    } 
                    array_push($borrar, $dupla);
                }
            }
        }


        //ENVIAR UN CORREO ELECTRONICO AL DESTINATARIO AL MOMENTO DE RADICAR.
        if($sendEmail && !$esNotificacion){
            $codTx=51;
            $_emailUser  = $_POST[$valor."_".email]; //Email del usuario
            $radicadosSelText = $nurad;
            if ($_emailUser == ""){$_emailUser="no-reply@test.com";}

            $entidad = $_SESSION['entidad'];
            $nombre_fichero = $_SESSION['entidad'].".mailInformar.php";
            $ruta_fichero = $ruta_raiz.'/include/mail/'.$nombre_fichero;
            /**
            if (file_exists($ruta_fichero)) {
                  require("$ruta_raiz/include/mail/$entidad.mailInformar.php");
            } else {
                  require("$ruta_raiz/include/mail/GENERAL.mailInformar.php");
            }
             */
            ob_end_clean();
        }//FIN enviar email

    }

    }

	    //** Elimina Terceros que no llegaron
            if ($modificar) {
		    $sqlDirPrev="Select sgd_dir_codigo as COD,sgd_dir_nombre nom from sgd_dir_drecciones where radi_nume_radi=$nurad";
		    $dirPrev=$db->conn->GetArray($sqlDirPrev);
		    foreach ($dirPrev as $llave => $value) {
			    if(!in_array($value['COD'], $dirCodigos)){
					$classusua->borrarUsuarioRadicado($value['COD'],$nurad);
					$obsHist.=" Eliminado Rem/Des: ".$value['COD'];
					$flagModDir_T=true;
			    }
		    }
	    //if(is_null($flagModRad) && is_null($flagModDir_T))
		//$data[] = array("error" => 'No se actualiz&oacute; el radicado');
	    }
}
if(($esNotificacion == true || $ent == MEMORANDO || $ent == SALIDA) && !isset($_POST['modificar'])) {

 $hist->insertarHistorico( $radicadosSel,
    $dependencia ,
    $codusuario,
    $dependencia,
    $codusuario,
    "Se generó borrador: " . $nurad,
    104);  

} else {

 $hist->insertarHistorico( $radicadosSel,
    $dependencia ,
    $codusuario,
    $dependencia,
    $codusuario,
    $obsHist,
    $_tipo_tx);   
}


#Se guarda el usuairo por defecto de la super en circulares para poder anexar adjunto word
if($esNotificacionCircular) {
    $sqlUsuarioDefecto = "INSERT INTO sgd_dir_drecciones(
    id, sgd_dir_codigo, sgd_dir_tipo, sgd_ciu_codigo, radi_nume_radi, muni_codi, dpto_codi, sgd_dir_direccion, sgd_dir_telefono, sgd_dir_mail, sgd_sec_codigo, sgd_dir_nombre, sgd_doc_fun, sgd_dir_nomremdes, sgd_trd_codigo, sgd_dir_tdoc, sgd_dir_doc, id_pais, id_cont, sgd_dir_apellido, sgd_dir_enviado, medio_envio)
    VALUES ((SELECT nextval('sgd_dir_drecciones_id_seq')), (SELECT nextval('sec_dir_drecciones')),
              1, 0, " . $nurad . ", 1, 11, 'Carrera 68A N.º 24B – 10', '744 2000', 'info@supersalud.gov.co', 0, 'Super Salud', 0, 'NA', 2, 4, 1, 170, 1, 'NA', 0, 2)";
    $db->conn->Execute($sqlUsuarioDefecto);    

}

#Para notificaciones se asgina a los radicados la clasificación documental automáticamente dependiente del tipo

if($esNotificacion) {
    $record = array(); 
    $record["RADI_NUME_RADI"] = $nurad;
    $record["DEPE_CODI"]      = $dependencia;
    $record["USUA_CODI"]      = $codusuario;
    $record["USUA_DOC"]       = $usua_doc;    
    $record["SGD_RDF_FECH"]   = $db->conn->OffsetDate(0,$db->conn->sysTimeStamp);

    if ($ent == CIRC_INTERNA) {
        $record["SGD_MRD_CODIGO"] = 15720;
        $nombTrd = "Circular Interna";
        $sgdTprCodigo = 273;
    } elseif($ent == CIRC_EXTERNA) {
        $record["SGD_MRD_CODIGO"] = 15718;
        $nombTrd = "Circular Externa";
        $sgdTprCodigo = 274;
    } elseif($ent == RESOLUCION) {
        $record["SGD_MRD_CODIGO"] = 15716;
        $nombTrd = "Resolución";
        $sgdTprCodigo = 258;
    } elseif($ent == AUTO){     
        $record["SGD_MRD_CODIGO"] = 15714;
        $nombTrd = "Auto";
        $sgdTprCodigo = 276;
    }

    $insertSQL = $db->insert("SGD_RDF_RETDOCF", $record, "true");

    $updateTdocCodi = 'update public.radicado set tdoc_codi = ' . $sgdTprCodigo . ' where radi_nume_radi = ' . $nurad;
    $db->conn->Execute($updateTdocCodi);

     $hist->insertarHistorico($radicadosSel,
        $dependencia ,
        $codusuario,
        $dependencia,
        $codusuario,
        "Se agregó TRD Automático: " . $nombTrd,
        32);   



    include_once ("$ruta_raiz/include/tx/TipoDocumental.php");   
    $trd = new TipoDocumental($db);
    $trd->setFechVenci($nurad,$sgdTprCodigo);    
}


echo json_encode($data);

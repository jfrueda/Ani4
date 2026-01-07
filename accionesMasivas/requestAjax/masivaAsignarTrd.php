<?php
include_once('dataCommon.php');
  /*
    * dataCommon.php comparte los datos mas relevantes y los 
    * objetos mas utilizados como session,adodb, etc.
    */	
  
  $mensaje0		= "Parametros incorrectos";	 
  $mensaje1		= "NO SE MODIFICO LA TRD DE NINGUN RADICADO";	 
				  
  /** Retorna los radicados a los cuales se le cambia la trd
    *  Cambio y registro en el historico de trd se los radicados
    *  seleccionados 	 
    */	
	  
  if (empty($depenUsua) || empty($selectTipoDoc) || empty($selectSubSerie)){
	  salirError ($mensaje0);
	  return;
  }		
  
  //Buscamos en la matriz el valor que une a la dependencia, serie, subserie, tipoDoc.
  $isqlTRD = "
  select 
  SGD_MRD_CODIGO
	  from 
	  SGD_MRD_MATRIRD
	  where 
      SGD_SRD_ID 	= $selectSerie
      and SGD_SBRD_ID = $selectSubSerie
      and SGD_TPR_CODIGO 	= $selectTipoDoc";
  //$db->conn->debug = true;
  $rsTRD = $db->conn->Execute($isqlTRD);			

	  
  //Se crean dos variables por que la clase esta creada de esta manera
  //y no se cambiara en este momento.
  $codiTRDS[] = $codiTRD = $rsTRD->fields['SGD_MRD_CODIGO'];    
  //echo ">>>>".$codiTRD."<<<<<";
  //Proceso de asginacion de trd para los radicados que no tienen
  //echo ">>>>> ($radConTrd) && $cambExiTrd  <<<<<<<<<<<";
  if(!empty($radSinTrd)){
	  
    $radSinTrdArr= explode(",",$radSinTrd);		

    // Get all related radicados including anexos recursively
    $allRadSinTrd = array();
    foreach ($radSinTrdArr as $value) {
      $anexRad = Tx::recursiveAnex(array($value), $db);
      $allRadSinTrd = array_merge($allRadSinTrd, $anexRad);
    }
    $allRadSinTrd = array_unique($allRadSinTrd);

    foreach ($allRadSinTrd as $value){										
      $trd->insertarTRD($codiTRDS,$codiTRD,$value,$depenUsua, $codusuario);			
      //guardar el registro en el historico de tipo documental.
      //permite controlar cambios del TD de un radicado
      
      $queryGrabar	= "INSERT INTO SGD_HMTD_HISMATDOC(											
			  SGD_HMTD_FECHA,
			  RADI_NUME_RADI,
			  USUA_CODI,
			  SGD_HMTD_OBSE,
			  USUA_DOC,
			  DEPE_CODI,
			  SGD_MRD_CODIGO)
			      VALUES(
			      $sqlFechaHoy,
			      $value,
			      $codusuario,
			      'El usuario: $usua_nomb Cambio el tipo de documento',
			      $usua_doc,
			      $depenUsua,
			      '$codiTRD')";
      //$db->conn->Execute($queryGrabar);
      
      //Actulizar la TD en el radicado					
      $upRadiTdoc	="UPDATE 
			      RADICADO
		      SET  
			      TDOC_CODI = $selectTipoDoc
		      WHERE 
			      radi_nume_radi =  $value";
      
      $db->conn->Execute($upRadiTdoc);
	    
    }
    $observa   = "Asignar TRD de forma masiva";
    
    $radiModi  = $Historico->insertarHistorico(	$allRadSinTrd,
					$depenUsua,
					$codusuario,
					$depenUsua,
					$codusuario,
					$observa,
					32);	
	  $result = $radSinTrd;		
  }			
	
	
  //Proceso de asginacion de trd para los radicados que SI tienen
  //y se quiere es modificar.
  
  
  if(!empty($radConTrd) && $cambExiTrd == 111){
	  
    $radConTrdArr		= explode(",",$radConTrd);		
    
    foreach ($radConTrdArr as $radicadoCon){
	    
    //Buscamos los datos anteriores de la trd y los
    //colocamos en el mensaje del historico
    
    $sqlhis="	select 
              s.sgd_srd_descrip || '/' || su.sgd_sbrd_descrip || '/' || t.sgd_tpr_descrip AS TRD_ANTERIOR
            from 
                SGD_RDF_RETDOCF mf,
                SGD_MRD_MATRIRD m, 
                DEPENDENCIA d,
                SGD_SRD_SERIESRD s,
                SGD_SBRD_SUBSERIERD su, 
                SGD_TPR_TPDCUMENTO t
            where d.depe_codi = mf.depe_codi
                and s.id = m.sgd_srd_id
                and su.id = m.sgd_sbrd_id
                and su.sgd_srd_id = m.sgd_srd_id
                and t.sgd_tpr_codigo = m.sgd_tpr_codigo
                and mf.sgd_mrd_codigo = m.sgd_mrd_codigo
                and mf.radi_nume_radi = $radicadoCon";

    $resultHis	= $db->conn->Execute($sqlhis);			
    $histTrd 	= ($resultHis && !$resultHis->EOF) ? $resultHis->fields['TRD_ANTERIOR'] : 'Sin TRD anterior';			

    $sqlUA 		= "	UPDATE 
					    SGD_RDF_RETDOCF 
				    SET 
					    SGD_MRD_CODIGO 	= '$codiTRD',
				    USUA_CODI 		= '$codusuario'
			    WHERE 
			      RADI_NUME_RADI 	= '$radicadoCon' 
			      AND DEPE_CODI 	= '$depenUsua'";
					    
    $rsUp 		= $db->conn->query($sqlUA);
	    
    //guardar el registro en el historico de tipo documental.
    //permite controlar cambios del TD de un radicado
    
    $queryGrabar	= "INSERT INTO SGD_HMTD_HISMATDOC(											
			SGD_HMTD_FECHA,
			RADI_NUME_RADI,
			USUA_CODI,
			SGD_HMTD_OBSE,
			USUA_DOC,
			DEPE_CODI,
			SGD_MRD_CODIGO
			)
			  VALUES(
			  $sqlFechaHoy,
			  $radicadoCon,
			  $codusuario,
				  'El usuario: $usua_nomb Cambio el tipo de documento',
				  $usua_doc,
				  $depenUsua,
				  '$codiTRD')";					
    
    //$db->conn->Execute($queryGrabar);	
    
    //Actulizar la TD en el radicado					
    $upRadiTdoc	=	"UPDATE 
			  RADICADO
			  SET  
			    TDOC_CODI = $selectTipoDoc
			  WHERE 
			    radi_nume_radi = $radicadoCon";
    
    $db->conn->Execute($upRadiTdoc);										
    }			
    
    $observa 	= "	Cambio masivo TRD por: Usuario: $usua_nomb - Dependencia: $depenUsua
				    TRD Anterior: $histTrd";
    
    $radiModi 	= $Historico->insertarHistorico(
			    $radConTrdArr
			    ,$depenUsua
			    ,$codusuario
			    ,$depenUsua
			    ,$codusuario
			    ,$observa
			    ,32);	
    $result 	.= $radConTrd;
						  
  }			
	
	$result = (empty($result))? $mensaje1 : $result;
	
	$accion= array( 'respuesta' => true,
					'mensaje'	=> $result);
	print_r(json_encode($accion));
?>

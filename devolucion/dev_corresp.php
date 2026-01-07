<?php
 session_start();
 foreach ($_GET as $key => $valor)   ${$key} = $valor;
 foreach ($_POST as $key => $valor)   ${$key} = $valor;

 $krd            = $_SESSION["krd"];
 $dependencia    = $_SESSION["dependencia"];
 $usua_doc       = $_SESSION["usua_doc"];
 $codusuario     = $_SESSION["codusuario"];
 $depe_codi_territorial     = $_SESSION["depe_codi_territorial"];
 $anoActual = date("Y");
 $ruta_raiz = "..";
 include_once "$ruta_raiz/include/db/ConnectionHandler.php";
 $db = new ConnectionHandler("$ruta_raiz");	


 define('ADODB_FETCH_ASSOC',2);
 $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
?>
<head>
<!--<link rel="stylesheet" href="../estilos/orfeo.css">-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<BODY>

<form name="new_product"  action='dev_corresp.php?<?=session_name()."=".session_id()."&krd=$krd&fecha_h=$fechah&fecha_busq=$fecha_busq"?>' method=post>
<div class="alert alert-success" role="alert">
 <h3>DEVOLUCIÓN DE RADICADOS POR TIEMPO DE ESPERA</h3>
</div>


<div class="form-group">
    <label for="exampleFormControlSelect1">Seleccione una Dependencia</label>
  
<?
	$encabezado = "".session_name()."=".session_id()."&krd=$krd&fecha_busq=$fecha_busq&estado_sal=$estado_sal&estado_sal_max=$estado_sal_max&dep_sel=$dep_sel&filtroSelect=$filtroSelect&nomcarpeta=$nomcarpeta&orderTipo=$orderTipo&orderNo=";
        $linkPagina = "$PHP_SELF?$encabezado&orderTipo=$orderTipo";
    include "$ruta_raiz/include/query/devolucion/querydependencia.php";
	error_reporting(7);
	$ss_RADI_DEPE_ACTUDisplayValue = "--- TODAS LAS DEPENDENCIAS ---";
	$valor = 0;
	$sqlD = "select $sqlConcat ,depe_codi from dependencia where depe_estado = 1 order by depe_codi";
	$rsDep = $db->conn->Execute($sqlD);
	 print $rsDep->GetMenu2("dep_sel","$dep_sel",$blank1stItem = "$valor:$ss_RADI_DEPE_ACTUDisplayValue", false, 0," onChange='submit();' class='form-control'");	
	?>

  </div>
<button type="submit" class="btn btn-primary">Consultar</button>
<input type="hidden" name="busqueda" value="ok">

</form>

<?php
error_reporting(7);

if($busqueda=='ok')
{
    if ($dep_sel == 0 ) 
		{
         include "$ruta_raiz/include/query/devolucion/querydependencia.php";
	     $sqlD = "select $sqlConcat ,depe_codi from dependencia 
       	            where depe_estado=1
					order by depe_codi";

				//echo $sqlD; 
	
		define('ADODB_FETCH_ASSOC',2);
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$rsDep = $db->conn->Execute($sqlD);
		
		while(!$rsDep->EOF)
		{
	            $depcod = $rsDep->fields["DEPE_CODI"];
		    $lista_depcod .= " $depcod,";
		    $rsDep->MoveNext();
		}   
		$lista_depcod .= "0"; 	
	    	//$where_depe = "";
		}
	else 
	{ 
	$lista_depcod = $dep_sel;
	//$where_depe = ' and '.$db->conn->substr.'(radi_nume_salida, 5, 3) ='.$dep_sel;
	
	}
    
	$fecha_busqt = $fecha_busq;
	$fecha_fin = mktime($hora_ini,$hora_fin,00,substr($fecha_busqt,5,2),substr($fecha_busqt,8,2),substr($fecha_busqt,0,4));
	//$where_like = " and radi_nume_salida like '$anoActual%'";
	$where_like = "";
	//$where_fecha = "sgd_fech_impres <= TO_DATE('$fecha_busqt','yyyy-mm-dd HH24:MI:ss')";
    include "$ruta_raiz/include/query/devolucion/querydev_corresp.php";



		define('ADODB_FETCH_ASSOC',2);
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $rsCount = $db->query($isqlC);
	$num_reg = $rsCount->fields["NUMERO"];
    if ($num_reg == 0)
	{
	echo "<script>alert('No existen radicados para devolver de esta Seleccion');</script>";
	}
	echo "</p> 

	<form action='confirmaDev.php' method=post>


	<table border='0' class=borde_tab width=100%><tr></tr><td class=titulos2><strong>Registros Encontrados : " .$rsCount->fields["NUMERO"]. "</strong></td></tr></table>";
	$fecha_busqt = $fecha_busq;
	$fecha_fin = mktime($hora_ini,$hora_fin,00,substr($fecha_busqt,5,2),substr($fecha_busqt,8,2),substr($fecha_busqt,0,4));
    $where_like = "";
	$fech_devol = "'".date("Y-m-d H:i:s")."'" ;
	$usua_devol = "'".$usua_nomb."'" ;
	include "$ruta_raiz/include/query/devolucion/querydev_corresp.php";
    $rs = $db->conn->Execute($isql);
	$fech_tot = $fecha_busqt."  ".$hora_ini.":".$minutos_ini;
	//echo "<p><table class=borde_tab width='100%'><tr><td class=titulos2>RADICADOS ENVIADOS A CORRESPONDENCIA ANTES DE $fech_tot </p></td></tr></table>";
	/*
	Listado Resultado de la seleccion
	*/


	$encabezado = "".session_name()."=".session_id()."&krd=$krd&fecha_busq=$fecha_busq&devolver_rad=$devolver_rad&estado_sal=$estado_sal&estado_sal_max=$estado_sal_max&dep_sel=$dep_sel&filtroSelect=$filtroSelect&nomcarpeta=$nomcarpeta&orderTipo=     $orderTipo&orderNo=";
        $linkPagina = "$PHP_SELF?$encabezado&orderTipo=$orderTipo&orderNo=$orderNo";



	$pager = new ADODB_Pager($db,$isql,'adodb', true,$orderNo,$orderTipo);
	$pager->toRefLinks = $linkPagina;
	$pager->toRefVars = $encabezado;
	$pager->checkAll = true;
	$pager->checkTitulo = false; 
	$pager->Render($rows_per_page=1000,$linkPagina,$checkbox=chkEnviar);

 if(!$devolver_dependencias and $num_reg > 0)
	{
     ?>
 	  <center><input type=SUBMIT name='devolver_dependencias'  value = 'CONFIRMAR DEVOLUCION' class="btn btn-success"></center>
 	</form>
	  <?php
	 }

	}

?>

</html>

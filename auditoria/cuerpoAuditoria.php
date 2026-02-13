<?php
session_start();
$ruta_raiz = "..";
include_once "$ruta_raiz/processConfig.php";

if (!$_SESSION['dependencia'])
    header ("Location: $ruta_raiz/cerrar_session.php");

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

define('ADODB_ASSOC_CASE', 2);
$verrad         = "";
$krd            = $_SESSION["krd"];
$dependencia    = $_SESSION["dependencia"];
$usua_doc       = $_SESSION["usua_doc"];
$codusuario     = $_SESSION["codusuario"];
$verrad         = "";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
  <link rel="stylesheet" type="text/css" href="https://unpkg.com/notie/dist/notie.min.css">
  <script src="./js/popcalendar.js"></script>
  <script src="./js/mensajeria.js"></script>
  <div id="spiffycalendar" class="text"></div>
  <script language="JavaScript" src="<?=$ruta_raiz?>/js/loader/waitMe.js"></script>
  <link rel="stylesheet" href="<?=$ruta_raiz?>/js/loader/waitMe.css">
</head>
<?

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once("$ruta_raiz/class_control/Mensaje.php");
if (!$db) $db = new ConnectionHandler($ruta_raiz);

$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
$objMensaje= new Mensaje($db);
$mesajes = $objMensaje->getMsgsUsr($_SESSION['usua_doc'],$_SESSION['dependencia']);

$nomcarpeta = "Reporte de auditoria";
include "./envios/paEncabeza.php";
?>
<body onLoad="window_onload();" class="waitMe_body" >
    <div class="waitMe_container img" style="background:rgba(255,255,255,0.7)"> 
      <div style="background:url('../bodega/sys_img/logo.png') no-repeat center center; background-size: contain; width: 256px; height: 256px; position: absolute; left: 50%; top: 50%; margin: -128px 0 0 -128px;"></div>
    </div>
<script src="https://unpkg.com/notie"></script>
<?
   

if ($swLog==1)
	echo ($mesajes);
	  if(trim($orderTipo)=="") $orderTipo="DESC";
  if($orden_cambio==1){
	  if(trim($orderTipo)!="DESC"){
		   $orderTipo="DESC";
		}else{
			$orderTipo="ASC";
		}
	}

	if(!$carpeta) $carpeta=0;

	if($busqUsuario || $busqRadicado || $busExpediente){
    $busqUsuario = trim($busqUsuario);
    $busqRadicado = trim($busqRadicado);
    $busExpediente = trim($busExpediente);
  
    $whereUsuario .= " h.HIST_FECH BETWEEN '$busqFechaInicial 0:0' AND '$busqFechaFinal 23:59' ";
    $whereUsuarioExp .= " t.SGD_HFLD_FECH BETWEEN '$busqFechaInicial 0:0' AND '$busqFechaFinal 23:59' ";
    if( strlen($busqUsuario)>4 ){
      $whereUsuario .= " AND us.usua_nomb like '%$busqUsuario%'";$whereUsuarioExp .=" AND us.usua_nomb like '%$busqUsuario%'";
    }
    if( strlen($busqRadicado)>4 ){
      $whereUsuario .= " AND (b.RADI_NUME_RADI  = '$busqRadicado' or cast(b.radi_nume_borrador as varchar(20)) = '$busqRadicado')  ";$whereUsuarioExp .= " AND t.RADI_NUME_RADI  = '$busqRadicado'";
    }
    if( strlen($busExpediente)>4 ){
      $whereUsuarioExp .= " AND t.SGD_EXP_NUMERO  = '$busExpediente'";
    }
	}
   $encabezado = "".session_name()."=".session_id()."&busqUsuario=$busqUsuario&busqFechaInicial=$busqFechaInicial&busqFechaFinal=$busqFechaFinal";
   $linkPagina = "$PHP_SELF?$encabezado&orderTipo=$orderTipo&orderNo=$orderNo&busqUsuario=$busqUsuario&busqFechaInicial=$busqFechaInicial&busqFechaFinal=$busqFechaFinal&busqRadicado=$busqRadicado&busExpediente=$busExpediente";

   require dirname(__DIR__, 1)."/vendor/autoload.php";
    
   $client = new Predis\Client(array(
     'scheme' => 'tcp',
     'host'   => $redis_host??'redis',
     'port'   => 6379,
   ));
 
   $cacheKey = 'usuarios';
   $usuarios = $client->get($cacheKey);
   
  if (!$usuarios || count($usuarios) == 0 || time() - $client->ttl($cacheKey) > 28800) {
    $usuarios = $db->conn->GetAll("SELECT distinct t.usua_nomb FROM public.usuario t");
    $client->set($cacheKey, serialize($usuarios), 'EX', 28800);
  } else {
    $usuarios = unserialize($usuarios);
  }
 
?>
<div class="col-sm-12"> <!-- widget grid -->
  <section>
      <!-- row -->
      <div class="row">
      <!-- NEW WIDGET START -->
      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- Widget ID (each widget will need unique ID)-->
        <div class="well" data-widget-editbutton="false">
          <!-- widget content -->
          <div class="widget-body" class="smart-form">
            <h2 class="text-center">Informe Auditoria Usuarios</h2>
          </div>
        </div>
      </div>
    </article>
    </div>
  </section>
</div>
<div class="col-sm-12"> <!-- widget grid -->
  <section>
    <!-- row -->
    <div class="row">
    <!-- NEW WIDGET START -->
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <!-- Widget ID (each widget will need unique ID)-->
      <div class="well" data-widget-editbutton="false">
        <!-- widget content -->
        <div class="widget-body" class="smart-form">
            <form 
              name="form_busq_rad" 
              id="form_busq_rad" 
              class="form-inline" 
              action='<?=$_SERVER['PHP_SELF']?>?<?=$encabezado?>' 
              method="post"
              onsubmit="return validateForm()"
              >
              Nombre Usuario
              <input 
                  style="width: 150px;" 
                  autocomplete="off"  
                  name="busqUsuario" 
                  id="busqUsuario" 
                  class="input" 
                  type="text" 
                  value="<?=$busqUsuario?>" 
                  list="usuarios">
              <datalist id="usuarios">
                <?php
                foreach($usuarios as $usuario) {
                  echo "<option value='".$usuario['USUA_NOMB']."'>";
                }
                ?>
              </datalist>
              Radicado
              <input  autocomplete="off"  name="busqRadicado" id="busqRadicado"  class="input" type="text" value="<?=$busqRadicado?>" >
              Expediente
              <input  autocomplete="off"  name="busExpediente" id="busExpediente"  class="input" type="text" value="<?=$busExpediente?>" >
              Fecha inicial
              <input name="busqFechaInicial" required class="input" type="date" value="<?=$busqFechaInicial?>">
              Fecha final
              <input name="busqFechaFinal" required class="input" type="date" value="<?=$busqFechaFinal?>">
              <?
             
              $fecha_hoy = Date("Y-m-d");
              $sqlFechaHoy=$db->conn->DBDate($fecha_hoy);
              //Filtra el query para documentos agendados
            ?>
             
            </div>
            <input type=submit value='Buscar ' id="Buscar" name=Buscar valign='middle' class='btn btn-primary btn-sm'>
          </form>
        </div>
      </div>
    </div>
  </article>
  </div>
</section>
</div>

<form name="form1" id="form1" action="./tx/formEnvio.php?<?=$encabezado?>" method="POST">
<div class="col-sm-12"> <!-- widget grid -->
<section>
<!-- row -->
<div class="row">
<!-- NEW WIDGET START -->
<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<!-- Widget ID (each widget will need unique ID)-->
<div class="jarviswidget jarviswidget-color-darken" data-widget-editbutton="false">
<!-- widget div-->
<div>
  <!-- widget content -->
  <div class="widget-body">
    <div class="table-responsive">

  <?
  $currentPage = isset($_GET['adodb_next_page']) ? (int)$_GET['adodb_next_page'] : 1;
  $perPage = 100;
  $offset = ($currentPage - 1) * $perPage;
  $controlAgenda=1;
	if($carpeta==11 and !$tipo_carp and $codusuario!=1){
	}else
	{
  //include "./tx/txOrfeo.php";
	}
	/*  GENERACION LISTADO DE RADICADOS
	 *  Aqui utilizamos la clase adodb para generar el listado de los radicados
	 *  Esta clase cuenta con una adaptacion a las clases utiilzadas de orfeo.
	 *  el archivo original es adodb-pager.inc.php la modificada es adodb-paginacion.inc.php
	 *
	 */
	
        
        include "$ruta_raiz/include/query/queryAuditoria.php";
        if($busqFechaInicial>$busqFechaFinal){
          echo "<script>notie.alert({ type: 'error', text: 'Fechas invalidas' })</script>";
          $error = 1;
        }
        
        if(
          (
            strlen($busqUsuario)>4 ||
            strlen($busqRadicado)>4 ||
            strlen($busExpediente)>4
          ) && 
          is_string($busqFechaInicial) && 
          is_string($busqFechaFinal)
        ){
          $rs=$db->conn->Execute($isql);
          if ($rs->EOF and $busqUsuario)  {
            echo "<hr><center><b><span class='alarmas'>No se encuentra ningun registro con el criterio de busqueda</span></center></b></hr>";
          }
          else{
            $pager = new ADODB_Pager($db,$isql,'adodb', true,1,'desc');
            $pager->checkAll = false;
            $pager->checkTitulo = true; $pager->toRefLinks = $linkPagina;
            $pager->toRefVars = $encabezado;
            $pager->descCarpetasGen=$descCarpetasGen;
            isset($_GET["adodb_next_page"]) ? $pager->curr_page = $_GET["adodb_next_page"] : $pager->curr_page = 1 ;
            $pager->descCarpetasPer=$descCarpetasPer;
            $pager->Render($rows_per_page=100,$linkPagina);
          }
        }


        ?>
        </div>

      </div>
    </div>
  </div>

</article>
</div>
</section>
</div>
</form>
</body>
 <script>
      var input = document.getElementById('busqUsuario');
      var datalist = document.getElementById('usuarios');
      input.addEventListener('input', function() {
          var val = this.value;
          var opts = datalist.childNodes;
          for (var i = 0; i < opts.length; i++) {
              if (opts[i].value === val) {
                  // An item was selected from the list!
                  // yourCallbackHere()
                  break;
              }
          }
      });
      function validateForm() {

        var busqUsuario = document.getElementById('busqUsuario').value;
        var busqRadicado = document.getElementById('busqRadicado').value;
        var busExpediente = document.getElementById('busExpediente').value;

        if (busqUsuario.trim() === '' && busqRadicado.trim() === '' && busExpediente.trim() === '') {
          notie.alert({ type: 'error', text: 'Se debe diligenciar al menos un campo' })
          return false;
        }

        return true;
      }

      const waitMe = () => {
          $('body').waitMe({
              effect: 'img', // Use the progress bar effect
              text: '',             // No text
              bg: "rgba(255,255,255,0.7)",  // Semi-transparent white background
              source: "../bodega/sys_img/logo.png",           // No additional source
              onClose: function() {} // Callback when closed
          });
      }

      $("form").submit(function(event) {
        $("input[name='Buscar']").addClass("disabled");
        waitMe();
      });

  </script>
</html>

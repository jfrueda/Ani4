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
  <script language="JavaScript" src="<?=$ruta_raiz?>/js/loader/waitMe.js"></script>
  <link rel="stylesheet" href="<?=$ruta_raiz?>/js/loader/waitMe.css">
  <div id="spiffycalendar" class="text"></div>
</head>
<?
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once("$ruta_raiz/class_control/Mensaje.php");
if (!$db) $db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
$objMensaje= new Mensaje($db);
$mesajes = $objMensaje->getMsgsUsr($_SESSION['usua_doc'],$_SESSION['dependencia']);

$nomcarpeta = "Reporte de auditoria sesion";
include "./envios/paEncabeza.php";
?>
<body onLoad="window_onload();" class="waitMe_body" >
    <div class="waitMe_container img" style="background:rgba(255,255,255,0.7)">
      <div style="background:url('../imagenes/reload.gif')"></div>
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

	if($busqUsuario){
    $busqUsuario = trim($busqUsuario);
    $whereUsuario .= " (TO_TIMESTAMP(h.FECHA)::timestamp AT TIME ZONE 'UTC' AT TIME ZONE 'COT')::date BETWEEN TO_DATE('$busqFechaInicial', 'YYYY-MM-DD') AND  TO_DATE('$busqFechaFinal', 'YYYY-MM-DD') AND us.usua_nomb like '%$busqUsuario%'";
	}
   $encabezado = "".session_name()."=".session_id()."&depeBuscada=$depeBuscada&filtroSelect=$filtroSelect&tpAnulacion=$tpAnulacion&carpeta=8&tipo_carp=$tipo_carp&chkCarpeta=$chkCarpeta&nomcarpeta=$nomcarpeta&&busqUsuario=$busqUsuario&busqFechaInicial=$busqFechaInicial&busqFechaFinal=$busqFechaFinal";
   $linkPagina = "$PHP_SELF?$encabezado&orderTipo=$orderTipo&orderNo=$orderNo&carpeta=8&busqUsuario=$busqUsuario&busqFechaInicial=$busqFechaInicial&busqFechaFinal=$busqFechaFinal";
   
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
            <h2 class="text-center">Informe Auditoria Sesión</h2>
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
            <form name="form_busq_rad" id="form_busq_rad" class="form-inline" action='<?=$_SERVER['PHP_SELF']?>?<?=$encabezado?>' method="post">
              Nombre Usuario
              <input  autocomplete="off" name="busqUsuario"  class="input" type="text" value="<?=$busqUsuario?>" list="usuarios">
              <datalist id="usuarios">
                <?php
                foreach($usuarios as $usuario) {
                  echo "<option value='".($usuario['USUA_NOMB']??$usuario['usua_nomb'])."'>";
                }
                ?>
              </datalist>
              Radicado
              <input name="busqRadicado"  class="input" type="text" maxlength="19" value="<?=$busqRadicado?>">
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
            <input type=submit value='Buscar ' name=Buscar valign='middle' class='btn btn-primary btn-sm'>
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

        include "$ruta_raiz/include/query/queryAuditoriaSesion.php";
        if($busqFechaInicial>$busqFechaFinal){
          echo "<script>notie.alert({ type: 'error', text: 'Fechas invalidas' })</script>";
          $error = 1;
        }
        if( empty($busqUsuario) && empty($busqRadicado)){
          echo "<script>notie.alert({ type: 'error', text: 'Usuario o radicado requerido' })</script>";
          $error = 1;
        }
        if(
          is_string($busqFechaInicial) && 
          is_string($busqFechaFinal) &&
          !isset($error)
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
            if($_GET["adodb_next_page"]) $pager->curr_page = $_GET["adodb_next_page"];
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

      const waitMe = () => {
          $('body').waitMe({
              effect: 'img', // Use the progress bar effect
              text: '',             // No text
              bg: "rgba(255,255,255,0.7)",  // Semi-transparent white background
              source: "../imagenes/reload.gif",           // No additional source
              onClose: function() {} // Callback when closed
          });
      }

      $("form").submit(function(event) {
        $("input[name='Buscar']").addClass("disabled");
        waitMe();
      });
  </script>
</html>

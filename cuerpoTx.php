<?php

/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

SIIM2 Models are the data definition of SIIM2 Information System
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

session_start();

$ruta_raiz = ".";
if (!$_SESSION['dependencia'])
  header("Location: $ruta_raiz/cerrar_session.php");

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
  <script src="./js/popcalendar.js"></script>
  <script src="./js/mensajeria.js"></script>
  <div id="spiffycalendar" class="text"></div>
</head>
<?
include_once "./include/db/ConnectionHandler.php";
require_once("$ruta_raiz/class_control/Mensaje.php");
if (!$db) $db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
$objMensaje = new Mensaje($db);
$mesajes = $objMensaje->getMsgsUsr($_SESSION['usua_doc'], $_SESSION['dependencia']);

$nomcarpeta = "Ultimas Transacciones Realizadas";
include "./envios/paEncabeza.php";
?>

<body onLoad="window_onload();">
  <?
  if ($swLog == 1)
    echo ($mesajes);
  if (trim($orderTipo) == "") $orderTipo = "DESC";
  if ($orden_cambio == 1) {
    if (trim($orderTipo) != "DESC") {
      $orderTipo = "DESC";
    } else {
      $orderTipo = "ASC";
    }
  }

  if (!$carpeta) $carpeta = 0;

  if ($busqRadicados) {
    $busqRadicados = trim($busqRadicados);
    $textElements  = explode(",", $busqRadicados);
    $newText       = "";
    $dep_sel       = $dependencia;
    foreach ($textElements as $item) {
      $item = trim($item);
      if (strlen($item) != 0) {
        $busqRadicadosTmp .= " cast(b.radi_nume_radi as varchar) like '%$item%' or";
      }
    }

    if (substr($busqRadicadosTmp, -2) == "or") {
      $busqRadicadosTmp = substr($busqRadicadosTmp, 0, strlen($busqRadicadosTmp) - 2);
    }

    if (trim($busqRadicadosTmp)) {
      $whereFiltro .= "and ( $busqRadicadosTmp ) ";
    }
  }
  $encabezado = "" . session_name() . "=" . session_id() . "&depeBuscada=$depeBuscada&filtroSelect=$filtroSelect&tpAnulacion=$tpAnulacion&carpeta=8&tipo_carp=$tipo_carp&chkCarpeta=$chkCarpeta&nomcarpeta=$nomcarpeta&&busqRadicados=$busqRadicados&";
  $linkPagina = "$PHP_SELF?$encabezado&orderTipo=$orderTipo&orderNo=$orderNo&carpeta=8";
  $encabezado = "" . session_name() . "=" . session_id() . "&adodb_next_page=1&krd=$krd&depeBuscada=$depeBuscada&filtroSelect=$filtroSelect&tpAnulacion=$tpAnulacion&carpeta=8&tipo_carp=$tipo_carp&nomcarpeta=$nomcarpeta&orderTipo=$orderTipo&orderNo=";

  if ($selttr) {
    $whereFiltro .= "and ttr.sgd_ttr_codigo = $selttr ";
  }

  if (!empty($_REQUEST['fecha_inicial']) && !empty($_REQUEST['fecha_final'])) {
    $_SESSION['fecha_inicial'] = $_REQUEST['fecha_inicial'];
    $_SESSION['fecha_final'] = $_REQUEST['fecha_final'];
  }

  if ($fecha_inicial && $fecha_final) {
    // Agregar horas para PostgreSQL: 00:00:00 para fecha inicial y 23:59:59 para fecha final
    $fecha_inicial_completa = $fecha_inicial . ' 00:00:00';
    $fecha_final_completa = $fecha_final . ' 23:59:59';
    $whereFiltro .= "and (h.hist_fech between '$fecha_inicial_completa' and '$fecha_final_completa')";
  }

  $queryttr = "select sgd_ttr_descrip,sgd_ttr_codigo from sgd_ttr_transaccion where sgd_ttr_codigo in (2,9,8,13,12,16,25,26,51,52,53,54,55) order by sgd_ttr_descrip";
  $rsD = $db->conn->query($queryttr);
  $ttr = $rsD->GetMenu2("selttr", $selttr, '0:Todos', false, "", "onChange='submit()' class='select'");
  ?>
  <div class="container-fluid">
    <div class="col-sm-12">
      <section>
        <div class="row">
          <article class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
              <div class="card-header bg-orfeo text-white">
                <h4 class="mb-0">
                  Buscar Radicado(s)
                </h4>
                <p class="text-white small">
                  Ingrese uno o varios radicados separados por coma.
                </p>
              </div>
              <div class="card-body py-4">
                <form name="form_busq_rad"
                  id="form_busq_rad"
                  class="row gy-3 align-items-end"
                  action='<?= $_SERVER['PHP_SELF'] ?>?<?= $encabezado ?>'
                  method="post">

                  <?
                  if ($chkCarpeta) {
                    $chkValue = " checked ";
                    $whereCarpeta = " ";
                  } else {
                    $chkValue = "";
                    if (!$tipo_carp) $tipo_carp = "0";
                    $whereCarpeta = " and b.carp_codi=$carpeta  and b.carp_per=$tipo_carp";
                  }
                  $fecha_hoy = Date("Y-m-d");
                  $sqlFechaHoy = $db->conn->DBDate($fecha_hoy);
                  //Filtra el query para documentos agendados
                  ?>

                  <!-- BUSQUEDA RADICADOS -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Radicados</label>
                    <input name="busqRadicados"
                      class="form-control"
                      type="text"
                      value="<?= $busqRadicados ?>"
                      placeholder="Ejemplo: 2024-0001, 2024-0002">
                  </div>

                  <!-- FECHA INICIAL -->
                  <div class="col-md-3">
                    <label class="form-label fw-semibold">Fecha inicial</label>
                    <input type="date"
                      name="fecha_inicial"
                      id="fecha_inicial_aux"
                      autocomplete="off"
                      format="YYYY-MM-DD"
                      class="form-control"
                      value="<?= empty($_SESSION['fecha_inicial']) ? '' : $_SESSION['fecha_inicial'] ?>">
                  </div>

                  <!-- FECHA FINAL -->
                  <div class="col-md-3">
                    <label class="form-label fw-semibold">Fecha final</label>
                    <input type="date"
                      name="fecha_final"
                      id="fecha_final_aux"
                      autocomplete="off"
                      format="YYYY-MM-DD"
                      class="form-control"
                      value="<?= empty($_SESSION['fecha_final']) ? '' : $_SESSION['fecha_final'] ?>">
                  </div>

                  <!-- TIPO TRANSACCION -->
                  <div class="col-md-4">
                    <label class="form-label fw-semibold">Tipo transacción</label>
                    <?= $ttr ?>
                  </div>

                  <!-- BOTÓN BUSCAR -->
                  <div class="col-md-4 text-end">
                    <input type="submit"
                      value="Buscar"
                      name="Buscar"
                      valign="middle"
                      class="btn btn-primary px-4 py-2 fw-semibold">
                  </div>
                </form>
              </div>
            </div>
          </article>
        </div>
      </section>
    </div>
  </div>

  <form name="form1" id="form1" action="./tx/formEnvio.php?<?= $encabezado ?>" method="POST">
    <div class="col-sm-12">
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
                  <div class="table-responsive" style='display:none'>
                    <?
                    $controlAgenda = 1;
                    if ($carpeta == 11 and !$tipo_carp and $codusuario != 1) {
                    } else {
                      //include "./tx/txOrfeo.php";
                    }
                    /**
                     * GENERACION LISTADO DE RADICADOS
                     * Aqui utilizamos la clase adodb para generar el listado de los radicados
                     * Esta clase cuenta con una adaptacion a las clases utiilzadas de orfeo.
                     * el archivo original es adodb-pager.inc.php la modificada es adodb-paginacion.inc.php
                     */
                    if (strlen($orderNo) == 0) {
                      $orderNo = "2";
                      $order = 3;
                    } else {
                      $order = $orderNo + 1;
                    }

                    $sqlFecha = $db->conn->SQLDate("Y-m-d H:i A", "h.HIST_FECH");
                    include "$ruta_raiz/include/query/queryCuerpoTx.php";
                    $rs = $db->conn->Execute($isql);
                    if ($rs->EOF and $busqRadicados) {
                      echo "<hr><center><b><span class='alarmas'>No se encuentra ningun radicado con el criterio de busqueda</span></center></b></hr>";
                    } else {
                      $pager = new ADODB_Pager($db, $isql, 'adodb', true, $orderNo, $orderTipo);
                      $pager->checkAll = false;
                      $pager->checkTitulo = true;
                      $pager->toRefLinks = $linkPagina;
                      $pager->toRefVars = $encabezado;
                      $pager->descCarpetasGen = $descCarpetasGen;
                      if ($_GET["adodb_next_page"]) $pager->curr_page = $_GET["adodb_next_page"];
                      $pager->descCarpetasPer = $descCarpetasPer;
                      $pager->Render($rows_per_page = 2000, $linkPagina, $checkbox = $chkAnulados);
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

  <script>
    $(document).ready(function() {
      $('td span a').each(function() {
        if ($(this).hasClass('radNumClass')) {
          $(this).removeAttr('href');
          $(this).css('textDecoration', 'none')
          $(this).css('pointer', 'none')
          $(this).css('color', 'black');
        }
      });
      $('.table-responsive').show();
    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const select = document.querySelector("select[name='selttr']");
      if (select) {
        select.classList.add("form-select");
      }
    });
  </script>
</body>

</html>
<?php
session_start();
$ruta_raiz = "..";
define('ADODB_ASSOC_CASE', 1);
if (!$_SESSION['dependencia'])
  header("Location: $ruta_raiz/cerrar_session.php");
/**
 * Paggina generar_envio.php Genera las Planillas de Envio
 * Se añadio compatibilidad con variables globales en Off
 * @autor Jairo Losada 2011-12
 * @licencia GNU/GPL V 3
 */

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$verrad         = "";
$krd            = $_SESSION["krd"];
$dependencia    = $_SESSION["dependencia"];
$usua_doc       = $_SESSION["usua_doc"];
$codusuario     = $_SESSION["codusuario"];
$codigo_envio = $tipo_envio;
include_once  "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler("..");
if (!defined('ADODB_FETCH_ASSOC'))  define('ADODB_FETCH_ASSOC', 1);
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if (!$fecha_busq) $fecha_busq = date("Y-m-d");

?>

<head>
  <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
  <script>
    function validar(action) {
      if (action != 2) {
        document.new_product.action = "generar_envio.php?<?= session_name() . "=" . session_id() . "&krd=$krd&fecha_h=$fechah" ?>&generar_listado_existente= Generar Plantilla existente ";
      } else {
        document.new_product.action = "generar_envio.php?<?= session_name() . "=" . session_id() . "&krd=$krd&fecha_h=$fechah" ?>&generar_listado= Generar Nuevo Envio ";
      }
      solonumeros();
    }

    function rightTrim(sString) {
      while (sString.substring(sString.length - 1, sString.length) == ' ') {
        sString = sString.substring(0, sString.length - 1);
      }
      return sString;
    }

    function solonumeros() {
      jh = document.getElementById('no_planilla');
      if (rightTrim(jh.value) == "" || isNaN(jh.value)) {
        alert('Solo introduzca numeros.');
        jh.value = "";
        jh.focus();
        return false;
      } else {
        document.new_product.submit();
      }
    }
  </script>
</head>

<body>
  <?
  //$db->conn->debug = true;
  ?>
  <div id="spiffycalendar" class="text"></div>
  <div class="container-fluid mt-4">
    <form name="new_product" class="smart-form"
      action='generar_envio.php?<?= session_name() . "=" . session_id() . "&krd=$krd&fecha_h=$fechah" ?>'
      method="post">

      <div class="card shadow-lg border-0">
        <div class="card-header bg-orfeo text-white d-flex align-items-center gap-2">
          <h3 class="fw-semibold">
            📦 Generación de Planillas y Guías de Correo
          </h3>
        </div>
        <div class="card-body">
          <table class="table table-borderless align-middle">
            <!-- Fecha -->
            <tr>
              <td class="fw-bold text-secondary" width="20%">
                Fecha <br>
                <small class="text-muted">(<?= date("Y-m-d") ?>)</small>
              </td>
              <td>
                <input id="fecha_busq" name="fecha_busq"
                  type="text"
                  class="form-control"
                  data-provide="datepicker"
                  data-date-format="YYYY/MM/DD"
                  data-date-end-date="0d"
                  value="<?= $fecha_busq; ?>">
              </td>
            </tr>
            <!-- Hora inicial -->
            <tr>
              <td class="fw-bold text-secondary">Desde la Hora</td>
              <td>

                <div class="d-flex gap-2">
                  <select name="hora_ini" class="form-select w-auto">
                    <?php
                    if (!$hora_ini) $hora_ini = 01;
                    if (!$minutos_ini) $minutos_ini = 01;

                    for ($i = 0; $i <= 23; $i++) {
                      $datoss = ($hora_ini == $i) ? "selected" : "";
                      echo "<option value='$i' $datoss>$i</option>";
                    }
                    ?>
                  </select>

                  <span class="mt-2">:</span>

                  <select name="minutos_ini" class="form-select w-auto">
                    <?php
                    for ($i = 0; $i <= 59; $i++) {
                      $datoss = ($minutos_ini == $i) ? "selected" : "";
                      echo "<option value='$i' $datoss>$i</option>";
                    }
                    ?>
                  </select>
                </div>

              </td>
            </tr>
            <!-- Hora final -->
            <tr>
              <td class="fw-bold text-secondary">Hasta</td>
              <td>

                <div class="d-flex gap-2">
                  <select name="hora_fin" class="form-select w-auto">
                    <?php
                    if (!$hora_fin) $hora_fin = date("H");
                    if (!$minutos_fin) $minutos_fin = date("i");

                    for ($i = 0; $i <= 23; $i++) {
                      $datoss = ($hora_fin == $i) ? "selected" : "";
                      echo "<option value='$i' $datoss>$i</option>";
                    }
                    ?>
                  </select>

                  <span class="mt-2">:</span>

                  <select name="minutos_fin" class="form-select w-auto">
                    <?php
                    for ($i = 0; $i <= 59; $i++) {
                      $datoss = ($minutos_fin == $i) ? "selected" : "";
                      echo "<option value='$i' $datoss>$i</option>";
                    }
                    ?>
                  </select>
                </div>

              </td>
            </tr>
            <!-- Tipo de salida -->
            <tr>
              <td class="fw-bold text-secondary">Tipo de Salida</td>
              <td>
                <div class="w-50">
                  <?php
                  $iSql = "select sgd_fenv_descrip,sgd_fenv_codigo from  sgd_fenv_frmenvio
                        where sgd_fenv_estado = 1
                        order by sgd_fenv_descrip";

                  $rs = $db->conn->query($iSql);

                  print $rs->GetMenu2(
                    "tipo_envio",
                    $tipo_envio,
                    "0:-- Seleccione --",
                    false,
                    "",
                    " class='form-select' onChange='submit();'"
                  );

                  $codigo_envio = $tipo_envio;
                  ?>
                </div>
              </td>
            </tr>
            <!-- Número de planilla -->
            <tr>
              <td class="fw-bold text-secondary">Número de Planilla</td>
              <td>
                <div class="d-flex align-items-center gap-3 w-50">
                  <input type="text"
                    name="no_planilla"
                    id="no_planilla"
                    value="<?= $no_planilla ?>"
                    class="form-control"
                    maxlength="9">

                  <?php
                  $fecha_mes = substr($fecha_busq, 0, 4);
                  $sqlChar = $db->conn->SQLDate("Y", "SGD_RENV_FECH");

                  $query = "SELECT sgd_renv_planilla, sgd_renv_fech 
                          FROM sgd_renv_regenvio
                          WHERE DEPE_CODI=$dependencia 
                          AND $sqlChar = '$fecha_mes'
                          AND sgd_fenv_codigo = $tipo_envio
                          ORDER BY sgd_renv_regenvio desc";

                  $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
                  $rs = $db->conn->query($query);

                  if ($rs) {
                    $planilla_ant = $rs->fields["SGD_RENV_PLANILLA"];
                    $fecha_planilla_ant = $rs->fields["SGD_RENV_FECH"];
                  }
                  ?>
                </div>

                <?php if ($codigo_envio && $planilla_ant): ?>
                  <small class="text-muted">
                    Última planilla generada:
                    <strong><?= $planilla_ant ?></strong>
                    (<?= $fecha_planilla_ant ?>)
                  </small>
                <?php endif; ?>
              </td>
            </tr>
            <!-- Botones -->
            <tr>
              <td colspan="2" class="pt-4">
                <div class="d-flex justify-content-center gap-3">
                  <input type="button"
                    name="generar_listado_existente"
                    value="Generar Plantilla Existente"
                    class="btn btn-outline-primary px-4 py-2"
                    onClick="validar(1);">

                  <input type="button"
                    name="generar_listado"
                    value="Generar Nuevo Envío"
                    class="btn btn-primary px-4 py-2"
                    onClick="validar(2);">
                </div>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </form>

    <div style="overflow-x:auto;" class="mt-4 margin-botton-table">
      <?php

      if (!$fecha_busq) $fecha_busq = date("Y-m-d");
      if ($generar_listado or $generar_listado_existente) {
        $no_planilla_Inicial = $no_planilla;
        if ($tipo_envio != 111111) {
          error_reporting(7);
          if ($generar_listado_existente) {
            $generar_listado = "Genzzz";
            echo "<table  width='100%'><tr><td class=listado2><CENTER>Generar Listado Existente</td></tr></table>";
          }
          include "./listado_planillas.php";
          echo "<table  width='100%'><tr><td class=titulos2><CENTER>FECHA DE BUSQUEDA $fecha_busq</cebter></td></tr></table>";
        }
        if ($tipo_envio == 2222) {

          include "./listado_guias.php";
          echo "<table  width='100%'><tr><td class=listado2><CENTER>FECHA DE BUSQUEDA $fecha_busq </center></td></tr></table>";
        }
        if ($tipo_envio == 1108) {

          echo "<table  width='100%'><tr><td class=titulos2><CENTER>PLANILLA NORMAL</center></td></tr></table>";
          if ($generar_listado_existente)  $generar_listado = "Genzzz";
          include "./listado_planillas_normal.php";
          echo "<table  width='100%'><tr><td class=titulos2><CENTER>FECHA DE BUSQUEDA $fecha_busq </center></td></tr></table>";
        }
        if ($tipo_envio == 1109) {

          echo "<table  width='100%'><tr><td class=titulos2><CENTER>PLANILLA ACUSE DE RECIBO</center></td></tr></table>";
          if ($generar_listado_existente)  $generar_listado = "Genzzz";
          include "./lPlanillaAcuseR.php";
          echo "<table  width='100%'><tr><td class=listado2><CENTER>FECHA DE BUSQUEDA $fecha_busq </td></tr></table>";
        }
        include "./generar_planos.php";
      }
      ?>
    </div>
  </div>

  <script>
    <?php
    if (!$fecha_busq) $fecha_busq = date("Y-m-d");

    ?>
    $('#fecha_busq').datepicker({
      dateFormat: 'yy-mm-dd',
      format: {
        /*
         * Say our UI should display a week ahead,
         * but textbox should store the actual date.
         * This is useful if we need UI to select local dates,
         * but store in UTC
         */
        toDisplay: function(date, format, language) {
          var d = new Date('<?= $fecha_busq ?>');
          d.setDate(d.getDate() - 7);
          return d.toISOString();
        },
        toValue: function(date, format, language) {
          var d = new Date('<?= $fecha_busq ?>');
          d.setDate(d.getDate() + 7);
          return new Date(d);
        }
      },
      autoclose: true
    });
    $("#fecha_busq").datepicker("setDate", "<?= $fecha_busq ?>");
  </script>
</body>
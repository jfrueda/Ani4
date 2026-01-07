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
(at your option) any later version.

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

define('ADODB_ASSOC_CASE', 1);
$verrad         = "";
$krd            = $_SESSION["krd"];
$dependencia    = $_SESSION["dependencia"];
$usua_doc       = $_SESSION["usua_doc"];
$codusuario     = $_SESSION["codusuario"];
$tip3Nombre     = $_SESSION["tip3Nombre"];
$tip3desc       = $_SESSION["tip3desc"];
$tip3img        = $_SESSION["tip3img"];
$descCarpetasGen = $_SESSION["descCarpetasGen"];
$descCarpetasPer = $_SESSION["descCarpetasPer"];

$_SESSION['numExpedienteSelected'] = null;

include_once("$ruta_raiz/include/db/ConnectionHandler.php");
if (!isset($db) || !$db) $db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
$sqlFecha = $db->conn->SQLDate("Y-m-d H:i A", "a.RADI_FECH_RADI");
if (strlen($orderNo) == 0) {
  $orderNo = "2";
  $order = 3;
} else {
  $order = $orderNo + 1;
}

if (trim($orderTipo) == "") $orderTipo = "DESC";
if (isset($orden_cambio) && $orden_cambio == 1) {
  if (trim($orderTipo) != "DESC") {
    $orderTipo = "DESC";
  } else {
    $orderTipo = "ASC";
  }
}

if (!$carpeta) $carpeta = 0;
if (!$nomcarpeta) $nomcarpeta = "Entrada";

$tipo_carp = !isset($tipo_carp) ? 0 : $tipo_carp;

/**
 * Este if verifica si se debe buscar en los radicados de todas las carpetas.
 * @$chkCarpeta char  Variable que indica si se busca en todas las carpetas.
 *
 */
if (isset($chkCarpeta) && $chkCarpeta) {
  $chkValue = " checked ";
  $whereCarpeta = " ";
} else {
  $chkValue = "";
  $whereCarpeta = " and b.carp_codi=$carpeta ";
  $whereCarpeta   = $whereCarpeta . " and b.carp_per=$tipo_carp ";
}


$fecha_hoy      = Date("Y-m-d");
$sqlFechaHoy    = $db->conn->DBDate($fecha_hoy);

//Filtra el query para documentos agendados
if (isset($agendado) && $agendado == 1) {
  $sqlAgendado = " and (radi_agend=1 and radi_fech_agend > $sqlFechaHoy) "; // No vencidos
} else  if (isset($agendado) && $agendado == 2) {
  $sqlAgendado = " and (radi_agend=1 and radi_fech_agend <= $sqlFechaHoy)  "; // vencidos
}

if (isset($agendado) && $agendado) {
  $colAgendado = "," . $db->conn->SQLDate("Y-m-d H:i A", "b.RADI_FECH_AGEND") . ' as "Fecha Agendado"';
  $whereCarpeta = "";
}

//Filtra teniendo en cienta que se trate de la carpeta Vb.
if ($carpeta == 11 && $codusuario != 1 && $_GET['tipo_carp'] != 1) {
  $whereUsuario = " and  b.radi_usu_ante ='$krd' ";
} else {
  $whereUsuario = " and b.radi_usua_actu='$codusuario' ";
}

$sqlNoRad = "
               select
                    b.carp_codi as carp, count(1) as COUNT
               from
                    radicado b left outer join SGD_TPR_TPDCUMENTO c on
                    b.tdoc_codi=c.sgd_tpr_codigo left outer join SGD_DIR_DRECCIONES d on
                    b.radi_nume_radi=d.radi_nume_radi
               where
                    b.is_borrador = false
                    and b.radi_nume_radi is not null
                    and d.sgd_dir_tipo = 1
		                and b.radi_depe_actu= $dependencia
                    $whereUsuario
                    GROUP BY  b.carp_codi";

$rs          = $db->conn->Execute($sqlNoRad);


$numRad = isset($numRad) ? $numRad : '';
$totrad = isset($totrad) ? $totrad : 0;
while (!$rs->EOF) {
  $numRad    .= empty($numRad) ? $rs->fields["COUNT"] : ", " . $rs->fields["COUNT"];
  $totrad    += $rs->fields["COUNT"];
  $rs->MoveNext();
}

$sqlTotalRad = "select count(1) as TOTAL
                  from  radicado b left outer join SGD_TPR_TPDCUMENTO c on
                        b.tdoc_codi=c.sgd_tpr_codigo left outer join SGD_DIR_DRECCIONES d on b.radi_nume_radi=d.radi_nume_radi
                  where
                        b.radi_nume_radi is not null
                        and d.sgd_dir_tipo = 1";

//$rs          = $db->conn->Execute($sqlTotalRad);
$numTotal      = $rs->fields["TOTAL"] ?? '';

?>
<html>

<head>
  <title>Sistema de informaci&oacute;n integrado de Metrovivienda</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="SIIM2">
  <meta name="keywords" content="siim, metrovivienda, gestion, misional">
  <link rel="shortcut icon" href="<?= $ruta_raiz ?>/img/favicon.png">
  <!-- Bootstrap core CSS -->
  <?php include_once "htmlheader.inc.php"; ?>
</head>

<body>
  <form name="form1" id="form1" action="./tx/formEnvio.php?<?= $encabezado ?>" method="post">
    <div id="content" class="container-fluid py-3">
      <!-- Mensajes dinámicos -->
      <div id="informarUsuario" class="row"></div>
      <!-- Campo oculto -->
      <input name="codTx" id="codTx" type="hidden">
      <!-- Encabezado -->
      <div class="row align-items-center mb-4">
        <div class="col-12 col-md-6">
          <h1 class="h4 text-primary d-flex align-items-center gap-2">
            <i class="bi bi-inbox-fill fs-4"></i>
            Bandeja <span class="fw-semibold"><?= $nomcarpeta ?></span>
          </h1>
        </div>

        <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
          <div class="d-inline-flex flex-column align-items-end p-2 border rounded bg-light shadow-sm">
            <span class="fw-semibold">Radicados:</span>
            <span class="text-primary fw-bold fs-5"><?= $totrad ?></span>

            <!-- Sparkline -->
            <div class="small text-muted">
              <?= $numRad ?>
            </div>
          </div>
        </div>
      </div>

      <!-- widget grid -->
      <section id="widget-grid" class="">
        <!-- row -->
        <div class="row">
          <!-- NEW WIDGET START -->
          <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
              <header class="bg-orfeo"> </header>
              <!-- widget div-->
              <div>
                <!-- ui-dialog -->
                <div id="dialog-message" title="Dialog Simple Title">
                  <div class="form-group smart-form" id="">
                    <?php
                    $norandom = "file1";
                    echo ("<div $addAttr id='$norandom'>A&ntilde;adir Archivos  <input  type='HIDDEN' value='' id='inp_$norandom'/>");
                    $scriptJS .= "
                                  var rad=($(\"#dt_basic input:checked\").attr('id'));
                                  $norandom = 'file1';
                                  var anexTecCCU=$('#$norandom').uploadFile({
                                  url:'$ruta_raiz/tx/anexarAjaxServer.php?tx=2&numrad='+rad,
                                  fileName: 'fileFormDinamic',
                                  autoSubmit:false,
                                  done:false,
                                  showDelete: true,
                                  multiple:false,
                                  id: '$norandom',
                                  showAbort:false,
                                  showDone:false,
                                  dragDrop: true,
                                  showFileCounter: false,
                                  onSuccess:function(files,data,xhr){
                                          document.getElementById('$norandom').style.display = 'none';
                                          document.getElementById('startUpload').style.display = 'none';
                                    $norandom = true;
                                          $.post(\"./tx/ajaxBorrarInformado.php\", {\"rads\":rad}).done(
                                      function( data ) {
                                        $('#informarUsuario').html(data);
                                        setTimeout(\"location.reload(true);\", 1000);
                                      }
                                    );

                                  },
                                  deleteCallback: function(data,pd){
                                    $norandom = false;
                                    document.getElementById('$norandom').style.display = 'block';
                                    pd.statusbar.hide();
                                  }
                                });
                                $(\"#startUpload\").click(function()
                                {
                                  anexTecCCU.startUpload();
                                });
                              ";
                    echo "</div>";
                    ?>
                  </div>
                </div>

                <script type="text/javascript">
                  pageSetUp();
                  var pagefunction = function() {
                    /**
                     * CONVERT DIALOG TITLE TO HTML
                     * REF: http://stackoverflow.com/questions/14488774/using-html-in-a-dialogs-title-in-jquery-ui-1-10
                     */
                    $.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
                      _title: function(title) {
                        if (!this.options.title) {
                          title.html("&#160;");
                        } else {
                          title.html(this.options.title);
                        }
                      }
                    }));

                    /**
                     * DIALOG HEADER ICON
                     */
                    $("#dialog-message").dialog({
                      autoOpen: false,
                      modal: true,
                      title: "<div class='widget-header'><h4><i class='icon-ok'></i>Anexos Tecnicos CCU</h4></div>",
                      buttons: [{
                        html: "Salir",
                        "class": "btn btn-default",
                        click: function() {
                          $(this).dialog("close");
                          setTimeout(function() {
                            window.location.reload(1);
                          });
                        }
                      }, {
                        html: "<i class='fa fa-check'></i>&nbsp; Anexar y Borrar",
                        "class": "btn btn-primary",
                        "id": "startUpload",
                        click: function() {}
                      }]

                    });
                  };
                  // end pagefunction
                  // run pagefunction on load
                  pagefunction();
                </script>

                <div class="card mb-3">
                  <div class="card-body py-3">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                      <div class="flex-grow-1" style="max-width: 260px;">
                        <label class="form-label fw-semibold">Acción</label>
                        <select id="AccionCaliope" name="AccionCaliope" class="form-select form-select-sm" aria-controls="dt_basic">
                          <option value="0" selected>Escoja una acción...</option>
                          <option value="8">Informar ...</option>
                          <option value="19">Borrar ...</option>
                        </select>
                      </div>

                      <!-- Select dinámico según condición PHP -->
                      <?php
                      $controlAgenda = 1;
                      if ($carpeta == 11 and !$tipo_carp and $codusuario != 1) {
                        // No mostrar nada
                      } else {
                      ?>
                        <div class="flex-grow-1" style="max-width: 260px;">
                          <label class="form-label fw-semibold">Opciones</label>
                          <div>
                            <?php include "./tx/txOrfeo.php"; ?>
                          </div>
                        </div>
                      <?php } ?>
                    </div>
                  </div>

                  <div class="table-responsive">
                    <table id="dt_basic" class="table table-striped table-hover align-middle mb-0">

                      <!-- Encabezado -->
                      <thead class="table-primary">
                        <tr>
                          <th style="width: 35px;">
                            <div class="form-check m-0">
                              <input class="form-check-input" type="checkbox" onclick="markAll();" name="checkAll" id="checkAll">
                            </div>
                          </th>
                          <th>Radicado</th>
                          <th>Fecha Radicado</th>
                          <th>Asunto</th>
                          <th>Remitente</th>
                          <th>Tipo Documento</th>
                          <th>Días Restantes</th>
                          <th>Enviado Por</th>
                        </tr>
                      </thead>

                      <!-- Cuerpo -->
                      <tbody>
                        <?php
                        include "$ruta_raiz/include/query/queryCuerpoinf.php";
                        $rs = $db->conn->Execute($isql);
                        $xsql = serialize($isql);
                        $contadorImagenes = 0;

                        while (!$rs->EOF) {
                          $numeroRadicado        = $rs->fields["HID_RADI_NUME_RADI"];
                          $fechaRadicado         = $rs->fields["DAT_FECHA RADICADO"];
                          $asuntoRadicado        = $rs->fields["ASUNTO"];
                          $remitenteRadicado     = $rs->fields["REMITENTE"];
                          $tipoDocumentoRadicado = $rs->fields["TIPO DOCUMENTO"];
                          $diasRadicado          = $rs->fields["DIAS RESTANTES"];
                          $enviadoPor            = $rs->fields["ENVIADO POR"];
                          $radiPath              = $rs->fields["HID_RADI_PATH"];
                          $radiLeido             = $rs->fields["HID_RADI_LEIDO"];

                          if (strpos($radiPath, "/") != 0) {
                            $radiPath = "/" . $radiPath;
                          }

                          $linkVerRadicado = "./verradicado.php?verrad=$numeroRadicado&" . session_name() . "=" . session_id() . "&nomcarpeta=$nomcarpeta#tabs-a";
                          $linkImagen = "$ruta_raiz/bodega" . $radiPath;
                          $contadorImagenes++;

                          $leido = (!$radiLeido) ? 'class="table-warning"' : '';
                        ?>

                          <tr <?= $leido ?>>
                            <!-- Checkbox de selección -->
                            <td>
                              <div class="form-check">
                                <input class="form-check-input"
                                  id="<?= $numeroRadicado ?>"
                                  onChange="clickTx();"
                                  name="checkValue[<?= $numeroRadicado ?>]"
                                  value="CHKANULAR"
                                  type="checkbox">
                              </div>
                            </td>

                            <!-- Radicado -->
                            <?php
                            if (empty($radiPath)) {
                              echo "
                                <td>
                                  <small>$numeroRadicado</small>
                                </td>";
                            } else {

                              echo "
                                <td>
                                  <small>
                                    <a href='javascript:void(0)' 
                                      class='abrirVisor text-decoration-none' 
                                      contador='$contadorImagenes' 
                                      link='$linkImagen'>
                                      $numeroRadicado
                                    </a>
                                  </small>
                                </td>";

                              // Modal Visor oculto
                              $visorId = "visor_" . $contadorImagenes;
                              echo "
                                <div id='$visorId' 
                                  style='display:none; 
                                    position:fixed;
                                    padding:26px 30px 30px;
                                    top:0; left:0; right:0; bottom:0;
                                    background: rgba(0,0,0,0.65);
                                    z-index:9999'>
                                  
                                  <button class='cerrarVisor btn btn-danger btn-sm' 
                                          type='button' 
                                          contador='$contadorImagenes'
                                          style='float:right;'>
                                      <b>X</b>
                                  </button>
                                  
                                </div>";
                            }
                            ?>

                            <!-- Fecha Radicado -->
                            <td>
                              <small><a href="<?= $linkVerRadicado ?>" target="mainFrame"><?= $fechaRadicado ?></a></small>
                            </td>

                            <!-- Asunto -->
                            <td><small><?= $asuntoRadicado ?></small></td>

                            <!-- Remitente -->
                            <td><small><?= $remitenteRadicado ?></small></td>

                            <!-- Tipo Documento -->
                            <td><small><?= $tipoDocumentoRadicado ?></small></td>

                            <!-- Días Restantes -->
                            <td><small><?= $diasRadicado ?></small></td>

                            <!-- Enviado Por -->
                            <td><small><?= $enviadoPor ?></small></td>
                          </tr>
                        <?php
                          $rs->MoveNext();
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>

                </div>
                <!-- end widget content -->
                <?php
                $_SESSION['xsql'] = $xsql;
                echo "<a style='border:0px' href='./adodb/adodb-doc.inc.php?" . session_name() . "=" . session_id() . "' target='_blank'><img src='./adodb/compfile.png' width='40' heigth='    40' border='0' ></a>";
                echo "<a href='./adodb/adodb-xls.inc.php?" . session_name() . "=" . session_id() . "' target='_blank'><img src='./adodb/spreadsheet.png' width='40' heigth='40' border='0'></a>";
                ?>
              </div>
            </div>
          </article>
        </div>
      </section>
      <!-- end widget grid -->
    </div>
  </form>

  <script type="text/javascript">
    $(document).ready(function() {
      $('.abrirVisor').off('click').on('click', function() {
        var contador = $(this).attr('contador');
        var link = $(this).attr('link');
        var visorId = "#visor_" + contador;
        var iframe = $(visorId).find('iframe');
        if (iframe.length > 0) {
          iframe.attr('src', link);
        } else {
          $(visorId).append("<iframe style='width:100%; height:100%; z-index:-2;' src=" + link + "></iframe>");
        }
        $(visorId).dialog();
      });

      $('.cerrarVisor').off('click').on('click', function() {
        var visorId = "#visor_" + $(this).attr('contador');
        $(visorId).dialog('destroy');
      });
    });

    // DO NOT REMOVE : GLOBAL FUNCTIONS!
    pageSetUp();

    // PAGE RELATED SCRIPTS

    loadDataTableScripts();

    function loadDataTableScripts() {

      loadScript("js/plugin/datatables/jquery.dataTables-cust.min.js", dt_2);

      function dt_2() {
        loadScript("js/plugin/datatables/ColReorder.min.js", dt_3);
      }

      function dt_3() {
        loadScript("js/plugin/datatables/FixedColumns.min.js", dt_4);
      }

      function dt_4() {
        loadScript("js/plugin/datatables/ColVis.min.js", dt_5);
      }

      function dt_5() {
        loadScript("js/plugin/datatables/ZeroClipboard.js", dt_6);
      }

      function dt_6() {
        loadScript("js/plugin/datatables/media/js/TableTools.min.js", dt_7);
      }

      function dt_7() {
        loadScript("js/plugin/datatables/DT_bootstrap.js", runDataTables);
      }

    }

    function runDataTables() {
      /*
       * BASIC
       */
      $('#dt_basic').dataTable({
        "sPaginationType": "bootstrap_full",
        "iDisplayLength": 25
      });

      /* END BASIC */

      /* Add the events etc before DataTables hides a column */
      $("#datatable_fixed_column thead input").keyup(function() {
        oTable.fnFilter(this.value, oTable.oApi._fnVisibleToColumnIndex(oTable.fnSettings(), $("thead input").index(this)));
      });

      $("#datatable_fixed_column thead input").each(function(i) {
        this.initVal = this.value;
      });

      $("#datatable_fixed_column thead input").focus(function() {
        if (this.className == "search_init") {
          this.className = "";
          this.value = "";
        }
      });

      $("#datatable_fixed_column thead input").blur(function(i) {
        if (this.value == "") {
          this.className = "search_init";
          this.value = this.initVal;
        }
      });

      var oTable = $('#datatable_fixed_column').dataTable({
        "sDom": "<'dt-top-row'><'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
        //"sDom" : "t<'row dt-wrapper'<'col-sm-6'i><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'>>",
        "oLanguage": {
          "sSearch": "Search all columns:"
        },
        "bSortCellsTop": true
      });

      /*
       * COL ORDER
       */
      $('#datatable_col_reorder').dataTable({
        "sPaginationType": "bootstrap",
        "sDom": "R<'dt-top-row'Clf>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
        "fnInitComplete": function(oSettings, json) {
          $('.ColVis_Button').addClass('btn btn-default btn-sm').html('Columns <i class="icon-arrow-down"></i>');
        }
      });

      /* END COL ORDER */

      /* TABLE TOOLS */
      $('#datatable_tabletools').dataTable({
        "sDom": "<'dt-top-row'Tlf>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
        "oTableTools": {
          "aButtons": ["copy", "print", {
            "sExtends": "collection",
            "sButtonText": 'Save <span class="caret" />',
            "aButtons": ["csv", "xls", "pdf"]
          }],
          "sSwfPath": "js/plugin/datatables/media/swf/copy_csv_xls_pdf.swf"
        },
        "fnInitComplete": function(oSettings, json) {
          $(this).closest('#dt_table_tools_wrapper').find('.DTTT.btn-group').addClass('table_tools_group').children('a.btn').each(function() {
            $(this).addClass('btn-sm btn-default');
          });
        }
      });
      /* END TABLE TOOLS */
    }
  </script>
</body>

</html>
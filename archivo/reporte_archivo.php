<?php

/********************************************************************************/
/*DESCRIPCION: Reporte que muestra los radicados archivados                     */
/*FECHA: 15 Diciembre de 2006*/
/*MODIFICADO: 15 Noviembre del 2008*/
/*AUTOR: Supersolidaria*/
/*MODIFICADO: Mario Manotas Duran*/
/*********************************************************************************/
?>
<?php
$per = 1;
session_start();
foreach ($_GET as $key => $valor) {
  ${$key} = $valor;
}

foreach ($_POST as $key => $valor) {
  ${$key} = $valor;
}

if (!$krd) {
  $krd = $krdOld;
}

if (!$ruta_raiz) {
  $ruta_raiz = "..";
}

include "$ruta_raiz/rec_session.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once "$ruta_raiz/htmlheader.inc.php";

$db = new ConnectionHandler("$ruta_raiz");
//$db->conn->debug=true;
if (trim($orderTipo) == "") {
  $orderTipo = "DESC";
}

if ($orden_cambio == 1) {
  if (trim($orderTipo) != "DESC") {
    $orderTipo = "DESC";
  } else {
    $orderTipo = "ASC";
  }
}

if (strlen($orderNo) == 0) {
  $orderNo = "2";
  $order = 3;
} else {
  $order = $orderNo + 1;
}

$encabezado = "" . session_name() . "=" . session_id() . "&krd=$krd&dep_sel=$dep_sel&codigoUsuario=$codigoUsuario&filtroSelect=$filtroSelect&tpAnulacion=$tpAnulacion&carpeta=$carpeta&tipo_carp=$tipo_carp&chkCarpeta=$chkCarpeta&busqRadicados=$busqRadicados&nomcarpeta=$nomcarpeta&agendado=$agendado&";
$linkPagina = "$PHP_SELF?$encabezado&orderTipo=$orderTipo&orderNo=$orderNo";

if ($_GET['fechaIniSel'] == "" && $_GET['fechaInifSel'] == "") {
  $encabezado = "" . session_name() . "=" . session_id() . "&fechaIniSel=" . $_POST['fechaIni'] . "&fechaInifSel=" . $_POST['fechaInif'] . "&adodb_next_page=1&krd=$krd&depeBuscada=$dep_sel&codigoUsuario=$codigoUsuario&filtroSelect=$filtroSelect&tpAnulacion=$tpAnulacion&carpeta=$carpeta&tipo_carp=$tipo_carp&nomcarpeta=$nomcarpeta&agendado=$agendado&orderTipo=$orderTipo&orderNo=";
} else {
  $encabezado = "" . session_name() . "=" . session_id() . "&fechaIniSel=" . $_GET['fechaIniSel'] . "&fechaInifSel=" . $_GET['fechaInifSel'] . "&adodb_next_page=1&krd=$krd&depeBuscada=$dep_sel&codigoUsuario=$codigoUsuario&filtroSelect=$filtroSelect&tpAnulacion=$tpAnulacion&carpeta=$carpeta&tipo_carp=$tipo_carp&nomcarpeta=$nomcarpeta&agendado=$agendado&orderTipo=$orderTipo&orderNo=";
}
?>
<html>

<head>
  <title>REPORTE DE RADICADOS ARCHIVADOS</title>
  <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
  <link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
  <style>
    #spiffycalendar {
      z-index: 1;
    }
  </style>
  <script language="JavaScript" src="../js/spiffyCal/spiffyCal_v2_1.js"> </script>
</head>

<body bgcolor="#FFFFFF">
  <div id="spiffycalendar" class="text"></div>

  <form name="reporte_archivo" action='' method="post">
    <div class="container my-4">
      <div class="card shadow-sm">
        <div class="card-header bg-orfeo text-white">
          <h5 class="mb-0 text-center">
            REPORTE DE RADICADOS ARCHIVADOS
          </h5>
        </div>

        <div class="card-body">
          <form method="post" name="reporte_archivo">
            <div class="row mb-3 align-items-center">
              <div class="col-md-4 text-end fw-semibold">
                Dependencia que Archiva
              </div>
              <div class="col-md-8">
                <?php
                if (!$dep_sel) $dep_sel = 0;
                $fechah = date("dmy") . " " . time("h_m_s");
                $fecha_hoy = Date("Y-m-d");
                $sqlFechaHoy = $db->conn->DBDate($fecha_hoy);
                $check = 1;
                $fechaf = date("dmy") . "_" . time("hms");
                $numeroa = 0;
                $numero = 0;
                $numeros = 0;
                $numerot = 0;
                $numerop = 0;
                $numeroh = 0;

                include "$ruta_raiz/include/query/expediente/queryReporte.php";

                $queryUs = "select depe_codi from usuario where USUA_LOGIN='$krd' AND cast(USUA_ESTA as numeric)=1";
                $rsUs = $db->query($queryUs);
                $depe = $rsUs->fields["DEPE_CODI"];

                if ($dependencia_busq != 99999) {
                  $whereDependencia = " AND DEPE_CODI=$depe";
                }
                ?>

                <?php
                error_reporting(0);
                $query2 = "SELECT DISTINCT D.DEPE_NOMB, D.DEPE_CODI FROM DEPENDENCIA D, USUARIO U WHERE D.DEPE_CODI=U.DEPE_CODI AND U.USUA_ADMIN_ARCHIVO >= 1 ORDER BY D.DEPE_NOMB";
                $rs1 = $db->conn->query($query2);
                print $rs1->GetMenu2(
                  'dep_sel',
                  $_POST['dep_sel'],
                  "0:--- Seleccione---",
                  false,
                  "",
                  "class='form-select' onChange='submit();'"
                );
                ?>
              </div>
            </div>

            <div class="row mb-3 align-items-center">
              <div class="col-md-4 text-end fw-semibold">
                Tipo de Radicado
              </div>
              <div class="col-md-8">
                <?php
                $sql = "select sgd_trad_descr,sgd_trad_codigo from sgd_trad_tiporad order by sgd_trad_codigo";
                $rs = $db->query($sql);
                print $rs->GetMenu2(
                  "trad",
                  $_POST['trad'],
                  "0:-- Seleccione --",
                  false,
                  "",
                  "class='form-select'"
                );
                ?>
              </div>
            </div>

            <div class="row mb-3 align-items-center">
              <div class="col-md-4 text-end fw-semibold">
                Usuario que Archiva
              </div>
              <div class="col-md-8">
                <select name="codigoUsuario" class="form-select" onChange="formulario.submit();">
                  <option value="0">-- AGRUPAR POR TODOS LOS USUARIOS --</option>
                  <?php
                  $whereUsSelect = "";

                  if (!$usActivos) {
                    $whereUsua = " u.USUA_ESTA = '1' ";
                    $whereUsSelect = " AND u.USUA_ESTA = '1' ";
                    $whereActivos = " AND b.USUA_ESTA='1'";
                  }

                  if ($_POST['dep_sel'] != "") {
                    $isqlus = "SELECT u.USUA_NOMB,u.USUA_CODI FROM USUARIO u, DEPENDENCIA D 
                                           WHERE u.USUA_ESTA = '1' 
                                           AND D.DEPE_CODI=U.DEPE_CODI 
                                           AND U.DEPE_CODI='" . $_POST['dep_sel'] . "' 
                                           ORDER BY u.USUA_NOMB";

                    $rs1 = $db->query($isqlus);

                    while (!$rs1->EOF) {
                      $selected = ($_POST['codigoUsuario'] == $rs1->fields["USUA_CODI"]) ? "selected" : "";

                      echo "<option value='{$rs1->fields["USUA_CODI"]}' $selected>
                                            {$rs1->fields["USUA_NOMB"]}
                                          </option>";
                      $rs1->MoveNext();
                    }
                  }
                  ?>
                </select>
              </div>
            </div>

            <?php
            /**
             * Modificado Supersolidaria 05-Dic-2006ivados Ha
             * El rango inicial de fechas se estableci� en 1 mes.
             */

            // Fecha inicial
            if ($_GET['fechaIniSel'] == "" && $_POST['fechaIni'] == "") {
              $fechaIni = date('Y-m-d', strtotime("-1 month"));
            } elseif ($_POST['fechaIni'] != "") {
              $fechaIni = $_POST['fechaIni'];
            } elseif ($_GET['fechaIniSel'] != "") {
              $fechaIni = $_GET['fechaIniSel'];
            }
            // Fecha final
            if ($_GET['fechaInifSel'] == "" && $_POST['fechaInif'] == "") {
              $fechaInif = date('Y-m-d');
            } elseif ($_POST['fechaInif'] != "") {
              $fechaInif = $_POST['fechaInif'];
            } elseif ($_GET['fechaInifSel'] != "") {
              $fechaInif = $_GET['fechaInifSel'];
            }
            ?>

            <div class="row mb-3 align-items-center">
              <div class="col-md-4 text-end fw-semibold">
                Fecha Archivados Desde
              </div>
              <div class="col-md-8">
                <script>
                  var dateAvailable1 = new ctlSpiffyCalendarBox(
                    "dateAvailable1",
                    "reporte_archivo",
                    "fechaIni",
                    "btnDate1",
                    "<?= $fechaIni ?>",
                    scBTNMODE_CUSTOMBLUE
                  );
                  dateAvailable1.dateFormat = "yyyy-MM-dd";
                  dateAvailable1.writeControl();
                </script>
              </div>
            </div>

            <div class="row mb-4 align-items-center">
              <div class="col-md-4 text-end fw-semibold">
                Fecha Archivados Hasta
              </div>
              <div class="col-md-8">
                <script>
                  var dateAvailable2 = new ctlSpiffyCalendarBox(
                    "dateAvailable2",
                    "reporte_archivo",
                    "fechaInif",
                    "btnDate2",
                    "<?= $fechaInif ?>",
                    scBTNMODE_CUSTOMBLUE
                  );
                  dateAvailable2.dateFormat = "yyyy-MM-dd";
                  dateAvailable2.writeControl();
                </script>
              </div>
            </div>

            <div class="d-flex justify-content-center gap-3">
              <input type="submit" class="btn btn-primary px-4" value="Buscar" name="Buscar">
              <input type="button" class="btn btn-outline-secondary px-4" value="Cancelar" name="Cancelar" onClick="javascript:history.back()">
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <p>&nbsp;</p>
    <?
    $Buscar = $_POST['Buscar'];
    if ($Buscar) {
      include_once "$ruta_raiz/include/query/archivo/queryReportePorRadicados.php";
      if ($genDetalle == 1) {
        $queryUs = $queryEDetalle;
      }
      if ($genTodosDetalle == 1) {
        $queryUs = $queryETodosDetalle;
      }
      $reporte = 1;
      $rsE = $db->conn->Execute($queryUs);
      include "../archivo/tablaHtml.php";
    }
    $db->conn->Close();
    ?>
    <p>&nbsp;</p>
  </form>

</html>
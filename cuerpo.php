<?php


/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Augusto   <aurigadl@gmail.com>
 * @author Correlibre.org // Tomado de version orginal realizada por JL en SSPD, modificado.
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 *
 * @copyleft

OrfeoGpl Models are the data definition of OrfeoGpl Information System
Copyright (C) 2013 Infometrika Ltda.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Fou@copyrightndation, either version 3 of the License, or
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
include_once $ruta_raiz . "/include/tx/sanitize.php";
//require $ruta_raiz."/vendor/autoload.php";
if ($_REQUEST['radicado_a_buscar']) {
    $radicados_a_buscar = $_REQUEST['radicado_a_buscar'];
}

$ruta_raiz = ".";
if (!$_SESSION['dependencia'])
    header("Location: $ruta_raiz/cerrar_session.php");

foreach ($_REQUEST as $key => $valor)   ${$key} = $valor;
foreach ($_REQUEST as $key => $valor)   ${$key} = $valor;

define('ADODB_ASSOC_CASE', 1);
define('CIRC_INTERNA', 4);
define('CIRC_EXTERNA', 5);

$verrad         = "";
$krd            = $_SESSION["krd"];
//$dependencia    = $_SESSION["dependencia"];
$dependencia    = $_SESSION["dependencia_aux"];
$usua_doc       = $_SESSION["usua_doc"];
$usua_email     = $_SESSION["usua_email"];
$codusuario     = $_SESSION["codusuario"];
$tip3Nombre     = $_SESSION["tip3Nombre"];
$tip3desc       = $_SESSION["tip3desc"];
$tip3img        = $_SESSION["tip3img"];
$descCarpetasGen = $_SESSION["descCarpetasGen"];
$descCarpetasPer = $_SESSION["descCarpetasPer"];
$verradPermisos = "Full"; //Variable necesaria en tx/txorfeo para mostrar dependencias en transacciones

$entidad = $_SESSION["entidad"];

$_SESSION['numExpedienteSelected'] = null;

include_once("$ruta_raiz/include/db/ConnectionHandler.php");
if (!$db) $db = new ConnectionHandler($ruta_raiz);
$db->conn->debug = false;
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
$sqlFecha = $db->conn->SQLDate("Y-m-d H:i A", "b.RADI_FECH_RADI");
$medios_recepcion = $db->conn->getAll('SELECT * FROM medio_recepcion ORDER BY MREC_CODI');
if (strlen($orderNo) == 0) {
    $orderNo = "2";
    $order = 3;
} else {
    $order = $orderNo + 1;
}

if (!empty($_REQUEST['fecha_inicial']) && !empty($_REQUEST['fecha_final'])) {
    $_SESSION['fecha_inicial'] = $_REQUEST['fecha_inicial'];
    $_SESSION['fecha_final'] = $_REQUEST['fecha_final'];
}

if (!empty($_REQUEST['medio_recepcion'])) {
    $_SESSION['medio_recepcion'] = $_REQUEST['medio_recepcion'];
}

if (!empty($_REQUEST['resultados_query_cuerpo'])) {
    $_SESSION['resultados'] = $_REQUEST['resultados_query_cuerpo'];
}

if (!empty($_REQUEST['fecha_final_b']) && !empty($_REQUEST['fecha_inicial_b'])  && $_REQUEST['fecha_inicial_b'] == "nA" && $_REQUEST['fecha_final_b'] == "nA") {
    $_SESSION['fecha_inicial'] = date('Y-m-d', strtotime('-6 month'));
    $_SESSION['fecha_final'] = date("Y-m-d");
}

if (empty($_SESSION['fecha_inicial']) && empty($_SESSION['fecha_final'])) {
    $_SESSION['fecha_inicial'] =  date('Y-m-d', strtotime('-3 month'));
    $_SESSION['fecha_final'] = date("Y-m-d");
}

if (empty($_SESSION['resultados'])) {
    $_SESSION['resultados'] = 1;
}

//Start::no restablecer filtro de resultados
/*
if (empty($_REQUEST['resultados_query_cuerpo'])){
    $_SESSION['resultados'] = 10;
}
*/
//End::no restablecer filtro de resultados


if (trim($orderTipo) == "") $orderTipo = " DESC ";

if ($orden_cambio == 1) {
    if (trim($orderTipo) != "DESC") {
        $orderTipo = "DESC";
    } else {
        $orderTipo = "ASC";
    }
}

if (!$carpeta) $carpeta = 9998;
if ($carpeta == 9998) $carpeta = 0;
if (!$nomcarpeta) $nomcarpeta = "Carpeta de Entrada";

if (!$tipo_carp) $tipo_carp = 0;

/**
 * Este if verifica si se debe buscar en los radicados de todas las carpetas.
 * @$chkCarpeta char  Variable que indica si se busca en todas las carpetas.
 *
 */
if ($chkCarpeta) {
    $chkValue = " checked ";
    $whereCarpeta = " ";
} else {
    $chkValue = "";
    if ($carpeta != 9999) {
        $whereCarpeta  = "and b.carp_codi=$carpeta  and b.carp_per=$tipo_carp ";
    }
}

$fecha_hoy      = Date("Y-m-d");
$sqlFechaHoy    = $db->conn->DBDate($fecha_hoy);

//Filtra el query para documentos agendados
if ($agendado == 1) {
    $sqlAgendado = " and (radi_agend=1 and radi_fech_agend > $sqlFechaHoy) "; // No vencidos
} else  if ($agendado == 2) {
    $sqlAgendado = " and (radi_agend=1 and radi_fech_agend <= $sqlFechaHoy)  "; // vencidos
}

if ($agendado) {
    $colAgendado = "," . $db->conn->SQLDate("Y-m-d H:i A", "b.RADI_FECH_AGEND") . ' as "Fecha Agendado"';
    $whereCarpeta = "";
}

//Filtra teniendo en cienta que se trate de la carpeta Vb.
if ($carpeta == 11 && $codusuario != 1 && $_REQUEST['tipo_carp'] != 1) {
    $whereUsuario = " and  (b.radi_usu_ante ='$krd' or b.radi_usua_actu='$codusuario') ";
} else {
    $whereUsuario = " and b.radi_usua_actu='$codusuario' ";
}


$sqlNoRad = "select
                        b.carp_codi as carp, count(1) as COUNT
                from
                        radicado b left outer join SGD_TPR_TPDCUMENTO c on
                        b.tdoc_codi=c.sgd_tpr_codigo left outer join SGD_DIR_DRECCIONES d on
                        b.radi_nume_radi=d.radi_nume_radi
                where
                        b.radi_nume_radi is not null
                        and d.sgd_dir_tipo = 1
                and b.radi_depe_actu= $dependencia
                        $whereUsuario
                        GROUP BY B.carp_codi";

$sqlTotalRad = "select count(1) as TOTAL
                  from  radicado b where  b.radi_depe_actu= $dependencia
                  $whereUsuario ";
?>
<html>

<head>
    <title>Sistema de informaci&oacute;n <?= $entidad_largo ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap core CSS -->
    <?php include_once "htmlheader.inc.php"; ?>
</head>

<style>
    .dt-wrapper {
        overflow: hidden;
        overflow: auto;
    }

    .enviossalida {
        background-color: #0000ffa1;
        border-radius: 20px;
        color: white;
        padding: 1px 2 0 2;
        font-size: 11px;
        position: relative;
        top: -4px;
        left: -4px;
    }

    .scrollable-div {
        max-height: 200px;
        overflow: auto;
    }
</style>

<body>
    <form method="get" id="borrar_borrador_exc">
        <input type="hidden" name="c" value="<?= empty($_REQUEST['c']) ? '' : $_REQUEST['c'] ?>">
        <input type="hidden" name="nomcarpeta" value="<?= empty($_REQUEST['nomcarpeta']) ? '' : $_REQUEST['nomcarpeta'] ?>">
        <input type="hidden" name="tipo_carpt" value="<?= empty($_REQUEST['tipo_carpt']) ? '' : $_REQUEST['tipo_carpt'] ?>">
        <input type="hidden" name="order" value="<?= empty($_REQUEST['order']) ? '' : $_REQUEST['order'] ?>">
        <input type="hidden" name="carpeta" value="<?= empty($_REQUEST['carpeta']) ? '' : $_REQUEST['carpeta'] ?>">
    </form>

    <form method="get" id="filtro_fechas">
        <input type="hidden" name="c" value="<?= empty($_REQUEST['c']) ? '' : $_REQUEST['c'] ?>">
        <input type="hidden" name="nomcarpeta" value="<?= empty($_REQUEST['nomcarpeta']) ? '' : $_REQUEST['nomcarpeta'] ?>">
        <input type="hidden" name="tipo_carpt" value="<?= empty($_REQUEST['tipo_carpt']) ? '' : $_REQUEST['tipo_carpt'] ?>">
        <input type="hidden" name="order" value="<?= empty($_REQUEST['order']) ? '' : $_REQUEST['order'] ?>">
        <input type="hidden" name="carpeta" value="<?= empty($_REQUEST['carpeta']) ? '' : $_REQUEST['carpeta'] ?>">
        <input type="hidden" name="radicado_a_buscar" value="<?= empty($_REQUEST['radicado_a_buscar']) ? '' : $_REQUEST['radicado_a_buscar'] ?>">
        <input type="hidden" name="fecha_inicial" id="fecha_inicial" value="<?= empty($_SESSION['fecha_inicial']) ? '' : $_SESSION['fecha_inicial'] ?>">
        <input type="hidden" name="fecha_final" id="fecha_final" value="<?= empty($_SESSION['fecha_final']) ? '' : $_SESSION['fecha_final'] ?>">
        <input type="hidden" name="medio_recepcion" id="medio_recepcion" value="<?= empty($_SESSION['medio_recepcion']) ? '' : $_SESSION['medio_recepcion'] ?>">
        <input type="hidden" name="resultados_query_cuerpo" id="resultados_query_cuerpo" value="<?= empty($_SESSION['resultados']) ? '' : $_SESSION['resultados'] ?>">
    </form>

    <form method="get" id="borrar_filtro_fechas">
        <input type="hidden" name="c" value="<?= empty($_REQUEST['c']) ? '' : $_REQUEST['c'] ?>">
        <input type="hidden" name="nomcarpeta" value="<?= empty($_REQUEST['nomcarpeta']) ? '' : $_REQUEST['nomcarpeta'] ?>">
        <input type="hidden" name="tipo_carpt" value="<?= empty($_REQUEST['tipo_carpt']) ? '' : $_REQUEST['tipo_carpt'] ?>">
        <input type="hidden" name="order" value="<?= empty($_REQUEST['order']) ? '' : $_REQUEST['order'] ?>">
        <input type="hidden" name="carpeta" value="<?= empty($_REQUEST['carpeta']) ? '' : $_REQUEST['carpeta'] ?>">
        <input type="hidden" name="radicado_a_buscar" value="<?= empty($_REQUEST['radicado_a_buscar']) ? '' : $_REQUEST['radicado_a_buscar'] ?>">
        <input type="hidden" name="fecha_inicial_b" id="fecha_inicial_b" value="nA">
        <input type="hidden" name="fecha_final_b" id="fecha_final_b" value="nA">
        <input type="hidden" name="medio_recepcion_b" id="medio_recepcion" value="">
    </form>

    <div id="app">
        <Transition name="slide-fade">
            <form v-if="showForm" name="form1" id="form1" action="./tx/formEnvio.php?<?= $encabezado ?>#informados" methos="post">
                <div id="content" class="card shadow">
                    <div class="card-header bg-orfeo text-white">
                        <h6 class="mb-0">
                            <i class="fa fa-inbox fs-4"></i>
                            Bandeja <span><?= $nomcarpeta ?></span> <span style="color:azure"> <?= $_SESSION['dependencia'] ?> <?= $_SESSION["codusuario"] ?></span>
                        </h6>
                    </div>

                    <!-- widget grid -->
                    <section id="widget-grid" class="">
                        <!-- row -->
                        <div class="row">
                            <!-- NEW WIDGET START -->
                            <article class="col-xs-12">
                                <!-- Widget ID (each widget will need unique ID)-->
                                <div class="jarviswidget jarviswidget-color-darken"
                                    id="wid-id-0"
                                    data-widget-editbutton="false">
                                    <!-- widget div-->
                                    <div>
                                        <!-- widget content -->
                                        <div class="actions smart-form" style="position: absolute !important; top: 147; z-index: 1; left: 28px;">
                                            <?php
                                            $controlAgenda = 1;
                                            if (($carpeta == 11 && !$_SESSION["USUA_JEFE_DE_GRUPO"]) && !$tipo_carp && $codusuario != 1) {
                                            } else { ?>
                                            <?php include "./tx/txOrfeo.php";
                                            }
                                            ?>
                                        </div>
                                        <div class="widget-body no-padding">
                                            <div class="widget-body-toolbar border rounded-4 p-4 bg-light mb-4">
                                                <h4 class="fw-bold mb-3">Filtrar por fechas</h4>
                                                <div class="row g-3 align-items-end">
                                                    <!-- Página -->
                                                    <div class="col-md-3">
                                                        <label for="resultados_aux" class="form-label fw-semibold">Página</label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" max="1000" min="0" value="<?= $_SESSION['resultados'] ?>" name="resultados_aux" id="resultados_aux">
                                                            <span class="input-group-text bg-white">
                                                                de&nbsp;<span id="total_bandeja" class="fw-bold"></span>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Fecha inicial -->
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold">Fecha inicial</label>
                                                        <input
                                                            type="date"
                                                            class="form-control"
                                                            name="fecha_inicial_aux"
                                                            id="fecha_inicial_aux"
                                                            value="<?= empty($_SESSION['fecha_inicial']) ? '' : $_SESSION['fecha_inicial'] ?>">
                                                    </div>

                                                    <!-- Fecha final -->
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold">Fecha final</label>
                                                        <input
                                                            type="date"
                                                            class="form-control"
                                                            name="fecha_final_aux"
                                                            id="fecha_final_aux"
                                                            value="<?= empty($_SESSION['fecha_final']) ? '' : $_SESSION['fecha_final'] ?>">
                                                    </div>

                                                    <!-- Medio de recepción -->
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold">Medio de recepción</label>
                                                        <select
                                                            class="form-select"
                                                            name="medio_recepcion_aux"
                                                            id="medio_recepcion_aux">
                                                            <option value="todos">Todos</option>
                                                            <?php foreach ($medios_recepcion as $medio): ?>
                                                                <option
                                                                    value="<?= $medio['MREC_CODI'] ?>"
                                                                    <?= $medio['MREC_CODI'] == $_SESSION['medio_recepcion'] ? "selected" : '' ?>>
                                                                    <?= $medio['MREC_DESC'] ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <!-- Loader -->
                                                    <div class="col-md-1 d-flex align-items-center mt-2">
                                                        <img
                                                            id="cargando_bandeja"
                                                            src="https://cdn.shortpixel.ai/client/q_glossy,ret_img,w_800,h_600/https://codigofuente.io/wp-content/uploads/2018/09/progress.gif"
                                                            width="40"
                                                            style="display: none;"
                                                            class="ms-2">
                                                    </div>

                                                    <!-- Botones -->
                                                    <div class="col-12 mt-3 text-end">
                                                        <input type="button" id="botongrande" value="Filtrar" class="btn btn-primary px-4">
                                                        <input type="button" id="botongrandeBorrar" value="Borrar" class="btn btn-outline-secondary px-4 ms-2">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Contenedor responsive para evitar overflow -->
                                            <div class="table-responsive margin-botton-table" style="max-height: 70vh; overflow-y: auto;">
                                                <table id="dt_basic" class="table table-hover table-striped table-bordered align-middle">
                                                    <thead class="text-center bg-orfeo">
                                                        <tr>
                                                            <th style="width: 40px;">
                                                                <div class="form-check form-switch d-flex justify-content-center">
                                                                    <input
                                                                        type="checkbox"
                                                                        class="form-check-input"
                                                                        onclick="markAll();"
                                                                        id="checkAll"
                                                                        name="checkAll"
                                                                        value="checkAll"
                                                                        style="cursor: pointer;">
                                                                </div>
                                                            </th>
                                                            <th>Radicado</th>
                                                            <th style="display:none;">Alerta</th>
                                                            <th>Fecha</th>
                                                            <th>Asunto</th>
                                                            <th>Remitente / Destinatario</th>
                                                            <th>Identificación</th>
                                                            <th>Expediente</th>
                                                            <th>Enviado Por</th>
                                                            <th>Tipo Documento</th>
                                                            <th>Días Restantes</th>
                                                            <th>Ref</th>
                                                            <th>Medio de Recepción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        include "$ruta_raiz/include/query/queryCuerpo.php";

                                                        $rs = $db->conn->Execute($isql);

                                                        if (!empty($isqlconteo)) {
                                                            $rs_conteo = $db->conn->Execute($isqlconteo);
                                                        }

                                                        include_once "$ruta_raiz/tx/diasHabiles.php";
                                                        $a = new FechaHabil($db);

                                                        $contadorImagenes = 0;
                                                        $aux = '';
                                                        while (!$rs->EOF) {
                                                            $numeroRadicado        = $rs->fields["HID_RADI_NUME_RADI"];
                                                            $fechaRadicado         = $rs->fields["HID_RADI_FECH_RADI"];
                                                            $refRadicado           = $rs->fields["REFERENCIA"];
                                                            $asuntoRadicado        = $rs->fields["ASUNTO"];
                                                            $remitenteRadicado     = $rs->fields["REMITENTE"];
                                                            $tipoDocumentoRadicado = $rs->fields["TIPO DOCUMENTO"];
                                                            $fech_vcmto            = $rs->fields["FECHA_VCMTO"];
                                                            $enviadoPor            = $rs->fields["ENVIADO POR"];
                                                            $radiPath              = $rs->fields["HID_RADI_PATH"];
                                                            $documentoUsuario      = $rs->fields["DOCUMENTO_USUARIO"];
                                                            $tipo_rad              = $rs->fields["TIPO_RAD"];
                                                            $mrec_desc             = $rs->fields["RADI_MREC_DESC"];
                                                            $usuaCodi              = $rs->fields['RADI_USUA_ACTU'];
                                                            $radi_depe_actu        = $rs->fields['RADI_DEPE_ACTU'];

                                                            if ($aux === $rs->fields["HID_RADI_NUME_RADI"])
                                                                
                                                            //  $radiLeido             = $rs->fields["HID_RADI_LEIDO"];
                                                            $radianulado       = $rs->fields["HID_EANU_CODIGO"];
                                                            //Datos obtenidos para pintar los radicados

                                                            //Start::expediente
                                                            $iSqlexpTot = "select * from sgd_exp_expediente where radi_nume_radi in ($numeroRadicado) limit 1;";
                                                            $rsiSqlexpTot = $db->conn->query($iSqlexpTot);
                                                            $numExpediente = $rsiSqlexpTot->fields["SGD_EXP_NUMERO"];
                                                            //End::expediente            

                                                            if (empty($remitenteRadicado) && ($tipo_rad == CIRC_INTERNA || $tipo_rad == CIRC_EXTERNA)) {
                                                                include_once("$ruta_raiz/include/tx/notificacion.php");
                                                                $notificacion = new Notificacion($db);
                                                                $destinatarios_circ = $notificacion->destinatariosPorRadicado($numeroRadicado);
                                                                $remitenteRadicado = $destinatarios_circ[0]["DESTINATARIOS"];
                                                            }

                                                            $anexEstado = $rs->fields["ANEX_ESTADO"];
                                                            $_radiLeido = $rs->fields["HID_RADI_LEIDO"];
                                                            //$numExpediente = $rs->fields["SGD_EXP_NUMERO"];
                                                            $diasRadicado = $a->getDiasRestantes($numeroRadicado, $fech_vcmto, $tipoDocumentoRadicado);

                                                            unset($TipoAlerta);
                                                            unset($ColorAlerta);
                                                            unset($MensajeAlerta);

                                                            unset($TipoAlerta2);
                                                            unset($ColorAlerta2);
                                                            unset($MensajeAlerta2);

                                                            /**************** Script que colorea los radicados nuevos, leidos , por vencer y vencidos *******************/

                                                            switch ($_radiLeido) {
                                                                case 0:
                                                                    $TipoAlerta = "class='fa fa-circle'";
                                                                    $ColorAlerta =  "style='color:#356635;cursor:help'";
                                                                    $ColorAlertaNoLeido =  "<b>";
                                                                    $MensajeAlerta = "Radicado Nuevo";

                                                                    break;
                                                                case 1:
                                                                    $TipoAlerta = "class='fa fa-circle'";
                                                                    $ColorAlerta =  "style='font-weight: bold; color:#3276B1;cursor:help'";
                                                                    $ColorAlertaleido =  "";
                                                                    $MensajeAlerta = "Leido";

                                                                    break;
                                                            }

                                                            //Debo calcular los dias del radicado antes
                                                            if ($diasRadicado != "") {
                                                                if ($diasRadicado == "-" || $diasRadicado == "N/A ó termino no definido") {
                                                                    #No se pintan.
                                                                } else {
                                                                    if ($diasRadicado <= 0) {
                                                                        $TipoAlerta2 = "class='fa fa-circle'";
                                                                        $ColorAlerta2 =  "style='color:#FE2E2E;cursor:help'";
                                                                        $MensajeAlerta2 = "Vencido";
                                                                    } else {
                                                                        if ($diasRadicado > 0 && $diasRadicado <= 3) {
                                                                            $TipoAlerta2 = "class='fa fa-circle'";
                                                                            $ColorAlerta2 =  "style='color:#8A2908;cursor:help'";
                                                                            $MensajeAlerta2 = "Por Vencer";
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            /*******************Script que colorea los radicados con anex_estado=4 (envíos)*******************/

                                                            unset($anexEstadoEstilo);
                                                            unset($anexEstadoEstiloLink);

                                                            switch ($anexEstado) {
                                                                case 3:
                                                                    $TipoAlerta = "class='fa fa-circle'";
                                                                    //$ColorAlerta =  "style='color:#FF8000;cursor:help'";
                                                                    $MensajeAlerta = "Marcado como Impreso";
                                                                    break;

                                                                case 4: //(envios)
                                                                    //@anexEstadoEstilo estilo para el <tr>
                                                                    //@anexEstadoEstiloLink estilo para enlaces <a>
                                                                    $anexEstadoEstilo = " style='color: #356635'";
                                                                    $anexEstadoEstiloLink = " style='color: #356635'";
                                                                    break;
                                                            }

                                                            if ($linkVerRadicado != '') {
                                                                // $anexEstado_linkradi = " style='text-decoration: underline'";
                                                            }

                                                            /****************Mostrar icono (folder) para radicados dentro de Expedientes****************************/

                                                            unset($radInExpStyle);

                                                            if (strlen($numExpediente) > 0) {

                                                                $radInExpStyle = "<img src='img/icon-folder-open-big.png' width=15 alt='Expediente : $numExpediente' title='Expedientes: $numExpediente'>";
                                                            }

                                                            /*******************************************************************************************************/

                                                            if (strpos($radiPath, "/") != 0) {
                                                                $radiPath = "/" . $radiPath;
                                                            }

                                                            $linkVerRadicado = "./verradicado.php?verrad=$numeroRadicado&depe_actu=$radi_depe_actu&usuacodi=$usuaCodi&tieneAsignacion=0";
                                                            $linkImagen = "$ruta_raiz/bodega" . $radiPath;
                                                            $contadorImagenes++;

                                                            unset($leido);
                                                            if ($_radiLeido == 0) {
                                                                $leido = "success";
                                                            }
                                                            unset($colorAnulado);
                                                            if ($radianulado == 2) {
                                                                $colorAnulado = " text-danger ";
                                                            }
                                                        ?>
                                                            <tr <?= $anexEstadoEstilo ?> class="<?= $leido ?> ">
                                                                <td class="inbox-table-icon sorting_1 ">
                                                                    <div>
                                                                        <label class="checkbox">
                                                                            <input id="<?= $numeroRadicado ?>" name="checkValue[<?= $numeroRadicado ?>]" value="CHKANULAR" type="checkbox">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                            <i></i>
                                                                            <?php
                                                                            $iSqlEstadoAnexos = null;
                                                                            $anex_estado = null;
                                                                            $envio_estado = null;
                                                                            $img_estado = null;
                                                                            $anex_estado = $rs->fields["ANEX_ESTADO"];
                                                                            $envio_estado = $rs->fields["SGD_DEVE_CODIGO"];

                                                                            if ($anex_estado == '4') {
                                                                                $img_estado = "<img src='./bodega/sys_img/enviado.png' width=15 title='Archivo Enviado. . .'>";
                                                                            }

                                                                            if ($envio_estado <> 0 && $anex_estado == '2') {
                                                                                $img_estado = "<img src='./bodega/sys_img/devuelto.png' width=15 title='Archivo devuelto. . .'>";
                                                                            }

                                                                            if ($envio_estado <> 0 && $anex_estado == '3') {
                                                                                $img_estado = "<img src='./bodega/sys_img/devuelto.png' width=15 title='Archivo devuelto. . .'>";
                                                                            }

                                                                            $ultimoDigito = str_split($numeroRadicado);
                                                                            if (end($ultimoDigito) == '2') {
                                                                                //Start::enviado en entradas
                                                                                $iSqlEntradaConteo = "
                                                                                        SELECT count(a.*) as TOTAL
                                                                                        FROM anexos a
                                                                                        WHERE 
                                                                                        anex_radi_nume =  '$numeroRadicado'
                                                                                        and a.radi_nume_salida::text like '%1'
                                                                                        and a.sgd_deve_codigo != 0 and  a.anex_estado in(2,3)";
                                                                                $rsEntradaConteo = $db->conn->query($iSqlEntradaConteo);

                                                                                //End::enviado en entradas
                                                                                //Start::enviado en entradas
                                                                                $iSqlEntradaConteoEnviados = "
                                                                                        SELECT count(a.*) as TOTAL
                                                                                        FROM anexos  a
                                                                                        WHERE
                                                                                        anex_radi_nume =  '$numeroRadicado'
                                                                                        and a.radi_nume_salida::text like '%1' 
                                                                                        and anex_estado = 4";
                                                                                $rsEntradaConteoEnviados = $db->conn->query($iSqlEntradaConteoEnviados);

                                                                                $iSqlEntradaConteoTotal = "
                                                                                            SELECT count(a.*) as TOTAL
                                                                                            FROM anexos  a
                                                                                            WHERE
                                                                                            anex_radi_nume =  '$numeroRadicado'
                                                                                            and a.radi_nume_salida::text like '%1' 
                                                                                            and anex_estado >= 2";
                                                                                $rsEntradaConteoTotal = $db->conn->query($iSqlEntradaConteoTotal);
                                                                                $img_estado = '';
                                                                                $img_estado .=  "<img src='./bodega/sys_img/enviado.png' width=15 title='Enviados . . .'> <span class='enviossalida' >" . $rsEntradaConteoEnviados->fields['TOTAL'] . "</span>";
                                                                                $img_estado .=  "<img src='./bodega/sys_img/devuelto.png' width=15 title='Devueltos . . .'> <span class='enviossalida' >" . $rsEntradaConteo->fields['TOTAL'] . "</span>";
                                                                                $img_estado .=  "<img src='./bodega/sys_img/bandejasalida.svg' width=15 title='Total salidas. . .'> <span class='enviossalida' >" . $rsEntradaConteoTotal->fields['TOTAL'] . "</span>";

                                                                                //End::enviado en entradas
                                                                            }
                                                                            ?>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <?php
                                                                $fechasymd = date('ymdhis');
                                                                if (!empty($radiPath)) {
                                                                    $extension = explode('.', $radiPath);
                                                                    if ($extension[1] == 'pdf') {
                                                                        //Muestra el archivo en una nueva pestanha sin usar el modal visor
                                                                        //echo "<td class='inbox-data-from'> <div><small> <a target='_blank' href='$linkImagen'>$numeroRadicado</a></small> </div></td>";

                                                                        //Muestra el pdf en el visor modal
                                                                        echo "<td class='inbox-data-from'> 
                                                                    <div><small> 
                                                                    <a href='javascript:void(0)' class='abrirVisor' contador=$contadorImagenes link=$linkImagen>$numeroRadicado
                                                                    </a>
                                                                    </small>$radInExpStyle</div>$img_estado
                                                                </td>";

                                                                        //Modal Visor
                                                                        $visorId = "visor_" . $contadorImagenes;
                                                                        echo "<div id=$visorId style='display:none; 
                                                                        position:fixed;
                                                                        padding:26px 30px 30px;
                                                                        top:0;
                                                                        left:0;
                                                                        right:0;
                                                                        bottom:0;
                                                                        z-index:2'>
                                                                        <button class='cerrarVisor' type='button' style='float:right; background-color:red;' contador=$contadorImagenes><b>x</b></button>  
                                                                        <!--iframe></iframe-->
                                                                        $img_estado
                                                                    </div>";
                                                                    } else {
                                                                        //Funcionalidad para descargar el archivo.
                                                                        echo "<td > <div > <small> <a $anexEstado_linkradi  $anexEstadoEstiloLink href='javascript:void(0)' onclick=\"funlinkArchivo('$numeroRadicado','$ruta_raiz');\">$numeroRadicado</a></small> $radInExpStyle</div> $img_estado</td>";
                                                                    }
                                                                } else {
                                                                    $incialRadicado = substr($numeroRadicado, 0, 4);
                                                                    if ($incialRadicado >= 3000) {
                                                                        $img_borrar_borrador = "<a class=\"btn btn-warning btn-xs\" onclick=\"eliminarBorrador('$numeroRadicado');\" title='Eliminar borrador'><i class=\"fa fa-times\"></i></a>";
                                                                    } else {
                                                                        $img_borrar_borrador = "";
                                                                    }
                                                                    echo "<td > <div > <small> $numeroRadicado</small> $radInExpStyle</div> $img_estado 
                                                                    $img_borrar_borrador
                                                            </td>";
                                                                }
                                                                ?>

                                                                <td align="center" style="display: none">
                                                                    <a <?= $ColorAlerta ?> title="<?= $MensajeAlerta ?>">
                                                                        <div <?= $TipoAlerta ?>></div>
                                                                    </a>
                                                                    <?php if ($MensajeAlerta2 != "") { ?> <a <?= $ColorAlerta2 ?> title="<?= $MensajeAlerta2 ?>">
                                                                            <div <?= $TipoAlerta2 ?>></div>
                                                                        </a> <?php } ?>
                                                                </td>

                                                                <td>
                                                                    <div><small><a title="click para ver radicado <?= $numeroRadicado ?>" <?= $anexEstadoEstiloLink ?> href="<?= $linkVerRadicado ?><?= empty($_REQUEST['nomcarpeta']) ? '' : '&nomcarpeta=' . $_REQUEST['nomcarpeta'] ?>" target="mainFrame"><?= $fechaRadicado ?></a></small></div>
                                                                </td>
                                                                <td width="250px">
                                                                    <div><span><small><?= $asuntoRadicado ?></small></span> </div>
                                                                </td>
                                                                <td>
                                                                    <div class="scrollable-div"> <small><?= $remitenteRadicado ?></small> </div>
                                                                </td>
                                                                <td>
                                                                    <div><span><small><?= $documentoUsuario ?></small></span> </div>
                                                                </td>
                                                                <td>
                                                                    <div><span><small><?= $numExpediente ?></small></span> </div>
                                                                </td>
                                                                <td>
                                                                    <div> <small><?= $enviadoPor ?></small> </div>
                                                                </td>
                                                                <td>
                                                                    <div> <small><?= $tipoDocumentoRadicado ?></small> </div>
                                                                </td>
                                                                <td>
                                                                    <div> <small><?= $diasRadicado ?></small> </div>
                                                                </td>
                                                                <td>
                                                                    <div><span><small><?= $refRadicado ?></small></span> </div>
                                                                </td>
                                                                <td>
                                                                    <div><span><small><?= $mrec_desc ?></small></span> </div>
                                                                </td>

                                                            </tr>
                                                        <?php
                                                            $aux = $rs->fields["HID_RADI_NUME_RADI"];
                                                            $rs->MoveNext();
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <?php

                                                if ((isset($krd) && $krd == "CONTACTCENTER") || (isset($dependencia) && $dependencia == '8010'))
                                                    $paginacion = 200;
                                                else
                                                    $paginacion = 100;

                                                if (!empty($rs_conteo->fields['COUNT']) && ($rs_conteo->fields['COUNT'] / $paginacion) > 1)
                                                    $conteo_paginas =  ceil($rs_conteo->fields['COUNT'] / $paginacion);
                                                else
                                                    $conteo_paginas = 1;

                                                ?>
                                                <script type="text/javascript">
                                                    document.getElementById("total_bandeja").textContent = "<?= $conteo_paginas ?>";
                                                </script>

                                                <?php
                                                $xsql = serialize($isql);
                                                $_SESSION['xsql'] = $xsql;
                                                echo "<a style='border:0px' href='./adodb/adodb-doc.inc.php?" . session_name() . "=" . session_id() . "' target='_blank'><img src='./adodb/compfile.png' width='40' heigth='    40' border='0' ></a>";
                                                echo "<a href='./adodb/adodb-xls.inc.php?" . session_name() . "=" . session_id() . "' target='_blank'><img src='./adodb/spreadsheet.png' width='40' heigth='40' border='0'></a>";
                                                ?>
                                            </div>
                                        </div>
                                        <!-- end widget content -->
                                    </div>
                                    <!-- end widget div -->
                                </div>
                                <!-- end widget -->
                            </article>
                        </div>
                        <!-- end row -->
                    </section>
                    <!-- end widget grid -->
                </div>
            </form>
        </Transition>
    </div>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {

            // Abrir visor
            document.querySelectorAll('.abrirVisor').forEach(btn => {
                btn.addEventListener('click', () => {

                    const contador = btn.getAttribute('contador');
                    const link = btn.getAttribute('link');
                    const visorId = "#visor_" + contador;

                    const visor = document.querySelector(visorId);

                    let iframe = visor.querySelector('iframe');

                    if (iframe) {
                        iframe.src = link;
                    } else {
                        visor.insertAdjacentHTML(
                            'beforeend',
                            `<iframe style="width:100%; height:100%; z-index:-2;" src="${link}"></iframe>`
                        );
                    }

                    // Usa jQuery UI, inevitable
                    $(visorId).dialog();
                });
            });

            // Cerrar visor
            document.querySelectorAll('.cerrarVisor').forEach(btn => {
                btn.addEventListener('click', () => {
                    const visorId = "#visor_" + btn.getAttribute('contador');
                    $(visorId).dialog("destroy");
                });
            });

            /* BOTÓN BORRAR */
            document.querySelector("#botongrandeBorrar")?.addEventListener("click", () => {
                document.querySelector("#botongrande").disabled = true;
                document.querySelector("#botongrandeBorrar").disabled = true;

                const loader = document.querySelector("#cargando_bandeja");
                loader.style.display = "initial";
                loader.style.display = "block";

                document.querySelector("#borrar_filtro_fechas").submit();
            });

            /* BOTÓN FILTRAR */
            document.querySelector("#botongrande")?.addEventListener("click", (e) => {

                e.target.disabled = true;
                document.querySelector("#botongrandeBorrar").disabled = true;

                const loader = document.querySelector("#cargando_bandeja");
                loader.style.display = "initial";
                loader.style.display = "block";

                document.querySelector("#fecha_inicial").value =
                    document.querySelector("#fecha_inicial_aux").value;

                document.querySelector("#fecha_final").value =
                    document.querySelector("#fecha_final_aux").value;

                document.querySelector("#medio_recepcion").value =
                    document.querySelector("#medio_recepcion_aux").value;

                document.querySelector("#resultados_query_cuerpo").value =
                    document.querySelector("#resultados_aux").value;

                document.querySelector("#filtro_fechas").submit();
            });

        });

        function eliminarBorrador(numbor) {
            if (confirm('Esta seguro que desea eliminar el borrador: ' + numbor)) {

                fetch("eliminarBorrador.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: new URLSearchParams({
                            funcion: '1',
                            numbor: numbor
                        })
                    })
                    .then(response => response.text())
                    .then(result => {
                        if (result == 200) {
                            document.querySelector("#borrar_borrador_exc").submit();
                        } else {
                            alert("Ha ocurrido un error, por favor comuníquese con el Administrador del sistema.");
                        }
                    })
                    .catch(() => {
                        alert("Ha ocurrido un error, por favor comuníquese con el Administrador del sistema.");
                    });
            }
        }

        // Muestra las imágenes de los radicados
        function funlinkArchivo(numrad, rutaRaiz) {
            const nombreventana = "linkVistArch";

            const url = `${rutaRaiz}/linkArchivo.php?<?php echo session_name() . "=" . session_id() ?>&numrad=${numrad}`;

            window.open(
                url,
                nombreventana,
                "scrollbars=1,height=50,width=250"
            );
        }

        // DO NOT REMOVE : GLOBAL FUNCTIONS!
        pageSetUp();

        // PAGE RELATED SCRIPTS

        loadDataTableScripts();

        function loadDataTableScripts() {

            loadScript("js/plugin/datatables/jquery.dataTables-cust.js", dt_2);

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
                //"sScrollX": "100%",
                //"bScrollCollapse": true,
                "bInfo": null,
                "aaSorting": [
                    [3, 'desc']
                ],
                "iDisplayLength": 27,
                "paging": false,
                "bPaginate": false,
                "aLengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"]
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "oLanguage": {
                    "oPaginate": {
                        "sPrevious": "Anterior",
                        "sNext": "Siguiente",
                        "sLast": "Ultima",
                        "sFirst": "Primera"
                    }
                }
            });

            /* END BASIC */

            const filterInputs = document.querySelectorAll("#datatable_fixed_column thead input");

            /* Add the events etc before DataTables hides a column */
            filterInputs.forEach((input, index) => {
                input.initVal = input.value;

                input.addEventListener("keyup", () => {
                    oTable.fnFilter(
                        input.value,
                        oTable.oApi._fnVisibleToColumnIndex(oTable.fnSettings(), index)
                    );
                });

                input.addEventListener("focus", () => {
                    if (input.classList.contains("search_init")) {
                        input.classList.remove("search_init");
                        input.value = "";
                    }
                });

                input.addEventListener("blur", () => {
                    if (input.value === "") {
                        input.classList.add("search_init");
                        input.value = input.initVal;
                    }
                });
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
                "aLengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"]
                ],
                "paging": false,
                "bPaginate": false,
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

            // Modal Link
            /* ACCIÓN MASIVA */
            const accion = document.querySelector("#AccionCaliope");

            if (accion) {
                accion.addEventListener("change", e => {

                    const value = e.target.value;

                    if (value == 21 || value == 20) {

                        let text = "";
                        document.querySelectorAll("input[name^='checkValue']:checked")
                            .forEach((chk, i) => {
                                text += (i === 0 ? "" : ",") + chk.id;
                            });

                        if (!text) return;

                        const div = document.createElement("div");

                        $(div).dialog({
                            modal: true,
                            open: function() {
                                if (value == 21) {
                                    $(this).load('accionesMasivas/masivaAsignarTrd.php?radicados=' + text);
                                }
                                if (value == 20) {
                                    $(this).load('accionesMasivas/masivaIncluirExp.php?radicados=' + text);
                                }
                            },
                            title: 'Acción Masiva',
                            width: "600px"
                        });
                    }
                });
            }
        }
    </script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script>
        const {
            createApp
        } = Vue;

        createApp({
            data() {
                return {
                    showForm: false
                };
            },
            mounted() {
                this.showForm = true
            }
        }).mount('#app');
    </script>
</body>

</html>
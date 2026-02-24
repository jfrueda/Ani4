<?php

/**
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Correlibre.org
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright
 *
 * OrfeoGpl Models are the data definition of OrfeoGpl Information System
 * Copyright (C) 2013 Infometrika Ltda.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();
define('SALIDA', 1);
define('ENTRADA', 2);
define('MEMORANDO', 3);
define('CIRC_INTERNA', 4);
define('CIRC_EXTERNA', 5);
define('RESOLUCION', 6);
define('AUTO', 7);
define('SIIM2_RECEPCION', 10);

//VARIABLE INCREMENTAL PARA CONTROLAR LOS CAMPOS DE LOS USUARIOS
unset($_SESSION['INCREMENTAL1']);
$_SESSION['INCREMENTAL1'] = 0;

$ruta_raiz = "..";
if (!$_SESSION['dependencia'])
    header("Location: $ruta_raiz/cerrar_session.php");

foreach ($_GET as $key => $valor) ${$key} = $valor;
foreach ($_POST as $key => $valor) ${$key} = $valor;

if (!empty($fecha_gen_doc)) {
    $fecha_gen_doc = date('Y-m-d', strtotime(str_replace('/', '-', $fecha_gen_doc)));
} else {
    $fecha_gen_doc = date('Y-m-d');
}

/**  Fin variables de session de Radicacion de Mail. **/
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
include_once("$ruta_raiz/include/tx/usuario.php");
include_once("$ruta_raiz/include/tx/notificacion.php");
include_once("$ruta_raiz/processConfig.php");

$db = new ConnectionHandler("$ruta_raiz");

$usuario = new Usuario($db);

$showtable = 'd-none';
$hidetable = '';
$showEntrada = '';
$modificar = 'd-none';

if ($Submit3 == "ModificarDocumentos") {
    $hidetable = 'd-none';
    $modificar = '';
}

$radMail = $_GET["radMail"];
$ddate = date('d');
$mdate = date('m');
$adate = date('Y');
$nurad = trim($nurad);
$hora = date('H:i:s');
$fechaf = $date . $mdate . $adate . $hora;
$dependencia = $_SESSION["dependencia"];
$ADODB_COUNTRECS = true;
$coddepe = $dependencia;
$codusua = $_SESSION["codusuario"];
//valor necesario para crear enlaces de los distintos elementos
//como el sticker
$idsession = session_id(); //valor necesario para crear enlaces

$_TIPO_INFORMADO = 1;
$_enable_1 = false;
$_enable_2 = true;
$_name_2 = "Entidad";
$_name_6 = "Funcionario";
$_name_4 = "Destinatarios Circular Interna";
$_name_5 = "Destinatarios Circular Externa";
$_show_type_doc = true;
$nivelSeguridadSeleccionado = isset($nivelSeguridad) ? $nivelSeguridad : null;
if (!$ent && $nurad) $ent = substr($nurad, -1);
//Mostrar el tipo de radicacion que se esta realizando
$selTipoRad = "select
                  sgd_trad_codigo,
                  sgd_trad_descr,
                  sgd_trad_icono,
                  sgd_trad_genradsal
                from
                  sgd_trad_tiporad
                where sgd_trad_codigo = $ent";

$rs = $db->conn->query($selTipoRad);

if (!$rs->EOF) {
    $nomEntidad = $rs->fields["SGD_TRAD_DESCR"];
}

$med = null;

$styleFirmador = "";
if ($ent == ENTRADA || $ent == MEMORANDO || $ent == SALIDA) {
    $styleFirmador = "display:none";
}

if ($ent == MEMORANDO) {
    $usuario_selected = 'selected';
    $med = SIIM2_RECEPCION;
} else {
    $ciudadano_selected = 'selected';
}

if (
    $ent == CIRC_INTERNA || $ent == CIRC_EXTERNA ||
    $ent == RESOLUCION || $ent == AUTO
) {
    $esNotificacion = true;
    $notificacion = new Notificacion($db);
    if ($ent == CIRC_INTERNA) {
        $circ_int_selected = 'selected';
    }
    if ($ent == CIRC_EXTERNA) {
        $circ_ext_selected = 'selected';
    }
} else {
    $esNotificacion = false;
}

if ($ent == CIRC_INTERNA || $ent == CIRC_EXTERNA) {
    $esNotificacionCircular = true;
} else {
    $esNotificacionCircular = false;
}

// Bloqueo de edición en Entradas (modificación) salvo excepciones
$isModEntrada = ($ent == ENTRADA && !empty($nurad));
$depNameSess = isset($_SESSION['DEPENDENCIA_NOMB']) ? $_SESSION['DEPENDENCIA_NOMB'] : (
    (isset($_SESSION['depe_nomb']) ? $_SESSION['depe_nomb'] : (
        (isset($_SESSION['dependencia_nombre']) ? $_SESSION['dependencia_nombre'] : '')
    ))
);

$depNameSessNorm = strtolower(trim((string)$depNameSess));
$isGrupoCorrespondencia = ($depNameSessNorm === 'grupo de correspondencia');
$isRadicadorEntrada = !empty($_SESSION['USUA_RADICADOR_ENTRADA']);
$blockEntrada = ($isModEntrada && !($isGrupoCorrespondencia || $isRadicadorEntrada));

if ($rad0) {
    $javascriptCapDatos = 'datorad=0';
} elseif ($rad1) {
    $javascriptCapDatos = 'datorad=1';
} elseif ($rad2) {
    $javascriptCapDatos = 'datorad=2';
}

//CARGAR INFORMACION SI SE TRAE DE UN ANEXO O COPIA DE DATOS
$radi_a_buscar = !empty($radicadopadre) ? $radicadopadre : (isset($cuentai) && is_numeric($cuentai) && strlen($cuentai) >= 14 ? $cuentai : null);

if ($radi_a_buscar) {

    $query = "SELECT
          a.*
          FROM
          RADICADO A
          WHERE
          A.RADI_NUME_RADI = $radi_a_buscar";

    $rs = $db->conn->query($query);

    if (!$rs->EOF) {
        $asu = $rs->fields["RA_ASUN"];
        $ane = $rs->fields["RADI_DESC_ANEX"];
        $cuentai = !empty($rs->fields["RADI_CUENTAI"]) ? $rs->fields["RADI_CUENTAI"] : $rs->fields["RADI_NUME_RADI"];
        $tdoc = $rs->fields["TDOC_CODI"];
        $med = $rs->fields["MREC_CODI"];
        $coddepe = $rs->fields["RADI_DEPE_ACTU"];
        $codusuarioActu = $rs->fields["RADI_USUA_RADI"];
        $radi_fecha = $rs->fields["RADI_FECH_RADI"];
        $fecha_temp = !empty($rs->fields["RADI_FECH_OFIC"]) ? $rs->fields["RADI_FECH_OFIC"] : $rs->fields["RADI_FECH_RADI"];
        $fecha_gen_doc = date('Y-m-d', strtotime($fecha_temp));
        $guia = $rs->fields["RADI_NUME_GUIA"];
        $empTrans = $rs->fields["EMP_TRANSPORTADORA"];
        $radi_dato_001 = $rs->fields["RADI_DATO_001"]; //Campo de uso general
        $radi_dato_002 = $rs->fields["RADI_DATO_002"]; //Campo de uso general
    }

    if (!$esNotificacionCircular) {
        //Filtro por el tipo de usuario
        $result = $usuario->usuarioPorRadicado($radi_a_buscar, $esNotificacion);

        if ($result) {
            $usuario_nuevo = true;
            $showUsers = $usuario->resRadicadoHtml($usuario_nuevo);
            $showtable = '';
        }
    }

    //Informacion sobre Notificaciones
    if ($esNotificacion) {
        $infoNotificacion = $notificacion->cargarNotificacionAntigua($radi_a_buscar);
        $notifica_codi = ""; // Es un nuevo radicado
        $medio_pub = $infoNotificacion["med_public"];
        $caracter_adtvo = $infoNotificacion["caracter_adtvo"];
        $prioridad_prestablecido = $infoNotificacion["prioridad"] === "t" ? 1 : 0;

        if ($esNotificacionCircular) {
            $result = $notificacion->destinatariosPorRadicado($radi_a_buscar);

            if ($result) {
                $showUsers = $notificacion->agregarDestinatarios($result, true);
                $showtable = '';
            }
        }
    }
}

//CARGAR INFORMACION SI SE ENVIA NUMERO DE RADICADO PARA MODIFICAR
if ($nurad) {

    $query = "SELECT
          a.*
          FROM
          RADICADO A
          WHERE
          A.RADI_NUME_RADI = $nurad";

    $rs = $db->conn->query($query);

    if (!$rs->EOF) {
        $asu            = $rs->fields["RA_ASUN"];
        $radicadopadre  = $rs->fields["RADI_NUME_DERI"];
        $ane            = $rs->fields["RADI_DESC_ANEX"];
        $cuentai        = $rs->fields["RADI_CUENTAI"];
        $tdoc           = $rs->fields["TDOC_CODI"];
        $med            = $rs->fields["MREC_CODI"];
        $coddepe        = $rs->fields["RADI_DEPE_ACTU"];
        $codusuarioActu = $rs->fields["RADI_USUA_RADI"];
        $radi_fecha     = $rs->fields["RADI_FECH_RADI"];
        $fecha_gen_doc  = !empty($rs->fields["RADI_FECH_OFIC"]) ? $rs->fields["RADI_FECH_OFIC"] : $rs->fields["RADI_FECH_RADI"];
        $guia           = $rs->fields["RADI_NUME_GUIA"];
        $empTrans       = $rs->fields["EMP_TRANSPORTADORA"];
        $numFolio       = $rs->fields["RADI_NUME_FOLIO"];
        $numAnexo       = $rs->fields["RADI_NUME_ANEXO"];
        $esta_fisico    = $rs->fields["ESTA_FISICO"];
        $radi_dato_001  = $rs->fields["RADI_DATO_001"]; //Campo de uso general
        $radi_dato_002  = $rs->fields["RADI_DATO_002"]; //Campo de uso general
        $firmador       = $rs->fields["RADI_USUA_FIRMA"] . "-" . $rs->fields["RADI_DEPE_FIRMA"];
        $nivelSeguridadSeleccionado = $rs->fields["SGD_SPUB_CODIGO"];
    }

    $date1 = date_create($radi_fecha);

    list($adate, $mdate, $ddate) = explode('-', date_format($date1, 'Y-m-d'));

    if ($fecha_gen_doc) {
        $fecha_gen_doc = date('Y-m-d', strtotime($fecha_gen_doc));
    }

    $ent = substr($nurad, -1);

    if (!$esNotificacionCircular) {
        //Filtro por el tipo de usuario
        $result = $usuario->usuarioPorRadicado($nurad, $esNotificacion);

        if ($result) {
            $usuario_nuevo = false;
            $showUsers = $usuario->resRadicadoHtml($usuario_nuevo);
            $hidetable = 'hide';
            $modificar = '';
            $showtable = '';
        }
    }

    $varEnvio = session_name() . "=" . session_id() . "&nurad=$nurad&ent=$ent";
    $senddata = "<input name='nurad' value='$nurad' type=hidden>";
    $senddata .= "<input name='idCodigo' value='$nurad' type=hidden>";

    //Informacion sobre Notificaciones
    if ($esNotificacion) {
        $infoNotificacion = $notificacion->cargarNotificacionAntigua($nurad);
        $notifica_codi = $infoNotificacion["notifica_codi"];
        $medio_pub = $infoNotificacion["med_public"];
        $caracter_adtvo = $infoNotificacion["caracter_adtvo"];
        $prioridad_prestablecido = $infoNotificacion["prioridad"] === "t" ? 1 : 0;

        if ($esNotificacionCircular) {
            $result = $notificacion->destinatariosPorRadicado($nurad);

            if ($result) {
                $showUsers = $notificacion->agregarDestinatarios($result);
                $hidetable = 'hide';
                $modificar = '';
                $showtable = '';
            }
        }
    }
}

//****************************************************************************************************************************************
// Se agrega filtro para la dependencias que solo traiga ese valor
//****************************************************************************************************************************************
if ($dependencia == '95000' || $dependencia == '95001' and $ent != 2) {
    $filtDep = "AND d.DEPE_CODI = {$dependencia}";
    $filtNvSeg1 = 'checked';
}

//****************************************************************************************************************************************

$query = "SELECT " .
    $db->conn->Concat("d.DEPE_CODI", "'-'", "d.DEPE_NOMB") . ", d.DEPE_CODI
        FROM
          DEPENDENCIA d
        join DEPENDENCIA_VISIBILIDAD dv on (
          d.depe_codi = dv.dependencia_visible 
          and dv.dependencia_observa = $dependencia)
        where
          d.depe_estado = 1
          {$filtDep}
        ORDER BY d.DEPE_CODI, d.DEPE_NOMB";

$rs = $db->conn->query($query);

if ($_TIPO_INFORMADO != 2) {
    $depselect = $rs->GetMenu2("coddepe", $nurad || $radicadopadre || $esNotificacion || in_array($ent, [MEMORANDO, SALIDA]) ? $coddepe : false, "0:-- Seleccione una Dependencia --", false, false, "class='form-control'  title='seleccione una dependencia'  id='dep-control'");
} else if ($_TIPO_INFORMADO == 2) {
    $depselect = $rs->GetMenu2("coddepe", false, false, false, "class='form-control'", "id='dep-control' ");
}

$sqlquery = "SELECT " .
    $db->conn->Concat("d.ID", "'-'", "d.NOMBRE") . ", d.ID
            FROM
              SGD_EMPRESA_TRANSPORTADORA d
            ORDER BY d.NOMBRE";

$rs = $db->conn->query($sqlquery);

$transpSelect = $rs->GetMenu2("empTrans", $empTrans, false, false, '', " class='form-select'");

$queryData = "SELECT " .
    $db->conn->Concat("d.DEPE_CODI", "'-'", "d.DEPE_NOMB") . ", d.DEPE_CODI
                FROM
                DEPENDENCIA d
		where depe_estado = 1 order by 1 asc";

$rs = $db->conn->query($queryData);

if ($_TIPO_INFORMADO == 1) {
    $depselectInf = $rs->GetMenu2(
        "coddepe_informados",
        $coddepe,
        "0:-- Seleccione una Dependencia --",
        false,
        false,
        "class='form-control custom-scroll' id='informar'"
    );
} elseif ($_TIPO_INFORMADO == 2) {
    $depselectInf = $rs->GetMenu2(
        "coddepe",
        $coddepe,
        "",
        false,
        false,
        "class='form-control custom-scroll' multiple='multiple' id='informar' style='height: 15%;' "
    );
}

if ($ent == MEMORANDO) {
    $query = "SELECT
                MREC_DESC, MREC_CODI
                FROM MEDIO_RECEPCION
                WHERE MREC_CODI = 4
                ORDER BY MREC_CODI";
} else {
    $query = "SELECT
                MREC_DESC, MREC_CODI
                FROM MEDIO_RECEPCION
                WHERE MREC_CODI NOT IN (0,3,5,6)
                ORDER BY MREC_CODI";
}

$rs = $db->conn->query($query);
if ($tipoMedio == "eMail") $med = 4;

$medioRec = $rs->GetMenu2(
    "med",
    $med,
    '',
    false,
    "",
    "required class='form-control' id='mrecep' title='seleccione un medio recepción/envio'"
);

// Si se debe bloquear edición en Entrada, deshabilitar el select y enviar valor oculto


$query = "SELECT
                  SGD_TPR_DESCRIP
                  ,SGD_TPR_CODIGO
                FROM
                  SGD_TPR_TPDCUMENTO
                WHERE
                  SGD_TPR_TP$ent     ='1'
                  and SGD_TPR_RADICA ='1'
                  ORDER BY SGD_TPR_DESCRIP ";

$opcMenu = "0:-- Seleccione un tipo --";
$fechaHoy = date("Y-m-d");
$fechaHoy = $fechaHoy . "";
$rs = $db->conn->query($query);
$tipoDoc = $rs->GetMenu2(
    "tdoc",
    $tdoc,
    "$opcMenu",
    false,
    "",
    "title='Seleccione el tipo documental' class='form-control'"
);

if ($esNotificacion) {
    $camposFormulario = $notificacion->cargarCamposFormulario($ent, $medio_pub, $caracter_adtvo);
    $tdoc           = !empty($camposFormulario["tdoc"]) ? $camposFormulario["tdoc"] : 'null';
    $medioPub       = $camposFormulario["medioPub"];
    $caracterAdtvo  = $camposFormulario["caracterAdtvo"];
}

$showEntrada = "
            <section class='col-12 col-md-2 mb-3'>
                <label class='form-label fw-semibold'>
                    Referencia
                </label>
                <input id='cuentai'
                    class='form-control'
                    title='Coloque aquí el número de referencia de la comunicación'
                    name='cuentai'
                    type='text'
                    maxlength='100'
                    value='{$cuentai}'>
            </section>
            
            <section class='col-12 col-md-2 mb-3'>
                <label class='form-label fw-semibold'>Fecha Referencia</label>
                <input type='date' class='form-control' id='fecha_gen_doc' name='fecha_gen_doc' value='{$fecha_gen_doc}' required>
            </section>";

if ($ent == 2) {
    $showEntrada = "
                <section class='col-12 col-md-2 mb-3'>
                    <label class='form-label fw-semibold'>Referencia</label>
                    <input id='cuentai'
                        class='form-control'
                        title='Coloque aquí el número de referencia de la comunicación'
                        name='cuentai'
                        type='text'
                        maxlength='100'
                        value='{$cuentai}'>
                </section>

                <section class='col-12 col-md-2 mb-3'>
                    <label class='form-label fw-semibold'>Fecha Referencia</label>
                    <input type='date' class='form-control' id='fecha_gen_doc' name='fecha_gen_doc' value='{$fecha_gen_doc}' required>
                </section>

                <section class='col-12 col-md-2 mb-3'>
                    <label class='form-label fw-semibold'>Guía</label>
                    <input type='text'
                        class='form-control'
                        name='guia'
                        title='Si tiene un número de guía digítelo.'
                        id='guia'
                        value='{$guia}'>
                </section>

                <section class='col-12 col-md-3 mb-3'>
                    <label class='form-label fw-semibold'>Transportadora</label>
                    {$transpSelect}
                </section>";

    $showEntrada1 = "<label>Usuario quien radica</label>";
} else {
    $showEntrada = "
            <section class='col-12 col-md-2 mb-3'>
                <label class='form-label fw-semibold'>
                    Referencia
                </label>
                <input id='cuentai'
                    class='form-control'
                    title='Coloque aquí el número de referencia de la comunicación'
                    name='cuentai'
                    type='text'
                    maxlength='100'
                    value='{$cuentai}'>
            </section>
            
            <section class='col-12 col-md-2 mb-3'>
                <label class='form-label fw-semibold'>Fecha Referencia</label>
                <input type='date' class='form-control' id='fecha_gen_doc' name='fecha_gen_doc' value='{$fecha_gen_doc}' required>
            </section>";
}

$shouldDisableNivelSeguridad = (!empty($filtNvSeg) && !empty($filtNvSeg1) && empty($nurad));
$checkedPublico = '';
$checkedConfidencial = '';
$checkedClasificada = '';
$publicDisabledAttr = $shouldDisableNivelSeguridad ? 'disabled' : '';
$clasificadaDisabledAttr = $shouldDisableNivelSeguridad ? 'disabled' : '';

if ($nivelSeguridadSeleccionado !== null && $nivelSeguridadSeleccionado !== '') {
    $nivelSeguridadSeleccionado = (int) $nivelSeguridadSeleccionado;
    switch ($nivelSeguridadSeleccionado) {
        case 0:
            $checkedPublico = 'checked';
            $publicDisabledAttr = '';
            $clasificadaDisabledAttr = $shouldDisableNivelSeguridad ? 'disabled' : '';
            break;
        case 1:
            $checkedConfidencial = 'checked';
            $publicDisabledAttr = $shouldDisableNivelSeguridad ? 'disabled' : '';
            $clasificadaDisabledAttr = $shouldDisableNivelSeguridad ? 'disabled' : '';
            break;
        case 2:
            $checkedClasificada = 'checked';
            $clasificadaDisabledAttr = '';
            $publicDisabledAttr = $shouldDisableNivelSeguridad ? 'disabled' : '';
            break;
    }
} else {
    if ($shouldDisableNivelSeguridad) {
        $checkedConfidencial = 'checked';
    } else {
        $checkedPublico = 'checked';
        $publicDisabledAttr = '';
        $clasificadaDisabledAttr = '';
    }
}
?>

<html>

<head>
    <?php include_once "$ruta_raiz/htmlheader.inc.php" ?>
    <link rel="stylesheet" href="../tooltips/jquery-ui.css">
    <!-- Al colocar esto hace confilcto <script src="../tooltips/jquery-1.10.2.js"></script>-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="../tooltips/jquery-ui.js"></script>
    <link rel="stylesheet" href="../tooltips/tool.css">
    <script src="../tooltips/tool.js"></script>
    <script src="../tooltips/valida_email.js"></script>
    <style>
        .inbox-download-list li>*:first-child {
            width: 250px;
        }

        .sticky-top-custom {
            position: sticky;
            top: 0px;
            /* distancia desde arriba */
            z-index: 1020;
            /* encima del contenido */
            background: #fff;
            /* evita transparencias */
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <form method="post" name="formulario" id="formulario" action="">
            <input type="hidden" name="ent" value="<?= $ent ?>">
            <input type="hidden" name="radicadopadre" value="<?= $radicadopadre ?>">

            <!-- HEADER -->
            <div class="card shadow-sm mb-1">
                <div class="card-header bg-orfeo text-white">
                    <h4 class="mb-0">
                        Radicación <?= $nomEntidad ?> <?= $tRadicacionDesc ?>
                    </h4>
                    <p class="mb-0" id="idrad">
                        <?= $nurad ?> <?= $encabezado ?>
                    </p>
                </div>
            </div>

            <!-- FORM CONTENT -->
            <!-- sticky-top-custom -->
            <div class="card shadow-sm my-1 ">
                <div class="card-body">
                    <div class="row align-items-end g-3">
                        <!-- FECHA ACTUAL -->
                        <section class="col-12 col-md-2 mb-3">
                            <label class="form-label fw-semibold">Fecha actual</label>
                            <div class="form-control-plaintext fw-bold">
                                <?= $ddate ?> / <?= $mdate ?> / <?= $adate ?>
                            </div>
                        </section>

                        <!-- ENTRADAS DINÁMICAS -->
                        <?= $showEntrada ?>
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="row g-3 align-items-center mb-1 mx-2">
                    <div id="showRadicar" class="col-12 col-md-6 <?= $hidetable ?>">
                        <a
                            title="Radicar documento"
                            class="btn btn-primary btn-lg w-50 radicarNuevo">
                            <i class="fa fa-circle-arrow-up me-2"></i>
                            Radicar documento
                        </a>
                    </div>

                    <div id="showModificar" class="col-12 col-md-6 <?= $modificar ?> d-flex ">
                        <a
                            title="Modificar"
                            id="modificaRad"
                            class="btn btn-success btn-lg w-50">
                            Modificar <?= $nurad ?> <?= $senddata ?>
                        </a>

                        <div class="d-flex justify-content-between">
                            <a title="Sticker"
                                id="sticker"
                                href="#"
                                onClick="window.open('./stickerWeb/index.php?<?= $varEnvio ?>&alineacion=Center','sticker<?= $nurad ?>','width=450,height=180');"
                                class="btn btn-link px-0">
                                Sticker |
                            </a>

                            <!-- <a title="Asociar Imagen"
                                id="asociar"
                                href="javascript:void(0);"
                                onClick="window.open('../uploadFiles/uploadFileRadicado.php?busqRadicados=<?= $nurad ?>&Buscar=Buscar&<?= $varEnvio ?>','asociar<?= $nurad ?>','width=550,height=280');"
                                class="btn btn-link px-0">
                                Asociar Imagen
                            </a> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <section id="alertmessage"></section>
                </div>
            </div>

            <?php if ($esNotificacion) { ?>
                <div class="row my-3">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                            <?php } ?>

                            <div class="row">
                                <article class="col-12">
                                    <div class="card border-0">
                                        <div class="card-body">

                                            <?= $showEntrada1 ?>

                                            <!-- FORM SEARCH -->
                                            <section id="formsearch" class="row g-2 align-items-end mb-3">
                                                <!-- TIPO USUARIO -->
                                                <div class="<?= $esNotificacionCircular ? 'col-md-3' : 'col-md-2' ?>">
                                                    <label for="tipo_usuario" class="form-label fw-semibold">Tipo usuario</label>
                                                    <?php if ($esNotificacionCircular) { ?>
                                                        <select id="tipo_usuario" name="tipo_usuario" class="form-select form-select-sm" disabled>
                                                            <option value='4' <?= $circ_int_selected ?>><?= $_name_4 ?></option>
                                                            <option value='5' <?= $circ_ext_selected ?>><?= $_name_5 ?></option>
                                                        </select>
                                                    <?php } else { ?>
                                                        <select id="tipo_usuario" name="tipo_usuario" class="form-select form-select-sm">
                                                            <option value=''>Seleccionar</option>
                                                            <?php if ($ent != MEMORANDO) { ?>
                                                                <option value='0' <?= $ciudadano_select ?>>Solicitante</option>
                                                            <?php } ?>
                                                            <?php if ($_enable_1) { ?>
                                                                <option value='1' <?= $esp_select ?>>ESP</option>
                                                            <?php } ?>
                                                            <?php if ($_enable_2 && $ent != MEMORANDO) { ?>
                                                                <option value='2' <?= $entidad_selected ?>><?= $_name_2 ?></option>
                                                            <?php } ?>
                                                            <option value='6' <?= $usuario_selected ?>><?= $_name_6 ?></option>
                                                        </select>
                                                    <?php } ?>
                                                </div>

                                                <?php if (!$esNotificacionCircular) { ?>
                                                    <div class="col-md-2">
                                                        <label class="form-label fw-semibold">Identificación</label>
                                                        <input type="text" id="documento_us" pattern="[0-9]"
                                                            class="form-control form-control-sm"
                                                            placeholder="Identificación">
                                                    </div>
                                                <?php } ?>

                                                <?php if ($esNotificacionCircular) { ?>
                                                    <div class="col-md-6">
                                                        <label for="destinatario_us" class="form-label fw-semibold">Destinatarios</label>
                                                        <input type="text" id="destinatario_us" name="destinatario_us" pattern="[A-Za-z]" class="form-control form-control-sm" placeholder="Destinatarios">
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="col-md-2">
                                                        <label for="nombre_us" class="form-label fw-semibold">Nombre</label>
                                                        <input type="text" id="nombre_us" name="nombre_us" pattern="[A-Za-z]" class="form-control form-control-sm" placeholder="Nombre">
                                                    </div>
                                                <?php } ?>

                                                <?php if (!$esNotificacionCircular) { ?>
                                                    <div class="col-md-2">
                                                        <label for="telefono_us" class="form-label fw-semibold">Teléfono</label>
                                                        <input type="text" id="telefono_us" pattern="[0-9]"
                                                            class="form-control form-control-sm" name="telefono_us"
                                                            placeholder="Teléfono">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="mail_us" class="form-label fw-semibold">Correo electrónico</label>
                                                        <input type="text" id="mail_us" name="mail_us"
                                                            pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}"
                                                            class="form-control form-control-sm"
                                                            placeholder="Correo electrónico">
                                                    </div>
                                                <?php } ?>

                                                <!-- BOTONES -->
                                                <!-- href="javascript:void(0);" -->
                                                <div class="col-auto">
                                                    <button id="idnuevo"
                                                        title="Solo si su destinatario no se encuentra en la búsqueda ingrese uno nuevo."
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fa fa-plus-circle me-1"></i> Nuevo
                                                    </button>
                                                </div>

                                                <div class="col-auto">
                                                    <button id="idconsulta"
                                                        title="Pulse clic para buscar el remitente o destinatario."
                                                        class="btn btn-outline-primary btn-sm">
                                                        <i class="fa fa-search me-1"></i> Buscar
                                                    </button>
                                                </div>
                                            </section>

                                            <!-- RESULTADOS BUSQUEDA -->
                                            <section id="showAnswer" class="d-none mt-3">
                                                <div class="card border-info">
                                                    <div class="card-body p-2">
                                                        <ul id="resBusqueda" class="list-group list-group-flush"></ul>
                                                    </div>
                                                </div>
                                            </section>

                                            <!-- TABLA -->
                                            <section id="tableSection" class="card mt-3 py-1 px-1 <?= $showtable ?>">
                                                <div class="card-body p-0">
                                                    <i class="fas fa-time"></i>
                                                    <table class="table table-hover table-sm mb-0">
                                                        <tbody id="tableshow">
                                                            <?= $showUsers ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                </article>
                            </div>

                            <?php if ($esNotificacion) { ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="row my-3">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <fieldset>
                                <div class="row g-3">
                                    <!-- ASUNTO -->
                                    <div class="col-md-3">
                                        <div class="mb-2">
                                            <?php if ($esNotificacion) { ?>
                                                <label id="lbAsunto" class="form-label fw-semibold">Asunto / epígrafe 0/342</label>
                                                <?php } else {
                                                if ($ent == SALIDA) { ?>
                                                    <label id="lbAsunto" class="form-label fw-semibold">* Asunto 0/510</label>
                                                <?php } else { ?>
                                                    <label id="lbAsunto" class="form-label fw-semibold">* Asunto 0/342</label>
                                            <?php }
                                            } ?>

                                            <textarea
                                                title='<?= $blockEntrada ? "El asunto no se puede modificar en documentos de entrada" : "Coloque aquí el asunto" ?>'
                                                <?= !$blockEntrada ? 'required' : '' ?>
                                                id="asu"
                                                name="asu"
                                                rows="4"
                                                maxlength="342"
                                                onpaste="limitPaste(this)"
                                                class="form-control"
                                                style="resize:none;<?= $blockEntrada ? 'background-color:#f8f9fa;cursor:not-allowed;' : '' ?>"
                                                <?= $blockEntrada ? 'readonly' : '' ?>><?= $asu ?></textarea>

                                            <?php if ($blockEntrada) { ?>
                                                <small class="text-muted">
                                                    <i class="fa fa-info-circle me-1"></i>
                                                    El asunto no se puede modificar en documentos de entrada
                                                </small>
                                            <?php } ?>
                                        </div>

                                        <?php if ($esNotificacion) { ?>
                                            <div class="mb-2">
                                                <label class="form-label fw-semibold">Medio de Publicación</label>
                                                <?= $medioPub ?>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <!-- MEDIO / FOLIOS -->
                                    <div class="col-md-3">
                                        <?php if ($esNotificacion) { ?>
                                            <div class="mb-3 opacity-50">
                                                <label class="form-label fw-semibold">Medio de envío</label>
                                                <input type="text" class="form-control" disabled>
                                            </div>
                                        <?php } else { ?>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">* Medio Recepción / Envío</label>
                                                <?= $medioRec ?>
                                            </div>
                                        <?php } ?>

                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label">No. Folios</label>
                                                <input type="text" name="nofolios" id="nofolios"
                                                    pattern="[0-9]" maxlength="5"
                                                    onkeypress="return justNumbers(event);"
                                                    onpaste="limitPaste(this)"
                                                    class="form-control"
                                                    value="<?= $numFolio ?>" <?= $blockEntrada ? 'readonly' : '' ?>>
                                            </div>

                                            <div class="col-6">
                                                <label class="form-label">No. Anexos</label>
                                                <input type="text" name="noanexos" id="noanexos"
                                                    pattern="[0-9]" maxlength="5"
                                                    onkeypress="return justNumbers(event);"
                                                    onpaste="limitPaste(this)"
                                                    class="form-control"
                                                    value="<?= $numAnexo ?>" <?= $blockEntrada ? 'readonly' : '' ?>>
                                            </div>
                                        </div>

                                        <?php if ($esNotificacion) { ?>
                                            <div class="mt-2">
                                                <label class="form-label fw-semibold">Carácter acto administrativo</label>
                                                <?= $caracterAdtvo ?>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <!-- DEPENDENCIA -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">* Dependencia</label>
                                            <?= $depselect ?>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Descripción Anexos</label>
                                            <input type="text" name="ane" id="ane"
                                                class="form-control"
                                                maxlength="200"
                                                onpaste="limitPaste(this)"
                                                value="<?= $ane ?>" <?= $blockEntrada ? 'readonly' : '' ?>>
                                        </div>
                                    </div>

                                    <!-- SEGURIDAD -->
                                    <div class="col-md-3">
                                        <?php if ($_show_type_doc == true && !$esNotificacion) { ?>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Clasificación Previa</label>
                                                <?= $tipoDoc ?>
                                            </div>
                                        <?php } else { ?>
                                            <input type="hidden" name="tdoc" value="<?= $tdoc ?>">
                                        <?php } ?>

                                        <label class="form-label fw-semibold">Nivel de Seguridad</label>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="nivelSeguridad"
                                                id="publico" value="0" <?= $checkedPublico ?> <?= $publicDisabledAttr ?>>
                                            <label class="form-check-label" for="publico">Público</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="nivelSeguridad"
                                                id="confidencial" value="1" <?= $checkedConfidencial ?>>
                                            <label class="form-check-label" for="confidencial">Reservado</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="nivelSeguridad"
                                                id="clasificada" value="2" <?= $checkedClasificada ?> <?= $clasificadaDisabledAttr ?>>
                                            <label class="form-check-label" for="clasificada">Clasificado</label>
                                        </div>
                                    </div>

                                    <?php if ($_SESSION["varEstaenfisico"] == 1) { ?>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Físico en archivo</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="esta_fisico"
                                                    id="esta_fisico" <?= $esta_fisico == 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="esta_fisico">Sí</label>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </fieldset>

                            <!-- FIRMADOR -->
                            <div class="mt-4" style="<?= $styleFirmador ?>">
                                <label class="form-label fw-semibold">Funcionario que firma</label>
                                <?php
                                $sqlFirmador = "SELECT u.usua_nomb, (u.usua_codi || '-' || u.depe_codi) as id  
                                            FROM  usuario u
                                            JOIN autm_membresias me on me.autu_id = u.id
                                            JOIN autg_grupos gr on gr.id = me.autg_id
                                            JOin autr_restric_grupo rg on rg.autg_id = gr.id
                                            JOin autp_permisos ap on ap.id = rg.autp_id
                                            WHERE ap.nombre = 'USUA_PERM_FIRMA' and  u.depe_codi != 900
                                            GROUP BY u.usua_nomb, u.usua_codi, u.depe_codi  order by u.usua_nomb";

                                $rsSqlFirmador = $db->conn->Execute($sqlFirmador);
                                print $rsSqlFirmador->GetMenu2(
                                    "s_firmador",
                                    "$firmador",
                                    "0-0:-- Seleccione el funcionario --",
                                    false,
                                    "",
                                    "class='form-select'"
                                );
                                ?>
                            </div>

                            <!-- INFORMAR -->
                            <hr class="my-4">
                            <legend class="<?= $modificar ?> fw-semibold">Informar a:</legend>

                            <fieldset>
                                <div class="row g-3 <?= $modificar ?>" id="inforshow">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Dependencia</label>
                                        <?= $depselectInf ?>
                                    </div>

                                    <?php if ($_TIPO_INFORMADO == 1) { ?>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Usuario</label>
                                            <select name="usuarios_informar" id="informarUsuario"
                                                multiple class="form-select">
                                                <option value="0">-- Seleccione un Usuario --</option>
                                            </select>
                                        </div>
                                    <?php } ?>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">
                                            Usuarios seleccionados para notificar
                                        </label>
                                        <div id="showusers" class="border rounded p-2 small bg-light"></div>
                                    </div>

                                    <div class="col-md-3 d-flex align-items-end">
                                        <a id="accioninfousua"
                                            title="Informar"
                                            class="btn btn-success btn-sm">
                                            <i class="fa fa-circle-arrow-up me-1"></i> Informar
                                        </a>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9"></div>
            <div id="copyradicar"></div>
            <input type="hidden" name="errormail" id="errormail" value="0">
            <br><br><br><br><br>
        </form>

        <!-- Modal de alerta -->
        <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="alertModalLabel">Alerta</h5>
                    </div>
                    <div class="modal-body" id="alertModalBody">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 align-items-center mt-2">

            <!-- STICKER -->
            <a title="Sticker"
                id="skeleton"
                href="javascript:void(0);"
                onclick="window.open('./stickerWeb/index.php?<?= $idsession ?>&nurad=xxxxxx&ent=<?= $ent ?>',
            'stickerxxxxxx','menubar=0,resizable=0,scrollbars=0,width=450,height=180,toolbar=0,location=0');"
                class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1 hide"
                data-bs-toggle="tooltip">
                <i class="fa fa-tag"></i>
                <span>Sticker</span>
            </a>

            <?php
            require_once "$ruta_raiz/include/tx/RadicadoFilter.php";
            $radicadoFilter = new RadicadoFilter($db);
            ?>

            <!-- VER RADICADO -->
            <?php if ($_SESSION["perm_rad_reser"] >= 1): ?>
                <a title="Ver radicado"
                    id="skeleton8"
                    href="../verradicado.php?verrad=xxxxxx&depe_actu=<?= $coddepe ?>&usuacodi=<?= $codusua ?>"
                    class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 hide"
                    data-bs-toggle="tooltip">
                    <i class="fa fa-eye"></i>
                    <span>Ver radicado</span>
                </a>
            <?php elseif ($radicadoFilter->isDependenciaInFilter($cod_radi, $_SESSION["dependencia"])): ?>
                <a title="Ver radicado"
                    id="skeleton8"
                    href="../verradicado.php?verrad=xxxxxx&depe_actu=<?= $coddepe ?>&usuacodi=<?= $codusua ?>"
                    class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 hide"
                    data-bs-toggle="tooltip">
                    <i class="fa fa-eye"></i>
                    <span>Ver radicado</span>
                </a>
            <?php else: ?>
                <a title="Ver radicado"
                    id="skeleton8"
                    href="../verradicado.php?verrad=xxxxxx"
                    class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 hide"
                    data-bs-toggle="tooltip">
                    <i class="fa fa-eye"></i>
                    <span>Ver radicado</span>
                </a>
            <?php endif; ?>

            <!-- ASOCIAR IMAGEN -->
            <a title="Asociar imagen"
                id="skeleton9"
                href="javascript:void(0);"
                onclick="window.open('../uploadFiles/uploadFileRadicado.php?<?= $idsession ?>&busqRadicados=xxxxxx&Buscar=Buscar&alineacion=Center', 'busqRadicados=xxxxxx','menubar=0,resizable=0,scrollbars=0,width=550,height=280,toolbar=0,location=0');"
                class="btn btn-outline-success btn-sm d-flex align-items-center gap-1 hide"
                data-bs-toggle="tooltip">
                <i class="fa fa-image"></i>
                <span>Asociar imagen</span>
            </a>

            <!-- TIPIFICAR -->
            <a title="Tipificar documento"
                id="skeleton10"
                href="javascript:void(0);"
                onclick="window.open('../radicacion/tipificar_documento.php?nurad=xxxxxx&ind_ProcAnex=N&codusua=<?= $codusua ?>&coddepe=<?= $coddepe ?>&codusuario=<?= $codusua ?>&dependencia=<?= $coddepe ?>&tsub=0&codserie=0', 'busqRadicados=<?= $nurad ?>','menubar=0,resizable=0,scrollbars=0,width=650,height=480,toolbar=0,location=0');"
                class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1 hide"
                data-bs-toggle="tooltip">
                <i class="fa fa-tags"></i>
                <span>Tipificar</span>
            </a>

            <!-- CHECKBOX OCULTO -->
            <label class="form-check userinfo hide mb-0 ms-2">
                <input type="checkbox" class="form-check-input" checked name="radio[]" value="">
            </label>
        </div>

        <script type="text/javascript">
            //************************************************************************************************
            // Bloquea el nivel de seguridad según la dependencia.
            //************************************************************************************************
            let idDep = document.getElementById('dep-control'),
                nvPublico = document.getElementById('publico'),
                nvConfidencial = document.getElementById('confidencial'),
                nvClasificada = document.getElementById('clasificada');
            var cntRes = document.getElementById('cntRes');

            idDep.addEventListener("change", (e) => {
                nvConfidencial.innerHTML = '';

                if (idDep.value == 95000 || idDep.value == 95001) {
                    nvPublico.removeAttribute("checked");
                    nvPublico.setAttribute("disabled", true);
                    nvConfidencial.setAttribute("hidden", true);
                    cntRes.innerHTML = `<input type="radio" title="seleccione el nivel de confidencial" name="nivelSeguridad" id="confidencial" value="1" checked> Reservado`;
                    nvClasificada.setAttribute("disabled", true);
                } else {
                    nvPublico.removeAttribute("disabled");
                    nvPublico.setAttribute("checked", '');
                    nvConfidencial.removeAttribute("checked");
                    nvClasificada.removeAttribute("disabled");
                }
            });

            //************************************************************************************************
            function justNumbers(e) {
                var keynum = window.event ? window.event.keyCode : e.which;
                if ((keynum == 8) || (keynum == 46))
                    return true;
                return /\d/.test(String.fromCharCode(keynum));
            }

            document.addEventListener('DOMContentLoaded', function() {
                var TIPO_RADICADO = '<?= $ent ?>';

                if (TIPO_RADICADO == 1) {
                    document.getElementById("asu").setAttribute('maxlength', '510');
                }

                var dependencia_usuario = <?= $_SESSION['dependencia'] ?>;
                var dependencias_clasificadas_trigger = <?= $dependencias_clasificadas_trigger; ?>;
                var dependencias_clasificadas = [<?= $dependencias_clasificadas; ?>];

                function showAlertModal(message, title = 'Alerta') {
                    const modalEl = document.getElementById('alertModal');
                    const modalTitle = document.getElementById('alertModalLabel');
                    const modalBody = document.getElementById('alertModalBody');

                    if (!modalEl || !modalTitle || !modalBody) return;

                    modalTitle.textContent = title;
                    modalBody.innerHTML = message;

                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                }

                if (dependencias_clasificadas_trigger && dependencias_clasificadas.includes(dependencia_usuario)) {
                    const radios = document.querySelectorAll('input[name="nivelSeguridad"]');
                    const radioClasificado = document.querySelector('input[name="nivelSeguridad"][value="2"]');

                    // Desmarcar todos
                    radios.forEach(radio => {
                        radio.checked = false;
                    });

                    // Marcar el nivel clasificado
                    if (radioClasificado) {
                        radioClasificado.checked = true;
                    }

                    // Bloquear cambios
                    radios.forEach(radio => {
                        radio.addEventListener('click', (e) => {
                            showAlertModal(
                                'El cambio del nivel de seguridad está restringido para usuarios de las dependencias <?= $dependencias_clasificadas; ?> por políticas de la entidad.'
                            );
                            e.preventDefault();
                        });
                    });
                }

                var ALLDATA;
                var INCREMENTAL1 = 0;
                var EJECUCION = false;
                var RADICACION_NOTIFICACION = '<?= $esNotificacion ?>';
                var RADICACION_CIRCULAR = '<?= $esNotificacionCircular ?>';

                // DO NOT REMOVE : GLOBAL FUNCTIONS!
                pageSetUp();

                const source = document.getElementById('showRadicar');
                const target = document.getElementById('copyradicar');

                if (source && target) {
                    target.innerHTML = ''; // Limpia el contenido previo (equivalente a .html())
                    target.appendChild(source.cloneNode(true)); // true = clonado profundo
                }

                //Datepicker muestra fecha
                $('#fecha_gen_doc').datepicker({
                    dateFormat: 'dd-mm-yy',
                    onSelect: function(selectedDate) {
                        $('#date').datepicker('option', 'maxDate', selectedDate);
                    }
                });

                /**
                 * Generacion de eventos para los usuarios seleccionados
                 * permitiendo cambiar la informacion antes de ser enviada al
                 * servidor. Guardando de esta manera los datos del usuario con
                 * las modificiaciones necesarias
                 */
                document.body.addEventListener('click', function(e) {
                    const target = e.target;

                    if (!target.classList.contains('fa-check')) {
                        return;
                    }

                    // Oculta labels inp_*
                    document.querySelectorAll('label[name^="inp_"]').forEach(el => {
                        el.classList.add('hide');
                    });

                    // Muestra divs div_*
                    document.querySelectorAll('div[name^="div_"]').forEach(el => {
                        el.classList.remove('hide');
                    });

                    // Obtiene el id dinámico
                    const parent = target.parentElement;
                    if (!parent || !parent.getAttribute('name')) return;

                    const iddiv = parent.getAttribute('name').substring(4);

                    // Obtiene el valor del input
                    const label = document.querySelector(`label[name="inp_${iddiv}"]`);
                    const input = label ? label.querySelector('input') : null;
                    const tex_nuevo = input ? input.value : '';

                    // Clona el div original
                    const divOriginal = document.querySelector(`div[name="div_${iddiv}"]`);
                    if (!divOriginal) return;

                    const divClonado = divOriginal.cloneNode(true);

                    // Reemplaza el texto y conserva los hijos
                    divOriginal.textContent = tex_nuevo;

                    Array.from(divClonado.children).forEach(child => {
                        divOriginal.appendChild(child);
                    });
                });

                /**
                 * Si el formulario es llamado desde anexos para modificar la información
                 * o si se acaba de radicar y debemos modificar datos mostrarmos el boton
                 * de modificacion duplicado en la parte superior y en la inferior.
                 */
                <?php if ($modificar != 'hide') { ?>
                    const copyRadicar = document.getElementById('copyradicar');
                    const showModificar = document.getElementById('showModificar');

                    if (copyRadicar && showModificar) {
                        copyRadicar.innerHTML = '';
                        copyRadicar.appendChild(showModificar.cloneNode(true));
                    }

                <?php } ?>

                /**
                 * Generacion de eventos para los usuarios seleccionados
                 * permitiendo cambiar la informacion antes de ser enviada al
                 * servidor. Guardando de esta manera los datos del usuario con
                 * las modificiaciones necesarias
                 */
                document.body.addEventListener('change', function(e) {
                    if (e.target && e.target.id === 'informar') {
                        const values = e.target.value;

                        <?php if ($_TIPO_INFORMADO == 1) { ?>
                            fetch('./ajax_buscarUsuario.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    },
                                    body: new URLSearchParams({
                                        searchUserInDep: values
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    const cont = document.getElementById('informarUsuario');
                                    if (cont) cont.innerHTML = data[0];
                                });

                        <?php } else if ($_TIPO_INFORMADO == 2) { ?>

                            fetch('./ajax_buscarUsuario.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    },
                                    body: new URLSearchParams({
                                        MsearchUserInDep: values
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    const cont = document.getElementById('showusers');
                                    if (cont) cont.innerHTML = data[0];
                                });

                        <?php } ?>
                    }
                });

                /**
                 * Generacion de eventos para los usuarios seleccionados
                 * Selecciona los usuarios y los muestra para informar con
                 * el radicado seleccionado.
                 */
                <?php if ($_TIPO_INFORMADO == 1) { ?>
                    document.body.addEventListener('change', function(e) {
                        if (e.target && e.target.id === 'informarUsuario') {

                            const select = e.target;
                            const selectedOptions = select.querySelectorAll('option:checked');
                            const showUsers = document.getElementById('showusers');
                            const informar = document.getElementById('informar');

                            selectedOptions.forEach(option => {
                                const lastUser = document.querySelector('.userinfo:last-of-type');
                                if (!lastUser) return;

                                const newUser = lastUser.cloneNode(true);

                                newUser.classList.remove('hide');
                                newUser.append(document.createTextNode(option.text));

                                const input = newUser.querySelector('input');
                                if (input) {
                                    input.value = informar.value + '_' + option.value;
                                }

                                showUsers.appendChild(newUser);
                            });
                        }
                    });
                <?php } ?>

                document.body.addEventListener('click', function(e) {
                    if (e.target && e.target.id === 'accioninfousua') {

                        const text = [];
                        const showUsers = document.getElementById('showusers');

                        <?php if ($_TIPO_INFORMADO == 1) { ?>
                            showUsers.querySelectorAll('input').forEach(input => {
                                text.push(input.value);
                            });
                        <?php } elseif ($_TIPO_INFORMADO == 2) { ?>
                            showUsers.querySelectorAll('input:checked').forEach(input => {
                                text.push(input.value);
                            });
                        <?php } ?>

                        const nuradInput = document.querySelector('input[name="nurad"]');
                        const nurad = nuradInput ? nuradInput.value : '';

                        fetch('./ajax_informarUsuario.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                                },
                                body: new URLSearchParams({
                                    addUser: JSON.stringify(text),
                                    radicado: nurad
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                const showResult = document.getElementById('showresult');
                                if (showResult && data.true !== undefined) {
                                    showResult.textContent = data.true;
                                    showResult.parentElement.classList.remove('hide');
                                }
                            })
                            .catch(error => {
                                console.error('Error al informar usuario:', error);
                            });
                    }
                });

                document.body.addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('fa-pencil')) {

                        const parent = e.target.parentElement;
                        if (!parent) return;

                        const texto = parent.getAttribute('name');
                        if (!texto) return;

                        // Mostrar inputs
                        document
                            .querySelectorAll('[name^="inp_' + texto + '"]')
                            .forEach(el => el.classList.remove('hide'));

                        // Ocultar divs
                        document
                            .querySelectorAll('[name^="div_' + texto + '"]')
                            .forEach(el => el.classList.add('hide'));
                    }
                });

                <?php if ($_TIPO_INFORMADO == 1) { ?>
                    document.body.addEventListener('change', function(e) {
                        if (e.target && e.target.classList.contains('informarusuarios')) {

                            const content = e.target.value;
                            const showUsers = document.getElementById('showusers');

                            const label = document.createElement('label');
                            label.className = 'radio';

                            label.innerHTML = `
                                            <input type="radio" name="radio-inline" checked>
                                            <i></i> ${content}
                                        `;

                            showUsers.appendChild(label);
                        }
                    });
                <?php } ?>

                /**
                 * Permite crear un nuevo usurio mostrando los campos vacíos y
                 * dejando que el usuario registre los datos de la persona que necesita.
                 * las modificiaciones necesarias
                 * Se envia en el codigo dos xx para identificar que es un usuario nuevo.
                 * Cuando se carga el usuario de un radicado ya existente en cambio de las dos xx
                 * se muestra el codigo con el cual se guardo.
                 */
                const btnNuevo = document.getElementById('idnuevo');

                if (!btnNuevo) return;

                btnNuevo.addEventListener('click', function(e) {
                    e.preventDefault();

                    const tipoUsuario = document.getElementById('tipo_usuario').value;

                    if (!tipoUsuario) {
                        alert('Por favor seleccione el tipo de usuario que desea crear.');
                        return;
                    }

                    let payload = new FormData();

                    if (RADICACION_CIRCULAR) {
                        const iddata = [{
                            CODIGO_DESTINATARIOS: "",
                            DESTINATARIOS: "",
                            TIPO_CIRCULAR: tipoUsuario
                        }];

                        payload.append(
                            'addDestinatariosCircular',
                            JSON.stringify(iddata)
                        );
                    } else {
                        const iddata = [{
                            CODIGO: 'XX' + INCREMENTAL1,
                            NOMBRE: "",
                            TELEF: "",
                            EMAIL: "",
                            CEDULA: "",
                            PAIS: "COLOMBIA",
                            PAIS_CODIGO: "170",
                            DEP: "D.C.",
                            DEP_CODIGO: "11",
                            MUNI: "BOGOTA",
                            MUNI_CODIGO: "1",
                            TIPO: tipoUsuario,
                            APELLIDO: "",
                            NECESITA_NOTIFICACION: RADICACION_NOTIFICACION,
                            TIPO_RADICADO: TIPO_RADICADO,
                            CARGO: ""
                        }];

                        payload.append(
                            'addUser',
                            JSON.stringify(iddata)
                        );
                    }

                    fetch('./ajax_buscarUsuario.php', {
                            method: 'POST',
                            body: payload
                        })
                        .then(response => response.json())
                        .then(data => {
                            const tableShow = document.getElementById('tableshow');
                            const tableSection = document.getElementById('tableSection');

                            if (data && data[0]) {
                                const improvedHTML = beautifyUsuarioHTML(data[0]);
                                // tableShow.insertAdjacentHTML('beforeend', improvedHTML);
                                tableShow.append(improvedHTML);
                                tableSection.classList.remove('d-none');
                                INCREMENTAL1++;
                            }
                        })
                        .catch(error => {
                            console.error('Error en la petición:', error);
                        });

                });

                /**
                 * Parsea un string HTML y lo convierte en un DOM manipulable
                 *
                 * @param   htmlString    html retornado del back
                 */
                function parseHTML(htmlString) {
                    const wrapper = document.createElement('div');
                    wrapper.innerHTML = htmlString.trim();
                    return wrapper;
                }

                /**
                 * Formatear la tabla para mejorar la UI
                 *
                 * @param   htmlString    html retornado del back
                 */
                function beautifyUsuarioHTML(htmlString) {
                    // const dom = parseHTML(htmlString);
                    const template = document.createElement('template');
                    template.innerHTML = htmlString.trim();
                    const dom = template.content;

                    /* 1️⃣ Tabla principal */
                    dom.querySelectorAll('table').forEach(table => {
                        table.classList.add(
                            'table',
                            'table-bordered',
                            'table-sm',
                            'align-middle',
                            'mb-3'
                        );
                    });

                    /* 2️⃣ Filas como cards visuales */
                    dom.querySelectorAll('tr.item_usuario').forEach(tr => {
                        tr.classList.add('border', 'rounded', 'p-2', 'mb-3');
                    });

                    /* 3️⃣ Inputs Bootstrap */
                    dom.querySelectorAll('input[type="text"], input[type="email"]').forEach(input => {
                        input.classList.add('form-control', 'form-control-sm');
                    });

                    /* 4️⃣ Selects Bootstrap */
                    dom.querySelectorAll('select').forEach(select => {
                        select.classList.add('form-select', 'form-select-sm');
                    });

                    /* 5️⃣ Labels */
                    dom.querySelectorAll('label').forEach(label => {
                        label.classList.add('form-label', 'fw-semibold');
                    });

                    /* 6️⃣ Botón eliminar */
                    dom.querySelectorAll('button').forEach(btn => {
                        btn.classList.add('btn', 'btn-outline-danger', 'btn-sm');
                        btn.setAttribute('data-rel', 'remove');
                        btn.innerHTML = '<i class="fa fa-minus" aria-hidden="true"></i>';
                    });

                    /* 7️⃣ Ocultos */
                    dom.querySelectorAll('.hide').forEach(el => {
                        el.classList.add('d-none');
                        el.classList.remove('hide');
                    });

                    /* 8️⃣ row-fluid → row */
                    dom.querySelectorAll('.row-fluid').forEach(el => {
                        el.classList.add('row');
                        el.classList.remove('row-fluid');
                    });

                    return dom;
                }

                $("body").on("keyup", 'input[name$="muni"], input[name$="dep"], input[name$="pais"]', function() {
                    if ($(this).attr('autocomplete') === undefined) {
                        addAutocomple(this);
                    };
                });

                $("#asu").keypress(function() {
                    if ($("#asu").val().length <= 10) {
                        $('#asu').parent().removeClass('state-success').addClass('state-error');
                    } else {

                        $('#asu').parent().removeClass('state-error').addClass('state-success');
                    }
                });

                function addAutocomple(element) {
                    var accion = $(element).attr('name').split("_")[4];
                    var group = $(element).attr('name').split("_")[2] + "_" + $(element).attr('name').split("_")[3];

                    $(element).autocomplete({
                        source: function(request, response) {
                            if (accion == "muni" && $('input[name$="' + group + '_dep_codigo"]').val() == 0) {
                                alert("Debe seleccionar primero un Departamento de manera correcta." + accion);
                                $('input[name$="' + group + '_dep"]').focus();
                            }
                            $.ajax({
                                url: "./ajax_buscarDivipola.php",
                                dataType: "json",
                                type: 'POST',
                                maxRows: 12,
                                data: {
                                    'action': accion,
                                    'search': request.term,
                                    'muni': $('input[name$="' + group + '_muni"]').val(),
                                    'dep': $('input[name$="' + group + '_dep"]').val(),
                                    'pais': $('input[name$="' + group + '_pais"]').val()
                                },
                                success: function(data) {
                                    response($.map(data, function(item) {
                                        return {
                                            label: item.NOMBRE,
                                            id: item.CODIGO
                                        }
                                    }));
                                    if (accion == "dep") {
                                        $('input[name$="' + group + '_dep_codigo"]').val('0');
                                        $('input[name$="' + group + '_dep"]').parent().removeClass('state-success').addClass('state-error');
                                    }
                                    if (accion == "muni") {
                                        $('input[name$="' + group + '_muni_codigo"]').val('0');
                                        $('input[name$="' + group + '_muni"]').parent().removeClass('state-success').addClass('state-error');
                                    }

                                    $('.ui-autocomplete-input').removeClass('ui-autocomplete-loading');
                                }
                            });
                        },
                        minLength: 1,
                        select: function(event, ui) {
                            var setempty = $(this).attr('name').split("_")[4];
                            var namehiddent = $(this).attr('name') + "_codigo";
                            var nameinput = $(this).attr('name');
                            $("input[name=" + namehiddent + "]").val(ui.item.id);
                            switch (setempty) {
                                case 'muni':
                                    $('input[name$="' + group + '_muni"]').parent().removeClass('state-error').addClass('state-success');
                                    $('#asu').focus();
                                    break;
                                case 'dep':
                                    $('input[name$="' + group + '_muni"]').val('');
                                    $('input[name$="' + group + '_muni_codigo"]').val('');
                                    $('input[name$="' + group + '_dep"]').parent().removeClass('state-error').addClass('state-success');
                                    $('input[name$="' + group + '_muni"]').focus();
                                    break;

                                case 'pais':
                                    $('input[name$="' + group + '_muni"]').val('');
                                    $('input[name$="' + group + '_muni_codigo"]').val('');
                                    $('input[name$="' + group + '_dep"]').val('');
                                    $('input[name$="' + group + '_dep_codigo"]').val('');
                                    $('input[name$="' + group + '_pais"]').parent().removeClass('state-error').addClass('state-success');
                                    $('input[name$="' + group + '_dep"]').focus();
                                    break;
                            }
                        }
                    });
                }

                //Deja en blanco los campos de busqueda al seleccionar
                //un nuevo usuario.
                document.getElementById('tipo_usuario').addEventListener('change', function() {
                    const fields = ['documento_us', 'nombre_us', 'telefono_us', 'mail_us'];

                    fields.forEach(id => {
                        const input = document.getElementById(id);
                        input.value = '';

                        const parent = input.parentElement;
                        parent.classList.remove('state-success', 'state-error');
                    });

                    document.getElementById('resBusqueda').innerHTML = '';

                    document.getElementById('showAnswer').classList.add('d-none');
                });

                /* Capitaliza la primera letra de un texto */
                function uppFirs(txt = '') {
                    if (!txt) return '';
                    return txt.charAt(0).toUpperCase() + txt.slice(1).toLowerCase();
                }

                /**
                 * Valida los campos antes de ser enviados al servidor
                 * @objData array de los datos a validar
                 * @returns boolean true si pasa la validacion, false si no la pasa
                 */
                function validate(objData) {
                    let pass = false;
                    const min = 3;
                    let allEmpty = 0;
                    let allData = 0;

                    if (!objData || Object.keys(objData).length === 0) {
                        return false;
                    }

                    Object.keys(objData).forEach(key => {
                        const field = objData[key];
                        const value = field.value || '';
                        const input = document.getElementById(field.id);
                        const parent = input?.parentElement;

                        allData++;

                        // ❗ Regex corregida (la original estaba mal)
                        const invalidChars = !/^[a-zA-Z0-9áéíóúÁÉÍÓÚÑñ\s]+$/.test(value);

                        if ((value.length < min && value.length !== 0) || invalidChars) {
                            parent?.classList.remove('state-success');
                            parent?.classList.add('state-error');
                            delete objData[key];

                        } else if (value.length === 0) {
                            parent?.classList.remove('state-success', 'state-error');
                            delete objData[key];
                            allEmpty++;

                        } else {
                            parent?.classList.remove('state-error');
                            parent?.classList.add('state-success');
                            pass = true;
                        }
                    });

                    // Si todos están vacíos
                    if (allData === allEmpty) {
                        document.getElementById('resBusqueda').innerHTML = '';
                        document.getElementById('showAnswer').classList.add('d-none');
                    }

                    return pass;
                }

                /**
                 * Funcion para retornar los usuarios seleccionados y mostrarlos
                 * en la tabla seleccionado con las opciónes de modificaciones individuales
                 * @iddata array de los datos ya seleccionados
                 * @returns inserta html procesado a la tabla de usuarios seleccionados
                 */
                function passDataToTable(iddata) {
                    ALLDATA[iddata]["NECESITA_NOTIFICACION"] = RADICACION_NOTIFICACION;
                    ALLDATA[iddata]["TIPO_RADICADO"] = TIPO_RADICADO;

                    const trTable = [ALLDATA[iddata]];

                    fetch("./ajax_buscarUsuario.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
                            },
                            body: new URLSearchParams({
                                addUser: JSON.stringify(trTable)
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            const improvedHTML = beautifyUsuarioHTML(data[0]);

                            // document.getElementById("tableshow").insertAdjacentHTML("beforeend", data[0]);
                            document.getElementById("tableshow").append(improvedHTML);
                            document.getElementById("tableSection").classList.remove("d-none");
                        })
                        .catch(error => {
                            console.error("Error:", error);
                        });
                }

                //Modifica respuesta del servidor para presentarla con formato.
                function formatAnswer(data) {
                    const indiv = document.getElementById('resBusqueda');
                    indiv.innerHTML = '';

                    data.forEach((item, i) => {
                        const li = document.createElement('li');
                        indiv.appendChild(li);

                        const nombre = item.NOMBRE ? item.NOMBRE.replace(/\w\S*/g, uppFirs) : '';

                        const apell = item.APELLIDO ? item.APELLIDO.replace(/\w\S*/g, uppFirs) : '';

                        const telef = item.TELEF || '';
                        const email = item.EMAIL ? item.EMAIL.toLowerCase() : '';
                        const cedula = item.CEDULA || '';
                        const direccion = item.DIRECCION || '';

                        const div = document.createElement('div');
                        div.className = 'col-12 col-md-4';
                        div.setAttribute('name', 'cod_' + i);
                        div.setAttribute('tabindex', '5');

                        div.innerHTML = `
                                    <div class="col col-12">
                                        <h6 class="text-success semi-bold">
                                            ${cedula}
                                            <i title="agregar a ${nombre} ${apell}" class="fa fa-plus-square"></i>
                                        </h6>
                                    </div>
                                    <div class="showdot176"><b>${nombre} ${apell}</b></div>
                                    <div class="showdot176">${telef}</div>
                                    <div class="showdot176">${email}</div>
                                    <div class="showdot176">${direccion}</div>
                                `;

                        div.addEventListener('click', function() {
                            const codUser = this.getAttribute('name').substring(4);
                            passDataToTable(codUser);

                            this.classList.add('d-none');

                            const datali = document.querySelectorAll('#showAnswer ul li');
                            let count = 0;

                            datali.forEach(li => {
                                const childDiv = li.querySelector('div');
                                if (childDiv && childDiv.classList.contains('d-none')) {
                                    count++;
                                }
                            });

                            document.getElementById('showAnswer').classList.add('d-none');
                        });

                        li.appendChild(div);
                    });

                    document.getElementById('showAnswer').classList.remove('d-none');
                }

                /**
                 * Funcion para retornar los destinatarios seleccionados y mostrarlos
                 * en la tabla con las opciónes de modificaciones individuales
                 * @iddata int indice del array correspondiente al destinatario escogido
                 * @returns inserta html procesado al campo de destinarios seleccionados
                 */
                function passDestinatariosDataToTable(iddata) {
                    const trTable = [ALLDATA[iddata]];

                    fetch('./ajax_buscarUsuario.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                            },
                            body: new URLSearchParams({
                                addDestinatariosCircular: JSON.stringify(trTable)
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data || !data[0]) return;

                            document.getElementById('tableshow')
                                .insertAdjacentHTML('beforeend', data[0]);

                            document.getElementById('tableSection')
                                .classList.remove('d-none');
                        })
                        .catch(error => {
                            console.error('Error al agregar destinatarios:', error);
                        });
                }

                // Modifica respuesta del servidor para presentarla con formato.
                function formatAnswerDestinatario(data) {
                    const indiv = document.getElementById('resBusqueda');
                    const boton = 'Usar destinatarios';

                    indiv.innerHTML = '';

                    data.forEach((item, i) => {
                        // <li>
                        const li = document.createElement('li');
                        li.style.display = 'inline';
                        li.style.listStyleType = 'none';

                        // <div>
                        const div = document.createElement('div');
                        div.style.width = '100%';
                        div.classList.add('well', 'well-sm');
                        div.setAttribute('name', 'cod_' + i);

                        div.innerHTML = `
                                    <div class="col-12">
                                        <h6 class="text-success semi-bold">
                                            ${boton} <i class="fa fa-plus-square"></i>
                                        </h6>
                                    </div>
                                    <div><b>${item.DESTINATARIOS}</b></div>
                                `;

                        div.addEventListener('click', function() {
                            const codUser = this.getAttribute('name').substring(4);
                            const datali = document.querySelectorAll('#showAnswer ul li');

                            passDestinatariosDataToTable(codUser);
                            this.classList.add('d-none');

                            let count = 0;
                            datali.forEach(li => {
                                const childDiv = li.querySelector('div');
                                if (childDiv && childDiv.classList.contains('d-none')) {
                                    count++;
                                }
                            });

                            document.getElementById('showAnswer').classList.add('d-none');
                        });

                        li.appendChild(div);
                        indiv.appendChild(li);
                    });

                    document.getElementById('showAnswer').classList.remove('d-none');
                }

                //Autocomplete busqueda de usuarios
                const camposBusqueda = [
                    'documento_us',
                    'nombre_us',
                    'telefono_us',
                    'mail_us'
                ];

                camposBusqueda.forEach(id => {
                    const input = document.getElementById(id);

                    if (!input) return;

                    input.addEventListener('keyup', function(e) {
                        const tipo = document.getElementById('tipo_usuario')?.value;

                        if (!tipo) {
                            e.preventDefault();
                            alert('Por favor seleccione el tipo de usuario que desea buscar.');
                        }
                    });
                });

                document.getElementById('idconsulta').addEventListener('click', function(e) {
                    e.preventDefault();

                    let data = {};

                    data.docu = {
                        value: document.getElementById('documento_us').value,
                        id: 'documento_us'
                    };
                    data.name = {
                        value: document.getElementById('nombre_us').value,
                        id: 'nombre_us'
                    };
                    data.tele = {
                        value: document.getElementById('telefono_us').value,
                        id: 'telefono_us'
                    };
                    data.mail = {
                        value: document.getElementById('mail_us').value,
                        id: 'mail_us'
                    };

                    if (validate(data)) {
                        data.tdoc = document.getElementById('tipo_usuario').value;

                        fetch('./ajax_buscarUsuario.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: new URLSearchParams({
                                    search: JSON.stringify(data)
                                })
                            })
                            .then(response => response.json())
                            .then(responseData => {
                                ALLDATA = responseData;

                                if (responseData !== null) {
                                    formatAnswer(responseData);
                                }
                            })
                            .catch(error => {
                                console.error('Error en la consulta:', error);
                            });
                    }
                });

                /**
                 * Autocomplete busqueda de destinatarios para circulares
                 */
                // const destinatarioInput = document.getElementById('destinatario_us');

                document.body.addEventListener('keyup', function(e) {
                    const el = e.target;

                    // ⬅️ Validación AQUÍ: solo si el elemento existe y es el correcto
                    if (!el || el.id !== 'destinatario_us') return;

                    const data = {};
                    data.name = {
                        value: destinatarioInput.value,
                        id: 'destinatario_us'
                    };

                    if (validate(data)) {
                        const tipoUsuario = document.getElementById('tipo_usuario');
                        if (!tipoUsuario) return;

                        data.tdoc = tipoUsuario.value;

                        fetch('./ajax_buscarUsuario.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                                },
                                body: new URLSearchParams({
                                    searchDestinatarios: JSON.stringify(data)
                                })
                            })
                            .then(response => response.json())
                            .then(responseData => {
                                ALLDATA = responseData;

                                if (responseData !== null) {
                                    formatAnswerDestinatario(responseData);
                                }
                            })
                            .catch(error => {
                                console.error('Error en la búsqueda de destinatarios:', error);
                            });
                    }
                });

                // Mostrar validacion del formulario
                function mostrarAlert(objAlert) {
                    const {
                        type,
                        message
                    } = objAlert;

                    const alertContainer = document.getElementById('alertmessage');

                    const div = document.createElement('div');
                    div.className = `alert alert-${type} alert-dismissible `;
                    div.role = 'alert';

                    div.innerHTML = `
                                <strong>${message}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            `;

                    alertContainer.appendChild(div);
                }

                // Borrar alertas
                function borrarAlert() {
                    const alertContainer = document.getElementById('alertmessage');
                    alertContainer.innerHTML = '';
                }

                //****************************************************************************************//
                // Validacion de correos electronicos
                function validarEmail(idxEmail, emailId) {
                    console.log('validarEmail');
                    console.log(idxEmail, emailId);

                    const valEmaile = document.getElementById('errormail');
                    let correosValid = [];
                    const mailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    const regex = /^[^ ]+$/;

                    const correosProhibidos = [
                        'sarlaft@almamater.hospital',
                    ];
                    const correosPermitidos = [
                        'yulicita1982-@hotmail.com',
                        'corporativo@almamater.hospital',
                        'cocampo@diazyocampo.legal',
                        'notificaciones@diazyocampo.legal',
                        'contabilidad@fracturasyfracturas.com.co',
                        'juridico@fracturasyfracturas.com.co',
                        'notificaciones@diazyocampo.legal',
                        'ALCALDIA@OROCUE-CASANARE.GOV.CO',
                        'CONTACTENOS@CABUYARO-META.GOV.CO'
                    ];

                    if (correosProhibidos.includes(idxEmail)) {
                        swal({
                            title: "Advertencia!",
                            text: `El correo: [${idxEmail}] No es permitido en el sistema, este correo se eliminará del campo!!`,
                            icon: "warning",
                        })
                        emailId.focus();
                        valEmaile.value = 1;
                    } else if (!correosPermitidos.includes(idxEmail) && idxEmail.match(regex) == null) {
                        swal({
                            title: "Advertencia!",
                            text: `La estructura del correo: [${idxEmail}] solo permite -_`,
                            icon: "warning",
                        })
                        emailId.focus()
                        valEmaile.value = 1

                    } else if (!correosPermitidos.includes(idxEmail) && idxEmail.match(mailRegex) == null || !idxEmail) {
                        //alert(`El correo: [${idxEmail}] No cumple con la estructura para ser un correo electronico`);
                        swal({
                            title: "Advertencia!",
                            text: `El correo: [${idxEmail}] No cumple con la estructura para ser un correo electronico`,
                            icon: "warning",
                        })
                        emailId.focus()
                        valEmaile.value = 1;
                    } else {
                        correosValid.push(idxEmail)
                        valEmaile.value = 0
                    }

                    // Expresión regular para los dominios permitidos
                    const dominiosNoPermitidos = /^(?:[a-zA-Z0-9._%+-]+@(hotmaill\.com|gmaill\.com|outlookk\.com|yaoo\.com|yahooo\.es|almmamater\.hospitall|fracturasfracturas\.com\.co|ORCUE-CASANARE\.gov\.co|CABULLARO-META\.gov\.co))$/;

                    // Verifica si el correo pertenece a uno de los dominios permitidos
                    if (dominiosNoPermitidos.test(idxEmail)) {
                        swal({
                            title: "Advertencia!",
                            text: `El correo: [${idxEmail}] no tiene el dominio adecuado. Los dominios permitidos son @hotmail.com, @gmail.com, @outlook.com, @yahoo.com, @yahoo.es, @almamater.hospital, @fracturasyfracturas.com.co, @OROCUE-CASANARE.gov.co, o @CABUYARO-META.gov.co.....`,
                            icon: "warning",
                        })
                        valEmaile.value = 1;
                    }
                    //emailId.value = idxEmail.replace(/;+$/, '');
                }

                //Radicar documento nuevo
                document.body.addEventListener('click', function(e) {
                    if (!e.target.closest('.radicarNuevo, #modificaRad')) return;

                    if (EJECUCION) return;

                    const btn = e.target.closest('.radicarNuevo, #modificaRad');
                    const acction = btn.id;
                    let pass = true;
                    const idsession = '<?= $idsession ?>';

                    borrarAlert();

                    // Eliminar correos duplicados
                    function eliminarCorreosDuplicados(correo) {
                        console.log('eliminarCorreosDuplicados');
                        console.log(correo);

                        // Divide la cadena en correos separados por ';'
                        let correos = correo.split(';');
                        // Usa un Set para almacenar solo correos únicos
                        let correosUnicos = new Set();
                        // Itera sobre los correos y agrega solo los que no están duplicados
                        correos.forEach(correo => {
                            // Elimina espacios adicionales
                            correo = correo.trim();
                            // Verifica que el correo tenga un formato válido (opcional)
                            if (/^[\w\.-]+@[\w\.-]+\.\w+$/.test(correo)) {
                                correosUnicos.add(correo);
                            }
                        });
                        // Une los correos únicos de vuelta a una cadena separada por ';'
                        return Array.from(correosUnicos).join(';');
                    }

                    /*************************************************************************************************************************************************/
                    // Validaciones Campo de correo eléctronico y VALIDACIONES MEDIO DE RECEPCION
                    /*************************************************************************************************************************************************/
                    let medi_recepcion = document.getElementById('mrecep');
                    let ent = <?= $ent ?>;

                    for (let i = 1; i <= 50; i++) {

                        const emailInput = document.getElementById(`id_ema_${i}`);
                        const dirInput = document.getElementById(`id_dir_${i}`);

                        if (!emailInput) continue;

                        if (emailInput.value) {
                            emailInput.value = eliminarCorreosDuplicados(emailInput.value);
                        }

                        console.log('emailInput', emailInput.value);

                        const emails = emailInput.value.split(';');

                        console.log('emails', emails);

                        emails.forEach(email => {
                            if ((ent === 6 || ent === 7) && !email && !dirInput.value.trim()) {
                                swal({
                                    title: "Advertencia!",
                                    text: "Debe digitar el correo electronico o la direccion",
                                    icon: "warning",
                                });

                                mostrarAlert({
                                    type: 'danger',
                                    message: `Error: destinatario No. ${i} sin correo ni dirección`
                                });

                                pass = false;
                            }

                            if (email) {
                                validarEmail(email, emailInput)
                            };
                        });
                    }

                    /*************************************************************************************************************************************************/
                    //Folios y Anexos
                    const nofolios = document.getElementById('nofolios');
                    const noanexos = document.getElementById('noanexos');

                    console.log('nofolios', nofolios);
                    console.log('noanexos', noanexos);

                    if (
                        (nofolios && /[A-Za-z]+$/.test(nofolios.value)) ||
                        (noanexos && /[A-Za-z]+$/.test(noanexos.value))
                    ) {
                        mostrarAlert({
                            type: 'danger',
                            message: 'Escriba un número válido en No de folios o anexos.'
                        });
                        pass = false;
                    }

                    //Fecha del radicado
                    const fecha_doc = document.getElementById('fecha_gen_doc')?.value;
                    console.log('fecha_doc', fecha_doc);

                    const fechaFormateada = fecha_doc ?
                        fecha_doc.split('-').reverse().join('-') :
                        null;

                    console.log('fechaFormateada', fechaFormateada);

                    if (fechaFormateada) {
                        const fechaActual = new Date();
                        const d = fechaFormateada.substring(0, 2);
                        const m = fechaFormateada.substring(3, 5);
                        const y = fechaFormateada.substring(6, 10);
                        const fecha = new Date(y, m - 1, d);
                        const dias = Math.floor((fechaActual - fecha) / 86400000);

                        if (dias > 960 && dias < 1500) {
                            mostrarAlert({
                                type: 'danger',
                                message: 'El documento tiene fecha anterior a 60 días.'
                            });
                            pass = false;
                        } else if (dias > 1500) {
                            mostrarAlert({
                                type: 'danger',
                                message: 'Verifique la fecha del documento.'
                            });
                            pass = false;
                        } else if (dias < 0) {
                            mostrarAlert({
                                type: 'danger',
                                message: 'La fecha es superior a la actual.'
                            });
                            pass = false;
                        }
                    }

                    console.log('RADICACION_CIRCULAR', RADICACION_CIRCULAR);

                    if (RADICACION_CIRCULAR) {
                        if (!document.getElementById('id_destinatario')) {
                            mostrarAlert({
                                type: 'danger',
                                message: 'Seleccione un destinatario'
                            });
                            pass = false;
                        }
                    } else {
                        if (!document.querySelector('input[name^="usuario"]')) {
                            mostrarAlert({
                                type: 'danger',
                                message: 'Seleccione un usuario'
                            });
                            pass = false;
                        }
                    }

                    //Asunto
                    const asuInput = document.getElementById('asu');
                    const BLOQUEO_ENTRADA = <?= $blockEntrada ? 'true' : 'false' ?>;

                    if (!BLOQUEO_ENTRADA && asuInput) {
                        let asu = asuInput.value;

                        if (asu.length < 5) {
                            mostrarAlert({
                                type: 'danger',
                                message: 'Asunto muy corto.'
                            });
                            pass = false;
                        }

                        const max = (TIPO_RADICADO == 1) ? 510 : 350;
                        if (asu.length > max) {
                            mostrarAlert({
                                type: 'danger',
                                message: 'Asunto demasiado largo.'
                            });
                            pass = false;
                        }
                    }

                    //Email
                    if (document.getElementById('errormail')?.value == 1) {
                        mostrarAlert({
                            type: 'danger',
                            message: 'Error en el correo electrónico ingresado.'
                        });
                        pass = false;
                    }

                    //DIRECCION Ò EMAIL EN UN USUARIO NUEVO
                    /******************************************************************************************************/
                    if (!RADICACION_CIRCULAR) {
                        document.querySelectorAll("tr[name='item_usuario']").forEach((tr, index) => {

                            let apellidoInput = tr.querySelector("input[id^='id_apellido_']");
                            let apellido = apellidoInput?.value || '';
                            let direccion = tr.querySelector("input[id^='id_dir_']")?.value || '';
                            let email = tr.querySelector("input[id^='id_ema_']")?.value || '';
                            let nombre = tr.querySelector("input[id^='id_nombre_']")?.value || '';
                            let municipio = tr.querySelector("input[id^='id_muni_']")?.value;
                            let municipioCod = tr.querySelector("input[id^='id_muni_cod_']")?.value;
                            let departamento = tr.querySelector("input[id^='id_dep_']")?.value;
                            let departamentoCod = tr.querySelector("input[id^='id_dep_cod_']")?.value;

                            if (!email.trim() && [1, 2, 3].includes(ent) && medi_recepcion.value == 4) {
                                let textoEmail = ((index + 1) == 1) ? 'El campo correo electrónico esta vacio por favor verificar' : 'Los campos del correo electrónicos estan vacios por favor verificar';
                                swal({
                                    title: "Advertencia!",
                                    text: textoEmail,
                                    icon: "warning"
                                });
                                mostrarAlert({
                                    type: 'danger',
                                    message: `Destinatario No. ${index + 1} le falta correo electrónico - Por favor digitarlo, es obligatorio`
                                });
                                pass = false;
                            }

                            if (!direccion.trim() && [1, 2, 3].includes(ent) && [1, 2].includes(+medi_recepcion.value)) {
                                swal({
                                    title: "Advertencia!",
                                    text: "Para el medio de recepción / envio seleccionado el campo dirección es obligatorio y no debe estar vacío!",
                                    icon: "warning"
                                });
                                mostrarAlert({
                                    type: 'danger',
                                    message: `Destinatario No. ${index + 1} le falta Dirección - Si no reporta escribir Desconocida`
                                });
                                pass = false;
                            }

                            if (!nombre.trim()) {
                                mostrarAlert({
                                    type: 'danger',
                                    message: `Destinatario No. ${index + 1} le falta Nombre - Si no reporta escribir Anónimo`
                                });
                                pass = false;
                            }

                            if (!apellido.trim() && !apellidoInput?.dataset.role) {
                                mostrarAlert({
                                    type: 'danger',
                                    message: `Destinatario No. ${index + 1} le falta Apellido - Si no reporta escribir Anónimo`
                                });
                                pass = false;
                            }

                            if (!municipio || !municipioCod) {
                                mostrarAlert({
                                    type: 'danger',
                                    message: `Destinatario No. ${index + 1} sin municipio`
                                });
                                pass = false;
                            }

                            if (!departamento || !departamentoCod) {
                                mostrarAlert({
                                    type: 'danger',
                                    message: `Destinatario No. ${index + 1} sin departamento`
                                });
                                pass = false;
                            }
                        });
                    }

                    /******************************************************************************************************/
                    //GUIA
                    const guia = document.getElementById('guia');
                    if (guia && guia.value.length > 20) {
                        mostrarAlert({
                            type: 'danger',
                            message: 'Guía con más de 20 caracteres'
                        });
                        pass = false;
                    }

                    //REFERENCIA CUENTA_I
                    const cuentai = document.getElementById('cuentai');
                    if (cuentai && cuentai.value.length > 0 && cuentai.value.length > 100) {
                        mostrarAlert({
                            type: 'danger',
                            message: 'Referencia de cuenta mayor a 100 caracteres'
                        });
                        pass = false;
                    }

                    //Dependencia
                    const dep = document.querySelector('select[name="coddepe"]');
                    if (dep && parseInt(dep.value) === 0) {
                        mostrarAlert({
                            type: 'danger',
                            message: 'Seleccione una dependencia'
                        });
                        pass = false;
                    }

                    if (!pass) {
                        document.querySelectorAll('.radicarNuevo').forEach(b => b.classList.remove('d-none'));
                        return;
                    }

                    if (pass && !EJECUCION) {
                        console.log('pass & EJECUTION');
                        console.log(pass, EJECUCION);

                        // Limpiar alertas
                        borrarAlert();
                        EJECUCION = true;

                        // Serializar formulario (equivalente a $("form").serialize())
                        var form = document.querySelector('form');
                        var formData = new FormData(form);

                        /* Obtener fecha original YYYY-MM-DD */
                        var fechaISO = formData.get('fecha_gen_doc');

                        /* Formatear a DD-MM-YYYY */
                        if (fechaISO) {
                            var partes = fechaISO.split('-'); // [YYYY, MM, DD]
                            var newFecha = partes[2] + '-' + partes[1] + '-' + partes[0];

                            /* Reemplazar valor en el FormData */
                            formData.set('fecha_gen_doc', newFecha);
                        }

                        var datos = new URLSearchParams(formData).toString();
                        var radicado = '';

                        console.log(datos);


                        <?php
                        if ($datos) {
                            echo "datos = datos + '&$javascriptCapDatos';";
                        }
                        ?>

                        console.log("datos");
                        console.log(datos);
                        console.log("acction");
                        console.log(acction);

                        // Eliminar elementos
                        var showRadicar = document.getElementById('showRadicar');
                        if (showRadicar) showRadicar.remove();

                        var copyRadicar = document.getElementById('copyradicar');
                        if (copyRadicar) copyRadicar.remove();

                        // Acción modificar
                        if (acction === "modificaRad") {
                            datos += "&modificar=true";
                        }

                        // Notificación
                        if (RADICACION_NOTIFICACION) {
                            <?php if (!empty($notifica_codi)) { ?>
                                datos += "&notifica_codi=<?= $notifica_codi ?>";
                            <?php } ?>
                        }

                        // Envío AJAX nativo (fetch)
                        fetch("./ajax_radicarNuevo.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
                                },
                                body: datos
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('response server');
                                console.log(data);

                                for (var k in data) {
                                    if (data[k].error !== undefined) {
                                        mostrarAlert({
                                            type: 'danger',
                                            message: data[k].error
                                        });
                                    } else {
                                        if (acction !== "modificaRad") {
                                            radicado = data[k].answer;

                                            console.log('radicado');
                                            console.log(radicado);

                                            var modificaRad = document.getElementById('modificaRad');
                                            modificaRad.insertAdjacentHTML('beforeend', data[k].answer);
                                            modificaRad.insertAdjacentHTML(
                                                'beforeend',
                                                '<input type="hidden" name="nurad" value="' + data[k].answer + '" />'
                                            );

                                            document.getElementById('idrad').insertAdjacentHTML('beforeend', data[k].answer);
                                        } else {
                                            mostrarAlert({
                                                type: 'success',
                                                message: data[k].answer
                                            });
                                        }

                                        document.getElementById('showModificar').classList.remove('d-none');
                                    }
                                }

                                if (acction !== "modificaRad") {

                                    var contentstiker = document.getElementById('skeleton').cloneNode(true);
                                    contentstiker.classList.remove('hide');
                                    contentstiker = contentstiker.outerHTML.replace(/xxxxxx/g, radicado);

                                    var contentverrad = document.getElementById('skeleton8').cloneNode(true);
                                    contentverrad.classList.remove('hide');
                                    contentverrad = contentverrad.outerHTML.replace(/xxxxxx/g, radicado);

                                    var contentasocia = document.getElementById('skeleton9').cloneNode(true);
                                    contentasocia.classList.remove('hide');
                                    contentasocia = contentasocia.outerHTML.replace(/xxxxxx/g, radicado);

                                    var contenttipifica = document.getElementById('skeleton10').cloneNode(true);
                                    contenttipifica.classList.remove('hide');
                                    contenttipifica = contenttipifica.outerHTML.replace(/xxxxxx/g, radicado);

                                    document.getElementById('sticker').innerHTML = contentstiker + contentverrad;
                                    document.getElementById('asociar').innerHTML = contentasocia;
                                    document.getElementById('tipificar').innerHTML = contenttipifica;
                                }

                                <?php if (isset($uid)) { ?>
                                    window.parent.filed(radicado, <?= $uid ?>);
                                <?php } ?>

                                document.getElementById('inforshow').classList.remove('hide');
                                document.getElementById('showModificar').classList.remove('d-none');

                                var copy = document.getElementById('copyradicar');
                                if (copy) {
                                    copy.innerHTML = document.getElementById('showModificar').cloneNode(true).outerHTML;
                                }
                            })
                            .catch(err => {
                                console.log("err server");
                                console.log(err);

                                var errMsg = 'Error de creación/modificación del radicado. Reporte al administrador código http: ' + err.status;
                                /*mostrarAlert({
                                    type: 'danger',
                                    message: errMsg
                                });*/
                            })
                            .finally(() => {
                                EJECUCION = false;
                            });
                    }
                });

                // SOLO TEXTO (delegación en body)
                document.body.addEventListener('keypress', function(event) {
                    var target = event.target;

                    if (target.matches('*[data-rel="solo-text"]')) {
                        var regex = /^[a-zA-ZáÁéÉíÍóÓúÚñÑ ]+$/;
                        var charCode = event.charCode || event.which;
                        var key = String.fromCharCode(charCode);

                        if (!regex.test(key)) {
                            event.preventDefault();
                            return false;
                        }
                    }
                });

                // REMOVER FILA POR data-rel="remove"
                document.body.addEventListener('click', function(event) {
                    console.log(event);
                    
                    var removeDetail = event.target.closest('[data-rel="remove"]');

                    if (removeDetail && removeDetail.matches('*[data-rel="remove"]')) {
                        // buscar la clase 'tooltip' o 'ui-tooltip'
                        var visualTooltips = document.querySelectorAll('.tooltip, .ui-tooltip, .tipsy, .tooltipster-base');

                        visualTooltips.forEach(function(el) {
                            el.remove();
                        });

                        var tr = removeDetail.closest('tr.item_usuario');
                        if (tr) tr.remove();
                    }

                    var raditDocument = event.target.closest('[title="Radicar documento"]');
                    console.log(raditDocument);
                    if (raditDocument && raditDocument.matches('*[title="Radicar documento"]')) {
                        // buscar la clase 'tooltip' o 'ui-tooltip'
                        var visualTooltipsRadiDocument = document.querySelectorAll('.ui-tooltip-content');

                        visualTooltipsRadiDocument.forEach(function(el) {
                            el.remove();
                        });
                    }
                });

                // ELIMINAR USUARIO DESDE ICONO DE BÚSQUEDA
                document.body.addEventListener('click', function(event) {
                    var target = event.target;

                    if (target.matches('.search-table-icon')) {
                        var item = target.closest('.item_usuario');
                        if (item) item.remove();
                    }
                });

                // CONTADOR DE ASUNTO
                var asu = document.getElementById('asu');
                if (asu) {
                    asu.addEventListener('input', function(e) {
                        var textoAsunto = '';

                        if (TIPO_RADICADO >= 4) {
                            textoAsunto = '* Asunto / ep&iacute;grafe ';
                        } else {
                            textoAsunto = '* Asunto ';
                        }

                        textoAsunto += asu.value.length + "/" + e.target.maxLength;

                        var lbAsunto = document.getElementById('lbAsunto');
                        if (lbAsunto) {
                            lbAsunto.innerHTML = textoAsunto;
                        }
                    });
                }

                // SOLO NÚMEROS EN documento_us
                var documentoUs = document.getElementById('documento_us');
                if (documentoUs) {
                    documentoUs.addEventListener('keydown', function(e) {
                        var key = e.keyCode;

                        if (
                            !(
                                key === 8 || // backspace
                                key === 9 || // tab
                                key === 32 || // space
                                key === 46 || // delete
                                (key >= 35 && key <= 40) || // arrows/home/end
                                (key >= 48 && key <= 57) || // numbers
                                (key >= 96 && key <= 105) // numpad
                            ) ||
                            key === 81 || // Q
                            key === 225 || // AltGr
                            key === 16 // Shift
                        ) {
                            e.preventDefault();
                        }
                    });
                }

                // CONTROL DE PEGADO (PASTE)
                document.body.addEventListener('paste', function(event) {
                    var target = event.target;

                    if (
                        target.matches('[data-rel="solo-text"]') ||
                        target.matches('[id^="id_telefono"]') ||
                        target.matches('[id^="id_dir"]') ||
                        target.matches('#asu, #ane, #telefono_us, #mail_us')
                    ) {
                        event.preventDefault();

                        var regex = /[^a-zA-Z0-9áÁéÉíÍóÓúÚñÑ!@#$%^&*()_+\-=\[\]{}|;:,.<>¿?/\\'"\s]/g;

                        if (target.matches('[data-rel="solo-text"]')) {
                            regex = /[^a-zA-ZáÁéÉíÍóÓúÚñÑ ]/g;
                        }

                        var clipboardData = event.clipboardData || window.clipboardData;
                        var paste = clipboardData.getData('text');

                        paste = paste
                            .replace(regex, '')
                            .replace(/\s+/g, ' ')
                            .trim();

                        target.setRangeText(
                            paste,
                            target.selectionStart,
                            target.selectionEnd,
                            'end'
                        );
                    }
                });

                const select = document.querySelector("select[name='empTrans']");
                if (select) {
                    select.classList.add("form-select");
                }
            });
        </script>
    </div>
</body>

</html>
<?php

session_start();

$ruta_raiz = "..";
if (!$_SESSION['dependencia']) {
    header("Location: $ruta_raiz/cerrar_session.php");
}

$krd = $_SESSION["krd"];
foreach ($_GET as $key => $valor) {
    ${$key} = $valor;
}

foreach ($_POST as $key => $valor) {
    ${$key} = $valor;
}

$nomcarpeta = isset($_GET["carpeta"]) ? $_GET["carpeta"] : '';
$tipo_carpt = isset($_GET["tipo_carpt"]) ? $_GET["tipo_carpt"] : '';
$orderNo = isset($_GET["orderNo"]) ? $_GET["orderNo"] : '';
$orderTipo = isset($_GET["orderTipo"]) ? $_GET["orderTipo"] : '';
$tipoEstadistica = isset($_REQUEST["tipoEstadistica"]) ? $_REQUEST["tipoEstadistica"] : '';
$genDetalle = isset($_GET["genDetalle"]) ? $_GET["genDetalle"] : '';
$dependencia_busq = isset($_GET["dependencia_busq"]) ? $_GET["dependencia_busq"] : '';
$fecha_ini = isset($_GET["fecha_ini"]) ? $_GET["fecha_ini"] : '';
$fecha_fin = isset($_GET["fecha_fin"]) ? $_GET["fecha_fin"] : '';
$codus = isset($_GET["codus"]) ? $_GET["codus"] : '';
$tipoRadicado = isset($_GET["tipoRadicado"]) ? $_GET["tipoRadicado"] : '';

$codUs = isset($_GET["codUs"]) ? $_GET["codUs"] : '';
$fecSel = isset($_GET["fecSel"]) ? $_GET["fecSel"] : '';
$genDetalle = isset($_GET["genDetalle"]) ? $_GET["genDetalle"] : '';
$generarOrfeo = isset($_GET["generarOrfeo"]) ? $_GET["generarOrfeo"] : '';
$dependencia_busqOri = isset($_GET["dependencia_busqOri"]) ? $_GET["dependencia_busqOri"] : '';

$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
$tip3Nombre = $_SESSION["tip3Nombre"];
$tip3desc = $_SESSION["tip3desc"];
$tip3img = $_SESSION["tip3img"];
$usua_perm_estadistica = $_SESSION["usua_perm_estadistica"];
include_once "$ruta_raiz/include/db/ConnectionHandler.php";

$db = new ConnectionHandler($ruta_raiz);
//$db->conn->debug =true;
$sqlConcat = $db->conn->Concat("depe_codi ", "'-'", " lower(depe_nomb)");
if ($usua_perm_estadistica > 1) {
    $sql = "select $sqlConcat ,depe_codi from dependencia
    order by depe_codi";
    $rsDep = $db->conn->Execute($sql);
    //$dependencia
    $optionDep = $rsDep->GetMenu2("dependencia_busq", 99999, '99999:-- Todas las Dependencias --', false, "", " id='dependencia_busq' class=' text-capitalize custom-select'  data-live-search='true'");
} else {
    $sql = "select $sqlConcat ,depe_codi from dependencia where DEPE_CODI=$dependencia
    order by depe_codi";
    $rsDep = $db->conn->Execute($sql);
    $optionDep = $rsDep->GetMenu2("dependencia_busq", "$dependencia", false, false, " ", " id='dependencia_busq'  class='custom-select text-capitalize ' ");
}
$rs = $db->conn->Execute('select SGD_TRAD_DESCR, SGD_TRAD_CODIGO  from SGD_TRAD_TIPORAD order by 2');
$nmenu = "tipoRadicado";
$valor = "";
$itemBlanco = " -- Todos los Tipos de Radicado -- ";
$tipoRad = $rs->GetMenu2($nmenu, "", $blank1stItem = "$valor:$itemBlanco", false, 0, 'class="form-control text-capitalize " id="' . $nmenu . '"');

$ano_ini = date("Y");
$mes_ini = substr("00" . (date("m") - 1), -2);
if ($mes_ini == "00") {
    $ano_ini = $ano_ini - 1;
    $mes_ini = "12";
}
$dia_ini = date("d");
if ($mes_ini == '02' && $dia_ini > 28) {
    $dia_ini = 28;
}

if (!$fecha_ini) {
    $fecha_ini = "$ano_ini-$mes_ini-$dia_ini";
}
$optResp = '';
$reportes[1]['Nomb'] = 'Radicación- Consulta de Radicados por Usuario';
$reportes[1]['leyend'] = 'Este reporte muestra la cantidad de radicados generados por usuario. Se puede discriminar por tipo de radicación.';
$reportes[2]['Nomb'] = 'Radicación- Estadística Por Medio de Recepción- Envío';
$reportes[2]['leyend'] = 'Este reporte genera la cantidad de radicados de acuerdo al medio de recepción, realizado al momento de la radicación.';
$reportes[3]['Nomb'] = 'Radicación- Estadística de Medio Envío Final de Documentos';
$reportes[3]['leyend'] = 'Este reporte genera la cantidad de radicados enviados a su destino final por el área.';
$reportes[4]['Nomb'] = 'Radicación: Digitalización de Documentos';
$reportes[4]['leyend'] = 'Este reporte genera la cantidad de radicados digitalizados por usuario y el total de hojas digitalizadas. Se puede seleccionar el tipo de radicación.';
/**$reportes[5]['Nomb'] = 'Radicados de Entrada Recibos del Area de Correspondencia';
$reportes[5]['leyend'] = 'Este reporte genera la cantidad de documentos de entrada radicados del área de correspondencia a una dependencia.';
 */
$reportes[6]['Nomb'] = 'Radicados actuales en la dependencia';
$reportes[6]['leyend'] = 'Este reporte genera la cantidad de documentos de entrada radicados del área de correspondencia a una dependencia.';
$reportes[7]['Nomb'] = 'Control entrega de correspondencia recibida';
$reportes[7]['leyend'] = 'Este reporte genera la cantidad de documentos de entrada radicados del área de correspondencia a una dependencia.';
/**$reportes[8]['Nomb'] = 'ESTADISTICA POR RADICADOS Y SUS RESPUESTAS';
$reportes[8]['leyend'] = 'Este reporte genera la cantidad de documentos de entrada radicados del área de correspondencia a una dependencia.';*/
$reportes[9]['Nomb'] = 'Informe Tramite de Radicados de Entrada';
$reportes[9]['leyend'] = 'Reporte que Muestra la gestión de radicados de entrada ';
$reportes[10]['Nomb'] = 'Gestión De Radicados de Entrada';
$reportes[10]['leyend'] = 'Reporte que Muestra la gestión de radicados de entrada';
$reportes[11]['Nomb'] = 'Gestión De Radicados de Salida';
$reportes[11]['leyend'] = 'Reporte que Muestra la gestión de radicados de Salida';
$reportes[12]['Nomb'] = 'Gestión De Radicados de Memorandos';
$reportes[12]['leyend'] = 'Reporte que Muestra la gestión de radicados de Memorandos';
$reportes[30]['Nomb'] = 'Reporte De Borradores';
$reportes[30]['leyend'] = 'Reporte De Borradores';
$reportes[30]['reporteId'] = 15;
$reportes[14]['Nomb'] = 'Reporte jefes de area';
$reportes[14]['leyend'] = 'Reporte que los jefes actuales de las dependencias';
$reportes[17]['Nomb'] = 'Reporte expedientes por área';
$reportes[17]['leyend'] = 'Reporte expedientes por área';

foreach ($reportes as $key => $value) {
    $reporteId = isset($value['reporteId']) ? $value['reporteId'] : $key;
    $optResp .= "<option value='$key' data-inforp='{$value['leyend']}'>$reporteId - {$value['Nomb']}</option>";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen" href="../img/favicon.png">
    <!-- Bootstrap core CSS-->
    <!--<link rel="stylesheet" type="text/css" media="screen" href="../estilos/smartadmin-production.css">-->
    <!--<link rel="stylesheet" type="text/css" media="screen" href="../estilos/smartadmin-skins.css"> -->
    <link rel="stylesheet" type="text/css" media="screen" href="../estilos/bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../estilos/bootstrap-select.min.css">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" type="text/css" media="screen" href="../estilos/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" media="screen" href="../include/DataTables/datatables.css">

    <link rel="stylesheet" type="text/css" media="screen" href="../estilos/argo.css">
    <!--<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />-->

    <title>Estadisiticas - SuperArgo</title>
    <style>
        /* Estilos adicionales para mejorar la apariencia en BS4 */
        .card {
            border-radius: 10px;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .05);
        }

        .input-group-text {
            font-size: 0.82rem;
            color: #555;
        }

        .custom-select {
            cursor: pointer;
        }

        .btn {
            border-radius: 6px;
        }

        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
        }

        /* Para corregir el ancho de los selects generados por PHP si es necesario */
        .custom-select-container select {
            width: 100%;
            height: calc(1.5em + 0.5rem + 2px);
            padding: 0.25rem 0.5rem;
            font-size: .875rem;
            border: 1px solid #ced4da;
            border-radius: 0 4px 4px 0;
        }
    </style>
</head>

<body>
    <noscript>
        <span class="warningjs">
            Aviso: La ejecución de JavaScript está deshabilitada en su navegador. Es posible que no
            pueda responder todas las preguntas de la encuesta. Por favor, verifique la configuración de su
            navegador.
        </span>
    </noscript>
    <br>
    <div class="col-12 mt-3">
        <section id="widget-grid">
            <div class="row">
                <article class="col-12">
                    <div class="card shadow-sm border-light">
                        <div class="card-header bg-primary py-3">
                            <h5 class="m-0 font-weight-bold text-white">
                                <i class="fa fa-chart-bar mr-2"></i> Panel de Estadísticas
                            </h5>
                        </div>
                        <div class="card-body bg-light-50">
                            <div class="row">
                                <div class="col-lg-7 col-md-12 border-right">
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0" style="min-width: 140px;">Tipo Estadística</span>
                                            </div>
                                            <select name="tipoEstadistica" id="tipoEstadistica" class="form-control custom-select text-capitalize">
                                                <option value="0">-- Seleccione --</option>
                                                <?php echo $optResp; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0" style="min-width: 140px;">Dependencias</span>
                                                <div class="input-group-text bg-white border-left-0 border-right-0 py-0">
                                                    <small class="mr-2 text-muted">Adscritas</small>
                                                    <input id="CHKseldep" type="checkbox" aria-label="Adscritas">
                                                </div>
                                            </div>
                                            <select name="dependencia_busq" id="dependencia_busq" class="form-control custom-select text-capitalize"></select>
                                        </div>
                                    </div>

                                    <div class="row no-gutters mb-3">
                                        <div class="col-md-6 pr-md-1">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light" style="min-width: 80px;">Serie</span>
                                                </div>
                                                <select name="selSerie" id="selSerie" class="form-control custom-select"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 pl-md-1 mt-2 mt-md-0">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light" style="min-width: 80px;">Subserie</span>
                                                </div>
                                                <select name="selSubSerie" id="selSubSerie" class="form-control custom-select"></select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light border-right-0" style="min-width: 140px;">Usuario</span>
                                                <div class="input-group-text bg-white border-left-0 border-right-0 py-0">
                                                    <small class="mr-2 text-muted">Inactivos</small>
                                                    <input id="CHKselUsuario" onclick="usuario();" type="checkbox" aria-label="Inactivos">
                                                </div>
                                            </div>
                                            <select name="selUsuario" id="selUsuario" class="form-control custom-select text-capitalize"></select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-5 col-md-12 mt-3 mt-lg-0">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-12 mb-3">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light" style="min-width: 100px;">Desde</span>
                                                </div>
                                                <input type="date" name="fecha_ini" id="fecha_ini" value="<?php echo date($fecha_ini); ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-12 mb-3">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light" style="min-width: 100px;">Hasta</span>
                                                </div>
                                                <input type="date" name="fecha_fin" id="fecha_fin" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light" style="min-width: 100px;">Tipo Radicado</span>
                                            </div>
                                            <div class="flex-grow-1 custom-select-container">
                                                <?php echo $tipoRad; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light" style="min-width: 100px;">Tipo Documento</span>
                                            </div>
                                            <select id="selTipoDoc" class="form-control custom-select text-capitalize" data-live-search="true"></select>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end align-items-center mt-4">
                                        <button class="btn btn-sm btn-outline-secondary mr-2" type="button" onclick="location.reload();">
                                            <i class="fa fa-sync-alt mr-1"></i> Limpiar
                                        </button>
                                        <button class="btn btn-sm btn-primary px-4 shadow-sm font-weight-bold" type="button" id="generar">
                                            <i class="fa fa-play mr-1"></i> Generar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>

    <div class="col-12">
        <div class="alert alert-warning INFOalert ">
            <strong>Esta nueva interfase migrara los reportes o estadisticas a médida que estén aprobados</strong>
        </div>
    </div>

    <div class="col-12" id='resulEstdatos' style='display:none'>
        <section id="widget-grid">
            <div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="false">
                <header class='pl-2'>
                    <h2 id="nomReport"> Resultado</h2>
                </header>
                <!-- widget content -->
                <div class="widget-body">
                    <div class="" id='resultado'> resultado</div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal  show" id="DetEsta" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-argo modal-xl" style='min-width: 99%;'>
            <div class="modal-content">
                <div class="modal-header p-2">
                    <label class="modal-title " id="titDet"></label>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style='overflow: auto;'>
                    <div id='mdRespiues' style='height: 80vh;'>
                        <div id="imageLoad"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal  static fade" data-backdrop="static" id="processing-modal" aria-modal="true" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style='min-height:300px;'>
                    <div class="text-center">
                        <h5><span class="modal-text">Procesando, Espere por favor... </span></h5>
                        <div id="imageLoad"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <iframe name='excel_desc' id='excel_desc' style='display:none'></iframe>
    <script type="text/javascript" src="../js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap/popper.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap/bootstrap4.min.js"></script>
    <script type="text/javascript" src="../js/axios.min.js"></script>
    <script type="text/javascript" src="../include/DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="../include/DataTables/Buttons-1.7.0/js/buttons.html5.js"></script>
    <script type="text/javascript" src="estadisticas.js?<?= uniqid('h'); ?>"></script>
    <script>
        series();
        tipodoc();
        usuario();
    </script>

    <div id="animationload" class="animationload" style="display: none;">
        <div id="imageLoad"></div>
    </div>

    <script type="text/javascript" language="javascript">
        var aniLoad = document.getElementById('animationload');
        aniLoad.style.display = 'block';
    </script>
</body>

</html>
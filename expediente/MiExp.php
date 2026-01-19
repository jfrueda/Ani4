<?php

/** */
session_start();
/*ini_set('display_errors', '7');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);*/
$ruta_raiz = "..";
if (!$_SESSION['dependencia']) {
    $fallo['session'] = 'off';
    json_encode($fallo);
    die(); //prueba
}

$krd = $_SESSION["krd"];
foreach ($_GET as $key => $valor) {
    ${$key} = $valor;
}

foreach ($_POST as $key => $valor) {
    ${$key} = $valor;
}
// echo $index." = ".date('Y')."; $index  2012; $index--";
for ($index = date('Y'); $index >= 2020; $index--) {
    $select = '';
    if ($index == date('Y'))
        $select = 'selected';
    $yearselect .= "<option $select value='$index'>$index</option>";
}
//print_r($_SESSION);
$contab = $consul == 1 ? 1 : 0;
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
$usua_id = $_SESSION["usua_id"];
include_once("$ruta_raiz/processConfig.php");
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$perm_crea_exp_todasdependencias = 'no';
$db = new ConnectionHandler($ruta_raiz);
//$db->conn->debug =true;
if (!$dependenciaExp) {
    $dependenciaExp = $dependencia;
}

if ($perm_crea_exp_todasdependencias == 'no') {
    $_condicion_dependencia = " and d.depe_codi = $dependenciaExp";
};
$queryDep = "select depe_codi||' - '||depe_nomb, d.depe_codi from dependencia d where
                d.depe_estado=1 $_condicion_dependencia  and (depe_codi< 1000 or depe_codi> 9999)  order by depe_codi  ";
$rsD = $db->conn->Execute($queryDep);
$comentarioDev = "Muestra las Series Docuementales";
//    include "$ruta_raiz/include/tx/ComentarioTx.php";
if (!$dependenciaExp) {
    $dependenciaExp = $dependencia;
}

$optionDep = $rsD->GetMenu2("dependenciaExp", $dependenciaExp, "0:-- Seleccione --", false, "", "id='dependenciaExp' data-dependencias-clasificadas-trigger='" . $dependencias_clasificadas_trigger . "' data-dependencias-clasificadas='" . $dependencias_clasificadas . "' class='custom-select required text-uppercase'");
if ($_SESSION['dependencia'] <> 900) {
    $queryDep2 = "SELECT 
                CAST(d.depe_codi as varchar(5))||' - '||d.depe_nomb,
                d.depe_codi 
            FROM dependencia d 
            WHERE d.depe_estado = 1 AND 
                cast(d.depe_codi as varchar) LIKE '" . substr($_SESSION['dependencia'], 0, 2) . "%' 
                AND length(cast(d.depe_codi as varchar)) = 5";
} else {
    $queryDep2 = "select depe_codi||' - '||depe_nomb, d.depe_codi from dependencia d where
                d.depe_estado=1   and (depe_codi< 1000 or depe_codi> 9999) order by depe_codi";
}
$queryDepH = "select depe_codi||' - '||depe_nomb, d.depe_codi from dependencia d where
                d.depe_estado=1   and (depe_codi< 1000 or depe_codi> 9999) AND depe_codi = '" . $dependencia . "' order by depe_codi  ";
$rsD2 = $db->conn->Execute($queryDep2);
$optionDep2 = $rsD2->GetMenu2("bsq_dep", 0, "0:-- Seleccione --", false, "", "id='bsq_dep' class='custom-select required text-uppercase'");
$rsHerr = $db->conn->Execute($queryDepH);
$optionDepHerr = $rsHerr->GetMenu2("herr_dep", 0, "0:-- Seleccione --", false, "", "id='herr_dep' class='custom-select required text-uppercase'");
$rsDepeResp = $db->conn->Execute($queryDepH);
$optionDepeResp = $rsDepeResp->GetMenu2("herr_dep_resp", 0, "0:-- Seleccione --", false, "", "id='herr_dep_resp' class='custom-select required text-uppercase'");
$rsDepeResp2 = $db->conn->Execute($queryDepH);
$optionDepeResp2 = $rsDepeResp2->GetMenu2("herr_dep_seg_resp", 0, "0:-- Seleccione --", false, "", "id='herr_dep_seg_resp' class='custom-select required text-uppercase'");

include_once "$ruta_raiz/expediente/expediente.class.php";
$queryDep3 = "select depe_codi||' - '||depe_nomb, d.depe_codi from dependencia d where
                d.depe_estado=1 and (depe_codi< 1000 or depe_codi> 9999) order by depe_codi  ";
$rsD3 = $db->conn->Execute($queryDep3);
$optionDep3 = $rsD3->GetMenu2("dependenciaExp0", 0, "0:-- Seleccione --", false, "", "id='dependenciaExpO' class='custom-select required text-uppercase'");
$expClass = new expediente($ruta_raiz);
$paramsExp = $expClass->parametrosEXP($dependencia);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Expediente</title>
    <link rel="stylesheet" type="text/css" media="screen" href="../bodega/sys_img/favicon.png">
    <!-- Bootstrap core CSS-->
    <!--<link rel="stylesheet" type="text/css" media="screen" href="../estilos/smartadmin-production.css">-->
    <!--<link rel="stylesheet" type="text/css" media="screen" href="../estilos/smartadmin-skins.css"> -->
    <link rel="stylesheet" type="text/css" media="screen" href="../estilos/bootstrap-select.min.css">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" type="text/css" media="screen" href="../estilos/font-awesome.min.css">
    <link href="https://cdn.datatables.net/v/bs4/dt-2.3.1/datatables.min.css" rel="stylesheet" integrity="sha384-TQ2J6dWc3qjeryQasNW8LzwVr54MAzWT5rwHB6xx7gyMRISAFr53aEzTYTC+9cH2" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../estilos/custom.css">
    <style>
        .btn-nav-cel {
            color: white;
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

    <header class="navbar navbar-expand-lg sticky-top shadow-sm bg-orfeo mb-2">
        <!-- Hidden inputs (preservados) -->
        <input type="hidden" name="dependencia" id="dependencia" value="<?= $dependencia ?>">
        <input type="hidden" name="usua_codi" id="usua_codi" value="<?= $codusuario ?>">
        <input type="hidden" name="usuaid" id="usuaid" value="<?= $usua_id ?>">
        <input type="hidden" name="usua_doc" id="usua_doc" value="<?= $usua_doc ?>">

        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand  fw-semibold" href="#" style="font-size:1.1rem;">
                <i class="bi bi-archive me-1"></i> Expedientes
            </a>

            <!-- Toggler -->
            <button class="navbar-toggler text-white border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01"
                aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <!-- Tabs -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link btn-nav-cel <?= $contab == 1 ? '' : 'active' ?> "
                            href="#" id="btn-mis"
                            onclick="$('.herramientas').hide(); listar('mi');">
                            <i class="bi bi-person-circle me-1"></i> Mis Expedientes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link btn-nav-cel "
                            href="#" id="btn-dep"
                            onclick="$('.herramientas').hide(); listar('dp');">
                            <i class="bi bi-folder2-open me-1"></i> Dependencia
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link btn-nav-cel "
                            href="#" id="btn-ext"
                            onclick="$('.herramientas').hide(); listar('co');">
                            <i class="bi bi-people me-1"></i> Compartidos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link btn-nav-cel <?= $contab == 1 ? 'active' : '' ?>"
                            href="#" id="btn-ccc"
                            onclick="$('.herramientas').hide();$('#resulEstdatos').hide();$('#busqueda2').hide();">
                            <i class="bi bi-search me-1"></i> Consulta
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link btn-nav-cel "
                            href="#" id="btn-dep"
                            onclick="$('.herramientas').hide(); listar('dpOLD');">
                            <i class="bi bi-folder2 me-1"></i> Expediente V1
                        </a>
                    </li>

                    <?php if ($_SESSION["USUA_PERM_ADMEXPV1"] >= 1): ?>
                        <li class="nav-item">
                            <a class="nav-link btn-nav-cel "
                                href="#" id="btn-ccc"
                                onclick="$('.herramientas').hide();$('#resulEstdatos').hide();$('#busqueda').hide();$('#busqueda2').show();">
                                <i class="bi bi-search-heart me-1"></i> ADM V1
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['PERMISO_TEMPORAL_EXPEDIENTE']): ?>
                        <li class="nav-item me-1">
                            <a class="nav-link btn-nav-cel <?= $contab == 1 ? 'active' : '' ?> "
                                href="#" id="btn-indice-electronico"
                                onclick="$('#resulEstdatos').hide();$('#busqueda2').hide(); listar('ie');">
                                <i class="bi bi-file-earmark-text"></i> Índice Electrónico
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['ADM_EXP'] >= 1): ?>
                        <li class="nav-item">
                            <a class="nav-link btn-nav-cel <?= $contab == 1 ? 'active' : '' ?> "
                                href="#" id="btn-herramientas"
                                onclick="$('#resulEstdatos').hide();$('#busqueda2').hide(); $('.herramientas').show();">
                                <i class="bi bi-gear"></i> Herramientas
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- Right side buttons -->
                <div class="d-flex">
                    <button type="button" class="btn btn-warning fw-semibold" onclick="$('#crearExpedienteform').show(); cancelarCrearExp()" data-bs-toggle="modal" data-bs-target="#crearExpModal">
                        <i class="bi bi-plus-circle me-1"></i> Crear
                    </button>
                </div>
            </div>
        </div>
    </header>

    <input type="hidden" value="mi" id="tpacc" name="tpacc">

    <div class="col-12 mt-3" id="resulEstdatos" style="display:block;">
        <section id="widget-grid">
            <div class="card border-0 shadow-sm" id="wid-id-1">
                <!-- Header -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" id="nomListado">
                        <i class="fa fa-folder2-open me-2"></i> Expediente
                    </h5>
                </div>

                <!-- Filtros -->
                <div class="card-body pb-0">
                    <div class="row g-3">
                        <div class="col-12 col-md-6"></div>

                        <div class="col-12 col-md-6 d-flex justify-content-end">
                            <div class="input-group">
                                <!-- Buscar -->
                                <span class="input-group-text ">
                                    <i class="fa fa-search"></i>
                                </span>
                                <input class="form-control" id="mysearch" name="mysearch" type="text" placeholder="Buscar expediente...">

                                <!-- Año -->
                                <span class="input-group-text "
                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    title="Número de expediente completo">
                                    Año
                                </span>

                                <select class="form-select" id="anoDep">
                                    <option value="all">Todos</option>
                                    <?= $yearselect ?>
                                </select>

                                <!-- Filtrar -->
                                <button type="button"
                                    onclick="filtrobtn(0)"
                                    class="btn btn-warning">
                                    Filtrar
                                </button>

                                <!-- Descargar -->
                                <button type="button"
                                    onclick="filtrobtn(1)"
                                    class="btn btn-primary">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabla -->
                <div class="table-responsive mt-2">
                    <table class="table table-hover align-middle text-uppercase"
                        id="tb_listaexp" name="tb_listaexp">
                        <thead class="text-center" id="tb_titulo">
                            <tr>
                                <th></th>
                                <th scope="col">Expediente</th>
                                <th scope="col">Fecha Creación</th>
                                <th scope="col">Título</th>
                                <th scope="col">Responsable</th>
                                <th scope="col">Creador</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Índice<br>Electrónico</th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div class="card shadow-sm border-0" id="busqueda" style="display:none;">
        <div class="card-header bg-orfeo text-white">
            <h5 class="mb-0" id="nomListado">Consulta Expediente</h5>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <!-- COLUMNA IZQUIERDA -->
                <div class="col-md-6">
                    <!-- Expediente -->
                    <div class="form-floating">
                        <input type="text" class="form-control" id="bsq_nume_expe" name="bsq_nume_expe" placeholder="">
                        <label for="bsq_nume_expe">Número de Expediente</label>
                    </div>

                    <!-- Nombre expediente -->
                    <div class="form-floating mt-3">
                        <input type="text" class="form-control" id="bsq_nomexpe" name="bsq_nomexpe"
                            maxlength="4000" placeholder="">
                        <label for="bsq_nomexpe">Nombre del Expediente</label>
                    </div>

                    <!-- Usuario -->
                    <div class="form-floating mt-3">
                        <select class="form-select" id="bsq_usuaDoc" name="bsq_usuaDoc">
                            <option value="0">Todos los Usuarios</option>
                        </select>
                        <label for="bsq_usuaDoc">Usuario Responsable</label>
                    </div>
                </div>

                <!-- COLUMNA DERECHA -->
                <div class="col-md-6">
                    <!-- Radicado -->
                    <div class="form-floating">
                        <input type="text" class="form-control" id="bsq_nume_radi" name="bsq_nume_radi"
                            maxlength="17" placeholder="">
                        <label for="bsq_nume_radi">Radicado</label>
                    </div>

                    <!-- Dependencia -->
                    <div class="form-floating mt-3">
                        <?= $optionDep2; ?>
                        <label for="bsqDep">Dependencia Responsable</label>
                    </div>

                    <!-- BOTONES -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-outline-secondary btn-o-limpia">
                            Limpiar
                        </button>
                        <button type="button" class="btn btn-primary btn-o-bsq">
                            Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4" id="herramientas" style="display:none;">
        <!-- HEADER -->
        <div class="card-header bg-orfeo text-white py-3 d-flex justify-content-between align-items-center rounded-top-4">
            <h5 class="mb-0 fw-bold" id="nomListado">Consulta Expediente</h5>
            <span class="badge bg-light text-primary fw-semibold">Herramientas</span>
        </div>

        <!-- BODY -->
        <div class="card-body p-4">
            <div class="row g-4">
                <!-- IZQUIERDA -->
                <div class="col-md-6">
                    <!-- Expediente -->
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" name="herr_nume_expe" id="herr_nume_expe" placeholder=" ">
                        <label for="herr_nume_expe" class="fw-semibold">Expediente</label>
                    </div>

                    <!-- Nombre Expediente -->
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" name="herr_nomexpe" id="herr_nomexpe" maxlength="4000" placeholder=" ">
                        <label for="herr_nomexpe" class="fw-semibold">Nombre Expediente</label>
                    </div>

                    <!-- Usuario -->
                    <div class="mb-3">
                        <label for="herr_usuaDoc" class="form-label fw-semibold">Usuario Responsable</label>
                        <select name="herr_usuaDoc" id="herr_usuaDoc" class="form-select text-uppercase shadow-sm">
                            <option value="0">Todos los Usuarios</option>
                        </select>
                    </div>
                </div>

                <!-- DERECHA -->
                <div class="col-md-6">

                    <!-- Radicado -->
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" name="herr_nume_radi" id="herr_nume_radi" maxlength="17" placeholder=" ">
                        <label for="herr_nume_radi" class="fw-semibold">Radicado</label>
                    </div>

                    <!-- Dependencia -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="herr_dep">Dependencia</label>

                        <!-- SE IMPRIME TU SELECT -->
                        <?= $optionDepHerr; ?>
                    </div>

                    <!-- BOTONES -->
                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-outline-secondary btn-o-limpia-herr px-4 me-2">
                            Limpiar
                        </button>
                        <button type="button" class="btn btn-primary btn-o-herr px-4">
                            Búsqueda
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4" id="busqueda2" style="display:none;">
        <!-- HEADER -->
        <div class="card-header bg-orfeo text-white py-3 rounded-top-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold" id="nomListado">Consulta Expediente v1</h5>
            <span class="badge bg-light text-primary fw-semibold">Búsqueda</span>
        </div>

        <!-- BODY -->
        <div class="card-body px-4 py-4">
            <div class="row g-4">
                <!-- Expediente -->
                <div class="col-md-6">
                    <div class="form-floating">
                        <input
                            type="text"
                            class="form-control"
                            id="bsq_nume_expe2"
                            name="bsq_nume_expe2"
                            placeholder=" "
                            data-bs-toggle="tooltip"
                            data-bs-placement="bottom"
                            title="Número de expediente Completo">
                        <label for="bsq_nume_expe2" class="fw-semibold">Expediente</label>
                    </div>
                </div>

                <!-- Nombre Expediente -->
                <div class="col-md-6">
                    <div class="form-floating">
                        <input
                            type="text"
                            class="form-control"
                            id="bsq_nomexpe2"
                            name="bsq_nomexpe2"
                            maxlength="4000"
                            placeholder=" "
                            data-bs-toggle="tooltip"
                            data-bs-placement="bottom"
                            title="Nombre, título u otro atributo del expediente">
                        <label for="bsq_nomexpe2" class="fw-semibold">Nombre del Expediente</label>
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="col-12 text-end mt-2">
                    <button
                        type="button"
                        class="btn btn-outline-secondary btn-o-limpia2 px-4 me-2">
                        Limpiar
                    </button>

                    <button
                        type="button"
                        class="btn btn-primary btn-o-bsqV1 px-4">
                        Búsqueda
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="row"></div>

    <div class="card shadow-sm border-0 rounded-4" id="resulEstdatos2" style="display:none">
        <!-- Encabezado -->
        <div class="card-header bg-orfeo text-white py-3 rounded-top-4 d-flex align-items-center border-0">
            <h5 class="mb-0 fw-bold" id="nomListado">Resultado</h5>
        </div>

        <!-- Contenido -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table
                    class="table table-striped table-hover table-borderless align-middle text-uppercase mb-0"
                    id="tb_bsq_listaexp"
                    name="tb_bsq_listaexp">

                    <thead class="bg-orfeo text-center" id="tb_titulo">
                        <tr class="fw-bold text-secondary small">
                            <th style="width: 40px;"></th>
                            <th scope="col">Expediente</th>
                            <th scope="col">Fecha Creación</th>
                            <th scope="col">Título</th>
                            <th scope="col">Responsable</th>
                            <th scope="col">Creador</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- filas dinámicas -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row"></div>

    <div class="modal static fade" data-backdrop="static" id="modal_respuesta" aria-modal="true" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p id="mensaje_respuesta">Se actualizarón x registros</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="cerrar" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal static fade" data-backdrop="static" id="herr-responsable" aria-modal="true" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Dependencia:</label>
                            <?php echo $optionDepeResp; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="">Responsable</label>
                            <select aria-selected="true" name="herr_respUsuaDoc" id="herr_respUsuaDoc" class="custom-select">
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"><br></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <i><span class="herr_numExpedientesSeleccionados"></span> expediente(s) seleccionado(s).</i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" value="Cambiar responsable" id='cambiar_responsable_submit' class='btn btn-primary'>
                    <button type="button" class="btn btn-danger" id="cambiar_responsable_cancel" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row"></div>

    <div class="modal static fade" data-backdrop="static" id="herr-seguridad" aria-modal="true" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Dependencia:</label>
                            <?php echo $optionDepeResp2; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Usuarios</label>
                            <button class="btn btn-sm" id="seleccionar_todos_seguridad">Seleccionar todos</button> - <button class="btn btn-sm" id="cancelar_seleccion_seguridad">Cancelar selección</button>
                            <select multiple aria-selected="true" name="herr_respSegUsuaDoc[]" id="herr_respSegUsuaDoc" class="custom-select">
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Permisos:</label>
                            <select name="herr_segExp" id="herr_segExp" class='custom-select text-uppercase'>
                                <option value="0"> Denegar </option>
                                <option value="1"> Listar </option>
                                <option value="2"> Listar y Ver Documentos</option>
                                <option value="3"> Administrar </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"><br></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <i><span class="herr_numExpedientesSeleccionados"></span> expediente(s) seleccionado(s).</i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" value="Cambiar permisos" id='cambiar_permisos_submit' class='btn btn-primary'>
                    <button type="button" class="btn btn-danger" id='cambiar_seguridad_cancel' data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 p-3 m-3 herramientas" id="resulEstdatosHerr" style="display:none">
        <!-- Header -->
        <div class="card-header bg-orfeo text-white py-3 rounded-top-4 d-flex align-items-center">
            <h5 class="mb-0 fw-bold" id="nomListado">Resultado</h5>
        </div>

        <!-- Contenido -->
        <div class="card-body p-3">
            <!-- Acciones -->
            <div class="row mb-4 mt-2">
                <div class="col-md-4">
                    <div class="input-group">
                        <!-- Botón check/uncheck -->
                        <button class="btn btn-outline-secondary" id="check_uncheck" data-status="0">
                            <i class="fa fa-check-square-o" style="display:none;"></i>
                            <i class="fa fa-square-o"></i>
                        </button>

                        <!-- Select de acciones -->
                        <select id="herramienta_accion" class="form-select text-uppercase">
                            <option value="">Seleccionar</option>
                            <option value="herr-responsable">Cambio de responsable</option>
                            <option value="herr-seguridad">Cambio de permisos</option>
                        </select>

                        <!-- Botón ejecutar -->
                        <button id="lanzar-herramienta" class="btn btn-primary">
                            <i class="fa fa-play"></i>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Tabla -->
            <div class="table-responsive rounded-3">
                <table class="table table-striped table-hover align-middle text-uppercase mb-0"
                    id="tb_herr_listaexp"
                    name="tb_herr_listaexp"
                    style="width: 100%;">

                    <thead class="table-light text-center" id="tb_titulo">
                        <tr class="fw-semibold small">
                            <th style="width:40px;"></th>
                            <th>Expediente</th>
                            <th>Fecha Creación</th>
                            <th>Título</th>
                            <th>Responsable</th>
                            <th>Creador</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="row"></div>

    <div class="modal  static fade" data-backdrop="static" id="processing-modal" aria-modal="true" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style='min-height:300px'>
                    <div class="text-center">
                        <h5><span class="modal-text">Procesando, Espere por favor... </span></h5>
                        <div id="imageLoad"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    /**
     * Operaciones de expedientes como  crear, incluir, se utiliza la function parent para poder  ejecutarlo.
     * Creación de expediente
     */
    ?>
    <div id="crearExpModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shadow-lg">
                <!-- HEADER -->
                <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold mb-0" id="tituloExpmodal">
                        CREACIÓN EXPEDIENTE VIRTUAL
                    </h5>

                    <?php if ($_SESSION['CREATEEXPEXT'] == 1) { ?>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switch" name="switch" onchange="tpexpcrea()">
                            <label class="form-check-label text-white" for="switch">Modo avanzado</label>
                        </div>
                    <?php } ?>
                </div>

                <!-- BODY -->
                <div class="modal-body">
                    <div id="dataformCrearExp" name="dataformCrearExp">
                        <form id="formModRadanex" name="formExpinicial" method="post" enctype="multipart/form-data">

                            <!-- SECCIÓN TRD -->
                            <div class="mb-4">
                                <span class="badge bg-info mb-2">Aplicar TRD del expediente</span>
                                <div class="mb-3 input-group">
                                    <span class="input-group-text w-25">Dependencia</span>
                                    <?= $optionDep; ?>
                                </div>
                                <div class="mb-3 input-group">
                                    <span class="input-group-text w-25">Serie</span>
                                    <select name="selSerie" id="selSerie" class="form-select text-uppercase">
                                        <option value="0">-- Seleccione --</option>
                                        <?= $optionSSd; ?>
                                    </select>
                                </div>
                                <div class="mb-3 input-group">
                                    <span class="input-group-text w-25">Subserie</span>
                                    <select id="selSubSerie" name="selSubSerie" class="form-select text-uppercase">
                                        <option value="0">--- Seleccione ---</option>
                                    </select>
                                </div>
                                <div class="mb-3 input-group">
                                    <span class="input-group-text w-25">Seguridad Inicial</span>
                                    <select name="idseguridad" id="idseguridad" class="form-select text-uppercase">
                                        <option value="0" class='text-success'>Pública</option>
                                        <option value="1">Pública reservada (solo la dependencia)</option>
                                        <option value="2">Pública clasificada (usuario que proyectó, jefe y usuario actual)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- SECCIÓN DATOS -->
                            <div class="mb-4">
                                <span class="badge bg-secondary mb-2">Datos del expediente</span>

                                <div class="input-group mb-3">
                                    <select name="anoExp" id="anoExp" class="form-select" onchange="fn_numExp()">
                                        <?php
                                        $seleted = "selected";
                                        for ($i = date('Y'); $i >= 2020; $i--) {
                                            echo "<option value='$i' $seleted>$i</option>";
                                            $seleted = "";
                                        }
                                        ?>
                                    </select>
                                    <input type="text" id="depExp" name="depExp" class="form-control text-center" readonly value="<?= $dependenciaC ?>">
                                    <input type="text" id="numsrb" name="numsrb" class="form-control text-center" readonly value="00000">
                                    <input type="text" id="consecutivoExp" name="consecutivoExp" class="form-control text-center" readonly value="000001">
                                    <span class="input-group-text">E</span>
                                </div>

                                <div class="alert alert-warning small">
                                    El consecutivo "000X" temporal y puede cambiar al momento de crear el expediente.
                                    <strong id="dt_num_exp">20180000100001E</strong>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text w-25">
                                        <?= $paramsExp[1] ?: 'Nombre Expediente'; ?>
                                    </span>
                                    <input type="text" id="exptilulo" name="exptilulo" class="form-control">
                                </div>

                                <!-- CAMPOS DINÁMICOS -->
                                <div id="optionextatrib" name="optionextatrib>
                                <?php for ($i = 2; $i <= 5; $i++) {
                                    if ($paramsExp[$i]) { ?>
                                        <div class=" input-group mb-3">
                                    <span class="input-group-text w-25"><?= $paramsExp[$i] ?></span>
                                    <input type="text" id="param<?= $i ?>" name="param<?= $i ?>" class="form-control">
                            <?php }
                                } ?>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text w-25">Fecha de inicio</span>
                                    <input type="date" id="fechaExp" name="fechaExp" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text w-25">Responsable</span>
                                    <select name="selUsuario" id="selUsuario" class="form-select">
                                        <option value="0">-- Seleccione --</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                    <input type="hidden" name='accion' value='crearConfirmar'>
                    <div id="numExpL" style='display:none'></div>
                    <div class='row'></div>

                    <div name="confCrea" id="confCrea" style="display:none">
                        <div class="mb-4">
                            <!-- ALERTA PRINCIPAL -->
                            <div class="alert alert-warning border-start border-4 border-warning">
                                <h6 class="fw-bold mb-1">
                                    ¿Está seguro de crear el expediente
                                    <span class="text-primary" id="titleexp"></span>?
                                </h6>
                                <small class="text-muted">
                                    Revise cuidadosamente la información antes de continuar.
                                </small>
                            </div>

                            <!-- CARD RESUMEN -->
                            <div class="card shadow-sm mb-3">
                                <div class="card-header bg-light fw-semibold">
                                    Aplicación de la TRD del expediente
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered align-middle mb-0">
                                        <tbody>

                                            <tr>
                                                <th class="w-25 text-muted">Título</th>
                                                <td id="txt-titulo"></td>
                                            </tr>

                                            <tr>
                                                <th class="text-muted">Serie</th>
                                                <td id="txt-serie"></td>
                                            </tr>

                                            <tr>
                                                <th class="text-muted">Subserie</th>
                                                <td id="txt-subserie"></td>
                                            </tr>

                                            <tr id="tr-extEntidad">
                                                <th class="text-muted">Entidad</th>
                                                <td id="txt-extEntidad"></td>
                                            </tr>

                                            <tr id="tr-extasunto">
                                                <th class="text-muted">Asunto</th>
                                                <td id="txt-extasunto"></td>
                                            </tr>

                                            <tr id="tr-extobservacion">
                                                <th class="text-muted">Observación</th>
                                                <td id="txt-extobservacion"></td>
                                            </tr>

                                            <tr>
                                                <th class="text-muted">Fecha de inicio</th>
                                                <td id="txt-fechini"></td>
                                            </tr>

                                            <tr>
                                                <th class="text-muted">Seguridad</th>
                                                <td id="txt-seguridad"></td>
                                            </tr>

                                            <tr>
                                                <th class="text-muted">Responsable</th>
                                                <td id="txt-resp"></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- ALERTA FINAL -->
                            <div class="alert alert-danger border-start border-4 border-danger mb-0">
                                <strong>Nota:</strong>
                                Una vez creado el expediente, <u>no se podrá modificar</u> el número asignado.
                            </div>
                        </div>
                    </div>

                    <div name='creacionconfi' id='creacionconfi' style='display:none'>
                        <div class="form-group">
                        </div>
                        <div class='row'>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <input name="btnConfCrea" type="button" onclick='crearEA();' class="btn btn-warning " style='display: none' id='btnConfCrea' value=" Confirmación Creación Expediente ">
                    <input type="button" value="Crear Expediente" id='btnRcera' class='btn btn-warning btn-crearExp'>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="regresar()" style='display: none' id='btnradcerrartx'>Cerrar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick='cancelarCrearExp()' id='btnRadcancelartx'>Cancelar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick='location.reload()' style='display: none' id='btnRadcancelartxslir'>Salir</button>
                </div>
            </div>
        </div>
    </div>

    <div id="AddCrearExpModal" class="modal fade" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content cajabase">
                <div class="modal-header  bg-orfeo" style='color:#fff   ;  padding: 10px 5px 0px 5px;' id='tituloExpmodaltt' name='tituloExpmodaltt'>
                    <span id='tituloExpmodal'> CREACION EXPEDIENTE VIRTUAL </span>
                </div>
                <div class="modal-body">
                    <div class="input-group  ">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="width: 175px">Expediente</span>
                        </div>
                        <input type="text" name="expOld" id="expOld" class='form-control ' readonly>
                        <div class='with-errors text-danger pull-right' id='error-coddepe'></div>
                    </div>
                    <div class="input-group  ">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="width: 175px">Dependencia</span>
                        </div>
                        <?php echo $optionDep3; ?>
                        <div class='with-errors text-danger pull-right' id='error-coddepe'></div>
                    </div>
                    <div class="input-group  ">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="width: 175px">Responsable</span>
                        </div>
                        <select name="selUsuario2" id="selUsuario2" class='custom-select required text-uppercase'>
                            <option value="0">-- Seleccione --</option>
                        </select>
                    </div>
                    <div id='respodatox'></div>
                </div>
                <div class="modal-footer">
                    <input type="button" value="Asociar responsable" id='btnRcera' class='btn btn-warning btn-crearExpOLD' onclick='crearExpOLD () '>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id='btnRadcancelartxslir'>Salir</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?= $ruta_raiz ?>/js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="<?= $ruta_raiz ?>/js/jquery.table2excel.js"></script>
    <script type="text/javascript" src="../js/JsApp/comp.js?<?= uniqid('h'); ?>"></script>
    <script type="text/javascript" src="../js/bootstrap/popper.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-es_ES.min.js"></script>
    <!-- <script type="text/javascript" src="<?= $ruta_raiz ?>/js/bootstrap/bootstrap4.min.js?2"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/bs4/dt-2.3.1/datatables.min.js" integrity="sha384-PYIYDdAbo4ZJjBb8CoqMenNT3MsgyNDvhKNZIermUpUfdmkSsae++1lb/EsGnEuI" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../js/axios.min.js"></script>
    <script type="text/javascript" src="../js/JsApp/exp.js?<?= uniqid('h'); ?>"></script>
    <script type="text/javascript" src="../js/JsApp/plugin_herramientas.js?<?= uniqid('h'); ?>"></script>
    <script>
        <?php
        echo $contab == 1
            ? "
            document.getElementById('resulEstdatos')?.style.display = 'none';
            document.getElementById('busqueda')?.style.display = 'block';
            "
            : "
            listar('mi');
            ";
        ?>

        /* Equivalente a .val() */
        var dependenciaExp = document.getElementById('dependenciaExp');
        var depExp = document.getElementById('depExp');

        if (dependenciaExp && depExp) {
            depExp.value = dependenciaExp.value;
        }

        /* Llamadas existentes */
        series();

        /* Equivalente a .empty() */
        var selSubSerie = document.getElementById('selSubSerie');
        if (selSubSerie) {
            selSubSerie.innerHTML = '';
        }

        usuario();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const depSelect = document.getElementById('bsq_dep');
            const herrDep = document.querySelector("select[name='herr_dep']");
            const dep = document.querySelector("select[name='dependenciaExp']");

            if (depSelect) {
                depSelect.classList.remove('custom-select');
                depSelect.classList.add('form-select');
            }

            if (herrDep) {
                herrDep.classList.remove('custom-select');
                herrDep.classList.add('form-select');
            }

            if (dep) {
                dep.classList.remove('custom-select');
                dep.classList.add('form-select');
            }
        });
    </script>
</body>


</html>
<?php

if (!$ruta_raiz)
    $ruta_raiz = "..";

session_start();

if (!$_SESSION['dependencia'])
    header("Location: $ruta_raiz/cerrar_session.php");

?>

<html>

<head>
    <?php include_once "../htmlheader.inc.php"; ?>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script> -->
</head>

<body>
    <div class="container-fluid my-4">

        <h1 class="mb-4 text-primary fw-semibold">
            Constancia ejecutoria
        </h1>

        <!-- TABS -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                    Nueva Solicitud
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                    Devoluciones
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
                    Constancias finalizadas
                </button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-orfeo text-white fw-semibold">
                        Búsqueda de Resolución
                    </div>
                    <div class="card-body">
                        <form id="fmBusqueda">
                            <div class="row g-3 align-items-end">
                                <div class="col-6 col-md-3">
                                    <label class="form-label">No Resolución</label>
                                    <input type="number" class="form-control form-control-sm" id="inResolucion">
                                </div>
                                <div class="col-6 col-md-2">
                                    <button type="button" class="btn btn-primary btn-sm w-100" id="btBuscar">
                                        Buscar
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="alert alert-warning mt-3 d-none" id="dvBusqueda"></div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-orfeo text-white fw-semibold">
                        Datos de la Solicitud
                    </div>
                    <div class="card-body">
                        <form id="fmGeneral">
                            <input type="hidden" name="inFuncion" id="inFuncion" value="2">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">No. ID / PLE *</label>
                                    <input type="number" class="form-control form-control-sm" id="inIdple"
                                        name="inIdple">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">No. Resolución por la cual resuelve recurso de
                                        reposición</label>
                                    <input type="number" class="form-control form-control-sm"
                                        id="inResolucionReposicion" name="inResolucionReposicion" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">No. Resolución *</label>
                                    <input type="text" class="form-control form-control-sm" id="inResolucionInicial"
                                        name="inResolucionInicial">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Fecha Reposición</label>
                                    <input type="date" class="form-control form-control-sm" id="inFechaReposicion"
                                        name="inFechaReposicion" disabled>
                                </div>

                                <!-- (el resto de filas se mantienen igual, solo con col-md-6 y g-3) -->

                                <div class="col-md-6">
                                    <label class="form-label">Fecha de Firma*</label>
                                    <input type="date" class="form-control form-control-sm" id="inFechaActo"
                                        name="inFechaActo">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Presenta recursos de queja revocatoria directa*</label>
                                    <select class="form-select form-select-sm" id="seRecursoQueja"
                                        name="seRecursoQueja">
                                        <option value="0" selected>Seleccione una opción</option>
                                        <option value="Si">Si</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nit o C.C*</label>
                                    <input type="text" class="form-control form-control-sm" id="inNitCC" name="inNitCC">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        No. Resolución por la cual resuelve queja o revocatoria directa
                                    </label>
                                    <input type="number" class="form-control form-control-sm"
                                        id="inResolucionRecurosQueja" name="inResolucionRecurosQueja">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Tipo de notificación final</label>
                                    <select class="form-select form-select-sm" id="seNotificacionFinal"
                                        name="seNotificacionFinal" disabled>
                                        <option value="0" selected>Seleccione una opción</option>
                                        <option value="Notificación personal">Notificación personal</option>
                                        <option value="Notificación electrónica">Notificación electrónica</option>
                                        <option value="Notificación por aviso">Notificación por aviso</option>
                                        <option value="Notificación por edicto">Notificación por edicto</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Tipo notificación del acto*</label>
                                    <select class="form-select form-select-sm" id="seTipoNotifiacion"
                                        name="seTipoNotifiacion">
                                        <option value="0" selected>Seleccione una opción</option>
                                        <option value="Notificación personal">Notificación personal</option>
                                        <option value="Notificación electrónica">Notificación electrónica</option>
                                        <option value="Notificación por aviso">Notificación por aviso</option>
                                        <option value="Notificación por edicto">Notificación por edicto</option>
                                        <option value="Notificación en audiencia o estrado">
                                            Notificación en audiencia o estrado
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Fecha de recursos</label>
                                    <input type="date" class="form-control form-control-sm" id="inFechaRecursoQueja"
                                        name="inFechaRecursoQueja" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Fecha acuse y/o notificación*</label>
                                    <input type="date" class="form-control form-control-sm" id="inFechaAcuse"
                                        name="inFechaAcuse" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Expediente*</label>
                                    <input type="text" class="form-control form-control-sm" id="inExpediente"
                                        name="inExpediente">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Presenta recursos*</label>
                                    <select class="form-select form-select-sm" id="sePresentaRecurso"
                                        name="sePresentaRecurso">
                                        <option value="0" selected>Seleccione una opción</option>
                                        <option value="Apelación">Apelación</option>
                                        <option value="Reposición">Reposición</option>
                                        <option value="Ambos">Ambos</option>
                                        <option value="Ninguno">Ninguno</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Comentario</label>
                                    <textarea maxlength="199" class="form-control form-control-sm" id="taComentarioProy"
                                        name="taComentarioProy" rows="3"></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        No. Resolución por la cual resuelve recurso de apelación
                                    </label>
                                    <input type="number" class="form-control form-control-sm" id="inResolucionApleacion"
                                        name="inResolucionApleacion" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Fecha apelación
                                    </label>
                                    <input type="date" class="form-control form-control-sm" id="inFechaApelacion"
                                        name="inFechaApelacion">
                                </div>

                                <div class="col-md-6 d-none">
                                    <label class="form-label">
                                        Fecha ejecutoria
                                    </label>
                                    <input type="date" class="form-control form-control-sm" id="inFechaEjecutoria"
                                        name="inFechaEjecutoria">
                                </div>
                            </div>

                            <div class="alert alert-warning mt-3 d-none" id="dvForm"></div>

                            <div class="d-flex justify-content-center gap-2 mt-4">
                                <button type="button" class="btn btn-outline-secondary" id="btBorrarForm">
                                    Borrar formulario
                                </button>
                                <button type="button" class="btn btn-primary" id="btAgregar">
                                    Agregar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-orfeo text-white fw-semibold">
                        Carga Masiva
                    </div>
                    <div class="card-body">
                        <form id="fmMasivo" enctype="multipart/form-data">
                            <input type="file" class="form-control form-control-sm" id="btSolicitudMasiva"
                                name="btSolicitudMasiva" onchange='getFile(this)'
                                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">

                            <div class="text-center mt-3">
                                <div class="spinner-grow text-info d-none" id="spLoading"></div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-orfeo text-white fw-semibold">
                        Solicitudes por enviar
                    </div>
                    <div class="card-body table-responsive" style="max-height:450px;">
                        <table class="table table-sm table-striped table-bordered align-middle">
                            <thead class="table-light sticky-top">
                                <tr class="align-middle">
                                    <th scope="col">Fecha solicitud</th>
                                    <th scope="col">ID Solicitud</th>
                                    <th scope="col">Item</th>
                                    <th scope="col">No. Id/Pe</th>
                                    <th scope="col">No. Resolución</th>
                                    <th scope="col">Fecha de firma</th>
                                    <th scope="col">Nit O CC</th>
                                    <th scope="col">Razón social</th>
                                    <th scope="col">Tipo de notificación del acto</th>
                                    <th scope="col">Fecha acuse y/o notificación</th>
                                    <th scope="col">Presenta recursos</th>
                                    <th scope="col">Resolución por la cual se resuelve recurso de apelación</th>
                                    <th scope="col">Fecha del acto de apelación</th>
                                    <th scope="col">Resolución por la cual se resuelve recurso de reposición</th>
                                    <th scope="col">Fecha del acto de reposición</th>
                                    <th scope="col">Presenta recursos de queja o revocatoria directa</th>
                                    <th scope="col">Resolución por la cual se resuelve queja o revocatoria directa</th>
                                    <th scope="col">Tipo de notificación del acto final</th>
                                    <th scope="col">Fecha de recurso</th>
                                    <th scope="col">Expediente</th>
                                    <th scope="col">Comentario</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3 mb-5">
                    <button class="btn btn-success my-1" id="btEnviar">Enviar</button>
                    <button class="btn btn-danger my-1" id="btBorrarPen">Borrar</button>
                </div>
            </div>

            <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <!-- ALERTA -->
                <div class="alert alert-warning d-none" id="dvTbDevolucion"></div>

                <!-- TABLA DEVOLUCIONES -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header fw-semibold bg-orfeo text-white">
                        Solicitudes en devolución
                    </div>

                    <div class="card-body table-responsive" style="max-height:450px;" id="dvTableDevolucion">
                        <table id="tbDevolucion" class="table table-sm table-striped table-bordered align-middle mb-0"
                            width="100%">
                            <thead class="table-light sticky-top">
                                <tr class="align-middle text-center">
                                    <th>
                                        <input class="form-check-input" type="checkbox" id="cbSolicitudGen">
                                    </th>
                                    <th>Alerta</th>
                                    <th>Fecha solicitud</th>
                                    <th>ID Solicitud</th>
                                    <th>Item</th>
                                    <th>No. Id/Pe</th>
                                    <th>No. Resolución</th>
                                    <th>Fecha firma</th>
                                    <th>Nit / CC</th>
                                    <th>Razón social</th>
                                    <th>Tipo notificación acto</th>
                                    <th>Fecha acuse y/o notificación</th>
                                    <th>Presenta recursos</th>
                                    <th>Resolución por la cual se resuelve recurso de apelación</th>
                                    <th>Fecha del acto de apelación</th>
                                    <th>Resolución por la cual se resuelve recurso de reposición</th>
                                    <th>Fecha del acto de reposición</th>
                                    <th>Presenta recursos de queja o revocatoria directa</th>
                                    <th>Resolución por la cual se resuelve queja o revocatoria directa</th>
                                    <th>Tipo de notificación del acto final</th>
                                    <th>Fecha recurso</th>
                                    <th>Expediente</th>
                                    <th>Comentario devolución</th>
                                    <th>Comentario enviado</th>
                                    <th>Fecha notificación último acto</th>
                                    <th>Fecha ejecutoria</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-center mb-4">
                    <button type="button" class="btn btn-success px-4" id="btRetonarDevolucion">
                        Enviar
                    </button>
                </div>

                <div class="modal fade" id="mEdicion" tabindex="-1">
                    <div class="modal-dialog modal-fullscreen">
                        <div class="modal-content">
                            <div class="modal-header bg-orfeo text-white">
                                <h5 class="modal-title">
                                    Solicitud constancia – Edición
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body bg-light">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <form id="fmGeneral2">

                                            <input type="hidden" id="inFuncion2" value="9">
                                            <input type="hidden" id="idSolicitudEdit" value="-1">

                                            <div class="row g-3">

                                                <div class="col-md-6">
                                                    <label class="form-label">No. ID / PLE *</label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        id="inIdple2" name="inIdple">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">
                                                        No. Resolución por la cual resuelve recurso de reposición
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        id="inResolucionReposicion2" name="inResolucionReposicion"
                                                        disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">No. Resolución *</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="inResolucionInicial2" name="inResolucionInicial">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Fecha reposición</label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        id="inFechaReposicion2" name="inFechaReposicion" disabled>
                                                </div>

                                                <!-- (el resto de campos mantienen ID/name/type, solo reorganizados con col-md-6 y g-3) -->

                                                <div class="col-md-6">
                                                    <label class="form-label">Fecha de Firma*</label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        id="inFechaActo2" name="inFechaActo2">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Presenta recursos de queja revocatoria
                                                        directa*</label>
                                                    <select class="form-select form-select-sm" id="seRecursoQueja2"
                                                        name="seRecursoQueja2">
                                                        <option value="0" selected>Seleccione una opción</option>
                                                        <option value="Si">Si</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Nit o C.C*</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="inNitCC2" name="inNitCC2">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">
                                                        No. Resolución por la cual resuelve queja o revocatoria directa
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        id="inResolucionRecurosQueja2" name="inResolucionRecurosQueja2"
                                                        disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Razón Social*</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="seRazonSocial2" name="seRazonSocial2">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Tipo de notificación final</label>
                                                    <select class="form-select form-select-sm" id="seNotificacionFinal2"
                                                        name="seNotificacionFinal2" disabled>
                                                        <option value="0" selected>Seleccione una opción</option>
                                                        <option value="Notificación personal">
                                                            Notificación personal
                                                        </option>
                                                        <option value="Notificación electrónica">
                                                            Notificación electrónica
                                                        </option>
                                                        <option value="Notificación por aviso">Notificación por aviso
                                                        </option>
                                                        <option value="Notificación por edicto">Notificación por edicto
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Tipo notificación del acto*</label>
                                                    <select class="form-select form-select-sm" id="seTipoNotifiacion2"
                                                        name="seTipoNotifiacion2">
                                                        <option value="0" selected>Seleccione una opción</option>
                                                        <option value="Notificación personal">
                                                            Notificación personal
                                                        </option>
                                                        <option value="Notificación electrónica">
                                                            Notificación electrónica
                                                        </option>
                                                        <option value="Notificación por aviso">Notificación por aviso
                                                        </option>
                                                        <option value="Notificación por edicto">Notificación por edicto
                                                        </option>
                                                        <option value="Notificación en audiencia o estrado">
                                                            Notificación en audiencia o estrado
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Fecha de recursos</label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        id="inFechaRecursoQueja2" name="inFechaRecursoQueja2" disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Fecha acuse y/o notificación*</label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        id="inFechaAcuse2" name="inFechaAcuse2" disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Expediente*</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="inExpediente2" name="inExpediente2">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Presenta recursos*</label>
                                                    <select class="form-select form-select-sm" id="sePresentaRecurso2"
                                                        name="sePresentaRecurso2">
                                                        <option value="0" selected>Seleccione una opción</option>
                                                        <option value="Apelación">Apelación</option>
                                                        <option value="Reposición">Reposición</option>
                                                        <option value="Ambos">Ambos</option>
                                                        <option value="Ninguno">Ninguno</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Comentario</label>
                                                    <textarea maxlength="199" class="form-control form-control-sm"
                                                        id="taComentarioProy2" name="taComentarioProy2"
                                                        rows="3"></textarea>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">
                                                        No. Resolución por la cual resuelve recurso de apelación
                                                    </label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        id="inResolucionApleacion2" name="inResolucionApleacion2"
                                                        disabled>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">
                                                        Fecha notificación último acto
                                                    </label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        id="inFechaUltimoActo2" name="inFechaUltimoActo2">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">
                                                        Fecha apelación
                                                    </label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        id="inFechaApelacion2" name="inFechaApelacion">
                                                </div>

                                                <div class="col-md-6 d-none">
                                                    <label class="form-label">
                                                        Fecha ejecutoria
                                                    </label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        id="inFechaEjecutoria2" name="inFechaEjecutoria">
                                                </div>

                                            </div>

                                            <div class="alert alert-warning mt-3 d-none" id="dvForm2"></div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal" id="bmEdicionClose2">
                                                    Cancelar
                                                </button>

                                                <button type="button" class="btn btn-primary" id="btEditar">
                                                    Guardar cambios
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <!-- ALERTA -->
                <div class="alert alert-warning d-none" role="alert" id="dvTbConstanciaGen"></div>

                <!-- FILTRO DE BÚSQUEDA -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">

                        <form id="fmBusquedaConsta">
                            <input id="inFuncion" name="inFuncion" type="hidden" value="32">

                            <div class="row g-2 align-items-end">

                                <div class="col-md-3">
                                    <label class="form-label mb-1">Fecha inicio</label>
                                    <input type="date" class="form-control form-control-sm" id="inFechaInicio"
                                        name="inFechaInicio">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label mb-1">Fecha final</label>
                                    <input type="date" class="form-control form-control-sm" id="inFechaFinal"
                                        name="inFechaFinal">
                                </div>

                                <div class="col-md-3 d-flex gap-2">
                                    <button type="button" class="btn btn-info btn-sm" id="btBuscarConstan">
                                        Buscar
                                    </button>

                                    <div class="spinner-grow text-info align-self-center d-none" role="status"
                                        id="spLoading2">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>

                <!-- TABLA -->
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold bg-secondary text-white">
                        Constancias finalizadas
                    </div>

                    <div class="card-body table-responsive" style="max-height:450px;" id="dvTableConstanciaGen">
                        <table id="tbConstancia" class="table table-sm table-striped table-bordered align-middle mb-0"
                            width="100%">
                            <thead class="table-light sticky-top">
                                <tr class="align-middle text-center">
                                    <th>Fecha solicitud</th>
                                    <th>ID Solicitud</th>
                                    <th>Item</th>
                                    <th>No. Id/Pe</th>
                                    <th>No. Resolución</th>
                                    <th>Fecha de firma</th>
                                    <th>Nit O CC</th>
                                    <th>Razón social</th>
                                    <th>Tipo de notificación del acto</th>
                                    <th>Fecha acuse y/o notificación</th>
                                    <th>Presenta recursos</th>
                                    <th>Resolución por la cual se resuelve recurso de apelación</th>
                                    <th>Fecha del acto de apelación</th>
                                    <th>Resolución por la cual se resuelve recurso de reposición</th>
                                    <th>Fecha del acto de reposición</th>
                                    <th>Presenta recursos de queja o revocatoria directa</th>
                                    <th>Resolución por la cual se resuelve queja o revocatoria directa</th>
                                    <th>Tipo de notificación del acto final</th>
                                    <th>Fecha de recurso</th>
                                    <th>Expediente</th>
                                    <th>Fecha notificación último acto</th>
                                    <th>Fecha ejecutoria</th>
                                    <th>Fecha respuesta</th>
                                    <th>Constancia</th>
                                    <th>Comentario enviado</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="dvAbriVisor" class="position-fixed top-0 start-0 w-100 h-100 d-none"
            style="z-index:1050; background: rgba(0,0,0,.6);">

            <div class="d-flex justify-content-center align-items-center h-100">

                <div class="bg-white rounded-4 shadow-lg position-relative p-4"
                    style="max-width:95%; max-height:95%; width:100%; height:100%;">

                    <!-- BOTÓN CERRAR -->
                    <button id="btCerrarVisor" type="button"
                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-3 rounded-circle">
                        ✕
                    </button>

                    <!-- CONTENIDO DEL VISOR -->
                    <!-- aquí se carga el visor / iframe / documento -->
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT LOGICA -->
    <script type="text/javascript">
        $(document).ready(function() {
            $("#dvTableConstanciaGen").css("display", "none");
            cargarSolicitudPorEnviar();
        });

        //Inicio Logica Nueva solicitud
        $("#btBorrarForm").click(function() {
            resetFormulario();
        });

        $("#btBuscar").click(function() {
            $("#dvBusqueda").css("display", "none");
            $("#dvForm").css("display", "none");
            $("#dvBusqueda").empty();
            $("#dvForm").empty();
            let noResolucion = $('#inResolucion').val();
            if (noResolucion == "") {
                $("#dvBusqueda").append("Indique un número de resolución");
                $("#dvBusqueda").css("display", "block");
            } else {
                $.ajax({
                    url: "constanciaController.php",
                    type: "POST",
                    data: {
                        inFuncion: '1',
                        noResolucion: noResolucion
                    },
                    success: function(result) {
                        data = jQuery.parseJSON(result);
                        if (data.length == 0) {
                            document.getElementById("fmGeneral").reset();
                            document.getElementById("fmBusqueda").reset();
                            $("#dvBusqueda").append("Resolución no encontrada");
                            $("#dvBusqueda").css("display", "block");
                        } else {
                            document.getElementById("fmGeneral").reset();
                            document.getElementById("fmBusqueda").reset();
                            $("#dvBusqueda").css("display", "none");
                            $("#dvBusqueda").empty();
                            $('#inResolucionInicial').val(noResolucion);
                            $('#inResolucion').val(noResolucion);
                            $('#inFechaActo').val(data[0]);
                            $('#inNitCC').val(data[2]);
                            $('#seRazonSocial').val(data[1]);

                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                    }
                });
            }
        });

        $("#sePresentaRecurso").change(function() {
            let sePresentaRecurso = $('#sePresentaRecurso').val();
            if (sePresentaRecurso == 'Apelación') {
                $('#inResolucionApleacion').removeAttr("disabled");
                $('#inFechaApelacion').removeAttr("disabled");
                $("#inResolucionReposicion").attr("disabled", true);
                $("#inFechaReposicion").attr("disabled", true);
            } else if (sePresentaRecurso == 'Reposición') {
                $('#inResolucionReposicion').removeAttr("disabled");
                $('#inFechaReposicion').removeAttr("disabled");
                $("#inResolucionApleacion").attr("disabled", true);
                $("#inFechaApelacion").attr("disabled", true);
            } else if (sePresentaRecurso == 'Ambos') {
                $('#inResolucionApleacion').removeAttr("disabled");
                $('#inFechaApelacion').removeAttr("disabled");
                $('#inResolucionReposicion').removeAttr("disabled");
                $('#inFechaReposicion').removeAttr("disabled");
            } else {
                $("#inResolucionApleacion").attr("disabled", true);
                $("#inFechaApelacion").attr("disabled", true);
                $("#inResolucionReposicion").attr("disabled", true);
                $("#inFechaReposicion").attr("disabled", true);
            }
        });

        $("#seRecursoQueja").change(function() {
            let seRecursoQueja = $('#seRecursoQueja').val();
            if (seRecursoQueja == 'Si') {
                $('#inResolucionRecurosQueja').removeAttr("disabled");
                $('#seNotificacionFinal').removeAttr("disabled");
                $('#inFechaRecursoQueja').removeAttr("disabled");
            } else {
                $("#inResolucionRecurosQueja").attr("disabled", true);
                $("#seNotificacionFinal").attr("disabled", true);
                $("#inFechaRecursoQueja").attr("disabled", true);
            }
        });

        $("#btAgregar").click(function() {
            $("#dvBusqueda").css("display", "none");
            $("#dvForm").css("display", "none");
            $("#dvBusqueda").empty();
            $("#dvForm").empty();

            if (validarFormulario()) {
                $("#inFuncion").val() == 2;
                $.ajax({
                    url: "constanciaController.php",
                    type: "POST",
                    data: $("#fmGeneral").serialize(),
                    success: function(result) {
                        if (result == "200") {
                            alert("Registrado correctamente");
                            resetFormulario();
                            cargarSolicitudPorEnviar();
                        } else {
                            alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                    }
                });
            }
        });

        $("#btEnviar").click(function() {
            $.ajax({
                url: "constanciaController.php",
                type: "POST",
                data: {
                    inFuncion: '5'
                },
                success: function(result) {
                    if (result == "200") {
                        alert("Solicitudes enviadas correctamente");
                        resetFormulario();
                        cargarSolicitudPorEnviar();
                    } else {
                        alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                }
            });
        });

        $("#btBorrarPen").click(function() {
            if (confirm('Esta seguro que desea eliminar las peticiones pendientes por enviar?')) {
                $.ajax({
                    url: "constanciaController.php",
                    type: "POST",
                    data: {
                        inFuncion: '39'
                    },
                    success: function(result) {
                        if (result == "200") {
                            alert("Solicitudes eliminadas correctamente");
                            resetFormulario();
                            cargarSolicitudPorEnviar();
                        } else {
                            alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                    }
                });
            }
        });

        //Funcion para enviar excel y procesalor
        function getFile(elm) {
            var fd = new FormData();
            var files = $('#btSolicitudMasiva')[0].files;
            if (files.length > 0) {
                $("#spLoading").css("visibility", "visible");
                fd.append('btSolicitudMasiva', files[0]);
                fd.append('inFuncion', 3);
                $.ajax({
                    url: "constanciaController.php",
                    type: "POST",
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        resetFormulario();
                        $("#spLoading").css("visibility", "hidden");
                        if (result == "200") {
                            alert("Registrado correctamente");
                        } else {
                            $("#dvForm").empty();
                            $("#dvForm").append(result);
                            $("#dvForm").css("display", "block");
                        }

                        cargarSolicitudPorEnviar();

                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                        resetFormulario();
                    }
                });
            }
        }

        function resetFormulario() {
            document.getElementById("fmGeneral").reset();
            document.getElementById("fmBusqueda").reset();
            document.getElementById("fmMasivo").reset();
            $("#dvBusqueda").css("display", "none");
            $("#dvForm").css("display", "none");
            $("#spLoading").css("visibility", "hidden");
            $("#dvBusqueda").empty();
            $("#dvForm").empty();
        }

        function validarFormulario() {
            if ($("#inIdple").val() == "") {
                $("#dvForm").append("No. ID/PLE es requerido");
                $("#dvForm").css("display", "block");
                return false;
            }
            if ($("#inResolucionInicial").val() == "") {
                $("#dvForm").append("No. Resolución es requerido");
                $("#dvForm").css("display", "block");
                return false;
            }
            if ($("#inFechaActo").val() == "") {
                $("#dvForm").append("Fecha de firma es requerido");
                $("#dvForm").css("display", "block");
                return false;
            }
            if ($("#inNitCC").val() == "") {
                $("#dvForm").append("Nit o C.C es requerido");
                $("#dvForm").css("display", "block");
                return false;
            }
            if ($("#seRazonSocial").val() == "") {
                $("#dvForm").append("Razón Social es requerido");
                $("#dvForm").css("display", "block");
                return false;
            }
            if ($("#seTipoNotifiacion").val() == 0) {
                $("#dvForm").append("Tipo notificación del acto es requerido");
                $("#dvForm").css("display", "block");
                return false;
            }
            if ($("#inFechaAcuse").val() == "") {
                $("#dvForm").append("Fecha acuse y/o notificación es requerido");
                $("#dvForm").css("display", "block");
                return false;
            }
            if ($("#sePresentaRecurso").val() == 0) {
                $("#dvForm").append("Presenta recursos es requerido");
                $("#dvForm").css("display", "block");
                return false;
            }
            if ($("#sePresentaRecurso").val() == 'Apelación' || $("#sePresentaRecurso").val() == 'Ambos') {
                if ($("#inResolucionApleacion").val() == "") {
                    $("#dvForm").append("No. Resolución por la cual resuelve recurso de apelación es requerido");
                    $("#dvForm").css("display", "block");
                    return false;
                }
                if ($("#inFechaApelacion").val() == "") {
                    $("#dvForm").append("Fecha apelación es requerido");
                    $("#dvForm").css("display", "block");
                    return false;
                }
            }
            if ($("#sePresentaRecurso").val() == 'Reposición' || $("#sePresentaRecurso").val() == 'Ambos') {
                if ($("#inResolucionReposicion").val() == "") {
                    $("#dvForm").append("No. Resolución por la cual resuelve recurso de reposición es requerido");
                    $("#dvForm").css("display", "block");
                    return false;
                }
                if ($("#inFechaReposicion").val() == "") {
                    $("#dvForm").append("Fecha reposición es requerido");
                    $("#dvForm").css("display", "block");
                    return false;
                }
            }
            if ($("#seRecursoQueja").val() == 0) {
                $("#dvForm").append("Presenta recursos de queja revocatoria directa es requerido");
                $("#dvForm").css("display", "block");
                return false;
            } else {
                if ($("#seRecursoQueja").val() == 'Si') {
                    if ($("#inResolucionRecurosQueja").val() == "") {
                        $("#dvForm").append("No. Resolución por la cual resuelve queja o revocatoria directa es requerido");
                        $("#dvForm").css("display", "block");
                        return false;
                    }
                    if ($("#seNotificacionFinal").val() == 0) {
                        $("#dvForm").append("Tipo de notificación final es requerido");
                        $("#dvForm").css("display", "block");
                        return false;
                    }
                    if ($("#inFechaRecursoQueja").val() == "") {
                        $("#dvForm").append("Fecha de recursos es requerido");
                        $("#dvForm").css("display", "block");
                        return false;
                    }
                }
            }
            if ($("#inExpediente").val() == "") {
                $("#dvForm").append("Expediente es requerido");
                $("#dvForm").css("display", "block");
                return false;
            } else {
                let strUrl = false;
                $.ajax({
                    url: "constanciaController.php",
                    type: "POST",
                    async: false,
                    data: {
                        inFuncion: '37',
                        resolucion: $("#inResolucionInicial").val(),
                        inExpediente: $("#inExpediente").val()
                    },
                    success: function(result) {
                        var data = jQuery.parseJSON(result);
                        $.each(data, function(index, value) {
                            if (index == 'ok') {
                                strUrl = true;
                            } else {
                                $("#dvForm").append(value);
                                $("#dvForm").css("display", "block");
                            }
                        });

                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                    }
                });
                return strUrl;

            }

            return true;
        }

        function cargarSolicitudPorEnviar() {
            $("#tbHorizontal").find("tr:gt(0)").remove();
            $.ajax({
                url: "constanciaController.php",
                type: "POST",
                data: {
                    inFuncion: '4'
                },
                success: function(result) {
                    data = jQuery.parseJSON(result);
                    if (data.length > 0) {
                        $("#dvTableHorizontal").css("display", "block");
                        $("#btEnviar").css("display", "block");
                        $("#btBorrarPen").css("display", "block");
                        for (let i = 0; i < data.length; i++) {
                            let infoRow = '<tr>';
                            for (let j = 0; j < data[i].length; j++) {
                                infoRow += '<td>' + data[i][j] + '</td>';
                            }
                            infoRow += '</tr>';
                            $('#tbHorizontal tr:last').after(infoRow);
                        }
                    } else {
                        $("#dvTableHorizontal").css("display", "none");
                        $("#btEnviar").css("display", "none");
                        $("#btBorrarPen").css("display", "none");
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                }
            });

        }

        function resetFormulario2() {
            document.getElementById("fmGeneral2").reset();
            $("#dvForm2").css("display", "none");
            $("#dvForm2").empty();
        }

        $("#btEditar").click(function() {
            $("#dvForm2").css("display", "none");
            $("#dvForm2").empty();

            if (validarFormulario2()) {
                $("#inFuncion2").val() == 9;
                $.ajax({
                    url: "constanciaController.php",
                    type: "POST",
                    data: $("#fmGeneral2").serialize(),
                    success: function(result) {
                        if (result == "200") {
                            alert("Registro editado correctamente");
                            resetFormulario2();
                            cargarSolicitudDevuelta();
                            $('#mEdicion').modal('hide');
                        } else {
                            alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                    }
                });

            }
        });

        function validarFormulario2() {

            if ($("#inIdple2").val() == "") {
                $("#dvForm2").append("No. ID/PLE es requerido");
                $("#dvForm2").css("display", "block");
                return false;
            }
            if ($("#inResolucionInicial2").val() == "") {
                $("#dvForm2").append("No. Resolución es requerido");
                $("#dvForm2").css("display", "block");
                return false;
            }
            if ($("#inFechaActo2").val() == "") {
                $("#dvForm2").append("Fecha de firma es requerido");
                $("#dvForm2").css("display", "block");
                return false;
            }
            if ($("#inNitCC2").val() == "") {
                $("#dvForm2").append("Nit o C.C es requerido");
                $("#dvForm2").css("display", "block");
                return false;
            }
            if ($("#seRazonSocial2").val() == "") {
                $("#dvForm2").append("Razón Social es requerido");
                $("#dvForm2").css("display", "block");
                return false;
            }
            if ($("#seTipoNotifiacion2").val() == 0) {
                $("#dvForm2").append("Tipo notificación del acto es requerido");
                $("#dvForm2").css("display", "block");
                return false;
            }
            if ($("#inFechaAcuse2").val() == "") {
                $("#dvForm2").append("Fecha acuse y/o notificación es requerido");
                $("#dvForm2").css("display", "block");
                return false;
            }
            if ($("#sePresentaRecurso2").val() == 0) {
                $("#dvForm2").append("Presenta recursos es requerido");
                $("#dvForm2").css("display", "block");
                return false;
            }
            if ($("#sePresentaRecurso2").val() == 'Apelación' || $("#sePresentaRecurso2").val() == 'Ambos') {
                if ($("#inResolucionApleacion2").val() == "") {
                    $("#dvForm2").append("No. Resolución por la cual resuelve recurso de apelación es requerido");
                    $("#dvForm2").css("display", "block");
                    return false;
                }
                if ($("#inFechaApelacion2").val() == "") {
                    $("#dvForm2").append("Fecha apelación es requerido");
                    $("#dvForm2").css("display", "block");
                    return false;
                }
            }
            if ($("#sePresentaRecurso2").val() == 'Reposición' || $("#sePresentaRecurso2").val() == 'Ambos') {
                if ($("#inResolucionReposicion2").val() == "") {
                    $("#dvForm2").append("No. Resolución por la cual resuelve recurso de reposición es requerido");
                    $("#dvForm2").css("display", "block");
                    return false;
                }
                if ($("#inFechaReposicion2").val() == "") {
                    $("#dvForm2").append("Fecha reposición es requerido");
                    $("#dvForm2").css("display", "block");
                    return false;
                }
            }
            if ($("#seRecursoQueja2").val() == 0) {
                $("#dvForm2").append("Presenta recursos de queja revocatoria directa es requerido");
                $("#dvForm2").css("display", "block");
                return false;
            } else {
                if ($("#seRecursoQueja2").val() == 'Si') {
                    if ($("#inResolucionRecurosQueja2").val() == "") {
                        $("#dvForm2").append("No. Resolución por la cual resuelve queja o revocatoria directa es requerido");
                        $("#dvForm2").css("display", "block");
                        return false;
                    }
                    if ($("#seNotificacionFinal2").val() == 0) {
                        $("#dvForm2").append("Tipo de notificación final es requerido");
                        $("#dvForm2").css("display", "block");
                        return false;
                    }
                    if ($("#inFechaRecursoQueja2").val() == "") {
                        $("#dvForm2").append("Fecha de recursos es requerido");
                        $("#dvForm2").css("display", "block");
                        return false;
                    }
                }
            }
            if ($("#inExpediente2").val() == "") {
                $("#dvForm2").append("Expediente es requerido");
                $("#dvForm2").css("display", "block");
                return false;
            }
            return true;
        }

        $("#sePresentaRecurso2").change(function() {
            PresentaRecurso2();
        });

        $("#seRecursoQueja2").change(function() {
            changeRecursoQueja2();
        });

        function PresentaRecurso2() {
            let sePresentaRecurso = $('#sePresentaRecurso2').val();
            if (sePresentaRecurso == 'Apelación') {
                $('#inResolucionApleacion2').removeAttr("disabled");
                $('#inFechaApelacion2').removeAttr("disabled");
                $("#inResolucionReposicion2").attr("disabled", true);
                $("#inFechaReposicion2").attr("disabled", true);
            } else if (sePresentaRecurso == 'Reposición') {
                $('#inResolucionReposicion2').removeAttr("disabled");
                $('#inFechaReposicion2').removeAttr("disabled");
                $("#inResolucionApleacion2").attr("disabled", true);
                $("#inFechaApelacion2").attr("disabled", true);
            } else if (sePresentaRecurso == 'Ambos') {
                $('#inResolucionApleacion2').removeAttr("disabled");
                $('#inFechaApelacion2').removeAttr("disabled");
                $('#inResolucionReposicion2').removeAttr("disabled");
                $('#inFechaReposicion2').removeAttr("disabled");
            } else {
                $("#inResolucionApleacion2").attr("disabled", true);
                $("#inFechaApelacion2").attr("disabled", true);
                $("#inResolucionReposicion2").attr("disabled", true);
                $("#inFechaReposicion2").attr("disabled", true);
            }
        }

        function changeRecursoQueja2() {
            let seRecursoQueja = $('#seRecursoQueja2').val();
            if (seRecursoQueja == 'Si') {
                $('#inResolucionRecurosQueja2').removeAttr("disabled");
                $('#seNotificacionFinal2').removeAttr("disabled");
                $('#inFechaRecursoQueja2').removeAttr("disabled");
            } else {
                $("#inResolucionRecurosQueja2").attr("disabled", true);
                $("#seNotificacionFinal2").attr("disabled", true);
                $("#inFechaRecursoQueja2").attr("disabled", true);
            }
        }

        $("#profile-tab").click(function() {
            cargarSolicitudDevuelta();
        });

        $("#contact-tab").click(function() {
            //cargarConstanciaFinalizada();
        });

        $(document).on('click', '.cbEdit', function() {
            resetFormulario2();
            let idSolicitud = this.id.replace("cb", "");
            $('#idSolicitudEdit').val(idSolicitud);
            $.ajax({
                url: "constanciaController.php",
                type: "POST",
                data: {
                    inFuncion: '8',
                    idSolicitud: idSolicitud
                },
                success: function(result) {
                    data = jQuery.parseJSON(result);
                    if (data.length > 0) {

                        $('#inIdple2').val(data[0]);
                        $('#inResolucionInicial2').val(data[1]);

                        if (data[2] != "") {
                            const inFechaActo2 = data[2].split("/");
                            document.getElementById("inFechaActo2").value = inFechaActo2[2] + '-' + inFechaActo2[1] + '-' + inFechaActo2[0];
                        }
                        if (data[6] != "") {
                            const inFechaAcuse2 = data[6].split("/");
                            document.getElementById("inFechaAcuse2").value = inFechaAcuse2[2] + '-' + inFechaAcuse2[1] + '-' + inFechaAcuse2[0];
                        }
                        if (data[9] != "") {
                            const inFechaApelacion2 = data[9].split("/");
                            document.getElementById("inFechaApelacion2").value = inFechaApelacion2[2] + '-' + inFechaApelacion2[1] + '-' + inFechaApelacion2[0];
                        }
                        if (data[11] != "") {
                            const inFechaReposicion2 = data[11].split("/");
                            document.getElementById("inFechaReposicion2").value = inFechaReposicion2[2] + '-' + inFechaReposicion2[1] + '-' + inFechaReposicion2[0];
                        }
                        if (data[15] != "") {
                            const inFechaRecursoQueja2 = data[15].split("/");
                            document.getElementById("inFechaRecursoQueja2").value = inFechaRecursoQueja2[2] + '-' + inFechaRecursoQueja2[1] + '-' + inFechaRecursoQueja2[0];
                        }

                        $('#inNitCC2').val(data[3]);
                        $('#seRazonSocial2').val(data[4]);
                        $('#seTipoNotifiacion2').val(data[5]);
                        $('#sePresentaRecurso2').val(data[7]);
                        $('#inResolucionApleacion2').val(data[8]);
                        $('#inResolucionReposicion2').val(data[10]);
                        $('#seRecursoQueja2').val(data[12]);
                        $('#inResolucionRecurosQueja2').val(data[13]);
                        $('#seNotificacionFinal2').val(data[14]);
                        $('#inExpediente2').val(data[16]);
                        $('#taComentarioProy2').val(data[29]);
                        PresentaRecurso2();
                        changeRecursoQueja2();
                        $('#mEdicion').modal('show');
                    } else {
                        alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                    }

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                }
            });
        });

        $("#bmEdicionClose").click(function() {
            cargarSolicitudDevuelta();
            $('#mEdicion').modal('hide');
        });

        $("#bmEdicionClose2").click(function() {
            cargarSolicitudDevuelta();
            $('#mEdicion').modal('hide');
        });

        $(document).on('click', '.cbDelete', function() {
            let idSolicitud = this.id.replace("cb", "");
            if (confirm('Esta seguro que desea eliminar esta solicitud ?')) {
                $.ajax({
                    url: "constanciaController.php",
                    type: "POST",
                    data: {
                        inFuncion: '7',
                        idSolicitud: idSolicitud
                    },
                    success: function(result) {
                        if (result == "200") {
                            alert("Solicitud eliminada correctamnte.");
                            cargarSolicitudDevuelta();
                        } else {
                            alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                    }
                });
            }
        });

        function cargarSolicitudDevuelta() {
            $("#dvTbDevolucion").empty();
            $("#dvTbDevolucion").append("");
            $("#dvTbDevolucion").css("display", "none");
            $("#tbDevolucion").find("tr:gt(0)").remove();
            $.ajax({
                url: "constanciaController.php",
                type: "POST",
                data: {
                    inFuncion: '6'
                },
                success: function(result) {
                    data = jQuery.parseJSON(result);
                    if (data.length > 0) {
                        $("#dvTableDevolucion").css("display", "block");
                        $("#btRetonarDevolucion").css("display", "block");
                        for (let i = 0; i < data.length; i++) {
                            let infoRow = '<tr>';
                            let idRow;
                            for (let j = 0; j < data[i].length; j++) {

                                if (j == 0) {
                                    idRow = data[i][j];
                                    infoRow += '<td><input class="form-check-input" type="checkbox" name="cbSolicitud" value="' + idRow + '"></td>';
                                } else if (j == 1) {
                                    if (data[i][j] == 'f')
                                        infoRow += '<td><span class="circleGreen"></span></td>';
                                    else
                                        infoRow += '<td><span class="circleBlue"></span></td>';
                                } else
                                    infoRow += '<td>' + data[i][j] + '</td>';
                            }
                            infoRow += '<td>';
                            infoRow += '<i id="cb' + idRow + '" name="cb' + idRow + '" class="bi-pencil cbEdit" style="font-size: 1.5rem; color: cornflowerblue; cursor: pointer;"></i>&nbsp;&nbsp;&nbsp;';
                            infoRow += '<i id="cb' + idRow + '" name="cb' + idRow + '" class="bi-trash cbDelete" style="font-size: 1.5rem; color: cornflowerblue; cursor: pointer;"></i>';
                            infoRow += '</td>';
                            infoRow += '</tr>';
                            $('#tbDevolucion tr:last').after(infoRow);
                        }
                    } else {
                        $("#dvTableDevolucion").css("display", "none");
                        $("#btRetonarDevolucion").css("display", "none");
                        $("#dvTbDevolucion").append("No se registran devoluciones");
                        $("#dvTbDevolucion").css("display", "block");
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                }
            });
        }

        $("#cbSolicitudGen").click(function() {
            if ($('#cbSolicitudGen').is(':checked')) {
                $("input:checkbox[name=cbSolicitud]").each(function() {
                    $(this).prop('checked', true);
                });
            } else {
                $("input:checkbox[name=cbSolicitud]").each(function() {
                    $(this).prop('checked', false);
                });
            }
        });

        $("#btRetonarDevolucion").click(function() {
            let cantidadSeleccionada = 0;
            let arraySolicitud = [];
            $("input:checkbox[name=cbSolicitud]:checked").each(function() {
                arraySolicitud.push($(this).val());
                cantidadSeleccionada++;
            });

            if (cantidadSeleccionada == 0) {
                alert("Debe seleccionar al menos una solicitud para envair.");
            } else {
                if (confirm('Esta seguro que enviar los solicitudes ?')) {
                    $.ajax({
                        url: "constanciaController.php",
                        type: "POST",
                        data: {
                            inFuncion: '10',
                            arraySolicitud: arraySolicitud
                        },
                        success: function(result) {
                            if (result == "200") {
                                alert("Envio correcto.");
                                cargarSolicitudDevuelta();
                            } else {
                                alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                        }
                    });
                }
            }
        });
        //Fin Logica Tab Devoluciones

        $(document).on('click', '.cbDescarga', function() {
            let idSolicitud = this.id.replace("cbDes", "");
            $.ajax({
                url: "constanciaController.php",
                type: "POST",
                data: {
                    inFuncion: '19',
                    idSolicitud: idSolicitud
                },
                success: function(result) {
                    $('#dvAbriVisor').empty();
                    $("#dvAbriVisor").append("<button id='btCerrarVisor' type='button' style='float:right; background-color:red;'><b>x</b></button><iframe style='width:100%; height:89vh; z-index:-2;' src='.." + result + "'></iframe>");
                    $("#dvAbriVisor").css("display", "block");
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                }
            });
        });

        $(document).on('click', '#btCerrarVisor', function() {
            $("#dvAbriVisor").css("display", "none");
        });

        $("#btBuscarConstan").click(function() {

            $("#dvTbConstanciaGen").empty();
            $("#dvTbConstanciaGen").append("");
            $("#dvTbConstanciaGen").css("display", "none");

            if ($("#inFechaInicio").val() != "" && $("#inFechaFinal").val() != "") {
                let parts = $("#inFechaInicio").val().split('-');
                let dtFechaInicio = new Date(parts[0], parts[1] - 1, parts[2]);
                parts = $("#inFechaFinal").val().split('-');
                let dtFechaFinal = new Date(parts[0], parts[1] - 1, parts[2]);
                if (dtFechaInicio.getTime() > dtFechaFinal.getTime()) {
                    $("#dvTbConstanciaGen").append("Fecha de inicio debe ser menor o igual a la fecha final.");
                    $("#dvTbConstanciaGen").css("display", "block");
                    return;
                } else {
                    cargarConstanciaFinalizada();
                }
            } else {
                $("#dvTbConstanciaGen").append("Debe ingresar fecha de inicio y fecha final.");
                $("#dvTbConstanciaGen").css("display", "block");
                return;
            }

        });

        function cargarConstanciaFinalizada() {
            $("#spLoading2").css("visibility", "visible");
            $("#tbConstancia").find("tr:gt(0)").remove();
            $.ajax({
                url: "constanciaController.php",
                type: "POST",
                data: $("#fmBusquedaConsta").serialize(),
                success: function(result) {
                    data = jQuery.parseJSON(result);
                    if (data.length > 0) {
                        $("#dvTableConstanciaGen").css("display", "block");
                        let idRow;
                        for (let i = 0; i < data.length; i++) {
                            let infoRow = '<tr>';
                            for (let j = 0; j < data[i].length; j++) {

                                if (j == 0) {
                                    idRow = data[i][j];
                                } else if (j == 24) {
                                    if (data[i][j] != "") {
                                        infoRow += '<td><i id="cbDes' + idRow + '"  class="bi-file-earmark-pdf-fill cbDescarga" style="font-size: 1.5rem; color: FF0000; cursor: pointer;"></i></td>';
                                    } else {
                                        infoRow += '<td></td>';
                                    }
                                } else
                                    infoRow += '<td>' + data[i][j] + '</td>';
                            }
                            infoRow += '</tr>';
                            $('#tbConstancia tr:last').after(infoRow);
                        }
                    } else {
                        $("#dvTableConstanciaGen").css("display", "none");
                        $("#dvTbConstanciaGen").append("No se registran constancias");
                        $("#dvTbConstanciaGen").css("display", "block");
                    }
                    $("#spLoading2").css("visibility", "hidden");
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Ocurrió un error, comuníquese con el administrador del sistema.");
                    $("#spLoading2").css("visibility", "hidden");
                }
            });
        }
    </script>
    <!-- FIN SCRIPT LOGICA -->
</body>

</html>
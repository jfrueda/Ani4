<?php
session_start();
$ruta_raiz = __DIR__ . '/../../';

include_once "$ruta_raiz/processConfig.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler("$ruta_raiz");
if ($_SESSION['envios_general'] == 1) {
    $dependencias = explode(',', $dependencias_envio_general);
} else if ($_SESSION['envios_dependencia'] == 1) {
    $dependencias = [$_SESSION['dependencia']];
} else {
    die('No tiene permisos para acceder a esta página');
}

$filtro_usuarios = false;
if ($_SESSION['dependencia'] == 93001) {
    $filtro_usuarios = true;
    $usuarios = $db->conn->getAll("
        SELECT
            u.*
        FROM
            usuario u
            JOIN autm_membresias am ON am.autu_id = u.id
            JOIN autg_grupos ag ON am.autg_id = ag.id
            JOIN autr_restric_grupo arg ON arg.autg_id = ag.id
            JOIN autp_permisos ap ON arg.autp_id = ap.id
        WHERE
            ap.nombre = 'ENVIOS_DEPENDENCIA' AND
            u.depe_codi = 93001 AND
            u.usua_esta = '1'
    ");
}
$dependencias = $db->conn->getAll('SELECT * FROM dependencia WHERE depe_estado = 1 and depe_codi IN (' . implode(',', $dependencias) . ') order by depe_codi');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous"> -->
    <link href="https://cdn.datatables.net/v/bs/jq-3.7.0/dt-2.2.2/datatables.min.css" rel="stylesheet" integrity="sha384-7aS9/3QeF6aGHn5XMTAgFOPWKTTpPt1ewiY/Oola12c/sZEJ/FZARdWLLyunCg0v" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css"> -->
    <link rel="stylesheet" type="text/css" media="screen" href="../../estilos/custom.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs/jq-3.7.0/dt-2.2.2/datatables.min.js" integrity="sha384-0WAcEvM8/3uyhhothDyYO/XkDLsMjBykBMtB8rc+/6GEN1aY7pDeQzdsnQs0nxmp" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script> -->

    <title>Document</title>
    <style>
        .glyphicon-refresh-animate {
            -webkit-animation-name: rotateThis;
            -webkit-animation-duration: 2s;
            -webkit-animation-iteration-count: infinite;
            -webkit-animation-timing-function: linear;
        }

        @-webkit-keyframes "rotateThis" {
            from {
                -webkit-transform: rotate(0deg);
            }

            to {
                -webkit-transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 my-4">
            <!-- Título -->
            <div class="card-header bg-orfeo text-white text-center py-3">
                <h4 class="fw-bold">
                    <i class="bi bi-envelope"></i>
                    Envíos electrónicos
                </h4>
            </div>

            <!-- Filtros -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-4">
                            <form method="get">
                                <div class="row g-4">
                                    <!-- Dependencias -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold w-100" for="dependencia">
                                            Dependencia
                                        </label>

                                        <!-- data-actions-box="true" -->
                                        <select class="form-select w-100" name="dependencia" id="dependencia" multiple size="1" title="Selecciona las dependencias que vas a consultar">
                                            <?php $i = 0; ?>
                                            <?php foreach ($dependencias as $dependencia): ?>
                                                <option value="<?= $dependencia['DEPE_CODI'] ?>" <?= $i === 0 ? 'selected' : '' ?>>
                                                    <?= $dependencia['DEPE_CODI'] ?> - <?= $dependencia['DEPE_NOMB'] ?>
                                                </option>
                                                <?php $i++; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Usuarios -->
                                    <?php if ($filtro_usuarios): ?>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label fw-semibold w-100" for="usuario">
                                                Usuario <small class="text-muted">(Separados por coma)</small>
                                            </label>
                                            <select class="form-select w-100" name="usuario" id="usuario" multiple size="1" title="Selecciona los usuarios">
                                                <?php foreach ($usuarios as $usuario): ?>
                                                    <option value="<?= $usuario['USUA_LOGIN'] ?>" selected>
                                                        <?= $usuario['USUA_NOMB'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    <?php else: ?>
                                        <input type="hidden" name="usuario" id="usuario">
                                    <?php endif; ?>

                                    <!-- Radicados -->
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold w-100" for="radicados">
                                            Número de radicado
                                            <small class="text-muted">(Separar por coma)</small>
                                        </label>
                                        <input type="text" class="form-control w-100" name="radicados" id="radicados">
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                    <!-- Botón Buscar -->
                                    <input class="btn btn-primary px-4" type="submit" value="Buscar">

                                    <div class="d-flex gap-2">
                                        <!-- Botón Enviar todos (MISMO PHP) -->
                                        <button class="btn btn-secondary" type="button" id="enviar_todos">
                                            Enviar todos
                                        </button>

                                        <!-- Exportar (MISMO PHP) -->
                                        <a class="btn btn-outline-secondary" href="export.php" id="exportar">
                                            Exportar
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-body -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive" style="max-height: 65vh;">
                            <table id="envios" class="table table-hover table-striped align-middle">
                                <thead class="table-primary sticky-top">
                                    <tr>
                                        <th class="text-nowrap">Radicado salida</th>
                                        <th class="text-nowrap">Radicado padre</th>
                                        <th class="text-nowrap">Fecha radicado</th>
                                        <th class="text-nowrap">Descripción</th>
                                        <th class="text-nowrap">Fecha impresión</th>
                                        <th class="text-nowrap">Generado por</th>
                                        <th class="text-nowrap">Certificado</th>
                                        <th class="text-nowrap">Emails</th>
                                        <th class="text-center text-nowrap"></th>
                                        <th class="text-center text-nowrap"></th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="recordLogModal" tabindex="-1" role="dialog" aria-labelledby="errorLogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorLogModalLabel">Registro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Correo</th>
                                <th>Mensaje</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody id="errorLogTableBody">
                            <!-- Los registros de errores se cargarán aquí dinámicamente -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p id="errorMessage">Se ha producido un error.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Información</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="infoMessage">Aquí se mostrará la información relevante.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            // function reloadDataTable() {
            //     var table = $('#envios').DataTable();
            //     var currentPage = table.page();
            //     table.ajax.reload(null, false);
            //     table.page(currentPage).draw(false);
            // }

            // $('#dependencia').selectpicker({
            //     liveSearch: true,
            //     deselectAllText: 'Deseleccionar todos',
            //     selectAllText: 'Seleccionar todos',
            //     size: 10
            // });

            // $('#usuario').selectpicker({
            //     liveSearch: true,
            //     deselectAllText: 'Deseleccionar todos',
            //     selectAllText: 'Seleccionar todos',
            //     size: 10
            // });

            // $('form').on('submit', function(e) {
            //     e.preventDefault();
            //     $('#envios').DataTable().ajax.reload();
            // });

            $('#envios').DataTable({
                "pageLength": 10,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "table.php",
                    "type": "POST",
                    "data": function(d) {
                        d.dependencia = $('#dependencia').val();
                        d.usuario = $('#usuario').val();
                        d.radicados = $('#radicados').val();
                    }
                },
                "searching": false,
                "order": [],
                "columnDefs": [{
                        "orderable": true,
                        "targets": [0, 1, 2]
                    }, // Allow ordering on RADICADO_SALIDA and RADICADO_PADRE
                    {
                        "orderable": false,
                        "targets": "_all"
                    } // Disable ordering on all other columns
                ],
                "columns": [{
                        "data": "RADICADO_SALIDA"
                    },
                    {
                        "data": "RADICADO_PADRE"
                    },
                    {
                        "data": "FECHA_RADICADO"
                    },
                    {
                        "data": "DESCRIPCION"
                    },
                    {
                        "data": "FECHA_IMPRESION"
                    },
                    {
                        "data": "GENERADO_POR"
                    },
                    {
                        "data": "CERTIFICADO"
                    },
                    {
                        "data": "EMAILS",
                        "render": function(data, type, row) {
                            return data ? data.split(';').join('<br>') : '';
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            let json = JSON.parse(row.REGISTRO || '[]');
                            if (json && Array.isArray(json)) {
                                let hasError = json.some(item => item.status == 'error');
                                if (hasError && row.ESTADO == 1 && row.DEVUELTO == 'f') {
                                    return `<button class='registro btn btn-default' data-id='${row.ID}' data-log='${JSON.stringify(json)}'>
                                        <span class="glyphicon glyphicon-exclamation-sign"></span>
                                    </button>`;
                                }
                            }

                            return `<button class='enviar btn btn-default' data-id='${row.ID}' disabled>
                                <span class="glyphicon glyphicon-ban-circle"></span>
                            </button>`;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            let json = JSON.parse(row.REGISTRO || '[]');
                            let hasError = false;
                            if (json && Array.isArray(json)) {
                                hasError = json.some(item => item.status == 'error') && row.ESTADO == 1 && row.DEVUELTO == 'f';
                            }

                            return `<button class='enviar btn ${hasError ? 'btn-warning' : 'btn-primary'}' data-id='${row.ID}'>
                                <span class="glyphicon glyphicon-send"></span>
                            </button>`;
                        }
                    }
                ]
            });

            // $('body').on('click', '.registro', function() {
            //     var logData = $(this).data('log');
            //     var logTableBody = $('#errorLogTableBody');
            //     logTableBody.empty();

            //     logData.forEach(function(item) {
            //         logTableBody.append(`
            //             <tr class="${item.status == 'success' ? 'success' : 'danger'}">
            //                 <td>${item.correo}</td>
            //                 <td>${item.message}</td>
            //                 <td>${item.timestamp}</td>
            //             </tr>
            //         `);
            //     });

            //     $('#recordLogModal').modal('show');
            // });

            // $('body').on('click', '.enviar', function() {
            //     var id = $(this).data('id');
            //     var button = $(this);
            //     button.prop('disabled', true);
            //     button.html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
            //     $.ajax({
            //         url: 'send.php',
            //         type: 'POST',
            //         data: {
            //             id: id
            //         },
            //         success: function(response) {
            //             button.prop('disabled', false);
            //             button.html('<span class="glyphicon glyphicon-send"></span>');
            //             console.log(response);

            //             var allSuccess = true;
            //             var errorMessages = [];

            //             for (var key in response.registro) {
            //                 if (response.registro.hasOwnProperty(key)) {
            //                     var registros = response.registro[key];
            //                     for (var index in registros) {
            //                         if (registros.hasOwnProperty(index)) {
            //                             var registro = registros[index];
            //                             if (registro.status !== 'success') {
            //                                 allSuccess = false;
            //                                 errorMessages.push(registro.message);
            //                             }
            //                         }
            //                     }
            //                 }
            //             }

            //             if (allSuccess) {
            //                 $('#infoMessage').text('Envío realizado correctamente.');
            //                 $('#infoModal').modal('show');
            //             } else {
            //                 $('#infoMessage').html('Algunos registros no se enviaron correctamente.');
            //                 $('#infoModal').modal('show');
            //             }

            //             reloadDataTable();
            //         },
            //         error: function() {
            //             $('#errorMessage').text('Ocurrió un error al intentar enviar el registro.');
            //             $('#errorModal').modal('show');
            //             button.prop('disabled', false);
            //             button.html('<span class="glyphicon glyphicon-send"></span>');
            //             reloadDataTable();
            //         }
            //     });
            // });

            // $('#enviar_todos').on('click', function() {
            //     var button = $(this);
            //     button.prop('disabled', true);
            //     button.html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Enviando...');
            //     $('.enviar').prop('disabled', true);

            //     var table = $('#envios').DataTable();

            //     var visibleData = table.rows({
            //         page: 'current'
            //     }).data().toArray();

            //     var ids = visibleData.map(function(row) {
            //         return row.ID;
            //     });

            //     if (ids.length === 0) {
            //         $('#infoMessage').text('No hay registros en la página actual.');
            //         $('#infoModal').modal('show');
            //         button.prop('disabled', false);
            //         $('.enviar').prop('disabled', false);
            //         button.html('Enviar todos');
            //         return;
            //     }

            //     $.ajax({
            //         url: 'send.php',
            //         type: 'POST',
            //         data: {
            //             id: ids
            //         },
            //         success: function(response) {
            //             reloadDataTable();
            //             button.prop('disabled', false);
            //             button.html('Enviar todos');
            //             $('.enviar').prop('disabled', false);
            //             var allSuccess = response.registro.every(function(item) {
            //                 return item.status === 'success';
            //             });

            //             if (allSuccess) {
            //                 $('#infoMessage').text('Envío realizado correctamente.');
            //                 $('#infoModal').modal('show');
            //             } else {
            //                 $('#infoMessage').text('Algunos registros no se enviaron correctamente. Por favor, revise el registro.');
            //                 $('#infoModal').modal('show');
            //             }
            //         },
            //         error: function() {
            //             button.prop('disabled', false);
            //             button.html('Enviar todos');
            //             $('.enviar').prop('disabled', false);
            //             $('#infoMessage').text('Ocurrió un error al intentar enviar los correos.');
            //             $('#infoModal').modal('show');
            //             reloadDataTable();
            //         }
            //     });
            // });

            // $('#exportar').on('click', function(e) {
            //     console.log("exportar");
            //     e.preventDefault();

            //     var dependencia = $('#dependencia').val();
            //     var usuario = $('#usuario').val();
            //     var radicados = $('#radicados').val();

            //     var form = $('<form>', {
            //         method: 'POST',
            //         action: 'export.php'
            //     });

            //     form.append($('<input>', {
            //         type: 'hidden',
            //         name: 'dependencia',
            //         value: dependencia
            //     }));

            //     form.append($('<input>', {
            //         type: 'hidden',
            //         name: 'usuario',
            //         value: usuario
            //     }));

            //     form.append($('<input>', {
            //         type: 'hidden',
            //         name: 'radicados',
            //         value: radicados
            //     }));

            //     $('body').append(form);
            //     form.submit();
            // });
        });

        document.addEventListener("DOMContentLoaded", function() {

            function reloadDataTable() {
                let table = new DataTable('#envios');
                let currentPage = table.page();
                table.ajax.reload(null, false);
                table.page(currentPage).draw(false);
            }

            /* ---------------------------------------------------------
             * Submit del formulario → Recargar tabla
             * --------------------------------------------------------- */
            const form = document.querySelector("form");

            if (form) {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    let table = new DataTable('#envios');
                    table.ajax.reload();
                });
            }

            // Botón "Enviar todos"
            const enviarBtn = document.getElementById('enviar_todos');

            enviarBtn.addEventListener('click', function() {
                console.log("enviar-datos");

                // Desactivar botón
                enviarBtn.disabled = true;
                enviarBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';

                // Desactivar botones individuales
                document.querySelectorAll('.enviar').forEach(btn => btn.disabled = true);

                // Obtener DataTable
                let table = new DataTable('#envios');

                // Filtrar datos visibles en la página actual
                let visibleData = table.rows({
                    page: 'current'
                }).data().toArray();

                let ids = visibleData.map(row => row.ID);

                if (ids.length === 0) {
                    document.getElementById('infoMessage').textContent = "No hay registros en la página actual.";
                    const infoModal = new bootstrap.Modal(document.getElementById('infoModal'));
                    infoModal.show();

                    enviarBtn.disabled = false;
                    enviarBtn.innerHTML = "Enviar todos";

                    document.querySelectorAll('.enviar').forEach(btn => btn.disabled = false);
                    return;
                }

                // ---- AJAX NATIVO con fetch() ----
                fetch('send.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            id: ids
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        reloadDataTable();
                        enviarBtn.disabled = false;
                        enviarBtn.innerHTML = "Enviar todos";
                        document.querySelectorAll('.enviar').forEach(btn => btn.disabled = false);

                        let exitoTotal = data.registro.every(item => item.status === 'success');

                        document.getElementById('infoMessage').textContent =
                            exitoTotal ?
                            "Envío realizado correctamente." :
                            "Algunos registros no se enviaron correctamente. Por favor, revise el registro.";

                        const infoModal = new bootstrap.Modal(document.getElementById('infoModal'));
                        infoModal.show();
                    })
                    .catch(() => {
                        enviarBtn.disabled = false;
                        enviarBtn.innerHTML = "Enviar todos";
                        document.querySelectorAll('.enviar').forEach(btn => btn.disabled = false);

                        document.getElementById('infoMessage').textContent =
                            "Ocurrió un error al intentar enviar los correos.";

                        const infoModal = new bootstrap.Modal(document.getElementById('infoModal'));
                        infoModal.show();

                        reloadDataTable();
                    });
            });

            /* ---------------------------------------------------------
             * Botón Exportar
             * --------------------------------------------------------- */
            const exportar = document.getElementById("exportar");

            exportar.addEventListener("click", function(e) {
                e.preventDefault();
                console.log("exportar");

                let dependencia = document.getElementById("dependencia")?.value || "";
                let usuario = document.getElementById("usuario")?.value || "";
                let radicados = document.getElementById("radicados")?.value || "";

                let formExport = document.createElement("form");
                formExport.method = "POST";
                formExport.action = "export.php";

                function addField(name, value) {
                    let input = document.createElement("input");
                    input.type = "hidden";
                    input.name = name;
                    input.value = value;
                    formExport.appendChild(input);
                }

                addField("dependencia", dependencia);
                addField("usuario", usuario);
                addField("radicados", radicados);

                document.body.appendChild(formExport);
                formExport.submit();
            });

            /* ---------------------------------------------------------
             * CLICK EN .registro
             * --------------------------------------------------------- */
            document.addEventListener("click", function(e) {
                if (e.target.classList.contains("registro")) {

                    let logData = JSON.parse(e.target.dataset.log);
                    let logTableBody = document.getElementById('errorLogTableBody');

                    logTableBody.innerHTML = "";

                    logData.forEach(item => {
                        logTableBody.insertAdjacentHTML(
                            "beforeend",
                            `
                            <tr class="${item.status === 'success' ? 'table-success' : 'table-danger'}">
                                <td>${item.correo}</td>
                                <td>${item.message}</td>
                                <td>${item.timestamp}</td>
                            </tr>
                            `
                        );
                    });

                    new bootstrap.Modal(document.getElementById('recordLogModal')).show();
                }
            });

            /* ---------------------------------------------------------
             * CLICK EN .enviar → Enviar registro individual
             * --------------------------------------------------------- */
            document.addEventListener("click", function(e) {
                if (e.target.classList.contains("enviar")) {

                    let button = e.target;
                    let id = button.dataset.id;

                    button.disabled = true;
                    button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                    fetch('send.php', {
                            method: 'POST',
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: new URLSearchParams({
                                id
                            })
                        })
                        .then(res => res.json())
                        .then(response => {
                            button.disabled = false;
                            button.innerHTML = '<span class="glyphicon glyphicon-send"></span>';

                            let allSuccess = true;

                            for (let key in response.registro) {
                                response.registro[key].forEach(reg => {
                                    if (reg.status !== "success") allSuccess = false;
                                });
                            }

                            document.getElementById('infoMessage').textContent =
                                allSuccess ?
                                "Envío realizado correctamente." :
                                "Algunos registros no se enviaron correctamente.";

                            new bootstrap.Modal(document.getElementById('infoModal')).show();
                            reloadDataTable();
                        })
                        .catch(() => {
                            document.getElementById('errorMessage').textContent =
                                "Ocurrió un error al intentar enviar el registro.";

                            new bootstrap.Modal(document.getElementById('errorModal')).show();

                            button.disabled = false;
                            button.innerHTML = '<span class="glyphicon glyphicon-send"></span>';

                            reloadDataTable();
                        });
                }
            });
        });
    </script>
</body>

</html>
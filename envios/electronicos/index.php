<?php
session_start();
$ruta_raiz = __DIR__.'/../../';

include_once "$ruta_raiz/processConfig.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler("$ruta_raiz");
if ($_SESSION['envios_general'] == 1)
{
    $dependencias = explode(',', $dependencias_envio_general);
} else if ($_SESSION['envios_dependencia'] == 1) {
    $dependencias = [$_SESSION['dependencia']];
} else {
    die('No tiene permisos para acceder a esta página');
}

$filtro_usuarios = false;
if ($_SESSION['dependencia'] == 93001)
{
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
$dependencias = $db->conn->getAll('SELECT * FROM dependencia WHERE depe_estado = 1 and depe_codi IN ('.implode(',', $dependencias).') order by depe_codi');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/bs/jq-3.7.0/dt-2.2.2/datatables.min.css" rel="stylesheet" integrity="sha384-7aS9/3QeF6aGHn5XMTAgFOPWKTTpPt1ewiY/Oola12c/sZEJ/FZARdWLLyunCg0v" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs/jq-3.7.0/dt-2.2.2/datatables.min.js" integrity="sha384-0WAcEvM8/3uyhhothDyYO/XkDLsMjBykBMtB8rc+/6GEN1aY7pDeQzdsnQs0nxmp" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    
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
                -webkit-transform: rotate( 0deg );  
            }
        to  { 
                -webkit-transform: rotate( 360deg ); 
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2>Envios electrónicos</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form method="get">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="dependencia">Dependencia:</label>
                            <select 
                                class="form-control" 
                                name="dependencia" 
                                id="dependencia" 
                                title="Selecciona las dependencias que vas a consultar"
                                data-actions-box="true"
                                multiple>
                                <?php foreach ($dependencias as $dependencia): ?>
                                    <option value="<?= $dependencia['DEPE_CODI'] ?>" selected><?= $dependencia['DEPE_CODI'] ?> - <?= $dependencia['DEPE_NOMB'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if($filtro_usuarios): ?>
                            <div class="col-md-6">
                                <label class="form-label" for="usuarios">Usuario: <small class="text text-muted">Para incluir mas de un número de radicado separarlo por coma (,)</small></label>
                                <select 
                                    class="form-control" 
                                    name="usuario" 
                                    id="usuario" 
                                    title="Selecciona los usuarios a consultar"
                                    data-actions-box="true"
                                    multiple>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <option value="<?= $usuario['USUA_LOGIN'] ?>" selected><?= $usuario['USUA_NOMB'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php else: ?>
                            <input type="hidden" name="usuario" id="usuario">
                        <?php endif; ?>
                        <div class="col-md-6">
                            <label class="form-label" for="radicados">Número de radicado <small class="text text-muted">Para incluir mas de un número de radicado separarlo por coma (,)</small></label>
                            <input type="text" class="form-control" name="radicados" id="radicados">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit">Buscar</button>
                            <button class="btn btn-default pull-right" style="margin-left: 5px;" type="button" id="enviar_todos">Enviar todos</button>
                            <a class="btn btn-default pull-right" href="export.php" id="exportar">Exportar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <table id="envios" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Radicado salida</th>
                        <th>Radicado padre</th>
                        <th>Fecha radicado</th>
                        <th>Descripción</th>
                        <th>Fecha impresión</th>
                        <th>Generado por</th>
                        <th>Certificado</th>
                        <th>Emails</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
            </table>
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
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="errorMessage">Se ha producido un error.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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

            function reloadDataTable() {
                var table = $('#envios').DataTable();
                var currentPage = table.page();
                table.ajax.reload(null, false);
                table.page(currentPage).draw(false);
            }

            $('#dependencia').selectpicker({
                liveSearch: true,
                deselectAllText: 'Deseleccionar todos',
                selectAllText: 'Seleccionar todos',
                size: 10
            });

            $('#usuario').selectpicker({
                liveSearch: true,
                deselectAllText: 'Deseleccionar todos',
                selectAllText: 'Seleccionar todos',
                size: 10
            });

            $('form').on('submit', function(e) {
                e.preventDefault();
                $('#envios').DataTable().ajax.reload();
            });

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
                "columnDefs": [
                    { "orderable": true, "targets": [0, 1, 2] }, // Allow ordering on RADICADO_SALIDA and RADICADO_PADRE
                    { "orderable": false, "targets": "_all" } // Disable ordering on all other columns
                ],
                "columns": [
                    { "data": "RADICADO_SALIDA" },
                    { "data": "RADICADO_PADRE" },
                    { "data": "FECHA_RADICADO" },
                    { "data": "DESCRIPCION" },
                    { "data": "FECHA_IMPRESION" },
                    { "data": "GENERADO_POR" },
                    { "data": "CERTIFICADO" },
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

            $('body').on('click', '.registro', function() {
                var logData = $(this).data('log');
                var logTableBody = $('#errorLogTableBody');
                logTableBody.empty();
                
                logData.forEach(function(item) {
                    logTableBody.append(`
                        <tr class="${item.status == 'success' ? 'success' : 'danger'}">
                            <td>${item.correo}</td>
                            <td>${item.message}</td>
                            <td>${item.timestamp}</td>
                        </tr>
                    `);
                });

                $('#recordLogModal').modal('show');
            });

            $('body').on('click', '.enviar', function() {
                var id = $(this).data('id');
                var button = $(this);
                button.prop('disabled', true);
                button.html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
                $.ajax({
                    url: 'send.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        button.prop('disabled', false);
                        button.html('<span class="glyphicon glyphicon-send"></span>');
                        console.log(response);

                        var allSuccess = true;
                        var errorMessages = [];

                        for (var key in response.registro) {
                            if (response.registro.hasOwnProperty(key)) {
                                var registros = response.registro[key];
                                for (var index in registros) {
                                    if (registros.hasOwnProperty(index)) {
                                        var registro = registros[index];
                                        if (registro.status !== 'success') {
                                            allSuccess = false;
                                            errorMessages.push(registro.message);
                                        }
                                    }
                                }
                            }
                        }

                        if (allSuccess) {
                            $('#infoMessage').text('Envío realizado correctamente.');
                            $('#infoModal').modal('show');
                        } else {
                            $('#infoMessage').html('Algunos registros no se enviaron correctamente.');
                            $('#infoModal').modal('show');
                        }
                        
                        reloadDataTable();
                    },
                    error: function() {
                        $('#errorMessage').text('Ocurrió un error al intentar enviar el registro.');
                        $('#errorModal').modal('show');
                        button.prop('disabled', false);
                        button.html('<span class="glyphicon glyphicon-send"></span>');
                        reloadDataTable();
                    }
                });
            });

            $('#enviar_todos').on('click', function() {
                var button = $(this);
                button.prop('disabled', true);
                button.html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Enviando...');
                $('.enviar').prop('disabled', true);

                var table = $('#envios').DataTable();
                var visibleData = table.rows({ page: 'current' }).data().toArray();

                var ids = visibleData.map(function(row) {
                    return row.ID;
                });

                if (ids.length === 0) {
                    $('#infoMessage').text('No hay registros en la página actual.');
                    $('#infoModal').modal('show');
                    button.prop('disabled', false);
                    $('.enviar').prop('disabled', false);
                    button.html('Enviar todos');
                    return;
                }

                $.ajax({
                    url: 'send.php',
                    type: 'POST',
                    data: { id: ids },
                    success: function(response) {
                        reloadDataTable();
                        button.prop('disabled', false);
                        button.html('Enviar todos');
                        $('.enviar').prop('disabled', false);
                        var allSuccess = response.registro.every(function(item) {
                            return item.status === 'success';
                        });

                        if (allSuccess) {
                            $('#infoMessage').text('Envío realizado correctamente.');
                            $('#infoModal').modal('show');
                        } else {
                            $('#infoMessage').text('Algunos registros no se enviaron correctamente. Por favor, revise el registro.');
                            $('#infoModal').modal('show');
                        }
                    },
                    error: function() {
                        button.prop('disabled', false);
                        button.html('Enviar todos');
                        $('.enviar').prop('disabled', false);
                        $('#infoMessage').text('Ocurrió un error al intentar enviar los correos.');
                        $('#infoModal').modal('show');
                        reloadDataTable();
                    }
                });
            });

            $('#exportar').on('click', function(e) {
                e.preventDefault();

                var dependencia = $('#dependencia').val();
                var usuario = $('#usuario').val();
                var radicados = $('#radicados').val();

                var form = $('<form>', {
                    method: 'POST',
                    action: 'export.php'
                });

                form.append($('<input>', {
                    type: 'hidden',
                    name: 'dependencia',
                    value: dependencia
                }));

                form.append($('<input>', {
                    type: 'hidden',
                    name: 'usuario',
                    value: usuario
                }));

                form.append($('<input>', {
                    type: 'hidden',
                    name: 'radicados',
                    value: radicados
                }));

                $('body').append(form);
                form.submit();
            });
        });
    </script>
</body>
</html>
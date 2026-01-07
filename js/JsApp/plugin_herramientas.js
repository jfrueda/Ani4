$(function() {

    $('.btn-o-herr').on('click', function () {
        $('#tb_herr_listaexp').DataTable().ajax.reload();
    });

    $('#lanzar-herramienta').on('click', function(e) {
        if($('#herramienta_accion').val() == '')
        {
            alert('Seleccione la acción que desea realizar.');
            e.preventDefault();
            return false;
        }

        if($('input[name="expediente[]"]:checked').length == 0)
        {
            alert('Seleccione los expedientes a operar.');
            e.preventDefault();
            return false;
        }

        $('.herr_numExpedientesSeleccionados').text($('input[name="expediente[]"]:checked').length);
        $('#cambiar_responsable_submit').prop('disabled', false);
        $('#cambiar_responsable_cancel').prop('disabled', false);
        $('#cambiar_permisos_submit').prop('disabled', false);
        $('#cambiar_seguridad_cancel').prop('disabled', false);
        $('#'+$('#herramienta_accion').val()).modal('show');
    });

    $('#cambiar_responsable_submit').on('click', function(e) 
    {
        $('#cambiar_responsable_submit').prop('disabled', true);
        $('#cambiar_responsable_cancel').prop('disabled', true);
        var depe_codi = $('#herr_dep_resp').val();
        var usua_doc = $('#herr_respUsuaDoc').val();
        var expedientes = [];
        $('input[name="expediente[]"]:checked').each(function(i, element) {
            expedientes.push($(element).val());
        });

        var xhr = $.post(
            '../expediente/exp-rest.php',
            {
                fn: 'cambioResponsable',
                depe_codi,
                usua_doc,
                expedientes
            },
            'json'
        );

        xhr.done(function(res) {
            $('#'+$('#herramienta_accion').val()).modal('hide');
            if(res.data.status == 'success')
            {
                $('#mensaje_respuesta').text('Se cambio el responsable de '+expedientes.length+' expedientes.');
                $('.btn-o-herr').trigger('click');
            } else {
                $('#mensaje_respuesta').text('No se pudo completar la operación');
            }
            $('#cambiar_responsable_submit').prop('disabled', false);
            $('#cambiar_responsable_cancel').prop('disabled', false);
        });

        xhr.fail(function(){
            $('#mensaje_respuesta').text('No se pudo completar la operación');
            $('#cambiar_responsable_submit').prop('disabled', false);
            $('#cambiar_responsable_cancel').prop('disabled', false);
        });

        xhr.always(function() {
            $('#modal_respuesta').modal('show');
        });
    });

    $('#cambiar_permisos_submit').on('click', function(e)
    {
        $('#cambiar_permisos_submit').prop('disabled', true);
        $('#cambiar_seguridad_cancel').prop('disabled', true);
        var depe_codi = $('#herr_dep_seg_resp').val();
        var usua_codi = $('#herr_respSegUsuaDoc').val();
        var permisos = $('#herr_segExp').val();
        var expedientes = [];
        $('input[name="expediente[]"]:checked').each(function(i, element) {
            expedientes.push($(element).val());
        });

        var xhr = $.post(
            '../expediente/exp-rest.php',
            {
                fn: 'cambiarPermisosMasivo',
                depe_codi,
                usua_codi,
                permisos,
                expedientes
            },
            'json'
        );

        xhr.done(function(res) {
            $('#'+$('#herramienta_accion').val()).modal('hide');
            if(res.data.status == 'success')
            {
                $('#mensaje_respuesta').text('Se cambio la seguridad de '+expedientes.length+' expedientes.');
                $('.btn-o-herr').trigger('click');
            } else {
                $('#mensaje_respuesta').text('No se pudo completar la operación');
            }
            
            $('#cambiar_permisos_submit').prop('disabled', false);
            $('#cambiar_seguridad_cancel').prop('disabled', false);
        });

        xhr.fail(function(){
            $('#mensaje_respuesta').text('No se pudo completar la operación');
            $('#cambiar_permisos_submit').prop('disabled', false);
            $('#cambiar_seguridad_cancel').prop('disabled', false);
        });

        xhr.always(function() {
            $('#modal_respuesta').modal('show');
        });
    });

    $('#tb_herr_listaexp').DataTable({
        "pageLength": 25,
        "lengthMenu": [25, 50, 100, 500],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "../expediente/exp-rest.php",
            "type": "POST",
            "data": function(d) {
                d.fn = 'bsqexp_paginado';
                d.numExp = $('#herr_nume_expe').val();
                d.radicado = $('#herr_nume_radi').val();
                d.parametro = $('#herr_nomexpe').val(); 
                d.depe = $('#herr_dep').val();
                d.usuar = $('#herr_usuaDoc').val();
            }
        },
        "searching": false,
        "order": [],
        "columnDefs": [
            //{ "orderable": true, "targets": [1, 2] },
            { "orderable": false, "targets": "_all" }
        ],
        "columns": [
            { "data": null, "orderable": false, "searchable": false, "render": function(data, type, row) {
                    return '<input type="checkbox" name="expediente[]" value="' + row.NUM + '" />';
                }
            },
            { "data": "NUM", "className": "text-center" },
            { "data": "FECH", "className": "text-center" },
            { "data": "TITULO", "className": "text-left" },
            { "data": "RESPONSABLE", "className": "text-right" },
            { "data": "CREADOR", "className": "text-center" },
            { "data": "ESTADO", "className": "text-center", "render": function(data, type, row) {
                var estado = row.ESTADO == 1 ? 'Anulado' : row.estado == 2 ? 'Cerrado' : 'Abierto';
                if(estado == 'Anulado') {
                    return '<span class="badge badge-danger">' + estado + '</span>';
                } else if(estado == 'Cerrado') {
                    return '<span class="badge badge-warning">' + estado + '</span>';
                } else {
                    return '<span class="badge badge-success">' + estado + '</span>';
                }
            }}
        ]
    });
});
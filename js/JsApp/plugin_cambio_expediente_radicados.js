$(function(e) {
    var radicados_seleccionados = [];

    $('.tab_expediente').on('click', function(e) {
        radicados_seleccionados = [];
    });

    $('#btn-cambioExpediente').on('click', function(e) {
        $('#expediente_destino_estado').html('');
        $('#expediente_destino').val('');
        $('button#incluir').prop('disabled', true);

        if (radicados_seleccionados.length == 0)
        {
            $('#radicados_seleccionados').text(
                'No ha seleccionado ningún radicado para incluir.'
            );
        } else {
            var texto = '';
            radicados_seleccionados.forEach(function(element) {
                texto += (element.tipo == 'aexp' ? 'Anexo' : 'Radicado')+': '+element.seleccion+'<br>';
            });
            $('#radicados_seleccionados').html('Radicados a incluir:<br><small>'+texto+'</small>');
        }
    });

    $('#expediente_destino').on('blur', function(e) {
        $('#expediente_destino_estado').html('<small class="text-secondary">Verificando...</small>');
        $('button#incluir').prop('disabled', true);

        var request = $.post('exp-rest.php', {
            fn: 'validarEstadoExpediente',
            expediente: $(this).val(),
            estado: 0
        });

        request.done(function(data) {
            if(data.data.status == 'success') {
                $('#expediente_destino_estado').html('<small class="text-success">Expediente valido: '+data.data.expediente['SGD_SEXP_PAREXP1']+'</small>');
                if (radicados_seleccionados.length > 0)
                    $('button#incluir').prop('disabled', false);
            } else {
                $('#expediente_destino_estado').html('<small class="text-danger">El expediente que va a contener los radicados no existe o se encuentra en un estado inválido</small>');   
            }
        });
    });

    $('button#incluir').on('click', function(e) {
        
        var expediente = $('#expediente_destino').val();
        if(confirm('Esta seguro de realizar la inclusión de los elementos seleccionados al expediente: '+expediente))
        {
            var request = $.post('exp-rest.php', {
                fn: 'incluirEnExpediente',
                expediente,
                radicados_seleccionados
            });

            request.done(function(data) {
                if(data.data.status == 'success') {
                    window.location.reload();
                } else {
                    alert('No se pudo procesar la solicitud.');
                }
            });
        }
    });

    $('body').delegate('input[id="chks[]"]', 'change', function(e) {
        var seleccion = $(this).val();
        var selector = $('input[id="chks[]"][value="'+seleccion+'"]');
        var tipo = selector.data('tipo');
        var path = selector.data('path');
        if($(this).is(':checked')) {
            radicados_seleccionados.push({
                'seleccion': seleccion,
                'tipo': tipo,
                'path': path
            });
        } else {
            radicados_seleccionados = radicados_seleccionados.filter(function(radicado) {
                return radicado.seleccion !== seleccion
            })
        }

        console.log(radicados_seleccionados);
    });
});
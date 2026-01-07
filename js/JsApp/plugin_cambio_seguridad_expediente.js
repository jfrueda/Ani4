$(function(e) {
    $('#restaurar_seguridad').on('click', function(e) {
        
        $('#processing-modal').modal('show');
        $('#restaurarSeguridad').modal('hide');

        var request = $.post(
            'exp-rest.php', 
            {
                fn: 'restaurarSeguridadExpediente',
                expediente: $('#restaurarSeguridad_expediente').val(),
                nivel: $('#restaurarSeguridad_seguridad').val()
            }
        );

        request.done(function(data) {
            console.log(data);
            $('#processing-modal').modal('hide');
            window.location.reload();
        });
    });
});
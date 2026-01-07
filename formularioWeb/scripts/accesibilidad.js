$(function() {
    $('.contrast-ref').on('click', function(e) {
        var tema = $(this).data('tema');
        if (tema == 'claro')
            nuevo_tema = 'oscuro';
        else 
            nuevo_tema = 'claro';

        var request = $.post(
            'solicitudes_ajax.php',
            {
                servicio: 'cambiarTema',
                tema: nuevo_tema
            },
            'json'
        );

        request.done(function(res) {
            if(res.tema == 'claro')
                $('body').removeClass('oscuro')
            else
                $('body').addClass('oscuro')

            $('.contrast-ref').data('tema', res.tema);
        });
    });

    $('.max-fontsize').on('click', function(e) {
        $('body').addClass('zoom-in');
    });

    $('.min-fontsize').on('click', function(e) {
        $('body').removeClass('zoom-in');
    });
});
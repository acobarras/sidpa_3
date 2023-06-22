$(document).ready(function () {
    select_2();
    solo_numeros('valor_flete');
    alertify.set('notifier', 'position', 'bottom-left');
    envio_formulario();

});


var envio_formulario = function () {
    $('#adiciona_ruta').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#adiciona_diligencia').html();
        var form = $(this).serializeArray();
        var exepcion = ['valor_flete'];
        var valida = validar_formulario(form, exepcion);
        if (valida) {
            if ($('#valor_flete').val() == '') {
                alertify.error('Lo sentimos el valor del flete no puede ser vacio si no se paga el flete coloque cero.');
                $('#valor_flete').focus();
                return;
            }
            btn_procesando('adiciona_diligencia');
            $.ajax({
                url: `${PATH_NAME}/logistica/agregar_diligencia`,
                type: 'POST',
                data: form,
                success: function (res) {
                    console.log(res);
                    if (res.status) {
                        $('#adiciona_ruta')[0].reset();
                        limpiar_formulario('adiciona_ruta', 'select');
                        alertify.success('Datos ingresdados correctamente.');
                    } else {
                        alertify.error('Lo sentimos algo sucedio solicite soporte con el area');
                    }
                    btn_procesando('adiciona_diligencia', obj_inicial, 1);
                }
            });
        }
    });
}
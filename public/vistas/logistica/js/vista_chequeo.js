$(document).ready(function () {
    select_2();
    carga_data();
    enviar_chequeo();
});
var carga_data = function () {
    $('#vehiculo').on('change', function () {
        var data = JSON.parse($('option:selected').attr('data_vehi'));
        $('#propietario').val(data.nombre + ' ' + data.apellido);
        $('#capacidad').val(data.capacidad);
    });
}
var enviar_chequeo = function () {
    $('#form_chequeo_vehiculo').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        var form1 = document.getElementById('form_chequeo_vehiculo');
        if (valida) {
            $.ajax({
                url: `${PATH_NAME}/enviar_chequeo`,
                type: 'POST',
                data: new FormData(form1),
                processData: false,
                cache: false,
                contentType: false,
                success: function (res) {
                    if (res.status == 1) {

                    }
                }
            });
        }
    });
}
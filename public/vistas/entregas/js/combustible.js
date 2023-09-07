$(document).ready(function () {
    datos();
    consulta_combustible();
    enviar_combustible();
});
var datos = function () {
    $('.saca_calor').on('blur', function () {
        var precio_galon = $('#precio_galon').val();
        var valor_tanquea = $('#valor_tanqueado').val();
        var cant_galones = valor_tanquea / precio_galon;
        $('#cant_galones').val(cant_galones.toFixed(2));
    })
}

var consulta_combustible = function () {
    var table = $('#tab_combustible').DataTable({
        ajax: `${PATH_NAME}/consulta_combustible`,
        columns: [
            { "data": "fecha_crea" },
            { "data": "valor_tanqueado" },
            { "data": "precio_galon" },
            { "data": "kilometraje"},
            { "data": "cant_galones" },
            { "data": "km_galon", render: $.fn.dataTable.render.number('.', ',', 2, '') },
        ],
    });
}


var enviar_combustible = function () {
    $('#enviar_combus').on('click', function () {
        var form1 = $('#form_combustible').serializeArray();
        var excepcion = ['kilometraje_ant'];
        var valida = validar_formulario(form1, excepcion);
        if (valida) {
            var form = $('#form_combustible').serialize();
            $.ajax({
                url: `${PATH_NAME}/enviar_combustible`,
                type: 'POST',
                data: { form },
                success: function (res) {
                    alertify.success('Registro exitoso');
                    location.reload();
                }
            });
        }
    });
}
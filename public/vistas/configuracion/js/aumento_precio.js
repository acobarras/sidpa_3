$(document).ready(function () {
    select_2();
    enviar_aumento_cliente();
});
var enviar_aumento_cliente = function () {
    $('#form_aumento_cliente').submit(function (e) {
        e.preventDefault();
        var form1 = $(this).serializeArray();
        var clientes = $('#num_cliente').val();
        var porcentaje = $('#porcentaje').val();
        var excepcion = ['num_cliente', 'tipo_articulo', 'aumento'];
        if (clientes.length == 0) {
            clientes = 0;
        }
        // Validar el radio de aumento o disminucion
        if ($("#aumento_si").prop('checked') == false && $("#aumento_no").prop('checked') == false) {
            alertify.error('debe seleccionar si es aumento o disminuciòn');
            return;
        }
        if ($("#aumento_si").prop('checked') == true && $("#aumento_no").prop('checked') == true) {
            alertify.error('debe seleccionar una sola opción aumento o disminución');
            return;
        }
        if ($("#aumento_si").prop('checked') == true && $("#aumento_no").prop('checked') == false) {
            var aumento = 1;
        }
        if ($("#aumento_si").prop('checked') == false && $("#aumento_no").prop('checked') == true) {
            var aumento = 2;
        }
        // validar si es tecnologia o etiquetas
        if ($("#tipo_articulo_si").prop('checked') == false && $("#tipo_articulo_no").prop('checked') == false) {
            alertify.error('debe seleccionar si es tecnologia o etiquetas');
            return;
        }
        if ($("#tipo_articulo_si").prop('checked') == true && $("#tipo_articulo_no").prop('checked') == true) {
            alertify.error('debe seleccionar si es tecnologia o etiquetas');
            return;
        }
        if ($("#tipo_articulo_si").prop('checked') == true && $("#tipo_articulo_no").prop('checked') == false) {
            var tipo_articulo = 2;
        }
        if ($("#tipo_articulo_si").prop('checked') == false && $("#tipo_articulo_no").prop('checked') == true) {
            var tipo_articulo = 3;
        }
        var valida = validar_formulario(form1, excepcion);
        var obj_inicial = $('#envio_aumento_precio').html();
        if (valida) {
            btn_procesando('envio_aumento_precio');
            alertify.confirm('Confirmación', 'Desea editar el precio autorizado y el precio de venta',
                function () {
                    var valor = 1;
                    var envio = {
                        'clientes': clientes,
                        'aumento': aumento,
                        'porcentaje': porcentaje,
                        'ambos_precios': valor,
                        'tipo_articulo': tipo_articulo,
                    };
                    enviar_ajax(envio, obj_inicial);
                },
                function () {
                    var valor = 2;
                    var envio = {
                        'clientes': clientes,
                        'aumento': aumento,
                        'porcentaje': porcentaje,
                        'ambos_precios': valor,
                        'tipo_articulo': tipo_articulo,
                    };
                    enviar_ajax(envio, obj_inicial);
                }).set('labels', { ok: 'Si', cancel: 'No' });
        }
    });
}

var enviar_ajax = function (envio, obj_inicial) {
    $.ajax({
        url: `${PATH_NAME}/enviar_aumento_cliente`,
        type: 'POST',
        data: envio,
        success: function (res) {
            if (res.status == -1) {
                alertify.error(res.msg);
            } else {
                alertify.success(res.msg);
            }
            btn_procesando('envio_aumento_precio', obj_inicial, 1);
            limpiar_formulario('form_aumento_cliente', 'select');
            limpiar_formulario('form_aumento_cliente', 'input');
        }
    });
}
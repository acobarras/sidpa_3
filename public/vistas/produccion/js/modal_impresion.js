$(document).ready(function () {
    imprimir_trasavilidad();
    cajas_impresion();
    boton_imprime();
});
// funciones para el modal de impresion de remarcación
var imprimir_trasavilidad = function () {
    $('#tabla_embobinado tbody').on('click', 'button.imprimir_trasavilidad', function () {
        var data_item = $(this).attr('data-item');
        var data_m = $(this).attr('data-m');
        var item = JSON.parse(data_item);
        limpiar_formulario('formulario_remarcacion', 'input');
        $('.respu_consulta').empty().html('');
        $('.div_impresion').empty().html('');
        $('#lote').val(item.n_produccion);
        $('#cant_x').val(item.cant_x);
        $('#ImpresionItemsModal').modal('toggle');
        $('#boton_imprime').attr('item', data_item);
    });
}

var boton_imprime = function () {
    $('#formulario_remarcacion').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        if (form[0].value == 2) {
            valida = validar_formulario(form);
        } else {
            var exception = ['caja'];
            valida = validar_formulario(form, exception);
        }
        if (valida) {
            var form = $(this).serialize();
            var data = $('#boton_imprime').attr('item');
            $.post(`${PATH_NAME}/produccion/impresion_etiquetas_marcacion`,
                {
                    datos: data,
                    formulario: form
                },
                function (respu) {
                    $('.div_impresion').empty().html(respu);
                    var mode = 'iframe'; //popup
                    var close = mode == "popup";
                    var options = { mode: mode, popClose: close };
                    $("div.div_impresion").printArea(options);
                    $('#ImpresionItemsModal').modal('toggle');
                });
        }

    });
}

var cajas_impresion = function () {
    $('#tamano').on('change', function () {
        if ($(this).val() == 2) {
            $('.cajass').css('display', '');
        } else {
            $('.cajass').css('display', 'none');
            $('.cajass').val('');
            $('#caja').val('');

        }
    });
}

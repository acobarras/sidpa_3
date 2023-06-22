$(document).ready(function () {
    select_2();
    consultar_pedido();
    mostrar_formulario();
    boton_imprime();
    cajas_impresion();
    consulta_operario();
    // operarioGlobal = [];
});

var consulta_operario = function () {
    $('.codigo_operario').on('change', function () {
        var documento = $(this).val();
        $.ajax({
            url: `${PATH_NAME}/produccion/validar_operario`,
            type: "POST",
            data: { documento },
            success: function (respu) {
                if (respu != '') {
                    var items = `<h4> Nombre :
                    <span style="color: blue">${respu[0].nombres} ${respu[0].apellidos}</span>
                    </h4>
                    `;
                    var atributo = false;
                    var id_persona = respu[0].id_persona;
                } else {
                    var items = `<h4>
                    <span style="color: red">El código del operario es incorrecto !!</span>
                    </h4>`;
                    var atributo = true;
                    var id_persona = 0;
                }
                $('.respu_consulta').empty().html(items);
                $('.boton_imprime').prop('disabled', atributo);
                $('#id_persona').val(id_persona);
            }
        });
    });
}

var consultar_pedido = function () {
    $('.num_pedio').on('change', function () {
        var num_pedido = $(this).val();
        $.ajax({
            type: "POST",
            url: `${PATH_NAME}/produccion/consultar_items_pedido_impresion`,
            data: { num_pedido },
            success: function (response) {
                var items = '<option value="0"></option>';
                if (response != -1) {
                    response.forEach(element => {
                        items += `
                            <option value='${JSON.stringify(element)}'>Item ${element.item} Codigo ${element.codigo}</option>                   
                        `;
                    });
                    $('#envio').prop('disabled', false);
                } else {
                    items = `<option value="0"></option>`;
                    $('#envio').prop('disabled', true);
                    $('#formulario_remarcacion').css('display', 'none');
                }
                $('#slect_items').empty().html(items);
            }
        });
    });

}

//  funcion para mostrar el formulario
var mostrar_formulario = function () {
    $('#envio').on('click', function () {
        limpiar_formulario('formulario_remarcacion', 'input');
        $('.respu_consulta').empty().html('');
        $('.div_impresion').empty().html('');
        $('#formulario_remarcacion').css('display', '');
        $('.mensaje_data_proo').empty().html('');
        $('.codigo_operario').val('');
        var valor = $('#slect_items').val();
        $('#boton_imprime').attr('item', valor);
        var item = JSON.parse(valor);
        $('#cant_x').val(item.cant_x);
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
                    formulario: form,
                },
                function (respu) {
                    $('.div_impresion').empty().html(respu);
                    var mode = 'iframe'; //popup
                    var close = mode == "popup";
                    var options = { mode: mode, popClose: close };
                    $("div.div_impresion").printArea(options);
                    $('#formulario_remarcacion').css('display', 'none');
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

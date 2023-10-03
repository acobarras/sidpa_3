$(document).ready(function () {
    cotizar_etiquetas();
    chequeo_tintas();
    $('#material').select2();
    $('#adh').select2();
    medida_utilizar();
    selec_adh();
    estampado_calor();
    dato_adh();
});

var estampado_calor = function () {
    $('.estcalor').on('click', function () {
        if ($(this).val() == 1) {
            $('#cantestcalor').css('display', '');
            $('#cantestcalor').prop('disabled', false);
        } else {
            $('#cantestcalor').css('display', 'none');
            $('#cantestcalor').css('disabled', true);
        }
    });
}

var cotizar_etiquetas = function () {
    $('#enviar_cotiza').on('click', function () {
        var form = $('#formu_cotizador').serializeArray();
        if ($('#resultado').is(':visible')) {
            $('#resultado').hide();
        }
        if ($('#error_cotiza').is(':visible')) {
            $('#error_cotiza').hide();
        }
        var exepcion = ['selec_adh', 'selec_precio', 'tintas', 'laminado'];
        var valida = validar_formulario(form, exepcion);
        if (valida) {
            var obj_inicial = $("#enviar_cotiza").html();
            // btn_procesando('enviar_cotiza');
            $.ajax({
                url: `${PATH_NAME}/comercial/calcular_cotizacion_etiquetas`,
                type: 'POST',
                data: form,
                success: function (data) {
                    if (data.status == -1) {
                        if (!$('#error_cotiza').is(':visible')) {
                            $('#error_cotiza').show();
                        }
                        $('#error_cotiza h1').empty().html('Lo sentimos no hay precio de materia prima solicite su creación');
                        btn_procesando('enviar_cotiza', obj_inicial, 1);
                    } else {
                        if (data == '' || data == null) {
                            if (!$('#error_cotiza').is(':visible')) {
                                $('#error_cotiza').show();
                            }
                            $('#error_cotiza h1').empty().html('Lo sentimos no hay unidad magnetica para este tamaño');
                            btn_procesando('enviar_cotiza', obj_inicial, 1);
                        } else {
                            if (!$('#resultado').is(':visible')) {
                                $('#resultado').show();
                            }
                            $.each(data, function (name, value) {
                                $(`.${name}`).empty().html(value);
                            });
                            rellenarFormulario(data);
                            btn_procesando('enviar_cotiza', obj_inicial, 1);
                        }
                    }
                }
            });
        }
    });
};

var chequeo_tintas = function () {
    $('#tintas').on('change', function (e) {
        e.preventDefault();
        var tintas = $(this).val();
        if (tintas == '') {
            alertify.error('El campo no puede estar Vacio');
            $(this).focus();
            return;
        }
        if (tintas != '0') {
            $('#cyrel1').prop('checked', true);
            $('#cyrel2').prop('checked', false);
        } else {
            $('#cyrel1').prop('checked', false);
            $('#cyrel2').prop('checked', true);
        }
    });
}

var medida_utilizar = function () {
    $('.tipo_cotiza').on('change', function (e) {
        e.preventDefault();
        var elegido = $(this).val();
        if (elegido == 1) {
            $('#alto').attr('placeholder', 'Medida en Milimetros');
        } else {
            $('#alto').attr('placeholder', 'Medida en Metros');
        }
        // alert(elegido);
    });
}

var selec_adh = function () {
    $('#material').on('change', function () {
        var elegido = $(this).val();
        var data = JSON.parse($('#selec_adh').val());
        var data_precio = JSON.parse($('#selec_precio').val());
        var nuevo = [];
        data_precio.forEach(element => {
            if (element.id_tipo_material == elegido) {
                nuevo.push(element.id_adhesivo);
            }
        });
        var items = '<option value="0"></option>';
        if (nuevo == '') {
            data.forEach(element => {
                items += `<option value="${element.id_adh}" data-adh='${JSON.stringify(element)}'>${element.nombre_adh}</option>`;
            });
        } else {
            data.forEach(element => {
                var indice = nuevo.indexOf(element.id_adh);
                if (indice !== -1) {
                    items += `<option value="${element.id_adh}" data-adh='${JSON.stringify(element)}'>${element.nombre_adh}</option>`;
                }
            });
        }
        $('#adh').empty().html(items);
    })
    
}

var dato_adh = function () {
    $('#adh').on('change', function () {
        var data = JSON.parse($('option:selected',this).attr('data-adh'));
        $('#superficies').empty().html('El Adhesivo seleccionado tiene un buen comportamiento en las siguientes superficies: '+data.superficies);
        $('#rango_temp').empty().html('El rango de temperatura de servicio es de: '+data.rango_temp);
    });
}
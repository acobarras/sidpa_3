$(document).ready(function () {
    crear_bobina();
    valida_codigo_bobi();
    valida_solo_numeros();
    valida_metros();
    select_2();
});

var crear_bobina = function () {
    $('#bobina').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#btn_ingresar_bobi').html();
        btn_procesando('btn_ingresar_bobi');
        var formu = $(this).serializeArray();
        console.log(formu);
        var valida = validar_formulario(formu);
        var descrip = $('#btn_ingresar_bobi').attr('data-valida');
        if (valida) {
            if (descrip == 'true') {
                $.ajax({
                    url: `${PATH_NAME}/almacen/registrar_tecnologia`,
                    type: 'POST',
                    data: formu,
                    success: function (res) {
                        if (res.status) {
                            alertify.success('se ingreso correctamente')
                            btn_procesando('btn_ingresar_bobi', obj_inicial, 1);
                            limpiar_formulario('bobina', 'select')
                            // limpiar_formulario('tecnologia','span')
                            $("#bobina")[0].reset();
                            $('#respuesta').empty().html('');

                        } else {
                            alertify.success('no se pudo ingresar')
                            btn_procesando('btn_ingresar_bobi', obj_inicial, 1);

                        }

                    }
                });
            } else {
                alertify.error('el codigo no existe');
                btn_procesando('btn_ingresar_bobi', obj_inicial, 1);
            }
        } else {
            btn_procesando('btn_ingresar_bobi', obj_inicial, 1);//volver el boton a su estado inicial
        }


    });
}

var valida_codigo_bobi = function () {
    $('#codigo_producto_bob').on('blur', function () {
        var codigo = $(this).val();
        $.ajax({
            "url": `${PATH_NAME}/almacen/validar_codigo_tecno`,
            "type": 'POST',
            "data": { codigo },
            "success": function (respu) {
                if (respu.estado) {
                    if (respu.id_tipo_articulo == 4) {
                        $('#id_producto').val(respu.id_producto)
                        $('#respuesta').empty().html(respu.mensaje);
                        $('#btn_ingresar_bobi').attr('data-valida', true);
                    } else {
                        $('#respuesta').empty().html("Este producto no pecrtenece a este modulo.");
                        $('#codigo_producto_bob').focus();
                        $('#btn_ingresar_bobi').atrr('data-valida', false);
                    }
                } else {
                    $('#respuesta').empty().html(respu.mensaje);
                    $('#codigo_producto_bob').focus();
                    $('#btn_ingresar_bobi').atrr('data-valida', false);
                }
            }
        });
    })
}

var valida_solo_numeros = function () {
    $('.solo-numeros').keyup(function () {
        this.value = (this.value + '').replace(/[^0-9.]/g, '');
    });
}
var valida_metros = function () {
    $('#metro_lineales').change(function () {
        var ancho = $('#ancho').val();
        var metrosL = $(this).val();
        var m2 = (ancho * metrosL) / 1000;
        $('#m2').val(m2);

    });
    $('#metro_linealesOP').change(function () {
        var ancho = $('#anchoOP').val();
        var metrosL = $(this).val();
        var m2 = (ancho * metrosL) / 1000;
        $('#m2OP').val(m2);
    });

}
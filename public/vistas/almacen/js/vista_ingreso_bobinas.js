$(document).ready(function () {
    crear_bobina();
    valida_codigo_bobi();
    valida_solo_numeros();
    valida_metros();
    select_2();
    $('.select2-container ').css('width', '100%');
    ubicacion();
});

var crear_bobina = function () {
    $('#bobina').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#btn_ingresar_bob').html();
        btn_procesando('btn_ingresar_bob');
        var formu = $(this).serializeArray();
        var id_tipo_articulo = $('#id_tipo_articulo').val();
        if (id_tipo_articulo == 4) {
            var exepcion = ['id_productos'];
        } else {
            var exepcion = ['id_productos', 'ancho'];
        }
        var valida = validar_formulario(formu, exepcion);
        if (valida) {
            var codigo_val = JSON.parse($('#btn_ingresar_bob').attr('data-valida'));
            if (codigo_val) {
                $.ajax({
                    url: `${PATH_NAME}/almacen/registrar_tecnologia`,
                    type: 'POST',
                    data: formu,
                    success: function (res) {
                        if (res.status) {
                            alertify.success('se ingreso correctamente');
                            btn_procesando('btn_ingresar_bob', obj_inicial, 1);
                            // limpiar_formulario('bobina', 'select');
                            $("#bobina")[0].reset();
                            $('#respuesta').empty().html('');

                        } else {
                            alertify.error('No se pudo ingresar');
                            btn_procesando('btn_ingresar_bob', obj_inicial, 1);

                        }

                    }
                });
            } else {
                btn_procesando('btn_ingresar_bob', obj_inicial, 1);//volver el boton a su estado inicial
                alertify.error('Codigo de Producto no valido.');
            }
        } else {
            btn_procesando('btn_ingresar_bob', obj_inicial, 1);//volver el boton a su estado inicial

        }
    });
}

var valida_codigo_bobi = function () {
    $('#codigo_producto').on('change', function () {
        var data = $(this).val();
        var cant = data.split(';');
        var codigo = cant['0'];
        var ancho = cant['1'];
        var metros = cant['2'];
        $('#codigo_producto').val(codigo);
        $('#ancho').attr('readonly', 'readonly');
        $('#metro_lineales').attr('readonly', 'readonly');
        $.ajax({
            "url": `${PATH_NAME}/almacen/validar_codigo_tecno`,
            "type": 'POST',
            "data": { codigo },
            "success": function (respu) {
                $('#ancho').removeAttr('readonly');
                $('#metro_lineales').removeAttr('readonly');
                if (respu.estado) {
                    if (respu.id_tipo_articulo == 4 || respu.id_tipo_articulo == 15) {
                        $('#id_producto').val(respu.id_producto);
                        $('#id_tipo_articulo').val(respu.id_tipo_articulo);
                        $('#respuesta').empty().html(respu.mensaje);
                        $('#btn_ingresar_bob').attr('data-valida', true);
                        $('#ancho').val(ancho).change();
                        $('#metro_lineales').val(metros).trigger('keyup');
                    } else {
                        $('#respuesta').empty().html("Este producto no pertenece a este modulo.");
                        $('#codigo_producto').focus();
                        $('#btn_ingresar_bob').attr('data-valida', false);

                    }
                } else {
                    $('#respuesta').empty().html(respu.mensaje);
                    $('#codigo_producto').focus();
                    $('#btn_ingresar_bob').attr('data-valida', false);
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
    $('#metro_lineales').keyup(function () {
        var id_tipo_articulo = $('#id_tipo_articulo').val();
        var ancho = $('#ancho').val();
        var metrosL = $(this).val();
        if (id_tipo_articulo == 4 && ancho != 0) {
            var m2 = (ancho * metrosL) / 1000;
            $('#m2').val(m2);
        } else {
            $('#m2').val(metrosL);
        }

    });
}

var ubicacion = function () {
    $('#ancho').on('change', function () {
        var valor = $(this).val();
        $('#ubicacion').val(valor);
    });
}
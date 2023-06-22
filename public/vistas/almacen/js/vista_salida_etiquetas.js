$(document).ready(function () {
    select_2();
    ingresar_etiquetas();
    validar_codigo_etiq();
});

var ingresar_etiquetas = function () {
    $('#etiqueta').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#btn_ingresar_eti').html();
        btn_procesando('btn_ingresar_eti');
        var formu = $(this).serializeArray();
        var valida = validar_formulario(formu);
        var descrip = $('#btn_ingresar_eti').attr('data-valida');
        if (valida) {
            if (descrip == 'true') {
                $.ajax({
                    url: `${PATH_NAME}/almacen/registrar_tecnologia`,
                    type: 'POST',
                    data: formu,
                    success: function (res) {
                        if (res.status) {
                            alertify.success('se ingreso correctamente')
                            btn_procesando('btn_ingresar_eti', obj_inicial, 1);
                            limpiar_formulario('etiqueta', 'select')

                            $("#etiqueta")[0].reset();
                            $('#respuesta').empty().html('');

                        } else {
                            alertify.success('no se pudo ingresar')
                            btn_procesando('btn_ingresar_eti', obj_inicial, 1);

                        }

                    }
                });
            } else {
                alertify.error('el codigo no existe');
                btn_procesando('btn_ingresar_eti', obj_inicial, 1);
            }
        } else {
            btn_procesando('btn_ingresar_eti', obj_inicial, 1);
        }
    });
}


var validar_codigo_etiq = function () {
    $('#codigo_producto_etiq').on('blur', function () {
        var codigo = $(this).val();
        $.ajax({
            "url": `${PATH_NAME}/almacen/validar_codigo_tecno`,
            "type": "POST",
            "data": { codigo },
            "success": function (respu) {
                if (respu.estado) {
                    if (respu.id_tipo_articulo == 1) {
                        $('#id_producto').val(respu.id_producto)
                        $('#respuesta').empty().html(respu.mensaje);
                        $('#btn_ingresar_eti').attr('data-valida', true);
                    } else {
                        $('#respuesta').empty().html("Este producto no pertenece a este modulo.");
                        $('#codigo_producto_etiq').focus();
                        $('#btn_ingresar_eti').attr('data-valida', false);
                        $('#dt_etiquetas_disponibles').DataTable().clear().draw();
                    }
                } else {
                    $('#respuesta').empty().html(respu.mensaje);
                    $('#codigo_producto_etiq').focus();
                    $('#btn_ingresar_eti').attr('data-valida', false);
                }

            }
        });
    });
}

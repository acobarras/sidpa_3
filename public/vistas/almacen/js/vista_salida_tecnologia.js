$(document).ready(function () {
    select_2();
    crear_tecnologia();
    elimina_espacio('codigo_producto', 'respu_codigo_tecno');
    validar_codigo();
});

//funcion para crear una tecnologia con la validacion del codigo del producto
var crear_tecnologia = function () {

    $('#tecnologia').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#btn_ingresar_tec').html();
        btn_procesando('btn_ingresar_tec');
        var formu = $(this).serializeArray();
        var valida = validar_formulario(formu);
        var descrip = $('#btn_ingresar_tec').attr('data-valida');
        if (valida) {
            if (descrip == 'true') {
                $.ajax({
                    url: `${PATH_NAME}/almacen/registrar_tecnologia`,
                    type: 'POST',
                    data: formu,
                    success: function (res) {
                        if (res.status) {
                            alertify.success('se ingreso correctamente')
                            btn_procesando('btn_ingresar_tec', obj_inicial, 1);
                            limpiar_formulario('tecnologia', 'select')
                            // limpiar_formulario('tecnologia','span')
                            $("#tecnologia")[0].reset();
                            $('#respuesta_tec').empty().html('');

                        } else {
                            alertify.success('no se pudo ingresar')
                            btn_procesando('btn_ingresar_tec', obj_inicial, 1);
                        }

                    }
                });
            } else {
                alertify.error('el codigo no existe');
                btn_procesando('btn_ingresar_tec', obj_inicial, 1);
            }
        } else {
            btn_procesando('btn_ingresar_tec', obj_inicial, 1);//volver el boton a su estado inicial
        }
    });
}
//funcion para validar el codigo del productp
var validar_codigo = function () {
    $('#codigo_producto_tec').on('blur', function () {
        var codigo = $(this).val();
        $.ajax({
            "url": `${PATH_NAME}/almacen/validar_codigo_tecno`,
            "type": 'POST',
            "data": { codigo },
            "success": function (respu) {
                if (respu.estado) {
                    if (respu.id_tipo_articulo == 1 || respu.id_tipo_articulo == 4) {
                        $('#respuesta_tec').empty().html("Este producto no pertenece a este modulo.");
                        $('#codigo_producto_tec').focus();
                        $('#btn_ingresar_tec').attr('data-valida', false);
                    } else {
                        $('#id_producto_tec').val(respu.id_producto)
                        $('#respuesta_tec').empty().html(respu.mensaje);
                        $('#btn_ingresar_tec').attr('data-valida', true);
                    }
                } else {
                    $('#respuesta_tec').empty().html(respu.mensaje);
                    $('#codigo_producto_tec').focus();
                    $('#btn_ingresar_tec').attr('data-valida', false);
                }
            }
        });
    });
}
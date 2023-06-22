$(document).ready(function () {
    select_2();
    ingreso_etiquetas();
    validar_codigo();
    nueva_ubicacion();
});

var ingreso_etiquetas = function () {
    $('#etiqueta').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#btn_ingresar_eti').html();
        btn_procesando('btn_ingresar_eti');
        var formu = $(this).serializeArray();
        valida = validar_formulario(formu);
        var descrip = $('#btn_ingresar_eti').attr('data-valida');
        if (valida) {
            if (descrip == 'true') {
                $.ajax({
                    url: `${PATH_NAME}/almacen/registrar_tecnologia`,
                    type: 'POST',
                    data: formu,
                    success: function (res) {
                        if (res.status) {
                            alertify.success('Se ingreso mercancia correctamente')
                            btn_procesando('btn_ingresar_eti', obj_inicial, 1);
                            limpiar_formulario('etiqueta', 'select')
                            $("#etiqueta")[0].reset();
                            $('#respuesta').empty().html('');
                            $('#dt_etiquetas_disponibles').DataTable().clear().draw();

                        } else {
                            alertify.error('Error a procesar.')
                            btn_procesando('btn_ingresar_eti', obj_inicial, 1);

                        }

                    }
                });
            } else {
                alertify.error('el codigo no existe');
                btn_procesando('btn_ingresar_eti', obj_inicial, 1);
                $('#dt_etiquetas_disponibles').DataTable().clear().draw();
            }
        } else {
            btn_procesando('btn_ingresar_eti', obj_inicial, 1);//volver el boton a su estado inicial
        }
    });
}

// funcion  para validar el codigo
var validar_codigo = function () {
    $('#codigo_producto').on('change', function () {
        var codigo = $(this).val();
        $.ajax({
            "url": `${PATH_NAME}/almacen/validar_codigo_tecno`,
            "type": 'POST',
            "data": { codigo },
            "success": function (respu) {
                if (respu.estado) {
                    if (respu.id_tipo_articulo == 1) {
                        $('#id_producto').val(respu.id_producto);
                        $('#respuesta').empty().html(respu.mensaje);
                        $('#btn_ingresar_eti').attr('data-valida', true);
                        tabla_ingreso_etiquetas(respu.id_producto);
                    } else {
                        $('#respuesta').empty().html("Este producto no pertenece a este modulo.");
                        $('#codigo_producto').focus();
                        $('#btn_ingresar_eti').attr('data-valida', false);
                        $('#dt_etiquetas_disponibles').DataTable().clear().draw();
                    }
                } else {
                    $('#respuesta').empty().html(respu.mensaje);
                    $('#codigo_producto').focus();
                    $('#btn_ingresar_eti').attr('data-valida', false);
                    $('#dt_etiquetas_disponibles').DataTable().clear().draw();

                }

            }
        });
    });
}

var tabla_ingreso_etiquetas = function (id_producto) {
    var id = id_producto;
    var tabla_ubi_item = $('#dt_etiquetas_disponibles').DataTable({
        "ajax": {
            "url": `${PATH_NAME}/almacen/consultar_seguimiento`,
            "type": "POST",
            "data": { id },
        },
        "columns": [
            { "data": "ubicacion" },
            {
                "data": "entrada",
                "render": function (data, type, row) {
                    var entrada = '<i class="fa fa-caret-up text-success"></i> ' + row['entrada'];
                    return entrada;
                }
            },
            {
                "data": "salida",
                "render": function (data, type, row) {
                    var salida = '<i class="fa fa-caret-down text-danger"></i> ' + row['salida'];
                    return salida;
                }
            },
            {
                "data": "total",
                "render": function (data, type, row) {
                    var total = '<i class="fa fa-equals text-info"></i> ' + row['total'];
                    return total;
                }
            },
        ],
    });

}

//------------------------------------------------------ fin de ingreso de mercancia etiquetas------------------------------------------------------------->

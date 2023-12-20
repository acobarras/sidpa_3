$(document).ready(function () {
    select_2();
    consultar_orden();
    modificar_cantidad();
    consultar_material();
    modificar_estado_op();
    validar_checked();
    editar_estado_item();
});

var numFormat = $.fn.dataTable.render.number('.', ',', 0, '').display;
var consultar_orden = function () {
    $('#form_consulta_produccion').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                url: `${PATH_NAME}/configuracion/consultar_orden`,
                type: 'POST',
                data: form,
                success: function (res) {
                    var table = $("#tabla_orden").DataTable({
                        "data": res,
                        "columns": [
                            {
                                "data": "pedido_item", render: function (data, type, row) {
                                    return `${row.num_pedido}-${row.item}`;
                                }
                            },
                            { "data": "n_produccion" },
                            { "data": "codigo" },
                            {
                                "data": "cant_op", render: function (data, type, row) {
                                    var input = `<input type="text" id="cantidad_op${row.id_pedido_item}" class="form-control cantidad_op" style='border:none;' value="${(numFormat(row.cant_op))}">`;
                                    return input;
                                }
                            },
                            { "data": "ancho_material" },
                            { "data": "cav_montaje" },
                            { "data": "avance", render: $.fn.dataTable.render.number('.', ',', 3, '') },
                            { "data": "metrosl", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                            { "data": "magnetico" },
                            { "data": "metros2", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                        ],
                    });
                }
            });
        }
    });
}

var modificar_cantidad = function () {
    $("#tabla_orden tbody").on("blur", "input.cantidad_op", function (e) {
        e.preventDefault();
        var datos = $('#tabla_orden').DataTable().row($(this).parents("tr")).data();
        var valor = $(`#cantidad_op${datos['id_pedido_item']}`).val();
        alertify.confirm('Editar Cantidad', 'Desea continuar con la edición para cambiar la cantidad?.',
            function () {
                $.ajax({
                    url: `${PATH_NAME}/configuracion/editar_cantidad_op`,
                    type: 'POST',
                    data: { datos, valor },
                    success: function (res) {
                        if (res.status == 1) {
                            alertify.success('modificacion exitosa');
                        } else {
                            alertify.error('Algo a ocurrido');
                        }
                    }
                });
            },
            function () {
                alertify.error('Operación cancelada');
            });
    });
}

var consultar_material = function () {
    $('#form_consulta_material').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                url: `${PATH_NAME}/configuracion/consultar_material`,
                type: 'POST',
                data: form,
                success: function (res) {
                    if (res.status == -1) {
                        alertify.error('No existe esta orden de produccion. por favor revisar');
                    } else {
                        cambio_material_op(res);
                        $('#form_cambio_material').css('display', 'block');
                        $('#num_op').empty().html(res.data[0]['num_produccion']);
                        $('#material_op').val(res.data[0]['material']);
                        if (res.data[0]['material_solicitado'] == '') {
                            $('#material_confir').val('Material sin confirmar');
                        } else {
                            $('#material_confir').val(res.data[0]['material_solicitado']);
                        }
                    }
                }
            });
        }
    });
}
var cambio_material_op = function (datos) {
    $('#form_cambio_material').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#cambio_material').html();
        var form = $(this).serializeArray();
        var material = $('#material_op').val();
        var material_solicitado = $('#material_confir').val();
        var material_nuevo = $('#nuevo_material').val();
        var envio = [];
        var valida = validar_formulario(form);
        if (valida) {
            btn_procesando('cambio_material');
            envio = {
                'material': material,
                'material_solicitado': material_solicitado,
                'material_nuevo': material_nuevo,
            }
            $.ajax({
                url: `${PATH_NAME}/configuracion/cambio_material_op`,
                type: 'POST',
                data: { datos, envio },
                success: function (res) {
                    if (res.status == 1) {
                        $('#num_produccion').val('');
                        alertify.success('Cambio de material exitoso');
                        btn_procesando('cambio_material', obj_inicial, 1);
                    } else {
                        alertify.error('Algo a ocurrido');
                        btn_procesando('cambio_material', obj_inicial, 1);
                    }
                }
            });
        }
    });
}

var selecciona = [];

var modificar_estado_op = function () {
    $('#form_consulta_op').submit(function (e) {
        e.preventDefault();
        // var obj_inicial = $('#consultar_op').html();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            // btn_procesando('consultar_op');
            $.ajax({
                url: `${PATH_NAME}/configuracion/modificar_estado_op`,
                type: 'POST',
                data: { form },
                success: function (res) {
                    selecciona = [];
                    $('#id_maquina').val(res[0].id_maquina);
                    var table = $("#tabla_op").DataTable({
                        "data": res,
                        "columns": [

                            { "data": "n_produccion" },
                            {
                                "data": "pedido",
                                render: function (data, type, row) {
                                    return row.num_pedido + '-' + row.item;
                                }
                            },
                            { "data": "descripcion_productos" },
                            { "data": "material" },
                            { "data": "nombre_maquina" },
                            { "data": "turno_maquina" },
                            {
                                "data": "check",
                                render: function (data, type, row) {
                                    var check = `<div class="select_acob">
                                    <input type="checkbox" class="validar_checked me-2">&nbsp;  
                                </div>`;
                                    return check;
                                }
                            },
                        ],
                    });

                }
            });
        }
    });
}

var validar_checked = function () {
    $('#tabla_op tbody').on("click", "tr input.validar_checked", function () {
        var data = $('#tabla_op').DataTable().row($(this).parents("tr")).data();
        if ($(this).prop('checked') == true) {
            var agrega = true
            $('#select_estado_op').attr('disabled', true);
            if (selecciona.length != 0) {
                for (var a = 0; a < selecciona.length; a++) {
                    agrega = data.id_pedido_item != selecciona[a].id_pedido_item
                    if (!agrega) {
                        return;
                    }
                }
            }
            if (agrega) {
                selecciona.push({
                    'id_pedido_item': data.id_pedido_item,
                    'item': data.item,
                    'id_pedido': data.id_pedido,
                    'n_produccion': data.n_produccion,
                    'total_items': data.total_items,
                    'num_pedido': data.num_pedido,
                });
            }
        } else {
            for (var i = 0; i < selecciona.length; i++) {
                if (data.id_pedido_item === selecciona[i].id_pedido_item) {
                    selecciona.splice(i, 1);
                }
            }
            if (selecciona.length == 0) {
                $('#select_estado_op').attr('disabled', false);
            }
        }

    });
}

var editar_estado_item = function () {
    $('#envia_datos').on('click', function () {
        var nuevo_estado = $('#select_estado_op option:selected').val();
        var texto_estado = $('#select_estado_op option:selected').text();
        if (nuevo_estado == 0) {
            texto_estado = 'Fin Proceso';
        }
        var num_produccion = $('#n_produccion_cambio').val();
        var id_maquina = $('#id_maquina').val();
        if (selecciona.length == 0) {
            alertify.confirm('Confirmación', 'Esta seguro que desea cambiar la O.P a ' + texto_estado,
                function () {
                    enviar_datos(texto_estado, nuevo_estado, num_produccion, id_maquina);
                },
                function () {
                    alertify.error('Operacion Cancelada');
                });
        } else {
            var cadena = '';
            selecciona.forEach(element => {
                cadena = cadena + element.num_pedido + '-' + element.item + ', ';
            });
            alertify.confirm('Confirmación', 'Esta seguro que desea eliminar los pedidos ' + cadena + 'de la O.P',
                function () {
                    enviar_datos(texto_estado, nuevo_estado, num_produccion, id_maquina);
                },
                function () {
                    alertify.error('Operacion Cancelada');
                });
        }
    })
}

var enviar_datos = function (texto_estado, nuevo_estado, num_produccion, id_maquina) {
    $.ajax({
        url: `${PATH_NAME}/configuracion/modificar_estado_orden`,
        type: 'POST',
        data: { selecciona, nuevo_estado, num_produccion, texto_estado, id_maquina },
        success: function (res) {
            console.log(res);
            // $('#tabla_op').DataTable().ajax.reload();
            // alertify.success('Orden Produccion Modificada correctamente');
        }
    });
}
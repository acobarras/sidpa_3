$(document).ready(function () {
    select_2();
    consultar_orden();
    modificar_cantidad();
    consultar_material();
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
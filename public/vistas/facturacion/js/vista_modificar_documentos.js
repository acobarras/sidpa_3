$(document).ready(function () {
    alertify.set('notifier', 'position', 'bottom-left');
    consulta_documentos();
    cantidad_por_facturar();
    cambiar_fecha();
});

var PRINCIPAL = [];
var DATOS_TABLA = [];
var numFormat = $.fn.dataTable.render.number('.', ',', 0, '').display;

var consulta_documentos = function () {
    $('#consulta_lista_de_empaque').on('click', function () {
        var obj_inicial = $('#consulta_lista_de_empaque').html();
        var numero_lista_de_empaque = $('#numero_factura_consulta').val();
        if (numero_lista_de_empaque == '') {
            alertify.error('Se requiere El numero de lista de empaque');
            return;
        }
        btn_procesando('consulta_lista_de_empaque', obj_inicial);
        $.ajax({
            url: `${PATH_NAME}/entregas/consulta_lista_empaque`,
            type: "POST",
            data: { numero_lista_de_empaque },
            success: function (res) {
                PRINCIPAL = res.cabecera;
                rellenar_span(res.cabecera);
                var numero_relacionado = res.cabecera.num_remision;
                if (res.cabecera.tipo_documento == 8 || res.cabecera.tipo_documento == 9) {
                    numero_relacionado = res.cabecera.num_factura;
                }
                $('#documento_relacionado').empty().html(`${res.cabecera.tipo_documento_letra} ${numero_relacionado}`);
                // Rellenamos la tabla
                DATOS_TABLA = res.items;
                tabla_documentos();
                btn_procesando('consulta_lista_de_empaque', obj_inicial, 1);
                $('#reportar_documento').attr('disabled', false);
            }
        });
    });
    $('#consulta_num').on('click', function () {
        var obj_inicial = $('#consulta_num').html();
        var numero_lista_de_empaque = $('#num_lista').val();
        if (numero_lista_de_empaque == '') {
            alertify.error('Se requiere El numero de lista de empaque');
            return;
        }
        btn_procesando('consulta_num', obj_inicial);
        $.ajax({
            url: `${PATH_NAME}/entregas/consulta_lista_empaque`,
            type: "POST",
            data: { numero_lista_de_empaque },
            success: function (res) {
                btn_procesando('consulta_num', obj_inicial, 1);
                $('#cliente').val(res['cabecera'].nombre_empresa);
                $('#pedido').val(res['cabecera'].num_pedido);
                $('#fecha_factura').val(res['cabecera'].fecha_factura);
                $('#fecha_factura_ant').val(res['cabecera'].fecha_factura);
                $('#id_control_factura').val(res['cabecera'].id_control_factura);
            }
        });
    });
}

var cambiar_fecha = function () {
    $('#fecha_factura').change(function () {
        var nueva_fecha = $('#fecha_factura').val();
        var fecha_ant = $('#fecha_factura_ant').val();
        var id_control_factura = $('#id_control_factura').val();
        if (nueva_fecha != fecha_ant) {
            alertify.confirm('Cambio Fecha Factura', 'Esta seguro de cambiar la fecha del documento?.',
                function () {
                    $.ajax({
                        url: `${PATH_NAME}/cambio_fecha_fac`,
                        type: "POST",
                        data: { nueva_fecha, id_control_factura },
                        success: function (res) {
                            if (res.status == 1) {
                                alertify.success(res.msg);
                                location.reload();
                            } else {
                                alertify.error(res.msg);
                            }
                        }
                    });
                },
                function () {
                    alertify.error('Operación cancelada');
                });
        } else {
            alertify.error('No se puede cambiar la fecha');
        }
    })
}

var tabla_documentos = function (limpio = '') {
    var data = DATOS_TABLA;
    if (limpio != '') {
        data = [];
    }
    $('#tabla_productos_items').DataTable({
        "data": data,
        'ordering': false,
        "searching": false,
        "paging": false,
        "info": false,
        columnDefs: [
            { "width": "10%", "targets": 1 },
        ],
        columns: [
            { "data": "codigo" },
            {
                "data": "cantidad_factura", render: function (data, type, row) {
                    var valor = row.cantidad_factura;
                    var input = numFormat(row.cantidad_factura);
                    if (row.estado_entrega_logistica <= 2 || row.estado_entrega_logistica == 4) {
                        input = `<input type="text" id="pendiente${row.id_pedido_item}" class="form-control cantidad_factura" style='border:none;'  value="${numFormat(row.cantidad_factura)}">`;
                    }
                    return input;
                }
            },
            { "data": "descripcion_productos" },
        ]
    });
}

var cantidad_por_facturar = function () {
    $("#tabla_productos_items tbody").on("blur", "input.cantidad_factura", function () {
        var data = $('#tabla_productos_items').DataTable().row($(this).parents("tr")).data();
        var numero = $(this).val();
        numero = numero.replace(/\./g, '');
        if (numero == 0 || numero == data.cantidad_por_facturar) {
            alertify.error('Que esta haciedo si no va a cambiar la cantidad no se ponga a jugar y cero menos puede usar.');
            $(`#pendiente${data.id_pedido_item}`).val(numFormat(data.cantidad_por_facturar));
            return;
        }
        alertify.confirm('Editar Cantidad Envio', 'Desea continuar con la edición para cambiar la cantidad reportada.',
            function () {
                $.ajax({
                    url: `${PATH_NAME}/facturacion/editar_lista_empaque`,
                    type: 'POST',
                    data: { data, numero },
                    success: function (res) {
                        if (res.status == 1) {
                            alertify.success(res.msg);
                            $('#consulta_lista_de_empaque').click();
                            location.reload();
                        } else {
                            alertify.error(res.msg);
                        }
                    }
                });
            },
            function () {
                $(`#pendiente${data.id_pedido_item}`).val(numFormat(data.cantidad_factura));
                alertify.error('Operación cancelada');
            });

    });

}
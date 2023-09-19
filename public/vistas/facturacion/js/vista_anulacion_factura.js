$(document).ready(function () {
    consulta_factura();
});

var consulta_factura = function () {
    $("#consulta_factura").on('submit', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                url: `${PATH_NAME}/anular_factura`,
                type: "POST",
                data: form,
                success: function (res) {
                    if (res != '') {
                        $('#boton_anular').html(`<button class="btn btn-danger btn-lg boton-x btn_anular" id="btn_anular" type="submit" data_fact='${JSON.stringify(res)}'>Anular</button>`)
                        enviar_anulacion();
                        var table = $("#tab_informe_facturas").DataTable({
                            "data": res.items,
                            "columns": [
                                {
                                    "data": "nombre_empresa", render: function (data, type, row) {
                                        return row.nombre_empresa;
                                    }
                                },
                                { "data": "num_fact" },
                                { "data": "num_lista" },
                                {
                                    "data": "tipo_documento", render: function (data, type, row) {
                                        return row.tipo_documento;
                                    }
                                },
                                {
                                    "data": "fecha_factura", render: function (data, type, row) {
                                        return row.fecha_factura;
                                    }
                                },
                                {
                                    "data": "cantidad_factura", render: function (data, type, row) {
                                        return row.cantidad_factura;
                                    }
                                },
                                {
                                    "data": "Cant_solicitada", render: function (data, type, row) {
                                        return row.Cant_solicitada;
                                    }
                                },
                                {
                                    "data": "num_pedido", render: function (data, type, row) {
                                        return row.num_pedido;
                                    }
                                },
                                {
                                    "data": "item", render: function (data, type, row) {
                                        return row.item;
                                    }
                                },
                                {
                                    "data": "codigo", render: function (data, type, row) {
                                        return row.codigo;
                                    }
                                },
                                {
                                    "data": "descripcion_productos", render: function (data, type, row) {
                                        return row.descripcion_productos;
                                    }
                                },
                            ],
                        });
                    } else {
                        alertify.error('No existe esta factura');
                    }
                }
            });
        }
    });
}

var enviar_anulacion = function () {
    $('.btn_anular').on('click', function () {
        var data_fac = JSON.parse($(this).attr('data_fact'));
        var obj_inicial = $(`#btn_anular`).html();
        btn_procesando_tabla(`btn_anular`);
        $.ajax({
            url: `${PATH_NAME}/envia_anulacion`,
            type: "POST",
            data: { data_fac },
            success: function (res) {
                btn_procesando_tabla(`btn_anular`, obj_inicial, 1);
                if (res.status == -1) {
                    alertify.error(res.msg);
                } else {
                    alertify.success(res.msg);
                    location.reload();
                }
            }
        });
    })
}
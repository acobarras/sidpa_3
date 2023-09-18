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
                    $('#boton_anular').html(`<button class="btn btn-danger btn-lg boton-x btn_anular" type="submit" data_fact='${JSON.stringify(res)}'>Anular</button>`)
                    enviar_anulacion();
                    var table = $("#tab_informe_facturas").DataTable({
                        "data": res,
                        "columns": [
                            {
                                "data": "nombre_empresa", render: function (data, type, row) {
                                    return row.datos_fact[0].nombre_empresa;
                                }
                            },
                            { "data": "num_factura" },
                            { "data": "num_lista_empaque" },
                            {
                                "data": "tipo_documento", render: function (data, type, row) {
                                    return row.datos_fact[0].tipo_documento;
                                }
                            },
                            {
                                "data": "fecha_factura", render: function (data, type, row) {
                                    return row.datos_fact[0].fecha_factura;
                                }
                            },
                            {
                                "data": "cantidad_factura", render: function (data, type, row) {
                                    return row.datos_fact[0].cantidad_factura;
                                }
                            },
                            {
                                "data": "Cant_solicitada", render: function (data, type, row) {
                                    return row.datos_fact[0].Cant_solicitada;
                                }
                            },
                            {
                                "data": "num_pedido", render: function (data, type, row) {
                                    return row.datos_fact[0].num_pedido;
                                }
                            },
                            {
                                "data": "item", render: function (data, type, row) {
                                    return row.datos_fact[0].item;
                                }
                            },
                            {
                                "data": "codigo", render: function (data, type, row) {
                                    return row.datos_fact[0].codigo;
                                }
                            },
                            {
                                "data": "descripcion_productos", render: function (data, type, row) {
                                    return row.datos_fact[0].descripcion_productos;
                                }
                            },
                        ],
                    });
                }
            });
        }
    });
}

var enviar_anulacion = function () {
    $('.btn_anular').on('click', function () {
        var data_fac = JSON.parse($(this).attr('data_fact'));
        console.log(data_fac);
        $.ajax({
            url: `${PATH_NAME}/envia_anulacion`,
            type: "POST",
            data: {data_fac},
            success: function (res) {
                console.log(res);
            }
        });
    })
}
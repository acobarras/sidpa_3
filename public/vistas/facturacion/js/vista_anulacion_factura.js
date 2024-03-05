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
                    if (res.status == -1) {
                        alertify.error(res.msg);
                    } else {
                        $('#boton_anular').html(`
                        <button class="btn btn-danger btn-lg boton-x btn_anular" data_id="1" data_nombre="btn_anular" id="btn_anular" type="submit" data_fact='${JSON.stringify(res)}'>Anular</button>
                        <button class="btn btn-success btn-lg boton-x btn_anular" data_id="2" data_nombre="btn_remplazar" id="btn_remplazar" type="submit" data_fact='${JSON.stringify(res)}'>Remplazar</button>
                        `)
                        reemplazar_factura();
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
                    }
                }
            });
        }
    });
}

var reemplazar_factura = function () {
    $('.btn_anular').on('click', function () {
        var data_fac = JSON.parse($(this).attr('data_fact'));
        var boton = JSON.parse($(this).attr('data_id'));
        var nombre_boton = $(this).attr('data_nombre');
        if (boton == 1) {
            var texto_alert = 'Anular';
            $('#btn_remplazar').addClass('d-none');
        } else {
            var texto_alert = 'Remplazar';
            $('#btn_anular').addClass('d-none');
        }
        alertify.confirm('Alerta Factura', 'Esta seguro que desea ' + texto_alert + ' esta factura?.',
            function () {
                var obj_inicial = $(`#${nombre_boton}`).html();
                btn_procesando_tabla(`${nombre_boton}`);
                $.ajax({
                    url: `${PATH_NAME}/envia_anulacion`,
                    type: "POST",
                    data: { data_fac, boton },
                    success: function (res) {
                        btn_procesando_tabla(`${nombre_boton}`, obj_inicial, 1);
                        if (res.status == -1) {
                            alertify.error(res.msg);
                        } else {
                            alertify.success(res.msg);
                            location.reload();
                        }
                    }
                });
            },
            function () {
                alertify.error('Operación cancelada');
            });
    })
}
$(document).ready(function () {
    consultar_pedidos_alista();
    generar_doc();
});

var ejecutar = false;

var consultar_pedidos_alista = function () {
    var table = $('#tb_alista_cargue').DataTable({
        "ajax": {
            "url": `${PATH_NAME}/consultar_pedidos_alista`,
            "type": "GET",
        },
        "columns": [
            { "data": "nombre_empresa" },
            {
                "data": "forma_pago", render: function (data, type, row) {
                    if (row.forma_pago == 4) {
                        return 'CREDITO';
                    } if (row.forma_pago == 0) {
                        return 'NINGUNO';
                    }
                    else {
                        return 'CONTADO';
                    }
                }
            },
            { "data": "num_pedido" },
            { "data": "orden_compra" },
            { "data": "fecha_compromiso" },
            {
                "data": "recibe", render: (data, type, row) => {
                    let array = [];
                    if (row['difer_mas'] == 1) {
                        array.push('<b> + </b> ');
                    }
                    if (row['difer_menos'] == 1) {
                        array.push('<b> - </b> ');
                    }
                    if (row['difer_ext'] == 1) {
                        array.push('<b> Ext </b>');
                    }
                    return row['porcentaje'] + array.join(' ');
                }
            },
            { "data": "direccion" },
            { "data": "nombre_ruta" },
            {
                "data": "cantidad_items", render: function (data, type, row) {
                    return row.cantidad_items + ' de ' + row.cantidad_items;
                }
            },
            {
                "orderable": false,
                "defaultContent": `<div class="select_acob text-center">
                                 <input type="checkbox" class="agrupar_items">
                              </div>`
            },
        ]
    });
    if (!ejecutar) {
        items_certificado();
    }
}

var datos = [];

var items_certificado = function () {
    ejecutar = true;
    $('#tb_alista_cargue tbody').on("click", "input.agrupar_items", function () {
        var data = $("#tb_alista_cargue").DataTable().row($(this).parents("tr")).data();
        if ($(this).prop('checked') == true) {
            if (datos.length === 0) {
                datos.push(data);
            } else {
                var agrega = false;
                datos.forEach(element => {
                    if (element.num_pedido != data.num_pedido && agrega == false) {
                        datos.push(data);
                        agrega = true;
                    }
                });
            }
        } else {
            for (var i = 0; i < datos.length; i++) {
                if (datos[i].num_pedido === data.num_pedido) {
                    datos.splice(i, 1);
                }
            }
        }
    });
}

var generar_doc = function () {
    $('#doc_pedido').on('click', function () {
        var obj_inicial = $('#doc_pedido').html();
        if (datos == '' || datos.length == 0) {
            alertify.error('Por favor seleccione un pedido para continuar');
            btn_procesando('doc_pedido', obj_inicial, 1);
            return;
        }
        btn_procesando('doc_pedido');
        $.ajax({
            url: `${PATH_NAME}/doc_alistamiento_cargue`,
            type: 'POST',
            data: { datos },

            success: function (res) {
                $('#div_tabla_excel').removeClass('d-none');
                $('.agrupar_items').prop('checked', false);
                var tabla = $(`#tabla_descarga_excel`).DataTable({
                    "data": res,
                    "dom": 'Bflrtip',
                    "buttons": [{
                        extend: 'excelHtml5',
                        text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                        tittleAttr: ' Exportar a exel',
                        className: 'btn btn-success descargar_excel',
                    }],
                    "columns": [
                        { data: "nombre_empresa" },
                        { data: "num_pedido" },
                        { data: "item" },
                        { data: "codigo" },
                        { data: "descripcion_productos" },
                        { data: "ubicacion_material" },
                        { data: "cant_solicitada" },
                        {
                            "data": "cantidad_pendiente", render: function (data, type, row) {
                                if (row.cantidad_pendiente == 0) {
                                    return row.cant_solicitada;
                                }
                                else {
                                    return row.cantidad_pendiente;
                                }
                            }
                        },
                        { data: "nombre_ruta" },
                        { data: "modulo" },
                    ],
                });
                $('.descargar_excel').click();
                $('#div_tabla_excel').addClass('d-none');
                datos = [];
                btn_procesando('doc_pedido', obj_inicial, 1);
            }
        });
    })
}

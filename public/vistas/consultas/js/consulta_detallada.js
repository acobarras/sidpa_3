$(document).ready(function () {
    consulta_detallada_fecha();
    consulta_detallada_codigo();
    consulta_detallada_pedido();
    consulta_detallada_cliente();
    select_2();
});

var carga_tabla = function (form, url) {
    $(`#${form}`).on('submit', function (e) {
        e.preventDefault();
        var form = $(`#${form}`).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            if (url == '/consultas/consulta_detallada_cliente') {
                var form = $("#num_cliente").val();
            } else {
                var form = $(this).serialize();
            }
            var table = $('#tb_detallada').DataTable({
                "ajax": {
                    "url": `${PATH_NAME}${url}`,
                    "type": "POST",
                    "data": { form }
                },
                "dom": 'Bflrtip',
                "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                    tittleAttr: ' Exportar a exel',
                    className: 'btn btn-success',
                }],
                "columns": [
                    {
                        "data": "fecha_cre", render: function (data, type, row) {
                            return `${row.fecha_crea_p} ${row.hora_crea}`;
                        }
                    },
                    { "data": "fecha_compromiso" },
                    {
                        "data": "fecha_cre", render: function (data, type, row) {
                            return `${row.fecha_crea_p} ${row.hora_crea}`;
                        }
                    },
                    {
                        "data": "pedido_item", render: function (data, type, row) {
                            return `${row.num_pedido}-${row.item}`;
                        }
                    },
                    {
                        "data": "recibe", render: function (data, type, row) {
                            var respu = 'Mas o menos';
                            if (row.difer_ext != null) {
                                respu = 'Exacta';
                            } if (row.difer_mas != null && row.difer_menos == null) {
                                respu = 'Mas';
                            } if (row.difer_menos != null && row.difer_mas == null) {
                                respu = 'Menos';
                            }
                            return respu;
                        }
                    },
                    { "data": "Cant_solicitada", render: $.fn.dataTable.render.number(',') },
                    {
                        "data": "material_respu", render: function (data, type, row) {
                            if (row.material_solicitado == '') {
                                var material_respu = row.material;
                            } else {
                                var material_respu = row.material_solicitado;
                            }
                            return material_respu;
                        }
                    },
                    { "data": "n_produccion" },
                    {
                        "data": "ancho_respu", render: function (data, type, row) {
                            if (row.ancho_confirmado == 0) {
                                var ancho_respu = row.ancho_op;
                            } else {
                                var ancho_respu = row.ancho_confirmado;
                            }
                            return ancho_respu;
                        }
                    },
                    { "data": "cant_op", render: $.fn.dataTable.render.number(',', '.', 0, '') },
                    { "data": "m2", render: $.fn.dataTable.render.number(',', '.', 2, '') },
                    {
                        "data": "ml", render: function (data, type, row) {
                            var ancho_respu = row.ancho_op;
                            if (row.ancho_confirmado != 0) {
                                ancho_respu = row.ancho_confirmado;
                            }
                            var ml = 0;
                            if (row.n_produccion != 0) {
                                ml = row.m2/(ancho_respu/1000);
                            }
                            return $.fn.dataTable.render.number(',', '.', 2, '').display(ml);
                        }
                    },
                    {
                        "data": "fecha_provee", render: function (data, type, row) {
                            if (row.fecha_proveedor == '0000-00-00') {
                                var fecha_prove = '';
                            } else {
                                var fecha_prove = row.fecha_proveedor;
                            }
                            return fecha_prove;
                        }
                    },
                    { "data": "codigo" },
                    { "data": "nombre_core" },
                    { "data": "cant_x", render: $.fn.dataTable.render.number('.') },
                    {
                        "data": "ubi_troquel", render: function (data, type, row) {
                            return `${row.ficha_tecnica} - ${row.ubi_troquel}`;
                        }
                    },
                    {
                        "data": "descrip", render: function (data, type, row) {
                            return `${row.nombre_articulo} ${row.descripcion_productos}`;
                        }
                    },
                    { "data": "orden_compra" },
                    { "data": "nombre_empresa" },
                    {
                        "data": "asesor", render: function (data, type, row) {
                            return `${row.nombres} ${row.apellidos}`;
                        }
                    },
                    { "data": "forma_pago" },
                    { "data": "total" },
                    { "data": "nombre_estado_item" },
                    { "data": "numero_factura" },
                    { "data": "estado_entrega" },
                ]

            });
        }
    });
}
var consulta_detallada_fecha = function () {
    carga_tabla('form_detallada_fecha', '/consultas/consulta_detallada_fecha');
};
var consulta_detallada_codigo = function () {
    carga_tabla('form_codigo_producto', '/consultas/consulta_detallada_codigo');
}
var consulta_detallada_pedido = function () {
    carga_tabla('form_numero_pedido', '/consultas/consulta_detallada_numero_pedido');
}
var consulta_detallada_cliente = function () {
    carga_tabla('form_cliente', '/consultas/consulta_detallada_cliente');
}
$(document).ready(function () {
    select_2();
    valida_tipo_articulo();
    listar_inventario();
});

var valida_tipo_articulo = function () {
    $('#id_clase_articulo').on('change', function () {
        var id_tipo_articulo = $(this).val();
        var data = JSON.parse($('#data_tipo_articulo').val());
        var select = '<option value="-1" selected>Todo</option>';
        data.forEach(element => {
            if (element.id_clase_articulo == id_tipo_articulo) {
                select += `<option value="${element.id_tipo_articulo}"> ${element.nombre_articulo}</option> `;
            }
        });
        $(`#id_tipo_articulo`).empty().html(select);
    });
}

var listar_inventario = function () {
    $('#form_costo_inv').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida_form = validar_formulario(form);
        if (valida_form) {
            form = $(this).serialize();
            var table1 = $('#tabla_inv_costo').DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/gerencia/consultar_inventarios_gerencia`,
                    "type": "POST",
                    "data": { form }
                },
                dom: 'Bflrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                    tittleAttr: ' Exportar a exel',
                    className: 'btn btn-success',
                }],
                "columns": [
                    { "data": "codigo_producto" },
                    { "data": "descripcion_productos" },
                    // {"data": "entrada", render: $.fn.dataTable.render.number(',', '.', 2, '')},
                    // {"data": "salida", render: $.fn.dataTable.render.number(',', '.', 2, '')},
                    { "data": "cantidad_inventario", render: $.fn.dataTable.render.number(',', '.', 2, '') },
                    { "data": "costo", render: $.fn.dataTable.render.number(',', '.', 2, '') },
                    {
                        "data": "total", render: function (data, type, row) {
                            if (row.id_clase_articulo == 2) {
                                return 'N/A';
                            } else {
                                return $.fn.dataTable.render.number(',', '.', 2, '$ ').display(row.total);
                            }
                        }
                    }
                ],
            });
        }
    });
}
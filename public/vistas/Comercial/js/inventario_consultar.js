/* global idioma */

$(document).ready(function () {
    listar_inventario_etiquetas();
    listar_inventario_tecnologia();
});

var listar_inventario_etiquetas = function () {
    $('#btn_consultar_inventario_etiqueta').on('click', function () {
        $('#dt_consulta_inventario_etiqueta').DataTable().destroy();
        $('#dt_consulta_inventario_etiqueta').empty().html();

        var codigo_etiqueta = $('#codigo_etiqueta').val();

        var table = $('#dt_consulta_inventario_etiqueta').DataTable({
            "ajax": `${PATH_NAME}/comercial/consulta_inventario_comercial?codigo=` + codigo_etiqueta + '&clase_articulo=' + 2,
            "columnDefs": [
                {"title": "Código", "targets": 0},
                {"title": "Producto", "targets": 1},
                {"title": "Descripción", "targets": 2},
                {"title": "Cantidad", "targets": 3}
            ],
            "columns": [
                {"data": "codigo_producto"},
                {"data": "nombre_articulo"},
                {"data": "descripcion_productos"},
                {"data": "cantidad", render: $.fn.dataTable.render.number('.', ',', 2)}
            ],
        });
    });
};
//---------------------------------------------------------------------------------------------------------------------------------------------
var listar_inventario_tecnologia = function () {
    $('#btn_consultar_inventario_tec').on('click', function () {

        $('#dt_consulta_inventario_tec').DataTable().destroy();
        $('#dt_consulta_inventario_tec').empty().html();

        var codigo_tec = $('#codigo_tecnologia').val();

        var table = $('#dt_consulta_inventario_tec').DataTable({
            "ajax": `${PATH_NAME}/comercial/consulta_inventario_comercial?codigo=` + codigo_tec + '&clase_articulo=' + 3,
            "columnDefs": [
                {"title": "Código", "targets": 0},
                {"title": "Producto", "targets": 1},
                {"title": "Descripción", "targets": 2},
                {"title": "Cantidad", "targets": 3},
                {"title": "Precio Alto Volumen", "targets": 4},
                {"title": "Precio Bajo Volumen", "targets": 5}
            ],
            "columns": [
                {"data": "codigo_producto"},
                {"data": "nombre_articulo"},
                {"data": "descripcion_productos"},
                {"data": "cantidad", render: $.fn.dataTable.render.number('.', ',', 2)},
                {"data": "precio1", render: $.fn.dataTable.render.number('.', ',', 2)},
                {"data": "precio2", render: $.fn.dataTable.render.number('.', ',', 2)}
            ],
        });
    });
};

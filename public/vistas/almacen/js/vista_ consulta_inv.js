$(document).ready(function () {
    consulta_inv_etiquetas();
    consulta_inv_tecnologia();
    consulta_inv_bobinas();
    consulta_inv_ubicacion();
});
var consulta_inv_etiquetas = function () {
    $('#form_consulta_inventario_etiqueta').on('submit', function (e) {
        e.preventDefault();
        /*destruir anterior consulta*/
        $('#dt_consulta_inventario_etiqueta').DataTable().destroy();
        $('#dt_consulta_inventario_etiqueta').empty().html();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            form = $(this).serialize();
            var table = $("#dt_consulta_inventario_etiqueta").DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/almacen/consulta_producto_inventario`,
                    "type": 'POST',
                    "data": { form },
                },
                "columnDefs": [
                    { "title": "Código", "targets": 0 },
                    { "title": "Producto", "targets": 1 },
                    { "title": "Descripción", "targets": 2 },
                    { "title": "Cantidad", "targets": 3 },
                    { "title": "Opciones", "targets": 4 }
                ],
                "columns": [
                    { "data": "codigo_producto" },
                    { "data": "nombre_articulo" },
                    { "data": "descripcion_productos" },
                    { "data": "cantidad", render: $.fn.dataTable.render.number('.', ',', 2) },
                    {
                        "orderable": false,
                        "defaultContent": "<center>\n\
                                <button type='button' class='btn btn-info btn-sm btn-circle ver_etiqueta' \n\
                                            data-bs-toggle='modal' data-bs-target='#info_item_etiqueta'> <span class='fa fa-search'></span>\n\
                                </button>\n\
                                <center>"
                    }
                ],
            });
            analisis_producto_etiqueta('#dt_consulta_inventario_etiqueta tbody', table);
        }
    });
}
/**
 * 
 * Funcion para visualizar la informacion si es etiquetas
 */
var analisis_producto_etiqueta = function (tbody, table) {
    $(tbody).on('click', 'button.ver_etiqueta', function () {
        $('#dt_infor_producto_etiqueta').DataTable().destroy();
        var data = table.row($(this).parents("tr")).data();
        /*agregar informacion en el modal*/
        $('#cantidad_producto_etiqueta').empty().html(parseFloat(data.cantidad).toLocaleString(undefined, { minimumFractionDigits: 2 }));
        $('#nombre_producto_etiqueta').empty().html(data.descripcion_productos);
        consultar_seguimiento(data, '#dt_infor_producto_etiqueta');
    });
};
// --------------------------------------------------------------FIN CONSULTA INVENTARIO ETIQUETAS---------------------------------------------------------------------------------------------------------


/**
 * 
 * Funcion para cargar la tabla de /tecnologia/
 */
var consulta_inv_tecnologia = function () {
    $('#form_consulta_inventario_tec').on('submit', function (e) {
        e.preventDefault();

        /*destruir anterior consulta*/
        $('#dt_consulta_inventario_tec').DataTable().destroy();
        $('#dt_consulta_inventario_tec').empty().html();


        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            form = $(this).serialize();
            var table = $("#dt_consulta_inventario_tec").DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/almacen/consulta_producto_inventario`,
                    "type": 'POST',
                    "data": { form },
                },
                "columnDefs": [
                    { "title": "Código", "targets": 0 },
                    { "title": "Producto", "targets": 1 },
                    { "title": "Descripción", "targets": 2 },
                    { "title": "Cantidad", "targets": 3 },
                    { "title": "Opciones", "targets": 4 }
                ],
                "columns": [
                    { "data": "codigo_producto" },
                    { "data": "nombre_articulo" },
                    { "data": "descripcion_productos" },
                    { "data": "cantidad", render: $.fn.dataTable.render.number('.', ',', 2) },
                    {
                        "orderable": false,
                        "defaultContent": "<center>\n\
                                <button type='button' class='btn btn-info btn-sm btn-circle ver_tecnologia' \n\
                                            data-bs-toggle='modal' data-bs-target='#info_item_tec'> <span class='fa fa-search'></span>\n\
                                </button>\n\
                                <center>"
                    }
                ],
            });
        }
        analisis_producto_tec('#dt_consulta_inventario_tec tbody', table);
    });
};
/**
 * 
 * Funcion para visualizar la informacion si es tecnologia
 */
var analisis_producto_tec = function (tbody, table) {
    $(tbody).on('click', 'button.ver_tecnologia', function () {
        var data = table.row($(this).parents("tr")).data();
        $('#dt_infor_producto_tec').DataTable().destroy();
        $('#cantidad_producto_tec').empty().html(parseFloat(data.cantidad).toLocaleString(undefined, { minimumFractionDigits: 2 }));
        $('#nombre_producto_tec').empty().html(data.descripcion_productos);
        consultar_seguimiento(data, '#dt_infor_producto_tec');
    });
};

// --------------------------------------------------------------FIN CONSULTA INVENTARIO TECNOLOGIA---------------------------------------------------------------------------------------------------------


/**
 * 
 * Funcion para cargar la tabla de /bobinas/
 */
var consulta_inv_bobinas = function () {
    $('#form_consulta_inventario_bob').on('submit', function (e) {
        e.preventDefault();

        /*destruir anterior consulta*/
        $('#dt_consulta_inventario_bob').DataTable().destroy();
        $('#dt_consulta_inventario_bob').empty().html();


        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            form = $(this).serialize();
            var table = $("#dt_consulta_inventario_bob").DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/almacen/consulta_producto_inventario`,
                    "type": 'POST',
                    "data": { form },
                },
                "columnDefs": [
                    { "title": "Código", "targets": 0 },
                    { "title": "Producto", "targets": 1 },
                    { "title": "Descripción", "targets": 2 },
                    { "title": "Cantidad", "targets": 3 },
                    { "title": "Opciones", "targets": 4 }
                ],
                "columns": [
                    { "data": "codigo_producto" },
                    { "data": "nombre_articulo" },
                    { "data": "descripcion_productos" },
                    { "data": "cantidad", render: $.fn.dataTable.render.number('.', ',', 2) },
                    {
                        "orderable": false,
                        "defaultContent": "<center>\n\
                                <button type='button' class='btn btn-info btn-sm btn-circle ver_bobinas' \n\
                                            data-bs-toggle='modal' data-bs-target='#info_item_bob'> <span class='fa fa-search'></span>\n\
                                </button>\n\
                                <center>"
                    }
                ],
            });
        }
        analisis_producto_bob('#dt_consulta_inventario_bob tbody', table);
    });
};
/**
 * 
 * Funcion para visualizar la informacion si es tecnologia
 */
var analisis_producto_bob = function (tbody, table) {
    $(tbody).on('click', 'button.ver_bobinas', function () {
        var data = table.row($(this).parents("tr")).data();
        $('#dt_infor_producto_bob').DataTable().destroy();
        $('#cantidad_producto_bob').empty().html(parseFloat(data.cantidad).toLocaleString(undefined, { minimumFractionDigits: 2 }));
        $('#nombre_producto_bob').empty().html(data.descripcion_productos);
        $('#dt_infor_producto_bob').DataTable({
            "ajax": {
                "url": `${PATH_NAME}/almacen/consultar_inv_bobinas`,
                "type": "POST",
                "data": { id: data.id_productos },
            },
            "columns": [
                { "data": "ancho" },
                { "data": "ML", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                {
                    "data": "entrada", render: function (data, type, row) {
                        var entrada = '<i class="fa fa-caret-up text-success"></i> ' + row['entrada'];
                        return entrada;
                    }
                },
                {
                    "data": "salida", render: function (data, type, row) {
                        var salida = '<i class="fa fa-caret-down text-danger"></i> ' + row['salida'];
                        return salida;
                    }
                },
                {
                    "data": "total", render: function (data, type, row) {
                        var total_item = row['entrada'] - row['salida'];
                        var total = '<i class="fa fa-equals text-info"></i> ' + $.fn.dataTable.render.number('.', ',', 2, '').display(total_item);
                        return total;
                    }
                },
            ]
        });
    });
};
// --------------------------------------------------------------FIN CONSULTA INVENTARIO BOBINAS---------------------------------------------------------------------------------------------------------
/**
 * 
 * Funcion para cargar la tabla de /ubicacion/
 */
var consulta_inv_ubicacion = function () {
    $('#form_consulta_inventario_ubi').on('submit', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            form = $(this).serialize();
            var table = $("#dt_consulta_inventario_ubi").DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/almacen/consulta_producto_ubicacion`,
                    "type": 'POST',
                    "data": { form },
                },
                "columns": [
                    { "data": "ubicacion" },
                    { "data": "codigo_producto" },
                    { "data": "descripcion_productos" },
                    { "data": "cantidad", render: $.fn.dataTable.render.number('.', ',', 2) },
                ],
            });
        }
    });
};
// --------------------------------------------------------------FIN CONSULTA INVENTARIO BOBINAS---------------------------------------------------------------------------------------------------------
/**
 * 
 * Funcion para consultar los seguimientos de cada producto item
 */
var consultar_seguimiento = function (data, tabla) {

    var tabla_ubi_item = $(tabla).DataTable({
        "ajax": {
            "url": `${PATH_NAME}/almacen/consultar_seguimiento`,
            "type": "POST",
            "data": { id: data.id_productos },
        },
        "columns": [
            { "data": "ubicacion" },
            {
                "data": "entrada",
                "render": function (data, type, row) {
                    var entrada = '<i class="fa fa-caret-up text-success"></i> ' + row['entrada'];
                    return entrada;
                }
            },
            {
                "data": "salida",
                "render": function (data, type, row) {
                    var salida = '<i class="fa fa-caret-down text-danger"></i> ' + row['salida'];
                    return salida;
                }
            },
            {
                "data": "total",
                "render": function (data, type, row) {
                    var total = '<i class="fa fa-equals text-info"></i> ' + row['total'];
                    return total;
                }
            },
        ]
    });
};
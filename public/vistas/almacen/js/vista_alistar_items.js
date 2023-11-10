$(document).ready(function () {
    click();
    $('#ali_completo-tab').click();
    envio_cantidad_reproceso();
    envia();
    tablas();
});

var click = function () {
    $('#ali_completo-tab').on('click', function () {
        alistamiento_items_completos();
    });
    $('#ali_incompleto-tab').on('click', function () {
        alistamiento_items_incompletos();
    });
    $('#ali_bobinas-tab').on('click', function () {
        alistamiento_items_bobina();
    });
}

var alistamiento_items_completos = function () {
    var table = $('#dt_alistamiento_bod_completo').DataTable({
        ajax: `${PATH_NAME}/almacen/consultar_items_completos?data=2,4`,
        columns: [
            { "data": "fecha_compromiso" },
            { "data": "nombre_empresa" },
            {
                "data": "datos_item", render: (data, type, row) => {
                    return row['codigo_producto'];
                }
            },
            { "data": "salida", render: $.fn.dataTable.render.number('.', ',', 0, '') },
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
                    if (row['datos_item'][0]['grupo'] == 'TECNOLOGIA') {
                        array = [];
                        row['porcentaje'] = '';
                    }
                    return row['porcentaje'] + array.join(' ');
                }
            },
            {
                "data": "descripcion_productos", render: (data, type, row) => {
                    return `${row['nombre_articulo']} ${row['descripcion_productos']}`;
                }
            },
            {
                "data": "n_produccion", render: (data, type, row) => {
                    if (row['datos_item'][0]['n_produccion'] == 0) {
                        return `Terceros`;
                    }
                    return `<b>${row['datos_item'][0]['n_produccion']}</b>`;
                }
            },
            {
                "data": "pedido-item", render: (data, type, row) => {
                    return row['documento'];
                }
            },
            {
                "data": "nombre_core", render: (data, type, row) => {
                    return row['datos_item'][0]['nombre_core'];
                }
            },
            {
                "data": "cant_x", render: (data, type, row) => {
                    var cantidad_x = row['datos_item'][0]['cant_x'];
                    return $.fn.dataTable.render.number('.', ',', 0, '').display(cantidad_x);
                }
            },
            { "data": "ruta" },
            { "data": "nombre_estado_item" },
            {
                "data": "opciones", render: (data, type, row) => {
                    if (row.estado_inv == 4 || row.estado_inv == 5) {
                        boton_alistar = '';
                        boton_reproceso = `<button class="btn btn-danger btn-sm reportar_cant_reproceso" title="Reprocesando" id="reportar_cant_reproceso${row['id_ingresotec']}" data-bs-toggle="modal" data-bs-target="#Modal_CANT_REPROC"><i class="fas fa-sync"></i></button>`;
                    } else {
                        boton_alistar = `<button class="btn btn-success btn-sm report_cant_factu" title="Alistamiento completo" id="report_cant_factu${row['id_ingresotec']}" data-tabla="#dt_alistamiento_bod_completo" data_alistamiento="2"><i class="fa fa-check"></i></button>`;
                        boton_reproceso = `<button class="btn btn-warning btn-sm item_en_reproceso" title="Enviar a Reproceso" id="item_en_reproceso${row['id_ingresotec']}"><i class="fas fa-sync"></i></button>`;
                    }
                    return `<div>\n\
                                <button class="btn btn-primary btn-sm logistica_checked" title="Ubicacion Items" id="id_logistica_checked${row['id_ingresotec']}"><i class="fas fa-search"></i></button>\n\
                                ${boton_alistar}
                                ${boton_reproceso}
                            </div>`;
                    // <button class="btn btn-info btn-sm alistaminento_ver" data-toggle="modal" data-target="#Modalobs"><i class="fa fa-search"></i></button>      \n\
                }
            }
        ],
    });
    logistica_checked_completo('#dt_alistamiento_bod_completo tbody', table);
    // envio_completo('#dt_alistamiento_bod_completo tbody', table, '#dt_alistamiento_bod_completo', 2);// el segundo parametro es el tipo de alistamiento en este caso 2 "alistamiento completo".
    enviar_reproceso('#dt_alistamiento_bod_completo tbody', table, '#dt_alistamiento_bod_completo', 4);// el tercer parametro es para poder recargar la tabla despues de la accion y el cuarto parametro es el tipo de alistamiento 4 "reproceso completo"
    reportar_cant_reproceso('#dt_alistamiento_bod_completo tbody', table);
}

var detailProd = [];

var tabla_ver_producto = function (data) {
    var respu = /*html*/ `
    <div class="container">
        <br>
        <table class="table-bordered table table-responsive-lg table-responsive-md " cellspacing="0" width="100%" id="tb_comple${data.id_ingresotec}">
            <thead class="thead-dark">
                <tr>
                    <th>Ubicaciones</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
        </table>
        <br><br><br>
    </div>`;
    return respu;
}
var logistica_checked_completo = function (tbody, table) {
    $(tbody).on('click', 'tr button.logistica_checked', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var idx = $.inArray(tr.attr('id'), detailProd);
        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();

            // Eliminar de la matriz 'abierta'
            detailProd.splice(idx, 1);
        } else {
            var data = table.row($(this).parents("tr")).data();
            tr.addClass('details');
            row.child(tabla_ver_producto(data)).show();
            var documento = data.documento;
            var tabla_ubi_item = $(`#tb_comple${data.id_ingresotec}`).DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/almacen/consulta_ubicaciones_item`,
                    "type": "POST",
                    "data": { documento },
                },
                "columns": [
                    { "data": "ubicacion" },
                    {
                        "data": "salida", render: function (data, type, row) {
                            var input = row.salida;
                            if (row.salida != '0') {
                                input = `<input type="text" id="pendiente" class="form-control " style='border:none;'  value="${row.salida}">`;
                            }
                            return input;
                        }
                    },
                    // { "data": "salida" },
                ]
            });
            if (idx === -1) {
                detailProd.push(tr.attr('id'));
            }
        }
    });
}
var envio_completo = function (tbody, table, reload, alistamiento) {
    $(tbody).on('click', 'tr button.report_cant_factu', function () {
        //envia(data, alistamiento, reload);

    });
}

var tablas = function () {
    $('#dt_alistamiento_bod_completo, #dt_alistamiento_bod_incompleto').on('click', 'tr button.report_cant_factu', function () {
        $('#ubicacion').modal('show');
        var tablaDT = $(this).data('tabla');
        var alistamiento = $(this).attr('data_alistamiento');
        var data = $(`${tablaDT}`).DataTable().row($(this).parents("tr")).data();
        data.alistamiento = alistamiento;// llega como atributo
        data.tabla_dt = tablaDT;
        $('#modal_ubica').attr('data-item', JSON.stringify(data));
    })
}

var envia = function () {
    $('#modal_ubica').on('click', function (e) {
        e.preventDefault();
        var data = $(this).data('item');
        var ubicacion = $('#ubicacion_materialmodal').val();
        var obj_inicial = $(`#modal_ubica`).html();
        data.ubicacion_material = ubicacion;
        btn_procesando_tabla(`modal_ubica`);
        $.ajax({
            url: `${PATH_NAME}/almacen/crea_entrega_logistica`,
            type: "POST",
            data: data,
            success: function (res) {
                $(`${data.tabla_dt}`).DataTable().ajax.reload(function () {
                    btn_procesando_tabla(`modal_ubica`, obj_inicial, 1);
                    alertify.success(`${res} CORRECTAMENTE.`);
                    $('#ubicacion').modal('hide')
                });
            }
        });
    })
}
var enviar_reproceso = function (tbody, table, reload, alistamiento) {
    $(tbody).on('click', 'tr button.item_en_reproceso', function () {
        var data = table.row($(this).parents("tr")).data();
        var obj_inicial = $(`#item_en_reproceso${data['id_ingresotec']}`).html();
        btn_procesando_tabla(`item_en_reproceso${data['id_ingresotec']}`);
        data.alistamiento = alistamiento;
        $.ajax({
            url: `${PATH_NAME}/almacen/creacion_reproceso_logistica`,
            type: "POST",
            data: data,
            success: function (res) {
                $(reload).DataTable().ajax.reload(function () {
                    btn_procesando_tabla(`item_en_reproceso${data['id_ingresotec']}`, obj_inicial, 1);
                    alertify.success(`${res.observacion} CORRECTAMENTE.`);
                    $("div.div_impresion").empty().html(res.etiqueta);
                    $("div.div_impresion").printArea();
                    $("div.div_impresion").addClass('d-none');
                });
            }
        });
    });
}
var reportar_cant_reproceso = function (tbody, table) {
    $(tbody).on('click', 'tr button.reportar_cant_reproceso', function () {
        var data = table.row($(this).parents("tr")).data();
        $("#bt_reprocesar_item").attr('data', JSON.stringify(data))
    });

}
var envio_cantidad_reproceso = function () {
    $(`#bt_reprocesar_item`).on('click', function () {
        var data = JSON.parse($(this).attr('data'));
        var cantidad = $("#cantidad").val();
        var ubicacion_material = $("#ubicacion_material").val();
        if (cantidad == 0 || cantidad == '') {
            alertify.error('La cantidad no puede ser 0 o vacia.');
        } else {
            var obj_inicial = $(`#bt_reprocesar_item`).html();
            btn_procesando(`bt_reprocesar_item`);
            if (parseInt(cantidad) > Math.round(data.salida)) {
                alertify.error('La cantidad reportada es mayor a la cantidad solicitada.');
                btn_procesando(`bt_reprocesar_item`, obj_inicial, 1);
            } else {
                data.salida = cantidad;
                data.ubicacion_material = ubicacion_material;
                $.ajax({
                    url: `${PATH_NAME}/almacen/reportar_cant_reproceso`,
                    type: "POST",
                    data: data,
                    success: function (res) {
                        if (res.state == 1) {
                            $('#Modal_CANT_REPROC').modal('hide');
                            $('#form_reproceso')[0].reset();
                            if (res.estado_inv == 4) {
                                $("#dt_alistamiento_bod_completo").DataTable().ajax.reload();
                            } else {
                                $("#dt_alistamiento_bod_incompleto").DataTable().ajax.reload();
                            }
                            btn_procesando(`bt_reprocesar_item`, obj_inicial, 1);
                            alertify.success(`${res.observacion} CORRECTAMENTE.`);
                        } else {
                            alertify.error('Error lo sentimos algo a ocurrido comuniquese con los desarrolladores');
                            btn_procesando(`bt_reprocesar_item`, obj_inicial, 1);

                        }
                    }
                });
            }
        }
    });
}


// -------------------------------------------------- INICIO DE ALISTAMIENTO CON ITEMS INCOMPLETOS-----------------------------------------------------------------------
var alistamiento_items_incompletos = function () {
    var table1 = $('#dt_alistamiento_bod_incompleto').DataTable({
        ajax: `${PATH_NAME}/almacen/consultar_items_completos?data=3,5`,
        columns: [
            { "data": "fecha_compromiso" },
            { "data": "nombre_empresa" },
            {
                "data": "datos_item", render: (data, type, row) => {
                    return row['codigo_producto'];
                }
            },
            { "data": "salida", render: $.fn.dataTable.render.number('.', ',', 0, '') },
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
                    if (row['datos_item'][0]['grupo'] == 'TECNOLOGIA') {
                        array = [];
                        row['porcentaje'] = '';
                    }
                    return row['porcentaje'] + array.join(' ');
                }
            },
            {
                "data": "descripcion_productos", render: (data, type, row) => {
                    return `${row['nombre_articulo']} ${row['descripcion_productos']}`;
                }
            },
            {
                "data": "n_produccion", render: (data, type, row) => {
                    if (row['datos_item'][0]['n_produccion'] == 0) {
                        return `Terceros`;
                    }
                    return `<b>${row['datos_item'][0]['n_produccion']}</b>`;
                }
            },
            {
                "data": "pedido-item", render: (data, type, row) => {
                    return row['documento'];
                }
            },
            {
                "data": "nombre_core", render: (data, type, row) => {
                    return row['datos_item'][0]['nombre_core'];
                }
            },
            {
                "data": "cant_x", render: (data, type, row) => {
                    var cantidad_x = row['datos_item'][0]['cant_x'];
                    return $.fn.dataTable.render.number('.', ',', 0, '').display(cantidad_x);
                }
            },
            { "data": "ruta" },
            { "data": "nombre_estado_item" },
            {
                "data": "opciones", render: (data, type, row) => {
                    if (row.estado_inv == 4 || row.estado_inv == 5) {
                        boton_alistar = '';
                        boton_reproceso = `<button class="btn btn-danger btn-sm reportar_cant_reproceso" title="Reprocesando" id="reportar_cant_reproceso${row['id_ingresotec']}" data-bs-toggle="modal" data-bs-target="#Modal_CANT_REPROC"><i class="fas fa-sync"></i></button>`;
                    } else {
                        boton_alistar = `<button class="btn btn-success btn-sm report_cant_factu" title="Alistamiento completo" id="report_cant_factu${row['id_ingresotec']}" data-tabla="#dt_alistamiento_bod_incompleto" data_alistamiento="3"><i class="fa fa-check"></i></button>`;
                        boton_reproceso = `<button class="btn btn-warning btn-sm item_en_reproceso" title="Enviar a Reproceso" id="item_en_reproceso${row['id_ingresotec']}"><i class="fas fa-sync"></i></button>`;

                    }
                    return `<div>
                                <button class="btn btn-primary btn-sm logistica_checked" title="Ubicacion Items" id="id_logistica_checked${row['id_ingresotec']}"><i class="fas fa-search"></i></button>\n\
                                ${boton_alistar}
                                ${boton_reproceso}
                            </div>`;
                }
            }
        ],
    });
    logistica_checked_completo('#dt_alistamiento_bod_incompleto', table1);
    // envio_completo('#dt_alistamiento_bod_incompleto tbody', table1, '#dt_alistamiento_bod_incompleto', 3);// el segundo parametro es el tipo de alistamiento en este caso 2 "alistamiento completo".
    enviar_reproceso('#dt_alistamiento_bod_incompleto tbody', table1, '#dt_alistamiento_bod_incompleto', 5);// el tercer parametro es para poder recargar la tabla despues de la accion y el cuarto parametro es el tipo de alistamiento 5 "reproceso incompleto"
    reportar_cant_reproceso('#dt_alistamiento_bod_incompleto tbody', table1);
}
//------------------------------------------------------ INICIO DE SALIDA DE INVENTARIO Y ALISTAMIENTO BOBINAS------------------------------------------------------------->

var alistamiento_items_bobina = function () {
    var table = $('#dt_alistamiento_bod_bobinas').DataTable({
        ajax: `${PATH_NAME}/almacen/consultar_items_bobina?data=1`,
        columns: [
            { "data": "fecha_compromiso" },
            { "data": "nombre_empresa" },
            { "data": "codigo" },
            { "data": "Cant_solicitada", render: $.fn.dataTable.render.number('.', ',', 0, '') },
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
                    if (row['grupo'] == 'TECNOLOGIA') {
                        array = [];
                        row['porcentaje'] = '';
                    }
                    return row['porcentaje'] + array.join(' ');
                }
            },
            {
                "data": "descripcion_productos", render: (data, type, row) => {
                    return `${row['grupo']} ${row['descripcion_productos']}`;
                }
            },
            {
                "data": "n_produccion", render: (data, type, row) => {
                    if (row['n_produccion'] == 0) {
                        return `Terceros`;
                    }
                    return `<b>${row['n_produccion']}</b>`;
                }
            },
            {
                "data": "pedido-item", render: (data, type, row) => {
                    return `${row['num_pedido']}-${row['item']}`;
                }
            },
            { "data": "ruta" },
            { "data": "nombre_estado_item" },
            {
                "data": "opciones", render: (data, type, row) => {
                    return `<div>
                                <button class="btn btn-primary btn-sm consulta_inventario" title="Consulta Inventario"><i class="fas fa-search"></i></button>\n\
                                </div>`;
                    // <button class="btn btn-success btn-sm envio_compras" title="Enviar a compras"><i class="fas fa-cart-plus"></i></button>\n\
                }
            }
        ],
    });
    consulta_inventario('#dt_alistamiento_bod_bobinas tbody', table);
    enviar_compras('#dt_alistamiento_bod_bobinas tbody', table);
}

// //------------------------------------------------------ boton amarillo con lupa busqueda de producto por ancho------------------------------------------------------------->


var detailProd_bobi = [];

var tabla_ver_ubicacion = function (data) {
    var respu = /*html*/ `
    <div class="container-fluid">
        <br>
        <div class="row">
            <div class="col-2">
            <label class="fw-bolder">Observaciones del Pedido:<label>
            </div>
            <div class="col-2">
                <p class="text-uppercase text-decoration-underline">${data.observaciones}</p>
            </div>
            <div class="col-2">
            <label class="fw-bolder">Codigo:<label>
            </div>
            <div class="col-2">
                <p class="text-uppercase text-decoration-underline">${data.codigo}</p>
            </div>
        <br>
        <table class="table-bordered table table-responsive-lg table-responsive-md " cellspacing="0" width="100%" id="tb_${data.id_pedido_item}">
            <thead class="thead-dark">
                <tr>
                    <th>Ancho</th>
                    <th>Metros<sup>2</sup></th>
                    <th>Metros Lineales</th>
                    <th style="width:20px"></th>
                </tr>
            </thead>
        </table>
        <br><br><br>
    </div>`;
    return respu;
}
var consulta_inventario = function (tbody, table) {
    $(tbody).on('click', 'tr button.consulta_inventario', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var idx = $.inArray(tr.attr('id'), detailProd_bobi);
        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();

            // Eliminar de la matriz 'abierta'
            detailProd_bobi.splice(idx, 1);
        } else {
            var data = table.row($(this).parents("tr")).data();
            tr.addClass('details');
            row.child(tabla_ver_ubicacion(data)).show();
            var id_producto = data.id_producto;
            var id_pedido_item = data.id_pedido_item;
            var tabla_ubi_item_bobi = $(`#tb_${id_pedido_item}`).DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/almacen/consultar_inventario_items_bobina`,
                    "type": "POST",
                    "data": { id_producto },
                },
                "columns": [
                    { "data": "ancho" },
                    { "data": "M2", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                    { "data": "ML", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                    {
                        "data": "opciones", render: (data, type, row) => {
                            return `<div>
                                        <button class="btn btn-warning btn-sm buscar_ubi" title="consulta ubicación Bobina"><i class="fas fa-search"></i></button>\n\
                                    </div>`;
                        }
                    }
                ]
            });
            consulta_ubicacion_inventario(`#tb_${data.id_pedido_item}`, tabla_ubi_item_bobi, data);
            if (idx === -1) {
                detailProd_bobi.push(tr.attr('id'));
            }
        }
    });
}

// //------------------------------------------------------ boton fuera de tabla "alistar"------------------------------------------------------------->

var detailProd_ubi_bobi = [];

var tabla_ver_producto_ubi = function (data) {
    var respu = /*html*/ `
    <div class="container-fluid">
        <br>
        <table class="table-bordered table table-responsive-lg table-responsive-md " cellspacing="0" width="100%" id="tb_ubi${data.id_pedido_item}">
            <thead class="thead-dark">
                <tr>
                    <th>ubicación</th>
                    <th>Ancho</th>
                    <th>Metros<sup>2</sup></th>
                    <th>Metros Lineales</th>
                    <th style="width:20%">Elija Ubicación</th>
                    <th style="width:20%">Cantidad en Metros Lineales</th>
                </tr>
            </thead>
        </table>
        <br>
        <div>
            <center>
                <button class="btn btn-success btn-sm alistar" title="Alista Bobina">Alistar <i class="fas fa-check"></i></button>\n\
            </center>
         </div>
        <br><br><br>
    </div>`;
    return respu;
}

var consulta_ubicacion_inventario = function (tbody, table, data_princi) {
    $(tbody).on('click', 'tr button.buscar_ubi', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var idx = $.inArray(tr.attr('id'), detailProd_ubi_bobi);
        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();

            // Eliminar de la matriz 'abierta'
            detailProd_ubi_bobi.splice(idx, 1);
        } else {
            var data = table.row($(this).parents("tr")).data();
            tr.addClass('details');
            row.child(tabla_ver_producto_ubi(data)).show();
            var id_producto = data.id_producto;
            var ancho = data.ancho;
            var id_pedido_item = data.id_pedido_item;
            var tabla_ubi_item_bobi = $(`#tb_ubi${id_pedido_item}`).DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/almacen/consulta_ubicacion_inventario`,
                    "type": "POST",
                    "data": { id_producto, ancho },
                },
                "columns": [
                    { "data": "ubicacion" },
                    { "data": "ancho" },
                    { "data": "M2", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                    { "data": "ML", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                    {
                        "data": "checkbox", render: (data, type, row) => {
                            return `<div class="select_acob text-center">
                            <input type="checkbox" name="ubicacion${row.id_ingresotec}" data-radio="${row.id_ingresotec}" value="${row.id_ingresotec}"/>
                        </div>`;
                        }
                    },
                    {
                        "data": "input", render: (data, type, row) => {
                            return `<div>
                                        <input class="form-control cantidades" type="text" id="cantidad${row.id_ingresotec}">\n\
                                    </div>`;
                        }
                    },
                ]
            });
            alista_bobina(`#tb_ubi${data.id_pedido_item}`, tabla_ubi_item_bobi, data_princi);
            if (idx === -1) {
                detailProd_ubi_bobi.push(tr.attr('id'));
            }
        }
    });
}
//* funcion que valida y envia el inventario y alista el item

var alista_bobina = function (tbody, table, data_prici) {
    $(".alistar").on('click', function () {
        // Traigo la data de la table creada
        var data = table.rows().data();
        // Recorro la tabla validando que se diligencie en su totalidad
        var dato_tabla = table.rows().nodes();
        var arrayubicacion = [];
        var arraycantidad = [];
        var cantidad = 0;
        var mensaje = '';
        $.each(dato_tabla, function (index, value) {
            var p = $(this).find('input').val();
            var estado_radio = RadioElegido(`ubicacion${p}`);
            if (estado_radio == 'ninguno') {
                cantidad = $(`#cantidad${p}`).val('');
            } else {
                cantidad = $(`#cantidad${p}`).val();
                var dato_ml = Math.round((data[index].ML));
                if (cantidad != '' || cantidad != 0) {
                    if (cantidad > dato_ml) {
                        mensaje = ("La cantidad no puede superar la de la ubicación.");
                        $(`#cantidad${p}`).val('');
                        $(`#cantidad${p}`).focus();
                        return;
                    } else {
                        arrayubicacion = {
                            'ubicacion': data[index].ubicacion,
                            'cantidad': cantidad,
                            'documento': `${data_prici.num_pedido}-${data_prici.item}`,
                            'codigo': data_prici.codigo,
                            'id_producto': data_prici.id_producto,
                            'num_pedido': data_prici.num_pedido,
                            'item': data_prici.item,
                            'ancho': data[index].ancho,
                            'id_pedido_item': data_prici.id_pedido_item,
                        };
                        arraycantidad.push(arrayubicacion);
                    }
                } else {
                    mensaje = "Porfavor Diligencie correctamente.";
                    $(`#cantidad${p}`).focus();
                    return;
                }
            }
        });


        if (mensaje == '' && arraycantidad == '') {
            mensaje = 'Se debe elegir ubicación para descontar.';
            alertify.error(mensaje);
            return;
        }
        if (mensaje != '') {
            alertify.error(mensaje);
            return;
        }

        var cantidad_total = 0
        arraycantidad.forEach(element => {
            cantidad_total += parseInt(element.cantidad);
        });
        if (cantidad_total < data_prici.Cant_solicitada) {
            alertify.error("No puede alistar menos a lo solicitado.");
        } else {
            $.ajax({
                url: `${PATH_NAME}/almacen/descuento_inv_bob`,
                type: "POST",
                data: { data: arraycantidad },
                success: function (res) {
                    if (res.status == 1) {
                        $(`#dt_alistamiento_bod_bobinas`).DataTable().ajax.reload(function () {
                            alertify.success(res.msg);
                        });
                    } else {
                        alertify.error(res.msg);
                    }
                }
            });
        }
    });
}
//------------------------------------------------------ boton verde ------------------------------------------------------------->
var enviar_compras = function (tbody, table) {
    $(tbody).on('click', 'tr button.envio_compras', function () {
        var data = table.row($(this).parents("tr")).data();
        $.ajax({
            url: `${PATH_NAME}/almacen/envia_bob_compras_terceros`,
            type: "POST",
            data: data,
            success: function (res) {
                if (res.status == 1) {
                    $(`#dt_alistamiento_bod_bobinas`).DataTable().ajax.reload(function () {
                        alertify.success(res.msg);
                    });
                } else {
                    alertify.error(res.msg);
                }
            }
        });
    });
};



//------------------------------------------------------ FIN DE SALIDA DE INVENTARIO Y ALISTAMIENTO BOBINAS------------------------------------------------------------->

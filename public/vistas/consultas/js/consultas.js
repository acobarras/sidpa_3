$(document).ready(function () {
    consulta_pedido_item();
    consulta_n_produccion();
    consulta_fecha();
    consulta_op_num_pedido();
    consulta_pedidos_fecha();
    select_2();
    consulta_pqr();
    consulta_diag();
    consulta_prioridad();
    CKEDITOR.replace('observacion', { toolbar: 'mybar' });
    consulta_ubi_pedido();
});
// ---------------------------------------------------------INICIO CONSULTAS POR NUMERO DE PEDIDO-------------------------------------------------------------------------------

var consulta_pedido_item = function () {
    $("#form_pedidos_item").on("submit", function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var num_pedido = $("#num_pedido").val();
        var valida = validar_formulario(form);
        if (valida) {
            $('#tb_pedidos_item').DataTable().clear();
            var table = $('#tb_pedidos_item').DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/consultas/consulta_pedido_item`,
                    "type": "POST",
                    "data": { num_pedido }
                },
                "dom": 'Bflrtip',
                "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                    tittleAttr: ' Exportar a exel',
                    className: 'btn btn-success',
                }],
                "columns": [
                    { data: "fecha_crea" },
                    { data: "num_pedido" },
                    { data: "item" },
                    { data: "codigo" },
                    { data: "descripcion_productos" },
                    { data: "Cant_solicitada" },
                    { data: "nombre_estado_item" },
                    {
                        "orderable": false,
                        render: function (data, type, row) {
                            return `<center>
                            <button class="btn btn-primary btn-sm seguimiento" type="button" title="Ver detalle">
                            <i class="fas fa-search-plus"></i>
                            </button>
                            </center>`;
                        }
                    },
                ]
            });
            ver_detalle_pedidos_item('#tb_pedidos_item tbody', table);
        }
    });
}

function tabla_detalle_consul_ped_item(data) {
    var respu = /*html*/ `
    <br>
        <div class="container-fluid recuadro">
            <br>
            <center>
                <h3>Registro Pedido Item</h3>
            </center>
            <table class="table-bordered table table-responsive-lg table-responsive-md " cellspacing="0" width="100%" id="dt_ver_seguimiento${data}">
                <thead class="thead-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Persona</th>
                        <th>Area</th>
                        <th>Actividad</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <br><br><br> 
        </div>   
    </div>`;
    return respu;
}
var detailConsul = [];


var ver_detalle_pedidos_item = function (tbody, table) {
    /* ver productos  al hacer click en el boton azul*/
    $(tbody).on('click', 'tr button.seguimiento', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var idx = $.inArray(tr.attr('id'), detailConsul);
        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();
            // Eliminar de la matriz 'abierta'
            detailConsul.splice(idx, 1);
        } else {
            var data = $('#tb_pedidos_item').DataTable().row($(this).parents("tr")).data();
            tr.addClass('details');
            row.child(tabla_detalle_consul_ped_item(data.item)).show();
            $(`#dt_ver_seguimiento${data.item}`).DataTable({
                ajax: {
                    'url': `${PATH_NAME}/consultas/consulta_detalle_pedido_item`,
                    "type": "POST",
                    "data": data,
                },
                "columns": [
                    { data: "fecha_crea" },
                    { data: "hora_crea" },
                    { data: "nombres" },
                    { data: "nombre_area_trabajo" },
                    { data: "nombre_actividad_area" },
                    { data: "observacion" }
                ],
            });
            if (idx === -1) {
                detailConsul.push(tr.attr('id'));
            }
            /*cargar funciones*/

        }
    });
    // On each draw, loop over the `detailRows` array and show any child rows
    table.on('draw', function () {
        $.each(detailConsul, function (i, id) {
            $('#' + id + ' td.details-control').trigger('click');
        });
    });
}
// ---------------------------------------------------------INICIO CONSULTAS POR ORDEN DE PRODUCCION--------------------------------------------------------------------------------
var consulta_n_produccion = function () {
    $("#form_n_produccion").on("submit", function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var n_produccion = $("#n_produccion").val();
        var valida = validar_formulario(form);
        if (valida) {
            var table = $('#tb_n_produccion').DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/consultas/consulta_op`,
                    "type": "POST",
                    "data": { n_produccion }
                },
                "dom": 'Bflrtip',
                "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                    tittleAttr: ' Exportar a exel',
                    className: 'btn btn-success',
                }],
                "columns": [
                    { data: "fecha_crea" },
                    { data: "hora_crea" },
                    { data: "nombres" },
                    { data: "nombre_area_trabajo" },
                    { data: "nombre_actividad_area" },
                    { data: "nombre_maquina" },
                    { data: "observacion_op" }
                ]
            });
        }
    });
}
// ---------------------------------------------------------INICIO CONSULTAS POR FECHA------------------------------------------------------------------------------------------
var consulta_fecha = function () {
    $("#form_fecha").on("submit", function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var form1 = $(this).serialize();
        var actividades = $('#actividad').val();
        var valida = validar_formulario(form);
        if (valida) {
            var table = $('#tb_fecha').DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/consultas/consulta_fecha`,
                    "type": "POST",
                    "data": { form1, actividades }
                },
                "dom": 'Bflrtip',
                "buttons": [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                    tittleAttr: ' Exportar a exel',
                    className: 'btn btn-success',
                }],
                "columns": [
                    { data: "fecha_crea" },
                    { data: "hora_crea" },
                    { data: "num_produccion" },
                    { data: "nombres" },
                    { data: "nombre_actividad_area" },
                    { data: "observacion_op" }
                ]
            });
        }
    });
}
// ---------------------------------------------------------INICIO CONSULTAS PEDIDO ITEM ORDEN DE PRODUCCION---------------------------------------------------------------------
var consulta_op_num_pedido = function () {
    $("#form_op_pedido").on("submit", function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            form = $('#n_produccion_pedido').val();
            var table = $('#tb_num_pedido_op').DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/consulta/numero_pedido_op`,
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
                    { data: "fecha_crea_p" },
                    { data: "fecha_compromiso" },
                    {
                        data: "pedido", render: function (data, type, row) {
                            return `${row.num_pedido}-${row.item}`;
                        }
                    },
                    { data: "nombre_empresa" },
                    { data: "orden_compra" },
                    { data: "n_produccion" },
                    { data: "codigo" },
                    {
                        data: "descrip", render: function (data, type, row) {
                            return `${row.nombre_articulo} ${row.descripcion_productos}`;
                        }
                    },
                    { data: "Cant_solicitada" },
                    { data: "nombre_core" },
                    { data: "cant_x" },
                    { data: "nombre_estado_item" }
                ]
            });
        }
    });
}
// ---------------------------------------------------------INICIO CONSULTAS MOVIMIENTOS FECHA---------------------------------------------------------------------
var consulta_pedidos_fecha = function () {
    $("#form_movimientos").on("submit", function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            var form = $(this).serialize();
            var table = $('#tb_movimientos').DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/consulta/trasavilidad_pedido`,
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
                    { data: "fecha_crea" },
                    { data: "hora_crea" },
                    {
                        data: "operario", render: function (data, type, row) {
                            return `${row.nombres} ${row.apellidos}`;
                        }
                    },
                    {
                        data: "pedido", render: function (data, type, row) {
                            var q = row.item.length;
                            var item = row.item;
                            if (q == 1) {
                                var item = `0${row.item}`;
                            }
                            return `${row.pedido} ${item}`;
                        }
                    },
                    { data: "nombre_area_trabajo" },
                    { data: "nombre_actividad_area" },
                    { data: "observacion" },
                ]
            });
        }
    });
}

var consulta_pqr = function () {
    $("#form_pqr").on("submit", function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            form = $('#num_pqr').val();
            $.ajax({
                "url": `${PATH_NAME}/consulta/numero_pqr`,
                "type": "POST",
                "data": { form },
                success: function (res) {
                    if (res['data'] == '') {
                        alertify.error('no hay datos de este numero de reclamación');
                        $('#info_extra').css('display', 'none');
                        var table = $('#tabla_pqr').DataTable({
                            "data": res['data'],
                            "dom": 'Bflrtip',
                            "buttons": [{
                                extend: 'excelHtml5',
                                text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                                tittleAttr: ' Exportar a exel',
                                className: 'btn btn-success',
                            }],
                            "columns": [
                                { data: "fecha_segui" },
                                { data: "hora_segui" },
                                {
                                    data: "usuario", render: function (data, type, row) {
                                        return `${row.nombres} ${row.apellidos}`;
                                    }
                                },
                                { data: "nombre_actividad_area" },
                                { data: "num_pedido_cambio" }
                            ]
                        });
                        return;
                    } else {
                        cargar_info_pqr(res['info'][0], form);
                        var table = $('#tabla_pqr').DataTable({
                            "data": res['data'],
                            "dom": 'Bflrtip',
                            "buttons": [{
                                extend: 'excelHtml5',
                                text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                                tittleAttr: ' Exportar a exel',
                                className: 'btn btn-success',
                            }],
                            "columns": [
                                { data: "fecha_segui" },
                                { data: "hora_segui" },
                                {
                                    data: "usuario", render: function (data, type, row) {
                                        return `${row.nombres} ${row.apellidos}`;
                                    }
                                },
                                { data: "nombre_actividad_area" },
                                { data: "num_pedido_cambio" }
                            ]
                        });
                    }
                }
            });
        }
    });

}

var checkear = function (data, id_1, id_2) {
    if (data == 1) {
        $(`#${id_1}`).prop('checked', 'checked');
    } else {
        $(`#${id_2}`).prop('checked', 'checked');
    }
}

var cargar_info_pqr = function (datos, num_pqr) {

    $('#cantidad_reclama').empty().html(`${datos['cantidad_reclama']}`);
    CKEDITOR.instances.observacion.setData(datos['descripcion_pqr']);
    $('#info_extra').css('display', '');
    $('#titulo_pqr').html(`Información PQR N°:${num_pqr}`);
    var data_direccion = datos.datos_direccion[0];
    var data_producto = datos.datos_producto[0];
    var data_item = datos.datos_item[0];
    var num_pedido = data_item['num_pedido'];
    var id_cli_prov = datos['id_cli_prov'];
    checkear(datos['motivo_pqr'], 'motivo_no', 'motivo_si');
    $('#nit').html(`${data_direccion['nit']}`);
    $('#nombre_empresa').html(`${data_direccion['nombre_empresa']}`);
    $.ajax({
        type: "POST",
        url: `${PATH_NAME}/pqr/consultar_direc_pedido`,
        data: { num_pedido, id_cli_prov },
        success: function (res) {
            var datos_direc_pedido = res['direccion_pedido'][0];
            var datos_direc_cliente = res['direcciones_cliente'];
            $('#respu_elegido').css('display', 'none');
            $('#cambio_direccion').css('display', 'none');
            $('#direccion').html(`${datos_direc_pedido['direccion']}`);
            $('#direccion').attr('data', `${JSON.stringify(datos_direc_pedido)}`);
            $('#email').html(`${datos_direc_pedido['email']}`);
            $('#contacto').html(`${datos_direc_pedido['contacto']}`);
            $('#telefono').html(`${datos_direc_pedido['telefono']}`);
            $('#celular').html(`${datos_direc_pedido['celular']}`);
            if (datos['id_dir_pqr'] == datos_direc_pedido['id_direccion']) {
                checkear('2', 'cam_direc_si', 'cam_direc_no');
                var item = `<option>N/A</option>`;
                $('#cambio_direc').empty().html(item);
            } else {
                checkear('1', 'cam_direc_si', 'cam_direc_no');
                $('#respu_elegido').css('display', '');
                $('#cambio_direccion').css('display', '');
                rellena_direcciones(datos_direc_cliente, 'cambio_direc', data_direccion);
            }
        }
    });
    // SE CARGA LA INFORMACION DEL ITEM DEL PEDIDO
    var datos = [];
    datos.push(data_item);
    $('#tabla_item_pedido').DataTable({
        "data": datos,
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false,
        "columns": [
            { "data": "num_pedido" },
            { "data": "codigo" },
            { "data": "descripcion_productos" },
            { "data": "n_produccion" },
            { "data": "ruta_embobinado" },
            { "data": "core" },
            { "data": "cant_x", render: $.fn.dataTable.render.number('.', ',', 0, '', '') },
            { "data": "moneda" },
            { "data": "v_unidad", render: $.fn.dataTable.render.number('.', ',', 2, '$ ', '') },
        ]
    });
    // SE PREGUNTA SI EL PRODUCTO ES DISTINTO
    if (data_item['id_clien_produc'] != datos['id_clien_produc']) {
        checkear('1', 'cam_produc_si', 'cam_produc_no');
        $('#respu_elegido_produc').css('display', '');
        $('#cambio_producto').css('display', '');
        rellena_product(id_cli_prov, data_producto, 'cambio_produc');

    } else {
        checkear('2', 'cam_produc_si', 'cam_produc_no');
        var item = `<option>N/A</option>`;
        $('#cambio_produc').empty().html(item);
        $('#respu_elegido_produc').css('display', 'none');
        $('#cambio_producto').css('display', 'none');
    }

    checkear(datos['cambio_reproceso'], 'cambio_reproceso_si', 'cambio_reproceso_no');
    checkear(datos['recoger_producto'], 'recogida_produc_si', 'recogida_produc_no');
    if (datos['recoger_producto'] == 1) {
        $('#cita_previa').css('display', '');
        checkear(datos['requiere_cita'], 'requiere_cita_si', 'requiere_cita_no');
    }
    $('#email').html(`${data_direccion['email']}`);
}

var rellena_direcciones = function (data, id, data_direccion) {
    var seleccionar = '<option value="0">Seleccione</option>';
    data.forEach(element => {
        if (element.id_direccion == data_direccion['id_direccion']) {
            seleccionar += /*html*/
                `<option class="cambio"  data='${JSON.stringify(element)}' value="${element.id_direccion}" selected>${element.direccion}</option>
                `;
        }
        seleccionar += /*html*/
            `<option class="cambio" data='${JSON.stringify(element)}' value="${element.id_direccion}">${element.direccion}</option>
                `;
    });
    $(`#${id} `).empty().html(seleccionar);
    rellenar_campo_direc(data_direccion);
}

var rellenar_campo_direc = function (data_direccion) {
    $('#direccion_res').empty().html(`${data_direccion['direccion']}`);
    $('#email_res').empty().html(`${data_direccion['email']}`);
    $('#contacto_res').empty().html(`${data_direccion['contacto']}`);
    $('#telefono_res').empty().html(`${data_direccion['telefono']}`);
    $('#celular_res').empty().html(`${data_direccion['celular']}`);
}

var rellena_product = function (id_cliente, data_producto, id) {
    var seleccionar = '<option value="0">Seleccione</option>';
    seleccionar += /*html*/
        `<option class="cambio_produ"  data='${JSON.stringify(data_producto)}' value="${data_producto.id_clien_produc}" selected>${data_producto.codigo_producto}</option>
                        `;
    $(`#${id} `).empty().html(seleccionar);
    tabla_producto_cambio(data_producto);
}

var tabla_producto_cambio = function (data_producto) {
    var datos = [];
    datos.push(data_producto);
    $('#tabla_cambio_item_pedido').DataTable({
        "data": datos,
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false,
        "columns": [
            { "data": "codigo_producto" },
            { "data": "descripcion_productos" },
            { "data": "nombre_r_embobinado" },
            { "data": "nombre_core" },
            { "data": "cantidad_minima", render: $.fn.dataTable.render.number('.', ',', 0, '', '') },
            { "data": "precio_venta", render: $.fn.dataTable.render.number('.', ',', 2, '$ ', '') },
        ]
    });
}

var consulta_diag = function () {
    $("#form_diag").on("submit", function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            form = $('#num_diag').val();
            $.ajax({
                "url": `${PATH_NAME}/consulta/numero_diag`,
                "type": "POST",
                "data": { form },
                success: function (res) {
                    $('#tabla_diag').DataTable({
                        "data": res['data'],
                        "columns": [
                            { "data": "id_diagnostico" },
                            { "data": "fecha_crea" },
                            { "data": "num_consecutivo" },
                            { "data": "item_segui" },
                            { "data": "nombre_empresa" },
                            {
                                data: "equipo", render: function (data, type, row) {
                                    if (row.item = 0) {
                                        return 'No registrado';
                                    } else {

                                        return `${row.equipo} S/N ${row.serial_equipo}`;
                                    }
                                }
                            },
                            { "data": "accesorios" },
                            { "data": "procedimiento" },
                            { "data": "observacion" },
                        ]
                    });
                }
            });
        }
    });
}

var consulta_prioridad = function () {
    $('#form_prioridad').submit(function (e) {
        e.preventDefault();
        var form1 = $(this).serializeArray();
        var valida = validar_formulario(form1);
        if (valida) {
            var form = $(this).serialize();
            $.ajax({
                type: "POST",
                data: { form },
                url: `${PATH_NAME}/consultar_prioridades`,
                success: function (response) {
                    var table = $('#tabla_prioridades').DataTable({
                        data: response,
                        dom: 'Bflrtip',
                        buttons: [{
                            extend: 'excelHtml5',
                            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                            tittleAttr: ' Exportar a exel',
                            className: 'btn btn-success',
                        }],
                        "columns": [
                            { "data": "id_prioridad" },
                            { "data": "prioridad" },
                            { "data": "respuestas" },
                            {
                                "data": "estado", render: function (data, type, row) {
                                    if (row.estado == 1) {
                                        return '<h6 style="color:green">Abierto</h6>';
                                    } else {
                                        return '<h6 style="color:red">Cerrado</h6>';
                                    }
                                }
                            },
                        ],
                    });
                }
            });
        }
    });
}

var consulta_ubi_pedido = function () {
    $('#consulta_ubicacion').on('submit', function (e) {
        e.preventDefault();
        var form1 = $(this).serializeArray();
        var valida = validar_formulario(form1);
        var obj_inicial = $('#ubica_pedido').html();
        if (valida) {
            btn_procesando('ubica_pedido');
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/consulta_pedido_ubica`,
                data: { form1 },
                success: function (res) {
                    if (res.status == -1) {
                        alertify.error(res.msg);
                        btn_procesando('ubica_pedido', obj_inicial, 1);
                        limpiar_formulario('consulta_ubicacion', 'input');
                    } else {
                        limpiar_formulario('consulta_ubicacion', 'input');
                        btn_procesando('ubica_pedido', obj_inicial, 1);
                        var table = $('#tabla_ubicaciones').DataTable({
                            data: res.data,
                            "columns": [
                                { "data": "num_pedido" },
                                { "data": "item" },
                                { "data": "nombre_empresa" },
                                { "data": "orden_compra" },
                                { "data": "codigo" },
                                { "data": "cantidad_factura" },
                                { "data": "nombre_ubicacion" },

                            ],
                        });
                    }
                }
            });
        }
    })
}
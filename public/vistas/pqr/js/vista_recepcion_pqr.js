$(document).ready(function () {
    select_2();
    alertify.set('notifier', 'position', 'bottom-left');
    consultar_pqr();
    ObservacionPQR();
    reporte_recepcion();
    envio_correo();
    cerrar_modal();
    cerrar_modal_observaciones();
    elimina_pqr();
    informacion_extra();
    boton_regresar();
    cambio_select();
    cambio_select_produc();
    cambio_direccion();
    cambio_producto();
    envio_observacion();
    CKEDITOR.replace('observacion', { toolbar: 'mybar' });
    CKEDITOR.replace(`observaciones`, { toolbar: 'mybar' });
    CKEDITOR.replace(`reporte_inicial`, { toolbar: 'mybar' });
});

var consultar_pqr = function (cola = '') {
    $.ajax({
        url: `${PATH_NAME}/pqr/consultar_pqr?consulta=1`,
        type: "GET",
        success: function (res) {
            datos_productos(res);
            if (cola != '') {
                $('#cerrar_modal').click();
            }
        }
    });
}

var datos_productos = function (data) {
    $('#tabla_productos_pqr').DataTable({
        "data": data,
        "columns": [
            { "data": "fecha_crea" },
            { "data": "num_pqr" },
            {
                "data": "cliente", render: function (data, type, row) {
                    return row.datos_producto[0].nombre_empresa;
                }
            },
            {
                "data": "pedido_item", render: function (data, type, row) {
                    return `${row.datos_item[0].num_pedido}-${row.datos_item[0].item}`;
                }
            },
            { "data": "descripcion_pqr" },
            { "data": "cantidad_reclama", render: $.fn.dataTable.render.number('.', ',', 0) },
            {
                "data": "botones", render: function (data, type, row) {
                    var boton = '';
                    if (row.estado == 1) {
                        var boton = `
                        <button type="button" class="btn btn-primary btn-sm reporte_recepcion" data-bs-toggle="modal" data-bs-target="#recibePqr" >
                            <i class="fas fa-search"></i>
                        </button>  
                        <button type="button" class="btn btn-danger btn-sm elimina_pqr" >
                            <i class="fas fa-minus-circle"></i>
                        </button> 
                        `;
                    }
                    return boton;
                },
                "className": "text-center"
            },
            {
                "data": "num_pqr", render: function (data, type, row) {
                    return `
                    <button class="btn btn-success ver_info" data-ver="${row.num_pqr}" title="Ver info" ><i class="fa fa-marker me-1"></i></button>
                    <button type="button" class="btn btn-primary btn-sm codigo_motivo" title="Asignar Codigo Motivo"><i class="fa fa-scroll me-1"></i></button>
                    `;
                },
                "className": "text-center"
            },
        ]
    });
}

CKEDITOR.config.toolbar_mybar = [
    ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'SpellChecker', 'Scayt'],
    ['Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat'],
    ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'],
    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote'],
    ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
    ['TextColor', 'BGColor'],
    ['Styles', 'Format', 'Font', 'FontSize']
];

var informacion_extra = function () {
    $(`#tabla_productos_pqr tbody`).on('click', `tr button.ver_info`, function (e) {
        e.preventDefault();
        var dato = $(this).attr('data-ver');
        var data_row = $('#tabla_productos_pqr').DataTable().row($(this).parents("tr")).data();
        $('#id_num_pqr').val(data_row['id_pqr']);
        var data_direccion = data_row.datos_direccion[0];
        var data_producto = data_row.datos_producto[0];
        var data_item = data_row.datos_item[0];
        var num_pedido = data_item['num_pedido'];
        var id_cli_prov = data_row['id_cli_prov'];
        $('#principal').css('display', 'none');
        $('#info_extra').css('display', '');
        $('#titulo_info').html(`<h1>Información PQR N°:${dato}</h1>`);
        $('#nit').html(`${data_direccion['nit']}`);
        $('#nombre_empresa').html(`${data_direccion['nombre_empresa']}`);
        checkear(data_row['motivo_pqr'], 'motivo_no', 'motivo_si');
        // SE CONSULTA LA DIRECCION DEL PEDIDO Y LAS DEL CLIENTE
        $.ajax({
            type: "POST",
            url: `${PATH_NAME}/pqr/consultar_direc_pedido`,
            data: { id_cli_prov, num_pedido },
            success: function (res) {
                var datos_direc_pedido = res['direccion_pedido'][0];
                var datos_direc_cliente = res['direcciones_cliente'];
                cambiar_checket(datos_direc_cliente, 'cambio_direc', data_direccion);
                cambiar_checket_producto(id_cli_prov, data_producto, 'cambio_produc', data_item);
                $('#respu_elegido').css('display', 'none');
                $('#cambio_direccion').css('display', 'none');
                $('#direccion').html(`${datos_direc_pedido['direccion']}`);
                $('#direccion').attr('data', `${JSON.stringify(datos_direc_pedido)}`);
                $('#email').html(`${datos_direc_pedido['email']}`);
                $('#contacto').html(`${datos_direc_pedido['contacto']}`);
                $('#telefono').html(`${datos_direc_pedido['telefono']}`);
                $('#celular').html(`${datos_direc_pedido['celular']}`);
                if (data_row['id_dir_pqr'] == datos_direc_pedido['id_direccion']) {
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
        if (data_item['id_clien_produc'] != data_row['id_clien_produc']) {
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
        $('#cantidad_reclama').empty().html(`${data_row['cantidad_reclama']}`);
        CKEDITOR.instances.observacion.setData(data_row['descripcion_pqr']);
        
        checkear(data_row['cambio_reproceso'], 'cambio_reproceso_si', 'cambio_reproceso_no');
        checkear(data_row['recoger_producto'], 'recogida_produc_si', 'recogida_produc_no');
        if (data_row['recoger_producto'] == 1) {
            $('#cita_previa').css('display', '');
            checkear(data_row['requiere_cita'], 'requiere_cita_si', 'requiere_cita_no');
        }
        $('#email').html(`${data_direccion['email']}`);
    });
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

var cambio_select = function () {
    $('#cambio_direc').on('change', function (e) {
        e.preventDefault();
        var dato = JSON.parse($('.cambio:selected').attr("data"));
        rellenar_campo_direc(dato);
    });
}
var cambio_select_produc = function () {
    $('#cambio_produc').on('change', function (e) {
        e.preventDefault();
        var dato = JSON.parse($('.cambio_produ:selected').attr("data"));
        tabla_producto_cambio(dato);
    });
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
            { "data": "presentacion", render: $.fn.dataTable.render.number('.', ',', 0, '', '') },
            { "data": "precio_venta", render: $.fn.dataTable.render.number('.', ',', 2, '$ ', '') },
        ]
    });
}

var rellena_product = function (id_cliente, data_producto, id) {
    var seleccionar = '<option value="0">Seleccione</option>';
    $.ajax({
        type: "POST",
        url: `${PATH_NAME}/pqr/consultar_producto_cliente`,
        data: { id_cliente },
        success: function (res) {
            res.forEach(element => {
                if (element.id_clien_produc == data_producto['id_clien_produc']) {
                    seleccionar += /*html*/
                        `<option class="cambio_produ"  data='${JSON.stringify(element)}' value="${element.id_clien_produc}" selected>${element.codigo_producto}-${element.descripcion_productos}</option>
                        `;
                }
                seleccionar += /*html*/
                    `<option class="cambio_produ" data='${JSON.stringify(element)}' value="${element.id_clien_produc}">${element.codigo_producto}-${element.descripcion_productos}</option>
                        `;
            });
            $(`#${id} `).empty().html(seleccionar);
            tabla_producto_cambio(data_producto);
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

var enviar_ajax = function (id_pqr, dato, campo) {
    $.ajax({
        type: "POST",
        url: `${PATH_NAME}/pqr/modificar_pqr`,
        data: { id_pqr, dato, campo },
        success: function (res) {
            if (res.status == 1) {
                alertify.success('se a modificado correctamente la PQR');
            } else {
                alertify.error('algo a sucedido');
            }
        }
    });
}

var cambiar_checket = function (datos_direc_cliente, id, data_direccion) {
    $('.cambio_direc').click(function () {
        var valor = $(this).val();
        var campo = 'direccion';
        var id_pqr = $('#id_num_pqr').val();
        if (valor == 2) {
            dato = JSON.parse($('#direccion').attr("data"));
            $('#respu_elegido').css('display', 'none');
            $('#cambio_direccion').css('display', 'none');
            alertify.confirm(`ALERTA DE COFIRMACIÓN`, `¿Esta seguro que la dirección de la pqr es la misma del pedido?`,
                function () {
                    enviar_ajax(id_pqr, dato, campo);
                },
                function () {
                    alertify.error('Cancelado')
                }
            ).set('labels', { ok: 'Si', cancel: 'No' });
        } else {
            $('#respu_elegido').css('display', '');
            $('#cambio_direccion').css('display', '');
            rellena_direcciones(datos_direc_cliente, 'cambio_direc', data_direccion);
        }
    });
}

var cambio_direccion = function () {
    $('#cambio_direccion').on('change', function () {
        var dato = '';
        var valor = $('.cambio_direc').val();
        var campo = 'direccion';
        var id_pqr = $('#id_num_pqr').val();
        if (valor == 1) {
            dato = JSON.parse($('.cambio:selected').attr("data"));
        } else {
            dato = JSON.parse($('#direccion').attr("data"));
        }
        alertify.confirm(`ALERTA DE COFIRMACIÓN`, `¿Esta seguro que desea cambiar la dirección?`,
            function () {
                enviar_ajax(id_pqr, dato, campo);
            },
            function () {
                alertify.error('Cancelado')
            }
        ).set('labels', { ok: 'Si', cancel: 'No' });
    });
}

var cambiar_checket_producto = function (id_cli_prov, data_producto, boton, data_item) {
    $('.cambio_produc').click(function () {
        var valor = $(this).val();
        var campo = 'producto';
        var id_pqr = $('#id_num_pqr').val();
        var dato = data_item;
        var opcion = 2;
        if (valor == 2) {
            $('#respu_elegido_produc').css('display', 'none');
            $('#cambio_producto').css('display', 'none');
            alertify.confirm(`ALERTA DE COFIRMACIÓN`, `¿Esta seguro que el producto de el pqr es la misma del pedido?`,
                function () {
                    enviar_ajax(id_pqr, dato, campo);
                },
                function () {
                    alertify.error('Cancelado')
                }
            ).set('labels', { ok: 'Si', cancel: 'No' });
        } else {
            $('#respu_elegido_produc').css('display', '');
            $('#cambio_producto').css('display', '');
            rellena_product(id_cli_prov, data_producto, 'cambio_produc');
        }
    });
}

var cambio_producto = function () {
    $('#cambio_producto').on('change', function () {
        var dato = JSON.parse($('.cambio_produ:selected').attr("data"));
        var campo = 'producto';
        var id_pqr = $('#id_num_pqr').val();
        var opcion = 1;
        alertify.confirm(`ALERTA DE COFIRMACIÓN`, `¿Esta seguro que desea cambiar el producto de la pqr?`,
            function () {
                enviar_ajax(id_pqr, dato, campo);
            },
            function () {
                alertify.error('Cancelado')
            }
        ).set('labels', { ok: 'Si', cancel: 'No' });
    });
}

var boton_regresar = function () {
    $('#regresar').on('click', function (e) {
        e.preventDefault();
        $('#principal').css('display', '');
        $('#info_extra').css('display', 'none');
        CKEDITOR.instances.observacion.setData('');
    });
}

var elimina_pqr = function () {
    $('#tabla_productos_pqr tbody').on('click', 'button.elimina_pqr', function () {
        var data = $('#tabla_productos_pqr').DataTable().row($(this).parents("tr")).data();
        alertify.confirm('Eliminación PQR', `Esta seguro que desea eliminar la pqr No ${data.num_pqr}`,
            function () {
                $.ajax({
                    type: "POST",
                    url: `${PATH_NAME}/pqr/eliminar`,
                    data: { data },
                    success: function (res) {
                        console.log(res);
                        if (res) {
                            consultar_pqr();
                        }
                    }
                });
            },
            function () { }
        ).setting({
            'labels': { ok: 'Si', cancel: 'No' },
            'invokeOnCloseOff': true,
        });
    });
}

var reporte_recepcion = function () {
    $('#tabla_productos_pqr tbody').on('click', 'button.reporte_recepcion', function () {
        var data = $('#tabla_productos_pqr').DataTable().row($(this).parents("tr")).data();
        CKEDITOR.config.toolbar_Basic = [[]];
        $('#descripcion_pqr').empty().html(data.descripcion_pqr);
        $('#envio_correo').attr('data-pqr', JSON.stringify(data));
    });
}

var envio_correo = function () {
    $('#envio_correo').click(function () {
        var redaccion = CKEDITOR.instances.reporte_inicial.getData();
        var data = JSON.parse($('#envio_correo').attr('data-pqr'));
        if (redaccion == '') {
            alertify.error('El campo Aceptación de la reclamación es requerido');
            $('#reporte_inicial').focus();
            return;
        }
        var obj_inicial = $('#envio_correo').html();
        btn_procesando('envio_correo');
        $.ajax({
            type: "POST",
            url: `${PATH_NAME}/pqr/acepta_pqr`,
            data: { redaccion, data },
            success: function (res) {
                btn_procesando('envio_correo', obj_inicial, 1);
                if (res.state == 1) {
                    consultar_pqr(1);
                }
            }
        });
    });
}

var cerrar_modal = function () {
    $('#cerrar_modal').click(function () {
        CKEDITOR.instances.reporte_inicial.setData('');
    });
}

var cerrar_modal_observaciones = function () {
    $('#cerrar_observaciones').click(function () {
        CKEDITOR.instances.observaciones.setData('');
    });
}

var envio_observacion = function () {
    $('.boton_codigo_motivo').on('click', function (e) {
        e.preventDefault();
        var obj_inicial = $('#boton_observaciones').html();
        btn_procesando('boton_observaciones');
        var observacion = CKEDITOR.instances.observaciones.getData();
        var id_pqr = $('.boton_codigo_motivo').attr('data');
        $.ajax({
            type: "POST",
            url: `${PATH_NAME}/pqr/observaciones_pqr`,
            data: { observacion, id_pqr },
            success: function (res) {
                if (res.status == 1) {
                    btn_procesando('boton_observaciones', obj_inicial, 1);
                    alertify.success('Observacion guardada exitosamente');
                    $('#codigoMotivo').modal('hide');
                    consultar_pqr();
                } else {
                    btn_procesando('boton_observaciones', obj_inicial, 1);
                    alertify.error('Algo a ocurrido');
                }
            }
        });
    })
}
$(document).ready(function () {
    select_2();
    alertify.set('notifier', 'position', 'bottom-left');
    consultar_pedido();
    consultar_formulario();
    cambio_direc();
    cambio_direccion_pqr();
    cambio_produc();
    cambio_producto_pqr();
    cita_previa();
    generar_reclamacion();
    motivo_pqr();
    CKEDITOR.config.toolbar_mybar = [
        ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'SpellChecker', 'Scayt'],
        ['Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat'],
        // ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
        // '/',
        ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'],
        ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote'],
        ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
        ['TextColor', 'BGColor'],
        ['Styles', 'Format', 'Font', 'FontSize']
    ];
    CKEDITOR.replace('observacion', { toolbar: 'mybar' });
});

var consultar_pedido = function () {
    $('.num_pedio').on('change', function () {
        var num_pedido = $(this).val();
        if (num_pedido == '') {
            $('#slect_items').empty().html('');
        } else {
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/produccion/consultar_items_pedido_impresion`,
                data: { num_pedido },
                success: function (response) {
                    var items = '<option value="0"></option>';
                    if (response != -1) {
                        response.forEach(element => {
                            items += `
                            <option value='${JSON.stringify(element)}'>Item ${element.item} Producto ${element.codigo} ${element.descripcion_productos}</option>                   
                            `;
                        });
                        $('#envio').prop('disabled', false);
                    } else {
                        items = `<option value="0"></option>`;
                        $('#envio').prop('disabled', true);
                    }
                    $('#slect_items').empty().html(items);
                }
            });
        }
    });

}

var consultar_formulario = function () {
    $('#envio').on('click', function () {
        var num_pedido = $('#num_pedido').val();
        var slect_items = JSON.parse($('#slect_items').val());
        $.ajax({
            type: "POST",
            url: `${PATH_NAME}/pqr/consulta_datos_pedido`,
            data: { num_pedido },
            success: function (res) {
                var datos_pedido = res.datos[0];
                rellenar_span(datos_pedido);
                rellena_direcciones(res.direcciones);
                rellena_productos(res.productos);
                var datos = [];
                datos.push(slect_items);
                $('#item_pedido').val(JSON.stringify(slect_items));
                var table = $("#tabla_item_pedido").DataTable({
                    "data": datos,
                    "searching": false,
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "columns": [
                        {
                            "data": "num_pedido", render: function (data, type, row) {
                                return `${row.num_pedido}-${row.item}`;
                            }
                        },
                        { "data": "codigo" },
                        { "data": "descripcion_productos" },
                        { "data": "n_produccion" },
                        { "data": "nombre_r_embobinado" },
                        { "data": "nombre_core" },
                        { "data": "cant_x", render: $.fn.dataTable.render.number('.', ',', 0, '', '') },
                        { "data": "moneda" },
                        { "data": "v_unidad", render: $.fn.dataTable.render.number('.', ',', 2, '$ ', '') },
                    ]
                });
                // console.log(slect_items);
            }
        });
    });
}

var rellena_direcciones = function (data) {
    var item = "<option value='0'>Elija Una Dirección</option>";
    data.forEach(element => {
        item += /*html*/
            ` <option value='${JSON.stringify(element)}'>${element.direccion}</option>`;
    });
    $('#cambio_direc').empty().html(item);
}

var cambio_direc = function () {
    $('.cambio_direc').click(function () {
        var valor = $(this).val();
        if (valor == 1) {
            $('#cambio_direccion').css('display', 'block');
            $('#respu_elegido').css('display', 'block');
        } else {
            $('#cambio_direccion').css('display', 'none');
            $('#respu_elegido').css('display', 'none');
            if ($('#cambio_direc').val() != null) {
                $('#cambio_direc').val(0).change();
            }
        }
    });
}

var cambio_direccion_pqr = function () {
    $("#cambio_direc").on('change', function () {
        var elegido = JSON.parse($(this).val());
        if (elegido != '') {
            $('#respu_elegido').css('display', 'block');
            $('#direccion_res').empty().html(elegido.direccion);
            $('#email_res').empty().html(elegido.email);
            $('#contacto_res').empty().html(elegido.contacto);
            $('#telefono_res').empty().html(elegido.telefono);
            $('#celular_res').empty().html(elegido.celular);
        } else {
            $('#respu_elegido').css('display', 'none');
            $('#direccion_res').empty().html('N/A');
            $('#email_res').empty().html('N/A');
            $('#contacto_res').empty().html('N/A');
            $('#telefono_res').empty().html('N/A');
            $('#celular_res').empty().html('N/A');
        }
    });
}

var cambio_produc = function () {
    $('.cambio_produc').click(function () {
        var valor = $(this).val();
        if (valor == 1) {
            $('#cambio_producto').css('display', 'block');
            $('#respu_elegido_produc').css('display', 'block');
        } else {
            $('#cambio_producto').css('display', 'none');
            $('#respu_elegido_produc').css('display', 'none');
            if ($('#cambio_produc').val() != null) {
                $('#cambio_produc').val(0).change();
            }
        }
    });
}
var cita_previa = function () {
    $('.recoger_produc').click(function () {
        var valor = $(this).val();
        if (valor == 1) {
            $('#cita_previa').css('display', 'block');
        } else {
            $('#cita_previa').css('display', 'none');
        }
    });
}

var rellena_productos = function (data) {
    var item = "<option value='0'>Elija Un Producto</option>";
    data.forEach(element => {
        item += /*html*/
            ` <option value='${JSON.stringify(element)}'>${element.codigo_producto} | ${element.nombre_articulo} ${element.descripcion_productos} | ${element.nombre_r_embobinado} | ${element.nombre_core} | ${element.presentacion}</option>`;
    });
    $('#cambio_produc').empty().html(item);
}

var cambio_producto_pqr = function () {
    $("#cambio_produc").on('change', function () {
        var elegido = JSON.parse($(this).val());
        if (elegido != '') {
            var datos = [];
            datos.push(elegido);
            var table = $("#tabla_cambio_item_pedido").DataTable({
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
        } else {
            $("#tabla_cambio_item_pedido").DataTable().clear().draw();
        }
    });
}

var generar_reclamacion = function () {
    $('#generar_reclamacion').click(function () {
        var form = $('#form_grabar_reclamacion').serialize();
        var motivo = RadioElegido('motivo');
        var cam_direc = RadioElegido('cam_direc');
        var cam_produc = RadioElegido('cam_produc');
        var cam_produc = RadioElegido('requiere_cita');
        var exception = [];
        if (motivo == 1) {
            if (cam_direc == 2) {
                exception.push('cambio_direc');
            }
            if (cam_produc == 2) {
                exception.push('cambio_produc')
            }
        } else {
            exception = ['cantidad_reclama', 'cambio_direc', 'cambio_produc', 'requiere_cita'];
        }
        var validar = validar_formulario(form, exception);
        if (validar) {
            form = $('#form_grabar_reclamacion').serialize();
            var observacion = CKEDITOR.instances.observacion.getData();
            if (observacion == '') {
                alertify.error('El campo Descripción detallada de la reclamación y observaciones es requerido');
                $('#observacion').focus();
                return;
            }
            var obj_inicial = $('#generar_reclamacion').html();
            btn_procesando('generar_reclamacion');
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/pqr/grabar_reclamacion`,
                data: { form, observacion },
                success: function (res) {
                    if (res.status == 1) {
                        // console.log(res);
                        // return;
                        $('#num_pedido').val('').change();
                        $('#slect_items').val(0).change();
                        $('#motivo_no').click();
                        $('#cam_direc_no').click();
                        $('#cam_produc_no').click();
                        $('#nota_contable_no').click();
                        $('#cambio_reproceso_si').click();
                        $('#recogida_produc_no').click();
                        $('#cam_produc_no').click();
                        $('#nit').empty().html('N/A');
                        $('#nombre_empresa').empty().html('N/A');
                        $('#direccion').empty().html('N/A');
                        $('#email').empty().html('N/A');
                        $('#contacto').empty().html('N/A');
                        $('#telefono').empty().html('N/A');
                        $('#celular').empty().html('N/A');
                        $('#cantidad_reclama').val('');
                        // $('#observacion').val('');
                        CKEDITOR.instances.observacion.setData('');
                        $("#tabla_item_pedido").DataTable().clear().draw();
                        alertify.alert('Respuesta PQR', res.msg).set({
                            'label': 'Cerrar',
                            'transitionOff': true
                        });
                        // alertify.success(res.msg);
                    } else {
                        alertify.error(res.msg);
                    }
                    btn_procesando('generar_reclamacion', obj_inicial, 1);
                }
            });
        }
    });
}

var motivo_pqr = function () {
    $('.motivo_vs').click(function () {
        var motivo = $(this).val();
        if (motivo == 1) {
            $('#cam_direc_si').attr('disabled', false);
            $('#cam_direc_no').attr('disabled', false);
            $('#cam_produc_si').attr('disabled', false);
            $('#cam_produc_no').attr('disabled', false);
            $('#cambio_reproceso_si').attr('disabled', false);
            $('#cambio_reproceso_no').attr('disabled', false);
            $('#cambio_reproceso_si').click();
            $('#cantidad_reclama').attr('disabled', false);
            $('#recogida_produc_si').attr('disabled', false);
            $('#recogida_produc_no').attr('disabled', false);
        } else {
            $('#cam_direc_no').click();
            $('#cam_direc_si').attr('disabled', true);
            $('#cam_direc_no').attr('disabled', true);
            $('#cam_produc_no').click();
            $('#cam_produc_si').attr('disabled', true);
            $('#cam_produc_no').attr('disabled', true);
            $('#cambio_reproceso_no').click();
            $('#cambio_reproceso_si').attr('disabled', true);
            $('#cambio_reproceso_no').attr('disabled', true);
            $('#cantidad_reclama').attr('disabled', true);
            $('#recogida_produc_no').click();
            $('#recogida_produc_si').attr('disabled', true);
            $('#recogida_produc_no').attr('disabled', true);
        }
    });
}
$(document).ready(function () {
    select_2();
    alertify.set('notifier', 'position', 'bottom-left');
    consultar_pqr();
    visita_tecnica();
    ObservacionPQR();
    res_visita_tecnica();
    mercancia_bodega();
    analisis_mercancia();
    reimprimir_marcacion();
    documento_envio();
    entrega_pqr();
    informacion_extra();
    boton_regresar();
    CKEDITOR.replace('observacion', { toolbar: 'mybar' });
    CKEDITOR.replace(`observaciones`, { toolbar: 'mybar' });
    cambiar_cantidad_reclamacion();
    envio_observacion();
});

CKEDITOR.config.toolbar_mybar = [
    ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'SpellChecker', 'Scayt'],
    ['Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat'],
    ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'],
    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote'],
    ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
    ['TextColor', 'BGColor'],
    ['Styles', 'Format', 'Font', 'FontSize']
];

var consultar_pqr = function () {
    $.ajax({
        url: `${PATH_NAME}/pqr/consultar_pqr?consulta=2,3,4,5,6,7,8`,
        type: "GET",
        success: function (res) {
            datos_productos(res);
        }
    });
}
var numFormat = $.fn.dataTable.render.number('.', ',', 0, '').display;
var datos_productos = function (data) {
    var tabla = $('#tabla_gestion_pqr').DataTable({
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
                "data": "direccion", render: function (data, type, row) {
                    return `${row.datos_direccion[0].direccion} ${row.datos_direccion[0].contacto} ${row.datos_direccion[0].celular}`;
                }
            },
            {
                "data": "pedido_item", render: function (data, type, row) {
                    return `${row.datos_item[0].num_pedido}-${row.datos_item[0].item}`;
                }
            },
            { "data": "descripcion_pqr" },
            {
                "data": "cantidad_reclama", render: function (data, type, row) {
                    if (row.estado == 6) {
                        input = `<input type="text" id="cantidad${row.id_pqr}" class="form-control cantidad_cambiar"  value="${numFormat(row.cantidad_reclama)}">`;
                    } else {
                        var input = numFormat(row.cantidad_reclama);
                    }
                    return input;
                }
            },
            { "data": "nombre_estado_pqr" },
            {
                "data": "botones", render: function (data, type, row) {
                    var boton = valida_botones(row);
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
                }
            },
        ]
    });
    // cambiar_cantidad_reclamacion("#tabla_gestion_pqr tbody", tabla);
}
var cambiar_cantidad_reclamacion = function () {
    $('#tabla_gestion_pqr tbody').on("blur", "tr input.cantidad_cambiar", function () {
        var data = $('#tabla_gestion_pqr').DataTable().row($(this).parents("tr")).data();
        var nueva_cant = $(`#cantidad${data['id_pqr']}`).val();
        if (nueva_cant == 0) {
            alertify.error('por favor digite una cantidad mayor a 0');
        } else {
            var envio = {
                'cantidad_reclama': nueva_cant,
                'id_pqr': data['id_pqr'],

            }
            alertify.confirm('ALERTA ACOBARRAS', '¿Esta seguro que quiere cambiar la cantidad de la reclamacion?', function () {
                console.log(data);
                $.ajax({
                    "url": `${PATH_NAME}/pqr/cambiar_cantidad_reclamacion`,
                    "type": 'POST',
                    "data": envio,
                    success: function (res) {
                        alertify.success('Se cambio la cantidad de la reclamacion exitosamente');
                        location.reload();
                    }
                });
            }, function () {
                alertify.error('Cancelado');
                $(`#cantidad${data['id_pqr']}`).val(data['cantidad_reclama']);
            })
                .set('labels', { ok: 'Si', cancel: 'No' });
        }
    });
}
var informacion_extra = function () {
    $(`#tabla_gestion_pqr tbody`).on('click', `tr button.ver_info`, function (e) {
        e.preventDefault();
        var dato = $(this).attr('data-ver');
        var data_row = $('#tabla_gestion_pqr').DataTable().row($(this).parents("tr")).data();
        var data_direccion = data_row.datos_direccion[0];
        var data_producto = data_row.datos_producto[0];
        var data_item = data_row.datos_item[0];
        var num_pedido = data_item['num_pedido'];
        var id_cli_prov = data_row['id_cli_prov'];
        $('#principal').css('display', 'none');
        $('#info_extra').css('display', '');
        $('#titulo_info').html(`<h1>Información PQR N°:${dato}</h1>`);
        checkear(data_row['motivo_pqr'], 'motivo_no', 'motivo_si');
        $('#nit').html(`${data_direccion['nit']}`);
        $('#nombre_empresa').html(`${data_direccion['nombre_empresa']}`);
        $.ajax({
            type: "POST",
            url: `${PATH_NAME}/pqr/consultar_direc_pedido`,
            data: { num_pedido, id_cli_prov },
            success: function (res) {
                var datos_direc_pedido = res['direccion_pedido'][0];
                var datos_direc_cliente = res['direcciones_cliente'];
                // cambiar_checket(datos_direc_cliente, 'cambio_direc', data_direccion);
                // cambiar_checket_producto(id_cli_prov, data_producto, 'cambio_produc', data_item);
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
        $('#tabla_info_pedido').DataTable({
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
            { "data": "cantidad_minima", render: $.fn.dataTable.render.number('.', ',', 0, '', '') },
            { "data": "precio_venta", render: $.fn.dataTable.render.number('.', ',', 2, '$ ', '') },
        ]
    });
}

var rellena_product = function (id_cliente, data_producto, id) {
    var seleccionar = '<option value="0">Seleccione</option>';
    seleccionar += /*html*/
        `<option class="cambio_produ"  data='${JSON.stringify(data_producto)}' value="${data_producto.id_clien_produc}" selected>${data_producto.codigo_producto}</option>
                        `;
    $(`#${id} `).empty().html(seleccionar);
    tabla_producto_cambio(data_producto);

}


var checkear = function (data, id_1, id_2) {
    if (data == 1) {
        $(`#${id_1}`).prop('checked', 'checked');
    } else {
        $(`#${id_2}`).prop('checked', 'checked');
    }
}
var boton_regresar = function () {
    $('#regresar').on('click', function (e) {
        e.preventDefault();
        $('#principal').css('display', '');
        $('#info_extra').css('display', 'none');
    });
}

var valida_botones = function (row) {
    var boton = '';
    if (row.estado == 2) {
        var boton = `<button type="button" class="btn btn-primary btn-sm visita_tecnica" title="visita tecnica"><i class="fas fa-search"></i></button> `;
    }
    if (row.estado == 3) {
        var boton = `<button type="button" class="btn btn-danger btn-sm res_visita_tecnica" title="respuesta visita tecnica"><i class="fas fa-minus-circle"></i></button> `;
    }
    if (row.estado == 4 || row.estado == 5) {
        var stilo = 'btn-success';
        var icono = 'fas fa-check';
        if (row.estado == 5) {
            stilo = 'btn-warning';
            icono = 'fas fa-minus-circle';
        }
        var boton = `<button type="button" class="btn ${stilo} btn-sm mercancia_bodega" title="mercancia bodega"><i class="${icono}"></i></button> `;
    }
    if (row.estado == 6) {
        var boton = `<button type="button" class="btn btn-success btn-sm analisis_mercancia" title="analisis mercancia"><i class="fas fa-search"></i></button> `;
    }
    if (row.estado == 7) {
        var boton = `<button type="button" class="btn btn-info btn-sm documento_envio" title="Documento Envio"><i class="far fa-sticky-note"></i></button> `;
    }
    if (row.estado == 8) {
        var boton = `<button type="button" class="btn btn-success btn-sm entrega_pqr" title="Entrega Pqr"><i class="fas fa-truck"></i></button> `;
    }
    return boton;
}

var visita_tecnica = function () {
    $('#tabla_gestion_pqr tbody').on('click', 'button.visita_tecnica', function () {
        var data = $('#tabla_gestion_pqr').DataTable().row($(this).parents("tr")).data();
        alertify.confirm('Visita Tecnica', 'Se requiere realizar una visita técnica para resolver la reclamación.',
            function () {
                envio_visita_tecnica(data, 3, 67);
            },
            function () {
                envio_visita_tecnica(data, 4, 68);
            }
        ).setting({
            'labels': { ok: 'Si Visitar', cancel: 'No Visitar' },
            'invokeOnCloseOff': true,
        });
    });
}


var res_visita_tecnica = function () {
    $('#tabla_gestion_pqr tbody').on('click', 'button.res_visita_tecnica', function () {
        var data = $('#tabla_gestion_pqr').DataTable().row($(this).parents("tr")).data();
        alertify.confirm('Concepto Visita Tecnica', 'Con la visita técnica se pudo solucionar la reclamación.',
            function () {
                envio_visita_tecnica(data, 10, 69);
            },
            function () {
                envio_visita_tecnica(data, 4, 68);
            }
        ).setting({
            'labels': { ok: 'Si', cancel: 'No' },
            'invokeOnCloseOff': true,
        });
    });
}

var mercancia_bodega = function () {
    $('#tabla_gestion_pqr tbody').on('click', 'button.mercancia_bodega', function () {
        var data = $('#tabla_gestion_pqr').DataTable().row($(this).parents("tr")).data();
        alertify.confirm('Validación Mercancia en Bodega', 'La mercancia se encuentra en las instalaciones de Acobarras S.A.S..',
            function () {
                envio_visita_tecnica(data, 6, 70);
            },
            function () {
                envio_visita_tecnica(data, 5, 71);
            }
        ).setting({
            'labels': { ok: 'Si', cancel: 'No' },
            'invokeOnCloseOff': true,
        });
    });
}

var envio_visita_tecnica = function (data, estado, id_actividad_area) {
    $.ajax({
        type: "POST",
        url: `${PATH_NAME}/pqr/cambio_estado`,
        data: { data, estado, id_actividad_area },
        success: function (res) {
            consultar_pqr();
        }
    });
}

var analisis_mercancia = function () {
    $('#tabla_gestion_pqr tbody').on('click', 'button.analisis_mercancia', function () {
        var data = $('#tabla_gestion_pqr').DataTable().row($(this).parents("tr")).data();
        console.log(data);
        window.showAlert = function () {
            alertify.alert('Ejecucion de la Mercancia', 'La mercancia necesita de un reproceso.  <a href="javascript:showReproceso();" class="btn btn-success">Si</a>  <a href="javascript:showConfirm();" class="btn btn-danger">No</a>').set({
                'label': 'Cancelar',
                'transitionOff': true
            });
        }
        window.showConfirm = function () {
            alertify.alert().close();
            alertify.confirm('Ejecucion de la Mercancia', 'La mercancia necesita ser producida de nuevo.',
                function () {
                    envio_analisis_mercancia(data, 1, 8, 72);
                },
                function () {
                    envio_visita_tecnica(data, 10, 69);
                }
            ).setting({
                'labels': { ok: 'Si', cancel: 'No' },
                'invokeOnCloseOff': true,
            });
        }
        window.showReproceso = function () {
            envio_analisis_mercancia(data, 2, 7, 73);
            alertify.alert().close();
        }
        window.showAlert();
    });
}

var envio_analisis_mercancia = function (data, repro_produc, estado, id_actividad_area) {
    $.ajax({
        type: "POST",
        url: `${PATH_NAME}/pqr/analisis_mercancia`,
        data: { data, repro_produc, estado, id_actividad_area },
        success: function (res) {
            if (res.status == 1) {
                consultar_pqr();
                alertify.success(res.msg);
            } else if (res.status == 2) {
                consultar_pqr();
                $('.div_impresion').empty().html(res.data);
                $('#ImpresionItemsModal').modal('toggle');
                var mode = 'iframe'; //popup
                // var close = mode == "popup";
                var options = { mode: mode, popClose: close };
                $("div.div_impresion").printArea(options);
                alertify.success(res.msg);
            } else {
                alertify.error(res.msg);
            }
        }
    });
}

var reimprimir_marcacion = function () {
    $('#reimprimir_marcacion').click(function () {
        var mode = 'iframe'; //popup
        // var close = mode == "popup";
        var options = { mode: mode, popClose: close };
        $("div.div_impresion").printArea(options);
    });
}

var documento_envio = function () {
    $('#tabla_gestion_pqr tbody').on('click', 'button.documento_envio', function () {
        var data = $('#tabla_gestion_pqr').DataTable().row($(this).parents("tr")).data();
        var estado = 8;
        var id_actividad_area = 72;
        $.ajax({
            url: `${PATH_NAME}/pqr/documento_envio`,
            type: "POST",
            data: { data, estado, id_actividad_area },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (res) {
                if (res.size < 20) {
                    alertify.error("Lo sentimos el numero del documento ya fue utilizado intente nuevamente.");
                } else {
                    consultar_pqr();
                    // Descarga el pdf
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(res);
                    a.href = url;
                    // Linea para abrir el documento 
                    window.open(url, '_blank');
                    // Lineas para descargar el documento
                    // a.download = 'lista_empaque_' + num_pedido + '_pedido.pdf';
                    // a.click();
                    // window.URL.revokeObjectURL(url);
                }
            }
        });
    });
}

var entrega_pqr = function () {
    $('#tabla_gestion_pqr tbody').on('click', 'button.entrega_pqr', function () {
        var data = $('#tabla_gestion_pqr').DataTable().row($(this).parents("tr")).data();
        alertify.confirm('Entrega Mercancia P.Q.R.', 'La mercancia fue entregada al cliente.',
            function () {
                envio_visita_tecnica(data, 9, 27);
            },
            function () {
                alertify.error('Operación Cancelada');
            }
        ).setting({
            'labels': { ok: 'Si Entregado', cancel: 'No Entregado' },
            'invokeOnCloseOff': true,
        });
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
        var obj_inicial = $('#boton_observacion').html();
        btn_procesando('boton_observacion');
        var observacion = CKEDITOR.instances.observaciones.getData();
        var id_pqr = $('.boton_codigo_motivo').attr('data');
        $.ajax({
            type: "POST",
            url: `${PATH_NAME}/pqr/observaciones_pqr`,
            data: { observacion, id_pqr },
            success: function (res) {
                if (res.status == 1) {
                    btn_procesando('boton_observacion', obj_inicial, 1);
                    alertify.success('Observacion guardada exitosamente');
                    $('#codigoMotivo').modal('hide');
                    consultar_pqr();
                } else {
                    btn_procesando('boton_observacion', obj_inicial, 1);
                    alertify.error('Algo a ocurrido');
                }
            }
        });
    })
}
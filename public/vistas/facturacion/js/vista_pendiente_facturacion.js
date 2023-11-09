$(document).ready(function () {
    tabla_pendientes_facturar();
    boton_regresar();
    cambio_documento_factura();
    descarga_pedido();
    lista_de_empaque();
    alertify.set('notifier', 'position', 'bottom-left');
});

var tabla_pendientes_facturar = function () {
    var table = $('#table_pendientes_facturar').DataTable({
        ajax: `${PATH_NAME}/facturacion/tabla_facturacion`,
        buttons: [{
            extend: 'excelHtml5',
            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
            tittleAttr: ' Exportar a exel',
            className: 'btn btn-success',
        }],
        columns: [
            { "data": "fecha_compromiso" },
            { "data": "nombre_empresa" },
            { "data": "orden_compra" },
            {
                "data": "asesor", render: function (date, type, row) {
                    return `${row['nombres']} ${row['apellidos']}`;
                }
            },
            { "data": "parcial" },
            { "data": "empresa_pertenece" },
            { "data": "forma_pago" },
            {
                "data": "item", render: function (date, type, row) {
                    return `${row['cantidad_reporte']} de ${row['cantidad_item']}`;
                }
            },
            { "data": "total_etiq", render: $.fn.dataTable.render.number('.', ',', 2, '$') },
            { "data": "total_tec", render: $.fn.dataTable.render.number('.', ',', 2, '$') },
            { "data": "num_pedido" },
            { "data": "nombre_estado" },
            {
                "data": "botones", render: function (data, type, row) {
                    return `<button class="facturar btn btn-info" id="ver_${row.id_pedido}" title="ver pedido">
                        <i class="fa fa-search" ></i>
                    </button>`;
                },
                "className": "text-center"
            },
        ],
        "deferRender": true,
        "stateSave": true,
    });
    pedido_facturar("#table_pendientes_facturar tbody", table);

}

var boton_regresar = function () {
    $('.ocultar_ver_pedido').on('click', function () {
        $('#tabla_facturacion_listado').css('display', '');
        $('#ConsultarPedido').css('display', 'none');
    });
}

var pedido_facturar = function (tbody, table) {
    $(tbody).on("click", "button.facturar", function () {
        var data = table.row($(this).parents("tr")).data();
        var obj_inicial = $(`#ver_${data.id_pedido}`).html();
        btn_procesando_tabla(`ver_${data.id_pedido}`);
        if (data.orden_compra == null) {
            $('#descarga_oc').css('display', 'none');
        }
        $('#pertenece').val(data.pertenece);
        consecutivo_documento(data.pertenece);
        $.ajax({
            url: `${PATH_NAME}/facturacion/pedido_item_facturacion`,
            type: "POST",
            data: { "id_pedido": data.id_pedido },
            success: function (res) {
                btn_procesando_tabla(`ver_${data.id_pedido}`, obj_inicial, 1);
                $('#tabla_facturacion_listado').css('display', 'none');
                $('#ConsultarPedido').css('display', '');
                rellenar_formulario(data);
                $("#num_pedido").empty().html(data.num_pedido);
                $("#descarga_oc").val(data.num_pedido);
                $('.boton-x').attr('formulario', data.num_pedido);
                /*RELLENAR SI TIENE PARCIAL EL PEDIDO*/
                if (data.parcial == 'Si') {
                    $(".parcial_si").empty().prop('checked', true);
                } else {
                    $(".parcial_no").empty().prop('checked', true);
                }
                /*RELLENAR LAS DIFERENCIAS*/
                if (data.difer_mas == '1') {
                    $("#difer_mas").empty().prop('checked', true);
                }
                if (data.difer_menos == '1') {
                    $("#difer_menos").empty().prop('checked', true);
                }
                if (data.difer_ext == '1') {
                    $("#difer_ext").empty().prop('checked', true);
                }
                // Rellenar el iva
                if (data.iva == '1') {
                    $('.iva_si').prop('checked', true);
                } else {
                    $('.iva_no').prop('checked', false);
                }
                // Cargamos los datos de la tabla
                tabla_productos(res);
                cantidad_por_facturar();
            }
        });
    });
}

var numFormat = $.fn.dataTable.render.number('.', ',', 0, '').display;
var tabla_productos = function (data) {
    var table = $('#tabla_items_pedido').DataTable({
        "data": data,
        'ordering': false,
        columns: [
            { "data": "item" },
            { "data": "codigo" },
            { "data": "Cant_solicitada", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            {
                "data": "cantidad_por_facturar", render: function (data, type, row) {
                    var input = row.cantidad_por_facturar;
                    if (row.cantidad_por_facturar != '0') {
                        input = `<input type="text" id="pendiente${row.id_pedido_item}" class="form-control cantidad_por_facturar" style='border:none;'  value="${numFormat(row.cantidad_por_facturar)}">`;
                    }
                    return input;
                }
            },
            { "data": "cantidad_facturada", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "descripcion_productos" },
            { "data": "trm", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "moneda" },
            { "data": "v_unidad", render: $.fn.dataTable.render.number('.', ',', 2, '') },
            { "data": "total", render: $.fn.dataTable.render.number('.', ',', 2, '') },
            { "data": "nombre_estado_item" },
            {
                "data": "boton", render: function (data, type, row) {
                    var res = '';
                    if (row.cantidad_por_facturar != '0') {
                        res = `<div class="select_acob text-center">
                        <input type="checkbox" checked class="agrupar_items" name='id_pedido_item${row.id_pedido_item}' value="${row.id_pedido_item}">
                       </div>`;
                    }
                    return res;
                }
            },
        ]
    });

}

var faltantesPorFacturar = [];

var cantidad_por_facturar = function (tbody, table) {
    $("#tabla_items_pedido tbody").on("blur", "input.cantidad_por_facturar", function () {
        var data = $('#tabla_items_pedido').DataTable().row($(this).parents("tr")).data();
        var numero = $(this).val();
        numero = numero.replace(/\./g, '');
        var cant_fac = data.cantidad_facturada;
        var suma_cantidad = parseFloat(numero) + parseFloat(cant_fac);
        var restante = parseFloat(data.cantidad_por_facturar) - parseFloat(numero);
        var array_cantidad = {
            'id_pedido_item': data.id_pedido_item,
            'cantidad_factura': restante,
            'cantidad_inicial': data.cantidad_por_facturar,
        };
        var storage = JSON.parse(localStorage.getItem('items_factura' + data.num_pedido));
        if (storage != null) {
            faltantesPorFacturar = storage;
        }
        window.showAlert = function () {
            alertify.alert('Cantidad Pendiente Factura', 'Faltan por facturar ' + restante + ' ¿Desea conservarlo para un nuevo documento?<br><div class="text-center">  <a href="javascript:showConfirm(1);" class="btn btn-success">Si</a>  <a href="javascript:showConfirm(3);" class="btn btn-danger">No</a></div>',
                function () {
                    $(`#pendiente${data.id_pedido_item}`).val(numFormat(data.cantidad_por_facturar));
                    alertify.error('Operación cancelada');
                }).set({
                    'label': 'Cancelar',
                    'transitionOff': true
                });
        }
        window.showConfirm = function (vista) {
            if (vista == 3) {
                restante = 0;
            }
            alertify.alert().close();
            var no_sube = true;
            if (restante <= 0) {
                var consulta = faltantesPorFacturar.findIndex(element => element.id_pedido_item === data.id_pedido_item);
                faltantesPorFacturar.splice(consulta, 1);
                no_sube = false;
            } else {
                for (var i = 0; i < faltantesPorFacturar.length; i++) {
                    if (data.id_pedido_item === faltantesPorFacturar[i].id_pedido_item) {
                        faltantesPorFacturar[i].cantidad_factura = restante;
                        faltantesPorFacturar[i].cantidad_inicial = data.cantidad_por_facturar;
                        no_sube = false;
                    }
                }
            }
            if (no_sube) {
                faltantesPorFacturar.push(array_cantidad);
            }
            envioLocalStorage(faltantesPorFacturar, data.num_pedido);
            alertify.confirm('Editar Cantidad Envio', 'Desea continuar con la edición para cambiar la cantidad reportada.',
                function () {
                    $.ajax({
                        url: `${PATH_NAME}/facturacion/editar_cantidad_envio`,
                        type: 'POST',
                        data: { data, numero },
                        success: function (res) {
                            if (res.status == 1) {
                                tabla_productos(res.table);
                                alertify.success(res.msg);
                            } else {
                                alertify.error(res.msg);
                            }
                            if (faltantesPorFacturar == '') {
                                localStorage.removeItem('items_factura' + data.num_pedido);
                            }
                        }
                    });
                },
                function () {
                    for (var i = 0; i < faltantesPorFacturar.length; i++) {
                        if (data.id_pedido_item === faltantesPorFacturar[i].id_pedido_item) {
                            faltantesPorFacturar.splice(i, 1);
                        }
                    }
                    $(`#pendiente${data.id_pedido_item}`).val(numFormat(data.cantidad_por_facturar));
                    alertify.error('Operación cancelada');
                    envioLocalStorage(faltantesPorFacturar, data.num_pedido);
                }
            );
        }
        if (suma_cantidad < data.Cant_solicitada) {
            window.showAlert();
        } else {
            window.showConfirm(2);
        }
    });
}

function envioLocalStorage(faltantesPorFacturar, num_pedido) {
    localStorage.setItem('items_factura' + num_pedido, JSON.stringify(faltantesPorFacturar));
}

var consecutivo_documento = function (empresa) {
    var dato_consulta = 9;
    if (empresa == 1 || empresa == 3) {
        dato_consulta = 8;
    }
    var eleg = '';
    if (empresa == 3) {
        eleg = 'selected';
    }
    $.ajax({
        url: `${PATH_NAME}/facturacion/consecutivo_documento`,
        type: "POST",
        data: { dato_consulta },
        success: function (res) {
            var option = '';
            var prefijo = res[0].prefijo;
            if (res[0].id_consecutivo == 8) {
                option = `<option value="0" selected>Seleccione tipo de documento</option>
                <option value="8">Factura</option>
                <option value="11">Remisión</option>`;
            } else {
                option = `<option value="0" selected>Seleccione tipo de documento</option>
                <option value="9">Factura</option>
                <option value="12">Remisión</option>`;
            }
            option += `<option value="6" ${eleg}>Cuentas de Cobro</option>`;
            $('#remision_factura').empty().html(option);
            $("#numero_factura").empty().html(`${prefijo} ${res[0].numero_guardado}`);
            $("#numero_factura_consulta").val(res[0].numero_guardado);
            if (empresa == 3) {
                $('#remision_factura').change();
            }
        }
    });
}

var cambio_documento_factura = function () {
    $('#remision_factura').on('change', function (e) {
        e.preventDefault();
        var dato_consulta = $(this).val();
        $.ajax({
            url: `${PATH_NAME}/facturacion/consecutivo_documento`,
            type: "POST",
            data: { dato_consulta },
            success: function (res) {
                var prefijo = res[0].prefijo;
                $("#numero_factura").empty().html(`${prefijo} ${res[0].numero_guardado}`);
                $("#numero_factura_consulta").val(res[0].numero_guardado);
            }
        });
    });
}

var descarga_pedido = function () {
    $('.descarga').on('click', function () {
        var elegido = $(this).attr('data-boton');
        if (elegido == '1') {
            ruta = `${PATH_NAME}/configuracion/generar_pdf_num_pedido`;
        } else {
            ruta = `${PATH_NAME}/facturacion/descargar_orden_compra`;
        }
        var num_pedido = $(this).val();
        $.ajax({
            url: ruta,
            type: "POST",
            data: { num_pedido },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (res) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(res);
                a.href = url;
                a.download = num_pedido + '_pedido.pdf';
                a.click();
                window.URL.revokeObjectURL(url);
                $('.boton_cambio').addClass('fa fa-download');
                $('.boton_cambio').removeClass('fas fa-spinner fa-spin');
            }
        });
    });
}

var lista_de_empaque = function () {
    $('#genera_lista_de_empaque').on('click', function () {
        if ($('#remision_factura').val() == 0) {
            alertify.error('Selecciona el tipo de documento');
            $('#remision_factura').focus();
            return
        } else {
            var obj_inicial = $('#genera_lista_de_empaque').html();
            var num_pedido = $('.boton-x').attr('formulario');
            var pertenece = $('#pertenece').val();
            var tipo_documento = $('#remision_factura').val();
            var numero_factura_consulta = $('#numero_factura_consulta').val();
            var data = $("#tabla_items_pedido").DataTable().rows().data();
            var dato_tabla = $("#tabla_items_pedido").DataTable().rows().nodes();
            var mensaje = '';
            var data_envio = [];
            var storage = JSON.parse(localStorage.getItem('items_factura' + num_pedido));
            // return;
            $.each(dato_tabla, function (index, value) {
                var p = $(this).find('input:checkbox').val();
                var estado_radio = RadioElegido(`id_pedido_item${p}`);
                if (estado_radio == 'ninguno') {
                } else {
                    data_envio.push(data[index]);
                }
            });
            if (mensaje == '' && data_envio == '') {
                mensaje = 'Se debe elegir un item para poder continuar.';
                alertify.error(mensaje);
                return;
            }
            var envio = {
                'items': data_envio,
                'num_pedido': num_pedido,
                'tipo_documento': tipo_documento,
                'numero_factura_consulta': numero_factura_consulta
            };
            if (tipo_documento == 6) {
                alertify.confirm('Continuar proceso', '¿Esta seguro que desea continuar con la generación de una cuenta de cobro?',
                    function () {
                        btn_procesando('genera_lista_de_empaque');
                        envio_datos_documento(envio, obj_inicial);
                    },
                    function () {
                        alertify.error('Operación Cancelada');
                    });
            } else {
                btn_procesando('genera_lista_de_empaque');
                envio_datos_documento(envio, obj_inicial, storage);
            }
        }
    });
}

var envio_datos_documento = function (envio, obj_inicial, storage) {
    var tipo_documento = envio['tipo_documento'];   //El numero 6 es CUENTA DE COBRO
    if (storage == null) {
        storage = 0;
    }
    $.ajax({
        url: `${PATH_NAME}/facturacion/lista_empaque`,
        type: 'POST',
        data: { envio, storage },
        xhrFields: {
            responseType: 'blob'
        },
        success: function (res) {
            localStorage.removeItem('items_factura' + envio.num_pedido);
            btn_procesando('genera_lista_de_empaque', obj_inicial, 1);
            if (res.size < 20) {
                alertify.error("Lo sentimos el numero del documento ya fue utilizado.");
            } else {
                if (tipo_documento == 6) {
                    // Descarga el pdf cuando es cuenta de cobro
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(res);
                    a.href = url;
                    a.download = `CuentaCobro${envio['num_pedido']}.pdf`;// Es como se llama el pdf
                    a.click();
                    window.URL.revokeObjectURL(url);
                } else {
                    // Linea para abrir el documento 
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(res);
                    a.href = url;
                    window.open(url, '_blank');
                }
                // recargar tabla
                $('.ocultar_ver_pedido').click();
                $('#table_pendientes_facturar').DataTable().ajax.reload();
            }
        }
    });
}


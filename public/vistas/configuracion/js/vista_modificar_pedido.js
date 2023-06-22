$(document).ready(function () {
    select_2();
    consultar_formulario();
    tipo_consulta();
    $('#dato_consulta_select').select2().next().hide();
    $(".datepicker").datepicker();
    elimina_pedido();
    modificar_pedido();
    regresa_modificacion();
    eliminar_item_pedido('#tabla_item_pedido tbody');
    agregar_producto_item_pedido();
    agregar_producto()
    editar_estado_item('#tabla_item_pedido tbody');
    editar_material('#tabla_item_pedido tbody');
    editar_material_asignado();
    form_modificar_pedido();
    cambio_valor_item('#tabla_item_pedido tbody');
});

var consultar_formulario = function () {
    $('#form_consulta_pedido').submit(function (e) {
        e.preventDefault();
        var valida = false;
        var form = $(this).serializeArray();
        if ($('#tipo_consulta').val() == '' && $('#dato_consulta').val() == '') {
            alertify.confirm('Confirmación de consulta', 'Está a punto de realizar una consulta de toda la base de datos, este proceso puede tomar un tiempo prolongado.<br><br>¿Desea continuar con esta operación de todas formas.?',
                function () {
                    datos_consulta(form);
                },
                function () {
                    alertify.error('Operación Cancelada');
                });
        } else {
            valida = validar_formulario(form);
        }
        if (valida) {
            datos_consulta(form);
        }
    });
}

var tipo_consulta = function () {
    $('#tipo_consulta').on('change', function () {
        var elegido = $(this).val();
        if (elegido == 'id_cli_prov') {
            $('#dato_consulta_select').select2().next().show();
            $('#dato_consulta_select').attr('name', 'dato_consulta');
            $('#dato_consulta').css('display', 'none');
            $('#dato_consulta').removeAttr("name");

        } else {
            $('#dato_consulta_select').select2().next().hide();
            $('#dato_consulta_select').removeAttr("name");
            $('#dato_consulta').css('display', 'block');
            $('#dato_consulta').attr('name', 'dato_consulta');
        }
    });
}

var datos_consulta = function (form) {
    var obj_inicial = $('#consulta_pedido').html();
    btn_procesando('consulta_pedido');
    $.ajax({
        "url": `${PATH_NAME}/configuracion/consultar_pedido`,
        "type": 'POST',
        "data": form,
        "success": function (respu) {
            cargar_tabla_pedidos(respu);
            $('#dato_consulta_select').val('0').change();
            $('#tipo_consulta').val('num_pedido').change();
            limpiar_formulario('form_consulta_pedido', 'input');
            btn_procesando('consulta_pedido', obj_inicial, 1);
        }
    });
}

var cargar_tabla_pedidos = function (data) {
    var table = $("#tabla_pedidos").DataTable({
        "data": data,
        "columns": [
            { "data": "id_pedido" },
            { "data": "fecha_crea_p" },
            { "data": "hora_crea" },
            { "data": "num_pedido" },
            { "data": "nombre_empresa" },
            { "data": "orden_compra" },
            { "data": "total_etiq", render: $.fn.dataTable.render.number('.', ',', 2, '$') },
            { "data": "total_tec", render: $.fn.dataTable.render.number('.', ',', 2, '$') },
            {
                "data": "nombre_estado_pedido",
                render: function (data, type, row) {
                    return '<strong>' + row['nombre_estado_pedido'] + '</strong>';
                }
            },
            {
                "orderable": false,
                "defaultContent": '<button type="button" class=" view btn btn-success btn-sm modificar_p_asesor ">' +
                    '<span class="fa fa-pencil-alt "></span>' +
                    '</button> '
            },
            {
                "orderable": false,
                "defaultContent": '<button type="button" class=" view btn btn-danger btn-sm eliminar_pedido ">' +
                    '<span class="fa fa-times"></span>' +
                    '</button> '
            }
        ],
    });
}

/*
Boton para eliminar un pedido este boton debe tambien quitar los item de la tabla entrega_logistica
*/
var elimina_pedido = function () {
    $('#tabla_pedidos').on('click', 'button.eliminar_pedido', function () {
        var data = $("#tabla_pedidos").DataTable().row($(this).parents("tr")).data();
        /*Eliminar pedido*/
        alertify.confirm('Eliminar Pedido', '¿Está seguro?,desea eliminar el pedido: <strong>' + data.num_pedido + "</strong><br><br>\n\
            Esta opción eliminará permanentemente el pedido y no puede ser reversado.", function () {
            $.ajax({
                url: `${PATH_NAME}/configuracion/eliminar_pedido`,
                type: 'POST',
                data: data,
                success: function (r) {
                    var data = [];
                    cargar_tabla_pedidos(data);
                    alertify.success('Pedido eliminado correctamente !!');
                    // location.reload();
                }
            });
        }, function () {
            alertify.error('Operación Cancelada !!')
        });
    });
}

/*
Boton para la modificacion del pedido
*/

var modificar_pedido = function () {
    $('#tabla_pedidos').on("click", "button.modificar_p_asesor", function () {
        $('#ocultar').hide();
        $("#verPedidoM").collapse("toggle");
        var datos = $("#tabla_pedidos").DataTable().row($(this).parents("tr")).data();
        // CARGAR LOS SELEC DIRECCION
        cargar_direcciones_cliente(datos.nit, datos.id_usuario, datos.id_dire_entre, datos.id_dire_radic);
        // CARGAR LOS PRODUCTOS PARA AGREGAR AL PEDIDO
        cargar_productos_cliente(datos.id_cli_prov, datos.id_pedido);
        // Serellena el formulario para los elementos que son solo visibles
        $.each(datos, function (name, value) {
            $(`#${name}`).html(value);
        });
        // Se rellena el formulari de los select e input para modificar
        rellenar_formulario(datos);
        $('#orden_compra_antigua').val(datos.orden_compra);
        // Se rellenan los input check y radio
        $(`input[name=parcial][value='${datos.parcial}']`).prop("checked", true);
        $(`input[name=iva][value='${datos.iva}']`).prop("checked", true);
        if (datos.difer_mas == '1') {
            $('#difer_mas').prop('checked', true);
            $('#difer_mas').val(1);
        } else {
            $("#difer_mas").prop("checked", false);
            $("#difer_mas").val(0);
        }
        if (datos.difer_menos == '1') {
            $('#difer_menos').prop('checked', true);
            $('#difer_menos').val(1);
        } else {
            $("#difer_menos").prop("checked", false);
            $("#difer_menos").val(0);
        }
        if (datos.difer_ext == '1') {
            $('#difer_ext').prop('checked', true);
            $('#difer_ext').val(1);
        } else {
            $("#difer_ext").prop("checked", false);
            $("#difer_ext").val(0);
        }
        // Cargar los item del pedido
        var id_pedido = datos.id_pedido;
        $('#tabla_item_pedido').DataTable({
            "searching": false,
            "paging": false,
            "ordering": false,
            "info": false,
            "ajax": {
                "url": `${PATH_NAME}/configuracion/consultar_items_pedido`,
                "type": "POST",
                "data": { id_pedido }
            },
            "columns": [{
                "defaultContent": ` 
                    <button class="btn btn-danger btn-sm btn-circle eliminar_item"><i class="fa fa-times"></i></button>`,
                "className": "text-center"
            },
            { "data": "id_pedido_item" },
            { "data": "item" },
            { "data": "n_produccion" },
            { "data": "codigo" },
            { "data": "descripcion_productos" },
            { "data": "Cant_solicitada" },
            { "data": "ficha_tecnica" },
            { "data": "nombre_r_embobinado" },
            { "data": "nombre_core" },
            { "data": "cant_x" },
            { "data": "trm" },
            { "data": "moneda" },
            {
                "data": "v_unidad", render: function (data, type, row) {
                    var res = '';
                    if (row.roll == 1 || row.roll == 8) {
                        res = `<input class="form-control valor_item" type="text" value="${$.fn.dataTable.render.number('.', ',', 2, '$ ').display(row.v_unidad)}">`;
                    } else {
                        res = $.fn.dataTable.render.number('.', ',', 2, '$ ').display(row.v_unidad);
                    }
                    return res;
                }
            },
            { "data": "total", render: $.fn.dataTable.render.number('.', ',', 2, '$ ') },
            {
                "data": "estado",
                render: function (data, type, row) {
                    var items = '';
                    if (row.roll == 1 || row.roll == 8) {
                        items += "<select class='estado_item' style='width:90%; font-size: 12px;'>"; //estados del pedido
                        for (let j = 0; j < row.estados_item.length; j++) {
                            if (row.estados_item[j].id_estado_item_pedido == row.id_estado_item_pedido) {
                                items += `<option selected='true' value='${row.estados_item[j].id_estado_item_pedido}'>${row.estados_item[j].id_estado_item_pedido} ${row.estados_item[j].nombre_estado_item}</option>`;
                            } else {
                                items += `<option  value='${row.estados_item[j].id_estado_item_pedido}'>${row.estados_item[j].id_estado_item_pedido} ${row.estados_item[j].nombre_estado_item}</option>`;
                            }
                        }
                        items += "</select>";
                    }
                    items += `<br><b style='font-size: 12px;'>${row.nombre_estado_item}</b><br>`;
                    $('.estado_item').select2();
                    return items;
                }
            },
            {
                "data": "material",
                render: function (data, type, row) {
                    var material = '';
                    if (row.id_material == '' || row.id_material == 0) {
                        material += `<b style="color:red">No</b><button class="btn btn-circle btn-info asignacion_m" data-id_m="${row.id_material}" data-id="${row.id_clien_produc}"  data-bs-toggle="modal" data-bs-target="#AsignarMaterial"><i class="fa fa-search"></i></button>`;
                    } else {
                        material += `<b style="color:green">Si</b> <span id="${row.id_clien_produc}"><button class="btn btn-circle btn-info asignacion_m" data-id_m="${row.id_material}" data-id="${row.id_clien_produc}"  data-bs-toggle="modal" data-bs-target="#AsignarMaterial"><i class="fa fa-search"></i></button></span>`;
                    }
                    material += `<input type="hidden" value="${row.item}" />`;
                    return material;
                }
            },
            { "data": "fecha_compro_item" },
            ],
        });

    });
}

var regresa_modificacion = function () {
    $("button.mostrar").on("click", function () {
        $('#ocultar').show(500);
        $("#verPedidoM").collapse("toggle");
        $('#id_tabla').click();
    });
}

var cargar_direcciones_cliente = function (nit, id_usuario, id_dire_entre, id_dire_radic) {
    $.ajax({
        url: `${PATH_NAME}/configuracion/consultar_direccion_cliente`,
        type: 'POST',
        data: { 'nit': nit, 'id_usuario': id_usuario },
        success: function (r) {
            var items = '<option value="" >Elija</option>';
            for (var i = 0; i < r.length; i++) {
                items += "<option info-dir='" + JSON.stringify(r[i]) + "' value='" + r[i].id_direccion + "'>" + r[i].direccion + "</option>";
                if (id_dire_entre == r[i].id_direccion) {
                    var datos = {
                        'contacto': r[i].contacto,
                        'cargo': r[i].cargo,
                        'email': r[i].email,
                        'celular': r[i].celular,
                        'telefono': r[i].telefono,
                        'horario': r[i].horario,
                        'forma_pago': r[i].nombre_forma_pago,
                    }
                }
            }
            $("#id_dire_entre").empty().html(items);
            $('#id_dire_entre').val(id_dire_entre).trigger('change.select2');
            $("#id_dire_radic").empty().html(items);
            $('#id_dire_radic').val(id_dire_radic).trigger('change.select2');
            $.each(datos, function (name, value) {
                $(`#${name}`).html(value);
            });
        }
    });
}

var cargar_productos_cliente = function (id_cli_prov, id_pedido) {
    $.ajax({
        "url": `${PATH_NAME}/comercial/consultar_productos_clientes?id=` + id_cli_prov,
        success: function (r) {
            var items = "<option value='0'></option>";
            r.data.forEach(element => {
                var visible = '';
                if (element.estado_producto != 1) {
                    visible = 'disabled';
                }
                items += `<option ${visible} value='${JSON.stringify(element)}'>
                    ${element.codigo_producto} | ${element.descripcion_productos} | ${element.nombre_r_embobinado} | ${element.nombre_core} | ${element.presentacion} 
                </option>`;
            });
            $(".id_clien_produc").empty().empty().html(items);
        }
    });
}

var agregar_producto_item_pedido = function () {
    $('#id_clien_produc').change(function () {
        var datos = JSON.parse($(this).val());
        if (datos.moneda == 2) {
            if (!$("#trm_cambio").is(":visible")) {
                $("#trm_cambio").toggle(500);
            }
        } else {
            if ($("#trm_cambio").is(":visible")) {
                $("#trm_cambio").toggle(500);
            }
        }
        if (!$("#datos_anade").is(":visible")) {
            $("#datos_anade").toggle(500);
        }
        $("#valor_venta").html(datos.precio_venta);
        $("#moneda_venta").html(datos.nom_mon_venta);
        $("#valor_Autoriza").html(datos.precio_autorizado);
        $("#moneda_autoriza").html(datos.nom_mon_autoriza);
    });
}

var editar_codigo_producto = function (id_cli_prov, id_clien_produc, id_pedido_item) {
    $.ajax({
        "url": `${PATH_NAME}/comercial/consultar_productos_clientes?id=` + id_cli_prov,
        success: function (r) {
            var item = `<select class="editar_item" id="editar${id_pedido_item}"><option value=''></option>`;
            for (var i = 0; i < r.data.length; i++) {
                if (r.data[i].id_clien_produc == id_clien_produc) {
                    item += `<option value='${JSON.stringify(r.data[i])}' selected >${r.data[i].codigo_producto}</option>`;
                } else {
                    item += `<option value='${JSON.stringify(r.data[i])}'>${r.data[i].codigo_producto}</option>`;
                }
            }
            item += `</select>`;
            $(`#select${id_pedido_item}`).empty().html(item);
            $(`.editar_item`).select2();
        }
    });
}

var eliminar_item_pedido = function (tbody) {
    $(tbody).on('click', 'tr button.eliminar_item', function (e) {
        e.preventDefault();
        var data = $('#tabla_item_pedido').DataTable().row($(this).parents("tr")).data();
        var n_produccion = data.n_produccion;
        var id_pedido_item = data.id_pedido_item;
        alertify.confirm('Eliminar Item Pedido', '¿Está seguro?,desea eliminar el item con el codigo: <strong>' + data.codigo + "</strong><br>\n\
        Esta opción eliminara todo el item del pedido.", function () {
            $.ajax({
                url: `${PATH_NAME}/configuracion/eliminar_item_pedido`,
                type: 'POST',
                data: { id_pedido_item, n_produccion },
                success: function (r) {
                    if (r.estado == -1) {
                        alertify.error(r.mensaje);
                    } else {
                        $('#tabla_item_pedido').DataTable().ajax.reload();
                        cargar_tabla_pedidos(r);
                        alertify.success('Eliminado !!');
                    }
                }
            });
        }, function () {
            alertify.error('Operación Cancelada !!')
        });
    });
}

var agregar_producto = function () {
    $('#add_producto').on('click', function () {
        var datos = JSON.parse($('#id_clien_produc').val());
        var id_pedido = $('#id_pedido').html();
        var cantidad = $('#cantidad').val();
        var trm = $('#trm').val();
        var continua = true;
        if (cantidad == '' || cantidad == 0) {
            alertify.error('Lo sentimos debe haber una cantidad para continuar');
            $('#cantidad').focus();
            continua = false;
            return;
        }
        if (datos.moneda == 2) {
            if (trm == '' || trm == 0) {
                alertify.error('Lo sentimos se requiere la trm para continuar');
                $('#trm').focus();
                continua = false;
            }
        }
        if (continua) {
            var envio = {
                'datos': datos,
                'cantidad': cantidad,
                'trm': trm,
                'id_pedido': id_pedido
            };
            $.ajax({
                url: `${PATH_NAME}/configuracion/agregar_item_pedido`,
                type: 'POST',
                data: envio,
                success: function (respu) {
                    if (respu == '') {
                        alertify.error(respu.mensaje);
                    } else {
                        $('#tabla_item_pedido').DataTable().ajax.reload();
                        $('#trm').val('');
                        $('#cantidad').val('');
                        $("#datos_anade").toggle(500);
                        $("#id_clien_produc").val(0).trigger('change');
                        cargar_tabla_pedidos(respu);
                        alertify.success('Item agregado de manera correcta');
                    }
                }
            });
        }
    });
}

var editar_estado_item = function (tbody) {
    $(tbody).on('change', 'tr select.estado_item', function (e) {
        var data = $('#tabla_item_pedido').DataTable().row($(this).parents("tr")).data();
        var elegido = JSON.parse($(this).val());
        var envio = {
            'datos': elegido,
            'data_exist': data,
            'form_envio': 1
        }
        editar_item_pedido(envio);
    });
}

var editar_item_pedido = function (envio) {
    $.ajax({
        url: `${PATH_NAME}/configuracion/editar_item_pedido`,
        type: 'POST',
        data: envio,
        success: function (respu) {
            console.log(respu);
            $('#tabla_item_pedido').DataTable().ajax.reload();
            cargar_tabla_pedidos(respu);
            alertify.success('Item Modificado correctamente');
        }
    });
}

var editar_material = function (tbody) {
    $(tbody).on('click', 'tr button.asignacion_m', function (e) {
        e.preventDefault();
        var data = $('#tabla_item_pedido').DataTable().row($(this).parents("tr")).data();
        var elegido = $(this).val();
        $('#descripcion_modifi').html(data.descripcion_productos);
        $('#codigo_modifi').html(data.codigo);
        $('#id_material_modifi').val(data.id_material).trigger('change');
        $('#modificar_material').attr('data-id', data.id_clien_produc);
    });
}

var editar_material_asignado = function () {
    $('#form_modificar_material').submit(function (e) {
        e.preventDefault();
        var form = $(this).serialize();
        var id_clien_produc = $('#modificar_material').attr('data-id');
        var envio = {
            'form': form,
            'id': id_clien_produc
        };
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_tipo_material`,
            "type": 'POST',
            "data": envio,
            "success": function (respu) {
                console.log(respu);
                if (respu) {
                    $("#AsignarMaterial").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                    $('#tabla_item_pedido').DataTable().ajax.reload();
                } else {
                    alertify.error('Error inesperado');
                }
            }
        });
    });
}

var form_modificar_pedido = function () {
    $('#form_modificar_pedido').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#modifica_pedido').html();
        var form = $(this).serializeArray();
        form = document.getElementById('form_modificar_pedido');
        btn_procesando('modifica_pedido');
        $.ajax({
            url: `${PATH_NAME}/configuracion/modificar_pedido`,
            type: 'POST',
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            success: function (respuesta) {
                alertify.success('El pedido se edito de manera correcta. Esta pagina se recargara en 10 segundos');
                // btn_procesando('modifica_pedido', obj_inicial, 1);
                setTimeout('document.location.reload()', 10000);
                // location.reload();
            }
        });
    });
}

var cambio_valor_item = function (tbody) {
    $(tbody).on('change', 'tr input.valor_item', function (e) {
        var valor_item = $(this).val();
        var data = $('#tabla_item_pedido').DataTable().row($(this).parents("tr")).data();
        valor_item = valor_item.replace('$', '');
        valor_item = valor_item.replace(',', '.');
        valor_item = valor_item.replace('.', '.');
        valor_item = parseFloat(valor_item);
        $.ajax({
            url: `${PATH_NAME}/configuracion/modificar_valor_item`,
            type: 'POST',
            data: { valor_item, data },
            success: function (res) {
                console.log(res);
                $('#tabla_item_pedido').DataTable().ajax.reload();
                cargar_tabla_pedidos(res);
                alertify.success('Pedido Modificado correctamente !!');
            }
        });
    });
}
    // odod
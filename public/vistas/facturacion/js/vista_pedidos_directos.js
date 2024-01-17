$(document).ready(function () {
    select_2();
    crea_pedido_directo();
    carga_direccion();
    busca_productos();
    elije_productos();
    agrega_productos_storage();
    elimina_items();
    crea_pedido_directo_galan();
    crear_cliente();
    crear_direccion();
});

var crea_pedido_directo = function () {
    $("#crea_pedido_directo").on('submit', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);

        if (valida) {
            $.ajax({
                url: `${PATH_NAME}/facturacion/info_cliente_producto`,
                type: "POST",
                data: form,
                success: function (res) {
                    if (res == '') {
                        $('#botones_crea').removeClass('d-none');
                        $('#crear_cliente').removeClass('d-none');
                        $('#crear_direccion').removeClass('d-none');
                        $('#crear_direccion').attr('disabled', 'disabled');
                        $('#span_estado').html('El cliente no existe, por favor realice la creacion de este.');
                        $(".NuevoPEDIDO").css('display', 'none');
                    } else {
                        if (res[0].direccion == '') {
                            $('#botones_crea').removeClass('d-none');
                            $('#crear_cliente').addClass('d-none');
                            $('#crear_direccion').removeClass('d-none');
                            $('#crear_direccion').removeAttr('disabled');
                            $('#span_estado').html('El cliente no tiene direcciones, por favor realice la creacion de esta.');
                            $(".NuevoPEDIDO").css('display', 'none');
                            $('#nombre_empresa_dir').val(res[0].nombre_empresa);
                            $('#id_cli_prov').val(res[0].id_cli_prov);
                        } else {
                            $('#botones_crea').addClass('d-none');
                            $('#crear_cliente').addClass('d-none');
                            $('#crear_direccion').addClass('d-none');
                            $('#span_estado').html('');
                            if (!$(".NuevoPEDIDO").is(":visible")) {
                                $(".NuevoPEDIDO").css('display', 'block');
                            }
                            cargar_data(res);
                            carga_tabla_productos(res[0].id_cli_prov);
                            valores_sin_iva(res[0].id_cli_prov);
                            crea_producto_nuevo(res[0].id_cli_prov);
                            consecutivo_documento(res[0].pertenece);
                        }
                    }
                    // var direc = res[0].direccion;
                    // if (direc === null) {
                    //     alertify.alert('No hay direcciones', 'Este cliente no posee ninguna direccion. Por favor agregar una direccion',
                    //         function () {
                    //             $("#nit_empresa").val('');
                    //         }).set({
                    //             'label': 'Aceptar',
                    //             'transitionOff': true
                    //         });
                    // } else {
                    //     if (res != '') {
                    //         if (!$(".NuevoPEDIDO").is(":visible")) {
                    //             $(".NuevoPEDIDO").css('display', 'block');
                    //         }
                    //         $("#nombre_cliente").empty().html(res[0].nombre_empresa);
                    //         $("#nit_cliente").empty().html(res[0].nit);
                    //         $("#id_cliv_provP").val(res[0].id_cli_prov);
                    //         var direccion = `<option info-dir='${JSON.stringify(res[0].direccion)}' value="${res[0].direccion['id_direccion']}">${res[0].direccion['direccion']}</option>`;
                    //         $("#id_direccionC").empty().html(direccion);
                    //         $("#id_direccionCC").empty().html(direccion);
                    //         $("#id_direccionC").change();
                    //         carga_tabla_productos(res[0].id_cli_prov);
                    //         valores_sin_iva(res[0].id_cli_prov);
                    //         crea_producto_nuevo(res[0].id_cli_prov);
                    //         consecutivo_documento(res[0].pertenece);
                    //     } else {
                    //         if ($(".NuevoPEDIDO").is(":visible")) {
                    //             $(".NuevoPEDIDO").css('display', 'none');
                    //         }
                    //     }
                    // }
                }
            });
        }
    });
}

var crear_cliente = function () {
    $("#form_creacion_cliente").on('submit', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var exception = ['tipo_cli_prov', 'forma_pago', 'dias_dados', 'pertenece', 'lista_precio', 'cupo_cliente', 'dias_max_mora', 'tipo_prove', 'logo_etiqueta'];
        var valida = validar_formulario(form, exception);
        if (valida) {
            form = $(this).serialize();
            var id_usuarios_asesor = $('#id_usuarios_asesor').val();
            if ($('#id_usuarios_asesor').val() == '') {
                alertify.error('El campo Asesores se requiere almenos un asesor para continuar');
                return;
            }
            var envio = {
                'form': form,
                'id_usuarios_asesor': id_usuarios_asesor
            };
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_cliente`,
                "type": 'POST',
                "data": envio,
                "success": function (respuesta) {
                    $('#crear_direccion').removeClass('d-none');
                    $('#crear_direccion').removeAttr('disabled');
                    var nombre_empresa = $('#nombre_empresa').val();
                    $('#creacion_direc').modal('show');
                    $('#creacion_cliente').modal('hide');
                    $('#crear_cliente').attr('disabled', 'disabled');
                    $('#nombre_empresa_dir').val(nombre_empresa);
                    $('#id_cli_prov').val(respuesta.id);
                }
            });
        }
    });
}

var crear_direccion = function () {
    $("#form_creacion_direc").on('submit', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var exception = ['id_cli_prov', 'recor_dia_cierre'];
        var valida = validar_formulario(form, exception);
        if (valida) {
            $.ajax({
                "url": `${PATH_NAME}/comercial/crear_dir_clientes`,
                "type": 'POST',
                "data": form,
                "success": function (respuesta) {
                    if (respuesta.status == true) {
                        $('#creacion_direc').modal('hide');
                        $('#botones_crea').addClass('d-none');
                        $("#crea_pedido_directo").trigger('submit');
                    }
                }
            });
        }
    });
}

var cargar_data = function (res) {
    $("#nombre_cliente").empty().html(res[0].nombre_empresa);
    $("#nit_cliente").empty().html(res[0].nit);
    $("#id_cliv_provP").val(res[0].id_cli_prov);
    var direccion = `<option info-dir='${JSON.stringify(res[0].direccion)}' value="${res[0].direccion['id_direccion']}">${res[0].direccion['direccion']}</option>`;
    $("#id_direccionC").empty().html(direccion);
    $("#id_direccionCC").empty().html(direccion);
    $("#id_direccionC").change();
}

var carga_direccion = function () {
    $("#id_direccionC").change(function (e) {
        e.preventDefault();
        if ($("#id_direccionC").val() != 0) {
            if (!$(".dir_entrega").is(":visible")) {
                $(".dir_entrega").toggle(500);
            }
            var op = JSON.parse($("#id_direccionC option:selected").attr('info-dir'));
            $("#infoCon").empty().html(op.contacto);
            $("#infoCar").empty().html(op.cargo);
            $("#infoEmail").empty().html(op.email);
            $("#infoCel").empty().html(op.celular);
            $("#infoTel").empty().html(op.telefono);
            $("#infoHorario").empty().html(op.horario);
            if (op.forma_pago == 1) {
                $("#infoFo").empty().html("Contado Efectivo");
            }
            if (op.forma_pago == 2) {
                $("#infoFo").empty().html("Contado Factura");
            }
            if (op.forma_pago == 3) {
                $("#infoFo").empty().html("Cheque Posfechado");
            }
            if (op.forma_pago == 4) {
                $("#infoFo").empty().html("Credito");
            }
        }
    });
}
var busca_productos = function () {
    $("#productos").change(function (e) {
        e.preventDefault();
        var id_producto = $(this).val();
        var id_cli_prov = $("#id_cliv_provP").val();
        $.ajax({
            url: `${PATH_NAME}/facturacion/busca_producto`,
            type: "POST",
            data: { id_producto, id_cli_prov },
            success: function (res) {
                if (res != "") {
                    if (!$(".select_product").is(":visible")) {
                        $(".select_product").toggle(500);
                    }
                    if ($(".crea_producto").is(":visible")) {
                        $(".crea_producto").toggle(500);
                    }
                    var productos_disp = '<option value="0">Elije un producto de este cliente</option>';

                    res.forEach(element => {
                        var visible = '';
                        if (element.estado_producto != 1) {
                            visible = 'disabled';
                        }
                        productos_disp /*html*/ += `<option ${visible} value='${JSON.stringify(element)}'>
                            ${element.codigo_producto} | ${element.descripcion_productos} | ${element.nombre_r_embobinado} | ${element.nombre_core} | ${element.presentacion} 
                        </option>`;
                    });
                    $('#id_clien_producPP').empty().html(productos_disp);
                } else {
                    if ($(".select_product").is(":visible")) {
                        $(".select_product").toggle(500);
                    }
                    if ($(".add_product").is(":visible")) {
                        $(".add_product").toggle(500);
                    }
                    if (!$(".crea_producto").is(":visible")) {
                        $(".crea_producto").toggle(500);
                    }

                }

            }
        });
    });
}
var elije_productos = function () {
    $("#id_clien_producPP").change(function (e) {
        e.preventDefault();
        var info = JSON.parse($("#id_clien_producPP").val());
        if (info != 0) {
            $('#valor_venta').empty().html(info.precio_venta);
            $('#valor_Autoriza').empty().html(info.precio_autorizado);
            var moneda = '';
            var moneda_autoriza = '';
            if (info.moneda == 1) {
                moneda = ' COP';
            } else if (info.moneda == 0) {
                moneda = '<span style="color:red;">??</span>';
            } else {
                moneda = ' Dolares ';
            }
            if (info.moneda_autoriza == 1) {
                moneda_autoriza = ' COP';
            } else if (info.moneda_autoriza == 0) {
                moneda_autoriza = '<span style="color:red;"> ?? </span>';
            } else {
                moneda_autoriza = ' Dolares ';
            }
            $('#moneda_venta').empty().html(moneda);
            $('#moneda_autoriza').empty().html(moneda_autoriza);
            $("#add_producto").attr('data-product', JSON.stringify(info));
            if (!$(".add_product").is(":visible")) {
                $(".add_product").toggle(500);
            }
            //deshabilita boton de agregar  si no esta autorizado el precio
            if (info.moneda_autoriza == 0 || info.precio_autorizado == 0) {
                $("#add_producto").attr('disabled', true);
                alertify.error("Este precio aun no ha sido autorizado. <spam><i class='far fa-frown'></i></spam>");
            } else {
                $("#add_producto").attr('disabled', false);
                //valida si las monedas son iguales compara los precios para verificar que el precio de venta no es menor al autorizado
                if (info.moneda == info.moneda_autoriza) {
                    if (info.precio_autorizado > info.precio_venta) {
                        alertify.error("No se puede agregar este producto el precio de venta es menor al precio autorizado.");
                        $("#add_producto").attr('disabled', true);
                    } else {
                        $("#add_producto").attr('disabled', false);
                    }
                } else {
                    //valida si la moneda es pesos y la moneda autorizada es dolar convierte los dolares a pesos y compara los precios para verificar que el precio de venta no es menor al autorizado
                    var trm = $("#trmPP").val();
                    if (info.moneda == 1 && info.moneda_autoriza == 2) {
                        valor_pesos = parseFloat(info.precio_autorizado) * parseFloat(trm);
                        if (parseFloat(info.precio_venta) < parseFloat(valor_pesos)) {
                            $("#add_producto").attr('disabled', true);
                            alertify.error('El precio de venta es menor que el precio Autorizado !!');
                            return;
                        } else {
                            $("#add_producto").attr('disabled', false);
                        }
                    } else {
                        valor_pesos = parseFloat(info.precio_venta) * parseFloat(trm);
                        if (valor_pesos < parseFloat(info.precio_autorizado)) {
                            $("#add_producto").attr('disabled', true);
                            alertify.error('El precio de venta es menor que el precio Autorizado !!');
                            return;
                        } else {
                            $("#add_producto").attr('disabled', false);
                        }
                    } 1
                }
            }

        }
    });
}
/*
 *  funcion que agrega al local storage los productos que se van agregando para un clinte por el id cliente provedor 
 */
var agrega_productos_storage = function () {
    $("#add_producto").on('click', function (e) {
        e.preventDefault();
        var datos = JSON.parse($(this).attr('data-product'));
        var storage = JSON.parse(localStorage.getItem('productos_pedido' + datos.id_cli_prov));
        var nuevo = $("#cantidad").val();
        if (nuevo == '') {
            alertify.error("¡¡La cantidad esta vacia!!");
        } else {
            if (parseInt(nuevo) < parseInt(datos.cantidad_minima)) {
                alertify.error("La cantidad ingresada es menor a la cantidad minima de este producto");
            } else {
                if (storage != null) {
                    array_productos = storage;
                }
                if (array_productos == '') {
                    datos['cantidad_requerida'] = nuevo;
                    array_productos.push(datos);
                } else {
                    var respu = false;
                    array_productos.forEach(element => {
                        if (element.id_clien_produc == datos.id_clien_produc) {
                            element['cantidad_requerida'] = parseInt(element['cantidad_requerida']) + parseInt(nuevo);
                            respu = true;
                        }
                    });
                    if (!respu) {
                        datos['cantidad_requerida'] = nuevo;
                        array_productos.push(datos);
                    }
                }
                localStorage.setItem('productos_pedido' + datos.id_cli_prov, JSON.stringify(array_productos));
                carga_tabla_productos(datos.id_cli_prov);
                $("#cantidad").val('');
                $(".add_product").toggle(500);
                $("#id_clien_producPP").val(0).trigger('change');
                $(".select_product").toggle(500);
            }
        }
    });
}
/*
 *  funcion que carga la tabla de los productos agregados al local storage 
 */
var carga_tabla_productos = function (id_cli_prov) {
    var storage = JSON.parse(localStorage.getItem('productos_pedido' + id_cli_prov));
    // si no hay local storage vacia la tabla
    if (storage === null) {
        $('#tabla-item-pedido').DataTable().clear().draw();
    } else {

        $('#tabla-item-pedido').DataTable().clear().draw();
        storage.forEach(element => {
            var trm = $("#trmPP").val();
            var total = 0;
            if (element.moneda == 2) {
                var valor_pesos = parseFloat(element.precio_venta) * parseFloat(trm);
                total = parseFloat(valor_pesos) * parseFloat(element.cantidad_requerida);
            } else {
                total = parseFloat(element.precio_venta) * parseFloat(element.cantidad_requerida);
            }
            element['valor_total'] = total.toFixed(2);
        });
        localStorage.setItem('productos_pedido' + id_cli_prov, JSON.stringify(storage));
        var nuevo_storage = JSON.parse(localStorage.getItem('productos_pedido' + id_cli_prov));
        var table = $("#tabla-item-pedido").DataTable({
            "data": nuevo_storage,
            "searching": false,
            "bPaginate": false,
            "info": false,
            "ordering": false,
            "pageLength": 30, //good,
            "columns": [
                {
                    "defaultContent": `<center>
                        <button class="btn btn-danger btn-sm btn-circle elimina_item"><i class="fa fa-times"></i></button>
                        </center>` },
                { "data": "codigo_producto" },
                { "data": "descripcion_productos" },
                { "data": "cantidad_requerida" },
                { "data": "ficha_tecnica" },
                { "data": "nombre_r_embobinado" },
                { "data": "nombre_core" },
                { "data": "presentacion" },
                { "data": "moneda" },
                { "data": "precio_venta" },
                { "data": "valor_total" },
            ],
        });
    }
    suma_items(storage);
}
/*
 *  funcion que muestra el resumen de valores totales del pedido
 */
var suma_items = function (storage) {
    if (storage == null) {
        $("#totalitems").empty().html('');
        $("#subtotal").empty().html('');
        $("#iva").empty().html('');
        $("#total").empty().html('');

    } else {
        var totalitems = storage.length;
        var subtotal = 0;
        var total = 0;
        storage.forEach(element => {
            subtotal += parseFloat(element.valor_total);
        });
        $("#totalitems").empty().html(totalitems);
        $("#subtotal").empty().html((subtotal).toLocaleString(undefined, { minimumFractionDigits: 2 }) + " COP");
        $("#iva").empty().html((IVA * 100) + "%");
        total = (subtotal * IVA) + subtotal;
        $("#total").empty().html((total).toLocaleString(undefined, { minimumFractionDigits: 2 }) + " COP");
    }
}
/*
 * cambia los valores dependiendo del iva
 */
var valores_sin_iva = function (id_cli_prov) {
    $("#iva_no").change(function (e) {
        e.preventDefault();
        var iva = document.getElementById("iva_no");
        if (iva.checked == true) {
            var subtotal = $("#subtotal").html()
            $("#iva").empty().html("0 %");
            $("#total").empty().html(subtotal);
        }
    });

    $("#iva_si").change(function (e) {
        e.preventDefault();
        var iva = document.getElementById("iva_si");
        if (iva.checked == true) {
            var storage = JSON.parse(localStorage.getItem('productos_pedido' + id_cli_prov));
            suma_items(storage);
        }

    });
}

/*
 *  funcion que elimina producto agregado al pedido cliente
 */
var elimina_items = function () {
    $("#tabla-item-pedido").on("click", "button.elimina_item", function (e) {
        e.preventDefault();
        var data = $("#tabla-item-pedido").DataTable().row($(this).parents("tr")).data();
        var id_cli_prov = $("#id_cliv_provP").val();
        var storage = JSON.parse(localStorage.getItem('productos_pedido' + id_cli_prov));
        storage.forEach(element => {
            if (element.id_clien_produc === data.id_clien_produc) {
                var index = storage.indexOf(element);
                if (index > -1) {
                    storage.splice(index, 1);
                }
            }
        });
        localStorage.setItem('productos_pedido' + id_cli_prov, JSON.stringify(storage));
        carga_tabla_productos(id_cli_prov);
    });
}


var crea_producto_nuevo = function (id_cli_prov) {
    $("#crea_producto").on('click', function (e) {
        e.preventDefault();
        var data = [{
            id_cli_prov: id_cli_prov,
            id_producto: $("#productos").val(),
            id_ruta_embobinado: $("#id_ruta_embobinado").val(),
            id_core: $("#id_core").val(),
            presentacion: $("#presentacion").val(),
            ficha_tecnica: 'N/A',
            moneda: $("#moneda").val(),
            precio_venta: $("#precio_venta").val(),
            moneda_autoriza: $("#moneda").val(),
            precio_autorizado: $("#precio_venta").val(),
            cantidad_minima: $("#cantidad_agrega").val(),
        }];
        var validar = false;
        data.forEach(element => {
            if (element.id_ruta_embobinado == 0) {
                alertify.error("El campo Ruta Embobinado es requerido.");
                validar = false;
                return;
            }
            if (element.id_core == 0) {
                alertify.error("El campo Core es requerido.");
                validar = false;
                return;
            }
            if (element.presentacion == 0) {
                alertify.error("El campo Rollos ó Paquetes Por es requerido.");
                validar = false;
                return;
            }
            if (element.moneda == 0) {
                alertify.error("El campo Moneda es requerido.");
                validar = false;
                return;
            }
            if (element.precio_venta == 0 || element.precio_venta == 0) {
                alertify.error("El campo Precio Venta es requerido.");
                validar = false;
                return;
            }
            if (element.cantidad_minima == 0 || element.cantidad_minima == '') {
                alertify.error("El campo Cantidad es requerido.");
                validar = false;
                return;
            }
            validar = true;
        });
        if (validar == true) {
            $.ajax({
                url: `${PATH_NAME}/facturacion/crea_cliente_producto_galan`,
                type: "POST",
                data: { data },
                success: function (res) {
                    if (!$(".select_product").is(":visible")) {
                        $(".select_product").toggle(500);
                    }

                    if (!$(".add_product").is(":visible")) {
                        $(".add_product").toggle(500);
                    }

                    if ($(".crea_producto").is(":visible")) {
                        $(".crea_producto").toggle(500);
                    }

                    $('#cantidad').val(res[0].cantidad_minima);
                    $("#add_producto").attr('data-product', JSON.stringify(res[0]));
                    $('#valor_venta').empty().html(res[0].precio_venta);
                    $('#valor_Autoriza').empty().html(res[0].precio_autorizado);
                    var productos_disp = '<option value="0">Seleccione un producto</option>';
                    res.forEach(element => {
                        productos_disp /*html*/ += `<option selected value='${JSON.stringify(element)}'>
                        ${element.codigo_producto} | ${element.descripcion_productos} | ${element.nombre_r_embobinado} | ${element.nombre_core} | ${element.presentacion} 
                        </option>`;
                    });
                    $('#id_clien_producPP').empty().html(productos_disp);
                }
            });
        }
    });
}


var crea_pedido_directo_galan = function () {
    $("#form_envia_pedido_directo").on('submit', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var exception = ['data_product', 'id_ruta_embobinado', 'id_core', 'presentacion', 'moneda', 'precio_venta', 'cantidad_minima', 'cantidad_agrega', 'observaciones'];
        var validar = validar_formulario(form, exception);
        if (validar) {
            var id_cliv_prov = $("#id_cliv_provP").val();
            var storage = JSON.parse(localStorage.getItem('productos_pedido' + id_cliv_prov));
            if (storage != null && storage.length != 0) { //valida que l local storage no este vacio o nulo
                $("#data_product").val(JSON.stringify(storage));
                var obj_inicial = $('#btn_crear_pedido').html();
                btn_procesando('btn_crear_pedido');
                form = $(this).serializeArray();
                $.ajax({
                    url: `${PATH_NAME}/facturacion/crear_pedido_directo`,
                    method: 'POST',
                    data: form,
                    success: function (res) {
                        if (res.status == 1) {
                            alertify.success(res.msg);
                            var lista_empaque = res.num_lista_empaque;
                            var totaliza = '';
                            alertify.confirm('Alerta SIDPA', 'Desea totalizar el documento?',
                                function () {
                                    totaliza = 1;
                                    generar_pdf_lista_empaque(lista_empaque, totaliza, id_cliv_prov);
                                },
                                function () {
                                    totaliza = 2;
                                    generar_pdf_lista_empaque(lista_empaque, totaliza, id_cliv_prov);

                                }).set('labels', { ok: 'Si', cancel: 'No' });
                        } else {
                            alertify.error(res.msg);
                            btn_procesando('btn_crear_pedido', obj_inicial, 1);
                        }
                    }
                });
            } else {
                alertify.error(`¡¡No hay productos agregados!!`);
            }
        }
    });
}

var generar_pdf_lista_empaque = function (lista_empaque, totaliza, id_cliv_prov) {
    $.ajax({
        url: `${PATH_NAME}/configuracion/generar_pdf_lista_empaque`,
        type: 'POST',
        data: { lista_empaque, totaliza },
        xhrFields: {
            responseType: 'blob'
        },
        success: function (regreso) {
            var a = document.createElement('a');
            var url = window.URL.createObjectURL(regreso);
            a.href = url;
            a.download = lista_empaque + '_lista_empaque.pdf';
            a.click();
            window.URL.revokeObjectURL(url);
            localStorage.removeItem('productos_pedido' + id_cliv_prov);
            location.reload();
        }
    });
}

var consecutivo_documento = function (empresa) {
    var dato_consulta = 9;
    if (empresa == 1) {
        dato_consulta = 8;
    }
    $.ajax({
        url: `${PATH_NAME}/facturacion/consecutivo_documento`,
        type: "POST",
        data: { dato_consulta },
        success: function (res) {
            var option = '';
            var prefijo = res[0].prefijo;
            if (res[0].id_consecutivo == 8) {
                option = `<option value="8">Factura</option>`;
            } else {
                option = `<option value="9">Factura</option>`;
            }
            $('#remision_factura').empty().html(option);
            $("#numero_factura").empty().html(`${prefijo} ${res[0].numero_guardado}`);
            $("#numero_factura_consulta").val(res[0].numero_guardado);
        }
    });
}

$(document).ready(function () {
    carga_tabla_clientes_asesor();
    crear_direccion_cliente();
    valida_correo_cliente();
    ValidaCelular();
    regresa_clientes();
    crear_pro_cli();
    select_2();
    elimina_espacio('precio_ventaM', 'span_precio_ventaM');
    consulta_por_tipo_producto();
    diferencia();
    agrega_productos_storage();
    carga_datos_productos(); //funcion para agregar filas a la tabla de productos.
    crear_pedido();
    remueve_caracteres('observaciones'); // ejecuta remover caracteres de (constantes.js) para texarea observaciones del pedido
    $(".datepicker").datepicker();
    sin_puntos('cantidad');
    elimina_items(); // funcion que elimina producto agregado al pedido cliente
    crear_producto_cli(); /*mostrar la informacion del producto*/
    modificar_p_cliente(); /*modificar producto  cliente*/
    carga_dir_nueva();
    modi_pais_dep(); // carga los departamentos y ciudades segun lo elejido.


});

var carga_dir_nueva = function () {
    $(".crea_dir").on('click', function () {
        carga_ubi_paises(); // carga los departamentos y ciudades segun lo elejido.

    });
}
/*
 * SIGUIENTES DOS FUNCIONES INHABILITAN 20 DIAS LA FECHA DE COMPROMISO PROGRAMADO
 * PARA ELLOS SE UTILIZO LA LIBRERIA DATAEPIKER.JS Y MOMENT.JS 
 */
$("#fecha_compro_programado").datepicker({
    beforeShowDay
});

function beforeShowDay(date) {
    let disabledDates = [];
    if (!disabledDates.length) {
        disabledDates = getDatesBetween();
    }
    var currentDate = moment(date).format('YYYY-MM-DD');
    return [disabledDates.indexOf(currentDate.toString()) == -1];
}


function getDatesBetween() {
    var now = moment();
    var start = moment(now, 'YYYY-MM-DD');
    var end = moment(now, 'YYYY-MM-DD');
    end.add(21, 'days');
    var dates = [];
    while (start < end) {
        dates.push(start.format('YYYY-MM-DD').toString());
        start.add(1, 'days');
    }
    return dates;
}

/*
 * CARGA DE TABLA DE CLIENTES POR ASESOR
 */
var carga_tabla_clientes_asesor = function () {
    /*  listar clientes */
    var table = $("#dt_mis_clientes").DataTable({
        "order": [
            [0, "desc"]
        ],
        "ajax": `${PATH_NAME}/comercial/consutlar_mis_clientes`,
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
        "columns": [

            { "data": "id_cli_prov" },
            { "data": "nit" },
            { "data": "nombre_empresa" },
            {
                "data": "pertenece",
                "render": function (data, type, row) {

                    if (row["pertenece"] == 0) {
                        return 'SIN ASIGNAR';
                    }
                    if (row["pertenece"] == 1) {
                        return 'ACOBARRAS S.A.S';
                    }
                    if (row["pertenece"] == 2) {
                        return 'ACOBARRAS COLOMBIA';
                    }
                    if (row["pertenece"] == 3) {
                        return 'ACOBARRAS ESPECIAL';
                    }
                }

            },
            {
                "render": function (data, type, row) {
                    var res = '';
                    if (INVENTARIO == 1) {
                        res = `<center>\n\
                        <button type='button' class='btn btn-success btn-circle  rounded-circle ver_crear_pedidos_cli' title='Nuevo Pedido' id='crear_pedidos_cli${row.id_cli_prov}'><span class='fa fa-list-alt'></span></button>\n\
                        <center>`;
                    }
                    return res;
                }
            },
            {
                "class": "detalles",
                "orderable": false,
                "data": null,
                "searchable": false,
                "defaultContent": "<center><button type='button' class='btn btn-danger btn-circle  rounded-circle' title='Ver dirección'><span class='fa fa-map-marker-alt'></button></center>"
            },
            {
                "orderable": false,
                "defaultContent": "<center>\n\
                                <button type='button' class='btn btn-primary btn-circle  rounded-circle ver_product' title='Ver producto'> <span class='fa fa-cart-arrow-down'></span></button>\n\
                                <center>"
            }

        ],
    });
    ver_direccion_cliente('#dt_mis_clientes tbody', table);
    obtener_data_pedi(table); /*obtener informacion de cliente*/
    ver_productos_cliente('#dt_mis_clientes tbody', table);
    /*obtener los datos de direcciones y enviarlos a la funcion obtener_data_dir*/
    obtener_data_dir("#dt_mis_clientes tbody", table);

};
var detailProd = [];

var tabla_ver_producto = function (data) {
    var respu = /*html*/ ` <div class="container-fluid">
    <h3>${data.nombre_empresa}</h3>
    <button type='button' style='color:#ffff' class='btn btn-warning  crear_product_cli' info-p='${JSON.stringify(data)}' data-bs-toggle='modal' data-bs-target='#ModalCREARPRODUCTO'><i class='fa fa-plus'> Crear Producto</i></button>
    <br><br>
    <table class="table-bordered table table-responsive-lg table-responsive-md " cellspacing="0" width="100%" id="dt_ver_producto${data.id_cli_prov}">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Ruta</th>
                <th>Core</th>
                <th>Roll Paq X</th>
                <th>Moneda</th>
                <th>Precio Venta</th>
                <th>Moneda Autorizada</th>
                <th>Precio Autorizado</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <br><br><br>
</div>`;
    return respu;
}

var ver_productos_cliente = function (tbody, table) {
    /* ver productos  al hacer click en el boton azul*/
    $(tbody).on('click', 'button.ver_product', function (e) {
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
            var tabla_ver_p = $(`#dt_ver_producto${data.id_cli_prov}`).DataTable({
                "ajax": `${PATH_NAME}/comercial/consultar_productos_clientes?id=${data.id_cli_prov}`,
                "columns": [
                    { "data": "id_clien_produc" },
                    { "data": "codigo_producto" },
                    { "data": "descripcion_productos" },
                    { "data": "nombre_r_embobinado" },
                    { "data": "nombre_core" },
                    { "data": "presentacion", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                    { "data": "nom_mon_venta" },
                    { "data": "precio_venta", render: $.fn.dataTable.render.number('.', ',', 2, '$') },
                    { "data": "nom_mon_autoriza" },
                    { "data": "precio_autorizado", render: $.fn.dataTable.render.number('.', ',', 2, '$') },
                    {
                        "orderable": false,
                        "defaultContent": "<center>\n\
                        <button type='button' class='btn btn-success btn-sm modificar_pro_cli' data-bs-toggle='modal' data-bs-target='#ModalMODIFICARPRODUCTO'> <span class='fa fa-edit'></span></button>\n\
                        <center>"
                    },
                    {
                        "orderable": false,
                        "defaultContent": "<center>\n\
                        <button type='button' class='btn btn-danger btn-sm eliminar_pro_cli'> <span class='fa fa-times'></span></button>\n\
                        <center>"
                    }
                ],
            });
            if (idx === -1) {
                detailProd.push(tr.attr('id'));
            }
            /*cargar funciones*/
            modificar_pro_cli(`#dt_ver_producto${data.id_cli_prov}`, tabla_ver_p);
            eliminar_pro_cli(`#dt_ver_producto${data.id_cli_prov}`, tabla_ver_p);

        }
    });
}



var detailRows = [];

var ver_direccion_cliente = function (tbody, table) {
    /*crear array detalles de fila  */
    /*mostrar detalles de direcciones al presionar el icono rojo */
    $(tbody).on('click', 'tr td.detalles', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var idx = $.inArray(tr.attr('id'), detailRows);

        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();

            // Eliminar de la matriz 'abierta'
            detailRows.splice(idx, 1);
        } else {
            tr.addClass('details');
            var data = table.row($(this).parents("tr")).data();
            var id_cliv_prov = data.id_cli_prov;
            var TT = $.ajax({
                type: "POST",
                url: `${PATH_NAME}/comercial/consultar_direccion_cliente`,
                data: { id_cliv_prov },
                success: function (r) {
                    row.child(items_direccion(r)).show("slow");
                    tr.addClass('details');
                    // agregar de la matriz 'abierta' 
                    if (idx === -1) {
                        detailRows.push(tr.attr('id'));
                    }
                    /*cargar la funcion  modificar_dir_cliente --modificacion de direcciones*/
                    modificar_dir_cliente(table);


                }
            });
        }

    });

}
var items_direccion = function (r) {
    var items = "<div class='row'>";
    for (var i = 0; i < r.length; i++) {
        items += '<div class="col-lg-6" style="border: 1px solid #dddddd">';
        items += '<h4 style="text-align:center">Dirección</h4>';
        items += '<strong>Dirección : </strong>' + r[i].direccion + '<br>';
        items += '<strong>Ciudad : </strong>' + r[i].nombre + '<br>';
        items += '<strong>Teléfono : </strong>' + r[i].telefono + '<br>';
        items += '<strong>Contacto : </strong>' + r[i].contacto + '<br>';
        items += '<strong>Horario : </strong>' + r[i].horario + '<br>';
        items += "<button class='btn btn-link modificar_direccion_cli' info='" + (JSON.stringify(r[i])) + "' data-bs-toggle='modal' data-bs-target='#ModalDIR'><i class='text-primary fa fa-pencil-alt'></i></button>";
        items += `<button class = "btn btn-link btn-lg eliminar_direccion_cli" info='${JSON.stringify(r[i])}'><i class="text-danger fa fa-times-circle"></i></button>`;
        items += '</div>';

    }
    items += '</div>';
    return items;
}

/*
 * funcion para cargar los departamentos segun el pais y las ciudades segun el departamento
 */
var carga_ubi_paises = function () {
    $('#id_pais').change(function () {
        var pais = $(this).val();
        recargarListadep(pais, 'id_departamento');
        $("#id_departamento").val(0).trigger('change');
    });
    $('#id_departamento').change(function () {
        var dep = $(this).val();
        recargarListaCiud(dep, 'id_ciudad');
        $("#id_ciudad").val(0).trigger('change');

    });
}
var carga_ubi_paises_modifi = function (pais, departamento, ciudad) {
    var paises = JSON.parse($('#select_paises').val());
    var item = '<option value="0">seleccione</option>'
    paises.forEach(element => {
        if (element.id_pais == pais) {
            item += /*html*/
                ` <option value="${element.id_pais}" selected>${element.nombre}</option>`;
        } else {
            item += /*html*/
                ` <option value="${element.id_pais}">${element.nombre}</option>`;
        }
        $("#id_paisD").empty().html(item);

    });
    recargarListadep(pais, 'id_departamentoD', departamento);
    recargarListaCiud(departamento, 'id_ciudadD', ciudad);

}


var modi_pais_dep = function () {
    $('#id_paisD').change(function () {
        var paisM = $(this).val();
        recargarListadep(paisM, 'id_departamentoD');
        $("#id_departamentoD").val(0).trigger('change');
        $("#id_ciudadD").val(0).trigger('change');
    });
    $('#id_departamentoD').change(function () {
        var depM = $(this).val();
        recargarListaCiud(depM, 'id_ciudadD');
        $("#id_ciudadD").val(0).trigger('change');
    });

}
/*
 * funcion para validar campo correo en formilario de crear direccion 
 */
var valida_correo_cliente = function () {
    $('#email').on('blur', function () {
        var correo = $(this).val();
        var respu = ValidarMail(correo);
        if (!respu) {
            $('#email').focus();
        }
    });
}
/*
 * funcion para validar campo telefono en formilario de crear direccion 
 */
var ValidaCelular = function () {
    $('#celular').on('keyup', function () {
        ValidarCelular('celular');
    });
};
/*
 * funcion para crearle una direccion a un cliente
 */
var crear_direccion_cliente = function () {
    $("#form_crear_dir_cli").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_dir_cli').html();
        btn_procesando('crear_dir_cli');
        var form = $("#form_crear_dir_cli").serializeArray();
        var valida_form = validar_formulario(form);
        if (valida_form) {
            $.ajax({
                url: `${PATH_NAME}/comercial/crear_dir_clientes`,
                type: "POST",
                data: form,
                success: function (res) {
                    if (res.status) {
                        carga_direcciones();
                        $("#ModalCLI").modal('hide');
                        $("#form_crear_dir_cli")[0].reset();
                        alertify.success(`Datos ingresados corretamente la posicion insertada es ${res.id}`);
                        btn_procesando('crear_dir_cli', obj_inicial, 1);
                        limpiar_formulario('form_crear_dir_cli', 'select');
                    } else {
                        alertify.error(`Error al insertar`);
                        btn_procesando('crear_dir_cli', obj_inicial, 1);
                    }
                }
            });
        } else {
            btn_procesando('crear_dir_cli', obj_inicial, 1);
        }

    });
}

/*
 * obtener los datos de direcciones del cliente seleccionado y cargarlos en el modal modificar direcciones 
 */
var obtener_data_dir = function (tbody, table) {
    $(tbody).on("click", "button.modificar_direccion_cli", function () {
        var info = JSON.parse($(this).attr('info'));
        $("#CLIENTE").html(info.nombre_empresa);
        $("#direccionD").val(info.direccion);
        $("#telefonoD").val(info.telefono);
        $("#celularD").val(info.celular);
        $("#emailD").val(info.email);
        $("#contactoD").val(info.contacto);
        // $("#id_departamentoD").val(info.id_departamento).trigger('change');
        $("#cargoD").val(info.cargo);
        $("#horarioD").val(info.horario);
        $("#id_direccion").val(info.id_direccion);
        $("#rutaD").val(info.ruta).trigger('change');
        $("#link_mapsD").val(info.link_maps);
        // $("#id_ciudadD").val(info.id_ciudad).trigger('change');
        carga_ubi_paises_modifi(info.id_pais, info.id_departamento, info.id_ciudad);


    });

    $(tbody).on("click", "button.eliminar_direccion_cli", function () {
        var info = JSON.parse($(this).attr('info'));
        alertify.confirm('ALERTA ACOBARRAS', '¿Esta seguro de eliminar esta dirección?', function () {
            $.ajax({
                "url": `${PATH_NAME}/comercial/modificar_estado_dir`,
                "type": 'POST',
                "data": { "id_direccion": info.id_direccion, "estado_direccion": '0' },
                success: function (res) {
                    if (res) {
                        alertify.success('¡¡Se elimino correctamente!!')
                        table.ajax.reload(function () { });
                    } else {
                        alertify.error(`Error al eliminar `);
                    }
                }
            });
        }, function () { alertify.error('Cancelado') });
    });
};

/*
 * modificar las direcciones del cliente seleccionado
 */
var modificar_dir_cliente = function (table) {
    $("#form_modificar_direccion_cli").submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        var obj_inicial = $('#modificar_dir_cliente').html();
        btn_procesando('modificar_dir_cliente');
        if (valida) {
            $.ajax({
                url: `${PATH_NAME}/comercial/modificar_direccion_cliente`,
                type: "POST",
                data: form,
                success: function (res) {
                    if (res) {
                        alertify.success(`¡¡Datos modificados corretamente!!`);
                        btn_procesando('modificar_dir_cliente', obj_inicial, 1);
                        limpiar_formulario('form_modificar_direccion_cli', 'select');
                        $(".cerrar").click();
                        $("#form_modificar_direccion_cli")[0].reset();
                        table.ajax.reload(function () { });
                    } else {
                        alertify.error(`Error al modificar`);
                    }
                },
            });
        }
    });
};
/*
 * cargar los datos para crear productos de cliente al presionar el boton crear producto
 */
var crear_producto_cli = function () {
    $("#dt_mis_clientes tbody").on("click", "button.crear_product_cli", function () {
        var info_p = JSON.parse($(this).attr('info-p'));
        $("#id_cli_provC").val(info_p.id_cli_prov);
        $('#NE').empty().html(info_p.nombre_empresa);
    });
};
/*
 * funcion que consulta los productos en creacion de producto dependiendo de el tipo de producto que se elija.
 */
var consulta_por_tipo_producto = function () {
    $("#id_tipo_producto").change(function (e) {
        e.preventDefault();
        var tipo_articulo = $(this).val();
        $('.div_cod_product').empty().html('<div class="d-flex justify-content-center mt-1"><div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        if ($('#id_producto').val() != 0) {
            $('.div_cod_product').empty().html('<div class="d-flex justify-content-center mt-1"><div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }
        $.ajax({
            url: `${PATH_NAME}/comercial/productos_por_tipo_producto`,
            type: "POST",
            data: { tipo_articulo },
            success: function (res) {
                var productos = '<option value="0">Elije Un Producto</option>';
                res.forEach(element => {
                    productos += /*html*/ `<option value="${element.id_productos}"> ${element.codigo_producto} | ${element.descripcion_productos}</option>
                `;
                });
                $('.div_cod_product').empty().html('<div class="form-group"><label for="id_producto" class="col-form-label">Código Producto:</label><select class="form-select select_2" style="width: 100%" id="id_producto" name="id_producto"></select></div>');
                $('#id_producto').empty().html(productos);
                $('#id_producto').select2();
            }
        });
    });

}

/*
 * crear productos de cliente seleccionado (boton amarillo)
 */
var crear_pro_cli = function () {
    $("#form_crear_producto_cli").submit(function (e) {
        e.preventDefault();
        var form1 = $(this).serializeArray();
        var valida = validar_formulario(form1);
        var obj_inicial = $('#crear_pro_cli').html();
        var envio = 1;
        if (valida) {
            btn_procesando('crear_pro_cli');
            var form = $(this).serialize();
            $.ajax({
                url: `${PATH_NAME}/comercial/crear_producto_clientes`,
                type: 'POST',
                data: { form, envio },
                success: function (res) {
                    if (res.status == true) {
                        var id_cli = $("#id_cli_provC").val();
                        btn_procesando('crear_pro_cli', obj_inicial, 1);
                        alertify.success("Registro Exitoso!!");
                        limpiar_formulario('form_crear_producto_cli', 'select');
                        $("#form_crear_producto_cli")[0].reset();
                        $(".cerrar").click();
                        $("#dt_ver_producto" + form1[0].value).DataTable().ajax.reload();
                        $("#id_cli_provC").val(id_cli);
                    } else {
                        alertify.confirm('Precio venta', 'El precio de venta es inferior al precio de aprobación, desea continuar para la espera de la aprobación del precio por parte del área de compras.',
                            function () {
                                envio = 2;
                                $.ajax({
                                    url: `${PATH_NAME}/comercial/crear_producto_clientes`,
                                    type: 'POST',
                                    data: { form, envio },
                                    success: function (res) {
                                        if (res.status == true) {
                                            var id_cli = $("#id_cli_provC").val();
                                            btn_procesando('crear_pro_cli', obj_inicial, 1);
                                            alertify.success("Registro Exitoso!!");
                                            limpiar_formulario('form_crear_producto_cli', 'select');
                                            $("#form_crear_producto_cli")[0].reset();
                                            $(".cerrar").click();
                                            $("#dt_ver_producto" + form1[0].value).DataTable().ajax.reload();
                                            $("#id_cli_provC").val(id_cli);
                                        }
                                    }
                                });
                            },
                            function () {
                                btn_procesando('crear_pro_cli', obj_inicial, 1);
                                alertify.error("Precio Venta No aprobado el producto no fue creado.");
                            }
                        ).setting({
                            'labels': { ok: 'Continuar', cancel: 'Cancelar' },
                            'invokeOnCloseOff': true,
                        });
                    }
                }
            });
        }
    });
};

/*
 *  cargar datos para modificar los productos del cliente seleccionado
 */
var modificar_pro_cli = function (tbody, table_ver_p) {
    solo_numeros_coma('precio_ventaM');
    $(tbody).on("click", "button.modificar_pro_cli", function () {
        var datap = table_ver_p.row($(this).parents("tr")).data();
        $('#modificar_pro_client').attr('disabled', true);
        var tipo_articulo = datap.id_clase_articulo;
        $('.div_cod_productM').empty().html('<div class="d-flex justify-content-center mt-1"><div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        if ($('#id_productoM').val() != 0) {
            $('.div_cod_productM').empty().html('<div class="d-flex justify-content-center mt-1"><div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }
        $.ajax({
            url: `${PATH_NAME}/comercial/productos_por_tipo_producto`,
            type: "POST",
            data: { tipo_articulo },
            success: function (res) {
                var productos = '<option value="0">Elije Un Producto</option>';
                res.forEach(element => {
                    productos += /*html*/ `
                    <option value = "${element.id_productos}" > ${element.codigo_producto} | ${element.descripcion_productos} </option>`;
                });
                $('.div_cod_productM').empty().html('<div class="form-group"><label for="id_producto" class="col-form-label">Código Producto:</label><select class="form-control select_2" style="width: 100%" id="id_productoM" name="id_producto"></select></div>');
                $('#id_productoM').empty().html(productos);
                $(`#id_productoM `).val(datap.id_producto).trigger('change');
                $(`#id_productoMM `).val(datap.id_producto);
                $('#id_productoM').select2();
                $(`#id_productoM `).attr('disabled', 'true');
                $('#modificar_pro_client').attr('disabled', false);

            }
        });
        $('#MP').empty().html(datap.nombre_empresa);
        $("#id_clien_producM").val(datap.id_clien_produc);
        $("#id_cli_provM").val(datap.id_cli_prov);
        $("#id_clase_articuloM").val(datap.id_clase_articulo);
        $(`#id_tipo_productoM `).val(datap.id_clase_articulo).trigger('change');
        $(`#id_tipo_productoM `).attr('disabled', 'true');
        $(`#id_ruta_embobinadoM `).val(datap.id_ruta_embobinado).trigger('change');
        $(`#id_coreM `).val(datap.id_core).trigger('change');
        $(`#monedaM `).val(datap.moneda).trigger('change');
        $("#presentacionM").val(datap.presentacion);
        $("#ficha_tecnicaM").val(datap.ficha_tecnica);
        $("#precio_ventaM").val($.fn.dataTable.render.number('', ',', 2, '').display(datap.precio_venta));
        $("#precio_autorizado").val(datap.precio_autorizado);
        $("#moneda_autoriza").val(datap.moneda_autoriza);
        $("#cantidad_minimaM").val(datap.cantidad_minima);
        if (datap.cantidad_minima == 0) {
            $("#cambio_cantidad").css('display', '');
        } else {
            $("#cambio_cantidad").css('display', 'none');
        }
    });
};
/*
 * funcion para validar que al tratar de modificar el precio de un producto de un cliente no sea menor al precio autorizado dependiendo de las monedas. 
 */
var valida_precio_vent = function (precioA, monedaA) {
    var precioM = $("#precio_ventaM").val();
    if (precioM != 0) {
        precioM = precioM.replace(/\,/g, '.');
    }
    var monedaM = $("#monedaM").val();
    var trm = $("#trm").val();
    var respu = true;
    if (monedaM == monedaA) {
        if (parseFloat(precioM) < parseFloat(precioA)) {
            alertify.error("¡¡No puede modificar por un precio menor al autorizado!!");
            $("#span_precio_ventaM").empty().html("precio autorizado $" + precioA);
            var respu = false;
        } else if (precioM == '') {
            alertify.error("¡¡Este campo no puede ir vacio!!");
            var respu = false;
        } else {
            respu = true;
        }
        return respu;
    } else {
        if (monedaA == 2 && monedaM == 1) {
            new_precio = parseFloat(precioA) * parseFloat(trm);
            if (parseFloat(precioM) < parseFloat(new_precio)) {
                alertify.error("¡¡No puede modificar por un precio menor al autorizado!!");
                $("#span_precio_ventaM").empty().html("precio autorizado $" + precioA + " Dolares");
                var respu = false;
            } else if (precioM == '') {
                alertify.error("¡¡Este campo no puede ir vacio!!");
                var respu = false;
            } else {
                respu = true;
            }
            return respu;

        } else {
            new_precio = parseFloat(precioM) * parseFloat(trm);
            if (parseFloat(new_precio) < parseFloat(precioA)) {
                alertify.error("¡¡No puede modificar por un precio menor al autorizado!!");
                $("#span_precio_ventaM").empty().html("precio autorizado $" + precioA + " COP");
                var respu = false;
            } else if (precioM == '') {
                alertify.error("¡¡Este campo no puede ir vacio!!");
                var respu = false;
            } else {
                respu = true;
            }
            return respu;
        }


    }
}

/*
 * modificar productos al presionar el icono verde de modificar 
 */
var modificar_p_cliente = function (table) {
    $("#form_modificar_producto_cli").submit(function (e) {
        e.preventDefault();
        var form1 = $(this).serializeArray();
        var valida = validar_formulario(form1);
        var envio = 1;
        var obj_inicial = $('#modificar_pro_client').html();
        btn_procesando('modificar_pro_client');
        if (valida) {
            var valida_precio = valida_precio_vent($('#precio_autorizado').val(), $("#moneda_autoriza").val());
            if (valida_precio) {
                var form = $(this).serialize();
                $.ajax({
                    url: `${PATH_NAME}/comercial/modificar_producto_clientes`,
                    type: 'POST',
                    data: { form, envio },
                    success: function (res) {
                        if (res) {
                            alertify.success("Modificación Exitosa!!");
                            limpiar_formulario('form_modificar_producto_cli', 'select');
                            $("#form_modificar_producto_cli")[0].reset();
                            btn_procesando('modificar_pro_client', obj_inicial, 1);
                            $("#ModalMODIFICARPRODUCTO").modal("hide");
                            $('#dt_ver_producto' + form1[1].value).DataTable().ajax.reload();
                        } else {
                            alertify.confirm('Precio venta', 'El precio de venta es inferior al precio de aprobación, desea continuar para la espera de la aprobación del precio por parte del área de compras.',
                                function () {
                                    envio = 2;
                                    $.ajax({
                                        url: `${PATH_NAME}/comercial/modificar_producto_clientes`,
                                        type: 'POST',
                                        data: { form, envio },
                                        success: function (res) {
                                            alertify.success("Modificación Exitosa!!");
                                            limpiar_formulario('form_modificar_producto_cli', 'select');
                                            $("#form_modificar_producto_cli")[0].reset();
                                            btn_procesando('modificar_pro_client', obj_inicial, 1);
                                            $("#ModalMODIFICARPRODUCTO").modal("hide");
                                            $('#dt_ver_producto' + form1[1].value).DataTable().ajax.reload();
                                        }
                                    });
                                },
                                function () {
                                    btn_procesando('crear_pro_cli', obj_inicial, 1);
                                    alertify.error("Precio Venta No aprobado el producto no fue creado.");
                                }
                            ).setting({
                                'labels': { ok: 'Continuar', cancel: 'Cancelar' },
                                'invokeOnCloseOff': true,
                            });
                        }
                    }
                });
            } else {
                btn_procesando('modificar_pro_client', obj_inicial, 1);
            }
        } else {
            btn_procesando('modificar_pro_client', obj_inicial, 1);
        }
    });
};

/*
 *  funcion para cambia estado de producto (boton rojo) tabla productos 
 */
var eliminar_pro_cli = function (tbody, table_ver_p) {
    $(tbody).on("click", "button.eliminar_pro_cli", function () {
        var datap = table_ver_p.row($(this).parents("tr")).data();
        alertify.confirm('ALERTA ACOBARRAS', '¿Esta seguro de eliminar el producto: <strong>' + datap.codigo_producto + '</strong> ?', function () {
            $.ajax({
                url: `${PATH_NAME}/comercial/cambiar_estado_pro_cli`,
                type: 'POST',
                data: { id_clien_produc: datap.id_clien_produc },
                success: function (res) {
                    if (res) {
                        alertify.success('¡¡Se elimino correctamente!!')
                        table_ver_p.ajax.reload(function () { });
                    } else {
                        alertify.error(`Error al eliminar `);
                    }
                }
            });
        }, function () { alertify.error('Cancelado') })
    });
};

/*
 * obtener los datos necesarios para crear pedidos del cliente (BOTON VERDE)
 */
var obtener_data_pedi = function (table) {
    $("#dt_mis_clientes tbody").on("click", "button.ver_crear_pedidos_cli", function () {
        var data = table.row($(this).parents("tr")).data();
        inicio_portafolio(data);
    });

};
var paso_crea_pedido = function (respu, data) {
    if (respu) {
        $(".NuevoPEDIDO").css('display', '');
        $(".tab_mis_clientes").toggle(500);
        $("#nombre_cliente").empty().html(data.nombre_empresa);
        $("#nit_cliente").empty().html(data.nit);
        $("#num_nit_cliente").val(data.nit);
        $("#id_cliv_provP").val(data.id_cli_prov);
        carga_direcciones(); //carga de direcciones despues de darle valor a "id_cliv_provP";
        // carga_ubi_paises(); // carga los departamentos y ciudades segun lo elejido.
        //se pasa este id para crear un nuevo producto
        $("#id_cli_provC").val(data.id_cli_prov);
        productos_disponibles_cli(data.id_cli_prov);
        carga_tabla_productos(data.id_cli_prov); //funcion que muestra productos agregados a un pedido cliente
        valores_sin_iva(data.id_cli_prov);
    }
}

/*
 *  funcion de hacer pedido regresar a tabla clientes (boton verde) 
 */
var regresa_clientes = function () {
    $(".regresa_clientes").click(function (e) {
        e.preventDefault();
        $(".NuevoPEDIDO").toggle(500);
        $(".tab_mis_clientes").toggle(500);
        $("#form_crear_pedido")[0].reset();
        limpiar_formulario('form_crear_pedido', 'select');
        if ($(".dir_entrega").is(":visible")) {
            $(".dir_entrega").toggle(500);
        }
    });
}

/*
 *  funcion que  carga de direcciones de cliente por asesor
 */
var carga_direcciones = function () {
    var id_cliv_prov = $("#id_cliv_provP").val();
    $.ajax({
        url: `${PATH_NAME}/comercial/consultar_direccion_cliente`,
        type: "POST",
        data: { id_cliv_prov },
        success: function (res) {
            var direcciones = '<option value="0">Elije una Dirección</option>';
            res.forEach(element => {
                direcciones += /*html*/ `
                <option info-dir='${JSON.stringify(element)}' value="${element.id_direccion}">${element.direccion}</option>
                `;
            });
            $('#id_direccionC').empty().html(direcciones);
            $('#id_direccionCC').empty().html(direcciones);
        }
    });
}

/*
 *  funcion de crear un nuevo pedido (boton Azul "crear pedido") 
 */
var crear_pedido = function () {
    $("#check_oc").change(function (e) {
        e.preventDefault();
        //valida si hay orden de compra 
        var check_oc = document.getElementById("check_oc");
        if (check_oc.checked == true) {
            $("#orden_compra").attr("disabled", false);
            $("#PDF_compra").attr("disabled", false);
            $("#span_orden_compra").empty().html("Si");
        } else {
            $("#orden_compra").attr("disabled", true);
            $("#PDF_compra").attr("disabled", true);
            $("#orden_compra").val("");
            $("#span_orden_compra").empty().html("NO");
        }
    });
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

    $("#PDF_compra").on('change', function (e) {
        e.preventDefault();
        // aqui se valida el pdf
        var ext = $(this).val().split('.').pop();
        if (ext == "pdf" || ext == "PDF") {
            var imgsize = this.files[0].size;
            if (imgsize > 2000000) {
                alertify.error("El documento excede el tamaño máximo");
                $('#m1').empty().html("Se solicita un archivo no mayor a 2MB. Por favor verifica.");
                $(this).val(null);
            } else {
                $('#m1').empty().html("");
            }
        } else {
            alertify.error("Solo se admiten archivos PDF");
            $('#m1').empty().html("");
            $(this).val(null);
        }
    });
    $("#form_crear_pedido").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#btn_crear_pedido').html();
        btn_procesando('btn_crear_pedido');
        var form = $(this).serializeArray();
        var exception = ['porcentaje', 'observaciones', 'data_product', 'fecha_compromiso'];
        var valida = validar_formulario(form, exception);
        if (valida) { // validacion general  del formulario de pedido
            var porcentaje = $("#porcentaje").val();
            if (porcentaje != '') { // valida que la diferencia en porcentaje no este vacia
                var pfd = $("#PDF_compra").val();
                if (pfd != '' || $("#PDF_compra").is(":disabled")) { // valida que el input file del PDF no este habilidatado y/o vacio
                    var id_cliv_prov = $("#id_cliv_provP").val();
                    var nom_pdf = $("#orden_compra").val();
                    if (nom_pdf != '') {
                        $.ajax({
                            url: `${PATH_NAME}/comercial/valida_nombre_pdf_oc`,
                            type: "POST",
                            data: { nom_pdf, id_cliv_prov },
                            success: function (res) {
                                if (res != '') {
                                    btn_procesando('btn_crear_pedido', obj_inicial, 1);
                                    alertify.error("Esta orden de compra ya fue utilizada para este cliente con el pedido: " + res[0].num_pedido);
                                } else {
                                    var storage = JSON.parse(localStorage.getItem('productos_pedido' + id_cliv_prov));
                                    if (storage != null && storage.length != 0) { //valida que l local storage no este vacio o nulo 
                                        $("#data_product").val(JSON.stringify(storage));
                                        var form1 = document.getElementById('form_crear_pedido');
                                        envio_nuevo_pedido(form1, storage);
                                    } else {
                                        btn_procesando('btn_crear_pedido', obj_inicial, 1);
                                        alertify.error(`¡¡No hay productos agregados!!`);
                                    }
                                }
                            }
                        });
                    } else {
                        var storage = JSON.parse(localStorage.getItem('productos_pedido' + id_cliv_prov));
                        if (storage != null && storage.length != 0) { //valida que l local storage no este vacio o nulo 
                            $("#data_product").val(JSON.stringify(storage));
                            var form1 = document.getElementById('form_crear_pedido');
                            envio_nuevo_pedido(form1, storage);
                        } else {
                            alertify.error(`¡¡No hay productos agregados!!`);
                            btn_procesando('btn_crear_pedido', obj_inicial, 1);
                        }
                    }
                } else {
                    alertify.error(`El PDF esta vacio.`);
                    btn_procesando('btn_crear_pedido', obj_inicial, 1);
                }
            } else {
                alertify.error(`El campo Diferencia es requerido.`);
                btn_procesando('btn_crear_pedido', obj_inicial, 1);
            }
        } else {
            btn_procesando('btn_crear_pedido', obj_inicial, 1);
        }
    });
}


/*
 * si el nombre de orden de compra ya existe para ese cliente
 */

var valida_nombre_pdf_oc = function (id_cliv_prov) {

}

/*
 * validar si tiene diferencia %
 */
var diferencia = function () {
    $("#parcial").change(function () {
        var check_oc = document.getElementById("parcial");
        if (check_oc.checked == true) {
            $("#span_parcial").empty().html("Si");
        } else {
            $("#span_parcial").empty().html("No");
        }
    });

    $("#porcentaje").keyup(function () {
        var porcentaje = $(this).val();
        if (porcentaje == 0) {
            $("#difer_ext").prop("checked", true);
            $("#difer_ext").prop("disabled", false);
            /*deshabilitar mas*/
            $("#difer_mas").prop("disabled", true);
            $("#difer_mas").prop("checked", false);

            /*desahibilitar menos*/
            $("#difer_menos").prop("disabled", true);
            $("#difer_menos").prop("checked", false);

        } else {
            /*deshabilitar exacto*/
            $("#difer_ext").prop('disabled', true);
            $("#difer_ext").prop("checked", false);


            /*habilitar mas*/
            $("#difer_mas").prop("disabled", false);
            $("#difer_mas").prop("checked", true);

            /*habilitar menos*/
            $("#difer_menos").prop("disabled", false);
            $("#difer_menos").prop("checked", true);
        }
        if (porcentaje == "") {
            $("#difer_ext").prop("checked", false);
            $("#difer_mas").prop("checked", false);
            $("#difer_menos").prop("checked", false);
        }

    });
};

/*
 *  funcion que carga los productos por cliente al select
 */
var productos_disponibles_cli = function (id_cli_prov) {
    $.ajax({
        "url": `${PATH_NAME}/comercial/consultar_productos_clientes?id=${id_cli_prov}`,
        success: function (res) {
            var data = res['data'];
            var productos_disp = '<option value="0">Elije un producto de este cliente</option>';
            if (data.id_clien_produc == 0) {
                productos_disp = `<option value="0">${data.descripcion_productos}</option>`;
            } else {
                data.forEach(element => {
                    var visible = '';
                    if (element.estado_producto != 1) {
                        visible = 'disabled';
                    }
                    productos_disp /*html*/ += `<option ${visible} value='${JSON.stringify(element)}'>
                    ${element.id_producto}| ${element.codigo_producto} | ${element.descripcion_productos} | ${element.nombre_r_embobinado} | ${element.nombre_core} | ${element.presentacion} 
                    </option>`;
                });
            }
            $('#id_clien_producPP').empty().html(productos_disp);
        }
    });
}

/*
 *  funcion  que carga datos informacion de los precios de este producto.
 */
var carga_datos_productos = function () {
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
                    if (parseFloat(info.precio_autorizado) > parseFloat(info.precio_venta)) {
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
                    }
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
                $(".add_product").toggle(500);
                $("#cantidad").val('');
                $("#id_clien_producPP").val(0).trigger('change');
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


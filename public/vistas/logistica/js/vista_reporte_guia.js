$(document).ready(function () {
    select_2();
    alertify.set('notifier', 'position', 'bottom-left');
    consultar_factura();
    consultar_ubicacion();
    agregar_guia();
    cambiar_ubicacion_modal();
    ubicaciones();
    enviar_ubicaciones();
});

var DATA = [];

var consultar_factura = function () {
    $('#form_consultar_documento').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#boton_enviar').html();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            btn_procesando('boton_enviar');
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/logistica/consultar_lista_empaque`,
                data: form,
                success: function (respu) {
                    btn_procesando('boton_enviar', obj_inicial, 1);
                    var table = $('#tabla_item_lista').DataTable({
                        "data": respu,
                        'ordering': false,
                        columns: [
                            { "data": "nombre_empresa" },
                            {
                                "data": "pedido_item", render: function (data, type, row) {
                                    return `${row.num_pedido}-${row.item}`;
                                }
                            },
                            { "data": "cantidad_factura", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                            { "data": "descripcion_productos" },
                            {
                                "data": "documento", render: function (data, type, row) {
                                    var num_documento = row.num_remision;
                                    if (row.tipo_documento == 8 || row.tipo_documento == 9) {
                                        num_documento = row.num_factura;
                                    }
                                    return `${row.letra_tipo_documento} ${num_documento}`;
                                }
                            },
                        ]
                    });
                    DATA = respu;
                }
            });

        }
    });
}

var agregar_guia = function () {
    $('#form_agregar_guia').submit(function (e) {
        e.preventDefault();
        if (DATA == '') {
            alertify.error('Por favor realice la consulta de la lista de empaque para asignar guia.');
            return;
        }
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/logistica/insertar_num_guia`,
                data: { DATA, form },
                success: function (response) {
                    if (response == 1) {
                        $('#tabla_item_lista').DataTable().destroy();
                        $('#list-items').empty().html('');
                        $('#guia').val('');
                        $('#num_lista_empaque').val('');
                        alertify.success('Registro Exitoso !!');
                        DATA = [];
                    }
                }
            });
        }
    });
}

var consultar_ubicacion = function () {
    $('#consultar_ubicacion').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        var obj_inicial = $('#boton_consultar').html();

        var ubicacion = $('#num_ubicacion').val().toUpperCase();
        if (ubicacion.startsWith("!$")) {
            var array = ubicacion.split(";");
            if (array[0] != '!$' || array[1] != 'UBI') {
                alertify.error('La ubicacion que esta tratando de ingresar no cumple');
                $('#num_ubicacion').val('');
                $('#num_ubicacion').focus();
                return;
            }
            var ubicacion_com = array[2] + array[3];
        } else {
            var ubicacion_com = ubicacion;
        }
        if (valida) {
            btn_procesando('boton_consultar');
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/logistica/consultar_ubi_despacho`,
                data: { num_ubicacion: ubicacion_com },
                success: function (respu) {
                    btn_procesando('boton_consultar', obj_inicial, 1);
                    var table = $('#tabla_pedido_ubi').DataTable({
                        "data": respu,
                        'ordering': false,
                        columns: [
                            { "data": "nombre_empresa" },
                            {
                                "data": "pedido_item", render: function (data, type, row) {
                                    return `${row.num_pedido}-${row.item}`;
                                }
                            },
                            { "data": "cantidad_factura", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                            { "data": "ubicacion_material", },
                            { "data": "codigo" },
                            {
                                "render": function (data, type, row) {
                                    return `
                                    <center>
                                        <div class="text-center">
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#cambio_ubicacion" title="Cambiar ubicación" class="btn btn-success cambio_ubicacion" data_pedido='${JSON.stringify(row)}'>
                                            <span class="fas fa-exchange-alt"></span>
                                        </button>
                                    <center>`
                                }
                            },
                        ]
                    });
                }
            });

        }
    });
}

UBICACIONES = [];

var cambiar_ubicacion_modal = function () {
    $('#tabla_pedido_ubi').on('click', 'button.cambio_ubicacion', function () {
        var data = $('#tabla_pedido_ubi').DataTable().row($(this).parents("tr")).data();
        UBICACIONES = [];
        $('#enviar_ubicacion').attr('data-tabla', JSON.stringify(data));
        var dato = convertirArray(data);
        UBICACIONES.push(dato.ubicacion_material);
        cargar_span();
    });
}

var convertirArray = function (dato) {
    // Verificar si ubicacion_material es una cadena o un array
    if (typeof dato.ubicacion_material === 'string') {
        // Dividir la cadena por comas para obtener un array
        dato.ubicacion_material = dato.ubicacion_material.split(',');
    }
    // Devolver el dato modificado
    return dato;
}


var ubicaciones = function () {
    $('#ubicacion_materialmodal').on('change', function () {
        var ubicacion = $('#ubicacion_materialmodal').val().toUpperCase();
        if (ubicacion.startsWith("!$")) {
            var array = ubicacion.split(";");
            if (array[0] != '!$' || array[1] != 'UBI') {
                alertify.error('La ubicacion que esta tratando de ingresar no cumple');
                $('#ubicacion_materialmodal').val('');
                $('#ubicacion_materialmodal').focus();
                return;
            }
            var ubicacion_com = array[2] + array[3];
            $('#ubicacion_materialmodal').val('');
        } else {
            var ubicacion_com = ubicacion;
            $('#ubicacion_materialmodal').val('');
        }
        var agregar = UBICACIONES[0].find(element => element == ubicacion_com) ?? false;
        if (!agregar) {
            UBICACIONES[0].push(ubicacion_com);
        } else {
            alertify.error('Ya se cargo esta ubicacion');
        }
        cargar_span();
    })
}

var cargar_span = function () {
    var html = '';
    if (UBICACIONES.length != 0) {
        UBICACIONES[0].forEach((element, a) => {
            html += `${element} <button class="btn btn-danger btn-sm btn_eliminar" type="button" title="Eliminar Ubi" data-posicion="${a}"><i class="fas fa-trash-alt"></i></button><br>`
        });
    }
    $('.span_ubi').html(html);
    eliminar_ubi();
}

var eliminar_ubi = function () {
    $('.btn_eliminar').on('click', function () {
        var posicion = $(this).data('posicion');
        UBICACIONES[0].splice(posicion, 1);
        cargar_span();
    })
}

var enviar_ubicaciones = function () {
    $('#enviar_ubicacion').on('click', function () {
        var data = JSON.parse($('#enviar_ubicacion').attr('data-tabla'));
        var obj_inicial = $('#enviar_ubicacion').html();
        if (UBICACIONES[0] == '') {
            alertify.error('Se necesita una ubicacion para continuar');
            return;
        }
        var nueva_ubi = UBICACIONES[0].toString();
        btn_procesando('enviar_ubicacion');
        $.ajax({
            type: "POST",
            url: `${PATH_NAME}/logistica/cambio_ubicacion_despacho`,
            data: { data, nueva_ubi },
            success: function (respu) {
                btn_procesando('enviar_ubicacion', obj_inicial, 1);
                if (respu == true) {
                    $('#boton_consultar').click();
                    setTimeout(function () {
                        alertify.success('Modificación exitosa');
                        UBICACIONES = [];
                        $('#cambio_ubicacion').modal('hide');
                    }, 3000);
                } else {
                    alertify.error('Algo paso');
                }
            }
        });
    })
}
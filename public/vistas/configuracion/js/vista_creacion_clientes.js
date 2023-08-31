$(document).ready(function () {
    select_2();
    cargar_tabla_clientes();
    editar_cliente();
    remueve_caracteres('nombre_empresa');
    remueve_caracteres('nombre_empresa_modifi');
    cliente();
    crear_cliente();
    // elimina_espacio('codigo_producto_bobina', 'span_codigo_MP');
    solo_numeros('cupo_cliente_modifi');
    solo_numeros('cupo_cliente');
    solo_numeros('dias_max_mora');
    solo_numeros('dias_max_mora_modifi');
    datos_usu();
});

// datos de asesores en vista principal
var datos_usuarios = [];
var datos_usu = function () {
    datos_usuarios = JSON.parse($('#datos_usuarios').val());
}
var vista_asesores_tb = function (id_asesores='') {
    id_asesores = id_asesores.split(',');
    nombres_asesor = '';
    id_asesores.forEach(id => {
        datos_usuarios.forEach(datos => {
            if (id == datos.id_persona) {
               nombres_asesor += datos.nombre + ' ' + datos.apellido + '<br> ';
            }
        });
    });
    return nombres_asesor;
}

var cliente = function () {
    $(".tipo_cli_prov1").on('click', function () {
        var valor1 = $("#tipo_cli_prov1").val();
        if (valor1 == 1) {
            $(".formaPago").toggle(500);
        }
    });
    $(".tipo_cli_prov2").on('click', function () {
        var valor2 = $("#tipo_cli_prov2").val();
        if (valor2 == 2) {
            $(".tipoProv").toggle(500);
        }
    });
};

var cargar_tabla_clientes = function () {
    var table = $("#tabla_clientes").DataTable({
        "ajax": `${PATH_NAME}/configuracion/consultar_clientes`,
        "columns": [
            { "data": "id_cli_prov" },
            { "data": "nit" },
            { "data": "nombre_empresa" },
            { 
                "data": "id_usuarios_asesor",
                render : function (data, type, row) {
                    if (row.id_usuarios_asesor != null) {
                        return vista_asesores_tb(row.id_usuarios_asesor);
                    }else{
                        return '<p></p>'
                    }
                    
                }
            },
            {
                "data": "estado_cli_prov",
                "searchable": false,
                "orderable": false,
                "render": function (data, type, row) {
                    if (row["estado_cli_prov"] == 1) {
                        return '<center><button class="btn btn-link estado" value="1"><i style="font-size:25px" class="fa fa-toggle-on text-success"></i></button></center>';
                    } else {
                        return '<center><button class="btn btn-link estado" value="0"><i style="font-size:25px" class="fa fa-toggle-off text-danger"></i></button></center>';
                    }
                }

            },
            { "defaultContent": '<button type="button" class="btn btn-primary editar_registro" title="Consultar/Modificar" data-bs-toggle="modal" data-bs-target="#ModalClientes"><i class="fa fa-edit"></i></button>', "className": "text-center" },
            {
                "data": "estado_cli_prov",
                "searchable": false,
                "orderable": false,
                "render": function (data, type, row) {
                    if (row["paso_pedido"] == 1) {
                        return '<center><button class="btn btn-success paso_pedido" title="Quita paso pedido"value="0"><i class="fas fa-key text-white"></i></button></center>';
                    } else {
                        return '<center><button class="btn btn-danger paso_pedido" title="Paso a un pedido" value="1"><i class="fas fa-user-lock text-white"></i></button></center>';
                    }
                }

            },
        ],
    });
    cambiar_estado("#tabla_clientes tbody", table);
    obtener_data_editar("#tabla_clientes tbody", table);
    pasa_pedido("#tabla_clientes tbody", table);// permite pasar un pedido por permiso administrativo
}

var cambiar_estado = function (tbody, table) {
    $(tbody).on("click", "button.estado", function () {
        var data = table.row($(this).parents("tr")).data();
        var valor_btn = $(this).val();
        if (valor_btn == 1) {
            //inactivar usuario
            envio = { 'id_cli_prov': data.id_cli_prov, 'estado_cli_prov': 0 };
        } else {
            //activar usuario
            envio = { 'id_cli_prov': data.id_cli_prov, 'estado_cli_prov': 1 };
        }
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_estados_cliente`,
            "type": 'POST',
            "data": envio,
            "success": function (respuesta) {
                $("#tabla_clientes").DataTable().ajax.reload();
                alertify.success('Estado cambiado Correctamente');
            }
        });
    });
}

var obtener_data_editar = function (tbody, table) {
    $(tbody).on('click', 'button.editar_registro', function () {
        var data = table.row($(this).parents("tr")).data();
        var id_usuarios_asesor = '';
        if (data.id_usuarios_asesor != null) {
            id_usuarios_asesor = data.id_usuarios_asesor.split(",");
        }
        rellenar_formulario(data);
        $('#id_usuarios_asesor_modifi').val(id_usuarios_asesor).trigger('change');
        $('#modificar_cliente').attr('data-id', data.id_cli_prov);
    });
}

var editar_cliente = function () {
    $('#form_modificar_cliente').submit(function (e) {
        e.preventDefault();
        var form = $(this).serialize();
        var id_cli_prov = $('#modificar_cliente').attr('data-id');
        var id_usuarios_asesor = $('#id_usuarios_asesor_modifi').val();
        var envio = {
            'form': form,
            'id': id_cli_prov,
            'id_usuarios_asesor': id_usuarios_asesor
        };
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_cliente`,
            "type": 'POST',
            "data": envio,
            "success": function (respu) {
                if (respu) {
                    $("#ModalClientes").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                    $("#tabla_clientes").DataTable().ajax.reload();
                } else {
                    alertify.error('Error inesperado');
                }
            }
        });
    });
}

var crear_cliente = function () {
    $("#form_crear_cliente").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_cliente').html();
        var form = $(this).serializeArray();
        var exception;
        if ($("#tipo_cli_prov1").prop('checked') == false && $("#tipo_cli_prov2").prop('checked') == false) {
            alertify.alert('ALERTA', 'Selecione -> Cliente / Proovedor');
            return;
        }
        if ($("#tipo_cli_prov1").prop('checked') == true && $("#tipo_cli_prov2").prop('checked') == true) {
            exception = ['dig_verificacion', 'dias_dados'];
        }
        if ($("#tipo_cli_prov1").prop('checked') == true && $("#tipo_cli_prov2").prop('checked') == false) {
            exception = ['dig_verificacion', 'tipo_prove', 'forma_pago', 'dias_dados'];
        }
        if ($("#tipo_cli_prov1").prop('checked') == false && $("#tipo_cli_prov2").prop('checked') == true) {
            exception = ['dig_verificacion', 'forma_pago', 'dias_dados', 'pertenece', 'lista_precio', 'id_usuarios_asesor'];
        }
        valida = validar_formulario(form, exception);
        if (valida) {
            if ($('#forma_pago').val() == 4 && $('#dias_dados').val() == 0) {
                alertify.error('El campo Dias Otorgados es requerido');
                return;
            }
            if ($('#id_usuarios_asesor').val() == '' && $("#tipo_cli_prov1").prop('checked') == true) {
                alertify.error('El campo Asesores se requiere almenos un asesor para continuar');
                return;
            }
            btn_procesando('crear_cliente');
            form = $(this).serialize();
            var id_usuarios_asesor = $('#id_usuarios_asesor').val();
            var envio = {
                'form': form,
                'id_usuarios_asesor': id_usuarios_asesor
            };
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_cliente`,
                "type": 'POST',
                "data": envio,
                "success": function (respuesta) {
                    if (respuesta.status == true) {
                        alertify.success(`Datos ingresados corretamente la posicion insetada es ${respuesta.id}`);
                        btn_procesando('crear_cliente', obj_inicial, 1);
                        if ($("#tipo_cli_prov1").prop('checked') == true) {
                            $(".formaPago").toggle(500);
                        }
                        if ($("#tipo_cli_prov2").prop('checked') == true) {
                            $(".tipoProv").toggle(500);
                        }
                        limpiar_formulario('form_crear_cliente', 'select');
                        $('#form_crear_cliente')[0].reset();
                        $('#id_usuarios_asesor').val('').trigger('change');
                        $("#tabla_cliente").DataTable().ajax.reload();
                    } else {
                        alertify.error(`Error al insertar cliente ya creado`);
                        btn_procesando('crear_cliente', obj_inicial, 1);
                        if ($("#tipo_cli_prov1").prop('checked') == true) {
                            $(".formaPago").toggle(500);
                        }
                        if ($("#tipo_cli_prov2").prop('checked') == true) {
                            $(".tipoProv").toggle(500);
                        }
                        limpiar_formulario('form_crear_cliente', 'select');
                        $('#form_crear_cliente')[0].reset();
                        $('#id_usuarios_asesor').val('').trigger('change');
                    }
                }
            });
        }
    });
}

var pasa_pedido = function (tbody, table) {
    $(tbody).on("click", "button.paso_pedido", function () {
        var data = table.row($(this).parents("tr")).data();
        var valor_btn = $(this).val();
        envio = { 'id_cli_prov': data.id_cli_prov, 'paso_pedido': valor_btn };
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_paso_pedido`,
            "type": 'POST',
            "data": envio,
            "success": function (respuesta) {
                $("#tabla_clientes").DataTable().ajax.reload();
                alertify.success('Has activado el paso de un pedido para este cliente.');
            }
        });
    });
}
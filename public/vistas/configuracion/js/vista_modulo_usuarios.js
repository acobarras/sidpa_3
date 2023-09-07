$(document).ready(function () {
    listar_usuarios();
    cambiar_estado_usuario();
    eliminar_usuario();
    obtener_data_editar_usuario();
    $('.id_roll').select2();
    $('#tipo_clave_modifi').select2();
    $('.id_persona').select2();
    editar_usuario();
    $(".datepicker").datepicker();
    crear_usuario();
    permisos_usuario();
    boton_regresar();
    cargar_permisos();
    add_permiso("#tabla_modulo tbody");
    cambio_per_respuesta();
});

var listar_usuarios = function () {
    $.ajax({
        url: `${PATH_NAME}/configuracion/consultar_modulo_usuarios`,
        type: 'GET',
        success: function (respu) {
            localStorage.setItem('lista-usuarios', JSON.stringify(respu));
            var table = $("#tabla_usuarios").DataTable({
                "data": respu,
                "columns": [

                    { "data": "id_usuario" },
                    { "data": "usuario" },
                    { "data": "nombre_roll" },
                    { "data": "nombres" },
                    { "data": "apellidos" },
                    { "data": "num_documento" },
                    {
                        "data": "estado_usu",
                        "searchable": false,
                        "orderable": false,
                        "render": function (data, type, row) {

                            if (row["estado_usu"] == 1) {
                                return '<button class="btn btn-link estado" value="1"><i style="font-size:25px" class="fa fa-toggle-on text-success"></i></button>';
                            } else {
                                return '<button class="btn btn-link estado" value="0"><i style="font-size:25px" class="fa fa-toggle-off text-danger"></i></button>';
                            }
                        }

                    },
                    {
                        "data": "permisos", render: function (data, type, row) {
                            if (row.id_roll == 1) {
                                return `<div class='text-white'>
                                 <span class='fas fa-user-shield rounded-circle bg-warning fs-6 px-2 py-2'></span>
                             </div>`;

                            } else {
                                return `<button type='button' class='btn btn-success btn-sm permisos'>
                                <span class='fa fa-folder-open'></span>
                            </button>`;
                            }
                        },
                        "className": "text-center"
                    },
                    {
                        "defaultContent":
                            `<button type='button' class='btn btn-danger btn-sm eliminar'>
                            <span class='fa fa-times'></span>
                        </button>`,
                        "className": "text-center"
                    },
                    {
                        "defaultContent":
                            `<button type='button' class='btn btn-primary btn-sm modificar_info' data-bs-toggle='modal' data-bs-target='#ModalUsuario'>
                            <i class='fa fa-edit'></i>
                        </button>`,
                        "className": "text-center"
                    },
                ],
            });
        }
    });
}

var cambiar_estado_usuario = function () {
    $("#tabla_usuarios tbody").on("click", "button.estado", function () {
        var data = $('#tabla_usuarios').DataTable().row($(this).parents("tr")).data();
        var valor_btn = $(this).val();
        if (valor_btn == 1) {
            //inactivar usuario
            envio = { 'id_usuario': data.id_usuario, 'estado': 0 };
        } else {
            //activar usuario
            envio = { 'id_usuario': data.id_usuario, 'estado': 1 };
        }
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_estado_usuario`,
            "type": 'POST',
            "data": envio,
            "success": function (respuesta) {
                listar_usuarios();
                alertify.success('Estado cambiado Correctamente');
                // $("#tabla_usuarios").DataTable().ajax.reload();
            }
        });
    });
}

var eliminar_usuario = function () {
    //eliminar usuario
    $("#tabla_usuarios tbody").on("click", "button.eliminar", function () {
        var data = $('#tabla_usuarios').DataTable().row($(this).parents("tr")).data();
        alertify.confirm('Eliminar Usuario', '¿Está seguro?,desea eliminar el usuario: <strong>' + data.usuario + "</strong>", function () {
            $.ajax({
                "url": `${PATH_NAME}/configuracion/eliminar_usuario`,
                "type": 'POST',
                "data": data,//{ "id_usuario": data.id_usuario },
                "success": function (respuesta) {
                    listar_usuarios();
                    alertify.success('Eliminado !!');
                }
            });
        }, function () {
        });
    });
}

var obtener_data_editar_usuario = function (tbody, table) {
    //mostrar info usuario modificar
    $("#tabla_usuarios tbody").on("click", "button.modificar_info", function () {
        $('#pasword').val('');
        var data = $('#tabla_usuarios').DataTable().row($(this).parents("tr")).data();
        $('#res_prioridad_modifi').attr('data', JSON.stringify(data));
        rellenar_formulario(data);
        $('#modificar_usuario').attr('data-id', data.id_usuario);
        $('#ruta_foto').empty().html(`<img width="100px" src="${PATH_NAME}/public/img/foto_usuarios/${data.ruta_foto}" />`);
    });

}

var editar_usuario = function () {
    //modificar usuario
    $("#form_editar_usuario").submit(function (e) {
        e.preventDefault();
        var datos = {
            form: $(this).serialize(),
            id_usuario: $('#modificar_usuario').attr('data-id'),
        };
        $.ajax({
            url: `${PATH_NAME}/configuracion/modificar_usuario`,
            type: 'POST',
            data: datos,
            success: function (res) {
                if (res == true) {
                    listar_usuarios();
                    $("#ModalUsuario").modal("hide");
                    // $("#dt_usu_personas").DataTable().ajax.reload();
                    alertify.success('Usuario Modificado !!');
                }
            }
        });
    });
}

var crear_usuario = function () {
    $('#form_crear_usuario').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var excepcion = ['res_prioridad'];
        valida = validar_formulario(form, excepcion);
        if (valida) {
            // btn_procesando('crear_persona');
            form = document.getElementById('form_crear_usuario');
            $.ajax({
                url: `${PATH_NAME}/configuracion/insertar_usuarios`,
                type: 'POST',
                data: new FormData(form),
                contentType: false,
                cache: false,
                processData: false,
                success: function (respuesta) {
                    if (respuesta.estado === false) {
                        limpiar_formulario('form_crear_usuario', 'select');
                        limpiar_formulario('form_crear_usuario', 'input');
                        alertify.error('Este usuario ya se encuentra creado');
                    } else {
                        listar_usuarios();
                        limpiar_formulario('form_crear_usuario', 'select');
                        limpiar_formulario('form_crear_usuario', 'input');
                        alertify.success('Usuario creado correctamente');
                        $('#pasword_crea').val('Acobarras2019');
                    }
                }
            });
        }

    });
}

var permisos_usuario = function () {
    $("#tabla_usuarios tbody").on("click", "button.permisos", function (e) {
        var data = $('#tabla_usuarios').DataTable().row($(this).parents("tr")).data();
        var id_usuario = data.id_usuario;
        $('#principal').css('display', 'none');
        $('#permisos_usuario').css('display', '');
        $('#titulo_permisos').empty().html(`Permisos Usuario ${data.nombres}`);
        var tabla = $('#tabla_permisos_usuario').DataTable({
            "ajax": {
                "url": `${PATH_NAME}/configuracion/permisos_usuario`,
                "type": "POST",
                "data": { id_usuario }
            },
            "columns": [
                { "data": "nombre_hoja" },
                { "data": "titulo" },
                {
                    "data": "img", render: function (date, type, row) {
                        return `<i class="${row.icono}" style="font-size: 20px; color:${row.color_icono}"></i>`;
                    }
                },
                { "data": "nombre_estado" },
                {
                    "defaultContent":
                        `<button type="button" class="elimina btn btn-danger">
						<i class="fas fa-times"></i>
					</button>`,
                    "className": "text-center"
                },
            ],
        });
        $('.link_carga').attr('data-usuario', data.id_usuario);
        permiso_eliminar("#tabla_permisos_usuario tbody", tabla);
        $('#inicio-tab').click();
    });
}

var boton_regresar = function () {
    $('#regresar').on('click', function (e) {
        e.preventDefault();
        $('#principal').css('display', '');
        $('#permisos_usuario').css('display', 'none');
    });
}

var permiso_eliminar = function (tbody, table) {
    $(tbody).on("click", "button.elimina", function (e) {
        e.preventDefault();// Detiene la recarga
        var data = table.row($(this).parents("tr")).data();
        var form = data.id_permisos;
        $.ajax({
            url: `${PATH_NAME}/configuracion/eliminar_permisos_usuario`,
            type: 'POST',
            data: { form },
            success: function (res) {
                if (res) {
                    alertify.success('Dato Eliminado.');
                    $('#tabla_permisos_usuario').DataTable().ajax.reload();
                }
            }
        });
    });
}

var cargar_permisos = function () {
    $('.link_carga').on('click', function (e) {
        // e.preventDefault();
        var form = $(this).attr('data-value');
        var id_usuario = $(this).attr('data-usuario');
        var tab = $('#tabla_modulo').DataTable({
            "ajax": {
                "url": `${PATH_NAME}/configuracion/listar_datos_permisos`,
                "type": "POST",
                "data": { form, id_usuario }
            },
            "columns": [
                { "data": "nombre_hoja" },
                { "data": "titulo" },
                {
                    "data": "img", render: function (date, type, row) {
                        return `<i class="${row.icono}" style="font-size: 20px; color:${row.color_icono}"></i>`;
                    }
                },
                {
                    "defaultContent":
                        `<select class="form-control permiso_autorizado" >
						<option value="0"></option>
						<option value="1">Activo</option>
					</select>`,
                    "className": "text-center"
                },
            ],
        });

    });
}

var add_permiso = function (tbody, table) {
    $(tbody).on("change", "select.permiso_autorizado", function (e) {
        e.preventDefault();// Detiene la recarga
        var data = $('#tabla_modulo').DataTable().row($(this).parents("tr")).data();
        var id_usuario = $('.link_carga').attr('data-usuario');
        var elegido = $(this).val();
        if (elegido != 0) {
            var form = {
                'id_modulo_hoja': data.id_hoja,
                'id_usuario': id_usuario,
                'estado_permisos': 1,
            };
            $.ajax({
                url: `${PATH_NAME}/configuracion/add_permisos_usuario`,
                type: 'POST',
                data: { form },
                success: function (res) {
                    // recargar tabla
                    if (res) {
                        $('.permiso_autorizado').val('');
                        $('#tabla_permisos_usuario').DataTable().ajax.reload();
                        $('#tabla_modulo').DataTable().ajax.reload();
                        alertify.success('Permiso insertado');
                    } else {
                        $('.permiso_autorizado').val('');
                    }
                }
            });
        }

    });
}

var cambio_per_respuesta = function () {
    $('#res_prioridad_modifi').on("blur", function (e) {
        e.preventDefault();
        $('#modificar_usuario').addClass('d-none');
        var data = JSON.parse($('#res_prioridad_modifi').attr('data'));
        var valor = $(this).val();
        var id_usuario = data.id_usuario;
        var id_persona = data.id_persona;
        var id_area_trabajo = data.id_area_trabajo;
        $.ajax({
            url: `${PATH_NAME}/valida_per_respuesta`,
            type: 'POST',
            data: { id_usuario, valor, id_persona, id_area_trabajo },
            success: function (res) {
                if (res != true) {
                    alertify.error(res.msg);
                    $('#modificar_usuario').removeClass('d-none');
                    $('#res_prioridad_modifi').val(0);
                    return;
                } else {
                    alertify.success('permiso asignado');
                    $('#modificar_usuario').removeClass('d-none');

                }
            }
        });
    });
}
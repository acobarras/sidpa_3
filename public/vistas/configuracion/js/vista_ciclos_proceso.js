$(document).ready(function () {
    select_2();
    cargar_tabla_area_trabajo();
    cargar_tabla_actividad_area();
    crear_actividad_area();
    crear_area_trabajo();
    editar_area();
    editar_actividad();
    // elimina_espacio('codigo_producto_bobina', 'span_codigo_MP');
});

var cargar_tabla_area_trabajo = function () {

    var table = $("#tabla_actividad_area").DataTable({
        "ajax": `${PATH_NAME}/configuracion/consultar_actividad_area`,
        "columns": [
            { "data": "id_actividad_area" },
            { "data": "codigo_actividad_area" },
            { "data": "nombre_area_trabajo" },
            { "data": "nombre_actividad_area" },
            {
                "data": "estado_actividad_area",
                "searchable": false,
                "orderable": false,
                "render": function (data, type, row) {
                    if (row["estado_actividad_area"] == 1) {
                        return '<center><button class="btn btn-link estado" value="1"><i style="font-size:25px" class="fa fa-toggle-on text-success"></i></button></center>';
                    } else {
                        return '<center><button class="btn btn-link estado" value="0"><i style="font-size:25px" class="fa fa-toggle-off text-danger"></i></button></center>';
                    }
                }

            },
            { "defaultContent": '<button type="button" class="btn btn-primary editar_registro" title="Consultar/Modificar" data-bs-toggle="modal" data-bs-target="#ModalActividad"><i class="fa fa-edit"></i></button>', "className": "text-center" }

        ],
    });
    cambiar_estado_actividad("#tabla_actividad_area tbody", table);
    obtener_data_editar_actividad("#tabla_actividad_area tbody", table);
}


var cargar_tabla_actividad_area = function () {
    var table = $("#tabla_area_trabajo").DataTable({
        "ajax": `${PATH_NAME}/configuracion/consultar_area_trabajo`,
        "columns": [
            { "data": "id_area_trabajo" },
            { "data": "codigo_area_trabajo" },
            { "data": "nombre_area_trabajo" },
            {
                "data": "estado_area_trabajo",
                "searchable": false,
                "orderable": false,
                "render": function (data, type, row) {
                    if (row["estado_area_trabajo"] == 1) {
                        return '<center><button class="btn btn-link estado" value="1"><i style="font-size:25px" class="fa fa-toggle-on text-success"></i></button></center>';
                    } else {
                        return '<center><button class="btn btn-link estado" value="0"><i style="font-size:25px" class="fa fa-toggle-off text-danger"></i></button></center>';
                    }
                }

            },
            { "defaultContent": '<button type="button" class="btn btn-primary editar_registro" title="Consultar/Modificar" data-bs-toggle="modal" data-bs-target="#ModalArea"><i class="fa fa-edit"></i></button>', "className": "text-center" }

        ],
    });
    cambiar_estado_area("#tabla_area_trabajo tbody", table);
    obtener_data_editar_area("#tabla_area_trabajo tbody", table);
}

var obtener_data_editar_area = function (tbody, table) {
    $(tbody).on('click', 'button.editar_registro', function () {
        var data = table.row($(this).parents("tr")).data();
        console.log(data);
        rellenar_formulario(data);
        $('#modificar_area_trabajo').attr('data-id', data.id_area_trabajo);
    });
}

var editar_area = function () {
    $('#form_modificar_area_trabajo').submit(function (e) {
        e.preventDefault();
        var form = $(this).serialize();
        var id_area_trabajo = $('#modificar_area_trabajo').attr('data-id');
        var envio = {
            'form': form,
            'id': id_area_trabajo
        };
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_area_trabajo`,
            "type": 'POST',
            "data": envio,
            "success": function (respu) {
                if (respu) {
                    $("#ModalArea").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                    $("#tabla_area_trabajo").DataTable().ajax.reload();
                } else {
                    alertify.error('Error inesperado');
                }
            }
        });
    });
}

var obtener_data_editar_actividad = function (tbody, table) {
    $(tbody).on('click', 'button.editar_registro', function () {
        var data = table.row($(this).parents("tr")).data();
        rellenar_formulario(data);
        $('#modificar_actividad_area').attr('data-id', data.id_actividad_area);
    });
}

var editar_actividad = function () {
    $('#form_modificar_actividad_area').submit(function (e) {
        e.preventDefault();
        var form = $(this).serialize();
        var id_actividad_area = $('#modificar_actividad_area').attr('data-id');
        var envio = {
            'form': form,
            'id': id_actividad_area
        };
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_actividad_area`,
            "type": 'POST',
            "data": envio,
            "success": function (respu) {
                if (respu) {
                    $("#ModalActividad").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                    $("#tabla_actividad_area").DataTable().ajax.reload();
                } else {
                    alertify.error('Error inesperado');
                }
            }
        });
    });
}

var cambiar_estado_actividad = function (tbody, table) {
    $(tbody).on("click", "button.estado", function () {
        var data = table.row($(this).parents("tr")).data();
        var valor_btn = $(this).val();
        if (valor_btn == 1) {
            //inactivar usuario
            envio = { 'id_actividad_area': data.id_actividad_area, 'estado_actividad_area': 0 };
        } else {
            //activar usuario
            envio = { 'id_actividad_area': data.id_actividad_area, 'estado_actividad_area': 1 };
        }
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_estados_actividad`,
            "type": 'POST',
            "data": envio,
            "success": function (respuesta) {
                $("#tabla_actividad_area").DataTable().ajax.reload();
                alertify.success('Estado cambiado Correctamente');
            }
        });
    });
}

var cambiar_estado_area = function (tbody, table) {
    $(tbody).on("click", "button.estado", function () {
        var data = table.row($(this).parents("tr")).data();
        var valor_btn = $(this).val();
        if (valor_btn == 1) {
            //inactivar usuario
            envio = { 'id_area_trabajo': data.id_area_trabajo, 'estado_area_trabajo': 0 };
        } else {
            //activar usuario
            envio = { 'id_area_trabajo': data.id_area_trabajo, 'estado_area_trabajo': 1 };
        }
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_estados_area`,
            "type": 'POST',
            "data": envio,
            "success": function (respuesta) {
                $("#tabla_area_trabajo").DataTable().ajax.reload();
                alertify.success('Estado cambiado Correctamente');
            }
        });
    });
}

var crear_actividad_area = function () {
    $("#form_crear_actividad_area").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_actividad_area').html();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            btn_procesando('crear_actividad_area');
            $.ajax({
                "url": `${PATH_NAME}/configuracion/crear_nueva_actividad`,
                "type": 'POST',
                "data": form,
                "success": function (respuesta) {
                    if (respuesta.estado) {
                        alertify.success(`Datos ingresados corretamente la posicion insetada es ${respuesta.id}`);
                        btn_procesando('crear_actividad_area', obj_inicial, 1);
                        limpiar_formulario('form_crear_actividad_area', 'select');
                        limpiar_formulario('form_crear_actividad_area', 'input');
                        $("#tabla_actividad_area").DataTable().ajax.reload();
                    } else {
                        alertify.error(`Error al insertar`);
                        btn_procesando('crear_actividad_area', obj_inicial, 1);
                    }
                }
            });
        }
    });
}

var crear_area_trabajo = function () {
    $("#form_crear_area_trabajo").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_area_trabajo').html();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            btn_procesando('crear_area_trabajo');
            $.ajax({
                "url": `${PATH_NAME}/configuracion/crear_nueva_area`,
                "type": 'POST',
                "data": form,
                "success": function (respuesta) {
                    if (respuesta.estado) {
                        alertify.success(`Datos ingresados corretamente la posicion insetada es ${respuesta.id}`);
                        btn_procesando('crear_area_trabajo', obj_inicial, 1);
                        limpiar_formulario('form_crear_area_trabajo', 'select');
                        limpiar_formulario('form_crear_area_trabajo', 'input');
                        $("#tabla_area_trabajo").DataTable().ajax.reload();
                    } else {
                        alertify.error(`Error al insertar`);
                        btn_procesando('crear_area_trabajo', obj_inicial, 1);
                    }
                }
            });
        }
    });
}

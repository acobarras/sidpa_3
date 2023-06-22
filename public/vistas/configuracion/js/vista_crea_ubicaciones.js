$(document).ready(function () {
    select_2();
    carga_tabla_ubicaciones();
    crea_ubicacion();
    editar_ubicacion();
});


var carga_tabla_ubicaciones = function() {
    var table = $("#tabla_ubicaciones").DataTable({
        "deferRender": true,
        "ajax": `${PATH_NAME}/configuracion/consultar_ubicaciones`,
        "columns": [
            { "data": "id" },
            { "data": "nombre_ubicacion" },
            { "data": "nombre_articulo" },
            {
                "data": "estado",
                "searchable": false,
                "orderable": false,
                "render": function (data, type, row) {
                    if (row["estado"] == 1) {
                        return '<center><button class="btn btn-link estado" value="1"><i style="font-size:25px" class="fa fa-toggle-on text-success"></i></button></center>';
                    } else {
                        return '<center><button class="btn btn-link estado" value="0"><i style="font-size:25px" class="fa fa-toggle-off text-danger"></i></button></center>';
                    }
                }
            },
            { "defaultContent": '<button type="button" class="btn btn-primary editar_registro" title="Consultar/Modificar" data-bs-toggle="modal" data-bs-target="#ModalUbicacion"><i class="fa fa-edit"></i></button>',"className": "text-center" }
        ],
        "language": idioma
    });
    cambiar_estado_ubicacion("#tabla_ubicaciones tbody", table);
    obtener_data_editar("#tabla_ubicaciones tbody", table);
}

var cambiar_estado_ubicacion = function (tbody, table) {
    $(tbody).on("click", "button.estado", function () {
        var data = table.row($(this).parents("tr")).data();
        var valor_btn = $(this).val();
        if (valor_btn == 1) {
            //inactivar usuario
            envio = { 'id': data.id, 'estado': 0, 'solo_estado': 1 };
        } else {
            //activar usuario
            envio = { 'id': data.id, 'estado': 1, 'solo_estado': 1 };
        }
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_ubicacion`,
            "type": 'POST',
            "data": envio,
            "success": function (respuesta) {
                $("#tabla_ubicaciones").DataTable().ajax.reload();
                alertify.success('Estado cambiado Correctamente');
            }
        });
    });
}

var obtener_data_editar = function (tbody, table) {
    $(tbody).on('click', 'button.editar_registro', function () {
        var data = table.row($(this).parents("tr")).data();
        rellenar_formulario(data);
        $('#modificar_ubicacion').attr('data-id', data.id);
    });
}

var editar_ubicacion = function () {
    $('#form_modificar_ubicacion').submit(function (e) {
        e.preventDefault();
        var form = $(this).serialize();
        var id_ubicacion = $('#modificar_ubicacion').attr('data-id');
        var envio = {
            'form': form,
            'id': id_ubicacion,
            'solo_estado': 2
        };
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_ubicacion`,
            "type": 'POST',
            "data": envio,
            "success": function (respu) {
                if (respu) {
                    $("#ModalUbicacion").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                    $("#tabla_ubicaciones").DataTable().ajax.reload();
                } else {
                    alertify.error('Error inesperado');
                }
            }
        });
    });
}



var crea_ubicacion = function() {
    $("#form_crear_ubicacion").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_ubicacion').html();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            btn_procesando('crear_ubicacion');
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_ubicaciones`,
                "type": 'POST',
                "data": form,
                "success": function (respuesta) {
                    if (respuesta.estado) {
                        alertify.success(`Datos ingresados corretamente la posicion insetada es ${respuesta.id}`);
                        btn_procesando('crear_ubicacion', obj_inicial, 1);
                        limpiar_formulario('form_crear_ubicacion', 'select');
                        limpiar_formulario('form_crear_ubicacion', 'input');
                        $("#tabla_ubicaciones").DataTable().ajax.reload();
                    } else {
                        alertify.error(`Error al insertar`);
                        btn_procesando('crear_ubicacion', obj_inicial, 1);
                    }
                }
            });
        }
    });
}
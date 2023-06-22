$(document).ready(function () {
    select_2();
    cargar_tabla_adhesivos();
    editar_adhesivo();
    crear_adhesivo();
    // elimina_espacio('codigo_producto_bobina', 'span_codigo_MP');
});

var cargar_tabla_adhesivos = function () {
    var table = $("#tabla_adhesivo").DataTable({
        "ajax": `${PATH_NAME}/configuracion/consultar_adhesivo`,
        "columns": [
            { "data": "id_adh" },
            { "data": "codigo_adh" },
            { "data": "nombre_adh" },
            { "defaultContent": '<button type="button" class="btn btn-primary editar_registro" title="Consultar/Modificar" data-bs-toggle="modal" data-bs-target="#ModalAdhesivo"><i class="fa fa-edit"></i></button>', "className": "text-center" }

        ],
    });
    obtener_data_editar("#tabla_adhesivo tbody", table);
}

var obtener_data_editar = function (tbody, table) {
    $(tbody).on('click', 'button.editar_registro', function () {
        var data = table.row($(this).parents("tr")).data();
        rellenar_formulario(data);
        $('#modificar_adhesivo').attr('data-id', data.id_adh);
    });
}

var editar_adhesivo = function () {
    $('#form_modificar_adhesivo').submit(function (e) {
        e.preventDefault();
        var form = $(this).serialize();
        var id_adhesivo = $('#modificar_adhesivo').attr('data-id');
        var envio = {
            'form': form,
            'id': id_adhesivo
        };
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_adhesivo`,
            "type": 'POST',
            "data": envio,
            "success": function (respu) {
                if (respu) {
                    $("#ModalAdhesivo").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                    $("#tabla_adhesivo").DataTable().ajax.reload();
                } else {
                    alertify.error('Error inesperado');
                }
            }
        });
    });
}

var crear_adhesivo = function () {
    $("#form_crear_adhesivo").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_adhesivo').html();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            btn_procesando('crear_adhesivo');
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_adhesivo`,
                "type": 'POST',
                "data": form,
                "success": function (respuesta) {
                    if (respuesta.estado) {
                        alertify.success(`Datos ingresados corretamente la posicion insetada es ${respuesta.id}`);
                        btn_procesando('crear_adhesivo', obj_inicial, 1);
                        limpiar_formulario('form_crear_adhesivo', 'select');
                        limpiar_formulario('form_crear_adhesivo', 'input');
                        $("#tabla_adhesivo").DataTable().ajax.reload();
                    } else {
                        alertify.error(`Error al insertar`);
                        btn_procesando('crear_adhesivo', obj_inicial, 1);
                    }
                }
            });
        }
    });
}
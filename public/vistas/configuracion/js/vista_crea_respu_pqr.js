$(document).ready(function () {
    select_2();
    alertify.set('notifier', 'position', 'bottom-left');
    cargar_tabla_codigos_pqr();
    crear_codigo_pqr();
    editar_codigo_pqr();
});

var cargar_tabla_codigos_pqr = function () {
    var table = $("#tabla_respuestas_pqr").DataTable({
        "ajax": `${PATH_NAME}/configuracion/consultar_codigos_pqr`,
        "columns": [
            { "data": "id_respuesta_pqr" },
            { "data": "codigo" },
            { "data": "nombre_tipo_pqr" },
            { "data": "descripcion" },
            { "defaultContent": '<button type="button" class="btn btn-info editar_codigo"><i class="fas fa-search"></i></button>',"className": "text-center" }

        ],
    });
    obtener_data_pqr("#tabla_respuestas_pqr tbody", table);
}

var obtener_data_pqr = function(tbody, table) {
    $(tbody).on("click", "button.editar_codigo", function () {
        var data = table.row($(this).parents("tr")).data();
        rellenar_formulario(data);
        $('#modificar_codigo_pqr').attr('data-id', data.id_respuesta_pqr);
        $('#ModalUbicacion').modal('toggle');
    });

}

var editar_codigo_pqr = function () {
    $('#form_modificar_codigo_pqr').submit(function (e) {
        e.preventDefault();
        var form = $(this).serialize();
        var id_ubicacion = $('#modificar_codigo_pqr').attr('data-id');
        var envio = {
            'form': form,
            'id': id_ubicacion,
        };
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_codigo_pqr`,
            "type": 'POST',
            "data": envio,
            "success": function (respu) {
                if (respu) {
                    $("#ModalUbicacion").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                    $("#tabla_respuestas_pqr").DataTable().ajax.reload();
                } else {
                    alertify.error('Error inesperado');
                }
            }
        });
    });
}

var crear_codigo_pqr = function () {
    $("#form_crear_codigo_pqr").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_codigo_pqr').html();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            btn_procesando('crear_codigo_pqr');
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_codigo_pqr`,
                "type": 'POST',
                "data": form,
                "success": function (respuesta) {
                    if (respuesta.status) {
                        alertify.success(`Datos ingresados corretamente la posicion insetada es ${respuesta.id}`);
                        btn_procesando('crear_codigo_pqr', obj_inicial, 1);
                        limpiar_formulario('form_crear_codigo_pqr', 'select');
                        limpiar_formulario('form_crear_codigo_pqr', 'input');
                        $("#tabla_respuestas_pqr").DataTable().ajax.reload();
                    } else {
                        alertify.error(`Error al insertar`);
                        btn_procesando('crear_codigo_pqr', obj_inicial, 1);
                    }
                }
            });
        }
    });
}

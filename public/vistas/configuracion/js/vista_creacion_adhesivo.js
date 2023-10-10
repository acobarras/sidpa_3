$(document).ready(function () {
    select_2();
    cargar_tabla_adhesivos();
    // editar_adhesivo();
    crear_adhesivo();
    regresar_adhesivo();
    obtener_data_editar();
});

var cargar_tabla_adhesivos = function () {
    var table = $("#tabla_adhesivo").DataTable({
        "ajax": `${PATH_NAME}/configuracion/consultar_adhesivo`,
        "columns": [
            { "data": "id_adh" },
            { "data": "codigo_adh" },
            { "data": "nombre_adh" },
            { "data": "nombre_corto" },
            { "data": "superficies" },
            { "data": "rango_temp" },
            { "defaultContent": '<button type="button" class="btn btn-primary editar_registro" title="Consultar/Modificar"><i class="fa fa-edit"></i></button>', "className": "text-center" }

        ],
    });
}

var regresar_adhesivo = function () {
    $('#home-tab').on('click', function () {
        $('#agrega-tab').empty().html('Nuevo Adhesivo');
        $('#titulo_form').empty().html('Crear Nuevo Adhesivo');
        limpiar_formulario('form_crear_adhesivo', 'input');
        limpiar_formulario('form_crear_adhesivo', 'select');
        $('#crear_adhesivo').empty().html(`<i class="fa fa-plus-circle"></i> Crear Adhesivo`);
    });
}
var obtener_data_editar = function (tbody, table) {
    $("#tabla_adhesivo tbody").on('click', 'button.editar_registro', function () {
        var data = $("#tabla_adhesivo").DataTable().row($(this).parents("tr")).data();
        $('#agrega-tab').empty().html('Modificar Adhesivo');
        $('#titulo_form').empty().html('Modificar Adhesivo');
        $('#crear_adhesivo').empty().html(`<i class="fa fa-plus-circle"></i> Modificar Adhesivo`);
        rellenarFormulario(data);
        $('#agrega-tab').click();
    });
}

var crear_adhesivo = function () {
    $("#form_crear_adhesivo").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_adhesivo').html();
        var exepcion = ['id_adh'];
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            btn_procesando('crear_adhesivo');
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_adhesivo`,
                "type": 'POST',
                "data": form,
                "success": function (respuesta) {
                    if (respuesta) {
                        alertify.success('Modificacion realizada corectamente');
                        $("#tabla_adhesivo").DataTable().ajax.reload();
                    } else {
                        if (respuesta.estado) {
                            alertify.success(`Datos ingresados corretamente la posicion insetada es ${respuesta.id}`);
                            btn_procesando('crear_adhesivo', obj_inicial, 1);
                            limpiar_formulario('form_crear_adhesivo', 'select');
                            limpiar_formulario('form_crear_adhesivo', 'input');
                            $("#tabla_adhesivo").DataTable().ajax.reload();
                        } else {
                            alertify.error(`Error al insertar`);
                        }
                    }
                    btn_procesando('crear_adhesivo', obj_inicial, 1);
                    $('#home-tab').click();
                }
            });
        }
    });
}
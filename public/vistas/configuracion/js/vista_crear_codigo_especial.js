$(document).ready(function () {
    select_2();
    cargar_tabla_codigos_especiales();
    // editar_tipo_articulo();
    crear_codigo_especial();
});

var cargar_tabla_codigos_especiales = function () {
    var table = $("#tabla_codigos_especiales").DataTable({
        "ajax": `${PATH_NAME}/configuracion/consultar_codigos_especiales`,
        "columns": [
            { "data": "id_codigos_especial" },
            { "data": "codigo_especial" },
            { "data": "codigo_relacion" },
            { "defaultContent": '<button type="button" class="btn btn-danger eliminar_codigo"><i class="fa fa-times"></i></button>',"className": "text-center" }

        ],
    });
    eliminar_codigo_especial('#tabla_codigos_especiales', table);
    // obtener_data_editar("#tabla_codigos_especiales tbody", table);
}

var eliminar_codigo_especial = function(tbody, table) {
    $(tbody).on("click", "button.eliminar_codigo", function () {
        var data = table.row($(this).parents("tr")).data();
        alertify.confirm('Confirmación Eliminación', 'Esta seguro que desea eliminar este registro la eliminacion de este registro no es reversible y puede implicar errores en algunas tablas', 
            function(){ 
                $.ajax({
                    type: "POST",
                    url: `${PATH_NAME}/configuracion/eliminar_codigos_especiales`,
                    data: { id_codigos_especial: data.id_codigos_especial },
                    success: function (response) {
                        $('#tabla_codigos_especiales').DataTable().ajax.reload(function () { });
                        alertify.success('Eliminación ejecutada correctamente') 
                    }
                });
            }, 
            function(){ alertify.error('Operación Cancelada')});
    });

}

var crear_codigo_especial = function () {
    $("#form_crear_codigo_especial").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_codigo_especial').html();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            btn_procesando('crear_codigo_especial');
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_codigo_especial`,
                "type": 'POST',
                "data": form,
                "success": function (respuesta) {
                    if (respuesta.status) {
                        alertify.success(`Datos ingresados corretamente la posicion insetada es ${respuesta.id}`);
                        btn_procesando('crear_codigo_especial', obj_inicial, 1);
                        limpiar_formulario('form_crear_codigo_especial', 'select');
                        limpiar_formulario('form_crear_codigo_especial', 'input');
                        $("#tabla_codigos_especiales").DataTable().ajax.reload();
                    } else {
                        alertify.error(`Error al insertar`);
                        btn_procesando('crear_codigo_especial', obj_inicial, 1);
                    }
                }
            });
        }
    });
}

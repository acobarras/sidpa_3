$(document).ready(function () {
    select_2();
    cargar_tabla_articulos();
    editar_tipo_articulo();
    crear_tipo_articulo();
    // elimina_espacio('codigo_producto_bobina', 'span_codigo_MP');
});


var cargar_tabla_articulos = function () {
    var table = $("#tabla_articulo").DataTable({
        "ajax": `${PATH_NAME}/configuracion/consultar_articulo`,
        "columns": [
            { "data": "id_tipo_articulo" },
            { "data": "nombre_articulo" },
            { "data": "nombre_clase_articulo" },
            {
                "data": "estado_articulo",
                "searchable": false,
                "orderable": false,
                "render": function (data, type, row) {
                    if (row["estado_articulo"] == 1) {
                        return '<center><button class="btn btn-link estado" value="1"><i style="font-size:25px" class="fa fa-toggle-on text-success"></i></button></center>';
                    } else {
                        return '<center><button class="btn btn-link estado" value="0"><i style="font-size:25px" class="fa fa-toggle-off text-danger"></i></button></center>';
                    }
                }

            },
            { "defaultContent": '<button type="button" class="btn btn-primary editar_registro" title="Consultar/Modificar" data-bs-toggle="modal" data-bs-target="#ModalArticulo"><i class="fa fa-edit"></i></button>',"className": "text-center" }

        ],
    });
    cambiar_estado("#tabla_articulo tbody", table);
    obtener_data_editar("#tabla_articulo tbody", table);
}

var cambiar_estado = function (tbody, table) {
    $(tbody).on("click", "button.estado", function () {
        var data = table.row($(this).parents("tr")).data();
        var valor_btn = $(this).val();
        if (valor_btn == 1) {
            //inactivar usuario
            envio = { 'id_tipo_articulo': data.id_tipo_articulo, 'estado_articulo': 0 };
        } else {
            //activar usuario
            envio = { 'id_tipo_articulo': data.id_tipo_articulo, 'estado_articulo': 1 };
        }
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_estados_tipo_articulo`,
            "type": 'POST',
            "data": envio,
            "success": function (respuesta) {
                $("#tabla_articulo").DataTable().ajax.reload();
                alertify.success('Estado cambiado Correctamente');
            }
        });
    });
}

var obtener_data_editar = function (tbody, table) {
    $(tbody).on('click', 'button.editar_registro', function () {
        var data = table.row($(this).parents("tr")).data();
        rellenar_formulario(data);
        $('#modificar_tipo_articulo').attr('data-id', data.id_tipo_articulo);
    });
}

var editar_tipo_articulo = function () {
    $('#form_modificar_tipo_articulo').submit(function (e) {
        e.preventDefault();
        var form = $(this).serialize();
        var id_tipo_articulo = $('#modificar_tipo_articulo').attr('data-id');
        var envio = {
            'form': form,
            'id': id_tipo_articulo
        };
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_tipo_articulo`,
            "type": 'POST',
            "data": envio,
            "success": function (respu) {
                if (respu) {
                    $("#ModalArticulo").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                    $("#tabla_articulo").DataTable().ajax.reload();
                } else {
                    alertify.error('Error inesperado');
                }
            }
        });
    });
}

var crear_tipo_articulo = function () {
    $("#form_crear_tipo_articulo").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_tipo_articulo').html();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            btn_procesando('crear_tipo_articulo');
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_tipo_articulo`,
                "type": 'POST',
                "data": form,
                "success": function (respuesta) {
                    if (respuesta.estado) {
                        alertify.success(`Datos ingresados corretamente la posicion insetada es ${respuesta.id}`);
                        btn_procesando('crear_tipo_articulo', obj_inicial, 1);
                        limpiar_formulario('form_crear_tipo_articulo', 'select');
                        limpiar_formulario('form_crear_tipo_articulo', 'input');
                        $("#tabla_articulo").DataTable().ajax.reload();
                    } else {
                        alertify.error(`Error al insertar`);
                        btn_procesando('crear_tipo_articulo', obj_inicial, 1);
                    }
                }
            });
        }
    });
}

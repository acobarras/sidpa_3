$(document).ready(function () {
    select_2();
    cargar_tabla_direcciones();
    editar_direccion1();
    editar_direccion2();
    // elimina_espacio('codigo_producto_bobina', 'span_codigo_MP');
});

const roll = $('#roll').val();

var cargar_tabla_direcciones = function () {

    var table = $("#tabla_direcciones").DataTable({
        "ajax": `${PATH_NAME}/configuracion/consultar_direcciones`,
        "columns": [
            { "data": "id_direccion" },
            { "data": "nombre_empresa" },
            {
                "data": "asesor", render: function (data, type, row) {
                    respu = `${row.nombres} ${row.apellidos}`;
                    return respu;
                }
            },
            { "data": "nombre_departamento" },
            { "data": "nombre_ciudad" },
            { "data": "direccion" },
            { "data": "telefono" },
            { "data": "celular" },
            { "data": "contacto" },
            { "data": "nombre_ruta" },
            {
                "data": "estado_direccion",
                "searchable": false,
                "orderable": false,
                "render": function (data, type, row) {
                    if (row["estado_direccion"] == 1) {
                        return '<center><button class="btn btn-link estado" value="1"><i style="font-size:25px" class="fa fa-toggle-on text-success"></i></button></center>';
                    } else {
                        return '<center><button class="btn btn-link estado" value="0"><i style="font-size:25px" class="fa fa-toggle-off text-danger"></i></button></center>';
                    }
                }
            },
            { "defaultContent": '<button type="button" class="btn btn-primary editar_registro" title="Consultar/Modificar" data-bs-toggle="modal" data-bs-target="#ModalDireccion1"><i class="fa fa-edit"></i></button>', "className": "text-center" },
            { "defaultContent": '<button type="button" class="btn btn-success editar_registro1" title="Consultar/Modificar" data-bs-toggle="modal" data-bs-target="#ModalDireccion2"><i class="fa fa-edit"></i></button>', "className": "text-center" }

        ],
    });
    if (roll != 1) {
        table.columns([10, 11]).visible(false);
    } else {
        table.columns([12]).visible(false);
    }
    cambiar_estado("#tabla_direcciones tbody", table);
    obtener_data_editar("#tabla_direcciones tbody", table);
    obtener_data_editar1("#tabla_direcciones tbody", table);
}

var cambiar_estado = function (tbody, table) {
    $(tbody).on("click", "button.estado", function () {
        var data = table.row($(this).parents("tr")).data();
        var valor_btn = $(this).val();
        if (valor_btn == 1) {
            //inactivar usuario
            envio = { 'id_direccion': data.id_direccion, 'estado_direccion': 0 };
        } else {
            //activar usuario
            envio = { 'id_direccion': data.id_direccion, 'estado_direccion': 1 };
        }
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_estados_direccion`,
            "type": 'POST',
            "data": envio,
            "success": function (respuesta) {
                $("#tabla_direcciones").DataTable().ajax.reload();
                alertify.success('Estado cambiado Correctamente');
            }
        });
    });
}

var obtener_data_editar = function (tbody, table) {
    $(tbody).on('click', 'button.editar_registro', function () {
        var data = table.row($(this).parents("tr")).data();
        rellenar_formulario(data);
        $('#modificar_direccion1').attr('data-id', data.id_direccion);
    });
}

var editar_direccion1 = function () {
    $('#form_modificar_direccion1').submit(function (e) {
        e.preventDefault();
        var form = $(this).serialize();
        var id_direccion = $('#modificar_direccion1').attr('data-id');
        var envio = {
            'form': form,
            'id': id_direccion
        };
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_direccion1`,
            "type": 'POST',
            "data": envio,
            "success": function (respu) {
                if (respu) {
                    $("#ModalDireccion1").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                    $("#tabla_direcciones").DataTable().ajax.reload();
                } else {
                    alertify.error('Error inesperado');
                }
            }
        });
    });
}

var obtener_data_editar1 = function (tbody, table) {
    $(tbody).on('click', 'button.editar_registro1', function () {
        var data = table.row($(this).parents("tr")).data();
        $('#cliente').empty().html(data.nombre_empresa);
        $('#nombre_pais').empty().html(data.nombre_pais);
        $('#nombre_departamento').empty().html(data.nombre_departamento);
        $('#nombre_ciudad').empty().html(data.nombre_ciudad);
        $('#direccion').empty().html(data.direccion);
        $('#ruta_actual').empty().html(data.nombre_ruta);
        $('#modificar_direccion2').attr('data-id', data.id_direccion);
    });
}

var editar_direccion2 = function () {
    $('#form_modificar_direccion2').submit(function (e) {
        e.preventDefault();
        var form = $(this).serialize();
        var id_direccion = $('#modificar_direccion2').attr('data-id');
        var envio = {
            'form': form,
            'id': id_direccion
        };
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_direccion1`,
            "type": 'POST',
            "data": envio,
            "success": function (respu) {
                if (respu) {
                    $("#ModalDireccion2").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                    $("#tabla_direcciones").DataTable().ajax.reload();
                } else {
                    alertify.error('Error inesperado');
                }
            }
        });
    });
}

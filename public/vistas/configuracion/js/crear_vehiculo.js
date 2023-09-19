$(document).ready(function () {
    select_2();
    enviar_vehiculo();
    consulta_vehiculo();
});
var enviar_vehiculo = function () {
    $('#form_crear_vehiculo').submit(function (e) {
        e.preventDefault();
        var form1 = $(this).serializeArray();
        var valida = validar_formulario(form1);
        var id_vehiculo = 0;
        if (valida) {
            var form = $(this).serialize();
            $.ajax({
                url: `${PATH_NAME}/enviar_vehiculo`,
                type: 'POST',
                data: { id_vehiculo, form },
                success: function (res) {
                    if (res.status == 1) {
                        alertify.success(res.msg);
                        limpiar_formulario('form_crear_vehiculo', 'input');
                        limpiar_formulario('form_crear_vehiculo', 'select');
                    }
                }
            });
        }
    });
}

var editar = function () {
    $("#tabla_vehiculos tbody").on("click", "button.editar_vehiculo", function () {
        var data = $("#tabla_vehiculos").DataTable().row($(this).parents("tr")).data();
        var id_vehiculo = data.id_vehiculo;
        $('#modificar_vehiculo').on('click', function (e) {
            e.preventDefault();
            var form = $('#form_modifica_vehiculo').serialize();
            $.ajax({
                url: `${PATH_NAME}/enviar_vehiculo`,
                type: 'POST',
                data: { id_vehiculo, form },
                success: function (res) {
                    if (res == true) {
                        alertify.success('Modificacion Exitosa');
                        $("#tabla_vehiculos").DataTable().ajax.reload();
                        limpiar_formulario('form_modifica_vehiculo', 'select');
                        $('#modal_vehiculo').modal('hide');
                    }
                }
            });
        });
    });
}
var consulta_vehiculo = function () {
    var table = $("#tabla_vehiculos").DataTable({
        "ajax": `${PATH_NAME}/consulta_vehiculo`,
        "columns": [
            {
                "data": "id_area_trabajo", render: function (data, type, row) {
                    return row.nombre + ' ' + row.apellido;
                }
            },
            { "data": "placa" },
            { "data": "marca" },
            { "data": "linea" },
            { "data": "modelo" },
            { "data": "color" },
            {
                "data": "servicio", render: function (data, type, row) {
                    if (row.servicio == 1) {
                        return 'Particular';
                    } else {
                        return 'Publico';
                    }
                }
            },
            { "data": "clase_vehiculo" },
            { "data": "carroceria" },
            { "data": "capacidad" },
            {
                "render": function (data, type, row) {
                    return '<button type="button" class="btn btn-primary editar_vehiculo" data-bs-toggle="modal" data-bs-target="#modal_vehiculo"><i class="fa fa-edit"></i></button>';
                }
            },
        ],
    });
    editar();
}
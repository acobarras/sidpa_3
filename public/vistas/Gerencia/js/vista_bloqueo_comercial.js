$(document).ready(function () {
    carga_tabla_cliente();
    bloquear_todos();
});

var carga_tabla_cliente = function () {
    var table1 = $('#tabla_asesores').DataTable({
        "ajax": {
            "url": `${PATH_NAME}/gerencia/consultar_asesores`,
            "type": "GET",

        },
        "columns": [
            {
                "data": "asesor", render: function (data, type, row) {
                    return `${row.nombre} ${row.apellido}`;
                }

            },
            {
                "data": "estado_usu",
                "searchable": false,
                "orderable": false,
                "render": function (data, type, row) {

                    if (row["bloqueo_pedido"] == 0) {
                        return '<button class="btn btn-link estado" value="0"><i style="font-size:25px" class="fa fa-toggle-on text-success"></i></button>';
                    } else {
                        return '<button class="btn btn-link estado" value="1"><i style="font-size:25px" class="fa fa-toggle-off text-danger"></i></button>';
                    }
                }

            },
        ],
    });
    bloquear_asesor();
}

var bloquear_asesor = function () {
    $("#tabla_asesores tbody").on("click", "button.estado", function () {
        var data = $('#tabla_asesores').DataTable().row($(this).parents("tr")).data();
        var valor_btn = $(this).val();
        $(".estado").attr('disabled', 'disabled');
        if (valor_btn == 0) {
            //activar usuario
            envio = { 'id_usuario': data.id_usuario, 'bloqueo_pedido': 1 };
        } else {
            //inactivar usuario
            envio = { 'id_usuario': data.id_usuario, 'bloqueo_pedido': 0 };
        }
        $.ajax({
            "url": `${PATH_NAME}/gerencia/bloquear_asesor`,
            "type": 'POST',
            "data": envio,
            "success": function (res) {
                $("#tabla_asesores").DataTable().ajax.reload(function () {
                    alertify.success('Estado cambiado Correctamente');
                    $(".estado").removeAttr('disabled', '')
                });
            }
        });
    });
}
var bloquear_todos = function () {
    $(".bloquear_todos").on("click", function () {
        var valor_btn = $(this).val();
        $(".bloquear_todos").attr('disabled', 'disabled');
        envio = { 'id_usuario': 0, 'bloqueo_pedido': valor_btn };
        $.ajax({
            "url": `${PATH_NAME}/gerencia/bloquear_asesor`,
            "type": 'POST',
            "data": envio,
            "success": function (res) {
                $("#tabla_asesores").DataTable().ajax.reload(function () {
                    alertify.success('Estado cambiado Correctamente');
                    $(".bloquear_todos").removeAttr('disabled', '')
                });
            }
        });
    });
}
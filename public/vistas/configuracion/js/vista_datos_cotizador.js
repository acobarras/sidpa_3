$(document).ready(function () {
    tabla_datos_cotizador();
    editar_dato();
});

var tabla_datos_cotizador = function () {
    var table = $("#tabla_datos_cotizador").DataTable({
        "ajax": `${PATH_NAME}/comercial/consulta_datos_cotizador`,
        "columns": [
            { "data": "id_cotizador" },
            { "data": "nombre_variable" },
            {
                "data": "valor", render: function (data, type, row) {
                    respu = `<input autocomplete="off" type="text" class="form-control editar" id="valor${row.id_cotizador}" value="${row.valor_campo}">`;
                    return respu;
                }
            },
            { "data": "fecha_modifi" },
        ],
    });
}

function editar_dato() {
    $("#tabla_datos_cotizador tbody").on("change", "input.editar", function (e) {
        e.preventDefault();
        console.log($(this).val());
        var data = $("#tabla_datos_cotizador").DataTable().row($(this).parents("tr")).data();
        var envio = {
            nuevo_numero: $(this).val(),
            id_cotizador: data.id_cotizador
        }
        alertify.confirm('Confirmación Envio', 'Esta seguro que desea realizar este cambio.',
            function () {
                $.ajax({
                    url: `${PATH_NAME}/comercial/editar_cotizador`,
                    type: 'POST',
                    data: envio,
                    success: function (res) {
                        console.log(res);
                        if (res) {
                            $('#tabla_datos_cotizador').DataTable().ajax.reload();
                            alertify.success('Datos Modificados correctamente.');
                        } else {
                            $('#tabla_datos_cotizador').DataTable().ajax.reload();
                            alertify.error('Error al tratar de grabar los datos.');
                        }
                    }
                });
            },
            function () {
                $('#tabla_datos_cotizador').DataTable().ajax.reload();
                alertify.error('Operacion Cancelada');
            });
    });
}
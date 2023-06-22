$(document).ready(function () {
    cargar_tabla_consecutivos();
});

var cargar_tabla_consecutivos = function () {
    var table = $(`#tabla_consecutivos`).DataTable({
        "ajax": {
            "url": `${PATH_NAME}/configuracion/consultar_consecutivo`,
        },
        "columns": [
            { "data": "id_consecutivo" },
            { "data": "nombre" },
            {
                "data": "numero", render: function (data, type, row) {
                    var editor = `<input class="form-control editar" type="text" value="${row.numero_guardado}" />`;
                    return editor;
                }
            },
        ],
    });
    consecutivo_editar("#tabla_consecutivos tbody", table);
}

var consecutivo_editar = function (tbody, table) {
    $(tbody).on("change", "input.editar", function (e) {
        e.preventDefault();
        var data = table.row($(this).parents("tr")).data();
        var envio = {
            nuevo_numero: $(this).val(),
            id_consecutivo: data.id_consecutivo
        }
        alertify.confirm('Confirmación Envio', 'Esta seguro que desea realizar este cambio.',
            function () {
                $.ajax({
                    url: `${PATH_NAME}/configuracion/editar_consecutivo`,
                    type: 'POST',
                    data: envio,
                    success: function (res) {
                        console.log(res);
                        if (res) {
                            $('#tabla_consecutivos').DataTable().ajax.reload();
                            alertify.success('Datos Modificados correctamente.');
                        } else {
                            $('#tabla_consecutivos').DataTable().ajax.reload();
                            alertify.error('Error al tratar de grabar los datos.');
                        }
                    }
                });
            },
            function () {
                $('#tabla_consecutivos').DataTable().ajax.reload();
                alertify.error('Operacion Cancelada');
            });
    });
}

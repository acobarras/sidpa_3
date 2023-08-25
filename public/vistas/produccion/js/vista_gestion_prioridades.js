$(document).ready(function () {
    consultar_prioridades();
    enviar_prioridad();
});

var consultar_prioridades = function () {
    var estado = '1,2';
    $.ajax({
        type: "POST",
        url: `${PATH_NAME}/produccion/consultar_prioridades`,
        data: { estado },
        success: function (response) {
            var table = $("#tabla_gestion_pri").DataTable({
                "data": response.data,
                "columns": [
                    { "data": "id_prioridad" },
                    { "data": "num_produccion" },
                    {
                        "data": "item", render: function (data, type, row) {
                            var texto = row.item
                            if (texto == 0) {
                                texto = 'Completo';
                            }
                            return texto;
                        }
                    },
                    { "data": "fecha_comp" },
                    { "data": "observacion" },
                    {
                        render: function (data, type, row) {
                            if (row.estado == 1 || row.estado == 2) {
                                return `
                        <div class="select_acob text-center">
                            <button class="btn btn-info btn-sm pasar_prioridad" title="Pasar Prioridad" data_id=${row.id_prioridad} data_observacion="${row.observacion}" data-bs-toggle="modal" data-bs-target="#obs_prioridad" style="margin-top: 5px;"><i class="fas fa-people-arrows"></i></button>
                            <button class="btn btn-success btn-sm cerrar_prioridad" data_id=${row.id_prioridad} title="Cerra Prioridad" style="margin-top: 5px;"><i class="fas fa-lock"></i></button>
                        </div>`;
                            } else {
                                return '<h5 style="color:red">Prioridad Cerrada</h5>'
                            }
                        }
                    }
                ],
            });
            incompleta(`#tabla_gestion_pri tbody`, table);
            cerrar_prioridad(`#tabla_gestion_pri tbody`, table);
        }
    });
}

var incompleta = function (tbody, table) {
    $(tbody).on('click', `tr button.pasar_prioridad`, function (e) {
        e.preventDefault();
        var id_prioridad = $(this).attr('data_id');
        $('#id_prioridad').val(id_prioridad);
        $('#estado').val(2);
    });
}
var cerrar_prioridad = function (tbody, table) {
    $(tbody).on('click', `tr button.cerrar_prioridad`, function (e) {
        e.preventDefault();
        var id_prioridad = $(this).attr('data_id');
        var form = `id_prioridad=${id_prioridad}&estado=3`
        $.ajax({
            url: `${PATH_NAME}/produccion/enviar_prioridad`,
            type: 'POST',
            data: { form },
            success: function (res) {
                if (res.status == 1) {
                    alertify.success(res.msg);
                    btn_procesando('enviar_prioridad', obj_inicial, 1);
                    location.reload();
                }
            }
        });
    });
}
var enviar_prioridad = function () {
    $('#enviar_gestion').on('click', function (e) {
        e.preventDefault();
        var obj_inicial = $('#enviar_prioridad').html();
        var form = $('#observacion_gestion').serializeArray();
        var excepcion = ['id_prioridad', 'estado'];
        btn_procesando('enviar_prioridad');
        var valida = validar_formulario(form, excepcion);
        if (valida) {
            var form = $('#observacion_gestion').serialize();
            $.ajax({
                url: `${PATH_NAME}/produccion/enviar_prioridad`,
                type: 'POST',
                data: { form },
                success: function (res) {
                    if (res.status == 1) {
                        alertify.success(res.msg);
                        btn_procesando('enviar_prioridad', obj_inicial, 1);
                        location.reload();
                    }
                }
            });
        }
    });
}
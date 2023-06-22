$(document).ready(function () {
    tabla_solicitud_descargos();
    modificar_descargos();
    tabla_ejecucion_descargos();
    modificar_reprograma();
    tabla_pendiente_respuesta();
    modificar_responder();
    tabla_todos_descargos();
});

var tabla_solicitud_descargos = function () {
    var table = $('#tabla_descargos').DataTable({
        "ajax": `${PATH_NAME}/talento_humano/tabla_descargos`,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        "scrollX": true,
        columns: [
            {
                "defaultContent": `
                <button type="button" class="editar btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fas fa-pen"></i>
                </button>`
            },
            { "data": "nombre_estado" },
            { "data": "fecha_crea" },
            { "data": "lider" },
            { "data": "colaborador" },
            { "data": "fecha_falla" },
            { "data": "hora_falla" },
            { "data": "soporte" },
            {
                "data": "turno", render: function (data, type, row) {
                    var respu = `${row.fecha_oficina_desde} / ${row.fecha_oficina_hasta}`;
                    return respu;
                }
            },
            {
                "data": "descripcion", render: function (data, type, row) {
                    var res = `<textarea style="height: 100px; width: 300px; resize: none; text-align: justify; white-space: normal;" >${row.descripcion_falla}</textarea>`;
                    return res;
                }
            },
        ],
        "deferRender": true,
        "stateSave": true,
    });
    fecha_citacion("#tabla_descargos tbody", table);
}

var fecha_citacion = function (tbody, table) {
    $(tbody).on("click", "button.editar", function () {
        var data = table.row($(this).parents("tr")).data();
        $('.boton-x').attr('formulario', data.id_descargo);
    });
}

var modificar_descargos = function () {
    $('#editar-descargo').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var validar = validar_formulario(form);
        if (validar) {
            var form = $('#editar-descargo').serialize();
            var id_descargo = $('.boton-x').attr('formulario');
            var estado = 2;
            $.ajax({
                url: `${PATH_NAME}/talento_humano/editar_descargos`,
                type: 'POST',
                data: { form, id_descargo, estado },
                success: function (res) {
                    // recargar tabla
                    if (res == true) {
                        alertify.success('Modificación correcta');
                        $('#tabla_descargos').DataTable().ajax.reload();
                        $('#tabla_ejecucion').DataTable().ajax.reload();
                        $('#editar-descargo')[0].reset();
                        $('#exampleModal').modal('hide');
                    } else {
                        alertify.error('Ocurrio un error');
                    }
                }
            });
        }
    });
}

var tabla_ejecucion_descargos = function () {
    var table = $('#tabla_ejecucion').DataTable({
        "ajax": `${PATH_NAME}/talento_humano/tabla_ejecucion_descargos`,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        "scrollX": true,
        columns: [
            {
                "defaultContent": `
                <button type="button" class="gestionado btn btn-success" >
                    <i class="fas fa-check"></i>
                </button>
                <button type="button" class="cancelado btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalReprograma">
                    <i class="fas fa-times"></i>
                </button>`
            },
            { "data": "nombre_estado" },
            { "data": "fecha_citacion" },
            { "data": "hora_citacion" },
            { "data": "lider" },
            { "data": "colaborador" },
            { "data": "fecha_falla" },
            { "data": "hora_falla" },
            {
                "data": "descripcion", render: function (data, type, row) {
                    var res = `<textarea style="height: 100px; width: 300px; resize: none; text-align: justify; white-space: normal;" >${row.descripcion_falla}</textarea>`;
                    return res;
                }
            },
        ],
        "deferRender": true,
        "stateSave": true,
    });
    gestionado("#tabla_ejecucion tbody", table);
    cancelado("#tabla_ejecucion tbody", table);
}

var gestionado = function (tbody, table) {
    $(tbody).on("click", "button.gestionado", function () {
        var data = table.row($(this).parents("tr")).data();
        var fecha = FECHA_HOY;
        var hora = actual();
        var form = `fecha_ejecucion=${fecha}&hora_ejecucion=${hora}`;
        var id_descargo = data.id_descargo;
        var estado = 4;
        $.ajax({
            url: `${PATH_NAME}/talento_humano/editar_descargos`,
            type: 'POST',
            data: { form, id_descargo, estado },
            success: function (res) {
                // recargar tabla
                if (res) {
                    alertify.success('Modificación correcta');
                    $('#tabla_ejecucion').DataTable().ajax.reload();
                    $('#tabla_pendientes').DataTable().ajax.reload();
                } else {
                    alertify.error('Ocurrio un error');
                }
            }
        });
    });
}

var cancelado = function (tbody, table) {
    $(tbody).on("click", "button.cancelado", function () {
        var data = table.row($(this).parents("tr")).data();
        $('.boton-y').attr('formulario', data.id_descargo);
    });
}

var modificar_reprograma = function () {
    $('#reprograma-descargo').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var validar = validar_formulario(form);
        if (validar) {
            var form = $('#reprograma-descargo').serialize();
            var id_descargo = $('.boton-y').attr('formulario');
            var estado = 3;
            $.ajax({
                url: `${PATH_NAME}/talento_humano/editar_descargos`,
                type: 'POST',
                data: { form, id_descargo, estado },
                success: function (res) {
                    // recargar tabla
                    if (res) {
                        alertify.success('Modificación correcta');
                        $('#tabla_ejecucion').DataTable().ajax.reload();
                        $('#reprograma-descargo')[0].reset();
                    } else {
                        alertify.error('Ocurrio un error');
                    }
                }
            });
        }
    });
}

var tabla_pendiente_respuesta = function () {
    var table = $('#tabla_pendientes').DataTable({
        "ajax": `${PATH_NAME}/talento_humano/tabla_pendiente_respuesta_descargos`,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        "scrollX": true,
        columns: [
            {
                "defaultContent": `
                <button type="button" class="responder btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRespuesta" >
                    <i class="fas fa-pen"></i>
                </button>`
            },
            { "data": "nombre_estado" },
            { "data": "fecha_ejecucion" },
            { "data": "hora_ejecucion" },
            { "data": "lider" },
            { "data": "colaborador" },
            { "data": "fecha_falla" },
            { "data": "hora_falla" },
            {
                "data": "descripcion", render: function (data, type, row) {
                    var res = `<textarea style="height: 100px; width: 300px; resize: none; text-align: justify; white-space: normal;" >${row.descripcion_falla}</textarea>`;
                    return res;
                }
            },
        ],
        "deferRender": true,
        "stateSave": true,
    });
    responder("#tabla_pendientes tbody", table);

}

var responder = function (tbody, table) {
    $(tbody).on("click", "button.responder", function () {
        var data = table.row($(this).parents("tr")).data();
        $('.boton-z').attr('formulario', data.id_descargo);
    });
}

var modificar_responder = function () {
    $('#responder-descargo').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var validar = validar_formulario(form);
        if (validar) {
            var form = $('#responder-descargo').serialize();
            var id_descargo = $('.boton-z').attr('formulario');
            var estado = 5;
            $.ajax({
                url: `${PATH_NAME}/talento_humano/editar_descargos`,
                type: 'POST',
                data: { form, id_descargo, estado },
                success: function (res) {
                    // recargar tabla
                    if (res) {
                        alertify.success('Modificación correcta');
                        $('#tabla_pendientes').DataTable().ajax.reload();
                        $('#tabla_todos_descargos').DataTable().ajax.reload();
                        $('#responder-descargo')[0].reset();
                        $('#modalRespuesta').modal('hide');
                    } else {
                        alertify.error('Ocurrio un error');
                    }
                }
            });
        }
    });
}

var tabla_todos_descargos = function () {
    var table = $('#tabla_todos_descargos').DataTable({
        "ajax": `${PATH_NAME}/talento_humano/tabla_todos_descargos`,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        "scrollX": true,
        columns: [
            { "data": "nombre_estado" },
            { "data": "fecha_crea" },
            { "data": "lider" },
            { "data": "colaborador" },
            { "data": "soporte" },
            {
                "data": "fecha_hora_falla", render: function (data, type, row) {
                    return `${row.fecha_falla} ${row.hora_falla}`;
                }
            },
            {
                "data": "descripcion", render: function (data, type, row) {
                    var res = `<textarea style="height: 100px; width: 300px; resize: none; text-align: justify; white-space: normal;" >${row.descripcion_falla}</textarea>`;
                    return res;
                }
            },
            {
                "data": "fecha_hora_cita", render: function (data, type, row) {
                    return `${row.fecha_citacion} ${row.hora_citacion}`;
                }
            },
            {
                "data": "fecha_hora_ejecu", render: function (data, type, row) {
                    return `${row.fecha_ejecucion} ${row.hora_ejecucion}`;
                }
            },
            {
                "data": "fecha_hora_respu", render: function (data, type, row) {
                    var respu = `${row.fecha_respuesta} / ${row.hora_respuesta}`;
                    return respu;
                }
            },
            {
                "data": "descripcion", render: function (data, type, row) {
                    var res = `<textarea style="height: 100px; width: 300px; resize: none; text-align: justify; white-space: normal;" >${row.resumen_respuesta}</textarea>`;
                    return res;
                }
            },
        ],
        "deferRender": true,
        "stateSave": true,
    });
}

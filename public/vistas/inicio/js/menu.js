$(document).ready(function () {
    mostrar_menu();
    modal_contrasena();
    cambio_contraseña_user();
    prioridades();
    $("#prioridades").modal("show");
    $("#chequeo").modal("show");
});

// FUNCIÓN PARA DESPLEGAR MENU GLOBAL (SIDEBAR) LATERAL .
var mostrar_menu = function () {
    $('#sbuton').on('click', function () {
        $('#menu_lateral').toggleClass('abrir');
    });
}

// FUNCIÓN PARA ABRI MODAL DE CAMBIO DE CONTRASEÑA.
var modal_contrasena = function () {
    $("#cambioClave").modal("show");
}
// FUNCIÓN PARA SOLICITAR CAMBIO DE CONTRASEÑA AL USUARIO CUANDO INICIA SESIÓN.
var cambio_contraseña_user = function () {
    $('#actualizar_clave').click(function (e) {
        e.preventDefault();
        pasword = $('#pasword').val();
        pasword_conf = $('#pasword-conf').val();
        if (pasword == '') {
            alertify.error('Ingrese la contraseña !!');
            return;
        }
        if (pasword != pasword_conf) {
            alertify.error('la clave no coincide !!');
            return;
        }
        $.ajax({
            url: `${PATH_NAME}/usuario/actualizar_contrasena`,
            type: 'POST',
            data: { pasword },
            success: function (res) {
                if (res == '1') {
                    window.location = PATH_NAME;
                }
            }
        });

    });
}

var prioridades = function () {
    var data = JSON.parse($('#data_prioridad').val());
    var tb_prioridades = $(`#tb_prioridades`).DataTable({
        "data": data,
        "columns": [
            { "data": "id_prioridad" },
            { "data": "mensaje" },
            {
                "render": function (data, type, row) {
                    if (row.pedido == 0) {
                        return 'N/A';
                    } else {
                        return row.pedido;
                    }
                }
            },
            {
                "render": function (data, type, row) {
                    if (row.item == 0) {
                        return 'N/A';
                    } else {
                        return row.item;
                    }
                }
            },
            { "data": "fecha_mensaje" },
            {
                "render": function (data, type, row) {
                    return row.nombre + ' ' + row.apellido;
                }
            },

            {
                "render": function (data, type, row) {
                    botones = `
                <center>
                    <button type='button' title='responder' class='btn btn-info btn-circle responder'>
                        <span class="fas fa-search"></span>
                    </button>
                <center>`;
                    return botones;
                }
            },
        ],
    });
    respuesta_prioridad('#tb_prioridades tbody', tb_prioridades);
}
var detaConsulta = [];
var respuesta_prioridad = function (tbody, table) {
    $(tbody).on("click", "button.responder", function () {
        var data = $('#tb_prioridades').DataTable().row($(this).parents("tr")).data();
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var id = $.inArray(tr.attr('id_prioridad'), detaConsulta);
        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();
            detaConsulta.splice(id, 1);
        } else {
            var data = $('#tb_prioridades').DataTable().row($(this).parents("tr")).data();
            tr.addClass('details');
            row.child(mostrar_formulario(data)).show();
            $('#area').select2();
            enviar_formulario();
        }
    });
}

var mostrar_formulario = function (data) {
    var respuesta = /*html*/
        `
    <div class="mx-3 row row-cols-2">
    <div class="overflow-auto p-3 bg-light col-6" style="max-height: 15rem;">
    <h3 class="text-primary text-center">Historial Mensajes</h3>
    `
    data.mensajes_prioridad.forEach(element => {
        respuesta += `
            <b class="text-success">${element.nombre_area_trabajo} </b><b>${element.nombre} ${element.apellido}: ${element.fecha_crea}</b><br> ${element.mensaje}<hr>`;
    });
    respuesta += `
        </div>
        <form id="form_respuesta${data.id_prioridad}">
            <div class="m-auto justify-content-center col-12 text-center">
                <label class="col-form-label" for="area" style="font-family: 'gothic'; font-weight: bold; ">Area:<br><span class="text-info">Para mantener la solicitud abierta seleccione siempre el area que debe responder su solicitud</span></label>
                <select class="form-control select_2" name="area" id="area">
                    <option value="0">Respuesta Definitiva</option>
                    <option value="${data.id_area_trabajo}">${data.nombre_area_trabajo}</option>
                    `;
    var areas_prio = data.areas_implicada;
    areas_prio.forEach(element_area => {
        respuesta += `<option value="${element_area.id_area_trabajo}">${element_area.nombre_area_trabajo}</option>`;
    });
    respuesta += `</select>
            </div>
            <div class="m-auto justify-content-center col-12 text-center">
                <label class="col-form-label" for="observacion" style="font-family: 'gothic'; font-weight: bold; ">Mensaje</label>
                <textarea class="form-control" name="observacion" id="observacion"></textarea>
            </div>
            <span class="text-danger"><b>Nota:</b>Cualquier duda con la respuesta de la prioridad comunicarse con el area de servicio al cliente</span>
            <br>
            <div class="text-center">
                <button class="btn btn-success enviar_form" id="enviar_prioridad${data.id_prioridad}" type="button" data_pri='${data.id_prioridad}'>
                    <i class="fa fa-plus-circle"></i> Enviar
                </button>
            </div>
        </form>
    </div>
`;
    // <span class="text-danger">si quiere cerrar el caso no seleccione el area </span>
    return respuesta;
}

var enviar_formulario = function () {
    $('.enviar_form').on('click', function (e) {
        e.preventDefault();
        var id_prioridad = $(this).attr('data_pri');
        var obj_inicial = $(`#enviar_prioridad${id_prioridad}`).html();
        var form1 = $(`#form_respuesta${id_prioridad}`).serializeArray();
        var excepcion = ['area'];
        var valida = validar_formulario(form1, excepcion);
        if (valida) {
            var form = $(`#form_respuesta${id_prioridad}`).serialize();
            btn_procesando(`enviar_prioridad${id_prioridad}`);
            $.ajax({
                url: `${PATH_NAME}/mensaje_prioridad`,
                type: 'POST',
                data: { form, id_prioridad },
                success: function (res) {
                    if (res.status == 1) {
                        btn_procesando(`enviar_prioridad${id_prioridad}`, obj_inicial, 1);
                        window.location.reload();
                    } else {
                        btn_procesando(`enviar_prioridad${id_prioridad}`, obj_inicial, 1);
                        alertify.error('El area seleccionada no puede responder');
                    }
                }
            });
        }
    })
}
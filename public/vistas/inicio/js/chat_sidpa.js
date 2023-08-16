$(document).ready(function () {
    vista_chat();
});

var vista_chat = function () {
    $.get(`${PATH_NAME}/chatsidpa/vista_chat`,
        function (respu) {
            $('#vista_chat_sidpa').html(respu);
            conversacion();
            cerrar();
            busca_chat();
            envio_mensaje();
        }
    );
}

var busca_chat = function () {
    $('#busca_chat').on('keyup', function () {
        var buscar = $(this).val();
        let expresion = new RegExp(`${buscar}.*`, "i");
        var data = JSON.parse($(this).attr('data-list'));
        data.forEach(element => {
            element.busca = `${element.nombre} ${element.apellido}`;
        });
        var filtro = data.filter(lista => expresion.test(lista.busca));
        var new_list = '';
        filtro.forEach(element => {
            new_list += `<div class="conversa position-relative" id-usuario="${element.id_usuario}" id="usuario${element.id_usuario}" data-nombre="${element.nombre} ${element.apellido}">
                        <h6>${element.nombre} ${element.apellido}<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">${element.msg_pend}
                        </span></h6>
                    </div>
                    <hr>`;
        });
        $('#filtro').empty().html(new_list);
        conversacion();
    });
}
var conversacion = function () {
    $('.conversa').click(function () {
        var persona_conversa = $(this).attr('id-usuario');
        var data_nombre = $(this).attr('data-nombre');
        $.ajax({
            type: 'POST',
            url: `${PATH_NAME}/chatsidpa/historico_chat`,
            data: { persona_conversa },
            success: function (data) {
                var historico = '';
                data.forEach(element => {
                    if (element.id_usuario == SESION) {
                        historico += `<div class="row justify-content-start">
                            <div class="col-10">
                                <p class="mb-0">${element.mensaje}</p>
                                <sub><small>${element.fecha_crea}</small></sub>
                            </div>
                        </div>
                        <hr>`;
                    } else {
                        historico += `<div class="row justify-content-end">
                            <div class="col-10">
                                <p class="mb-0">${element.mensaje}</p>
                                <sub><small>${element.fecha_crea}</small></sub>
                            </div>
                        </div>
                        <hr>`;
                    }
                });
                $('#containerMessages').empty().html(historico);
                $('#usuarios').addClass('d-none');
                $('.conversacion').removeClass('d-none');
                $('#contacto').val(persona_conversa);
                $('#nombre_chat').empty().html(data_nombre);
            }
        });
    });
}
var cerrar = function () {
    $('#cerrar_chat').click(function () {
        var id_contacto = $('#contacto').val();
        $('.conversacion').addClass('d-none');
        $('#usuarios').removeClass('d-none');
        $('#busca_chat').val('');
        $('#nombre_chat').empty().html('Chat Sidpa');
        // $(`#usuario${id_contacto} span`).html('0');
        $.ajax({
            type: 'GET',
            url: `${PATH_NAME}/chatsidpa/mensaje_pendiente`,
            success: function (data) {
                $('#busca_chat').attr('data-list',JSON.stringify(data));
                $('#busca_chat').keyup();
            }
        });
    });
}

var envio_mensaje = function () {
    $('#formChat').on('submit', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                type: 'POST',
                url: `${PATH_NAME}/chatsidpa/mensajes_chat`,
                data: form,
                success: function (data) {
                    send(data);
                    $('#message').val('').focus();
                }
            })
        }
        return;
    });
};
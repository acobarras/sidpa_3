$(document).ready(function () {
    listar_colaborador();
    soporte_falla();
    cargar_solicitud_descargos();
    $('.select2').select2();
    alertify.set('notifier', 'position', 'bottom-left');
    cargar_solicitud_personal();
});

var listar_colaborador = function () {
    $('#id_lider_proceso').on('change', function (e) {
        e.preventDefault();
        var dato = $(this).val();
        $.ajax({
            url: `${PATH_NAME}/talento_humano/colaborador_lider`,
            type: 'POST',
            data: { dato },
            success: function (res) {
                var items = `<option value="0"></option>`;
                for (var i = 0; i < res.length; i++) {
                    items += `
                    <option value="${res[i].id_persona}">${res[i].nombres} ${res[i].apellidos}</option>
                    `;
                }
                $('#id_colaborador').empty().html(items);
                $('#id_colaborador').select2();
            }
        });
    });
}

var soporte_falla = function () {
    $('.soporte_falla').on('click', function (e) {
        var elegido = $(this).val();
        var mensaje = '';
        if (elegido == 1) {
            var mensaje = `Por favor enviar el soporte al correo diego.wilches@acobarras.com previo a la creacióm de la solicitud`;
        }
        $('#mensaje').empty().html(mensaje);
        $('#mensaje').css('color', 'red');
    });
}

var cargar_solicitud_descargos = function () {
    $('#solicitud_descargos').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var validar = validar_formulario(form);
        if (validar) {
            form = $(this).serialize();
            $.ajax({
                url: `${PATH_NAME}/talento_humano/add_descargo`,
                type: 'POST',
                data: form,
                success: function (res) {
                    if (res.estado = 'true') {
                        $('#solicitud_descargos')[0].reset();
                        $('#id_lider_proceso').val(null).trigger('change');
                        $('#mensaje').empty().html('');
                        alertify.success(`Formulario Registrado Correctamente No registro: ${res.id}.`);
                    }
                }
            });
        }
    });
}

var cargar_solicitud_personal = function () {
    $('#solicitud_personal').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var validar = validar_formulario(form);
        if (validar) {
            form = $(this).serialize();
            $.ajax({
                url: `${PATH_NAME}/talento_humano/add_personal`,
                type: 'POST',
                data: form,
                success: function (res) {
                    $('#solicitud_personal')[0].reset();
                    $('#id_lider_proceso_personal').val(null).trigger('change');
                    $('#id_perfil_cargo').val(null).trigger('change');
                    alertify.success(`Formulario Registrado Correctamente No registro: ${res.id}.`);
                }
            });
        }
    });
}

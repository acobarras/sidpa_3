$(document).ready(function () {
    select_2();
    carga_data();
    enviar_chequeo();
    cambio_form();
    enviar_chequeo_moto();
});
var carga_data = function () {
    $('#id_vehiculo').on('change', function () {
        var data = JSON.parse($('#id_vehiculo option:selected').attr('data_vehi'));
        $('#propietario_vehiculo').val(data.nombre + ' ' + data.apellido);
    });
    $('#id_moto').on('change', function () {
        var data = JSON.parse($('#id_moto option:selected').attr('data_vehi'));
        $('#propietario_moto').val(data.nombre + ' ' + data.apellido);
    });
}

var cambio_form = function () {
    $('.formulario_chequeo').on('change', function () {
        var valor = $(this).val();
        // 1 es formulario moto y 2 el de carro
        if (valor == 1) {
            $('#form_chequeo_moto').removeClass('d-none');
            $('#form_chequeo_vehiculo').addClass('d-none');
        } else {
            $('#form_chequeo_vehiculo').removeClass('d-none');
            $('#form_chequeo_moto').addClass('d-none');
        }
    })
}

var enviar_chequeo = function () {
    $('#form_chequeo_vehiculo').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        var form1 = document.getElementById('form_chequeo_vehiculo');
        var datos = new FormData(form1);
        var obj_inicial = $('#enviar_chequeo').html();
        datos.append("tipo_vehiculo", "vehiculo");
        if (valida) {
            btn_procesando('enviar_chequeo');
            $.ajax({
                url: `${PATH_NAME}/enviar_chequeo`,
                type: 'POST',
                data: datos,
                processData: false,
                cache: false,
                contentType: false,
                success: function (res) {
                    if (res.status == 1) {
                        alertify.success(res.msg);
                        generar_doc(res.id);
                        btn_procesando('enviar_chequeo', obj_inicial, 1);

                    } else {
                        btn_procesando('enviar_chequeo', obj_inicial, 1);
                        alertify.error(res.msg);
                    }
                }
            });
        }
    });
}
var enviar_chequeo_moto = function () {
    $('#form_chequeo_moto').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        var form1 = document.getElementById('form_chequeo_moto');
        var datos = new FormData(form1);
        var obj_inicial = $('#enviar_chequeo_moto').html();
        datos.append("tipo_vehiculo", "moto")
        if (valida) {
            btn_procesando('enviar_chequeo_moto');
            $.ajax({
                url: `${PATH_NAME}/enviar_chequeo`,
                type: 'POST',
                data: datos,
                processData: false,
                cache: false,
                contentType: false,
                success: function (res) {
                    if (res.status == 1) {
                        alertify.success(res.msg);
                        generar_doc(res.id);
                        btn_procesando('enviar_chequeo_moto', obj_inicial, 1);

                    } else {
                        btn_procesando('enviar_chequeo_moto', obj_inicial, 1);
                        alertify.error(res.msg);
                    }
                }
            });
        }
    });
}

var generar_doc = function (id_chequeo) {
    $.ajax({
        "url": `${PATH_NAME}/pdf_chequeo`,
        "type": 'POST',
        "data": { id_chequeo },
        xhrFields: {
            responseType: 'blob'
        },
        success: function (res) {
            var a = document.createElement('a');
            var url = window.URL.createObjectURL(res);
            a.href = url;
            a.download = 'Chequeo-' + id_chequeo + '.pdf';
            a.click();
            window.URL.revokeObjectURL(url);
            alertify.success('Chequeo Exitoso');
            location.reload();
        }
    });
}
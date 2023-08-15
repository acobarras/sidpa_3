$(document).ready(function () {
    descarga_num_pedido();
    descarga_orden_produccion();
    descarga_lista_empaque();
    acta_entrega();
    cotizacion();
});
// Funcion para descargar el pdf de aacuerdo al numero de pedido

var descarga_num_pedido = function () {
    $('#btn-num-pedido').on('click', function () {
        var num_pedido = $('#num_pedido').val();
        if (num_pedido == '') {
            alertify.warning('ingrese un numero de pedido para continuar.');
            return;
        }
        $.ajax({
            url: `${PATH_NAME}/configuracion/generar_pdf_num_pedido`,
            type: 'POST',
            data: { num_pedido },
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: function (res) {
                $('.boton_cambio_pedido').removeClass('fa fa-download');
                $('.boton_cambio_pedido').addClass('fas fa-spinner fa-spin');
                $('#num_pedido').val('');
            },
            success: function (regreso) {
                // console.log(regreso);
                // $('#respu').empty().html(regreso);
                if (regreso === '0') {
                    alertify.error('No se encontró ese número de pedido');
                } else {
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(regreso);
                    a.href = url;
                    a.download = num_pedido + '_pedido.pdf';
                    a.click();
                    window.URL.revokeObjectURL(url);
                    $('.boton_cambio_pedido').addClass('fa fa-download');
                    $('.boton_cambio_pedido').removeClass('fas fa-spinner fa-spin');
                }
            }
        });
    });
};

// Funcion para descargar el pdf de acuerdo al numero orden de produccion

var descarga_orden_produccion = function () {
    $('#btn-orden-produccion').on('click', function () {
        var orden_produccion = $('#orden_produccion').val();
        if (orden_produccion == '') {
            alertify.warning('ingrese un valor');
            return;
        }

        $.ajax({
            url: `${PATH_NAME}/configuracion/generar_pdf_orden_produccion`,
            type: 'POST',
            data: { orden_produccion },
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: function (res) {
                $('.boton_cambio_op').removeClass('fa fa-download');
                $('.boton_cambio_op').addClass('fas fa-spinner fa-spin');
                $('#orden_produccion').val('');
            },
            success: function (regreso) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(regreso);
                a.href = url;
                a.download = orden_produccion + '_O_P.pdf';
                a.click();
                window.URL.revokeObjectURL(url);
                $('.boton_cambio_op').addClass('fa fa-download');
                $('.boton_cambio_op').removeClass('fas fa-spinner fa-spin');
            }
        });
    });
};


var descarga_lista_empaque = function () {
    $('#btn-lista-empaque').on('click', function () {
        var lista_empaque = $('#lista_empaque').val();
        if (lista_empaque == '') {
            alertify.warning('ingrese un valor');
            $('#lista_empaque').focus();
            return;
        }

        $.ajax({
            url: `${PATH_NAME}/configuracion/generar_pdf_lista_empaque`,
            type: 'POST',
            data: { lista_empaque },
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: function (res) {
                $('.boton_cambio_lista').removeClass('fa fa-download');
                $('.boton_cambio_lista').addClass('fas fa-spinner fa-spin');
                $('#lista_empaque').val('');
            },
            success: function (regreso) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(regreso);
                a.href = url;
                a.download = lista_empaque + '_lista_empaque.pdf';
                a.click();
                window.URL.revokeObjectURL(url);
                $('.boton_cambio_lista').addClass('fa fa-download');
                $('.boton_cambio_lista').removeClass('fas fa-spinner fa-spin');
            }
        });
    });
}

var acta_entrega = function () {
    $('#genera_acta').on('click', function () {
        var num_acta = $('#num_acta').val();
        var estado_pdf = 1;
        if (num_acta == '') {
            alertify.warning('ingrese un valor');
            $('#num_acta').focus();
            return;
        }
        $.ajax({
            url: `${PATH_NAME}/soporte_tecnico/generar_pdf_acta`,
            type: 'POST',
            data: { num_acta, estado_pdf },
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: function (res) {
                $('.boton_acta').removeClass('fa fa-download');
                $('.boton_acta').addClass('fas fa-spinner fa-spin');
                $('#num_acta').val('');
            },
            success: function (regreso) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(regreso);
                a.href = url;
                a.download = 'Acta_ENT-' + num_acta + '.pdf';
                a.click();
                window.URL.revokeObjectURL(url);
                $('.boton_acta').addClass('fa fa-download');
                $('.boton_acta').removeClass('fas fa-spinner fa-spin');
                $('#num_acta').val('');
            },
            error: function (err) {
                alertify.error("No existe un PDF con este numero de acta");
                $('#num_acta').val('');
                $('.boton_acta').addClass('fa fa-download');
                $('.boton_acta').removeClass('fas fa-spinner fa-spin');
            }
        });
    });
}
var cotizacion = function () {
    $('#genera_cotiza').on('click', function () {
        var num_cotiza = $('#num_cotiza').val();
        if (num_cotiza == '') {
            alertify.warning('ingrese un valor');
            $('#num_cotiza').focus();
            return;
        }
        $.ajax({
            url: `${PATH_NAME}/soporte_tecnico/generar_cotizacion`,
            type: 'POST',
            data: { num_cotiza },
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: function (res) {
                $('.boton_cotiza').removeClass('fa fa-download');
                $('.boton_cotiza').addClass('fas fa-spinner fa-spin');
                $('#num_cotiza').val('');
            },
            success: function (response, status, xhr) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(response);
                a.href = url;
                a.download = 'Cotizacion_MA-' + num_cotiza + '.pdf';
                a.click();
                window.URL.revokeObjectURL(url);
                $('.boton_cotiza').addClass('fa fa-download');
                $('.boton_cotiza').removeClass('fas fa-spinner fa-spin');
            },
            error: function (err) {
                alertify.error("No existe un PDF con este numero de cotización");
                $('#num_cotiza').val('');
                $('.boton_cotiza').addClass('fa fa-download');
                $('.boton_cotiza').removeClass('fas fa-spinner fa-spin');
            }
        });
    });
}

$(document).ready(function () {
    $("#inicio_sesion").modal("show");
    var Url = window.location;
    if (Url.pathname == PATH_NAME + '/') {
        $('body').attr('class', 'imagen_login img-fluid');
        $('.imagen_login').css('background-image', `url('${IMG}${PROYECTO}/login/bg-aco2.png')`);
        $('footer').addClass('login_footer');
    }
});
var sesion = {
    init: function () {
        $('#IngresoLog').on('click', sesion.inicio);
    },
    peticion: function (url, parametros, metodo) {
        $.ajax({
            "url": url,
            "type": 'POST',
            "data": parametros,
            success: function (respuesta) {
                metodo(respuesta);
            },
            error: function (error) {
                alert(error);
                console.log(error);
            }
        });
    },
    detener: function (e) {
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        if (e.preventDefault) {
            e.preventDefault();
        }
        if (e.returnValue) {
            e.returnValue = false;
        }

    },
    inicio: function (e) {
        sesion.detener(e);
        var url = `${PATH_NAME}/autenticar`; // para mas informacion en ruta.php
        var parametro = {};
        parametro.usu_usuario = $('#usu_usuario').val(); //usu_nombre_usuario de la variable por post
        parametro.usu_pasword = $('#usu_pasword').val(); //usu_clave de la variable por post
        sesion.peticion(url, parametro, sesion.respuesta); // ajax
    },
    respuesta: function (respuesta) {
        // console.log(respuesta);
        $('#usu_pasword').val('').html('');
        $('#usu_usuario').val('').html('');
        if (respuesta.codigo === 1) {
            window.location = respuesta.url;
            return;
        }
        //Cuando hay un error
        if (respuesta.codigo === -1) {

            var mensaje = $('#mensaje');
            mensaje.addClass('alert-danger');
            mensaje.show('slow');
            mensaje.fadeOut(3000);
            mensaje.empty().html(respuesta.mensaje);
            return;
        }
    }
};
sesion.init();
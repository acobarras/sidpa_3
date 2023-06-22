$(document).ready(function () {
    mostrar_menu();
    modal_contrasena();
    cambio_contraseña_user();

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


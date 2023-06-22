$(document).ready(function () {
    envio_trm();
});

var envio_trm = function () {
    $("#form_envio_trm").on('submit', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        $.ajax({
            url: `${PATH_NAME}/contabilidad/ingreso_trm`,
            type: "POST",
            data: form,
            success: function (res) {
               alertify.success("¡Trm actualizada correctamente!");
               $("#form_envio_trm")[0].reset();
            }
        });
    });
}
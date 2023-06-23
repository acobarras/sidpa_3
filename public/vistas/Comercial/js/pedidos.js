$(document).ready(function () {

});
var envio_nuevo_pedido = function (form, storage) {
    $.ajax({
        url: `${PATH_NAME}/comercial/crear_pedido`,
        method: 'POST',
        data: new FormData(form),
        contentType: false,
        cache: false,
        processData: false,
        success: function (res) {
            if (res.status == 1) {
                localStorage.removeItem('productos_pedido' + res.id_cli_prov);
                $("#alert_aco").modal("show");
                $("#title_modal").empty().html('Alerta Sidpa');
                $("#content_modal").empty().html(res.msg);
            } else {
                alertify.error(res.msg);
            }
        }
    });
}
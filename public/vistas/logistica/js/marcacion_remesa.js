$(document).ready(function () {
    consultar_pedido();
});

var consultar_pedido = function () {
    $('#form_marcacion').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        var obj_inicial = $('#consulta_pedido').html();
        if (valida) {
            var num_pedido = $('#num_pedido').val();
            btn_procesando('consulta_pedido');
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/logistica/consulta_direccion_pedido`,
                data: { num_pedido },
                beforeSend: function (respu) {
                    $("div.div_impresion").empty().html('');
                    $("div.div_impresion").removeClass('d-none');
                },
                success: function (respu) {
                    btn_procesando('consulta_pedido', obj_inicial, 1);
                    $("div.div_impresion").empty().html(respu);
                    $("div.div_impresion").printArea();
                    $("div.div_impresion").addClass('d-none');
                    limpiar_formulario('form_marcacion', 'input');
                }
            });
        }
    });

}
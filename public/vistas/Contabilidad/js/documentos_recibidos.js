$(document).ready(function () {
    valida_documento_recib();
    $(".datepicker").datepicker({ minDate: new Date('2020/01/01') });
    recibe_documento();
});

var valida_documento_recib = function () {
    $("#recibe_docu").submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida_form = validar_formulario(form);
        if (valida_form) {
            $.ajax({
                url: `${PATH_NAME}/contabilidad/valida_documento_recibido`,
                type: "POST",
                data: form,
                success: function (res) {
                    $("#btn_recibe_doc").attr('id_recibe', JSON.stringify(res.num_factura_d)); //damos valor a atributo del boton para anulacion de factura
                    consulta_asesores(res.id_usuarios_asesor, res.id_asesor, 'id_usuarios_asesor_d'); // consulta los asesores de el cliente provedor
                    $("#num_recibe_factura").empty().html(res.num_factura_d);
                    if (res.status == 1) { //status 1 si encuentra una factura
                        $('#no_existe_documento').css('display', "none"); //quita mesaje de no existe documento en portafolio
                        if (!$(".consulta_documento_tg").is(":visible")) { // valida si el formulario esta abierto
                            $(".consulta_documento_tg").toggle(500);
                        }
                        rellenar_formulario(res); // llena los datos del formulario
                        if (res.iva_d == 1) { // valida si tiene iva la factura segun el pedido
                            $('#iva_d').prop('checked', true);
                        } else {
                            $('#iva_d').prop('checked', false);
                        }
                        if (res.modifi_reci_doc == 2) {
                            $("#btn_recibe_doc").attr('disabled', true);
                            $("#span_fecha_recibe_doc").empty().html('Este documento ya tiene una fecha asignada');
                        } else {
                            $("#btn_recibe_doc").attr('disabled', false);
                            $("#span_fecha_recibe_doc").empty().html('');
                        }
                    } else {
                        $("#btn_recibe_doc").attr('id_recibe', ''); //quitamos valor a atributo del boton para que no se valla a enviar algo erroneo

                        if ($(".consulta_documento_tg").is(":visible")) { // valida si el formulario esta abierto
                            $(".consulta_documento_tg").toggle(500);
                        }
                        $('#no_existe_documento').css('display', ''); //mostar mesaje de no existe documento en portafolio
                        $('#iva_d').prop('checked', false);
                        limpiar_formulario('formulario_recibe_documento', 'input'); //limpia formulario
                        limpiar_formulario('formulario_recibe_documento', 'select'); //limpia formulario

                    }
                }
            });
        }
    });
}

var recibe_documento = function () {
    $("#formulario_recibe_documento").submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida_form = validar_formulario(form);
        if (valida_form) {
            alertify.confirm('Alerta Sidpa', '¿Esta seguro que desea recibir este documento?', function () { // advierte sobre la aceptacion de el documento
                var num_factura = JSON.parse($("#btn_recibe_doc").attr('id_recibe'));
                var fecha_recibe = $("#fecha_reci_doc_d_modifi").val();
                $.ajax({
                    url: `${PATH_NAME}/contabilidad/recibe_documento`,
                    type: "POST",
                    data: { num_factura, fecha_recibe },
                    success: function (res) {
                        if (res.status == -1) { //status -1 ya se relaciono esta factura
                            alertify.error(res.msg);
                        } else {
                            alertify.success("Factura recibida correctamente.");
                        }
                    }
                });
            }, function () { alertify.error('Cancelado') }); // cancela operacion factura
        }

    });
}
$(document).ready(function () {
    valida_factura_anular();
    anular_factura();
    valida_factura_pago();
    $(".datepicker").datepicker({ minDate: new Date('2020/01/01') });
    fecha_pago_factura();
    aplica_iva();
    calculo_iva();
    select_2();
});
var valida_factura_anular = function () {
    $("#anular_factura").submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida_form = validar_formulario(form);
        if (valida_form) {
            $.ajax({
                url: `${PATH_NAME}/contabilidad/valida_factura_anular`,
                type: "POST",
                data: form,
                success: function (res) {
                    $("#btn_anula").attr('id_anula', JSON.stringify(res.num_factura_a)); //damos valor a atributo del boton para anulacion de factura
                    consulta_asesores(res.id_usuarios_asesor, res.id_asesor, 'id_usuarios_asesor_a'); // consulta los asesores de el cliente provedor
                    $("#num_anula_factura").empty().html(res.num_factura_a);
                    if (res.status == 1) { //status 1 si encuentra una factura
                        $('#no_factura_anula').css('display', "none"); //quita mesaje de no existe documento en portafolio
                        if (!$(".consulta_anula_factura_tg").is(":visible")) { // valida si el formulario esta abierto
                            $(".consulta_anula_factura_tg").toggle(500);
                        }
                        rellenar_formulario(res); // llena los datos del formulario
                        if (res.iva_a == 1) { // valida si tiene iva la factura segun el pedido
                            $('#iva_a').prop('checked', true);
                        } else {
                            $('#iva_a').prop('checked', false);
                        }

                    } else { //sin no encuentra factura
                        $("#btn_anula").attr('id_anula', ''); //quitamos valor a atributo del boton para que no se valla a enviar algo erroneo
                        if ($(".consulta_anula_factura_tg").is(":visible")) { // valida si el formulario esta abierto
                            $(".consulta_anula_factura_tg").toggle(500);
                        }
                        $('#no_factura_anula').css('display', ''); //mostar mesaje de no existe documento en portafolio
                        $('#iva_a').prop('checked', false);
                        limpiar_formulario('formulario_anula_factura', 'input'); //limpia formulario
                        limpiar_formulario('formulario_anula_factura', 'select'); //limpia formulario
                    }
                }
            });
        }
    });
}
var anular_factura = function () {
    $("#btn_anula").click(function (e) {
        e.preventDefault();
        alertify.confirm('Alerta Sidpa', 'Esta apunto de anular este documento. ¿Esta seguro que desea continuar con la anulación?', function () { // advierte sobre la anulacion de la factura
            var num_factura_anula = JSON.parse($("#btn_anula").attr('id_anula'));
            $.ajax({
                url: `${PATH_NAME}/contabilidad/anula_factura`,
                type: "POST",
                data: { num_factura_anula },
                success: function (res) {
                    if (res.status == -1) { //status -1 ya se relaciono esta factura
                        alertify.error(res.msg);
                    } else {
                        alertify.success("Factura Anulada correctamente.");
                    }
                }
            });
        }, function () { alertify.error('Cancelado') }); // cancela operacion factura
    });
}
var valida_factura_pago = function () {
    $("#fecha_pago_factura").submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida_form = validar_formulario(form);
        if (valida_form) {
            $.ajax({
                url: `${PATH_NAME}/contabilidad/valida_fecha_factura`,
                type: "POST",
                data: form,
                success: function (res) {
                    $("#num_factura_fecha_p").val(res.num_factura_f); //damos valor a atributo del boton para anulacion de factura
                    consulta_asesores(res.id_usuarios_asesor, res.id_asesor, 'id_usuarios_asesor_f'); // consulta los asesores de el cliente provedor
                    $("#num_fecha_pago_factura").empty().html(res.num_factura_f);
                    $("#fecha_pago_f_modifi").val('');
                    $("#total_factura_f_modifi").attr('disabled', true);
                    if (res.status == 1) { //status 1 si encuentra una factura
                        $('#no_factura_fecha').css('display', "none"); //quita mesaje de no existe documento en portafolio
                        if (!$(".consulta_fecha_pago_factura_tg").is(":visible")) { // valida si el formulario esta abierto
                            $(".consulta_fecha_pago_factura_tg").toggle(500);
                        }
                        rellenar_formulario(res); // llena los datos del formulario
                        if (res.iva_f == 1) { // valida si tiene iva la factura segun el pedido
                            $('#iva_f').prop('checked', true);
                        } else {
                            $('#iva_f').prop('checked', false);
                        }
                        total_factura();
                    } else { //sin no encuentra factura
                        $("#num_factura_fecha_p").val(''); //quitamos valor a atributo del boton para que no se valla a enviar algo erroneo
                        if ($(".consulta_fecha_pago_factura_tg").is(":visible")) { // valida si el formulario esta abierto
                            $(".consulta_fecha_pago_factura_tg").toggle(500);
                        }
                        $('#no_factura_fecha').css('display', ''); //mostar mesaje de no existe documento en portafolio
                        $('#iva_f').prop('checked', false);
                        limpiar_formulario('formulario_fecha_pago_factura', 'input'); //limpia formulario
                        limpiar_formulario('formulario_fecha_pago_factura', 'select'); //limpia formulario
                    }
                }
            });
        }
    });
}

var aplica_iva = function () {
    $("#iva_f").change(function (e) {
        e.preventDefault();
        var cintas = parseFloat($("#total_cintas_f_modifi").val());
        var etiquetas = parseFloat($("#total_etiquetas_f_modifi").val());
        var alquiler = parseFloat($("#total_alquiler_f_modifi").val());
        var tecnologia = parseFloat($("#total_tecnologia_f_modifi").val());
        var soporte = parseFloat($("#total_soporte_f_modifi").val());
        var flete = parseFloat($("#total_fletes_f_modifi").val());
        var m_prima = parseFloat($("#total_m_prima_f_modifi").val());
        var cinta_iva = cintas;
        var etiq_iva = etiquetas;
        var alqui_iva = alquiler;
        var tecno_iva = tecnologia;
        var soporte_iva = soporte;
        var flete_iva = flete;
        var m_prima_iva = m_prima;

        if ($('#iva_f').is(':checked')) {
            cinta_iva = (cintas + (cintas * IVA));
            etiq_iva = (etiquetas + (etiquetas * IVA));
            alqui_iva = (alquiler + (alquiler * IVA));
            tecno_iva = (tecnologia + (tecnologia * IVA));
            soporte_iva = (soporte + (soporte * IVA));
            flete_iva = (flete + (flete * IVA));
            m_prima_iva = (m_prima + (m_prima * IVA));
        }
        $("#total_cintas_f_modifi").val(cintas);
        $("#total_etiquetas_f_modifi").val(etiquetas);
        $("#total_alquiler_f_modifi").val(alquiler);
        $("#total_tecnologia_f_modifi").val(tecnologia);
        $("#total_cintas_iva_f_modifi").val(cinta_iva);
        $("#total_etiquetas_iva_f_modifi").val(etiq_iva);
        $("#total_alquiler_iva_f_modifi").val(alqui_iva);
        $("#total_tecnologia_iva_f_modifi").val(tecno_iva);
        $("#total_soporte_iva_f_modifi").val(soporte_iva);
        $("#total_fletes_iva_f_modifi").val(flete_iva);
        $("#total_m_prima_iva_f_modifi").val(m_prima_iva);
        total_factura();

    });
}

var calculo_iva = function () {
    $('.totales').on('keyup', function () {
        var valor = parseFloat($(this).val());
        var name = $(this).attr('name');
        var iva = 0;
        if ($('#iva_f').is(':checked')) {
            iva = IVA;
        }
        var respu = (valor * parseFloat(iva)) + valor;
        this.value = this.value.replace(/[^0-9.]/g, '');
        $(`#${name}_iva_f_modifi`).val(respu);
        total_factura();
    });
    $('.totales').on('blur', function () {
        var name = $(this).attr('name');
        if ($(this).val() == '') {
            $(this).val(0);
            $(`#${name}_iva_modifi`).val(0);
        }
        total_factura();
    });

}
var total_factura = function () {
    var cintas = parseFloat($("#total_cintas_f_modifi").val());
    var etiquetas = parseFloat($("#total_etiquetas_f_modifi").val());
    var alquiler = parseFloat($("#total_alquiler_f_modifi").val());
    var tecnologia = parseFloat($("#total_tecnologia_f_modifi").val());
    var soporte = parseFloat($("#total_soporte_f_modifi").val());
    var flete = parseFloat($("#total_fletes_f_modifi").val());
    var m_prima = parseFloat($("#total_m_prima_f_modifi").val());
    var cinta_iva = cintas;
    var etiq_iva = etiquetas;
    var alqui_iva = alquiler;
    var tecno_iva = tecnologia;
    var soporte_iva = soporte;
    var flete_iva = flete;
    var m_prima_iva = m_prima;

    if ($('#iva_f').is(':checked')) {
        cinta_iva = (cintas + (cintas * IVA));
        etiq_iva = (etiquetas + (etiquetas * IVA));
        alqui_iva = (alquiler + (alquiler * IVA));
        tecno_iva = (tecnologia + (tecnologia * IVA));
        soporte_iva = (soporte + (soporte * IVA));
        flete_iva = (flete + (flete * IVA));
        m_prima_iva = (m_prima_iva + (m_prima_iva * IVA));
    }
    var total_factura = cinta_iva + etiq_iva + alqui_iva + tecno_iva + soporte_iva + flete_iva + m_prima_iva;
    $("#total_factura_f_modifi").val(total_factura);

}




var fecha_pago_factura = function () {
    $("#btn_fecha_pago").click(function (e) {
        e.preventDefault();
        $("#total_factura_f_modifi").attr('disabled', false);
        var form = $("#formulario_fecha_pago_factura").serializeArray();
        var exception = ['total_cintas', 'total_etiquetas', 'total_alquiler', 'total_tecnologia', 'total_soporte','total_fletes','total_m_prima'];
        var valida_form = validar_formulario(form, exception);
        if (valida_form) {
            alertify.confirm('Alerta Sidpa', 'Esta apunto de asignar fecha de pago a este documento. ¿Esta seguro que desea continuar con la asignación?', function () { // advierte sobre la anulacion de la factura
                var obj_inicial = $(`#btn_fecha_pago`).html();
                btn_procesando(`btn_fecha_pago`);
                $.ajax({
                    url: `${PATH_NAME}/contabilidad/fecha_pago_factura`,
                    type: "POST",
                    data: form,
                    success: function (res) {
                        btn_procesando(`btn_fecha_pago`, obj_inicial, 1);
                        if (res.status == -1) { //status -1 ya se asigno pago a esta factura
                            alertify.error(res.msg);
                        } else {
                            alertify.success("Asignación fecha Factura correctamente.");
                            limpiar_formulario('fecha_pago_factura', 'input'); //limpia formulario
                            if ($(".consulta_fecha_pago_factura_tg").is(":visible")) { // valida si el formulario esta abierto
                                $(".consulta_fecha_pago_factura_tg").toggle(500);
                            }
                        }
                    }
                });
            }, function () { alertify.error('Cancelado') }); // cancela operacion factura
        }
    });
}


                                                

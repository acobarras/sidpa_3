$(document).ready(function () {
    valida_factura();
    select_2();
    var d = new Date();
    var dias = sumarDias(d, -2);
    $(".datepicker").datepicker({ minDate: new Date(dias) });
    $("#fecha_vencimiento_modifi").datepicker('disable');
    $("#fecha_factura_a_modifi").datepicker('disable');
    $("#fecha_vencimiento_a_modifi").datepicker('disable');
    aplica_iva();
    calculo_iva();
    envio_factura();
    tb_acobarras_sas();
    tb_acobarras_col();
});
function sumarDias(fecha, dias) {
    fecha.setDate(fecha.getDate() + dias);
    return fecha;
}

var valida_factura = function () {
    $("#consulta_factura").submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida_form = validar_formulario(form);
        if (valida_form) {
            $.ajax({
                url: `${PATH_NAME}/contabilidad/valida_factura`,
                type: "POST",
                data: form,
                success: function (res) {
                    $("#num_factura").empty().html(res.num_factura);
                    if (res.status == 1) { //status 1 si encuentra una factura                      
                        consulta_asesores(res.id_usuarios_asesor, res.id_persona, 'id_usuarios_asesor'); // consulta los asesores de el cliente provedor
                        $("#crea").empty().html(''); // quita contenido del id
                        if (!$(".consulta_factura_tg").is(":visible")) { // valida si el formulario esta abierto
                            $(".consulta_factura_tg").toggle(500);
                        }
                        $('#iva').attr('data', JSON.stringify(res)); // guarda data en check box para la funcion aplica iva
                        rellenar_formulario(res); // llena los datos del formulario
                        fecha_factura(); //funcion de cambio de fecha 
                        if (res.iva == 1) { // valida si tiene iva la factura segun el pedido
                            $('#iva').prop('checked', true);
                        } else {
                            $('#iva').prop('checked', false);
                        }
                        $("#nit_modifi").attr('disabled', true);
                        $("#nombre_empresa_modifi").attr('disabled', true);
                        $("#dias_dados_modifi").attr('readonly', true);
                        $("#fecha_vencimiento_modifi").attr('readonly', true);
                        $("#empresa_modifi").attr('readonly', true);
                        $("#empresa_modifi").attr('disabled', true);
                        $("#id_usuarios_asesor").attr('readonly', true);
                        $("#total_etiquetas_iva_modifi").attr('disabled', true);
                        $("#total_cintas_iva_modifi").attr('disabled', true);
                        $("#total_alquiler_iva_modifi").attr('disabled', true);
                        $("#total_tecnologia_iva_modifi").attr('disabled', true);
                        $("#total_soporte_iva_modifi").attr('disabled', true);
                        $("#total_fletes_iva_modifi").attr('disabled', true);
                        $("#total_m_prima_iva_modifi").attr('disabled', true);
                        $("#total_factura_modifi").attr('readonly', true);
                        total_factura();
                        res.id_cli_prov=1931;
                        if (res.id_cli_prov == ID_CLI_PROV_PQR || res.id_cli_prov == ID_CLI_PROV_NO_PRODUCIR) {
                            $("#nit_modifi").attr('disabled', false);
                            crea_factura_nueva();
                        }
                    } else if (res.status == -1) { //sin no encuentra factura
                        if (!$(".consulta_factura_tg").is(":visible")) { // valida si el formulario esta abierto
                            $(".consulta_factura_tg").toggle(500);
                        }
                        limpiar_formulario('formulario', 'input'); //limpia formulario
                        limpiar_formulario('formulario', 'select'); //limpia formulario
                        alertify.confirm('Alerta Sidpa', 'Esta factura no Existe. ¿Decea crearla?', function () { // advierte que no existe factura
                            $('#iva').prop('checked', true);
                            $("#crea").empty().html('Crea '); // llena contenido del id
                            $("#nit_modifi").attr('disabled', false);
                            $("#nombre_empresa_modifi").attr('disabled', true);
                            $("#dias_dados_modifi").attr('readonly', true);
                            $("#fecha_vencimiento_modifi").attr('readonly', true);
                            $("#empresa_modifi").attr('readonly', true);
                            $("#empresa_modifi").attr('disabled', true);
                            $("#total_etiquetas_iva_modifi").attr('disabled', true);
                            $("#total_cintas_iva_modifi").attr('disabled', true);
                            $("#total_alquiler_iva_modifi").attr('disabled', true);
                            $("#total_tecnologia_iva_modifi").attr('disabled', true);
                            $("#total_soporte_iva_modifi").attr('disabled', true);
                            $("#total_fletes_iva_modifi").attr('disabled', true);
                            $("#total_m_prima_iva_modifi").attr('disabled', true);
                            $('#iva').attr('data', 0); // guarda data en check box para la funcion aplica iva
                            $('#id_usuarios_asesor').empty().html(''); // vacia el id si ha sido cargado alguna vez
                            $("#id_usuarios_asesor").attr('readonly', false); // habilita campo de ese id
                            $("#total_cintas_modifi").val(0); //ponemos en 0 el input para que no genere errores de alculo del iva
                            $("#total_etiquetas_modifi").val(0); //ponemos en 0 el input para que no genere errores de alculo del iva
                            $("#total_alquiler_modifi").val(0); //ponemos en 0 el input para que no genere errores de alculo del iva
                            $("#total_tecnologia_modifi").val(0); //ponemos en 0 el input para que no genere errores de alculo del iva
                            $("#total_soporte_modifi").val(0); //ponemos en 0 el input para que no genere errores de alculo del iva
                            $("#total_fletes_modifi").val(0); //ponemos en 0 el input para que no genere errores de alculo del iva
                            $("#total_m_prima_modifi").val(0); //ponemos en 0 el input para que no genere errores de alculo del iva
                            $("#nit_modifi").attr('placeholder', '» Ingrese Nit a Buscar «'); //mensaje
                            $("#total_factura_modifi").attr('readonly', true);
                            crea_factura_nueva(); // ejecuta funcion de creacion de nueva factura
                        }, function () { alertify.error('Cancelado') }); // cancela nueva factura

                    } else {
                        if ($(".consulta_factura_tg").is(":visible")) { // valida si el formulario esta abierto
                            $(".consulta_factura_tg").toggle(500);
                        }
                        alertify.error('Esta factura ya fue creada para portafolio.');
                    }
                    $("#span_fecha_factura").empty().html(""); //vacia advertencia de fecha factura
                }
            });
        }

    });
}
var aplica_iva = function () {
    $("#iva").change(function (e) {
        e.preventDefault();
        var cintas = parseFloat($("#total_cintas_modifi").val());
        var etiquetas = parseFloat($("#total_etiquetas_modifi").val());
        var alquiler = parseFloat($("#total_alquiler_modifi").val());
        var tecnologia = parseFloat($("#total_tecnologia_modifi").val());
        var soporte = parseFloat($("#total_soporte_modifi").val());
        var fletes = parseFloat($("#total_fletes_modifi").val());
        var m_prima = parseFloat($("#total_m_prima_modifi").val());
        var cinta_iva = cintas;
        var etiq_iva = etiquetas;
        var alqui_iva = alquiler;
        var tecno_iva = tecnologia;
        var soporte_iva = soporte;
        var fletes_iva = fletes;
        var m_prima_iva = m_prima;

        if ($('#iva').is(':checked')) {
            cinta_iva = (cintas + (cintas * IVA));
            etiq_iva = (etiquetas + (etiquetas * IVA));
            alqui_iva = (alquiler + (alquiler * IVA));
            tecno_iva = (tecnologia + (tecnologia * IVA));
            soporte_iva = (soporte + (soporte * IVA));
            fletes_iva = (fletes + (fletes * IVA));
            m_prima_iva = (m_prima + (m_prima * IVA));
        }
        $("#total_cintas_modifi").val(cintas);
        $("#total_etiquetas_modifi").val(etiquetas);
        $("#total_alquiler_modifi").val(alquiler);
        $("#total_tecnologia_modifi").val(tecnologia);
        $("#total_cintas_iva_modifi").val(cinta_iva);
        $("#total_etiquetas_iva_modifi").val(etiq_iva);
        $("#total_alquiler_iva_modifi").val(alqui_iva);
        $("#total_tecnologia_iva_modifi").val(tecno_iva);
        $("#total_soporte_iva_modifi").val(soporte_iva);
        $("#total_fletes_iva_modifi").val(fletes_iva);
        $("#total_m_prima_iva_modifi").val(m_prima_iva);
        total_factura();

    });
}

var calculo_iva = function () {
    $('.totales').on('keyup', function () {
        var valor = parseFloat($(this).val());
        var name = $(this).attr('name');
        var iva = 0;
        if ($('#iva').is(':checked')) {
            iva = IVA;
        }
        var respu = (valor * parseFloat(iva)) + valor;
        this.value = this.value.replace(/[^0-9.]/g, '');
        $(`#${name}_iva_modifi`).val(respu);
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
    var cintas = parseFloat($("#total_cintas_modifi").val());
    var etiquetas = parseFloat($("#total_etiquetas_modifi").val());
    var alquiler = parseFloat($("#total_alquiler_modifi").val());
    var tecnologia = parseFloat($("#total_tecnologia_modifi").val());
    var soporte = parseFloat($("#total_soporte_modifi").val());
    var flete = parseFloat($("#total_fletes_modifi").val());
    var m_prima = parseFloat($("#total_m_prima_modifi").val());
    var cinta_iva = cintas;
    var etiq_iva = etiquetas;
    var alqui_iva = alquiler;
    var tecno_iva = tecnologia;
    var soporte_iva = soporte;
    var flete_iva = flete;
    var m_prima_iva = m_prima;

    if ($('#iva').is(':checked')) {
        cinta_iva = (cintas + (cintas * IVA));
        etiq_iva = (etiquetas + (etiquetas * IVA));
        alqui_iva = (alquiler + (alquiler * IVA));
        tecno_iva = (tecnologia + (tecnologia * IVA));
        soporte_iva = (soporte + (soporte * IVA));
        flete_iva = (flete + (flete * IVA));
        m_prima_iva = (m_prima + (m_prima * IVA));
    }
    var total_factura = cinta_iva + etiq_iva + alqui_iva + tecno_iva + soporte_iva + flete_iva + m_prima_iva;
    $("#total_factura_modifi").val(Math.round(total_factura * 100) / 100);

}
var fecha_factura = function () {
    $("#fecha_factura_modifi").change(function (e) {
        e.preventDefault();
        $("#span_fecha_factura").empty().html("Notá: Si modifica la fecha de factura se recalcúlara la facha de vencimiento de la factura según los días dados.");
        var data = JSON.parse($("#iva").attr('data'));
        var new_feth = moment($(this).val());
        var end_fech = moment(new_feth, 'YYYY-MM-DD');
        var dias_dados = $("#dias_dados_modifi").val();
        end_fech.add(parseFloat(dias_dados), 'days');
        var fecha_vencimiento = '';
        if (data != 0) {
            fecha_vencimiento = moment(end_fech).format('YYYY-MM-DD');
            data.fecha_vencimiento = moment(end_fech).format('YYYY-MM-DD');
            $('#iva').attr('data', JSON.stringify(data));
        } else {
            fecha_vencimiento = moment(end_fech).format('YYYY-MM-DD');
        }
        $('#fecha_vencimiento_modifi').val(fecha_vencimiento);
    });
}
var crea_factura_nueva = function () {
    $("#nit_modifi").blur(function (e) {
        e.preventDefault();
        var nit = $("#nit_modifi").val();
        $.ajax({
            url: `${PATH_NAME}/contabilidad/consulta_empresa_nit`,
            type: "POST",
            data: { nit },
            success: function (res) {
                consulta_asesores(res.id_usuarios_asesor, 0, 'id_usuarios_asesor');
                rellenar_formulario(res); // llena los datos del formulario
                fecha_factura(); //funcion de cambio de fecha 

            }
        });

    });
}

var envio_factura = function () {
    $("#formulario").submit(function (e) {
        e.preventDefault();
        $("#empresa_modifi").attr('disabled', false);
        var span_num_fatu = $("#num_factura").text()
        $("#num_factura_modifi").val(span_num_fatu);
        var form = $(this).serializeArray();
        var exception = ['dias_dados', 'iva', 'total_cintas', 'total_etiquetas', 'total_alquiler', 'total_tecnologia', 'total_soporte', 'total_fletes', 'total_m_prima'];
        var valida = validar_formulario(form, exception);
        if (valida) {
            var obj_inicial = $(`#acepta_factu`).html();
            btn_procesando(`acepta_factu`);
            $.ajax({
                url: `${PATH_NAME}/contabilidad/envio_factura`,
                type: "POST",
                data: form,
                success: function (res) {
                    btn_procesando(`acepta_factu`, obj_inicial, 1);
                    if (res.status == -1) { //status -1 ya se relaciono esta factura
                        alertify.error(res.msg);
                    } else {
                        alertify.success("Factura relacionada correctamente.");
                        if ($(".consulta_factura_tg").is(":visible")) { // valida si el formulario esta abierto
                            $(".consulta_factura_tg").toggle(500);
                        }
                        $("#tabla-acobarras-sas").DataTable().ajax.reload();
                        $("#tabla-acobarras-col").DataTable().ajax.reload();

                    }
                }
            });
        }
    });
}

var tb_acobarras_sas = function () {
    var tb_acobarras_sas = $('#tabla-acobarras-sas').DataTable({
        "ajax": `${PATH_NAME}/contabilidad/consulta_acobarras_sas`,
        dom: 'Bflrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
            tittleAttr: ' Exportar a exel',
            className: 'btn btn-success',
        }],
        "columns": [
            { "data": "num_factura" },
            { "data": "nit" },
            { "data": "nombre_empresa" },
            { "data": "fecha_factura" },
            { "data": "fecha_vencimiento" },
            {
                "data": "iva",
                "render": function (data, type, row) {
                    if (row["iva"] == 1) {
                        return 'Si';
                    } else {
                        return 'No';
                    }
                }
            },
            { "data": "total_etiquetas", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_cintas", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_etiq_cint", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_alquiler", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_tecnologia", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_soporte", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_fletes", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_m_prima", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_factura", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "dias_mora" },
            {
                "data": "asesor",
                "render": function (data, type, row) {
                    var asesor = row["nombres"] + " " + row["apellidos"];
                    return asesor;
                }
            },
            { "data": "nombre_estado" }
        ],
    });
}
var tb_acobarras_col = function () {
    var tb_acobarras_col = $('#tabla-acobarras-col').DataTable({
        "ajax": `${PATH_NAME}/contabilidad/consulta_acobarras_col`,
        dom: 'Bflrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
            tittleAttr: ' Exportar a exel',
            className: 'btn btn-success',
        }],
        "columns": [
            { "data": "num_factura" },
            { "data": "nit" },
            { "data": "nombre_empresa" },
            { "data": "fecha_factura" },
            { "data": "fecha_vencimiento" },
            {
                "data": "iva",
                "render": function (data, type, row) {
                    if (row["iva"] == 1) {
                        return 'Si';
                    } else {
                        return 'No';
                    }
                }
            },
            { "data": "total_etiquetas", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_cintas", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_etiq_cint", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_alquiler", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_tecnologia", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_soporte", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_fletes", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_m_prima", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "total_factura", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            { "data": "dias_mora" },
            {
                "data": "asesor",
                "render": function (data, type, row) {
                    var asesor = row["nombres"] + " " + row["apellidos"];
                    return asesor;
                }
            },
            { "data": "nombre_estado" }
        ],
    });
}
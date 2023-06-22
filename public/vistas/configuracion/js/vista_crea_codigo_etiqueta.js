$(document).ready(function () {
    select_2();
    crear_codigo_final();
});

var crear_codigo_final = function () {
    $('#form_valida_codigo').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var excepcion = ['cod_final', 'desc_final', 'cant_tintas', 'gaf_cort', 'desc_etiq'];
        valida = validar_formulario(form, excepcion);
        if (valida) {
            if ($('#cant_tintas').val() == '') {
                alertify.error(`El campo Cantidad tintas es requerido.`);
                return;
            }
            if ($('#gaf_cort').val() == '') {
                alertify.error(`El campo Grafes y Cortes es requerido.`);
                return;
            }
            if ($('#cant_tintas').val() != '00') {
                if ($('#desc_etiq').val() == '') {
                    alertify.error(`El campo Descripción Etiqueta es requerido.`);
                    return;
                }
            }
            $.ajax({
                "url": `${PATH_NAME}/configuracion/valida_repeticion_codigo`,
                "type": 'POST',
                "data": form,
                "success": function (res) {
                    if (res.status == 1) {
                        $('#cod_final').empty().html(res.codigo_nuevo);
                        $('#desc_final').empty().html(res.descripcion);
                        $('#error_codigo').empty().html('');
                        if ($("#btn-carga-codigo").length == 0) {
                            $('#btn_valida_codigo').after(` <button type="button" class="btn btn-success" id="btn-carga-codigo" data-valida="">
                            <i class="fa fa-check"></i> Usar Codigo
                        </button>`);
                        }
                        usar_codigo();
                    } else {
                        $('#cod_final').empty().html('&nbsp;');
                        $('#desc_final').empty().html('&nbsp;');
                        $('#error_codigo').empty().html(res.msg);
                        $('#btn-carga-codigo').remove();
                    }
                }
            });
        }
    });
}

function usar_codigo() {
    $('#btn-carga-codigo').on('click', function () {
        var cod_final = $('#cod_final').html();
        var desc_final = $('#desc_final').html();
        $('#cod_final').empty().html('&nbsp;');
        $('#desc_final').empty().html('&nbsp;');
        $("#form_valida_codigo")[0].reset();
        limpiar_formulario('form_valida_codigo', 'select');
        $('#gaf_cort').val('').trigger('change');
        $('#btn-carga-codigo').remove();
        $("#Modal_crea_codigo").modal("hide");
        $('#codigo_producto').val(cod_final);
        $('#descripcion_productos').val(desc_final);
        $('#codigo_producto').focus();
        $('#codigo_producto').change();
    });
}
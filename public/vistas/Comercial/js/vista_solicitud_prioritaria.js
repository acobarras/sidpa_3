$(document).ready(function () {
    select_2();
    consultar_pedido();
    enviar_prioridad();
    cambio_actividad();
    CKEDITOR.config.toolbar_mybar = [
        ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'SpellChecker', 'Scayt'],
        ['Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat'],
        ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'],
        ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote'],
        ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
        ['TextColor', 'BGColor'],
        ['Styles', 'Format', 'Font', 'FontSize']
    ];
    CKEDITOR.replace('observacion', { toolbar: 'mybar' });
});

var cambio_actividad = function () {
    $('#actividad').on('change', function () {
        var valor = $(this).val();
        if (valor == 1) {
            $('#form_pedido').addClass('d-none');
        } else {
            $('#form_pedido').removeClass('d-none');
        }
    });
}

var consultar_pedido = function () {
    $('#pedido').on('change', function () {
        var num_pedido = $(this).val();
        if (num_pedido == '') {
            $('#slect_items').empty().html('');
        } else {
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/produccion/consultar_items_pedido_impresion`,
                data: { num_pedido },
                success: function (response) {
                    $('#cliente').val(response[0].nombre_empresa)
                    var items = '';
                    if (response != -1) {
                        response.forEach(element => {
                            items += `
                            <option value='${JSON.stringify(element)}'>Item ${element.item}</option>                   
                            `;
                        });
                    }
                    $('#item').empty().html(items);
                }
            });
        }
    });
}
var enviar_prioridad = function () {
    $('#form_prioridad').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#enviar_prioridad').html();
        var form = $(this).serializeArray();
        var valor = $('#actividad').val();
        var excepcion = ['item', 'observacion'];
        if (valor == 1) {
            excepcion = ['item', 'observacion', 'pedido', 'item', 'cliente'];
        }
        // btn_procesando('enviar_prioridad');
        var valida = validar_formulario(form, excepcion);
        if (valida) {
            var observaciones = CKEDITOR.instances.observacion.getData();
            var id_areas = $('#area').val();
            var form = $(this).serialize();
            form += observaciones;
            if (observaciones == '') {
                alertify.error('El campo observacion es requerido');
                $('#observacion').focus();
                return;
            }
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/enviar_solicitud_prioritaria`,
                data: { form, id_areas },
                success: function (response) {
                    console.log(response);
                }
            });
        }
    });
}
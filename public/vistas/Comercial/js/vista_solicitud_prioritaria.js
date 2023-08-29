$(document).ready(function () {
    select_2();
    consultar_pedido();
    enviar_prioridad();
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
                    var items = '<option value="0">Completo</option>';
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
        var excepcion = ['item', 'observacion'];
        // btn_procesando('enviar_prioridad');
        var valida = validar_formulario(form, excepcion);
        if (valida) {
            var observaciones = CKEDITOR.instances.observacion.getData();
            var form = $(this).serialize();
            if (observaciones == '') {
                alertify.error('El campo observacion es requerido');
                $('#observacion').focus();
                return;
            }
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/produccion/enviar_solicitud_prioritaria`,
                data: { form, observaciones },
                success: function (response) {
                    console.log(response);
                }
            });
        }
    });
}
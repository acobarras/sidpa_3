$(document).ready(function () {
    carga_caso_remoto();
    CKEDITOR.config.toolbar_mybar = [
        ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'SpellChecker', 'Scayt'],
        ['Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat'],
        ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'],
        ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote'],
        ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
        ['TextColor', 'BGColor'],
        ['Styles', 'Format', 'Font', 'FontSize']
    ];
    CKEDITOR.replace('cerrar_caso_remoto', { toolbar: 'mybar' });
});

var valida_url = function () {
    var params = new URLSearchParams(location.search);
    var id_url = params.get('id');
    if (id_url != '') {
        $(`#boton${id_url}`).on('click', function () {
        })
        $(`#boton${id_url}`).trigger('click');
    }
}

var carga_caso_remoto = function () {
    $.ajax({
        url: `${PATH_NAME}/soporte_tecnico/consulta_caso_remoto`,
        type: "POST",
        success: function (res) {
            var table = $("#tb_casos_remotos").DataTable({
                "data": res['data'],
                "columns": [
                    { "data": "id_diagnostico" },
                    { "data": "nombre_empresa" },
                    { "data": "direccion" },
                    { "data": "nombre_estado_soporte" },
                    { "data": "fecha_crea" },
                    {
                        "render": function (data, type, row) {
                            return `<center>
                            <button type='button' title='Cerrar diagnostico' id='boton${row.id_diagnostico}' class='btn btn-success btn-circle cerrar_diag' data-bs-toggle='modal' data-bs-target='#cierre_caso'>
                                <span class='fas fa-search'></span>
                            </button>
                                        <center>`
                        }
                    },
                ]
            });
            enviar_observacion('#tb_casos_remotos tbody', table);
            valida_url();
        }
    });
};

var enviar_observacion = function (tbody, table) {

    $(tbody).on("click", "button.cerrar_diag", function () {
        var data = table.row($(this).parents("tr")).data();
        $('#cerrar_diagnos').on('click', function () {
            CKEDITOR.instances.cerrar_caso_remoto.setData('');
        })
        $('#enviar_diagnos').on('click', function () {
            var observacion = CKEDITOR.instances.cerrar_caso_remoto.getData();
            if (observacion == '') {
                alertify.error('El campo Descripción detallada de la reclamación y observaciones es requerido');
                $('#observacion').focus();
                return;
            } else {
                var envio = {
                    'datos': data,
                    'observacion': observacion,
                }
                var obj_inicial = $('#enviar_diagnos').html();
                btn_procesando('enviar_diagnos');
                $.ajax({
                    url: `${PATH_NAME}/soporte_tecnico/enviar_observacion`,
                    type: "POST",
                    data: { envio },
                    success: function (res) {
                        if (res.status == 1) {
                            alertify.success(res.msg);
                            CKEDITOR.instances.cerrar_caso_remoto.setData('');
                            $('#cierre_caso').modal('hide');
                            carga_caso_remoto();
                        } else {
                            alertify.error(res.msg);
                            CKEDITOR.instances.cerrar_caso_remoto.setData('');
                            $('#cierre_caso').modal('hide');
                            carga_caso_remoto();
                        }
                        btn_procesando('enviar_diagnos', obj_inicial, 1);
                    }
                });
            }
        });
    });
}
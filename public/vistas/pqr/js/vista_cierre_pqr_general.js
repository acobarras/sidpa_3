$(document).ready(function () {
    select_2();
    alertify.set('notifier', 'position', 'bottom-left');
    consultar_pqr();
    consultar_pqr_comite();
    codigo_motivo();
    envio_motivo_pqr();
    cierre_comite();
    CKEDITOR.replace('accion_cierre', { toolbar: 'mybar' });
    CKEDITOR.replace('analisis_pqr_cierre', { toolbar: 'mybar' });
    CKEDITOR.replace('observacion', { toolbar: 'mybar' });
    CKEDITOR.replace('observacion_cierre', { toolbar: 'mybar' });
    envio_cierre_pqr();
});
CKEDITOR.config.toolbar_mybar = [
    ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'SpellChecker', 'Scayt'],
    ['Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat'],
    // ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
    // '/',
    ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'],
    ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote'],
    ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
    ['TextColor', 'BGColor'],
    ['Styles', 'Format', 'Font', 'FontSize']
];

var consultar_pqr = function () {
    $.ajax({
        url: `${PATH_NAME}/pqr/consultar_pqr?consulta=9`,
        type: "GET",
        success: function (res) {
            datos_productos(res, 'tabla_cierre_general_pqr');
        }
    });
}

var consultar_pqr_comite = function () {
    $.ajax({
        url: `${PATH_NAME}/pqr/consultar_pqr?consulta=10`,
        type: "GET",
        success: function (res) {
            datos_productos(res, 'tabla_cierre_comite_pqr');
        }
    });
}

var datos_productos = function (data, nombre_tabla) {
    $(`#${nombre_tabla}`).DataTable({
        "data": data,
        "columns": [
            { "data": "fecha_crea" },
            { "data": "num_pqr" },
            {
                "data": "cliente", render: function (data, type, row) {
                    return row.datos_producto[0].nombre_empresa;
                }
            },
            {
                "data": "pedido_item", render: function (data, type, row) {
                    return `${row.datos_item[0].num_pedido}-${row.datos_item[0].item}`;
                }
            },
            { "data": "descripcion_pqr" },
            { "data": "cantidad_reclama", render: $.fn.dataTable.render.number('.', ',', 0) },
            { "data": "nombre_estado_pqr" },
            {
                "data": "botones", render: function (data, type, row) {
                    var boton = valida_botones(row);
                    return boton;
                },
                "className": "text-center"
            },
        ]
    });
}

var valida_botones = function (row) {
    var boton = '';
    if (row.estado == 9) {
        var boton = `<button type="button" class="btn btn-primary btn-sm codigo_motivo" title="Asignar Codigo Motivo"><i class="fas fa-search"></i></button> `;
    }
    if (row.estado == 10) {
        var boton = `<button type="button" class="btn btn-primary btn-sm cierre_comite" title="Cierre_comite"><i class="fas fa-search"></i></button> `;
    }

    return boton;
}

var codigo_motivo = function () {
    $('#tabla_cierre_general_pqr tbody').on('click', 'button.codigo_motivo', function () {
        var data = $('#tabla_cierre_general_pqr').DataTable().row($(this).parents("tr")).data();
        $('#codigoMotivo').modal('toggle');
        $('#boton_codigo_motivo').attr('data', JSON.stringify(data));
        // $(`#observacion`).empty().html(data.observacion);
        CKEDITOR.instances.observacion.setData(data.observacion);
        CKEDITOR.instances.observacion_cierre.setData(data.observacion);
    });
}

var envio_motivo_pqr = function () {
    $('#boton_codigo_motivo').click(function () {
        var data = JSON.parse($('#boton_codigo_motivo').attr('data'));
        var codigo_motivo = $('#codigo_motivo').val();
        var clasificacion = $('#clasificacion').val();
        var responsable = $('#responsable').val();
        var costo = $('#costo').val();
        if (codigo_motivo == '' || codigo_motivo == 0) {
            alertify.error('Se requiere un codigo motivo para continuar');
            $('#codigo_motivo').focus()
            return;
        }
        if (clasificacion == '' || clasificacion == 0) {
            alertify.error('Se requiere un clasificacion para continuar');
            $('#clasificacion').focus()
            return;
        }
        if (responsable == '' || responsable == 0) {
            alertify.error('Se requiere un responsable para continuar');
            $('#responsable').focus()
            return;
        }
        if (costo == '') {
            alertify.error('Se requiere costo para continuar puede ser 0');
            $('#costo').focus()
            return;
        }
        var observacion = CKEDITOR.instances.observacion.getData();
        if (observacion == '') {
            alertify.error('El campo Observación es requerido');
            $('#observacion_cierre').focus();
            return;
        }
        var obj_inicial = $('#boton_codigo_motivo').html();
        btn_procesando('boton_codigo_motivo');
        $.ajax({
            url: `${PATH_NAME}/pqr/codigo_motivo_pqr`,
            type: "POST",
            data: { data, codigo_motivo, clasificacion, responsable, costo, observacion },
            success: function (res) {
                console.log(res);
                $('#codigoMotivo').modal('hide');
                alertify.success('Datos enviados correctamente');
                btn_procesando('boton_codigo_motivo', obj_inicial, 1);
                consultar_pqr();
                consultar_pqr_comite();
            }
        });
    });
}

var cierre_comite = function () {
    $('#tabla_cierre_comite_pqr tbody').on('click', 'button.cierre_comite', function () {
        var data = $('#tabla_cierre_comite_pqr').DataTable().row($(this).parents("tr")).data();
        $('#cierreComite').modal('toggle');
        $('#boton_cierre_pqr').attr('data', JSON.stringify(data));
        CKEDITOR.instances.accion_cierre.setData('');
        CKEDITOR.instances.analisis_pqr_cierre.setData('');
    });
}

var envio_cierre_pqr = function () {
    $('#boton_cierre_pqr').click(function () {
        var data = JSON.parse($('#boton_cierre_pqr').attr('data'));
        var codigo_motivo_cierre = $('#codigo_motivo_cierre').val();
        var clasificacion_cierre = $('#clasificacion_cierre').val();
        var responsable_cierre = $('#responsable_cierre').val();
        var costo_cierre = $('#costo_cierre').val();
        if (codigo_motivo_cierre == '' || codigo_motivo_cierre == 0) {
            alertify.error('Se requiere un codigo motivo para continuar');
            $('#codigo_motivo_cierre').focus()
            return;
        }
        if (clasificacion_cierre == '' || clasificacion_cierre == 0) {
            alertify.error('Se requiere una clasificacion para continuar');
            $('#clasificacion').focus()
            return;
        }
        var analisis_pqr_cierre = CKEDITOR.instances.analisis_pqr_cierre.getData();
        if (analisis_pqr_cierre == '') {
            alertify.error('El campo Analisis Pqr es requerido');
            $('#analisis_pqr_cierre').focus();
            return;
        }
        var accion_cierre = CKEDITOR.instances.accion_cierre.getData();
        if (accion_cierre == '') {
            alertify.error('El campo Acción Pqr es requerido');
            $('#accion_cierre').focus();
            return;
        }
        if (responsable_cierre == '' || responsable_cierre == 0) {
            alertify.error('Se requiere un responsable para continuar');
            $('#responsable_cierre').focus()
            return;
        }
        if (costo_cierre == '') {
            alertify.error('Se requiere costo para continuar puede ser 0');
            $('#costo_cierre').focus()
            return;
        }
        var observacion_cierre = CKEDITOR.instances.observacion_cierre.getData();
        if (observacion_cierre == '') {
            alertify.error('El campo Observación es requerido');
            $('#observacion_cierre').focus();
            return;
        }

        alertify.confirm('ALERTA ACOBARRAS', '¿Desea enviar correo al cliente?', function () {
            var envio_correo = 1;
            enviar_data(data, codigo_motivo_cierre, clasificacion_cierre, analisis_pqr_cierre, accion_cierre, responsable_cierre, costo_cierre, observacion_cierre, envio_correo);
        }, function () {
            var envio_correo = 2;
            enviar_data(data, codigo_motivo_cierre, clasificacion_cierre, analisis_pqr_cierre, accion_cierre, responsable_cierre, costo_cierre, observacion_cierre, envio_correo);
        })
            .set('labels', { ok: 'Si', cancel: 'No' });

    });
}

var enviar_data = function (data, codigo_motivo_cierre, clasificacion_cierre, analisis_pqr_cierre, accion_cierre, responsable_cierre, costo_cierre, observacion_cierre, envio_correo) {
    var obj_inicial = $('#boton_cierre_pqr').html();
    btn_procesando('boton_cierre_pqr');
    $.ajax({
        url: `${PATH_NAME}/pqr/motivo_cierre_pqr`,
        type: "POST",
        data: { data, codigo_motivo_cierre, clasificacion_cierre, analisis_pqr_cierre, accion_cierre, responsable_cierre, costo_cierre, observacion_cierre, envio_correo },
        success: function (res) {
            $('#cierreComite').modal('hide');
            alertify.success('Datos enviados correctamente');
            btn_procesando('boton_cierre_pqr', obj_inicial, 1);
            consultar_pqr_comite();
        }
    });
}
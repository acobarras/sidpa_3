$(document).ready(function () {
    alertify.set('notifier', 'position', 'bottom-left');
    consulta_documento();
    consulta_nuevo_documento();
    reportar_documento();
    solo_numeros('valor_flete');
});

var PRINCIPAL = [];
var DATOS_TABLA = [];

var consulta_documento = function () {
    $('#consulta_lista_de_empaque').on('click', function () {
        var obj_inicial = $('#consulta_lista_de_empaque').html();
        var numero_lista_de_empaque = $('#numero_factura_consulta').val();
        if (numero_lista_de_empaque == '') {
            alertify.error('Se requiere El numero de lista de empaque');
            return;
        }
        btn_procesando('consulta_lista_de_empaque', obj_inicial);
        $.ajax({
            url: `${PATH_NAME}/entregas/consulta_lista_empaque`,
            type: "POST",
            data: { numero_lista_de_empaque },
            success: function (res) {
                PRINCIPAL = res.cabecera;
                rellenar_span(res.cabecera);
                var numero_relacionado = res.cabecera.num_remision;
                if (res.cabecera.tipo_documento == 8 || res.cabecera.tipo_documento == 9 || res.cabecera.tipo_documento == 6) {
                    numero_relacionado = res.cabecera.num_factura;
                }
                $('#documento_relacionado').empty().html(`${res.cabecera.tipo_documento_letra} ${numero_relacionado}`);
                // Rellenamos la tabla
                DATOS_TABLA = res.items;
                tabla_documentos();
                btn_procesando('consulta_lista_de_empaque', obj_inicial, 1);
                $('#reportar_documento').attr('disabled', false);
            }
        });
    });
}

var tabla_documentos = function (limpio = '') {
    var data = DATOS_TABLA;
    if (limpio != '') {
        data = [];
    }
    $('#tabla_items_pedido').DataTable({
        "data": data,
        'ordering': false,
        columns: [
            { "data": "id_pedido_item" },
            { "data": "num_lista_empaque" },
            { "data": "codigo" },
            { "data": "cantidad_factura" },
            { "data": "descripcion_productos" },
        ]
    });
}

consulta_nuevo_documento = function () {
    $('#nuevo_documento').on('change', function () {
        $('#agrega_documento').attr('disabled', false);
    });
    $('#agrega_documento').on('click', function () {
        var obj_inicial = $('#agrega_documento').html();
        btn_procesando('agrega_documento', obj_inicial);
        var numero_lista_de_empaque = $('#nuevo_documento').val();
        $.ajax({
            url: `${PATH_NAME}/entregas/consulta_lista_empaque`,
            type: "POST",
            data: { numero_lista_de_empaque },
            success: function (res) {
                if (PRINCIPAL.id_cli_prov == res.cabecera.id_cli_prov) {
                    var data_item = res.items;
                    data_item.forEach(element => {
                        DATOS_TABLA.push(element);
                    });
                    tabla_documentos();
                } else {
                    alertify.error(`Lo sentimos el documento ${numero_lista_de_empaque} que intenta agregar no corresponde al mismo cliente`);
                }
                $('#nuevo_documento').val('');
                btn_procesando('agrega_documento', obj_inicial, 1);
                $('#agrega_documento').attr('disabled', true);

            }
        });
    });
}

var reportar_documento = function () {
    $('#reportar_documento').on('click', function () {
        var obj_inicial = $('#reportar_documento').html();
        var valor_flete = $('#valor_flete').val();
        if (valor_flete == 0 || valor_flete == '') {
            alertify.error('Se requiere el valor del flete para continuar');
            return;
        }
        btn_procesando('reportar_documento', obj_inicial);
        var envio = {
            'valor_flete': valor_flete,
            'items': DATOS_TABLA
        }
        $.ajax({
            url: `${PATH_NAME}/entregas/reporte_cargue_transportador`,
            type: "POST",
            data: envio,
            success: function (res) {
                if (res == 1) {
                    alertify.success('Registro guardado exitosamente.');
                } else {
                    alertify.error('Lo sentimos en este momento no puede generar un cargue por que tiene entregas pendientes por gestionar');
                }
                $('#datos_documento span').empty().html('N/A');
                $('#valor_flete').val('');
                $('#numero_factura_consulta').val('');
                tabla_documentos(1);
                btn_procesando('reportar_documento', obj_inicial, 1);
            }
        });
    });
}


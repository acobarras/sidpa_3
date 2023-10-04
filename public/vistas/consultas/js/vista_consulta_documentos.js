$(document).ready(function () {
    alertify.set('notifier', 'position', 'bottom-left');
    consulta_documentos();
});

var PRINCIPAL = [];
var DATOS_TABLA = [];

var consulta_documentos = function () {
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
                if (res.cabecera.tipo_documento == 8 || res.cabecera.tipo_documento == 9) {
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
    $('#tabla_productos_items').DataTable({
        "data": data,
        'ordering': false,
        "searching": false,
        "paging": false,
        "info": false,
        columnDefs: [
            { "width": "10%", "targets": 1 },
        ],
        columns: [
            { "data": "codigo" },
            { "data": "cantidad_factura" },
            { "data": "descripcion_productos" },
        ]
    });
}
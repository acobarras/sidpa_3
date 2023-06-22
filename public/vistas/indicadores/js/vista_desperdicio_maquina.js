$(document).ready(function () {
    $('#empleado').select2();
    alertify.set('notifier', 'position', 'bottom-left');
    solicitud_desperdicio()
});

var myChart;

var solicitud_desperdicio = function () {
    $('#solicitud_desperdicio').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var validar = validar_formulario(form);
        if (validar) {
            // var form = $('#solicitud_desperdicio').serialize();
            $.ajax({
                url: `${PATH_NAME}/indicador/indicador_desperdicio_maquina`,
                type: 'POST',
                data: form,
                success: function (res) {
                    tabla_datos_indicador(res.datos_indicador);
                    tabla_indicadores(res.indicador);
                }
            });
        }
    });
}

var tabla_datos_indicador = function (data) {
    var table = $('#tabla_datos_desperdicio_maquina').DataTable({
        "data": data,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        "scrollX": true,
        columns: [
            { "data": "num_produccion" },
            { "data": "nombre_maquina" },
            { "data": "tamanio_etiq" },
            { "data": "material" },
            { "data": "ml_orden", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "total_etiq_orden", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "m2_orden", render: $.fn.dataTable.render.number('.', ',', 2, '') },
            { "data": "m2_desperdicio_orden", render: $.fn.dataTable.render.number('.', ',', 2, '') },
            { "data": "porcentaje_desperdicio", render: $.fn.dataTable.render.number('.', ',', 2, '', '%') },
            { "data": "precio_material", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "precio_desperdicio", render: $.fn.dataTable.render.number('.', ',', 0, '') },
        ]
    });
}

var tabla_indicadores = function (data) {
    var table = $('#tabla_desperdicio_maquina').DataTable({
        "data": data,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        columns: [
            { "data": "nombre_maquina" },
            { "data": "total_etiq_orden", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "m2_orden", render: $.fn.dataTable.render.number('.', ',', 2, '') },
            { "data": "m2_desperdicio_orden", render: $.fn.dataTable.render.number('.', ',', 2, '') },
            { "data": "precio_desperdicio", render: $.fn.dataTable.render.number('.', ',', 2, '$ ') },
        ]
    });
    grafico_barra(data);
}

var grafico_barra = function (data) {
    if (myChart) {
        myChart.destroy();
    }
    // Defino la cantidad de colores que requiere mi tabla de manera fija
    var colores1 = colorrgb(2);
    var etiqueta = [];
    var color_dato = [];
    var color_dato1 = [];
    var dato = [];
    var dato1 = [];
    var numFormat = $.fn.dataTable.render.number(',', '.', 2, '').display;
    console.log(colores1);
    data.forEach(element => {
        etiqueta.push(element.nombre_maquina);
        color_dato.push(colores1[0]);
        color_dato1.push(colores1[1]);
        dato.push(numFormat(element.m2_orden));
        dato1.push(numFormat(element.m2_desperdicio_orden));
    });
    var ctx = $(`#myChart`);
    myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: etiqueta,
            datasets: [
                {
                    label: [`m2 Procesados`],
                    data: dato,
                    backgroundColor: color_dato,
                    borderColor: color_dato,
                    borderWidth: 1
                },
                {
                    label: [`m2 Desperdicio`],
                    data: dato1,
                    backgroundColor: color_dato1,
                    borderColor: color_dato1,
                    borderWidth: 1
                }
            ]
        },
        plugins: [ChartDataLabels],
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });
}
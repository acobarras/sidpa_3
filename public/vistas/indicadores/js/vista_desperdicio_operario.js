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
                url: `${PATH_NAME}/indicador/indicador_desperdicio_operario`,
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
    var table = $('#tabla_datos_desperdicio_operario').DataTable({
        "data": data,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        "scrollX": true,
        columns: [
            { "data": "num_produccion" },
            {
                "data": "todos_empleados",
                render: function (data, type, row) {
                    var emple = `${row.nombres} ${row.apellidos}`;
                    return emple;
                }
            },
            { "data": "tamanio_etiq" },
            { "data": "codigo_material" },
            { "data": "ancho_op" },
            { "data": "ml_usados", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "total_etiquetas", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "m2_item", render: $.fn.dataTable.render.number('.', ',', 2, '') },
            { "data": "m2_desperdicio", render: $.fn.dataTable.render.number('.', ',', 2, '') },
            { "data": "ml_desperdicio", render: $.fn.dataTable.render.number('.', ',', 2, '') },
            { "data": "porcentaje_desperdicio", render: $.fn.dataTable.render.number('.', ',', 2, '', '%') },
            { "data": "precio_mp", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "precio_desperdicio", render: $.fn.dataTable.render.number('.', ',', 0, '') },
        ]
    });
}

var tabla_indicadores = function (data) {
    var table = $('#tabla_desperdicio_operario').DataTable({
        "data": data,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        columns: [
            {
                "data": "empleados",
                render: function (data, type, row) {
                    var emple = `${row.nombres} ${row.apellidos}`;
                    return emple;
                }
            },
            { "data": "total_etiquetas", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "m2_item", render: $.fn.dataTable.render.number('.', ',', 2, '') },
            { "data": "m2_desperdicio", render: $.fn.dataTable.render.number('.', ',', 2, '') },
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
        etiqueta.push(element.nombres);
        color_dato.push(colores1[0]);
        color_dato1.push(colores1[1]);
        dato.push(numFormat(element.m2_item));
        dato1.push(numFormat(element.m2_desperdicio));
    });
    var ctx = $(`#myChart`);
    myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: etiqueta,
            datasets: [
                {
                    label: [`m2 Utilizados`],
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

$(document).ready(function () {
    select_2();
    alertify.set('notifier', 'position', 'bottom-left');
    resumen_motivo();
    cambio_ano();
    datos_pqr();
    filtro_mes();
    tabla_general_pqr();
});

var myChart;
var myChart1;

var cambio_ano = function () {
    $('#ano_consulta').change(function () {
        var ano = $(this).val();
        resumen_motivo(ano);
    });
}

var filtro_mes = function () {
    $('#mes').change(function () {
        var ano = '';
        var mes = $(this).val();
        resumen_motivo(ano, mes);
    });
}

var resumen_motivo = function (ano = '', mes = '') {
    var today = new Date();
    var year = $('#ano_consulta').val();//today.getFullYear();
    var month = $('#mes').val();//today.getFullYear();
    if (ano != '') {
        var year = ano;
    }
    if (mes != '') {
        var month = mes;
    }
    $.ajax({
        url: `${PATH_NAME}/indicador/tabla_motivo_pqr`,
        type: 'POST',
        data: { year, month },
        success: function (res) {
            $('#tabla_motivo_pqr').DataTable({
                "data": res,
                "dom": 'Bfrtip',
                "buttons": [
                    'copy', 'excel'
                ],
                columns: [
                    { "data": "codigo" },
                    { "data": "descripcion" },
                    { "data": "cantidad_pqr" },
                    {
                        "data": "opciones", render: (data, type, row) => {
                            var res = '';
                            if (row.cantidad_pqr != 0) {
                                res = `<button class="btn btn-info btn-sm datos_pqr" data-id="${row.id_respuesta_pqr}"><i class="fa fa-search"></i></button>`;
                            }
                            return res;
                        }
                    }
                ],
            });
            dibuja_torta(res);
        }
    });
}

var dibuja_torta = function (data) {
    if (myChart1) {
        myChart1.destroy();
    }
    var etiq = [];
    var valor = [];
    var q_total = 0;
    data.forEach(element => {
        q_total = q_total + element.cantidad_pqr;
    });
    data.forEach(element => {
        if (element.cantidad_pqr != 0) {
            etiq.push(element.descripcion);
            var porcentaje = (element.cantidad_pqr / q_total) * 100;
            valor.push(porcentaje);
        }
    });
    var q_colores = etiq.length;
    // Any of the following formats may be used
    var colores = colorrgb(q_colores);
    var etiqueta = etiq;//[`M2 Desperdicio`, `M2 Etiquetas`, `M2 Etiqu`];
    var numFormat = $.fn.dataTable.render.number('.', ',', 0, '', '%').display;
    var ctx = document.getElementById('myChart1').getContext('2d');// $(`#myChart1`);
    // var ctx = 'myChart';
    myChart1 = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: etiqueta,
            datasets: [{
                label: '',
                data: valor,
                backgroundColor: colores,
                borderColor: colores,
                borderWidth: 1
            }]
        },
        plugins: [ChartDataLabels],
        options: {
            plugins: {
                datalabels: {
                    formatter: function (value, context) {
                        return numFormat(value);
                    }
                },
                title: {
                    display: true,
                    text: 'PQR Totales',
                    align: 'center',
                },
                legend: {
                    position: 'center',
                    align: 'center',
                },
                scale: {
                    display: false
                },
            },
            responsive: true,
            // maintainAspectRatio: false,
        }
    });
}

var datos_pqr = function () {
    $('#tabla_motivo_pqr tbody').on("click", "button.datos_pqr", function () {
        var data = $('#tabla_motivo_pqr').DataTable().row($(this).parents("tr")).data();
        var datos = data.registros;
        $("#exampleModal").modal("show");
        $('#registros_pqr').DataTable({
            "data": datos,
            "dom": 'Bfrtip',
            "buttons": [
                'copy', 'excel'
            ],
            columns: [
                { "data": "fecha_crea" },
                { "data": "num_pqr" },
                { "data": "nombre_empresa" },
                { "data": "nombre_estado_pqr" },
            ],
        });
    });
}

var tabla_general_pqr = function (year) {
    $('#enviar_ano_general').submit(function (e) {
        e.preventDefault();
        var year = $('#ano_general').val();
        var obj_inicial = $('#envio_ano').html();
        btn_procesando('envio_ano');
        $.ajax({
            url: `${PATH_NAME}/indicador/tabla_general_pqr`,
            type: 'POST',
            data: { year },
            success: function (res) {
                btn_procesando('envio_ano', obj_inicial, 1);
                $('#tabla_general_pqr').DataTable({
                    "data": res,
                    "dom": 'Bfrtip',
                    "buttons": [
                        'copy', 'excel'
                    ],
                    columns: [
                        { "data": "fecha_crea" },
                        { "data": "num_pqr" },
                        { "data": "nombre_empresa" },
                        {
                            "data": "asesor", render: function (data, type, row) {
                                return `${row.nombres} ${row.apellidos}`;
                            }
                        },
                        { "data": "codigo" },
                        { "data": "num_pedido_cambio" },
                        { "data": "codigo_motivo" },
                        { "data": "apertura_pqr" },
                        { "data": "clasificacion" },
                        { "data": "responsable" },
                        { "data": "costo" },
                        { "data": "observacion" },
                        { "data": "nombre_estado_pqr" },
                    ],
                });
            }
        });
    })
}
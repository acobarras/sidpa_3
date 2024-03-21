$(document).ready(function () {
    select_2();
    ver_comision();
    cambio_consulta();
});

var tiempo = function () {
    const date = new Date();
    var tiempo = `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
    return tiempo;
}

var tabla_con_pago = `<thead style = "background:#0d1b50;color:white">
< tr >
    <th>Factura</th>
    <th>Nit</th>
    <th>Empresa</th>
    <th>Fecha Factura</th>
    <th>Fecha Vencimiento</th>
    <th>Pertenece a:</th>
    <th>Fecha Pago</th>
    <th>Asesor</th>
    <th>total Etiquetas</th>
    <th>% Comisión</th>
    <th>Comisión Etiqueta</th>
    <th>total Tecnologia</th>
    <th>% Comisión</th>
    <th>Comisión Tecnologia</th>
    <th>Total Comisión</th>
    <th>total Sin Comisión</th>
    <th>Sub Total Sin Iva</th>
    <th>Iva</th>
    <th>Total Factura</th>
    <th>Estado</th>
    <th>Dias Credito</th>
    <th>Dias Vencido</th>
</tr>
</thead>
<tfoot>
<tr class="text-danger table-secondary">
    <th colspan="3" style="text-align:right">Total portafolio:</th>
    <th colspan="2"></th>
</tr>
</tfoot>`;
var objeto_tabla = $('#tabla_comision').html();

var cambio_consulta = function () {
    $('#asesor').on('change', function () {
        var cambio = $(this).val();
        $('#tabla_comision').DataTable().destroy();
        $('#tabla_comision').empty().html(objeto_tabla);
        if (cambio == 'cambio') {
            $('#periodo_liquida').css('display', 'none');
            $('#rango_consulta').css("display", '');
            $('#fecha_inicial').val('');
            $('#fecha_fin').val('');
        } else if (cambio == 'sin_pago') {
            alertify.confirm('SIDPA INFORMA', 'Desea realizar la consulta para las facturas que no estan pagas?',
                function () {
                    fac_sin_pago();
                },
                function () {
                    alertify.error('Operacion Cancelada');
                }
            );
        } else if (cambio == 'con_pago') {
            $('#tabla_comision').empty().html(tabla_con_pago);
            $('#periodo_liquida').css('display', 'none');
            $('#rango_consulta').css("display", '');
            $('#fecha_inicial').val('');
            $('#fecha_fin').val('');
        }
        else {
            $('#periodo_liquida').css('display', '');
            $('#rango_consulta').css('display', 'none');
            $('#periodo').val('0').trigger('change');
        }
    });
}

var fac_sin_pago = function () {
    var asesor = $('#asesor').val();
    $.ajax({
        url: `${PATH_NAME}/gerencia/liquida_comision`,
        type: 'POST',
        data: { asesor },
        success: function (res) {
            cargar_tabla(res);
        }

    });
}

var cargar_tabla = function (data) {
    var table1 = $('#tabla_comision').DataTable({
        data: data,
        dom: 'Bflrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
            tittleAttr: ' Exportar a exel',
            className: 'btn btn-success',
        }],
        "columns": [
            { "data": "num_factura" },
            { "data": "nit" },
            { "data": "nombre_empresa" },
            { "data": "fecha_factura" },
            { "data": "fecha_vencimiento" },
            { "data": "nombre_compania" },
            { "data": "fecha_pago" },
            {
                "data": "asesor", render: function (data, type, row) {
                    return `${row.nombres} ${row.apellidos}`;
                }
            },
            { "data": "total_etiquetas", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            { "data": "total_cintas", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            { "data": "total_alquiler", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            { "data": "total_tecnologia", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            { "data": "total_soporte", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            { "data": "total_fletes", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            { "data": "total_m_prima", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            {
                "data": "iva", render: function (data, type, row) {
                    var iva = 0;
                    if (row.iva == 1) {
                        iva = (row.total_factura / 119) * 19;
                    }
                    return $.fn.dataTable.render.number(',', '.', 2, '$ ').display(iva);
                }
            },
            { "data": "total_factura", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            { "data": "nombre_estado" },
            { "data": "dias_dados" },
            { "data": "dias_vencimiento" },
            { "data": "sumatoria_tecsop", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            { "data": "subtotal", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            var Total = 0;
            data.forEach(element => {
                Total += parseFloat(element.total_factura);

            });
            // Update footer
            $(api.column(3).footer()).html($.fn.dataTable.render.number(',', '.', 2, '$ ').display(Total));
        },
    });
}

var ver_comision = function () {
    $('#form_comision').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida_form;
        if ($('#asesor').val() == '' || $('#asesor').val() == 0) {
            alertify.confirm('SIDPA INFORMA', 'Desea continuar con esta consulta ya que es una consulta muy grande y puede tardar.',
                function () {
                    ejecuta_comision(form);
                },
                function () {
                    alertify.error('Operacion Cancelada');
                }
            );
        } else {
            var exepcion = ['fecha_inicial', 'fecha_fin'];
            if (form[0].value == 'cambio') {
                exepcion = ['periodo'];
                var fecha_inicial = $('#fecha_inicial').val();
                var fecha_fin = $('#fecha_fin').val();
                var fecha1 = new Date(fecha_inicial);
                var fecha2 = new Date(fecha_fin);
                var diferencia = fecha2.getTime() - fecha1.getTime();
                var diasDeDiferencia = diferencia / 1000 / 60 / 60 / 24;
                if (diasDeDiferencia > 60) {
                    alertify.error('La consulta solo se puede hacer con un maximo de 60 dias');
                    return;
                }
            }
            if (form[0].value == 'sin_pago') {
                exepcion = ['periodo', 'fecha_inicial', 'fecha_fin'];
            }
            if (form[0].value == 'con_pago') {
                exepcion = ['periodo'];
            }
            var valida_form = validar_formulario(form, exepcion);
            if (valida_form) {
                ejecuta_comision(form);
            }
        }
    });
}

var ejecuta_comision = function (form) {
    var data_con_pago = form[0].value;
    $.ajax({
        url: `${PATH_NAME}/gerencia/liquida_comision`,
        type: 'POST',
        data: form,
        success: function (res) {
            if (data_con_pago == 'con_pago') {
                $('#tabla_comision').empty().html(tabla_con_pago);
                cargar_tabla_con_pago(res);
            } else {
                $('#tabla_comision').empty().html(objeto_tabla);
                cargar_tabla(res);
            }
        }
    });
}

var cargar_tabla_con_pago = function (data) {
    var table1 = $('#tabla_comision').DataTable({
        data: data,
        dom: 'Bflrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
            // titleAttr: ' Exportar a exel',
            filename: `Archivo Comisiones ${tiempo()}`,
            className: 'btn btn-success',
            exportOptions: {
                columns: [0, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 5], // Especifica las columnas que deseas exportar (0, 1, 2 son índices de columna)
                order: [7, 'asc'], // 'applied' Ordenará según el orden actual de la tabla
                // O puedes especificar un orden específico, por ejemplo: order: [0, 'asc'] para ordenar por la primera columna de forma ascendente
                title: null
            }
        }],
        "columns": [
            { "data": "num_factura" },
            { "data": "nit" },
            { "data": "nombre_empresa" },
            { "data": "fecha_factura" },
            { "data": "fecha_vencimiento" },
            { "data": "nombre_compania" },
            { "data": "fecha_pago" },
            {
                "data": "asesor", render: function (data, type, row) {
                    return `${row.nombres} ${row.apellidos}`;
                }
            },
            { "data": "total_etiquetas", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            {
                "data": "comi_etiq", render: function (data, type, row) {
                    var porcentaje = row.comi_etiq;
                    if (parseFloat(row.venta_mes) < 60000000) {
                        porcentaje = parseFloat(row.comi_etiq) - 0.5;
                    }
                    if (porcentaje < 0 || row.total_etiquetas == 0) {
                        porcentaje = 0;
                    }
                    return $.fn.dataTable.render.number(',', '.', 2, '', '%').display(porcentaje);
                }
            },
            {
                "data": "valor_comision", render: function (data, type, row) {
                    var valor = 0;
                    var porcentaje = row.comi_etiq;
                    if (parseFloat(row.venta_mes) < 60000000) {
                        porcentaje = parseFloat(row.comi_etiq) - 0.5;
                    }
                    if (porcentaje > 0 && row.total_etiquetas != 0 && row.estado_portafolio != 4) {
                        valor = (row.total_etiquetas * porcentaje) / 100;
                    }
                    return $.fn.dataTable.render.number(',', '.', 2, '$ ').display(valor);
                }
            },
            { "data": "totalTecnologia", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            {
                "data": "comi_tecn", render: function (data, type, row) {
                    var porcentaje = row.comi_tecn;
                    if (porcentaje < 0 || row.totalTecnologia == 0) {
                        porcentaje = 0;
                    }
                    return $.fn.dataTable.render.number(',', '.', 2, '', '%').display(porcentaje);
                }
            },
            {
                "data": "valor_tecno", render: function (data, type, row) {
                    var valor = 0;
                    if (row.comi_tecn != 0 && row.totalTecnologia != 0 && row.estado_portafolio != 4) {
                        valor = (row.totalTecnologia * row.comi_tecn) / 100;
                    }
                    return $.fn.dataTable.render.number(',', '.', 2, '$ ').display(valor);
                }
            },
            {
                "data": "total_comision", render: function (data, type, row) {
                    var valor = 0;
                    var valor1 = 0;
                    var porcentaje = row.comi_etiq;
                    if (parseFloat(row.venta_mes) < 60000000) {
                        porcentaje = parseFloat(row.comi_etiq) - 0.5;
                    }
                    if (porcentaje > 0 && row.total_etiquetas != 0 && row.estado_portafolio != 4) {
                        valor = (row.total_etiquetas * porcentaje) / 100;
                    }
                    if (row.comi_tecn != 0 && row.totalTecnologia != 0 && row.estado_portafolio != 4) {
                        valor1 = (row.totalTecnologia * row.comi_tecn) / 100;
                    }
                    total = valor + valor1;
                    return $.fn.dataTable.render.number(',', '.', 2, '$ ').display(total);
                }
            },
            { "data": "total_sin_comision", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            { "data": "subtotal", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            {
                "data": "iva", render: function (data, type, row) {
                    var iva = 0;
                    if (row.iva == 1) {
                        iva = (row.total_factura / 119) * 19;
                    }
                    return $.fn.dataTable.render.number(',', '.', 2, '$ ').display(iva);
                }
            },
            { "data": "total_factura", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            { "data": "nombre_estado" },
            { "data": "dias_dados" },
            { "data": "dias_vencimiento" },
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            var Total = 0;
            data.forEach(element => {
                Total += parseFloat(element.total_factura);

            });
            // Update footer
            $(api.column(3).footer()).html($.fn.dataTable.render.number(',', '.', 2, '$ ').display(Total));
        },
    });
    // Ordenar la tabla antes de exportarla
    $('#tabla_comision').DataTable().order([7, 'asc']).draw();
}
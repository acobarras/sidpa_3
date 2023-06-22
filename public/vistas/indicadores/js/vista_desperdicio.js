$(document).ready(function () {
    solicitud_desperdicio();
    consulta_op();
    $('#empleado').select2();
    alertify.set('notifier', 'position', 'bottom-left');
    editar_entrega();
    cambio_ml_entregados();
    editar_troquelado();
    cambio_ml_reportados();
    editar_embobinado();
    cambio_etiquetas_reportadas();
    consulta_fechas_op();
    consulta_fechas_embobinado();
});

var myChart;
var myChart1;
var id_roll = $('#id_roll').val();
var permiso_roll_1 = $('#permiso_roll_1').val();
var permiso_roll_2 = $('#permiso_roll_2').val();

var solicitud_desperdicio = function () {
    $('#solicitud_desperdicio').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $(`#id_envio`).html();
        btn_procesando(`id_envio`);
        var form = $(this).serializeArray();
        var validar = validar_formulario(form);
        if (validar) {
            var form = $('#solicitud_desperdicio').serialize();
            $.ajax({
                url: `${PATH_NAME}/indicador/indicador_desperdicio`,
                type: 'POST',
                data: { form },
                success: function (res) {
                    btn_procesando(`id_envio`, obj_inicial, 1);
                    datos_tabla_desperdicio(res);
                }
            });
        }
    });
}

var quita_duplicado = function (row) {
    const myArr = row;
    const newArr = [];
    const myObj = {};
    myArr.forEach(el => {
        // comprobamos si el valor existe en el objeto
        if (!(el in myObj)) {
            // si no existe creamos ese valor y lo añadimos al array final, y si sí existe no lo añadimos
            myObj[el] = true
            newArr.push(el)
        }
    });
    return newArr;
}

var datos_tabla_desperdicio = function (data) {
    var table = $('#tabla_desperdicio').DataTable({
        "data": data,
        "destroy": true,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        "scrollX": true,
        columns: [
            { "data": "fecha_produccion" },
            { "data": "nombre_maquina" },
            {
                "data": "todos_empleados",
                render: function (data, type, row) {
                    var emple = quita_duplicado(row.todos_empleados);
                    return emple;
                }
            },
            { "data": "num_produccion" },
            { "data": "tamanio_etiq" },
            {
                "data": "tintas",
                render: function (data, type, row) {
                    var tintas = quita_duplicado(row.tintas);
                    return tintas;
                }
            },
            { "data": "material_usado" },
            { "data": "ancho_op" },
            { "data": "mL_descontado", render: $.fn.dataTable.render.number(',', '.', 0, '') },
            { "data": "ml_retorno", render: $.fn.dataTable.render.number(',', '.', 0, '') },
            { "data": "total_etiquetas", render: $.fn.dataTable.render.number(',', '.', 0, '') },
            { "data": "m2_utilizados", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { "data": "m2_total_etiq", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { "data": "m2_desperdicio", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { "data": "porcentaje_desperdicio", render: $.fn.dataTable.render.number(',', '.', 2, '', '%') },
            { "data": "precio_material", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            { "data": "precio_mp_desperdicio", render: $.fn.dataTable.render.number(',', '.', 2, '') },
            // { "data": "valor_venta", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            { "data": "v_unidad_min", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            { "data": "total_op", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
            { "data": "porcentaje_venta", render: $.fn.dataTable.render.number(',', '.', 2, '', '%') },
        ]
    });
    grafica_desperdicio();
}

var grafica_desperdicio = function () {
    var data = $("#tabla_desperdicio").DataTable().rows().data();
    var dato_tabla = $("#tabla_desperdicio").DataTable().rows().nodes();
    var precio_mp_desperdicio = 0;
    var total_op = 0;
    var m2_desperdicio = 0;
    var m2_etiquetas = 0;
    $.each(dato_tabla, function (index, value) {
        precio_mp_desperdicio = precio_mp_desperdicio + data[index].precio_mp_desperdicio;
        total_op = total_op + data[index].total_op;
        m2_desperdicio = m2_desperdicio + data[index].m2_desperdicio;
        m2_etiquetas = m2_etiquetas + data[index].m2_total_etiq;
    });
    var valor = m2_desperdicio + m2_etiquetas;
    var m2_desperdicio = (m2_desperdicio / valor) * 100;
    var m2_etiquetas = (m2_etiquetas / valor) * 100;
    var numFormat = $.fn.dataTable.render.number('.', ',', 2, '', '%').display;
    var porcentaje_venta = (precio_mp_desperdicio / total_op) * 100;
    $('#porcentaje_venta').empty().html(`Relacion porcentual ${numFormat(porcentaje_venta)}`);

    var data = [precio_mp_desperdicio, total_op];
    dibuja_barra(data);
    var data1 = [m2_desperdicio, m2_etiquetas];
    dibuja_torta(data1);

}

var dibuja_barra = function (data) {
    if (myChart) {
        myChart.destroy();
    }
    var numFormat = $.fn.dataTable.render.number('.', ',', 2, '$ ').display;
    var colores = colorrgb(2);
    var etiqueta = [`Costo Desperdicio`, `P.V. Producido`];
    // Any of the following formats may be used
    var numFormat_porcentaje = $.fn.dataTable.render.number(',', '.', 2, '', '%').display;
    var ctx = $('#myChart');
    myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: etiqueta,
            datasets: [{
                label: `Relación porcentual ${numFormat((data[0] / data[1]) * 100)}`,
                data: data,
                backgroundColor: colores,
                borderColor: colores,
                borderWidth: 1
            }]
        },
        plugins: [ChartDataLabels],
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                datalabels: {
                    formatter: function (value, context) {
                        return numFormat(value);
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });
}

var dibuja_torta = function (data) {
    if (myChart1) {
        myChart1.destroy();
    }
    // Any of the following formats may be used
    var colores = colorrgb(2);
    var etiqueta = [`M2 Desperdicio`, `M2 Etiquetas`];
    var numFormat = $.fn.dataTable.render.number('.', ',', 2, '', '%').display;
    var ctx = $(`#myChart1`);
    // var ctx = 'myChart';
    myChart1 = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: etiqueta,
            datasets: [{
                data: data,
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
                }
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });
}



var consulta_op = function () {
    $('#solicitud_personal').submit(function (e) {
        $("#tabla_troquelado").dataTable().fnDestroy();
        e.preventDefault();
        var datos = $(this).serializeArray();
        var validar = validar_formulario(datos);
        if (validar) {
            datos = $(this).serialize();
            $.ajax({
                url: `${PATH_NAME}/indicador/consulta_op`,
                type: 'POST',
                data: datos,
                success: function (res) {

                    reportes_troquelado('tabla_troquelado', res.datos_troquelado);
                    reportes_embobinado('tabla_embobinado', res.datos_embobinado);
                }
            });
        }
    });
}
var consulta_fechas_op = function () {
    $('#consulta_fechas').submit(function (e) {
        $("#tabla_troquelado").dataTable().fnDestroy();
        e.preventDefault();
        var obj_inicial = $(`#envio_fecha`).html();
        btn_procesando(`envio_fecha`);
        var datos = $(this).serializeArray();
        var validar = validar_formulario(datos);
        if (validar) {
            datos = $(this).serialize();
            $.ajax({
                url: `${PATH_NAME}/indicador/consulta_fechas_op`,
                type: 'POST',
                data: datos,
                success: function (res) {
                    btn_procesando(`envio_fecha`, obj_inicial, 1);
                    reportes_troquelado('consulta_tabla_troquelado', res.datos_troquelado);
                }
            });
        }
    });
}
var consulta_fechas_embobinado = function () {
    $('#consulta_fecha_embo').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $(`#envio_fechaembo`).html();
        btn_procesando(`envio_fechaembo`);
        var datos = $(this).serializeArray();
        var validar = validar_formulario(datos);
        if (validar) {
            datos = $(this).serialize();
            $.ajax({
                url: `${PATH_NAME}/indicador/consulta_fechas_embobinado`,
                type: 'POST',
                data: datos,
                success: function (res) {
                    btn_procesando(`envio_fechaembo`, obj_inicial, 1);
                    reportes_embobinado('consulta_tabla_embobinado', res.datos_embobinado);
                }
            });
        }
    });
}

var reportes_troquelado = function (nombre_tabla, datos_troquelado) {
    $(`#${nombre_tabla}`).DataTable({
        "data": datos_troquelado,
        "dom": "Bfrtip",
        "buttons": [
            "copy", "excel", "pdf"
        ],
        columns: [
            { "data": "fecha_crea" },
            { "data": "codigo_material" },
            { "data": "ancho" },
            { "data": "metros_lineales" },
            { "data": "ml_usados" },
            { "data": "nombre_persona" },
            {
                "data": "accion",
                render: function (data, type, row) {
                    if (row.estado_ml == 2 || row.ml_usados == '0.00') {
                        if (id_roll == 1 || permiso_roll_2 == 'si') {
                            var res = `<button type="button" data-id="1" class="editar_entrega btn btn-info" data-bs-toggle="modal" data-bs-target="#entregaModal">
                                <span class="fas fa-search-plus"></span>
                            </button>`;
                        } else {
                            var res = `<button type="button" data-id="1" class="btn btn-info" disabled>
                                <span class="fas fa-search-plus"></span>
                            </button>`;
                        }
                    } else {
                        if (id_roll == 1 || permiso_roll_1 == 'si') {
                            var res = `<button type="button" data-id="1" class="editar_troquelado btn btn-info" data-bs-toggle="modal" data-bs-target="#troqueladoModal" >
                            <span class="fas fa-search-plus"></span>
                            </button>`;
                        } else {
                            var res = `<button type="button" data-id="1" class="btn btn-info" disabled>
                                <span class="fas fa-search-plus"></span>
                            </button>`;
                        }
                    }
                    return res;
                },
                "className": "text-center"
            },
        ],
    });
}

var editar_entrega = function () {
    $('#tabla_troquelado tbody').on("click", "button.editar_entrega", function () {
        var data = $('#tabla_troquelado').DataTable().row($(this).parents("tr")).data();
        $('#codigo_mate_entre').empty().val(data.codigo_material);
        $('#ancho_mate_entre').empty().val(data.ancho);
        $('#empleado_entre').val(data.id_persona).trigger('change');
        if (data.estado_ml == 2) {
            $('#titulo_mate_entre').html('Metros lineales Devueltos');
            $('#ml_entregados').empty().val(data.ml_usados);
        } else {
            $('#titulo_mate_entre').html('Metros lineales Entregados');
            $('#ml_entregados').empty().val(data.metros_lineales);
        }
        $('.boton-z').attr('id-cambio', JSON.stringify(data));
    });
}

var cambio_ml_entregados = function () {
    $('#cambio_ml_entregados').submit(function (e) {
        e.preventDefault();
        var data = JSON.parse($('.boton-z').attr('id-cambio'));
        var cambios = $(this).serializeArray();
        var id_persona_antes = data.id_persona;
        if (id_persona_antes == 0) {
            var ml_antes = data.ml_usados;
        } else {
            var ml_antes = data.metros_lineales;
        }
        var ml_nuevos = cambios[0].value;
        if (ml_antes == ml_nuevos) {
            alertify.error('No realizo ningun cambio para poder modificar');
            return;
        } else {
            $.ajax({
                url: `${PATH_NAME}/indicador/editar_ml_entregados`,
                type: 'POST',
                data: { data, cambios },
                success: function (res) {
                    if (res == -1) {
                        alertify.error('Lo sentimos esta modificacion no se puede realizar solo lo puede realizar uno de los desarrolladores');
                        return;
                    } else {
                        $('#envio1').click();
                        $('#entregaModal').modal('hide');
                    }
                }
            });
        }
    });
}

var editar_troquelado = function (tbody, table) {
    $('#tabla_troquelado tbody').on("click", "button.editar_troquelado", function () {
        var data = $('#tabla_troquelado').DataTable().row($(this).parents("tr")).data();
        $('#codigo_material').empty().val(data.codigo_material);
        $('#ancho_material').empty().val(data.ancho);
        $('#empleado').val(data.id_persona).trigger('change');
        $('#ml_usados').empty().val(data.ml_usados);
        $('.boton-x').attr('id-cambio', JSON.stringify(data));
    });
}

var cambio_ml_reportados = function () {
    $('#cambio_ml_reportados').submit(function (e) {
        e.preventDefault();
        var data = JSON.parse($('.boton-x').attr('id-cambio'));
        var cambios = $(this).serializeArray();
        var id_persona_nueva = cambios[0].value;
        var ml_nuevos = cambios[1].value;
        var id_persona_antes = data.id_persona;
        var ml_antes = data.ml_usados;
        if (id_persona_antes == id_persona_nueva && ml_antes == ml_nuevos) {
            alertify.error('No realizo ningun cambio para poder modificar');
            return;
        } else {
            $.ajax({
                url: `${PATH_NAME}/indicador/editar_desperdicio`,
                type: 'POST',
                data: { data, cambios },
                success: function (res) {
                    if (res == -1) {
                        alertify.error('Lo sentimos esta modificacion no se puede realizar solo lo puede realizar uno de los desarrolladores');
                        return;
                    } else {
                        $('#envio1').click();
                        $('#troqueladoModal').modal('hide');
                    }
                }
            });
        }
    });
}

var reportes_embobinado = function (nombre_tabla, datos_embobinado) {
    $(`#${nombre_tabla}`).DataTable({
        "data": datos_embobinado,
        "dom": "Bfrtip",
        "buttons": [
            "copy", "excel", "pdf"
        ],
        columns: [
            { "data": "fecha_crea" },
            {
                "data": "num_pedido",
                render: function (data, type, row) {
                    return `${row.num_pedido}-${row.item}`;
                }
            },
            { "data": "tamanio_etiq" },
            { "data": "ml_empleado" },
            { "data": "cantidad_etiquetas" },
            {
                "data": "nombre_persona",
                render: function (data, type, row) {
                    return `${row.nombres} ${row.apellidos}`;
                }
            },
            {
                "data": "accion",
                render: function (data, type, row) {
                    if (id_roll == 1 || permiso_roll_2 == 'si') {
                        var res = `<button type="button" data-id="1" class="editar_embobinado btn btn-info" data-bs-toggle="modal" data-bs-target="#embobinadoModal" >
                            <span class="fas fa-search-plus"></span>
                        </button>`;
                    } else {
                        var res = `<button type="button" data-id="1" class="btn btn-info" disabled>
                                <span class="fas fa-search-plus"></span>
                            </button>`;
                    }
                    return res;
                },
                "className": "text-center"
            },
        ],
    });
}

var editar_embobinado = function () {
    $('#tabla_embobinado tbody').on("click", "button.editar_embobinado", function () {
        var data = $('#tabla_embobinado').DataTable().row($(this).parents("tr")).data();
        $('#tamano_etiq').empty().val(data.tamanio_etiq);
        $('#empleado1').val(data.id_persona).trigger('change');
        $('#cantidad_etiq').empty().val(data.cantidad_etiquetas);
        $('#metros_lineales').empty().val(data.ml_empleado);
        $('.boton-y').attr('id-cambio', JSON.stringify(data));
    });
}

var cambio_etiquetas_reportadas = function () {
    $('#cambio_etiquetas').submit(function (e) {
        e.preventDefault();
        var data = JSON.parse($('.boton-y').attr('id-cambio'));
        var cambios = $(this).serializeArray();
        var id_persona_nueva = cambios[0].value;
        var cantidad_etiquetas_nuevos = cambios[1].value;
        var metros_lineales_nuevos = cambios[2].value;
        var id_persona_antes = data.id_persona;
        var cantidad_etiquetas = data.cantidad_etiquetas;
        var metros_lineales_antes = data.ml_empleado;
        if (id_persona_antes == id_persona_nueva && cantidad_etiquetas == cantidad_etiquetas_nuevos && metros_lineales_nuevos == metros_lineales_antes) {
            alertify.error('No realizo ningun cambio para poder modificar');
            return;
        } else {
            $.ajax({
                url: `${PATH_NAME}/indicador/editar_etiquetas`,
                type: 'POST',
                data: { data, cambios },
                success: function (res) {
                    if (res == -1) {
                        alertify.error('Lo sentimos esta modificacion no se puede realizar solo lo puede realizar uno de los desarrolladores');
                        return;
                    } else {
                        $('#envio1').click();
                        $('#embobinadoModal').modal('hide');
                    }
                }
            });
        }
    });
}


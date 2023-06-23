$(document).ready(function () {
    alertify.set('notifier', 'position', 'bottom-left');
    tb_info_variable();
    reportar_alistamiento_inf_variable();
});

var tb_info_variable = function () {
    var table = $('#table_info_variable').DataTable({
        ajax: `${PATH_NAME}/diseno/consulta_trabajos_diseno`,
        "rowCallback": function (row, data, index) {
            if (data.cant_bodega == "0") {
                $('td:eq(4)', row).css('color', '#00000');//coloca color de cantidad bodega en NEGRO
            } else {
                if (data.alista_inv == "2") {
                    $('td:eq(4)', row).css('color', '#dc3545');//coloca color de cantidad bodega en rojo
                } else {
                    $('td:eq(4)', row).css('color', '#06840E');//coloca color de cantidad bodega en verde
                }
            }
        },
        columns: [
            { "data": "fecha_compromiso" },
            { "data": "nombre_empresa" },
            { "data": "codigo" },
            { "data": "Cant_solicitada", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "cant_bodega", render: $.fn.dataTable.render.number('.', ',', 0, '') },

            { "data": "cant_op", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            {
                "data": "recibe", render: (data, type, row) => {
                    return informa_diferencia(row);
                }
            },
            {
                "data": "descripcion_productos", render: (data, type, row) => {
                    return `${row['nombre_clase_articulo']} ${row['descripcion_productos']}`;
                }
            },
            { "data": "n_produccion" },
            {
                "data": "pedido-item", render: (data, type, row) => {
                    return row['num_pedido'] + '-' + row['item'];
                }
            },
            { "data": "nombre_core" },
            { "data": "cant_x", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "ruta" },
            { "data": "nombre_estado_item" },
            {
                "data": "opciones", render: (data, type, row) => {
                    var res = `<div class="custom-control custom-checkbox mr-sm-2">
                    <button class="btn btn-success btn-sm logistica_checked"><i class="fa fa-check"></i></button>  `;
                    if (row.n_produccion == 0) {
                        res += `<button class="btn btn-danger btn-sm envio_genera_op"><i class="fa fa-times"></i></button>`;
                    }
                    res += `<button class="btn btn-info btn-sm observaciones_ver" style="margin-top: 5px;" data-bs-toggle="modal" data-bs-target="#observaciones_Modal"><i class="fa fa-search"></i></button>
                    </div>`;
                    return res;
                }
            }
        ],
    });
    alistamiento_checked('#table_info_variable tbody', table);
    envio_genera_op('#table_info_variable tbody', table);
    observaciones_ver('#table_info_variable tbody', table);
}

var observaciones_ver = function (tbody, table) {
    $(tbody).on('click', 'button.observaciones_ver', function () {
        var data = table.row($(this).parents('tr')).data();
        $("#observaciones_p").empty().html(data.observaciones);
    });

}

var alistamiento_checked = function (tbody, table) {
    $(tbody).on('click', 'button.logistica_checked', function () {
        var data = table.row($(this).parents('tr')).data();
        console.log(data);
        if (data.alista_inv == 1 || data.cant_bodega == 0) {
            $("#logistica_checked_etiq_Modal").modal('show');
            $("#btn_reportar_factu_etiq").attr('data-id', JSON.stringify(data));
        } else {
            alertify.error("Se debe realizar primero el alistamiento de bodega.");
        }
    });
}

var envio_genera_op = function (tbody, table) {
    $(tbody).on('click', 'button.envio_genera_op', function () {
        var data = table.row($(this).parents('tr')).data();
        if (data.cant_bodega != 0) {

        }
        alertify.confirm('Envió ítem a Producción', 'Está seguro que desea enviar este ítem al proceso de Producción.',
            function () {
                $.ajax({
                    url: `${PATH_NAME}/diseno/pasar_a_produccion`,
                    type: "POST",
                    data: { data },
                    success: function (res) {
                        if (res.status == -1) { //status -1 ya se relaciono esta factura
                            alertify.error(res.msg);
                        } else {
                            alertify.success(res.msg);
                            $("#table_info_variable").DataTable().ajax.reload();
                        }
                    }
                });
            },
            function () {
                alertify.error('Se detuvo el proceso correctamente.');
            });
    });
}

var reportar_alistamiento_inf_variable = function () {
    $('#form_reporta_etiq_info').submit(function (e) {
        e.preventDefault();
        var data = JSON.parse($("#btn_reportar_factu_etiq").attr('data-id'));
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            var obj_inicial = $(`#btn_reportar_factu_etiq`).html();
            btn_procesando(`btn_reportar_factu_etiq`);
            form = $(this).serialize();
            var envio = 1;
            $.ajax({
                url: `${PATH_NAME}/diseno/reportar_etiq_procesadas`,
                type: 'POST',
                data: { form, data, envio },
                success: function (res) {
                    if (res.status == -1) {
                        envio = 2;
                        alertify.confirm('Alerta Sidpa', `${res.msg} ¿desea continuar?`,
                            function () {
                                $.ajax({
                                    url: `${PATH_NAME}/diseno/reportar_etiq_procesadas`,
                                    type: 'POST',
                                    data: { form, data, envio },
                                    success: function (res) {
                                        $("#table_info_variable").DataTable().ajax.reload(function () {
                                            $("#logistica_checked_etiq_Modal").modal('hide');
                                            btn_procesando(`btn_reportar_factu_etiq`, obj_inicial, 1);
                                            $("#form_reporta_etiq_info")[0].reset();
                                            alertify.success(res.msg);
                                        });
                                    }
                                });
                            },
                            function () {
                                $("#table_info_variable").DataTable().ajax.reload(function () {
                                    btn_procesando(`btn_reportar_factu_etiq`, obj_inicial, 1);
                                    $("#logistica_checked_etiq_Modal").modal('hide');
                                    $("#form_reporta_etiq_info")[0].reset();
                                    alertify.error('Operacion cancelada');
                                });
                            });
                    } else {
                        $("#table_info_variable").DataTable().ajax.reload(function () {
                            $("#logistica_checked_etiq_Modal").modal('hide');
                            btn_procesando(`btn_reportar_factu_etiq`, obj_inicial, 1);
                            $("#form_reporta_etiq_info")[0].reset();
                            alertify.success(res.msg);
                        });
                    }
                }
            });
        }
    });
}
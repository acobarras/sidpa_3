$(document).ready(function () {
    tb_alistamiento_tecnologia();
    envio_alistamiento_checked();
});

var tb_alistamiento_tecnologia = function () {
    var table = $('#dt_alistamiento_tecnologia').DataTable({
        ajax: `${PATH_NAME}/almacen/consultar_items_op?dato=2`,
        "rowCallback": function (row, data, index) {
            if (data.cant_bodega == "0") {
                $('td:eq(4)', row).css('color', '#00000');//coloca color de cantidad bodega en verde
            }
            else {
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
            { "data": "orden_compra" },
            { "data": "codigo" },
            { "data": "Cant_solicitada", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "cant_bodega", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            {
                "data": "descripcion_productos", render: (data, type, row) => {
                    return `${row['nombre_clase_articulo']} ${row['descripcion_productos']}`;
                }
            },
            //concatenar la data pedido y item 
            {
                "data": "pedido-item", render: (data, type, row) => {
                    return row['num_pedido'] + '-' + row['item'];
                }
            },
            {
                "data": "orden_compra", render: (data, type, row) => {
                    if (row['orden_compra'] != null) {
                        return `No.<b>${row['orden_compra']}</b> <b>||</b> ${row['fecha_proveedor']}`;
                    } else {

                        return `Sin orden de compra`;
                    }
                }
            },
            { "data": "fecha_proveedor" },
            { "data": "ruta" },
            { "data": "nombre_estado_item" },
            {
                "data": "opciones", render: (data, type, row) => {
                    return `<div class="custom-control custom-checkbox mr-sm-2">
                    <button class="btn btn-success btn-sm logistica_checked"><i class="fa fa-check"></i></button>  
                    <button class="btn btn-info btn-sm observaciones_ver"  style="margin-top: 5px;" data-bs-toggle="modal" data-bs-target="#observaciones_Modal"><i class="fa fa-search"></i></button>
                    </div>`;
                }
            }

        ],
    });
    alistamiento_checked('#dt_alistamiento_tecnologia tbody', table);
    observaciones_ver('#dt_alistamiento_tecnologia tbody', table);
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
        $('#tipo_envio').val(1);// valor para saber si es un reporte parcial o completo "1" completo 
        $("#btn_reportar_factu").attr('data-id', JSON.stringify(data));
        if (data.alista_inv == 1 || data.cant_bodega == 0) {
            $("#logistica_checked_Modal").modal('show');
        } else {
            alertify.error("Se debe realizar primero el alistamiento de bodega.");
        }
    });

}
var envio_alistamiento_checked = function () {
    $("#form_reporta_factu").on('submit', function (e) {
        e.preventDefault();
        var data = JSON.parse($("#btn_reportar_factu").attr('data-id'));
        var form = $(this).serializeArray();
        var form1 = $(this).serialize();
        var valida = validar_formulario(form);
        if (valida) {
            var obj_inicial = $(`#btn_reportar_factu`).html();
            // btn_procesando(`btn_reportar_factu`);
            $.ajax({
                url: `${PATH_NAME}/almacen/reportar_facturacion`,
                type: 'POST',
                data: { form1, data },
                success: function (res) {
                    if (res.status == 1) {
                        $("#dt_alistamiento_tecnologia").DataTable().ajax.reload(function () {
                            $("#logistica_checked_Modal").modal('hide');
                            // btn_procesando(`btn_reportar_factu`, obj_inicial, 1);
                            $("#form_reporta_factu")[0].reset();
                            alertify.success(res.msg);
                        });
                    } else {
                        $("#dt_alistamiento_tecnologia").DataTable().ajax.reload(function () {
                            // btn_procesando(`btn_reportar_factu`, obj_inicial, 1);
                            alertify.error(res.msg);
                        });

                    }
                }
            });
        }
    });

}
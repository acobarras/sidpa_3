$(document).ready(function () {
    tb_alistamiento_etiquetas();
    envio_alistamiento_checked();
    ubicaciones();
});

var tb_alistamiento_etiquetas = function () {
    var table = $('#dt_alistamiento_etiquetas').DataTable({
        ajax: `${PATH_NAME}/almacen/consultar_items_op?dato=1`,
        "rowCallback": function (row, data, index) {
            if (data.cant_bodega == "0") {
                $('td:eq(4)', row).css('color', '#00000');//coloca color de cantidad bodega en NEGRO
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
            { "data": "Cant_solicitada" },
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
            {
                "data": "n_produccion", render: (data) => {
                    if (data == 0) {
                        return `Terceros`;
                    }
                    return `<b>${data}</b>`;
                }
            },
            //concatenar la data pedido y item 
            {
                "data": "pedido-item", render: (data, type, row) => {
                    return row['num_pedido'] + '-' + row['item'];
                }
            },
            { "data": "nombre_core" },
            { "data": "cant_x", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            // {
            //     "data": "orden_compra", render: (data, type, row) => {
            //         if (row['orden_compra'] != null) {
            //             return `No.<b>${row['orden_compra']}</b> <b>||</b> ${row['fecha_proveedor']}`;
            //         } else {

            //             return `Sin orden de compra`;
            //         }
            //     }
            // },
            { "data": "ruta" },
            { "data": "nombre_estado_item" },
            {
                "data": "opciones", render: (data, type, row) => {
                    return `<div class="custom-control custom-checkbox mr-sm-2">
                    <button class="btn btn-success btn-sm logistica_checked"><i class="fa fa-check"></i></button>  
                    <button class="btn btn-danger btn-sm logistica_regresar_op"><i class="fa fa-times"></i></button>      \n\
                    <button class="btn btn-info btn-sm observaciones_ver"  style="margin-top: 5px;" data-bs-toggle="modal" data-bs-target="#observaciones_Modal"><i class="fa fa-search"></i></button>
                    </div>`;
                }
            }
        ],
    });
    alistamiento_checked('#dt_alistamiento_etiquetas tbody', table);
    logistica_regresar_op('#dt_alistamiento_etiquetas tbody', table);
    observaciones_ver('#dt_alistamiento_etiquetas tbody', table);
}

var observaciones_ver = function (tbody, table) {
    $(tbody).on('click', 'button.observaciones_ver', function () {
        var data = table.row($(this).parents('tr')).data();
        $("#observaciones_p").empty().html(data.observaciones);
    });

}

var UBICACIONES = [];

var alistamiento_checked = function (tbody, table) {
    $(tbody).on('click', 'button.logistica_checked', function () {
        var data = table.row($(this).parents('tr')).data();
        UBICACIONES = [];
        cargar_span();
        $('#tipo_envio').val(1);// valor para saber si es un reporte parcial o completo "1" completo 
        $("#btn_reportar_factu_etiq").attr('data-id', JSON.stringify(data));
        if (data.alista_inv == 1 || data.cant_bodega == 0) {
            $("#logistica_checked_etiq_Modal").modal('show');
        } else {
            alertify.error("Se debe realizar primero el alistamiento de bodega.");
        }
    });
}

var logistica_regresar_op = function (tbody, table) {
    $(tbody).on('click', 'button.logistica_regresar_op', function () {
        var data = table.row($(this).parents('tr')).data();
        $('#tipo_envio').val(2);// valor para saber si es un reporte parcial o completo "1" completo 
        $("#btn_reportar_factu_etiq").attr('data-id', JSON.stringify(data));
        if (data.alista_inv == 1 || data.cant_bodega == 0) {
            $("#logistica_checked_etiq_Modal").modal('show');
        } else {
            alertify.error("Se debe realizar primero el alistamiento de bodega.");
        }
    });
}

var ubicaciones = function () {
    $('#ubicacion_materialmodal').on('change', function () {
        var ubicacion = $('#ubicacion_materialmodal').val();
        $('#ubicacion_materialmodal').val('**********');
        var array = ubicacion.split(";");
        if (array[0] != '!$' || array[1] != 'UBI') {
            alertify.error('La ubicacion que esta tratando de ingresar no cumple');
            $('#ubicacion_materialmodal').val('');
            $('#ubicacion_materialmodal').focus();
            return;
        }
        var ubicacion_com = array[2] + array[3];
        $('#ubicacion_materialmodal').val('');

        var agregar = UBICACIONES.find(element => element == ubicacion_com) ?? false;
        if (!agregar) {
            UBICACIONES.push(ubicacion_com);
        } else {
            alertify.error('Ya se cargo esta ubicacion');
        }
        cargar_span();
    })
}

var cargar_span = function () {
    var html = '';
    UBICACIONES.forEach((element, a) => {
        html += `${element} <button class="btn btn-danger btn-sm btn_eliminar" type="button" title="Eliminar Ubi" data-posicion="${a}"><i class="fas fa-trash-alt"></i></button><br>`
    });
    $('.span_ubi').html(html);
    eliminar_ubi();
}

var eliminar_ubi = function () {
    $('.btn_eliminar').on('click', function () {
        var posicion = $(this).data('posicion');
        UBICACIONES.splice(posicion, 1);
        cargar_span();
    })
}


var envio_alistamiento_checked = function () {
    $("#form_reporta_factu_etiq").on('submit', function (e) {
        e.preventDefault();
        var data = JSON.parse($("#btn_reportar_factu_etiq").attr('data-id'));
        var form = $(this).serializeArray();
        var form1 = $(this).serialize();
        var valida = validar_formulario(form);
        if (valida) {
            var obj_inicial = $(`#btn_reportar_factu_etiq`).html();
            btn_procesando(`btn_reportar_factu_etiq`);
            $.ajax({
                url: `${PATH_NAME}/almacen/reportar_facturacion_etiq`,
                type: 'POST',
                data: { form1, data },
                success: function (res) {
                    if (res.status == 1) {
                        $("#dt_alistamiento_etiquetas").DataTable().ajax.reload(function () {
                            $("#logistica_checked_etiq_Modal").modal('hide');
                            btn_procesando(`btn_reportar_factu_etiq`, obj_inicial, 1);
                            $("#form_reporta_factu_etiq")[0].reset();
                            alertify.success(res.msg);
                        });
                    } else {
                        $("#dt_alistamiento_etiquetas").DataTable().ajax.reload(function () {
                            btn_procesando(`btn_reportar_factu_etiq`, obj_inicial, 1);
                            alertify.error(res.msg);
                        });

                    }
                }
            });
        }
    });

}
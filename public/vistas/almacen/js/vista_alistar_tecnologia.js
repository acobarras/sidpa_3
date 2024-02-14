$(document).ready(function () {
    tb_alistamiento_tecnologia();
    envio_alistamiento_checked();
    ubicaciones();
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
            { "data": "fecha_compromiso", "visible": false },
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

var UBICACIONES = [];
var DATOS_TABLA = [];
var ejecutar = false;
var alistamiento_checked = function (tbody, table) {
    $(tbody).on('click', 'button.logistica_checked', function () {
        var data = table.row($(this).parents('tr')).data();
        UBICACIONES = [];
        if (DATOS_TABLA.length != 0 || DATOS_TABLA != '') {
            DATOS_TABLA = [];
        }
        DATOS_TABLA = data;
        cargar_span();
        $('#tipo_envio').val(1);// valor para saber si es un reporte parcial o completo "1" completo 
        $("#btn_reportar_factu").attr('data-id', JSON.stringify(data));
        if (data.alista_inv == 1 || data.cant_bodega == 0) {
            $("#logistica_checked_Modal").modal('show');
            $('#btn_reportar_factu').attr('disabled', 'disabled');
            $('#cantidad_factura').val('')
            $('#text_ubicacion_inv').empty().html('');
            if (!ejecutar) {
                consultar_ubicacion();
            }
        } else {
            alertify.error("Se debe realizar primero el alistamiento de bodega.");
        }
    });

}
var consultar_ubicacion = function () {
    $('#cantidad_factura').on('change', function () {
        ejecutar = true;
        var data = DATOS_TABLA;
        var cantidad = $(this).val();
        var codigo = data.codigo;
        var id_producto = data.id_producto;
        var num_pedido = data.num_pedido;
        var item = data.item;
        var cant_total = (parseInt(cantidad) + parseInt(data.cant_bodega));
        $('#div_ubicacion_inv').removeClass('d-none');
        if (cant_total > data.Cant_solicitada) {
            alertify.error('La cantidad digitada, supera la cantidad solicitada');
            $('#cantidad_factura').val(0);
            $('#cantidad_factura').empty().html('');
            $('#text_ubicacion_inv').empty().html('');
            $('#btn_reportar_factu').attr('disabled', 'disabled');
            return;
        }
        $.ajax({
            url: `${PATH_NAME}/almacen/consulta_tec_inventario`,
            type: 'POST',
            data: { codigo, cantidad, id_producto, num_pedido, item },
            success: function (res) {
                if (res.status == -1) {
                    $('#text_ubicacion_inv').empty().html(res.msg);
                    $('#btn_reportar_factu').attr('disabled', 'disabled');
                } else {
                    $('#btn_reportar_factu').removeAttr('disabled');
                    $('#text_ubicacion_inv').empty().html(res.msg);
                }
            }
        });
    })
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
    $("#form_reporta_factu").on('submit', function (e) {
        e.preventDefault();
        var data = JSON.parse($("#btn_reportar_factu").attr('data-id'));
        var form = $(this).serializeArray();
        var form1 = form.reduce(function (a, z) { a[z.name] = z.value; return a; }, {});
        var excepcion = ['ubicacion_material'];
        var valida = validar_formulario(form, excepcion);
        if (valida) {
            form1.ubicacion_material = 0;
            if (REQ_UBICACION) {
                if (UBICACIONES.length == 0) {
                    alertify.error('Tiene que colocar una ubicacion');
                    return;
                }
                form1.ubicacion_material = UBICACIONES.toString();
            }
            var obj_inicial = $(`#btn_reportar_factu`).html();
            btn_procesando(`btn_reportar_factu`);
            if (SESION == 1) {
                alertify.confirm(`ALERTA SIDPA`, `¿Desea descontar del inventario?`, function () {
                    var condicion = 1;
                    envio_ajax(form1, data, condicion, obj_inicial);

                }, function () {
                    var condicion = 2;
                    envio_ajax(form1, data, condicion, obj_inicial);

                }).set({
                    'closable': false,
                    'labels': { ok: 'Si', cancel: 'No' }
                });
            } else {
                var condicion = 2;
                envio_ajax(form1, data, condicion, obj_inicial);
            }
        }
    });
}
var envio_ajax = function (form1, data, condi, obj_inicial) {
    // var obj_inicial = $(`#btn_reportar_factu`).html();
    $.ajax({
        url: `${PATH_NAME}/almacen/reportar_facturacion`,
        type: 'POST',
        data: { form1, data, condi },
        success: function (res) {
            UBICACIONES = [];
            if (res.status == 1) {
                $("#dt_alistamiento_tecnologia").DataTable().ajax.reload(function () {
                    $("#logistica_checked_Modal").modal('hide');
                    btn_procesando(`btn_reportar_factu`, obj_inicial, 1);
                    $("#form_reporta_factu")[0].reset();
                    alertify.success(res.msg);
                });
            } else {
                $("#dt_alistamiento_tecnologia").DataTable().ajax.reload(function () {
                    btn_procesando(`btn_reportar_factu`, obj_inicial, 1);
                    alertify.error(res.msg);
                });

            }
        }
    });
}
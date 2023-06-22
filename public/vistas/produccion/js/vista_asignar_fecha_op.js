$(document).ready(function () {
    listar_ordenes(); //Funcion para listar las ordenes de produccion.
    $('#fecha_produccion').datepicker(); //funcion para cargar datapicker con días festivos
    $('#fecha_compromiso').datepicker(); //funcion para cargar datapicker con días festivos
    alertify.set('notifier', 'position', 'bottom-left');
    asignar_fecha_op();
    ver_turno_fecha_maquina();
});

var arrayorden = [];

var listar_ordenes = function () {
    var table = $('#tabla_ordenes_produccion').DataTable({
        "pageLength": 10,
        "order": [
            [4, "desc"]
        ],
        "ajax": `${PATH_NAME}/produccion/consultar_ordenes_producciones`,
        "columnDefs": [
            { className: 'text-center', targets: [0, 1, 2, 3, 4] }
        ],
        "columns": [
            { "data": "fecha_comp" },
            { "data": "num_produccion" },
            {
                "data": "nombre_maquina",
                render: function (data, type, row) {
                    return '<b>' + row['nombre_maquina'] + '</b>';
                }
            },
            { "data": "tamanio_etiq" },
            {
                "data": "ancho",
                render: function (data, type, row) {

                    if (row["ancho_confirmado"] == 0) {
                        return row["ancho_op"];
                    } else {
                        return row["ancho_confirmado"];
                    }

                }
            },
            { "data": "cant_op", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "mL_total", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "mL_descontado", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            {
                "data": "material",
                render: function (data, type, row) {

                    if (row["material_solicitado"] == '') {
                        return row["material"];
                    } else {
                        return row["material_solicitado"];
                    }
                }
            },
            {
                "data": "fecha_proveedor",
                render: function (data, type, row) {

                    if (row["fecha_proveedor"] == '0000-00-00') {
                        return '';
                    }
                    return row["fecha_proveedor"];
                }
            },
            { "data": "orden_compra" },
            {
                "data": "botones", render: function (data, type, row) {
                    return `<div class="select_acob">
                    <input type="checkbox" class="validar_checked me-2">&nbsp;  
                </div>`;
                }
            },
            {
                "data": "botones", render: function (data, type, row) {
                    return `<button class="btn btn-info verOrden" id="ver_${row.id_item_producir}" title="Ver O.P.">
                        <i class="fa fa-search" ></i>
                    </button>`;
                }
            }
        ],
    });
    verOrden(table);
    validar_check(table);
}

$('#mostrarTabla').on('click', function () {
    $('.InfoOrden').collapse('toggle');
    $("#datos_orden").show(500);
});

var validar_check = function (table) {
    $('#tabla_ordenes_produccion tbody').on("click", "tr input.validar_checked", function () {
        var data = table.row($(this).parents("tr")).data();
        //validar que array no sea vacio
        if ($(this).prop('checked') == true) {
            if (arrayorden.length > 0) {
                alertify.alert('ALERTA', 'No puede seleccionar mas ordenes');
                $(this).prop('checked', false);
                return;
            }
            //agregar el item marcado a la lista
            arrayorden.push({
                'id_item_producir': data.id_item_producir,
                'num_produccion': data.num_produccion,
                'material': data.material,
                'maquina': data.maquina
            });
        } else {
            for (var i = 0; i < arrayorden.length; i++) {
                if (data.id_item_producir === arrayorden[i].id_item_producir) {
                    arrayorden.splice(i, 1);
                }
            }
        }
    });
}

var verOrden = function (table) {
    $('#tabla_ordenes_produccion tbody').on("click", "tr button.verOrden", function () {
        var data = table.row($(this).parents("tr")).data();
        var obj_inicial = $(`#ver_${data.id_item_producir}`).html();
        btn_procesando_tabla(`ver_${data.id_item_producir}`);
        $.ajax({
            url: `${PATH_NAME}/produccion/consultar_items_orden`,
            type: 'POST',
            data: { num_produccion: data.num_produccion },
            success: function (res) {
                btn_procesando_tabla(`ver_${data.id_item_producir}`, obj_inicial, 1);
                $('.InfoOrden').collapse('toggle');
                $("#datos_orden").hide(500);
                $('#numORDEN').empty().html(data.num_produccion);
                $('#anchoORDEN').empty().html(data.ancho_op);
                $('#cantORDEN').empty().html(parseFloat(data.cant_op).toLocaleString(undefined, { minimumFractionDigits: 0 }));
                $('#mtORDEN').empty().html(parseFloat(data.mL_total).toLocaleString(undefined, { minimumFractionDigits: 0 }));
                $('#tamanoORDEN').empty().html(data.tamanio_etiq);
                var table = $("#datos_op").DataTable({
                    "data": res,
                    "columns": [
                        { "data": "codigo" },
                        { "data": "descripcion_productos" },
                        { "data": "ubi_troquel" },
                        { "data": "cant_op", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                        { "data": "metrosl", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                        { "data": "metros2", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                        {
                            "data": "pedido_item", render: function (data, type, row) {
                                return `${row.num_pedido}-${row.item}`;
                            }
                        },
                        { "data": "nombre_core" },
                        { "data": "cant_x" },
                        { "data": "nombre_r_embobinado" },
                        { "data": "fecha_cierre" },
                    ]
                });
            }
        });
    });
}

var ver_turno_fecha_maquina = function () {
    $('#fecha_produccion').change(function () {
        if (arrayorden.length <= 0) {
            $('#fecha_produccion').val('');
            alertify.error('Seleccione orden de producción para continuar.');
            $('#fecha_produccion').focus();
            return;
        }
        if ($('#fecha_produccion').val() == '') {
            alertify.error('Seleccione fecha de producción para continuar.');
            $('#fecha_produccion').focus();
            return;
        }
        var fecha = $(this).val();
        var envio = {
            'id_maquina': arrayorden[0].maquina,
            'fecha_produccion': fecha,
        }
        $.ajax({
            url: `${PATH_NAME}/produccion/consultar_turno_maquina`,
            type: 'POST',
            data: envio,
            success: function (res) {
                var table = $("#tabla_turnos").DataTable({
                    "data": res,
                    "columns": [
                        { "data": "nombre_maquina" },
                        { "data": "turno_maquina" },
                        { "data": "num_produccion" },
                        { "data": "mL_total", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                        { "data": "tamanio_etiq" },
                        { "data": "cant_op", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                        {
                            "data": "elige", render: function (data, type, row) {
                                var elegi;
                                if (row.material_solicitado == '') {
                                    elegi = row.material;
                                } else {
                                    elegi = row.material_solicitado;
                                }
                                return elegi;
                            }
                        },
                        { "data": "nombre_estado" },
                    ],
                });
            }
        });

    });
}

var asignar_fecha_op = function () {
    $('#asignar_fecha_op').on('click', function () {
        var form = $('#form_datos').serializeArray();
        var obj_inicial = $('#asignar_fecha_op').html();
        valida = validar_formulario(form);
        if (valida) {
            form = $('#form_datos').serialize();
            btn_procesando('asignar_fecha_op');
            var datos = {
                'form': form,
                'orden': arrayorden
            }
            $.ajax({
                url: `${PATH_NAME}/produccion/asignar_fecha_produccion`,
                type: 'POST',
                data: datos,
                success: function (res) {
                    arrayorden = [];
                    btn_procesando('asignar_fecha_op', obj_inicial, 1);
                    alertify.success('Dato Asignado correctamente');
                    limpiar_formulario('form_datos', 'input');
                    $('#tabla_ordenes_produccion').DataTable().ajax.reload();
                }
            });
        }
    });
}

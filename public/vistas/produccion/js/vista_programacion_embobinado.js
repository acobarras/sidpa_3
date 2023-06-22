$(document).ready(function () {
    select_2();
    $(".datepicker").datepicker();
    alertify.set('notifier', 'position', 'bottom-left');
    listar_op_programacion();
    boton_regresar();
    grabar_programacion();
    validar_embobinado();
});

var listar_op_programacion = function () {
    var table = $('#tabla_programacion_maquina').DataTable({
        "pageLength": 10,
        "order": [
            [4, "desc"]
        ],
        "ajax": `${PATH_NAME}/produccion/consulta_pendiente_embobinar`,
        "columnDefs": [
            { className: 'text-center', targets: [0, 1, 2, 3, 4] }
        ],
        "columns": [
            { "data": "num_produccion" },
            { "data": "fecha_comp" },
            { "data": "tamanio_etiq" },
            { "data": "mL_total", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "mL_descontado", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "nombre_estado" },
            {
                "data": "boton", render: function (data, type, row) {

                    return `<button type="button" class="btn btn-info ver_orden_op" id="ver_op${row.id_item_producir}" >
                <i class="fa fa-search"></i>
                </button>`;
                },
                "className": "text-center"
            }
        ],
    });
    mostrar_orden_op('#tabla_programacion_maquina tbody', table);
}

var boton_regresar = function () {
    $('#regresar').on('click', function (e) {
        $('#principal').css('display', '');
        $('#permisos_usuario').css('display', 'none');
    });
}

var mostrar_orden_op = function (tbody, table) {
    $(tbody).on('click', 'button.ver_orden_op', function () {
        var data = table.row($(this).parents("tr")).data();
        var num_produccion = data.num_produccion;
        obj_inicial = $(`#ver_op${data.id_item_producir}`).html();
        btn_procesando_tabla(`ver_op${data.id_item_producir}`);
        var id_maquina = '';
        $.ajax({
            url: `${PATH_NAME}/produccion/datos_programacion_embobinado`,
            type: 'POST',
            data: { num_produccion, id_maquina },
            success: function (res) {
                $('#fecha_embo').val('');
                $('#maquina_embo').val(0).change();
                $('#turno_embo').val('');
                $("#tabla_prog_total").DataTable().clear().draw();
                $('#principal').css('display', 'none');
                $('#permisos_usuario').css('display', '');
                var items = 0;
                var q_reportada = 0;
                var ml_embobinado = 0;
                res.forEach(element => {
                    items = items + 1;
                    q_reportada = parseInt(q_reportada) + parseInt(element.q_etiq_reportadas);
                    if (element.id_estado_item_pedido == '15') {
                        var etiq_por_avance = ((parseInt(element.cant_op) - parseInt(element.q_etiq_reportadas)) * parseFloat(element.avance));
                        var eti_cav = etiq_por_avance / parseInt(element.cav_cliente);
                        var ml_item = eti_cav / 1000;
                        ml_embobinado = ml_embobinado + ml_item;
                    }
                });
                $('#ancho_orden').empty().html(data.ancho_op);
                $('#cant_orden').empty().html($.fn.dataTable.render.number('.', ',', 0, '').display(data.cant_op));
                $('#cant_reportada').empty().html($.fn.dataTable.render.number('.', ',', 0, '').display(q_reportada));
                $('#ml_orden').empty().html($.fn.dataTable.render.number('.', ',', 0, '').display(ml_embobinado));
                $('#num_op').val(num_produccion);
                $('#num_orden').empty().html(num_produccion);
                $('#items_orden').empty().html(items);
                $('#tabla_embobinado').DataTable({
                    "data": res,
                    "columnDefs": [
                        { className: 'text-center', targets: [0, 1, 2, 3, 4] }
                    ],
                    "columns": [
                        { "data": "id_pedido_item" },
                        {
                            "data": "pedido_item", render: function (data, type, row) {
                                return `${row.num_pedido}-${row.item}`;
                            }
                        },
                        { "data": "fecha_compro_item" },
                        { "data": "codigo" },
                        { "data": "descripcion_productos" },
                        { "data": "cant_op", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                        { "data": "q_etiq_reportadas", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                        // { "data": "nombre_estado_item" },
                        { "data": "nombre_core" },
                        {
                            "data": "boton", render: function (data, type, row) {
                                var respu = '';
                                if (row.id_estado_item_pedido == 15) {
                                    respu = `<div class="select_acob text-center">
                                    <input type="checkbox" checked name="pedido_item${row.id_pedido_item}" value="${row.id_pedido_item}"/>
                                </div>`;
                                }
                                return respu;
                            },
                            "className": "text-center"
                        }
                    ],
                });
                btn_procesando_tabla(`ver_op${data.id_item_producir}`, obj_inicial, 1);
            }
        });
    });
}

var grabar_programacion = function () {
    $('#form_asigna_embobinado').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            form = $(this).serialize();
            // Recorro la tabla validando que se diligencie en su totalidad
            var data = $("#tabla_embobinado").DataTable().rows().data();
            var dato_tabla = $("#tabla_embobinado").DataTable().rows().nodes();
            var mensaje = '';
            var data_envio = [];
            $.each(dato_tabla, function (index, value) {
                var p = $(this).find('input').val();
                var estado_radio = RadioElegido(`pedido_item${p}`);
                // var codigo_material = '';
                if (estado_radio == 'ninguno') {
                } else {
                    data_envio.push(data[index]);
                }
            });
            if (mensaje == '' && data_envio == '') {
                mensaje = 'Se debe elegir un item para poder programar.';
                alertify.error(mensaje);
                return;
            }
            $.ajax({
                url: `${PATH_NAME}/produccion/programacion_embobinado`,
                type: "POST",
                data: { data_envio, form },
                success: function (res) {
                    $("#tabla_programacion_maquina").DataTable().ajax.reload();
                    $('#regresar').click();
                }
            });
        }

    });
}

var validar_embobinado = function () {
    $('.ver_programacion').on('change', function () {
        var id_maquina = $('#maquina_embo').val();
        var fecha_embo = $('#fecha_embo').val();
        if (id_maquina != '' && fecha_embo != '') {
            $.ajax({
                url: `${PATH_NAME}/produccion/valida_embobinado`,
                type: "POST",
                data: { id_maquina, fecha_embo },
                success: function (res) {
                    $('#tabla_prog_total').DataTable({
                        "data": res,
                        "columns": [
                            { "data": "num_produccion" },
                            { "data": "nombre_maquina" },
                            { "data": "turno_maquina" },
                            { "data": "ml_asignados", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                            { "data": "nombre_estado" },
                        ],
                    });
                    
                }
            });
        }
    });
}

$(document).ready(function () {
    select_2();
    consultar_pedido();
    enviar_prioridad();
    cambio_actividad();
    consultar_prioridad();
});

var cambio_actividad = function () {
    $('#actividad').on('change', function () {
        var valor = $(this).val();
        if (valor == 2) {
            $('#form_pedido').addClass('d-none');
        } else {
            $('#form_pedido').removeClass('d-none');
        }
    });
}

var consultar_pedido = function () {
    $('#pedido').on('change', function () {
        var num_pedido = $(this).val();
        if (num_pedido == '') {
            $('#slect_items').empty().html('');
        } else {
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/produccion/consultar_items_pedido_impresion`,
                data: { num_pedido },
                success: function (response) {
                    $('#cliente').val(response[0].nombre_empresa)
                    var items = '<option value="0">Completo</option>';
                    if (response != -1) {
                        response.forEach(element => {
                            items += `
                            <option value='${JSON.stringify(element)}'>Item ${element.item}</option>                   
                            `;
                        });
                        $('#item').empty().html(items);
                    }
                }
            });
        }
    });
}
var enviar_prioridad = function () {
    $('#form_prioridad').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#enviar_prioridad').html();
        var form = $(this).serializeArray();
        var valor = $('#actividad').val();
        var excepcion = ['item', 'observacion'];
        if (valor == 2) {
            excepcion = ['item', 'observacion', 'pedido', 'item', 'cliente'];
        }
        var valida = validar_formulario(form, excepcion);
        if (valida) {
            btn_procesando('enviar_prioridad');
            var observaciones = $('#observacion').val();
            var id_areas = $('#area').val();
            var form = $(this).serialize();
            // form += observaciones;
            if (observaciones == '') {
                alertify.error('El campo observacion es requerido');
                $('#observacion').focus();
                return;
            }
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/enviar_solicitud_prioritaria`,
                data: { form, id_areas },
                success: function (res) {
                    if (res.status == true) {
                        btn_procesando(`enviar_prioridad`, obj_inicial, 1);
                        alertify.success('Prioridad creada');
                        limpiar_formulario('form_prioridad', 'input');
                        limpiar_formulario('form_prioridad', 'select');
                        limpiar_formulario('form_prioridad', 'textarea');
                        $('#observacion').val('');
                    } else {
                        btn_procesando(`enviar_prioridad`, obj_inicial, 1);
                        alertify.error('Algo a pasado');
                    }
                }
            });
        }
    });
}
var consultar_prioridad = function () {
    $('#consulta-tab').click(function (e) {
        e.preventDefault();
        var id_prioridad = 0;
        $.ajax({
            type: "POST",
            data: { id_prioridad },
            url: `${PATH_NAME}/consultar_prioridades`,
            success: function (response) {
                var table = $('#tabla_prioridades').DataTable({
                    data: response,
                    dom: 'Bflrtip',
                    buttons: [{
                        extend: 'excelHtml5',
                        text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                        tittleAttr: ' Exportar a exel',
                        className: 'btn btn-success',
                    }],
                    "columns": [
                        { "data": "id_prioridad" },
                        { "data": "prioridad" },
                        { "data": "fecha_crea" },
                        { "data": "nombre_empresa" },
                        {
                            "render": function (data, type, row) {
                                if (row.pedido == 0) {
                                    return 'N/A';
                                } else {
                                    return row.pedido;
                                }
                            }
                        },
                        {
                            "render": function (data, type, row) {
                                if (row.item == 0) {
                                    return 'N/A';
                                } else {
                                    return row.item;
                                }
                            }
                        },
                        {
                            "data": "respuestas", render: function (data, type, row) {
                                return `<div class="overflow-auto" style="max-height: 10rem;">
                            ${row.respuestas}
                            </div>
                            `;
                            }
                        },
                        {
                            "data": "estado", render: function (data, type, row) {
                                if (row.estado == 1) {
                                    return '<h6 style="color:green">Abierto</h6>';
                                } else {
                                    return '<h6 style="color:red">Cerrado</h6>';
                                }
                            }
                        },
                    ],
                });
            }
        });
    });
}
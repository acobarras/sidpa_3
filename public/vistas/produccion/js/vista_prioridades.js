$(document).ready(function () {
    select_2();
    consultar_op();
    enviar_prioridad();
    prioridades_abiertas();
    prioridades_cerradas();
});

var consultar_op = function () {
    $('#orden_produccion').on('blur', function () {
        var num_produccion = $(this).val();
        $.ajax({
            url: `${PATH_NAME}/produccion/consultar_op_prioridad`,
            type: 'POST',
            data: { num_produccion },
            success: function (res) {
                if (res.status == -1) {
                    alertify.error(res.msg);
                    return;
                } else {
                    $('#fecha_compro').val(res['data'][0].fecha_comp);
                    var item = "<option value='0'selected>Completa</option>";
                    res['data'].forEach(element => {
                        item +=/*html*/
                            ` <option value="${JSON.stringify(element.num_pedido + '-' + element.item)}">${element.num_pedido}-${element.item}</option>`;
                    });
                    $('#item').html(item);
                }
            }
        });
    });
}

var prioridades_abiertas = function () {
    $('#profile-tab').on('click', function () {
        consultar_prioridades('1,2', 'tabla_prioridades');
    })
}
var prioridades_cerradas = function () {
    $('#consulta-tab').on('click', function () {
        consultar_prioridades('3', 'prioridades_cerradas');
    })
}

var enviar_prioridad = function () {
    $('#form_prioridad').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#enviar_prioridad').html();
        var form = $(this).serializeArray();
        var excepcion = ['item', 'id_prioridad'];
        btn_procesando('enviar_prioridad');
        var valida = validar_formulario(form, excepcion);
        if (valida) {
            var form = $(this).serialize();
            $.ajax({
                url: `${PATH_NAME}/produccion/enviar_prioridad`,
                type: 'POST',
                data: { form },
                success: function (res) {
                    if (res.status == 1) {
                        alertify.success(res.msg);
                        btn_procesando('enviar_prioridad', obj_inicial, 1);
                        limpiar_formulario('form_prioridad', 'input');
                        limpiar_formulario('form_prioridad', 'select');
                        limpiar_formulario('form_prioridad', 'textarea');
                    }
                }
            });
        }
    });
}

var consultar_prioridades = function (estado, tabla) {
    $.ajax({
        type: "POST",
        url: `${PATH_NAME}/produccion/consultar_prioridades`,
        data: { estado },
        success: function (response) {
            var table = $(`#${tabla}`).DataTable({
                "data": response.data,
                "columns": [
                    { "data": "id_prioridad" },
                    { "data": "num_produccion" },
                    {
                        "data": "item", render: function (data, type, row) {
                            var texto = row.item
                            if (texto == 0) {
                                texto = 'Completo';
                            }
                            return texto;
                        }
                    },
                    { "data": "fecha_comp" },
                    { "data": "observacion" },
                    { "data": "nombre_estado" },
                ],
            });
        }
    });
}
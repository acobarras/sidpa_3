$(document).ready(function () {
    select_2();
    alertify.set('notifier', 'position', 'bottom-left');
    consultar_factura();
    agregar_guia();
});

var DATA = [];

var consultar_factura = function () {
    $('#form_consultar_documento').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#boton_enviar').html();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            btn_procesando('boton_enviar');
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/logistica/consultar_lista_empaque`,
                data: form,
                success: function (respu) {
                    btn_procesando('boton_enviar', obj_inicial, 1);
                    var table = $('#tabla_item_lista').DataTable({
                        "data": respu,
                        'ordering': false,
                        columns: [
                            { "data": "nombre_empresa" },
                            {
                                "data": "pedido_item", render: function (data, type, row) {
                                    return `${row.num_pedido}-${row.item}`;
                                }
                            },
                            { "data": "cantidad_factura", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                            { "data": "descripcion_productos" },
                            {
                                "data": "documento", render: function (data, type, row) {
                                    var num_documento = row.num_remision;
                                    if (row.tipo_documento == 8 || row.tipo_documento == 9) {
                                        num_documento = row.num_factura;
                                    }
                                    return `${row.letra_tipo_documento} ${num_documento}`;
                                }
                            },
                        ]
                    });
                    DATA = respu;
                }
            });

        }
    });
}

var agregar_guia = function () {
    $('#form_agregar_guia').submit(function (e) {
        e.preventDefault();
        if (DATA == '') {
            alertify.error('Por favor realice la consulta de la lista de empaque para asignar guia.');
            return;
        }
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/logistica/insertar_num_guia`,
                data: { DATA, form },
                success: function (response) {
                    if (response == 1) {
                        $('#tabla_item_lista').DataTable().destroy();
                        $('#list-items').empty().html('');
                        $('#guia').val('');
                        $('#num_lista_empaque').val('');
                        alertify.success('Registro Exitoso !!');
                        DATA = [];
                    }
                }
            });
        }
    });
}
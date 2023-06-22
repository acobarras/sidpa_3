$(document).ready(function () {
    listar_items_compra();
    $('#fecha_proveedor').datepicker({ minDate: new Date('1999/10/25') }); //funcion para cargar datapicker con días festivos
    selecciona_items_confirmar_etiq();
});
//---------------------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------------------//
var listar_items_compra = function () {

    var table = $("#dt_items_compra").DataTable({
        "ajax": `${PATH_NAME}/compras/consultar_items_pendientes_compra_etiq`,
        "columns": [
            { "data": "fecha_compromiso" },
            { "data": "nombre_empresa" },
            {
                "data": "num_pedido", render: function (data, type, row) {
                    return row['num_pedido'] + '-' + row['item'];
                }
            },
            { "data": "codigo" },
            { "data": "descripcion_productos" },
            { "data": "cant_faltante", render: $.fn.dataTable.render.number('.', ',', '', '') },
            {
                "data": "option", render: (date, type, row) => {
                    if (row.id_estado_item_pedido == 22) {
                        return `Pendiente <i class="fa fa-history"></i>`;
                    } else {
                        return `<center><button class="btn btn-primary esperar_material_etiq" type="button" id="btn_espera_fecha_etq${row.id_pedido_item}"  title="Espera Fecha"><i class="fa fa-clock"></i></button></center>`;
                    }
                }
            },
            {
                "data": "checkbox", render: (data, type, row) => {
                    return `<div class="select_acob text-center">
                    <input class="asigna_fecha_etiq" type="checkbox" name="asigna_etiq${row.id_pedido_item}" value="${row.id_pedido_item}"/>
                </div>`;
                }
            },
        ],
    });
    eviar_espera_fecha_etiquetas('#dt_items_compra tbody', table);
    asigna_fecha_entrega();
};

var eviar_espera_fecha_etiquetas = function (tbody, table) {
    $(tbody).on('click', 'button.esperar_material_etiq', function () {
        var data = table.row($(this).parents("tr")).data();
        var obj_inicial = $(`#btn_espera_fecha_etq${data['id_pedido_item']}`).html();
        btn_procesando_tabla(`btn_espera_fecha_etq${data['id_pedido_item']}`);
        $.ajax({
            url: `${PATH_NAME}/compras/fecha_pendiente_etiquetas_tecnologia`,
            type: 'POST',
            data: data,
            success: function (res) {
                if (res.state == 1) {
                    $("#dt_items_compra").DataTable().ajax.reload(function () {
                        btn_procesando_tabla(`btn_espera_fecha_etq${data['id_pedido_item']}`, obj_inicial, 1);
                        alertify.success(res.msg);
                    });
                } else {
                    $("#dt_items_compra").DataTable().ajax.reload(function () {
                        btn_procesando_tabla(`btn_espera_fecha_etq${data['id_pedido_item']}`, obj_inicial, 1);
                        alertify.error(res.msg);
                    });

                }
            }
        });
    });
};
var datos = [];
var selecciona_items_confirmar_etiq = function () {
    $('#dt_items_compra tbody').on("click", "input.asigna_fecha_etiq", function () {
        var data = $("#dt_items_compra").DataTable().row($(this).parents("tr")).data();
        if ($(this).prop('checked') == true) {
            datos.push(data);
        } else {
            for (var i = 0; i < datos.length; i++) {
                if (datos[i].id_pedido_item === data.id_pedido_item) {
                    datos.splice(i, 1);
                }
            }
        }
    });
}
var asigna_fecha_entrega = function () {
    $("#form_asigna_fecha_entrega_etiq").submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var form1 = $(this).serialize();

        var valida = validar_formulario(form);

        if (valida) {
            if (datos.length === 0) {
                alertify.error("Debe elegir algun item para continuar.");
            } else {
                var obj_inicial = $(`#asigna_material_etiq`).html();
                btn_procesando(`asigna_material_etiq`);
                $.ajax({
                    url: `${PATH_NAME}/compras/asigna_material_tec`,
                    type: "POST",
                    data: { datos, form1 },
                    success: function (res) {
                        if (res.state == 1) {
                            $("#dt_items_compra").DataTable().ajax.reload(function () {
                                btn_procesando(`asigna_material_etiq`, obj_inicial, 1);
                                $("#form_asigna_fecha_entrega_etiq")[0].reset();
                                alertify.success(res.msg);
                            });
                        } else {
                            $("#dt_items_compra").DataTable().ajax.reload(function () {
                                btn_procesando(`asigna_material_etiq`, obj_inicial, 1);
                                alertify.error(res.msg);
                            });

                        }
                    }
                });
            }
        }
    });
}
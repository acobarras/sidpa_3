$(document).ready(function () {
    tb_compras_tecnologia();
    $('#fecha_proveedor').datepicker({ minDate: new Date('1999/10/25') }); //funcion para cargar datapicker con días festivos
    selecciona_items_confirmar_tec();
});

var tb_compras_tecnologia = function () {
    var table = $("#dt_compra_tecnologia").DataTable({
        ajax: `${PATH_NAME}/compras/consultar_items_pendientes_compra`,
        columns: [
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
                        return `<center><button class="btn btn-primary esperar_material_tec" type="button" id="btn_espera_fecha_tec${row.id_pedido_item}"  title="Espera Fecha"><i class="fa fa-clock"></i></button></center>`;
                    }
                }
            },
            {
                "data": "checkbox", render: (data, type, row) => {
                    return `<div class="select_acob text-center">
                    <input class="reporta_tec" type="checkbox" name="asigna_tec${row.id_pedido_item}" value="${row.id_pedido_item}"/>
                </div>`;
                }
            },
        ],
    });
    eviar_espera_fecha_tecnologia('#dt_compra_tecnologia tbody', table);
    asigna_fecha_entrega();
}

//------------------------------------------------------ boton azul reloj espera fecha ------------------------------------------------------------->

var eviar_espera_fecha_tecnologia = function (tbody, table) {
    $(tbody).on('click', 'button.esperar_material_tec', function () {
        var data = table.row($(this).parents("tr")).data();
        var obj_inicial = $(`#btn_espera_fecha_tec${data['id_pedido_item']}`).html();
        btn_procesando_tabla(`btn_espera_fecha_tec${data['id_pedido_item']}`);
        $.ajax({
            url: `${PATH_NAME}/compras/fecha_pendiente_etiquetas_tecnologia`,
            type: 'POST',
            data: data,
            success: function (res) {
                if (res.state == 1) {
                    $("#dt_compra_tecnologia").DataTable().ajax.reload(function () {
                        btn_procesando_tabla(`btn_espera_fecha_tec${data['id_pedido_item']}`, obj_inicial, 1);
                        alertify.success(res.msg);
                    });
                } else {
                    $("#dt_compra_tecnologia").DataTable().ajax.reload(function () {
                        btn_procesando_tabla(`btn_espera_fecha_tec${data['id_pedido_item']}`, obj_inicial, 1);
                        alertify.error(res.msg);
                    });

                }
            }
        });
    });
};
var datos = [];
var selecciona_items_confirmar_tec = function () {
    $('#dt_compra_tecnologia tbody').on("click", "input.reporta_tec", function () {
        var data = $("#dt_compra_tecnologia").DataTable().row($(this).parents("tr")).data();
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
    $("#form_asigna_fecha_entrega_tec").submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var form1 = $(this).serialize();

        var valida = validar_formulario(form);
        if (valida) {
            if (datos.length === 0) {
                alertify.error("Debe elegir algun item para continuar.");
                return;
            }
            var obj_inicial = $(`#asigna_material_tec`).html();
            btn_procesando(`asigna_material_tec`);
            $.ajax({
                url: `${PATH_NAME}/compras/asigna_material_tec`,
                type: "POST",
                data: { datos, form1 },
                success: function (res) {
                    datos = [];
                    if (res.state == 1) {
                        $("#dt_compra_tecnologia").DataTable().ajax.reload(function () {
                            btn_procesando(`asigna_material_tec`, obj_inicial, 1);
                            $("#form_asigna_fecha_entrega_tec")[0].reset();
                            alertify.success(res.msg);
                        });
                    } else {
                        $("#dt_compra_tecnologia").DataTable().ajax.reload(function () {
                            btn_procesando(`asigna_material_tec`, obj_inicial, 1);
                            alertify.error(res.msg);
                        });

                    }
                }
            });
        }
    });
}
$(document).ready(function () {
    dt_materia_prima_op();
    select_2();
    alertify.set('notifier', 'position', 'bottom-left');
    $('#fecha_proveedor').datepicker({ minDate: new Date('1999/10/25') }); //funcion para cargar datapicker con días festivos
    solo_numeros('ancho_confirmado');
    selecciona_items_confirmar();
});

var dt_materia_prima_op = function () {
    var table = $("#dt_materia_prima_op").DataTable({
        ajax: `${PATH_NAME}/compras/consultar_ordenes_produccion`,
        "autoWidth": true,
        columns: [
            { "data": "fecha_comp" },
            { "data": "num_produccion" },
            { "data": "tamanio_etiq" },
            { "data": "ancho_op" },
            { "data": "cant_op", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "mL_total", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "mL_descontado", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "material" },
            {
                "data": "opciones", render: (date, type, row) => {
                    if (row['espera_material'] != 0) {
                        return '<center>\n\
                        <button class="btn btn-info verOrden" type="button" data-bs-toggle="collapse" data-bs-target=".TablaOrden" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-search"></i></button>\n\
                        Pendiente <i class="fa fa-history"></i>';
                    } else {
                        return `<center>\n\
                        <button class="btn btn-info verOrden" type="button" ><i class="fa fa-search"></i></button>\n\
                        <button class="btn btn-primary esperar_material" type="button" id='btn_espera_fecha${row.id_item_producir}'  title="Espera Fecha"><i class="fa fa-clock"></i></button>\n\
                      <center>`;
                    }
                }
            },
            {
                "data": "checkbox", render: (data, type, row) => {
                    return `<div class="select_acob text-center">
                    <input class="confirma_fecha" type="checkbox" name="asigna${row.id_item_producir}" value="${row.id_item_producir}"/>
                </div>`;
                }
            },
        ],
    });

    ver_detalle_orden("#dt_materia_prima_op tbody", table);
    eviar_espera_fecha("#dt_materia_prima_op tbody", table);
    asigna_material();

};
//------------------------------------------------------ boton azul lupa detalle de items op ------------------------------------------------------------->

var ver_detalle_orden = function (tbody, table) {
    $(tbody).on('click', 'button.verOrden', function () {
        var data = table.row($(this).parents("tr")).data();
        $(".TablaOrden").css('display', '');
        $(".tb_materia_prima_op").toggle(500);
        $.ajax({
            url: `${PATH_NAME}/produccion/consultar_items_orden`,
            type: 'POST',
            data: { num_produccion: data.num_produccion },
            success: function (res) {
                $("#dt_detalle_materia_prima_op").DataTable({
                    "data": res,
                    "columns": [
                        { "data": "codigo" },
                        { "data": "descripcion_productos" },
                        { "data": "ubi_troquel" },
                        { "data": "cant_faltante" },
                        { "data": "metrosl" },
                        { "data": "metros2" },
                        {
                            "data": "num_pedido", render: function (date, type, row) {
                                var pedido = row['num_pedido'];
                                var item = row['item'];
                                var pedido_item = pedido + '-' + item;
                                return pedido_item;
                            }
                        },
                        { "data": "nombre_core" },
                        { "data": "cant_x" },
                        { "data": "nombre_r_embobinado" },
                    ],
                });
                $('#numORDEN').empty().html(data.num_produccion);
                $('#anchoORDEN').empty().html(data.ancho_confirmado);
                $('#cantORDEN').empty().html(parseFloat(data.cant_op).toLocaleString(undefined, { minimumFractionDigits: 0 }));
                $('#mtORDEN').empty().html(parseFloat(data.mL_total).toLocaleString(undefined, { minimumFractionDigits: 0 }));
                $('#etiqTAMNIO').empty().html(data.tamanio_etiq);
            }
        });
    });
}

$('#mostrarTabla').on('click', function () {
    $(".TablaOrden").css('display', 'none');
    $(".tb_materia_prima_op").toggle(500);
});

//------------------------------------------------------ boton azul reloj espera fecha ------------------------------------------------------------->

var eviar_espera_fecha = function (tbody, table) {
    $(tbody).on('click', 'button.esperar_material', function () {
        var data = table.row($(this).parents("tr")).data();
        var obj_inicial = $(`#btn_espera_fecha${data['id_item_producir']}`).html();
        btn_procesando_tabla(`btn_espera_fecha${data['id_item_producir']}`);
        $.ajax({
            url: `${PATH_NAME}/compras/espera_por_fecha`,
            type: 'POST',
            data: data,
            success: function (res) {
                if (res.state == 1) {
                    $("#dt_materia_prima_op").DataTable().ajax.reload(function () {
                        btn_procesando_tabla(`btn_espera_fecha${data['id_item_producir']}`, obj_inicial, 1);
                        alertify.success(res.msg);
                    });
                } else {
                    $("#dt_materia_prima_op").DataTable().ajax.reload(function () {
                        btn_procesando_tabla(`btn_espera_fecha${data['id_item_producir']}`, obj_inicial, 1);
                        alertify.error(res.msg);
                    });

                }
            }
        });
    });
};
$('#material_solicitado').change(function () {
    var costo = $(this).find(':selected').attr('data-id');
    $("#costo").val(costo);

});
var datos = [];
var selecciona_items_confirmar = function () {
    $('#dt_materia_prima_op tbody').on("click", "input.confirma_fecha", function () {
        var data = $("#dt_materia_prima_op").DataTable().row($(this).parents("tr")).data();
        if ($(this).prop('checked') == true) {
            datos.push(data);
        } else {
            for (var i = 0; i < datos.length; i++) {
                if (datos[i].id_item_producir === data.id_item_producir) {
                    datos.splice(i, 1);
                }
            }
        }
    });
}


var asigna_material = function () {
    $("#form_asigna_materiales").on('submit', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var form1 = $(this).serialize();
        var valida = validar_formulario(form);
        if (valida) {
            if (datos.length === 0) {
                alertify.error("Debe elegir alguna orden para continuar.");
            } else {
                var obj_inicial = $(`#asigna_material`).html();
                btn_procesando(`asigna_material`);
                $.ajax({
                    url: `${PATH_NAME}/compras/asigna_material`,
                    type: "POST",
                    data: { datos, form1 },
                    success: function (res) {
                        datos=[];
                        if (res.state == 1) {
                            $("#dt_materia_prima_op").DataTable().ajax.reload(function () {
                                btn_procesando(`asigna_material`, obj_inicial, 1);
                                $("#form_asigna_materiales")[0].reset();
                                alertify.success(res.msg);
                            });
                        } else {
                            $("#dt_materia_prima_op").DataTable().ajax.reload(function () {
                                btn_procesando(`asigna_material`, obj_inicial, 1);
                                alertify.error(res.msg);
                            });

                        }
                    }
                });
            }
        }
    });
};

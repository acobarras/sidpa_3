$(document).ready(function () {
    click();
    $('#home-tab').click();
    obtener_data();
});

var data_tabla = {};

var tabla_pedidos = function (val = 1, tab = 'tb_pedidos_credito') {
    var tipo = val;//El 1 significa que son los pedidos de credito, 2 son pedidos de contado, 3 sin fecha de compromiso

    var table = $(`#${tab}`).DataTable({
        "ajax": {
            "url": `${PATH_NAME}/consulta_pedidos`,
            "type": "POST",
            "data": { tipo },
        },
        "columns": [
            { "data": "fecha_compromiso" },
            { "data": "fecha_crea_p" },
            { "data": "fecha_cierre" },
            { "data": "nombre_empresa" },
            { "data": "num_pedido" },
            {
                "render": function (data, type, row) {
                    return row.items_reportados + ' de ' + row.items_pedido;
                }
            },
            {
                "render": function (data, type, row) {
                    return row.items_op;
                }
            },
            {
                "render": function (data, type, row) {
                    if (row.forma_pago != 4) {
                        return 'Contado';
                    } else {
                        return 'Credito';
                    }
                }
            },
            {
                "render": function (data, type, row) {
                    return row.nombres + ' ' + row.apellidos;
                }
            },
            {
                "render": function (data, type, row) {
                    return `<center>
                                    <div class="text-center">
                                            <button type="button" class="btn btn-primary cons_pedido" id="num${row.num_pedido}" data_pedido='${JSON.stringify(row)}'>
                                            <span class="fas fa-search"></span>
                                            </button>
                                        </div>
                                <center>`
                }
            },
        ]
    });
    data_tabla = {
        'tabla2': table,
    }
}


var click = function () {
    $('#home-tab').on('click', function () {
        tabla_pedidos(1, 'tb_pedidos_credito');
    });
    $('#profile-tab').on('click', function () {
        tabla_pedidos(2, 'tb_pedidos_contado');
    })
    $('#sin_compromiso-tab').on('click', function () {
        tabla_pedidos(3, 'tb_sin_compromiso');
    })
    $('#incompletos-tab').on('click', function () {
        pedidos_incompletos();
    })
}

var detailConsul = [];

var obtener_data = function () {
    $("#tb_pedidos_credito, #tb_pedidos_contado, #tb_sin_compromiso").on("click", "button.cons_pedido", function (e) {
        e.preventDefault();
        var data = JSON.parse($(this).attr('data_pedido'));
        var tr = $(this).closest('tr');
        var row = data_tabla.tabla2.row(tr);
        var idx = $.inArray(tr.attr('id'), detailConsul);
        var obj_inicial = $(`#num${data.num_pedido}`).html();
        btn_procesando_tabla(`num${data.num_pedido}`);
        $.ajax({
            url: `${PATH_NAME}/consulta_seguimientos`,
            type: "POST",
            data: { data },
            success: function (res) {
                btn_procesando_tabla(`num${data.num_pedido}`, obj_inicial, 1);
                if (row.child.isShown()) {
                    tr.removeClass('details');
                    row.child.hide();
                    detailConsul.splice(idx, 1);
                } else {
                    tr.addClass('details');
                    row.child(tabla_detalle_consul_ped_item(data.num_pedido)).show();
                    var tabla = $(`#dt_ver_seguimiento${data.num_pedido}`).DataTable({
                        "data": res,
                        "columns": [
                            { data: "pedido" },
                            { data: "item" },
                            { data: "codigo_producto" },
                            { data: "descripcion_productos" },
                            { data: "n_produccion" },
                            { data: "Cant_solicitada" },
                            { data: "cant_reportada" },
                            {
                                "render": function (data, type, row) {
                                    var cantidad_faltante = (row.Cant_solicitada - row.cant_reportada);
                                    if (cantidad_faltante > 0) {
                                        return `<span class="text-danger">${cantidad_faltante}</span>`;
                                    }
                                    return cantidad_faltante;
                                }
                            },
                            { data: "nombre_estado_item" },
                            {
                                "render": function (data, type, row) {
                                    return `<center>
                                    <div class="text-center">
                                            <button type="button" class="btn btn-warning cons_item" id="item${row.item}">
                                            <span class="fas fa-search"></span>
                                            </button>
                                        </div>
                                <center>`
                                }
                            },
                        ],
                    });
                    seguimientos(`#dt_ver_seguimiento${data.num_pedido}`, tabla);
                    if (idx === -1) {
                        detailConsul.push(tr.attr('id'));
                    }
                }
            }
        });
    });
};

var seguimientos = function (tbody, table) {
    $(tbody).on("click", "button.cons_item", function () {
        var data = table.row($(this).parents("tr")).data();
        $('#modal_movimientos').modal('show');
        $('#nombre').html(`Item ${data.item}`);
        if (data.n_produccion != '') {
            $('#nombre').html(`Orden N° ${data.n_produccion}`);
        }
        var tabla = $(`#movimientos_item`).DataTable({
            "ajax": {
                "url": `${PATH_NAME}/movimientos_item`,
                "type": "POST",
                "data": { data },
            },
            "columns": [
                { data: "fecha_crea" },
                { data: "hora_crea" },
                {
                    "render": function (data, type, row) {
                        return row.nombres + ' ' + row.apellidos;
                    }
                },
                { data: "nombre_area_trabajo" },
                { data: "nombre_actividad_area" },
                { data: "observacion" },
            ],
        });
    });
}

function tabla_detalle_consul_ped_item(data) {
    var respu = /*html*/ `
    <br>
        <div class="container-fluid recuadro">
            <br>
            <center>
                <h3>Registro Pedido Item</h3>
            </center>
            <table class="table-bordered table table-responsive-lg table-responsive-md " cellspacing="0" width="100%" id="dt_ver_seguimiento${data}">
                <thead class="thead-dark">
                    <tr>
                        <th>Pedido</th>
                        <th>Item</th>
                        <th>Codigo</th>
                        <th>Descripción</th>
                        <th>Orden Produccion</th>
                        <th>Cant Solicitada</th>
                        <th>Cant Facturada</th>
                        <th>Cant Faltante</th>
                        <th>Estado Item</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <br><br><br> 
        </div>   
    </div>`;
    return respu;
}

var pedidos_incompletos = function () {
    var tabla = $(`#pedidos_incompletos`).DataTable({
        "ajax": {
            "url": `${PATH_NAME}/pedidos_incompletos`,
            "type": "POST",
        },
        "columns": [
            { data: "num_pedido" },
            { data: "item" },
            { data: "Cant_solicitada", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { data: "cant_facturada", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { data: "cant_reportada", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            {
                "render": function (data, type, row) {
                    return informa_diferencia(row);
                }
            },
            {
                "render": function (data, type, row) {
                    var saldo = row.Cant_solicitada - row.cant_facturada - row.cant_reportada;
                    return $.fn.dataTable.render.number('.', ',', 0, '').display(saldo) ;
                }
            },
        ],
    });
}
$(document).ready(function () {
    consulta_product();
});

var consulta_product = function () {
    $('#cons_productividad').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $(`#buscar_productividad`).html();
        var form = $(this).serializeArray();
        var validar = validar_formulario(form);
        btn_procesando(`buscar_productividad`);
        if (validar) {
            var fecha_hasta = $('#fecha_hasta').val();
            var fecha_desde = $('#fecha_desde').val();
            $.ajax({
                url: `${PATH_NAME}/consulta_productividad`,
                type: 'POST',
                data: { fecha_hasta, fecha_desde },
                success: function (res) {
                    btn_procesando(`buscar_productividad`, obj_inicial, 1);
                    var tabla = $(`#tabla_productividad`).DataTable({
                        'data': res,
                        "dom": 'Bfrtip',
                        "buttons": [
                            'copy', 'excel'
                        ],
                        columns: [
                            {
                                data: "nombres", render: function (data, type, row) {
                                    return row.nombres + ' ' + row.apellidos;
                                }
                            },
                            { data: "total_ml", render: $.fn.DataTable.render.number(',', '.', 0) },
                            { data: "total_horas" },
                            {
                                data: "opcion", render: function (data, type, row) {
                                    return `<button class="btn btn-primary mas_info"><i class="fas fa-search-plus"></i></button>`;
                                }
                            }
                        ]
                    });
                    datos_productividad('#tabla_productividad tbody', tabla);
                }
            });
        }
    });
}
var detaConsulta = [];
var datos_productividad = function (tbody, table) {
    $(tbody).on("click", "button.mas_info", function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var id = $.inArray(tr.attr('id_persona'), detaConsulta);
        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();
            detaConsulta.splice(id, 1);
        } else {
            var data = $('#tabla_productividad').DataTable().row($(this).parents("tr")).data();
            tr.addClass('details');
            row.child(detalle_productividad(data)).show();
            $.ajax({
                url: `${PATH_NAME}/detalle_productividad`,
                type: 'POST',
                data: { data },
                success: function (res) {
                    // console.log(res);
                    $(`#tb_detalle_persona${data.id_persona}`).DataTable({
                        "data": res,
                        "dom": 'Bfrtip',
                        "buttons": [
                            'copy', 'excel'
                        ],
                        columns: [
                            {
                                "data": "nombres", render: function (data, type, row) {
                                    return row.nombres + ' ' + row.apellidos;
                                }
                            },
                            { "data": "nombre_maquina" },
                            { "data": "dia" },
                            { "data": "turno_hora" },
                            { "data": "total_dia", render: $.fn.DataTable.render.number(',', '.', 0) },
                        ],
                    });
                }
            });
        }
    });
}

function detalle_productividad(data) {
    var respuesta = /*html*/
        `
    <div class="container-fluid borde_per" style="width: 95%; background: white;">
        <h5 class="sub_titulo_ubicacion text-center">Detalle Productividad</h5><hr>
        <br>
        <div class="table-responsive">
            <table id = "tb_detalle_persona${data.id_persona}" class=" text-center table table-striped table-light table-bordered mb-3 mt-3" cellspacing="0">
                <thead class="text-center">
                    <tr class="table-detalle">
                        <th class="col-4">Empleado</th>
                        <th class="col-3">Maquina</th>
                        <th class="col-1">dia del mes</th>
                        <th class="col-1">horas Turno Laboradas</th>
                        <th class="col-3">total Ml</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <br>
        </div><br>
        </div>   
    </div>
    <br>`;
    return respuesta;
}
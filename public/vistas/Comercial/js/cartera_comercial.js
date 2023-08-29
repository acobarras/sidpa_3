$(document).ready(function () {
    consulta();
});

//--------------------------------------------------Variables -------------------------------------------------------
var detaConsulta = [];
// ----------------------------------------Consultas-----------------------------------------------------
function consulta() {
    $.ajax({
        url: `${PATH_NAME}/comercial/consulta_cartera_vencida`,
        success: function (res) {
            consulta_carteraVencidas(res.vencida);
            consulta_cartera(res.cartera);
        }
    });
}

//------------------------------------------------- tabla vencidas ------------------------------------------
function consulta_carteraVencidas(data) {
    var table = $('#tb_cartera_vencida').DataTable({
        data: data,
        dom: 'Bflrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
            tittleAttr: ' Exportar a exel',
            className: 'btn btn-success',
        }],
        "columns": [
           { "data": "id_cli_prov" },
           { "data": "nit" },
           { "data": "nombre_empresa" },
           { "data": "dias_credito" },
           { "data": "dias_mora" },
           { "data": "cantidad_facturas" },
           { "data": "factura_masantigua" },
           { "data": "total_facturas", render: $.fn.dataTable.render.number('.', ',', 2, '$ ')},
           {
                "render": function (data, type, row) {
                    botones = `
                    <center>
                        <button type='button' title='Consultar facturas' class='btn btn-info btn-circle consultar_facturas'>
                            <span class="fas fa-search"></span>
                        </button>
                    <center>`;
                    return botones;
                }
            }
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            var Total = 0;
            data.forEach(element => {
                Total += parseFloat(element.total_facturas);

            });
            // Update footer
            $(api.column(3).footer()).html($.fn.dataTable.render.number(',', '.', 2, '$ ').display(Total));
        },
    })
    detalle_facturas('#tb_cartera_vencida', table, 'vencida')
}

// -------------------------------------------- facturas por vencer tabla---------------------
function consulta_cartera(data) {
    var table = $('#tb_otra_cartera').DataTable({
        data: data,
        dom: 'Bflrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
            tittleAttr: ' Exportar a exel',
            className: 'btn btn-success',
        }],
        "columns": [
            { "data": "id_cli_prov" },
            { "data": "nit" },
            { "data": "nombre_empresa" },
            { "data": "dias_credito" },
            { "data": "cantidad_facturas" },
            { "data": "factura_masantigua" },
            { "data": "total_facturas", render: $.fn.dataTable.render.number('.', ',', 2, '$ ')},
            {
                "render": function (data, type, row) {
                    botones = `
                     <center>
                         <button type='button' title='Consultar facturas' class='btn btn-info btn-circle consultar_facturas'>
                             <span class="fas fa-search"></span>
                         </button>
                     <center>`;
                    return botones;
                }
            }
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            var Total = 0;
            data.forEach(element => {
                Total += parseFloat(element.total_facturas);

            });
            // Update footer
            $(api.column(3).footer()).html($.fn.dataTable.render.number(',', '.', 2, '$ ').display(Total));
        },
    })
    detalle_facturas('#tb_otra_cartera', table, 'novencida')
}

//-------------------------------------------------------------- detalle facturas -------------------------------------------------------------
function detalle_facturas(tbody, table, tipo) {
    $(tbody).on('click', 'tr button.consultar_facturas', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var id = $.inArray(tr.attr('id'), detaConsulta);
        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();
            detaConsulta.splice(id, 1);
        } else {
            var data = $(tbody).DataTable().row($(this).parents("tr")).data();
            data.tipo = tipo
            tr.addClass('details');
            row.child(tabla_detallesFactura(data)).show();
            var tabla = $(`#tb_factura_cliente${data.id_cli_prov}_${data.tipo}`).DataTable({
                ajax: {
                    "url": `${PATH_NAME}/comercial/consulta_detallada_facturas`,
                    "type": "POST",
                    "data": data,
                },
                dom: 'Bflrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                    tittleAttr: ' Exportar a exel',
                    className: 'btn btn-success',
                }],
                "columns": [
                    { "data": "nit" },
                    { "data": "nombre_empresa" },
                    { "data": "num_factura" },
                    { "data": "total_factura", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
                    { "data": "fecha_factura" },
                    { "data": "fecha_vencimiento" },
                    {
                        "data": "dias_mora",
                        "render": function (data, type, row) {
                            if (row.dias_mora > 0) {
                                return row.dias_mora;
                            } else {
                                return 0;
                            }
                        },
                    }],
                    footerCallback: function (row, data, start, end, display) {
                        var api = this.api();
                        var Total = 0;
                        data.forEach(element => {
                            Total += parseFloat(element.total_factura);
            
                        });
                        // Update footer
                        $(api.column(3).footer()).html($.fn.dataTable.render.number('.', ',', 2, '$ ').display(Total));
                    }
            })
            if (id === -1) {
                detaConsulta.push(tr.attr('id'));
            }
        }
    })
    // // // On each draw, loop over the `detailRows` array and show any child rows
    table.on('draw', function () {
        $.each(detaConsulta, function (i, id) {
            $('#' + id + ' td.details-control').trigger('click');
        });
    });
}
// // ------------------------------------------------- Cargar tabla detalles de facturas -----------------------------------------------------
function tabla_detallesFactura(data) {
    var respuesta = /*html*/
        `<br>
    <div class="container-fluid borde_per" style="width:95%; background: white;">
        <br>
        <h5 class="sub_titulo_ubicacion text-center">Detalle facturas</h5><hr>
        <br>
        <div class="table-responsive">
            <table id ="tb_factura_cliente${data.id_cli_prov}_${data.tipo}" class=" text-center table table-striped table-light table-bordered m-3 p-2" cellspacing="0" style="width:95%">
                <thead class="bg-layout">
                    <tr>
                        <th>Nit</th>
                        <th>Cliente</th>
                        <th>Factura</th>
                        <th>Valor total</th>
                        <th>Fecha de facturación</th>
                        <th>Fecha de vencimiento</th>
                        <th>Días de mora</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr class="table-secondary">
                        <th colspan="3" style="text-align:right">Total Facturas:</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
            <br>
        </div><br>
        </div>   
    </div>
    <br>`;
    return respuesta;
}


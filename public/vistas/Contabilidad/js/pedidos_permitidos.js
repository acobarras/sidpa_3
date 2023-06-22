$(document).ready(function () {
    consulta_pedidos_permitidos();
});

var consulta_pedidos_permitidos = function () {
    var tb_pedidos_permitidos = $('#tb_pedidos_permitidos').DataTable({
        "ajax": `${PATH_NAME}/contabilidad/consulta_pedidos_permitidos`,
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
            { "data": "paso_pedido" },
            {
                "orderable": false,
                "defaultContent": "<center><button type='button' class='btn btn-primary btn-circle  rounded-circle ver_pedidos' title='Ver pedidos'><i class='fas fa-search'></i></button></center>"
            }

        ],
    });
    ver_pedidos_cliente('#tb_pedidos_permitidos tbody', tb_pedidos_permitidos);

}
var detailPed = [];

var tabla_ver_pedidos = function (data) {
    var respu = /*html*/ ` <div class="container-fluid">
    <h3>${data.nombre_empresa}</h3>
    <br><br>
    <table class="table-bordered table table-responsive-lg table-responsive-md" style="background: white"  cellspacing="0" width="100%" id="dt_ver_producto${data.id_cli_prov}">
        <thead style="background:#0d1b50;color:white">
            <tr>
                <th>Id</th>
                <th>Numero Pedido</th>
                <th>Orden de Compra</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <br><br><br>
</div>`;
    return respu;
}

var ver_pedidos_cliente = function (tbody, table) {
    /* ver productos  al hacer click en el boton azul*/
    $(tbody).on('click', 'button.ver_pedidos', function (e) {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var idx = $.inArray(tr.attr('id'), detailPed);
        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();
            // Eliminar de la matriz 'abierta'
            detailPed.splice(idx, 1);
        } else {
            var data = table.row($(this).parents("tr")).data();
            tr.addClass('details');
            row.child(tabla_ver_pedidos(data)).show();
            var pedidos = data.dato_pedidos;

            var tabla_ver_ped = $(`#dt_ver_producto${data.id_cli_prov}`).DataTable({
                "data": pedidos,
                "columns": [
                    { "data": "id_pedido" },
                    { "data": "num_pedido" },
                    { "data": "orden_compra" },
                    { "data": "nombre_estado_pedido" },
                ],
            });
            if (idx === -1) {
                detailPed.push(tr.attr('id'));
            }
        }
    });
}



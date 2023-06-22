$(document).ready(function () {
    intentos_pedidos();
});

var carga_tabla_cliente = function (busqueda) {
    $("#tabla_cliente").css("display", "")
    $("#tabla_asesor").css("display", "none")
    var table = $('#tabla_intento_pedido_cliente').DataTable({
        "ajax": {
            "url": `${PATH_NAME}/Gerencia/carga_tb_intento_ped`,
            "type": "POST",
            "data": { busqueda }
        },
        dom: 'Bflrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
            tittleAttr: ' Exportar a exel',
            className: 'btn btn-success',
        }],
        "columns": [
            { "data": "nombre_empresa" },
            { "data": "cant_intento_p" },
            {
                "orderable": false,
                "defaultContent": "<center><button type='button' class='btn btn-primary btn-circle  rounded-circle ver_detalle_intento_ped' data-val='3' title='Ver detalle'><i class='fas fa-search'></i></button></center>"
            }
        ],
    });
    ver_detalle_intento_ped('#tabla_intento_pedido_cliente', table);
}
var carga_tabla_asesor = function (busqueda) {
    $("#tabla_cliente").css("display", "none")
    $("#tabla_asesor").css("display", "")
    var table1 = $('#tabla_intento_pedido_asesor').DataTable({
        "ajax": {
            "url": `${PATH_NAME}/Gerencia/carga_tb_intento_ped`,
            "type": "POST",
            "data": { busqueda }
        },
        dom: 'Bflrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
            tittleAttr: ' Exportar a exel',
            className: 'btn btn-success',
        }],
        "columns": [
            { "data": "nombre" },
            { "data": "cant_intento_p" },
            {
                "orderable": false,
                "defaultContent": "<center><button type='button' class='btn btn-primary btn-circle  rounded-circle ver_detalle_intento_ped_ase' data-val='4' title='Ver detalle'><i class='fas fa-search'></i></button></center>"
            }
        ],
    });
    ver_detalle_intento_ped_ase('#tabla_intento_pedido_asesor', table1);
}

var intentos_pedidos = function () {
    $("#form_intentos_ped").submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida_form = validar_formulario(form);
        var busqueda = $("#buscar_intentos_ped").val();
        if (valida_form) {
            if (busqueda == 1) {
                carga_tabla_cliente(busqueda);
            } else {
                carga_tabla_asesor(busqueda);
            }
        }
    });
}

var detail_intento_ped = [];

var tabla_ver_detalle_intento_ped = function (data) {
    var respu = /*html*/ ` <div class="container-fluid">
    <br><br>
    <table class="table-bordered table table-responsive-lg table-responsive-md " cellspacing="0" width="100%" id="dt_detalle_intento_ped${data.id_cli_prov}">
        <thead class="thead-dark">
            <tr>
                <th>Fecha Intento</th>
                <th>Asesor</th>
                <th>Observación</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <br><br><br>
</div>`;
    return respu;
}

var ver_detalle_intento_ped = function (tbody, table) {
    /* ver productos  al hacer click en el boton azul*/
    $(tbody).on('click', 'button.ver_detalle_intento_ped', function (e) {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var idx = $.inArray(tr.attr('id'), detail_intento_ped);
        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();

            // Eliminar de la matriz 'abierta'
            detail_intento_ped.splice(idx, 1);
        } else {
            var data = $('#tabla_intento_pedido_cliente').DataTable().row($(this).parents("tr")).data();
            tr.addClass('details');
            row.child(tabla_ver_detalle_intento_ped(data)).show();
            var id = data.id_cli_prov;
            var busqueda = $(this).attr('data-val');
            $(`#dt_detalle_intento_ped${data.id_cli_prov}`).DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/Gerencia/carga_tb_intento_ped`,
                    "type": "POST",
                    "data": { busqueda, id }
                },
                "columns": [
                    { "data": "fecha_crea" },
                    { "data": "nombre" },
                    { "data": "observacion" },
                ],
            });
            if (idx === -1) {
                detail_intento_ped.push(tr.attr('id'));
            }
        }
    });
}

var detail_intento_ped_ase = [];

var tabla_ver_detalle_intento_ped_ase = function (data) {
    var respu = /*html*/ ` <div class="container-fluid">
    <br><br>
    <table class="table-bordered table table-responsive-lg table-responsive-md " cellspacing="0" width="100%" id="dt_detalle_intento_ped_ase${data.asesor}">
        <thead class="thead-dark">
            <tr>
                <th>Fecha Intento</th>
                <th>Cliente</th>
                <th>Observación</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <br><br><br>
</div>`;
    return respu;
}

var ver_detalle_intento_ped_ase = function (tbody, table, busqueda) {
    /* ver productos  al hacer click en el boton azul*/
    $(tbody).on('click', 'button.ver_detalle_intento_ped_ase', function (e) {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var idx = $.inArray(tr.attr('id'), detail_intento_ped_ase);
        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();

            // Eliminar de la matriz 'abierta'
            detail_intento_ped_ase.splice(idx, 1);
        } else {
            var data = $('#tabla_intento_pedido_asesor').DataTable().row($(this).parents("tr")).data();
            tr.addClass('details');
            row.child(tabla_ver_detalle_intento_ped_ase(data)).show();
            var id = data.asesor;
            var busqueda = $(this).attr('data-val');
            $(`#dt_detalle_intento_ped_ase${data.asesor}`).DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/Gerencia/carga_tb_intento_ped`,
                    "type": "POST",
                    "data": { busqueda, id }
                },
                "columns": [
                    { "data": "fecha_crea" },
                    { "data": "nombre_empresa" },
                    { "data": "observacion" },

                ],
            });
            if (idx === -1) {
                detail_intento_ped_ase.push(tr.attr('id'));
            }
        }
    });
}
$(document).ready(function () {
    select_2();
    alertify.set('notifier', 'position', 'bottom-left');
    pendiente_radicar();
    cambio_remision();
    documentos_cambio();
    cambio_documento_factura();
});

var elegidos = [];

var pendiente_radicar = function () {
    var table = $('#table_pendientes_radicar').DataTable({
        ajax: `${PATH_NAME}/facturacion/pendiente_radicar`,
        columns: [
            { "data": "nombre_empresa" },
            {
                "data": "documento", render: function (data, type, row) {
                    return `${row.tipo_documento} ${row.num_remision}`;
                }
            },
            { "data": "num_pedido" },
            {
                "data": "asesor", render: function (data, type, row) {
                    return `${row.nombres} ${row.apellidos}`;
                }
            },
            {
                "data": "elegido", render: function (data, type, row) {
                    var elegido = `<div class="select_acob text-center">
                        <input type="checkbox" class="agrupar_items" value="${row.id_control_factura}">
                    </div>`;
                    return elegido;
                }, "className": "text-center"
            },
        ],
        "deferRender": true,
        "stateSave": true,
    });

}

var documentos_cambio = function () {
    $("#table_pendientes_radicar tbody").on("click", "input.agrupar_items", function () {
        var data = $('#table_pendientes_radicar').DataTable().row($(this).parents("tr")).data();
        if ($(this).prop('checked') == true) {
            if (elegidos.length == 0) {
                //agregar el item marcado a la lista
                elegidos.push({
                    'id_control_factura': data.id_control_factura,
                    'id_cli_prov': data.id_cli_prov,
                    'id_persona': data.id_persona,
                });
            } else {
                var mensaje = '';
                elegidos.forEach(element => {
                    if (data.id_cli_prov === element.id_cli_prov && data.id_persona === element.id_persona) {
                        mensaje = '';
                    } else {
                        mensaje = 'solo se puede agrupar el mismo cliente con el mismo asesor.'
                        $(this).prop('checked', false);
                    }
                });
                if (mensaje != '') {
                    alertify.error(mensaje);
                } else {
                    elegidos.push({
                        'id_control_factura': data.id_control_factura,
                        'id_cli_prov': data.id_cli_prov,
                        'id_persona': data.id_persona,
                    });
                }
            }
        } else {
            //recorrer la lista de items y eliminar el item desmarcado
            for (var i = 0; i < elegidos.length; i++) {
                if (data.id_control_factura === elegidos[i].id_control_factura) {
                    elegidos.splice(i, 1);
                }
            }
        }
    });
}

var cambio_documento_factura = function () {
    $('#remision_factura').on('change', function (e) {
        e.preventDefault();
        var dato_consulta = $(this).val();
        if (dato_consulta == 0 || dato_consulta == 99) {
            $("#numero_factura").empty().html(`Sin Asignar`);
            $("#numero_factura_consulta").val('');
        } else {
            $.ajax({
                url: `${PATH_NAME}/facturacion/consecutivo_documento`,
                type: "POST",
                data: { dato_consulta },
                success: function (res) {
                    var prefijo = res[0].prefijo;
                    $("#numero_factura").empty().html(`${prefijo} ${res[0].numero_guardado}`);
                    $("#numero_factura_consulta").val(res[0].numero_guardado);
                }
            });
        }
    });
}

var cambio_remision = function () {
    $('#cambio_remision').on('click', function () {
        var tipo_documento = $('#remision_factura').val();
        var num_documento = $("#numero_factura_consulta").val();
        if (elegidos == '') {
            alertify.error('Se requiere al menos un documento para continuar.');
            return;
        }
        if (tipo_documento == 0) {
            alertify.error('Se requiere el tipo de documento para continuar.');
            return;
        }
        alertify.confirm('Cambio Documento', 'Desea continuar con el cambio del documento para los documentos relacionados.',
            function () {
                $.ajax({
                    url: `${PATH_NAME}/facturacion/cambio_remision_factura`,
                    type: 'POST',
                    data: { elegidos, tipo_documento, num_documento },
                    success: function (res) {
                        if (res.status == 1) {
                            alertify.success(res.msg);
                        } else {
                            alertify.error(res.msg);
                        }
                        $("#remision_factura").val(0).trigger('change');
                        elegidos = [];
                        $('#table_pendientes_radicar').DataTable().ajax.reload();
                    }
                });
            },
            function () {
                $("#remision_factura").val(0).trigger('change');
                elegidos = [];
                $('#table_pendientes_radicar').DataTable().ajax.reload();
                alertify.error('Operación cancelada');
            }
        );
    });
}
$(document).ready(function () {
    $('.select_2').select2();
    consulta_visita();
    consulta_autorizaciones();
    consulta_pendientes();
    consulta_comisiones();
    remision();
    etiqueta_ingreso();
    consulta_cliente();
});

var tabla = '';
var iniciada = false;

var consulta_visita = function () {
    $('#form_visitas').submit(function (e) {
        e.preventDefault();
        var formulario = $('#form_visitas').serializeArray();
        var valida = validar_formulario(formulario);
        formulario = formulario.reduce(function (a, z) { a[z.name] = z.value; return a; }, {});
        if (valida) {
            var table = $('#tb_indicador_visitas').DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/soporte_tecnico/consultas_reporte`,
                    "type": "POST",
                    "data": { formulario },
                },
                dom: 'Bflrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                    tittleAttr: ' Exportar a exel',
                    className: 'btn btn-success',
                }],
                "columns": [
                    { "data": "num_consecutivo" },
                    { "data": "observacion" },
                    { "data": "fecha_crea" },
                    { "data": "nombre_empresa" },
                    { "data": "equipo" },
                    { "data": "serial_equipo" },
                    {
                        "data": "estado", render: function (data, type, row) {
                            switch (row.id_actividad_area) {
                                case 87:
                                    return '<p class="text-primary">Agendado</p>'
                                case 88:
                                    return '<p class="text-success">Realizado</p>'
                                case 99:
                                    return '<p class="text-success">Realizado</p>'
                                case 92:
                                    return '<p class="text-danger">Reagendado</p>'
                                default:
                                    return '<p></p>'
                            }
                        }, "className": "text-center"
                    },
                ]
            })
        }
    })
}

var consulta_autorizaciones = function () {
    $('#form_autorizaciones').submit(function (e) {
        e.preventDefault();
        var formulario = $('#form_autorizaciones').serializeArray();
        var valida = validar_formulario(formulario);
        formulario = formulario.reduce(function (a, z) { a[z.name] = z.value; return a; }, {});
        if (valida) {
            var table = $('#tb_indicador_autorizacion').DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/soporte_tecnico/consultas_reporte`,
                    "type": "POST",
                    "data": { formulario },
                },
                dom: 'Bflrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                    tittleAttr: ' Exportar a exel',
                    className: 'btn btn-success',
                }],
                "columns": [
                    { "data": "num_consecutivo" },
                    { "data": "observacion" },
                    { "data": "fecha_crea" },
                    { "data": "nombre_empresa" },
                    { "data": "equipo" },
                    { "data": "serial_equipo" },
                    {
                        "data": "estado", render: function (data, type, row) {
                            switch (row.id_actividad_area) {
                                case 79:
                                    return '<p class="text-primary">Aceptado</p>'
                                case 82:
                                    return '<p class="text-success">Realizado</p>'
                                default:
                                    return '<p></p>'
                            }
                        }, "className": "text-center"
                    },
                ]
            })
        }
    })
}

var consulta_pendientes = function () {
    var formulario = { consulta: 3 }
    var table = $('#tb_consolidado').DataTable({
        "ajax": {
            "url": `${PATH_NAME}/soporte_tecnico/consultas_reporte`,
            "type": "POST",
            "data": { formulario },
        },
        dom: 'Bflrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
            tittleAttr: ' Exportar a exel',
            className: 'btn btn-success',
        }],
        "columns": [
            { "data": "num_consecutivo" },
            {
                "data": "fecha_crea",
                render: function (data, type, row) {
                    return '<b>Fecha cracción: </b>' + row.fecha_crea + '<br>' +
                        '<b>Fecha agendamiento: </b>' + row.fecha_agendamiento + '<br>'
                }
            },
            { "data": "nombre_estado_soporte" },
            {
                "data": "nombre_empresa",
                render: function (data, type, row) {
                    return '<b>' + row.nombre_empresa + ' </b><br>' +
                    '<b>Dirección: </b>' + row.direccion
                }
            },

        ]
    })
}

var consulta_comisiones = function () {
    $('#form_comisiones').submit(function (e) {
        e.preventDefault();
        var formulario = $('#form_comisiones').serializeArray();
        var valida = validar_formulario(formulario);
        formulario = formulario.reduce(function (a, z) { a[z.name] = z.value; return a; }, {});
        if (valida) {
            var table = $('#tb_comisiones').DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/soporte_tecnico/consultas_reporte`,
                    "type": "POST",
                    "data": { formulario },
                },
                dom: 'Bflrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                    tittleAttr: ' Exportar a exel',
                    className: 'btn btn-success',
                }],
                "columns": [
                    {
                        "data": "fecha_crea",
                        render: function (data, type, row) {
                            return '<b>Fecha de creación: </b>' + row.fecha_crea + '<br>' +
                                '<b>Fecha de ingreso: </b>' + row.fecha_ingreso + '<br>' +
                                '<b>Fecha de agendamiento: </b>' + row.fecha_agendamiento + '<br>'+
                                '<b>Fecha de ejecución: </b>' + row.fecha_ejecucion + '<br>'
                        }
                    },
                    {
                        "data": "nombre_empresa",
                        render: function (data, type, row) {
                            return '<b>' + row.nombre_empresa + ' </b><br>' +
                            '<b>Dirección: </b>' + row.direccion
                        }
                    },
                    { "data": "num_consecutivo" },
                    { "data": "equipo" },
                    { "data": "serial_equipo" },
                    {
                        "data": "procedimiento", render: function (data, type, row) {
                            if (row.id_actividad_area == 84) {
                                return row.cierre_diag_remoto;
                            } else {
                                return row.procedimiento;
                            }
                        }
                    },
                    { "data": "accesorios" },
                    {
                        "data": "tipo_impacto", render: function (data, type, row) {
                            switch (row.id_actividad_area) {
                                case 78:
                                    return '<p>Diagnostico</p>'
                                case 82:
                                    return '<p>Mantenimiento</p>'
                                case 84:
                                    return '<p>Remoto</p>'
                                case 89:
                                    return '<p>Diagnostico</p>'
                                case 98:
                                    return '<p>Instalación</p>'
                                default:
                                    return '<p></p>'
                            }
                        }, "className": "text-center"
                    },
                ]
            })
        }
    })
}

var consulta_cliente = function () {
    $('#form_cliente').submit(function (e) {
        e.preventDefault();
        var formulario = $('#form_cliente').serializeArray();
        var valida = validar_formulario(formulario);
        formulario = formulario.reduce(function (a, z) { a[z.name] = z.value; return a; }, {});
        if (valida) {
            if (table) {
                tabla.distroy()
                table.distroy()
            }
            var table = $('#tb_clientes').DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/soporte_tecnico/consultas_reporte`,
                    "type": "POST",
                    "data": { formulario },
                },
                dom: 'Bflrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                    tittleAttr: ' Exportar a exel',
                    className: 'btn btn-success',
                }],
                "columns": [
                    {
                        "data": "fecha_crea",
                        render: function (data, type, row) {
                            return '<b>Fecha de creación: </b>' + row.fecha_crea + '<br>' +
                                '<b>Fecha de ingreso: </b>' + row.fecha_ingreso + '<br>' +
                                '<b>Fecha de agendamiento: </b>' + row.fecha_agendamiento + '<br>'
                        }
                    },
                    { "data": "num_consecutivo" },
                    { "data": "item" },
                    {
                        "data": "nombre_empresa",
                        render: function (data, type, row) {
                            return '<b>' + row.nombre_empresa + '</b><br>' +
                                '<b>Dirección: </b>' + row.direccion + '<br>' +
                                '<b>Nit: </b>' + row.nit + '-' + row.dig_verificacion + '<br>'
                        }
                    },
                    { "data": "equipo" },
                    { "data": "serial_equipo" },
                    { "data": "procedimiento" },
                    {
                        "data": "detalles",
                        render: function (data, type, row) {
                            return '<center><button type="button" title="Seguimiento" class="btn btn-success btn-circle detalles" data-diag="' + row.id_diagnostico + '" data-item="' + row.item + '"><span class="fas fa-search"></span> </button></center>'
                        }

                    },
                ]
            });
            tabla = table;
            if (!iniciada) {
                seguiminto('#tb_clientes tbody');
            }
        }
    })

}

function tabla_detalle_consul_diagnostico(data) {
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
                        <th>Fecha</th>
                        <th>Diagnostico</th>
                        <th>Item</th>
                        <th>Observación</th>
                        <th>Nombre usuario</th>
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
var detailConsul = [];

var seguiminto = function (tbody) {
    iniciada = true;
    $(tbody).on('click', 'tr button.detalles', function () {
        var tr = $(this).closest('tr');
        var row = tabla.row(tr);
        var idx = $.inArray(tr.attr('id'), detailConsul);
        if (row.child.isShown()) {
            tr.removeClass('details');
            row.child.hide();
            detailConsul.splice(idx, 1);
        } else {
            var data = tabla.row($(this).parents("tr")).data();
            tr.addClass('details');
            row.child(tabla_detalle_consul_diagnostico(data.id_diagnostico + '-' + data.item)).show();
            var formulario = { consulta: 6, diagnostico: data.id_diagnostico, item: data.item }
            $(`#dt_ver_seguimiento${data.id_diagnostico + '-' + data.item}`).DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/soporte_tecnico/consultas_reporte`,
                    "type": "POST",
                    "data": { formulario },
                },
                "columns": [
                    { data: "fecha_crea" },
                    {
                        data: "id_diagnostico",
                        render: function (data, type, row) {
                            return 'DS-' + row.id_diagnostico
                        }
                    },
                    { data: "item" },
                    { data: "observacion" },
                    { data: "nombre_usuario" },
                ],
            });
            if (idx === -1) {
                detailConsul.push(tr.attr('id'));
            }

        }
    });
    // On each draw, loop over the `detailRows` array and show any child rows
    tabla.on('draw', function () {
        $.each(detailConsul, function (i, id) {
            $('#' + id + ' td.details-control').trigger('click');
        });
    });
}

var remision = function () {
    $('#genera_remision').on('click', function () {
        var num_remision = $('#num_diag_remision').val();
        if (num_remision == '') {
            alertify.warning('ingrese un valor');
            $('#num_diag_remision').focus();
            return;
        }
        $.ajax({
            url: `${PATH_NAME}/soporte_tecnico/reimpresion_remision`,
            type: 'POST',
            data: { num_remision },
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: function (res) {
                $('.boton_remision').removeClass('fa fa-download');
                $('.boton_remision').addClass('fas fa-spinner fa-spin');
                $('#num_diag_remision').val('');
            },
            success: function (regreso) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(regreso);
                a.href = url;
                a.download = 'Reporte_DS-' + num_remision + '.pdf';
                a.click();
                window.URL.revokeObjectURL(url);
                $('.boton_remision').addClass('fa fa-download');
                $('.boton_remision').removeClass('fas fa-spinner fa-spin');
                $('#num_diag_remision').val('');
            },
            error: function (err) {
                alertify.error("No existe un PDF con este numero de acta");
                $('#num_diag_remision').val('');
                $('.boton_remision').addClass('fa fa-download');
                $('.boton_remision').removeClass('fas fa-spinner fa-spin');
            }
        });
    });
}

var etiqueta_ingreso = function () {
    $('#genera_zpl').on('click', function () {
        var consecutivo = $('#num_diag_ingreso').val();
        var datos = 'consultar'
        if (consecutivo == '') {
            alertify.warning('ingrese un valor');
            $('#num_diag_ingreso').focus();
            return;
        }
        $.ajax({
            "url": `${PATH_NAME}/soporte_tecnico/impresion_etiqueta_equipo`,
            "type": 'POST',
            "data": { consecutivo, datos },
            beforeSend: function (res) {
                $('.boton_zpl').removeClass('fa fa-download');
                $('.boton_zpl').addClass('fas fa-spinner fa-spin');
                $('#num_diag_ingreso').val('');
                $("div.div_impresion").empty().html();
                $("div.div_impresion").removeClass('d-none');
            },
            success: function (res) {
                if (res.length === 0) {
                    alertify.error('¡Este registro no existe o corresponde a una visita!');
                } else {
                    res = res.toString();
                    $("div.div_impresion").empty().html(res);
                    $("div.div_impresion").printArea();
                    $("div.div_impresion").addClass('d-none');
                }
                $('.boton_zpl').addClass('fa fa-download');
                $('.boton_zpl').removeClass('fas fa-spinner fa-spin');
            }
        });
    });
}
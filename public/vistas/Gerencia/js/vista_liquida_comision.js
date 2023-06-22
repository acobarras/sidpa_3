$(document).ready(function () {
    select_2();
    ver_comision();
    cambio_consulta();
});

var cambio_consulta = function () {
    $('#asesor').on('change', function () {
        var cambio = $(this).val();
        if (cambio == 'cambio') {
            $('#periodo_liquida').css('display', 'none');
            $('#rango_consulta').css('display', '');
            $('#fecha_inicial').val('');
            $('#fecha_fin').val('');
        } else {
            $('#periodo_liquida').css('display', '');
            $('#rango_consulta').css('display', 'none');
            $('#periodo').val('0').trigger('change');
        }
    });
}

var ver_comision = function () {
    $('#form_comision').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida_form;
        if ($('#asesor').val() == '' || $('#asesor').val() == 0) {
            alertify.confirm('SIDPA INFORMA', 'Desea continuar con esta consulta ya que es una consulta muy grande y puede tardar.',
                function () {
                    ejecuta_comision(form);
                },
                function () {
                    alertify.error('Operacion Cancelada');
                }
            );
        } else {
            var exepcion = ['fecha_inicial','fecha_fin'];
            if (form[0].value == 'cambio') {
                exepcion = ['periodo'];
            }
            var valida_form = validar_formulario(form,exepcion);
            if (valida_form) {
                ejecuta_comision(form);
            }
        }
    });
}

var ejecuta_comision = function (form) {
    $.ajax({
        url: `${PATH_NAME}/gerencia/liquida_comision`,
        type: 'POST',
        data: form,
        success: function (res) {
            var table1 = $('#tabla_comision').DataTable({
                data: res,
                dom: 'Bflrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                    tittleAttr: ' Exportar a exel',
                    className: 'btn btn-success',
                }],
                "columns": [
                    { "data": "num_factura" },
                    { "data": "nit" },
                    { "data": "nombre_empresa" },
                    { "data": "fecha_factura" },
                    {
                        "data": "empresa",
                        render: function (data, type, row) {
                            var nombre_empresa = '';
                            if (row.empresa == 1) {
                                nombre_empresa = 'Acobarras SAS';
                            } else {
                                nombre_empresa = 'Acobarras Colombia';
                            }
                            return nombre_empresa;
                        }
                    },
                    { "data": "fecha_pago" },
                    {
                        "data": "asesor", render: function (data, type, row) {
                            return `${row.nombres} ${row.apellidos}`;
                        }
                    },
                    { "data": "total_etiquetas", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
                    { "data": "total_cintas", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
                    { "data": "total_alquiler", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
                    { "data": "total_tecnologia", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
                    { "data": "total_soporte", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
                    { "data": "total_fletes", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
                    { "data": "total_m_prima", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
                    {
                        "data": "iva", render: function (data, type, row) {
                            var iva = 0;
                            if (row.iva == 1) {
                                iva = (row.total_factura / 119) * 19;
                            }
                            return $.fn.dataTable.render.number('.', ',', 2, '$ ').display(iva);
                        }
                    },
                    { "data": "total_factura", render: $.fn.dataTable.render.number(',', '.', 2, '$ ') },
                    { "data": "nombre_estado" },
                    { "data": "dias_dados" },
                ],
            });
        }
    });
}
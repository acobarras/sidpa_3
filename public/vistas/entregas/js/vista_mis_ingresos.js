$(document).ready(function () {
    select_2();
    $(".datepicker").datepicker({ minDate: new Date('2020/01/01') });
    alertify.set('notifier', 'position', 'bottom-left');
    consulta_ingresos();
});

const ROLL = $('#roll').val();
const ID_PERSONA = $('#id_persona').val();

var consulta_ingresos = function () {
    $('#form_consulta_ingresos').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        if (ROLL != 1) {
            var falta = { name: 'transportador', value: ID_PERSONA };
            form.push(falta);
        }
        console.log(form);
        valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                "url": `${PATH_NAME}/entregas/consulta_mis_ingresos`,
                "type": 'POST',
                "data": form,
                "success": function (respu) {
                    $("#table_mis_ingresos").DataTable({
                        "dom": "Bfrtip",
                        "buttons": [
                            "copy", "excel", "pdf"
                        ],
                        "data": respu,
                        "columns": [

                            { "data": "fecha_cargue" },
                            {
                                "data": "transportador", render: function (data, type, row) {
                                    return `${row.nombres} ${row.apellidos}`;
                                }
                            },
                            { "data": "documento" },
                            { "data": "valor_documento", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
                            { "data": "valor_flete", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
                            { "data": "observacion" },
                        ],
                        "footerCallback": function (row, data, start, end, display) {
                            total_documeto = this.api()
                                .column(3)//numero de columna a sumar
                                .data()
                                .reduce(function (a, b) {
                                    return parseInt(a) + parseInt(b);
                                }, 0);
                            total_flete = this.api()
                                .column(4)//numero de columna a sumar
                                .data()
                                .reduce(function (a, b) {
                                    return parseInt(a) + parseInt(b);
                                }, 0);
                            var numFormat = $.fn.dataTable.render.number('.', ',', 0, '$ ').display;
                            $(this.api().column(3).footer()).html(numFormat(total_documeto));
                            $(this.api().column(4).footer()).html(numFormat(total_flete));
                        }
                    });
                    console.log(respu);
                }
            });
        }
    });
}
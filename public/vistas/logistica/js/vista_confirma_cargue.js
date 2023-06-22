$(document).ready(function () {
    select_2();
    alertify.set('notifier', 'position', 'bottom-left');
    carga_tabla_confirma_flete();
    agrupar_items();
    acepta_flete();
    cambio_valor_flete();
});
var numFormat = $.fn.dataTable.render.number('.', ',', 0, '$ ').display;

var carga_tabla_confirma_flete = function () {
    var table = $('#tabla_confirma_flete').DataTable({
        "ajax": `${PATH_NAME}/logistica/tabla_confirma_flete`,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        "scrollX": true,
        columnDefs: [
            { "width": "10%", "targets": 4 },
        ],
        columns: [
            { "data": "fecha_cargue" },
            {
                "data": "transportador", render: function (data, type, row) {
                    return `${row.nombres} ${row.apellidos}`;
                }
            },
            { "data": "documento" },
            { "data": "valor_documento", render: $.fn.dataTable.render.number('.', ',', 0, '$ ') },
            {
                "data": "valor_flete", render: function (data, type, row) {
                    var valor = row.valor_flete;
                    var input = `<input type="text" id="flete${row.id_pago_flete}" class="form-control cambio_valor_flete" style='border:none;'  value="${numFormat(row.valor_flete)}">`;
                    return input;
                }
            },
            { "data": "observacion" },
            {
                "orderable": false,
                "defaultContent": `<div class="select_acob text-center">
                                     <input type="checkbox" class="agrupar_items">
                                  </div>`
            }
        ]
    });
}

var arrayitems = [];
var agrupar_items = function () {
    $('#tabla_confirma_flete tbody').on("click", "tr input.agrupar_items", function () {
        var data = $('#tabla_confirma_flete').DataTable().row($(this).parents("tr")).data();
        if ($(this).prop('checked') == true) {
            //agregar el item marcado a la lista
            arrayitems.push(data);
        } else {
            //recorrer la lista de items y eliminar el item desmarcado
            for (var i = 0; i < arrayitems.length; i++) {
                console.log(i);
                if (data.id_pago_flete === arrayitems[i].id_pago_flete) {
                    arrayitems.splice(i, 1);
                }
            }
        }
    });
}

var acepta_flete = function () {
    $('#acepta_flete').on('click', function () {
        if (arrayitems == '') {
            alertify.error('Eliga almenos un item para continuar.');
            return;
        }
        var obj_inicial = $('#acepta_flete').html();
        btn_procesando('acepta_flete');
        $.ajax({
            url: `${PATH_NAME}/logistica/aceptar_fletes`,
            type: 'POST',
            data: { 'items': arrayitems },
            success: function (res) {
                arrayitems = [];
                if (res) {
                    alertify.success('Datos ingresdados correctamente.');
                } else {
                    alertify.error('Lo sentimos algo sucedio solicite soporte con el area');
                }
                $('#tabla_confirma_flete').DataTable().ajax.reload();
                btn_procesando('acepta_flete', obj_inicial, 1);
            }
        });
    });
}

var cambio_valor_flete = function () {
    $('#tabla_confirma_flete tbody').on("blur", "tr input.cambio_valor_flete", function () {
        var data = $('#tabla_confirma_flete').DataTable().row($(this).parents("tr")).data();
        var numero = $(this).val();
        if (numero != 0) {
            numero = numero.replace(/\./g, '');
            numero = numero.replace(/\$/g, '');
        }
        numero = parseInt(numero);
        alertify.confirm('Editar Valor Flete', 'Desea continuar con la edición.',
            function () {
                $.ajax({
                    url: `${PATH_NAME}/logistica/editar_valor_flete`,
                    type: 'POST',
                    data: { data, numero },
                    success: function (res) {
                        arrayitems = [];
                        if (res) {
                            alertify.success('Datos ingresdados correctamente.');
                        } else {
                            alertify.error('Lo sentimos algo sucedio solicite soporte con el area');
                        }
                        $('#tabla_confirma_flete').DataTable().ajax.reload();
                    }
                });
            },
            function () {
                $(`#flete${data.id_pago_flete}`).val(numFormat(data.valor_flete));
            });
    });
}
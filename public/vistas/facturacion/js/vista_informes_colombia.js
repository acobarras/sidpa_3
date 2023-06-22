$(document).ready(function () {
    // select_2();
    crea_informe_colombia();
    genera_informe();
});
var crea_informe_colombia = function () {
    $("#crea_informe_colombia").on('submit', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                url: `${PATH_NAME}/facturacion/informe_colombia`,
                type: "POST",
                data: form,
                success: function (res) {
                    if (res.length === 0) {
                        alertify.alert('No hay Informe', 'La fecha digitada no posee ninguna factura de colombia. Por este motivo no se puede generar el reporte.',
                            function () {
                                $("#fecha_informe").val('');
                            }).set({
                                'label': 'Aceptar',
                                'transitionOff': true
                            });
                    } else {
                        $('#boton_informe').css('display', 'block');

                        var table = $("#tab_informe").DataTable({
                            "data": res,
                            "columns": [
                                { "data": "fecha_factura" },
                                { "data": "codigo_producto" },
                                { "data": "descripcion_productos" },
                                { "data": "total_factura", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                                { "data": "precio_venta", render: $.fn.dataTable.render.number('.', ',', 2, '$ ') },
                                {
                                    "data": "total", render: function (data, type, row) {
                                        var total = row.total_factura * row.precio_venta;
                                        return $.fn.dataTable.render.number('.', ',', 2, '$ ').display(total);
                                    }
                                },
                                {
                                    "data": "valor_unitario", render: function (data, type, row) {
                                        var valor_unitario = row.precio_venta * 0.92;
                                        return $.fn.dataTable.render.number('.', ',', 2, '$ ').display(valor_unitario);
                                    }
                                },

                                {
                                    "data": 'boton', render: function (data, type, row) {
                                        var res = `<div class="select_acob text-center">
                                        <input class="agrupar_check" type='checkbox' checked name='id_pedido_item${row.id_pedido_item}' value="${row.id_pedido_item}">
                                    </div>`;
                                        return res;
                                    }
                                }
                            ],
                        });
                    }
                }
            });
        }
    });
}

var genera_informe = function () {
    $('#genera_informe').on('click', function () {
        // var obj_inicial = $('#genera_informe').html();
        var data = $("#tab_informe").DataTable().rows().data();
        var dato_tabla = $("#tab_informe").DataTable().rows().nodes();
        var mensaje = '';
        var data_envio = [];
        $.each(dato_tabla, function (index, value) {
            var p = $(this).find('input:checkbox').val();
            var estado_radio = RadioElegido(`id_pedido_item${p}`);
            if (estado_radio == 'ninguno') {
            } else {
                data_envio.push(data[index]);
            }
        });
        if (mensaje == '' && data_envio == '') {
            mensaje = 'Se debe elegir un item para poder continuar.';
            alertify.error(mensaje);
            return;
        } else {
            // btn_procesando('genera_lista_de_empaque');
            envio_datos(data_envio);
        }
    });
}
var envio_datos = function (data_envio) {
    $.ajax({
        url: `${PATH_NAME}/facturacion/genera_informe`,
        type: 'POST',
        data: { data_envio },
        xhrFields: {
            responseType: 'blob'
        },
        success: function (res) {
            if (res.size < 20) {
                alertify.error("Lo sentimos el numero del documento ya fue utilizado.");
            } else {
                // Descarga el pdf
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(res);
                a.href = url;
                // Linea para abrir el documento 
                window.open(url, '_blank');
                // location.reload();
            }
        }
    });
}

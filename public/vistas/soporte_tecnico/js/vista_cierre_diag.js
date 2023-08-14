$(document).ready(function () {
    consultar_datos_item();
});
var consultar_datos_item = function () {
    $.ajax({
        url: `${PATH_NAME}/soporte_tecnico/consultar_datos_cierre`,
        type: "GET",
        success: function (res) {
            var table = $("#tabla_cierre_diag").DataTable({
                "data": res['data'],
                "columns": [

                    {
                        "render": function (data, type, row) {
                            return `${row.id_diagnostico}-${row.item}`
                        },

                    },
                    { "data": "num_consecutivo" },
                    { "data": "nombre_empresa" },
                    { "data": "item" },
                    { "data": "equipo" },
                    { "data": "serial_equipo" },
                    { "data": "nombre_estado_soporte" },
                    {
                        "render": function (data, type, row) {
                            if (row.estado_item == 11 || row.estado_item == 15 || row.estado_item == 16) {
                                return `<center>
                            <div class="select_acob text-center">
                            <input type="checkbox" class="items_selec" id="diagnostico${row.id_diagnostico}" name='diagnostico${row.id_diagnostico}' value="${row.item}">
                            </div>
                            <center>`;
                            }
                        }
                    }
                ]
            });
            validar_check();
            generar_acta();
        }
    });
}

var array_item = [];
var validar_check = function () {
    $('#tabla_cierre_diag tbody').on("click", "tr input.items_selec", function () {
        var data = $('#tabla_cierre_diag').DataTable().row($(this).parents("tr")).data();
        var id_diagnostico = data['id_diagnostico'];
        var estado_item = data['estado_item'];
        if ($(this).prop('checked') == true) {
            if (array_item.length === 0) {
                array_item.push(data);
            } else {
                array_item.forEach(element => {
                    if (element.id_diagnostico == id_diagnostico) {
                        if (element.estado_item == estado_item) {
                            array_item.push(data);
                        } else {
                            $(this).prop('checked', false);
                            alertify.error("Los items seleccionados no pertenecen al mismo estado");
                            return;
                        }
                    } else {
                        $(this).prop('checked', false);
                        alertify.error("Los items seleccionados no pertenecen al mismo diagnostico");
                        return;
                    }
                })
            }
        } else {
            for (var i = 0; i < array_item.length; i++) {
                if (array_item[i].item === data.item) {
                    array_item.splice(i, 1);
                }
            }
        }
    });
}

var generar_acta = function () {
    $('#generar_acta').on("click", function () {
        var cant_item_array = array_item.length;
        if (cant_item_array === 0) {
            alertify.error('Debe seleccionar un item para realizar el acta');
            return;
        } else {
            if (array_item[0].estado_item == 15) {
                alertify.confirm(`ALERTA SIDPA`, `¿Requiere Factura?`,
                    function () {
                        alertify.alert('ALERTA SIDPA', '¿Factura con IVA?"<a href="javascript:showConfirm(1);" class="btn btn-success">Si</a>  <a href="javascript:showConfirm(2);" class="btn btn-danger">No</a></div>',
                            function () {
                            }).set({
                                'label': 'Cancelar',
                                'transitionOff': true
                            });
                        window.showConfirm = function (vista) {
                            if (vista == 1) {
                                var iva = 1;
                                enviar_datos_acta(iva, 1);
                            } else {
                                var iva = 2;
                                enviar_datos_acta(iva, 2);
                            }
                        }
                    }, function () {
                        enviar_datos_acta(2, 2);
                    })
                    .set('labels', { ok: 'Si', cancel: 'No' });
            } else {
                enviar_datos_acta(2, 2);
            }
        }
    })
}
// La variable estado es para saber si toca crear un pedido o no 
var enviar_datos_acta = function (iva, estado) {
    var data = array_item;
    $.ajax({
        "url": `${PATH_NAME}/soporte_tecnico/generar_acta`,
        "type": 'POST',
        "data": { data, iva, estado },
        success: function (res) {
            console.log(res)
            var num_acta = res.num_acta;
            var num_pedido = res.num_pedido;
            if (estado == 1) {
                generar_pdf_acta(num_pedido, num_acta, 1);
            } else {
                generar_pdf_acta(num_pedido, num_acta, 2)
            }
        }
    });
}

var generar_pdf_acta = function (num_pedido, num_acta, estado_pdf) {
    $.ajax({
        "url": `${PATH_NAME}/soporte_tecnico/generar_pdf_acta`,
        "type": 'POST',
        "data": { num_acta, estado_pdf },
        xhrFields: {
            responseType: 'blob'
        },
        success: function (respuesta) {

            var a = document.createElement('a');
            var url = window.URL.createObjectURL(respuesta);
            a.href = url;
            a.download = 'ActaEntrega-' + num_acta + '.pdf';
            a.click();
            window.URL.revokeObjectURL(url);

            alertify.success('Cierre exitoso');
            if (num_pedido != '' && estado_pdf != 2) {
                alertify.alert()
                    .setting({
                        'label': 'Cerrar',
                        'message': `Su numero de pedido es ${num_pedido}`,
                        'onok': function () {
                            location.reload();
                        }
                    }).show();
            } else {
                location.reload();
            }
        }
    })
}
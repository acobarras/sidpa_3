$(document).ready(function () {
    consultar_datos_item();
});
var consultar_datos_item = function () { // esto es mejor cambiarlo 
    var tabla = $("#tabla_cierre_diag").DataTable({
        "ajax": {
            "url": `${PATH_NAME}/soporte_tecnico/consultar_datos_cierre`,
            "type": "GET",
        }, "columns": [
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
                    return `<center>
                            <div class="select_acob text-center">
                            <input type="checkbox" class="items_selec" id="diagnostico${row.id_diagnostico}" name='diagnostico${row.id_diagnostico}' value="${row.item}">
                            </div>
                            <center>`;
                }
            }
        ]
    });
    validar_check();
    generar_acta();
}

var array_item = [];
function validar_check() { //ok
    $('#tabla_cierre_diag tbody').on("click", "tr input.items_selec", function () {
        var data = $('#tabla_cierre_diag').DataTable().row($(this).parents("tr")).data();
        var id_diagnostico = data['id_diagnostico'];
        array_item = [];
        var alerta = false
        $('.items_selec:checked').each(function () {
            var data1 = $('#tabla_cierre_diag').DataTable().row($(this).parents("tr")).data();
            if (data1['id_diagnostico'] == id_diagnostico) {
                array_item.push(data1);// Agregar los valores al array
            } else {
                $(this).prop('checked', false);
                alerta = true
            }
        });
        console.log(array_item);
        if (alerta) alertify.error('¡Los items seleccionados no pertenecen al mismo diagnostico!')
    })
}


var generar_acta = function () { // falta el boton de procesado
    $('#generar_acta').on("click", function () {
        var cant_item_array = array_item.length;
        if (cant_item_array === 0) {
            alertify.error('Debe seleccionar un item para realizar el acta');
            return;
        } else {
            if (array_item[0].estado_item == 15) {//15-Reparación Efectuada  
                alertify.confirm(`ALERTA SIDPA`, `¿Requiere Factura?`,
                    function () {// si requiere factura 
                        alertify.alert('ALERTA SIDPA', '</div><label for="observaciones"><b>Observaciones Pedido:</b></label><br> <textarea name="observaciones" id="observaciones" class="col-10" rows="10"></textarea><br><br>¿Factura con IVA?" <button type="button" onclick="showConfirm(1);" id="iva_si" class="btn btn-success">Si</button>  <button type="button" onclick="showConfirm(2);" id="iva_no" class="btn btn-danger">No</button>',
                            function () {
                            }).set({
                                'label': 'Cancelar',
                                'transitionOff': true
                            });
                        window.showConfirm = function (vista) {
                            btn_procesando('iva_no');
                            btn_procesando('iva_si');
                            if (vista == 1) {// con iva
                                var iva = 1;
                            } else {//sin iva
                                var iva = 2;
                            }
                            var observaciones = $('#observaciones').val();
                            enviar_datos_acta(iva, 1, observaciones);
                        }
                    }, function () {// no requiere factura
                        return;
                    }).set({
                        'labels': { ok: 'Si', cancel: 'Cancelar' },
                    });
            } else if (array_item[0].estado_item == 16 || array_item[0].estado_item == 11) { //16-reparación fallida 11-cotización cancelada
                enviar_datos_acta(2, 2);
            } else {//17-comodato garantía
                enviar_datos_acta(2, 3);
            }
        }
    })
}
// La variable estado es para saber si toca crear un pedido o no 
var enviar_datos_acta = function (iva, estado, observaciones = '') {// estos estado estado para el pedido (1-acta y pedido)(2-actaDSR y cambio estados)(3-acta y cambio estados)
    var obj_inicial = $('#generar_acta').html();
    btn_procesando('generar_acta');
    var data = array_item;
    $.ajax({
        "url": `${PATH_NAME}/soporte_tecnico/generar_acta`,
        "type": 'POST',
        "data": { data, iva, estado, observaciones },
        success: function (res) {
            btn_procesando('generar_acta', obj_inicial, 1);
            var num_acta = res.num_acta;
            var num_pedido = res.num_pedido;
            generar_pdf_acta(num_pedido, num_acta);
        }
    });
}

var generar_pdf_acta = function (num_pedido, num_acta) {// estos estados son del acta toca mirarlos
    $.ajax({
        "url": `${PATH_NAME}/soporte_tecnico/generar_pdf_acta`,
        "type": 'POST',
        "data": { num_acta },
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
            if (num_pedido != '') {
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
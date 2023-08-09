$(document).ready(function () {
    consultar_repuestos();
    validacion_inv();
    enviar();
    cancelar_diagnostico();
    compras_diag();
});

var consultar_repuestos = function () {
    var table = $("#tabla_repuestos").DataTable({
        "ajax": `${PATH_NAME}/soporte_tecnico/consultar_repuestos`,
        "columns": [
            {
                "render": function (data, type, row) {
                    return `${row.id_diagnostico}-${row.item}`
                },

            },
            { "data": "nombre_empresa" },
            { "data": "equipo" },
            { "data": "serial_equipo" },
            { "data": "codigo_producto" },
            { "data": "descripcion_productos" },
            { "data": "nombre_estado_item" },
            {
                "render": function (data, type, row) {
                    if (row.estado_cotizacion == 2) {
                        return `
                                <center>
                                    <button type='button' title='Aprobar cotización' class='btn btn-success btn-circle aprueba_cotiza'>
                                        <span class='fas fa-check'></span>
                                    </button>
                                    <button type='button' title='Cancelar cotización' class='btn btn-danger btn-circle cancela_cotiza'>
                                        <span class='fas fa-ban'></span>
                                    </button>
                                </center>`;
                    } if (row.estado_cotizacion == 3) {
                        return `
                                <center>
                                    <button type='button' title='Validar en inventario' class='btn btn-warning btn-circle validar_inv'">
                                        <span class='fas fa-swatchbook'></span>
                                    </button>
                                    <button type='button' title='Cancelar diagnostico' class='btn btn-danger btn-circle cancelar_diag'">
                                        <span class='fas fa-ban'></span>
                                    </button>
                                </center>`
                    } if (row.estado_cotizacion == 4) {
                        return `
                                <center>
                                    <button type='button' title='Compras' class='btn btn-info btn-circle compras'>
                                        <span class='fas fa-money-bill-alt'></span>
                                    </button>
                                </center>`
                    }
                }
            },
        ]
    });
}

var cancelar_diagnostico = function () {
    $('#tabla_repuestos tbody').on("click", "button.cancelar_diag", function (e) {
        e.preventDefault();
        var array_item = $("#tabla_repuestos").DataTable().row($(this).parents("tr")).data();
        var estado_cotiza = 8; //FIN PROCESO REPUESTOS
        var estado_item = 16; //REPARACION NO REALIZADA
        alertify.confirm('ALERTA SIDPA', '¿Esta seguro que desea devolver este equipo sin reparar?', function () {
            $.ajax({
                "url": `${PATH_NAME}/soporte_tecnico/cancelar_diagnostico`,
                "type": 'POST',
                "data": { array_item, estado_cotiza, estado_item },
                success: function (res) {
                    if (res.status == 1) {
                        alertify.success('Se cerro el diagnostico');
                        window.location.href = `${PATH_NAME}/vista_cierre_diag`;
                    } else {
                        alertify.error('Algo a ocurrido');
                    }
                }
            });
        }, function () { alertify.error('Cancelado'); })
            .set('labels', { ok: 'Si', cancel: 'No' });
    });
}

var compras_diag = function () {
    $('#tabla_repuestos tbody').on("click", "button.compras", function (e) {
        e.preventDefault();
        var array_item = $("#tabla_repuestos").DataTable().row($(this).parents("tr")).data();
        var estado_cotiza = 5; //REPUESTOS EN REPACION
        var estado_item = 12; //EN EJECUCION DE REPARACION
        alertify.confirm('ALERTA SIDPA', '¿Esta seguro que ya cuenta con los repuestos de este equipo?', function () {
            $.ajax({
                "url": `${PATH_NAME}/soporte_tecnico/compras_diag`,
                "type": 'POST',
                "data": { array_item, estado_cotiza, estado_item },
                success: function (res) {
                    if (res == true) {
                        window.location.href = `${PATH_NAME}/ejecucion_reparacion`;
                    } else {
                        alertify.error('Algo a ocurrido');
                    }
                }
            });
        }, function () { alertify.error('Cancelado'); })
            .set('labels', { ok: 'Si', cancel: 'No' });
    });
}

var datos_envio = [];
var validacion_inv = function () {
    $('#tabla_repuestos tbody').on("click", "button.validar_inv", function (e) {
        e.preventDefault();
        var array_item = $("#tabla_repuestos").DataTable().row($(this).parents("tr")).data();
        var array_datos = [];
        array_datos.push(array_item);
        $('#tabla_inventario').DataTable({
            "data": array_datos,
            "columns": [
                {
                    "render": function (data, type, row) {
                        return `${row.codigo_producto}-${row.descripcion_productos}`
                    }
                },
                {
                    "render": function (data, type, row) {
                        return `${row.cantidad_inventario[0].total}`
                    }
                },
            ],
        });
        $("#modal_inventario").modal("show");
        if (array_item['cantidad_inventario'][0].total < array_item['cantidad']) {
            $('#descontar_inv').attr('disabled', 'disabled');
        } else {
            $('#descontar_inv').attr('disabled', false);
        }
        datos_envio = array_datos[0];
    })
};

var enviar = function () {
    $('.inventario').on("click", function (e) {
        e.preventDefault();
        var valor = $(this).val();
        if (valor == 1) {
            var obj_inicial = $(`#descontar_inv`).html();
            btn_procesando(`descontar_inv`, obj_inicial, 1);
            $.ajax({
                "url": `${PATH_NAME}/soporte_tecnico/validacion_inv`,
                "type": 'POST',
                "data": { valor, datos_envio },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (res) {
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(res);
                    a.href = url;
                    a.download = 'MemorandoInterno' + datos_envio['id_diagnostico'] + '.pdf';
                    a.click();
                    window.URL.revokeObjectURL(url);

                    consultar_repuestos();
                    $("#modal_inventario").modal("hide");
                }
            });
        } else {
            var obj_inicial = $(`#comprar`).html();
            btn_procesando(`comprar`, obj_inicial, 1);
            $.ajax({
                "url": `${PATH_NAME}/soporte_tecnico/validacion_inv`,
                "type": 'POST',
                "data": { valor, datos_envio },
                success: function (res) {
                    if (res == 1) {
                        consultar_repuestos();
                        $("#modal_inventario").modal("hide");
                    } else {
                        alertify.error('Algo a ocurrido');
                        $("#modal_inventario").modal("hide");
                    }
                }
            });
        }
    });
}


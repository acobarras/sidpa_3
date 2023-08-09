$(document).ready(function () {
    carga_cotizacion();
    personal_soporte();
});

var valida_url = function () {
    var params = new URLSearchParams(location.search);
    var id_url = params.get('id');
    if (id_url != '') {
        $(`#cotiza${id_url}`).on('click', function () {
        })
        $(`#cotiza${id_url}`).trigger('click');
    }
}

var carga_cotizacion = function () {
    var table = $("#tb_cotizacion_visita").DataTable({
        "ajax": {
            "url": `${PATH_NAME}/soporte_tecnico/carga_cotizacion`,
            "type": "POST",
        },
        "columns": [
            { "data": "id_diagnostico" },
            { "data": "nombre_empresa" },
            { "data": "direccion" },
            { "data": "nombre_estado_soporte" },
            { "data": "fecha_creacion_diag" },
            {
                "render": function (data, type, row) {
                    if (row.estado == 2) {
                        return `<center>
                                <button type='button' title='Cotizar' id='cotiza${row.id_diagnostico}' class='btn btn-info btn-circle cotiza' data-bs-toggle='modal' data-bs-target='#cotiza'>
                                    <span class='fas fa-search'></span>
                                </button>
                                `
                    } if (row.estado == 3) {
                        return `<center>
                                <button type='button' title='Aprobar' id='si_aprobo' class='btn btn-success btn-circle aprobacion'>
                                    <span class='fas fa-check'></span>
                                </button>
                                <button type='button' title='No aprobar' id='no_aprobo' class='btn btn-danger btn-circle noaprobacion'>
                                    <span class='fas fa-ban'></span>
                                </button>
                                <center>`
                    } if (row.estado == 4) {
                        return `<center>
                                <button type='button' title='Agendar visita' id='boton_agendar' class='btn btn-warning btn-circle agendar_visita' data-bs-toggle='modal' data-bs-target='#agendar_visita' >
                                    <span class='fas fa-book-medical'></span>
                                </button>
                                <center>`
                    } if (row.estado == 6 && row.boton == true) {
                        return `
                        <center>
                            <button type='button' title='Reasignar' id='boton${row.id_diagnostico}' class='btn btn-secondary btn-circle reasignar' data-bs-toggle='modal' data-bs-target='#reagendar_visita'>
                                <span class='fas fa-calendar-alt'></span>
                            </button>
                        <center>`
                    } else {
                        return `
                        <center>
                            <button type='button' title='Reasignar - Desactivado' class='btn btn-danger btn-circle reasignar'>
                                <span class='fas fa-exclamation-triangle'></span>
                            </button>
                            <h6> Sin acciones </h6>
                        <center>`
                    }
                }
            },
        ]
    });
    enviar_cotizacion('#tb_cotizacion_visita tbody', table);
    seleccionar_producto();
    valida_url();
    reagendar_visita('#tb_cotizacion_visita tbody', table);
};

var seleccionar_producto = function () {
    $.ajax({
        "url": `${PATH_NAME}/soporte_tecnico/producto_serman`,
        "type": 'POST',
        success: function (res) {
            var producto = "<option val='0'>Elija Una Producto</option>";
            res.forEach(element => {
                producto +=/*html*/
                    ` <option value='${JSON.stringify(element.id_productos)}'>${element.codigo_producto}</option>`;
            });
            $('#producto_cotiza').html(producto);
        }
    })
}

var reagendar_visita = function (tbody, table) {
    $(tbody).on("click", "button.reasignar", function () {
        var datos_tabla = table.row($(this).parents("tr")).data();
        var id_diagnostico = datos_tabla['id_diagnostico'];
        $('#reagenda_persona').val(datos_tabla['id_usuario_visita']);
        $('#reagendar_fecha').empty().val(datos_tabla['fecha_agendamiento']);
        $('#enviar_reagendar_visita').on('click', function () {
            var fecha = $('#reagendar_fecha').val();
            var persona = $('#reagenda_persona').val();
            var envio = {
                'id_diagnostico': id_diagnostico,
                'fecha_visita': fecha,
                'persona_visita': persona
            }
            $.ajax({
                "url": `${PATH_NAME}/soporte_tecnico/reagendar_visita`,
                "type": 'POST',
                "data": envio,
                success: function (res) {
                    if (res.status == 1) {
                        limpiar_formulario('form_reagendar_visita', 'input');
                        $('#reagendar_visita').modal('hide')
                        alertify.success('Se Reagendo la visita correctamente');
                        table.ajax.reload(function () { });
                        window.location.href = `${PATH_NAME}/visitas_agendadas`;
                    } else {
                        alertify.error('Algo a ocurrido');
                    }
                }
            })
        })
        $('#cerrar_reagenda').on('click', function () {
            alertify.error('Cancelado');
        })
    });
}

var enviar_cotizacion = function (tbody, table) {
    $(tbody).on("click", "button.cotiza", function () {
        var datos_tabla = table.row($(this).parents("tr")).data();
        $('#enviar_cotiza').on('click', function () {
            var form = $('#form_cotizar').serialize();
            var valida = validar_formulario(form);
            var envio = {
                'datos': datos_tabla,
                'form': form,
            }
            if (valida) {
                alertify.confirm('ALERTA SIDPA', '¿Esta seguro que desea continuar con el valor digitado?', function () {
                    $.ajax({
                        "url": `${PATH_NAME}/soporte_tecnico/cotizacion_visita`,
                        "type": 'POST',
                        "data": envio,
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function (res) {
                            if (res.status == -1) {
                                alertify.error('algo a ocurrido');
                            } else {

                                var a = document.createElement('a');
                                var url = window.URL.createObjectURL(res);
                                a.href = url;
                                a.download = 'Cotizacion' + datos_tabla['id_diagnostico'] + '.pdf';
                                a.click();
                                window.URL.revokeObjectURL(url);

                                limpiar_formulario('form_cotizar', 'input');
                                $('#cotiza').modal('hide');
                                table.ajax.reload(function () { });
                                // location.reload();
                            }
                        }
                    });
                }, function () { alertify.error('Cancelado'); })
                    .set('labels', { ok: 'Si', cancel: 'No' });
            }
        });
        $('#cerrar_cotiza').on('click', function () {
            alertify.error('Cancelado');
        })
    });
    aprobacion_cotizacion(tbody, table);
    agendar_visita(tbody, table);

}

var aprobacion_cotizacion = function (tbody, table) {
    $(tbody).on("click", "button.aprobacion", function () {
        var data = table.row($(this).parents("tr")).data();
        var id_diagnostico = data['id_diagnostico'];
        estado = 4;
        var envio = {
            'id': id_diagnostico,
            'estado': estado,
        };
        $.ajax({
            "url": `${PATH_NAME}/soporte_tecnico/aprueba_cotizacion`,
            "type": 'POST',
            "data": envio,
            success: function (res) {
                if (res.status == 1) {
                    alertify.success('en espera de agendamiento');
                    table.ajax.reload(function () { });
                } else {
                    alertify.error('Algo a ocurrido');
                }
            }
        });
    });
    $(tbody).on("click", "button.noaprobacion", function () {
        var data = table.row($(this).parents("tr")).data();
        var id_diagnostico = data['id_diagnostico'];
        var estado = 14;
        var envio = {
            'id': id_diagnostico,
            'estado': estado,
        };
        alertify.confirm('ALERTA SIDPA', '¿Esta seguro de no aprobar la cotización?', function () {
            $.ajax({
                "url": `${PATH_NAME}/soporte_tecnico/aprueba_cotizacion`,
                "type": 'POST',
                "data": envio,
                success: function (res) {
                    if (res.status == 1) {
                        alertify.error('La Cotización NO fue aprobada');
                        table.ajax.reload(function () { });
                    } else {
                        alertify.error('Algo a ocurrido');
                    }
                }
            });
        }, function () { alertify.error('Cancelado'); })
            .set('labels', { ok: 'Si', cancel: 'No' });
    });
}
var personal_soporte = function () {
    // SE CARGAN EL PERSONAL DE SOPORTE TECNICO
    $.ajax({
        "url": `${PATH_NAME}/soporte_tecnico/personal_soporte`,
        "type": 'POST',
        success: function (res) {
            var persona = "<option value='0'>Elija Una Persona</option>";
            res.forEach(element => {
                persona +=/*html*/
                    ` <option value='${JSON.stringify(element.id_persona)}'>${element.nombres} ${element.apellidos}</option>`;
            });
            $('#persona_visita').html(persona);
            $('#reagenda_persona').html(persona);

        }
    })
}

var agendar_visita = function (tbody, table) {
    // SE ENVIA EL FORMULARIO DE LA VISITA 
    $(tbody).on("click", "button.agendar_visita", function () {
        var datos_tabla = table.row($(this).parents("tr")).data();
        var id_diagnostico = datos_tabla['id_diagnostico'];
        $('#enviar_agenda_visita').on("click", function () {
            var form = $('#form_agendar_visita').serialize();
            var valida = validar_formulario(form);
            var envio = {
                'id_diagnostico': id_diagnostico,
                'form': form,
            };
            if (valida) {
                $.ajax({
                    "url": `${PATH_NAME}/soporte_tecnico/agendar_visita`,
                    "type": 'POST',
                    "data": envio,
                    success: function (res) {
                        if (res.status == 1) {
                            limpiar_formulario('form_cotizar', 'input');
                            $('#agendar_visita').modal('hide')
                            alertify.success('Se agendo la visita correctamente');
                            window.location.href = `${PATH_NAME}/visitas_agendadas`;
                        } else {
                            alertify.error('Algo a ocurrido');
                        }
                    }
                });
            }
        })
        $('#cerrar_agenda_visita').on("click", function () {
            alertify.error('Cancelado');
        })

    });
}
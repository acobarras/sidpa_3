$(document).ready(function () {
    consultar_datos_ejecucion();
    boton_regresar();
    personal_soporte();
    reparacion_ejecutada();
    no_ejecutado();
});

var consultar_datos_ejecucion = function () {
    var table = $("#tabla_ejecucion").DataTable({
        "ajax": `${PATH_NAME}/soporte_tecnico/consultar_datos_ejecucion`,
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
                    if (row.estado_item == 12) {
                        if (row.repuestos_listos == row.total_repuestos) {
                            botones = `
                                <center>
                                <button type='button' class='btn btn-secondary btn-circle asignar_tec' data-bs-toggle='modal' data-bs-target='#asignar_tec'>
                                    <span class="fas fa-user-plus"></span>
                                </button>
                                <button type='button' class='btn btn-info btn-circle consultar_repuestos'>
                                <span class="fas fa-search"></span>
                                </button>
                                <center>`;
                            return botones;
                        } else {
                            botones = `
                            <center>
                                <button type='button' class='btn btn-info btn-circle consultar_repuestos'>
                                    <span class="fas fa-search"></span>
                                </button>
                            <center>`;
                            return botones;
                        }
                    } if (row.estado_item == 13) {
                        if (row.id_usuario_reparacion != 0 && row.fecha_ejecucion != '0000-00-00') {
                            botones = `
                            <center>
                                <button type='button' class='btn btn-success btn-circle ejecutado'>
                                    <span class="fas fa-check"></span>
                                </button>
                                <button type='button' class='btn btn-danger btn-circle no_ejecutado'>
                                    <span class="fas fa-ban"></span>
                                </button>
                                <button type='button' class='btn btn-info btn-circle consultar_repuestos'>
                                    <span class="fas fa-search"></span>
                                </button>
                            <center>`;
                            return botones;
                        }
                    }
                }
            }
        ]
    });
    datos_repuestos('#tabla_ejecucion tbody', table);
    asignar_tecnico('#tabla_ejecucion tbody', table);
}

var datos_repuestos = function (tbody, table) {
    $(tbody).on("click", "button.consultar_repuestos", function () {
        var data = table.row($(this).parents("tr")).data();
        $('#principal_reparacion').css('display', 'none');
        $('#titulo_equipo').html(`${data.equipo}` + ' S/N ' + `${data.serial_equipo}`);
        $('#repuestos').css('display', '');
        $('#tabla_repuestos').DataTable({
            "data": data['repuestos'],
            "columns": [
                { "data": "codigo_producto" },
                { "data": "descripcion_productos" },
                { "data": "cantidad" },
                { "data": "nombre_estado" },
            ],
        });
    });
}

var boton_regresar = function () {
    $('#regresar').on('click', function (e) {
        e.preventDefault();
        window.location.reload();
    });
}

var personal_soporte = function () {
    $.ajax({
        "url": `${PATH_NAME}/soporte_tecnico/personal_soporte`,
        "type": 'POST',
        success: function (res) {
            var persona = "<option value='0'>Elija Una Persona</option>";
            res.forEach(element => {
                persona +=/*html*/
                    ` <option value='${JSON.stringify(element.id_persona)}'>${element.nombres} ${element.apellidos}</option>`;
            });
            $('#id_persona_reparacion').html(persona);
        }
    })
}

var asignar_tecnico = function (tbody, table) {
    $(tbody).on("click", "button.asignar_tec", function () {
        var data = table.row($(this).parents("tr")).data();
        var id_diagnostico_item = data['id_diagnostico_item'];
        $('#enviar_selec').on('click', function () {
            var form = $('#form_selec_tecnico').serialize();
            if ($('#fecha_ejecucion').val() == '') {
                alertify.error('Se necesita una fecha de agendamiento');
                return;
            }
            var valida = validar_formulario(form);
            var obj_inicial = $('#enviar_selec').html();
            btn_procesando('enviar_selec');
            if (valida) {
                $.ajax({
                    "url": `${PATH_NAME}/soporte_tecnico/asignar_tecnico`,
                    "type": 'POST',
                    "data": { data, id_diagnostico_item, form },
                    success: function (res) {
                        if (res == true) {
                            btn_procesando('enviar_selec', obj_inicial, 1);
                            alertify.success('Técnico Asignado');
                            location.reload();
                        } else {
                            btn_procesando('enviar_selec', obj_inicial, 1);
                            alertify.error('Algo a ocurrido');
                            return;
                        }
                    }
                })
            }
        })
    });
}
var reparacion_ejecutada = function () {
    $('#tabla_ejecucion tbody').on("click", "button.ejecutado", function (e) {
        e.preventDefault();
        var data = $('#tabla_ejecucion').DataTable().row($(this).parents("tr")).data();
        var estado_cotiza = 8; //Estado de fin de proceso de los repuestos
        var estado_item = 15; // Reparacion Exitosa
        $.ajax({
            "url": `${PATH_NAME}/soporte_tecnico/reparacion_ejecutada`,
            "type": 'POST',
            "data": { data, estado_cotiza, estado_item },
            success: function (res) {
                if (res == true) {
                    alertify.success('Reparación Ejecutada Exitosamente');
                    window.location.href = `${PATH_NAME}/vista_cierre_diag`;
                } else {
                    alertify.error('Algo a ocurrido');
                }
            }
        });
    });
}
var no_ejecutado = function () {
    $('#tabla_ejecucion tbody').on("click", "button.no_ejecutado", function (e) {
        e.preventDefault();
        var data = $('#tabla_ejecucion').DataTable().row($(this).parents("tr")).data();
        var estado_cotiza = 8; //Estado de fin de proceso de los repuestos
        var estado_item = 16; // Reparacion fallida
        alertify.confirm(`ALERTA ACOBARRAS`, `¿Esta seguro que quiere devolver sin reparar el equipo?`, function () {
            $.ajax({
                "url": `${PATH_NAME}/soporte_tecnico/reparacion_ejecutada`,
                "type": 'POST',
                "data": { data, estado_cotiza, estado_item },
                success: function (res) {
                    if (res == true) {
                        alertify.error('Reparación No ejecutada');
                        window.location.href = `${PATH_NAME}/vista_cierre_diag`;
                    } else {
                        alertify.error('Algo a ocurrido');
                    }
                }
            });
        }, function () { alertify.error('Cancelado') })
            .set('labels', { ok: 'Si', cancel: 'No' });
    });
}
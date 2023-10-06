$(document).ready(function () {
    carga_visita_agendada();
    boton_regresar();
    cargar_item();
    mostrar_formulario();
    seleccionar_producto();
});

const IDPERSONA = $('#id_usuario').val();
const ROLL = $('#roll_usuario').val();

var carga_visita_agendada = function () {
    var table = $("#tb_visita_agendada").DataTable({
        "ajax": `${PATH_NAME}/soporte_tecnico/carga_visita_agendada`,
        "columns": [
            { "data": "id_diagnostico" },
            { "data": "nombre_empresa" },
            { "data": "direccion" },
            { "data": "fecha_agendamiento" },
            {
                "render": function (data, type, row) {
                    return `<center>${row.nombres} ${row.apellidos}<center>`
                }
            },
            { "data": "nombre_estado_soporte" },
            {
                "render": function (data, type, row) {
                    if (row.estado == 5) {
                        return `<center>
                                        <button type='button' title='Enviar agenda' class='btn btn-success btn-circle envio_agenda'>
                                        <span class='fas fa-check'></span>
                                        </button>
                                        <center>
                                        <center>
                                        <button type='button' title='Cancelar agenda' class='btn btn-danger btn-circle cancela_agenda'>
                                        <span class='fas fa-ban'></span>
                                        </button>
                                        <center>`
                    } else {
                        // ESTADO 8->AGREGA INFORMACION DEL EQUIPO
                        return `<center>
                    <button type='button' title='Agregar item' id='boton${row.id_diagnostico}' class='agregar_item btn btn-info btn-circle'>
                        <i class="fas fa-laptop-medical"></i>
                    </button>
                <center>`
                    }
                }
            },
        ]
    });
    agendamiento_visita('#tb_visita_agendada tbody', table);
};

var agendamiento_visita = function (tbody, table) {
    $(tbody).on("click", "button.cancela_agenda", function () {
        var data_tabla = table.row($(this).parents("tr")).data();
        var envio = {
            'data': data_tabla,
            'estado': 6,
        }
        $.ajax({
            url: `${PATH_NAME}/soporte_tecnico/agendamiento`,
            type: "POST",
            data: envio,
            success: function (res) {
                if (res.status == 1) {
                    alertify.success('Se reagendara la visita');
                    window.location.href = `${PATH_NAME}/vista_cotizacion?id=${data_tabla['id_diagnostico']}`;
                } else {
                    alertify.error('Algo a ocurrido');
                }
            }
        });
    })
    $(tbody).on("click", "button.envio_agenda", function () {
        var data_envio = table.row($(this).parents("tr")).data();
        if (data_envio.req_cotiza == 0) {
            var envio = {
                'data': data_envio,
                'estado': 8,
            }
            alertify.confirm('ALERTA SIDPA', '¿Es instalacion de un equipo de venta comercial?', function () {
                $("#modal_instalacion").modal("show");
                enviar_equipo(data_envio);
            }, function () {
                $.ajax({
                    url: `${PATH_NAME}/soporte_tecnico/agendamiento`,
                    type: "POST",
                    data: envio,
                    success: function (res) {
                        if (res.status == 1) {
                            location.reload();
                        } else {
                            alertify.error('Algo a ocurrido');
                        }
                    }
                });
            }).set('labels', { ok: 'Si', cancel: 'No' });
        } else {
            var envio = {
                'data': data_envio,
                'estado': 8,
            }
            $.ajax({
                url: `${PATH_NAME}/soporte_tecnico/agendamiento`,
                type: "POST",
                data: envio,
                success: function (res) {
                    if (res.status == 1) {
                        location.reload();
                    } else {
                        alertify.error('Algo a ocurrido');
                    }
                }
            });
        }
    });
}

var enviar_equipo = function (data) {
    var firma_insta = document.getElementById('captura_firma');
    var signaturePad = new SignaturePad(firma_insta, {
        backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
    });
    $('.borrar_firma').on('click', function () {
        var data = signaturePad.toData();
        if (data) {
            data.pop(); // remove the last dot or line
            signaturePad.fromData(data);
        }
    });
    $('#form_instalacion').submit(function (e) {
        e.preventDefault();
        if (signaturePad.isEmpty()) {
            return alertify.error("Ingrese la firma");
        } else {
            var firma_fin = captura_firma(firma_insta, signaturePad, 1);
            var form = $(this).serializeArray();
            var validar = validar_formulario(form);
        }
        if (validar) {
            var envio = {
                'form': form,
                'data': data,
                'firma': firma_fin,
            }
            $.ajax({
                url: `${PATH_NAME}/soporte_tecnico/generar_instalacion`,
                type: "POST",
                data: envio,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (res) {
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(res);
                    a.href = url;
                    a.download = 'Visita.pdf';
                    a.click();
                    window.URL.revokeObjectURL(url);

                    $("#modal_instalacion").modal("hide");
                    carga_visita_agendada();
                }
            });
        }
    });
}

var mostrar_formulario = function () {
    $('#tb_visita_agendada tbody').on("click", "button.agregar_item", function () {
        $('#principal_visita').css('display', 'none');
        $('#agregar_item_visita').css('display', '');
        var fecha = new Date();
        document.getElementById("fecha_ingreso").value = fecha.toJSON().slice(0, 10);
        var table = $("#tb_visita_agendada").DataTable().row($(this).parents("tr")).data();
        $('#cargar_item').attr('data-row', JSON.stringify(table));
        enviar_items(table['num_consecutivo']);
        cargar_tabla_item(table['num_consecutivo']);

    });
}

var boton_regresar = function () {
    $('#regresar').on('click', function (e) {
        e.preventDefault();
        window.location.reload();
    });
}
var array_item = [];
var cargar_item = function () {
    $('#cargar_item').on("click", function () {
        var datos = JSON.parse($(this).attr('data-row'));
        var form = $('#form_agregar_equipo').serializeArray();
        var fecha = $('#fecha_ingreso').val();
        var equipo = $('#equipo').val();
        var accesorios = $('#accesorios').val();
        var serial_equipo = $('#serial').val();
        var procedimiento = $('#procedimiento').val();
        var valida = validar_formulario(form);
        if (valida) {
            var envio = {
                'fecha_ingreso': fecha,
                'equipo': equipo,
                'serial_equipo': serial_equipo,
                'procedimiento': procedimiento,
                'accesorios': accesorios,
                'num_consecutivo': datos['num_consecutivo'],
                'id_diagnostico': datos['id_diagnostico'],
                'id_cli_prov': datos['id_cli_prov'],
                'direccion': datos['direccion'],
                'ciudad': datos['nombre_ciudad'],
                'departamento': datos['nombre_departa'],
                'pais': datos['nombre_pais'],
                'nombre_empresa': datos['nombre_empresa'],
            }
            var storage = JSON.parse(localStorage.getItem('item_diagnostico' + datos['num_consecutivo']));
            if (storage != null) {
                array_item = storage;
            }
            if (array_item == '') {
                array_item.push(envio);
                alertify.success('datos cargados exitosamente');
                borrar_formulario();
            } else {
                var respu = false;
                array_item.forEach(element => {
                    if (element.serial_equipo == envio['serial_equipo']) {
                        alertify.error('el serial digitado ya fue cargado');
                        borrar_formulario();
                        respu = true;
                    }
                });
                if (!respu) {
                    array_item.push(envio);
                    alertify.success('datos cargados exitosamente');
                    borrar_formulario();
                }
            }
            localStorage.setItem('item_diagnostico' + datos['num_consecutivo'], JSON.stringify(array_item));
            cargar_tabla_item(datos['num_consecutivo']);
        }
    });
}

var cargar_tabla_item = function (consecutivo) {
    var nuevo_storage = JSON.parse(localStorage.getItem('item_diagnostico' + consecutivo));
    var table = $("#tb_item_agregados").DataTable({
        "data": nuevo_storage,
        "columns": [
            { "data": "equipo" },
            { "data": "serial_equipo" },
            { "data": "procedimiento" },
            { "data": "accesorios" },
            {
                "defaultContent":
                    `<center>
                    <button title='Eliminar item' class="btn btn-danger btn-sm btn-circle elimina_item"><i class="fa fa-times"></i></button>
                </center>`
            },

        ],
    });
    elimina_items(consecutivo);
}

var borrar_formulario = function () {
    limpiar_formulario('form_agregar_equipo', 'textarea');
    limpiar_formulario('form_agregar_equipo', 'input');
    limpiar_formulario('form_agregar_equipo', 'select');
    var fecha = new Date();
    document.getElementById("fecha_ingreso").value = fecha.toJSON().slice(0, 10);
}

var elimina_items = function (consecutivo) {
    $('#tb_item_agregados tbody').on("click", "button.elimina_item", function (e) {
        e.preventDefault();
        var data = $('#tb_item_agregados').DataTable().row($(this).parents("tr")).data();
        var storage = JSON.parse(localStorage.getItem('item_diagnostico' + consecutivo));
        storage.forEach(element => {
            if (element.serial_equipo === data.serial_equipo) {
                var index = storage.indexOf(element);
                if (index > -1) {
                    storage.splice(index, 1);
                }
            }
        });
        localStorage.setItem('item_diagnostico' + consecutivo, JSON.stringify(storage));
        cargar_tabla_item(consecutivo);
    });
};



function enviar_items(consecutivo) {
    var canvas = document.getElementById('capturafirmacliente');
    var estado = 2;
    var sede = 0;
    var signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
    });
    $('.borrar_firma').on('click', function () {
        var data = signaturePad.toData();
        if (data) {
            data.pop(); // remove the last dot or line
            signaturePad.fromData(data);
        }
    });

    $('#enviar_datos').on('click', function () {
        var nuevo_storage = JSON.parse(localStorage.getItem('item_diagnostico' + consecutivo));
        var obj_inicial = $('#enviar_datos').html();
        var nota = $('#nota').val();
        var recibido = $('#recibido').val();
        if (signaturePad.isEmpty()) {
            return alertify.error("Esta vacio el campo");
        } else {
            btn_procesando('enviar_datos');
            var firma = captura_firma(canvas, signaturePad, 1);
            $.ajax({
                "url": `${PATH_NAME}/soporte_tecnico/enviar_equipo_soporte`,
                "type": 'POST',
                "data": { nuevo_storage, estado, firma, sede, nota, recibido },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (res) {
                    localStorage.removeItem('item_diagnostico' + consecutivo);
                    alertify.success('Se han cargado exitosamente los equipos');
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(res);
                    a.href = url;
                    a.download = `Documento${consecutivo}`;// Es como se llama el pdf
                    a.click();
                    window.URL.revokeObjectURL(url);
                    // window.location.href = `${PATH_NAME}/gestionar_diagnostico`;
                    location.reload();
                    btn_procesando(`enviar_items`, obj_inicial, 1);
                }
            });
        }

    });
}

var seleccionar_producto = function () {
    $.ajax({
        "url": `${PATH_NAME}/soporte_tecnico/producto_serman`,
        "type": 'POST',
        success: function (res) {
            var producto = "<option value ='0'>Elija Una Producto</option>";
            res.forEach(element => {
                producto +=/*html*/
                    ` <option value='${JSON.stringify(element.id_productos)}'>${element.codigo_producto}</option>`;
            });
            $('#codigo_producto').html(producto);
        }
    })
}
$(document).ready(function () {
    carga_laboratorio();
    boton_regresar();
    cargar_item();
    mostrar_formulario();
    redirigir();
});


var valida_url = function () {
    var params = new URLSearchParams(location.search);
    var id_url = params.get('id');
    if (id_url != '') {
        $(`#boton${id_url}`).on('click', function () {
        })
        $(`#boton${id_url}`).trigger('click');
    }
}

var carga_laboratorio = function () {
    var table = $("#tb_laboratorio").DataTable({
        "ajax": `${PATH_NAME}/soporte_tecnico/carga_laboratorio`,
        "columnDefs": [
            { "visible": false, "targets": 0 }
        ],
        "columns": [
            { "data": "num_consecutivo" },
            { "data": "id_diagnostico" },
            { "data": "nombre_empresa" },
            { "data": "direccion" },
            { "data": "nombre_estado_soporte" }, 
            {
                "render": function (data, type, row) {
                    return `<center>
                    <button type='button' id='boton${row.id_diagnostico}' title="Ingresar equipos" class='agregar_item btn btn-info btn-circle'>
                        <i class="fas fa-laptop-medical"></i>
                    </button>
                <center>`
                }
            },
        ]
    });
    valida_url();
}
var mostrar_formulario = function () {
    $('#tb_laboratorio tbody').on("click", "button.agregar_item", function () {
        $('#principal_laboratorio').css('display', 'none');
        $('#agregar_item').css('display', '');
        var fecha = new Date();
        document.getElementById("fecha_ingreso").value = fecha.toJSON().slice(0, 10);
        var table = $("#tb_laboratorio").DataTable().row($(this).parents("tr")).data();
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
                    if (element.serial_equipo == serial_equipo) {
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
    elimina_items('#tb_item_agregados tbody', table, consecutivo);
}

var borrar_formulario = function () {
    limpiar_formulario('form_agregar_equipo', 'textarea');
    limpiar_formulario('form_agregar_equipo', 'input');
    limpiar_formulario('form_agregar_equipo', 'select');
    var fecha = new Date();
    document.getElementById("fecha_ingreso").value = fecha.toJSON().slice(0, 10);
}

var elimina_items = function (tbody, table, consecutivo) {
    $(tbody).on("click", "button.elimina_item", function () {
        var data = table.row($(this).parents("tr")).data();
        var storage = JSON.parse(localStorage.getItem('item_diagnostico' + consecutivo));
        storage.forEach(element => {
            if (element.serial === data.serial) {
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

var enviar_items = function (consecutivo) {
    $('#enviar_items').on("click", function () {
        var nuevo_storage = JSON.parse(localStorage.getItem('item_diagnostico' + consecutivo));
        var estado = 1;
        var firma = 2;
        var sede = $('#sede').val();
        var nota = $('#nota').val();
        var recibido = 0;
        var obj_inicial = $('#enviar_items').html();
        btn_procesando('enviar_items');
        if (nuevo_storage == null) {
            alertify.error('Por favor agregue un equipo');
            btn_procesando(`enviar_items`, obj_inicial, 1);
        } else {
            $.ajax({
                "url": `${PATH_NAME}/soporte_tecnico/enviar_equipo_soporte`,
                "type": 'POST',
                "data": { nuevo_storage, estado, firma, sede, nota, recibido },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (res) {
                    alertify.success('Se han cargado exitosamente los equipos');
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(res);
                    a.href = url;
                    // a.download = 'DocumentoEquipos' + consecutivo + '.pdf'; esta es la remision de ingreso
                    a.download = 'Remisión_' + consecutivo + '.pdf';
                    a.click();
                    window.URL.revokeObjectURL(url);

                    btn_procesando(`enviar_items`, obj_inicial, 1);
                    imprimir_etiquetas(consecutivo, nuevo_storage);
                    $('#enviar_items').css("display", 'none');
                    $('#cargar_item').css("display", 'none');
                    $('#redirigir').css("display", 'block');
                    localStorage.removeItem('item_diagnostico' + consecutivo);
                }
            });
        }
    });
}

var imprimir_etiquetas = function (consecutivo, datos) {
    $.ajax({
        "url": `${PATH_NAME}/soporte_tecnico/impresion_etiqueta_equipo`,
        "type": 'POST',
        "data": { consecutivo, datos },
        success: function (res) {
            $("div.div_impresion").empty().html(res);
            $("div.div_impresion").printArea();
            $("div.div_impresion").addClass('d-none');
        }
    });
}

var redirigir = function () {
    $('#redirigir').on("click", function () {
        location.reload();
        // window.location.href = `${PATH_NAME}/gestionar_diagnostico`;
    });
}
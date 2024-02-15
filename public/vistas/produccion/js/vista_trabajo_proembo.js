$(document).ready(function () {
    pestana_activa();
    select_2();
    $(".datepicker").datepicker();
    consultar_turnos();
    consultar_turnos_embo();
    generar_cambio_maquina();
    alertify.set('notifier', 'position', 'bottom-left');
    consulta_operario();
    validar_operario();
    cerrar_modal();
    generar_cambio_maquina_embo();
    boton_regresar();
    inicio_trabajo_item();
    datos_inicio_dk();
    grabar_produccion_pro_embo();
    grabar_reporte_etiquetas();
});

$('.pestana_maquina').on('click', function () {
    pestana_activa();
});

var pestana_activa = function () {
    $('.nav li a').each(function (index) {
        if ($(this).hasClass('active')) {
            var datos = $(this).attr('id-maquina');
            trabajo_maquinas(datos);
        }
    });
}

var carga_funcion = function () {
    boton_puesta_punto(); //Se carga la funcion por primera vez despues de que se crean las tablas
    boton_inicio_produccion(); //Se carga la funcion por primera vez despues de que se crean las tablas
    consulta_reporte();
    ver_ficha();
}

var datos_consulta = $('#datos_consulta').val();
var q_maquinas = JSON.parse($('#q_maquinas').val());
var id_persona_sesion = $('#id_persona_sesion').val();
var id_usuario_sesion = $('#id_usuario_sesion').val();
var estados_activos = ['4', '5', '6', '7', '10', '15'];//,8,9];

var trabajo_maquinas = function (id_maquina) {
    $.ajax({
        url: `${PATH_NAME}/produccion/consultar_trabajo_pro_embo?id_maquina=${id_maquina}`,
        type: 'GET',
        success: function (res) {
            carga_tablas(res, id_maquina);
            carga_funcion();
        }
    });
}

var carga_tablas = function (data, maquina) {
    var table = $(`#tabla_maquina_produccion${maquina}`).DataTable({
        "data": data,
        "order": [
            [1, "asc"],
            [2, "asc"]
        ],
        "columnDefs": [{
            "orderable": false,
            "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8]
        }],
        "columns": [
            { "data": "fecha_comp" },
            { "data": "fecha_produccion" },
            { "data": "turno_maquina" },
            { "data": "num_produccion" },
            {
                "data": "nombre_estado",
                render: function (data, type, row) {
                    if (row['estado_item_producir'] == 7) {
                        return `<b>${row['nombre_estado']}</br><span style="color:blue;font-size:12px;" class="displayReloj" ></span></b>`;
                    }
                    return `<b>${row['nombre_estado']}</b>`;
                }
            },
            { "data": "tamanio_etiq" },
            { "data": "mL_total", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "mL_descontado", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            {
                "data": "opcion", render: function (data, type, row) {
                    botones = ``;
                    var estado_ver_item = ['6', '7', '10', '15'];
                    if (estado_ver_item.indexOf(row['estado_item_producir'])) {
                        botones += `<button class="btn btn-info ver_op" data-ver="${row.id_maquina}" title="Ver O.P." ><i class="fa fa-search"></i></button> `;
                    }
                    if (datos_consulta == 1 && estados_activos.indexOf(row['estado_item_producir'])) { //row['estado_item_producir'] != 9) {
                        var color = 'btn-warning';
                        if (row['estado_item_producir'] == 15) {
                            color = 'btn-warning-like';
                        }
                        botones += `<button class="btn ${color} reasignar" data-id="${row.id_maquina}" title="Reasignar M.Q" ><i class="fa fa-retweet"></i></button> `;
                    }

                    var fecha_antes = new Date();
                    fecha_antes.setDate(fecha_antes.getDate() - 1);
                    var fecha = fecha_antes.toLocaleDateString();
                    fecha = fecha.split("/");
                    var fecha_antigua = fecha[2] + "-0" + fecha[1] + "-" + fecha[0];
                    if ((row.fecha_produccion == FECHA_HOY) || ((row.fecha_produccion == fecha_antigua) && (HORA_HOY <= '06:10:00') && (HORA_HOY >= '00:00:00'))) {
                        if (row['estado_item_producir'] == 6 || row['estado_item_producir'] == 10) {//pasar Puesta a punto
                            botones += `<button class="btn btn-primary puesta_punto" id="puesta_punto${row.id_item_producir}" data-puesta="${row.id_maquina}" title="Puesta Punto"><i class="fa fa-check"></i></button> `;
                        }
                        if (row['estado_item_producir'] == 7) {//pasar a inicio producción
                            botones += `<button class="btn btn-success inicio_produccion" id="inicio_produc${row.id_item_producir}" inicio-produc="${row.id_maquina}" title="Inicio Producción"><i class="fa fa-check" ></i></button> `;
                        }
                        if (row['estado_item_producir'] == 9) {//pasar a inicio producción
                            botones += `<button class="btn btn-success reportar_trabajo" id="ver-pro${row.id_item_producir}" data-reporta="${row.id_maquina}" title="Ver Trabajo" ><i class="fa fa-search"></i></button>`;
                        }
                        // }
                        if (row['estado_item_producir'] == 15) {//pasar a inicio Embobinado
                            botones += `<button class="btn btn-success inicio_produccion" id="inicio_produc${row.id_item_producir}" inicio-produc="${row.id_maquina}" title="Inicio Producción"><i class="fa fa-check" ></i></button> `;
                        }
                        if (row['estado_item_producir'] == 16) {//ver trabajos embobinados
                            botones += `<button class="btn btn-success reportar_trabajo" id="ver-embo${row.id_item_producir}" data-reporta="${row.id_maquina}" title="Ver Trabajo Embobinado"><i class="fa fa-search" ></i></button> `;
                        }
                    }
                    return botones;
                }, "className": "text-center"
            }
        ],
    });
    consulta_seguimiento(`#tabla_maquina_produccion${maquina} tbody`);
    reasignar(`#tabla_maquina_produccion${maquina} tbody`); //Se carga la funcion por primera vez despues de que se crean las tablas
}

function format(d) {
    var respu = `
    <div class="modal-header">
	    <h3 class="modal-title text-danger" style="text-align: center;" id="exampleModalLabel">ITEMS : <span style="color: #3932a9"></span></h3>
	</div>
    <table class="table table-bordered table-responsive table-hover tabla_edita" cellspacing="0" width="100%" id="tabla-item-op${d}">
        <thead class="thead-dark">
            <tr>
                <th>Codigo</th>
                <th>Descripción</th>
                <th>Tintas</th>
                <th>ubicación</th>
                <th>Cantidad</th>
                <th>mL</th>
                <th>m²</th>
                <th>Pedido-Item</th>
                <th>Core</th>
                <th>Rollo X/paq</th>
                <th>Sen/Emb</th>
                <th>Opciones</th>
            </tr>	
        </thead>
        <tbody>
        </tbody>
    </table>
    <br>`;
    return respu;
}

var detailRows = []; //Cunado se requiere poder ver mas de 

var consulta_seguimiento = function (tbody, table) {
    $(tbody).on('click', `tr button.ver_op`, function () {
        var dato = $(this).attr('data-ver');
        var data_row = $(`#tabla_maquina_produccion${dato}`).DataTable().row($(this).parents("tr")).data(); //capturar valores de la fila seleccionada
        var tr = $(this).closest('tr');
        var row = $(`#tabla_maquina_produccion${dato}`).DataTable().row(tr);
        var idx = $.inArray(tr.attr('id'), detailRows);
        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('details');
            detailRows.splice(idx, 1);
        }
        else {
            row.child(format(data_row.id_item_producir)).show(); // Carga el encabezado de la tabla para poder recibir los datos 
            tr.addClass('details');
            var num_produccion = data_row.num_produccion;
            var tabla_2 = $(`#tabla-item-op${data_row.id_item_producir}`).DataTable({
                "ajax": {
                    'url': `${PATH_NAME}/produccion/consulta_items_op`,
                    'data': { num_produccion },
                    'type': 'post'
                },
                columns: [
                    { data: "codigo" },
                    { data: "descripcion_productos" },
                    { data: "tintas" },
                    { data: "ubi_troquel" },
                    { data: "cant_op", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                    { data: "metrosl", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                    { data: "metros2", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                    {
                        data: "pedi_item", render: function (data, type, row) {
                            return `${row.num_pedido}-${row.item}`;
                        }
                    },
                    { data: "nombre_core" },
                    { data: "cant_x", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                    { data: "nombre_r_embobinado" },
                    {
                        data: "boton1", render: function (data, type, row) {
                            boton = `<button class="btn btn-success ver_ficha" data_produ="${row.codigo}" title="Ficha Tecnica"><i class="fas fa-eye"></i></button>`;
                            return boton;
                        }
                    }
                ]
            });
            if (idx === -1) {
                detailRows.push(tr.attr('id'));
            }
            ver_ficha(`#tabla-item-op${data_row.id_item_producir} tbody`, tabla_2);
        }
    });
}

var ver_ficha = function (tbody, table) {
    $(tbody).on('click', `tr button.ver_ficha`, function (e) {
        var codigo = $(this).attr('data_produ');
        var data = table.row($(this).parents("tr")).data();
        var observacion = data.observaciones_ft;
        var area = 1; //EL AREA 1 ES PRODUCCION Y EL 2 SERIAN ASESORES
        $.ajax({
            url: `${PATH_NAME}/configuracion/consultar_cod_producto`,
            type: 'POST',
            data: { codigo, area, observacion },
            success: function (res) {
                if (res[0].ficha_tecnica_produc != null) {
                    $('#ficha').modal('show');
                    $.post(`${PATH_NAME}/configuracion/vista_ficha_tec`,
                        {
                            datos: res[0],
                        },
                        function (respu) {
                            $('#ficha_tec').empty().html(respu);
                        });
                    $()
                } else {
                    res = alertify.error('Este producto no posee ficha tecnica digital, solicite la ficha tecnica impresa para su uso.');
                    return;
                }
            }
        });
    })
}

var reasignar = function (tbody) {
    $(tbody).on('click', `tr button.reasignar`, function () {
        var dato = $(this).attr('data-id');
        var data = $(`#tabla_maquina_produccion${dato}`).DataTable().row($(this).parents("tr")).data(); //capturar valores de la fila seleccionada
        if (data.estado_item_producir == 15) {
            $('#CambioMaquinaEmboModal').modal('show');
            $('#num_produccion_embo').empty().html(data.num_produccion);
            $('#turno_embobinado').val(data.turno_maquina);
            $('#fecha_embobinado_data').val(data.fecha_produccion);
            $('#maquina_embobinado').val(data.id_maquina).trigger('change');
            $('#generar_cambio_maquina_embo').attr('data-id', JSON.stringify(data));
        } else {
            $('#CambioMaquinaModal').modal('show');
            $('#num_produccion_data').empty().html(data.num_produccion);
            $('#turno_data').val(data.turno_maquina);
            $('#fecha_produccion_data').val(data.fecha_produccion);
            $('#maquina_data').val(data.id_maquina).trigger('change');
            $('#generar_cambio_maquina').attr('data-id', JSON.stringify(data));
        }
    });
}

var consultar_turnos = function () {
    $('.turno_maquina').change(function () {
        if ($('#fecha_produccion_data').val() == '') {
            console.log('campo vacio');
            return;
        }
        if ($('#maquina_data').val() == '') {
            console.log('campo vacio');
            return;
        }
        var fecha_produccion = $('#fecha_produccion_data').val();
        var id_maquina = $('#maquina_data').val();
        $.ajax({
            url: `${PATH_NAME}/produccion/consultar_turno_maquina`,
            type: 'POST',
            data: {
                fecha_produccion: fecha_produccion,
                id_maquina: id_maquina
            },
            success: function (res) {
                $(`#fecha_turno_maquina_data`).DataTable({
                    "data": res,
                    "columns": [
                        { "data": "nombre_maquina" },
                        { "data": "turno_maquina" },
                        { "data": "num_produccion" },
                        { "data": "mL_total", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                        { "data": "tamanio_etiq" },
                        { "data": "cant_op", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                        {
                            "data": "mate", render: function (data, type, row) {
                                var respu = row.material;
                                if (row.material_solicitado != '') {
                                    respu = row.material_solicitado;
                                }
                                return respu;
                            }
                        },
                        { "data": "nombre_estado" },
                    ],
                    "footerCallback": function (row, data, start, end, display) {
                        ml = this.api()
                            .column(3)//numero de columna a sumar
                            .data()
                            .reduce(function (a, b) {
                                return parseInt(a) + parseInt(b);
                            }, 0);
                        cantida_etiq = this.api()
                            .column(5)//numero de columna a sumar
                            .data()
                            .reduce(function (a, b) {
                                return parseInt(a) + parseInt(b);
                            }, 0);
                        var numFormat = $.fn.dataTable.render.number('.', ',', 2, '').display;
                        $(this.api().column(3).footer()).html(numFormat(ml));
                        $(this.api().column(5).footer()).html(numFormat(cantida_etiq));
                    }
                });
            }
        });
    });
}

var consultar_turnos_embo = function () {
    $('.turno_maquina_embo').change(function () {
        if ($('#fecha_embobinado_data').val() == '') {
            console.log('campo vacio');
            return;
        }
        if ($('#maquina_embobinado').val() == '') {
            console.log('campo vacio');
            return;
        }
        var fecha_produccion = $('#fecha_embobinado_data').val();
        var id_maquina = $('#maquina_embobinado').val();
        $.ajax({
            url: `${PATH_NAME}/produccion/consultar_turno_embobinado`,
            type: 'POST',
            data: {
                fecha_produccion: fecha_produccion,
                id_maquina: id_maquina
            },
            success: function (res) {
                $(`#fecha_turno_maquina_embo`).DataTable({
                    "data": res,
                    "columns": [
                        { "data": "nombre_maquina" },
                        { "data": "turno_maquina" },
                        { "data": "num_produccion" },
                        { "data": "ml_asignados", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                        { "data": "nombre_estado" },
                    ],
                    "footerCallback": function (row, data, start, end, display) {
                        ml = this.api()
                            .column(3)//numero de columna a sumar
                            .data()
                            .reduce(function (a, b) {
                                return parseInt(a) + parseInt(b);
                            }, 0);
                        var numFormat = $.fn.dataTable.render.number('.', ',', 2, '').display;
                        $(this.api().column(3).footer()).html(numFormat(ml));
                    }
                });
            }
        });
    });
}

var generar_cambio_maquina = function () {
    $('#generar_cambio_maquina').on('click', function () {
        var fecha_produccion = $('#fecha_produccion_data').val();
        var id_maquina = $('#maquina_data').val();
        var turno = $('#turno_data').val();
        var data = JSON.parse($('#generar_cambio_maquina').attr('data-id'));
        if (data.fecha_produccion == fecha_produccion && data.id_maquina == id_maquina) {
            alertify.error('No realizo ningun cambio');
            $('#CambioMaquinaModal').modal('toggle');
            return;
        }
        var datos = {
            'fecha_produccion': fecha_produccion,
            'id_maquina': id_maquina,
            'turno': turno,
            'id_item_producir': data.id_item_producir
        }
        $.ajax({
            url: `${PATH_NAME}/produccion/cambiar_op_maquina_pro_embo`,
            type: "POST",
            data: datos,
            success: function (res) {
                pestana_activa();
                $('#CambioMaquinaModal').modal('toggle');
            }
        });
    });
}

var generar_cambio_maquina_embo = function () {
    $('#generar_cambio_maquina_embo').on('click', function () {
        var fecha_embobinado = $('#fecha_embobinado_data').val();
        var maquina_embo = $('#maquina_embobinado').val();
        var turno = $('#turno_embobinado').val();
        var data = JSON.parse($('#generar_cambio_maquina_embo').attr('data-id'));
        if (data.fecha_embobinado == fecha_embobinado && data.maquina == maquina_embo) {
            alertify.error('No realizo ningun cambio');
            $('#CambioMaquinaEmboModal').modal('toggle');
            return;
        }
        var datos = {
            'fecha_embobinado': fecha_embobinado,
            'maquina': maquina_embo,
            'turno': turno,
            'id_maquina': data.id_maquina_embo
        }
        $.ajax({
            url: `${PATH_NAME}/produccion/cambiar_maquina_embo_dk`,
            type: "POST",
            data: datos,
            success: function (res) {
                pestana_activa();
                carga_funcion();
                $('#CambioMaquinaEmboModal').modal('toggle');
            }
        });
    });
}

var obj_inicial;
var item;
var boton_puesta_punto = function () {
    $('.puesta_punto').on('click', function () {
        var maquina = $(this).attr('data-puesta');
        $('.respu_consulta').empty().html();
        var data = $(`#tabla_maquina_produccion${maquina}`).DataTable().row($(this).parents("tr")).data(); //capturar valores de la fila seleccionada
        obj_inicial = $(`#puesta_punto${data.id_item_producir}`).html();
        item = data.id_item_producir;
        btn_procesando_tabla(`puesta_punto${data.id_item_producir}`);
        if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
            $('.respu_consulta').empty().html();
            $('#codigo_operario').val('');
            $('#obj_inicial').val('');
            $('#num_troquel').val('');
            $('#OperarioModal').modal('show');
            $('#data_boton').val(JSON.stringify(data));
            $('#boton_ejecuta').val(1);
        } else {
            activa_puesta_punto(data, id_persona_sesion);
        }
    });
}

var cerrar_modal = function () {
    $("#OperarioModal").on('hidden.bs.modal', function () {
        btn_procesando_tabla(`puesta_punto${item}`, obj_inicial, 1);
        btn_procesando_tabla(`inicio_produc${item}`, obj_inicial, 1);
    });
    $("#ProduccionModal").on('hidden.bs.modal', function () {
        $('#num_troquel').val('');
        $('#observacion_op').val('');
        $('#cant_etiq').val('');
        if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
            $('#id_persona').val(0);
        }
    });
}

var consulta_operario = function () {
    $('.codigo_operario').on('change', function () {
        var documento = $(this).val();
        $.ajax({
            url: `${PATH_NAME}/produccion/validar_operario`,
            type: "POST",
            data: { documento },
            success: function (respu) {
                if (respu != '') {
                    var items = `<h4> Nombre :
                                    <span style="color: blue">${respu[0].nombres} ${respu[0].apellidos}</span>
                                </h4>
                                `;
                    var atributo = false;
                    var id_persona = respu[0].id_persona;
                } else {
                    var items = `<h4>
                    <span style="color: red">El código del operario es incorrecto !!</span>
                    </h4>`;
                    var atributo = true;
                    var id_persona = 0;
                }
                $('.respu_consulta').empty().html(items);
                $('.boton_codigo_operario').prop('disabled', atributo);
                $('.id_persona').val(id_persona);
                $('#id_persona').val(id_persona);
            }
        });
    });
}

var activa_puesta_punto = function (data, usuario) {
    var estado_item_producir = 7;
    var id_actividad_area = 11;
    $.ajax({
        url: `${PATH_NAME}/produccion/ejecuta_puesta_punto_pro_embo`,
        type: "POST",
        data: { data, usuario, estado_item_producir, id_actividad_area },
        success: function (res) {
            pestana_activa();
            carga_funcion();
        }
    });

}

var consulta_metros_lineales = function (id_item_producir) {
    $.ajax({
        url: `${PATH_NAME}/produccion/consultar_metros_lineales_op`,
        type: 'POST',
        data: { id_item_producir },
        success: function (res) {
            $("#tabla_datos_metros_lineales").DataTable({
                "data": res,
                "columns": [
                    { "data": "codigo_material" },
                    { "data": "ancho" },
                    {
                        "data": "ml", render: function (data, type, row) {
                            return parseFloat(row.metros_lineales_dispo) - parseFloat(row.suma_ml);
                        }
                    },
                    {
                        "data": "boton1", render: function (data, type, row) {
                            var suma = parseFloat(row.metros_lineales_dispo) - parseFloat(row.suma_ml);
                            if (suma == 0) {
                                var respu = 'Material ya';
                            } else {
                                var respu = `
                            Si <input type="radio" data-radio="1" name="material${row.id_metros_lineales}" value="${row.id_metros_lineales}" class="agregar_metros_lineales_radio">
                            No <input type="radio" data-radio="0" name="material${row.id_metros_lineales}" value="${row.id_metros_lineales}" class="agregar_metros_lineales_radio">
                            `;
                            }
                            return respu;
                        }
                    },
                    {
                        "data": "boton2", render: function (data, type, row) {
                            var suma = parseFloat(row.metros_lineales_dispo) - parseFloat(row.suma_ml);
                            if (suma == 0) {
                                var respu = 'Utilizado';
                            } else {
                                var respu = `<input class="form-control agregar_metros_lineales" id="uso${row.id_metros_lineales}" data-id="${row.ancho}" type="text" disabled="true" >`;
                            }
                            return respu;
                        }
                    }
                ],
            });
            agregar_metros_lineales_radio();

            // estado_inicio_produccion = 11;
        }
    });
}

var agregar_metros_lineales_radio = function () {//OK
    $('.agregar_metros_lineales_radio').on('click', function () {
        var id = $(this).val();
        var estado_radio = $(this).attr('data-radio');//data radio
        if (estado_radio == 0) {
            $(`#uso${id}`).attr('disabled', false);
            $(`#uso${id}`).focus();
        } else {
            $(`#uso${id}`).attr('disabled', true);
            $(`#uso${id}`).val('');
        }
        $('#grabar_produccion').attr('disabled', false);
    });
}

var validar_operario = function () {
    $('#grabar_codigo_operario').on('click', function () {
        datos = $('#codigo_operario').val();// No se esta usando para el envio se usa para la consulta
        id_persona = $('#id_persona').val();
        data = JSON.parse($('#data_boton').val());
        var ejecuta_funcion = $('#boton_ejecuta').val();
        if (ejecuta_funcion == 1) {
            activa_puesta_punto(data, id_persona);
        }
        if (ejecuta_funcion == 2) {
            if (data.estado_item_producir == 15) {
                activa_inicio_embobinado(data, id_persona);
            } else {
                activa_inicio_produccion(data, id_persona);
            }
        }
        $('#OperarioModal').modal('toggle');
    });
}

var boton_inicio_produccion = function () {
    $('.inicio_produccion').on('click', function () {
        $('.respu_consulta').empty().html();
        var maquina = $(this).attr('inicio-produc');
        var data = $(`#tabla_maquina_produccion${maquina}`).DataTable().row($(this).parents("tr")).data(); //capturar valores de la fila seleccionada
        obj_inicial = $(`#inicio_produc${data.id_item_producir}`).html();
        item = data.id_item_producir;
        btn_procesando_tabla(`inicio_produc${data.id_item_producir}`);
        if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
            $('.respu_consulta').empty().html();
            $('#codigo_operario').val('');
            $('#obj_inicial').val('');
            $('#OperarioModal').modal('show');
            $('#data_boton').val(JSON.stringify(data));
            $('#boton_ejecuta').val(2);
        } else {
            if (data.estado_item_producir == 15) {
                activa_inicio_embobinado(data, id_persona_sesion);
            } else {
                activa_inicio_produccion(data, id_persona_sesion);
            }
        }
    });
}

var activa_inicio_produccion = function (data, usuario) {
    var estado_item_producir = 9;
    var id_actividad_area = 48;
    $.ajax({
        url: `${PATH_NAME}/produccion/ejecuta_puesta_punto_pro_embo`,
        type: "POST",
        data: { data, usuario, estado_item_producir, id_actividad_area },
        success: function (res) {
            pestana_activa();
            carga_funcion();
        }
    });
}

var activa_inicio_embobinado = function (data, id_persona_sesion) {
    $.ajax({
        url: `${PATH_NAME}/produccion/inicio_embo_dk`,
        type: "POST",
        data: { data, id_persona_sesion },
        success: function (res) {
            pestana_activa();
            carga_funcion();
        }
    });
}

var boton_regresar = function () {
    $('#regresar').on('click', function (e) {
        $('#principal').css('display', '');
        $('#item_embobinado').css('display', 'none');
    });
}

var consulta_reporte = function (tbody, table) {
    $('.reportar_trabajo').on('click', function () {
        var dato = $(this).attr('data-reporta');
        var data_row = $(`#tabla_maquina_produccion${dato}`).DataTable().row($(this).parents("tr")).data(); //capturar valores de la fila seleccionada
        var id_maquina = 0;
        var boton_oprimido = `ver-pro${data_row.id_item_producir}`;
        if (data_row.estado_item_producir == 16) {
            boton_oprimido = `ver-embo${data_row.id_item_producir}`;
            id_maquina = data_row.id_maquina_embo;
        }
        obj_inicial = $(`#${boton_oprimido}`).html();
        btn_procesando_tabla(`${boton_oprimido}`);
        var num_produccion = data_row.num_produccion;
        $.ajax({
            url: `${PATH_NAME}/produccion/consulta_items_pro_embo`,
            type: "POST",
            data: { num_produccion, id_maquina },
            success: function (res) {
                $('#principal').css('display', 'none');
                $('#item_embobinado').css('display', '');
                btn_procesando_tabla(`${boton_oprimido}`, obj_inicial, 1);
                tabla_items_embo_dk(res, data_row);
            }
        });
    });
}

var tabla_items_embo_dk = function (res, data_row) {
    var items = 0;
    var cant_op = 0;
    var q_reportada = 0;
    res.forEach(element => {
        items = items + 1;
        q_reportada = parseInt(q_reportada) + parseInt(element.q_etiq_reportadas);
        cant_op = parseInt(cant_op) + parseInt(element.cant_op);
    });
    $('#cant_orden').empty().html($.fn.dataTable.render.number('.', ',', 0, '').display(cant_op));
    $('#cant_reportada').empty().html($.fn.dataTable.render.number('.', ',', 0, '').display(q_reportada));
    $('#num_op').val(data_row.num_produccion);
    $('#num_orden').empty().html(data_row.num_produccion);
    $('#items_orden').empty().html(items);
    $('#nombre_maquina').empty().html(data_row.nombre_maquina);
    $('#reportes_embobinado').val(JSON.stringify(data_row));
    $('#tabla_embobinado').DataTable({
        "data": res,
        "columnDefs": [
            { className: 'text-center', targets: [0, 1, 2, 3, 4] }
        ],
        "columns": [
            {
                "data": "pedido_item", render: function (data, type, row) {
                    return `${row.num_pedido}-${row.item}`;
                }
            },
            { "data": "codigo" },
            { "data": "descripcion_productos" },
            { "data": "cant_op", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "salida_bodega", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "q_etiq_reportadas", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "nombre_core" },
            { "data": "cant_x", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            {
                "data": "ml_rollo", render: function (data, type, row) {
                    var etiq_por_avance = ((row.cant_x) * parseFloat(row.avance));
                    var eti_cav = etiq_por_avance / parseInt(row.cav_cliente);
                    var ml_item = eti_cav / 1000;
                    return $.fn.dataTable.render.number('.', ',', 2, '').display(ml_item);
                }
            },
            { "data": "nombre_r_embobinado" },
            {
                "data": "boton", render: function (data, type, row) {
                    var respu = '';
                    if (row.id_estado_item_pedido == 21 || row.id_estado_item_pedido == 10) {
                        respu = `<div class="select_acob text-center">
                                    <input type="checkbox" name="pedido_item${row.id_pedido_item}" value="${row.id_pedido_item}" data-estado="20" />
                                </div>`;
                    }
                    return respu;
                },
                "className": "text-center"
            },
            {
                "data": "boton1", render: function (data, type, row) {
                    var respu = '';
                    if (row.id_estado_item_pedido == 20) {
                        respu = `<button class='btn btn-info btn-circle imprimir_trasavilidad' data-id-m='${data_row.maquina}' data-m='${row.nombre_maquina}' data-item='${JSON.stringify(row)}'  data-toggle='modal' data-target='.modalImpresion' title='Imprimir Etiqueta Remarcación'><i class='fa fa-print'></i> </button>`;
                    }
                    return respu;
                },
                "className": "text-center"
            },
            {
                "data": "boton2", render: function (data, type, row) {
                    var respu = '';
                    if (row.id_estado_item_pedido == 20) {
                        respu = `<div class="select_acob text-center">
                                    <input type="radio" name="pedido_item${row.id_pedido_item}" value="${row.id_pedido_item}" data-estado="1" />
                                </div>`;
                    }
                    return respu;
                },
                "className": "text-center"
            },
            {
                "data": "boton3", render: function (data, type, row) {
                    var respu = '';
                    if (row.id_estado_item_pedido == 20) {
                        respu = `<div class="select_acob text-center">
                                    <input type="radio" name="pedido_item${row.id_pedido_item}" value="${row.id_pedido_item}" data-estado="2" />
                                </div>`;
                    }
                    return respu;
                },
                "className": "text-center"
            },
        ],
    });
}

var inicio_trabajo_item = function () {
    $('#reportes_embobinado').on('click', function (e) {
        var data_maquina = JSON.parse($(this).val());
        var data = $("#tabla_embobinado").DataTable().rows().data();
        var dato_tabla = $("#tabla_embobinado").DataTable().rows().nodes();
        var mensaje = '';
        var data_envio = [];
        var data_reporte = [];
        $.each(dato_tabla, function (index, value) {
            var p = $(this).find('input').val();
            var estado_item = $(this).find('input').attr('data-estado');
            var estado_radio = RadioElegidoAttr(`pedido_item${p}`, 'data-estado');
            if (data[index].id_estado_item_pedido == '20' && estado_radio == 'ninguno') {
                mensaje = 'Se requiere finalizar un trabajo para iniciar uno nuevo.';
                return;
            }
            if (estado_radio == 'ninguno') {
            } else {
                if (estado_radio == 1 || estado_radio == 2) {
                    data[index].estado_envio = estado_radio;
                    data_reporte.push(data[index]);
                } else {
                    if (data_envio == "") {
                        data[index].estado_cambio_item_pedido = estado_item;
                        data_envio.push(data[index]);
                    } else {
                        mensaje = 'Solo se puede iniciar un trabajo a la vez.';
                        return;
                    }
                }
            }
        });
        if (mensaje == '' && data_envio == '' && data_reporte == '') {
            mensaje = 'Se debe elegir un item para poder continuar.';
            alertify.error(mensaje);
            return;
        }
        if (mensaje != '' && data_envio == '' && data_reporte == '') {
            mensaje = 'Se debe elegir un item para poder continuar.';
            alertify.error(mensaje);
            return;
        }
        if (mensaje != '' && data_envio != '') {
            alertify.error(mensaje);
            return;
        }
        if (data_reporte != '') {
            if (data_reporte[0].id_maqui_embo == '' || data_reporte[0].id_maqui_embo == null) {
                $('#ProduccionModal').modal('toggle');
                $('.respu_consulta').empty().html();
                $('#codigo_operario_cierre').val('');
                // $('#ProduccionModalLabel').empty().html(`Cierre TOTAL Orden De Producción: <span style="color:darkred">${data.num_produccion}</span> | ${data.nombre_maquina}`);
                if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
                    $('#requiere_operario').css('display', '');
                } else {
                    $('#requiere_operario').css('display', 'none');
                }
                if (data_reporte[0].estado_envio == '1') {
                    $('#detencion').css('display', '');
                } else {
                    $('#detencion').css('display', 'none');
                }
                $('#data_items').val(JSON.stringify(data_reporte));
                $('#data_row').val(JSON.stringify(data_maquina));
                $('#data_envio').val(JSON.stringify(data_envio));
                consulta_metros_lineales(data_maquina.id_item_producir);
            } else {
                // alert('DatosItemPModal');
                $('#DatosItemPModal').modal('toggle');
                $('.respu_consulta').empty().html();
                $('#reporte_etiquetas').val('');
                if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
                    $('#reporte_etiquetas').css('display', '');
                } else {
                    $('#reporte_etiquetas').css('display', 'none');
                }
                reporte_cantidades(data_envio, data_reporte, data_maquina);

            }
        } else {
            if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
                $('#reporte_operario').val('');
                $('.respu_consulta').empty().html();
                // $("#ReporteItemsModal").modal("show");
                $('#ReporteItemsModal').modal('toggle');
                $('#data_row').val(JSON.stringify(data_envio));
                $('#grabar_reporte_operario').val(JSON.stringify(data_maquina));
            } else {
                iniciar_item_dk(data_envio, id_persona_sesion, data_maquina);
            }
        }
    });
}

var datos_inicio_dk = function () {
    $('#grabar_reporte_operario').on('click', function () {
        var data_envio = JSON.parse($('#data_row').val());
        var data_maquina = JSON.parse($(this).val());
        var id_persona = $('#id_persona').val();
        iniciar_item_dk(data_envio, id_persona, data_maquina);
    });
}

var iniciar_item_dk = function (envio, id_persona_sesion, data_maquina) {
    $.ajax({
        url: `${PATH_NAME}/produccion/inicio_pro_embo_item`,
        type: "POST",
        data: { envio, id_persona_sesion },
        success: function (res) {
            tabla_items_embo_dk(res, data_maquina);
            if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
                $('#ReporteItemsModal').modal('toggle');
            }
        }
    });
}

var grabar_produccion_pro_embo = function () {
    $('#grabar_produccion').on('click', function () {
        var data_items = JSON.parse($('#data_items').val());
        var data_row = JSON.parse($('#data_row').val());
        var data_envio = JSON.parse($('#data_envio').val());
        var operario =  id_persona_sesion;
        if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
            operario = $('#id_persona').val();
            if (operario == 0) {
                alertify.error('se requiere el codigo del operario para continuar');
                $('#codigo_operario_cierre').focus();
                return;
            }
        }
        var num_troquel = $('#num_troquel').val();
        if (num_troquel == '' || num_troquel == 0) {
            alertify.error('se requiere numero del troquel utilizado para continuar.');
            $('#num_troquel').focus();
            return;
        }
        var detencion = '';
        if (data_items[0].estado_envio == '1') {
            if ($('#observacion_op').val() == '' || $('#observacion_op').val() == 0) {
                alertify.error('se requiere motivo detención para continuar');
                $('#observacion_op').select2('open');
                return;
            }
            detencion = $('#observacion_op').val();
        }
        var cant_etiquetas = $('#cant_etiq').val();
        if (cant_etiquetas == '' || cant_etiquetas == 0) {
            alertify.error('se requiere la cantidad etiquetas para continuar.');
            $('#cant_etiq').focus();
            return;
        }
        var data_ml_usados = [];
        // Traigo la data de la table creada
        var data = $("#tabla_datos_metros_lineales").DataTable().rows().data();
        var id_item_producir = data[0].id_item_producir;
        // Recorro la tabla validando que se diligencie en su totalidad
        var dato_tabla = $("#tabla_datos_metros_lineales").DataTable().rows().nodes();
        var mensaje = '';
        $.each(dato_tabla, function (index, value) {
            var p = $(this).find('input').val();
            var estado_radio = RadioElegidoAttr(`material${p}`, 'data-radio');
            var codigo_material = '';
            if (estado_radio == 'ninguno') {
            } else {
                var dato_ml_usados = $(this).find('td').eq(2).html();
                if (estado_radio == 0) {
                    dato_ml_usados = $(`#uso${p}`).val();
                    if (dato_ml_usados == 0 || dato_ml_usados == '') {
                        mensaje = 'Se requiere los metros lineales usados';
                        $(`#uso${p}`).focus();
                        return;
                    }
                }
                codigo_material = $(this).find('td').eq(0).html();
                ancho = $(this).find('td').eq(1).html();
                data_carga = {
                    'codigo_material': codigo_material,
                    'ancho': ancho,
                    'ml': dato_ml_usados,
                };
                data_ml_usados.push(data_carga);
            }
        });
        if (mensaje == '' && data_ml_usados == '') {
            mensaje = 'Se debe elegir el material utilizado.';
            alertify.error(mensaje);
            return;
        }
        if (mensaje != '') {
            alertify.error(mensaje);
            return;
        }
        // Agregar los datos adicionales a la variable de item
        data_items[0].ml_usados = data_ml_usados;
        data_items[0].operario = operario;
        data_items[0].num_troquel = num_troquel;
        data_items[0].detencion = detencion;
        data_items[0].cant_etiquetas = cant_etiquetas;
        // Envio los datos para realizar el cambio del estado y reporte de seguimiento en las tablas necesarias
        $.ajax({
            url: `${PATH_NAME}/produccion/reporte_item_pro_embo_dk`,
            type: "POST",
            data: { data_items, data_row, data_envio },
            success: function (res) {
                $('#ProduccionModal').modal('toggle');
                if (res.status == 1) {
                    $('#regresar').click();
                    pestana_activa();
                    carga_funcion();
                } else {
                    tabla_items_embo_dk(res.respu, data_row);
                }
            }
        });
    });
}

var reporte_cantidades = function (data_envio, data_reporte, data_maquina) {
    var n_produccion = data_reporte[0].n_produccion;
    $.ajax({
        url: `${PATH_NAME}/produccion/personas_troquelado`,
        type: "POST",
        data: { n_produccion },
        success: function (res) {
            $('.operario_reporte').css('display', 'none');
            $('#grabar_reporte_etiquetas').attr('disabled', false);
            if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
                $('.operario_reporte').css('display', '');
                $('#grabar_reporte_etiquetas').attr('disabled', true);
            }
            $('#grabar_reporte_etiquetas').attr('data-envio', JSON.stringify(data_envio));
            $('#grabar_reporte_etiquetas').attr('data-reporte', JSON.stringify(data_reporte));
            $('#grabar_reporte_etiquetas').attr('data-maquina', JSON.stringify(data_maquina));

            var table = $('#tabla_troqueladores').DataTable({
                "data": res,
                "columns": [
                    {
                        "data": "pedido_item", render: function (data, type, row) {
                            return `${row.nombres} ${row.apellidos}`;
                        }
                    },
                    { "data": "ml_usados" },
                    {
                        "data": "pedido_item", render: function (data, type, row) {
                            return `<input type="text" class="form-control" id="ml_procesados${row.id_persona}" data-id="${row.id_persona}" />`;
                        }
                    },
                    {
                        "data": "pedido_item", render: function (data, type, row) {
                            return `<input type="text" class="form-control" id="etiquetas${row.id_persona}" data-id="${row.id_persona}" />`;
                        }
                    },
                ]
            });
        }
    });
}

var grabar_reporte_etiquetas = function () {
    $('#grabar_reporte_etiquetas').on('click', function () {
        var data_envio = JSON.parse($(this).attr('data-envio'));
        var data_reporte = JSON.parse($(this).attr('data-reporte'));
        var data_maquina = JSON.parse($(this).attr('data-maquina'));
        if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
            if ($('#id_persona').val() == 0 || $('#reporte_etiquetas').val() == '' || $('#reporte_etiquetas').val() == 0) {
                alertify.error('Se Requiere el usuario para continuar');
                return;
            }
            id_persona_sesion = $('#id_persona').val();
        }
        var data = $("#tabla_troqueladores").DataTable().rows().data();
        var dato_tabla = $("#tabla_troqueladores").DataTable().rows().nodes();
        var mensaje = "";
        var envio_troquelador = [];
        $.each(dato_tabla, function (index, value) {
            var p = $(this).find('input').attr('data-id');
            var ml_procesados = $(`#ml_procesados${p}`).val();
            var etiquetas = $(`#etiquetas${p}`).val();
            if ((ml_procesados == '' && etiquetas == '') || (ml_procesados == 0 && etiquetas == 0)) {
                mensaje = 'Se requiere los campos de ml procesados y cantidad etiquetas.';
                return;
            } else {
                var carga_datos = {
                    'ml_procesados': ml_procesados,
                    'etiquetas': etiquetas,
                    'id_persona': data[index].id_persona
                }
                envio_troquelador.push(carga_datos);
            }
        });
        if (mensaje != '' && envio_troquelador == '') {
            alertify.error(mensaje);
            return;
        }
        var obj_inicial = $('#grabar_reporte_etiquetas').html();
        btn_procesando('grabar_reporte_etiquetas');
        data_reporte[0].datos_etiquetas_embo = envio_troquelador;
        reportar_embobinado_etiquetas(data_envio, data_reporte, id_persona_sesion, data_maquina, obj_inicial);
    });
}

var reportar_embobinado_etiquetas = function (envio, reporte, id_persona_sesion, data_maquina, obj_inicial) {
    $.ajax({
        url: `${PATH_NAME}/produccion/reportar_embobinado_etiquetas_dk`,
        type: "POST",
        data: { envio, reporte, id_persona_sesion, data_maquina },
        success: function (res) {
            if (res.status == 1) {
                $('#regresar').click();
                pestana_activa();
                carga_funcion();
            } else {
                tabla_items_embo_dk(res.respu, data_maquina);
            }
            $('#DatosItemPModal').modal('toggle');
            $('.codigo_operario').val('');
            $('.respu_consulta').empty().html('');
            btn_procesando('grabar_reporte_etiquetas', obj_inicial, 1);
        }
    });
}

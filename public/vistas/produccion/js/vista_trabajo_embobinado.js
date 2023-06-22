$(document).ready(function () {
    trabajo_maquinas();
    select_2();
    $(".datepicker").datepicker();
    consulta_operario();
    validar_operario();
    alertify.set('notifier', 'position', 'bottom-left');
    boton_regresar();
    inicio_embobinado_item();
    cerrar_modal();
    datos_faltantes();
    grabar_reporte_etiquetas();
    consultar_turnos();
    generar_cambio_maquina_embo();
});

var datos_consulta = $('#datos_consulta').val();
var q_maquinas = JSON.parse($('#q_maquinas').val());
var id_persona_sesion = $('#id_persona_sesion').val();
var id_usuario_sesion = $('#id_usuario_sesion').val();
var estados_activos = ['4', '5', '6', '7', '10'];//,8,9];
var obj_inicial;
var item;

var cerrar_modal = function () {
    $("#OperarioModal").on('hidden.bs.modal', function () {
        btn_procesando_tabla(`inicio_embo${item}`, obj_inicial, 1);
    });
    $("#ReporteItemsModal").on('hidden.bs.modal', function () {
        $('.codigo_operario').val('');
        $('.respu_consulta').empty().html('');
    });
    $("#DatosItemPModal").on('hidden.bs.modal', function () {
        $('.codigo_operario').val('');
        $('.respu_consulta').empty().html('');
    });
}

var carga_funcion = function () {
    consulta_seguimiento();
    boton_inicio_produccion(); //Se carga la funcion por primera vez despues de que se crean las tablas
    reporte_items();
    reasignar();
}

var trabajo_maquinas = function () {
    $.ajax({
        url: `${PATH_NAME}/produccion/consultar_trabajo_embobinado`,
        type: 'GET',
        success: function (res) {
            q_maquinas.forEach(element => {
                carga_tablas(res, element.id_maquina);
            });
            carga_funcion();
        }
    });
}

var carga_tablas = function (data, maquina) {
    var data_tabla = [];
    data.forEach(element => {
        if (element.maquina == maquina) {
            data_tabla.push(element);
        }
    });
    var table = $(`#tabla_maquina_embobinado${maquina}`).DataTable({
        "data": data_tabla,
        "order": [
            [1, "desc"],
            // [3, "asc"],
        ],
        "columnDefs": [{
            "orderable": false,
            "targets": [0, 2, 3, 4, 5, 6]
        }],
        "columns": [
            { "data": "fecha_comp" },
            { "data": "fecha_embobinado" },
            { "data": "num_produccion" },
            { "data": "turno_maquina" },
            { "data": "tamanio_etiq" },
            { "data": "nombre_estado" },
            {
                "data": "opcion", render: function (data, type, row) {
                    botones = ``;
                    if (row['estado_item_producir'] == 12) {//pasar a inicio embobinado
                        botones += `<button class="btn btn-info ver_op" data-ver="${row.maquina}" title="Ver O.P." >
                                <i class="fa fa-search"></i>
                            </button> `;
                        if (datos_consulta == 1) {
                            botones += `<button class="btn btn-warning reasignar" data-id="${row.maquina}" title="Reasignar M.Q" >
                                        <i class="fa fa-retweet"></i>
                                    </button> `;
                        }
                        botones += `<button class="btn btn-success inicio_produccion" id="inicio_embo${row.id_maquina}" inicio-embo="${row.maquina}" title="Inicio Producción">
                                <i class="fa fa-check" ></i>
                            </button>
                        `;
                    } else {
                        botones += `<button class="btn btn-info reporte_items" id="ver_embo${row.id_maquina}" data-reporte="${row.maquina}" title = "Ver Item producción" >
                            <i class="fa fa-search"></i>
                            </button > `;
                    }
                    return botones;
                }, "className": "text-center"
            }
        ],
    });

}

var reasignar = function () {
    $('.reasignar').on('click', function () {
        var dato = $(this).attr('data-id');
        var data = $(`#tabla_maquina_embobinado${dato} `).DataTable().row($(this).parents("tr")).data(); //capturar valores de la fila seleccionada
        $('#CambioMaquinaEmboModal').modal('show');
        $('#num_produccion_data').empty().html(data.num_produccion);
        $('#turno_embobinado').val(data.turno_maquina);
        $('#fecha_embobinado_data').val(data.fecha_embobinado);
        $('#maquina_embobinado').val(data.maquina).trigger('change');
        $('#generar_cambio_maquina_embo').attr('data-id', JSON.stringify(data));
    });
}

var consultar_turnos = function () {
    $('.turno_maquina').change(function () {
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
            'id_maquina': data.id_maquina
        }
        $.ajax({
            url: `${PATH_NAME}/produccion/cambiar_maquina_embo`,
            type: "POST",
            data: datos,
            success: function (res) {
                q_maquinas.forEach(element => {
                    carga_tablas(res, element.id_maquina);
                });
                carga_funcion();
                $('#CambioMaquinaEmboModal').modal('toggle');
            }
        });
    });
}

function format(d) {
    var respu = `
    < div class="modal-header" >
        <h3 class="modal-title text-danger" style="text-align: center;" id="exampleModalLabel">ITEMS : <span style="color: #3932a9"></span></h3>
	</div >
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
    $('.ver_op').on('click', function () {
        var dato = $(this).attr('data-ver');
        var data_row = $(`#tabla_maquina_embobinado${dato}`).DataTable().row($(this).parents("tr")).data(); //capturar valores de la fila seleccionada
        var tr = $(this).closest('tr');
        var row = $(`#tabla_maquina_embobinado${dato}`).DataTable().row(tr);
        var idx = $.inArray(tr.attr('id'), detailRows);
        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('details');
            detailRows.splice(idx, 1);
        }
        else {
            row.child(format(data_row.id_maquina)).show(); // Carga el encabezado de la tabla para poder recibir los datos 
            tr.addClass('details');
            var num_produccion = data_row.num_produccion;
            var tabla_2 = $(`#tabla-item-op${data_row.id_maquina}`).DataTable({
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
                ]
            });
            if (idx === -1) {
                detailRows.push(tr.attr('id'));
            }
        }
    });
}

var boton_inicio_produccion = function () {
    $('.inicio_produccion').on('click', function () {
        $('.respu_consulta').empty().html();
        var maquina = $(this).attr('inicio-embo');
        var data = $(`#tabla_maquina_embobinado${maquina}`).DataTable().row($(this).parents("tr")).data(); //capturar valores de la fila seleccionada
        obj_inicial = $(`#inicio_embo${data.id_maquina}`).html();
        item = data.id_maquina;
        btn_procesando_tabla(`inicio_embo${data.id_maquina}`);
        if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
            $('.respu_consulta').empty().html();
            $('#codigo_operario').val('');
            $('#obj_inicial').val('');
            $('#OperarioModal').modal('show');
            $('#data_boton').val(JSON.stringify(data));
            $('#boton_ejecuta').val(2);
        } else {
            activa_inicio_produccion(data, id_persona_sesion);
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
                $('#id_persona').val(id_persona);
            }
        });
    });
}

var validar_operario = function () {
    $('#grabar_codigo_operario').on('click', function () {
        datos = $('#codigo_operario').val();// No se esta usando para el envio se usa para la consulta
        id_persona = $('#id_persona').val();
        data = JSON.parse($('#data_boton').val());
        var ejecuta_funcion = $('#boton_ejecuta').val();
        activa_inicio_produccion(data, id_persona);
        $('#OperarioModal').modal('toggle');
    });
}

var activa_inicio_produccion = function (data, usuario) {
    var estado_item_producir = 13;
    var id_actividad_area = 15;
    $.ajax({
        url: `${PATH_NAME}/produccion/ejecuta_inicio_embo`,
        type: "POST",
        data: { data, usuario, estado_item_producir, id_actividad_area },
        success: function (res) {
            q_maquinas.forEach(element => {
                carga_tablas(res, element.id_maquina);
            });
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

var reporte_items = function () {
    $('.reporte_items').on('click', function () {
        var maquina = $(this).attr('data-reporte');
        var data = $(`#tabla_maquina_embobinado${maquina}`).DataTable().row($(this).parents("tr")).data(); //capturar valores de la fila seleccionada
        obj_inicial = $(`#ver_embo${data.id_maquina}`).html();
        btn_procesando_tabla(`ver_embo${data.id_maquina}`);
        var num_produccion = data.num_produccion;
        var id_maquina = data.id_maquina;
        $.ajax({
            url: `${PATH_NAME}/produccion/datos_programacion_embobinado`,
            type: 'POST',
            data: { num_produccion, id_maquina },
            success: function (res) {
                btn_procesando_tabla(`ver_embo${data.id_maquina}`, obj_inicial, 1);
                $('#principal').css('display', 'none');
                $('#item_embobinado').css('display', '');
                tabla_items_embo(res, data);
            }
        });
    });
}

var tabla_items_embo = function (data, data_maquina) {
    var items = 0;
    var cant_op = 0;
    var q_reportada = 0;
    data.forEach(element => {
        items = items + 1;
        q_reportada = parseInt(q_reportada) + parseInt(element.q_etiq_reportadas);
        cant_op = parseInt(cant_op) + parseInt(element.cant_op);
    });
    $('#cant_orden').empty().html($.fn.dataTable.render.number('.', ',', 0, '').display(cant_op));
    $('#cant_reportada').empty().html($.fn.dataTable.render.number('.', ',', 0, '').display(q_reportada));
    $('#num_op').val(data_maquina.num_produccion);
    $('#num_orden').empty().html(data_maquina.num_produccion);
    $('#items_orden').empty().html(items);
    $('#nombre_maquina').empty().html(data_maquina.nombre_maquina);
    $('#reportes_embobinado').val(JSON.stringify(data_maquina));
    $('#tabla_embobinado').DataTable({
        "data": data,
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
                    if (row.id_estado_item_pedido == 21) {
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
                        respu = `<button class='btn btn-info btn-circle imprimir_trasavilidad' data-m='${row.nombre_maquina}' data-item='${JSON.stringify(row)}'  data-toggle='modal' data-target='.modalImpresion' title='Imprimir Etiqueta Remarcación'><i class='fa fa-print'></i> </button>`;
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

var inicio_embobinado_item = function () {
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
            $('#DatosItemPModal').modal('toggle');
            reporte_cantidades(data_envio, data_reporte, data_maquina);
        } else {
            if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
                $('#reporte_operario').val('');
                $('.respu_consulta').empty().html();
                // $("#ReporteItemsModal").modal("show");
                $('#ReporteItemsModal').modal('toggle');
                $('#data_row').val(JSON.stringify(data_envio));
                $('#grabar_reporte_operario').val(JSON.stringify(data_maquina));
            } else {
                reporta_embobinado(data_envio, id_persona_sesion, data_maquina);
            }
        }
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
        reportar_embobinado_etiquetas(data_envio, data_reporte, id_persona_sesion, data_maquina,obj_inicial);
    });
}

var reportar_embobinado_etiquetas = function (envio, reporte, id_persona_sesion, data_maquina) {
    $.ajax({
        url: `${PATH_NAME}/produccion/reportar_embobinado_etiquetas`,
        type: "POST",
        data: { envio, reporte, id_persona_sesion, data_maquina },
        success: function (res) {
            if (res.status == 1) {
                $('#regresar').click();
                q_maquinas.forEach(element => {
                    carga_tablas(res.respu, element.id_maquina);
                });
                carga_funcion();
            } else {
                tabla_items_embo(res.respu, data_maquina);
            }
            $('#DatosItemPModal').modal('toggle');
            $('.codigo_operario').val('');
            $('.respu_consulta').empty().html('');
            btn_procesando('grabar_reporte_etiquetas', obj_inicial, 1);
        }
    });
}

var datos_faltantes = function () {
    $('#grabar_reporte_operario').on('click', function () {
        var data_envio = JSON.parse($('#data_row').val());
        var data_maquina = JSON.parse($(this).val());
        var id_persona = $('#id_persona').val();
        reporta_embobinado(data_envio, id_persona, data_maquina);
    });
}

var reporta_embobinado = function (envio, id_persona_sesion, data_maquina) {
    $.ajax({
        url: `${PATH_NAME}/produccion/inicio_embo_item`,
        type: "POST",
        data: { envio, id_persona_sesion },
        success: function (res) {
            console.log(res);
            tabla_items_embo(res, data_maquina);
            if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
                $('#ReporteItemsModal').modal('toggle');
            }
        }
    });
}



$(document).ready(function () {
    icono_impresion();//boton de area de trabajo
    pestana_activa();
    select_2();
    $(".datepicker").datepicker();
    consultar_turnos();
    generar_cambio_maquina();
    alertify.set('notifier', 'position', 'bottom-left');
    consulta_operario();
    validar_operario();
    cerrar_modal();
    envio_datos();
    cierre_items();
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
    pro_completa();
}

var datos_consulta = $('#datos_consulta').val();
var q_maquinas = JSON.parse($('#q_maquinas').val());
var id_persona_sesion = $('#id_persona_sesion').val();
var id_usuario_sesion = $('#id_usuario_sesion').val();
var estados_activos = ['4', '5', '6', '7', '10'];//,8,9];

var trabajo_maquinas = function (id_maquina) {
    $.ajax({
        url: `${PATH_NAME}/produccion/consultar_trabajo_maquinas?id_maquina=${id_maquina}`,
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
                    botones = `<button class="btn btn-info ver_op" data-ver="${row.id_maquina}" data-fecha="${row.fecha_produccion}" title="Ver O.P." ><i class="fa fa-search"></i></button> `;
                    if (datos_consulta == 1 && estados_activos.indexOf(row['estado_item_producir'])) { //row['estado_item_producir'] != 9) {
                        botones += `<button class="btn btn-warning reasignar" data-id="${row.id_maquina}" title="Reasignar M.Q" ><i class="fa fa-retweet"></i></button> `;
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
                            botones += `<button class="btn btn-secondary inicio_produccion" id="inicio_produc${row.id_item_producir}" inicio-produc="${row.id_maquina}" title="Inicio Producción"><i class="fa fa-check" ></i></button> `;
                        }
                        if (row['estado_item_producir'] == 9) {//pasar a inicio producción
                            botones += `<button class="btn btn-success pro_completa" id="produc_comp${row.id_item_producir}" produc-comp="${row.id_maquina}" title="Completar"><i class="fa fa-check"></i></button>`;
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
        var fecha = $(this).data('fecha');
        var data_row = $(`#tabla_maquina_produccion${dato}`).DataTable().row($(this).parents("tr")).data(); //capturar valores de la fila seleccionada
        var data_row1 = JSON.stringify(data_row)
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
                            boton = '';
                            if (data_row.estado_item_producir == 9 && row.id_estado_item_pedido == 10 && fecha == FECHA_HOY) {// se agrega fecha hoy para que solo se pueda cerrar el item de lo que esta programado 
                                boton += `<button class="m-1 btn btn-primary btn-circle cambiar_estado_item" data-item="${row.id_pedido_item}" data-op='${JSON.stringify(data_row)}' title="Listo"><i class="fa fa-clipboard-check"></i></button>
                                <button class="m-1 btn btn-info btn-circle imprimir_eti" title="Imprimir"><i class="fas fa-print"></i></button>`;
                            }
                            boton += `<button class="btn btn-success ver_ficha" data_produ="${row.codigo}" title="Ficha Tecnica"><i class="fas fa-eye"></i></button>`;
                            return boton;
                        }
                    }
                ]
            });
            if (idx === -1) {
                detailRows.push(tr.attr('id'));
            }
            cambiar_estado_item(`#tabla-item-op${data_row.id_item_producir} tbody`, tabla_2);
            boton_imprimir(`#tabla-item-op${data_row.id_item_producir} tbody`, tabla_2);//regresar aqui imprimir
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

function cambiar_estado_item(tbody, table) {
    $(tbody).on('click', `tr button.cambiar_estado_item`, function (e) {
        e.preventDefault();
        var data = table.row($(this).parents("tr")).data();
        var data_op = $(this).data('op');
        var data_completa = { data_item: data, data_op: data_op };
        $('#grabar_produccion').attr('disabled', true);
        $('.respu_consulta').empty().html();
        $('.codigo_operario').val('');
        $('#ProduccionModal').modal('show');
        $('#ProduccionModalLabel').empty().html(`Cierre ITEM Orden De Producción: <span style="color:orange">${data.n_produccion}</span> | Pedido Item: ${data.num_pedido}-${data.item} `);
        $('#tabla_datos_metros_lineales').css('display', '')
        if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
            $('#requiere_operario').css('display', '');
        } else {
            $('#requiere_operario').css('display', 'none');
        }
        $('#data_row').val(JSON.stringify(data_completa));
        $('#troquel').css('display', '');
        $('#detencion').css('display', 'none');
        $('#radio_completo').css('display', 'flex');
        document.querySelector('#cierre_item1').checked = true;
        consulta_metros_lineales(data_op.id_item_producir);
        $('#boton_ejecuta').val(5);// creo que este boton es para enviar el ajax

    })
}

function cierre_items() {
    $('.cierre_item').change(function (e) {
        e.preventDefault();
        var cierre_item_tipo = $(this).val();
        if (cierre_item_tipo == 1) {// cierre completo
            $('#detencion').css('display', 'none');
        } else if (cierre_item_tipo == 2) {// cierre parcial
            $('#detencion').css('display', '');
        }
    });

}

var reasignar = function (tbody) {
    $(tbody).on('click', `tr button.reasignar`, function () {
        var dato = $(this).attr('data-id');
        var numeroFila = $(this).closest('tr').index();
        var rowData = $(`#tabla_maquina_produccion${dato}`).DataTable().row(0).data();
        var data = $(`#tabla_maquina_produccion${dato}`).DataTable().row($(this).parents("tr")).data(); //capturar valores de la fila seleccionada
        if (numeroFila == 0) {
            $('#div_motivo_cambio').removeClass('d-none');
        } else {
            $('#div_motivo_cambio').addClass('d-none');
        }
        $('#CambioMaquinaModal').modal('show');
        $('#num_produccion_data').empty().html(data.num_produccion);
        $('#turno_data').val(data.turno_maquina);
        $('#fecha_produccion_data').val(data.fecha_produccion);
        $('#maquina_data').val(data.id_maquina).trigger('change');
        $('#generar_cambio_maquina').attr('data-id', JSON.stringify(data));
        $('#motivo_cambio').attr('data-row0', JSON.stringify(rowData));
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

var generar_cambio_maquina = function () {
    $('#generar_cambio_maquina').on('click', function () {
        var fecha_produccion = $('#fecha_produccion_data').val();
        var id_maquina = $('#maquina_data').val();
        var turno = $('#turno_data').val();
        var motivo_cambio = $('#motivo_cambio').val();
        var data = JSON.parse($('#generar_cambio_maquina').attr('data-id'));
        var dataRow0 = JSON.parse($('#motivo_cambio').attr('data-row0'));
        if (data.fecha_produccion == fecha_produccion && data.id_maquina == id_maquina && data.turno_maquina == turno) {
            alertify.error('No realizo ningun cambio');
            $('#CambioMaquinaModal').modal('toggle');
            return;
        }
        if (data.num_produccion == dataRow0.num_produccion && motivo_cambio == '') {
            alertify.error('Lo sentimos, no puede cambiar de turno este ítem sin un motivo.');
            return;
        }
        if (data.motivo_cambio != '') {
            alertify.error('Lo sentimos, este ítem ya tiene un motivo y no puede ser movido de nuevo.');
            return;
        }
        if (turno <= dataRow0.turno_maquina && fecha_produccion == dataRow0.fecha_produccion) {
            alertify.error('Lo sentimos, la primera posición no se puede utilizar en el mismo día.');
            return;
        }
        var datos = {
            'fecha_produccion': fecha_produccion,
            'id_maquina': id_maquina,
            'turno': turno,
            'motivo_cambio': motivo_cambio,
            'num_produccion': data.num_produccion,
            'id_item_producir': data.id_item_producir
        }
        $.ajax({
            url: `${PATH_NAME}/produccion/cambiar_op_maquina`,
            type: "POST",
            data: datos,
            success: function (res) {
                pestana_activa();
                carga_funcion();
                $('#CambioMaquinaModal').modal('toggle');
                $('#motivo_cambio').val('');
            }
        });
    });
}

var obj_inicial;
var item;
var boton_puesta_punto = function () {
    $('.puesta_punto').on('click', function () {
        var maquina = $(this).attr('data-puesta');
        var numeroFila = $(this).closest('tr').index();
        if (numeroFila != 0) {
            alertify.error('Lo sentimos no puede iniciar un trabajo sin haber finalizado el anterior');
            return;
        }
        $('.respu_consulta').empty().html();
        var data = $(`#tabla_maquina_produccion${maquina}`).DataTable().row($(this).parents("tr")).data(); //capturar valores de la fila seleccionada
        obj_inicial = $(`#puesta_punto${data.id_item_producir}`).html();
        item = data.id_item_producir;
        btn_procesando_tabla(`puesta_punto${data.id_item_producir}`);
        if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
            $('.respu_consulta').empty().html();
            $('#codigo_operario').val('');
            $('#obj_inicial').val('');
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
        if (ejecuta_funcion == 1) {
            activa_puesta_punto(data, id_persona);
        }
        if (ejecuta_funcion == 2) {
            activa_inicio_produccion(data, id_persona);
        }
        $('#OperarioModal').modal('toggle');
    });
}

var activa_puesta_punto = function (data, usuario) {
    var estado_item_producir = 7;
    var id_actividad_area = 11;
    $.ajax({
        url: `${PATH_NAME}/produccion/ejecuta_puesta_punto`,
        type: "POST",
        data: { data, usuario, estado_item_producir, id_actividad_area },
        success: function (res) {
            pestana_activa();
            carga_funcion();
        }
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
            $('#num_troquel').val('');
            $('#OperarioModal').modal('show');
            $('#data_boton').val(JSON.stringify(data));
            $('#boton_ejecuta').val(2);
        } else {
            activa_inicio_produccion(data, id_persona_sesion);
        }
    });
}

var activa_inicio_produccion = function (data, usuario) {
    var estado_item_producir = 9;
    var id_actividad_area = 9;
    $.ajax({
        url: `${PATH_NAME}/produccion/ejecuta_puesta_punto`,
        type: "POST",
        data: { data, usuario, estado_item_producir, id_actividad_area },
        success: function (res) {
            pestana_activa();
            carga_funcion();
        }
    });
}


var pro_completa = function () {
    $('.pro_completa').on('click', function () {
        $('#grabar_produccion').attr('disabled', true);
        $('.respu_consulta').empty().html();
        $('.codigo_operario').val('');
        var maquina = $(this).attr('produc-comp');
        var data = $(`#tabla_maquina_produccion${maquina}`).DataTable().row($(this).parents("tr")).data(); //capturar valores de la fila seleccionada
        obj_inicial = $(`#produc_inco${data.id_item_producir}`).html();
        item = data.id_item_producir;
        btn_procesando_tabla(`produc_inco${data.id_item_producir}`);
        $('#ProduccionModal').modal('show');
        $('#ProduccionModalLabel').empty().html(`Cierre TOTAL Orden De Producción: <span style="color:darkred">${data.num_produccion}</span> | ${data.nombre_maquina}`);
        $('#tabla_datos_metros_lineales').css('display', '')
        if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
            $('#requiere_operario').css('display', '');
        } else {
            $('#requiere_operario').css('display', 'none');
        }
        $('#detencion').css('display', 'none');
        $('#troquel').css('display', '');
        $('#data_row').val(JSON.stringify(data));
        $('#radio_completo').css('display', 'none');
        document.querySelector('#cierre_item1').checked = true;
        consulta_metros_lineales(data.id_item_producir);
        $('#boton_ejecuta').val(4);
    });
}

// Esta es la funcion que debo odificar para que no saquen mas del material que hay cargado
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

var agregar_metros_lineales_radio = function () {
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



var envio_datos = function () {
    $('#grabar_produccion').on('click', function () {
        var valor = $('#boton_ejecuta').val();
        var tipo_cierre = $('.cierre_item:checked').val() // valor del cirre 1 = completo 2 = incompleto
        /*boton 5 tiene 2 casos 
        A-> SI ES 5 Y 1 cirrre completo del item 
        b-> SI ES 5 Y 2 cierre incompleto del item 
        c -> si es 4 CIERRE COMPLETO  */

        // ======================== Datos para todos los casos =====================
        var data_row = JSON.parse($('#data_row').val());
        var operario = id_persona_sesion;
        if (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13) {
            operario = $('#id_persona').val();
            if (operario == 0) {
                alertify.error('se requiere el codigo del operario para continuar');
                $('#codigo_operario_cierre').focus();
                return;
            }
        }
        var detencion = '';
        var parcial_total = 1;
        // ===========================================================================

        if (tipo_cierre == 2) { // cierre incompleto
            // =============== Solo se habilita para el 3 ================================
            if ($('#observacion_op').val() == '' || $('#observacion_op').val() == 0) {
                alertify.error('se requiere motivo detención para continuar');
                $('#observacion_op').focus();
                return;
            }
            parcial_total = 2;
            detencion = $('#observacion_op').val();

            // ===========================================================================
        }
        if (valor == 4 || valor == 5) {
            // ============== Se habilita el troquel en el 4 y 5 =========================
            var num_troquel = $('#num_troquel').val();
            if (num_troquel == '' || num_troquel == 0) {
                alertify.error('se requiere numero del troquel utilizado para continuar.');
                $('#num_troquel').focus();
                return;
            }
            // ===========================================================================
            // ================ Tabla de metros lineales solo para 4 y 5 =================
            var data_envio = [];
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
                    metros_lineales_existentes = $(this).find('td').eq(2).html();
                    if (estado_radio == 0) {
                        dato_ml_usados = $(`#uso${p}`).val();
                        if (dato_ml_usados == 0 || dato_ml_usados == '') {
                            mensaje = 'Se requiere los metros lineales usados';
                            $(`#uso${p}`).focus();
                            return;
                        } else if (metros_lineales_existentes < dato_ml_usados) { //aqui podemos mirar que no sea mayor que lo que ya tiene 
                            mensaje = 'Los metros lineales usados no pueden ser mayor a los cargados';
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
                    data_envio.push(data_carga);
                }
            });
            if (mensaje == '' && data_envio == '') {
                mensaje = 'Se debe elegir el material utilizado.';
                alertify.error(mensaje);
                return;
            }
            if (mensaje != '') {
                alertify.error(mensaje);
                return;
            }
            // ======================================================================

        }
        var obj_inicial = $('#grabar_produccion').html();
        btn_procesando('grabar_produccion');

        envio = {
            'operario': operario,
            'detencion': detencion,
            'datos_material': data_envio,
            'id_item_producir': id_item_producir,// este viene de la tabla de metros lineales
            'data_row': data_row,
            'parcial_total': parcial_total,//2 incompleto
            'num_troquel': num_troquel,
            'tipo_cierre': tipo_cierre
        };


        //================================ Aqui ponemos la funcion de cierre por item =====================
        if (valor == 5) {// envia a la funcion que cierra cada item completo e incompleto
            var item = data_row.data_item.num_pedido + '-' + data_row.data_item.item;
            var op = data_row.data_op.num_produccion;
            $.ajax({
                url: `${PATH_NAME}/produccion/cambiar_estado_pedido_item`,
                type: 'POST',
                data: { envio },
                success: function (res) {
                    if (res == -1) {
                        alertify.alert('Cerrar Orden', '¡Solo queda un item, por favor cirre la orden completa!', function () {
                            alertify.error('Cancelado');
                            $('#ProduccionModal').modal('toggle');
                            btn_procesando('grabar_produccion', obj_inicial, 1);
                        });
                    } else {
                        pestana_activa();
                        carga_funcion();
                        btn_procesando('grabar_produccion', obj_inicial, 1);
                        $('#ProduccionModal').modal('toggle');

                    }
                }
            });

            // ================================================================================================
        } else { // caso del 4 cuando es completa toda la orden
            var item = 'N/A';
            var op = data_row.num_produccion;
            $.ajax({
                url: `${PATH_NAME}/produccion/produccion_comp_incomp`,
                type: "POST",
                data: { envio },
                success: function (res) {
                    pestana_activa();
                    carga_funcion();
                    $('#ProduccionModal').modal('toggle');
                    btn_procesando('grabar_produccion', obj_inicial, 1);
                }
            });

        }
    });
}

function impresion_etiquetas_op(op, item, id_operario = '') { // falta poner el operario :(
    // =================== Impresion de etiqueta ================
    $('.div_impresion').empty().html('');
    alertify.alert().destroy();
    var html_impresion = ``
    if (id_operario == '' && (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13)) {
        html_impresion = `<div class="mb-3 row">
            <label for="cantidad" class="col-sm-6 col-form-label fw-bold">Cantidad Etiquetas:</label>
            <div class="col-sm-6 p-1">
                <input type="number" class="form-control" name="cantidad" id="cantidad">
            </div>
            <label for="operario_imp" class="col-sm-6 col-form-label fw-bold">Código Operario:</label>
            <div class="col-sm-6 p-1">
                <input type="password" class="form-control codigo_operario" name="operario_imp" id="operario_imp">
            </div>
            <div class="mb-3">
                <span class="respu_consulta"></span>
            </div>
            <div class="div_impresion"></div>
        </div>`
        id_operario = ''
        $('#id_persona').val('')
    } else {
        html_impresion = `<div class="mb-3 row">
        <label for="cantidad" class="col-sm-6 col-form-label fw-bold">Cantidad Etiquetas:</label>
        <div class="col-sm-6">
        <input type="number" class="form-control" name="cantidad" id="cantidad">
        </div>
        <div class="div_impresion"></div>
        </div>`
        id_operario = id_persona_sesion
    }
    alertify.alert('Impresión de etiquetas',
        html_impresion,
        function () {
            if (id_operario == '' && (datos_consulta == 1 || id_usuario_sesion == 8 || id_usuario_sesion == 13)) {
                id_operario = $('#id_persona').val();
            }
            var cantidad = $('#cantidad').val();
            if (cantidad === '' || cantidad <= 0) {
                $('#cantidad').focus();
                return false
            } if (id_operario == '' && ($('#id_persona').val() == '')) {
                $('#operario_imp').focus();
                return false
            } else {
                var sistema_operativo = navigator.platform;
                var eswindow = sistema_operativo.includes('Win')
                var id_tamano = 3;
                $.ajax({
                    type: "GET",
                    url: `${PATH_NAME}/produccion/impresoras_marcacion`,
                    data: { id_usuario: $('#sesion').val(), id_tamano: id_tamano, id_estacion_impre: $('#id_estacion_imp').val() },
                    success: function (res) {
                        var resolucion = 200;// para eticaribe es de 300 OJO
                        if (res == -1) {// no hay impresoras en base de datos
                            impresion_red = false
                        } else {
                            impresion_red = true
                            var datos_impresora = res['impresora'];
                            resolucion = datos_impresora[0]['resolucion'];
                        }

                        $.post(`${PATH_NAME}/produccion/impresion_etiquetas_troquelado`,
                            {
                                resolucion: resolucion,
                                data: { op: op, cantidad: cantidad, item: item, id_persona_sesion: id_operario },
                            },
                            function (respu) {
                                if (IMPRESION_API === 1 && impresion_red == true) { // esta es la condicion para imprimir directo o por controlador depende del proyecto

                                    const zplData = respu;
                                    const ip_impresora = datos_impresora[0]['ip'];
                                    const xhr = new XMLHttpRequest();

                                    xhr.open('POST', SERVIDOR_IMPRESION + '/print', true);// esta es la ip del servidor de desarrollo el cual servira de alojamiento de la api
                                    xhr.setRequestHeader('Content-Type', 'application/json');

                                    xhr.onreadystatechange = function () {
                                        if (xhr.readyState === 4 && xhr.status === 200) {
                                            respuesta = JSON.parse(xhr.responseText);
                                            if (respuesta.status == 1) {
                                                alertify.success('Impresión enviada a la estación ' + datos_impresora[0]['id_estacion'])
                                            } else {
                                                alertify.alert('Error de impresión', '¡Verifica la conexión de la impresora!',
                                                    function () {
                                                        window.open('https://' + ip_impresora, "_blank")// abrimos la impresora en otra pestaña para ver si esta conectada
                                                        btn_procesando('imprimir', obj_inicial, 1);
                                                    }
                                                );
                                            }
                                        }
                                    };
                                    xhr.onerror = function (e) {// esta funcion la utilizo cuando no tenemos respuesta del servidor; puede ser por dos razones; no esta corriendo la api o no se han aceptado los certificados autofirmados
                                        alertify.alert('Error de servidor', '¡Verifica el servidor de impresión!',
                                            function () {
                                                window.open(SERVIDOR_IMPRESION, "_blank")// abrimos el servidor en otra pestaña para que acepten el certificado SSL
                                                btn_procesando('imprimir', obj_inicial, 1);
                                            }
                                        );
                                    };

                                    const data = JSON.stringify({ zplData: zplData, ip: ip_impresora });
                                    xhr.send(data);
                                } else if (impresion_red == false && eswindow == false && IMPRESION_API === 1) {
                                    alertify.alert('Alerta Impresoras', '¡No hay impresoras configuradas para esta área!',
                                        function () { alertify.success(''); });
                                } else {
                                    $('.div_impresion').empty().html(respu);
                                    var mode = 'iframe'; //popup
                                    var close = mode == "popup";
                                    var options = { mode: mode, popClose: close };
                                    $("div.div_impresion").printArea(options);
                                }

                            }
                        )
                    }
                })
            }
        }).set({
            'closable': false,
            'label': 'imprimir',
            'closableByDimmer': false,
            onfocus: function () { consulta_operario() }
        });
}
// ============= Arreglo boton de impresion ========================
function boton_imprimir(tbody, table) {
    $(tbody).on('click', `tr button.imprimir_eti`, function (e) {
        e.preventDefault();
        var data = table.row($(this).parents("tr")).data();
        var op = data.n_produccion
        var item = data.item
        impresion_etiquetas_op(op, item);// esta funcion se traslada a un boton (Y)
    })
} 
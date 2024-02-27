$(document).ready(function () {
    pendiente_alistamiento_mp_op();
    ver_ordenes();
    materiales();
    descargarOrdenes();
    alistar_material();
    ok_material_completo();
    consultar_turnos();
    generar_cambio_maquina();
    liberarop();
    if (!DOS_MODULOS) {
        $('#contenido').addClass('d-none');
        $( ".recuadro" ).append( `<div class="text-center"><h1>Modulo no activo para este proyecto comuniquese con su desarrollador</h1></div>` );
    }
    alertify.set('notifier', 'position', 'bottom-left');
});

$('#mostrarTabla').on('click', function () {
    $('.TablaOrden').toggle(500);
});


var pendiente_alistamiento_mp_op = function () {
    var table = $('#dt_alista_ordenes_produccion').DataTable({
        "ajax": `${PATH_NAME}/almacen/consultar_pendientes_mp_op?paso=1`,
        "columns": [
            { "data": "fecha_comp" },
            { "data": "num_produccion" },
            { "data": "turno_maquina" },
            { "data": "nombre_maquina" },
            { "data": "tamanio_etiq" },
            {
                "data": "ancho",
                render: function (data, type, row) {

                    if (row["ancho_confirmado"] == 0) {
                        return row["ancho_op"];
                    } else {
                        return row["ancho_confirmado"];
                    }

                }
            },
            { "data": "cant_op", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "mL_total", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            {
                "data": "mL_descontado", render: function (date, type, row) {
                    if (row.mL_descontado > 0) {
                        return `${row.mL_descontado}
                        <button type="button" class="btn btn-info materiales" title="Modal_materiales">
                        <i class="fa fa-ring"></i>
                      </button>`;
                    }
                    return row.mL_descontado;
                }
            },
            {
                "data": "material",
                render: function (data, type, row) {

                    if (row["material_solicitado"] == '') {
                        return row["material"];
                    } else {
                        return row["material_solicitado"];
                    }
                }
            },
            {
                "data": "fecha_proveedor",
                render: function (data, type, row) {

                    if (row["fecha_proveedor"] == '0000-00-00') {
                        return '';
                    }
                    return row["fecha_proveedor"];
                }
            },
            { "data": "orden_compra" },
            {
                "data": "fecha_produccion",
                render: function (data, type, row) {
                    return '<b>' + (row['fecha_produccion']).replace(/[- ]+/g, '/') + '</b>';
                }
            },
            { "data": "nombre_estado", orderable: false },
            {
                "orderable": false,
                render: function (data, type, row) {
                    var producirOrden = '';
                    var alistar_material = '';
                    var descargarPDF = '';
                    var liberarop = '';
                    if (row.descarga_pdf == 0) {
                        descargarPDF = `<button class="btn btn-sm  btn-warning descargarPDF" id="descargarPDF${row.id_item_producir}"type="button" title="Descargar PDF"><i class="fa fa-download boton_cambioO' + row['id_item_producir'] + '"></i></button>`;
                    } else {
                        liberarop = `<button class="btn btn-sm btn-secondary liberarop" type="button" id="libera${row.id_item_producir}" aria-expanded="false" title="Liberar O.P"><i class="fas fa-tasks"></i></button>`;
                    }
                    if (parseInt(row.mL_descontado) < parseInt(row.mL_total)) {
                        alistar_material = `<button style="margin-top:2px;margin-right: -2px; background-color:#A65BEE;color:white;" class="btn btn-sm alistar_material" type="button" title="Alistamiento"><i class="fa fa-check"></i></button>`;
                    }
                    return `<center>
                                <button class="btn btn-sm btn-info verOrden" type="button"data-bs-toggle="collapse" data-bs-target=".InfoOrden" aria-expanded="false" aria-controls="collapseExample" title="Ver O.P."><i class="fa fa-search"></i></button>
                                <button class="btn btn-sm btn-secondary reasignar" data-id="${row.id_maquina}" title="Reasignar M.Q" ><i class="fa fa-retweet"></i></button> 
                                ${descargarPDF}
                                ${producirOrden}
                                ${alistar_material}
                                ${liberarop}
                                \n\
                            </center>`;
                }
            }
        ],
    });
    reasignar(`#dt_alista_ordenes_produccion, tbody`); //Se carga la funcion por primera vez despues de que se crean las tablas

}

//----------------------------------------- Agregar boton reasignar -------------------------------------
var reasignar = function (tbody) {
    $(tbody).on('click', `tr button.reasignar`, function () {
    var dato = $(this).attr('data-id');
    var data = $(`#dt_alista_ordenes_produccion`).DataTable().row($(this).parents("tr")).data(); //capturar valores de la fila seleccionada

    $('#CambioMaquinaModal').modal('show');
    $('#num_produccion_data').empty().html(data.num_produccion);
    $('#turno_data').val(data.turno_maquina);
    $('#fecha_produccion_data').val(data.fecha_produccion);
    $('#maquina_data').val(data.id_maquina).trigger('change');
    $('#generar_cambio_maquina').attr('data-id', JSON.stringify(data));
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
        var data = JSON.parse($('#generar_cambio_maquina').attr('data-id'));
        if (data.fecha_produccion == fecha_produccion && data.id_maquina == id_maquina  && data.turno == turno) {
            alertify.error('No realizo ningun cambio');
            $('#CambioMaquinaModal').modal('toggle');
            return;
        }
        var obj_inicial = $('#generar_cambio_maquina').html();
        btn_procesando('generar_cambio_maquina');
        var datos = {
            'fecha_produccion': fecha_produccion,
            'id_maquina': id_maquina,
            'turno': turno,
            'id_item_producir': data.id_item_producir
        }
        $.ajax({
            url: `${PATH_NAME}/produccion/cambiar_op_maquina`,
            type: "POST",
            data: datos,
            success: function (res) {
                btn_procesando('generar_cambio_maquina', obj_inicial, 1);
                $('#CambioMaquinaModal').modal('toggle');
                $("#dt_alista_ordenes_produccion").DataTable().ajax.reload();
            }
        });
    });
}

// --------------------------------------- boton azul metros lineales alistados ---------------------
var materiales = function (tbody, table) {
    $('#dt_alista_ordenes_produccion tbody').on("click", "tr button.materiales", function () {
        var data = $('#dt_alista_ordenes_produccion').DataTable().row($(this).parents("tr")).data();
        var materiales = data.materiales;
        $("#Num_OP").html(data.num_produccion);
        $('#dt_materiales').DataTable({
            "data": materiales,
            "columns": [
                { "data": "codigo_material" },
                { "data": "ancho" },
                {
                    "data": "metros_lineales", render: function (data, type, row) {
                        return parseFloat(row.metros_lineales_dispo) - parseFloat(row.suma_ml);
                    }
                },
            ],
        });
        $("#Modal_materiales").modal("show");
    });
};

// --------------------------------------- boton azul  lupa ---------------------
var ver_ordenes = function () {
    $('#dt_alista_ordenes_produccion tbody').on("click", "tr button.verOrden", function () {
        var data = $('#dt_alista_ordenes_produccion').DataTable().row($(this).parents("tr")).data();

        $(".InfoOrden").css('display', '');
        $('.TablaOrden').toggle(500);

        $('#numORDEN').empty().html(data.num_produccion);
        if (data.ancho_confirmado == '0') {
            $('#anchoORDEN').empty().html(data.ancho_op);
        } else {
            $('#anchoORDEN').empty().html(data.ancho_confirmado);
        }


        $('#cantORDEN').empty().html(parseFloat(data.cant_op).toLocaleString(undefined, { minimumFractionDigits: 0 }));
        $('#mtORDEN').empty().html(parseFloat(data.mL_total).toLocaleString(undefined, { minimumFractionDigits: 0 }));
        $('#etiqTAMNIO').empty().html(data.tamanio_etiq);

        var table = $('#dt_items_op').DataTable({
            "ajax": {
                "url": `${PATH_NAME}/almacen/consultar_items_op`,
                "type": "POST",
                "data": data,
            },
            "columns": [
                { "data": "codigo" },
                { "data": "descripcion_productos" },
                { "data": "ubi_troquel" },
                { "data": "ficha_tecnica" },
                { "data": "cant_faltante" },
                { "data": "metrosl" },
                { "data": "metros2" },
                {
                    "data": "num_pedido",
                    render: function (data, type, row) {
                        var pedido_item = row.num_pedido + "-" + row.item;
                        return pedido_item;
                    }
                },
                { "data": "nombre_core" },
                { "data": "cant_x" },
                { "data": "nombre_r_embobinado" },
                { "data": "nombre_estado_item" },
            ],

        });
    });
}


// ------------------------------------- boton amarillo descarga op -----------------------------------
var descargarOrdenes = function () {
    $('#dt_alista_ordenes_produccion tbody').on("click", "tr button.descargarPDF", function () {
        var data = $('#dt_alista_ordenes_produccion').DataTable().row($(this).parents("tr")).data();
        var orden_produccion = data.num_produccion;
        var reporte = 1;
        var obj_inicial = $(`#descargarPDF${data.id_item_producir}`).html();
        btn_procesando_tabla(`descargarPDF${data.id_item_producir}`);
        $.ajax({
            url: `${PATH_NAME}/configuracion/generar_pdf_orden_produccion`,
            type: 'POST',
            data: { orden_produccion, reporte },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (regreso) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(regreso);
                a.href = url;
                $("#dt_alista_ordenes_produccion").DataTable().ajax.reload(function () {
                    btn_procesando_tabla(`descargarPDF${data.id_item_producir}`, obj_inicial, 1); 
                    window.open(url, '_blank');
                });
            }
        });
    });
};

// ------------------------------------- boton Morado alistar mas material -----------------------------------

var array_cantidad = [];

var alistar_material = function () {
    $('#dt_alista_ordenes_produccion tbody').on("click", "tr button.alistar_material", function () {
        $('#dt_alista_material tbody').off('keyup', 'tr input.cantidad_repor');
        var data = $("#dt_alista_ordenes_produccion").DataTable().row($(this).parents("tr")).data();
        array_cantidad = [];
        if (data.material_solicitado == '') {
            var material = data.material;
        } else {
            var material = data.material_solicitado;
        }

        $('#ordenP').empty().html(data.num_produccion); //cargar valores al DOM 
        //condicional para verificar si el material solicitado es vacio
        if (data.material_solicitado == '') {
            $('#nombreM').empty().html(data.material);
        } else {
            $('#nombreM').empty().html(data.material_solicitado);
        }

        if (data.ancho_confirmado == 0) {
            $('#Ancho').empty().html(data.ancho_op);
            $('#info_ML').empty().html(data.mL_descontado);

        } else {
            $('#Ancho').empty().html(data.ancho_op);
            $('#info_ML').empty().html(data.mL_descontado);
            $('#MLTotal').empty().html(data.mL_total);
        }
        $('#maquinaM').empty().html(data.nombre_maquina);
        $('#id_maquinaM').val(data.maquina);
        $('#info_M2').empty().html('');

        tabla_materiales(material, data);
    });
};
var tabla_materiales = function (material, data_op) {
    var table1 = $(`#dt_alista_material`).DataTable({
        "ajax": {
            'url': `${PATH_NAME}/almacen/consulta_materiales`,
            'data': { material },
            'type': 'post'
        },
        "columns": [
            { "data": "ancho" },
            { "data": "M2", render: $.fn.dataTable.render.number('.', ',', 2) },
            { "data": "ML", render: $.fn.dataTable.render.number('.', ',', 2) },
            {
                "data": "checkbox", render: (data, type, row) => {
                    return `<div class="select_acob text-center">
                    <input type="checkbox" name="check_report${row.id_ingresotec}" value="${row.id_ingresotec}"/>
                </div>`;
                }
            },
            {
                "data": "input", render: (data, type, row) => {
                    return `
                    <input type="text"class="form-control cantidad_repor" id="cantidad_repor${row.id_ingresotec}" style="display:none;"/>`;
                }
            },
        ],
    });
    $("#Modal_CARGA_MATERIAL").modal("show");
    validar_input(`#dt_alista_material tbody`, table1, data_op);
    desbloquea_input(`#dt_alista_material tbody`, table1);
    $("#btn_ok_alista").attr('data-op', JSON.stringify(data_op));
    $("#btn_parcial_alista").attr('data-op', JSON.stringify(data_op));
}
var desbloquea_input = function (tbody, table) {
    $(tbody).on("click", "tr .select_acob", function () {
        var element = table.rows().nodes();
        $.each(element, function (index, value) {
            var p = $(this).find('input').val();
            var estado_radio = RadioElegido(`check_report${p}`);
            if (estado_radio == 'ninguno') {
                for (var i = 0; i < array_cantidad.length; i++) {
                    if (array_cantidad[i].id_ingresotec === p) {
                        array_cantidad.splice(i, 1);
                    }
                }
                calcula_tolales();
                $(`#cantidad_repor${p}`).css('display', 'none');
                $(`#cantidad_repor${p}`).val('');
            } else {
                $(`#cantidad_repor${p}`).css('display', '');
            }
        });
    });
}

var validar_input = function (tbody, table, datos_op) {
    $(tbody).on("keyup", "tr input.cantidad_repor", function () {
        var data = $('#dt_alista_material').DataTable().row($(this).parents("tr")).data();
        // var data = table.rows().data();
        var element = table.rows().nodes();
        array_cantidad = [];
        var mensaje = '';
        $.each(element, function (index, value) {
            var p = $(this).find('input').val();

            var estado_radio = RadioElegido(`check_report${p}`);
            var cantidad = 0;
            if (estado_radio == 'ninguno') {
                $('#info_ML').empty().html('');
                $('#info_M2').empty().html('');
            } else {
                cantidad = $(`#cantidad_repor${p}`).val();
                if (cantidad == '' || cantidad == 0) {
                    mensaje = ("Verifique que los materiales elegidos no esten en 0 o vacios.");
                } else {
                    var dato_ml = Math.round((data['ML']));
                    if (cantidad > dato_ml) {
                        mensaje = ("La cantidad no puede superar la de la ubicación.");
                        $(`#cantidad_repor${p}`).val('');
                        $(`#cantidad_repor${p}`).focus();
                        return;
                    } else {
                        var material = datos_op.material;
                        if (datos_op.material_solicitado) {
                            material = datos_op.material_solicitado;
                        }
                        var arrayubicacion = {
                            'ancho': data['ancho'],
                            'ml': cantidad,
                            'm2': (cantidad * data['ancho']) / 1000,
                            'id_productos': data['id_productos'],
                            'id_ingresotec': estado_radio,
                            'id_item_producir': datos_op.id_item_producir,
                            'num_produccion': datos_op.num_produccion,
                            'codigo': material,
                            'maquina': datos_op.maquina,
                        };
                        array_cantidad.push(arrayubicacion);
                    }
                }
            }
        });
        if (mensaje != '') {
            alertify.error(mensaje);
            return;
        }
        calcula_tolales();
    });
}

var calcula_tolales = function () {
    var ml = 0;
    var m2 = 0;
    array_cantidad.forEach(element => {
        ml += parseFloat(element.ml);
        m2 += parseFloat(element.m2);

    });
    $('#info_ML').empty().html(ml);
    $('#info_M2').empty().html(m2);
}

// -----boton verde de modal "ok" Alistamiento de material completo --------->
var ok_material_completo = function () {
    $(".alista_mat_completo").on("click", function () {
        var obj_inicial = $('#btn_ok_alista').html();
        btn_procesando('btn_ok_alista');
        var data_op = JSON.parse($("#btn_ok_alista").attr('data-op'));
        if (array_cantidad.length == 0) {
            alertify.error("No ha elegido ningún material para alistar.");
        } else {
            var material = data_op.material;
            var ancho = data_op.ancho_op;
            if (data_op.material_solicitado != '') {
                material = data_op.material_solicitado;
                ancho = data_op.ancho_confirmado;
            }
            var ml = 0;
            array_cantidad.forEach(element => {
                ml += parseFloat(element.ml);
            });
            $.ajax({
                url: `${PATH_NAME}/almacen/aumenta_material_op`,
                type: "POST",
                data: { array_cantidad },
                success: function (res) {
                    $("#dt_alista_ordenes_produccion").DataTable().ajax.reload(function () {
                        if (res.status == 1) {
                            alertify.success(res.msg);
                        } else {
                            alertify.error(res.msg);
                        }
                        $("#Modal_CARGA_MATERIAL").modal('hide');
                        btn_procesando('btn_ok_alista', obj_inicial, 1);
                    });
                }
            });
        }
    });
};

var liberarop = function () {
    $('#dt_alista_ordenes_produccion tbody').on("click", "tr button.liberarop", function () {
        var data = $('#dt_alista_ordenes_produccion').DataTable().row($(this).parents("tr")).data();
        if (data.mL_descontado == 0) {
            alertify.error('Lo sentimos debe de tener materia prima separada para poder continuar.');
            return;
        }
        if (data.mL_descontado < data.mL_total) {
            alertify.confirm('Sidpa Informa', 'Esta seguro que desea comenzar este trabajo sin la materia prima completa.',
                function () {
                    cambiar_estado_op(data);
                },
                function () {
                    alertify.error('Operación Cancelada')
                });
        } else {
            cambiar_estado_op(data);
        }
    });
}

var cambiar_estado_op = function (data) {
    var obj_inicial = $(`#libera${data.id_item_producir}`).html();
        btn_procesando_tabla(`libera${data.id_item_producir}`);
        $.ajax({
            url: `${PATH_NAME}/almacen/editar_estado_op`,
            type: "POST",
            data: { data },
            success: function (res) {
                $("#dt_alista_ordenes_produccion").DataTable().ajax.reload(function () {
                    btn_procesando_tabla(`libera${data.id_item_producir}`,obj_inicial, 1);
                    alertify.success('Actualizacion Exitosa');
                });
        }
    });
}
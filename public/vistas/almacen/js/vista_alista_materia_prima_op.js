$(document).ready(function () {
    pendiente_alistamiento_mp_op();
    carga_inventario();
    ok_material_completo();
    grabar_material_final();
    parcial_material_inconpleto();
    select_2();
    validar_check();
    valida_metros_retorno();
    alertify.set('notifier', 'position', 'bottom-left');
});


var pendiente_alistamiento_mp_op = function () {
    var paso = 1;
    if (DOS_MODULOS) {
        paso = 2;
    }
    var table = $('#dt_alista_ordenes_produccion').DataTable({
        "ajax": `${PATH_NAME}/almacen/consultar_pendientes_mp_op?paso=${paso}`,
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
            { "data": "ubi_troquel" },
            { "data": "ficha_tecnica" },
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
                    if (!DOS_MODULOS) {
                        descargarPDF = `<button class="btn btn-sm  btn-warning descargarPDF" id="descargarPDF${row.id_item_producir}"type="button" title="Descargar PDF"><i class="fa fa-download boton_cambioO' + row['id_item_producir'] + '"></i></button>`;
                    }
                    if (parseInt(row.mL_descontado) >= parseInt(row.mL_total) || DOS_MODULOS) {
                        producirOrden = `<button class="btn btn-sm btn-success producirOrden" id="btn_producirOrden${row.id_item_producir}" title="Producir"><i class="fa fa-check"></i></button>`;
                    } else {
                        alistar_material = `<button style="margin-top:2px;margin-right: -2px; background-color:#A65BEE;color:white;" class="btn btn-sm alistar_material" type="button" title="Alistamiento"><i class="fa fa-check"></i></button>`;
                    }
                    return `<center>
                                            <button class="btn btn-sm btn-info verOrden" type="button"data-bs-toggle="collapse" data-bs-target=".InfoOrden" aria-expanded="false" aria-controls="collapseExample" title="Ver O.P."><i class="fa fa-search"></i></button>
                                            ${descargarPDF}
                                            ${producirOrden}
                                            ${alistar_material}
                                            <button  class="btn btn-sm btn-danger motivosOP" type="button" data-bs-toggle="modal" data-bs-target="#Modal_motivos"  title="Motivos"><i class="fa fa-times"></i></button>\n\
                                        </center>`;
                }
            }
        ],
    });
    ver_ordenes('#dt_alista_ordenes_produccion tbody', table);
    producirOrden('#dt_alista_ordenes_produccion tbody', table);
    materiales('#dt_alista_ordenes_produccion tbody', table);
    descargarOrdenes('#dt_alista_ordenes_produccion tbody', table);
    alistar_material(table);
    motivosOP(table); //mostrar dinamicamente un modal con la informacion correspondiente
};

/**
 * Funcion para cargar y ocultar el formulario de inventario
 */
$('#mostrarTabla').on('click', function () {
    $('.TablaOrden').toggle(500);
});
// --------------------------------------------------------------boton azul lupa ------------------------------------------------------------------------------------------------------------------------------
/**
 * Funcion para cargar y ocultar el formulario de inventario
 */
var ver_ordenes = function (tbody, table1) {
    $(tbody).on("click", "tr button.verOrden", function () {
        var data = table1.row($(this).parents("tr")).data();

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

// --------------------------------------------------------------boton verde check------------------------------------------------------------------------------------------------------------------------------
var producirOrden = function (tbody, table) {
    $(tbody).on("click", "tr button.producirOrden", function () {
        var data = table.row($(this).parents("tr")).data();

        var maquina = data.maquina;
        var num_produccion = data.num_produccion;

        var obj_inicial = $(`#btn_producirOrden${data.id_item_producir}`).html();
        btn_procesando_tabla(`btn_producirOrden${data.id_item_producir}`);

        $.ajax({
            url: `${PATH_NAME}/almacen/proceso_turno_produccion`,
            type: 'POST',
            data: { maquina, num_produccion },
            success: function (res) {
                if (res.status == 1) {
                    table.ajax.reload(function () {
                        btn_procesando_tabla(`btn_producirOrden${data.id_item_producir}`, obj_inicial, 1);
                        alertify.success(res.msg);
                    });
                } else {
                    table.ajax.reload(function () {
                        btn_procesando_tabla(`btn_producirOrden${data.id_item_producir}`, obj_inicial, 1);
                        alertify.error(res.msg);
                    });
                }
            }
        });
    });
};
// --------------------------------------------------------------boton verde check------------------------------------------------------------------------------------------------------------------------------
var materiales = function (tbody, table) {
    $(tbody).on("click", "tr button.materiales", function () {
        var data = table.row($(this).parents("tr")).data();
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

// --------------------------------------------------------------boton amarillo descarga op------------------------------------------------------------------------------------------------------------------------------
var descargarOrdenes = function (tbody, table) {
    $(tbody).on("click", "tr button.descargarPDF", function () {
        var data = table.row($(this).parents("tr")).data();
        var orden_produccion = data.num_produccion;
        var obj_inicial = $(`#descargarPDF${data.id_item_producir}`).html();
        btn_procesando_tabla(`descargarPDF${data.id_item_producir}`);
        $.ajax({
            url: `${PATH_NAME}/configuracion/generar_pdf_orden_produccion`,
            type: 'POST',
            data: { orden_produccion },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (regreso) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(regreso);
                a.href = url;
                window.open(url, '_blank');
                btn_procesando_tabla(`descargarPDF${data.id_item_producir}`, obj_inicial, 1);
            }
        });
    });
};
// --------------------------------------------------------------boton morado check alista mas material------------------------------------------------------------------------------------------------------------------------------
var array_cantidad = [];
var arrayorden = []; //array global para almacenar la orden de produccion
var arrayMotivos = []; //array global para almacenar los motivos de la orden de produccion 
var cantidad_tintas = ''; //array global para almacenar las tintas 

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
// -----boton azul de modal alista mas material "carga inventario"--------->

/**
 * Funcion para cargar y ocultar el formulario de inventario
 */
$('#btn_ocultar_inventario').on('click', function () {
    $('.div_alista_material').toggle(500);
    $(".div_agrega_inventario").css('display', 'none');
    $(`#dt_alista_material`).DataTable().ajax.reload();
});

var carga_inventario = function () {
    $(".carga_inventario").on("click", function () {
        $(".div_agrega_inventario").css('display', '');
        $('.div_alista_material').toggle(500);
    });
};
// -----boton verde de modal "ok" Alistamiento de material completo --------->
var ok_material_completo = function () {
    $(".alista_mat_completo").on("click", function () {
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
            var m2 = 0;
            array_cantidad.forEach(element => {
                ml += parseFloat(element.ml);
                m2 += parseFloat(element.m2);
            });
            var ml_alistados_total = ml + parseFloat(data_op.mL_descontado);//sumamos toda la cantidad alistadas todo en metros lineales
            var m2_solicitados = (parseFloat(data_op.mL_total) * parseFloat(data_op.ancho_op)) / 1000;
            var m2_alistados_total = m2 + parseFloat(data_op.m2_inicial);//sumamos toda la cantidad alistadas todo en metros lineales
            if (m2_alistados_total >= m2_solicitados) {
                $(".div_imprimir_etiqueta").css('display', '');
                $('.div_alista_material').toggle(500);
                var maquina = data_op.nombre_maquina;
                var ancho = ancho;
                var num_produccion = data_op.num_produccion;
                var material = material;
                var metros_lineales_final = data_op.mL_total;
                var informacion = {
                    maquina,
                    ancho,
                    num_produccion,
                    material,
                    metros_lineales_final
                };
                var link = `<a class="btn btn-info" href='${PATH_NAME}/imprimir_etiquetas_bobinas?datos=${JSON.stringify(informacion)}'
                target="_blank">Impresión Etiqueta <i class="fa fa-print"></i></a>`;
                $('#link').empty().html(link);

            } else {
                alertify.error("Aun falta material para esta orden.");
            }
        }
    });
};

/**
 * Funcion para cargar y ocultar el impresion de etiqueta
 */
$('.btn_ocultar_imprimir_etiq').on('click', function () {
    $('.div_alista_material').toggle(500);
    $(".div_imprimir_etiqueta").css('display', 'none');
});

var grabar_material_final = function () {
    $("#grabar_material_final").on("click", function () {
        var obj_inicial = $('#grabar_material_final').html();
        btn_procesando_tabla('grabar_material_final');
        $.ajax({
            url: `${PATH_NAME}/almacen/agrega_material_completo`,
            type: "POST",
            data: { array_cantidad },
            success: function (res) {
                if (res.status == 1) {
                    $("#dt_alista_ordenes_produccion").DataTable().ajax.reload(function () {
                        btn_procesando('grabar_material_final', obj_inicial, 1);
                        $("#Modal_CARGA_MATERIAL").modal('hide');
                        alertify.success(res.msg);
                    });
                } else {
                    $("#dt_alista_ordenes_produccion").DataTable().ajax.reload(function () {
                        btn_procesando('grabar_material_final', obj_inicial, 1);
                        alertify.error(res.msg);
                        $("#Modal_CARGA_MATERIAL").modal('hide');
                    });
                }

            }
        });

    });
}

// -----boton verde de modal "parcial" Alistamiento de material parcial --------->
var parcial_material_inconpleto = function () {
    $(".alista_mat_parcial").on("click", function () {
        var data_op = JSON.parse($("#btn_parcial_alista").attr('data-op'));
        if (array_cantidad.length > 0) {
            alertify.alert('ALERTA Sidpa', '<b>No puede seleccionar ningun ancho !!</b>');
            return;
        }
        if (data_op.mL_descontado == 0) {
            alertify.alert('ALERTA Sidpa', '<b>No se alistaron Metros Lineales<br>Debe alistar metros lineales para la Orden de Producción. </b>');
            return;
        }

        var obj_inicial = $('#btn_parcial_alista').html();
        btn_procesando_tabla('btn_parcial_alista');
        var num_produccion = data_op.num_produccion
        var maquina = data_op.nombre_maquina
        $.ajax({
            url: `${PATH_NAME}/almacen/agrega_material_completo`,
            type: "POST",
            data: { parcial: 1, num_produccion, maquina },
            success: function (res) {
                if (res.status == 1) {
                    $("#dt_alista_ordenes_produccion").DataTable().ajax.reload(function () {
                        btn_procesando('btn_parcial_alista', obj_inicial, 1);
                        $("#Modal_CARGA_MATERIAL").modal('hide');
                        alertify.success(res.msg);
                    });
                } else {
                    $("#dt_alista_ordenes_produccion").DataTable().ajax.reload(function () {
                        btn_procesando('btn_parcial_alista', obj_inicial, 1);
                        alertify.error(res.msg);
                        $("#Modal_CARGA_MATERIAL").modal('hide');
                    });
                }

            }
        });

    });
};

var motivosOP = function (table) {
    $('#dt_alista_ordenes_produccion tbody').on("click", "tr button.motivosOP", function () {
        var data = table.row($(this).parents("tr")).data();
        if ($('#sin_ficha').prop('checked') == true) {
            arrayMotivos = [];
            $('#sin_ficha').prop('checked', false);
        }
        if ($('#sin_cireles').prop('checked') == true) {
            arrayMotivos = [];
            $('#sin_cireles').prop('checked', false);
            $('#total_tintas').prop('checked', false);
            $('#fotopolimeros').prop('checked', false);
            $('#cantidad_tintas').val('');
            $('#cantidad_tintas').css('display', 'none');
            cantidad_tintas = '';
        }
        if ($('#sin_troquel').prop('checked') == true) {
            arrayMotivos = [];
            $('#sin_troquel').prop('checked', false);
            $('#troquel_dañado').prop('checked', false);
            $('#troquelN').prop('checked', false);
        }
        var data = table.row($(this).parents("tr")).data();
        $('#ordenPP').empty().html(data.num_produccion);
        $('#id_maquinap').val(data.maquina);
        $('#troquel_2').css('display', 'none');
        $('#cireles_2').css('display', 'none');
        arrayorden = data;
    });
};
/**
 * 
 * validar los input del modal del boton rojo
 */
var validar_check = function () {
    /**
     * Sin ficha tecnica
     */
    $('#sin_ficha').on('click', function () {
        if ($('#sin_ficha').prop('checked') == true) {
            arrayMotivos.push({
                id: 1,
                descripcion: 'Falta Ficha Tecnica'
            });
        } else {
            //recorrer la lista de items y eliminar el item desmarcado
            for (var i = 0; i < arrayMotivos.length; i++) {
                if ($('#sin_ficha').val() == arrayMotivos[i].id) {
                    arrayMotivos.splice(i, 1);
                }
            }
        }
    });
    //--------------------------------------------------------------------------
    //--------------------------------------------------------------------------
    /**
     * Validar los cireles checkbox
     */
    $('#sin_cireles').on('click', function () {
        if ($('#sin_cireles').prop('checked') == true) {
            arrayMotivos.push({
                id: 2,
                descripcion: 'Falta Cireles'
            });
            $('#cireles_2').css('display', 'block');
        } else {
            //recorrer la lista de items y eliminar el item desmarcado
            for (var i = 0; i < arrayMotivos.length; i++) {
                if ($('#sin_cireles').val() == arrayMotivos[i].id) {
                    arrayMotivos.splice(i, 1);
                }
            }
            $('#cireles_2').css('display', 'none');
            $('#fotopolimeros').prop('checked', false);
            $('#total_tintas').prop('checked', false);
            $('#cantidad_tintas').val('');
            $('#cantidad_tintas').css('display', 'none');
        }
    });
    //--------------------------------------------------------------------------
    /*** Validar cireles opcion total tintas */
    $('#total_tintas').on('click', function () {
        if ($('#total_tintas').prop('checked') == true) {
            arrayMotivos.push({
                id: 4,
                descripcion: 'Falta todas las Tintas'
            });
            $('#fotopolimeros').prop('checked', false);
            $('#cantidad_tintas').val('');
            $('#cantidad_tintas').css('display', 'none');
            cantidad_tintas = '';
        } else {
            for (var i = 0; i < arrayMotivos.length; i++) {
                if ($('#total_tintas').val() == arrayMotivos[i].id) {
                    arrayMotivos.splice(i, 1);
                }
            }
        }
    });
    //--------------------------------------------------------------------------
    /*** Validar los cireles /fotopolimeros*/
    $('#fotopolimeros').on('click', function () {
        if ($('#fotopolimeros').prop('checked') == true) {
            $('#cantidad_tintas').css('display', 'block');
            $('#total_tintas').prop('checked', false);
            //recorrer array para quitar totas las tintas
            for (var i = 0; i < arrayMotivos.length; i++) {
                if ($('#total_tintas').val() == arrayMotivos[i].id) {
                    arrayMotivos.splice(i, 1);
                }
            }
        } else {
            $('#cantidad_tintas').css('display', 'none');
            $('#cantidad_tintas').val('');
        }
    });
    //--------------------------------------------------------------------------
    /** Validar cantidad tintas  */
    $('#cantidad_tintas').change(function () {
        var cantidad = $('#cantidad_tintas').val();
        cantidad_tintas = 'Faltan ' + cantidad + ' Tintas /Fotopolímeros';
    });
    //--------------------------------------------------------------------------
    //--------------------------------------------------------------------------
    //--------------------------------------------------------------------------
    //--------------------------------------------------------------------------
    /**
     * validar troquel cuando sea checked true
     */
    $('#sin_troquel').on('click', function () {
        if ($('#sin_troquel').prop('checked') == true) {
            arrayMotivos.push({
                id: 3,
                descripcion: 'Falta Troquel'
            });
            $('#troquel_2').css('display', 'block');
        } else {
            //recorrer la lista de items y eliminar el item desmarcado
            for (var i = 0; i < arrayMotivos.length; i++) {
                if ($('#sin_troquel').val() == arrayMotivos[i].id) {
                    arrayMotivos.splice(i, 1);
                }
            }
            $('#troquel_2').css('display', 'none');
        }
    });
    //--------------------------------------------------------------------------
    $('#troquel_dañado').on('click', function () {
        if ($('#troquel_dañado').prop('checked') == true) {
            arrayMotivos.push({
                id: 5,
                descripcion: 'Troquel Dañado / No encontrado'
            });
        } else {
            //recorrer la lista de items y eliminar el item desmarcado
            for (var i = 0; i < arrayMotivos.length; i++) {
                if ($('#troquel_dañado').val() == arrayMotivos[i].id) {
                    arrayMotivos.splice(i, 1);
                }
            }
        }
    });
    //--------------------------------------------------------------------------
    $('#troquelN').on('click', function () {
        if ($('#troquelN').prop('checked') == true) {
            arrayMotivos.push({
                id: 6,
                descripcion: 'Troquel No a llegado'
            });
        } else {
            //recorrer la lista de items y eliminar el item desmarcado
            for (var i = 0; i < arrayMotivos.length; i++) {
                if ($('#troquelN').val() == arrayMotivos[i].id) {
                    arrayMotivos.splice(i, 1);
                }
            }
        }
    });
    //--------------------------------------------------------------------------
    //--------------------------------------------------------------------------



};
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
/**
 * Funcion para enviar y validar los datos finales del modal Motivos OP
 */
$('#btn-retener').on('click', function () {

    if ($('#sin_ficha').prop('checked') == false && $('#sin_cireles').prop('checked') == false && $('#sin_troquel').prop('checked') == false) {
        alertify.alert('Alerta Sidpa', 'Debe elegir una opción');
        return;
    } else {
        enviar_datos(); //enviar los datos motivos op
        return;
    }
});
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
/**
 * Funcion para enviar correo y cambiar el estado de la orden de produccion.
 */
var enviar_datos = function () {
    $.ajax({
        url: `${PATH_NAME}/almacen/retener_op`,
        type: 'POST',
        data: { arrayMotivos, arrayorden, cantidad_tintas, maquina: $('#id_maquinap').val() },
        success: function (res) {
            $('#dt_ordenes_produccion').DataTable().ajax.reload(function () { });
            alertify.success('Exito');
        }
    });
};

var valida_metros_retorno = function () {
    $('#metro_lineales_alista_op').keyup(function () {
        var ancho = $('#ancho').val();
        if (ancho == 0 || ancho == '') {
            alertify.error('El ancho es requerido para continuar.');
            $('#ancho').focus();
            $('#m2').val(0);
            $(this).val('');
            return;
        }
        var metrosL = $(this).val();
        var m2 = (ancho * metrosL) / 1000;
        $('#m2').val(m2);
    });
}
$(document).ready(function () {
    dt_ordenes_produccion();
    carga_inventario();
    ok_material_completo();
    grabar_material_final();
    valida_metros_retorno();
});

var dt_ordenes_produccion = function () {
    $('#form_ordenes_produccion').on('submit', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var validar = validar_formulario(form);
        if (validar) {
            var num_produccion = $("#numero_produccion").val();
            var table = $('#dt_ordenes_produccion').DataTable({
                "ajax": {
                    "url": `${PATH_NAME}/almacen/consultar_ordenes_producciones`,
                    "type": "POST",
                    "data": { num_produccion },
                },
                "columns": [
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
                    { "data": "mL_descontado", render: $.fn.dataTable.render.number('.', ',', 0, '') },
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
                            var retorno = `
                            <button class="btn btn-info btn-sm retorno_material" data-bs-toggle="modal" data-bs-target="#Modal_detalle_material_op" type="button" title="Retornar Material">
                                <i class="fa fa-search"></i>
                            </button>`;
                            var aumento = `
                            <button style="margin-top:2px;margin-right: -2px; background-color:#A65BEE;color:white;" class="btn btn-sm alistar_material"  data-bs-toggle="modal" data-bs-target="#Modal_CARGA_MATERIAL" type="button" title="Alistamiento">
                                 <i class="fa fa-check"></i>
                            </button>
                            `;
                            // if (row.roll_usuario == 12) {//si el roll del usuario que consulto es 12 "cordinador de produccion"
                            //     retorno = '';
                            // }
                            return `<center>
                                        ${retorno}
                                        ${aumento}
                                    </center>`;
                        }
                    }
                ],
            });
            retorno_material();
            aumento_material();

        }
    });


    var retorno_material = function () {
        $('#dt_ordenes_produccion tbody').on("click", "tr button.retorno_material", function () {
            var data = $('#dt_ordenes_produccion').DataTable().row($(this).parents("tr")).data();

            $('#ordenPOPM').empty().html(data.num_produccion);
            $('#maquinaMOPM').empty().html(data.nombre_maquina);

            for (let i = 0; i < data.materiales.length; i++) {
                const element = data.materiales[i];
                var suma_final_m = parseFloat(element.metros_lineales_dispo) - parseFloat(element.suma_ml);

                if (suma_final_m == 0) { //validar si ya usaron los metros lineales
                    data.materiales.splice(i, 1); //eliminar los que ya han sido usados
                }
            }
            $('#dt_materiales_op tbody').off('click', 'tr button.retornar_material_op');

            var table = $('#dt_materiales_op').DataTable({
                "data": data.materiales,
                "columns": [
                    { "data": "codigo_material" },
                    { "data": "ancho" },
                    { "data": "metros_lineales_dispo" },
                    { "data": "suma_ml" },
                    {
                        "data": "metros_lineales_final",
                        render: function (date, type, row) {
                            var suma_final = parseFloat(row.metros_lineales_dispo) - parseFloat(row.suma_ml);
                            if (suma_final == 0) {
                                return "";
                            }
                            return /*html */ `
                                    <input type="text" name="cantidad_ml" class="form-control  agregar_cantidad" value="${suma_final}"> 
                            `;
                        }
                    },
                    {
                        "data": "retorno_material",
                        render: function (date, type, row) {
                            var suma_final = parseFloat(row.metros_lineales_dispo) - parseFloat(row.suma_ml);
                            if (suma_final == 0) {
                                return "";
                            }
                            return /*html */ `
                            <center>
                            <button class="btn btn-success retornar_material_op" id="btn_retorna_mat${row.id_metros_lineales}">
                                <i class="fa fa-exchange-alt"></i>
                            </button>                                          
                            <center>`;
                        }
                    }
                ],
            });
            retornar_material_op(data);

        });

    }
}
var retornar_material_op = function (data_princ) {
    $('#dt_materiales_op tbody').on('click', 'tr button.retornar_material_op', function () {
        var data = $("#dt_materiales_op").DataTable().row($(this).parents("tr")).data();

        var obj_inicial = $(`#btn_retorna_mat${data.id_metros_lineales}`).html();
        btn_procesando_tabla(`btn_retorna_mat${data.id_metros_lineales}`);

        var suma_final = $(this).parents("tr").find('td').eq(4).children('input').val();
        var m2 = (parseFloat(data.ancho) * parseFloat(suma_final)) / 1000;
        var entrada_tecnologia = {
            documento: data_princ.num_produccion,
            codigo_producto: data.codigo_material,
            metros: suma_final,
            ancho: data.ancho,
            entrada: m2
        }
        var metros_lineales_op = {
            id_item_producir: data.id_item_producir,
            ancho: data.ancho,
            codigo_material: data.codigo_material,
            estado_ml: 2,
            ml_usados: suma_final
        }
        var retorno_ml = parseFloat(data_princ.ml_retorno);
        var cantidad_suma = parseFloat(suma_final);
        var suma_retorno_ml = retorno_ml + cantidad_suma;
        if (data_princ.m2_retorno == '') {
            var suma_m2_retorno = m2;
        } else {
            var suma_m2_retorno = parseFloat(data_princ.m2_retorno) + m2;
        }
        var item_producir = {
            id_item_producir: data.id_item_producir,
            ml_retorno: suma_retorno_ml,
            m2_retorno: suma_m2_retorno,
        }

        $.ajax({
            type: "POST",
            url: `${PATH_NAME}/almacen/retorno_materiales_op`,
            data: { entrada_tecnologia, metros_lineales_op, item_producir },
            success: function (res) {
                if (res.status == 1) {
                    $("#dt_ordenes_produccion").DataTable().ajax.reload(function () {
                        btn_procesando_tabla('btn_retorna_mat', obj_inicial, 1);
                        $("#Modal_detalle_material_op").modal('hide');
                        alertify.success(res.msg);
                    });
                } else {
                    $("#dt_ordenes_produccion").DataTable().ajax.reload(function () {
                        btn_procesando_tabla(`btn_retorna_mat${data.id_item_producir}`, obj_inicial, 1);
                        alertify.error(res.msg);
                        $("#Modal_detalle_material_op").modal('hide');
                    });
                }
            }
        });

    });
}
var array_cantidad = [];

var aumento_material = function (tbody, table) {
    $('#dt_ordenes_produccion tbody').on("click", "tr button.alistar_material", function () {
        $('#dt_alista_material tbody').off('keyup', 'tr input.cantidad_repor');
        var data = $('#dt_ordenes_produccion').DataTable().row($(this).parents("tr")).data();
        var material = '';
        if (data.material_solicitado === '') {
            material = data.material;
        } else {
            material = data.material_solicitado;
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
}

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
    validar_input(`#dt_alista_material tbody`, table1, data_op);
    desbloquea_input(`#dt_alista_material tbody`, table1);
    $("#btn_ok_alista").attr('data-op', JSON.stringify(data_op));
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
        var data = $("#dt_alista_material").DataTable().row($(this).parents("tr")).data();
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
            array_cantidad.forEach(element => {
                ml += parseFloat(element.ml);
            });
            $(".div_imprimir_etiqueta").css('display', '');
            $('.div_alista_material').toggle(500);
            var maquina = data_op.maquina;
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
            url: `${PATH_NAME}/almacen/aumenta_material_op`,
            type: "POST",
            data: { array_cantidad },
            success: function (res) {
                if (res.status == 1) {
                    alertify.success(res.msg);
                } else {
                    alertify.error(res.msg);
                }
                $('#btn_consultar_op').click();
                $("#Modal_CARGA_MATERIAL").modal('hide');
                btn_procesando('grabar_material_final', obj_inicial, 1);

            }
        });

    });
}

var valida_metros_retorno = function () {
    $('#metro_lineales_retorno').keyup(function () {
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
$(document).ready(function () {
    select_2();
    listar_ordenes();
    agregar_items();
    validar_input();
    comprar_orden();
    pendiente_fecha_op();
});

array_cant_m = [];

var listar_ordenes = function () {
    var table = $('#tabla_ordenes_op').DataTable({
        "order": [
            [4, "desc"]
        ],
        "ajax": `${PATH_NAME}/produccion/ordenes_produccion`,
        "columnDefs": [
            { className: 'text-center', targets: [0, 1, 2, 3, 4] }
        ],
        "columns": [
            { "data": "fecha_crea" },
            { "data": "num_produccion" },
            {
                "data": "nombre_maquina",
                render: function (date, type, row) {
                    return '<b>' + row['nombre_maquina'] + '</b>';
                }
            },
            { "data": "tamanio_etiq" },
            { "data": "ancho_op" },
            { "data": "cant_op", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "mL_total", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "material" },
            { "data": "cantidad_inventario", render: $.fn.dataTable.render.number('.', ',', 2, '', ' <b>m²</b>') },
            {
                "data": "botones", render: function (data, type, row) {
                    var res = `<center>
                        <button class="btn btn-info verOrden" type="button" id="ver_${row.id_item_producir}">
                            <i class="fa fa-search"></i>
                        </button>
                        <button class="btn btn-success alistar_material" type="button" id="alista_${row.id_item_producir}" title="Alistar">
                            <i class="fa fa-check"></i>
                        </button>
                    <center>`;
                    return res;
                }
            }
        ],
    });
    verOrden(table);
    alistar_material(table);
}

$('#mostrarTabla').on('click', function () {
    $('.InfoOrden').collapse('toggle');
    $("#datos_orden").show(500);
});

var verOrden = function (table) {
    $('#tabla_ordenes_op tbody').on("click", "tr button.verOrden", function () {
        var data = table.row($(this).parents("tr")).data();
        var obj_inicial = $(`#ver_${data.id_item_producir}`).html();
        btn_procesando_tabla(`ver_${data.id_item_producir}`);
        $.ajax({
            url: `${PATH_NAME}/produccion/consultar_items_orden`,
            type: 'POST',
            data: { num_produccion: data.num_produccion },
            success: function (res) {
                btn_procesando_tabla(`ver_${data.id_item_producir}`, obj_inicial, 1);
                $('.InfoOrden').collapse('toggle');
                $("#datos_orden").hide(500);
                $('#numORDEN').empty().html(data.num_produccion);
                $('#anchoORDEN').empty().html(data.ancho_op);
                $('#cantORDEN').empty().html(parseFloat(data.cant_op).toLocaleString(undefined, { minimumFractionDigits: 0 }));
                $('#mtORDEN').empty().html(parseFloat(data.mL_total).toLocaleString(undefined, { minimumFractionDigits: 0 }));
                $('#tamanoORDEN').empty().html(data.tamanio_etiq);
                var table = $("#datos_op").DataTable({
                    "data": res,
                    "columns": [
                        { "data": "codigo" },
                        { "data": "descripcion_productos" },
                        { "data": "ubi_troquel" },
                        { "data": "cant_op", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                        { "data": "metrosl", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                        { "data": "metros2", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                        {
                            "data": "pedido_item", render: function (data, type, row) {
                                return `${row.num_pedido}-${row.item}`;
                            }
                        },
                        { "data": "nombre_core" },
                        { "data": "cant_x" },
                        { "data": "nombre_r_embobinado" },
                    ]
                });
            }
        });
    });
}

var alistar_material = function (table) {
    $('#tabla_ordenes_op tbody').on("click", "tr button.alistar_material", function () {
        var data = table.row($(this).parents("tr")).data();
        $("#dt_anchos").DataTable().clear().draw();// Se limpia la tabla cada vez que se salgan 
        var obj_inicial = $(`#alista_${data.id_item_producir}`).html();
        btn_procesando_tabla(`alista_${data.id_item_producir}`);
        //Consultar seguimiento-inventario del producto apartir del codigo
        $.ajax({
            url: `${PATH_NAME}/produccion/consultar_inventario_anchos_material`,
            type: 'POST',
            data: { codigo: data.material }, //enviar el codigo seleccionado inicialmente
            success: function (res) {
                btn_procesando_tabla(`alista_${data.id_item_producir}`, obj_inicial, 1);
                $("#AlistaMaterialModal").modal("show");
                var items = ''; //variable para guardar y estructurar la respuesta
                if (Array.isArray(res)) {
                    //recorrer los materiales vinculados para el codigo especial
                    for (var i = 0; i < res.length; i++) {
                        items +=
                            `<b>
                            Material ${i + 1} : ${res[i]}  <input type="radio" name="selec" value="${res[i]}" class="codigo_consulta_dos"> ||
                        </b><br>`;
                    }
                    $('#materiales_dos').empty().html(items); //funcion para cargar en el DOM los materiales 
                } else {
                    items +=
                        `<b>
                        Material: ${res}  <input type="radio" name="selec" value="${res}" class="codigo_consulta_dos"> || 
                    </b>`;
                    $('#materiales_dos').empty().html(items); //funcion para cargar el material
                }
                $('#pendiente_fecha_op').prop('disabled', true);
                $('#ordenP').empty().html(data.num_produccion);
                $('#ordenP').empty().html(data.num_produccion);
                $('#ordenP').val(data.mL_descontado);
                $('#codigoE').empty().html(data.tamanio_etiq);
                $('#maquinaL').empty().html(data.nombre_maquina);
                $('#maquinaL').val(data.maquina);
                if (parseFloat(data.mL_descontado) == 0) {
                    var suma = parseFloat(data.mL_total);
                } else {
                    var suma = (parseFloat(data.mL_total)) - (parseFloat(data.mL_descontado));
                }
                $('#MLTotal').empty().html(suma);
                $('#Ancho').empty().html(data.ancho_op);
                $('#codigoM').empty().html(data.material);
                $('#info_M2').empty().html(0);
                $('#info_ML').empty().html(0);
                codigo_consulta_dos();// Se cargan los materiales del codigo
            }
        });

    });
}

var codigo_consulta_dos = function () {
    $('.codigo_consulta_dos').on('click', function () {
        var codigo = ($(this).val());
        $.ajax({
            url: `${PATH_NAME}/produccion/inventario_anchos_material`,
            type: "POST",
            data: { codigo },
            success: function (res) {
                var table = $("#dt_anchos").DataTable({
                    "data": res,
                    "pageLength": 10,
                    "columns": [
                        { "data": "ancho" },
                        { "data": "M2", render: $.fn.dataTable.render.number('.', ',', 2, '') },
                        { "data": "ML", render: $.fn.dataTable.render.number('.', ',', 0, '') },
                        {
                            "data": "elige", render: function (data, type, row) {
                                return `<div class='select_acob text-center'><input type="checkbox" class="agregar_items" /></div>`;
                            }
                        },
                        {
                            "data": "elige", render: function (data, type, row) {
                                var id_posicion = row.id_posicion;
                                return `<input type="number" class="form-control validar" id="ancho_${id_posicion}" value="" disabled />`;
                            }
                        },
                    ],
                });
                $('#codigoM').empty().html(codigo);
                $('#info_M2').empty().html(0);
                $('#info_ML').empty().html(0);
            }
        });
    });
}

var agregar_items = function () {
    $('#dt_anchos tbody').on("click", "input.agregar_items", function () {
        var data = $("#dt_anchos").DataTable().row($(this).parents("tr")).data();
        if ($(this).prop('checked') == true) {
            $('#pendiente_fecha_op').prop('disabled', false);
            $(`#ancho_${data.id_posicion}`).attr('disabled', false);
            array_cant_m.push({
                id_posicion: data.id_posicion,
                id_productos: data.id_productos,
                ancho: data.ancho,
                cantidad: 0,
                m2_sacados: 0,
                codigo: $('#codigoM').text(),
                num_produccion: $('#ordenP').text()
            });
        } else {
            $(`#ancho_${data.id_posicion}`).attr('disabled', true);
            $(`#ancho_${data.id_posicion}`).val('');
            //recorrer el array de cantidad de metros lineales 
            for (var i = 0; i < array_cant_m.length; i++) {
                //validar si el ancho seleccionado ya existe en el array 
                //si existe se remplaza el valor de cantidad 
                if (array_cant_m[i].id_posicion === data.id_posicion) {
                    array_cant_m.splice(i, 1);
                }
            }
            var respu = calculo_totales();
            $('#info_M2').empty().html((respu.m2_total).toFixed(2));
            $('#info_ML').empty().html(respu.ml_total);
        }
    });
}

var validar_input = function () {
    $('#dt_anchos tbody').on("keyup", "tr input.validar", function () {
        var data = $("#dt_anchos").DataTable().row($(this).parents("tr")).data();
        var cantidad = $(`#ancho_${data.id_posicion}`).val();
        var edita = true;
        if (cantidad == 0) {
            alertify.error('el campo no puede ser 0');
            $(`#ancho_${data.id_posicion}`).val('');
            $(`#ancho_${data.id_posicion}`).focus();
            edita = false;
        }
        if (cantidad > parseInt(data.ML)) {
            alertify.alert('ALERTA', '<b>La cantidad ingresada supera la cantidad disponible !! </b>');
            $(`#ancho_${data.id_posicion}`).val('');
            $(`#ancho_${data.id_posicion}`).focus();
            edita = false;
        }
        // edito la cantidad y los metros lineales del item en la variable global
        if (edita) {
            var m2_cambio = (data.ancho * cantidad) / 1000;
            array_cant_m.forEach(element => {
                if (element.id_posicion == data.id_posicion) {
                    element.m2_sacados = m2_cambio;
                    element.cantidad = cantidad;
                }
            });
        }
        var respu = calculo_totales();
        $('#info_M2').empty().html((respu.m2_total).toFixed(2));
        $('#info_ML').empty().html(respu.ml_total);
    });
}

var calculo_totales = function () {
    var data = $('#dt_anchos').DataTable().rows().nodes();
    var m2_total = 0;
    var ml_total = 0;
    $.each(data, function (index, value) {
        if ($(this).find('input').prop('checked')) {
            var ancho = $(this).find('td').eq(0).text();
            var ml_descontados = $(this).find('.validar').val();
            var m2_linea;
            if (parseInt(ml_descontados) == 0 || ml_descontados == '') {
                m2_linea = 0;
                ml_descontados = 0;
            } else {
                m2_linea = (parseInt(ancho) * ml_descontados) / 1000;
            }
            ml_total = ml_total + parseInt(ml_descontados);
            m2_total = m2_total + m2_linea;
        }
    });
    var respu = {
        'm2_total': m2_total,
        'ml_total': ml_total
    };
    return respu;
}

var pendiente_fecha_op = function () {
    $('#pendiente_fecha_op').on('click', function () {
        var totalSolicitado = $('#MLTotal').text(); //obtener total metros lineales solicitados
        var totalIngresado = $('#info_ML').text(); //obtener metros lineales ingresados
        if (parseInt(totalSolicitado) > parseInt(totalIngresado)) {
            alertify.alert('Alerta', 'La cantidad total de metros lineales es muy baja.');
            return;
        }
        if (array_cant_m.length <= 0) {
            alertify.alert('Alerta', 'Ningun Ancho seleccionado.');
            return;
        }
        alertify.confirm("Confirmar",
            `Esta apunto de descontar el ancho seleccionado.\n\
                <br> <b>¿Esta seguro que desea continuar ?</b>`,
            function () {
                $.ajax({
                    url: `${PATH_NAME}/produccion/separa_materia_prima`, //enviar peticion 
                    type: 'POST',
                    data: { array_cant_m },
                    success: function (res) {
                        array_cant_m=[];
                        $('#tabla_ordenes_op').DataTable().ajax.reload(function () {
                            $("#AlistaMaterialModal").modal("hide");
                            alertify.success('Exito al descontar !!');
                        });
                    }
                });
            },
            function () {
                alertify.error('Operación Cancelada');
            });
    });
}

var comprar_orden = function () {
    $('#compra_orden_produccion').on('click', function () {
        var elegido = RadioElegido('selec');
        var num_produccion = $('#ordenP').text();
        var mensaje = false;
        var envio = '';
        if (elegido == 'ninguno') {
            alertify.error('Se requiere que se elija un material para continuar.');
            return;
        }
        if (parseFloat($('#info_ML').text()) > parseFloat($('#MLTotal').text())) {
            alertify.alert('ALERTA', 'sobrepaso la cantidad metros lineales no puede mandar a comprar =>' + $('#info_ML').text() + " mL");
            return;
        }
        if (array_cant_m.length > 0) {
            envio = {
                'material': elegido,
                'datos': array_cant_m,
                'valida': 1
            };
            mensaje = true;
        } else {
            envio = {
                'material': elegido,
                'datos': num_produccion,
                'valida': 2
            };
            mensaje = false;
            envio_datos_compra(envio);
        }
        if (mensaje) {
            alertify.confirm("Confirmar",
                `Esta apunto de descontar el ancho seleccionado.\n\
                <br> <b>¿Esta seguro que desea continuar ?</b>`,
                function () {
                    envio_datos_compra(envio);
                },
                function () {
                    alertify.error('Operación Cancelada');
                });
        }
    });
}

var envio_datos_compra = function (envio) {
    $.ajax({
        url: `${PATH_NAME}/produccion/separa_compra_materia_prima`, //enviar peticion 
        type: 'POST',
        data: envio,
        success: function (res) {
            array_cant_m = [];
            $('#tabla_ordenes_op').DataTable().ajax.reload(function () {
                $("#AlistaMaterialModal").modal("hide");
                alertify.success('Operación ejecutada exitosamente !!');
            });
        }
    });

}


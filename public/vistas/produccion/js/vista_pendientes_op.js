$(document).ready(function () {
    select_2();
    listar_items_op();
    generar_num_produccion();
    form_consultar_op();
    form_modificar_item_producir();
});

var listar_items_op = function () {

    var table = $("#tabla_pendientes_op").DataTable({
        "order": [
            [4, "asc"],
            [10, "desc"]
        ],
        "ajax": `${PATH_NAME}/produccion/consultar_items_pendientes_op`,
        // "columnDefs": [{
        //     "orderable": false,
        //     "targets": [0, 1, 2, 4, 5, 6, 7, 8, 10, 11, 12, 13, 14]
        // }],
        "columns": [
            // { "data": "fecha_compromiso" },
            { "data": "nombre_empresa" },
            { "data": "cant_op", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            {
                "data": "num_pedido",
                render: function (data, type, row) {
                    return `${row['num_pedido']}-${row['item']} ${row['num_pqr']}`;
                }
            },
            { "data": "codigo" },
            {
                "data": "troquel", render: function (data, type, row) {
                    if (row.troquel == 1) {
                        return `<h5 style="color:green;">SI</h5>`
                    } else {
                        return `<h5 style="color:red;">NO</h5>`
                    }
                }
            },
            { "data": "magnetico" },
            { "data": "tintas" },
            { "data": "descripcion_productos" },
            { "data": "nombre_core" },
            { "data": "cav_montaje" },
            { "data": "material" },
            { "data": "ancho_material" },
            { "data": "cant_x", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "metrosl", render: $.fn.dataTable.render.number('.', ',', 0, '') },
            { "data": "fecha_cierre" },
            { "data": "nombre_estado_item" },
            {
                "orderable": false,
                "defaultContent": `<div class="select_acob text-center">
                                     <input type="checkbox" class="agrupar_items"> 
                                    </div>`
            },
            {
                "orderable": false,
                render: function (data, type, row) {
                    return `<div class="select_acob text-center">
                                         <button class="btn btn-info btn-sm observaciones_ver"  style="margin-top: 5px;" data-bs-toggle="modal" data-bs-target="#observaciones_Modal"><i class="fa fa-search"></i></button>
                                         <button class="btn btn-success btn-sm ver_ficha" data_produ="${row.codigo}" title="Ficha Tecnica"><i class="fas fa-eye"></i></button>
                                         </div>
                                        `
                }
            }
        ],
    });
    agrupar_items(table);
    observaciones_ver('#tabla_pendientes_op tbody', table);
    ver_ficha(`#tabla_pendientes_op tbody`, table);

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

var observaciones_ver = function (tbody, table) {
    $(tbody).on('click', 'button.observaciones_ver', function () {
        var data = table.row($(this).parents('tr')).data();
        $("#observaciones_p").empty().html(data.observaciones);
    });

}

var arrayitems = [];
var arrayinfo = [];
var agrupar_items = function (table) {
    $('#tabla_pendientes_op tbody').on("click", "tr input.agrupar_items", function () {
        var data = table.row($(this).parents("tr")).data();
        if (data.material == '<b>SIN ASIGNAR</b>') {
            alertify.error('este item no tiene un material asignado solicite su asignación.');
            $(this).prop('checked', false);
            return;
        }
        if ($(this).prop('checked') == true) {
            //agregar el item marcado a la lista
            arrayitems.push({
                'id_item': data.id_pedido_item
            });
            arrayinfo.push({
                datos: data
            });
        } else {
            //recorrer la lista de items y eliminar el item desmarcado
            for (var i = 0; i < arrayitems.length; i++) {
                if (data.id_pedido_item === arrayitems[i].id_item) {
                    arrayitems.splice(i, 1);
                    arrayinfo.splice(i, 1);
                }
            }
        }
        if (arrayinfo.length > 0) {
            for (var j = 0; j < arrayinfo.length; j++) {
                //validar que el codigo sea igual 
                if (arrayinfo[0].datos.codigoT != arrayinfo[j].datos.codigoT) {
                    alertify.dialog('alert').set({ transition: 'zoom', message: 'Transition effect: zoom' }).show();
                    alertify
                        .alert('ALERTA', '<h3>No coincide el tamaño de la Etiqueta</h3>', function () {

                        });
                    $(this).prop('checked', false);
                    arrayitems.splice(j, 1);
                    arrayinfo.splice(j, 1);
                    return;
                }
                if (arrayinfo[0].datos.material != arrayinfo[j].datos.material) {
                    alertify.dialog('alert').set({ transition: 'zoom', message: 'Transition effect: zoom' }).show();
                    alertify
                        .alert('ALERTA', '<h3>No coincide el material</h3>', function () {

                        });
                    $(this).prop('checked', false);
                    arrayitems.splice(j, 1);
                    arrayinfo.splice(j, 1);
                    return;
                }
            }
        }
    });
    //iniciar la funcion info_productos informacion de productos 
    info_producto();
}

var info_producto = function () {
    //boton verde generar O.P
    $('#btn-agrupar').on('click', function () {
        var items = '';
        if (arrayitems != '') {
            $("#GeneraOpModal").modal("show");
            var total1 = 0;
            var total2 = 0;
            for (var i = 0; i < arrayinfo.length; i++) {
                total1 = total1 + parseFloat(arrayinfo[i].datos.cant_op);
                total2 = total2 + parseFloat(arrayinfo[i].datos.metrosl);
                items += '<tr>';
                items += '<td>' + arrayinfo[i].datos.num_pedido + '-' + arrayinfo[i].datos.item + '</td>';
                items += '<td>' + arrayinfo[i].datos.codigo + '</td>';
                items += '<td>' + arrayinfo[i].datos.tintas + '</td>';
                items += '<td>' + arrayinfo[i].datos.ubi_troquel + '</td>';
                items += '<td>' + parseFloat(arrayinfo[i].datos.cant_op).toLocaleString(undefined, { minimumFractionDigits: 0 }) + '</td>';
                items += '<td>' + parseFloat(arrayinfo[i].datos.metrosl).toFixed(0) + '</td>';
                items += '<td>' + parseFloat(arrayinfo[i].datos.metros2).toFixed(2) + '</td>';
                items += '<td>' + arrayinfo[i].datos.nombre_core + '</td>';
                items += '<td>' + parseFloat(arrayinfo[i].datos.cant_x).toLocaleString(undefined, { minimumFractionDigits: 0 }) + '</td>';
                items += '<td>' + arrayinfo[i].datos.nombre_r_embobinado + '</td>';
                if (arrayinfo[i].datos.difer_ext == "1") {
                    var ext = 'Ext';
                } else {
                    var ext = '';
                }
                if (arrayinfo[i].datos.difer_mas == "1") {
                    var mas = '+';
                } else {
                    var mas = '';
                }
                if (arrayinfo[i].datos.difer_menos == "1") {
                    var menos = '-';
                } else {
                    var menos = '';
                }
                items += '<td>' + arrayinfo[i].datos.porcentaje + ' ' + ext + mas + ' ' + menos + '</td>';
                items += '</tr>';
            }
            $('#items-seleccionados').empty().html(items);
            $('#num_produccion_asig').empty().html(arrayinfo[0].datos.num_produccion);
            $('#total_1').empty().html(total1);
            $('#total_2').empty().html(total2);
        } else {
            alertify.error('Lo sentimos se requiere al menos un ítem para poder continuar.');
        }

    });
}

var generar_num_produccion = function () {
    $('#generar_num_produccion').on('click', function () {
        var obj_inicial = $('#generar_num_produccion').html();
        btn_procesando('generar_num_produccion');
        var maquina = $('#maquina').val();
        // Si queremos activar que no se pueda generar la op si no tiene fecha de compromiso
        for (let i = 0; i < arrayinfo.length; i++) {
            const element = arrayinfo[i];
            // if (element.datos.fecha_compromiso == "0000-00-00") {
            //     alertify.error('Fecha de compromiso no asignada!!');
            //     return;
            // }
        }

        $.ajax({
            url: `${PATH_NAME}/produccion/generar_num_produccion`,
            type: 'POST',
            data: { arrayinfo, maquina },
            // xhrFields: {
            //     responseType: 'blob'
            // },
            beforeSend: function (res) {
                alertify.alert('ALERTA', 'Espere un momento..');
            },
            success: function (regreso) {
                //validar si el tamaño de PDF regreso es menor 1180 
                if (regreso.estado == 1) {
                    alertify.success(regreso.mensaje);
                    $("#tabla_pendientes_op").DataTable().ajax.reload();
                    $('#items-seleccionados').empty().html(' ');
                    $('#total_1').empty().html('');
                    $('#total_2').empty().html('');
                    arrayitems = [];
                    arrayinfo = [];
                } else {
                    alertify.alert('ALERTA ', `<br><b>${regreso.mensaje}</b>`);
                    $("#tabla_pendientes_op").DataTable().ajax.reload();
                    $('#items-seleccionados').empty().html(' ');
                    $('#total_1').empty().html('');
                    $('#total_2').empty().html('');
                    arrayitems = [];
                    arrayinfo = [];
                    console.log(regreso);
                }
                btn_procesando('generar_num_produccion', obj_inicial, 1);
                $("#GeneraOpModal").modal("hide");
            }
        });
    });
}

var form_consultar_op = function () {
    $('#form_consultar_op').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#consulta_op').html();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            btn_procesando('consulta_op');
            if (!$("#respu_consulta").is(":visible")) {
                $("#respu_consulta").toggle(500);
            }
            $.ajax({
                "url": `${PATH_NAME}/produccion/consultar_op`,
                "type": 'POST',
                "data": form,
                "success": function (respu) {
                    // Serellena el formulario para los elementos que son solo visibles
                    $.each(respu[0], function (name, value) {
                        $(`#${name}_modifi`).html(value);
                    });
                    $('#edita_op').attr('data-edit', respu[0].id_item_producir);
                    btn_procesando('consulta_op', obj_inicial, 1);
                    limpiar_formulario('form_consultar_op', 'input');
                }
            });
        }
    });
}

var form_modificar_item_producir = function () {
    $('#form_modificar_item_producir').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#edita_op').html();
        var form = $(this).serializeArray();
        var valida = true;
        if ($('#nueva_maquina').val() == '' || $('#nueva_maquina').val() == 0) {
            alertify.error('Se requiere el campo Nueva Maquina para continuar');
            $('#nueva_maquina').focus();
            valida = false;
            return;
        }
        if (valida) {
            form = $(this).serialize();
            btn_procesando('edita_op');
            var id_item_producir = $('#edita_op').attr('data-edit');
            var envio = {
                'form': form,
                'id': id_item_producir
            };
            $.ajax({
                "url": `${PATH_NAME}/produccion/modifica_op`,
                "type": 'POST',
                "data": envio,
                "success": function (respu) {
                    if (respu) {
                        btn_procesando('edita_op', obj_inicial, 1);
                        $('.fw-bold').empty().html('');
                        alertify.success('Maquina cambiada correctamente.');
                        limpiar_formulario('form_modificar_item_producir', 'select');
                        limpiar_formulario('form_modificar_item_producir', 'input');
                        $("#respu_consulta").toggle(500);
                    } else {
                        btn_procesando('edita_op', obj_inicial, 1);
                        alertify.error('Algo paso comuniquese con su desarrollador');
                    }
                }
            });
        }
    });
}
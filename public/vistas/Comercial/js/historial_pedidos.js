/*
 * NOTA : ESTE JS SE ESJECUTA EN DOS VISTAS EN LA DE MIS CLIENTES EN LA VENTANA DE PEDIDOS Y EN LA DE HISTORIAL DE PEDIDOS QUE HACEN EXACTAMENTE LO MISMO. 
 */

$(document).ready(function () {
    select_2();
    listar_pedidos_asesor();
    cambio_tipo_consulta();
    regresa_pedidos();
    seguimiento_items();
    generar_pdf_pedido();
    regresa_pedidos_h();
    detalle_pedido_creado();
});
var lista_clientes = JSON.parse($('#lista_clientes').val());
/*
 *  funcion de hacer pedido regresar a tabla clientes (boton verde) "VISTA EN HISTORIAL DE PEDIDOS" 
 */
var regresa_pedidos = function () {
    $('.regresa_pedidos').on('click', function () {
        if ($(".detalle_pedido").is(":visible")) {
            $(".tabla_h_pedido").toggle(500);
            $(".detalle_pedido").toggle(500);
        }
    });
}
/*
 *  funcion de hacer pedido regresar a tabla clientes (boton verde) "VISTA EN MIS CLIENTES" 
 */
var regresa_pedidos_h = function () {
    $('.regresa_pedidos_h').on('click', function () {
        if ($(".detalle_pedido_h").is(":visible")) {
            $(".tabla_c_pedido_h").toggle(500);
            $(".detalle_pedido_h").toggle(500);
        }
    });
}

var cambio_tipo_consulta = function () {
    $('#tipo').on('change', function () {
        var tipo = $(this).val();
        var res = '';
        if (tipo == 1) {
            res = `<label for="cliente">Razón Social</label>
            <select class="form-control" name="cliente" id="cliente">
            <option value="0" selected></option>`;
            lista_clientes.forEach(element => {
                res += `<option value="${element.id_cli_prov}">${element.nombre_empresa}</option>`;
            });
            res += `</select>`;
        } if (tipo == 2) {
            res = `<label for="fecha">Fecha Creación</label>
        <input type="date" class="form-control" name="fecha" id="fecha">`;
        } if (tipo == 3) {
            res = `<label for="num_pedido">Numero Pedido</label>
        <input type="text" class="form-control" name="num_pedido" id="num_pedido">`;
        }
        $('#ver_cliente').empty().html(res);
        if (tipo == 1) {
            $('#cliente').select2();
        }
    });
}

var listar_pedidos_asesor = function () {
    $("#form_consulta_pedidos").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#boton_consulta').html();
        var form = $("#form_consulta_pedidos").serializeArray();
        var valida_form = validar_formulario(form);
        if (valida_form) {
            btn_procesando('boton_consulta');
            $.ajax({
                url: `${PATH_NAME}/comercial/consultar_pedidos_asesor`,
                type: "POST",
                data: form,
                success: function (res) {
                    if (res == '') {
                        alertify.error('Estos datos no le pertenecen o no hay datos creados');
                        btn_procesando('boton_consulta', obj_inicial, 1);
                    } else {
                        btn_procesando('boton_consulta', obj_inicial, 1);
                        var table = $('#dt_historial_pedidos_asesor').DataTable({
                            "data": res,
                            "columns": [
                                { "data": "fecha_crea_p" },
                                { "data": "hora_crea" },
                                { "data": "num_pedido" },
                                { "data": "nombre_empresa" },
                                { "data": "orden_compra" },
                                { "data": "total_etiq", render: $.fn.dataTable.render.number('.', ',', 2, '$') },
                                { "data": "total_tec", render: $.fn.dataTable.render.number('.', ',', 2, '$') },
                                {
                                    "data": "nombre_estado_pedido",
                                    render: function (data, type, row) {
                                        return '<strong>' + row['nombre_estado_pedido'] + '</strong>';
                                    }
                                },
                                {
                                    "orderable": false,
                                    "defaultContent": '<button type="button" class="view btn btn-info btn-sm ver_p" type="button" data-toggle="collapse" data-target="#verPedido" aria-expanded="false" aria-controls="collapseExample" >' +
                                        '<i class="fas fa-search"></i>' +
                                        '</button> '
                                },
                                {
                                    "data": "id_estado_pedido",
                                    "searchable": false,
                                    "orderable": false,
                                    "render": function (data, type, row) {
                                        /*si el estado es devuelto sale icono de modificar*/
                                        if (row["id_estado_pedido"] == 10) {
                                            return '<button type="button" class=" view btn btn-success btn-sm modificar_p_asesor ">' +
                                                '<i class="fas fa-edit"></i>' +
                                                '</button> ';
                                        } else if (row["id_estado_pedido"] == 9 || row["id_estado_pedido"] == 3 || row["id_estado_pedido"] == 1) {
                                            return '';
                                        } else {
                                            return '<button type="button" class=" view btn btn-danger btn-sm pdf_pedido">' +
                                                '<span class="' + row['id_pedido'] + 's fas fa-file-download"></span>' +
                                                '</button> ';
                                        }
                                    }
                                }

                            ],
                        });
                    }
                }
            });
        }
    });


};

var detalle_pedido_creado = function () {
    $('#dt_historial_pedidos_asesor').on("click", "tr button.ver_p", function () {
        var data = $("#dt_historial_pedidos_asesor").DataTable().row($(this).parents("tr")).data();
        if ($(".tabla_h_pedido").is(":visible")) {
            $(".tabla_h_pedido").toggle(500);
            $(".detalle_pedido").toggle(500);
        }
        //el siguinte if se utiliza para la vista que esta en mis clientes en la ventana de pedidos que hace lo mismo que la vista historial de pedido
        if ($(".tabla_c_pedido_h").is(":visible")) {
            $(".tabla_c_pedido_h").toggle(500);
            $(".detalle_pedido_h").toggle(500);
        }
        $("#asesor").empty().html(data.nombre + " " + data.apellido);
        $("#num_pedidoPC").empty().html(data.num_pedido);
        $("#num_pedidoPC").empty().html(data.num_pedido);
        $("#nombre_cliente_h").empty().html(data.nombre_empresa);
        $("#fecha_pedido_h").empty().html(data.fecha_crea_p);
        $("#nit_cliente_h").empty().html(data.nit);
        $("#span_num_orden_compra_h").empty().html(data.orden_compra);
        $("#id_direccion_entre_PC").empty().html(data.nombre_ciudad + " " + data.direccion_entrega);
        $("#id_direccionPC").empty().html(data.direccion_radica);
        $("#infoCon_h").empty().html(data.contacto);
        $("#infoCar_h").empty().html(data.cargo);
        $("#infoEmail_h").empty().html(data.email);
        $("#infoCel_h").empty().html(data.celular);
        $("#infoTel_h").empty().html(data.telefono);
        $("#infoHorario_h").empty().html(data.horario);
        $("#infoFo_h").empty().html(data.horario);
        if (data.forma_pago == 1) {
            $("#infoFo_h").empty().html("Contado Efectivo");
        }
        if (data.forma_pago == 2) {
            $("#infoFo_h").empty().html("Contado Factura");
        }
        if (data.forma_pago == 3) {
            $("#infoFo_h").empty().html("Cheque Posfechado");
        }
        if (data.forma_pago == 4) {
            $("#infoFo_h").empty().html("Credito");
        }
        if (data.porcentaje != 0) {
            $("#span_parcial_h").empty().html("Si");
        } else {
            $("#span_parcial_h").empty().html("No");
        }
        $("#span_porcentaje_h").empty().html(data.porcentaje);
        var table1 = $('#dt_itemas_pedidos_asesor').DataTable({
            "ajax": {
                "url": `${PATH_NAME}/comercial/consultar_items_pedido`,
                "type": "POST",
                "data": { "id_pedido": data.id_pedido },
            },
            "columns": [
                { "data": "item" },
                { "data": "codigo" },
                { "data": "descripcion_productos" },
                { "data": "Cant_solicitada" },
                { "data": "ficha_tecnica" },
                { "data": "nombre_r_embobinado" },
                { "data": "nombre_core" },
                { "data": "cant_x" },
                {
                    "data": "trm",
                    "render": function (data, type, row) {
                        var trmFinal = "";
                        if (row["trm"] == 0.00) {
                            trmFinal = "";
                        } else {
                            trmFinal = parseFloat(row["trm"]).toFixed(2);
                            trmFinal = parseFloat(trmFinal).toLocaleString(undefined, { minimumFractionDigits: 2 });
                        }
                        return trmFinal;
                    }
                },
                { "data": "moneda" },
                { "data": "v_unidad", render: $.fn.dataTable.render.number('.', ',', 2, '$ ') },
                { "data": "total", render: $.fn.dataTable.render.number('.', ',', 2, '$ ') },
                { "data": "nombre_estado_item" },
                {
                    "render": function (data, type, row) {
                        /*si el estado es devuelto sale icono de modificar*/
                        return '<button type="button" class="btn btn-info  info_item_seguimiento" data-bs-toggle="modal" data-bs-target="#info_item"><i class="fa fa-search"></i></button>';
                    }
                },
            ],
        });


        $("#observaciones_h").empty().html(data.observaciones);
        $("#fecha_cierre_h").val(data.fecha_cierre);
        $("#fecha_compro_programado_h").val(data.fecha_compromiso);
        if (data.iva == 1) {
            $("#r_iva_h").empty().html("Si");
        } else {
            $("#r_iva_h").empty().html("No");
        }
    });
}

var seguimiento_items = function (tbody, table) {
    $("#dt_itemas_pedidos_asesor").on("click", "tr button.info_item_seguimiento", function () {
        var data = $('#dt_itemas_pedidos_asesor').DataTable().row($(this).parents("tr")).data();
        $("#dt_infor_item").DataTable({
            "ajax": {
                "url": `${PATH_NAME}/comercial/consultar_seguimiento_op_item`,
                "type": "POST",
                "data": { "num_pedido": data.num_pedido, "item": data.item },
            },
            "columns": [

                {
                    "data": "fecha_crea_seguimiento",
                    render: function (data, type, row) {
                        return row['fecha_crea'] + '-' + row['hora_crea'];
                    }
                },
                {
                    "data": "pedido_item",
                    render: function (data, type, row) {
                        return row['pedido'] + '-' + row['item'];
                    }
                },
                { "data": "nombre_area_trabajo" },
                { "data": "nombre_actividad_area" },
                { "data": "nombres" },
                { "data": "observacion" },
            ]
        });

    });
}

var generar_pdf_pedido = function () {
    $('#dt_historial_pedidos_asesor').on('click', 'tr button.pdf_pedido', function () {
        var data = $('#dt_historial_pedidos_asesor').DataTable().row($(this).parents("tr")).data();
        var params = JSON.stringify(data);
        $.ajax({
            url: `${PATH_NAME}/comercial/pdf_pedido`,
            method: "POST",
            data: { 'valores': params },
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: function (r) {

                $('.' + data.id_pedido + 's').removeClass('glyphicon glyphicon-download-alt');
                $('.' + data.id_pedido + 's').addClass('fa fa-spinner fa-spin fa-fw');

            },
            success: function (regreso) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(regreso);
                a.href = url;
                a.download = data.num_pedido + "_" + data.nombre_empresa + '.pdf';
                a.click();
                window.URL.revokeObjectURL(url);
                $('.' + data.id_pedido + 's').removeClass('fa fa-spinner fa-spin fa-fw');
                $('.' + data.id_pedido + 's').addClass('glyphicon glyphicon-download-alt');

            }
        });
    });

};
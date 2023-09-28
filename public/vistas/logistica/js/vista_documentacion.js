$(document).ready(function () {
    select_2();
    consultar_pedido();
    items_certificado();
    descarga_certificado();
    consulta_carta();
    agregar_serial();
    descarga_cartas();
    item_carta();
});

var consultar_pedido = function () {
    $('#certificado_calidad').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            var num_lista_empaque = $('#num_lista_empaque').val();
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/logistica/consulta_documentos`,
                data: form,
                success: function (respu) {
                    var table = $('#tabla_certifi_1').DataTable({
                        "data": respu,
                        columns: [
                            { "data": "codigo" },
                            { "data": "descripcion_productos" },
                            { "data": "cantidad_factura" },
                            {
                                "data": "n_produccion", render: function (data, type, row) {
                                    var res = row.n_produccion;
                                    if (row.n_produccion == 0) {
                                        res = `<input type="text" id="nuevo_lote${row.id_entrega}">`;
                                    }
                                    return res;
                                }
                            },
                            {
                                "orderable": false,
                                "defaultContent": `<div class="select_acob text-center">
                                                 <input type="checkbox" class="agrupar_items">
                                              </div>`
                            }
                        ]
                    });
                    $('#nombre_empresa').val(respu[0].nombre_empresa);
                    $('#orden_compra').val(respu[0].orden_compra);
                }
            });
        }
    });

}

var datos = [];

var items_certificado = function () {
    $('#tabla_certifi_1 tbody').on("click", "input.agrupar_items", function () {
        var data = $("#tabla_certifi_1").DataTable().row($(this).parents("tr")).data();
        if (data.n_produccion == 0) {
            var nuevo_lote = $(`#nuevo_lote${data.id_entrega}`).val();
            if (nuevo_lote == '' || nuevo_lote == 0) {
                $(this).prop('checked', false);
                alertify.error('Lo sentimos se requiere un lote para continuar.');
                return;
            } else {
                data.n_produccion = nuevo_lote;
            }
        }
        if ($(this).prop('checked') == true) {
            if (datos.length === 0) {
                datos.push(data);
            } else {
                var agrega = false;
                datos.forEach(element => {
                    if (element.id_clase_articulo == data.id_clase_articulo) {
                        agrega = true;
                    } else {
                        agrega = false;
                        $(this).prop('checked', false);
                    }
                });
                if (agrega) {
                    datos.push(data);
                } else {
                    alertify.error("Solo se pueden agrupar por la misma clase de articulo.");
                }
            }
        } else {
            for (var i = 0; i < datos.length; i++) {
                if (datos[i].id_clien_produc === data.id_clien_produc) {
                    datos.splice(i, 1);
                }
            }
        }
    });
}

var descarga_certificado = function () {
    $('#formulario_certificados').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#boton_certificado').html();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            form = $(this).serialize();
            if (datos.length === 0) {
                alertify.error("Debe elegir algun item para continuar.");
                return;
            }
            btn_procesando('boton_certificado');
            $.ajax({
                url: `${PATH_NAME}/logistica/descarga_certificado`,
                type: 'POST',
                data: { form, datos },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (res) {
                    if (res.size == 1) {
                        alertify.error("Lo sentimos este item ya contiene un certificado generado.");
                    } else {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(res);
                        a.href = url;
                        a.download = 'certificado.pdf';
                        a.click();
                        window.URL.revokeObjectURL(url);
                    }
                    btn_procesando('boton_certificado', obj_inicial, 1);
                    datos = [];
                    $('#consulta_certificado').click();
                    $('#vencimiento').val('');
                }
            });
        }
    });
}

var consulta_carta = function () {
    $('#carta_garantia').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            var num_lista_empaque = $('#num_lista_carta').val();
            $.ajax({
                type: "POST",
                url: `${PATH_NAME}/logistica/consulta_documentos`,
                data: { num_lista_empaque },
                success: function (respu) {
                    var table = $('#tabla_carta_1').DataTable({
                        "data": respu,
                        columns: [
                            { "data": "codigo" },
                            { "data": "descripcion_productos" },
                            { "data": "cantidad_factura" },
                            {
                                "orderable": false,
                                "defaultContent": `<div class="select_acob text-center">
                                                 <input type="checkbox" class="agrupar_items">
                                              </div>`
                            }
                        ]
                    });
                    var primer_documento = respu[0].num_factura;
                    if (primer_documento == 0) {
                        primer_documento = respu[0].num_remision;
                    }
                    $('#nombre_empresa_carta').val(respu[0].nombre_empresa);
                    $('#orden_compra_carta').val(primer_documento);
                    items_carta = [];
                }
            });
        }
    });
}

var items_carta = [];

var item_carta = function () {
    $('#tabla_carta_1 tbody').on("click", "input.agrupar_items", function () {
        var data = $("#tabla_carta_1").DataTable().row($(this).parents("tr")).data();
        if (data.n_produccion == 0) {
            var nuevo_lote = $(`#nuevo_lote${data.id_entrega}`).val();
            if (nuevo_lote == '' || nuevo_lote == 0) {
                $(this).prop('checked', false);
                alertify.error('Lo sentimos se requiere un lote para continuar.');
                return;
            } else {
                data.n_produccion = nuevo_lote;
            }
        }
        if ($(this).prop('checked') == true) {
            if (items_carta.length === 0) {
                if (data.id_clase_articulo == 3) {
                    items_carta.push(data);
                } else {
                    $(this).prop('checked', false);
                    alertify.error("Solo se pueden generar la carta a la tecnologia.");
                }
            } else {
                if (data.id_clase_articulo == 3) {
                    $(this).prop('checked', false);
                    alertify.error("Solo se pueden generar la carta por item.");
                } else {
                    $(this).prop('checked', false);
                    alertify.error("Solo se pueden generar la carta a la tecnologia.");
                }
            }
        } else {
            for (var i = 0; i < items_carta.length; i++) {
                if (items_carta[i].id_clien_produc === data.id_clien_produc) {
                    items_carta.splice(i, 1);
                }
            }
        }
        var cantidad_items = 0;
        items_carta.forEach(element => {
            cantidad_items = cantidad_items + element.cantidad_factura;
        });
        $('#cantidad_item').val(cantidad_items);
    });
}

var numero = 0;
var agregar_serial = function () {
    $('#agrega_serial').on('click', function () {
        numero = numero + 1;
        $("#sn1").append(`<div class="mb-3" id="linea${numero}">
            <div class="row">
                <label class="col-2" for="seriales">Serial ${numero}:</label>
                <div class="col-10">
                    <div class="input-group">
                        <input type="text" class="form-control" id="input${numero}" name="seriales">
                        <button class="btn btn btn-danger" type="button" id="elimina" onclick="javascript:eliminar(${numero})"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
            </div>
        </div>`);
        verifica_serial();
    });
}

var verifica_serial = function () {
    $('#sn1 input').change(function () {
        var $current = $(this);
        $('#sn1 input').each(function () {
            if ($(this).val() == $current.val() && $(this).attr('id') != $current.attr('id')) {
                alertify.error('Serial Ya Tomado.');
                eliminar(numero);
            }
        });
    });
    var cantidad = $("#cantidad_item").val();
    var cuenta = ($('#sn1 input').length);
    if (cuenta == cantidad) {
        alertify.success('Ya Capturo Todos Los Seriales.');
        $("#agrega_serial").fadeOut();
    }
}

function eliminar(numero2) {
    $("#linea" + numero2).remove();
    $("#agrega_serial").fadeIn();
}

var descarga_cartas = function () {
    $('#formulario_cartas').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#boton_carta').html();
        var form = $(this).serializeArray();
        var exepcion = ['seriales[]'];
        var valida = validar_formulario(form, exepcion);
        if (valida) {
            if (items_carta.length === 0) {
                alertify.error("Debe elegir algun item para continuar.");
                return;
            }
            var respu = false;
            $('#sn1 input').each(function () {
                if ($(this).val() == 0 || $(this).val() == '') {
                    respu = true;
                }
            });
            if (respu) {
                alertify.error("Debe capturar todos los seriales para continuar.");
                return;
            }
            btn_procesando('boton_carta');
            $.ajax({
                url: `${PATH_NAME}/logistica/descarga_carta`,
                type: 'POST',
                data: { form, items_carta },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (res) {
                    if (res.size == 1) {
                        alertify.error("Lo sentimos este producto no contiene meses de garantia.");
                    } else if (res.size == 0) {
                        alertify.error("Lo sentimos este documento ya contiene un carta generada.");
                    } else {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(res);
                        a.href = url;
                        a.download = 'carta.pdf';
                        a.click();
                        window.URL.revokeObjectURL(url);
                    }
                    btn_procesando('boton_carta', obj_inicial, 1);
                    datos = [];
                    var cuenta = ($('#sn1 input').length);
                    for (let i = 1; i < cuenta; i++) {
                        eliminar(i);
                    }
                    $('#consulta_carta').click();
                    $('#cantidad_item').val('');
                    $('#input').val('');
                }
            });
        }
    });
}
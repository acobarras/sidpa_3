$(document).ready(function () {
    select_2();
    consultar_pedido();
    items_certificado();
    descarga_certificado();
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
                    $('#formulario_remarcacion').css('display', '');
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
        console.log(data);
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
$(document).ready(function () {
    listar_items();
    select_2();
    asigna_precios();
    busca_productos_asesor();
    modificar_precio();
    selecciona_items_autorizar();
    enviar_items_autorizar();
});

var listar_items = function () {

    var table = $('#dt_tabla_ajuste').DataTable({
        'ajax': {
            'url': `${PATH_NAME}/compras/consulta_ajuste_precio`
        },
        "columns": [
            {
                "data": "nombre", render: function (date, type, row) {
                    return `${row['nombre']} ${row['apellido']}`;
                }
            },
            { "data": "nombre_empresa" },
            { "data": "codigo_producto" },
            { "data": "descripcion_productos" },
            { "data": "nombre_core" },
            { "data": "presentacion", render: $.fn.dataTable.render.number('.', ',', '', '') },
            {
                "data": "moneda_autoriza", render: function (date, type, row) {
                    if (row['moneda_autoriza'] == 1) {
                        return `<b>Pesos</b>`;
                    }
                    if (row['moneda_autoriza'] == 2) {
                        return `<b>Dolar</b>`;
                    }
                    if (row['moneda_autoriza'] == 0 || row['moneda_autoriza'] == '') {
                        return `<b style="color:red;padding: 5px 10px;border:  0px solid;" >Sin asignar</b>`;
                    }
                }
            },
            {
                "data": "precio_autorizado", render: function (date, type, row) {
                    if (row['precio_autorizado'] == 0.00 || row['precio_autorizado'] == '') {
                        return `<b style="color:red" >Sin asignar</b>`;
                    } else {
                        return row['precio_autorizado'];
                    }

                }
            },
            { "data": "cantidad_minima" },
            { "data": "precio_venta", render: $.fn.dataTable.render.number('.', ',', 2) },
            {
                "data": "moneda", render: function (date, type, row) {
                    if (row['moneda'] == 1) {
                        return `<b>Pesos</b>`;
                    }
                    if (row['moneda'] == 2) {
                        return `<b>Dolar</b>`;
                    }
                    if (row['moneda'] == 0 || row['moneda'] == '') {
                        return `<b style="color:red" >Sin asignar</b>`;
                    }

                }
            },
            {
                "data": "checkbox", render: (data, type, row) => {
                    return `<div class="select_acob text-center">
                    <input class="agregar_items" type="checkbox" name="precio_autoriza${row.id_clien_produc}" value="${row.id_clien_produc}"/>
                </div>`;
                }
            },
        ],
    });
}

var datos = [];
var selecciona_items_autorizar = function () {
    $('#dt_tabla_ajuste tbody').on("click", "input.agregar_items", function () {
        var data = $("#dt_tabla_ajuste").DataTable().row($(this).parents("tr")).data();
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
                        alertify.error("Solo se pueden agrupar por la misma clase de articulo.");
                    }
                });
                if (agrega) {
                    datos.push(data);
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

var enviar_items_autorizar = function () {
    $("#asigna_precios").on('click', function () {
        if (datos.length === 0) {
            alertify.error("Debe elegir algun item para continuar.");
        } else {
            if (datos[0].id_clase_articulo == 3) {
                $("#div_material_ajuste").css('display','none')
            }else{
                $("#div_material_ajuste").css('display','block')
            }
            $("#AsignaPrecioModal").modal("show");
        }
    });
}

var asigna_precios = function () {
    $("#form_asigna_precio").on('submit', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var form1 = $(this).serialize();
        var exepccion = '';
        if (datos[0].id_clase_articulo ==3) {
            exepccion = ['id_material'];
        }
        var valida = validar_formulario(form, exepccion);
        if (valida) {
            var obj_inicial = $(`#btn_asignar_precio`).html();
            btn_procesando(`btn_asignar_precio`);
            $.ajax({
                url: `${PATH_NAME}/compras/ajuste_precio`,
                type: "POST",
                data: { datos, form1 },
                success: function (res) {
                    if (res.state == 1) {
                        $("#dt_tabla_ajuste").DataTable().ajax.reload(function () {
                            $("#AsignaPrecioModal").modal('hide');
                            btn_procesando(`btn_asignar_precio`, obj_inicial, 1);
                            $("#form_asigna_precio")[0].reset();
                            limpiar_formulario('form_asigna_precio', 'select');
                            alertify.success(res.msg);
                        });
                    } else {
                        $("#dt_tabla_ajuste").DataTable().ajax.reload(function () {
                            btn_procesando(`btn_asignar_precio`, obj_inicial, 1);
                            alertify.error(res.msg);
                        });
                    }
                    datos = [];
                }
            });
        }
    });
}

//------------------------------------------------------ FIN ASIGNACION DE PRECIOS ------------------------------------------------------------->
var busca_productos_asesor = function () {
    $("#btn_busca_prod_asesor").on('click', function () {
        var id = $("#id_usuario_asesor").val();
        var table1 = $('#dt_tabla_productos').DataTable({
            "ajax": {
                "url": `${PATH_NAME}/compras/consulta_asesores_id?id=${id}`,
            },
            "columns": [
                {
                    "data": "nombre", render: function (date, type, row) {
                        return `${row['nombre']} ${row['apellido']}`;
                    }
                },
                { "data": "nombre_empresa" },
                { "data": "codigo_producto" },
                { "data": "descripcion_productos" },
                { "data": "nombre_core" },
                { "data": "presentacion" },
                {
                    "data": "moneda_autoriza", render: function (date, type, row) {
                        if (row['moneda_autoriza'] == 1) {
                            return `<b>Pesos</b>`;
                        }
                        if (row['moneda_autoriza'] == 2) {
                            return `<b>Dolar</b>`;
                        }
                    }
                },
                { "data": "precio_autorizado" },
                { "data": "cantidad_minima" },
                { "data": "precio_venta" },
                {
                    "data": "moneda", render: function (date, type, row) {
                        if (row['moneda'] == 1) {
                            return `<b>Pesos</b>`;
                        }
                        if (row['moneda'] == 2) {
                            return `<b>Dolar</b>`;
                        }
                    }
                },
                {
                    "orderable": false,
                    "defaultContent": `<button class="btn btn-primary modificar_por_asesor"><i class="fa fa-edit"></i></button>`
                }
            ],
        });
        carga_modificar_precio();
    });
}

var carga_modificar_precio = function () {
    $('#dt_tabla_productos tbody').on("click", "button.modificar_por_asesor", function () {
        var data = $('#dt_tabla_productos').DataTable().row($(this).parents("tr")).data();

        $("#modificaPrecioModal").modal("show");
        $(".codigo_productoD").empty().html(data.codigo_producto);
        $(".descripcion_productosD").empty().html(data.descripcion_productos);
        $("#id_moneda_autoriza").val(data.moneda_autoriza);
        $("#id_cantidad_minima").val(data.cantidad_minima);
        $("#id_precio_autorizado").val(data.precio_autorizado);
        $("#id_clien_produc").val(data.id_clien_produc);
        $("#id_id_material").val(data.id_material).trigger('change');
    });
}
var modificar_precio = function () {
    $("#form_modifica_producto").on('submit', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var exepccion = ['id_material'];
        var valida = validar_formulario(form, exepccion);
        if (valida) {
            var obj_inicial = $(`#btn_modifica_precio`).html();
            btn_procesando(`btn_modifica_precio`);
            $.ajax({
                url: `${PATH_NAME}/compras/modificar_producto`,
                type: "POST",
                data: form,
                success: function (res) {
                    if (res.state == 1) {
                        $("#dt_tabla_productos").DataTable().ajax.reload(function () {
                            $("#modificaPrecioModal").modal('hide');
                            btn_procesando(`btn_modifica_precio`, obj_inicial, 1);
                            $("#form_modifica_producto")[0].reset();
                            limpiar_formulario('form_modifica_producto', 'select');
                            alertify.success(res.msg);
                        });
                    } else {
                        $("#dt_tabla_productos").DataTable().ajax.reload(function () {
                            btn_procesando(`btn_modifica_precio`, obj_inicial, 1);
                            alertify.error(res.msg);
                        });

                    }
                }
            });
        }

    });
}


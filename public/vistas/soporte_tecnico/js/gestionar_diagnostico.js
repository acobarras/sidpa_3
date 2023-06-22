$(document).ready(function () {
    consultar_datos_item();
    boton_regresar();
    select_2();
    enviar_items_cotizacion();
});

var consultar_datos_item = function () {
    $.ajax({
        url: `${PATH_NAME}/soporte_tecnico/consultar_datos_item`,
        type: "GET",
        success: function (res) {
            var table = $("#tb_gestion_diag").DataTable({
                "data": res['data'],
                "columns": [
                    {
                        "render": function (data, type, row) {
                            return `${row.id_diagnostico}-${row.item}`;
                        }
                    },
                    { "data": "nombre_empresa" },
                    { "data": "equipo" },
                    { "data": "serial_equipo" },
                    { "data": "procedimiento" },
                    { "data": "nombre_estado_soporte" },
                    {
                        "render": function (data, type, row) {
                            return `<center>
                            <div class="select_acob text-center">
                                    <input type="checkbox" class="items_selec" id="diagnostico${row.id_diagnostico}" name='diagnostico${row.id_diagnostico}' value="${row.item}">
                                </div>
                        <center>`
                        }
                    },
                ]
            });
            validar_check();
            cotizar();
        }
    });
}
var array_item = [];
var validar_check = function () {
    $('#tb_gestion_diag tbody').on("click", "tr input.items_selec", function () {
        var data = $('#tb_gestion_diag').DataTable().row($(this).parents("tr")).data();
        var id_diagnostico = data['id_diagnostico'];
        var cant = data['total_items'];
        if ($(this).prop('checked') == true) {
            if (array_item.length == ['']) {
                array_item.push(data);
            } else {
                array_item.forEach(element => {
                    if (element.id_diagnostico == id_diagnostico) {
                        if (array_item.length < cant) {
                            array_item.push(data);
                        }
                    } else {
                        $(this).prop('checked', false);
                        alertify.error("Los items seleccionados no pertenecen al mismo diagnostico");
                        return;
                    }
                });
            }
        } else {
            for (var i = 0; i < array_item.length; i++) {
                if (array_item[i].item === data.item) {
                    array_item.splice(i, 1);
                }
            }
        }
    });
}

var cotizar = function () {
    $('#cotizar_repu').on("click", function () {
        var cant_item_array = array_item.length;
        if (cant_item_array === 0) {
            alertify.error('Debe seleccionar un item para realizar la cotización');
            return;
        } else {
            var resta = array_item[0].total_items - cant_item_array;
            var numero = array_item[0].total_items - resta;
            if (cant_item_array < array_item[0].total_items) {
                alertify.confirm(`ALERTA ACOBARRAS`, `¿Esta seguro que quiere realizar la cotizacion a ${numero} equipo de un total de ${array_item[0].total_items} equipos del diagnostico?`, function () {
                    mostrar_formulario();
                }, function () { alertify.error('Cancelado') })
                    .set('labels', { ok: 'Si', cancel: 'No' });
            } else {
                mostrar_formulario();
            }
        }
    })
}

var cargar_productos = function (datos) {
    var productos_disp = '<option value="0">Elija un producto</option>';
    $.ajax({
        "url": `${PATH_NAME}/soporte_tecnico/cargar_productos`,
        "type": 'GET',
        success: function (res) {
            res.forEach(element => {
                productos_disp /*html*/ += `<option value='${JSON.stringify(element)}'>
                ${element.codigo_producto} | ${element.descripcion_productos}
                </option>`;
            });
            for (let i = 0; i < datos.length; i++) {
                var element = datos[i];
                $(`.productos_tecno${element.item}`).empty().html(productos_disp);
            }
        }
    });
}

var cargar_moneda = function (datos) {
    var moneda = '';
    for (let i = 0; i < datos.length; i++) {
        var element = datos[i];
        moneda /*html*/ += ` 
             <option value="1">Pesos</option>
                <option value="2">Dolar</option>`;
        $(`#moneda${element.item}`).empty().html(moneda);
    }

}

var mostrar_formulario = function () {
    var datos = array_item;
    var vista = '';
    $('#principal_gestion').css('display', 'none');
    $('#titulo').html(`${datos[0].nombre_empresa}`);
    $('#cotizar').css('display', '');
    for (let i = 0; i < datos.length; i++) {
        var element = datos[i];
        var tabla = `tabla_productos${element.item}`;
        vista /*html*/ += `
                        <div class="col-md-12">
                            <form id="formulario_articulo${element.item}">
                                <div class="col-md-12" id="titulo_articulo">
                                    <h2>Articulo ${element.item}<span style="color: red;" id="nombre_articulo"> ${element.equipo}<span style="color:#302b63"> S/N: ${element.serial_equipo}</span></span></h2>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <label for="producto${element.item}" class="form-label">Producto:</label>
                                        <select class="form-select select_2 productos_tecno${element.item}" id="productos${element.item}" name="producto${element.item}">
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <label class="form-label" for="moneda${element.item}">Moneda:</label>
                                        <select class="form-select" name="moneda${element.item}" data-item="${element.item}" id="moneda${element.item}">
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <label class="form-label" for="precio${element.item}">Precio:</label>
                                        <input type="text" class="form-control bg-white" name="precio${element.item}" id="precio${element.item}">
                                    </div>
                                    <div class="col-md-1 col-sm-12">
                                        <label class="form-label" for="cantidad${element.item}">Cantidad:</label>
                                        <input type="text" class="form-control bg-white" name="cantidad${element.item}" id="cantidad${element.item}">
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <button class="btn btn-success agregar_producto" data-item_boton='${element.item}' data-id='${JSON.stringify(element)}' type="button">Agregar</button>
                                    </div>
                                </div>
                            </form>
                            <table id="${tabla}" style="background: white; width: 100%;" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Moneda</th>
                                        <th>Precio</th>
                                        <th>Opción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>`;
    }
    $('#formulario_articulos').empty().html(vista);
    cargar_productos(datos);
    cargar_moneda(datos);
    cargar_tabla(datos);
    cargar_datos_tabla();
    select_2();
}

var cargar_datos_tabla = function () {
    $(".agregar_producto").on("click", function () {
        var datos = JSON.parse($(this).attr("data-id"));
        var item = $(this).attr("data-item_boton");
        var moneda = $(`#moneda${item}`).val();
        var valor = $(`#precio${item}`).val();
        var cantidad = $(`#cantidad${item}`).val();
        var producto = JSON.parse($(`.productos_tecno${item}`).val());
        var form = $(`#formulario_articulo${item}`).serializeArray();
        var valida = validar_formulario(form);
        var tabla_global = localStorage.getItem('articulo' + datos['id_diagnostico'] + '-' + item);
        var array_productos = [];
        if (valida) {
            var envio = {
                'moneda': moneda,
                'valor': valor,
                'cantidad': cantidad,
                'producto': producto,
                'item': item,
                'producto': producto,
            };
            if (tabla_global == null && Array.isArray(tabla_global) == false) {
                array_productos.push(envio);
                localStorage.setItem('articulo' + datos['id_diagnostico'] + '-' + item, JSON.stringify(array_productos));
            } else {
                array_productos = JSON.parse(tabla_global);
                var respu = false;
                array_productos.forEach(element => {
                    if (element.producto.id_productos == envio.producto.id_productos) {
                        var nueva_cantidad = parseInt(envio.cantidad) + parseInt(element.cantidad);
                        element.cantidad = nueva_cantidad;
                        localStorage.setItem('articulo' + datos['id_diagnostico'] + '-' + item, JSON.stringify(array_productos));
                    } else {
                        respu = true;
                    }
                });
                if (respu) {
                    array_productos.push(envio);
                    localStorage.setItem('articulo' + datos['id_diagnostico'] + '-' + item, JSON.stringify(array_productos));
                    array_productos = [];
                }
            }
            mostrar_formulario();
        }
    });
}

var cargar_tabla = function (datos) {
    for (let i = 0; i < datos.length; i++) {
        var element = datos[i];
        var nuevo_storage = JSON.parse(localStorage.getItem('articulo' + element['id_diagnostico'] + '-' + element.item));
        var table = $(`#tabla_productos${element.item}`).DataTable({
            "data": nuevo_storage,
            "columns": [
                {
                    "data": "producto", render: (date, type, row) => {
                        var nombre = row['producto']['codigo_producto'] + row['producto']['descripcion_productos'];
                        return nombre;
                    }
                },
                { "data": "cantidad" },
                {
                    "data": "moneda", render: (date, type, row) => {
                        if (row.moneda == 1) {
                            return 'Pesos';
                        } else {
                            return 'Dolar';
                        }
                    }
                },
                { "data": "valor" },
                {
                    "defaultContent":
                        `<center>
                            <button class="btn btn-danger btn-sm btn-circle elimina_item"><i class="fa fa-times"></i></button>
                        </center>`
                },

            ],
        });
        elimina_items(`#tabla_productos${element.item} tbody`, table, datos);
    }
}

var boton_regresar = function () {
    $('#regresar').on('click', function (e) {
        e.preventDefault();
        window.location.reload();
    });
}

var elimina_items = function (tbody, table, datos) {
    $(tbody).on("click", "button.elimina_item", function () {
        var data = table.row($(this).parents("tr")).data();
        for (let i = 0; i < datos.length; i++) {
            const element = datos[i];
            var storage = JSON.parse(localStorage.getItem('articulo' + element.id_diagnostico + '-' + data.item));
            storage.forEach(element => {
                if (element.producto.id_productos == data.producto.id_productos) {
                    var index = storage.indexOf(element);
                    if (index > -1) {
                        storage.splice(index, 1);
                    }
                }
            });
            localStorage.setItem('articulo' + element.id_diagnostico + '-' + data.item, JSON.stringify(storage));
            mostrar_formulario();
        }
    });
};
var enviar_items_cotizacion = function () {
    $('.enviar_cotizacion').on("click", function () {
        var data = array_item;
        var valor_boton = $(this).val();
        var array_storage = [];
        for (let i = 0; i < data.length; i++) {
            const element = data[i];
            var storage = JSON.parse(localStorage.getItem('articulo' + element.id_diagnostico + '-' + element.item));
            array_storage.push(storage);
        }
        if (valor_boton == 1) {
            var estado = 2
            var boton_procesando = 'enviar_cotizacion';
            enviar_ajax(boton_procesando, estado, array_storage);
        } else {
            alertify.confirm(`ALERTA ACOBARRAS`, `¿Es un comodato o una garantia?`, function () {
                if (array_storage == [] || array_storage[0] == null) {
                    alertify.error('Debe agregar repuestos para continuar');
                    return;
                } else {
                    var estado = 3;
                    var boton_procesando = 'continuar_diag';
                    enviar_ajax(boton_procesando, estado, array_storage);
                }
            }, function () {
                var estado = 4;
                var boton_procesando = 'continuar_diag';
                enviar_ajax(boton_procesando, estado, array_storage);
            })
                .set('labels', { ok: 'Si', cancel: 'No' });
        }
    })
}

var enviar_ajax = function (boton_procesando, estado, array_storage) {
    if (array_storage[0] == null && estado == 2) {
        alertify.error('Debe seleccionar un repuesto para realizar la cotización');
        return;
    } else {
        var datos = array_item;
        var obj_inicial = $(`#${boton_procesando}`).html();
        btn_procesando(`${boton_procesando}`);
        if (estado === 2) {
            $.ajax({
                "url": `${PATH_NAME}/soporte_tecnico/enviar_items_cotizacion`,
                "type": 'POST',
                "data": { estado, datos, array_storage },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (res) {
                    for (let i = 0; i < datos.length; i++) {
                        const element1 = datos[i];
                        localStorage.removeItem('articulo' + element1.id_diagnostico + '-' + element1.item);
                    }
                    btn_procesando(`${boton_procesando}`, obj_inicial, 1);
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(res);
                    a.href = url;
                    a.download = 'Cotizacion' + datos[0]['id_diagnostico'] + '.pdf';
                    a.click();
                    window.URL.revokeObjectURL(url);
                    window.location.href = `${PATH_NAME}/vista_aprobacion`;
                }
            });
        }
        else {
            $.ajax({
                "url": `${PATH_NAME}/soporte_tecnico/enviar_items_cotizacion`,
                "type": 'POST',
                "data": { estado, datos, array_storage },
                success: function (res) {
                    if (res.status == -1) {
                        alertify.success(res.msg);
                        window.location.href = `${PATH_NAME}/vista_cierre_diag`;

                    } if (res.status == -2) {
                        btn_procesando(`${boton_procesando}`, obj_inicial, 1);
                        window.location.href = `${PATH_NAME}/vista_aprobacion`;
                    }
                    else {
                        for (let i = 0; i < datos.length; i++) {
                            const element2 = datos[i];
                            localStorage.removeItem('articulo' + element2.id_diagnostico + '-' + element2.item);
                        }
                    }
                }
            });
        }
    }
}

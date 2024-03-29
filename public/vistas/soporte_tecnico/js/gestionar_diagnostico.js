$(document).ready(function () {
    consultar_datos_item();
    boton_regresar();
    select_2();
    enviar_items_cotizacion();
});

var consultar_datos_item = function () { //ok
    var tabla = $("#tb_gestion_diag").DataTable({
        "ajax": {
            "url": `${PATH_NAME}/soporte_tecnico/consultar_datos_item`,
            "type": "GET",
        }, "columns": [
            {
                "render": function (data, type, row) {
                    return `${row.id_diagnostico}-${row.item}`;
                }
            },
            { "data": "nombre_empresa" },
            { "data": "equipo" },
            { "data": "serial_equipo" },
            { "data": "procedimiento" },//observaciones este no existe en soporte ingreso laboratorio 
            { "data": "accesorios" },
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

var array_item = [];
function validar_check() { //ok
    $('#tb_gestion_diag tbody').on("click", "tr input.items_selec", function () {
        var data = $('#tb_gestion_diag').DataTable().row($(this).parents("tr")).data();
        var id_diagnostico = data['id_diagnostico'];
        array_item = [];
        var alerta = false
        $('.items_selec:checked').each(function () {
            data1 = $('#tb_gestion_diag').DataTable().row($(this).parents("tr")).data();
            if (data1['id_diagnostico'] == id_diagnostico) {
                array_item.push(data1);// Agregar los valores al array
            } else {
                $(this).prop('checked', false);
                alerta = true
            }
        });
        if (alerta) alertify.error('¡Los items seleccionados no pertenecen al mismo diagnostico!')
    })
}


var cotizar = function () { //ok
    $('#cotizar_repu').on("click", function () {
        var cant_item_array = array_item.length;
        if (cant_item_array === 0) {
            alertify.error('Debe seleccionar un item para realizar la cotización');
            return;
        } else if (cant_item_array < array_item[0].total_items) {
            alertify.confirm(`ALERTA SIDPA`, `¿Esta seguro que quiere realizar la cotización a ${cant_item_array} equipo de un total de ${array_item[0].total_items} equipos del diagnostico?`, function () {
                mostrar_formulario();
            }, function () {
                alertify.error('Cancelado')
            }).set('labels', { ok: 'Si', cancel: 'No' });
        } else {
            mostrar_formulario();
        }
    })
}


var mostrar_formulario = function () {//ok
    var datos = array_item;
    var vista = '';
    $('#principal_gestion').css('display', 'none');
    $('#titulo').html(`${datos[0].nombre_empresa}`);
    $('#cotizar').css('display', '');
    datos.forEach(element => {
        var tabla = `tabla_productos${element.item}`;
        vista /*html*/ += `
                        <div class="col-md-12">
                            <div class="alert alert-primary" role="alert">
                                <p class="mb-0" ><b>Observaciones y accesorios: </b>${element.accesorios}<br>
                                <b>Procedimiento: </b>${element.procedimiento}</p>
                            </div>
                                <form id="formulario_articulo${element.item}">
                                    <div class="col-md-12" id="titulo_articulo">
                                        <h2>Articulo ${element.item}<span style="color: red;"> ${element.equipo}<span style="color:#302b63"> S/N: ${element.serial_equipo}</span></span></h2>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 col-sm-12">
                                            <label for="producto" class="form-label">Producto:</label>
                                            <select class="form-select select_2 productos_tecno${element.item}" id="productos${element.item}" name="producto">
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <label class="form-label" for="moneda">Moneda:</label>
                                            <select class="form-select" name="moneda" data-item="${element.item}" id="moneda${element.item}">
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <label class="form-label" for="valor">Precio:</label>
                                            <input type="number" class="form-control bg-white" name="valor" id="valor${element.item}">
                                        </div>
                                        <div class="col-md-1 col-sm-12">
                                            <label class="form-label" for="cantidad">Cantidad:</label>
                                            <input type="number" class="form-control bg-white" name="cantidad" id="cantidad${element.item}">
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <button class="btn btn-success agregar_producto" title='Agregar producto' data-item_boton='${element.item}' data-id='${JSON.stringify(element)}' type="button">Agregar</button>
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

    });
    $('#formulario_articulos').empty().html(vista);
    cargar_productos(datos);
    cargar_moneda(datos);
    cargar_tabla(datos);
    cargar_datos_tabla();
    select_2();
}

var cargar_productos = function (datos) {//ok
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
            datos.forEach(element => {
                $(`.productos_tecno${element.item}`).empty().html(productos_disp);
            });
        }
    });
}

var cargar_moneda = function (datos) {//ok
    var moneda = `<option value="1">Pesos</option>
                <option value="2">Dolar</option>`;
    datos.forEach(element => {
        $(`#moneda${element.item}`).empty().html(moneda);
    });
}

var cargar_tabla = function (datos) { //ok no voy a cambiar el for de este
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
                            <button title='Eliminar item' class="btn btn-danger btn-sm btn-circle elimina_item"><i class="fa fa-times"></i></button>
                        </center>`
                },

            ],
        });
        elimina_items(`#tabla_productos${element.item} tbody`, table, datos);
    }
}

function cargar_datos_tabla() {
    $(".agregar_producto").on("click", function () {
        var datos = JSON.parse($(this).attr("data-id"));
        var item = $(this).attr("data-item_boton");
        var form = $(`#formulario_articulo${item}`).serializeArray();
        var exepcion = ['valor'];
        var valida = validar_formulario(form, exepcion);
        var envio = form.reduce(function (a, z) { a[z.name] = z.value; return a; }, {});
        if (valida) {
            envio.producto = JSON.parse(envio.producto);
            envio.item = item;
            if (envio.valor == '') { envio.valor = 0 };
            var tabla_global = localStorage.getItem('articulo' + datos['id_diagnostico'] + '-' + item);
            var array_productos = [];
            if ((tabla_global == null || tabla_global == '[]') && Array.isArray(tabla_global) == false) {
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
    })
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
        var estado = '';
        var data = array_item;
        var valor_boton = $(this).val();
        var array_storage = [];
        for (let i = 0; i < data.length; i++) {
            const element = data[i];
            var storage = JSON.parse(localStorage.getItem('articulo' + element.id_diagnostico + '-' + element.item));
            if (storage != null) { array_storage.push(storage); }
        }
        if (array_storage.length == 0 || (array_storage.length == 1 && array_storage[0].length == 0) || data.length != array_storage.length) {// sin repuestos
            alertify.error('Debe agregar repuestos para continuar');
            return;
        } else {
            if (valor_boton == 1) {//cotizar
                var boton_procesando = 'enviar_cotizacion';
                estado = 2 // generar un PDF y queda en espera de aprobacion
                enviar_ajax(boton_procesando, estado, array_storage);
            } else {//no cotizar
                var boton_procesando = 'continuar_diag';
                alertify.confirm(`ALERTA SIDPA`, `¿Es un comodato o una garantia?`, function () {// si es comodato
                    estado = 3;// validacion piezas 
                    enviar_ajax(boton_procesando, estado, array_storage);
                }, function () {//no es comodato
                    estado = 6;// Se cambio de 7 DSR a 6 Pendiente Acta Entrega
                    enviar_ajax(boton_procesando, estado, array_storage);
                }).set({
                    'labels': { ok: 'Si', cancel: 'No' },
                    'invokeOnCloseOff': true
                });
            }
        }
    })
}

var enviar_ajax = function (boton_procesando, estado, array_storage) {
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
                a.download = 'Cotización-' + datos[0]['num_consecutivo'] + '.pdf';
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
                for (let i = 0; i < datos.length; i++) {
                    const element2 = datos[i];
                    localStorage.removeItem('articulo' + element2.id_diagnostico + '-' + element2.item);
                }
                if (res.status == -1) {//DSR
                    alertify.success(res.msg);
                    window.location.href = `${PATH_NAME}/vista_cierre_diag`;

                } else if (res.status == -2) { // COMODATO
                    btn_procesando(`${boton_procesando}`, obj_inicial, 1);
                    window.location.href = `${PATH_NAME}/validacion_repuestos`;
                }


            }
        });
    }
}

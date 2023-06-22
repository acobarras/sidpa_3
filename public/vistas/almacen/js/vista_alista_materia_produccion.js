$(document).ready(function () {
    genera_documento();
    validar_codigo_etiq();
    select_2();
    crea_tabla();
    crea_items();
});
var storage = [];

var crea_items = function () {
    $("#etiqueta").on('submit', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var exepcion = ['cav', 'cor', 'rollos_x', 'id_productos'];
        var valida = validar_formulario(form, exepcion);
        if (valida) {
            var descrip = $("#btn_ingresar_eti").attr('data-valida');
            if (descrip == 'true') {
                var items = {
                    documento: 'Memorando Interno Entrega',
                    ubicacion: $("#ubicacion").val(),
                    codigo_producto: $("#codigo_producto").val(),
                    id_producto: $("#id_producto").val(),
                    salida: $("#salida").val(),
                    descripcion: $("#respuesta").html(),
                    cav: $("#cav").val(),
                    cor: $("#cor").val(),
                    rollos_x: $("#rollos_x").val(),
                    valor_uni: 0.00,
                    valor_total: 0.00,
                }

                var items_storage = JSON.parse(localStorage.getItem('items_memorando_entrega'));
                if (items_storage != null) {
                    storage = items_storage;
                }
                if (storage == '') {
                    storage.push(items);
                } else {
                    var respu = false;
                    storage.forEach(element => {
                        if (element.id_producto == items.id_producto) {
                            var nuevo = parseInt(element.salida) + parseInt(items.salida);
                            element['salida'] = nuevo;
                            respu = true;
                        }
                    });
                    if (!respu) {
                        storage.push(items);
                    }
                }

                localStorage.setItem('items_memorando_entrega', JSON.stringify(storage));
                crea_tabla();
                limpiar_formulario('etiqueta', 'input');
                limpiar_formulario('etiqueta', 'select');
                $(`#respuesta`).empty().html('');
                // $(`#ubicacion`).html('');


            } else {
                alertify.error('el codigo no valido');
            }
        }
    });

}

var crea_tabla = function () {
    var data = JSON.parse(localStorage.getItem('items_memorando_entrega'));
    var table = $('#tabla_items').DataTable({
        "data": data,
        "columns": [
            { "data": "codigo_producto" },
            { "data": "salida" },
            { "data": "descripcion" },
            { "data": "cav" },
            { "data": "cor" },
            { "data": "rollos_x" },
            { "data": "valor_uni" },
            { "data": "valor_total" },
            {
                "orderable": false,
                render: function (data, type, row) {
                    return `<center>
                                <button class="btn btn-danger btn-sm borrar" data-id="${row.codigo_producto}" type="button" title="elimina"><i class="far fa-trash-alt"></i></button>
                            </center>`;
                }
            }
        ]

    });
    borrar_item();
}
var borrar_item = function () {
    $('.borrar').on('click', function () {
        var id = $(this).attr('data-id');
        let storage = JSON.parse(localStorage.getItem('items_memorando_entrega'));
        storage.forEach(element => {
            if (element.codigo_producto == id) {
                var index = storage.indexOf(element);
                if (index > -1) {
                    storage.splice(index, 1);
                    localStorage.setItem('items_memorando_entrega', JSON.stringify(storage));
                    crea_tabla();
                }
            }
        });
    });
}

var validar_codigo_etiq = function () {
    $('#codigo_producto').on('blur', function () {
        var codigo = $(this).val();
        $.ajax({
            "url": `${PATH_NAME}/almacen/validar_codigo_tecno`,
            "type": "POST",
            "data": { codigo },
            "success": function (respu) {
                if (respu.estado) {
                    if (respu.id_tipo_articulo == 4) {
                        $('#respuesta').empty().html('Lo sentimos este modulo no permite descontar bobinas.');
                        $('#codigo_producto').focus();
                        $('#btn_ingresar_eti').attr('data-valida', false);
                    } else {
                        $('#id_producto').val(respu.id_producto)
                        $('#respuesta').empty().html(respu.mensaje);
                        $('#btn_ingresar_eti').attr('data-valida', true);
                        ubicacion_producto(respu.ubicacion);
                        calculo_ubicacion();
                    }
                } else {
                    $('#respuesta').empty().html(respu.mensaje);
                    $('#codigo_producto').focus();
                    $('#btn_ingresar_eti').attr('data-valida', false);
                }
            }
        });
    });
}

var ubicacion_producto = function (ubicaciones) {
    var select = '<select class="form-control col-8" name="ubicacion" id="ubicacion" style="width: 100%;"><option value="0"></option>';
    ubicaciones.forEach(element => {
        if (element.total != '0.00') {
            select += `<option data-total="${element.total}" value="${element.ubicacion}" id="id_${element.ubicacion.replace(/ /g, "")}">${element.ubicacion}</option>`;
        }
    });
    select += '</select>';
    $('#select_ubicacion').empty().html(select);
    $('#ubicacion').select2();
}

var calculo_ubicacion = function () {
    $('#salida').on('keyup', function () {
        var total = $(this).val();
        var elegido = $('#ubicacion').val();
        var elegido_2 = elegido.replace(/ /g, "");
        var existencia = $(`#id_${elegido_2}`).attr('data-total');
        if (parseInt(existencia) < parseInt(total)) {
            total = total.toString();
            var total_2 = total.slice(0, -1);
            $('#salida').val(total_2);
            alertify.error('Supero la cantidad en la ubicación');
        }
    });
}

var genera_documento = function () {
    $("#crea_salida_inv").on('click', function (e) {
        e.preventDefault();
        let storage = JSON.parse(localStorage.getItem('items_memorando_entrega'));
        if ($('#nombre').val() == '') {
            alertify.error('Se requiere el nombre de la persona.');
            return;
        }
        if ($('#area').val() == 0) {
            alertify.error('Se requiere el area.');
            return;
        }
        var datos = {
            nombre: $('#nombre').val(),
            area: $('#area').val(),
            obseveciones: $('#observaciones').val(),
        }
        var obj_inicial = $("#crea_salida_inv").html();
        btn_procesando('crea_salida_inv');
        $.ajax({
            url: `${PATH_NAME}/almacen/genera_pdf`,
            type: 'POST',
            data: { storage, datos },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (regreso) {
                // btn_procesando('crea_salida_inv', obj_inicial, 1);
                // localStorage.removeItem('items_memorando_entrega');
                // location.reload();
                // var a = document.createElement('a');
                // var url = window.URL.createObjectURL(regreso);
                // a.href = url;
                // a.download = 'memorando_interno_entrega.pdf';
                // a.click();
                // window.URL.revokeObjectURL(url);
            }
        });
    });
}
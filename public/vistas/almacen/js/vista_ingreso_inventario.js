$(document).ready(function () {
    cambio_formato();
    valida_ubicacion();
    limpiar();
    consulta_codigo();
});
var UBICA = '';

var cambio_formato = function () {
    $('#codigo').on('change', function () {
        var valor = $('#codigo').val();
        $('#codigo').val('**********');
        var array = valor.split(";");
        if (UBICA == '') {
            alertify.error('Se requiere la ubicacion');
            $('#codigo').val('');
            $('#ubicacion').focus();
            return;
        }
        var codigo = array[0];
        $.ajax({
            "url": `${PATH_NAME}/almacen/validar_codigo_tecno`,
            "type": 'POST',
            "data": { codigo },
            "success": function (respu) {
                var entrada = 1;
                if (respu.id_clase_articulo == 2) {
                    entrada = array[1];
                    if (array.length < 2) {
                        alertify.error('La estructura del codigo no corresponde a la estructura original');
                        $('#codigo').val('');
                        $('#codigo').focus();
                        return;
                    }
                }
                var id_productos = respu.id_producto;
                var ubicacion = UBICA;
                var codigo_producto = codigo;
                $('#codigo_producto').html('Codigo Producto: ' + array[0]);
                $('#cantidad').html('Cantidad Rollo: ' + entrada);
                $('#descripcion').html('Descripción Producto: ' + respu.mensaje);
                alertify.confirm(`ALERTA SIDPA`, `¿Esta seguro de cargar este producto en esta ubicación?`, function () {
                    $.ajax({
                        "url": `${PATH_NAME}/cargar_inventario`,
                        "type": 'POST',
                        "data": { codigo_producto, ubicacion, entrada, id_productos },
                        "success": function (respu) {
                            alertify.success(respu.msg);
                            $('#codigo').val('');
                            $('#codigo').focus();
                            $('#codigo_producto').html('&nbsp;');
                            $('#cantidad').html('&nbsp;');
                            $('#descripcion').html('&nbsp;');
                        }
                    });
                }, function () {
                    alertify.error('Cancelado');
                    $('#codigo').val('');
                    $('#codigo').focus();
                    $('#codigo_producto').html('&nbsp;');
                    $('#cantidad').html('&nbsp;');
                    $('#descripcion').html('&nbsp;');
                })
                    .set('closable', false, 'labels', { ok: 'Si', cancel: 'No' });
            }
        });
    })
}

var valida_ubicacion = function () {
    $('#ubicacion').on('change', function () {
        var ubicacion = $('#ubicacion').val();
        $('#ubicacion').val('**********');
        var array = ubicacion.split(";");
        if (array[0] != '@Ca' || array[2] != '!$CANASTA') {
            alertify.error('Lo sentimos esta ubicacion no existe');
            $('#ubicacion').val('');
            $('#ubicacion').focus();
            return;
        }
        UBICA = array[1];
        $('#ubica_producto').html(UBICA);
        $('#codigo').focus();
    });
}

var limpiar = function () {
    $('#limpiar').on('click', function () {
        $('#ubicacion').val('');
        $('#codigo').val('');
        $('#codigo_producto').html('&nbsp;');
        $('#cantidad').html('&nbsp;');
        $('#descripcion').html('&nbsp;');
        $('#ubica_producto').html('&nbsp;');
        UBICA = '';
    });
}
var consulta_codigo = function () {
    $('#cons_codigo').on('change', function () {
        var valor = $('#cons_codigo').val();
        $('#cons_codigo').val('**********');
        var array = valor.split(";");
        var codigo = array[0];
        $.ajax({
            "url": `${PATH_NAME}/almacen/validar_codigo_tecno`,
            "type": 'POST',
            "data": { codigo },
            "success": function (respu) {
                var ubica = [];
                respu.ubicacion.forEach(element => {
                    if (element.total > 0) {
                        element.codigo = codigo;
                        ubica.push(element);
                    }
                });
                var tb_ubicacion = $('#tb_ubicacion').DataTable({
                    "data": ubica,
                    "columns": [
                        { "data": "codigo" },
                        { "data": "ubicacion" },
                    ],
                });
            }
        });
    })
}
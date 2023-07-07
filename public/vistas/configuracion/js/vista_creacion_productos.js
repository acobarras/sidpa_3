$(document).ready(function () {
    select_2();
    cargar_tabla_productos();
    producto_crea();
    elimina_espacio('codigo_producto', 'span_codigo_CE');
    cambiar_estado();
    obtener_data_editar();
    costo_etiq();
    calculo_precios_tecno();
    crear_codigo_etiqueta();
    regresar_productos();
    agrega_edita_productos();
    ver_ficha();
    $('#agrega-tab').on('click', function () {
        $('#clase_articulo').change();
        $('#codigo_producto').addClass('precio_etiq');
        $('#avance').addClass('precio_etiq');
    });
});

var cargar_tabla_productos = function () {
    var table = $("#tabla_productos").DataTable({
        "ajax": `${PATH_NAME}/configuracion/consultar_productos`,
        "columns": [
            { "data": "id_productos" },
            { "data": "codigo_producto" },
            { "data": "nombre_articulo" },
            { "data": "tamano" },
            { "data": "descripcion_productos" },
            {
                "data": "estado_producto",
                "searchable": false,
                "orderable": false,
                "render": function (data, type, row) {

                    if (row["estado_producto"] == 1) {
                        return '<center><button class="btn btn-link estado" value="1"><i style="font-size:25px" class="fa fa-toggle-on text-success"></i></button></center>';
                    } else {

                        return '<center><button class="btn btn-link estado" value="0"><i style="font-size:25px" class="fa fa-toggle-off text-danger"></i></button></center>';

                    }
                }

            },
            { "defaultContent": '<button type="button" class="btn btn-primary agregar_campos" title="Consultar/Modificar"><i class="fa fa-edit"></i></button>' }

        ],
    });
}

var cambiar_estado = function () {
    $("#tabla_productos tbody").on("click", "button.estado", function () {
        var data = $("#tabla_productos").DataTable().row($(this).parents("tr")).data();
        var valor_btn = $(this).val();
        if (valor_btn == 1) {
            //inactivar usuario
            envio = { 'id_productos': data.id_productos, 'estado_producto': 0 };
        } else {
            //activar usuario
            envio = { 'id_productos': data.id_productos, 'estado_producto': 1 };
        }
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_estados_producto`,
            "type": 'POST',
            "data": envio,
            "success": function (respuesta) {
                $("#tabla_productos").DataTable().ajax.reload();
                alertify.success('Estado cambiado Correctamente');
            }
        });
    });
}

var producto_crea = function () {
    $('#clase_articulo').on('change', function () {
        var producto_crea = $(this).val();
        var data_tipo_articulo = JSON.parse($('#data_tipo_articulo').val());
        var id_clase_articulo;
        if (producto_crea == 1) {
            id_clase_articulo = 1;
            $('.bobina').addClass('d-none');
            $('#crea_cod_etiq').addClass('d-none');
            $('.especial').removeClass('d-none');
            $('#codigo_grupo').removeClass('input-group');
        }
        if (producto_crea == 3) {
            id_clase_articulo = 3;
            $('.bobina').removeClass('d-none');
            $('.tecnologia').addClass('d-none');
            $('.especial').addClass('d-none');
            $('#crea_cod_etiq').addClass('d-none');
            $('#codigo_grupo').removeClass('input-group');
        }
        if (producto_crea == 2 || producto_crea == 0) {
            id_clase_articulo = 2;
            $('.bobina').removeClass('d-none');
            $('.tecnologia').removeClass('d-none');
            $('#crea_cod_etiq').removeClass('d-none');
            $('#codigo_grupo').addClass('input-group');
            $('.etiqueta').addClass('d-none');
            $('.especial').addClass('d-none');
        }
        var item = '<option value="0"></option>';
        data_tipo_articulo.forEach(element => {
            if (id_clase_articulo == element.id_clase_articulo) {
                item += ` <option value="${element.id_tipo_articulo}">${element.nombre_articulo}</option>`;
            }
        });
        $('#id_tipo_articulo').empty().html(item);
    });
}

var regresar_productos = function () {
    $('#home-tab').on('click', function () {
        $('#agrega-tab').empty().html('Nuevo Producto');
        $('#titulo_form').empty().html('Crear Nuevo Producto');
        $('label[for=clase_articulo]').empty().html('Producto A Crear :');
        limpiar_formulario('form_crear_producto', 'input');
        limpiar_formulario('form_crear_producto', 'select');
        $('#id_productos').val('0');
        $('#descripcion_productos').val('');
        $('#clase_articulo').val(2).change();
        $("#img_ficha").val('0');
        $('#crear_etiqueta').empty().html(`<i class="fa fa-plus-circle"></i> Crear Producto`);
        $('#codigo_producto').removeClass('precio_etiq');
        $('#avance').removeClass('precio_etiq');
        $('#muestro_img').empty().html(`<div class="text-center">
        <button class="btn btn-success d-none" type="submit" id="ver_ficha">
            <i class="fa fa-plus-circle"></i>Ver Ficha Tecnica
        </button>
        </div>`);
    });
}

var obtener_data_editar = function () {
    $("#tabla_productos tbody").on('click', 'button.agregar_campos', function () {
        var data = $("#tabla_productos").DataTable().row($(this).parents("tr")).data();
        $('#agrega-tab').empty().html('Modificar Producto');
        $('#titulo_form').empty().html('Modificar Producto');
        $('#crear_etiqueta').empty().html(`<i class="fa fa-plus-circle"></i> Modificar Producto`);
        $('#clase_articulo').val(data.id_clase_articulo).change();
        $('label[for=clase_articulo]').empty().html('Producto A Modificar :');
        $('#ver_ficha').removeClass('d-none');
        $('#ver_ficha').attr('data_produ', JSON.stringify(data));
        rellenarFormulario(data);
        $('#codigo_producto').addClass('precio_etiq');
        $('#avance').addClass('precio_etiq');
        setTimeout(function () {
            $('#id_tipo_articulo').val(data.id_tipo_articulo).change();
        }, 1000);
        $('#agrega-tab').click();
    });
}

var ver_ficha = function () {
    $('#ver_ficha').on('click', function () {
        var data = JSON.parse($('#ver_ficha').attr('data_produ'));
        $.post(`${PATH_NAME}/configuracion/vista_ficha_tec`,
            {
                datos: data,
            },
            function (respu) {
                $('#ficha_tec').empty().html(respu);
            });
        $()
    });
}

var costo_etiq = function () {
    $('.precio_etiq').on('change', function () {
        var clase_articulo = $('#clase_articulo').val();
        var codigo = $('#codigo_producto').val();
        var avance = $('#avance').val();
        if (avance == '') {
            avance = 0;
        }
        if (clase_articulo == 2) {
            costo_etiqueta(codigo, avance);
        }
    });
}

var costo_etiqueta = function (codigo, avance) {
    $.ajax({
        "url": `${PATH_NAME}/configuracion/valida_precio_codigo`,
        "type": 'POST',
        "data": { codigo, avance },
        "success": function (respu) {
            if (respu.status == -1) {
                $('#codigo_producto').focus();
                alertify.error('No hay precio de materia prima para poder crear el codigo');
            } else {
                if (avance == 0) {
                    $('#magnetico').val(respu.magnetico);
                    $('#avance').val(respu.avance);
                    $('#tamano').val(respu.tamano);
                } else {
                    $('#magnetico').val('');
                }
                $('#costo').val(respu.costo);
                $('#precio1').val(respu.precio1);
                $('#precio2').val(respu.precio2);
                $('#precio3').val(respu.precio3);
            }
        }
    });
}

function calculo_precios_tecno() {
    $('#costo').on('change', function () {
        var clase_articulo = $('#clase_articulo').val();
        if (clase_articulo == 3) {
            var precio = $(this).val();
            var precio1 = parseFloat(precio) / PRECIO1_TECNO;
            var precio2 = parseFloat(precio) / PRECIO2_TECNO;
            var precio3 = parseFloat(precio) / PRECIO3_TECNO;
            $('#precio1').val(precio1.toFixed(2));
            $('#precio2').val(precio2.toFixed(2));
            $('#precio3').val(precio3.toFixed(2));
        }
    });
}

var agrega_edita_productos = function () {
    $('#form_crear_producto').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_etiqueta').html();
        var clase_articulo = $('#clase_articulo').val();
        var filename = $("#img_ficha_1").val();
        var imagen_base = $("#img_ficha").val();
        var color_producto = $("#color_producto").val();
        var nombre_color = $("#nombre_color").val();
        var array_color = [];
        var array_nombre_color = [];
        var exepcion;
        if (clase_articulo == 1) {
            exepcion = ['id_productos', 'tamano', 'ubi_troquel', 'ancho_material', 'cav_montaje', 'avance', 'magnetico', 'precio3', 'consumo', 'ficha_tecnica', 'ubica_ficha', 'img_ficha_1', 'img_ficha', 'acabados_ficha', 'nombre_color[]', 'color_producto[]'];
        }
        if (clase_articulo == 2 || clase_articulo == 0) {
            exepcion = ['id_productos', 'avance', 'id_adh', 'consumo', 'img_ficha', 'acabados_ficha', 'nombre_color[]', 'color_producto[]'];
        }
        if (clase_articulo == 3) {
            exepcion = ['id_productos', 'avance', 'tamano', 'ubi_troquel', 'ancho_material', 'cav_montaje', 'avance', 'magnetico', 'consumo', 'ficha_tecnica', 'ubica_ficha', 'img_ficha_1', 'img_ficha', 'acabados_ficha', 'nombre_color[]', 'color_producto[]'];
        }
        var form = $(this).serializeArray();
        var valida_form = validar_formulario(form, exepcion);
        if (valida_form) {
            if (color_producto == '') {
                alertify.error('Se requiere un color');
                return;
            }
            if (nombre_color == '') {
                alertify.error('Se requiere un nombre');
                return;
            }
            if (color_producto.search(',') != -1) {
                array_color = color_producto.split(',');
            } else {
                array_color.push(color_producto);
            }
            if (nombre_color.search(',') != -1) {
                array_nombre_color = nombre_color.split(',');
            } else {
                array_nombre_color.push(nombre_color);
            }
            if (array_color.length != array_nombre_color.length) {
                alertify.error('La cantidad de colores no corresponden a la cantidad de nombres');
                return;
            }
            if (clase_articulo == 2 && filename == '' && imagen_base == 0) {
                alertify.error('Se requiere la imagen ficha tecnica para continuar');
                return;
            }
            // btn_procesando('crear_etiqueta');
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_producto`,
                "type": 'POST',
                "data": new FormData(this),
                "cache": false,
                "processData": false,
                "contentType": false,
                "success": function (respu) {
                    if (respu['status'] == true) {
                        $("#tabla_productos").DataTable().ajax.reload();
                        alertify.success(respu.msg);
                        $('#home-tab').click();
                    }
                    btn_procesando('crear_etiqueta', obj_inicial, 1);
                }
            });
        }
    });
}

var crear_codigo_etiqueta = function () {
    $('#crea_cod_etiq').click(function () {
        var data = 0;
        $.post(`${PATH_NAME}/configuracion/vista_crea_codigo_etiqueta`, {
            datos: data
        },).done(function (respu) {
            $('#respuesta_codigo').empty().html(respu);
            $("#Modal_crea_codigo").modal("show");
        });
    });
}
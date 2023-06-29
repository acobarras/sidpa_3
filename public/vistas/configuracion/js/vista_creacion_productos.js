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
    $('#agrega-tab').on('click', function () {
        $('#clase_articulo').change();
        $('#codigo_producto').addClass('precio_etiq');
        $('#avance').addClass('precio_etiq');
    });
    // dibujo();
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
        $('#muestro_img').empty().html('');
    });
}

var obtener_data_editar = function () {
    $("#tabla_productos tbody").on('click', 'button.agregar_campos', function () {
        var data = $("#tabla_productos").DataTable().row($(this).parents("tr")).data();
        var imagenes = vista_imagen(data.img_ficha, data);
        $('#agrega-tab').empty().html('Modificar Producto');
        $('#titulo_form').empty().html('Modificar Producto');
        $('#crear_etiqueta').empty().html(`<i class="fa fa-plus-circle"></i> Modificar Producto`);
        $('#clase_articulo').val(data.id_clase_articulo).change();
        $('label[for=clase_articulo]').empty().html('Producto A Modificar :');
        rellenarFormulario(data);
        $('#codigo_producto').addClass('precio_etiq');
        $('#avance').addClass('precio_etiq');
        $('#muestro_img').empty().html(imagenes);
        if (data.img_ficha != '') {
            new Splide('#image-carousel').mount();
        }
        $('#id_tipo_articulo').val(data.id_tipo_articulo).change();
        $('#agrega-tab').click();
    });
}

var vista_imagen = function name(dataImagenes, data) {
    var res = '';
    res = `<div class="position-relative">
    <img src="${IMG}${PROYECTO}/PDF/ficha_tecnica/ficha_encabezado.png" width="100%" alt="">
    <div class="position-absolute top-50 start-50" style="font-size: x-small;line-height: 0; margin-left: 20.5%; margin-top: -6px; color: #001689;">
    <p>No: 004924</p>
    <p>Fecha: 09-Jun-2023</p>
    </div>
    </div>`;
    if (dataImagenes != '') {
        var imagenes = dataImagenes.split(",");
        res += `<section id="image-carousel" class="splide" aria-label="Beautiful Images">
            <div class="splide__track">
                <ul class="splide__list">`;
        imagenes.forEach(element => {
            res += `<li class="splide__slide">
                        <img src="${IMG}${PROYECTO}/PDF/ficha_tecnica/${element}" width="100%" alt="">
                    </li>`;
        });
        res += `</ul>
            </div>
        </section>`;
    } else {
        res += `<div class="position-relative" style="width: 100%; height: 375px;" id="contenedor">
            <div class="position-absolute top-50 start-50 translate-middle" id="lienzo1">
                <div id="cota1" style="position: relative; top: -20px; text-align: center; border-top: 1px solid black;"></div>
                <div id="cota2" style="position: relative; left: -23px; text-align: center; border-left: 1px solid black; writing-mode: vertical-lr; top: -20px;"></div>
            </div>
        </div>`;
    }
    res += `<div class="ficha_pie_pagina" style="padding: 0;">
        <div class="row" style="font-size: 7px;">
            <div class="col-5 pe-0">
                <div class="text-center degradado_sidpa" style="border-radius: 7px 0px 0px 0px;">ESPECIFICACIONES TÉCNICAS</div>
                <table class="table table-bordered border-dark border-2 table-sm my-0" width="100%">
                    <tbody>
                        <tr>
                            <th class="py-0">referencia:</th>
                            <th class="py-0" colspan="3">Cilindro de Impresión y/o Troquelado:</th>
                        </tr>
                        <tr>
                            <th class="py-0">Versión:</th>
                            <td class="py-0">01</td>
                            <th class="py-0">Forma:</th>
                            <td class="py-0">Rectangular</td>
                        </tr>
                        <tr>
                            <th class="py-0">Dimensión:</th>
                            <td class="py-0">100,5X105,4</td>
                            <th class="py-0">Codigo:</th>
                            <td class="py-0">100X100-1011A00001</td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center degradado_sidpa" style="border-radius: 0px 0px 0px 0px;">MONTAJE</div>
                <table class="table table-bordered border-dark border-2 table-sm my-0" width="100%">
                    <tbody>
                        <tr>
                            <th class="py-0">Cavidades:</th>
                            <td class="py-0">1</td>
                            <th class="py-0" colspan="2">Cilindro de Impresión y/o Troquelado:</th>
                        </tr>
                        <tr>
                            <th class="py-0">Repeticiones:</th>
                            <td class="py-0">4</td>
                            <th class="py-0">Dientes:</th>
                            <td class="py-0">72</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-3 px-0">
                <div class="text-center degradado_sidpa" style="border-radius: 0px 0px 0px 0px;">TINTAS</div>
            </div>
            <div class="col-4 ps-0 border-dark border-start">
                <div class="text-center degradado_sidpa" style="border-radius: 0px 7px 0px 0px;">ACABADOS ETIQUETA</div>
                <p class="my-0 mx-0 px-2" style="text-align: justify;">como podemos ver este texto deberia llegar a una longitud de hasta 100 caracteres para poder determinar si no se sale de lo demarcado</p>
                <div class="text-center degradado_sidpa" style="border-radius: 0px 0px 0px 0px;">OBSERVACIONES</div>
                <p class="my-0 mx-0 px-2" style="text-align: justify;"></p>
            </div>
        </div>
    </div>`;
    setTimeout(function () {
        dibujo(data);
    }, 1000);
    return res;
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
            var precio1 = parseFloat(precio) / .8;
            var precio2 = parseFloat(precio) / .78;
            var precio3 = parseFloat(precio) / .76;
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
        var exepcion;
        if (clase_articulo == 1) {
            exepcion = ['id_productos', 'tamano', 'ubi_troquel', 'ancho_material', 'cav_montaje', 'avance', 'magnetico', 'precio3', 'consumo', 'ficha_tecnica', 'ubica_ficha', 'img_ficha_1', 'img_ficha', 'acabados_ficha'];
        }
        if (clase_articulo == 2 || clase_articulo == 0) {
            exepcion = ['id_productos', 'avance', 'id_adh', 'consumo', 'img_ficha', 'acabados_ficha'];
        }
        if (clase_articulo == 3) {
            exepcion = ['id_productos', 'avance', 'tamano', 'ubi_troquel', 'ancho_material', 'cav_montaje', 'avance', 'magnetico', 'consumo', 'ficha_tecnica', 'ubica_ficha', 'img_ficha_1', 'img_ficha', 'acabados_ficha'];
        }
        var form = $(this).serializeArray();
        var valida_form = validar_formulario(form, exepcion);
        if (valida_form) {
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

var dibujo = function (data) {
    var cod = data.codigo_producto;
    var tamano = tamano_codigo(cod);
    var color = '#ffff';
    if (data.color_producto != '') {
        color = data.color_producto;
    }
    var ancho = tamano.ancho;
    var alto = tamano.alto;
    var ancho_text = `${ancho}mm`;
    var alto_text = `${alto}mm`;
    var scale = 1;
    var scale1 = 1;
    var scale2 = 1;
    if (ancho > 150) {
        scale1 = 150 / ancho;
    }
    if (alto > 90) {
        scale2 = 90 / alto;
    }
    if (scale1 > scale2) {
        scale = scale2;
    } else {
        scale = scale1;
    }
    $('#contenedor').css('transform', `scale(${scale})`);
    $('#lienzo1').css('width', ancho_text);
    $('#lienzo1').css('height', alto_text);
    $('#lienzo1').css('border', '1px solid black');
    $('#lienzo1').css('border-radius', '8px');
    $('#lienzo1').css('background', color);
    $('#cota1').css('width', ancho_text);
    $('#cota1').empty().html(ancho_text);
    $('#cota2').css('height', alto_text);
    $('#cota2').empty().html(alto_text);
}

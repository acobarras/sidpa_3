$(document).ready(function () {
        mostrar();
});

var mostrar = function () {
        var data = JSON.parse($('#data_producto').val());
        var imagenes = vista_imagen(data['datos']);
        $('#ficha_tec').empty().html(imagenes);
        if (data['datos'].img_ficha != '') {
                new Splide('#image-carousel').mount();
        }
}

var vista_imagen = function name(data) {
        var res = '';
        var contador = 4;
        res = `<div class="position-relative">
    <img src="${IMG}${PROYECTO}/PDF/ficha_tecnica/ficha_encabezado.png" width="100%" alt="">
    <div class="position-absolute top-50 start-50" style="font-size: 90%;;line-height: 0; margin-left: 20.5%; margin-top: -6px; color: #001689;">
    <p>No: ${data.ficha_tecnica_produc}</p>
    <p>Fecha: ${data.fecha_crea}</p>
    </div>
    </div>`;

        if (data.img_ficha != '') {
                var imagenes = data.img_ficha.split(",");
                res += `<section id="image-carousel" class="splide" aria-label="Beautiful Images">
            <div class="splide__track">
                <ul class="splide__list">`;
                imagenes.forEach(element => {
                        res += `<li class="splide__slide">
                        <img src="${IMG}${PROYECTO}/PDF/ficha_tecnica/${element}" style="max-width:100%;max-height:600px" alt="">
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
        <div class="row">
            <div class="col-5 pe-0">
                <div class="text-center degradado_sidpa" style="border-radius: 7px 0px 0px 0px;">ESPECIFICACIONES TÉCNICAS</div>
                <table class="table table-bordered border-dark border-2 table-sm my-0" width="100%">
                    <tbody>
                        <tr>
                            <th class="py-0">referencia:</th>
                            <th id="referencia" class="py-0" colspan="3"></th>
                        </tr>
                        <tr>
                            <th class="py-0">Versión:</th>
                            <td id="version" class="py-0"></td>
                            <th class="py-0">Forma:</th>
                            <td id="forma" class="py-0"></td>
                        </tr>
                        <tr>
                            <th class="py-0">Dimensión:</th>
                            <td id="dimension" class="py-0"></td>
                            <th class="py-0">Codigo:</th>
                            <td id="codigo" class="py-0"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center degradado_sidpa" style="border-radius: 0px 0px 0px 0px;">MONTAJE</div>
                <table class="table table-bordered border-dark border-2 table-sm my-0" width="100%">
                    <tbody>
                        <tr>
                            <th class="py-0">Cavidades:</th>
                            <td id="cavidades" class="py-0"></td>
                            <th class="py-0" colspan="2">Cilindro de Impresión y/o Troquelado</th>
                        </tr>
                        <tr>
                            <th class="py-0">Repeticiones:</th>
                            <td id="repeticiones" class="py-0"></td>
                            <th class="py-0">Dientes:</th>
                            <td id="dientes" class="py-0"></td>
                        </tr>
                    </tbody>
                </table>
            </div> 
            <div class="col-3 px-0">
                <div class="text-center degradado_sidpa" style="border-left: 1px solid white;border-right: 1px solid white;">TINTAS</div>
                <div class="row row-cols-2 py-2">`;
        if (data.nombre_color != '' && data.color_producto != '') {
                var color = data.nombre_color.split(",");
                var cod_color = data.color_producto.split(",");
                for (let i = 0; i < color.length; i++) {
                        var element = color[i];
                        var element_cod = cod_color[i];
                        if (i == 0) {
                                res += ` <div class="col">`;
                        } if (i == contador) {
                                res += ` </div><div class="col">`;
                        }
                        res += `<p class="my-0 mx-0 px-2">
                                    <span class="ovalo_ficha_tec px-2" style="background:${element_cod};">&nbsp;</span>&nbsp;${element}
                                </p>`;
                }
                res += ` </div></div> </div>`;
        } else {
                res += `<div class="col">
                                <p class="my-0 mx-0 px-2">
                                    <span class="ovalo_ficha_tec px-2" style="background:#ffffff;">&nbsp;</span>&nbsp;Blanca
                                </p>
                        </div>
                        </div>
                </div>`;
        }
        if (data.acabados_ficha == '') {
                var diseno = "my-4 mx-0 px-2";
        } else {
                var diseno = "my-0 mx-0 px-2";
        }
        res += `<div class="col-4 ps-0 border-dark border-start">
                        <div class="text-center degradado_sidpa" style="border-radius: 0px 7px 0px 0px;">ACABADOS ETIQUETA</div>
                                <p id="acabados" class="${diseno}" style="text-align: justify;"></p>
                        <div class="text-center degradado_sidpa" style="border-radius: 0px 0px 0px 0px;">OBSERVACIONES</div>
                                <p id="observaciones_ft" class="my-0 mx-0 px-2" style="text-align: justify;color:red;font-weight: bold;"></p>
                </div>`;
        setTimeout(function () {
                dibujo(data);
                cargar_ficha(data);
        }, 1000);
        return res;
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
        $('#lienzo1').css('width', `${ancho_text}`);
        $('#lienzo1').css('height', `${alto_text}`);
        $('#lienzo1').css('border', '1px solid black');
        $('#lienzo1').css('border-radius', '8px');
        $('#lienzo1').css('background', color);
        $('#cota1').css('width', ancho_text);
        $('#cota1').empty().html(ancho_text);
        $('#cota2').css('height', alto_text);
        $('#cota2').empty().html(alto_text);
}

var cargar_ficha = function (data) {
        var magnetico = parseFloat(data.magnetico);
        var avance = parseFloat(data.avance);
        var repeticiones = (PINONES * magnetico) / avance;
        repeticiones = redondear(repeticiones, 0);
        if (data['area'] == 2) {
                $('#cavidades').empty().html('N/A');
                $('#dientes').empty().html('N/A');
                $('#repeticiones').empty().html('N/A');
        } else {
                $('#cavidades').empty().html(data.cav_montaje);
                $('#dientes').empty().html(data.magnetico);
                $('#repeticiones').empty().html(repeticiones);
        }
        $('#dimension').empty().html(data.tamano);
        $('#codigo').empty().html(data.codigo_producto);
        $('#acabados').empty().html(data.acabados_ficha);
        $('#referencia').empty().html(data.descripcion_productos);
        $('#forma').empty().html(data.forma);
        $('#version').empty().html(data.version_ft);
        $('#observaciones_ft').empty().html(data.observaciones_ft);
}
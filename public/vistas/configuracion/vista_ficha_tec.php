<div class="col" id="muestro_img">
    <div class="position-relative">
        <img src="<?= CARPETA_IMG . PROYECTO . "/PDF/ficha_tecnica/" ?>ficha_encabezado.png" width="100%" alt="">
        <div class="position-absolute top-50 start-50" style="font-size: x-small;line-height: 0; margin-left: 20.5%; margin-top: -6px; color: #001689;">
            <p>No: 004924</p>
            <p>Fecha: 09-Jun-2023</p>
        </div>
    </div>
    <div class="position-relative" style="width: 100%; height: 375px;" id="contenedor">
        <div class="position-absolute top-50 start-50 translate-middle" id="lienzo1">
            <div id="cota1" style="position: relative; top: -20px; text-align: center; border-top: 1px solid black;"></div>
            <div id="cota2" style="position: relative; left: -23px; text-align: center; border-left: 1px solid black; writing-mode: vertical-lr; top: -20px;"></div>
        </div>
    </div>
    <div class="ficha_pie_pagina" style="padding: 0;">
        <div class="row" style="font-size: 7px;">
            <div class="col-5 pe-0">
                <div class="text-center degradado_sidpa" style="border-radius: 7px 0px 0px 0px;">ESPECIFICACIONES TÉCNICAS</div>
                <table class="table table-bordered border-dark border-2 table-sm my-0" width="100%">
                    <tbody>
                        <tr>
                            <th class="py-0">referencia:</th>
                            <th id="referencia" class="py-0" colspan="3">Cilindro de Impresión y/o Troquelado:</th>
                        </tr>
                        <tr>
                            <th class="py-0">Versión:</th>
                            <td id="version" class="py-0">01</td>
                            <th class="py-0">Forma:</th>
                            <td id="forma" class="py-0">Rectangular</td>
                        </tr>
                        <tr>
                            <th class="py-0">Dimensión:</th>
                            <td id="dimension" class="py-0">100,5X105,4</td>
                            <th class="py-0">Codigo:</th>
                            <td id="codigo" class="py-0">100X100-1011A00001</td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center degradado_sidpa" style="border-radius: 0px 0px 0px 0px;">MONTAJE</div>
                <table class="table table-bordered border-dark border-2 table-sm my-0" width="100%">
                    <tbody>
                        <tr>
                            <th class="py-0">Cavidades:</th>
                            <td id="cavidades" class="py-0">1</td>
                            <th class="py-0" colspan="2">Cilindro de Impresión y/o Troquelado:</th>
                        </tr>
                        <tr>
                            <th class="py-0">Repeticiones:</th>
                            <td id="repeticiones" class="py-0">4</td>
                            <th class="py-0">Dientes:</th>
                            <td id="dientes" class="py-0">72</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-3 px-0">
                <div class="text-center degradado_sidpa" style="border-radius: 0px 0px 0px 0px;">TINTAS</div>
                <div class="row row-cols-2" id="colores_tintas">
                    <div class="col">
                        <p class="my-0 mx-0 px-2">
                            <span class="ovalo_ficha_tec px-1" style="background:#001689;">&nbsp;</span>&nbsp;Pantone 7733C
                        </p>
                        <p class="my-0 mx-0 px-2">
                            <span class="ovalo_ficha_tec px-1">&nbsp;</span>&nbsp;verde
                        </p>
                        <p class="my-0 mx-0 px-2">
                            <span class="ovalo_ficha_tec px-1">&nbsp;</span>&nbsp;verde
                        </p>
                        <p class="my-0 mx-0 px-2">
                            <span class="ovalo_ficha_tec px-1">&nbsp;</span>&nbsp;verde
                        </p>
                    </div>
                    <div class="col">
                        <p class="my-0 mx-0 px-2">
                            <span class="ovalo_ficha_tec px-1">&nbsp;</span>&nbsp;Magenta
                        </p>
                        <p class="my-0 mx-0 px-2">
                            <span class="ovalo_ficha_tec px-1">&nbsp;</span>&nbsp;verde
                        </p>
                        <p class="my-0 mx-0 px-2">
                            <span class="ovalo_ficha_tec px-1">&nbsp;</span>&nbsp;verde
                        </p>
                        <p class="my-0 mx-0 px-2">
                            <span class="ovalo_ficha_tec px-1">&nbsp;</span>&nbsp;verde
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-4 ps-0 border-dark border-start">
                <div class="text-center degradado_sidpa" style="border-radius: 0px 7px 0px 0px;">ACABADOS ETIQUETA</div>
                <p id="acabados" class="my-0 mx-0 px-2" style="text-align: justify;">como podemos ver este texto deberia llegar a una longitud de hasta 100 caracteres para poder determinar si no se sale de lo demarcado</p>
                <div class="text-center degradado_sidpa" style="border-radius: 0px 0px 0px 0px;">OBSERVACIONES</div>
                <p id="observaciones" class="my-0 mx-0 px-2" style="text-align: justify;"></p>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_ficha_tec.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active btn btn-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Tabla Productos</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link btn btn-link" id="agrega-tab" data-bs-toggle="tab" data-bs-target="#agrega-tab-pane" type="button" role="tab" aria-controls="agrega-tab-pane" aria-selected="true">Nuevo Producto</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab">
                    <div class="mx-3 mt-2">
                        <div class="text-center">
                            <h2>Tabla de Productos</h2>
                        </div>
                        <br>
                        <table id="tabla_productos" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <th>#</th>
                                <th>Código de Producto</th>
                                <th>Clase Artículo</th>
                                <th>Tamaño</th>
                                <th>Descripción Productos</th>
                                <th>Estado</th>
                                <th>Opciónes</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade" id="agrega-tab-pane" role="tabpanel" aria-labelledby="agrega-tab">
                    <br>
                    <div id="respuesta_codigo"></div>
                    <input type="hidden" id="data_tipo_articulo" value='<?= json_encode($tipo_articulo) ?>'>
                    <form class="container" id="form_crear_producto" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <input id="id_productos" type="hidden" value="0" name="id_productos" />
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4" id="titulo_form">Crear Nuevo Producto</h1>
                        </div>
                        <div class="mb-3 row row-cols-2">
                            <div class="mb-3 col">
                                <label for="clase_articulo" class="form-label">Producto A Crear : </label>
                                <select class="form-control select_2" style="width: 100%;" name="clase_articulo" id="clase_articulo">
                                    <option value="0"></option>
                                    <?php foreach ($clase_articulo as $value) {
                                        $select = '';
                                        if ($value->id_clase_articulo == 2) {
                                            $select = 'selected';
                                        } ?>
                                        <option value="<?= $value->id_clase_articulo ?>" <?= $select ?>><?= $value->nombre_clase_articulo ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col">
                                <label for="codigo_producto" class="form-label">Código : </label>
                                <div class="input-group" id="codigo_grupo">
                                    <input autocomplete="off" type="text" class="form-control" name="codigo_producto" id="codigo_producto">
                                    <span class="input-group-text" style="cursor: pointer;" id="crea_cod_etiq"><i class="fas fa-eye"></i></span>
                                </div>
                                <span class="text-danger" id="span_codigo_CE"></span>
                            </div>
                            <div class="mb-3 col">
                                <label for="id_tipo_articulo" class="form-label">Tipo Artículo : </label>
                                <select class="form-control select_2" style="width: 100%;" name="id_tipo_articulo" id="id_tipo_articulo">
                                    <option value="0"></option>
                                    <?php foreach ($tipo_articulo as $t_articulo) {
                                        if ($t_articulo->id_clase_articulo == 2) { ?>
                                            <option value="<?= $t_articulo->id_tipo_articulo ?>"><?= $t_articulo->nombre_articulo ?></option>
                                    <?php
                                        }
                                    } ?>
                                </select>
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="tamano" class="form-label">Tamaño : </label>
                                <input autocomplete="off" type="text" class="form-control" name="tamano" id="tamano">
                            </div>
                            <div class="mb-3 col">
                                <label for="descripcion_productos" class="form-label">Descripción</label>
                                <textarea class="form-control" rows="2" cols="30" name="descripcion_productos" id="descripcion_productos"></textarea>
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="ubi_troquel" class="form-label">Ubicación Troquel : </label>
                                <input autocomplete="off" type="text" class="form-control" name="ubi_troquel" id="ubi_troquel" />
                            </div>
                            <div class="mb-3 col especial">
                                <label for="id_adh" class="form-label">Adhesivo</label>
                                <select class="form-control select_2" style="width: 100%;" id="id_adh" name="id_adh">
                                    <option value="0"></option>
                                    <?php foreach ($adhesivo as $adh) { ?>
                                        <option value="<?= $adh->id_adh ?>"><?= $adh->nombre_adh ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="ancho_material" class="form-label">Ancho Material</label>
                                <input autocomplete="off" type="text" class="form-control" name="ancho_material" id="ancho_material" />
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="cav_montaje" class="form-label">Cavidad Montaje</label>
                                <input autocomplete="off" type="text" class="form-control" name="cav_montaje" id="cav_montaje" />
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="avance" class="form-label">Avance</label>
                                <input autocomplete="off" type="text" class="form-control" name="avance" id="avance" />
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="magnetico" class="form-label">Magnetico</label>
                                <input autocomplete="off" type="text" class="form-control" name="magnetico" id="magnetico" />
                            </div>
                            <div class="mb-3 col">
                                <label for="costo" class="form-label">Costo</label>
                                <input autocomplete="off" type="text" class="form-control" name="costo" id="costo" />
                            </div>
                            <div class="mb-3 col">
                                <label for="precio1" class="form-label">Precio 1</label>
                                <input autocomplete="off" type="text" class="form-control" name="precio1" id="precio1" />
                            </div>
                            <div class="mb-3 col">
                                <label for="precio2" class="form-label">Precio 2</label>
                                <input autocomplete="off" type="text" class="form-control" name="precio2" id="precio2" />
                            </div>
                            <div class="mb-3 col bobina">
                                <label for="precio3" class="form-label">Precio 3</label>
                                <input autocomplete="off" type="text" class="form-control" name="precio3" id="precio3" />
                            </div>
                            <div class="mb-3 col">
                                <label for="moneda_producto" class="form-label">Moneda : </label>
                                <select class="form-control" style="width: 100%;" name="moneda_producto" id="moneda_producto">
                                    <option value="0"></option>
                                    <option value="1">Pesos</option>
                                    <option value="2">Dolar</option>
                                </select>
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="consumo" class="form-label">Consumo</label>
                                <input autocomplete="off" type="text" class="form-control" name="consumo" id="consumo" />
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="ficha_tecnica" class="form-label">Ficha Tecnica</label>
                                <input autocomplete="off" type="text" class="form-control" name="ficha_tecnica" id="ficha_tecnica" />
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="ubica_ficha" class="form-label">Ubicación Ficha Tecnica</label>
                                <input autocomplete="off" type="text" class="form-control" name="ubica_ficha" id="ubica_ficha" />
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="img_ficha_1" class="form-label">Imagen Ficha Tecnica</label>
                                <input autocomplete="off" type="file" class="form-control" name="img_ficha_1[]" id="img_ficha_1" multiple />
                                <input type="hidden" name="img_ficha" id="img_ficha" value="0">
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="acabados_ficha" class="form-label">Acabados Ficha Tecnica</label>
                                <input autocomplete="off" type="text" class="form-control" name="acabados_ficha" id="acabados_ficha" multiple />
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="color_producto" class="form-label">Codigo Color</label>
                                <input autocomplete="off" type="text" class="form-control" name="color_producto[]" id="color_producto" multiple />
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="nombre_color" class="form-label">Nombre Color</label>
                                <input autocomplete="off" type="text" class="form-control" name="nombre_color[]" id="nombre_color" multiple />
                            </div>
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
                                <!-- <section id="image-carousel" class="splide" aria-label="Beautiful Images">
                                    <div class="splide__track">
                                        <ul class="splide__list">
                                            <li class="splide__slide">
                                                <img src="<?= CARPETA_IMG . PROYECTO . "/PDF/ficha_tecnica/" ?>FT-12345_0.png" width="100%" alt="">
                                            </li>
                                            <li class="splide__slide">
                                                <img src="<?= CARPETA_IMG . PROYECTO . "/PDF/ficha_tecnica/" ?>FT-12345_1.png" width="100%" alt="">
                                            </li>
                                        </ul>
                                    </div>
                                </section> -->
                                <div class="ficha_pie_pagina" style="padding: 0;">
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
                                </div>
                                <!-- <img src="<?= CARPETA_IMG . PROYECTO . "/PDF/ficha_tecnica/" ?>ficha_pie_pagina.png" width="100%" alt=""> -->
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" id="crear_etiqueta">
                                    <i class="fa fa-plus-circle"></i> Crear Producto
                                </button>
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <br>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_creacion_productos.js"></script>
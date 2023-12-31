<!-- EDWIN  -->
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
                                <th>Ficha Tecnica</th>
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
                                    <input autocomplete="off" type="text" class="form-control precio_etiq" name="codigo_producto" id="codigo_producto">
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
                            <div class="mb-3 col d-none" id="muestra_garantia">
                                <label for="garantia" class="form-label">Garantia :<span style="color:red">Colocar la cantidad de meses en numero: Ej:12</span> </label>
                                <input autocomplete="off" value="0" type="number" class="form-control" name="garantia" id="garantia">
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
                                <label for="troquel" class="form-label">Troquel : </label>
                                <select class="form-control select_2" style="width: 100%;" id="troquel" name="troquel">
                                    <option value="1">Si</option>
                                    <option value="2">No</option>
                                </select>
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
                                <input autocomplete="off" type="text" class="form-control precio_etiq" name="avance" id="avance" />
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
                                <label for="ficha_tecnica_produc" class="form-label">Ficha Tecnica</label>
                                <input autocomplete="off" type="text" class="form-control" name="ficha_tecnica_produc" id="ficha_tecnica_produc" />
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="ubica_ficha" class="form-label">Ubicación Ficha Tecnica</label>
                                <input autocomplete="off" type="text" class="form-control" name="ubica_ficha" id="ubica_ficha" />
                            </div>
                            <div class="mb-3 col bobina tecnologia">
                                <label for="ubica_ficha" class="form-label">Ficha Tecnica</label>
                                <div class="input-group">
                                    <input autocomplete="off" type="file" class="form-control" name="img_ficha_1[]" id="img_ficha_1" multiple>
                                    <span class="input-group-text d-none" id="elimina_img" data-bs-toggle="modal" data-bs-target="#img_ficha_elimina"><i class="fas fa-trash"></i></span>
                                    <input type="hidden" name="img_ficha" id="img_ficha" value="0">
                                </div>
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
                            <div class="mb-3 col bobina tecnologia">
                                <label for="version_ft" class="form-label">Versión Ficha Tec:</label>
                                <input autocomplete="off" type="number" class="form-control" name="version_ft" id="version_ft" multiple />
                            </div>
                        </div>
                        <div class="mb-3">
                            <!-- BOTON PARA VER FICHA -->
                            <div class="py-3 text-center">
                                <button class="btn btn-primary" type="submit" id="crear_etiqueta">
                                    <i class="fa fa-plus-circle"></i> Crear Producto
                                </button>
                                <button class="btn btn-success d-none" type="button" data_produ="" id="ver_ficha">
                                    <i class="fa fa-plus-circle"></i>Ver Ficha Tecnica
                                </button>
                            </div>
                        </div>
                        <br>
                    </form>
                    <div class="container" id="ficha_tec">
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
    </div>
</div>

<!-- MODAL ELIMINAR IMAGENES -->

<!-- Modal -->
<div class="modal fade" id="img_ficha_elimina" tabindex="-1" aria-labelledby="img_ficha_eliminaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="img_ficha_eliminaLabel">Eliminar Imagenes Ficha Tecnica</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_elimina">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_creacion_productos.js"></script>
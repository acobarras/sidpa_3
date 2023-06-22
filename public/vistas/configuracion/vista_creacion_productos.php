<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Productos</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="first-tab" data-bs-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="false">Nueva Etiqueta</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="second-tab" data-bs-toggle="tab" href="#second" role="tab" aria-controls="second" aria-selected="false">Nueva Tecnología</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="third-tab" data-bs-toggle="tab" href="#third" role="tab" aria-controls="third" aria-selected="true">Nueva Materia Prima</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
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
                <div class="tab-pane fade" id="first" role="tabpanel" aria-labelledby="first-tab">
                    <br>
                    <div id="respuesta_codigo"></div>
                    <form class="container" id="form_crear_etiqueta" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <input id="paso_etiqueta" type="hidden" value="form_etiquetas" name="paso" />
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Crear Nueva Etiqueta</h1>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="codigo_producto" class="form-label">Código : </label>
                                <div class="input-group">
                                    <input autocomplete="off" type="text" class="form-control" name="codigo_producto" id="codigo_producto">
                                    <span class="input-group-text" style="cursor: pointer;" id="crea_cod_etiq"><i class="fas fa-eye"></i></span>
                                </div>
                                <span class="text-danger" id="span_codigo_CE"></span>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="id_tipo_articulo" class="form-label">Tipo Artículo : </label>
                                <select class="form-control select_2" style="width: 100%;" name="id_tipo_articulo" id="id_tipo_articulo">
                                    <option value="0"></option>
                                    <?php foreach ($tipo_articulo as $t_articulo) {
                                        if ($t_articulo->id_clase_articulo == 2) {
                                            if ($t_articulo->id_tipo_articulo == 1) { ?>
                                                <option value="<?= $t_articulo->id_tipo_articulo ?>" selected><?= $t_articulo->nombre_articulo ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $t_articulo->id_tipo_articulo ?>"><?= $t_articulo->nombre_articulo ?></option>
                                    <?php }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="tamano" class="form-label">Tamaño : </label>
                                <input autocomplete="off" type="text" class="form-control" name="tamano" id="tamano">
                            </div>
                            <div class="mb-3 col-6">
                                <label for="descripcion_productos" class="form-label">Descripción</label>
                                <textarea class="form-control" rows="2" cols="30" name="descripcion_productos" id="descripcion_productos"></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="ubi_troquel" class="form-label">Ubicación Troquel : </label>
                                <input autocomplete="off" type="text" class="form-control" name="ubi_troquel" id="ubi_troquel" />
                            </div>
                            <div class="mb-3 col-6">
                                <label for="ancho_material" class="form-label">Ancho Material</label>
                                <input autocomplete="off" type="text" class="form-control" name="ancho_material" id="ancho_material" />
                            </div>
                            <div class="mb-3 col-6">
                                <label for="cav_montaje" class="form-label">Cavidad Montaje</label>
                                <input autocomplete="off" type="text" class="form-control" name="cav_montaje" id="cav_montaje" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="avance" class="form-label">Avance</label>
                                <input autocomplete="off" type="text" class="form-control" name="avance" id="avance" />
                            </div>
                            <div class="mb-3 col-6">
                                <label for="magnetico" class="form-label">Magnetico</label>
                                <input autocomplete="off" type="text" class="form-control" name="magnetico" id="magnetico" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="costo" class="form-label">Costo</label>
                                <input autocomplete="off" type="text" class="form-control" name="costo" id="costo" />
                            </div>
                            <div class="mb-3 col-6">
                                <label for="precio1" class="form-label">Precio 1</label>
                                <input autocomplete="off" type="text" class="form-control" name="precio1" id="precio1" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="precio2" class="form-label">Precio 2</label>
                                <input autocomplete="off" type="text" class="form-control" name="precio2" id="precio2" />
                            </div>
                            <div class="mb-3 col-6">
                                <label for="precio3" class="form-label">Precio 3</label>
                                <input autocomplete="off" type="text" class="form-control" name="precio3" id="precio3" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="moneda_producto" class="form-label">Moneda : </label>
                                <select class="form-control" style="width: 100%;" name="moneda_producto" id="moneda_producto">
                                    <option value="0"></option>
                                    <option value="1">Pesos</option>
                                    <option value="2">Dolar</option>
                                </select>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="consumo" class="form-label">Consumo</label>
                                <input autocomplete="off" type="text" class="form-control" name="consumo" id="consumo" />
                            </div>
                        </div>
                        <div class="mb-3 ">
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" id="crear_etiqueta">
                                    <i class="fa fa-plus-circle"></i> Crear Etiqueta
                                </button>
                                <input type="text" name="id_adh" id="id_adh" value="99" hidden="true" />
                                <input autocomplete="off" type="text" hidden="true" name="id_usuario" id="id_usuario" value="<?= $_SESSION['usuario']->getId_usuario() ?>">
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
                <!-- tercer link -->
                <div class="tab-pane fade" id="second" role="tabpanel" aria-labelledby="second-tab">
                    <br>
                    <form class="container" id="form_crear_tecnologia" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <input type="hidden" value="form_tecnologia" name="paso" id="paso_tecnologia" />
                        <input type="hidden" value="0.000000" name="consumo" />
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Crear Nueva Tecnología</h1>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="codigo_producto_tecno" class="form-label">Código</label>
                                <input autocomplete="off" type="text" class="form-control" name="codigo_producto" id="codigo_producto_tecno" />
                                <span class="text-danger" id="span_codigo_TE"></span>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="id_tipo_articulo_tecno" class="form-label">Tipo Artículo</label>
                                <select class="form-control select_2" style="width: 100%;" id="id_tipo_articulo_tecno" name="id_tipo_articulo">
                                    <option value="0"></option>
                                    <?php foreach ($tipo_articulo_tecnologia as $clase_art) { ?>
                                        <option value="<?= $clase_art->id_tipo_articulo ?>"><?= $clase_art->nombre_articulo ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="descripcion_productos_tecno" class="form-label">Descripción</label>
                                <textarea class="form-control" rows="2" cols="30" name="descripcion_productos" id="descripcion_productos_tecno"></textarea>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="moneda_producto_tecno" class="form-label">Moneda</label>
                                <select class="form-control select_2" style="width: 100%;" name="moneda_producto" id="moneda_producto_tecno">
                                    <option value="0"></option>
                                    <option value="1">Pesos</option>
                                    <option value="2">Dolar</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="costo_tecno" class="form-label">Costo</label>
                                <input autocomplete="off" type="text" class="form-control precios_tecno" name="costo" id="costo_tecno" />
                            </div>
                            <div class="mb-3 col-6">
                                <label for="precio1_tecno" class="form-label">Precio 1</label>
                                <input autocomplete="off" type="text" class="form-control" name="precio1" id="precio1_tecno" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="precio2_tecno" class="form-label">Precio 2</label>
                                <input autocomplete="off" class="form-control" type="text" name="precio2" id="precio2_tecno" />
                            </div>
                            <div class="mb-3 col-6">
                                <label for="precio3_tecno" class="form-label">Precio 3</label>
                                <input autocomplete="off" class="form-control" type="text" name="precio3" id="precio3_tecno" />
                            </div>
                        </div>
                        <div class="mb-3 ">
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" id="crear_tecnlogia">
                                    <i class="fa fa-plus-circle"></i> Crear Tecnología
                                </button>
                                <input autocomplete="off" type="text" name="id_adh" id="id_adh_tecno" value="99" hidden="true" />
                                <input autocomplete="off" type="text" hidden="true" name="id_usuario" id="id_usuario_tecno" value="<?= $_SESSION['usuario']->getId_usuario() ?>">
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
                <!-- Cuarto link -->
                <div class="tab-pane fade" id="third" role="tabpanel" aria-labelledby="third-tab">
                    <br>
                    <form class="container" id="form_crear_materiaP" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <input id="paso_materia" type="hidden" value="form_tecnologia" name="paso" />
                        <input type="hidden" value="0.000000" name="consumo" id="consumo_materia" />
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Crear Materia Prima</h1>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="codigo_producto_bobina" class="form-label">Código</label>
                                <input autocomplete="off" type="text" class="form-control" name="codigo_producto" id="codigo_producto_bobina" />
                                <span class="text-danger" id="span_codigo_MP"></span>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="id_tipo_articulo_bobina" class="form-label">Tipo Artículo</label>
                                <select class="form-control select_2" style="width: 100%;" id="id_tipo_articulo_bobina" name="id_tipo_articulo">
                                    <option value="0"></option>
                                    <?php foreach ($tipo_articulo as $t_articulo) {
                                        if ($t_articulo->id_clase_articulo == 1) {
                                            if ($t_articulo->id_tipo_articulo == 4) { ?>
                                                <option value="<?= $t_articulo->id_tipo_articulo ?>" selected><?= $t_articulo->nombre_articulo ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $t_articulo->id_tipo_articulo ?>"><?= $t_articulo->nombre_articulo ?></option>
                                    <?php }
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="descripcion_productos_bobina" class="form-label">Descripción</label>
                                <textarea class="form-control" rows="2" cols="30" name="descripcion_productos" id="descripcion_productos_bobina"></textarea>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="id_adh_bobina" class="form-label">Adhesivo</label>
                                <select class="form-control select_2" style="width: 100%;" id="id_adh_bobina" name="id_adh">
                                    <option value="0"></option>
                                    <?php foreach ($adhesivo as $adh) { ?>
                                        <option value="<?= $adh->id_adh ?>"><?= $adh->nombre_adh ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="costo_bobina" class="form-label">Costo</label>
                                <input autocomplete="off" type="text" class="form-control" name="costo" id="costo_bobina" />
                            </div>
                            <!-- <div class="mb-3 col-6">
                                <label for="precio2_bobina" class="form-label">Precio 2</label>
                                <input autocomplete="off" type="text" class="form-control" name="precio2" id="precio2_bobina" />
                            </div> -->
                        </div>
                        <div class="mb-3 ">
                            <div class="mb-3 row">
                                <div class="mb-3 col-6">
                                    <label for="precio1_bobina" class="form-label">Precio 1</label>
                                    <input autocomplete="off" type="text" class="form-control" name="precio1" id="precio1_bobina" />
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="precio2_bobina" class="form-label">Precio 2</label>
                                    <input autocomplete="off" type="text" class="form-control" name="precio2" id="precio2_bobina" />
                                </div>
                            </div>
                            <div class="mb-3 ">
                                <div class="text-center">
                                    <button class="btn btn-primary" type="submit" id="crear_materiaP">
                                        <i class="fa fa-plus-circle"></i> Crear Materia Prima
                                    </button>
                                    <input autocomplete="off" type="text" hidden="true" name="id_usuario" id="id_usuario_bobina" value="<?= $_SESSION['usuario']->getId_usuario() ?>">
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

<!-- Modal para editar Producto -->

<div class="modal fade" id="ModalProducto" aria-labelledby="ModalProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modificar_producto">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalProductoLabel">Modificar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <h1 class="col-md-12 col-md-offset-4 ">Modificar Producto</h1>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="codigo_producto_modifi" class="form-label">Codigo : </label>
                        <input autocomplete="off" type="text" class="form-control" name="codigo_producto" id="codigo_producto_modifi">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="id_tipo_articulo_modifi" class="form-label">Tipo Artículo : </label>
                        <select class="form-control" style="width: 100%;" name="id_tipo_articulo" id="id_tipo_articulo_modifi">
                            <?php foreach ($tipo_articulo as $tipo_art) { ?>
                                <option value="<?= $tipo_art->id_tipo_articulo ?>"><?= $tipo_art->nombre_articulo ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="tamano_modifi" class="form-label">Tamaño : </label>
                        <input autocomplete="off" type="text" class="form-control" name="tamano" id="tamano_modifi" />
                    </div>
                    <div class="mb-3 col-6">
                        <label for="descripcion_productos_modifi" class="form-label">Descripción : </label>
                        <textarea class="form-control" rows="5" cols="30" name="descripcion_productos" id="descripcion_productos_modifi"></textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="ubi_troquel_modifi" class="form-label">Ubicación Troquel : </label>
                        <input autocomplete="off" type="text" class="form-control" name="ubi_troquel" id="ubi_troquel_modifi" />
                    </div>
                    <div class="mb-3 col-6">
                        <label for="ancho_material_modifi" class="form-label">Ancho Material : </label>
                        <input autocomplete="off" type="text" class="form-control" name="ancho_material" id="ancho_material_modifi">
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="cav_montaje_modifi" class="form-label">Cavidad Montaje : </label>
                        <input autocomplete="off" type="text" class="form-control" name="cav_montaje" id="cav_montaje_modifi" />
                    </div>
                    <div class="mb-3 col-6">
                        <label for="avance_modifi" class="form-label">Avance : </label>
                        <input autocomplete="off" type="text" class="form-control" name="avance" id="avance_modifi">
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="magnetico_modifi" class="form-label">Magnetico : </label>
                        <input autocomplete="off" type="text" class="form-control" name="magnetico" id="magnetico_modifi">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="costo_modifi" class="form-label">Costo : </label>
                        <input autocomplete="off" type="text" class="form-control" name="costo" id="costo_modifi">
                        <input type="hidden" name="id_clase_articulo" id="id_clase_articulo_modifi">
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="precio1_modifi" class="form-label">Precio 1 : </label>
                        <input autocomplete="off" type="text" class="form-control" name="precio1" id="precio1_modifi">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="precio2_modifi" class="form-label">Precio 2 : </label>
                        <input autocomplete="off" type="text" class="form-control" name="precio2" id="precio2_modifi" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="precio3_modifi" class="form-label">Precio 3 : </label>
                        <input autocomplete="off" type="text" class="form-control" name="precio3" id="precio3_modifi" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="moneda_producto_modifi" class="form-label">Moneda : </label>
                        <select class="form-control" style="width: 100%;" name="moneda_producto" id="moneda_producto_modifi">
                            <option value="0"></option>
                            <option value="1">Pesos</option>
                            <option value="2">Dolar</option>
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="id_adh_modifi" class="form-label">Adhesivo : </label>
                        <select class="form-control" style="width: 100%;" name="id_adh" id="id_adh_modifi">
                            <?php foreach ($adhesivo as $adh) { ?>
                                <option value="<?= $adh->id_adh ?>"><?= $adh->nombre_adh ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary" id="modificar_producto" data-id="">Modificar</button>
            </div>
        </form>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_creacion_productos.js"></script>
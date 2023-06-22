<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Artículos</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="first-tab" data-bs-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="false">Nueva Articulo</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="mx-3 mt-2">
                        <div class="text-center">
                            <h2>Tabla Artículos</h2>
                        </div>
                        <br>
                        <table id="tabla_articulo" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Clase Artículo</th>
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
                    <form class="container" id="form_crear_tipo_articulo" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <br>
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Crear Artículo</h1>
                        </div>
                        <div class="mb-3">
                            <label for="nombre_articulo" class="form-label">Nombre Artículo : </label>
                            <input autocomplete="off" type="text" class="form-control" name="nombre_articulo" id="nombre_articulo" />
                        </div>
                        <div class="mb-3">
                            <label for="id_clase_articulo" class="form-label">Clase Artículo : </label>
                            <select class="form-control select_2" style="width: 100%;" id="id_clase_articulo" name="id_clase_articulo">
                                <option value="0"></option>
                                <?php foreach ($clase_articulo as $class_art) { ?>
                                    <option value="<?= $class_art->id_clase_articulo ?>"><?= $class_art->nombre_clase_articulo ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3 ">
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" id="crear_tipo_articulo">
                                    <i class="fa fa-plus-circle"></i> Crear Articulo
                                </button>
                            </div>
                        </div>
                        <br>
                    </form>
                    <br><br><br><br><br><br><br><br>
                    <br><br><br><br><br><br><br><br>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar Tipo Producto -->

<div class="modal fade" id="ModalArticulo" aria-labelledby="ModalArticuloLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modificar_tipo_articulo">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="ModalArticuloLabel">Modificar Tipo Articulo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h1 class="col-md-12 col-md-offset-4 ">Modificar Tipo Articulo</h1>
                </div>
                <div class="mb-3">
                    <label for="nombre_articulo_modifi" class="form-label">Nombre Artículo : </label>
                    <input autocomplete="off" type="text" class="form-control" name="nombre_articulo" id="nombre_articulo_modifi">
                </div>
                <div class="mb-3">
                    <label for="id_clase_articulo_modifi" class="form-label">Clase Artículo : </label>
                    <select class="form-control select_2" style="width: 100%;" name="id_clase_articulo" id="id_clase_articulo_modifi">
                        <?php foreach ($clase_articulo as $class_art) { ?>
                            <option value="<?= $class_art->id_clase_articulo ?>"><?= $class_art->nombre_clase_articulo ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary" id="modificar_tipo_articulo" data-id="">Modificar</button>
            </div>
        </form>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_creacion_articulos.js"></script>
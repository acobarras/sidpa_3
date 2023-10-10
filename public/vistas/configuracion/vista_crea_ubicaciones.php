<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Ubicaciones</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="first-tab" data-bs-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="false">Nueva Ubicación</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="mx-3 mt-2">
                        <div class="text-center">
                            <h2>Tabla Ubicaciones</h2>
                        </div>
                        <br>
                        <table id="tabla_ubicaciones" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <th>#</th>
                                <th>Nombre Ubicación</th>
                                <th>Tipo Producto</th>
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
                    <form class="container" id="form_crear_ubicacion" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <br>
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Crear Ubicación</h1>
                        </div>
                        <div class="mb-3">
                            <label for="nombre_ubicacion" class="form-label">Nombre Ubicación : </label>
                            <input autocomplete="off" type="text" class="form-control" name="nombre_ubicacion" id="nombre_ubicacion" />
                        </div>
                        <div class="mb-3">
                            <label for="tipo_producto" class="form-label">Tipo Producto : </label>
                            <select class="form-control select_2" style="width: 100%;" id="tipo_producto" name="tipo_producto">
                                <option value="0">Ubicación Despacho</option>
                                <?php foreach ($clase_articulo as $value) { ?>
                                    <option value="<?= $value->id_clase_articulo ?>"><?= $value->nombre_clase_articulo ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="carga_inv" class="form-label">Se carga al inventario : </label>
                            <select class="form-control select_2" style="width: 100%;" id="carga_inv" name="carga_inv">
                                <option value="0"></option>
                                <option value="1">Si</option>
                                <option value="2">No</option>
                            </select>
                        </div>
                        <div class="mb-3 ">
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" id="crear_ubicacion">
                                    <i class="fa fa-plus-circle"></i> Crear Ubicación
                                </button>
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal para editar Ubicacion -->

<div class="modal fade" id="ModalUbicacion" aria-labelledby="ModalUbicacionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modificar_ubicacion">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="ModalUbicacionLabel">Modificar Ubicación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h1 class="col-md-12 col-md-offset-4 ">Modificar Ubicación</h1>
                </div>
                <div class="mb-3">
                    <label for="nombre_ubicacion_modifi" class="form-label">Nombre Ubicación : </label>
                    <input autocomplete="off" type="text" class="form-control" name="nombre_ubicacion" id="nombre_ubicacion_modifi">
                </div>
                <div class="mb-3">
                    <label for="tipo_producto_modifi" class="form-label">Tipo Producto : </label>
                    <select class="form-control select_2" style="width: 100%;" name="tipo_producto" id="tipo_producto_modifi">
                        <?php foreach ($clase_articulo as $value) { ?>
                            <option value="<?= $value->id_clase_articulo ?>"><?= $value->nombre_clase_articulo ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="carga_inv_modifi" class="form-label">Se carga al inventario : </label>
                    <select class="form-control select_2" style="width: 100%;" name="carga_inv" id="carga_inv_modifi">
                        <option value="0"></option>
                        <option value="1">Si</option>
                        <option value="2">No</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary" id="modificar_ubicacion" data-id="">Modificar</button>
            </div>
        </form>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_crea_ubicaciones.js"></script>
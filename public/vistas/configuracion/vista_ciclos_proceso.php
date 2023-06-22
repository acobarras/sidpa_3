<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Actividades</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="first-tab" data-bs-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="false">Tabla Área de trabajo</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <form class="container" id="form_crear_actividad_area" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <br>
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Crear Nueva Actividad</h1>
                        </div>
                        <div class="mb-3">
                            <label for="codigo_actividad_area" class="form-label">Codigo Actividad : </label>
                            <input autocomplete="off" type="text" class="form-control" name="codigo_actividad_area" id="codigo_actividad_area" />
                        </div>
                        <div class="mb-3">
                            <label for="id_area_trabajo" class="form-label">Área : </label>
                            <select class="form-control select_2" style="width: 100%;" id="id_area_trabajo" name="id_area_trabajo">
                                <option value="0"></option>
                                <?php foreach ($area_trabajo as $are_trabaj) { ?>
                                    <option value="<?= $are_trabaj->id_area_trabajo ?>"><?= $are_trabaj->nombre_area_trabajo ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nombre_actividad_area" class="form-label">Nombre Actividad : </label>
                            <input autocomplete="off" type="text" class="form-control" id="nombre_actividad_area" name="nombre_actividad_area" />
                        </div>
                        <div class="mb-3 ">
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" id="crear_actividad_area">
                                    <i class="fa fa-plus-circle"></i> Crear Actividad
                                </button>
                            </div>
                        </div>
                        <br>
                    </form>
                    <br>
                    <div class="mx-3 mt-2">
                        <div class="text-center">
                            <h2>Tabla de Actividades del área</h2>
                        </div>
                        <br>
                        <table id="tabla_actividad_area" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <th>#</th>
                                <th>Codigo</th>
                                <th>Área</th>
                                <th>Nombre Actividad</th>
                                <th>Estado</th>
                                <th>Opción</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade" id="first" role="tabpanel" aria-labelledby="first-tab">
                    <br>
                    <form class="container" id="form_crear_area_trabajo" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <br>
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Crear Área Trabajo</h1>
                        </div>
                        <div class="mb-3">
                            <label for="codigo_area_trabajo" class="form-label">Codigo : </label>
                            <input autocomplete="off" type="text" class="form-control" name="codigo_area_trabajo" id="codigo_area_trabajo" />
                        </div>
                        <div class="mb-3">
                            <label for="nombre_area_trabajo" class="form-label">Nombre : </label>
                            <input autocomplete="off" type="text" class="form-control" id="nombre_area_trabajo" name="nombre_area_trabajo">
                        </div>
                        <div class="mb-3 ">
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" id="crear_area_trabajo">
                                    <i class="fa fa-plus-circle"></i> Crear Área
                                </button>
                            </div>
                        </div>
                        <br>
                    </form>
                    <br>
                    <div class="mx-3 mt-2">
                        <div class="text-center">
                            <h2>Tabla de Área de trabajo</h2>
                        </div>
                        <br>
                        <table id="tabla_area_trabajo" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <th>#</th>
                                <th>Codigo</th>
                                <th>Nombre Área</th>
                                <th>Estado</th>
                                <th>Opción</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar Actividad Area -->

<div class="modal fade" id="ModalActividad" aria-labelledby="ModalActividadLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modificar_actividad_area">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="ModalActividadLabel">Modificar Actividad Área</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h1 class="col-md-12 col-md-offset-4 ">Modificar Actividad Área</h1>
                </div>
                <div class="mb-3">
                    <label for="codigo_actividad_area_modifi" class="form-label">Código : </label>
                    <input autocomplete="off" type="text" class="form-control" name="codigo_actividad_area" id="codigo_actividad_area_modifi">
                </div>
                <div class="mb-3">
                    <label for="id_area_trabajo_modifi" class="form-label">Área : </label>
                    <select class="form-control select_2" style="width: 100%;" name="id_area_trabajo" id="id_area_trabajo_modifi">
                        <?php foreach ($area_trabajo as $are_trabaj) { ?>
                            <option value="<?= $are_trabaj->id_area_trabajo ?>"><?= $are_trabaj->nombre_area_trabajo ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nombre_actividad_area_modifi" class="form-label">Nombre Actividad : </label>
                    <input autocomplete="off" type="text" class="form-control" name="nombre_actividad_area" id="nombre_actividad_area_modifi">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary" id="modificar_actividad_area" data-id="">Modificar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para editar Area Trabajo -->

<div class="modal fade" id="ModalArea" aria-labelledby="ModalAreaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modificar_area_trabajo">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="ModalAreaLabel">Modificar Area Trabajo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h1 class="col-md-12 col-md-offset-4 ">Modificar Area Trabajo</h1>
                </div>
                <div class="mb-3">
                    <label for="codigo_area_trabajo_modifi" class="form-label">Código : </label>
                    <input autocomplete="off" type="text" class="form-control" name="codigo_area_trabajo" id="codigo_area_trabajo_modifi">
                </div>
                <div class="mb-3">
                    <label for="nombre_area_trabajo_modifi" class="form-label">Nombre área trabajo : </label>
                    <input autocomplete="off" type="text" class="form-control" name="nombre_area_trabajo" id="nombre_area_trabajo_modifi" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary" id="modificar_area_trabajo" data-id="">Modificar</button>
            </div>
        </form>
    </div>
</div>


<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_ciclos_proceso.js"></script>
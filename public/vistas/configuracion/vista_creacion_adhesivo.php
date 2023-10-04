<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active btn btn-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Tabla Adhesivo</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link btn btn-link" id="agrega-tab" data-bs-toggle="tab" data-bs-target="#agrega-tab-pane" type="button" role="tab" aria-controls="agrega-tab-pane" aria-selected="true">Nuevo Adhesivo</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab">
                    <div class="text-center">
                        <h2>Tabla de Adhesivos</h2>
                    </div>
                    <br>
                    <table id="tabla_adhesivo" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                        <thead style="background: #002b5f;color: white">
                            <th>#</th>
                            <th>Código</th>
                            <th>nombre ADH</th>
                            <th>nombre corto</th>
                            <th>Superficies</th>
                            <th>Rango Temperatura</th>
                            <th>Opción</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade" id="agrega-tab-pane" role="tabpanel" aria-labelledby="agrega-tab">
                    <br>
                    <form class="container" id="form_crear_adhesivo" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <input id="id_adh" type="hidden" value="0" name="id_adh" />
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4" id="titulo_form">Crear Nuevo Adhesivo</h1>
                        </div>
                        <div class="mb-3">
                            <label for="codigo_adh" class="form-label">Código : </label>
                            <input autocomplete="off" type="text" class="form-control" name="codigo_adh" id="codigo_adh" />
                        </div>
                        <div class="mb-3">
                            <label for="nombre_adh" class="form-label">Nombre ADH : </label>
                            <input type="text" class="form-control" id="nombre_adh" name="nombre_adh" />
                        </div>
                        <div class="mb-3">
                            <label for="nombre_corto" class="form-label">Nombre Corto : </label>
                            <input type="text" class="form-control" id="nombre_corto" name="nombre_corto" />
                        </div>
                        <div class="mb-3">
                            <label for="superficies" class="form-label">Superficies : </label>
                            <input type="text" class="form-control" id="superficies" name="superficies" />
                        </div>
                        <div class="mb-3">
                            <label for="rango_temp" class="form-label">Rango de temperatura : </label>
                            <input type="text" class="form-control" id="rango_temp" name="rango_temp" />
                        </div>
                        <div class="mb-3 ">
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" id="crear_adhesivo">
                                    <i class="fa fa-plus-circle"></i> Crear Adhesivo
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

<!-- Modal para editar Adhesivo -->

<div class="modal fade" id="ModalAdhesivo" aria-labelledby="ModalAdhesivoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modificar_adhesivo">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="ModalAdhesivoLabel">Modificar Adhesivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h1 class="col-md-12 col-md-offset-4 ">Modificar Adhesivo</h1>
                </div>
                <div class="mb-3">
                    <label for="codigo_adh_modifi" class="form-label">Código : </label>
                    <input autocomplete="off" type="text" class="form-control" name="codigo_adh" id="codigo_adh_modifi">
                </div>
                <div class="mb-3">
                    <label for="nombre_adh_modifi" class="form-label">Nombre ADH : </label>
                    <input type="text" class="form-control" name="nombre_adh" id="nombre_adh_modifi">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary" id="modificar_adhesivo" data-id="">Modificar</button>
            </div>
        </form>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_creacion_adhesivo.js"></script>
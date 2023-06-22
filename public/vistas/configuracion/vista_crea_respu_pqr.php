<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Respuestas Pqr</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="first-tab" data-bs-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="false">Nueva Respuesta Pqr</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="mx-3 mt-2">
                        <div class="text-center">
                            <h2>Tabla Respuestas Pqr</h2>
                        </div>
                        <br>
                        <table id="tabla_respuestas_pqr" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <th>#</th>
                                <th>Código</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Opciónes</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade" id="first" role="tabpanel" aria-labelledby="first-tab">
                    <br>
                    <form class="container" id="form_crear_codigo_pqr" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <br>
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Crear Codigo Pqr</h1>
                        </div>
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Codigo : </label>
                            <input autocomplete="off" type="text" class="form-control" name="codigo" id="codigo" />
                        </div>
                        <div class="mb-3">
                            <label for="tipo_pqr" class="form-label">Tipo Pqr : </label>
                            <select class="form-control select_2" style="width: 100%;" id="tipo_pqr" name="tipo_pqr">
                                <option value="0"></option>
                                <option value="1">Comité</option>
                                <option value="2">General</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción : </label>
                            <input autocomplete="off" type="text" class="form-control" name="descripcion" id="descripcion" />
                        </div>
                        <div class="mb-3">
                            <label for="analisis_pqr" class="form-label">Análisis redactado : </label>
                            <input autocomplete="off" type="text" class="form-control" name="analisis_pqr" id="analisis_pqr" />
                        </div>
                        <div class="mb-3">
                            <label for="accion" class="form-label">Acción : </label>
                            <input autocomplete="off" type="text" class="form-control" name="accion" id="accion" />
                        </div>
                        <div class="mb-3 ">
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" id="crear_codigo_pqr">
                                    <i class="fa fa-plus-circle"></i> Crear Código
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
    <div class="modal-dialog modal-xl">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modificar_codigo_pqr">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="ModalUbicacionLabel">Modificar Codigo Pqr</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h1 class="col-md-12 col-md-offset-4 ">Modificar Codigo Pqr</h1>
                </div>
                <div class="mb-3">
                    <label for="codigo" class="form-label">Codigo : </label>
                    <input autocomplete="off" type="text" class="form-control" name="codigo" id="codigo_modifi" />
                </div>
                <div class="mb-3">
                    <label for="tipo_pqr" class="form-label">Tipo Pqr : </label>
                    <select class="form-control select_2" style="width: 100%;" id="tipo_pqr_modifi" name="tipo_pqr">
                        <option value="0"></option>
                        <option value="1">Comité</option>
                        <option value="2">General</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción : </label>
                    <input autocomplete="off" type="text" class="form-control" name="descripcion" id="descripcion_modifi" />
                </div>
                <div class="mb-3">
                    <label for="analisis_pqr" class="form-label">Análisis redactado : </label>
                    <input autocomplete="off" type="text" class="form-control" name="analisis_pqr" id="analisis_pqr_modifi" />
                </div>
                <div class="mb-3">
                    <label for="accion" class="form-label">Acción : </label>
                    <input autocomplete="off" type="text" class="form-control" name="accion" id="accion_modifi" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary" id="modificar_codigo_pqr" data-id="">Modificar</button>
            </div>
        </form>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_crea_respu_pqr.js"></script>
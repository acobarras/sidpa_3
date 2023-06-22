<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Perfiles Cargo</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="first-tab" data-bs-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="false">Nuevo Perfil Cargo</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="mx-3 mt-2">
                        <div class="text-center">
                            <h2>Tabla Perfiles</h2>
                        </div>
                        <br>
                        <table id="tabla_perfil_cargo" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <th>#</th>
                                <th>Nombre Cargo</th>
                                <th>Observaciones</th>
                                <th>Estado</th>
                                <th>Opcion</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade" id="first" role="tabpanel" aria-labelledby="first-tab">
                    <br>
                    <form class="container" id="form_crear_perfil_cargo" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <br>
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Crear Nuevo Perfil De Cargo</h1>
                        </div>
                        <div class="mb-3">
                            <label for="nombre_cargo" class="form-label">Nombre Cargo : </label>
                            <input autocomplete="off" type="text" class="form-control" name="nombre_cargo" id="nombre_cargo" />
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción : </label>
                            <textarea class="form-control" name="descripcion" id="descripcion" style="height: 214px; resize: none;"></textarea>
                        </div>
                        <div class="mb-3 ">
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" id="crear_perfil_cargo">
                                    <i class="fa fa-plus-circle"></i> Crear Perfil
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

<!-- Modal para editar Tipo Producto -->

<div class="modal fade" id="ModalPerfilCargo" aria-labelledby="ModalPerfilCargoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modificar_perfil_cargo">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="ModalPerfilCargoLabel">Modificación Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h1 class="col-md-12 col-md-offset-4 ">Modificación Perfil</h1>
                </div>
                <div class="mb-3">
                    <label for="nombre_cargo_modifi" class="form-label">Nombre Cargo : </label>
                    <input autocomplete="off" type="text" class="form-control" name="nombre_cargo" id="nombre_cargo_modifi">
                </div>
                <div class="mb-3">
                    <label for="descripcion_modifi" class="form-label">Descripción : </label>
                    <textarea class="form-control" name="descripcion" id="descripcion_modifi" style="height: 214px; resize: none;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary" id="modificar_perfil_cargo" data-id="">Modificar</button>
            </div>
        </form>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_perfil_cargo.js"></script>
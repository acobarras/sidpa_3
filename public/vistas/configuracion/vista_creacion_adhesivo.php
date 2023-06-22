<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <br>
            <form class="container" id="form_crear_adhesivo" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                <br>
                <div class="mb-3 row">
                    <h1 class="col-md-12 col-md-offset-4 ">Crear Nuevo Adhesivo</h1>
                </div>
                <div class="mb-3">
                    <label for="codigo_adh" class="form-label">Código : </label>
                    <input autocomplete="off" type="text" class="form-control" name="codigo_adh" id="codigo_adh" />
                </div>
                <div class="mb-3">
                    <label for="nombre_adh" class="form-label">Nombre ADH : </label>
                    <input type="text" class="form-control" id="nombre_adh" name="nombre_adh" />
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
            <br>
            <div class="mx-3 mt-2">
                <div class="text-center">
                    <h2>Tabla de Adhesivos</h2>
                </div>
                <br>
                <table id="tabla_adhesivo" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                    <thead style="background: #002b5f;color: white">
                        <th>#</th>
                        <th>Código</th>
                        <th>nombre ADH</th>
                        <th>Opción</th>
                    </thead>
                    <tbody></tbody>
                </table>
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
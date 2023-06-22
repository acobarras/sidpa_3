<div class="modal fade" id="Modal_crea_ubicacion" data-bs-backdrop="static" aria-labelledby="LargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal">
                    <p> </p>
                </div>
                <h5 class="modal-title">Crear una ubicación
                </h5>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <form id="form_ubicacion">
                    <div class="mb-3 row">
                        <label for="nombre_ubicacion" class="col-sm-2 col-form-ablel fw-bold">Nombre Ubicacion:</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="nombre_ubicacion" id="nombre_ubicacion">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tipo_producto" class="col-sm-2 col-form-ablel fw-bold">Tipo producto:</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="tipo_producto" id="tipo_producto" style="width: 100%;">
                                <option value="0"></option>
                                <?php foreach ($tipo_producto as $value) { ?>
                                    <option value="<?= $value->id_clase_articulo ?>"><?= $value->nombre_clase_articulo ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary" id="btn_crea_ubicacion" data-valida="">
                            <i class="fa fa-plus-circle"></i> Grabar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
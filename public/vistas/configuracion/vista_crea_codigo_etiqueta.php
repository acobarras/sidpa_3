<div class="modal fade" id="Modal_crea_codigo" data-bs-backdrop="static" aria-labelledby="LargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal">
                    <p> </p>
                </div>
                <h5 class="modal-title">Crear codigo producto
                </h5>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <form id="form_valida_codigo">
                    <div class="mb-3 row">
                        <div class="col-6">
                            <label for="ancho" class="form-label fw-bold">Ancho:</label>
                            <input class="form-control" type="text" name="ancho" id="ancho">
                        </div>
                        <div class="col-6">
                            <label for="alto" class="form-label fw-bold">Alto:</label>
                            <input class="form-control" type="text" name="alto" id="alto">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-6">
                            <label for="tipo_product" class="form-label fw-bold">Tipo Producto:</label>
                            <select class="form-control select_2" style="width: 100%;" name="tipo_product" id="tipo_product">
                                <option value="0"></option>
                                <option value="1">Etiquetas</option>
                                <option value="2">Hojas</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="forma_material" class="form-label fw-bold">Forma Material:</label>
                            <select class="form-control select_2" style="width: 100%;" name="forma_material" id="forma_material">
                                <option value="0"></option>
                                <?php foreach ($forma_material as $value) { ?>
                                    <option value="<?= $value->id_forma ?>"><?= $value->nombre_forma ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-6">
                            <label for="tipo_material" class="form-label fw-bold">Tipo Material:</label>
                            <select class="form-control select_2" style="width: 100%;" name="tipo_material" id="tipo_material">
                                <option value="0"></option>
                                <?php foreach ($tipo_material as $value) { ?>
                                    <option value="<?= $value->codigo ?>"><?= $value->nombre_material ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="id_adh" class="form-label fw-bold">Adhesivo:</label>
                            <select class="form-control select_2" style="width: 100%;" name="id_adh" id="id_adh">
                                <option value="0"></option>
                                <?php foreach ($adh as $value) { ?>
                                    <option value="<?= $value->codigo_adh ?>"><?= $value->nombre_adh ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-6">
                            <label for="cavidad" class="form-label fw-bold">Cavidad:</label>
                            <input class="form-control" type="text" name="cavidad" id="cavidad">
                        </div>
                        <div class="col-6">
                            <label for="cant_tintas" class="form-label fw-bold">Cantidad tintas:</label>
                            <select class="form-control select_2" style="width: 100%;" name="cant_tintas" id="cant_tintas">
                                <option value=""></option>
                                <?php foreach ($tintas as $value) { ?>
                                    <option value="<?= $value->numeros ?>"><?= $value->num_tintas ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-6">
                            <label for="gaf_cort" class="form-label fw-bold">Grafes y Cortes:</label>
                            <select class="form-control select_2" style="width: 100%;" name="gaf_cort" id="gaf_cort">
                                <option value=""></option>
                                <?php foreach (GRAF_CORTE as $key => $value) { ?>
                                    <option value="<?= $key ?>"><?= $value['nombre'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="desc_etiq" class="form-label fw-bold">Descripción Etiqueta:</label>
                            <input class="form-control" type="text" name="desc_etiq" id="desc_etiq">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-6">
                            <label for="cod_final" class="form-label fw-bold">Codigo Final:</label>
                            <span class="form-control" id="cod_final">&nbsp;</span>
                        </div>
                        <div class="col-6">
                            <label for="desc_final" class="form-label fw-bold">Descripción Final:</label>
                            <span class="form-control" id="desc_final">&nbsp;</span>
                        </div>
                    </div>
                    <span id="error_codigo"></span>
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary" id="btn_valida_codigo" data-valida="">
                            <i class="fa fa-plus-circle"></i> Validar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_crea_codigo_etiqueta.js"></script>
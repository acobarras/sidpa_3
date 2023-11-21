<!-- Modal para la impresion del item embobinado -->
<div class="modal fade" id="ImpresionItemsModal" aria-labelledby="ImpresionItemsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="formulario_remarcacion">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="ImpresionItemsModalLabel">DATOS IMPRESIÓN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="tamano" class="col-sm-2 col-form-label">Tamaño Etiqueta:</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="tamano" id="tamano">
                            <?php foreach ($tamano_impresion as $value) { ?>
                                <option value="<?= $value->id ?>"><?= $value->tamano ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <?php
                if ($_SESSION['usuario']->getId_roll() == 12 || $_SESSION['usuario']->getId_roll() == 1) {// EL ID ROLL PARA ETICARIBE ES 9 EN VEZ DE 12
                    $stylo = '';
                    $nombre = 'operario';
                    $valor_id_persona = '';
                } else {
                    $stylo = 'style="display: none;"';
                    $nombre = '';
                    // $valor_id_persona = 'value = "'. $_SESSION['usuario']->getId_persona().'"';
                    $valor_id_persona =  $_SESSION['usuario']->getId_persona();
                } ?>
                <div class="mb-3 row" id="div_operario" <?= $stylo ?>>
                    <label for="operario" class="col-2 form-label">Código Operario : </label>
                    <div class="col-10">
                        <input autocomplete="off" type="password" class="form-control codigo_operario" name="<?= $nombre ?>" id="operario">
                        <span class="respu_consulta"></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="lote" class="col-2 col-form-label">Lote:</label>
                    <div class="col-10">
                        <input class="form-control" type="text" name="lote" id="lote">
                    </div>
                </div>
                <div class="mb-3 row cajass" style="display:none;">
                    <label for="caja" class="col-2 col-form-label">Total Caja:</label>
                    <div class="col-10">
                        <input class="form-control" type="text" name="caja" id="caja">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="cantidad" class="col-2 col-form-label">Etiquetas a Imprimir:</label>
                    <div class="col-10">
                        <input class="form-control" type="text" name="cantidad" id="cantidad">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="cant_x" class="col-2 col-form-label">Rollo X:</label>
                    <div class="col-10">
                        <input class="form-control" type="text" name="cant_x" id="cant_x">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <label for="id_persona" style="display: none;">Codigo Operario:</label>
                <input type="hidden" class="id_persona" name="id_persona" id="id_persona" data-persona="<?= $valor_id_persona ?>">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary boton_codigo_operario" id="boton_imprime">Imprimir</button>
            </div>
            <br>
            <div class="div_impresion"></div>
        </form>
    </div>
</div>
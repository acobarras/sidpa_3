<div>
    <div class="recuadro p-4">
        <br>
        <div class="shadow p-3 mb-5 bg-body-tertiary rounded">
            <div class="mb-3 text-center">
                <h1>DATOS PARA LA MARCACIÓN BOBINAS </h1>
            </div>
            <div class="col-lg-9 col-12 m-auto">
                <div class="row ">
                    <div class="col-lg-10 col-12">
                        <label for="codigo">Código Bobina:</label>
                        <select name="codigo" id="codigo" class="select_2" style="width: 100%;">
                            <option value="0" selected>Seleccione un material</option>
                            <?php
                            foreach ($bobinas as $key => $value) { ?>
                                <option value="<?= $value->codigo_producto ?>"><?= $value->codigo_producto ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-12 d-flex justify-content-center m-auto">
                        <div class="col-2 m-auto mt-3">
                            <button type="button" id="consulta" class="btn btn-success">Consultar</button>
                        </div>
                    </div>
                </div>
                <br>
                <div class="col-lg-9 col-12 m-auto shadow-sm p-3 mb-5 bg-body-tertiary rounded" id="contenedor" style="display: none;">
                    <input type="hidden" id="tamano" value="<?= $tamano[0]->id ?>">
                    <form id="formulario_bobinas">
                        <input type="hidden" name="codigo" id="codigo_form">
                        <div class="mb-3 row">
                            <label for="descripcion" class="col-sm-2 col-form-label fw-bold">Descripción:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="descripcion" id="descripcion" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="ancho" class="col-sm-2 col-form-label fw-bold">Ancho material:</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="ancho" id="ancho">
                            </div>
                        </div>
                        <?php
                        if ($_SESSION['usuario']->getId_roll() == 12 || $_SESSION['usuario']->getId_roll() == 1) { // EL ID ROLL PARA ETICARIBE ES 9 EN VEZ DE 12
                            $stylo = '';
                            $nombre = 'operario_digitado';
                            $valor_id_persona = '';
                        } else {
                            $stylo = 'style="display: none;"';
                            $nombre = '';
                            // $valor_id_persona = 'value = "'. $_SESSION['usuario']->getId_persona().'"';
                            $valor_id_persona =  $_SESSION['usuario']->getId_persona();
                        } ?>
                        <div class="mb-3 row" <?= $stylo ?>>
                            <label for="operario_digitado" class="col-sm-2 col-form-label fw-bold">Codigo Operario:</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="<?= $nombre ?>" id="operario_digitado">
                                <span class="respu_consulta"></span>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="peso" class="col-sm-2 col-form-label fw-bold">Peso:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="peso" id="peso">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="lote" class="col-sm-2 col-form-label fw-bold">Lote:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="lote" id="lote">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="ml" class="col-sm-2 col-form-label fw-bold">Metros Lineales:</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="ml" id="ml">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="cantidad" class="col-sm-2 col-form-label fw-bold">Cantidad Etiquetas:</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="cantidad" id="cantidad">
                            </div>
                        </div>
                        <label for="id_persona" style="display: none;">Codigo Operario:</label>
                        <input type="hidden" class="id_persona" id="id_persona" name="id_persona" data-persona="<?= $valor_id_persona ?>"">
                        <!-- aqui va el operario  -->
                        <br>
                            <button type="submit" class="btn btn-primary d-block m-auto" id="imprimir">Imprimir</button>
                        <br>
                        <div class="div_impresion"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/almacen/js/vista_marcacion_bobinas.js"></script>
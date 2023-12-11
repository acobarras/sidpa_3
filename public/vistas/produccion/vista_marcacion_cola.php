<div>
    <div class="recuadro p-4">
        <br>
        <div class="shadow p-3 mb-5 bg-body-tertiary rounded">
            <div class="mb-3 text-center">
                <h1>DATOS PARA LA MARCACIÓN COLAS </h1>
            </div>
            <div class="col-lg-9 col-12 m-auto ">
                <div class="row">
                    <div class="col-md-10 col-12">
                        <div class="input-group mb-3 ">
                            <span class="input-group-text" id="inputGroup-sizing-default">Numero de O.P</span>
                            <input type="number" class="form-control" id="op" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                        </div>
                    </div>
                    <div class="col-md-2 col-10 d-flex justify-content-center m-auto mb-3">
                        <button class="btn btn-success mx-3 " id="consulta_op" type="button">Consultar</button>
                    </div>
                </div>
                <br>
                <div class="col-lg-9 col-12 m-auto shadow-sm p-3 mb-5 bg-body-tertiary rounded" id="contenedor" style="display: none;">
                    <input type="hidden" id="tamano" value="<?= $tamano[0]->id ?>">
                    <form id="formulario_etiqueta">
                        <div class="mb-3 row">
                            <label for="codigo" class="col-sm-2 col-form-label fw-bold">Código material:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="codigo" id="codigo" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="ancho" class="col-sm-2 col-form-label fw-bold">Ancho material:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="ancho" id="ancho">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="descripcion" class="col-sm-2 col-form-label fw-bold">Descripción:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="descripcion" id="descripcion" readonly>
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
                                <input type="text" class="form-control" name="ml" id="ml">
                            </div>
                        </div>
                        <label for="id_persona" style="display: none;">Codigo Operario:</label>
                        <input type="hidden" class="id_persona" id="id_persona" name="id_persona" data-persona="<?= $valor_id_persona ?>">
                        <!-- aqui va el operario  -->
                        <br>
                        <div>
                            <button type="submit" class="btn btn-primary d-block m-auto" id="imprimir">Imprimir</button>
                        </div>
                        <br>
                        <div class="div_impresion"></div>
                    </form>
                    <br>
                </div>
            </div>

        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/produccion/js/vista_marcacion_cola.js"></script>
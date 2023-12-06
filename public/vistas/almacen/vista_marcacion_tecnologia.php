<div>
    <div class="recuadro p-4">
        <br>
        <div class="shadow p-3 mb-5 bg-body-tertiary rounded">
            <div class="mb-3 text-center">
                <h1>DATOS PARA MARCACIÓN DE TECNOLOGÍA</h1>
            </div>
            <div class="col-lg-9 col-12 m-auto">
                <div class="row">
                    <div class="col-lg-10 col-12">
                        <label for="nparte">Numero de parte:</label>
                        <select name="nparte" id="nparte" class="select_2" style="width: 100%;">
                            <option value="0" selected>Seleccione un producto</option>
                            <?php
                            foreach ($tecnologia as $key => $value) { ?>
                                <option value="<?= $value->codigo_producto ?>"><?= $value->codigo_producto ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <div class="col-lg-2 col-12 d-flex justify-content-center m-auto">
                            <div class="col-2 m-auto mt-3">
                                <button type="button" id="consulta" class="btn btn-success">Consultar</button>
                            </div>
                        </div>
                        <br>
                        <div class="col-lg-9 col-12 m-auto shadow-sm p-3 mb-5 bg-body-tertiary rounded" id="contenedor" style="display: none;">
                            <input type="hidden" id="tamano" value="<?= $tamano[0]->id ?>">
                            <form id="formulario_tecnologia">
                                <input type="hidden" name="nparte" id="nparte_form">
                                <div class="mb-3 row">
                                    <label for="descripcion" class="col-sm-2 col-form-label fw-bold">Descripción:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="descripcion" id="descripcion" readonly>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="cantidad" class="col-sm-2 col-form-label fw-bold">Cantidad producto:</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="cantidad" id="cantidad">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="cantidad_eti" class="col-sm-2 col-form-label fw-bold">Cantidad Etiquetas:</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="cantidad_eti" id="cantidad_eti">
                                    </div>
                                </div>
                                <input type="hidden" class="id_persona" id="id_persona" name="id_persona" data-persona="<?= $_SESSION['usuario']->getId_persona() ?>">
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
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src=" <?= PUBLICO ?>/vistas/almacen/js/vista_marcacion_tecnologia.js"></script>
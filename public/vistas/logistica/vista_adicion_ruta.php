<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12" id="tabla_facturacion_listado">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Agrega Diligencia</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <form class="panel panel-default" id="adiciona_ruta">
                        <div class="panel-heading text-center mb-3">
                            <h3><b>Agregar Diligencia</b></h3>
                        </div>
                        <div class="panel-body">
                            <div class="mb-3">
                                <div class="row">
                                    <label class="col-2" for="documento">Documento:</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control" id="documento" name="documento" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row">
                                    <label class="col-2" for="id_transportador">Transportador:</label>
                                    <div class="col-10">
                                        <select class="form-control select_2" id="id_transportador" name="id_transportador">
                                            <option value="0"></option>
                                            <?php foreach ($personas as $value) { ?>
                                                <option value="<?= $value->id_persona; ?>"><?= $value->nombres . " " . $value->apellidos; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row">
                                    <label class="col-2" for="valor_flete">Valor Flete:</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control" id="valor_flete" name="valor_flete">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row">
                                    <label class="col-2" for="observacion">Diligencia:</label>
                                    <div class="col-10">
                                        <textarea class="form-control" id="observacion" name="observacion" cols="40" rows="5" style="resize: none;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-center">
                            <button type="submit" class="btn btn-success" id="adiciona_diligencia">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/logistica/js/vista_adicion_ruta.js"></script>
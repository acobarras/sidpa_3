<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="ingreso-trm-tab" data-bs-toggle="tab" href="#ingreso-trm-tab" role="tab" aria-controls="ingreso-trm-tab" aria-selected="true">Ingreso TRM</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Primer link -->
                    <div class="tab-pane fade show active" id="ingreso-trm-tab" role="tabpanel" aria-labelledby="ingreso-trm-tab">
                        <br>
                        <div class="container">
                            <div class="recuadro">
                                <div class="container">

                                    <br>
                                    <form id="form_envio_trm">
                                        <div class="form-group row">
                                            <label for="fecha_crea" class="col-md-2">Fecha TRM :</label>
                                            <div class="col-md-10">
                                                <input autocomplete="off" class="form-control" type="date" name="fecha_crea" id="fecha_crea">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label for="valor_trm" class="col-md-2">Valor TRM :</label>
                                            <div class="col-md-10">
                                                <input autocomplete="off" class="form-control" type="text" name="valor_trm" id="valor_trm" placeholder="EJ:(3565.82)">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="panel-footer">
                                            <center>
                                                <button type="submit" id="btn_registrar_trm" class="btn btn-lg btn-primary">Enviar</button>
                                            </center>
                                    </form>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
    <script src="<?= PUBLICO ?>/vistas/Contabilidad/js/ingreso_trm.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="nav-codigo-tab" data-bs-toggle="tab" href="#nav-codigo" role="tab" aria-controls="nav-codigo" aria-selected="true">Solicitud De Código</a>
                        <a class="nav-link" id="nav-diseno-tab" data-bs-toggle="tab" href="#nav-diseno" role="tab" aria-controls="nav-diseno" aria-selected="true">Solicitud De Diseño</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-codigo" role="tabpanel" aria-labelledby="nav-codigo-tab">
                        <div class="recuadro">
                            <div class="container-fluid">
                                <center>
                                    <iframe src=<?= FORM_DISENOCOD ?> width="1000" height="5420" frameborder="0" marginheight="0" marginwidth="0">Cargando…</iframe>
                                </center>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade show" id="nav-diseno" role="tabpanel" aria-labelledby="nav-diseno-tab">
                        <div class="recuadro">
                            <div class="container-fluid">
                                <center>
                                    <iframe src=<?= FORM_DISENODIS ?> width="1000" height="5420" frameborder="0" marginheight="0" marginwidth="0">Cargando…</iframe>
                                </center>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
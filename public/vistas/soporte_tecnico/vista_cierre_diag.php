<div id="principal_gestion" class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="laboratorio_soporte" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab-solicitud" role="tablist">
                        <a class="nav-link active" id="nav-solicitud-tab" data-bs-toggle="tab" href="#nav-solicitud" role="tab" aria-controls="nav-solicitud" aria-selected="true">Cierre Diagnostico</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent-solicitud">
                    <div class="tab-pane fade show active" id="nav-solicitud" role="tabpanel" aria-labelledby="nav-solicitud-tab">
                        <div class="container-fluid">
                            <br>
                            <div class="mb-3 text-center row">
                                <h1 class="col-md-12 col-md-offset-4 ">Cierre Diagnostico</h1>
                            </div>
                            <div class="table-responsive">
                                <div class="container-fluid">
                                    <table id="tabla_cierre_diag" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                        <thead style="background:#0d1b50;color:white">
                                            <tr>
                                                <td>Id diagnostico Item</td>
                                                <td>Consecutivo</td>
                                                <td>Cliente</td>
                                                <td>Item</td>
                                                <td>Equipo</td>
                                                <td>Serial</td>
                                                <td>Estado Diagnostico</td>
                                                <td>Opción</td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class=" col-4 btn btn-info" id="generar_acta" type="button">Generar Acta</button>
                            </div>
                            <div class="text-center">
                                <button class=" col-4 btn btn-info d-none" id="pdf_acta" type="button" data="">PDF Acta</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/soporte_tecnico/js/vista_cierre_diag.js"></script>
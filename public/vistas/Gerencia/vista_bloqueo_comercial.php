<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="bloqueo_comercial-tab" data-bs-toggle="tab" href="#bloqueo_comercial" role="tab" aria-controls="bloqueo_comercial" aria-selected="true">Liquida Comisión</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Primer link -->
                    <div class="tab-pane fade show active m-4" id="bloqueo_comercial" role="tabpanel" aria-labelledby="bloqueo_comercial-tab">
                        <div class="mb-3 row text-center">
                            <h1 class="col-md-12 col-md-offset-4 ">Bloqueo Asesor Comercial
                                <button type="button" class="btn btn-danger bloquear_todos" value="1" title="Bloquear a todos">
                                    <i class="fas fa-user-slash"></i>
                                </button>
                                <button type="button" class="btn btn-success bloquear_todos" value="0" title="Desbloquear a todos">
                                    <i class="fas fa-user"></i>
                                </button>
                            </h1>
                        </div>
                        <div>
                            <table class="table table-bordered table-responsive table-hover" id="tabla_asesores" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Asesor</th>
                                        <th>Opción(Bloquear Asesor)</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Gerencia/js/vista_bloqueo_comercial.js"></script>
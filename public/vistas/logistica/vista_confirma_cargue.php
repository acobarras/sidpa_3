<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12" id="tabla_facturacion_listado">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Confirmar Cargue</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <div class="panel panel-default" id="adiciona_ruta">
                        <div class="panel-heading text-center mb-3">
                            <h3><b>Tabla Confirmar Cargue</b></h3>
                        </div>
                        <div class="panel-body">
                            <table id="tabla_confirma_flete" class="table table-bordered table-responsive table-hover mb-3" cellspacing="0" width="100%">
                                <thead style="background: #002b5f;color: white">
                                    <tr>
                                        <td>Fecha Cargue</td>
                                        <td>Transportador</td>
                                        <td>Documento</td>
                                        <td>Valor Documento</td>
                                        <td>Valor Flete</td>
                                        <td>Observación</td>
                                        <td>Opción</td>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <div class="mb-3 text-center">
                                <button id="acepta_flete" class="btn btn-success">Acepta Flete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/logistica/js/vista_confirma_cargue.js"></script>
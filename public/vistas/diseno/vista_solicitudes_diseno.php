<div class="container-fluid p-4">
    <div class="recuadro p-2">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="codigo-tab" data-bs-toggle="tab" href="#codigo" role="tab" aria-controls="codigo" aria-selected="true">Solicitud de creación de código</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
        <div class="recuadro p-2">
            <div class="tab-pane fade show active p-3" id="codigo" role="tabpanel" aria-labelledby="codigo-tab">
                <div class="panel panel-default">
                    <br>
                    <div class="panel-heading text-center mb-3">
                        <h2>Solicitud de creación de código</h2>
                    </div>
                    <div class="table-responsive p-3">
                        <table id="tb_solicitudes" class=" table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                            <thead class="bg-layout" style="background:#0d1b50;color:white">
                                <tr>
                                    <th>id</th>
                                    <th>Nit</th>
                                    <th>Nombre empresa</th>
                                    <th>Asesor</th>
                                    <th>Tamaño</th>
                                    <th>Tipo producto</th>
                                    <th>Forma</th>
                                    <th>Material</th>
                                    <th>Adhesivo</th>
                                    <th>Cavidades</th>
                                    <th>Cant. tintas</th>
                                    <th>Terminados</th>
                                    <th>Grafe</th>
                                    <th>Observaciones</th>
                                    <th>Codigo_creado</th>
                                    <th>Acción</th>
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
<script src="<?= PUBLICO ?>/vistas/diseno/js/vista_solicitudes_diseno.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla de PQR</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Motivos PQR</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <div class="mb-3 text-center">
                        <h3>Tabla General PQR</h3>
                    </div>
                    <form id="enviar_ano_general">
                        <div class="row">
                            <div class="col-3">
                                <label for="ano_general">Año Consulta</label>
                                <select class="form-control select_2" id="ano_general" style="width: 100%;">
                                    <?php
                                    $ano = 2020;
                                    $ano_actual = date("Y");
                                    for ($i = $ano; $i < ($ano + 50); $i++) {
                                        if ($i == $ano_actual) { ?>
                                            <option value="<?= $i ?>" selected><?= $i ?></option>
                                        <?php } else { ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-primary btn-lg" id="envio_ano">Grabar</button>
                            </div>
                        </div>
                    </form>
                    <table id="tabla_general_pqr" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                        <thead style="background: #002b5f;color: white">
                            <tr>
                                <th>Fecha Radicación</th>
                                <th>Numero PQR</th>
                                <th>Dirección PQR</th>
                                <th>Contacto</th>
                                <th>Cantidad PQR</th>
                                <th>Cliente</th>
                                <th>Asesor</th>
                                <th>Codigo Producto</th>
                                <th>Descripción Producto</th>
                                <th>Pedido Item</th>
                                <th>Pedido Cambio</th>
                                <th>Codigo Motivo</th>
                                <th>Descripción Motivo</th>
                                <th>Clasificación</th>
                                <th>Responsable</th>
                                <th>Costo</th>
                                <th>Observación</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- Segundo link -->
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <br>
                    <div class="text-center">
                        <h3>Motivos PQR</h3>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-6">
                            <div class="mb-3 row">
                                <div class="col-6">
                                    <label for="ano_consulta">Año Consulta</label>
                                    <select class="form-control select_2" id="ano_consulta" style="width: 100%;">
                                        <?php
                                        $ano = 2020;
                                        $ano_actual = date("Y");
                                        for ($i = $ano; $i < ($ano + 50); $i++) {
                                            if ($i == $ano_actual) { ?>
                                                <option value="<?= $i ?>" selected><?= $i ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $i ?>"><?= $i ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="mes">Mes</label>
                                    <select class="form-control select_2" id="mes" style="width: 100%;">
                                        <option value="0"></option>
                                        <?php foreach (PQR_MES as $key => $value) { ?>
                                            <option value="<?= $value ?>"><?= $key ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <table id="tabla_motivo_pqr" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                <thead style="background: #002b5f;color: white">
                                    <tr>
                                        <th>codigo</th>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-6">
                            <canvas id="myChart1" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="exampleModalLabel">Datos Motivo PQR</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="registros_pqr" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                        <thead style="background: #002b5f;color: white">
                            <tr>
                                <th>Fecha</th>
                                <th>Numero PQR</th>
                                <th>Cliente</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/indicadores/js/vista_indicador_pqr.js"></script>
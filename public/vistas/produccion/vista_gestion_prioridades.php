<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Gestionar Prioridades</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="mx-3 mt-2">
                        <div class="text-center">
                            <h2>Tabla Gestionar Prioridades</h2>
                        </div>
                        <br>
                        <table id="tabla_gestion_pri" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <tr>
                                    <th>Id</th>
                                    <th>Numero Op</th>
                                    <th>Item</th>
                                    <th>Fecha Compromiso</th>
                                    <th>Observaciones</th>
                                    <th>Opción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal obsevaciones prioridades-->
<div class="modal fade" id="obs_prioridad" aria-labelledby="obs_prioridadLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <div class="row">
                    <div class="col">
                        <h5 class="modal-title">Observaciones de Prioridades</h5>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="container" id="observacion_gestion">
                    <input type="hidden" class="form-control" id="id_prioridad" name="id_prioridad">
                    <input type="hidden" class="form-control" id="estado" name="estado">
                    <div class="col-12">
                        <label for="observacion">Observaciones</label>
                        <textarea class="form-control" name="observacion" id="observacion"></textarea>
                    </div>
                    <div class="col-12">
                        <label for="coordinador" class="form-label">Coordinador</label>
                        <select class="form-control select_2" id="coordinador" name="coordinador">
                            <option value="0">Elija un coordinador</option>
                            <?php foreach ($coordinadores as $value) { ?>
                                <option value="<?= $value->id_usuario ?>"> <?= $value->nombre . ' ' . $value->apellido ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="enviar_gestion">Enviar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/produccion/js/vista_gestion_prioridades.js"></script>
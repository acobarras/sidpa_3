<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Formulario Prioridades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Resumen Prioridades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="consulta-tab" data-bs-toggle="tab" href="#consulta" role="tab" aria-controls="consulta" aria-selected="false">Prioridades Cerradas</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <div class="mb-3 text-center">
                        <h3>Formulario Prioridades</h3>
                    </div>
                    <form class="container" id="form_prioridad">
                        <div class="row">
                            <input type="hidden" class="form-control" id="id_prioridad" value="0" name="id_prioridad">
                            <div class="col-md-4 col-12">
                                <div class="mb-3">
                                    <label for="orden_produccion" class="form-label">Orden Produccion</label>
                                    <input type="number" class="form-control" id="orden_produccion" name="orden_produccion">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="mb-3">
                                    <label for="item" class="form-label">Item</label>
                                    <select class="form-control select_2" id="item" name="item">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="mb-3">
                                    <label for="fecha_compro" class="form-label">Fecha Compromiso</label>
                                    <input type="date" readonly class="form-control" id="fecha_compro" name="fecha_compro">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="mb-3">
                                    <label for="coordinador" class="form-label">Coordinador</label>
                                    <select class="form-control select_2" id="coordinador" name="coordinador">
                                        <option value="0">Elija un coordinador</option>
                                        <?php foreach ($coordinadores as $value) { ?>
                                            <option value="<?= $value->id_usuario ?>"> <?= $value->nombre . ' ' . $value->apellido ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="observacion">Observaciones</label>
                                <textarea class="form-control" name="observacion" id="observacion"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="m-auto btn btn-success col-3 d-block mt-3" id="enviar_prioridad">Grabar</button>
                    </form>
                </div>
                <!-- Segundo link -->
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <br>
                    <div class="text-center">
                        <h3>Prioridades</h3>
                    </div>
                    <div class="mb-3 row">
                        <div class="container col-12">
                            <table id="tabla_prioridades" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                <thead style="background: #002b5f;color: white">
                                    <tr>
                                        <th>Id</th>
                                        <th>Numero Op</th>
                                        <th>Item</th>
                                        <th>Fecha Compromiso</th>
                                        <th>Observaciones</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Tercer Link -->
                <div class="tab-pane fade" id="consulta" role="tabpanel" aria-labelledby="consulta-tab">
                    <br>
                    <div class="text-center">
                        <h3>Prioridades Cerradas</h3>
                    </div>
                    <div class="mb-3 row">
                        <div class="container col-12">
                            <table id="prioridades_cerradas" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                <thead style="background: #002b5f;color: white">
                                    <tr>
                                        <th>Id</th>
                                        <th>Numero Op</th>
                                        <th>Item</th>
                                        <th>Fecha Compromiso</th>
                                        <th>Observaciones</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/produccion/js/vista_prioridades.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="pqr-product-tab" data-bs-toggle="tab" href="#pqr-product" role="tab" aria-controls="pqr-product" aria-selected="true">PQR Generales</a>
                        <a class="nav-link" id="pqr-comite-tab" data-bs-toggle="tab" href="#pqr-comite" role="tab" aria-controls="pqr-comite" aria-selected="true">PQR Comite</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="pqr-product" role="tabpanel" aria-labelledby="pqr-product-tab">
                        <br>
                        <div class="mb-3 text-center row">
                            <h1 class="col-md-12 col-md-offset-4 ">Tabla Gestión Cierre PQR Generales</h1>
                        </div>
                        <br>
                        <div>
                            <table id="tabla_cierre_general_pqr" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                                <thead style="background: #002b5f;color: white">
                                    <tr>
                                        <td>Fecha</td>
                                        <td>Núm PQR</td>
                                        <td>Cliente</td>
                                        <td>Pedido Item</td>
                                        <td>Motivo PQR</td>
                                        <td>Cantidad</td>
                                        <td>Estado</td>
                                        <td>Opción </td>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <!-- segundo link -->
                    <div class="tab-pane fade" id="pqr-comite" role="tabpanel" aria-labelledby="pqr-comite-tab">
                        <br>
                        <div class="mb-3 text-center row">
                            <h1 class="col-md-12 col-md-offset-4 ">Tabla Gestión Cierre PQR Comite</h1>
                        </div>
                        <br>
                        <div>
                            <table id="tabla_cierre_comite_pqr" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                                <thead style="background: #002b5f;color: white">
                                    <tr>
                                        <td>Fecha</td>
                                        <td>Núm PQR</td>
                                        <td>Cliente</td>
                                        <td>Pedido Item</td>
                                        <td>Motivo PQR</td>
                                        <td>Cantidad</td>
                                        <td>Estado</td>
                                        <td>Opción </td>
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

<!-- Modal Cierre General -->
<div class="modal fade" id="codigoMotivo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="codigoMotivoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="codigoMotivoLabel">Determinación de motivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="codigo_motivo" class="form-label">Codigo Motivo : </label>
                    <select class="form-control select_2" style="width: 100%;" id="codigo_motivo" name="codigo_motivo">
                        <option value="0"></option>
                        <?php foreach ($motivo_pqr as $value) { ?>
                            <option value="<?= $value->id_respuesta_pqr ?>"><?= $value->codigo . " | " . $value->descripcion ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="clasificacion" class="form-label">Clasificación : </label>
                    <select class="form-control select_2" style="width: 100%;" id="clasificacion" name="clasificacion">
                        <option value="0"></option>
                        <option value="Petición">Petición</option>
                        <option value="Queja">Queja</option>
                        <option value="Reclamo">Reclamo</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="responsable" class="form-label">Responsable : </label>
                    <input class="form-control" id="responsable" name="responsable" />
                </div>
                <div class="mb-3">
                    <label for="costo" class="form-label">Costo : </label>
                    <input class="form-control" id="costo" name="costo" />
                </div>
                <div class="mb-3">
                    <label for="observacion" class="form-label">Observación : </label>
                    <input class="form-control" id="observacion" name="observacion" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="boton_codigo_motivo" data="">Continuar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cierre Comite -->
<div class="modal fade" id="cierreComite" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cierreComiteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cierreComiteLabel">Determinación de motivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="codigo_motivo" class="form-label">Codigo Motivo : </label>
                    <select class="form-control select_2" style="width: 100%;" id="codigo_motivo_cierre" name="codigo_motivo">
                        <option value="0"></option>
                        <?php foreach ($motivo_pqr as $value) { ?>
                            <option value="<?= $value->id_respuesta_pqr ?>"><?= $value->codigo . " | " . $value->descripcion ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="clasificacion" class="form-label">Clasificación : </label>
                    <select class="form-control select_2" style="width: 100%;" id="clasificacion_cierre" name="clasificacion">
                        <option value="0"></option>
                        <option value="Petición">Petición</option>
                        <option value="Queja">Queja</option>
                        <option value="Reclamo">Reclamo</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="analisis_pqr" class="form-label">Analisis Pqr : </label>
                    <textarea class="form-control" id="analisis_pqr_cierre" name="analisis_pqr">
                    </textarea>
                </div>
                <div class="mb-3">
                    <label for="accion" class="form-label">Acción Pqr : </label>
                    <textarea class="form-control" id="accion_cierre" name="accion">
                    </textarea>
                </div>
                <div class="mb-3">
                    <label for="responsable" class="form-label">Responsable : </label>
                    <input class="form-control" id="responsable_cierre" name="responsable" />
                </div>
                <div class="mb-3">
                    <label for="costo" class="form-label">Costo : </label>
                    <input class="form-control" id="costo_cierre" name="costo" />
                </div>
                <div class="mb-3">
                    <label for="observacion" class="form-label">Observación : </label>
                    <input class="form-control" id="observacion_cierre" name="observacion" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="boton_cierre_pqr" data="">Continuar</button>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/pqr/js/vista_cierre_pqr_general.js"></script>
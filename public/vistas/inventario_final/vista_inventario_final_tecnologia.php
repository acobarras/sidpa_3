<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="tecnologia-tab" data-bs-toggle="tab" href="#tecnologia" role="tab" aria-controls="tecnologia" aria-selected="true">Tecnología</a>
                </div>
            </nav>
            <div id="cargando" class="text-center">
                <button class="btn" disabled>
                    <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
                    <span role="status">Cargando...</span>
                </button>
            </div>
            <div class="tab-content d-none" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="tecnologia" role="tabpanel" aria-labelledby="tecnologia-tab">
                    <br>
                    <h3 class="text-center fw-bold">FORMULARIO DE REGISTRO TECNOLOGÍA</h3>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <form id="form_conteo_tec">
                                            <br>
                                            <div class="row">
                                                <div class="form-group col-md-3"></div>
                                                <div class="form-group col-md-4">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="conteo" id="conteo_tec">
                                                        <label class="form-check-label" for="inlineCheckbox1">Conteo</label>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <center><span id="span_check_tec" style="font-size: 13px;padding: 0 0 0 0; color:red;"></span></center>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="verificacion" id="verificacion_tec">
                                                        <label class="form-check-label" for="inlineCheckbox2">Verificación</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="fecha_crea">Operario:</label>
                                                <input autocomplete='off' class="form-control" type="number" name="num_usuario" required="" id="id_usuario_tec">
                                                <input type="hidden" name="id_usuario" id="id_persona_T">
                                                <div class="text-center" id="span_operario_CT"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="fecha_crea">Ubicación:</label>
                                                <select autocomplete='off' class="form-control" type="text" name="ubicacion" required="" id="ubicacion_tec">
                                                    <option value="0">Elija una ubicación</option>
                                                    <?php
                                                    foreach ($ubicaciones_tec as $value) { ?>
                                                        <option value="<?= $value->nombre_ubicacion ?>"><?= $value->nombre_ubicacion ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="fecha_crea">Codigo Producto:</label>
                                                <input autocomplete='off' class="form-control" type="text" name="codigo_producto" required="" id="codigo_producto_tec">
                                                <input type="hidden" name="id_producto" id="id_producto_tec">
                                                <center><span id="span_codigo_CT" style="font-size: 13px;padding: 0 0 0 0;"></span></center>
                                            </div>
                                            <div class="mb-3">
                                                <label for="fecha_crea">Cantidad a Ingresar:</label>
                                                <input autocomplete='off' class="form-control" type="number" name="entrada" required="" id="entrada_tec">
                                            </div>

                                            <div class="d-grid gap-2 col-6 mx-auto">
                                                <button type="button" class="btn btn-primary envio_conteo_tec" id="envio_conteo_tec">Agregar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="conteo_tecnologia" class=" table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                            <thead class="bg-layout">
                                                <tr class="">
                                                    <td><b>Código Producto</b></td>
                                                    <td><b>Cantidad</b></td>
                                                    <td><b>Canasta</td>
                                            </thead>
                                            <tbody id="data_item_tec"></tbody>
                                        </table>
                                    </div><br>
                                    <div class="d-grid gap-2 col-6 mx-auto">
                                        <button type="button" class="btn btn-success registro_conteo_tec" id="registro_conteo_tec">Registrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal para modificar precios -->
<div class="modal fade" id="modal_no_verify_tec" aria-labelledby="modal_no_verify_tecLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <center>
                    <h5 clas s="modal-title" id="ReporteItemsModalLabel">¡¡Alerta Sidpa!!</h5>
                </center>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container recuadro">
                    <br>
                    <p class="text-center" id="text_modal_tec"></p><br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/inventario_final/js/inventario_tecnologia.js"></script>
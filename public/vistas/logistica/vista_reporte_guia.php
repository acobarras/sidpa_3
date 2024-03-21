<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12" id="tabla_facturacion_listado">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Reporte Guía</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="ubicacion-tab" data-bs-toggle="tab" href="#ubicacion" role="tab" aria-controls="ubicacion" aria-selected="true">Cambio Ubicacion Despacho</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <div class="panel panel-default" id="adiciona_ruta">
                        <div class="panel-heading text-center mb-3">
                            <h3><b>Formulario Reporte de Guía</b></h3>
                        </div>
                        <div class="panel-body">
                            <form class="row mb-3" id="form_consultar_documento">
                                <div class="col-2"></div>
                                <div class="col-8">
                                    <label for="num_lista_empaque" class="col-form-label">Numero de Lista Empaque:</label>
                                    <input type="text" class="form-control" name="num_lista_empaque" id="num_lista_empaque">
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-primary" type="submit" id="boton_enviar">Consultar</button>
                                </div>
                            </form>
                            <br>
                            <div class="mb-3">
                                <table id="tabla_item_lista" class="table table-bordered table-responsive table-hover mb-3" cellspacing="0" width="100%">
                                    <thead style="background: #002b5f;color: white">
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Pedido Item</th>
                                            <th>Cantidad</th>
                                            <th>Descripción</th>
                                            <th>Numero Factura</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list-items"></tbody>
                                </table>
                                <br>
                                <form id="form_agregar_guia">
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-6 row">
                                            <label for="guia" class="col-form-label col-3">Numero Guia:</label>
                                            <div class="col-9">
                                                <input type="text" class="form-control" name="guia" id="guia">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <button class="btn btn-success" id="boton_graba_guia" type="submit">Grabar</button>
                                        </div>
                                        <div class="col-2"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade show" id="ubicacion" role="tabpanel" aria-labelledby="ubicacion-tab">
                    <br>
                    <div class="panel panel-default" id="ubicacion_despacho">
                        <div class="panel-heading text-center mb-3">
                            <h3><b>Cambio de ubicacion de Despacho</b></h3>
                        </div>
                        <div class="panel-body">
                            <form class="row mb-3" id="consultar_ubicacion">
                                <div class="col-2"></div>
                                <div class="col-8">
                                    <label for="num_ubicacion" class="col-form-label">Digite la ubicación:</label>
                                    <input type="text" class="form-control" name="num_ubicacion" id="num_ubicacion">
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-primary" type="submit" id="boton_consultar">Consultar</button>
                                </div>
                            </form>
                            <br>
                            <div class="mb-3">
                                <table id="tabla_pedido_ubi" class="table table-bordered table-responsive table-hover mb-3" cellspacing="0" width="100%">
                                    <thead style="background: #002b5f;color: white">
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Pedido Item</th>
                                            <th>Cantidad</th>
                                            <th>Ubicación</th>
                                            <th>Descripción</th>
                                            <th>Opción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list-items"></tbody>
                                </table>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal para agregar la imagen de la entrega -->
<div class="modal fade" id="cambio_ubicacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cambio_ubicacionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="cambio_ubicacionLabel">Cambio Ubicaciones despacho</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-center"><b class="color-danger">Nota:</b> Se elimina o se realiza el cambio.</h6>
                <div>
                    <label for="ubicacion_material">Ubicación:</label>
                    <input type="text" class="form-control" id="ubicacion_materialmodal" name="ubicacion_material">
                    <span class="text-primary">Las Ubicaciones seleccionadas son:</span><br><span class="text-danger span_ubi"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="enviar_ubicacion">Enviar</button>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/logistica/js/vista_reporte_guia.js"></script>
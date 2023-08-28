<div id="principal" class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="pqr-product-tab" data-bs-toggle="tab" href="#pqr-product" role="tab" aria-controls="pqr-product" aria-selected="true">PQR Producto</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="pqr-product" role="tabpanel" aria-labelledby="pqr-product-tab">
                        <br>
                        <div class="mb-3 text-center row">
                            <h1 class="col-md-12 col-md-offset-4 ">Tabla Gestión PQR Productos</h1>
                        </div>
                        <br>
                        <div>
                            <table id="tabla_gestion_pqr" class="table table-bordered table-responsive table-hover observaciones_pqr" cellspacing="0" width="100%">
                                <thead style="background: #002b5f;color: white">
                                    <tr>
                                        <td>Fecha</td>
                                        <td>Núm PQR</td>
                                        <td>Cliente</td>
                                        <td>Dirección</td>
                                        <td>Pedido Item</td>
                                        <td>Motivo PQR</td>
                                        <td>Cantidad Reclamacion</td>
                                        <td>Estado</td>
                                        <td>Opción</td>
                                        <td>Información</td>
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

<div class="modal fade" id="modal_observacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_observacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_observacionLabel">Observacion Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="fw-bolder"> Observacion :</label>
                <textarea class="form-control" id="observacion_pedido"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="enviar_observacion" data-bs-dismiss="modal">Enviar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ImpresionItemsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ImpresionItemsModalLabel" aria-hidden="true">
    <!-- <div class="modal fade" id="ImpresionItemsModal" aria-labelledby="ImpresionItemsModalLabel" aria-hidden="true"> -->
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ImpresionItemsModalLabel">Impresion etiqueta PQR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="div_impresion"></div>
                <h4 class="text-danger">Tenga en cuenta que al dar click en cerrar no podra imprimir nuevamente la etiqueta</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="reimprimir_marcacion">Imprimir</button>
            </div>
        </div>
    </div>
</div>

<!------------------------------------------------------------------------------------------------------------------------------------------------------------>
<!-- Mas informacion -->
<!------------------------------------------------------------------------------------------------------------------------------------------------------------>
<div class="container-fluid mt-3 mb-3" id="info_extra" style="display: none;">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">

            <br>
            <div>
                <button class="btn btn-primary" id="regresar">Regresar</button>
            </div>
            <br>
            <div>
                <center>
                    <h2 id="titulo_permisos"></h2>
                </center>
                <div>
                    <div class="mb-3 text-center row" id="titulo_info">
                    </div>
                    <form id="form_grabar_reclamacion">
                        <div class="row mb-3">
                            <div class="form-group col-2">
                                <label class="fw-bolder" for="motivo_no">Motivo de la PQR :</label>
                                <span class="form-control select_acob">
                                    Producto &nbsp;&nbsp;
                                    <input type="radio" name="motivo" class="motivo_vs" id="motivo_no" value="1">
                                    <span style="padding-left: 30px"></span>
                                    Servicio &nbsp;&nbsp;
                                    <input type="radio" name="motivo" class="motivo_vs" id="motivo_si" value="2">
                                </span>
                            </div>
                            <div class="form-group col-4">
                                <label class="fw-bolder"> Nit :</label>
                                <span class="form-control" disabled id="nit">N/A</span>
                            </div>
                            <div class="form-group col-6">
                                <label class="fw-bolder"> Cliente :</label>
                                <span class="form-control" id="nombre_empresa">N/A</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group col-8">
                                <label class="fw-bolder"> Dirección pedido :</label>
                                <span class="form-control" id="direccion">N/A</span>
                            </div>
                            <div class="form-group col-4">
                                <label class="fw-bolder"> Correo electrónico :</label>
                                <span class="form-control" id="email">N/A</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group col-4">
                                <label class="fw-bolder"> Persona de contacto :</label>
                                <span class="form-control" id="contacto">N/A</span>
                            </div>
                            <div class="form-group col-4">
                                <label class="fw-bolder"> Teléfono :</label>
                                <span class="form-control" id="telefono">N/A</span>
                            </div>
                            <div class="form-group col-4">
                                <label class="fw-bolder"> Celular :</label>
                                <span class="form-control" id="celular">N/A</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group col-4">
                                <label class="fw-bolder" for="cam_direc_no">¿La dirección es distinta a la dirección del pedido ?</label>
                                <span class="form-control select_acob">
                                    Si &nbsp;&nbsp;
                                    <input type="radio" class="cambio_direc" name="cam_direc" id="cam_direc_si" value="1">
                                    <span style="padding-left: 30px"></span>
                                    No &nbsp;&nbsp;
                                    <input type="radio" class="cambio_direc" name="cam_direc" id="cam_direc_no" value="2">
                                </span>
                            </div>
                            <div class="form-group col-8" id="cambio_direccion">
                                <label class="fw-bolder" for="cambio_direc"> Seleccione la dirección :</label>
                                <select class="form-control select_2" name="cambio_direc" id="cambio_direc" style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="mb-3" id="respu_elegido">
                            <div class="row mb-3">
                                <div class="form-group col-8">
                                    <label class="fw-bolder"> Dirección pedido :</label>
                                    <span class="form-control" id="direccion_res">N/A</span>
                                </div>
                                <div class="form-group col-4">
                                    <label class="fw-bolder"> Correo electrónico :</label>
                                    <span class="form-control" id="email_res">N/A</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-4">
                                    <label class="fw-bolder"> Persona de contacto :</label>
                                    <span class="form-control" id="contacto_res">N/A</span>
                                </div>
                                <div class="form-group col-4">
                                    <label class="fw-bolder"> Teléfono :</label>
                                    <span class="form-control" id="telefono_res">N/A</span>
                                </div>
                                <div class="form-group col-4">
                                    <label class="fw-bolder"> Celular :</label>
                                    <span class="form-control" id="celular_res">N/A</span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <table style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" id="tabla_info_pedido" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Pedido Item</th>
                                        <th>Codigo</th>
                                        <th>Descripción</th>
                                        <th>O.P.</th>
                                        <th>Ruta Emb</th>
                                        <th>Core</th>
                                        <th>Roll. paq X</th>
                                        <th>Moneda</th>
                                        <th>V.unidad</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group col-4">
                                <label class="fw-bolder" for="cam_produc_no">¿La información del producto es distinto ?</label>
                                <span class="form-control select_acob">
                                    Si &nbsp;&nbsp;
                                    <input type="radio" class="cambio_produc" name="cam_produc" id="cam_produc_si" value="1">
                                    <span style="padding-left: 30px"></span>
                                    No &nbsp;&nbsp;
                                    <input type="radio" class="cambio_produc" name="cam_produc" id="cam_produc_no" value="2">
                                </span>
                            </div>
                            <div class="form-group col-8" id="cambio_producto">
                                <label class="fw-bolder" for="cambio_produc"> Seleccione un producto :</label>
                                <select class="form-control select_2" name="cambio_produc" id="cambio_produc" style="width: 100%;"></select>
                            </div>
                        </div>
                        <div class="mb-3" id="respu_elegido_produc">
                            <table style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" id="tabla_cambio_item_pedido" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Descripción</th>
                                        <th>Ruta Emb</th>
                                        <th>Core</th>
                                        <th>Roll. paq X</th>
                                        <th>V.unidad</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="row mb-3">
                            <div class="form-group col-3">
                                <label class="fw-bolder" for="cambio_reproceso_si">¿Se debe realizar un cambio o reproceso de material?</label>
                                <span class="form-control select_acob">
                                    Si &nbsp;&nbsp;
                                    <input type="radio" class="repro" name="cambio_reproceso" id="cambio_reproceso_si" value="1">
                                    <span style="padding-left: 30px"></span>
                                    No &nbsp;&nbsp;
                                    <input type="radio" class="repro" name="cambio_reproceso" id="cambio_reproceso_no" value="2">
                                </span>
                            </div>
                            <div class="form-group col-3">
                                <label class="fw-bolder" for="recogida_produc_no">¿La mercancía se debe recoger donde el cliente?</label>
                                <span class="form-control select_acob">
                                    Si &nbsp;&nbsp;
                                    <input type="radio" class="recogida" name="recogida_produc" id="recogida_produc_si" value="1">
                                    <span style="padding-left: 30px"></span>
                                    No &nbsp;&nbsp;
                                    <input type="radio" class="recogida" name="recogida_produc" id="recogida_produc_no" value="2">
                                </span>
                            </div>
                            <div class="form-group col-6">
                                <label class="fw-bolder" for="cantidad_reclama">Cantidad Reclamación :</label>
                                <span type="text" class="form-control" name="cantidad_reclama" id="cantidad_reclama"></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <label class="fw-bolder" for="observacion">Descripción detallada de la reclamación y observaciones :</label>
                                <textarea class="form-control" id="observacion" style="resize: none; height:250px;"></textarea>
                            </div>
                        </div>
                    </form>
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
                <h5 class="modal-title" id="codigoMotivoLabel">Observaciones PQR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observación : </label>
                    <textarea class="form-control" id="observaciones" name="observaciones"> </textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cerrar_observaciones" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary boton_codigo_motivo" id="boton_observacion" data="">Continuar</button>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/pqr/js/vista_gestionar_pqr.js"></script>
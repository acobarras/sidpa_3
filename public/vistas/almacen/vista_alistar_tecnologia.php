<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="alista_tec-tab" data-bs-toggle="tab" href="#alista_tec" role="tab" aria-controls="alista_tec" aria-selected="true">Alistar tecnología</a>
                </div>
            </nav>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="alista_tec" role="tabpanel" aria-labelledby="alista_tec-tab">
                    <br>
                    <h3 class="text-center fw-bold">Pendientes por alistar tecnología</h3>
                    <br>
                    <div class="mb-3">
                            <p class="mx-3 text-danger"> ► Si la cantidad de bodega se encuentra en rojo la mercancia no ha sido alistada</p>
                            <p class="mx-3 text-success"> ► Si la cantidad de bodega se encuentra en verde la mercancia ya se alisto</p>
                        </div>
                    <table id="dt_alistamiento_tecnologia" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                        <thead style="background:#0d1b50;color:white">
                            <tr>
                                <td>Fecha Compro</td>
                                <td>Empresa</td>
                                <td>orden compra</td>
                                <td>Código</td>
                                <td>Cantidad<br> Solicitada</td>
                                <td>Cantidad<br> Alistada</td>
                                <td>Descripción </td>
                                <td>Pedido-Item </td>
                                <td>Orden Compra </td>
                                <td>Fecha proveedor</td>
                                <td>Ruta</td>
                                <td>Estado</td>
                                <td></td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ver obsevaciones-->
<div class="modal fade" id="observaciones_Modal" aria-labelledby="observaciones_ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <div class="row">
                    <div class="col">
                        <h5 class="modal-title" id="ReporteItemsModalLabel">Observaciones de Pedido</h5>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <br><br>
                <h5 class="text-center fw-bold" style="color:#2e2a5a" id="observaciones_p"></h5>
                <br><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para reportar facturacion -->
<div class="modal fade" id="logistica_checked_Modal" aria-labelledby="logistica_checked_ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <div class="row">
                    <div class="col">
                        <h5 class="modal-title" id="ReporteItemsModalLabel">Reporte Mercancia</h5>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_reporta_factu">
                <div class="modal-body">
                    <br><br>
                    <div class="container-fluid mb-3">
                        <div class="recuadro">
                            <div id="contenido" class="px-2 py-2 col-lg-12">
                                <input type="hidden" name="tipo_envio" id="tipo_envio">
                                <div>
                                    <label for="precio_autorizado">Cantidad Reporte:</label>
                                    <input type="text" class="form-control" name="cantidad_factura" id="cantidad_factura">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-info" id="btn_reportar_factu" data-id="">Reportar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/almacen/js/vista_alistar_tecnologia.js"></script>
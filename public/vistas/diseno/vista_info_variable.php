<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Infomación Variable</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="panel panel-default">
                        <br>
                        <div class="panel-heading text-center mb-3">
                            <h2>Infomación Variable</h2>
                        </div>
                        <div class="mb-3">
                            <p class="mx-3 text-danger"> ► Si la cantidad de bodega se encuentra en rojo la mercancia no ha sido alistada</p>
                            <p class="mx-3 text-success"> ► Si la cantidad de bodega se encuentra en verde la mercancia ya se alisto</p>
                        </div>
                        <table id="table_info_variable" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <tr>
                                    <td>Fecha Compro</td>
                                    <td>Empresa</td>
                                    <td>Código</td>
                                    <td>Cantidad<br> Solicitada</td>
                                    <td>Cantidad<br> Bodega</td>
                                    <td>Cantidad O.P</td>
                                    <td>Recibe</td>
                                    <td>Descripción </td>
                                    <td>No. Producción </td>
                                    <td>Pedido-Item </td>
                                    <td>Core </td>
                                    <td>Rollos X </td>
                                    <td>Ruta</td>
                                    <td>Estado</td>
                                    <td></td>
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
<div class="modal fade" id="logistica_checked_etiq_Modal" aria-labelledby="logistica_checked_ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
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
            <form id="form_reporta_etiq_info">
                <div class="modal-body">
                    <br><br>
                    <div class="container-fluid mb-3">
                        <div class="recuadro">
                            <div id="contenido" class="px-2 py-2 col-lg-12">
                                <div>
                                    <label for="cant_reporte">Cantidad Reporte:</label>
                                    <input type="text" class="form-control" name="cant_reporte" id="cant_reporte">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-info" id="btn_reportar_factu_etiq" data-id="">Reportar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/diseno/js/vista_info_variable.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="ali_completo-tab" data-bs-toggle="tab" href="#ali_completo" role="tab" aria-controls="ali_completo" aria-selected="true">Completos</a>
                    <a class="nav-link" id="ali_incompleto-tab" data-bs-toggle="tab" href="#ali_incompleto" role="tab" aria-controls="ali_incompleto" aria-selected="true">Parciales</a>
                    <a class="nav-link" id="ali_bobinas-tab" data-bs-toggle="tab" href="#ali_bobinas" role="tab" aria-controls="ali_bobinas" aria-selected="true">Bobinas</a>
                </div>
            </nav>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="ali_completo" role="tabpanel" aria-labelledby="ali_completo-tab">
                    <br>
                    <table id="dt_alistamiento_bod_completo" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                        <thead style="background:#0d1b50;color:white">
                            <tr>
                                <td>Fecha Compro</td>
                                <td>Empresa</td>
                                <td>Código</td>
                                <td>Cantidad</td>
                                <td>Recibe</td>
                                <td>Descripción </td>
                                <td>No. Producción </td>
                                <td>Pedido-Item </td>
                                <td>Core </td>
                                <td>Rollos X </td>
                                <td>Ruta</td>
                                <td>Estado</td>
                                <td>opciones</td>
                            </tr>
                        </thead>

                    </table>
                </div>
                <div class="tab-pane fade show" id="ali_incompleto" role="tabpanel" aria-labelledby="ali_incompleto-tab">
                    <br>
                    <table id="dt_alistamiento_bod_incompleto" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                        <thead style="background:#0d1b50;color:white">
                            <tr>
                                <td>Fecha Compro</td>
                                <td>Empresa</td>
                                <td>Código</td>
                                <td>Cantidad</td>
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
                    </table>
                </div>
                <div class="tab-pane fade show" id="ali_bobinas" role="tabpanel" aria-labelledby="ali_bobinas-tab">
                    <br>
                    <table id="dt_alistamiento_bod_bobinas" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                        <thead style="background:#0d1b50;color:white">
                            <tr>
                                <td>Fecha Compro</td>
                                <td>Empresa</td>
                                <td>Código</td>
                                <td>Cantidad</td>
                                <td>Recibe</td>
                                <td>Descripción </td>
                                <td>No. Producción </td>
                                <td>Pedido-Item </td>
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
<div class="div_impresion"></div>



<!-------------------------------------------------- INICIO MODALEs--------------------------------------------->
<!-------------------------------------------------- MODAL REPROCESO --------------------------------------------->
<div class="modal fade" id="Modal_CANT_REPROC" role="dialog" aria-labelledby="exampleModal_CANT_REPROC" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="exampleModal_CANT_REPROC">Cantidad Reprocesada</h3>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <div class="recuadro">
                    <div class="container-fluid">
                        <form id="form_reproceso">
                            <div class="mb-3">
                                <label for="cantidad" class="col-form-label">Cantidad:</label>
                                <input type="text" class="form-control" id="cantidad" name="cantidad">
                                <!-- <input type="hidden" id="data"> -->
                            </div>
                            <br>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary btn-sm" id="bt_reprocesar_item" data="">Reportar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-------------------------------------------------- FIN MODALES --------------------------------------------->
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>

<script src="<?= PUBLICO ?>/vistas/almacen/js/vista_alistar_items.js"></script>
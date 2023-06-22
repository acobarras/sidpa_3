<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="CompraEtiquetas-tab" data-bs-toggle="tab" href="#CompraEtiquetas" role="tab" aria-controls="CompraEtiquetas" aria-selected="true">Etiquetas</a>
                </div>
            </nav>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="CompraEtiquetas" role="tabpanel" aria-labelledby="CompraEtiquetas-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Etiquetas pendiente</h3>
                        <br>
                        <table id="dt_items_compra" class="table table-bordered 
                        table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                            <thead style="background: #0d1b50;color: white">
                                <tr>
                                    <td>Fecha Compromiso</td>
                                    <td>Nombre Empresa</td>
                                    <td>Pedido-Item</td>
                                    <td>Código</td>
                                    <td>Descripción</td>
                                    <td>Cantidad </td>
                                    <td>Espera Fecha</td>
                                    <td>Opción</td>
                                </tr>
                            </thead>
                            <br>
                        </table>
                        <br>
                        <div class="container recuadro">
                                <br>
                                <form id="form_asigna_fecha_entrega_etiq">
                                <div class="mb-3">
                                        <label for="orden_compra" class="col-form-label fw-bold">Numero Orden De Compra:</label>
                                        <input autocomplete="off" class="form-control" type="text" id="orden_compra" name="orden_compra">
                                    </div>
                                    <div class="mb-3">
                                        <label for="fecha_proveedor" class="col-form-label fw-bold">Fecha Entrega:</label>
                                        <input autocomplete="off" class="form-control" type="text" id="fecha_proveedor" name="fecha_proveedor">
                                    </div>
                                    <center>
                                        <button type="submit" class="btn btn-info" id="asigna_material_etiq"><i class="fas fa-check"></i> Asigna</button>
                                    </center>
                                </form>
                                <br>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Compras/js/etiquetas.js"></script>
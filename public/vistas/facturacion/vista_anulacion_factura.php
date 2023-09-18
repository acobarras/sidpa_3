<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12" id="tabla_documentos">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Anulación Factura</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <div class="panel-heading text-center mb-3">
                        <h3><b>Anulación Factura</b></h3>
                    </div>
                    <form class="panel-body" id="consulta_factura">
                        <div class="row">
                            <div class="col-10">
                                <div class="form-group row mb-3">
                                    <label for="numero_factura_consulta" class="col-2">N° factura:</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control" id="numero_factura_consulta" name="numero_factura_consulta">
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="text-center mb-3">
                                    <button class="btn btn-primary btn-lg boton-x" type="submit" id="consulta_lista_de_empaque">Consultar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div>
                        <table id="tab_informe_facturas" class="table table-bordered table-responsive-sm  text-center table-responsive-lg table-responsive-md " cellspacing="0" width="100%">
                            <thead style="background: #0d1b50;color: white;">
                                <tr>
                                    <th>Cliente</th>
                                    <th>N° Factura</th>
                                    <th>N° Lista Empaque</th>
                                    <th>Tipo Documento</th>
                                    <th>Fecha Factura</th>
                                    <th>Cantidad Factura</th>
                                    <th>Cantidad Solicitada</th>
                                    <th>N° Pedido</th>
                                    <th>Item</th>
                                    <th>Codigo Producto</th>
                                    <th>Descripción Producto</th>
                                </tr>
                            </thead>
                        </table>
                        <div id="boton_anular" class="text-center mb-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/facturacion/js/vista_anulacion_factura.js"></script>
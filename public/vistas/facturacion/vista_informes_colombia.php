<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido_informe_col" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab-informe" role="tablist">
                        <a class="nav-link active" id="nav-informe-tab" data-bs-toggle="tab" href="#nav-informe" role="tab" aria-controls="nav-informe" aria-selected="true">Crea Informe Colombia</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent-informe">
                    <div class="tab-pane fade show active" id="nav-informe" role="tabpanel" aria-labelledby="nav-informe-tab">
                        <div class="container-fluid">
                            <br>
                            <form id="crea_informe_colombia">
                                <div class="row g-3 align-items-center">
                                    <div class="col-3"></div>
                                    <div class="col-1">
                                        <label for="fecha_crea" class="col-form-label" style="font-family: 'gothic'; font-weight: bold;">Fecha Informe</label>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control" name="fecha_crea" id="fecha_informe">
                                            <button type="submit" class="btn btn-info col-3">Consultar Informe <i class="fas fa-check"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <br>
                        </div>
                    </div>
                </div>
                <table id="tab_informe" class="table table-bordered table-responsive-sm  text-center table-responsive-lg table-responsive-md " cellspacing="0" width="100%">
                    <thead style="background: #0d1b50;color: white;">
                        <tr>
                            <th>Fecha Factura</th>
                            <th>Cod Producto</th>
                            <th>Descripcion Producto</th>
                            <th>Cantidad Facturada</th>
                            <th>Valor Unitario</th>
                            <th>Total</th>
                            <th>Valor Unitario factura</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
                <div id="boton_informe" class="text-center mb-3" style='display:none;'>
                    <button class="btn btn-primary btn-lg boton-x" type="submit" id="genera_informe">Generar Informe</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/facturacion/js/vista_informes_colombia.js"></script>
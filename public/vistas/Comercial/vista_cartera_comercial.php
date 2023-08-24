<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <br>
        <h2 style="text-align: center"> Cartera comercial</h2>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-link active" id="nav-carteraVencida-tab" data-bs-toggle="tab" href="#nav-carteraVencida" role="tab" aria-controls="nav-carteraVencida" aria-selected="true">Facturas vencidas</a>
                <a class="nav-link" id="nav-cartera-tab" data-bs-toggle="tab" href="#nav-cartera" role="tab" aria-controls="nav-cartera" aria-selected="true">Facturas por vencer</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-carteraVencida" role="tabpanel" aria-labelledby="nav-carteraVencida-tab">
                <div class="m-4">
                    <div class="container-fluid">
                        <div class="recuadro m-3" id="cartera_vencida">
                            <div class="col-lg-12">
                                <br>
                                <h4 class="text-center text-danger">Facturas vencidas</h4>
                                <div class="col-lg-12 p-4">
                                    <div class="table-responsive">
                                        <table id="tb_cartera_vencida" class=" table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                            <thead class="bg-layout" style="background:#0d1b50;color:white">
                                                <tr>
                                                    <th>id</th>
                                                    <th>Nit</th>
                                                    <th class="col-3">Empresa</th>
                                                    <th>Dias Credito</th>
                                                    <th>Mayor día mora</th>
                                                    <th>Cantidad de facturas</th>
                                                    <th>Fecha  vencimiento /factura mas antigua</th>
                                                    <th>Total Facturado</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr class="text-danger table-secondary">
                                                    <th colspan="3" style="text-align:right">Total cartera vencida:</th>
                                                    <th colspan="2"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <div class="tab-pane fade show" id="nav-cartera" role="tabpanel" aria-labelledby="nav-cartera-tab">
                <div class="m-4">
                    <div class="recuadro m-3" id="otra_cartera">
                        <div class="col-lg-12 p-4">
                            <br>
                            <h4 class="text-center text-success">Facturas por vencer</h4>
                            <div class="table-responsive">
                                <table id="tb_otra_cartera" class=" table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                    <thead class="bg-layout" style="background:#0d1b50;color:white">
                                        <tr>
                                            <th>id</th>
                                            <th>Nit</th>
                                            <th class="col-3">Empresa</th>
                                            <th>Dias Credito</th>
                                            <th>Cantidad de facturas</th>
                                            <th>Fecha factura próxima a vencer</th>
                                            <th>Total Facturado</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr class="text-success table-secondary">
                                            <th colspan="3" style="text-align:right">Total cartera no vencida:</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Comercial/js/cartera_comercial.js"></script>
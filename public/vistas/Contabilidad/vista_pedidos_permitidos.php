<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pedidos_permitidos-tab" data-bs-toggle="tab" href="#pedidos_permitidos" role="tab" aria-controls="pedidos_permitidos" aria-selected="true">Pedidos Permitidos</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Primer link -->
                    <div class="tab-pane fade show active" id="pedidos_permitidos" role="tabpanel" aria-labelledby="pedidos_permitidos-tab">
                        <div class="container-fluid">
                            <br>
                            <div class="recuadro">
                                <div class="container-fluid">
                                    <br>
                                    <h3 class="text-center fw-bolder" style="font-family: 'gothic';">Pedidos Permitidos</h3>
                                    <table id="tb_pedidos_permitidos" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                        <thead style="background:#0d1b50;color:white">
                                            <tr>
                                                <th>Nit</th>
                                                <th>Cliente</th>
                                                <th>Cantidad Paso Pedido</th>
                                                <th style="width: 100px;">Ver Pedidos </th>
                                            </tr>
                                        </thead>
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
</div>


<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Contabilidad/js/pedidos_permitidos.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Pedidos Creditos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Pedidos Contados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="sin_compromiso-tab" data-bs-toggle="tab" href="#sin_compromiso" role="tab" aria-controls="sin_compromiso" aria-selected="false">Pedidos Sin Fecha Compromiso</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="incompletos-tab" data-bs-toggle="tab" href="#incompletos" role="tab" aria-controls="incompletos" aria-selected="false">Items Incompletos</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="my-3 panel-heading text-center">
                        <h2>Pedidos Atrasados y en proceso</h2>
                    </div>
                    <br>
                    <table id="tb_pedidos_credito" class="table table-bordered table-responsive table-hover mb-3" cellspacing="0" width="100%">
                        <thead style="background: #002b5f;color: white">
                            <tr>
                                <th>Fecha Compromiso</th>
                                <th>Fecha Creacion Pedido</th>
                                <th>Fecha Cierre</th>
                                <th>Cliente</th>
                                <th>Pedido</th>
                                <th>Items Reportados</th>
                                <th>Cantidad Items Con OP</th>
                                <th>forma Pago</th>
                                <th>Asesor</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- Segundo link -->
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="my-3 panel-heading text-center">
                        <h2>Pedidos Atrasados y en proceso</h2>
                    </div>
                    <br>
                    <table id="tb_pedidos_contado" class="table table-bordered table-responsive table-hover mb-3" cellspacing="0" width="100%">
                        <thead style="background: #002b5f;color: white">
                            <tr>
                                <th>Fecha Compromiso</th>
                                <th>Fecha Creacion Pedido</th>
                                <th>Fecha Cierre</th>
                                <th>Cliente</th>
                                <th>Pedido</th>
                                <th>Items Reportados</th>
                                <th>Cantidad Items Con OP</th>
                                <th>forma Pago</th>
                                <th>Asesor</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- Tercero link -->
                <div class="tab-pane fade" id="sin_compromiso" role="tabpanel" aria-labelledby="sin_compromiso-tab">
                    <div class="my-3 panel-heading text-center">
                        <h2>Pedidos Sin fecha de Compromiso</h2>
                    </div>
                    <br>
                    <table id="tb_sin_compromiso" class="table table-bordered table-responsive table-hover mb-3" cellspacing="0" width="100%">
                        <thead style="background: #002b5f;color: white">
                            <tr>
                                <th>Fecha Compromiso</th>
                                <th>Fecha Creacion Pedido</th>
                                <th>Fecha Cierre</th>
                                <th>Cliente</th>
                                <th>Pedido</th>
                                <th>Items Reportados</th>
                                <th>Cantidad Items Con OP</th>
                                <th>forma Pago</th>
                                <th>Asesor</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- Cuarto link -->
                <div class="tab-pane fade" id="incompletos" role="tabpanel" aria-labelledby="incompletos-tab">
                    <div class="my-3 panel-heading text-center">
                        <h2>Pedidos Incompletos</h2>
                    </div>
                    <br>
                    <table id="pedidos_incompletos" class="table table-bordered table-responsive table-hover mb-3" cellspacing="0" width="100%">
                        <thead style="background: #002b5f;color: white">
                            <tr>
                                <th>Pedido</th>
                                <th>Item</th>
                                <th>Cantidad Solicitada</th>
                                <th>Cantidad Facturada</th>
                                <th>Cantidad Pendiente Facturar</th>
                                <th>Tolerancia</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL MOVIMIENTOS -->
<div class="modal fade" id="modal_movimientos" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <h3 class="modal-title" id="exampleModalLabel">Movimientos <span id="nombre"></span></h3>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <div class="recuadro">
                    <div class="container-fluid">
                        <div class="movimientos_item">
                            <hr>
                            <table id="movimientos_item" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Responsable</th>
                                        <th>Area</th>
                                        <th>Actividad Area</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                            </table>
                            <br><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/logistica/js/pedidos_atrasados.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="pedido_item-tab" data-bs-toggle="tab" href="#pedido_item" role="tab" aria-controls="pedido_item" aria-selected="true">Pedido Item</a>
                    <a class="nav-link " id="orden_produccion-tab" data-bs-toggle="tab" href="#orden_produccion" role="tab" aria-controls="orden_produccion" aria-selected="true">Orden de Producción</a>
                    <a class="nav-link " id="pedido_item_fecha-tab" data-bs-toggle="tab" href="#pedido_item_fecha" role="tab" aria-controls="pedido_item_fecha" aria-selected="true">Fecha</a>
                    <a class="nav-link " id="pedido_item_op-tab" data-bs-toggle="tab" href="#pedido_item_op" role="tab" aria-controls="pedido_item_op" aria-selected="true">Pedidos O.P</a>
                    <a class="nav-link " id="movimientos_pedido-tab" data-bs-toggle="tab" href="#movimientos_pedido" role="tab" aria-controls="movimientos_pedido" aria-selected="true">Movimientos Pedido</a>
                    <a class="nav-link " id="consulta_pqr-tab" data-bs-toggle="tab" href="#consulta_pqr" role="tab" aria-controls="movimientos_pedido" aria-selected="true">Consulta PQR</a>
                    <a class="nav-link " id="consulta_diag-tab" data-bs-toggle="tab" href="#consulta_diag" role="tab" aria-controls="consulta_diag" aria-selected="true">Consulta Diag. Soporte</a>
                </div>
            </nav>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="pedido_item" role="tabpanel" aria-labelledby="pedido_item-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Consulta Pedido Item</h3>
                        <br>
                        <div class="container mt-3 mb-3">
                            <div class="recuadro">
                                <div id="contenido" class="px-2 py-2 col-lg-12">
                                    <form id="form_pedidos_item">
                                        <div class="input-group mt-3 mb-3">
                                            <label class="input-group-text" for="num_pedido">N° Pedido</label>
                                            <input type="text" class="form-control" name="num_pedido" id="num_pedido" placeholder="Ingrese N° Pedido">
                                            <button class="btn btn-primary" type="success" id=""><i class="fa fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="col-lg-12">
                            <table id="tb_pedidos_item" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Fecha Pedido</th>
                                        <th>Pedido</th>
                                        <th>Item</th>
                                        <th>Codigo Producto</th>
                                        <th>Descripción</th>
                                        <th>Cantidad Solicitada</th>
                                        <th>Estado</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <br>
                    </div>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade show" id="orden_produccion" role="tabpanel" aria-labelledby="orden_produccion-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Consulta Orden de Producción</h3>
                        <br>
                        <div class="container mt-3 mb-3">
                            <div class="recuadro">
                                <div id="contenido" class="px-2 py-2 col-lg-12">
                                    <form id="form_n_produccion">
                                        <div class="input-group mt-3 mb-3">
                                            <label class="input-group-text" for="n_produccion">N° Orden Producción</label>
                                            <input type="text" class="form-control" name="n_produccion" id="n_produccion" placeholder="Ingrese N° Orden Producción">
                                            <button class="btn btn-primary" type="success" id=""><i class="fa fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="col-lg-12">
                            <table id="tb_n_produccion" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Persona</th>
                                        <th>Area</th>
                                        <th>Actividad</th>
                                        <th>Maquina</th>
                                        <th>Observación O.p.</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <br>
                    </div>
                </div>
                <!-- tercer link -->
                <div class="tab-pane fade show" id="pedido_item_fecha" role="tabpanel" aria-labelledby="pedido_item_fecha-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Consulta Pedido Item Por Fecha</h3>
                        <br>
                        <form id="form_fecha">
                            <div class="row container-fluid">
                                <div class="form-group col-12 col-md-3">
                                    <label for="fecha_desde">Fecha desde</label>
                                    <input class="form-control" type="date" name="fecha_desde" id="fecha_desde">
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label for="fecha_hasta">Fecha Hasta</label>
                                    <input class="form-control" type="date" name="fecha_hasta" id="fecha_hasta">
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label for="actividad">Actividad</label>
                                    <select class="form-control select_2" style="width:100%" name="actividad" id="actividad">
                                        <option value="0"></option>
                                        <?php foreach ($nombre_actividad as $actividad) { ?>
                                            <option value="<?= $actividad->nombre_actividad_area ?>"><?= $actividad->nombre_actividad_area ?></option>
                                        <?php } ?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                                <div class="col-12 col-md-3">
                                    <br>
                                    <button type="submit" class="btn btn-primary">Consultar</button>
                                </div>
                            </div>
                        </form>
                        <br>
                        <div class="col-lg-12">
                            <table id="tb_fecha" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Orden Producción</th>
                                        <th>Operario</th>
                                        <th>Actividad</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <br>
                    </div>
                </div>
                <!-- cuarto link -->
                <div class="tab-pane fade show" id="pedido_item_op" role="tabpanel" aria-labelledby="pedido_item_op-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Pedido Item Orden de Producción</h3>
                        <br>
                        <div class="container mt-3 mb-3">
                            <div class="recuadro">
                                <div id="contenido" class="px-2 py-2 col-lg-12">
                                    <form id="form_op_pedido">
                                        <div class="input-group mt-3 mb-3">
                                            <label class="input-group-text" for="n_produccion_pedido">N° Orden Producción</label>
                                            <input type="text" class="form-control" name="n_produccion_pedido" id="n_produccion_pedido" placeholder="N° Orden Producción">
                                            <button class="btn btn-primary" type="success" id=""><i class="fa fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="col-lg-12">
                            <table id="tb_num_pedido_op" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Fecha Pedido</th>
                                        <th>Fecha Compromiso</th>
                                        <th>Pedido Item</th>
                                        <th>Cliente</th>
                                        <th>Orden Compra</th>
                                        <th>O.P.</th>
                                        <th>Codigo Producto</th>
                                        <th>Descripción</th>
                                        <th>Cantidad Solicitada</th>
                                        <th>Core</th>
                                        <th>Rollos X</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <br>
                    </div>
                </div>
                <!-- quinto link -->
                <div class="tab-pane fade show" id="movimientos_pedido" role="tabpanel" aria-labelledby="movimientos_pedido-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Consulta Movimientos Pedidos</h3>
                        <br>
                        <form id="form_movimientos">
                            <div class="row container-fluid">
                                <div class="form-group col-12 col-md-3"></div>
                                <div class="form-group col-12 col-md-3">
                                    <label for="fecha_desde">Fecha desde</label>
                                    <input class="form-control" type="date" name="fecha_inicial" id="fecha_inicial">
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label for="fecha_hasta">Fecha Hasta</label>
                                    <input class="form-control" type="date" name="fecha_final" id="fecha_final">
                                    <p class="help-block"></p>
                                </div>
                                <div class="col-12 col-md-3">
                                    <br>
                                    <button type="submit" class="btn btn-primary">Consultar</button>
                                </div>
                            </div>
                        </form>
                        <br>
                        <div class="col-lg-12">
                            <table id="tb_movimientos" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Operario</th>
                                        <th>Pedido Item</th>
                                        <th>Area</th>
                                        <th>Actividad</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <br>
                    </div>
                </div>
                <!-- sexto link -->
                <div class="tab-pane fade show" id="consulta_pqr" role="tabpanel" aria-labelledby="consulta_pqr-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Consulta Movimientos PQR.</h3>
                        <br>
                        <div class="container mt-3 mb-3">
                            <div class="recuadro">
                                <div class="px-2 py-2 col-lg-12">
                                    <form id="form_pqr">
                                        <div class="input-group mt-3 mb-3">
                                            <label class="input-group-text" for="num_pqr">N° Pqr</label>
                                            <input type="text" class="form-control" name="num_pqr" id="num_pqr" placeholder="N° De La Pqr">
                                            <button class="btn btn-primary" type="submit" id="boton_pqr"><i class="fa fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="col-lg-12">
                            <table id="tabla_pqr" style="background: white;" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white;">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Usuario</th>
                                        <th>Actividad</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <br>
                    </div>
                    <div id="datos_pqr" data="">
                        <?php include PUBLICO . '/vistas/pqr/vista_pqr.php' ?>
                    </div>
                </div>
                <!-- septimo link -->
                <div class="tab-pane fade show" id="consulta_diag" role="tabpanel" aria-labelledby="consulta_diag-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Consulta Movimientos Diag. Soporte</h3>
                        <br>
                        <div class="container mt-3 mb-3">
                            <div class="recuadro">
                                <div class="px-2 py-2 col-lg-12">
                                    <form id="form_diag">
                                        <div class="input-group mt-3 mb-3">
                                            <label class="input-group-text" for="num_diag">N° Diagnostico</label>
                                            <input type="text" class="form-control" name="num_diag" id="num_diag" placeholder="N° Del Diagnostico">
                                            <button class="btn btn-primary" type="submit" id="boton_pqr"><i class="fa fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="col-lg-12">
                            <table id="tabla_diag" style="background: white;" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white;">
                                    <tr>
                                        <th>Id Diagnostico</th>
                                        <th>Fecha seguimiento</th>
                                        <th>Num Consecutivo</th>
                                        <th>Item</th>
                                        <th>Empresa</th>
                                        <th>Equipo</th>
                                        <th>Observación</th>
                                        <th>Procedimiento</th>
                                        <th>Actividad en Curso</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/consultas/js/consultas.js"></script>
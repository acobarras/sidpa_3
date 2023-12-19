<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="consulta_fecha-tab" data-bs-toggle="tab" href="#consulta_fecha" role="tab" aria-controls="consulta_fecha" aria-selected="true">Consulta Fecha</a>
                    <a class="nav-link" id="consulta_codigo-tab" data-bs-toggle="tab" href="#consulta_codigo" role="tab" aria-controls="consulta_codigo" aria-selected="true">Consulta Código</a>
                    <a class="nav-link" id="consulta_pedido-tab" data-bs-toggle="tab" href="#consulta_pedido" role="tab" aria-controls="consulta_pedido" aria-selected="true">Consulta pedido</a>
                    <a class="nav-link" id="consulta_cliente-tab" data-bs-toggle="tab" href="#consulta_cliente" role="tab" aria-controls="consulta_cliente" aria-selected="true">Consulta cliente</a>
                </div>
            </nav>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="consulta_fecha" role="tabpanel" aria-labelledby="consulta_fecha-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Consulta Fecha</h3>
                        <br>
                        <form id="form_detallada_fecha">
                            <div class="row container-fluid">
                                <div class="form-group col-12 col-md-3">
                                    <label for="fecha">Tipo Consulta</label>
                                    <select class="form-control" name="fecha" id="fecha">
                                        <option value="1">Fecha Recepción</option>
                                        <option value="2" selected>Fecha Compromiso</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label for="desde">Fecha desde</label>
                                    <input class="form-control" type="date" name="desde" id="desde">
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label for="hasta">Fecha Hasta</label>
                                    <input class="form-control" type="date" name="hasta" id="hasta">
                                    <p class="help-block"></p>
                                </div>
                                <div class="col-12 col-md-3">
                                    <br>
                                    <button type="submit" class="btn btn-primary">Consultar</button>
                                </div>
                            </div>
                        </form>
                        <br>
                    </div>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade show" id="consulta_codigo" role="tabpanel" aria-labelledby="consulta_codigo-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Consulta Por Código</h3>
                        <br>
                        <div class="container mt-3 mb-3">
                            <div class="recuadro">
                                <div id="contenido" class="px-2 py-2 col-lg-12">
                                    <form id="form_codigo_producto">
                                        <div class="input-group mt-3 mb-3">
                                            <label class="input-group-text" for="codigo_producto">Código Producto</label>
                                            <input type="text" class="form-control" name="codigo_producto" id="codigo_producto" placeholder="Ingrese Código de Producto">
                                            <button class="btn btn-primary" type="submit" id=""><i class="fa fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
                <!-- tercer link -->
                <div class="tab-pane fade show" id="consulta_pedido" role="tabpanel" aria-labelledby="consulta_pedido-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Consulta Por Número Pedido</h3>
                        <br>
                        <div class="container mt-3 mb-3">
                            <div class="recuadro">
                                <div id="contenido" class="px-2 py-2 col-lg-12">
                                    <form id="form_numero_pedido">
                                        <div class="input-group mt-3 mb-3">
                                            <label class="input-group-text" for="numero_pedido">Número Pedido</label>
                                            <input type="text" class="form-control" name="numero_pedido" id="numero_pedido" placeholder="Ingrese número de Pedido">
                                            <button class="btn btn-primary" type="submit" id=""><i class="fa fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
                <!-- cuarto link -->
                <div class="tab-pane fade show" id="consulta_cliente" role="tabpanel" aria-labelledby="consulta_cliente-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Consulta Cliente</h3>
                        <br>
                        <div class="container mt-3 mb-3">
                            <div class="recuadro">
                                <div id="contenido" class="px-2 py-2 col-lg-12">
                                    <form id="form_cliente">
                                        <div class="input-group mt-3 mb-3">
                                            <label class="input-group-text" for="num_cliente">Cliente</label>
                                            <select class="form-select select_2" multiple name="num_cliente" id="num_cliente" style="width: 90%;">
                                                <option value="0">Elija un Cliente</option>
                                                <?php foreach ($clientes as $cliente) { ?>
                                                    <option value="<?= $cliente->id_cli_prov ?>"><?= $cliente->nombre_empresa ?></option>
                                                <?php } ?>
                                            </select>
                                            <button class="btn btn-primary" type="submit" id=""><i class="fa fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-3 mb-3">
        <div class="recuadro">
            <div id="contenido" class="px-2 py-2 col-lg-12">
                <br>
                <h3 class="text-center fw-bold">Resultado consulta</h3>
                <br>
                <div class="col-lg-12">
                    <table id="tb_detallada" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                        <thead style="background:#0d1b50;color:white">
                            <tr>
                                <th>Fecha Ingreso</th>
                                <th>Fecha Compromiso</th>
                                <th>Fecha Liberación</th>
                                <th>Pedido Item</th>
                                <th>Recibe</th>
                                <th>Cantidad</th>
                                <th>Ref. Material</th>
                                <th>O.P.</th>
                                <th>Ancho</th>
                                <th>Cantidad O.P.</th>
                                <th>M2</th>
                                <th>ML</th>
                                <th>Fecha Proveedor</th>
                                <th>Codigo Articulo</th>
                                <th>core</th>
                                <th>Rollos X</th>
                                <th>Ficha Tec. Ubi Troquel</th>
                                <th style="width: 300px;">Descripción</th>
                                <th>orden de compra</th>
                                <th style="width: 300px;">Cliente</th>
                                <th style="width: 100px;">Asesor</th>
                                <th>Condición de pago</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Numero Factura</th>
                                <th>Estado Entrega</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <br>
            </div>
        </div>
        <br>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/consultas/js/consulta_detallada.js"></script>
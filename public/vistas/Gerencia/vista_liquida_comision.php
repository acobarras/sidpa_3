<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="intento_pedido-tab" data-bs-toggle="tab" href="#intento_pedido" role="tab" aria-controls="intento_pedido" aria-selected="true">Liquida Comisión</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Primer link -->
                    <div class="tab-pane fade show active" id="intento_pedido" role="tabpanel" aria-labelledby="intento_pedido-tab">
                        <div class="mb-3 row text-center">
                            <h1 class="col-md-12 col-md-offset-4 ">Comisión Asesor Periodo</h1>
                        </div>
                        <form class="mb-3 row" id="form_comision">
                            <div class="col-4">
                                <label for="asesor">Asesor Comercial</label>
                                <select class="form-control select_2" name="asesor" id="asesor">
                                    <option value="0"></option>
                                    <option value="cambio">Cambio Consulta</option>
                                    <?php foreach ($asesores as $asesor) { ?>
                                        <option value="<?= $asesor->id_usuario ?>"><?= $asesor->nombre ?> <?= $asesor->apellido ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div id="periodo_liquida" class="col-6">
                                <label for="periodo">Periodo Liquidado</label>
                                <select class="form-control select_2" name="periodo" id="periodo">
                                    <option value="0"></option>
                                    <?php foreach ($periodo as $per) { ?>
                                        <option value="<?= $per->id ?>"><?= $per->mes ?> - <?= $per->ano ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div id="rango_consulta" class="col-6 row" style="display: none;">
                                <div class="col-6">
                                    <label for="fecha_inicial">Fecha desde</label>
                                    <input type="date" class="form-control" name="fecha_inicial" id="fecha_inicial">
                                </div>
                                <div class="col-6">
                                    <label for="fecha_fin">Fecha Hasta</label>
                                    <input type="date" class="form-control" name="fecha_fin" id="fecha_fin">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-primary" type="submit" id="consultar_inventarios">Consultar <i class="fa fa-search"></i></button>
                            </div>
                        </form>
                        <div>
                            <table class="table table-bordered table-responsive table-hover" id="tabla_comision" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Factura</th>
                                        <th>Nit</th>
                                        <th>Empresa</th>
                                        <th>Fecha Factura</th>
                                        <th>Pertenece a:</th>
                                        <th>Fecha Pago</th>
                                        <th>Asesor</th>
                                        <th>total Etiquetas</th>
                                        <th>total Cintas</th>
                                        <th>total Alquiler</th>
                                        <th>total Tecnologia</th>
                                        <th>total Soporte</th>
                                        <th>total Flete</th>
                                        <th>total M. Prima</th>
                                        <th>Iva</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Dias Credito</th>
                                    </tr>
                                </thead>
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
<script src="<?= PUBLICO ?>/vistas/Gerencia/js/vista_liquida_comision.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="intento_pedido-tab" data-bs-toggle="tab" href="#intento_pedido" role="tab" aria-controls="intento_pedido" aria-selected="true">Costo Inventario</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Primer link -->
                    <div class="tab-pane fade show active" id="intento_pedido" role="tabpanel" aria-labelledby="intento_pedido-tab">
                        <div class="mb-3 row text-center">
                            <h1 class="col-md-12 col-md-offset-4 ">Costo Inventario</h1>
                        </div>
                        <form class="mb-3 row" id="form_costo_inv">
                            <div class="col-4">
                                <select class="form-control select_2" name="id_clase_articulo" id="id_clase_articulo">
                                    <option value="0"></option>
                                    <?php foreach ($clase_articulo as $value) { ?>
                                        <option value="<?= $value->id_clase_articulo ?>"><?= $value->nombre_clase_articulo ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div id="mostrar_tipo_articulo" class="col-6">
                                <input type="hidden" id="data_tipo_articulo" value='<?= json_encode($tipo_articulo) ?>'>
                                <select class="form-control select_2" name="id_tipo_articulo" id="id_tipo_articulo">
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-primary" type="submit" id="consultar_inventarios">Consultar <i class="fa fa-search"></i></button>
                            </div>
                        </form>
                        <div>
                            <table class="table table-bordered table-responsive table-hover" id="tabla_inv_costo" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <!-- <th>Entrada</th> -->
                                        <!-- <th>Salida</th> -->
                                        <th>Cantidad M2</th>
                                        <th>Costo</th>
                                        <th>Total</th>
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
<script src="<?= PUBLICO ?>/vistas/Gerencia/js/vista_inv_gerencia.js"></script>
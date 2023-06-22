<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="intento_pedido-tab" data-bs-toggle="tab" href="#intento_pedido" role="tab" aria-controls="intento_pedido" aria-selected="true">Intento Pedidos</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Primer link -->
                    <div class="tab-pane fade show active" id="intento_pedido" role="tabpanel" aria-labelledby="intento_pedido-tab">
                        <div class="container-fluid">
                            <br>
                            <form id="form_intentos_ped">
                                <div class="row g-3 align-items-center">
                                    <div class="col-3"></div>
                                    <div class="col-2">
                                        <label for="intentos_ped" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Criterio de búsqueda</label>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group mb-3">
                                            <select class="form-select" id="buscar_intentos_ped" name="intentos_ped">
                                                <option value="0">Elija una opcción</option>
                                                <option value="1">Cliente</option>
                                                <option value="2">Asesor</option>
                                            </select>
                                            <button type="submit" class="btn btn-success col-3">Buscar <i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <br><br>
                            <div style="display: none;" id="tabla_cliente">
                                <table id="tabla_intento_pedido_cliente" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                    <thead style="background:#0d1b50;color:white">
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Cantidad</th>
                                            <th style="width: 100px;">Opcción</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <br><br>
                            <div style="display: none;" id="tabla_asesor">
                                <table id="tabla_intento_pedido_asesor" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                    <thead style="background:#0d1b50;color:white">
                                        <tr>
                                            <th>Asesor</th>
                                            <th>Cantidad</th>
                                            <th style="width: 80px;">Opcción</th>
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
<script src="<?= PUBLICO ?>/vistas/Gerencia/js/intento_pedidos.js"></script>
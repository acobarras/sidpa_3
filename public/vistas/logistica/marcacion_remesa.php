<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12" id="tabla_facturacion_listado">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Marcacion Remesa</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="container text-center">
                    <form class="panel panel-default" name="form_marcacion" id="form_marcacion">
                        <div class="panel-heading text-center">
                            <h2>Marcacion Remesa</h2>
                        </div>
                        <div class="row panel-body">
                            <div class="col-10">
                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="num_pedido">Numero Pedido:</label>
                                    <input type="text" class="form-control num_pedio" id="num_pedido" name="num_pedido">
                                </div>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-success" id="consulta_pedido">Consultar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="div_impresion"></div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/logistica/js/marcacion_remesa.js"></script>
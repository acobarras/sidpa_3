<div class="container-fluid recuadro">
    <br>
    <h3 class="text-center fw-bold">Aumento De Precios</h3>
    <br>
    <div class="container mt-3 mb-3">
        <div class="recuadro">
            <div id="contenido" class="px-2 py-2 col-lg-12">
                <form id="form_aumento_cliente">
                    <div class="row">
                        <div class="col-6">
                            <label for="num_cliente" class="form-label">Cliente</label>
                            <select class="form-control select_2" style="width: 100%" multiple name="num_cliente" id="num_cliente">
                                <option value="0">Todos</option>
                                <?php foreach ($clientes as $cliente) { ?>
                                    <option value="<?= $cliente->nit ?>"><?= $cliente->nombre_empresa ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-3">
                            <div class="mt-4">
                                <label class="fw-bolder" for="aumento">Aumento:</label>
                                <input type="radio" class="aumento select_acob" name="aumento" id="aumento_si" value="1">

                                <label class="fw-bolder" for="aumento">Disminución:</label>
                                <input type="radio" class="aumento select_acob" name="aumento" id="aumento_no" value="2">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class=" mt-4 input-group">
                                <label for="porcentaje" class="form-label">Porcentaje(%) </label>
                                <input type="number" class="form-control" name="porcentaje" id="porcentaje">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <button class="btn btn-primary" type="submit" id="envio_aumento_precio">Grabar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/aumento_precio.js"></script>
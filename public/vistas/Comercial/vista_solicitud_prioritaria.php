<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Solicitud Prioritaria</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="container">
                        <div class="recuadro p-4">
                            <div class="mb-3 text-center">
                                <h3>Formulario Creacion Prioridad</h3>
                            </div>
                            <form id="form_prioridad">
                                <div class="mx-3 row">
                                    <div class="col-3">
                                        <label for="proceso" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Proceso</label>
                                        <select class="form-control select_2" name="proceso" id="proceso">
                                            <option value="0">Elija un proceso</option>
                                            <option value="1">Desarrollo</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label for="pedido" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Pedido</label>
                                        <input type="text" class="form-control" id="pedido" name="pedido">
                                    </div>
                                    <div class="col-3">
                                        <label for="item" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Pedido Item</label>
                                        <select class="form-control select_2" id="item" name="item">
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label for="cliente" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Cliente</label>
                                        <input type="text" disabled class="form-control" id="cliente" name="cliente">
                                    </div>
                                </div>
                                <div class="mx-3 row">
                                    <div class="m-auto justify-content-center col-8 text-center">
                                        <label class="col-form-label" for="observacion" style="font-family: 'gothic'; font-weight: bold; ">Decripcion Solicitud</label>
                                        <textarea class="form-control" name="observacion" id="observacion"></textarea>
                                    </div>
                                </div>
                                <br>
                                <div class="text-center">
                                    <button class="btn btn-success" type="submit" id="enviar_prioridad">
                                        <i class="fa fa-plus-circle"></i> Enviar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/comercial/js/vista_solicitud_prioritaria.js"></script>
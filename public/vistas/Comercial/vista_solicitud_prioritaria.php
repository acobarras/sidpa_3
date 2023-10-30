<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Solicitud Prioritaria</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="consulta-tab" data-bs-toggle="tab" href="#consulta" role="tab" aria-controls="consulta" aria-selected="true">Consultas Prioritarias</a>
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
                                <div class="mx-3 row m-auto justify-content-center">
                                    <div class="col-12 text-center">
                                        <h6 class="text-danger">*Si el proceso que busca no se encuentra, es por que no han designado a ninguna persona para responder prioridades.</h6>
                                    </div>
                                    <div class="col-5">
                                        <label for="area" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Proceso</label>
                                        <select class="form-control select_2" multiple name="" id="area">
                                            <option value="0"></option>
                                            <?php foreach ($area as $areas) { ?>
                                                <option value="<?= $areas->id_area_trabajo ?>"><?= $areas->nombre_area_trabajo ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <label for="actividad" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Actividad</label>
                                        <select class="form-control select_2" name="actividad" id="actividad">
                                            <option value="1" selected>Pedido</option>
                                            <option value="2">Otros</option>
                                        </select>
                                    </div>
                                    <div id="form_pedido" class="row m-auto justify-content-center">
                                        <div class="col-4">
                                            <label for="pedido" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Pedido</label>
                                            <input type="number" class="form-control" value="0" id="pedido" name="pedido">
                                        </div>
                                        <div class="col-4">
                                            <label for="item" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Pedido Item</label>
                                            <select class="form-control select_2" id="item" name="item">
                                                <option value="0">Completo</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label for="cliente" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Cliente</label>
                                            <input type="text" readonly class="form-control" id="cliente" name="cliente">
                                        </div>
                                    </div>
                                </div>
                                <div class="mx-3 row">
                                    <div class="m-auto justify-content-center col-8 text-center">
                                        <label class="col-form-label" for="observacion" style="font-family: 'gothic'; font-weight: bold; ">Decripcion Solicitud</label>
                                        <textarea class="form-control" rows="10" name="observacion" id="observacion"></textarea>
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
                <!-- segundo link -->
                <div class="tab-pane fade show" id="consulta" role="tabpanel" aria-labelledby="consulta-tab">
                    <div class="container">
                        <div class="mb-3 text-center">
                            <h3>Consultas Prioridad</h3>
                        </div>
                        <div class="mb-3">
                            <table style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" id="tabla_prioridades" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>id_Prioridad</th>
                                        <th>Prioridad</th>
                                        <th>Fecha Creación</th>
                                        <th>Cliente</th>
                                        <th>Pedido</th>
                                        <th>Item</th>
                                        <th>Respuestas</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Comercial/js/vista_solicitud_prioritaria.js"></script>
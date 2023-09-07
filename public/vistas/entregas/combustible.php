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
                                <h3>Formulario Reporte Combustible</h3>
                            </div>
                            <form id="form_combustible">
                                <input type="hidden" class="form-control" value='<?=json_encode($ultimo[0]) ?>' id="ultimo_registro" name="ultimo_registro">
                                <div class="mx-3 row m-auto justify-content-center">
                                    <div class="col-4">
                                        <label for="fecha_crea" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Fecha:</label>
                                        <input type="date" class="form-control" value="0" id="fecha_crea" name="fecha_crea">
                                    </div>
                                    <div class="col-4">
                                        <label for="valor_tanqueado" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Valor Tanqueado:</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control saca_calor" value="0" id="valor_tanqueado" name="valor_tanqueado">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="precio_galon" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Precio Galón</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control saca_calor" value="0" id="precio_galon" name="precio_galon">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="kilometraje_ant" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Kilometraje Previo</label>
                                        <input type="number" class="form-control" value="<?= $ultimo[0]->kilometraje ?>" id="kilometraje_ant" name="kilometraje_ant" readonly>
                                    </div>
                                    <div class="col-4">
                                        <label for="kilometraje" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Kilometraje</label>
                                        <input type="number" class="form-control" id="kilometraje" name="kilometraje">
                                    </div>
                                    <div class="col-4">
                                        <label for="cant_galones" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Cant. Galones</label>
                                        <input type="number" class="form-control" id="cant_galones" name="cant_galones" readonly>
                                    </div>
                                </div>
                                <br>
                                <div class="text-center">
                                    <button class="btn btn-success" type="button" id="enviar_combus">
                                        <i class="fa fa-plus-circle"></i> Enviar
                                    </button>
                                </div>
                            </form>
                        </div>
                        <table id="tab_combustible" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Valor Tanqueado</th>
                                    <th>Precio Galón</th>
                                    <th>Kilometraje</th>
                                    <th>Cant Galones</th>
                                    <th>Kilometros X Galon</th>
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

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/entregas/js/combustible.js"></script>
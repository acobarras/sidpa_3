<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Chequeo Vehicular</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form class="container" id="form_chequeo_vehiculo" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <br>
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 text-center">Chequeo Vehicular</h1>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-md-4 col-12">
                                <label for="vehiculo" class="form-label">Vehiculos : </label>
                                <select class="form-control select_2" style="width: 100%;" id="vehiculo" name="vehiculo">
                                    <option value="0">Elija un vehiculo</option>
                                    <?php foreach ($vehiculos as $vehiculo) { ?>
                                        <option value="<?= $vehiculo->id_vehiculo ?>" data_vehi='<?= json_encode($vehiculo) ?>'><?= $vehiculo->placa ?> | <?= $vehiculo->marca ?> <?= $vehiculo->linea ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-md-4 col-12">
                                <label for="propietario" class="form-label">Propietario : </label>
                                <input class="form-control" type="text" name="propietario" id="propietario" readonly>
                            </div>
                            <div class="mb-3 col-md-4 col-12">
                                <label for="revisado_por" class="form-label">Revisado Por: </label>
                                <input class="form-control" type="hidden" name="revisado_por" id="revisado_por" value="<?= $_SESSION['usuario']->getid_usuario() ?>">
                                <input class="form-control" type="text" readonly value="<?= $_SESSION['usuario']->getNombre() ?> <?= $_SESSION['usuario']->getApellido() ?>">
                            </div>
                        </div>
                        <div class="recuadro">
                            <br>
                            <div class="container text-center">
                                <div class="mb-3 row row-cols-2">
                                    <?php $num = 0;
                                    foreach (PREG_CHEQUEO as $value) {
                                        $num++;
                                        if (($num % 2) == 0) {
                                            $estilo = '';
                                        } else {
                                            $estilo = 'border-end';
                                        }
                                        if ($value['tipo'] == 'select') { ?>
                                            <div class="mb-3 col-md-6 col-12 border-bottom <?= $estilo ?> border border-1">
                                                <label class="fw-bolder" for="<?= $value['name'] ?>"><?= $value['pregunta'] ?></label>
                                                <div class="mb-3 col-12">
                                                    <span class="select_acob">
                                                        <!-- Se crea un input oculto con valor de 0 para que haga el validar formulario y se deja chkeado para que valide hasta que se haga el cambio del valor -->
                                                        <input class="d-none" type="radio" name="<?= $value['name'] ?>" id="<?= $value['name'] ?>_vacio" value="0">
                                                        Si &nbsp;&nbsp;
                                                        <input type="radio" name="<?= $value['name'] ?>" id="<?= $value['name'] ?>" value="1" checked>
                                                        <span style="padding-left: 30px"></span>
                                                        No &nbsp;&nbsp;
                                                        <input type="radio" name="<?= $value['name'] ?>" id="<?= $value['name'] ?>_no" value="2">
                                                    </span>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="mb-3 col-md-6 col-12 border-bottom <?= $estilo ?> border border-1">
                                                <label class="fw-bolder" for="<?= $value['name'] ?>"><?= $value['pregunta'] ?></label>
                                                <div class="mb-3 col-12">
                                                    <input class="form-control" type="date" name="<?= $value['name'] ?>" id="<?= $value['name'] ?>">
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="panel-footer text-center">
                            <button type="submit" class="btn btn-success" id="enviar_chequeo">Enviar</button>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/logistica/js/vista_chequeo.js"></script>
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
                    <div class="mb-3 row">
                        <h1 class="col-md-12 col-md-offset-4 text-center">Chequeo Vehicular</h1>
                    </div>
                    <br>
                    <div style="text-align:center;">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input formulario_chequeo" type="radio" name="select_form" id="select_form_moto" value="1">
                            <label class="form-check-label" for="select_form_moto">Chequeo Moto</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input formulario_chequeo" type="radio" name="select_form" id="select_form_carro" value="2">
                            <label class="form-check-label" for="select_form_carro">Chequeo Carro</label>
                        </div>
                    </div>
                    <br>
                    <form class="container d-none" id="form_chequeo_moto" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <div class="mb-3 row">
                            <div class="mb-3 col-md-4 col-12">
                                <label for="id_moto" class="form-label">Vehiculos : </label>
                                <select class="form-control select_2 vehiculo" style="width: 100%;" id="id_moto" name="id_vehiculo">
                                    <option value="0">Elija un vehiculo</option>
                                    <?php foreach ($vehiculos as $vehiculo) { ?>
                                        <option value="<?= $vehiculo->id_vehiculo ?>" data_vehi='<?= json_encode($vehiculo) ?>'><?= $vehiculo->placa ?> | <?= $vehiculo->marca ?> <?= $vehiculo->linea ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-md-4 col-12">
                                <label for="propietario_moto" class="form-label">Propietario : </label>
                                <input class="form-control" type="text" name="propietario" id="propietario_moto" readonly>
                            </div>
                            <div class="mb-3 col-md-4 col-12">
                                <label for="id_user_chequeo_moto" class="form-label">Revisado Por: </label>
                                <input class="form-control" type="hidden" name="id_user_chequeo" id="id_user_chequeo_moto" value="<?= $_SESSION['usuario']->getid_usuario() ?>">
                                <input class="form-control" type="text" readonly value="<?= $_SESSION['usuario']->getNombre() ?> <?= $_SESSION['usuario']->getApellido() ?>">
                            </div>
                        </div>
                        <div class="recuadro">
                            <br>
                            <div class="container text-center">
                                <div class="mb-3 row row-cols-2">
                                    <div class="mb-3 col-md-6 col-12 border-bottom border border-1">
                                        <label class="fw-bolder" for="refrigeracion_moto">¿El nivel del líquido de refrigeración se encuentra entre el máximo y el mínimo?</label>
                                        <div class="mb-3 col-12">
                                            <span class="select_acob">
                                                <!-- Se crea un input oculto con valor de 0 para que haga el validar formulario y se deja chkeado para que valide hasta que se haga el cambio del valor -->
                                                <input class="d-none" type="radio" name="refrigeracion_moto" id="refrigeracion_moto_vacio" value="0" checked>
                                                Si &nbsp;&nbsp;
                                                <input type="radio" name="refrigeracion_moto" id="refrigeracion_moto" value="si">
                                                <span style="padding-left: 30px"></span>
                                                No &nbsp;&nbsp;
                                                <input type="radio" name="refrigeracion_moto" id="refrigeracion_moto_no" value="no">
                                                <span style="padding-left: 30px"></span>
                                                N/A &nbsp;&nbsp;
                                                <input type="radio" name="refrigeracion_moto" id="refrigeracion_moto_na" value="n/a">
                                            </span>
                                        </div>
                                    </div>
                                    <?php $num = 0;
                                    foreach (CHEQUEO_MOTO as $value) {
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
                                                        <input class="d-none" type="radio" name="<?= $value['name'] ?>" id="<?= $value['name'] ?>_vacio" value="0" checked>
                                                        Si &nbsp;&nbsp;
                                                        <input type="radio" name="<?= $value['name'] ?>" id="<?= $value['name'] ?>" value="si">
                                                        <span style="padding-left: 30px"></span>
                                                        No &nbsp;&nbsp;
                                                        <input type="radio" name="<?= $value['name'] ?>" id="<?= $value['name'] ?>_no" value="no">
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
                        <div class="panel-footer text-center">
                            <button type="submit" class="btn btn-success" id="enviar_chequeo_moto">Enviar</button>
                        </div>
                    </form>
                    <form class="container d-none" id="form_chequeo_vehiculo" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <div class="mb-3 row">
                            <div class="mb-3 col-md-4 col-12">
                                <label for="id_vehiculo" class="form-label">Vehiculos : </label>
                                <select class="form-control select_2 vehiculo" style="width: 100%;" id="id_vehiculo" name="id_vehiculo">
                                    <option value="0">Elija un vehiculo</option>
                                    <?php foreach ($vehiculos as $vehiculo) { ?>
                                        <option value="<?= $vehiculo->id_vehiculo ?>" data_vehi='<?= json_encode($vehiculo) ?>'><?= $vehiculo->placa ?> | <?= $vehiculo->marca ?> <?= $vehiculo->linea ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-md-4 col-12">
                                <label for="propietario_vehiculo" class="form-label">Propietario : </label>
                                <input class="form-control" type="text" name="propietario" id="propietario_vehiculo" readonly>
                            </div>
                            <div class="mb-3 col-md-4 col-12">
                                <label for="revisado_por" class="form-label">Revisado Por: </label>
                                <input class="form-control" type="hidden" name="id_user_chequeo" id="revisado_por" value="<?= $_SESSION['usuario']->getid_usuario() ?>">
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
                                                        <input class="d-none" type="radio" name="<?= $value['name'] ?>" id="<?= $value['name'] ?>_vacio" value="0" checked>
                                                        Si &nbsp;&nbsp;
                                                        <input type="radio" name="<?= $value['name'] ?>" id="<?= $value['name'] ?>" value="si">
                                                        <span style="padding-left: 30px"></span>
                                                        No &nbsp;&nbsp;
                                                        <input type="radio" name="<?= $value['name'] ?>" id="<?= $value['name'] ?>_no" value="no">
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
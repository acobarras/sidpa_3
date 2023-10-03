<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="nav-cotizado-tab" data-bs-toggle="tab" href="#nav-cotizado" role="tab" aria-controls="nav-cotizado" aria-selected="true">Cotizador de Etiquetas</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-cotizado" role="tabpanel" aria-labelledby="nav-cotizado-tab">
                        <div class="recuadro">
                            <br>
                            <br>
                            <div class="container-fluid">
                                <div>
                                    <h1 style="text-align: center" id="titulo">COTIZADOR DE ETIQUETAS</h1>
                                </div>
                                <div class="mt-1 panel-body">
                                    <div id="error-cotizador"></div>
                                    <form method="post" name="cotiza" id="formu_cotizador">
                                        <div class="form-group row">
                                            <label for="fecha" class="col-sm-2 col-form-label">FECHA:</label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="fecha" value="<?php date_default_timezone_set('America/Bogota');
                                                                                                echo date('Y/m/d h:i:s a') ?>" readonly="readonly" type="text">
                                            </div>
                                            <label for="tipo_cotiza1" class="col-sm-2 col-form-label">TIPO COTIZACIÓN:</label>
                                            <div class="col-sm-4">
                                                <p class="form-control text-center">
                                                    Etiquetas <input type="radio" id="tipo_cotiza1" name="tipo_cotiza" class="tipo_cotiza" value="1" checked />
                                                    Templero <input type="radio" id="tipo_cotiza2" name="tipo_cotiza" class="tipo_cotiza" value="2" />
                                                </p>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="ancho" class="col-sm-2 col-form-label">ANCHO ETIQUETA:</label>
                                            <div class="col-sm-4">
                                                <input class="form-control" type="text" id="ancho" name="ancho" placeholder="Medida en Milimetros" required />
                                            </div>
                                            <label for="alto" class="col-sm-2 col-form-label">ALTO ETIQUETA:</label>
                                            <div class="col-sm-4">
                                                <input class="form-control" type="text" id="alto" name="alto" placeholder="Medida en Milimetros" required />
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <label for="material" class="col-sm-2 col-form-label">TIPO DE MATERIAL:</label>
                                            <div class="col-sm-4">
                                                <select class="form-control" style="width: 100%;" id="material" name="material" required>
                                                    <option value="0"></option>
                                                    <?php foreach ($mat as $tipo_material) {
                                                        if ($tipo_material->estado == 1) {
                                                    ?>
                                                            <option value="<?= $tipo_material->id_tipo_material ?>"><?= mb_strtoupper($tipo_material->nombre_material, 'UTF-8') ?></option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                            <!----------------------------------------------------------------------->
                                            <label for="adh" class="col-sm-2 col-form-label">ADHESIVO:</label>
                                            <div class="col-sm-4">
                                                <input type="hidden" id="selec_adh" value='<?= json_encode($adh) ?>'>
                                                <input type="hidden" id="selec_precio" value='<?= json_encode($precio) ?>'>
                                                <select class="form-control" style="width: 100%;" id="adh" name="adh" required>
                                                    <option value="0"></option>
                                                    <?php foreach ($adh as $adhesivo) { ?>
                                                        <option value="<?= $adhesivo->id_adh ?>"><?= $adhesivo->nombre_adh ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group row">
                                            <label for="tintas" class="col-sm-2 col-form-label">APLICACION DE TINTAS:</label>
                                            <div class="col-sm-4">
                                                <input class="form-control" type="text" name="tintas" id="tintas" maxlength="1" value="0" />
                                            </div>
                                            <label for="cyrel1" class="col-sm-2 col-form-label">REQUIERE CYREL:</label>
                                            <div class="col-sm-4">
                                                <p class="form-control text-center">
                                                    <?php if ($_SESSION['usuario']->getId_roll() == 1 || $_SESSION['usuario']->getId_roll() == 8) { ?>
                                                        SI <input type="radio" id="cyrel1" name="cyrel" value="1" />
                                                        NO <input type="radio" id="cyrel2" name="cyrel" value="2" checked />
                                                    <?php } else { ?>
                                                        SI <input disabled type="radio" id="cyrel1" name="cyrel" value="1" />
                                                        NO <input disabled type="radio" id="cyrel2" name="cyrel" value="2" checked />
                                                    <?php } ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="troquel1" class="col-sm-2 col-form-label">REQUIERE TROQUEL:</label>
                                            <div class="col-sm-4">
                                                <p class="form-control text-center">
                                                    SI <input type="radio" id="troquel1" name="troquel" value="1" />
                                                    NO <input type="radio" id="troquel2" name="troquel" value="2" checked="checked" />
                                                </p>
                                            </div>
                                            <label for="estcalor1" class="col-sm-2 col-form-label">ESTAMPADO AL CALOR:</label>
                                            <div class="col-sm-4">
                                                <p class="form-control text-center">
                                                    SI <input class="estcalor" type="radio" id="estcalor1" name="estcalor" value="1" />
                                                    NO <input class="estcalor" type="radio" id="estcalor2" name="estcalor" value="2" checked="checked" />
                                                    <input disabled type="text" name="cantestcalor" id="cantestcalor" value="1" placeholder="Q. Cintas" style="width: 60px; display: none;">
                                                </p>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="estfrio1" class="col-sm-2 col-form-label">ESTAMPADO AL FRIO:</label>
                                            <div class="col-sm-4 text-center">
                                                <p class="form-control">
                                                    SI <input type="radio" id="estfrio1" name="estfrio" value="1" />
                                                    NO <input type="radio" id="estfrio2" name="estfrio" value="2" checked="checked" />
                                                </p>
                                            </div>
                                            <label for="laminado" class="col-sm-2 col-form-label">LAMINADO:</label>
                                            <div class="col-sm-4">
                                                <select class="form-control" style="width: 100%;" id="laminado" name="laminado" required>
                                                    <option value="0">NO</option>
                                                    <option value="2">BRILLANTE</option>
                                                    <option value="3">MATE</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3 form-group row">
                                            <label for="cantidad" class="col-sm-2 col-form-label">Q. SOLICITADA:</label>
                                            <div class="col-sm-4">
                                                <input class="form-control" type="text" id="cantidad" name="cantidad" required />
                                            </div>
                                        </div>
                                        <div class="mb-3 form-group row">
                                            <div class="col-12 text-primary"><span id="superficies"></span></div>
                                        </div>
                                        <div class="mb-3 form-group row">
                                            <div class="col-12 text-primary"><span id="rango_temp"></span></div>
                                        </div>
                                        <div class="text-center">
                                            <button class="btn btn-primary" type="button" id="enviar_cotiza">
                                                <i class="fa fa-plus-circle"></i> Consultar
                                            </button>
                                        </div>
                                        <br>
                                        <div id="error_cotiza" style="display: none;">
                                            <div class="text-center">
                                                <h1 class="text-danger">Lo sentimos no hay unidad magnetica para este tamaño</h1>
                                            </div>
                                        </div>
                                        <div id="resultado" style="display: none;">
                                            <div class="mb-3 form-group row">
                                                <label for="" class="col-sm-6 col-form-label">PRECIO PARA MENOS DE <span class="cant_minima_etiq">$cant_minima_etiq </span> <span class="texto">$texto</span> </label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" id="precio_variante" readonly="readonly" value="$ ' . number_format($precio_variante, 2) . '" />
                                                </div>
                                            </div>
                                            <div class="mb-3 form-group row">
                                                <label for="" class="col-sm-6 col-form-label">PRECIO ENTRE <span class="cant_minima_etiq">$cant_minima_etiq </span> Y MENOS DE <span class="cant_minima_etiq1">$cant_minima_etiq1 </span> <span class="texto">$texto</span></label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" id="precio_alto" readonly="readonly" value="$' . number_format($precio_alto, 2) . '" />
                                                </div>
                                            </div>
                                            <div class="mb-3 form-group row">
                                                <label for="" class="col-sm-6 col-form-label">PRECIO ENTRE <span class="cant_minima_etiq1">$cant_minima_etiq1 </span> Y MENOS DE <span class="cant_minima_etiq2">$cant_minima_etiq2 </span> <span class="texto">$texto</span></label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" id="precio_medio" readonly="readonly" value="$ ' . number_format($precio_medio, 2) . '" />
                                                </div>
                                            </div>
                                            <div class="mb-3 form-group row">
                                                <label for="" class="col-sm-6 col-form-label">PRECIO PARA MAS DE <span class="cant_minima_etiq2">$cant_minima_etiq2 </span> <span class="texto">$texto</span></label>
                                                <div class="col-sm-6">
                                                    <input class="form-control" id="precio_bajo" readonly="readonly" value="$' . number_format($precio_bajo, 2) . '" />
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>

<script src="<?= PUBLICO ?>/vistas/Comercial/js/cotizar_etiquetas.js"></script>
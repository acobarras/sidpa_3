<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="fecha_corte-tab" data-bs-toggle="tab" href="#fecha_corte-tab" role="tab" aria-controls="fecha_corte-tab" aria-selected="true">Fecha Corte</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Primer link -->
                    <div class="tab-pane fade show active" id="fecha_corte-tab" role="tabpanel" aria-labelledby="fecha_corte-tab">
                        <br>
                        <div class="container">
                            <div class="recuadro p-4">
                                <form id="form_fecha_corte">
                                    <div class="mx-3 row">
                                        <input type="hidden" class="form-control" id="id_corte" value="0">
                                        <div class="col-3">
                                            <label for="mes" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Mes</label>
                                            <select class="form-control select_2" name="mes" id="mes">
                                                <option value="0">Elija un mes</option>
                                                <?php
                                                foreach (MES_ESP as $mes) { ?>
                                                    <option value="<?= $mes ?>"><?= $mes ?></option>
                                                <?php
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <label for="ano" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Año</label>
                                            <select class="form-control select_2" name="ano" id="ano">
                                                <option value="0">Elija un año</option>
                                                <?php
                                                $ano = 2020;
                                                $ano_actual = date("Y");
                                                for ($i = $ano; $i < ($ano + 50); $i++) {
                                                    if ($i == $ano_actual) { ?>
                                                        <option value="<?= $i ?>"><?= $i ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?= $i ?>"><?= $i ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <label for="corte" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Fecha Cierre</label>
                                            <input type="date" class="form-control" id="corte" name="corte">
                                        </div>
                                        <div class="col-3 mt-4">
                                            <button type="submit" class="btn btn-success" id="enviar_fecha">Enviar <i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="mb-3 text-center">
                                    <h3>Tabla Fechas Corte</h3>
                                </div>
                                <table id="tabla_fecha" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                    <thead style="background: #002b5f;color: white">
                                        <tr>
                                            <th>Id</th>
                                            <th>Mes</th>
                                            <th>Año</th>
                                            <th>Fecha Cierre</th>
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
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Contabilidad/js/vista_fecha_corte.js"></script>
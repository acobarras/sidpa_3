<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Reportar Mi Cargue</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <input type="hidden" id="roll" value="<?= $_SESSION['usuario']->getId_roll(); ?>">
                    <input type="hidden" id="id_persona" value="<?= $_SESSION['usuario']->getId_persona(); ?>">
                    <div class="panel panel-default" id="adiciona_ruta">
                        <br>
                        <div class="panel-heading text-center mb-3">
                            <h3><b>Consultar Mis Ingresos</b></h3>
                        </div>
                        <div class="panel-body">
                            <form class="row mb-3" id="form_consulta_ingresos">
                                <?php if ($_SESSION['usuario']->getId_roll() == 1||$_SESSION['usuario']->getId_roll() == 10) { ?>
                                    <div class="col-3">
                                        <div class="mb-3">
                                            <label for="transportador" class="form-label">Transportador:</label>
                                            <select class="form-control select_2" id="transportador" name="transportador">
                                                <option value="0"></option>
                                                <?php foreach ($personas as $value) { ?>
                                                    <option value="<?= $value->id_persona; ?>">
                                                        <?= $value->nombres . " " . $value->apellidos; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="desde" class="form-label">Fecha Desde:</label>
                                        <input type="text" class="form-control datepicker" id="desde" name="desde" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="hasta" class="form-label">Fecha Hasta:</label>
                                        <input type="text" class="form-control datepicker" id="hasta" name="hasta" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center mb-3">
                                        <button class="btn btn-primary btn-lg boton-x" type="submit" id="consulta_mis_ingresos">Consultar</button>
                                    </div>
                                </div>
                            </form>
                            <div class="mb-3">
                                <table id="table_mis_ingresos" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                    <thead style="background: #002b5f;color: white">
                                        <tr>
                                            <th>Fecha Cargue</th>
                                            <th>Transportador</th>
                                            <th>Documento</th>
                                            <th>Cliente</th>
                                            <th>Valor Documento</th>
                                            <th>Valor Flete</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3">Total :</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
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
<script src="<?= PUBLICO ?>/vistas/entregas/js/vista_mis_ingresos.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Indicador Desperdicio Operario</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Datos Indicador</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <form class="panel panel-default" name="solicitud_desperdicio" id="solicitud_desperdicio">
                        <center class="panel-heading">
                            <h2>Periodo del indicador</h2>
                        </center>
                        <div class="panel-body">
                            <div class="mb-3 row">
                                <div class="form-group col-md-6">
                                    <label for="fecha_desde">Fecha desde:</label>
                                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="fecha_hasta">Fecha Hasta:</label>
                                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta">
                                </div>
                            </div>
                        </div>
                        <div class="text-center" id="muestro">
                            <button type="submit" class="btn btn-primary" id="id_envio">Grabar</button>
                        </div>
                        <br>
                    </form>
                    <div class="panel panel-default">
                        <center class="panel-heading">
                            <h2>Desperdicio Operario</h2>
                        </center>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="tabla_desperdicio_operario" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                    <thead style="background: #002b5f;color: white">
                                        <tr>
                                            <th>Operario</th>
                                            <th>Q. Etiquetas</th>
                                            <th>m<sup style="color: white;">2</sup><br>Utilizados</th>
                                            <th>m<sup style="color: white;">2</sup><br>Desperdicio</th>
                                            <th>$ Total<br>Desperdicio</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <canvas id="myChart" width="400" height="400"></canvas>
                        </div>
                        <div class="col-6">
                            <canvas id="myChart1" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Segundo link -->
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <br>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>Tabla datos Indicador Desperdicio Operario</h2>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="tabla_datos_desperdicio_operario" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                    <thead style="background: #002b5f;color: white">
                                        <tr>
                                            <th>O.P.</th>
                                            <th>Operario</th>
                                            <th>Tamaño<br>Etiqueta</th>
                                            <th>Material</th>
                                            <th>Ancho</th>
                                            <th>m<br>Utilizados</th>
                                            <th>Q. Etiquetas</th>
                                            <th>m<sup style="color: white;">2</sup><br>Utilizados</th>
                                            <th>m<sup style="color: white;">2</sup><br>Desperdicio</th>
                                            <th>ml<br>Desperdicio</th>
                                            <th>%<br />Desperdicio</th>
                                            <th>$<br>M.P.</th>
                                            <th>$<br>Desperdicio</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/indicadores/js/vista_desperdicio_operario.js"></script>
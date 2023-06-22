<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Items Pendientes O.P.</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="first-tab" data-bs-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="false">Fechas Programadas</a>
                </li>
                <!-- <li class="nav-item" role="presentation">
                    <a class="nav-link" id="second-tab" data-bs-toggle="tab" href="#second" role="tab" aria-controls="second" aria-selected="false">Productividad Operario</a>
                </li> -->
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <div class="mb-3 text-center">
                        <h1 class="text-center">TURNOS OPERARIO</h1>
                    </div>
                    <table id="tabla_operarios" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                        <thead style="background: #002b5f;color: white">
                            <tr>
                                <th>Operario</th>
                                <th>Ver</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade" id="first" role="tabpanel" aria-labelledby="first-tab">
                    <br>
                    <div class="mb-3 text-center">
                        <h1 class="text-center">FECHAS PROGRAMADAS</h1>
                    </div>
                    <div class="mb-3 row">
                        <div class="mb-3 col-5">
                            <label for="fecha_inicio" class="form-label">Fecha Inicio : </label>
                            <input class="form-control datepicker" type="text" autocomplete="off" name="" id="fecha_inicio">
                        </div>
                        <div class="mb-3 col-5">
                            <label for="fecha_fin" class="form-label">Fecha Fin : </label>
                            <input class="form-control datepicker" type="text" autocomplete="off" name="" id="fecha_fin">
                        </div>
                        <div class="pt-3 col-2 text-center">
                            <button class="btn btn-primary" type="button" id="consultar_fechas">
                                <i class="fa fa-plus-circle"></i> Consultar
                            </button>
                        </div>
                    </div>
                    <br>
                    <table id="tabla_fechas" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                        <thead style="background: #002b5f;color: white">
                            <tr>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Horario</th>
                                <th>Horas</th>
                                <th>Fecha</th>
                                <th>Maquina</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- tercero link -->
                <div class="tab-pane fade" id="second" role="tabpanel" aria-labelledby="second-tab">
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/produccion/js/vista_consulta_operario.js"></script>
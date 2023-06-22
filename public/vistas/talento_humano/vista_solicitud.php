<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Solicitud Descargos</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="first-tab" data-bs-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="false">Solicitud Personal</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <form class="container panel panel-default" id="solicitud_descargos">
                        <div class="mb-3 text-center">
                            <h1 class="text-center">Solicitud Descargos</h1>
                        </div>
                        <div class="panel-body">
                            <div class="form-group row mb-3">
                                <label class="col-sm-2" for="id_lider_proceso">Solicitante:</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" name="id_lider_proceso" id="id_lider_proceso">
                                        <option value="0"></option>
                                        <?php foreach ($liderProceso as $lider) { ?>
                                            <option value="<?= $lider->id_persona ?>"><?= $lider->nombres . " " . $lider->apellidos ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-2" for="id_colaborador">Colaborador:</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="id_colaborador" id="id_colaborador">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-2" for="fecha_falla">Fecha de la presunta Falla:</label>
                                <div class="col-sm-4">
                                    <input class="form-control" name="fecha_falla" id="fecha_falla" type="date" />
                                </div>
                                <label class="col-sm-2" for="hora_falla">Hora de la presunta Falla:</label>
                                <div class="col-sm-4">
                                    <input class="form-control" name="hora_falla" id="hora_falla" type="time" />
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-2" for="soporte_falla">Cuenta con soporte de la falla:</label>
                                <div class="col-sm-10">
                                    Si <input class="soporte_falla" name="soporte_falla" id="soporte_falla1" value="1" type="radio" />
                                    No <input class="soporte_falla" name="soporte_falla" id="soporte_falla2" value="2" type="radio" checked />
                                    <span id="mensaje"></span>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-2" for="fecha_oficina_desde">Fecha Desde Inicio Semana Horario Oficina:</label>
                                <div class="col-sm-4">
                                    <input class="form-control" name="fecha_oficina_desde" id="fecha_oficina_desde" type="date" />
                                </div>
                                <label class="col-sm-2" for="fecha_oficina_hasta">Fecha Hasta Fin Semana Horario Oficina:</label>
                                <div class="col-sm-4">
                                    <input class="form-control" name="fecha_oficina_hasta" id="fecha_oficina_hasta" type="date" />
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-sm-2" for="descripcion_falla">Presunta Falla Detectada:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="descripcion_falla" id="descripcion_falla" style="height: 214px; resize: none;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-center" id="muestro">
                            <button type="submit" class="btn btn-primary" id="id_envio">Grabar</button>
                        </div>
                        <br>
                    </form>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade" id="first" role="tabpanel" aria-labelledby="first-tab">
                    <br>
                    <form class="container panel panel-default" id="solicitud_personal">
                        <div class="mb-3 text-center">
                            <h1 class="text-center">Solicitud Personal</h1>
                        </div>
                        <div class="panel-body">
                            <div class="mb-3 form-group row">
                                <label class="col-sm-2" for="id_lider_proceso_personal">Solicitante:</label>
                                <div class="col-sm-10">
                                    <select class="select2" style="width: 100%;" name="id_lider_proceso" id="id_lider_proceso_personal">
                                        <option value="0"></option>
                                        <?php foreach ($liderProceso as $lider) { ?>
                                            <option value="<?= $lider->id_persona ?>"><?= $lider->nombres . " " . $lider->apellidos ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 form-group row">
                                <label class="col-sm-2" for="id_perfil_cargo">Cargo solicitado:</label>
                                <div class="col-sm-10">
                                    <select class="select2" style="width: 100%;" name="id_perfil_cargo" id="id_perfil_cargo">
                                        <option value="0"></option>
                                        <?php foreach ($perfilCargo as $perfil) { ?>
                                            <option value="<?= $perfil->id_perfil ?>"><?= $perfil->nombre_cargo ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 form-group row">
                                <label class="col-sm-2" for="num_vacantes">Numero de vacantes requeridas:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="num_vacantes" id="num_vacantes" />
                                </div>
                            </div>
                            <div class="mb-3 form-group row">
                                <label class="col-sm-2" for="observaciones">Observaciones:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="observaciones" id="observaciones" style="height: 214px; resize: none;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Grabar</button>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/talento_humano/js/vista_solicitud.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Descargos Solicitados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Descargos Ejecución</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="third-tab" data-bs-toggle="tab" href="#third" role="tab" aria-controls="third" aria-selected="false">Descargos Pendientes Respuesta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="quarter-tab" data-bs-toggle="tab" href="#quarter" role="tab" aria-controls="quarter" aria-selected="false">Todos Los Descargos</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <form class="panel panel-default" id="solicitud_descargos">
                        <center class="panel-heading">
                            <h2>Descargos Solicitados</h2>
                        </center>
                        <div class="panel-body">
                            <br>
                            <table id="tabla_descargos" class="display responsive nowrap table table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Solicitante descargo</th>
                                        <th>Colaborador</th>
                                        <th>Fecha Falla</th>
                                        <th>Hora Falla</th>
                                        <th>Reporta Soporte</th>
                                        <th>Turno Oficina</th>
                                        <th style="width: 100px">Falla Reportada</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <br>
                    </form>
                </div>
                <!-- Segundo link -->
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <br>
                    <form class="panel panel-default" id="form_codigo_producto">
                        <center class="panel-heading">
                            <h2>Descargos Ejecución</h2>
                        </center>
                        <div class="panel-body">
                            <br>
                            <table id="tabla_ejecucion" class="display responsive nowrap table table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Estado</th>
                                        <th>Fecha Citación</th>
                                        <th>Hora Citación</th>
                                        <th>Solicitante</th>
                                        <th>Colaborador</th>
                                        <th>Fecha Falla</th>
                                        <th>Hora Falla</th>
                                        <th>Falla Reportada</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Consultar</button>
                        </div>
                        <br>
                    </form>
                </div>
                <!-- Tercer link -->
                <div class="tab-pane fade" id="third" role="tabpanel" aria-labelledby="third-tab">
                    <br>
                    <form class="panel panel-default" id="form_codigo_producto">
                        <center class="panel-heading">
                            <h2>Pendientes Respuesta</h2>
                        </center>
                        <div class="panel-body">
                            <br>
                            <table id="tabla_pendientes" class="display responsive nowrap table table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Estado</th>
                                        <th>Fecha Ejecución</th>
                                        <th>Hora Ejecución</th>
                                        <th>Solicitante</th>
                                        <th>Colaborador</th>
                                        <th>Fecha Falla</th>
                                        <th>Hora Falla</th>
                                        <th>Falla Reportada</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Consultar</button>
                        </div>
                        <br>
                    </form>
                </div>
                <!-- Cuarto link -->
                <div class="tab-pane fade" id="quarter" role="tabpanel" aria-labelledby="quarter-tab">
                    <br>
                    <form class="panel panel-default" id="form_codigo_producto">
                        <center class="panel-heading">
                            <h2>Todos Los Descargos</h2>
                        </center>
                        <div class="panel-body">
                            <br>
                            <table id="tabla_todos_descargos" class="display responsive nowrap table table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Solicitante</th>
                                        <th>Colaborador</th>
                                        <th>Soporte Falla</th>
                                        <th>Fecha y Hora Falla</th>
                                        <th>Falla Reportada</th>
                                        <th>Fecha y Hora Citación</th>
                                        <th>Fecha y Hora Ejecución</th>
                                        <th>Fecha y Hora Respuesta</th>
                                        <th>Resumen Respuesta</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" method="post" id="editar-descargo" enctype="multipart/form-data">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="exampleModalLabel">Fecha Citación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-2" for="fecha_citacion">Fecha Citacion:</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="date" name="fecha_citacion" id="fecha_citacion" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2" for="hora_citacion">Hora Citación:</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="hora_citacion" id="hora_citacion" type="time" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary boton-x" formulario="">Modificar Datos</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reprogramar -->
<div class="modal fade" id="modalReprograma" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" method="post" id="reprograma-descargo" enctype="multipart/form-data">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="modalReprogramaLabel">Reprogramar Fecha Citación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-2" for="fecha_citacion">Reprogramar Fecha Citacion:</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="date" name="fecha_citacion" id="fecha_citacion" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2" for="hora_citacion">Reprogramar Hora Citación:</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="hora_citacion" id="hora_citacion" type="time" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary boton-y" formulario="">Modificar Datos</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Responder -->
<div class="modal fade" id="modalRespuesta" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" method="post" id="responder-descargo" enctype="multipart/form-data">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="modalRespuestaLabel">Reprogramar Fecha Citación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-2" for="fecha_respuesta">Fecha Respuesta:</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="date" name="fecha_respuesta" id="fecha_respuesta" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2" for="hora_respuesta">Hora Respuesta:</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="hora_respuesta" id="hora_respuesta" type="time" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2" for="resumen_respuesta">Resumen Respuesta:</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="resumen_respuesta" id="resumen_respuesta" style="height: 200px; resize: none;"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary boton-z" formulario="">Modificar Datos</button>
            </div>
        </form>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/talento_humano/js/vista_descargos.js"></script>
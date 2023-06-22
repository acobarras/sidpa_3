<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Personal Solicitados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Personal En Proceso de Selección</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="entrevista-tab" data-bs-toggle="tab" href="#entrevista" role="tab" aria-controls="entrevista" aria-selected="false">Personal En Entrevista</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pruebas-tab" data-bs-toggle="tab" href="#pruebas" role="tab" aria-controls="pruebas" aria-selected="false">Personal En Pruebas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contratacion-tab" data-bs-toggle="tab" href="#contratacion" role="tab" aria-controls="contratacion" aria-selected="false">Personal En Contratación</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="final-tab" data-bs-toggle="tab" href="#final" role="tab" aria-controls="final" aria-selected="false">Final Solicitudes</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <form class="panel panel-default">
                        <center class="panel-heading">
                            <h2>Personal Solicitados</h2>
                        </center>
                        <div class="panel-body">
                            <br>
                            <table id="tabla_personal" class="display responsive nowrap table table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Fecha</th>
                                        <th>Solicitante Personal</th>
                                        <th>Cargo Solicitado</th>
                                        <th>Cantidad Vacantes</th>
                                        <th>Observaciones</th>
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
                            <h2>Personal En Proceso de Selección</h2>
                        </center>
                        <div class="panel-body">
                            <br>
                            <table id="tabla_proceso_seleccion" class="display responsive nowrap table table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Solicitante Personal</th>
                                        <th>Cargo Solicitado</th>
                                        <th>Cantidad Vacantes</th>
                                        <th>Observaciones</th>
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
                <div class="tab-pane fade" id="entrevista" role="tabpanel" aria-labelledby="entrevista-tab">
                    <br>
                    <form class="panel panel-default" id="form_codigo_producto">
                        <center class="panel-heading">
                            <h2>Personal En Entrevista</h2>
                        </center>
                        <div class="panel-body">
                            <br>
                            <table id="tabla_entrevista" class="display responsive nowrap table table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Solicitante Personal</th>
                                        <th>Cargo Solicitado</th>
                                        <th>Cantidad Vacantes</th>
                                        <th>Observaciones</th>
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
                <div class="tab-pane fade" id="pruebas" role="tabpanel" aria-labelledby="pruebas-tab">
                    <br>
                    <form class="panel panel-default" id="form_codigo_producto">
                        <center class="panel-heading">
                            <h2>Personal En Pruebas</h2>
                        </center>
                        <div class="panel-body">
                            <br>
                            <table id="tabla_pruebas" class="display responsive nowrap table table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Solicitante Personal</th>
                                        <th>Cargo Solicitado</th>
                                        <th>Cantidad Vacantes</th>
                                        <th>Observaciones</th>
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
                <!-- Quinto link -->
                <div class="tab-pane fade" id="contratacion" role="tabpanel" aria-labelledby="contratacion-tab">
                    <br>
                    <form class="panel panel-default" id="form_codigo_producto">
                        <center class="panel-heading">
                            <h2>Personal En Contratación</h2>
                        </center>
                        <div class="panel-body">
                            <br>
                            <table id="tabla_contratacion" class="display responsive nowrap table table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Solicitante Personal</th>
                                        <th>Cargo Solicitado</th>
                                        <th>Cantidad Vacantes</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <!-- Sexto link -->
                <div class="tab-pane fade" id="final" role="tabpanel" aria-labelledby="final-tab">
                    <br>
                    <form class="panel panel-default" id="form_codigo_producto">
                        <center class="panel-heading">
                            <h2>Personal En Contratación</h2>
                        </center>
                        <div class="panel-body">
                            <br>
                            <table id="tabla_final" class="display responsive nowrap table table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Solicitante Personal</th>
                                        <th>Cargo Solicitado</th>
                                        <th>Cantidad Vacantes</th>
                                        <th>Observaciones</th>
                                        <th>Fecha Publicación</th>
                                        <th>Cantidad Postulados</th>
                                        <th>Cantidad Entrevista</th>
                                        <th>Cantidad Descartados</th>
                                        <th>Cantidad Entrevistas Ejecutadas</th>
                                        <th>Cantidad Entrevistas Ausentes</th>
                                        <th>Cantidad Preseleccionados</th>
                                        <th>Convocados Pruebas</th>
                                        <th>Asisten Psicotecnicas</th>
                                        <th>Asistentes Pruebas Medicas</th>
                                        <th>Candidatos Aptos</th>
                                        <th>Candidatos Contratados</th>
                                        <th>Fecha Contratación</th>
                                        <th>Fecha Inducción</th>
                                        <th>Fecha Inicio Labores</th>
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
        <form class="modal-content" method="post" id="editar-fecha-publicacion" enctype="multipart/form-data">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="exampleModalLabel">Fecha Publicación Vacante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-2" for="fecha_publicacion">Fecha Publicacion:</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="date" name="fecha_publicacion" id="fecha_publicacion" />
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

<!-- Modal Proseso de seleccion -->
<div class="modal fade" id="procesoSeleccion" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" method="post" id="editar-proceso-seleccion" enctype="multipart/form-data">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="procesoSeleccionLabel">En Proceso Selección</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-2" for="can_postulados">Cantidad Postulados:</label>
                    <div class="col-sm-10">
                        <input class="form-control calculo" type="text" name="can_postulados" id="can_postulados" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2" for="can_entrevista">Convocados a Entrevista:</label>
                    <div class="col-sm-10">
                        <input class="form-control calculo" type="text" name="can_entrevista" id="can_entrevista" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2" for="can_descartados">Cantidad Descartados:</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" name="can_descartados" id="can_descartados" readonly />
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

<!-- Modal Entrevista -->
<div class="modal fade" id="procesoEntrevista" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" method="post" id="editar-proceso-entrevista" enctype="multipart/form-data">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="procesoEntrevistaLabel">Entrevistados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-3" for="can_ent_ejecu">Cantidad Entrevistas Ejecutadas:</label>
                    <div class="col-sm-9">
                        <input class="form-control calculo" type="text" name="can_ent_ejecu" id="can_ent_ejecu" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3" for="can_ausentes">Cantidad Ausentes:</label>
                    <div class="col-sm-9">
                        <input class="form-control calculo" type="text" name="can_ausentes" id="can_ausentes" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3" for="can_preselec">Cantidad Preseleccionados:</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="can_preselec" id="can_preselec" />
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

<!-- Modal Pruebas -->
<div class="modal fade" id="procesoPruebas" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" method="post" id="editar-proceso-pruebas" enctype="multipart/form-data">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="procesoPruebasLabel">Pruebas Ejecutadas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-3" for="can_conv_prueba">Cantidad Convocados a Pruebas:</label>
                    <div class="col-sm-9">
                        <input class="form-control calculo" type="text" name="can_conv_prueba" id="can_conv_prueba" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3" for="can_asist_psico">Cantidad Asistentes a psicotecnicas:</label>
                    <div class="col-sm-9">
                        <input class="form-control calculo" type="text" name="can_asist_psico" id="can_asist_psico" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3" for="can_prueb_medic">Cantidad Asistentes a pruebas medicas:</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="can_prueb_medic" id="can_prueb_medic" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3" for="can_aptos">Cantidad Candidatos aptos:</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="can_aptos" id="can_aptos" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary boton-a" formulario="">Modificar Datos</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Contratacion -->
<div class="modal fade" id="procesoContratacion" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" method="post" id="editar-proceso-contratacion" enctype="multipart/form-data">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="procesoContratacionLabel">Personas En Contratación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-3" for="can_contratados">Candidatos contratados:</label>
                    <div class="col-sm-9">
                        <input class="form-control calculo" type="text" name="can_contratados" id="can_contratados" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3" for="fecha_contratacion">Fecha de contratación:</label>
                    <div class="col-sm-9">
                        <input class="form-control calculo" type="date" name="fecha_contratacion" id="fecha_contratacion" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3" for="fecha_induccion">Fecha inducción:</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="date" name="fecha_induccion" id="fecha_induccion" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3" for="fecha_inicio_labores">Fecha inicio labores:</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="date" name="fecha_inicio_labores" id="fecha_inicio_labores" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary boton-b" formulario="">Modificar Datos</button>
            </div>
        </form>
    </div>
</div>


<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/talento_humano/js/vista_personal.js"></script>
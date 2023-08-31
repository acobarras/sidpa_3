<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Personas</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="departamento-tab" data-bs-toggle="tab" href="#departamento" role="tab" aria-controls="departamento" aria-selected="false">Nueva Persona</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <center>
                        <h2>Tabla de Personas</h2>
                    </center>
                    <div>
                        <table id="tabla_personas" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead class="text-center" style="background: #002b5f;color: white">
                                <tr>
                                    <th>#</th>
                                    <th>Documento</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Estado Imagen</th>
                                    <th>Fecha Nacimiento</th>
                                    <th>Dirección</th>
                                    <th>Barrio</th>
                                    <th>Celular</th>
                                    <th>Correo</th>
                                    <th>Estado</th>
                                    <th>Opción</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade" id="departamento" role="tabpanel" aria-labelledby="departamento-tab">
                    <form class="container" id="form_crear_persona" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Crear Persona</h1>
                        </div>
                        <div class="mb-3 col-12 row cuadro_imagenes">
                            <div class="image-upload">
                                <label for="foto_persona" style="display:block;">
                                    <spam id="imagen_persona"><i class="fas fa-camera camara"></i></spam>
                                </label>
                                <input class="d-none" id="foto_persona" type="file" name="foto_persona" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="nombres" class="form-label">Nombres : </label>
                                <input type="text" class="form-control" name="nombres" id="nombres">
                            </div>
                            <div class="mb-3 col-6">
                                <label for="apellidos" class="form-label">Apellidos : </label>
                                <input type="text" class="form-control" name="apellidos" id="apellidos">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="id_tipo_documento" class="form-label">Tipo Documento : </label>
                                <select class="form-control id_tipo_documento" style="width: 100%;" name="id_tipo_documento" id="id_tipo_documento">
                                    <option value="0"></option>
                                    <?php foreach ($tipo_documento as $doc) { ?>
                                        <option value="<?= $doc->id_tipo_documento ?>"><?= ($doc->nombre_documento) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="num_documento" class="form-label">Numero Documento : </label>
                                <input type="text" class="form-control" name="num_documento" id="num_documento">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-4">
                                <label for="id_pais" class="form-label">Pais : </label>
                                <select class="form-control id_pais" style="width: 100%;" name="id_pais" id="id_pais">
                                    <option value="0"></option>
                                    <?php foreach ($pais as $pa) { ?>
                                        <option value="<?= $pa->id_pais ?>"><?= ($pa->nombre) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-4">
                                <label for="id_departamento" class="form-label">Departamento : </label>
                                <select class="form-control id_departamento" style="width: 100%;" name="id_departamento" id="id_departamento">
                                    <option value="0"></option>
                                    <?php foreach ($departamento as $dep) { ?>
                                        <option value="<?= $dep->id_departamento ?>"><?= ($dep->nombre) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-4">
                                <label for="id_ciudad" class="form-label">Ciudad : </label>
                                <select class="form-control id_ciudad" style="width: 100%;" name="id_ciudad" id="id_ciudad">
                                    <option value="0"></option>
                                    <?php foreach ($ciudad as $ciu) { ?>
                                        <option value="<?= $ciu->id_ciudad ?>"><?= ($ciu->nombre) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="direccion" class="form-label">Dirección : </label>
                                <input type="text" class="form-control" name="direccion" id="direccion">
                            </div>
                            <div class="mb-3 col-6">
                                <label for="barrio" class="form-label">Barrio : </label>
                                <input type="text" class="form-control" name="barrio" id="barrio">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="fecha_nacimiento" class="form-label">Fecha Nacimiento : </label>
                                <input type="text" class="form-control datepicker" name="fecha_nacimiento" id="fecha_nacimiento">
                            </div>
                            <div class="mb-3 col-6">
                                <label for="correo" class="form-label">Correo : </label>
                                <input type="text" class="form-control" name="correo" id="correo">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-4">
                                <label for="celular" class="form-label">Celular : </label>
                                <input type="text" class="form-control" name="celular" id="celular">
                            </div>
                            <div class="mb-3 col-4">
                                <label for="id_area_trabajo" class="form-label">Area : </label>
                                <select class="form-control select_2" style="width: 100%;" name="id_area_trabajo" id="id_area_trabajo">
                                    <option value="0"></option>
                                    <?php foreach ($area as $areas) { ?>
                                        <option value="<?= $areas->id_area_trabajo ?>"><?= $areas->nombre_area_trabajo ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-4">
                                <label for="tipo" class="form-label">Tipo Usuario : </label>
                                <select class="form-control tipo_usuario" style="width: 100%;" name="tipo" id="tipo">
                                    <?php foreach (TIPO_USUARIO as $key => $tipo) { ?>
                                        <option value="<?= $key ?>"><?= $tipo ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="id_jefe_imediato" class="form-label">Jefe Imediato : </label>
                                <select class="form-control jefe_imediato" style="width: 100%;" name="id_jefe_imediato" id="id_jefe_imediato">
                                    <option value="0"></option>
                                    <?php foreach ($jefeImediato as $jefe) { ?>
                                        <option value="<?= $jefe->id_persona ?>"><?= $jefe->nombres . " " . $jefe->apellidos ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-6">
                                <!-- <label for="tipo" class="form-label">Tipo Usuario : </label> -->
                                <!-- <input type="text" class="form-control" name="tipo" id="tipo"> -->
                            </div>
                        </div>
                        <div class="mb-3 ">
                            <div class="position-relative start-50">
                                <button class="btn btn-primary" type="submit" id="crear_persona">
                                    <i class="fa fa-plus-circle"></i> Crear Persona
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar persona -->

<div class="modal fade" id="ModalPersona" aria-labelledby="ModalPersonaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_editar_persona">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalPersonaLabel">Modificar Persona</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <h1 class="col-md-12 col-md-offset-4 ">Modificar Persona</h1>
                </div>
                <div class="mb-3 col-12 row cuadro_imagenes">
                    <div class="image-upload">
                        <label for="foto_persona_modifi" style="display:block;">
                            <spam id="imagen_persona_modifi"><i class="fas fa-camera camara"></i></spam>
                        </label>
                        <input class="d-none" id="foto_persona_modifi" type="file" name="foto_persona" />
                    </div>
                </div>
                <input type="hidden" name="id_persona" id="id_persona_modifi"></input>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="nombres_modifi" class="form-label">Nombres : </label>
                        <input type="text" class="form-control" name="nombres" id="nombres_modifi">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="apellidos_modifi" class="form-label">Apellidos : </label>
                        <input type="text" class="form-control" name="apellidos" id="apellidos_modifi">
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="id_tipo_documento_modifi" class="form-label">Tipo Documento : </label>
                        <select class="form-control id_tipo_documento" style="width: 100%;" name="id_tipo_documento" id="id_tipo_documento_modifi">
                            <option value="0"></option>
                            <?php foreach ($tipo_documento as $doc) { ?>
                                <option value="<?= $doc->id_tipo_documento ?>"><?= ($doc->nombre_documento) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="num_documento_modifi" class="form-label">Numero Documento : </label>
                        <input type="text" class="form-control" name="num_documento" id="num_documento_modifi">
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-4">
                        <label for="id_pais_modifi" class="form-label">Pais : </label>
                        <select class="form-control id_pais" style="width: 100%;" name="id_pais" id="id_pais_modifi">
                            <option value="0"></option>
                            <?php foreach ($pais as $pa) { ?>
                                <option value="<?= $pa->id_pais ?>"><?= ($pa->nombre) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3 col-4">
                        <label for="id_departamento_modifi" class="form-label">Departamento : </label>
                        <select class="form-control id_departamento" style="width: 100%;" name="id_departamento" id="id_departamento_modifi">
                            <option value="0"></option>
                            <?php foreach ($departamento as $dep) { ?>
                                <option value="<?= $dep->id_departamento ?>"><?= ($dep->nombre) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3 col-4">
                        <label for="id_ciudad_modifi" class="form-label">Ciudad : </label>
                        <select class="form-control id_ciudad" style="width: 100%;" name="id_ciudad" id="id_ciudad_modifi">
                            <option value="0"></option>
                            <?php foreach ($ciudad as $ciu) { ?>
                                <option value="<?= $ciu->id_ciudad ?>"><?= ($ciu->nombre) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="direccion_modifi" class="form-label">Dirección : </label>
                        <input type="text" class="form-control" name="direccion" id="direccion_modifi">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="barrio_modifi" class="form-label">Barrio : </label>
                        <input type="text" class="form-control" name="barrio" id="barrio_modifi">
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="fecha_nacimiento_modifi" class="form-label">Fecha Nacimiento : </label>
                        <input type="text" class="form-control datepicker" name="fecha_nacimiento" id="fecha_nacimiento_modifi">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="correo_modifi" class="form-label">Correo : </label>
                        <input type="text" class="form-control" name="correo" id="correo_modifi">
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="celular_modifi" class="form-label">Celular : </label>
                        <input type="text" class="form-control" name="celular" id="celular_modifi">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="tipo_modifi" class="form-label">Tipo Usuario : </label>
                        <select class="form-control tipo_usuario" style="width: 100%;" name="tipo" id="tipo_modifi">
                            <?php foreach (TIPO_USUARIO as $key => $tipo) { ?>
                                <option value="<?= $key ?>"><?= $tipo ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="id_jefe_imediato_modifi" class="form-label">Jefe Imediato : </label>
                        <select class="form-control jefe_imediato" style="width: 100%;" name="id_jefe_imediato" id="id_jefe_imediato_modifi">
                            <option value="0"></option>
                            <?php foreach ($jefeImediato as $jefe) { ?>
                                <option value="<?= $jefe->id_persona ?>"><?= $jefe->nombres . " " . $jefe->apellidos ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="estado_modifi" class="form-label">Estado : </label>
                        <select class="form-select" style="width: 100%;" name="estado" id="estado_modifi">
                            <?php foreach (ESTADO_SIMPLE as $key => $estado) { ?>
                                <option value="<?= $key ?>"><?= $estado ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="id_area_trabajo_modifi" class="form-label">Area : </label>
                        <select class="form-control select_2" style="width: 100%;" name="id_area_trabajo" id="id_area_trabajo_modifi">
                            <option value="0"></option>
                            <?php foreach ($area as $areas) { ?>
                                <option value="<?= $areas->id_area_trabajo ?>"><?= $areas->nombre_area_trabajo ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="comite_modifi" class="form-label">Comite : </label>
                        <select class="form-control select_2" multiple="multiple" style="width: 100%;" name="comite[]" id="comite_modifi">
                            <option value="0"></option>
                            <?php foreach (COMITE as $key => $comite) { ?>
                                <option value="<?= $key ?>"><?= $comite ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary" id="modificar_persona" data-id="">Modificar</button>
            </div>
        </form>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_crear_persona.js"></script>
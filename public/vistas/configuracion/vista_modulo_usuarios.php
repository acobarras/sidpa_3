<div id="principal" class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Usuarios</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="one-tab" data-bs-toggle="tab" href="#one" role="tab" aria-controls="one" aria-selected="false">Nuevo Usuario</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <center>
                        <h2>Tabla de Usuarios</h2>
                    </center>
                    <div>
                        <table id="tabla_usuarios" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead class="text-center" style="background: #002b5f;color: white">
                                <th>#</th>
                                <th>Usuario</th>
                                <th>Roll de Usuario</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>N. Documento</th>
                                <th>Estado</th>
                                <th>Permisos</th>
                                <th>Eliminar</th>
                                <th>Modificar</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade" id="one" role="tabpanel" aria-labelledby="one-tab">
                    <form class="container" id="form_crear_usuario" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Crear Usuario </h1>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="id_roll" class="form-label">Roll : </label>
                                <select class="form-control id_roll" style="width: 100%;" name="id_roll" id="id_roll">
                                    <option value="0"></option>
                                    <?php foreach ($r as $roll) { ?>
                                        <option value="<?= $roll->id_roll ?>"><?= $roll->nombre_roll ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="id_persona" class="form-label">Persona : </label>
                                <select class="form-control id_persona" style="width: 100%;" name="id_persona" id="id_persona">
                                    <option value="0"></option>
                                    <?php foreach ($p as $persona) { ?>
                                        <option value="<?= $persona->id_persona ?>"><?= $persona->nombres . ' ' . $persona->apellidos ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="usuario" class="form-label">Nombre Usuario : </label>
                                <input type="text" class="form-control" name="usuario" id="usuario" />
                            </div>
                            <div class="mb-3 col-6">
                                <label for="pasword_crea" class="form-label">Clave : </label>
                                <input type="text" class="form-control" name="pasword" id="pasword_crea" value="Acobarras2019">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="nombre" class="form-label">Nombre : </label>
                                <input type="text" class="form-control" name="nombre" id="nombre" />
                            </div>
                            <div class="mb-3 col-6">
                                <label for="apellido" class="form-label">Apellido : </label>
                                <input type="text" class="form-control" name="apellido" id="apellido" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-4">
                                <label for="res_prioridad" class="form-label">Responde Prioridades : </label>
                                <select class="form-control select_2" style="width: 100%;" name="res_prioridad" id="res_prioridad">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                            <div class="mb-3 col-4">
                                <label for="ruta_foto" class="form-label">Foto : </label>
                                <input type="file" class="form-control" name="ruta_foto" id="ruta_foto" />
                            </div>
                            <div class="mb-3 col-4">
                                <label for="fecha_caduca" class="form-label">Fecha Fin : </label>
                                <input type="text" class="form-control datepicker" name="fecha_caduca" id="fecha_caduca" />
                            </div>
                        </div>
                        <div class="mb-3 ">
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit" id="crear_persona">
                                    <i class="fa fa-plus-circle"></i> Crear Usuario
                                </button>
                            </div>
                        </div>
                    </form>
                    <br><br><br><br><br>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Permisos del usuario -->

<div class="container-fluid mt-3 mb-3" id="permisos_usuario" style="display: none;">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">

            <br>
            <div>
                <button class="btn btn-primary" id="regresar">Regresar</button>
            </div>
            <br>
            <div>
                <center>
                    <h2 id="titulo_permisos"></h2>
                </center>
                <div>
                    <table id="tabla_permisos_usuario" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                        <thead class="thead-dark">
                            <tr>
                                <th>Modulo hoja</th>
                                <th>Permiso Modulo</th>
                                <th>Imagen</th>
                                <th>Estado</th>
                                <th>Opcion</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active link_carga" id="inicio-tab" data-bs-toggle="tab" data-bs-target="#inicio" role="tab" aria-controls="inicio" aria-selected="true" data-value="inicio">Inicio</a>
                    </li>
                    <?php foreach ($lista_inicio as $value) { ?>
                        <li class="nav-item">
                            <a class="nav-link link_carga" id="<?= $value->referencia_nombre ?>1-tab" data-bs-toggle="tab" data-bs-target="#<?= $value->referencia_nombre ?>" role="tab" aria-controls="<?= $value->referencia_nombre ?>1" aria-selected="false" data-value="<?= $value->referencia_nombre ?>"><?= $value->titulo ?></a>
                        </li>
                    <?php } ?>
                </ul>
                <br>
                <div class="tab-content">
                    <table id="tabla_modulo" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nombre de la hoja</th>
                                <th>Titulo Elemento</th>
                                <th>imagen</th>
                                <th>Permiso</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <!-- <div class="tab-pane fade show active" id="inicio" role="tabpanel" aria-labelledby="inicio-tab">
				    <h1>Hola Inicio</h1>
			    </div> -->
                    <?php foreach ($lista_inicio as $respu) { ?>
                        <div class="tab-pane fade show active" id="<?= $respu->referencia_nombre ?>1" role="tabpanel" aria-labelledby="<?= $respu->referencia_nombre ?>1-tab">
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para modificar el usuario -->

<div class="modal fade" id="ModalUsuario" tabindex="-1" aria-labelledby="ModalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_editar_usuario">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalUsuarioLabel">Modificar Persona</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <center>
                        <h3 class="col-md-12 col-md-offset-4 ">Modificar Persona</h3>
                    </center>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="foto" class="col-form-label fw-bold">Foto :</label>
                        <span id="ruta_foto"> </span>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="tipo_clave_modifi" class="form-label fw-bold">Opciones de contraseña : </label>
                        <select class="form-control" style="width: 100%;" name="tipo_clave" id="tipo_clave_modifi">
                            <option value="0">N/A</option>
                            <option value="1">Actualizar clave al iniciar Sesión</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="usuario_modifi" class="col-form-label fw-bold">Usuario:</label>
                    <input type="text" class="form-control" name="usuario" id="usuario_modifi">
                </div>
                <div class="mb-3">
                    <label for="nombre_modifi" class="col-form-label fw-bold">Nombre :</label>
                    <input type="text" class="form-control" name="nombre" id="nombre_modifi">
                </div>
                <div class="mb-3">
                    <label for="apellido_modifi" class="col-form-label fw-bold">Apellido :</label>
                    <input type="text" class="form-control" name="apellido" id="apellido_modifi">
                </div>
                <div class="mb-3">
                    <label for="nuevo_pasword_modifi" class="col-form-label fw-bold">Password :</label>
                    <input type="password" class="form-control" name="nuevo_pasword" id="nuevo_pasword_modifi">
                    <input type="password" hidden name="pasword" id="pasword_modifi">
                </div>
                <div class="mb-3">
                    <label for="id_roll_modifi" class="col-form-label fw-bold">Roll :</label>
                    <select class="form-control id_roll" style="width: 100%;" name="id_roll" id="id_roll_modifi">
                        <?php foreach ($r as $roll) { ?>
                            <option value="<?= $roll->id_roll ?>"><?= $roll->nombre_roll ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="res_prioridad_modifi" class="form-label">Responde Prioridades : </label>
                    <select class="form-control select_2" style="width: 100%;" name="res_prioridad" id="res_prioridad_modifi">
                        <option value="0">No</option>
                        <option value="1">Si</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_persona_modifi" class="col-form-label fw-bold">Persona vinculada a este usuario:</label>
                    <select class="form-control id_persona" style="width: 100%;" name="id_persona" id="id_persona_modifi">
                        <?php foreach ($p as $persona) { ?>
                            <option value="<?= $persona->id_persona ?>"><?= $persona->nombres . ' ' . $persona->apellidos ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary" id="modificar_usuario" data-id="">Modificar</button>
            </div>
        </form>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_modulo_usuarios.js"></script>
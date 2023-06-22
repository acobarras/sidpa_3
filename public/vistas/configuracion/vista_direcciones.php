<input type="hidden" value="<?= $_SESSION['usuario']->getId_roll() ?>" id="roll" />
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <div class="mx-3 mt-2">
                <div class="text-center">
                    <h2>Tabla Direcciones</h2>
                </div>
                <br>
                <table id="tabla_direcciones" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                    <thead style="background: #002b5f;color: white">
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Asesor</th>
                            <th>Departamento</th>
                            <th>Ciudad</th>
                            <th>Dirección</th>
                            <th>Télefono</th>
                            <th>Celular</th>
                            <th>Contacto</th>
                            <th>Ruta</th>
                            <th>estado</th>
                            <th>Opcion</th>
                            <th>Opcion</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar Todo el formulario de la direccion -->

<div class="modal fade" id="ModalDireccion1" aria-labelledby="ModalDireccion1Label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modificar_direccion1">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="ModalDireccion1Label">Modificar Dirección</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h1 class="col-md-12 col-md-offset-4 ">Modificar Dirección</h1>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="id_cli_prov_modifi" class="form-label">Nombre Cliente : </label>
                        <select class="form-control select_2" style="width: 100%;" name="id_cli_prov" id="id_cli_prov_modifi">
                            <?php foreach ($client as $clients) { ?>
                                <option value="<?= $clients->id_cli_prov ?>"><?= $clients->nombre_empresa ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="direccion_modifi" class="form-label">Dirección : </label>
                        <input autocomplete="off" type="text" class="form-control" name="direccion" id="direccion_modifi" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="id_pais_modifi" class="form-label">País : </label>
                        <select class="form-control select_2" style="width: 100%;" name="id_pais" id="id_pais_modifi">
                            <?php foreach ($paises as $pais) { ?>
                                <option value="<?= $pais->id_pais ?>"><?= $pais->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="id_departamento_modifi" class="form-label">Departamento : </label>
                        <select class="form-control select_2" style="width: 100%;" name="id_departamento" id="id_departamento_modifi">
                            <?php foreach ($departamento as $dep) { ?>
                                <option value="<?= $dep->id_departamento ?>"><?= $dep->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="id_ciudad_modifi" class="form-label">Ciudad : </label>
                        <select class="form-control select_2" style="width: 100%;" name="id_ciudad" id="id_ciudad_modifi">
                            <?php foreach ($ciud as $ciudad) { ?>
                                <option value="<?= $ciudad->id_ciudad ?>"><?= $ciudad->nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3 col-6">
                        <label for="telefono_modifi" class="form-label">Télefono : </label>
                        <input autocomplete="off" type="text" class="form-control" name="telefono" id="telefono_modifi" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="celular_modifi" class="form-label">Celular : </label>
                        <input autocomplete="off" type="text" class="form-control" name="celular" id="celular_modifi">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="email_modifi" class="form-label">Email : </label>
                        <input autocomplete="of" type="text" class="form-control" name="email" id="email_modifi" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="contacto_modifi" class="form-label">Contacto : </label>
                        <input autocomplete="off" type="text" class="form-control" name="contacto" id="contacto_modifi">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="cargo_modifi" class="form-label">Cargo : </label>
                        <input autocomplete="of" type="text" class="form-control" name="cargo" id="cargo_modifi" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="mb-3 col-6">
                        <label for="horario_modifi" class="form-label">Horario Atención : </label>
                        <input autocomplete="off" type="text" class="form-control" name="horario" id="horario_modifi">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="ruta_modifi" class="form-label">Ruta : </label>
                        <select class="form-control select_2" style="width: 100%;" name="ruta" id="ruta_modifi">
                            <?php foreach (RUTA_ENTREGA as $key => $ruta) { ?>
                                <option value="<?= $key ?>"><?= $ruta ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary" id="modificar_direccion1" data-id="">Modificar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para editar solo la ruta de la direccion -->

<div class="modal fade" id="ModalDireccion2" aria-labelledby="ModalDireccion2Label" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modificar_direccion2">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="ModalDireccion2Label">Modificar Ruta Dirección</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h1 class="col-md-12 col-md-offset-4 ">Modificar Ruta Dirección</h1>
                </div>
                <div class="mb-3">
                    <label for="cliente" class="form-label">Cliente : </label>
                    <span id="cliente"></span>
                </div>
                <div class="mb-3">
                    <label for="nombre_pais" class="form-label">Pais : </label>
                    <span id="nombre_pais"></span>
                </div>
                <div class="mb-3">
                    <label for="nombre_departamento" class="form-label">Departamento : </label>
                    <span id="nombre_departamento"></span>
                </div>
                <div class="mb-3">
                    <label for="nombre_ciudad" class="form-label">Ciudad : </label>
                    <span id="nombre_ciudad"></span>
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección : </label>
                    <span id="direccion"></span>
                </div>
                <div class="mb-3">
                    <label for="ruta_actual" class="form-label">Ruta Antigua : </label>
                    <span id="ruta_actual"></span>
                </div>
                <div class="mb-3">
                    <label for="ruta" class="form-label">Elija la Nueva Ruta : </label>
                    <select class="form-control select_2" style="width: 100%;" id="ruta" name="ruta">
                        <?php foreach (RUTA_ENTREGA as $key => $value) { ?>
                            <option value="<?= $key ?>"><?= $value ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary" id="modificar_direccion2" data-id="">Modificar</button>
            </div>
        </form>
    </div>
</div>


<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_direcciones.js"></script>
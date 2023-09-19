<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Creacion Vehiculos</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <form class="container" id="form_crear_vehiculo" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <br>
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 text-center">Creación de Vehiculos</h1>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-md-4 col-12">
                                <label for="id_usuario" class="form-label">Propietario : </label>
                                <select class="form-control select_2" style="width: 100%;" id="id_usuario" name="id_usuario">
                                    <option value="0"></option>
                                    <?php foreach ($transportadores as $transportador) { ?>
                                        <option value="<?= $transportador->id_usuario ?>"><?= $transportador->nombre ?> <?= $transportador->apellido ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-md-4 col-12">
                                <label for="placa" class="form-label">Placa : </label>
                                <input autocomplete="off" type="text" class="form-control" name="placa" id="placa" />
                            </div>
                            <div class="mb-3 col-md-4 col-12">
                                <label for="marca" class="form-label">Marca : </label>
                                <input autocomplete="off" type="text" class="form-control" id="marca" name="marca" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-md-4 col-12">
                                <label for="linea" class="form-label">Linea : </label>
                                <input autocomplete="off" type="text" class="form-control" id="linea" name="linea" />
                            </div>
                            <div class="mb-3 col-md-4 col-12">
                                <label for="modelo" class="form-label">Modelo : </label>
                                <input autocomplete="off" type="text" class="form-control" id="modelo" name="modelo" />
                            </div>
                            <div class="mb-3 col-md-4 col-12">
                                <label for="color" class="form-label">Color : </label>
                                <input autocomplete="off" type="text" class="form-control" id="color" name="color" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-md-4 col-12">
                                <label for="servicio" class="form-label">Servicio : </label>
                                <select class="form-control select_2" style="width: 100%;" id="servicio" name="servicio">
                                    <option value="0">Elija una Opción</option>
                                    <option value="1">Particular</option>
                                    <option value="2">Publico</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-4 col-12">
                                <label for="clase_vehiculo" class="form-label">Clase Vehiculo : </label>
                                <input autocomplete="off" type="text" class="form-control" name="clase_vehiculo" id="clase_vehiculo" />
                            </div>
                            <div class="mb-3 col-md-4 col-12">
                                <label for="carroceria" class="form-label">Tipo Carroceria : </label>
                                <input autocomplete="off" type="text" class="form-control" name="carroceria" id="carroceria" />
                            </div>
                        </div>
                        <div class="mb-3 col-md-4 col-12">
                            <label for="capacidad" class="form-label">Capacidad : Ej 2Kg o 2PSJ </label>
                            <input autocomplete="off" type="text" class="form-control" name="capacidad" id="capacidad" />
                        </div>
                        <div class="mb-3 ">
                            <div class="text-center">
                                <button class="btn btn-success" type="submit" id="crear_vehiculo">
                                    <i class="fa fa-plus-circle"></i> Crear Vehiculo
                                </button>
                            </div>
                        </div>
                        <br>
                    </form>
                    <br>
                    <table id="tabla_vehiculos" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                        <thead style="background: #002b5f;color: white">
                            <th>Propietario</th>
                            <td>Placa</td>
                            <td>Marca</td>
                            <td>Linea</td>
                            <td>Modelo</td>
                            <td>Color</td>
                            <td>Servicio</td>
                            <td>Clase Vehiculo</td>
                            <td>Carroceria</td>
                            <td>Capacidad</td>
                            <td>Opción</td>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PARA EDITAR PROPIETARIO VEHICULO -->

<div class="modal fade" id="modal_vehiculo" aria-labelledby="modal_vehiculoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modifica_vehiculo">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="modal_vehiculoLabel">Modificar Vehiculo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 col-12">
                    <label for="id_usuarioM" class="form-label">Propietario : </label>
                    <select class="form-control select_2" style="width: 100%;" id="id_usuarioM" name="id_usuario">
                        <option value="0"></option>
                        <?php foreach ($transportadores as $transportador) { ?>
                            <option value="<?= $transportador->id_usuario ?>"><?= $transportador->nombre ?> <?= $transportador->apellido ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="button" class="btn btn-primary" id="modificar_vehiculo" data-id="">Modificar</button>
            </div>
        </form>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/crear_vehiculo.js"></script>
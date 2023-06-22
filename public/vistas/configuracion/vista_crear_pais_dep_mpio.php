<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">País</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="departamento-tab" data-bs-toggle="tab" href="#departamento" role="tab" aria-controls="departamento" aria-selected="false">Departamento</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="municipio-tab" data-bs-toggle="tab" href="#municipio" role="tab" aria-controls="municipio" aria-selected="false">Ciudad o Municipio</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br />
                    <form id="form_crear_pais" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Ingreso País</h1>
                        </div>
                        <div class="mb-3 row">
                            <label for="codigo" class="col-sm-2 col-form-label">Codigo País:</label>
                            <div class="col-sm-10">
                                <input class="form-control col-8 codigo-validar" type="text" name="codigo" id="codigo" placeholder="Max 4 Dijitos Ejm 0001" maxlength="4" />
                                <span id="mensaje1"></span>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nombre" class="col-sm-2 col-form-label">Nombre Del Pais:</label>
                            <div class="col-sm-10">
                                <input class="form-control col-8 " type="text" id="nombre" name="nombre">
                                <span id="mensaje2"></span>

                            </div>
                        </div>
                        <div class="mb-3 ">
                            <div class="position-relative start-50">
                                <button class="btn btn-primary  " type="submit" id="crear_pais_boton">
                                    <i class="fa fa-plus-circle"></i> Crear Pais
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div>
                        <table id="tabla_pais" class="table table-bordered table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <tr>
                                    <th>id país</th>
                                    <th>código</th>
                                    <th>nombre país</th>
                                    <th>opción</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="espacio_pais"></div>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade" id="departamento" role="tabpanel" aria-labelledby="departamento-tab">
                    <form id="form_crear_departamento" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Ingreso Departamento</h1>
                        </div>
                        <div class="mb-3 row">
                            <label for="id_pais_form2" class="col-sm-2 col-form-label">País:</label>
                            <div class="col-sm-10">
                                <select class="form-control col-8" style="width: 100%;" type="text" name="id_pais_form2" id="id_pais_form2">
                                    <option value="0"></option>
                                    <?php foreach ($pais as $paises) { ?>
                                        <option value="<?= $paises->id_pais ?>"><?= $paises->nombre ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nombre_departamento" class="col-sm-2 col-form-label">Nombre Del Departamento:</label>
                            <div class="col-sm-10">
                                <input class="form-control col-8 " type="text" id="nombre_departamento" name="nombre_departamento">
                                <span id="mensaje2"></span>

                            </div>
                        </div>
                        <div class="mb-3 ">
                            <div class="position-relative start-50">
                                <button class="btn btn-primary" type="submit" id="crear_departamento">
                                    <i class="fa fa-plus-circle"></i> Crear Departamento
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div>
                        <table id="tabla_departamento" class="table table-bordered table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <tr>
                                    <th>id departamento</th>
                                    <th>País</th>
                                    <th>Departamento</th>
                                    <th>Opción</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- tercer link -->
                <div class="tab-pane fade" id="municipio" role="tabpanel" aria-labelledby="municipio-tab">
                    <form id="form_crear_ciudad" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Ingreso Ciudad o Municipio</h1>
                        </div>
                        <div class="mb-3 row">
                            <label for="id_departamento2" class="col-sm-2 col-form-label">Departamento:</label>
                            <div class="col-sm-10">
                                <select class="form-control col-8" style="width: 100%;" type="text" name="id_departamento2" id="id_departamento2">
                                    <option value="0"></option>
                                    <?php foreach ($departamento as $departamentos) { ?>
                                        <option value="<?= $departamentos->id_departamento ?>"><?= $departamentos->nombre ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nombre_ciudad" class="col-sm-2 col-form-label">Nombre De la Ciudad o Municipio:</label>
                            <div class="col-sm-10">
                                <input class="form-control col-8 " type="text" id="nombre_ciudad" name="nombre_ciudad">
                                <span id="mensaje2"></span>

                            </div>
                        </div>
                        <div class="mb-3 ">
                            <div class="position-relative start-50">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-plus-circle"></i> Crear Ciudad
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div>
                        <table id="tabla_ciudad" class="table table-bordered table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <tr>
                                    <th>id ciudad</th>
                                    <th>Departamento</th>
                                    <th>Ciudad</th>
                                    <th>Opción</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal para modificar los datos del pais -->
<div class="modal fade" id="ModalPais" aria-labelledby="ModalPaisLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="from_modificar_pais">
            <div class="modal-header header_aco">
                <div class="img_modal"></div>
                <h5 class="modal-title" id="ModalPaisLabel">Modificar Pais</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="codigo_pais">Codigo Pais:</label>
                    <input class="form-control  invisible-input" type="hidden" name="id_pais" id="id_pais" />
                    <input class="form-control " type="text" name="codigo_pais" id="codigo_pais" maxlength="4" />
                </div>
                <div class="mb-3">
                    <label for="nombre_pais">Nombre Del Pais:</label>
                    <input class="form-control col-2" type="text" id="nombre_pais" name="nombre_pais">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button id="modificar_pais_boton" type="submit" class="btn btn-primary">Modificar</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal para modificar los datos del Departamento -->
<div class="modal fade" id="ModalDepartamento" aria-labelledby="ModalDepartamentoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="from_modificar_departamento">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalDepartamentoLabel">Modificar Departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="id_pais_modifi">Pais:</label>
                    <input class="form-control  invisible-input" type="hidden" name="id_departamento_modifi" id="id_departamento_modifi" />
                    <select class="form-control" style="width: 100%;" name="id_pais_modifi" id="id_pais_modifi">
                        <option value="0"></option>
                        <?php foreach ($pais as $paises) { ?>
                            <option value="<?= $paises->id_pais ?>"><?= $paises->nombre ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nombre_departamento_modifi">Nombre Del Departamento:</label>
                    <input class="form-control col-2" type="text" id="nombre_departamento_modifi" name="nombre_departamento_modifi">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary">Modificar</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal para modificar los datos del Ciudad -->
<div class="modal fade" id="ModalCiudad" aria-labelledby="ModalCiudadLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="from_modificar_ciudad">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCiudadLabel">Modificar Ciudad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="id_depart_modifi">Departamento:</label>
                    <input class="form-control  invisible-input" type="hidden" name="id_ciudad_modifi" id="id_ciudad_modifi" />
                    <select class="form-control" style="width: 100%;" name="id_depart_modifi" id="id_depart_modifi">
                        <option value="0"></option>
                        <?php foreach ($departamento as $departamento) { ?>
                            <option value="<?= $departamento->id_departamento ?>"><?= $departamento->nombre ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nombre_ciudad_modifi">Nombre Ciudad:</label>
                    <input class="form-control col-2" type="text" id="nombre_ciudad_modifi" name="nombre_ciudad_modifi">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary">Modificar</button>
            </div>
        </form>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_crear_pais_dep_mpio.js"></script>
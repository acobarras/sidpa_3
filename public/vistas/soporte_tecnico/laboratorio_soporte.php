<div id="principal_laboratorio" class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="laboratorio_soporte" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab-solicitud" role="tablist">
                        <a class="nav-link active" id="nav-solicitud-tab" data-bs-toggle="tab" href="#nav-solicitud" role="tab" aria-controls="nav-solicitud" aria-selected="true">Laboratorio soporte</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent-solicitud">
                    <div class="tab-pane fade show active" id="nav-solicitud" role="tabpanel" aria-labelledby="nav-solicitud-tab">
                        <div class="container-fluid">
                            <br>
                            <div class="mb-3 text-center row">
                                <h1 class="col-md-12 col-md-offset-4 ">Laboratorio Soporte</h1>
                            </div>
                            <div class="table-responsive">
                                <div class="container-fluid">
                                    <hr>
                                    <input type="hidden" id="id_usuario" value="<?= $_SESSION['usuario']->getId_persona(); ?>">
                                    <input type="hidden" id="roll_usuario" value="<?= $_SESSION['usuario']->getId_roll(); ?>">
                                    <table id="tb_laboratorio" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                        <thead style="background:#0d1b50;color:white">
                                            <tr>
                                                <td>num consecutivo</td>
                                                <td>Id diagnostico</td>
                                                <td>Cliente</td>
                                                <td>Direccion</td>
                                                <td>Estado</td>
                                                <td></td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid mt-3 mb-3" id="agregar_item" style="display: none;">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <br>
            <div>
                <button class="btn btn-primary" id="regresar">Regresar</button>
            </div>
            <br>
            <div>
                <div class="mb-3 text-center row">
                    <h1 class="col-md-12 col-md-offset-4 ">Formulario Agregar Equipos Laboratorio</h1>
                </div>
                <div>
                    <div class="container-fluid">
                        <form id="form_agregar_equipo">
                            <div class="form-group">
                                <div class="row">
                                    <div class="mb-3 col-md-4 col-sm-12">
                                        <label for="fecha_ingreso" class="form-label">Fecha Ingreso</label>
                                        <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" disabled>
                                    </div>
                                    <div class="mb-3 col-md-4 col-sm-12">
                                        <label for="equipo" class="form-label">Equipo</label>
                                        <input type="text" class="form-control" id="equipo" name="equipo">
                                    </div>
                                    <div class="mb-3 col-md-4 col-sm-12">
                                        <label for="serial" class="form-label">Serial</label>
                                        <input type="text" class="form-control" id="serial" name="serial">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6 col-sm-12">
                                        <label for="procedimiento" class="form-label">Procedimiento</label>
                                        <textarea class="form-control" id="procedimiento" name="procedimiento"></textarea>
                                    </div>
                                    <div class="mb-3 col-md-6 col-sm-12">
                                        <label for="accesorios" class="form-label">Accesorios</label>
                                        <textarea class="form-control" id="accesorios" name="accesorios"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-success" id="cargar_item" type="button">Agregar</button>
                            </div>
                        </form>
                        <div class="row">
                            <div class="mb-3 col-md-4 col-sm-12">
                                <label for="sede" class="form-label">Sede</label>
                                <select class="form-select" aria-label="Default select example" id="sede" name="sede">
                                    <option selected="selected" value="1">Acobarras </option>
                                    <option value="2">Galán</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-8 col-sm-12">
                                <label for="nota" class="form-label">Nota</label>
                                <textarea class="form-control" id="nota" name="nota"></textarea>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <div class="container-fluid">
                                <table id="tb_item_agregados" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                    <thead style="background:#0d1b50;color:white">
                                        <tr>
                                            <td>Equipo</td>
                                            <td>Serial</td>
                                            <td>Procedimiento</td>
                                            <td>Accesorios</td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-center text-center">
                            <button class="btn btn-primary" id="enviar_items" type="button">ENVIAR EQUIPOS</button>
                            <button class="btn btn-success" style="display:none;" id="redirigir" type="button">Continuar</button>
                        </div>
                    </div>
                </div>
                <div class="div_impresion"></div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/soporte_tecnico/js/laboratorio_soporte.js"></script>
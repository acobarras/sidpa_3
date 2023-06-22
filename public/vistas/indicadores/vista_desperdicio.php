<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Indicador Desperdicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Ajuste Errores O.P.</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="consulta-tab" data-bs-toggle="tab" href="#consulta" role="tab" aria-controls="consulta" aria-selected="false">Consultas Indicadores troquelado</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="consulta_embo-tab" data-bs-toggle="tab" href="#consulta_embo" role="tab" aria-controls="consulta_embo" aria-selected="false">Consultas Indicadores embobinado</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <form class="panel panel-default" name="solicitud_desperdicio" id="solicitud_desperdicio">
                        <center class="panel-heading">
                            <h2>Periodo del indicador</h2>
                        </center>
                        <div class="panel-body">
                            <div class="mb-3 row">
                                <div class="form-group col-md-6">
                                    <label for="fecha_desde">Fecha desde:</label>
                                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="fecha_hasta">Fecha Hasta:</label>
                                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta">
                                </div>
                            </div>
                        </div>
                        <div class="text-center" id="muestro">
                            <button type="submit" class="btn btn-primary" id="id_envio">Grabar</button>
                        </div>
                        <br>
                    </form>
                    <div class="panel panel-default">
                        <center class="panel-heading">
                            <h2>Desperdicio</h2>
                        </center>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="tabla_desperdicio" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                    <thead style="background: #002b5f;color: white">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Máquina</th>
                                            <th>Operarios</th>
                                            <th>O.P.</th>
                                            <th>Tamaño<br>Etiqueta</th>
                                            <th>Q.<br>Tintas</th>
                                            <th>Referencia<br>Materia Prima</th>
                                            <th>Ancho<br>Optimo</th>
                                            <th>m<br>Entregados</th>
                                            <th>m<br>Devueltos</th>
                                            <th>Q Etiquetas<br>Embobinadas</th>
                                            <th>m<sup style="color: white;">2</sup><br>Entregados</th>
                                            <th>m<sup style="color: white;" style="color: white;">2</sup><br>Etiquetas</th>
                                            <th>m<sup style="color: white;">2</sup><br>Desperdicio</th>
                                            <th>%<br>Des. Material</th>
                                            <th>$<br>M.P.</th>
                                            <th>$<br>M.P. Desperdiciada</th>
                                            <!-- <th>$<br>Venta</th> -->
                                            <th>$<br>Unidad</th>
                                            <th>$<br>Etq Producción</th>
                                            <th>%<br />Desperdicio Venta</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <canvas id="myChart" width="400" height="400"></canvas>
                        </div>
                        <div class="col-6">
                            <canvas id="myChart1" width="400" height="400"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Segundo link -->
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <br>
                    <form class="panel panel-default" id="solicitud_personal">
                        <input type="hidden" id="id_roll" value="<?= $_SESSION['usuario']->getId_roll() ?>">
                        <input type="hidden" id="permiso_roll_1" value="<?= ROLL_DESPERDICIO[$_SESSION['usuario']->getId_usuario()] ?>">
                        <input type="hidden" id="permiso_roll_2" value="<?= ROLL_DESPERDICIO_1[$_SESSION['usuario']->getId_usuario()] ?>">
                        <center class="panel-heading">
                            <h2>Ajuste Errores Orden de Producción</h2>
                        </center>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-10">
                                    <label for="num_op">Numero Orden de Producción:</label>
                                    <input type="number" class="form-control" id="num_op" name="num_op">
                                </div>
                                <div class="col-2 text-center" style="margin-top: 1.5rem;" id="muestro">
                                    <button type="submit" class="btn btn-primary btn-lg" id="envio1">Grabar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                    </form>
                    <br>
                    <div class="panel panel-default">
                        <center class="panel-heading">
                            <h2>Datos Troquelado</h2>
                        </center>
                        <div class="panel-body">
                            <table id="tabla_troquelado" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                <thead style="background: #002b5f;color: white">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>codigo Material</th>
                                        <th>Ancho</th>
                                        <th>Metros Lineales Entregados</th>
                                        <th>Metros Lineales Usados</th>
                                        <th>Persona</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="panel panel-default">
                        <center class="panel-heading">
                            <h2>Datos Embobinado</h2>
                        </center>
                        <div class="panel-body">
                            <table id="tabla_embobinado" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                <thead style="background: #002b5f;color: white">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Num Pedido</th>
                                        <th>tamaño Etiqueta</th>
                                        <th>Metros Lineales Empleado</th>
                                        <th>Cantidad Etiquetas</th>
                                        <th>Persona</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- tercer link -->
                <div class="tab-pane fade" id="consulta" role="tabpanel" aria-labelledby="consulta-tab">
                    <br>
                    <form class="panel panel-default" id="consulta_fechas">
                        <input type="hidden" id="id_roll" value="<?= $_SESSION['usuario']->getId_roll() ?>">
                        <input type="hidden" id="permiso_roll_1" value="<?= ROLL_DESPERDICIO[$_SESSION['usuario']->getId_usuario()] ?>">
                        <input type="hidden" id="permiso_roll_2" value="<?= ROLL_DESPERDICIO_1[$_SESSION['usuario']->getId_usuario()] ?>">
                        <center class="panel-heading">
                            <h2>Consulta indicadores</h2>
                        </center>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-5">
                                    <label for="fecha_crea">Fecha desde</label>
                                    <input type="date" class="form-control" id="fecha_crea" name="fecha_crea">
                                </div>
                                <div class="col-5">
                                    <label for="fecha_fin">Fecha Hasta</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                                </div>
                                <div class="col-2 text-center" style="margin-top: 1.5rem;" id="fechas">
                                    <button type="submit" class="btn btn-primary btn-lg" id="envio_fecha">Grabar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                    </form>
                    <br>
                    <div class="panel panel-default">
                        <center class="panel-heading">
                            <h2>Datos Troquelado</h2>
                        </center>
                        <div class="panel-body">
                            <table id="consulta_tabla_troquelado" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                <thead style="background: #002b5f;color: white">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>codigo Material</th>
                                        <th>Ancho</th>
                                        <th>Metros Lineales Entregados</th>
                                        <th>Metros Lineales Usados</th>
                                        <th>Persona</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- cuarto link -->
                <div class="tab-pane fade" id="consulta_embo" role="tabpanel" aria-labelledby="consulta_embo-tab">
                    <br>
                    <form class="panel panel-default" id="consulta_fecha_embo">
                        <input type="hidden" id="id_roll" value="<?= $_SESSION['usuario']->getId_roll() ?>">
                        <input type="hidden" id="permiso_roll_1" value="<?= ROLL_DESPERDICIO[$_SESSION['usuario']->getId_usuario()] ?>">
                        <input type="hidden" id="permiso_roll_2" value="<?= ROLL_DESPERDICIO_1[$_SESSION['usuario']->getId_usuario()] ?>">
                        <center class="panel-heading">
                            <h2>Consulta indicadores Embobinado</h2>
                        </center>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-5">
                                    <label for="fecha_crea">Fecha desde</label>
                                    <input type="date" class="form-control" id="fecha_crea" name="fecha_crea">
                                </div>
                                <div class="col-5">
                                    <label for="fecha_fin">Fecha Hasta</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                                </div>
                                <div class="col-2 text-center" style="margin-top: 1.5rem;" id="fechas">
                                    <button type="submit" class="btn btn-primary btn-lg" id="envio_fechaembo">Grabar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                    </form>
                    <br>
                    <div class="panel panel-default">
                        <center class="panel-heading">
                            <h2>Datos Embobinado</h2>
                        </center>
                        <div class="panel-body">
                            <table id="consulta_tabla_embobinado" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                <thead style="background: #002b5f;color: white">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>tamaño Etiqueta</th>
                                        <th>Metros Lineales Empleado</th>
                                        <th>Cantidad Etiquetas</th>
                                        <th>Persona</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="exampleModalLabel">Datos Metros Lineales Orden de producción</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tabla_ml_op" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                        <thead style="background: #002b5f;color: white">
                            <tr>
                                <th>Fecha</th>
                                <th>Empleado</th>
                                <th>Material</th>
                                <th>Ancho</th>
                                <th>Metros Lineales Entregados</th>
                                <th>Metros Lineales Usados</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para el arreglo de troquelado -->
<div class="modal fade bd-example-modal-lg" id="troqueladoModal" tabindex="-1" role="dialog" aria-labelledby="troqueladoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" id="cambio_ml_reportados">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="troqueladoModalLabel">Arreglo Metros Lineales Usados</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="codigo_material">Codigo Material</label>
                    <input type="text" class="form-control" id="codigo_material" disabled>
                </div>
                <div class="form-group">
                    <label for="ancho_material">Ancho Material</label>
                    <input type="text" class="form-control" id="ancho_material" disabled>
                </div>
                <div class="form-group">
                    <label for="empleado">Persona Reportada</label>
                    <select class="form-control" id="empleado" name="empleado" style="width: 100%;">
                        <?php foreach ($personas as $value) { ?>
                            <option value="<?= $value->id_persona ?>"><?= $value->nombres . " " . $value->apellidos ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ml_usados">Metros lineales Usados</label>
                    <input type="text" class="form-control" id="ml_usados" name="ml_usados">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary boton-x">Cambiar Dato</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para el arreglo de Embobinado -->
<div class="modal fade bd-example-modal-lg" id="embobinadoModal" tabindex="-1" role="dialog" aria-labelledby="embobinadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" id="cambio_etiquetas">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="embobinadoModalLabel">Arreglo Cantidad Etiquetas</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="tamano_etiq">Tamaño Etiqueta</label>
                    <input type="text" class="form-control" id="tamano_etiq" disabled>
                </div>
                <div class="form-group">
                    <label for="empleado1">Persona Reportada</label>
                    <select class="form-control" id="empleado1" name="empleado1" style="width: 100%;">
                        <?php foreach ($personas as $value) { ?>
                            <option value="<?= $value->id_persona ?>"><?= $value->nombres . " " . $value->apellidos ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cantidad_etiq">Cantidad Etiquetas</label>
                    <input type="text" class="form-control" id="cantidad_etiq" name="cantidad_etiq">
                </div>
                <div class="form-group">
                    <label for="metros_lineales">Metros Lineales Empleado</label>
                    <input type="text" class="form-control" id="metros_lineales" name="metros_lineales">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary boton-y">Cambiar Dato</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para el arreglo de la Entrega de la materia prima -->
<div class="modal fade bd-example-modal-lg" id="entregaModal" tabindex="-1" role="dialog" aria-labelledby="entregaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" id="cambio_ml_entregados">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="entregaModalLabel">Arreglo Metros Lineales Entregados</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="codigo_mate_entre">Codigo Material</label>
                    <input type="text" class="form-control" id="codigo_mate_entre" disabled>
                </div>
                <div class="form-group">
                    <label for="ancho_mate_entre">Ancho Material</label>
                    <input type="text" class="form-control" id="ancho_mate_entre" disabled>
                </div>
                <div class="form-group">
                    <label for="empleado_entre">Persona Reportada</label>
                    <select class="form-control" id="empleado_entre" style="width: 100%;" disabled="">
                        <?php foreach ($personas as $value) { ?>
                            <option value="<?= $value->id_persona ?>"><?= $value->nombres . " " . $value->apellidos ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ml_entregados" id="titulo_mate_entre">Metros lineales Entregados</label>
                    <input type="text" class="form-control" id="ml_entregados" name="ml_entregados">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary boton-z">Cambiar Dato</button>
            </div>
        </form>
    </div>
</div>


<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/indicadores/js/vista_desperdicio.js"></script>
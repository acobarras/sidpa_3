<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <?php
        if (empty($maquinas_pro)) { ?>
            <div class="text-center">
                <h1 class="text-danger">Lo sentimos para el día de hoy no tiene programado ningún turno, hable con su jefe inmediato</h1>
            </div>
        <?php } ?>
        <input type="hidden" id="datos_consulta" value="<?= $datos ?>">
        <input type="hidden" id="q_maquinas" value='<?= $q_maquinas ?>'>
        <input type="hidden" id="id_persona_sesion" value="<?= $_SESSION['usuario']->getId_persona() ?>">
        <input type="hidden" id="id_usuario_sesion" value="<?= $_SESSION['usuario']->getId_usuario() ?>">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <?php foreach ($maquinas_pro as $mq_pro) {
                    if ($mq_pro == $maquinas_pro[0]) {
                        $id_item = 'home' . $mq_pro->id_maquina;
                        $activa = 'active';
                    } else {
                        $id_item = 'profile' . $mq_pro->id_maquina;
                        $activa = '';
                    }
                ?>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?= $activa ?> pestana_maquina" id="<?= $id_item ?>-tab" data-bs-toggle="tab" href="#<?= $id_item ?>" role="tab" aria-controls="<?= $id_item ?>" id-maquina="<?= $mq_pro->id_maquina ?>" aria-selected="true"><?= $mq_pro->nombre_maquina ?></a>
                    </li>
                <?php } ?>
            </ul>
            <div class="tab-content" id="myTabContent">
                <?php foreach ($maquinas_pro as $mq_pro) {
                    if ($mq_pro == $maquinas_pro[0]) {
                        $id_item1 = 'home' . $mq_pro->id_maquina;
                        $activa1 = 'show active';
                    } else {
                        $id_item1 = 'profile' . $mq_pro->id_maquina;
                        $activa1 = '';
                    }
                ?>
                    <!-- links -->
                    <div class="tab-pane fade <?= $activa1 ?>" id="<?= $id_item1 ?>" role="tabpanel" aria-labelledby="home<?= $id_item1 ?>-tab">
                        <input type="hidden" value="<?= $mq_pro->id_maquina ?>">
                        <br>
                        <div class="mb-3 text-center">
                            <h1 class="text-center">Trabajos Maquina Producción <?= ucwords(strtolower($mq_pro->nombre_maquina)) ?></h1>
                        </div>
                        <br>
                        <table id="tabla_maquina_produccion<?= $mq_pro->id_maquina ?>" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <tr>
                                    <th style="text-align: center">Fecha Compromiso</th>
                                    <th>Fecha Producción</th>
                                    <th>Turno</th>
                                    <th>N° Orden.Producción</th>
                                    <th>Estado</th>
                                    <th>Descripción</th>
                                    <th>mL O.P</th>
                                    <th>mL Alistados</th>
                                    <th>Opción Disponible</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FICHA TECNICA -->
<div class="modal fade" id="ficha" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ficha_tecnica" aria-hidden="true">
    <div class="modal-dialog modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="ficha_tecnica">Ficha Tecnica</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container" id="ficha_tec">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Reasignar la maquina de Produccion -->

<div class="modal fade" id="CambioMaquinaModal" aria-labelledby="CambioMaquinaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="form_cambio_maquina">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="CambioMaquinaModalLabel">Cambio Maquina Producción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h1 class="col-form-label">Número producción: <span style="color:red" id="num_produccion_data"></span></h1>
                </div>
                <div class="mb-3 row">
                    <div class="col-6">
                        <label class="col-form-label">Maquina:</label>
                        <select id="maquina_data" class="form-control select_2 turno_maquina" style="width: 100%">
                            <option value="0"></option>
                            <?php foreach ($maquinas as $maquina) {
                                if ($maquina->tipo_maquina == 1 || $maquina->tipo_maquina == 3) {
                            ?>
                                    <option value="<?= $maquina->id_maquina ?>"> <?= $maquina->nombre_maquina ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="col-form-label">Turno:</label>
                        <input type="number" class="form-control" id="turno_data" placeholder="Ingrese Turno">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-form-label">Fecha producción:</label>
                    <input type="text" class="form-control datepicker turno_maquina" id="fecha_produccion_data">
                </div>
                <div class="mb-3 d-none" id="div_motivo_cambio">
                    <label class="col-form-label">Motivo Cambio:</label>
                    <input type="text" class="form-control" data-row0="" id="motivo_cambio">
                </div>
                <h4 style="text-align: center">TURNOS</h4>
                <div class=" table-responsive">
                    <table id="fecha_turno_maquina_data" class="table table-hover table-bordered" cellspacing="0" width="100%">
                        <thead class="thead-dark">
                            <tr>
                                <th>Maquina</th>
                                <th>TURNO</th>
                                <th>Núm O.P.</th>
                                <th>mL total</th>
                                <th>Tamaño etiq</th>
                                <th>Cantidad </th>
                                <th>Material</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="3">Total Ml</td>
                                <td></td>
                                <td>Total Q</td>
                                <td></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                        <tbody></tbody>
                    </table>
                    <br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="button" class="btn btn-primary" id="generar_cambio_maquina" data-id="">Continuar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para datos del operario de Produccion -->

<div class="modal fade" id="OperarioModal" aria-labelledby="OperarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="form_codigo_operario">
            <input type="hidden" id="data_boton" value="">
            <input type="hidden" id="id_persona" value="">
            <input type="hidden" id="obj_inicial" value="">
            <input type="hidden" id="boton_ejecuta" value="">
            <input type="hidden" id="data_row" value="">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="OperarioModalLabel">CÓDIGO DE OPERARIO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="codigo_operario" class="form-label">Código Operario : </label>
                    <input autocomplete="off" type="password" class="form-control codigo_operario" name="codigo_operario" id="codigo_operario">
                </div>
                <div class="mb-3">
                    <span class="respu_consulta"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="button" class="btn btn-primary boton_codigo_operario" disabled="true" id="grabar_codigo_operario" data-id="">Continuar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para produccion incompleta y fin de produccion -->

<div class="modal fade" id="ProduccionModal" aria-labelledby="ProduccionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_cierre_produccion">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="ProduccionModalLabel">CÓDIGO DE OPERARIO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="requiere_operario" style="display: none;">
                    <div class="mb-3">
                        <label for="codigo_operario_cierre" class="form-label">Código Operario : </label>
                        <input autocomplete="off" type="password" class="form-control codigo_operario" name="codigo_operario_cierre" id="codigo_operario_cierre">
                    </div>
                    <div class="mb-3">
                        <span class="respu_consulta"></span>
                    </div>
                </div>
                <div class="mb-3" id="troquel">
                    <label for="num_troquel" class="form-label">Numero Troquel : </label>
                    <input autocomplete="off" type="text" class="form-control" name="num_troquel" id="num_troquel">
                </div>
                <div id="radio_completo" class="justify-content-center m-2" style="display: none;">
                    <div class="form-check col-md-3 col-12">
                        <input class="form-check-input m-1 cierre_item" type="radio" name="cierre_item" id="cierre_item1" value="1" checked>
                        <label class="form-check-label" for="cierre_item1">
                            Cierre completo
                        </label>
                    </div>
                    <div class="form-check col-md-3 col-12">
                        <input class="form-check-input m-1 cierre_item" type="radio" name="cierre_item" id="cierre_item2" value="2">
                        <label class="form-check-label" for="cierre_item2">
                            Cierre parcial
                        </label>
                    </div>
                </div>
                <div id="detencion" style="display: none;">
                    <div class="mb-3">
                        <label for="observacion_op" class="form-label">Motivo Detención : </label>
                        <select class="form-control select_2" name="observacion_op" id="observacion_op" style="width: 100%;">
                            <option value="0"></option>
                            <option value="Fin de Turno">Fin de Turno</option>
                            <option value="Daño Irreparable Troquel">Daño Irreparable Troquel</option>
                            <option value="Daño Irreparable Fotopolimero">Daño Irreparable Fotopolimero</option>
                            <option value="Material Faltante Por Llegar">Material Faltante Por Llegar</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <table id="tabla_datos_metros_lineales" class="table table-hover table-bordered" cellspacing="0" width="100%">
                        <thead class="thead-dark">
                            <th>Código Material</th>
                            <th>Ancho</th>
                            <th>metros_lineales</th>
                            <th>Uso Completo</th>
                            <th>Escriba metros lineales</th>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="button" class="btn btn-primary boton_codigo_operario" disabled="true" id="grabar_produccion" data-id="">Continuar</button>
            </div>
        </form>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/produccion/js/vista_trabajo_produccion.js"></script>
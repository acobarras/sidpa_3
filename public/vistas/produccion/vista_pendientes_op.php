<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Items Pendientes O.P.</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="first-tab" data-bs-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="false">Arreglo Maquina O.P.</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="mx-3 mt-2">
                        <div class="text-center">
                            <h2>Tabla Items Pendientes O.P.</h2>
                        </div>
                        <br>
                        <table id="tabla_pendientes_op" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <tr>
                                    <!-- <td>Fecha Compromiso</td> -->
                                    <td>Nombre Empresa</td>
                                    <td>Cantidad </td>
                                    <td>Pedido-Item</td>
                                    <td>Código</td>
                                    <td>Troquel</td>
                                    <td>Magnetico</td>
                                    <td>Tintas</td>
                                    <td>Descripción</td>
                                    <td>Core</td>
                                    <td>Cavidad </td>
                                    <td>Material </td>
                                    <td>Ancho Material </td>
                                    <td>Rollos X </td>
                                    <td><b>mL</b></td>
                                    <td>Fecha Cierre</td>
                                    <td>Estado </td>
                                    <td>Opción </td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <br>
                        <div class="text-center">
                            <button class="btn btn-success" type="button" id="btn-agrupar">
                                <i class="fa fa-plus-circle"></i> Generar O.P.
                            </button>
                        </div>
                        <br>
                    </div>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade" id="first" role="tabpanel" aria-labelledby="first-tab">
                    <br>
                    <form class="container" id="form_consultar_op" enctype="multipart/form-data">
                        <div class="mb-3 text-center">
                            <h1>Cambiar Maquina O.P.</h1>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-10 row">
                                <label for="num_produccion" class="form-label col-2">Numero O.P. : </label>
                                <div class="col-10">
                                    <input type="text" class="form-control" name="num_produccion" id="num_produccion">
                                </div>
                            </div>
                            <div class="mb-3 col-2">
                                <div class="">
                                    <button class="btn btn-primary" type="submit" id="consulta_op">
                                        <i class="fa fa-plus-circle"></i> Consultar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div id="respu_consulta" class="collapse">
                        <form class="container" id="form_modificar_item_producir" enctype="multipart/form-data">
                            <div class="mb-3 text-center">
                                <h1>O.P. Consultada</h1>
                            </div>
                            <div class="mb-3 row">
                                <div class="mb-3 col-6">
                                    <label for="num_produccion_modifi" class="form-label">Numero O.P. : </label>
                                    <span class="fw-bold" id="num_produccion_modifi"></span>
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="cant_op_modifi" class="form-label">Cantidad Etiquetas : </label>
                                    <span class="fw-bold" id="cant_op_modifi"></span>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="mb-3 col-6">
                                    <label for="ancho_op_modifi" class="form-label">Ancho O.P. : </label>
                                    <span class="fw-bold" id="ancho_op_modifi"></span>
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="material_modifi" class="form-label">Material : </label>
                                    <span class="fw-bold" id="material_modifi"></span>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="mb-3 col-6">
                                    <label for="nombre_maquina_modifi" class="form-label">Maquina : </label>
                                    <span class="fw-bold" id="nombre_maquina_modifi"></span>
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="nueva_maquina" class="form-label">Nueva Maquina : </label>
                                    <select id="nueva_maquina" name="maquina" class="form-control select_2" style="width: 50%">
                                        <option value="0"></option>
                                        <?php foreach ($maquinas as $maquina1) { ?>
                                            <?php if ($maquina1->tipo_maquina != 2) { ?>
                                                <option value="<?= $maquina1->id_maquina ?>"> <?= $maquina1->nombre_maquina ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="text-center">
                                    <button class="btn btn-primary" type="submit" id="edita_op" data-edit="">
                                        <i class="fa fa-plus-circle"></i> Modificar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
<!-- Modal para Asignar orden de producción -->

<div class="modal fade" id="GeneraOpModal" aria-labelledby="GeneraOpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modificar_material">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="GeneraOpModalLabel">Asignación Orden De Producción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h1 class="col-md-12 col-md-offset-4 ">Esta a punto de generar O.P | Número : <b id="num_produccion_asig"></b></h1>
                </div>
                <div class=" table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <td><b>Pedido/item</td>
                                <td><b>Código</td>
                                <td><b>Tintas</td>
                                <td><b>Ubicación</td>
                                <td><b>Cantidad</b></td>
                                <td><b>mL</td>
                                <td><b>m²</td>
                                <td><b>Core</td>
                                <td><b>Rollos X Paq</td>
                                <td><b>Sent/Emb</td>
                                <td><b>Tolerancia</td>
                            </tr>
                        </thead>
                        <tbody id="items-seleccionados">
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-center">Total:</td>
                                <td id="total_1"></td>
                                <td id="total_2"></td>
                                <td colspan="5"></td>
                            </tr>
                        </tfoot>
                    </table>
                    <h4>Maquina :</h4>
                    <select id="maquina" class="form-control select_2" style="width: 50%">
                        <?php foreach ($maquinas as $maquina) { ?>
                            <?php if ($maquina->tipo_maquina != 2) { ?>
                                <option value="<?= $maquina->id_maquina ?>"> <?= $maquina->nombre_maquina ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    <br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="button" class="btn btn-primary" id="generar_num_produccion" data-id="">Generar O.P.</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal ver obsevaciones-->
<div class="modal fade" id="observaciones_Modal" aria-labelledby="observaciones_ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <div class="row">
                    <div class="col">
                        <h5 class="modal-title" id="ReporteItemsModalLabel">Observaciones de Pedido</h5>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <br><br>
                <h5 class="text-center fw-bold" style="color:#2e2a5a" id="observaciones_p"></h5>
                <br><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/produccion/js/vista_pendientes_op.js"></script>
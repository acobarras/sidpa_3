<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="orden_pro-tab" data-bs-toggle="tab" href="#orden_pro" role="tab" aria-controls="orden_pro" aria-selected="true">Tabla Ordenes Producción</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="material-tab" data-bs-toggle="tab" href="#material" role="tab" aria-controls="material" aria-selected="false">Tabla Cambio Material</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="orden_pro" role="tabpanel" aria-labelledby="orden_pro-tab">
                    <form class="container" id="form_consulta_produccion" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <div class="my-4 row text-center">
                            <h1>Modificar Orden Producción</h1>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-3">
                                <label for="dato_consulta" class="form-label">Tipo Consulta</label>
                                <input type="text" class="form-control" name="dato_consulta" disabled value="Orden Produccion" id="dato_consulta">
                            </div>
                            <div class="mb-3 col-6">
                                <label for="num_produccion" class="form-label">Numero de Producción</label>
                                <input type="text" class="form-control" name="num_produccion" id="num_produccion">
                            </div>
                            <div class="mb-3 col-2 pt-3">
                                <button class="btn btn-primary" type="submit" id="consulta_orden">
                                    <i class="fa fa-plus-circle"></i> Consultar
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div class="mx-3 mt-2">
                        <div class="text-center">
                            <h2>Tabla Respuesta</h2>
                        </div>
                        <br>
                        <table id="tabla_orden" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <th>Pedido Item</th>
                                <td>Orden Producción</td>
                                <td>Codigo</td>
                                <td>Cantidad Op</td>
                                <td>Ancho Material</td>
                                <td>Cavidad</td>
                                <td>Avance</td>
                                <td>MLtotales</td>
                                <td>Magnetico</td>
                                <td>M2</td>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!-- FORMULARIO CAMBIO MATERIAL -->
                <div class="tab-pane fade" id="material" role="tabpanel" aria-labelledby="material-tab">
                    <form class="container" id="form_consulta_material" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <div class="my-4 row text-center">
                            <h1>Modificar Material Orden Producción</h1>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="n_produccion" class="form-label">Numero de Producción</label>
                                <input type="text" class="form-control" name="n_produccion" id="n_produccion">
                            </div>
                            <div class="mb-3 col-2 pt-3">
                                <button class="btn btn-primary" type="submit" id="consulta_material">
                                    <i class="fa fa-plus-circle"></i> Consultar
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div class="form_material">
                        <form class="container" style="display: none;" id="form_cambio_material">
                            <br>
                            <div class="mb-3 col-md-12 text-center">
                                <h3 style="text-align: center;"><b>Nuevo Material <span style="color:red" id="num_op"></span></b></h3>
                            </div>
                            <div class="mb-3">
                                <label for="material_op" class="form-label">Material : </label>
                                <input autocomplete="off" disabled type="text" class="form-control" name="material_op" id="material_op" />
                            </div>
                            <div class="mb-3">
                                <label for="material_confir" class="form-label">Material Confirmado</label>
                                <input autocomplete="off" disabled type="text" class="form-control" name="material_confir" id="material_confir" />
                            </div>
                            <div class="mb-3">
                                <label for="nuevo_material" class="form-label">Material Nuevo :</label>
                                <select id="nuevo_material" style="width: 100%;" class="form-select select_2" name="nuevo_material">
                                    <option value="0">ELIJA</option>
                                    <?php foreach ($productos as $p) { ?>
                                        <option data-id="<?= $p->precio1 ?>" value="<?= $p->codigo_producto ?>"><?= $p->codigo_producto . " BOBINA " . $p->descripcion_productos ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 text-center">
                                <button class="btn btn-success" type="buttom" id="cambio_material">
                                    <i class="fa fa-plus-circle"></i> Nuevo Material
                                </button>
                            </div>
                            <br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/modificar_orden_produccion.js"></script>
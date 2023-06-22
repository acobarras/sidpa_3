<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="Comparativo-tab" data-bs-toggle="tab" href="#Comparativo" role="tab" aria-controls="Comparativo" aria-selected="true">Comparativo</a>
                    <a class="nav-link " id="arreglo_canasta-tab" data-bs-toggle="tab" href="#arreglo_canasta" role="tab" aria-controls="arreglo_canasta" aria-selected="true">Arreglo Ubicación</a>
                </div>
            </nav>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="Comparativo" role="tabpanel" aria-labelledby="Comparativo-tab">
                    <div class="container-fluid">

                        <br>
                        <table id="dt_inv_etiqueta" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                            <thead style="background:#0d1b50;color:white">
                                <tr>
                                    <th>Ubicación</th>
                                    <th>Codigo Producto</th>
                                    <th>Descripcion Producto</th>
                                    <th>Conteo</th>
                                    <th>Usuario Conteo</th>
                                    <th>Verificación</th>
                                    <th>Usuario Verificación</th>
                                    <th>Diferencia</th>
                                </tr>
                            </thead>
                            <tbody id=""></tbody>
                        </table>
                    </div>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade show" id="arreglo_canasta" role="tabpanel" aria-labelledby="arreglo_canasta-tab">
                    <br>
                    <h3 class="text-center fw-bold">Arreglo Ubicación</h3>
                    <br>
                    <div class="container mt-3 mb-3">
                        <div class="recuadro">
                            <div id="contenido" class="px-2 py-2 col-lg-12">
                                <form id="form_consulta_inventario_ubicacion">
                                    <div class="input-group mt-3 mb-3">
                                        <label class="input-group-text" for="ubicacion_canasta">Canasta Ubicación:</label>
                                        <input type="text" class="form-control" name="ubicacion" id="ubicacion_canasta" placeholder="Ingrese el Ubicación">
                                        <button class="btn btn-primary" type="button" id="btn_consultar_inventario_ubicacion"> <i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-lg-12">
                        <table id="dt_modifi_inv" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                            <thead style="background:#0d1b50;color:white">
                                <tr>
                                    <th>Ubicación</th>
                                    <th>Codigo Producto</th>
                                    <th>Descripcion Producto</th>
                                    <th>Ancho</th>
                                    <th>Metros</th>
                                    <th>Conteo</th>
                                    <th>Usuario Conteo</th>
                                    <th>Ancho Verificado</th>
                                    <th>Metros Verificado</th>
                                    <th>Verificación</th>
                                    <th>Usuario Verificación</th>
                                    <th>Opcción</th>
                                </tr>
                            </thead>
                        </table>

                        <center>
                            <button class="btn btn-primary btn-lg acepta_canasta" id="acepta_canasta" style="display: none;" data="">Acepta Canasta</button>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para modificar precios -->
<div class="modal fade" id="modificarUbicacionInv" aria-labelledby="modificarUbicacionInvLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <center>
                    <h5 clas s="modal-title" id="ReporteItemsModalLabel">Modificar Item Ubicación Inventario</h5>
                </center>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container recuadro">
                    <form id="form_edita_item_inv">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <input type="hidden" name="id" id="id">
                                <label for="">Ubicación:</label>
                                <input type="text" class="form-control" id="ubicacion">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="">Código:</label>
                                <input type="text" class="form-control" id="codigo_producto">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="ancho">Ancho:</label>
                                <input type="text" class="form-control" name="ancho" id="ancho">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="ancho_verificado">Ancho Verificado:</label>
                                <input type="text" class="form-control" name="ancho_verificado" id="ancho_verificado">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="metros">Metros:</label>
                                <input type="text" class="form-control" name="metros" id="metros">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="metros_verificado">Metros Verificado:</label>
                                <input type="text" class="form-control" name="metros_verificado" id="metros_verificado">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="entrada">Conteo:</label>
                                <input type="text" class="form-control" name="entrada" id="entrada">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="entrada_verificado">Verificación:</label>
                                <input type="text" class="form-control" name="entrada_verificado" id="entrada_verificado">
                            </div>

                            <div class="col-6 mb-3">
                                <label for="nombre_conteo">Usuario Conteo:</label>
                                <input type="text" class="form-control" id="nombre_conteo">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="nombre_verificado">Usuario Verificación:</label>
                                <input type="text" class="form-control" id="nombre_verificado">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="descripcion">Descripción Producto:</label>
                                <textarea class="form-control" id="descripcion" cols="30" rows="3"></textarea>
                            </div>
                            <br>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary btn_editar_item" id="btn_editar_item" data-bs-dismiss="">Modificar</button>

        </div>
    </div>
</div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/inventario_final/js/inventario_contabilidad.js"></script>
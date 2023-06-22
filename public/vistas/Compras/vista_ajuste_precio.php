<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="Asignar_precio-tab" data-bs-toggle="tab" href="#Asignar_precio" role="tab" aria-controls="Asignar_precio" aria-selected="true">Asignar Precio</a>
                    <a class="nav-link " id="Ajustar_precio_asesor-tab" data-bs-toggle="tab" href="#Ajustar_precio_asesor" role="tab" aria-controls="Ajustar_precio_asesor" aria-selected="true">Ajustar Precio</a>
                </div>
            </nav>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="Asignar_precio" role="tabpanel" aria-labelledby="Asignar_precio-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Precios Sin Asignar</h3>
                        <br>
                        <table id="dt_tabla_ajuste" class="table table-bordered 
                        table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                            <thead style="background: #0d1b50;color: white">
                                <tr>
                                    <th>Asesor</th>
                                    <th>Cliente</th>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Core</th>
                                    <th>Presentación</th>
                                    <th>Moneda Autorizada</th>
                                    <th>Precio Autorizado</th>
                                    <th>Cantidad Minima</th>
                                    <th>Precio Venta</th>
                                    <th>Moneda Venta</th>
                                    <th>Opcción</th>
                                </tr>
                            </thead>
                        </table>
                        <br>
                        <center>
                            <button type="button" class="btn btn-info" id="asigna_precios"><i class="fas fa-check"></i> Asigna</button>
                        </center>
                        <br>
                    </div>
                </div>
                <div class="tab-pane fade show" id="Ajustar_precio_asesor" role="tabpanel" aria-labelledby="Ajustar_precio_asesor-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Modifica Precio</h3>
                        <br>
                        <label for="id_usuario_asesor" class="form-label"><strong>Asesor</strong></label>
                        <div class="input-group mb-3">
                            <select id="id_usuario_asesor" class="form-control select_2" style="width: 90%;">
                                <option value="0">Elija una opcción</option>
                                <?php foreach ($asesores as $asesor) { ?>
                                    <option value="<?= $asesor->id_usuario ?>"><?= $asesor->nombre ?> <?= $asesor->apellido ?></option>
                                <?php } ?>
                            </select>
                            <button class="btn btn-primary" type="button" id="btn_busca_prod_asesor">Buscar <i class="fa fa-search"></i>
                        </div>
                        <br>

                        <table id="dt_tabla_productos" class="table table-bordered 
                        table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                            <thead style="background: #0d1b50;color: white">
                                <tr>
                                    <th>Asesor</th>
                                    <th>Cliente</th>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Core</th>
                                    <th>Presentación</th>
                                    <th>Moneda Autorizada</th>
                                    <th>Precio Autorizado</th>
                                    <th>Cantidad Minima</th>
                                    <th>Precio Venta</th>
                                    <th>Moneda Venta</th>
                                    <th>Modificar</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<!-- Modal para Asignar precios -->

<div class="modal fade" id="AsignaPrecioModal" aria-labelledby="AsignaPrecioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="form_reporte_operario">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="ReporteItemsModalLabel">Asignación de precios seleccionados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_asigna_precio">
                    <div>
                        <label for="moneda_autoriza">Moneda Autoriza</label>
                        <select class="form-select" name="moneda_autoriza" id="moneda_autoriza">
                            <option value=""></option>
                            <option value="1">Pesos</option>
                            <option value="2">Dolar</option>
                        </select>
                    </div>
                    <div>
                        <label for="precio_autorizado">Precio Autorizado</label>
                        <input type="text" class="form-control" name="precio_autorizado" id="precio_autorizado">
                    </div>
                    <div>
                        <label for="cantidad_minima">Cantidad Minima</label>
                        <input type="text" class="form-control" name="cantidad_minima" id="cantidad_minima">
                    </div>
                    <div id="div_material_ajuste" style="display: block;">
                        <label for="id_material"><strong>Material </strong></label>
                        <select class="form-control select_2" id="id_material" name="id_material" style="width: 100%">
                            <option value="0">Elija una opcción</option>
                            <?php foreach ($productos as $pros) { ?>
                                <option value="<?= $pros->id_productos ?>"><?= $pros->codigo_producto . " BOBINA " . $pros->descripcion_productos ?></option>
                            <?php } ?>
                        </select>
                        <br><br>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-info" id="btn_asignar_precio" data-item="">Asignar</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal para modificar precios -->
<div class="modal fade" id="modificaPrecioModal" aria-labelledby="modificaPrecioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <div class="row">
                    <div class="col">
                        <h5 clas s="modal-title" id="ReporteItemsModalLabel">Modifica precio</h5>
                    </div>
                    <div class="col">
                        <label for=""> Código:</label>
                        <span class="codigo_productoD"> </span>
                    </div>
                    <div class="col">
                        <label for=""> Descripción:</label>
                        <span class="descripcion_productosD"> </span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_modifica_producto">
                    <input type="hidden" name="id_clien_produc" id="id_clien_produc">

                    <div>
                        <label for="moneda_autoriza">Moneda Autoriza</label>
                        <select class="form-select" name="moneda_autoriza" id="id_moneda_autoriza">
                            <option value=""></option>
                            <option value="1">Pesos</option>
                            <option value="2">Dolar</option>
                        </select>
                    </div>
                    <div>
                        <label for="precio_autorizado">Precio Autorizado</label>
                        <input type="text" class="form-control" name="precio_autorizado" id="id_precio_autorizado">
                    </div>
                    <div>
                        <label for="cantidad_minima">Cantidad Minima</label>
                        <input type="text" class="form-control" name="cantidad_minima" id="id_cantidad_minima">
                    </div>
                    <div>
                        <label for="id_material"><strong>Material </strong></label>
                        <select class="form-control select_2" id="id_id_material" name="id_material" style="width: 100%">
                            <option value="0">Elija una opcción</option>
                            <?php foreach ($productos as $pros) { ?>
                                <option value="<?= $pros->id_productos ?>"><?= $pros->codigo_producto . " BOBINA " . $pros->descripcion_productos ?></option>
                            <?php } ?>
                        </select>
                        <br><br>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-info" id="btn_modifica_precio">Asignar</button>
            </div>
            </form>
        </div>
    </div>
</div>



<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Compras/js/ajuste_precio.js"></script>
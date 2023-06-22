<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="Materia_prima-tab" data-bs-toggle="tab" href="#Materia_primaop" role="tab" aria-controls="Materia_primaop" aria-selected="true">Materia Prima O.P</a>
                    <!-- <a class="nav-link " id="Materia_prima-tab" data-bs-toggle="tab" href="#Materia_prima" role="tab" aria-controls="Materia_prima" aria-selected="true">Materia Prima</a> -->
                </div>
            </nav>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="Materia_primaop" role="tabpanel" aria-labelledby="Materia_primaop-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <br>
                        <h3 class="text-center fw-bold">Ordenes De Producción</h3>
                        <br>

                        <div class="tb_materia_prima_op">
                            <table id="dt_materia_prima_op" style="background: white" class="table table-bordered 
                            table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <td>Fecha Compro</td>
                                        <td>No. Producción </td>
                                        <td>Tamaño Etiq </td>
                                        <td>Ancho</td>
                                        <td>Cantidad O.P </td>
                                        <td>ML Total</td>
                                        <td>ML Alistados</td>
                                        <td>Material</td>
                                        <td>Ver</td>
                                        <td>Opcción</td>
                                    </tr>
                                </thead>
                            </table>
                            <br>
                            <div class="container recuadro">
                                <br>
                                <form id="form_asigna_materiales">
                                    <div class="mb-3">
                                        <label for="material_solicitado" class="col-form-label fw-bold">Codigo Material Solicitado:</label>
                                        <select id="material_solicitado" class="form-select select_2" name="material_solicitado">
                                            <option value="0">ELIJA</option>
                                            <?php foreach ($productos as $p) { ?>
                                                <option data-id="<?= $p->precio1 ?>" value="<?= $p->codigo_producto ?>"><?= $p->codigo_producto . " BOBINA " . $p->descripcion_productos ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" id="costo" name="precio_material">
                                    </div>
                                    <div class="mb-3">
                                        <label for="orden_compra" class="col-form-label fw-bold">Numero Orden De Compra:</label>
                                        <input autocomplete="off" class="form-control" type="text" id="orden_compra" name="orden_compra">
                                    </div>
                                    <div class="mb-3">
                                        <label for="ancho_confirmado" class="col-form-label fw-bold">Ancho Confirmado:</label>
                                        <input autocomplete="off" class="form-control" type="text" id="ancho_confirmado" name="ancho_confirmado">
                                    </div>
                                    <div class="mb-3">
                                        <label for="fecha_proveedor" class="col-form-label fw-bold">Fecha Entrega:</label>
                                        <input autocomplete="off" class="form-control" type="text" id="fecha_proveedor" name="fecha_proveedor">
                                    </div>
                                    <center>
                                        <button type="submit" class="btn btn-info" id="asigna_material"><i class="fas fa-check"></i>Asigna</button>
                                    </center>
                                </form>
                                <br>
                            </div>
                        </div>
                        <br>
                        <div class="TablaOrden" style="display: none;">
                            <div>
                                <button class="btn btn-primary" id="mostrarTabla"><i class="fa fa-caret-left"></i> Regresar
                                </button>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4>Número de O.P. &nbsp;&nbsp;| <b style="color:#0027d2;" id="numORDEN"></b></h4>
                                            <h4>Tamaño Etiqueta | <b style="color:#0027d2;" id="etiqTAMNIO"></b></h4>
                                            <h4>Total Metros Lineales | <b style="color:#0027d2;" id="mtORDEN"></b> mL</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4>Ancho Confirmado &nbsp;&nbsp;| <b style="color:#0027d2;" id="anchoORDEN"></b></h4>
                                            <h4>Cantidad O.P | <b style="color:#0027d2;" id="cantORDEN"></b></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <h4 style="text-align: center"><b>ITEMS</b></h4>
                            <table id="dt_detalle_materia_prima_op" style="background: white" class="table table-bordered 
                            table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <td>Codigo</td>
                                        <td>Descripción </td>
                                        <td>ubicación </td>
                                        <td>Cantidad</td>
                                        <td>mL</td>
                                        <td>m²</td>
                                        <td>Pedido-Item</td>
                                        <td>Core</td>
                                        <td>Rollo X/paq</td>
                                        <td>Sen/Emb</td>
                                    </tr>
                                </thead>
                            </table>
                            <br>
                        </div>

                    </div>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade show" id="Materia_prima" role="tabpanel" aria-labelledby="Materia_prima-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        holaaaa2121
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Compras/js/materia_prima.js"></script>
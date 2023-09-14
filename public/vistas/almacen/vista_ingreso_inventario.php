<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active btn btn-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Tabla Productos</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link btn btn-link" id="agrega-tab" data-bs-toggle="tab" data-bs-target="#agrega-tab-pane" type="button" role="tab" aria-controls="agrega-tab-pane" aria-selected="true">Consultar Ubicacion</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <h3 class="text-center fw-bold">Ingreso Inventario</h3>
                    <br>
                    <div class="mt-3 mb-3">
                        <div class="recuadro">
                            <div id="contenido" class="px-2 py-2 col-lg-12">
                                <div class="mt-3 mb-3">
                                    <label class="form-label" for="ubicacion">Lectura Ubicación</label>
                                    <input type="text" autocomplete="off" class="form-control" style="color:transparent;" placeholder="ubicacion" name="ubicacion" id="ubicacion">
                                </div>
                                <div class="mt-3 mb-3">
                                    <label class="form-label" for="codigo">Lectura Codigo</label>
                                    <input type="text" autocomplete="off" class="form-control" style="color:transparent;" placeholder="Lectura Codigo" name="codigo" id="codigo">
                                </div>
                                <div class="mb-3">
                                    <p id="ubica_producto">&nbsp; </p>
                                    <p id="codigo_producto">&nbsp; </p>
                                    <p id="cantidad">&nbsp; </p>
                                    <p id="descripcion">&nbsp; </p>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-primary" type="button" id="limpiar">Limpiar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <!-- Segundo link -->
                <div class="tab-pane fade" id="agrega-tab-pane" role="tabpanel" aria-labelledby="agrega-tab">
                    <br>
                    <h3 class="text-center fw-bold">Consulta Ubicaciones Producto</h3>
                    <br>
                    <div class="mt-3 mb-3">
                        <div class="mt-3 mb-3">
                            <label class="form-label" for="cons_codigo">Consulta Codigo</label>
                            <input type="text" autocomplete="off" class="form-control" style="color:transparent;" placeholder="Consulta Codigo" name="cons_codigo" id="cons_codigo">
                        </div>
                        <table id="tb_ubicacion" class=" table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                            <thead class="bg-layout">
                                <tr>
                                    <td><b>Codigo</b></td>
                                    <td><b>Ubicación</b></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/almacen/js/vista_ingreso_inventario.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Etiquetas</a>
                </div>
            </nav>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                    <div class="panel panel-primary" style="margin-top: 20px;box-shadow: -4px 4px 4px -4px rgba(0,0,0,0.75); ">
                        <div class="panel-heading header_aco text-center">
                            <h3 class="col-md-12 col-md-offset-4 text-center">Ingreso De Mercancía Etiquetas</h3>
                        </div>
                        <br />
                        <div class="container">
                            <form id="etiqueta">
                                <div class="mb-3 row">
                                    <label for="documento" class="col-sm-2 col-form-ablel fw-bold">Orden Producción:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control col-8" type="text" name="documento" id="documento">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="ubicacion" class="col-sm-2 col-form-label fw-bold">Ubicación:</label>
                                    <div class="col-sm-10">
                                        <select class="form-control col-8 select_2" name="ubicacion" id="ubicacion">
                                            <option value="0"></option>
                                            <?php foreach ($ubicacion as $ubica) { ?>
                                                <option value="<?= $ubica->nombre_ubicacion ?>"><?= $ubica->nombre_ubicacion ?></option>
                                            <?php } ?>
                                            <option value="nuevo">Crear</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="codigo_producto" class="col-sm-2 col-form-label fw-bold">Codigo Producto:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control col-8" type="text" name="codigo_producto" id="codigo_producto">
                                        <input type="hidden" name="id_productos" id="id_producto">
                                        <span class="text-danger" id="respu_codigo_tecno"></span>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="entrada" class="col-sm-2 col-form-label fw-bold">Cantidad a Ingresar:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control col-8" type="text" name="entrada" id="entrada">
                                    </div>
                                </div>
                                <br>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label fw-bold">Descripción:</label>
                                    <span class="col-sm-10  fw-bold text-primary" id="respuesta"></span>
                                </div>
                                <div class="mb-3 row">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary" id="btn_ingresar_eti" data-valida="">
                                            <i class="fa fa-plus-circle"></i> Ingresar
                                        </button>
                                    </div>
                                </div>

                            </form>

                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table id="dt_etiquetas_disponibles" class=" table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                        <thead class="bg-layout">
                                            <tr>
                                                <td><b>Ubicación</b></td>
                                                <td><b>Entrada</b></td>
                                                <td><b>Salida</td>
                                                <td><b>Total</td>
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
    <div id="respuesta_vista"></div>
</div>




<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/almacen/js/vista_ingreso_etiquetas.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Salida Etiquetas</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                    <div class="panel panel-primary" style="margin-top: 20px;box-shadow: -4px 4px 4px -4px rgba(0,0,0,0.75); ">
                        <div class="panel-heading header_aco">
                            <center>
                                <h3 class="col-md-12 col-md-offset-4 text-center">Salida De Mercancía Etiquetas</h3>
                            </center>
                        </div>
                        <br />
                        <div class="container">
                            <form id="etiqueta">
                                <div class="mb-3 row">
                                    <label for="documento" class="col-sm-2 col-form-ablel fw-bold">N de pedido:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control col-8" type="text" name="documento" id="documento">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="codigo_producto" class="col-sm-2 col-form-ablel fw-bold">Codigo Producto:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control col-8" type="text" name="codigo_producto" id="codigo_producto_etiq">
                                        <input type="hidden" name="id_productos" id="id_producto">
                                        <span class="text-danger" id="respu_codigo_pro"></span>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="ubicacion" class="col-sm-2 col-form-ablel fw-bold">Ubicación</label>
                                    <div class="col-sm-10">
                                        <select class="form-control col-8 select_2" name="ubicacion">
                                            <option value="0"></option>
                                            <?php foreach ($ubicacion as $ubi) { ?>
                                                <option value="<?= $ubi->nombre_ubicacion ?>"><?= $ubi->nombre_ubicacion ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="salida" class="col-sm-2 col-form-ablel fw-bold">Cantidad a sacar:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control col-8" type="text" name="salida" id="salida">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-ablel fw-bold">Descripción:</label>
                                    <span class="col-sm-10  fw-bold text-primary" id="respuesta"></span>
                                </div>
                                <div class="mb-3 row">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary" id="btn_ingresar_eti" data-valida="">
                                            <i class="fa fa-plus-circle"></i> Grabar
                                        </button>
                                    </div>
                                </div>
                                <br>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/almacen/js/vista_salida_etiquetas.js"></script>
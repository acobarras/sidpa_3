<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Bobinas</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                    <div class="panel panel-primary" style="margin-top: 20px;box-shadow: -4px 4px 4px -4px rgba(0,0,0,0.75); ">
                        <div class="panel-heading header_aco">
                            <center>
                                <h3 class="col-md-12 col-md-offset-4 text-center">Ingreso De Mercancia Bobinas</h3>
                            </center>
                        </div>
                        <br />
                        <div class="container">
                            <form id="bobina">
                                <div class="mb-3 row">
                                    <label for="documento" class="col-sm-2 col-form-ablel fw-bold">Factura:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control col-8" type="text" name="documento" id="documento">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="codigo_producto" class="col-sm-2 col-form-ablel fw-bold">Codigo Bobina:</label> 
                                    <div class="col-sm-10">
                                    <input class="form-control col-8" type="text" name="codigo_producto" id="codigo_producto">
                                    <input type="hidden" name="id_productos" id="id_producto">
                                    <input type="hidden" id="id_tipo_articulo">
                                    <span class="text-danger" id="respu_codigo_tecno"></span>
                                    </div>
                                </div>
                                <!-- <div class="mb-3 row">
                                    <label for="ubicacion" class="col-sm-2 col-form-label fw-bold">Ubicación:</label>
                                    <div class="col-sm-10">
                                        <select class="form-control col-8 select_2" name="ubicacion" id="ubicacion">
                                            <option value="0"></option>
                                        <?php foreach ($ubicacion as $ubi) { ?>
                                            <option value="<?= intval($ubi->ancho)?>"><?= intval($ubi->ancho) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div> -->
                                <div class="mb-3 row">
                                    <label for="ancho" class="col-sm-2 col-form-ablel fw-bold">Ancho:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control solo-numeros col-8" type="text" name="ancho" id="ancho">
                                        <input type="hidden" name="ubicacion" id="ubicacion">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="metros" class="col-sm-2 col-form-ablel fw-bold">Metros Lineales:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control solo-numeros col-8" autocomplete="off" type="text" name="metros" id="metro_lineales">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="entrada" class="col-sm-2 col-form-ablel fw-bold">Metros Cuadrados:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control col-8" type="text" name="entrada" id="m2" readonly>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label fw-bold">Descripcion:</label>
                                    <span class="col-sm-10 fw-bold text-primary" id="respuesta"></span>
                                </div>
                                <div class="mb-3 row">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary" id="btn_ingresar_bob" data-valida="">
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
<script src="<?= PUBLICO ?>/vistas/almacen/js/vista_ingreso_bobinas.js"></script>
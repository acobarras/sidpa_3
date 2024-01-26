<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Salida Mercancía</a>
                </li>
            </ul>
            <div class="recuadro">
                <div class="container-fluid mt-3 mb-3">
                    <br>
                    <div class="mb-3 row">
                        <label for="nombre" class="col-sm-2 col-form-ablel fw-bold">Nombre:</label>
                        <div class="col-sm-10">
                            <input class="form-control col-8" type="text" id="nombre">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="area" class="col-sm-2 col-form-ablel fw-bold">Area:</label>
                        <div class="col-sm-10">
                            <select class="form-control select_2" name="area" id="area" style="width: 100%;">
                                <option value="0"></option>
                                <?php foreach ($area as $areas) { ?>
                                    <option value="<?= $areas->nombre_area_trabajo ?>"><?= $areas->nombre_area_trabajo ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                    <div class="panel panel-primary" style="margin-top: 20px;box-shadow: -4px 4px 4px -4px rgba(0,0,0,0.75); ">
                        <div class="panel-heading header_aco">
                            <center>
                                <h3 class="col-md-12 col-md-offset-4 text-center">Salida De Mercancía Producción</h3>
                            </center>
                        </div>
                        <br />
                        <div class="container">
                            <form id="etiqueta">
                                <div class="mb-3 row">
                                    <label for="codigo_producto" class="col-sm-2 col-form-ablel fw-bold">Codigo Producto:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control col-8" type="text" name="codigo_producto" id="codigo_producto">
                                        <input type="hidden" name="id_productos" id="id_producto">
                                        <span class="text-danger" id="respu_codigo_pro"></span>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="cav" class="col-sm-2 col-form-ablel fw-bold">Cav:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control col-8" type="text" name="cav" id="cav">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="cor" class="col-sm-2 col-form-ablel fw-bold">Cor:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control col-8" type="text" name="cor" id="cor">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="rollos_x" class="col-sm-2 col-form-ablel fw-bold">Rollos x:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control col-8" type="text" name="rollos_x" id="rollos_x">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="ubicacion" class="col-sm-2 col-form-ablel fw-bold">Ubicación</label>
                                    <div class="col-sm-10" id="select_ubicacion">
                                        <select class="form-control col-8 select_2" style="width: 100%;"></select>
                                    </div>
                                    <span class="text-center text-success" id="valor_ubicacion"><b></b></span>
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
                                        <button type="submit" class="btn btn-success" id="btn_ingresar_eti" data-valida="">
                                            <i class="fa fa-plus-circle"></i> Añade
                                        </button>
                                    </div>
                                </div>
                                <br>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="recuadro">
                <div class="container-fluid mt-3 mb-3">
                    <br>
                    <div class="mb-3 row">
                        <label for="area" class="col-sm-2 col-form-ablel fw-bold">Observación:</label>
                        <div class="col-sm-10">
                            <input class="form-control col-8" type="area" id="observaciones">
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <table id="tabla_items" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                <thead style="background:#0d1b50;color:white">
                    <tr>
                        <th>Código </th>
                        <th>Cantidad </th>
                        <th>Ubicacion </th>
                        <th>Descripcción </th>
                        <th>Cav</th>
                        <th>Cor</th>
                        <th>Rollos X</th>
                        <th>Valor Unitario</th>
                        <th>Valor Total</th>
                        <th>Opcción</th>
                    </tr>
                </thead>
            </table>
        </div>
        <br>
        <center>
            <button type="button" class="btn btn-primary" id="crea_salida_inv">
                <i class="fa fa-plus-circle"></i> Grabar
            </button>
        </center>
        <br>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/almacen/js/vista_alista_materia_produccion.js"></script>
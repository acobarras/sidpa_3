<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="etiquetas-tab" data-bs-toggle="tab" href="#etiquetas" role="tab" aria-controls="etiquetas" aria-selected="true">Etiquetas</a>
                    <a class="nav-link" id="tecnologia-tab" data-bs-toggle="tab" href="#tecnologia" role="tab" aria-controls="tecnologia" aria-selected="true">Tecnología</a>
                    <a class="nav-link" id="bobina-tab" data-bs-toggle="tab" href="#bobina" role="tab" aria-controls="bobina" aria-selected="true">Bobina</a>
                    <a class="nav-link" id="ubicacion-tab" data-bs-toggle="tab" href="#ubicacion" role="tab" aria-controls="ubicacion" aria-selected="true">Ubicación</a>
                </div>
            </nav>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="etiquetas" role="tabpanel" aria-labelledby="etiquetas-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <div class="col-lg-10">
                            <br>
                            <h4>Código Etiquetas</h4>
                        </div>
                        <div class="col-lg-8">
                            <form id="form_consulta_inventario_etiqueta">
                                <div class="form-group input-group">
                                    <input type="number" value="2" name="tipo_art" hidden="true" />
                                    <input type="text" class="form-control" name="codigo" placeholder="Ingrese el codigo del producto">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary"> <i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <br>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="dt_consulta_inventario_etiqueta" class=" table table-bordered table-hover 
                                table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                    <thead class="bg-layout">
                                        <tr class="">
                                            <td><b>Código <i class="fa fa-qrcode"></i></b></td>
                                            <td><b>Producto</td>
                                            <td><b>Descripción</td>
                                            <td><b>Cantidad</td>
                                            <td><b>Opciones</td>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-------------------------------------------------- MODAL DETALLE INFO ETIQUETA --------------------------------------------->
                        <div class="modal fade" id="info_item_etiqueta" role="dialog" aria-labelledby="exampleModal_info_item_etiqueta" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header header_aco">
                                        <div class="img_modal">
                                            <p> </p>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="modal-title" id="nombre_producto_etiqueta"></h5>
                                            </div>
                                            <div class="col">
                                                <p class="text-end" style="font-size: 15px;">Cantidad :<span id="cantidad_producto_etiqueta"></span></p>
                                            </div>
                                        </div>

                                        <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
                                    </div>
                                    <div class="modal-body">
                                        <div class="recuadro">
                                            <div class="container-fluid">
                                                <br>
                                                <h3 class="text-center">Análisis de Productos</h3><br>
                                                <table id="dt_infor_producto_etiqueta" class="table table-responsive-lg  table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Ubicación</th>
                                                            <th>Entrada</th>
                                                            <th>Salida</th>
                                                            <th>Total</th>
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
                <!-- segundo link -->
                <div class="tab-pane fade show" id="tecnologia" role="tabpanel" aria-labelledby="tecnologia-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <div class="col-lg-10">
                            <br>
                            <h4>Código de Parte :</h4>
                        </div>
                        <div class="col-lg-8">
                            <form id="form_consulta_inventario_tec">
                                <div class="form-group input-group">
                                    <input type="number" value="3" name="tipo_art" hidden="true" />
                                    <input type="text" class="form-control" name="codigo" placeholder="Ingrese el número de parte del producto">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <br>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="dt_consulta_inventario_tec" class=" table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                    <thead class="bg-layout">
                                        <tr>
                                            <td><b>Código <i class="fa fa-qrcode"></i></b></td>
                                            <td><b>Producto</td>
                                            <td><b>Descripción</td>
                                            <td><b>Cantidad</td>
                                            <td><b>Opciones</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-------------------------------------------------- MODAL DETALLE INFO ETIQUETA --------------------------------------------->
                        <div class="modal fade" id="info_item_tec" role="dialog" aria-labelledby="exampleModal_info_item_tec" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header header_aco">
                                        <div class="img_modal">
                                            <p> </p>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="modal-title" id="nombre_producto_tec"></h5>
                                            </div>
                                            <div class="col">
                                                <p class="text-end" style="font-size: 15px;">Cantidad :<span id="cantidad_producto_tec"></span></p>
                                            </div>
                                        </div>

                                        <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
                                    </div>
                                    <div class="modal-body">
                                        <div class="recuadro">
                                            <div class="container-fluid">
                                                <br>
                                                <h3 class="text-center">Análisis de Productos</h3><br>
                                                <table id="dt_infor_producto_tec" class="table table-responsive-lg  table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Ubicación</th>
                                                            <th>Entrada</th>
                                                            <th>Salida</th>
                                                            <th>Total</th>
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
                <!-- tercer link -->
                <div class="tab-pane fade show" id="bobina" role="tabpanel" aria-labelledby="bobina-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <div class="col-lg-10">
                            <br>
                            <h4>Código de bobina :</h4>
                        </div>
                        <div class="col-lg-8">
                            <form id="form_consulta_inventario_bob">
                                <div class="form-group input-group">
                                    <input type="number" value="1" name="tipo_art" hidden="true" />
                                    <input type="text" class="form-control" name="codigo" placeholder="Ingrese el número de parte del producto">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <br>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="dt_consulta_inventario_bob" class=" table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                    <thead class="bg-layout">
                                        <tr>
                                            <td><b>Código <i class="fa fa-qrcode"></i></b></td>
                                            <td><b>Producto</td>
                                            <td><b>Descripción</td>
                                            <td><b>Cantidad</td>
                                            <td><b>Opciones</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-------------------------------------------------- MODAL DETALLE INFO ETIQUETA --------------------------------------------->
                        <div class="modal fade" id="info_item_bob" role="dialog" aria-labelledby="exampleModal_info_item_tec" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header header_aco">
                                        <div class="img_modal">
                                            <p> </p>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="modal-title" id="nombre_producto_bob"></h5>
                                            </div>
                                            <div class="col">
                                                <p class="text-end" style="font-size: 15px;">Cantidad :<span id="cantidad_producto_bob"></span></p>
                                            </div>
                                        </div>

                                        <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
                                    </div>
                                    <div class="modal-body">
                                        <div class="recuadro">
                                            <div class="container-fluid">
                                                <br>
                                                <h3 class="text-center">Análisis de Productos</h3><br>
                                                <table id="dt_infor_producto_bob" class="table table-responsive-lg  table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Ancho</th>
                                                            <th>M Lineales</th>
                                                            <th>Entrada</th>
                                                            <th>Salida</th>
                                                            <th>Total</th>
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
                <!-- cuarto link -->
                <div class="tab-pane fade show" id="ubicacion" role="tabpanel" aria-labelledby="ubicacion-tab">
                    <br>
                    <div class="container-fluid recuadro">
                        <div class="col-lg-10">
                            <br>
                            <h4>Consulta Ubicación :</h4>
                        </div>
                        <div class="col-lg-8">
                            <form id="form_consulta_inventario_ubi">
                                <div class="form-group input-group">
                                    <input type="text" class="form-control" name="ubicacion" placeholder="Ingrese el la ubicación">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <br>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="dt_consulta_inventario_ubi" class=" table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                    <thead class="bg-layout">
                                        <tr>
                                            <td><b>Ubicación</td>
                                            <td><b>Código <i class="fa fa-qrcode"></i></b></td>
                                            <td><b>Descripción</td>
                                            <td><b>Cantidad</td>
                                            <td><b>Accion</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div id="btn_cambio_ubicacion" class="m-auto p-2 justify-content-center" style="display: none;">
                            <button class="btn btn-success" type="button" id='btn_cambio'> <i class="fas fa-exchange-alt"></i> Cambiar Ubicación</button>
                        </div>
                        <!-- modal de cambio de ubicacion  -->
                        <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" id='modal_cambio_ubicacion'>
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="titulo_modal"></h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="contenido_modal"></div>
                                    <div class="modal-footer" id="modal_footer"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/almacen/js/vista_ consulta_inv.js"></script>
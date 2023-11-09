<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12" id="tabla_documentos">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Modificar Documento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="fecha_fac-tab" data-bs-toggle="tab" href="#fecha_fac" role="tab" aria-controls="fecha_fac" aria-selected="true">Modificar Fecha Factura</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <div class="panel-heading text-center mb-3">
                        <h3><b>Modificar Documento</b></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-10">
                                <div class="form-group row mb-3">
                                    <label for="numero_factura_consulta" class="col-2">N° lista de empaque:</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control" id="numero_factura_consulta">
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="text-center mb-3">
                                    <button class="btn btn-primary btn-lg boton-x" type="button" id="consulta_lista_de_empaque">Consultar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="ConsultarListaEmpaque">
                        <br>
                        <div class="panel-heading text-center mb-3">
                            <h3><b>Documento A Modificar</b></h3>
                        </div>
                        <form id="form-facturacion" name="modificar_lista">
                            <br>
                            <div class="mb-3">
                                <div class="text-center">
                                    <h3>
                                        <b>LISTA DE EMPAQUE N°
                                            <span style="color: red" id="num_lista_empaque"></span>
                                            <span style="display: none" id="id_tipo_documento"></span>
                                        </b>
                                    </h3>
                                </div>
                                <br>
                                <div class="container-fluid">
                                    <div class="">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text">Razon social cliente:</label>
                                            <span id="nombre_empresa" class="form-control"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <div class="input-group">
                                                <label class="input-group-text">Orden de Compra:</label>
                                                <span class="form-control" id="orden_compra"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="input-group">
                                                <label class="input-group-text">Pais:</label>
                                                <span class="form-control" id="nombre_pais"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="input-group">
                                                <label class="input-group-text">Departamento:</label>
                                                <span class="form-control" id="nombre_departamento"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <div class="input-group">
                                                <label class="input-group-text">Ciudad:</label>
                                                <span id="nombre_ciudad" class="form-control"></span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group">
                                                <label class="input-group-text">Dirección:</label>
                                                <span id="direccion" class="form-control"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <div class="input-group">
                                                <label class="input-group-text">Numero Pedido:</label>
                                                <span class="form-control cabecera" id="num_pedido"></span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group">
                                                <label class="input-group-text">Doc.Relacionado:</label>
                                                <span class="form-control cabecera" id="documento_relacionado"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div>
                                <!--  style="border:2px solid #bdbdbd;padding:10px"> -->
                                <div class="row">
                                    <h4 style="text-align: center">ITEMS LISTA DE EMPAQUE</h4>
                                </div>
                                <div class="container-fluid">
                                    <div class="row">
                                        <table id="tabla_productos_items" style="background: white;" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" width="100%">
                                            <thead style="background:#0d1b50;color:white">
                                                <tr>
                                                    <th>Codigo</th>
                                                    <th>Cantidad</th>
                                                    <th>Descripción</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                        <hr>
                                        <br>
                                    </div>
                                    <br>
                                    <!-- <div class="col-lg-12 col-md-12" style="text-align: center;">
                            <button class="btn btn-primary btn-lg boton-x" type="submit" id="guardar" name="enviar">Grabar</button>
                        </div>
                        <br> -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- segundo link -->
                <div class="tab-pane fade show" id="fecha_fac" role="tabpanel" aria-labelledby="fecha_fac-tab">
                    <br>
                    <div class="panel-heading text-center mb-3">
                        <h3><b>Modificar Fecha Factura</b></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-10">
                                <div class="form-group row mb-3">
                                    <label for="num_lista" class="col-2">N° lista de empaque:</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control" id="num_lista">
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="text-center mb-3">
                                    <button class="btn btn-primary btn-lg boton-x" type="button" id="consulta_num">Consultar</button>
                                </div>
                            </div>
                        </div>
                        <form id="form_fecha">
                            <div class="mb-3">
                                <div class="text-center">
                                    <h3>
                                        <b>LISTA DE EMPAQUE N°
                                            <span style="color: red" id="num_documento"></span>
                                        </b>
                                    </h3>
                                </div>
                                <br>
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="col-form-label">Cliente:</label>
                                            <input type="text" readonly id="cliente" class="form-control"></input>
                                        </div>
                                        <div class="col-4">
                                            <label class="col-form-label">Numero Pedido:</label>
                                            <input type="number" readonly class="form-control cabecera" id="pedido"></input>
                                        </div>
                                        <div class="col-4">
                                            <label class="col-form-label">Fecha Factura:</label>
                                            <input type="date" class="form-control cabecera" id="fecha_factura"></input>
                                            <input type="hidden" class="form-control cabecera" id="fecha_factura_ant"></input>
                                            <input type="hidden" class="form-control cabecera" id="id_control_factura"></input>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/facturacion/js/vista_modificar_documentos.js"></script>
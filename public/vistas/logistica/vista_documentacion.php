<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12" id="tabla_facturacion_listado">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Certificado</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Cartas</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="container">
                        <form class="panel panel-default" name="certificado_calidad" id="certificado_calidad">
                            <div class="panel-heading text-center">
                                <h2>Certificado de calidad</h2>
                            </div>
                            <div class="row panel-body">
                                <div class="col-10">
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" for="num_lista_empaque">Numero lista de Empaque:</label>
                                        <input type="text" class="form-control num_pedio" id="num_lista_empaque" name="num_lista_empaque">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <input type="submit" class="btn btn-success" value="Enviar" id="consulta_certificado">
                                </div>
                            </div>
                        </form>
                        <br>
                        <form method="POST" enctype="multipart/form-data" id="formulario_certificados" name="formulario_certificados">
                            <table id="tabla_certifi_1" class="table table-bordered table-responsive table-hover mb-3" cellspacing="0" width="100%">
                                <thead style="background: #002b5f;color: white">
                                    <tr>
                                        <th>Cod. Producto</th>
                                        <th>Referencia</th>
                                        <th>Cantidad</th>
                                        <th>Lote</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <div class="mb-3">
                                <div class="row">
                                    <label class="col-2" for="nombre_empresa">Nombre Cliente:</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row">
                                    <label class="col-2" for="orden_compra">Orden Compra:</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control" id="orden_compra" name="orden_compra">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row">
                                    <label class="col-2" for="vencimiento">Vencimiento:</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control" id="vencimiento" name="vencimiento">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 text-center">
                                <button class="btn btn-success" id="boton_certificado" type="submit">Descargar Certificado<i class="fa fa-download boton_cambio_op"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Segundo link -->
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/logistica/js/vista_documentacion.js"></script>
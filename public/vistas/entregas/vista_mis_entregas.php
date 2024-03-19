<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Mis Entregas</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <input type="hidden" id="id_persona" value="<?= $_SESSION['usuario']->getId_persona(); ?>">
                    <input type="hidden" id="roll" value="<?= $_SESSION['usuario']->getId_roll(); ?>">
                    <table id="table_mis_entregas" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                        <thead style="background: #002b5f;color: white">
                            <tr>
                                <th>Opciones</th>
                                <th>Pedido</th>
                                <th>Documento</th>
                                <th>Cliente</th>
                                <th>Orden Ruta</th>
                                <th>Ruta</th>
                                <th>Dirección Entrega</th>
                                <th>Transportador</th>
                                <th>Forma Pago</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para el arreglo de la Entrega condicion -->
<div class="modal fade bd-example-modal-lg" id="entregaModal" tabindex="-1" role="dialog" aria-labelledby="entregaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" id="cambio_ml_entregados">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="entregaModalLabel">Motivo | Retorno Entregas</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" style="border: 1px solid #ccc;padding: 10px;border-radius: 5px">
                    <label for="motivo-eleccion">Elija el motivo:</label>
                    <select class="form-control" id="motivo-eleccion">
                        <option value="0"></option>
                        <option value="Cambio Dirección">Cambio Dirección</option>
                        <option value="Fuera De tiempo">Fuera De Tiempo</option>
                        <option value="Inconsistencia en Documentación">Inconsistencia en Documentación</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" data-id="3" data-bs-dismiss="modal" id="envio-motivo">Grabar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para el orden de las Entregas -->
<div class="modal fade bd-example-modal-sm" id="orden_rutaModal" tabindex="-1" role="dialog" aria-labelledby="orden_rutaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form class="modal-content" id="orden_ruta_entrega">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="orden_rutaModalLabel">Orden Ruta Entregas</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" style="border: 1px solid #ccc;padding: 10px;border-radius: 5px">
                    <label for="orden_ruta" class="form-label">Orden Ruta Entrega:</label>
                    <input type="text" id="orden_ruta" class="form-control orden_ruta" name="orden_ruta" style='border:black solid 1px;'>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" data-id="" id="envio_orden_ruta">Grabar</button>
            </div>
        </form>
    </div>
</div>


<!-- modal para agregar la imagen de la entrega -->
<div class="modal fade" id="ModalImagen" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ModalImagenLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalImagenLabel">Comprobante de entrega</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-center"><b class="color-danger">Nota:</b> Se debe Realizar la carga del documento firmado por el cliente.</h6>
                <div class="mb-3 col-12 row cuadro_imagenes" style="margin-left: 29%;">
                    <div class="image-upload">
                        <label for="foto_entrega" style="display:block;">
                            <spam id="imagen_entrega"><i class="fas fa-camera camara"></i></spam>
                        </label>
                        <input class="d-none" id="foto_entrega" type="file" name="foto_entrega" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="enviar_entrega">Enviar</button>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/entregas/js/vista_mis_entregas.js"></script>
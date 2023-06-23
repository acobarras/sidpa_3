<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12" id="tabla_facturacion_listado">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Pendientes Radicación</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="panel-heading text-center mb-3">
                        <h3><b>Documentos Pendientes Por Radicar</b></h3>
                    </div>
                    <table id="table_pendientes_radicar" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                        <thead style="background: #002b5f;color: white">
                            <tr>
                                <th>Cliente</th>
                                <th>Documento</th>
                                <th>Pedido</th>
                                <th>Asesor</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                    </table>
                    <br>
                    <div class="row">
                        <div class="form-group col-4">
                            <label for="remision_factura">Tipo Documento</label>
                            <select id="remision_factura" class="form-control">
                                <option value="0"></option>
                                <option value="8">Factura <?= NOMBRE_EMPRESA ?> S.A.S.</option>
                                <option value="9">Factura <?= NOMBRE_EMPRESA ?> Colombia</option>
                                <option value="99">Finalizar sin factura</option>
                            </select>
                        </div>
                        <div class="form-group col-4">
                            <label for="nombre_empresa">N° Documento:</label>
                            <span class="form-control" id="numero_factura">Sin Asignar</span>
                            <input type="hidden" id="numero_factura_consulta">
                            <!-- <input class="form-control" type="number" disabled id="numero_factura"> -->
                        </div>
                        <div class="text-center col-4">
                            <button class="btn btn-primary btn-lg boton-x" type="button" id="cambio_remision">Grabar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/facturacion/js/vista_pendientes_radicar.js"></script>
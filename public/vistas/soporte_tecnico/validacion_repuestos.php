<div id="principal_gestion" class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="laboratorio_soporte" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab-solicitud" role="tablist">
                        <a class="nav-link active" id="nav-solicitud-tab" data-bs-toggle="tab" href="#nav-solicitud" role="tab" aria-controls="nav-solicitud" aria-selected="true">Validacion Repuestos
                        </a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent-solicitud">
                    <div class="tab-pane fade show active" id="nav-solicitud" role="tabpanel" aria-labelledby="nav-solicitud-tab">
                        <div class="container-fluid">
                            <br>
                            <div class="mb-3 text-center row">
                                <h1 class="col-md-12 col-md-offset-4 ">Validacion de Repuestos en Inventario</h1>
                            </div>
                            <div class="table-responsive">
                                <div class="container-fluid">
                                    <table id="tabla_repuestos" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                        <thead style="background:#0d1b50;color:white">
                                            <tr>
                                                <td>Id diagnostico Item</td>
                                                <td>Consecutivo</td>
                                                <td>Cliente</td>
                                                <td>Item</td>
                                                <td>Equipo</td>
                                                <td>Serial</td>
                                                <td>Estado Diagnostico</td>
                                                <td>Opción</td>
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
</div>

<!-------------------------------------------------- MODAL INVENTARIO --------------------------------------------->
<div class="modal fade" id="modal_inventario" role="dialog" aria-labelledby="LargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <h3 class="modal-title">Consulta Inventario</h3>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <div class="recuadro">
                    <div class="container-fluid"><br>
                        <table id="tabla_inventario" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                            <thead style="background: black;color:white">
                                <tr>
                                    <td>Producto</td>
                                    <td>Cantidad</td>
                                </tr>
                            </thead>
                        </table>
                    </div><br>
                </div>
            </div>
            <div class="modal-footer">
                <button id="descontar_inv" type="button" class="btn btn-success inventario" value="1">Descontar</button>
                <button id="comprar" type="button" class="btn btn-info inventario" value="2">Comprar</button>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/soporte_tecnico/js/validacion_repuestos.js"></script>
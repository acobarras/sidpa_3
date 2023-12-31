<div id="principal_gestion" class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="laboratorio_soporte" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab-solicitud" role="tablist">
                        <a class="nav-link active" id="nav-solicitud-tab" data-bs-toggle="tab" href="#nav-solicitud" role="tab" aria-controls="nav-solicitud" aria-selected="true">Aprobacion De Cotización</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent-solicitud">
                    <div class="tab-pane fade show active" id="nav-solicitud" role="tabpanel" aria-labelledby="nav-solicitud-tab">
                        <div class="container-fluid">
                            <br>
                            <div class="mb-3 text-center row">
                                <h1 class="col-md-12 col-md-offset-4 ">Aprobacion De Cotización</h1>
                            </div>
                            <div class="table-responsive">
                                <div class="container-fluid">
                                    <table id="tabla_aprobacion" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
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

<!-- ------------------------------------------------ MODAL REPUESTOS ------------------------------------------- -->
<div class="modal fade" id="modal_repuestos" role="dialog" aria-labelledby="LargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <h3 class="modal-title">Repuestos</h3>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <div class="recuadro">
                    <div class="container-fluid"><br>
                        <table id="tabla_repuestos" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
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
        </div>
    </div>
</div>
<!-- ------------------------------------------------ MODAL RECOTIZAR ------------------------------------------- -->
<div class="modal fade" id="modal_recotizar" role="dialog" aria-labelledby="LargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <h3 class="modal-title">Recotizar Repuestos <span id="nombre_equipo"></span></h3>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <div class="recuadro" id="tabla_items">
                    <div class="container-fluid"><br>
                        <table id="tabla_recotizar" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                            <thead style="background: black;color:white">
                                <tr>
                                    <td>Producto</td>
                                    <td>Cantidad</td>
                                    <td>valor</td>
                                    <td>Opciones</td>
                                </tr>
                            </thead>
                        </table>
                    </div><br>
                    <center>
                        <button class="btn btn-primary generar_pdf" id="generar_pdf" data="">Generar Cotización</button>
                        <button class="btn btn-warning agregar_rep" data="">Agregar Repuesto</button>
                    </center>
                </div>
                <div class="recuadro p-4" id="agregar_item" style="display: none">
                    <form id="formulario_agregar">
                        <input type="hidden" id="id_diagnostico_ag" name="id_diagnostico">
                        <input type="hidden" id="item_ag" name="item">
                        <input type="hidden" id="cotizacion_ag" name="num_cotizacion">
                        <div class="row">
                            <div class="col-md-5 col-sm-12">
                                <label for="id_producto" class="form-label fw-bold">Producto:</label>
                                <select class="form-select select_2" id="id_producto" name="id_producto" style="width: 100%;">
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label class="form-label fw-bold" for="moneda">Moneda:</label>
                                <select class="form-select" name="moneda" id="moneda">
                                    <option value="1">Pesos</option>
                                    <option value="2">Dolar</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <label class="form-label fw-bold" for="valor">Precio:</label>
                                <input type="number" class="form-control bg-white" name="valor" id="valor">
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <label class="form-label fw-bold" for="cantidad">Cantidad:</label>
                                <input type="number" class="form-control bg-white" name="cantidad" id="cantidad">
                            </div>
                            <div class="col-12 d-flex justify-content-center p-2">
                                <button class="btn btn-success agregar_producto m-2" title='Agregar producto' type="submit" id="agregar_pro">Agregar</button>
                                <button class="btn btn-primary agregar_rep m-2" title='Regresar' type="button">Regresar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/soporte_tecnico/js/vista_aprobacion.js"></script>
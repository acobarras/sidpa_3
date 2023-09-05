<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="cotiza_visita-tab" data-bs-toggle="tab" href="#cotiza_visita" role="tab" aria-controls="nav-clintes" aria-selected="true">Cotizacion Visitas</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <!--------------------------------------------------  CASO REMOTO --------------------------------------------->
                    <div class="tab-pane fade show active" id="cotiza_visita" role="tabpanel" aria-labelledby="cotiza_visita-tab">
                        <div class="container-fluid mt-3 mb-3">
                            <div class="recuadro">
                                <div class="container-fluid">
                                    <br>
                                    <div class="table-responsive">
                                        <div class="container-fluid">
                                            <br>
                                            <h2 style="text-align:center">Tabla Cotizaciones Visitas</h2>
                                            <hr>
                                            <br>
                                            <input type="hidden" id="id_persona_cotiza" value="<?= $_SESSION['usuario']->getId_persona(); ?>">
                                            <input type="hidden" id="roll_persona_cotiza" value="<?= $_SESSION['usuario']->getId_roll(); ?>">
                                            <table id="tb_cotizacion_visita" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                                <thead style="background:#0d1b50;color:white">
                                                    <tr>
                                                        <td>Id diagnostico</td>
                                                        <td>Cliente</td>
                                                        <td>Direccion</td>
                                                        <td>Estado</td>
                                                        <td>Fecha crea</td>
                                                        <td></td>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- MODAL FORMULARIO COTIZACION -->
                    <div class="modal fade" id="cotiza" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-center" id="staticBackdropLabel">Formulario de Cotizacion</h5>
                                    <button style="color:black;" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="form_cotizar">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="mb-3 col-4">
                                                        <label for="codigo_producto" class="form-label">Codigo Producto</label>
                                                        <select class="form-select" aria-label="Default select example" id="producto_cotiza" name="codigo_producto">
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-4">
                                                        <label for="cantidad_cotiza_visita" class="form-label">Cantidad</label>
                                                        <input type="number" class="form-control" id="cantidad_cotiza_visita" name="cantidad_cotiza_visita">
                                                    </div>
                                                    <div class="mb-3 col-4">
                                                        <label for="valor_cotiza_visita" class="form-label">Valor Unidad visita</label>
                                                        <input type="number" class="form-control" id="valor_cotiza_visita" name="valor_cotiza_visita">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button id="cerrar_cotiza" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button id="enviar_cotiza" type="button" class="btn btn-primary">GRABAR</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- MODAL AGENDAR VISITA -->
                    <div class="modal fade" id="agendar_visita" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-center" id="staticBackdropLabel">Formulario Agendar Visita</h5>
                                    <button style="color:black;" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="form_agendar_visita">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="mb-3 col-6">
                                                        <label for="persona_visita" class="form-label">Persona Tecnica</label>
                                                        <select class="form-select" aria-label="Default select example" id="persona_visita" name="persona_visita">
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-6">
                                                        <label for="fecha_visita" class="form-label">Fecha de visita</label>
                                                        <input type="date" class="form-control" id="fecha_visita" name="fecha_visita">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button id="cerrar_agenda_visita" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button id="enviar_agenda_visita" type="button" class="btn btn-primary">AGENDAR</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- MODAL REAGENDAR VISITA -->
                    <div class="modal fade" id="reagendar_visita" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-center" id="staticBackdropLabel">Formulario Reagendar Visita</h5>
                                    <button style="color:black;" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="form_reagendar_visita">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="mb-3 col-6">
                                                        <label for="reagenda_persona" class="form-label">Visitador</label>
                                                        <select class="form-select" aria-label="Default select example" id="reagenda_persona" name="reagenda_persona">
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-6">
                                                        <label for="reagendar_fecha" class="form-label">Fecha de visita</label>
                                                        <input type="date" class="form-control" id="reagendar_fecha" name="reagendar_fecha">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button id="cerrar_reagenda" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button id="enviar_reagendar_visita" type="button" class="btn btn-primary">REAGENDAR</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/soporte_tecnico/js/vista_cotizacion.js"></script>
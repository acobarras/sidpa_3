<div id="principal_reparacion" class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="laboratorio_soporte" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab-solicitud" role="tablist">
                        <a class="nav-link active" id="nav-solicitud-tab" data-bs-toggle="tab" href="#nav-solicitud" role="tab" aria-controls="nav-solicitud" aria-selected="true">Ejecución De Aprobacion</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent-solicitud">
                    <div class="tab-pane fade show active" id="nav-solicitud" role="tabpanel" aria-labelledby="nav-solicitud-tab">
                        <div class="container-fluid">
                            <br>
                            <div class="mb-3 text-center row">
                                <h1 class="col-md-12 col-md-offset-4 ">Ejecución De Reparación</h1>
                            </div>
                            <div class="table-responsive">
                                <div class="container-fluid">
                                    <table id="tabla_ejecucion" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
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

<!-- MOSTRAR REPUESTOS -->
<div class="container-fluid mt-3 mb-3" id="repuestos" style="display: none;">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <br>
            <div>
                <button class="btn btn-primary" id="regresar">Regresar</button>
            </div>
            <br>
            <div>
                <div class="text-center py-3">
                    <h1>Repuestos <span style="color: red" id="titulo_equipo"></span></h1>
                </div>
                <div>
                    <div class="container-fluid">
                        <div class="mb-4" style="border:2px solid #bdbdbd;">
                            <div class="form-group" style="padding-left:2%;padding-right:2%;">
                                <div class="row mb-2 py-4" id="datos_repuestos">
                                    <div class="table-responsive">
                                        <div class="container-fluid">
                                            <table id="tabla_repuestos" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                                <thead style="background:#0d1b50;color:white">
                                                    <tr>
                                                        <td>Codigo Producto</td>
                                                        <td>Descripción Productos</td>
                                                        <td>Cantidad</td>
                                                        <td>Estado Repuesto</td>
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
    </div>
</div>

<!-- MODAL ASIGNAR TECNICO -->
<div class="modal fade" id="asignar_tec" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="staticBackdropLabel">Agendar Técnico</h5>
                <button style="color:black;" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_selec_tecnico">
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="form-group">
                            <div class="row">
                                <div class="mb-3 col-12">
                                    <label for="id_persona_reparacion" class="form-label">Técnico</label>
                                    <select class="form-select" aria-label="Default select example" id="id_persona_reparacion" name="id_persona_reparacion">
                                    </select>
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="fecha_ejecucion" class="form-label">Fecha Ejecución</label>
                                    <input type="date" class="form-control" id="fecha_ejecucion" name="fecha_ejecucion">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="cerrar_selec" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="enviar_selec" type="button" class="btn btn-primary">Asignar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/soporte_tecnico/js/ejecucion_reparacion.js"></script>
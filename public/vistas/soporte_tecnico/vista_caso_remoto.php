<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="caso-remoto-tab" data-bs-toggle="tab" href="#caso-remoto" role="tab" aria-controls="nav-clintes" aria-selected="true">Visitas Remotas</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <!--------------------------------------------------  CASO REMOTO --------------------------------------------->
                    <div class="tab-pane fade show active" id="caso-remoto" role="tabpanel" aria-labelledby="caso-remoto-tab">
                        <div class="container-fluid mt-3 mb-3">
                            <div class="recuadro">
                                <div class="container-fluid">
                                    <!--tabla pedidos asesor-->
                                    <br>
                                    <div class="table-responsive">
                                        <div class="container-fluid">
                                            <br>
                                            <h2 style="text-align:center">Tabla Casos Remotos </h2>
                                            <hr>
                                            <br>
                                            <table id="tb_casos_remotos" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
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
                                    <!-- Modal -->
                                    <div class="modal fade" id="cierre_caso" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-center" id="staticBackdropLabel">Cierre diagnostico remoto</h5>
                                                    <button style="color:black;" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form id="form_cerrar_caso">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <div class="form-group">
                                                                <label class="fw-bolder" for="observacion">Descripción detallada del cierre :</label>
                                                                <textarea class="form-control" id="cerrar_caso_remoto" style="resize: none; height:250px;"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button id="cerrar_diagnos" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button id="enviar_diagnos" type="button" class="btn btn-primary">GRABAR</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--------------------------------------------------  SEGUNDO LINK --------------------------------------------->
                    <div class="tab-pane fade show" id="nav-numero2" role="tabpanel" aria-labelledby="nav-numero2-tab">
                        HOLA2
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/soporte_tecnico/js/vista_caso_remoto.js"></script>
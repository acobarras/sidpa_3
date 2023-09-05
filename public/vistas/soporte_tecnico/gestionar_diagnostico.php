<div id="principal_gestion" class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="laboratorio_soporte" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab-solicitud" role="tablist">
                        <a class="nav-link active" id="nav-solicitud-tab" data-bs-toggle="tab" href="#nav-solicitud" role="tab" aria-controls="nav-solicitud" aria-selected="true">Gestionar Diagnostico</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent-solicitud">
                    <div class="tab-pane fade show active" id="nav-solicitud" role="tabpanel" aria-labelledby="nav-solicitud-tab">
                        <div class="container-fluid">
                            <br>
                            <div class="mb-3 text-center row">
                                <h1 class="col-md-12 col-md-offset-4 ">Gestionar Diagnostico</h1>
                            </div>
                            <div class="table-responsive">
                                <div class="container-fluid">
                                    <table id="tb_gestion_diag" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                        <thead style="background:#0d1b50;color:white">
                                            <tr>
                                                <td>Diagnostico Item</td>
                                                <td>Cliente</td>
                                                <td>Equipo</td>
                                                <td>Serial</td>
                                                <td>Procedimiento</td>
                                                <td>Observaciones y accesorios</td>
                                                <td>Estado Diagnostico</td>
                                                <td></td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class=" col-4 btn btn-success" id="cotizar_repu" type="button">Cotizar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FORMULARIO COTIZAR -->
<div class="container-fluid mt-3 mb-3" id="cotizar" style="display: none;">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <br>
            <div>
                <button class="btn btn-primary" id="regresar">Regresar</button>
            </div>
            <br>
            <div>
                <div class="text-center py-3">
                    <h1>Formulario Cotización <span style="color: red" id="titulo"></span></h1>
                </div>
                <div>
                    <div class="container-fluid">
                        <div class="mb-4" style="border:2px solid #bdbdbd;">
                            <div class="form-group" style="padding-left:2%;padding-right:2%;">
                                <div class="row mb-2 py-4" id="formulario_articulos">
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="col-2 btn btn-success enviar_cotizacion" value="1" id="enviar_cotizacion">Enviar Cotización</button>
                            <button class="col-2 btn btn-info enviar_cotizacion" value="2" id="continuar_diag" type="button">No cotizar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/soporte_tecnico/js/gestionar_diagnostico.js"></script>
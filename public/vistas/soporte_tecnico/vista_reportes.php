<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <nav>
            <div id="contenido" class="px-2 py-2 col-lg-12">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="indicador_visitas-tab" data-bs-toggle="tab" href="#indicador_visitas" role="tab" aria-controls="indicador_visitas" aria-selected="true">Indicador visitas</a>
                    <a class="nav-link " id="indicador_autorizaciones-tab" data-bs-toggle="tab" href="#indicador_autorizaciones" role="tab" aria-controls="indicador_autorizaciones" aria-selected="true">Indicador autorizaciones</a>
                    <a class="nav-link " id="consolidado_pendientes-tab" data-bs-toggle="tab" href="#consolidado_pendientes" role="tab" aria-controls="consolidado_pendientes" aria-selected="true">Consolidado casos pendientes</a>
                    <a class="nav-link " id="comisiones-tab" data-bs-toggle="tab" href="#comisiones" role="tab" aria-controls="comisiones" aria-selected="true">Reporte de comisiones</a>
                    <a class="nav-link " id="descargas-tab" data-bs-toggle="tab" href="#descargas" role="tab" aria-controls="descargas" aria-selected="true">Descargas</a>
                </div>
            </div>
        </nav>
        <div class="tab-content" id="myTabContent">
            <!-- pestañas 1 visitas -->
            <div class="tab-pane fade show active" id="indicador_visitas" role="tabpanel" aria-labelledby="indicador_visitas-tab">
                <div class="container-fluid">
                    <br>
                    <div class="mb-3 text-center row ">
                        <h1 class="col-md-12 col-md-offset-4 ">Indicador visitas agendadas</h1>
                    </div>
                    <div class="recuadro col-md-8 col-12 text-center d-block m-auto p-1">
                        <form  class="m-3" id="form_visitas">
                            <input type="hidden" name="consulta" value="1">
                            <div class="input-group mb-3">
                                <label for="mes_visitas" class="input-group-text">Mes: </label>
                                <select class="form-control" name="mes_visitas" id="mes_visitas">
                                    <option value="0" selected>Selecciona el mes</option>
                                    <?php $i = 1;
                                    foreach (MES_ESP as $mes) { ?>
                                        <option value="<?= $i ?>"><?= $mes ?></option>
                                    <?php $i++;} ?>
                                </select>
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>                                
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <div class="container-fluid">
                            <table style="background: white; margin-left: 0px;" id="tb_indicador_visitas"  class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Diagnostico</th>
                                        <th>Observación</th>
                                        <th>Fecha</th>
                                        <th>Nombre Empresa</th>
                                        <th>Modelo</th>
                                        <th>Número De Serie</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- pestañas 2 Autorizaciones-->
            <div class="tab-pane fade show" id="indicador_autorizaciones" role="tabpanel" aria-labelledby="indicador_autorizaciones-tab">
                <div class="container-fluid">
                    <br>
                    <div class="mb-3 text-center row ">
                        <h1 class="col-md-12 col-md-offset-4 ">Indicador autorizaciones</h1>
                    </div>
                    <div class="recuadro col-md-8 col-12 text-center d-block m-auto p-1">
                        <form class="m-3" id="form_autorizaciones">
                            <input type="hidden" name="consulta" value="2">
                            <div class="input-group mb-3">
                                <label for="mes_autorizaciones" class="input-group-text">Mes: </label>
                                <select class="form-control" name="mes_autorizaciones" id="mes_autorizaciones">
                                    <option value="0" selected>Selecciona el mes</option>
                                    <?php $i = 1;
                                    foreach (MES_ESP as $mes) { ?>
                                        <option value="<?= $i ?>"><?= $mes ?></option>
                                    <?php $i++;} ?>
                                </select>
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>                                
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <div class="container-fluid">
                            <table style="background: white; margin-left: 0px;" id="tb_indicador_autorizacion"  class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Diagnostico</th>
                                        <th>Observación</th>
                                        <th>Fecha</th>
                                        <th>Nombre Empresa</th>
                                        <th>Modelo</th>
                                        <th>Número De Serie</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- pestañas 3 consolidado-->
            <div class="tab-pane fade show" id="consolidado_pendientes" role="tabpanel" aria-labelledby="consolidado_pendientes-tab">
                <div class="table-responsive">
                    <div class="container-fluid">
                        <br>
                        <div class="mb-3 text-center row ">
                            <h1 class="col-md-12 col-md-offset-4 ">Consolidado casos pendientes</h1>
                        </div>
                        <table style="background: white; margin-left: 0px;" id="tb_consolidado"  class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                            <thead style="background:#0d1b50;color:white">
                                <tr>
                                    <th>Diagnostico</th>
                                    <th>Fecha</th>
                                    <th>Observación</th>
                                    <th>Nombre Empresa</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- pestañas 4 comisiones-->
            <div class="tab-pane fade show" id="comisiones" role="tabpanel" aria-labelledby="comisiones-tab">
                <div class="container-fluid">
                    <br>
                    <div class="mb-3 text-center row ">
                        <h1 class="col-md-12 col-md-offset-4 ">Reporte comisiones</h1>
                    </div>
                    <div class="recuadro col-md-9 col-12 text-center d-block m-auto p-1">
                        <form  class="m-3" id="form_comisiones">
                            <input type="hidden" name="consulta" value="4">
                            <div class="input-group mb-3">
                                <label for="mes_comisiones" class="input-group-text">Mes: </label>
                                <select class="form-control" name="mes_comisiones" id="mes_comisiones">
                                    <option value="0" selected>Selecciona el mes</option>
                                    <?php $i = 1;
                                    foreach (MES_ESP as $mes) { ?>
                                        <option value="<?= $i ?>"><?= $mes ?></option>
                                    <?php $i++;} ?>
                                </select>
                                <label for="id_persona_reparacion" class="input-group-text">Técnico: </label>
                                <select class="form-control" name="id_persona_reparacion" id="id_persona_reparacion"></select>
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>                                
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <div class="container-fluid">
                            <table style="background: white; margin-left: 0px;" id="tb_comisiones"  class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Nombre Empresa</th>
                                        <th>Diagnostico</th>
                                        <th>Modelo</th>
                                        <th>Serial</th>
                                        <th>Procedimiento</th>
                                        <th>Observaciones</th>
                                        <th>Tipo Impacto</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- pestañas 5 -->
            <div class="tab-pane fade show" id="descargas" role="tabpanel" aria-labelledby="descargas-tab">
                <div class="container-fluid">
                    <br>
                    <div class="mb-3 text-center row ">
                        <h1 class="col-md-12 col-md-offset-4 ">Descarga e impresión de documentos</h1>
                    </div>
                    <div class="recuadro col-md-10 col-12 text-center d-block m-auto p-1">
                        <div class="m-3">
                            <div class="input-group m-2">
                                <label for="num_acta" class="input-group-text">Número Acta de Entrega : </label>
                                <input autocomplete="off" type="number" class="form-control" name="num_acta" id="num_acta" placeholder="Descargar Acta entrega">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="genera_acta" type="button">Descargar <i class="fa fa-download boton_acta"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="m-3">
                            <div class="input-group m-2">
                                <label for="num_cotiza" class="input-group-text">Número Cotización : </label>
                                <input autocomplete="off" type="number" class="form-control" name="num_cotiza" id="num_cotiza" placeholder="Descargar cotización">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="genera_cotiza" type="button">Descargar <i class="fa fa-download boton_cotiza"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="m-3">
                            <div class="input-group m-2">
                                <label for="num_diag_remision" class="input-group-text">Número Diagnostico: </label>
                                <input autocomplete="off" type="number" class="form-control" name="num_diag_remision" id="num_diag_remision" placeholder="Descargar remisión o reporte visita">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="genera_remision" type="button">Descargar <i class="fa fa-download boton_remision"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="m-3">
                            <div class="input-group m-2">
                                <label for="num_diag_ingreso" class="input-group-text">Número Diagnostico: </label>
                                <input autocomplete="off" type="text" class="form-control" name="num_diag_ingreso" id="num_diag_ingreso" placeholder="Imprimir marcación de ingreso">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="genera_zpl" type="button">Imprimir <i class="fa fa-download boton_zpl"></i></button>
                                </div>
                            </div>
                        </div>
                    </div><br><br>
                    <div class="div_impresion"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/soporte_tecnico/js/vista_reportes.js"></script>
<script src="<?= PUBLICO ?>/vistas/soporte_tecnico/js/ejecucion_reparacion.js"></script>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_descargar_pdf.js"></script>


<div id="principal_visita" class="container-fluid mt-3 mb-3">
    <div class="container-fluid mt-3 mb-3">
        <div class="recuadro">
            <div class="container-fluid">
                <div id="contenido" class="px-2 py-2 col-md-12">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-link active" id="visita_agendada-tab" data-bs-toggle="tab" href="#visita_agendada" role="tab" aria-controls="nav-clintes" aria-selected="true">Visitas Agendadas</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <!-------------------------------------------------- VISITAS AGENDADAS--------------------------------------------->
                        <div class="tab-pane fade show active" id="visitas_agendadas" role="tabpanel" aria-labelledby="visitas_agendadas-tab">
                            <div class="container-fluid mt-3 mb-3">
                                <div class="recuadro">
                                    <div class="container-fluid">
                                        <br>
                                        <div class="table-responsive">
                                            <div class="container-fluid">
                                                <br>
                                                <h2 style="text-align:center">Tabla Visitas Agendadas</h2>
                                                <hr>
                                                <br>
                                                <input type="hidden" id="id_usuario" value="<?= $_SESSION['usuario']->getId_persona(); ?>">
                                                <input type="hidden" id="roll_usuario" value="<?= $_SESSION['usuario']->getId_roll(); ?>">
                                                <table id="tb_visita_agendada" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                                    <thead style="background:#0d1b50;color:white">
                                                        <tr>
                                                            <td>Id diagnostico</td>
                                                            <td>Cliente</td>
                                                            <td>Direccion</td>
                                                            <td>Fecha Visita</td>
                                                            <td>Usuario Visita</td>
                                                            <td>Estado</td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt-3 mb-3" id="agregar_item_visita" style="display: none;">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <br>
            <div>
                <button class="btn btn-primary" id="regresar">Regresar</button>
            </div>
            <br>
            <div>
                <div class="mb-3 text-center row">
                    <h1 class="col-md-12 col-md-offset-4 ">Formulario Agregar Equipos Visita</h1>
                </div>
                <div>
                    <div class="container-fluid">
                        <form id="form_agregar_equipo">
                            <div class="form-group">
                                <div class="row">
                                    <div class="mb-3 col-md-4 col-sm-12">
                                        <label for="fecha_ingreso" class="form-label">Fecha Ingreso</label>
                                        <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" disabled>
                                    </div>
                                    <div class="mb-3 col-md-4 col-sm-12">
                                        <label for="equipo" class="form-label">Equipo</label>
                                        <input type="text" class="form-control" id="equipo" name="equipo">
                                    </div>
                                    <div class="mb-3 col-md-4 col-sm-12">
                                        <label for="serial" class="form-label">Serial</label>
                                        <input type="text" class="form-control" id="serial" name="serial">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-3 col-md-6 col-sm-12">
                                        <label for="procedimiento" class="form-label">Procedimiento</label>
                                        <textarea class="form-control" id="procedimiento" name="procedimiento"></textarea>
                                    </div>
                                    <div class="mb-3 col-md-6 col-sm-12">
                                        <label for="accesorios" class="form-label">Observaciones</label>
                                        <textarea class="form-control" id="accesorios" name="accesorios"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-success" id="cargar_item" type="button">Agregar</button>
                            </div>
                        </form>
                        <div class="row">
                            <div class="mb-3 col-md-8 col-sm-12">
                                <label for="nota" class="form-label">Nota</label>
                                <textarea class="form-control" id="nota" name="nota"></textarea>
                            </div>
                            <div class="mb-3 col-md-4 col-sm-12">
                                <label for="recibido" class="form-label">Recibido Por</label>
                                <input type="text" class="form-control" id="recibido" name="recibido">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <div class="container-fluid">
                                <table id="tb_item_agregados" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                    <thead style="background:#0d1b50;color:white">
                                        <tr>
                                            <td>Equipo</td>
                                            <td>Serial</td>
                                            <td>Procedimiento</td>
                                            <td>Observaciones</td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="text-center">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#firmacliente">
                                ENVIAR EQUIPOS
                            </button>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="firmacliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="firmaclienteLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="firmaclienteLabel">Capturar Firma Cliente</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="wrapper">
                                        <canvas id="capturafirmacliente" class="signature-pad" width="400" height="200"></canvas>
                                    </div>
                                    <button class="btn btn-primary col-6" id="borrar_firma">borrar Firma</button>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="button" id="enviar_datos" class="btn btn-primary">Enviar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="div_impresion"></div>
            </div>
        </div>
    </div>
</div>
<!-- ------------------------------------------------ MODAL INSTALACION ------------------------------------------- -->
<div class="modal fade" id="modal_instalacion" role="dialog" aria-labelledby="LargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <h3 class="modal-title">Información de Instalación</h3>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <div class="recuadro">
                    <div class="container-fluid"><br>
                        <form id="form_instalacion">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label for="equipo" class="form-label">Referencia Equipo</label>
                                        <input type="text" class="form-control" id="equipo" name="equipo">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="serial" class="form-label">Serial Equipo</label>
                                        <input type="text" class="form-control" id="serial" name="serial">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label for="codigo_producto" class="form-label">Codigo Producto</label>
                                        <select class="form-select" aria-label="Default select example" id="producto_cotiza" name="codigo_producto">
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="justify-content: center;">
                                    <div class="wrapper">
                                        <canvas id="captura_firma" class="signature-pad" width="400" height="200"></canvas>
                                    </div>
                                    <button class="btn btn-primary col-6" id="borrar_firma">borrar Firma</button>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="cancelar_equipo" type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                <button id="enviar_equipo" type="submit" class="btn btn-success">Enviar</button>
                            </div>
                        </form>
                    </div><br>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/soporte_tecnico/js/visitas_agendadas.js"></script>
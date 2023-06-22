<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Reportar Mi Cargue</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="container-fluid py-3">
                        <form id="form-cargue">
                            <div class="panel-heading text-center mb-3">
                                <h3><b>REPORTE SU CARGUE</b></h3>
                            </div>
                            <div class="row">
                                <div class="col-10">
                                    <div class="form-group row mb-3">
                                        <label for="numero_factura_consulta" class="col-2">N° lista de empaque:</label>
                                        <div class="col-10">
                                            <input type="text" class="form-control" id="numero_factura_consulta">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="text-center mb-3">
                                        <button class="btn btn-primary btn-lg boton-x" type="button" id="consulta_lista_de_empaque">Consultar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="container-fluid" id="datos_documento">
                        <div class="text-center mb-3">
                            <h2><b>Datos del documento consultado</b></h2>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-6">
                                <div class="form-group row">
                                    <label class="col-2">Fecha Elaboración:</label>
                                    <div class="col-10">
                                        <span class="form-control" style="background: #6c757d30;" id="fecha_factura">N/A</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group row">
                                    <label class="col-3">N° Documento:</label>
                                    <div class="col-9">
                                        <span class="form-control" style="background: #6c757d30;" id="num_lista_empaque">N/A</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label class="col-1">Cliente:</label>
                                    <div class="col-11">
                                        <span class="form-control" style="background: #6c757d30;" id="nombre_empresa">N/A</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-4">
                                <div class="form-group row">
                                    <label class="col-3">Orden de Compra:</label>
                                    <div class="col-9">
                                        <span class="form-control" style="background: #6c757d30;" id="orden_compra">N/A</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group row">
                                    <label class="col-2">Pais:</label>
                                    <div class="col-10">
                                        <span class="form-control" style="background: #6c757d30;" id="nombre_pais">N/A</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group row">
                                    <label class="col-2">Departamento:</label>
                                    <div class="col-10">
                                        <span class="form-control" style="background: #6c757d30;" id="nombre_departamento">N/A</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-4">
                                <div class="form-group row">
                                    <label class="col-3">Ciudad:</label>
                                    <div class="col-9">
                                        <span class="form-control" style="background: #6c757d30;" id="nombre_ciudad">N/A</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group row">
                                    <label class="col-2">Dirección:</label>
                                    <div class="col-10">
                                        <span class="form-control" style="background: #6c757d30;" id="direccion">N/A</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-6">
                                <div class="form-group row">
                                    <label class="col-2">Numero Pedido:</label>
                                    <div class="col-10">
                                        <span class="form-control" style="background: #6c757d30;" id="num_pedido">N/A</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group row">
                                    <label class="col-2">Doc.Relacionado:</label>
                                    <div class="col-10">
                                        <span class="form-control" style="background: #6c757d30;" id="documento_relacionado">N/A</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <table id="tabla_items_pedido" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                <thead style="background: #002b5f;color: white">
                                    <tr>
                                        <th class="borde-right-1" style="width: 20mm">#</th>
                                        <th class="borde-right-1" style="width: 20mm">Lista Empaque</th>
                                        <th class="borde-right-1" style="width: 20mm">Codigo</th>
                                        <th class="borde-right-1" style="width: 20mm">Cantidad</th>
                                        <th style="width: 100mm;">Descripción</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-6">
                                <div class="form-group row mb-3">
                                    <label for="valor_flete" class="col-3">Valor del flete:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="valor_flete">
                                    </div>
                                </div>
                                <p style="color: red;">Por favor digite el valor del flete correspondiente, tenga encuenta agregar los documentos adicionales que se encuentran en el mismo paquete de envío.</p>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group row mb-3">
                                            <label for="nuevo_documento" class="col-3">N° lista de empaque:</label>
                                            <div class="col-9">
                                                <input type="text" class="form-control" id="nuevo_documento">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-success btn-lg" type="button" disabled="true" id="agrega_documento">Agregar Documento</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 text-center">
                            <button class="btn btn-primary btn-lg" type="button" disabled="true" id="reportar_documento">Reportar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/entregas/js/vista_reporte_cargue.js"></script>
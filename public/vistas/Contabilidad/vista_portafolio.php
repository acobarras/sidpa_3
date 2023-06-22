<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="valida_factura-tab" data-bs-toggle="tab" href="#valida_factura" role="tab" aria-controls="valida_factura" aria-selected="true">Valida factura portafolio</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="acobarras_sas-tab" data-bs-toggle="tab" href="#acobarras_sas" role="tab" aria-controls="acobarras_sas" aria-selected="true">Acobarras S.A.S</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="acobarras_col-tab" data-bs-toggle="tab" href="#acobarras_col" role="tab" aria-controls="acobarras_col" aria-selected="true">Acobarras Colombia</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Primer link -->
                    <div class="tab-pane fade show active" id="valida_factura" role="tabpanel" aria-labelledby="valida_factura-tab">
                        <div class="container-fluid">
                            <br>
                            <form id="consulta_factura">
                                <div class="row g-3 align-items-center">
                                    <div class="col-3"></div>
                                    <div class="col-1">
                                        <label for="num_factura" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Núm. Factura</label>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="num_factura">
                                            <button type="submit" class="btn btn-success col-3">Buscar <i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <div class="recuadro collapse consulta_factura_tg">
                                <div class="container-fluid">
                                    <br>
                                    <p class="h2 text-center fw-bolder"><span class="text-danger" id="crea"></span>Factura Nº <span class="text-danger" id="num_factura"></span></p>
                                    <form class="row g-3" id="formulario">
                                        <input type="hidden" name="num_factura" id="num_factura_modifi">
                                        <input type="hidden" name="id_cli_prov" id="id_cli_prov_modifi">
                                        <div class="col-md-3">
                                            <label for="nit" class="form-label">Nit :</label>
                                            <input type="text" class="form-control" name="nit" id="nit_modifi">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="nombre_empresa" class="form-label">Empresa :</label>
                                            <input type="text" class="form-control" name="nombre_empresa" id="nombre_empresa_modifi">
                                        </div>
                                        <div class="col-3">
                                            <label for="fecha_factura" class="form-label">Fecha Factura: </label>
                                            <input type="text" class="form-control datepicker" name="fecha_factura" id="fecha_factura_modifi">
                                            <span id="span_fecha_factura" class="fw-bolder"></span>
                                        </div>
                                        <div class="col-3">
                                            <label for="dias_dados" class="form-label">Dias Dados: </label>
                                            <input type="text" class="form-control" name="dias_dados" id="dias_dados_modifi">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="empresa" class="form-label">Empresa :</label>
                                            <select class="form-select select_2" class="form-control select_2" style="width: 100%;" name="empresa" id="empresa_modifi">
                                                <?php
                                                foreach (PERTENECE as $key => $value) {
                                                    if ($key != 3) {
                                                        if ($key != 0) { ?>
                                                            <option value="<?= $key ?>"><?= $value ?></option>
                                                <?php }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="fecha_vencimiento" class="form-label">Fecha Vencimiento:</label>
                                            <input type="text" class="form-control " name="fecha_vencimiento" id="fecha_vencimiento_modifi">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="id_usuarios_asesor" class="form-label">Vendedor :</label>
                                            <select class="form-select select_2" class="form-control select_2" style="width: 100%;" name="id_usuarios_asesor" id="id_usuarios_asesor">
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <div class='select_acob text-center'>
                                                <label for="iva" class="form-label">IVA :</label>
                                                <br>
                                                <input type="checkbox" name="iva" id="iva" data="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_etiquetas" class="form-label">Valor Neto Etiquetas :</label>
                                            <input type="text" class="form-control totales" name="total_etiquetas" id="total_etiquetas_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_etiquetas_iva" class="form-label">Valor Total Etiquetas :</label>
                                            <input type="text" class="form-control" name="total_etiquetas_iva" id="total_etiquetas_iva_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_cintas" class="form-label">Valor Neto Cintas :</label>
                                            <input type="text" class="form-control totales" name="total_cintas" id="total_cintas_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_cintas_iva" class="form-label">Valor Total Cintas :</label>
                                            <input type="text" class="form-control" name="total_cintas_iva" id="total_cintas_iva_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_alquiler" class="form-label">Valor Neto Alquiler :</label>
                                            <input type="text" class="form-control totales" name="total_alquiler" id="total_alquiler_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_alquiler_iva" class="form-label">Valor Total Alquiler :</label>
                                            <input type="text" class="form-control" name="total_alquiler_iva" id="total_alquiler_iva_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_tecnologia" class="form-label">Valor Neto Hardware :</label>
                                            <input type="text" class="form-control totales" name="total_tecnologia" id="total_tecnologia_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_tecnologia_iva" class="form-label">Valor Total Hardware :</label>
                                            <input type="text" class="form-control" name="total_tecnologia_iva" id="total_tecnologia_iva_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_soporte" class="form-label">Valor Neto Soporte :</label>
                                            <input type="text" class="form-control totales" name="total_soporte" id="total_soporte_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_soporte_iva" class="form-label">Valor Total Soporte :</label>
                                            <input type="text" class="form-control" name="total_soporte_iva" id="total_soporte_iva_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_fletes" class="form-label">Valor Neto Fletes :</label>
                                            <input type="text" class="form-control totales" name="total_fletes" id="total_fletes_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_fletes_iva_modifi" class="form-label">Valor Total Fletes :</label>
                                            <input type="text" class="form-control" name="total_fletes_iva_modifi" id="total_fletes_iva_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_m_prima" class="form-label">Valor Neto M. Prima :</label>
                                            <input type="text" class="form-control totales" name="total_m_prima" id="total_m_prima_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_m_prima_iva_modifi" class="form-label">Valor Total M. Prima :</label>
                                            <input type="text" class="form-control" name="total_m_prima_iva_modifi" id="total_m_prima_iva_modifi">
                                        </div>
                                        <br>
                                        <br>
                                        <div class="col-md-6"></div>
                                        <div class="col-md-6">
                                            <label for="total_factura" class="form-label">Total Factura :</label>
                                            <input type="text" class="form-control" name="total_factura" id="total_factura_modifi">
                                        </div>
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn  btn-success col-2" id="acepta_factu">Aceptar Factura</button>
                                        </div>
                                    </form>
                                    <br>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                    <!-- segundo link -->
                    <div class="tab-pane fade" id="acobarras_sas" role="tabpanel" aria-labelledby="acobarras_sas-tab">
                        <div class="container-fluid">
                            <div class="container-fluid">
                                <br>
                                <h3 class="text-center fw-bolder" style="font-family: 'gothic';">Portafolio Acobarras S.A.S</h3>
                                <table id="tabla-acobarras-sas" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                    <thead style="background:#0d1b50;color:white">
                                        <tr>
                                            <th>Factura</th>
                                            <th>Nit</th>
                                            <th>Cliente</th>
                                            <th>Fecha Factura</th>
                                            <th>Fecha Vencimiento</th>
                                            <th>Iva</th>
                                            <th>Total Etiquetas</th>
                                            <th>Total Cintas</th>
                                            <th>Total Etiquetas y Cintas</th>
                                            <th>Total Alquiler</th>
                                            <th>Total Hardware</th>
                                            <th>Total Soporte</th>
                                            <th>Total Fletes</th>
                                            <th>Total M. Prima</th>
                                            <th>Total Factura</th>
                                            <th>Dias Mora</th>
                                            <th>Asesor</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                </table>
                                <br>
                            </div>
                            <br>
                        </div>
                    </div>
                    <!-- tercer link -->
                    <div class="tab-pane fade" id="acobarras_col" role="tabpanel" aria-labelledby="acobarras_col-tab">
                        <div class="container-fluid">
                            <div class="container-fluid">
                                <br>
                                <h3 class="text-center fw-bolder" style="font-family: 'gothic';">Portafolio Acobarras Colombia</h3>
                                <table id="tabla-acobarras-col" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                    <thead style="background:#0d1b50;color:white">
                                        <tr>
                                            <th>Factura</th>
                                            <th>Nit</th>
                                            <th>Cliente</th>
                                            <th>Fecha Factura</th>
                                            <th>Fecha Vencimiento</th>
                                            <th>Iva</th>
                                            <th>Total Etiquetas</th>
                                            <th>Total Cintas</th>
                                            <th>Total Etiquetas y Cintas</th>
                                            <th>Total Alquiler</th>
                                            <th>Total Hardware</th>
                                            <th>Total Soporte</th>
                                            <th>Total Fletes</th>
                                            <th>Total M. Prima</th>
                                            <th>Total Factura</th>
                                            <th>Dias Mora</th>
                                            <th>Asesor</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                </table>
                                <br>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Contabilidad/js/portafolio.js"></script>
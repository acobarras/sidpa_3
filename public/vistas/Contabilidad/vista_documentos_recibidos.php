<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="rep_docu-tab" data-bs-toggle="tab" href="#rep_docu" role="tab" aria-controls="rep_docu" aria-selected="true">Recepción de Documentos</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Primer link -->
                    <div class="tab-pane fade show active" id="rep_docu" role="tabpanel" aria-labelledby="rep_docu-tab">
                        <div class="container-fluid">
                            <br>
                            <form id="recibe_docu">
                                <div class="row g-3 align-items-center">
                                    <div class="col-3"></div>
                                    <div class="col-2">
                                        <label for="recibe_docu" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Núm. Documento a Recibir</label>
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
                            <div class="recuadro collapse consulta_documento_tg">
                                <div class="container-fluid">
                                    <br>
                                    <p class="h2 text-center fw-bolder">Factura Nº <span class="text-danger" id="num_recibe_factura"></span></p>
                                    <form class="row g-3" id="formulario_recibe_documento">
                                        <div class="col-md-3">
                                            <label for="nit" class="form-label">Nit :</label>
                                            <input type="text" class="form-control" name="nit" id="nit_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="nombre_empresa" class="form-label">Empresa :</label>
                                            <input type="text" class="form-control" name="nombre_empresa" id="nombre_empresa_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-3">
                                            <label for="fecha_factura" class="form-label">Fecha Factura: </label>
                                            <input type="text" class="form-control" name="fecha_factura" id="fecha_factura_d_modifi" disabled="disabled">
                                            <span id="span_fecha_factura" class="fw-bolder"></span>
                                        </div>
                                        <div class="col-3">
                                            <label for="dias_dados" class="form-label">Dias Dados: </label>
                                            <input type="text" class="form-control" name="dias_dados" id="dias_dados_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="empresa_a" class="form-label">Empresa :</label>
                                            <select class="form-select select_2" class="form-control select_2" style="width: 100%;" name="empresa_a" id="empresa_d_modifi" disabled="disabled">
                                                <?php foreach ($pertenece as $value) { ?>
                                                    <option value="<?= $value->id_empresa ?>"><?= $value->nombre_compania ?></option>
                                                <?php } ?>
                                               
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="fecha_vencimiento" class="form-label">Fecha Vencimiento:</label>
                                            <input type="text" class="form-control" name="fecha_vencimiento" id="fecha_vencimiento_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="id_usuarios_asesor" class="form-label">Vendedor :</label>
                                            <select class="form-select select_2" class="form-control select_2" style="width: 100%;" name="id_usuarios_asesor" id="id_usuarios_asesor_d" disabled="disabled">
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <div class='select_acob text-center'>
                                                <label for="iva" class="form-label">IVA :</label>
                                                <br>
                                                <input type="checkbox" name="iva" id="iva_d" data="" disabled="disabled">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_etiquetas" class="form-label">Total Neto Etiquetas :</label>
                                            <input type="text" class="form-control totales" name="total_etiquetas" id="total_etiquetas_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_etiquetas_iva_a" class="form-label">Valor Total Etiquetas :</label>
                                            <input type="text" class="form-control" name="total_etiquetas_iva_a" id="total_etiquetas_iva_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_cintas" class="form-label">Total Neto Cintas :</label>
                                            <input type="text" class="form-control totales" name="total_cintas" id="total_cintas_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_cintas_iva" class="form-label">Valor Total Cintas :</label>
                                            <input type="text" class="form-control" name="total_cintas_iva" id="total_cintas_iva_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_alquiler" class="form-label">Total Neto Alquiler :</label>
                                            <input type="text" class="form-control totales" name="total_alquiler" id="total_alquiler_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_alquiler_iva" class="form-label">Valor Total Alquiler :</label>
                                            <input type="text" class="form-control" name="total_alquiler_iva" id="total_alquiler_iva_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_tecnologia" class="form-label">Total Neto Hardware :</label>
                                            <input type="text" class="form-control totales" name="total_tecnologia" id="total_tecnologia_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_tecnologia_iva" class="form-label">Valor Total Hardware :</label>
                                            <input type="text" class="form-control" name="total_tecnologia_iva" id="total_tecnologia_iva_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_tecnologia" class="form-label">Total Neto Soporte :</label>
                                            <input type="text" class="form-control totales" name="total_soporte" id="total_soporte_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_tecnologia_iva" class="form-label">Valor Total Soporte :</label>
                                            <input type="text" class="form-control" name="total_soporte_iva" id="total_soporte_iva_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_fletes" class="form-label">Total Neto Fletes :</label>
                                            <input type="text" class="form-control totales" name="total_fletes" id="total_fletes_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_fletes_iva" class="form-label">Valor Total Fletes :</label>
                                            <input type="text" class="form-control" name="total_fletes_iva" id="total_fletes_iva_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_m_prima" class="form-label">Total Neto M. Prima :</label>
                                            <input type="text" class="form-control totales" name="total_m_prima" id="total_m_prima_d_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_m_prima_iva" class="form-label">Valor Total M. Prima :</label>
                                            <input type="text" class="form-control" name="total_m_prima_iva" id="total_m_prima_iva_d_modifi" disabled="disabled">
                                        </div>
                                        <div class=" col-md-6 border border-primary  border-3">
                                            <div class="col-md-12">
                                                <br>
                                                <label for="fecha_reci_doc" class="form-label">Fecha Recibe Documento :</label>
                                                <input type="text" class="form-control datepicker" name="fecha_reci_doc" id="fecha_reci_doc_d_modifi">
                                                <span id="span_fecha_recibe_doc" class="fw-bold text-danger"></span>
                                                <br>
                                            </div>
                                            <br>
                                            <div class="col-12 text-center">
                                                <button type="submit" id="btn_recibe_doc" id_recibe="" class="btn btn-primary col-2">Asigna Fecha <i class="far fa-calendar-alt"></i></button>
                                            </div>
                                            <br>
                                        </div>
                                        <br>
                                        <!-- <div class="col-md-6"></div>    -->
                                        <div class="col-md-6">
                                            <br>
                                            <label for="total_factura_d_modifi" class="form-label">Total Factura :</label>
                                            <input type="text" class="form-control" name="total_factura" id="total_factura_d_modifi" disabled="disabled">
                                        </div>
                                        <br>
                                    </form>
                                    <br>
                                </div>
                            </div>
                            <br>
                            <h3 id="no_existe_documento" class="text-center fw-bolder" style="display: none; font-family: 'gothic';">¡Esta factura aún no ha sido relacionada o no existe!</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Contabilidad/js/documentos_recibidos.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <?php if ($_SESSION['usuario']->getId_roll() == 1 || (isset(USU_ANULA_FACTURA[$_SESSION['usuario']->getId_usuario()]) && USU_ANULA_FACTURA[$_SESSION['usuario']->getId_usuario()] == 1)) { ?>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="anula_factura-tab" data-bs-toggle="tab" href="#anula_factura" role="tab" aria-controls="anula_factura" aria-selected="true">Anula factura portafolio</a>
                        </li>
                    <?php } ?>
                    <?php if ($_SESSION['usuario']->getId_roll() == 1 || (isset(USU_ANULA_FACTURA[$_SESSION['usuario']->getId_usuario()]) && USU_ANULA_FACTURA[$_SESSION['usuario']->getId_usuario()] == 1)) { ?>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="fecha_pago-tab" data-bs-toggle="tab" href="#fecha_pago" role="tab" aria-controls="fecha_pago" aria-selected="true">Fecha pago factura</a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Primer link -->
                    <div class="tab-pane fade show active" id="anula_factura" role="tabpanel" aria-labelledby="anula_factura-tab">
                        <div class="container-fluid">
                            <br>
                            <form id="anular_factura">
                                <div class="row g-3 align-items-center">
                                    <div class="col-3"></div>
                                    <div class="col-2">
                                        <label for="num_factura" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Núm. Factura para anular</label>
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
                            <div class="recuadro collapse consulta_anula_factura_tg">
                                <div class="container-fluid">
                                    <br>
                                    <p class="h2 text-center fw-bolder"><span class="text-danger">Anula </span>Factura Nº <span class="text-danger" id="num_anula_factura"></span></p>
                                    <form class="row g-3" id="formulario_anula_factura">
                                        <div class="col-md-3">
                                            <label for="nit_a" class="form-label">Nit :</label>
                                            <input type="text" class="form-control" name="nit_a" id="nit_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="nombre_empresa_a" class="form-label">Empresa :</label>
                                            <input type="text" class="form-control" name="nombre_empresa_a" id="nombre_empresa_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-3">
                                            <label for="fecha_factura_a" class="form-label">Fecha Factura: </label>
                                            <input type="text" class="form-control" name="fecha_factura_a" id="fecha_factura_a_modifi" disabled="disabled">
                                            <span id="span_fecha_factura" class="fw-bolder"></span>
                                        </div>
                                        <div class="col-3">
                                            <label for="dias_dados_a" class="form-label">Dias Dados: </label>
                                            <input type="text" class="form-control" name="dias_dados_a" id="dias_dados_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="empresa_a" class="form-label">Empresa :</label>
                                            <select class="form-select select_2" class="form-control select_2" style="width: 100%;" name="empresa_a" id="empresa_a_modifi" disabled="disabled">
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
                                            <label for="fecha_vencimiento_a" class="form-label">Fecha Vencimiento:</label>
                                            <input type="text" class="form-control" name="fecha_vencimiento_a" id="fecha_vencimiento_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="id_usuarios_asesor" class="form-label">Vendedor :</label>
                                            <select class="form-select select_2" class="form-control select_2" style="width: 100%;" name="id_usuarios_asesor" id="id_usuarios_asesor_a" disabled="disabled">
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <div class='select_acob text-center'>
                                                <label for="iva_a" class="form-label">IVA :</label>
                                                <br>
                                                <input type="checkbox" name="iva_a" id="iva_a" data="" disabled="disabled">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_etiquetas_a" class="form-label">Total Neto Etiquetas :</label>
                                            <input type="text" class="form-control totales" name="total_etiquetas_a" id="total_etiquetas_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_etiquetas_iva_a" class="form-label">Valor Total Etiquetas :</label>
                                            <input type="text" class="form-control" name="total_etiquetas_iva_a" id="total_etiquetas_iva_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_cintas_a" class="form-label">Total Neto Cintas :</label>
                                            <input type="text" class="form-control totales" name="total_cintas_a" id="total_cintas_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_cintas_iva_a" class="form-label">Valor Total Cintas :</label>
                                            <input type="text" class="form-control" name="total_cintas_iva_a" id="total_cintas_iva_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_alquiler_a" class="form-label">Total Neto Alquiler :</label>
                                            <input type="text" class="form-control totales" name="total_alquiler_a" id="total_alquiler_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_alquiler_iva_a" class="form-label">Valor Total Alquiler :</label>
                                            <input type="text" class="form-control" name="total_alquiler_iva_a" id="total_alquiler_iva_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_tecnologia_a" class="form-label">Total Neto Hardware :</label>
                                            <input type="text" class="form-control totales" name="total_tecnologia_a" id="total_tecnologia_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_tecnologia_iva_a" class="form-label">Valor Total Hardware :</label>
                                            <input type="text" class="form-control" name="total_tecnologia_iva_a" id="total_tecnologia_iva_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_soporte_a" class="form-label">Total Neto Soporte :</label>
                                            <input type="text" class="form-control totales" name="total_soporte_a" id="total_soporte_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_soporte_iva_a" class="form-label">Valor Total Soporte :</label>
                                            <input type="text" class="form-control" name="total_soporte_iva_a" id="total_soporte_iva_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_fletes_a" class="form-label">Total Neto Fletes :</label>
                                            <input type="text" class="form-control totales" name="total_fletes_a" id="total_fletes_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_fletes_iva_a" class="form-label">Valor Total Fletes :</label>
                                            <input type="text" class="form-control" name="total_fletes_iva_a" id="total_fletes_iva_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_m_prima_a" class="form-label">Total Neto M. Prima :</label>
                                            <input type="text" class="form-control totales" name="total_m_prima_a" id="total_m_prima_a_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_m_prima_iva_a" class="form-label">Valor Total M. Prima :</label>
                                            <input type="text" class="form-control" name="total_m_prima_iva_a" id="total_m_prima_iva_a_modifi" disabled="disabled">
                                        </div>
                                        <br>
                                        <div class="col-12 text-center">
                                            <button type="submit" id="btn_anula" class="btn  btn-danger col-2" id_anula="">Anula Factura</button>
                                        </div>
                                    </form>
                                    <br>
                                </div>
                            </div>
                            <br>
                            <h3 id="no_factura_anula" class="text-center fw-bolder" style="display: none; font-family: 'gothic';">¡Esta factura aún no ha sido relacionada o no existe!</h3>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="fecha_pago" role="tabpanel" aria-labelledby="fecha_pago-tab">
                        <div class="container-fluid">
                            <br>
                            <form id="fecha_pago_factura">
                                <div class="row g-3 align-items-center">
                                    <div class="col-3"></div>
                                    <div class="col-2">
                                        <label for="num_factura" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Núm. Factura para agregar fecha de pago</label>
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
                            <div class="recuadro collapse consulta_fecha_pago_factura_tg">
                                <div class="container-fluid">
                                    <br>
                                    <p class="h2 text-center fw-bolder">Factura Nº <span class="text-danger" id="num_fecha_pago_factura"></span></p>
                                    <form class="row g-3" id="formulario_fecha_pago_factura">
                                        <input type="hidden" name="num_factura_fecha_p" id="num_factura_fecha_p">
                                        <div class="col-md-3">
                                            <label for="nit" class="form-label">Nit :</label>
                                            <input type="text" class="form-control" name="nit" id="nit_f_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="nombre_empresa" class="form-label">Empresa :</label>
                                            <input type="text" class="form-control" name="nombre_empresa" id="nombre_empresa_f_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-3">
                                            <label for="fecha_factura" class="form-label">Fecha Factura: </label>
                                            <input type="text" class="form-control" name="fecha_factura" id="fecha_factura_f_modifi" disabled="disabled">
                                            <span id="span_fecha_factura" class="fw-bolder"></span>
                                        </div>
                                        <div class="col-3">
                                            <label for="dias_dados" class="form-label">Dias Dados: </label>
                                            <input type="text" class="form-control" name="dias_dados" id="dias_dados_f_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="empresa_f" class="form-label">Empresa :</label>
                                            <select class="form-select select_2" class="form-control select_2" style="width: 100%;" name="empresa_f" id="empresa_f_modifi" disabled="disabled">
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
                                            <input type="text" class="form-control" name="fecha_vencimiento" id="fecha_vencimiento_f_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="id_usuarios_asesor" class="form-label">Vendedor :</label>
                                            <select class="form-select select_2" class="form-control select_2" style="width: 100%;" name="id_usuarios_asesor" id="id_usuarios_asesor_f">
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <div class='select_acob text-center'>
                                                <label for="iva" class="form-label">IVA :</label>
                                                <br>
                                                <input type="checkbox" name="iva" id="iva_f" data="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_etiquetas" class="form-label">Total Neto Etiquetas :</label>
                                            <input type="text" class="form-control totales" name="total_etiquetas" id="total_etiquetas_f_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_etiquetas_iva" class="form-label">Valor Total Etiquetas :</label>
                                            <input type="text" class="form-control" name="total_etiquetas_iva" id="total_etiquetas_iva_f_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_cintas" class="form-label">Total Neto Cintas :</label>
                                            <input type="text" class="form-control totales" name="total_cintas" id="total_cintas_f_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_cintas_iva" class="form-label">Valor Total Cintas :</label>
                                            <input type="text" class="form-control" name="total_cintas_iva" id="total_cintas_iva_f_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_alquiler" class="form-label">Total Neto Alquiler :</label>
                                            <input type="text" class="form-control totales" name="total_alquiler" id="total_alquiler_f_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_alquiler_iva" class="form-label">Valor Total Alquiler :</label>
                                            <input type="text" class="form-control" name="total_alquiler_iva" id="total_alquiler_iva_f_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_tecnologia" class="form-label">Total Neto Hardware :</label>
                                            <input type="text" class="form-control totales" name="total_tecnologia" id="total_tecnologia_f_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_tecnologia_iva_f" class="form-label">Valor Total Hardware :</label>
                                            <input type="text" class="form-control" name="total_tecnologia_iva_f" id="total_tecnologia_iva_f_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_soporte" class="form-label">Total Neto Soporte :</label>
                                            <input type="text" class="form-control totales" name="total_soporte" id="total_soporte_f_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_soporte_iva" class="form-label">Valor Total Soporte :</label>
                                            <input type="text" class="form-control" name="total_soporte_iva" id="total_soporte_iva_f_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_fletes" class="form-label">Total Neto Fletes :</label>
                                            <input type="text" class="form-control totales" name="total_fletes" id="total_fletes_f_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_fletes_iva" class="form-label">Valor Total Fletes :</label>
                                            <input type="text" class="form-control" name="total_fletes_iva" id="total_fletes_iva_f_modifi" disabled="disabled">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_m_prima" class="form-label">Total Neto M. Prima :</label>
                                            <input type="text" class="form-control totales" name="total_m_prima" id="total_m_prima_f_modifi">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="total_m_prima_iva" class="form-label">Valor Total M. Prima :</label>
                                            <input type="text" class="form-control" name="total_m_prima_iva" id="total_m_prima_iva_f_modifi" disabled="disabled">
                                        </div>
                                        <div class=" col-md-6 border border-success  border-3">
                                            <div class="col-md-12">
                                                <br>
                                                <label for="fecha_pago" class="form-label">Fecha de pago :</label>
                                                <input type="text" class="form-control datepicker" name="fecha_pago" id="fecha_pago_f_modifi">
                                                <br>
                                            </div>
                                            <br>
                                            <div class="col-12 text-center">
                                                <button type="submit" id="btn_fecha_pago" class="btn btn-success col-2">Asigna Fecha <i class="far fa-calendar-alt"></i></button>
                                            </div>
                                            <br>
                                        </div>
                                        <br>
                                        <div class="col-md-6">
                                            <br>
                                            <label for="total_tecnologia_iva" class="form-label">Total Factura :</label>
                                            <input type="text" class="form-control" name="total_factura" id="total_factura_f_modifi">
                                        </div>
                                    </form>
                                    <br>
                                </div>
                            </div>
                            <br>
                            <h3 id="no_factura_fecha" class="text-center fw-bolder" style="display: none; font-family: 'gothic';">¡Esta factura aún no ha sido relacionada o no existe!</h3>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Contabilidad/js/gestion_factura.js"></script>
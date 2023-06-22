<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido_solicitud_soporte" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab-solicitud" role="tablist">
                        <a class="nav-link active" id="nav-solicitud-tab" data-bs-toggle="tab" href="#nav-solicitud" role="tab" aria-controls="nav-solicitud" aria-selected="true">solicitud de soporte</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent-solicitud">
                    <div class="tab-pane fade show active" id="nav-solicitud" role="tabpanel" aria-labelledby="nav-solicitud-tab">
                        <div class="container-fluid">
                            <br>
                            <div class="mb-3 text-center row">
                                <h1 class="col-md-12 col-md-offset-4 ">Solicitud de soporte técnico</h1>
                            </div>
                            <form id="crea_solicitud_soporte">
                                <div class="row g-3 align-items-center">
                                    <div class="col-3">
                                        <label for="nit" class="col-form-label" style="font-family: 'gothic'; font-weight: bold;">Nit empresa</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" name="nit" id="nit_empresa">
                                        </div>
                                    </div>
                                    <div class="form-group col-3">
                                        <label class="fw-bolder"> Nombre empresa :</label>
                                        <div class="datos_empresa">
                                            <span class="form-control span_nombre">N/A</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-2">
                                        <label class="fw-bolder">Digito de verificacion:</label>
                                        <div class="dig_veri">
                                            <span class="form-control span_digito">N/A</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-4">
                                        <label class="fw-bolder"> Dirección pedido :</label>
                                        <input class="form-control" name="direccion_soli" id="direccion_soli" style="display:none;">
                                        <select class="form-control" name="direc_solicitud" id="direc_solicitud" style="width: 100%;"></select>
                                    </div>
                                    <div class="mb-3" id="form_direccion">
                                        <div class="row mb-3">
                                            <div class="form-group col-4">
                                                <label for="id_pais_soli" class="form-label">País : </label>
                                                <select class="form-control select_2 select_activo" style="width: 100%;" name="id_pais" id="id_pais_soli" disabled>
                                                    <?php foreach ($paises as $pais) { ?>
                                                        <option value="<?= $pais->id_pais ?>"><?= $pais->nombre ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="id_departamento_soli" class="form-label">Departamento : </label>
                                                <select class="form-control select_2 select_activo" style="width: 100%;" name="id_departamento" id="id_departamento_soli" disabled>
                                                    <?php foreach ($departamento as $dep) { ?>
                                                        <option value="<?= $dep->id_departamento ?>"><?= $dep->nombre ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="id_ciudad_soli" class="form-label">Ciudad : </label>
                                                <select class="form-control select_2 select_activo" style="width: 100%;" name="id_ciudad" id="id_ciudad_soli" disabled>
                                                    <?php foreach ($ciud as $ciudad) { ?>
                                                        <option value="<?= $ciudad->id_ciudad ?>"><?= $ciudad->nombre ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="form-group col-4">
                                                <label class="fw-bolder"> Teléfono :</label>
                                                <input class="form-control input_activo" name="telefono" id="telefono_soli" readonly></input>
                                            </div>
                                            <div class="form-group col-4">
                                                <label class="fw-bolder"> Celular :</label>
                                                <input class="form-control input_activo" name="celular" id="celular_soli" readonly></input>
                                            </div>
                                            <div class="form-group col-4">
                                                <label class="fw-bolder"> Correo :</label>
                                                <input class="form-control input_activo" name="email" id="correo_soli" readonly></input>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="form-group col-4">
                                                <label class="fw-bolder"> Contacto :</label>
                                                <input class="form-control input_activo" name="contacto" id="contacto_soli" readonly></input>
                                            </div>
                                            <div class="form-group col-4">
                                                <label class="fw-bolder"> Cargo :</label>
                                                <input class="form-control input_activo" name="cargo" id="cargo_soli" readonly></input>
                                            </div>
                                            <div class="form-group col-4">
                                                <label class="fw-bolder"> Horario :</label>
                                                <input class="form-control input_activo" name="horario" id="horario_soli" readonly></input>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="form-group col-8">
                                                <label class="fw-bolder"> Link google maps :</label>
                                                <input class="form-control input_activo" name="link_maps" id="link_soli" readonly></input>
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="ruta_modifi" class="form-label">Ruta : </label>
                                                <select class="form-control select_2 select_activo" style="width: 100%;" name="ruta" id="ruta_modifi" disabled>
                                                    <?php foreach (RUTA_ENTREGA as $key => $ruta) { ?>
                                                        <option value="<?= $key ?>"><?= $ruta ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="form-group col-4">
                                            <label class="fw-bolder" for="req_visita">¿Es ingreso a laboratorio ?</label>
                                            <span class="form-control select_acob">
                                                Si &nbsp;&nbsp;
                                                <input type="radio" class="req_visita" name="req_visita" id="req_visita_si" value="1">
                                                <span style="padding-left: 30px"></span>
                                                No &nbsp;&nbsp;
                                                <input type="radio" class="req_visita" name="req_visita" id="req_visita" value="2">
                                            </span>
                                        </div>
                                        <div class="form-group col-4" id="requiere_visita" style="display: none;">
                                            <label class="fw-bolder" for="visita_prese">¿La visita es presencial?</label>
                                            <span class="form-control select_acob">
                                                Si &nbsp;&nbsp;
                                                <input type="radio" class="visita_prese" name="visita_prese" id="visita_prese_si" value="1">
                                                <span style="padding-left: 30px"></span>
                                                No &nbsp;&nbsp;
                                                <input type="radio" class="visita_prese" name="visita_prese" id="visita_prese" value="2">
                                            </span>
                                        </div>
                                        <div class="form-group col-4" id="cobro_ser" style="display: none;">
                                            <label class="fw-bolder" for="cobro_ser">¿El servicio tiene cobro?</label>
                                            <span class="form-control select_acob">
                                                Si &nbsp;&nbsp;
                                                <input type="radio" class="cobro_ser" name="cobro_ser" id="cobro_ser_si" value="1">
                                                <span style="padding-left: 30px"></span>
                                                No &nbsp;&nbsp;
                                                <input type="radio" class="cobro_ser" name="cobro_ser" id="cobro_ser" value="2">
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="form-group col-4" id="req_cotiza" style="display: none;">
                                            <label class="fw-bolder" for="req_cotiza">¿Requiere Cotizacion?</label>
                                            <span class="form-control select_acob">
                                                Si &nbsp;&nbsp;
                                                <input type="radio" class="req_cotiza" name="req_cotiza" id="req_cotiza_si" value="1">
                                                <span style="padding-left: 30px"></span>
                                                No &nbsp;&nbsp;
                                                <input type="radio" class="req_cotiza" name="req_cotiza" id="req_cotiza" value="2">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/soporte_tecnico/js/solicitud_soporte.js"></script>
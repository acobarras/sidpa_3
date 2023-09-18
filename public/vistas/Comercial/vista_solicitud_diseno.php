<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="nav-codigo-tab" data-bs-toggle="tab" href="#nav-codigo" role="tab" aria-controls="nav-codigo" aria-selected="true">Solicitud De Código</a>
                        <a class="nav-link" id="nav-diseno-tab" data-bs-toggle="tab" href="#nav-diseno" role="tab" aria-controls="nav-diseno" aria-selected="true">Solicitud De Diseño</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <!-- pestaña solicitud de codigo -->
                    <div class="tab-pane fade show active" id="nav-codigo" role="tabpanel" aria-labelledby="nav-codigo-tab">
                        <div class="container mt-4 mb-4">
                            <center>
                                <iframe src=<?= FORM_DISENOCOD ?> width="1000" height="5420" frameborder="0" marginheight="0" marginwidth="0">Cargando…</iframe>
                            </center>
                            <!-- <form id="form_solicitud_codigo" class="shadow row g-3 p-2">
                                <h1 id="titulo_cod" class="text-center">Solicitud de Diseño</h1>
                                <input type="hidden" name="asesor_cod" id="asesor_cod" value="<?= $_SESSION['usuario']->getNombre() . ' ' . $_SESSION['usuario']->getApellido() ?>">
                                <input type="hidden" name="id_asesor_cod" id="id_asesor_cod" value="<?= $_SESSION['usuario']->getId_usuario() ?>">
                                <div class="col-md-6 col-12">
                                    <label for="nit" class="form-label fw-bold">NIT cliente (Sin digito de verificación):</label>
                                    <div class="input-group">
                                        <div class="input-group-text" id="icono_carga_cod">#</div>
                                        <input type="number" class="form-control" name="nit" id="nit_cod">
                                    </div>
                                </div>
                                <input type="hidden" id="id_cli_prov" name="id_cli_prov" value="">
                                <div class="col-md-6 col-12">
                                    <label for="nombre_cliente_cod" class="form-label fw-bold"> Nombre cliente:</label>
                                    <input type="text" class="form-control" name="nombre_cliente_cod" id="nombre_cliente_cod" readonly>
                                </div>
                                <div id="input_crea" class="d-none">
                                    <div class="col-12 mb-3">
                                        <label for="rut" class="form-label fw-bold">Adjuntar Rut:</label>
                                        <input class="form-control" type="file" id="rut" name="rut">
                                    </div>
                                    <div class="text-center">
                                        <button class="btn btn-success" type="button" id="enviar_creacion">Enviar</button>
                                    </div>
                                </div>
                                <div id="tipo_solicitud" class="d-none" style="text-align: center;">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input tipo_solicitud" type="radio" name="tipo_solicitud_check" id="tipo_sol1" value="1" checked>
                                        <label class="form-check-label" for="tipo_sol1">Código</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input tipo_solicitud" type="radio" name="tipo_solicitud_check" id="tipo_sol2" value="2">
                                        <label class="form-check-label" for="tipo_sol2">Diseño</label>
                                    </div>
                                </div>
                                /// parte formulario diseño ///
                                <div class=" row g-3 diseno d-none">
                                    <div class="col-md-6 col-12">
                                        <label for="contacto" class="form-label fw-bold"> Nombre contacto:</label>
                                        <input type="text" class="form-control" name="contacto" id="contacto">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="email_contacto" class="form-label fw-bold"> Correo contacto:</label>
                                        <input type="email" class="form-control" name="email_contacto" id="email_contacto" placeholder="Ej: email@correo.com">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="tipo_arte" class="form-label fw-bold">Tipo arte:</label>
                                        <select class="form-control select_2" name="tipo_arte" id="tipo_arte">
                                            <option value="">Selecciones tipo de arte</option>
                                            /// <?php foreach (GRAF_CORTE as $key => $value) { ?>
                                                <option value="<?= $key ?>"><?= $value['nombre'] ?></option>
                                            <?php } ?> ////
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="cantidad" class="form-label fw-bold"> Cantidad etiquetas:</label>
                                        <input type="number" class="form-control" name="cantidad" id="cantidad">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="cantida_tintas_dis" class="form-label fw-bold">Cantidad de tintas:</label>
                                        <input type="number" class="form-control" name="cantida_tintas_dis" id="cantida_tintas_dis" placeholder="Máximo 10">
                                    </div>
                                    <div id="input_tintas" class="row g-3"></div>
                                    <div class="col-md-4 col-12">
                                        <label for="grafe" class="form-label fw-bold">Tipo corte:</label>
                                        <select class="form-control select_2" name="grafe" id="grafe" multiple>
                                            //// <?php foreach (GRAF_CORTE as $key => $value) { ?>
                                                <option value="<?= $key ?>"><?= $value['nombre'] ?></option>
                                            <?php } ?> ////
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="entrega" class="form-label fw-bold">Tipo entrega:</label>
                                        <select class="form-control select_2" name="entrega" id="entrega" multiple>
                                            //// <?php foreach (GRAF_CORTE as $key => $value) { ?>
                                                <option value="<?= $key ?>"><?= $value['nombre'] ?></option>
                                            <?php } ?> ///
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="op_solicitud" class="form-label fw-bold">Tipo solicitud:</label>
                                        <select class="form-control select_2" name="op_solicitud" id="op_solicitud" multiple>
                                            //// <?php foreach (GRAF_CORTE as $key => $value) { ?>
                                                <option value="<?= $key ?>"><?= $value['nombre'] ?></option>
                                            <?php } ?> ////
                                        </select>
                                    </div>
                                </div>
                                //// Parte el fomulacio codigo ////
                                <div class=" row g-3 codigo d-none">
                                    <div class="col-md-6 col-12">
                                        <label for="ancho" class="form-label fw-bold">Ancho:</label>
                                        <input class="form-control" type="number" name="ancho" id="ancho" placeholder="Ancho en milimetros">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="alto" class="form-label fw-bold">Alto:</label>
                                        <input class="form-control" type="number" name="alto" id="alto" placeholder="Alto en milimetros">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="tipo_product" class="form-label fw-bold">Tipo Producto:</label>
                                        <select class="form-control select_2" style="width: 100%;" name="tipo_product" id="tipo_product">
                                            <option value="0">Seleccione un tipo</option>
                                            <option value="1">Etiquetas</option>
                                            <option value="2">Hojas</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="forma_material" class="form-label fw-bold">Forma Material:</label>
                                        <select class="form-control select_2" name="forma_material" id="forma_material">
                                            <option value="0">Seleccione una forma</option>
                                            <?php foreach ($forma_material as $value) { ?>
                                                <option value="<?= $value->id_forma ?>"><?= $value->nombre_forma ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="tipo_material" class="form-label fw-bold">Tipo Material:</label>
                                        <select class="form-control select_2" name="tipo_material" id="tipo_material" data_precio='<?= json_encode($precio) ?>'>>
                                            <option value="0">Selecciona un material</option>
                                            <?php foreach ($mat as $material) { ?>
                                                <option value="<?= $material->codigo ?>"><?= $material->nombre_material ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="id_adh" class="form-label fw-bold">Adhesivo:</label>
                                        <select class="form-control select_2" name="id_adh" id="id_adh" data_adh='<?= json_encode($adh) ?>'>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="cavidad" class="form-label fw-bold">Cavidad:</label>
                                        <input class="form-control" type="number" name="cavidad" id="cavidad" placeholder="Numero de cavidades">
                                    </div>
                                    <div class="col-md-6 col-12 solo_codigo d-none">
                                        <label for="cant_tintas" class="form-label fw-bold">Cantidad tintas:</label>
                                        <input class="form-control" type="number" id="cant_tintas" name="cant_tintas" placeholder="Numero de tintas">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="gaf_cort" class="form-label fw-bold">Grafes y Cortes:</label>
                                        <select class="form-control select_2" name="gaf_cort" id="gaf_cort">
                                            <option value="">Selecciones tipo de grafe</option>
                                            <?php foreach (GRAF_CORTE as $key => $value) { ?>
                                                <option value="<?= $key ?>"><?= $value['nombre'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="terminados1" class="form-label fw-bold">Tipo terminado:</label>
                                        <select class="form-control select_2" name="terminados1" id="terminados"  multiple="multiple">
                                             <?php foreach (TERMINADOS_DISENO as $key => $value) { ?>
                                                <option value="<?= $key ?>"><?= $value['nombre'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label for="obsevaciones_cod" class="form-label fw-bold"> Observaciones:</label>
                                        <textarea type="text" class="form-control" name="observaciones_cod" id="obsevaciones_cod"></textarea>
                                    </div>
                                    <div class="text-center col-12">
                                        <button class="btn btn-success" type="submit" id="enviar_solicitud_Cod">Enviar</button>
                                    </div>
                                </div>

                            </form> -->
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="nav-diseno" role="tabpanel" aria-labelledby="nav-diseno-tab">
                        <div class="recuadro">
                            <div class="container-fluid">
                                <center>
                                    <iframe src=<?= FORM_DISENODIS ?> width="1000" height="5420" frameborder="0" marginheight="0" marginwidth="0">Cargando…</iframe>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Comercial/js/solicitudes_diseno.js"></script>
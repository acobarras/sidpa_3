<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="nav-crea_pedido-tab" data-bs-toggle="tab" href="#nav-crea_pedido" role="tab" aria-controls="nav-crea_pedido" aria-selected="true">Crea Pedido</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-crea_pedido" role="tabpanel" aria-labelledby="nav-crea_pedido-tab">
                        <div class="container-fluid">
                            <br>
                            <form id="crea_pedido_directo">
                                <div class="row g-3 align-items-center">
                                    <div class="col-3"></div>
                                    <div class="col-1">
                                        <label for="id_cli_prov" class="col-form-label" style="font-family: 'gothic'; font-weight: bold;">Nit Cliente</label>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" name="nit" id="nit_empresa">
                                            <button type="submit" class="btn btn-success col-3">Crea Pedido <i class="fas fa-check"></i></button>
                                        </div>
                                    </div>
                                    <div class="row d-none" id="botones_crea">
                                        <span class="text-center text-danger" id="span_estado"></span>
                                        <br>
                                        <div class="col-md-6 col-12 input-group mb-3 m-auto justify-content-center">
                                            <button type="button" id="crear_cliente" class="btn btn-success col-3 d-none" data-bs-toggle="modal" data-bs-target="#creacion_cliente">Crea Cliente <i class="fas fa-user"></i></button>&nbsp;&nbsp;
                                            <button type="button" id="crear_direccion" class="btn btn-primary col-3 d-none" data-bs-toggle="modal" data-bs-target="#creacion_direc">Crea dirección <i class="fas fa-map-marked-alt"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <br>
                        </div>
                        <div class="NuevoPEDIDO" style="display: none;">
                            <br>
                            <!--formu crear pedido-->
                            <div class="card">
                                <div class="card-header">
                                    <center>
                                        <h4>Nuevo Pedido</h4>
                                    </center>
                                </div>
                                <div class="card-body fs-6" style="background: #f5f5f57a">
                                    <form id="form_envia_pedido_directo">
                                        <div class="mb-4" style="border:2px solid #bdbdbd;">
                                            <div class="container-fluid py-3">
                                                <div class="row">
                                                    <div class="form-group row col-4">
                                                        <label for="tipo_documento" class="col-3">Tipo Documento</label>
                                                        <div class="col-md-9">
                                                            <select id="remision_factura" name="tipo_documento" class="form-control"></select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row col-4">
                                                        <label for="nombre_empresa" class="col-3">N° Documento:</label>
                                                        <div class="col-9">
                                                            <span class="form-control" id="numero_factura">2021</span>
                                                            <input type="hidden" id="numero_factura_consulta" name="numero_factura_consulta">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row col-4">
                                                        <label for="nombre_empresa" class="col-3">Facturado Por:</label>
                                                        <div class="col-md-9">
                                                            <span class="form-control"><?= $usuario ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="fw-bolder"> Cliente :</label>
                                                <span for="id_cli_prov" id="nombre_cliente"></span>
                                                <input id="id_cliv_provP" name="id_cli_prov" type="text" hidden="true">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="id_persona" class="fw-bolder">Asesor :</label>
                                                <span><?= $_SESSION['usuario']->getNombres() . " " . $_SESSION['usuario']->getApellidos() ?></span>
                                                <input hidden="true" type="text" name="id_persona" value="<?= $_SESSION['usuario']->getId_persona() ?>">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="fw-bolder">Fecha :</label>
                                                <span><?php date_default_timezone_set('America/Bogota');
                                                        echo date('Y/m/d') ?></span>
                                                <label class="fw-bolder">Hora :</label>
                                                <span id="reloj">00 : 00 : 00</span>
                                                <br>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="num_nit_cliente" class="fw-bolder"> Nit :</label>
                                                <span id="nit_cliente"></span>
                                                <input hidden="true" type="text" id="num_nit_cliente" />
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="id_dire_entre" class="fw-bolder"> Dirección de Entrega<sup>*(requerido.)</sup></label>
                                                <select class="form-control select_2" style="width: 100%" id="id_direccionC" name="id_dire_entre"></select>
                                                <span id="m2"></span>
                                                <br>
                                                <br>
                                                <div class="collapse dir_entrega">
                                                    <label class="fw-bolder mb-2"> Contacto :</label>
                                                    <span class="label_blue mb-2" id="infoCon"></span><br>
                                                    <label class="fw-bolder mb-2">Cargo : </label>
                                                    <span class="label_blue mb-2" id="infoCar"></span><br>
                                                    <label class="fw-bolder mb-2">E-mail :</label>
                                                    <span class="label_blue mb-2" id="infoEmail"></span><br>
                                                    <label class="fw-bolder mb-2">Celular :</label>
                                                    <span class="label_blue mb-2" id="infoCel"></span><br>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="id_dire_radic" class="fw-bolder"> Dirección de Radicación<sup>*(requerido.)</sup></label>
                                                <select class="form-control select_2" style="width: 100%" id="id_direccionCC" name="id_dire_radic">
                                                </select>
                                                <span id="m3"></span>
                                                <br>
                                                <br>
                                                <div class="collapse dir_entrega">
                                                    <label class="fw-bolder mb-2">Telefono :</label>
                                                    <span class="label_blue mb-2" id="infoTel"></span><br>
                                                    <label class="fw-bolder mb-2">Horario Repeción :</label>
                                                    <span class="label_blue mb-2" id="infoHorario"></span><br>
                                                    <label class="fw-bolder mb-2">Condición Pago :</label>
                                                    <span class="label_blue mb-2" id="infoFo"></span><br>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <br>
                                        <h4 style="text-align: center">¡Agrega los Productos!</h4>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <select class="form-control select_2" title="Los productos se muestran en el orden | Codigo| Descripcción" style="width: 100%" id="productos">
                                                    <option value="0">Busca un producto</option>
                                                    <?php
                                                    foreach ($productos as $producto) {
                                                        $visible = '';
                                                        if ($producto->estado_producto != 1) {
                                                            $visible = 'disabled';
                                                        }
                                                    ?>
                                                        <option <?= $visible ?> value="<?= $producto->id_productos ?>"><?= $producto->id_productos .  " | " . $producto->codigo_producto .  " | " . $producto->descripcion_productos ?></option>
                                                    <?php } ?>
                                                </select>
                                                <p class="text-muted pt-2">NOTA: Los productos se muestran en el orden | Codigo | Descripcción </p>
                                            </div>
                                            <div class="col-md-5 select_product collapse">
                                                <select class="form-control select_2" title="Los productos se muestran en el orden | Codigo| Descripcción" style="width: 100%" id="id_clien_producPP"></select>
                                                <input type="hidden" id="data_product" name="data_product">
                                                <p class="text-muted pt-2">NOTA: Los productos se muestran en el orden | Codigo | Descripcción | Ruta Emb | Core | Roll. paq X </p>
                                            </div><br>
                                            <hr>
                                            <br>
                                            <div class="col-md-3 add_product collapse">
                                                <p class="fw-bold">Valor Venta:<span style="color: green;"> $ <span style="color: black;" id="valor_venta"></span><span id="moneda_venta"></span></span></p>
                                            </div>
                                            <div class="col-md-3 add_product collapse">
                                                <p class="fw-bold">Valor Autorizado:
                                                    <span style="color: green;"> $ <span style="color: black;" id="valor_Autoriza"></span><span id="moneda_autoriza"></span></span></span>
                                                </p>
                                            </div>
                                            <div class="col-md-6 add_product collapse">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" id="cantidad" placeholder="Este campo no admite puntos.">
                                                    <input hidden="true" type="type" id="trmPP" value="<?= $trm[0]->valor_trm ?>" />
                                                    <button class="btn btn-success" type="button" data-product="" id="add_producto">Agregar <i class="fas fa-pencil-alt"></i></button>
                                                </div>
                                            </div>
                                            <div class="col-md-4 py-3 crea_producto collapse">
                                                <select class="form-select select_2" style="width: 100%" id="id_ruta_embobinado" name="id_ruta_embobinado">
                                                    <option value="0">Selecciona una Ruta Embobinado</option>
                                                    <?php foreach ($ruta_em as $r) { ?>
                                                        <option value="<?= $r->id_ruta_embobinado ?>"><?= $r->nombre_r_embobinado ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 py-3 crea_producto collapse">
                                                <select class="form-control select_2" style="width: 100%" id="id_core" name="id_core">
                                                    <option value="0">Selecciona un Core</option>
                                                    <?php foreach ($core as $cores) { ?>
                                                        <option value="<?= $cores->id_core ?>"><?= $cores->nombre_core ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 py-3 crea_producto collapse">
                                                <select class="form-control select_2" style="width: 100%" id="moneda" name="moneda">
                                                    <option value="0">Selecciona la moneda</option>
                                                    <option value="1">Pesos</option>
                                                    <option value="2">Dolar</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 py-3 crea_producto collapse">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" id="presentacion" name="presentacion" placeholder="Rollos ó Paquetes Por">
                                                </div>
                                            </div>
                                            <div class="col-md-4 py-3 crea_producto collapse">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" id="precio_venta" name="precio_venta" placeholder="Precio venta.">
                                                </div>
                                            </div>
                                            <div class="col-md-4 py-3 crea_producto collapse">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" id="cantidad_agrega" name="cantidad_agrega" placeholder="Este campo no admite puntos.">
                                                    <input hidden="true" type="type" id="trmPP" value="<?= $trm[0]->valor_trm ?>" />
                                                    <button class="btn btn-warning" type="button" data-product="" id="crea_producto">Agregar <i class="fas fa-pencil-alt"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container-fluid">
                                            <table style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" id="tabla-item-pedido" cellspacing="0" width="100%">
                                                <thead style="background:#0d1b50;color:white">
                                                    <tr>
                                                        <th>Opción</th>
                                                        <th>Codigo</th>
                                                        <th>Descripción</th>
                                                        <th>Cant.</th>
                                                        <th>Ficha Tec N°</th>
                                                        <th>Ruta Emb</th>
                                                        <th>Core</th>
                                                        <th>Roll. paq X</th>
                                                        <th>Moneda</th>
                                                        <th>V.unidad</th>
                                                        <th>Valor Total </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <br>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-2 fw-bold">Total Items: <span style="color: green;" id="totalitems"></span></div>
                                            <div class="col-md-3 fw-bold">Subtotal: <span style="color: green;" id="subtotal"></span></div>
                                            <div class="col-md-3 fw-bold">Iva: <span style="color: green;" id="iva"></span></div>
                                            <div class="col-md-3 fw-bold">Total: <span style="color: green;" id="total"></span></div>
                                        </div>
                                        <br>
                                        <hr>
                                        <div class="row">
                                            <div class="container-fluid">

                                                <label for="observaciones">Observaciones :</label>
                                                <textarea class="form-control" rows="6" cols="50" id="observaciones" name="observaciones"></textarea>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-4"></div>
                                            <div class="col-lg-4"></div>
                                            <div class="col-lg-4">
                                                <br>
                                                <label>
                                                    <font style="vertical-align: inherit;">
                                                        <font style="vertical-align: inherit;">Requiere Iva :</font>
                                                    </font>
                                                </label>
                                                No
                                                <input type="radio" name="iva" class="iva_vs" id="iva_no" value="2">
                                                <span style="padding-left: 5px"></span>
                                                Si
                                                <input type="radio" name="iva" class="iva_vs " id="iva_si" value="1" checked>
                                            </div>
                                        </div>
                                        <br><br>
                                        <center>
                                            <button type="submit" class="btn btn-primary btn-lg" id="btn_crear_pedido">Grabar Pedido <i class="fa fa-angle-double-right"></i></button>
                                        </center>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-------------------------------------------------- MODAL CLIENTE --------------------------------------------->
<div class="modal fade" id="creacion_cliente" role="dialog" aria-labelledby="examplecreacion_cliente" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="examplecreacion_cliente">Formulario Creacion Cliente</h3>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <div class="recuadro">
                    <div class="container-fluid">
                        <form id="form_creacion_cliente">
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="tipo_documento" class="form-label">Tipo de documento : </label>
                                    <select class="form-control select_2" style="width: 100%" name="tipo_documento" id="tipo_documento">
                                        <option value="0"></option>
                                        <?php foreach ($documento as $doc) { ?>
                                            <option value="<?= $doc->id_tipo_documento ?>"><?= $doc->nombre_documento ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="mb-3 col-8">
                                    <label for="nit" class="form-label">Número de documento : </label>
                                    <input class="form-control" name="nit" id="nit" />
                                </div>
                                <div class="mb-3 col-4">
                                    <label for="dig_verificacion" class="form-label">Digito de Verificación : </label>
                                    <input class="form-control" name="dig_verificacion" id="dig_verificacion" />
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="nombre_empresa" class="form-label">Razón Social : </label>
                                    <input type="text" class="form-control" name="nombre_empresa" id="nombre_empresa" />
                                </div>
                                <div class="mb-3 col-8">
                                    <label for="id_usuarios_asesor" class="form-label">Asesores : </label>
                                    <select class="form-control select_2" multiple style="width: 100%;" name="id_usuarios_asesor" id="id_usuarios_asesor">
                                        <option value="0"></option>
                                        <?php foreach ($asesores as $usuario) { ?>
                                            <option value="<?= $usuario->id_persona ?>"><?= $usuario->nombre . " " . $usuario->apellido ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" value="1" name="tipo_cli_prov">
                            <input type="hidden" value="1" name="forma_pago">
                            <input type="hidden" value="0" name="dias_dados">
                            <input type="hidden" value="1" name="pertenece">
                            <input type="hidden" value="3" name="lista_precio">
                            <input type="hidden" value="0" name="cupo_cliente">
                            <input type="hidden" value="0" name="dias_max_mora">
                            <input type="hidden" value="0" name="tipo_prove">
                            <input type="hidden" value="1" name="logo_etiqueta">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary btn-sm" id="crear_cliente">Crear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-------------------------------------------------- MODAL DIRECCION --------------------------------------------->
<div class="modal fade" id="creacion_direc" role="dialog" aria-labelledby="examplecreacion_direc" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="examplecreacion_direc">Formulario Creacion Dirección</h3>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <div class="recuadro">
                    <div class="container-fluid">
                        <form id="form_creacion_direc">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" name="id_cli_prov" id="id_cli_prov">
                                        <label for="nombre_empresa" class="col-form-label">Nombre Cliente:</label>
                                        <input type="text" class="form-control" name="nombre_empresa" id="nombre_empresa_dir" disabled>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="id_pais" class="col-form-label">País:</label>
                                        <select id="id_pais" name="id_pais" class="form-select select_2" aria-label="Default select example" style="width:100%">
                                            <option value="0">Selecciona un Pais</option>
                                            <?php foreach ($paises as $pais) { ?>
                                                <option value="<?= $pais->id_pais ?>"><?= $pais->nombre ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="id_departamento" class="col-form-label">Departamento:</label>
                                        <select id="id_departamento" name="id_departamento" class="form-select select_2" aria-label="Default select example" style="width:100%">
                                            <option value="0">Selecciona un Departamento</option>
                                            <?php foreach ($departamento as $departamento) { ?>
                                                <option value="<?= $departamento->id_departamento ?>"><?= $departamento->nombre ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="id_ciudad" class="col-form-label">Ciudad:</label>
                                        <select id="id_ciudad" name="id_ciudad" class="form-select select_2" aria-label="Default select example" style="width:100%">
                                            <option value="0">Selecciona una Ciudad</option>
                                            <?php foreach ($ciudad as $ciudad) { ?>
                                                <option value="<?= $ciudad->id_ciudad ?>"><?= $ciudad->nombre ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="telefono" class="col-form-label">Télefono:</label>
                                        <input type="number" class="form-control" id="telefono" name="telefono">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="celular" class="col-form-label">Celular:</label>
                                        <input type="text" class="form-control" id="celular" name="celular">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email" class="col-form-label">Email:</label>
                                        <input type="text" class="form-control" id="email" name="email">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="cargo" class="col-form-label">Cargo:</label>
                                        <input type="text" class="form-control" id="cargo" name="cargo">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="contacto" class="col-form-label">Contacto:</label>
                                        <textarea cols="2" class="form-control" id="contacto" name="contacto"></textarea>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="direccion" class="col-form-label">Dirección:</label>
                                        <textarea cols="5" class="form-control" id="direccion" name="direccion"></textarea>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="direccion" class="col-form-label">Link Google Maps:</label>
                                        <input type="text" class="form-control" id="link_maps" name="link_maps">

                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="horario" class="col-form-label">Horario Atención:</label>
                                        <input type="text" class="form-control" id="horario" name="horario">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="recor_dia_cierre" class="col-form-label">Recordatorio dia Cierre:</label>
                                        <textarea type="text" class="form-control" id="recor_dia_cierre" name="recor_dia_cierre"></textarea>
                                    </div>
                                </div>
                                <input hidden="true" id="ruta" name="ruta" value="7">
                                <input hidden="true" type="text" name="id_usuario" name="id_usuario" value="<?= $_SESSION['usuario']->getId_usuario() ?>" />
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary btn-sm" id="crear_direccion">Cear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/facturacion/js/vista_pedidos_directos.js"></script>
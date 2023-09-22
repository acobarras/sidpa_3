<input type="hidden" value='<?= json_encode($paises) ?>' id="select_paises">
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="nav-clientes-tab" data-bs-toggle="tab" href="#nav-clientes" role="tab" aria-controls="nav-clintes" aria-selected="true">Mis Clientes</a>
                        <!-- <a class="nav-link" id="nav-pedidos-tab" data-bs-toggle="tab" href="#nav-pedidos" role="tab" aria-controls="nav-pedidos" aria-selected="true">Historial Mis pedidos</a> -->
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <!--------------------------------------------------  PANEL DE CLIENTES --------------------------------------------->
                    <div class="tab-pane fade show active" id="nav-clientes" role="tabpanel" aria-labelledby="nav-clientes-tab">

                        <!--------------------------------------------------  TABLA DE CLIENTES --------------------------------------------->
                        <div class="recuadro">
                            <div class="container-fluid">
                                <br>
                                <br>
                                <div class="text-center">
                                    <h2>Tabla de Clientes
                                        <!-- Button modal crea clientes -->
                                        <button type="button" class="btn btn-primary crea_dir" data-bs-toggle="modal" data-bs-target="#ModalCLI">
                                            <i class="fa fa-user-plus"></i>
                                        </button>
                                    </h2>
                                </div>
                                <br>
                                <div class="tab_mis_clientes">
                                    <table id="dt_mis_clientes" class="table table-bordered table-responsive-sm  text-center table-responsive-lg table-responsive-md " cellspacing="0" width="100%">
                                        <thead style="background: #0d1b50;color: white;">
                                            <tr>
                                                <th>#Código</th>
                                                <th>Nit</th>
                                                <th>Empresa</th>
                                                <th>Facturado Por</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <!-------------------------------------------------- INICIO PANEL DE NUEVO PEDIDO --------------------------------------------->
                                <div id="WindowLoad">
                                    <div class="NuevoPEDIDO" style="display: none;">
                                        <br>
                                        <!--formu crear pedido-->
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Nuevo Pedido</h4>
                                                <div class="row">
                                                    <div class="col-12 col-md-4 mb-2">
                                                        <button type='button' class='btn btn-success regresa_clientes'>
                                                            <span class='fa fa-arrow-circle-left'></span>
                                                            Regresar
                                                        </button>
                                                    </div>
                                                    <!--<div class="col-12 col-md-4 mb-2">-->
                                                    <!--    <button type='button' class='btn btn-warning ' data-bs-toggle='modal' data-bs-target='#ModalCREARPRODUCTO' style='color:#ffff'><i class='fa fa-plus-square'> Crear Producto</i></button>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-12 col-md-4 mb-2">-->
                                                    <!--    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalCLI">-->
                                                    <!--        <i class="fa fa-map-marked-alt"></i>Crear dirección-->
                                                    <!--    </button>-->
                                                    <!--</div>-->
                                                </div>
                                            </div>
                                            <div class="card-body fs-6" style="background: #f5f5f57a">
                                                <form id="form_crear_pedido">
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
                                                        <div class="form-group col-md-3">
                                                            <br>
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <label class="form-check-label fw-bolder">Tiene O.C :</label>
                                                                </div>
                                                                <div class="form-check form-switch col-9">
                                                                    <input class="form-check-input" type="checkbox" id="check_oc" checked <?= REQ_ORDEN ?>>
                                                                    <span class="fw-bolder" style="color: #0027d2;" id="span_orden_compra">Si</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--habilitar campos-->
                                                        <div class="form-group col-md-3">
                                                            <label for="orden_compra" class="fw-bolder"> Orden de Compra </label>
                                                            <input autocomplete='off' class="form-control " type="text" name="orden_compra" id="orden_compra">
                                                        </div>
                                                        <div class="form-group  col-md-4">
                                                            <label for="PDF_compra" class="fw-bolder"> PDF de Compra :</label>
                                                            <input class="form-control " type="file" name="PDF_compra" id="PDF_compra">
                                                            <span id="m1" style="color: red; font-size: 13px;"></span>
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
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="row">
                                                                <div class="col-4">
                                                                    <label class="form-check-label fw-bolder" for="parcial">Recibe Parcial :</label>
                                                                </div>
                                                                <div class="form-check form-switch col-8">
                                                                    <input class="form-check-input" name="parcial" type="checkbox" id="parcial">
                                                                    <span class="fw-bolder" style="color: #0027d2;" id="span_parcial">No</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="input-group mb-3">
                                                                <label for="porcentaje" class="input-group-text">Diferencia</label>
                                                                <input type="text" class="form-control" name="porcentaje" id="porcentaje">
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                            <span style="color: red; font-size: 13px;">*(requerido.)</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="checkbox-inline fw-bolder">
                                                                    <input type="checkbox" id="difer_mas" name="difer_mas" value="1">
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">Mas
                                                                        </font>
                                                                    </font>
                                                                </label>
                                                                <label class="checkbox-inline fw-bolder">
                                                                    <input type="checkbox" id="difer_menos" name="difer_menos" value="1">
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">Menos
                                                                        </font>
                                                                    </font>
                                                                </label>
                                                                <label class="checkbox-inline fw-bolder">
                                                                    <input type="checkbox" id="difer_ext" name="difer_ext" value="1">
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">Exacto
                                                                        </font>
                                                                    </font>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h4 style="text-align: center">¡Agrega los Productos!</h4>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <select class="form-control select_2" title="Los productos se muestran en el orden | Codigo | Codigo Cliente | Descripcción| Ruta Emb| Core| Roll. paq X" style="width: 100%" id="id_clien_producPP"></select>
                                                            <input type="hidden" id="data_product" name="data_product">
                                                            <p class="text-muted pt-2">NOTA: Los productos se muestran en el orden | Codigo | Codigo Cliente | Descripcción | Ruta Emb | Core | Roll. paq X </p>
                                                        </div>
                                                        <!-- <div class="col-md-1"></div> -->
                                                        <div class="col-md-2 add_product collapse">
                                                            <p class="fw-bold">Valor Venta:<span style="color: green;"> $ <span style="color: black;" id="valor_venta"></span><span id="moneda_venta"></span></span></p>
                                                        </div>
                                                        <div class="col-md-2 add_product collapse">
                                                            <p class="fw-bold">Valor Autorizado:
                                                                <span style="color: green;"> $ <span style="color: black;" id="valor_Autoriza"></span><span id="moneda_autoriza"></span></span></span>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-3 add_product collapse">
                                                            <div class="input-group">
                                                                <input class="form-control" type="text" id="cantidad" placeholder="Este campo no admite puntos.">
                                                                <input hidden="true" type="type" id="trmPP" value="<?= $trm[0]->valor_trm ?>" />
                                                                <button class="btn btn-success" type="button" data-product="" id="add_producto">Agregar <i class="fas fa-pencil-alt"></i></button>
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
                                                        <div class="col-lg-4">
                                                            <label for="fecha_cierre">Fecha Cierre Facturación <span class="text-rojos">*</span></label>
                                                            <input autocomplete="off" type="text" class="form-control datepicker" name="fecha_cierre" id="fecha_cierre">
                                                            <span id="m6"></span>
                                                            <span id="info_fecha" style="color:red;"></span>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label for="fecha_comp_programado">Fecha Compromiso Programado</span></label>
                                                            <input autocomplete="off" type="text" class="form-control" name="fecha_compromiso" id="fecha_compro_programado">
                                                            <span id="m6"></span>
                                                        </div>

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
                    <!-------------------------------------------------- FIN PANEL DE NUEVO PEDIDO --------------------------------------------->
                    <div class="tab-pane fade show" id="nav-pedidos" role="tabpanel" aria-labelledby="nav-pedidos-tab">
                        <div class="container-fluid mt-3 mb-3">
                            <div class="recuadro">
                                <div class="container-fluid">
                                    <!--tabla pedidos asesor-->
                                    <br>
                                    <div class="table-responsive">
                                        <div class="container-fluid">
                                            <div class="tabla_c_pedido_h">
                                                <br>
                                                <h2 style="text-align:center">Tabla Historial de Pedidos </h2>
                                                <hr>
                                                <br>
                                                <table id="dt_historial_pedidos_asesor" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                                    <thead style="background:#0d1b50;color:white">
                                                        <tr>
                                                            <td>Fecha creación</td>
                                                            <td>Hora creación</td>
                                                            <td>Número de pedido</td>
                                                            <td>Nombre Empresa</td>
                                                            <td>Orden de compra</td>
                                                            <td>Compra Etiquetas</td>
                                                            <td>Compra Tecnología</td>
                                                            <td>Estado </td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div class="collapse detalle_pedido_h">
                                                <br>
                                                <!--formu crear pedido-->
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 style="text-align: center;"><b>CONSULTAR PEDIDO N° <span style="color:red" id="num_pedidoPC"></span></b></h3>
                                                        <div class="row">
                                                            <div class="col-12 col-md-4 mb-2">
                                                                <button type='button' class='btn btn-success regresa_pedidos_h'>
                                                                    <span class='fa fa-arrow-circle-left'></span>
                                                                    Regresar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body fs-6" style="background: #f5f5f57a">
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label class="fw-bolder"> Cliente :</label>
                                                                <span for="id_cli_prov" id="nombre_cliente_h"></span>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="id_persona" class="fw-bolder">Asesor :</label>
                                                                <span id="asesor"></span>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label class="fw-bolder">Fecha :</label>
                                                                <span id="fecha_pedido_h"></span>
                                                                <br>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="num_nit_cliente" class="fw-bolder"> Nit :</label>
                                                                <span id="nit_cliente_h"></span>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <!--habilitar campos-->
                                                            <div class="form-group col-md-3">
                                                                <label for="orden_compra" class="fw-bolder"> Orden de Compra: </label>
                                                                <span class="fw-bolder" style="color: #0027d2;" id="span_num_orden_compra_h"></span>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label for="id_dire_entre" class="fw-bolder"> Dirección de Entrega: </label>
                                                                <span class="label_blue mb-2" id="id_direccion_entre_PC"></span><br>
                                                                <div class="dir_entrega">
                                                                    <label class="fw-bolder mb-2">Contacto :</label>
                                                                    <span class="label_blue mb-2" id="infoCon_h"></span><br>
                                                                    <label class="fw-bolder mb-2">Cargo : </label>
                                                                    <span class="label_blue mb-2" id="infoCar_h"></span><br>
                                                                    <label class="fw-bolder mb-2">E-mail :</label>
                                                                    <span class="label_blue mb-2" id="infoEmail_h"></span><br>
                                                                    <label class="fw-bolder mb-2">Celular :</label>
                                                                    <span class="label_blue mb-2" id="infoCel_h"></span><br>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="id_dire_radic" class="fw-bolder"> Dirección de Radicación:</label>
                                                                <span class="label_blue mb-2" id="id_direccionPC"></span><br>
                                                                <div class="dir_entrega">
                                                                    <label class="fw-bolder mb-2">Telefono :</label>
                                                                    <span class="label_blue mb-2" id="infoTel_h"></span><br>
                                                                    <label class="fw-bolder mb-2">Horario Repeción :</label>
                                                                    <span class="label_blue mb-2" id="infoHorario_h"></span><br>
                                                                    <label class="fw-bolder mb-2">Condición Pago :</label>
                                                                    <span class="label_blue mb-2" id="infoFo_h"></span><br>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="row">
                                                                    <div class="col-4">
                                                                        <label class="form-check-label fw-bolder" for="parcial">Recibe Parcial :</label>
                                                                    </div>
                                                                    <div class="form-check form-switch col-8">
                                                                        <span class="fw-bolder" style="color: #0027d2;" id="span_parcial_h"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="row">
                                                                    <div class="col-4">
                                                                        <label class="form-check-label fw-bolder" for="parcial">Diferencia :</label>
                                                                    </div>
                                                                    <div class="form-check form-switch col-8">
                                                                        <span class="fw-bolder" style="color: #0027d2;" id="span_porcentaje_h"></span><span> %</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <hr>
                                                        <br>
                                                        <div class="container-fluid">
                                                            <table id="dt_itemas_pedidos_asesor" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                                                <thead style="background:#0d1b50;color:white">
                                                                    <tr>
                                                                        <th>item #</th>
                                                                        <th style="width: 130px;">Codigo</th>
                                                                        <th>Descripción</th>
                                                                        <th>Cant.</th>
                                                                        <th>Ficha Tec N°</th>
                                                                        <th>Ruta Emb</th>
                                                                        <th>Core</th>
                                                                        <th style="width: 60px;">Roll. paq X</th>
                                                                        <th>Trm</th>
                                                                        <th>Moneda</th>
                                                                        <th>V.unidad</th>
                                                                        <th>Valor Total</th>
                                                                        <th>Estado</th>
                                                                        <th>Opción</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                            <br>
                                                        </div>
                                                        <br>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="container-fluid">

                                                                <label for="observaciones">Observaciones :</label>
                                                                <textarea class="form-control" rows="6" cols="50" id="observaciones_h" readonly></textarea>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <label for="fecha_cierre">Fecha Cierre Facturación <span class="text-rojos">*</span></label>
                                                                <input autocomplete="off" type="text" class="form-control" id="fecha_cierre_h" readonly>
                                                                <span id="m6"></span>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <label for="fecha_comp_programado">Fecha Compromiso Programado</span></label>
                                                                <input autocomplete="off" type="text" class="form-control" id="fecha_compro_programado_h" readonly>
                                                                <span id="m6"></span>
                                                            </div>

                                                            <div class="col-lg-4">
                                                                <br>
                                                                <label>
                                                                    <font style="vertical-align: inherit;">
                                                                        <font style="vertical-align: inherit;">Requiere Iva :</font>
                                                                    </font>
                                                                </label>
                                                                <span style="padding-left: 5px" id="r_iva_h"></span>
                                                            </div>
                                                        </div>
                                                        <br><br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!--ver modal informacion de item del pedido-->
                        <div class="modal fade" id="info_item" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header header_aco">
                                        <div class="img_modal">
                                            <p> </p>
                                        </div>
                                        <h3 class="modal-title" id="exampleModalLabel">Seguimiento Item</h3><span id="MP"></span>
                                        <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
                                    </div>
                                    <div class="modal-body">
                                        <div class="recuadro">
                                            <div class="container-fluid">
                                                <br>
                                                <table id="dt_infor_item" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                                    <thead style="background:#0d1b50;color:white">
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Pedido Item</th>
                                                            <th>Area</th>
                                                            <th>Actividad</th>
                                                            <th>Persona</th>
                                                            <th>Observación</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-------------------------------------------------- FIN PANEL DE CLIENTES --------------------------------------------->
        </div>
    </div>
</div>

<!----------------------------------------------------- INICIO MODALES ------------------------------------------------>
<!-------------------------------------------------- MODAL CREAR DIRECCION CLIENTE --------------------------------------------->
<div class="modal fade" id="ModalCLI" aria-labelledby="ModalCLILabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title text-center" id="ModalCLILabel">Crear Dirección Clientes</h5>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <form id="form_crear_dir_cli">
                <div class="modal-body">
                    <div class="card card-body">
                        <div class="row g-2">
                            <span id="nitt" style="display: none"></span>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="id_cli_prov" class="col-form-label">Nombre Cliente:</label>
                                    <select id="id_cli_prov" name="id_cli_prov" class="form-select select_2" aria-label="Default select example" style="width:100%">
                                        <option value="0">Selecciona un Cliente</option>
                                        <?php foreach ($client as $clients) { ?>
                                            <option value="<?= $clients->id_cli_prov ?>"><?= $clients->nombre_empresa ?></option>
                                        <?php } ?>
                                    </select>
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
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="id_ciudad" class="col-form-label">Ciudad:</label>
                                    <select id="id_ciudad" name="id_ciudad" class="form-select select_2" aria-label="Default select example" style="width:100%">
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary" id="crear_dir_cli">Crear <i class="fa fa-check"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-------------------------------------------------- MODAL MODIFICAR DIRECCION CLIENTE --------------------------------------------->
<div class="modal fade" id="ModalDIR" aria-labelledby="ModalDIRLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal">
                    <p> </p>
                </div>
                <h5 class="modal-title text-center" id="CLIENTE"></h5>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <form id="form_modificar_direccion_cli">
                <div class="modal-body">
                    <div class="card card-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="id_pais" class="col-form-label">País:</label>
                                    <select id="id_paisD" name="id_pais" class="form-select select_2" style="width: 100%">
                                        <!-- <?php foreach ($paises as $pais) { ?>
                                            <option value="<?= $pais->id_pais ?>"><?= $pais->nombre ?></option>
                                        <?php } ?> -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="id_departamento" class="col-form-label">Departamento:</label>
                                    <select id="id_departamentoD" name="id_departamento" class="form-select select_2" style="width: 100%">
                                        <!-- <?php foreach ($departamento as $r) { ?>
                                            <option value="<?= $r->id_departamento ?>"><?= $r->nombre ?></option>
                                        <?php } ?> -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="id_ciudad" class="col-form-label">Ciudad:</label>
                                    <select id="id_ciudadD" name="id_ciudad" class="form-select select_2" style="width: 100%">
                                        <!-- <?php foreach ($ciudad as $r) { ?>
                                            <option value="<?= $r->id_ciudad ?>"><?= $r->nombre ?></option>
                                        <?php } ?> -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="telefono" class="col-form-label">Télefono:</label>
                                    <input type="text" class="form-control" id="telefonoD" name="telefono">

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="celular" class="col-form-label">Celular:</label>
                                    <input type="text" class="form-control" id="celularD" name="celular">

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="email" class="col-form-label">Email:</label>
                                    <input type="text" class="form-control" id="emailD" name="email">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="cargo" class="col-form-label">Cargo:</label>
                                    <input type="text" class="form-control" id="cargoD" name="cargo">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="horario" class="col-form-label">Horario Atención:</label>
                                    <input type="text" class="form-control" id="horarioD" name="horario">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="contacto" class="col-form-label">Contacto:</label>
                                    <textarea cols="2" class="form-control" id="contactoD" name="contacto"></textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="direccion" class="col-form-label">Dirección:</label>
                                    <textarea cols="5" class="form-control" id="direccionD" name="direccion" style="height: 142px;"></textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="link_maps" class="col-form-label">Link Google Maps:</label>
                                    <input type="text" class="form-control" id="link_mapsD" name="link_maps">

                                </div>
                            </div>
                            <div class="col-6">
                                <input type="hidden" id="id_direccion" name="id_direccion">
                                <label for="ruta" class="col-form-label">Ruta:</label>
                                <select id="rutaD" name="ruta" class="form-select select_2" disabled style="width: 100%">
                                    <?php foreach (RUTA_ENTREGA as $key => $value) { ?>
                                        <option value="<?= $key ?>"><?= $value ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="recor_dia_cierre_modi" class="col-form-label">Recordatorio dia Cierre:</label>
                                    <textarea type="text" class="form-control" id="recor_dia_cierre_modi" name="recor_dia_cierre"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary" id="modificar_dir_cliente">Modificar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-------------------------------------------------- MODAL CREAR PRODUCTO CLIENTE --------------------------------------------->
<div class="modal fade" id="ModalCREARPRODUCTO" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="exampleModalLabel">Crear Nuevo Producto</h3><span id="NE"></span>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <form id="form_crear_producto_cli">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" hidden="true" id="id_cli_provC" name="id_cli_prov">
                        <input class="form-control" type="text" name="fecha_crea" value="
                        <?= date_default_timezone_set('America/Bogota');
                        echo date('Y/m/d h:i:s a') ?>" readonly="readonly" />
                    </div>
                    <div class="form-group">
                        <label for="id_tipo_producto" class="col-form-label">Tipo Producto (<i class="fa fa-clock"></i> 4seg):</label>
                        <select class="form-control select_2" style="width: 100%" name="id_tipo_producto" id="id_tipo_producto">
                            <option value="0">Selecciona un Tipo producto</option>
                            <?php foreach ($clase_articulo as $clase_art) { ?>
                                <option value="<?= $clase_art->id_clase_articulo ?>"><?= $clase_art->nombre_clase_articulo ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="div_cod_product"></div>
                    <div class="form-group">
                        <label for="id_ruta_embobinado" class="col-form-label">Ruta Embobinado:</label>
                        <select class="form-select select_2" style="width: 100%" id="id_ruta_embobinado" name="id_ruta_embobinado">
                            <option value="0">Selecciona una Ruta Embobinado</option>
                            <?php foreach ($ruta_em as $r) { ?>
                                <option value="<?= $r->id_ruta_embobinado ?>"><?= $r->nombre_r_embobinado ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="row">

                        <div class="form-group col-md-6">
                            <label for="id_core" class="col-form-label">Core:</label>
                            <select class="form-control select_2" style="width: 100%" id="id_core" name="id_core">
                                <option value="0">Selecciona un Core</option>
                                <?php foreach ($core as $cores) { ?>
                                    <option value="<?= $cores->id_core ?>"><?= $cores->nombre_core ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="moneda" class="col-form-label">Moneda:</label>
                            <select class="form-control select_2" style="width: 100%" id="moneda" name="moneda">
                                <option value="0">Selecciona la moneda</option>
                                <option value="1">Pesos</option>
                                <option value="2">Dolar</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group col-md-6">
                            <label for="presentacion" class="col-form-label">Rollos ó Paquetes Por:</label>
                            <input class="form-control" type="text" name="presentacion" id="presentacion">
                        </div>
                        <!-- <div class="form-group col-md-6">
                            <label for="ficha_tecnica" class="col-form-label">Ficha Tecnica:</label>
                            <input class="form-control" type="text" name="ficha_tecnica" id="ficha_tecnica">
                        </div> -->
                        <div class="form-group col-md-6">
                            <label for="cantidad_minima" class="col-form-label">Cantidad Cotizada:</label>
                            <input class="form-control" type="number" name="cantidad_minima" id="cantidad_minima">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="codigo_cliente" class="col-form-label">Codigo Cliente:</label>
                        <input class="form-control" type="text" name="codigo_cliente" id="codigo_cliente">
                        <span class="text-danger" id="span_cod_cliente"></span>
                    </div>
                    <div class="form-group ">
                        <label for="precio_venta" class="col-form-label">Precio Venta:(Ejemplo:[3,5] [1000] )</label>
                        <input class="form-control" type="number" step="Any" name="precio_venta" id="precio_venta">
                        <input hidden="true" type="text" name="id_usuario" name="id_usuario" value="<?= $_SESSION['usuario']->getId_usuario() ?>" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary" id="crear_pro_cli">Crear <i class="fa fa-check"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-------------------------------------------------- MODALE MODIFICAR PRODUCTO CLIENTE --------------------------------------------->
<div class="modal fade" id="ModalMODIFICARPRODUCTO" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="exampleModalLabel">Modificar Producto</h3><span id="MP"></span>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <form id="form_modificar_producto_cli">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" hidden="true" id="id_clien_producM" name="id_clien_produc">
                        <input type="text" hidden="true" id="id_cli_provM" name="id_cli_prov">
                        <input type="text" hidden="true" id="id_productoMM" name="id_producto">
                        <input type="text" hidden="true" id="id_clase_articuloM" name="id_clase_articulo">
                        <input class="form-control" type="text" name="fecha_crea" value="
                        <?php date_default_timezone_set('America/Bogota');
                        echo date('Y/m/d h:i:s a') ?>" readonly="readonly" />
                    </div>
                    <div class="form-group">
                        <label for="id_tipo_productoM" class="col-form-label">Tipo Producto (<i class="fa fa-clock"></i> 4seg):</label>
                        <select class="form-control select_2" style="width: 100%" id="id_tipo_productoM">
                            <option value="0">Selecciona un Tipo producto</option>
                            <?php foreach ($clase_articulo as $clase_art) { ?>
                                <option value="<?= $clase_art->id_clase_articulo ?>"><?= $clase_art->nombre_clase_articulo ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="div_cod_productM"></div>
                    <div class="form-group">
                        <label for="id_ruta_embobinado" class="col-form-label">Ruta Embobinado:</label>
                        <select class="form-control select_2" style="width: 100%" id="id_ruta_embobinadoM" name="id_ruta_embobinado">
                            <option value="0">Selecciona una Ruta Embobinado</option>
                            <?php foreach ($ruta_em as $r) { ?>
                                <option value="<?= $r->id_ruta_embobinado ?>"><?= $r->nombre_r_embobinado ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="id_core" class="col-form-label">Core:</label>
                            <select class="form-control select_2" style="width: 100%" id="id_coreM" name="id_core">
                                <option value="0">Selecciona un Core</option>
                                <?php foreach ($core as $cores) { ?>
                                    <option value="<?= $cores->id_core ?>"><?= $cores->nombre_core ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="moneda" class="col-form-label">Moneda:</label>
                            <select class="form-control select_2" style="width: 100%" id="monedaM" name="moneda">

                                <option value="0">Selecciona la moneda</option>
                                <option value="1">Pesos</option>
                                <option value="2">Dolar</option>
                            </select>
                            <input hidden="true" type="type" id="moneda_autoriza" />
                            <input hidden="true" type="type" id="trm" value="<?= $trm[0]->valor_trm ?>" />

                        </div>
                        <div class="form-group col-md-6">
                            <label for="presentacion" class="col-form-label">Rollos ó Pquetes Por:</label>
                            <input class="form-control" type="text" name="presentacion" id="presentacionM">
                        </div>
                        <!-- <div class="form-group col-md-6">
                            <label for="ficha_tecnica" class="col-form-label">Ficha Tecnica:</label>
                            <input class="form-control" type="text" name="ficha_tecnica" id="ficha_tecnicaM">
                        </div> -->
                    </div>
                    <div class="form-group" id="cambio_cantidad" style="display: none;">
                        <label for="cantidad_minimaM" class="col-form-label">Cantidad Cotizada:</label>
                        <input class="form-control" type="number" name="cantidad_minima" id="cantidad_minimaM">
                    </div>
                    <div class="form-group">
                        <label for="codigo_clienteM" class="col-form-label">Codigo Cliente:</label>
                        <input class="form-control" type="text" name="codigo_cliente" id="codigo_clienteM">
                        <span class="text-danger" id="span_cod_cliente"></span>
                    </div>
                    <div class="form-group ">
                        <label for="message-text" class="col-form-label">Precio Venta:(Ejemplo:3,5)</label>
                        <input class="form-control" type="text" name="precio_venta" id="precio_ventaM">
                        <input hidden="true" id="precio_autorizado">
                        <span id="span_precio_ventaM" style="color: red;"></span>
                        <!-- <input hidden="true" type="number" name="id_usuario" name="id_usuario" value="<?= $_SESSION['usuario']->getId_usuario() ?>" /> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary" id="modificar_pro_client" disabled>Modificar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- MODAL FICHA TECNICA -->
<div class="modal fade" id="ficha" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ficha_tecnica" aria-hidden="true">
    <div class="modal-dialog modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="ficha_tecnica">Ficha Tecnica</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container" id="ficha_tec">
                </div>
            </div>
        </div>
    </div>
</div>
<!-------------------------------------------------- MODALE NOTIFICACIONES PEDIDO --------------------------------------------->
<div class="modal fade" id="ModalNOTIFICACIONESPEDIDO" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="exampleModalLabel">Novedades de Portafolio</h3>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <div class="recuadro">
                    <div class="container-fluid">
                        <hr>
                        <span id="msg"></span>
                        <br><br>
                        <br>
                        <div class="facturas_vencidas" style="display: none;">
                            <hr>
                            <table id="facturas_vencidas" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
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
                                        <th>Total Tecnologia</th>
                                        <th>Total Factura</th>
                                        <th>Dias Mora</th>
                                        <th>Asesor</th>
                                    </tr>
                                </thead>
                            </table>
                            <br><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-------------------------------------------------- FIN MODALES --------------------------------------------->






<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>

<script src="<?= PUBLICO ?>/vistas/Comercial/js/mis_clientes.js"></script>
<script src="<?= PUBLICO ?>/vistas/Comercial/js/pedidos.js"></script>
<script src="<?= PUBLICO ?>/vistas/Comercial/js/valida_portafolio.js"></script>
<!-- <script src="<?= PUBLICO ?>/vistas/Comercial/js/historial_pedidos.js"></script> -->
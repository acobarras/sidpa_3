<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <div id="ocultar">
                <br>
                <form class="container" id="form_consulta_pedido" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                    <div class="mb-3 row text-center">
                        <h1>Modificar Pedido</h1>
                    </div>
                    <div class="mb-3 row">
                        <div class="mb-3 col-3">
                            <label for="tipo_consulta" class="form-label">Tipo Consulta</label>
                            <select class="form-control select_2" name="tipo_consulta" id="tipo_consulta">
                                <option value=""></option>
                                <?php foreach (MODIFICAR_PEDIDO as $key => $value) {
                                    if ($key == 'num_pedido') { ?>
                                        <option selected value="<?= $key ?>"><?= $value ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $key ?>"><?= $value ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="mb-3 col-7">
                            <label for="dato_consulta" class="form-label">Dato Consulta</label>
                            <input type="text" class="form-control" name="dato_consulta" id="dato_consulta">
                            <select class="form-control select_2" id="dato_consulta_select">
                                <option value="0"></option>
                                <?php foreach ($clientes as $cliente) { ?>
                                    <option value="<?= $cliente->id_cli_prov ?>"><?= $cliente->nombre_empresa ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3 col-2 pt-3">
                            <button class="btn btn-primary" type="submit" id="consulta_pedido">
                                <i class="fa fa-plus-circle"></i> Consultar Pedido
                            </button>
                        </div>
                    </div>
                </form>
                <br>
                <div class="mx-3 mt-2">
                    <div class="text-center">
                        <h2>Tabla Respuesta</h2>
                    </div>
                    <br>
                    <table id="tabla_pedidos" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                        <thead style="background: #002b5f;color: white">
                            <th id="id_tabla">#</th>
                            <td>Fecha creación</td>
                            <td>Hora creación</td>
                            <td>Número de pedido</td>
                            <td>Nombre Empresa</td>
                            <td>Orden de compra</td>
                            <td>Compra Etiquetas</td>
                            <td>Compra Tecnología</td>
                            <td>Estado </td>
                            <td> </td>
                            <td> </td>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="collapse px-2 py-2" id="verPedidoM" style="background: #f5f5f57a;border: 1px solid #ccc">
                <button class="btn btn-success mostrar" type="button">
                    <i class="fa fa-arrow-alt-circle-left"></i> Regresar
                </button>
                <br>
                <form id="form_modificar_pedido" enctype="multipart/form-data">
                    <div class="mb-3 row text-center">
                        <h2>
                            <b>MODIFICAR PEDIDO N°
                                <span style="color: red" id="num_pedido"></span> -
                                <span style="color: red" id="id_pedido"></span>
                                <input type="hidden" value="" name="id_pedido" id="id_pedido_modifi">
                            </b>
                        </h2>
                    </div>
                    <div class="mb-3 row">
                        <div class="mb-3 col-6">
                            <label for="nombre_empresa" class="form-label pe-2">Cliente : </label>
                            <span id="nombre_empresa"></span>
                        </div>
                        <div class="mb-3 row col-6">
                            <label for="nombre_empresa" class="form-label col-2">Asesor : </label>
                            <div class="col-10">
                                <select disabled="true" class="form-control select_2" style="width: 100%;" id="id_persona_modifi">
                                    <?php foreach ($personas as $pers) { ?>
                                        <option value="<?= $pers->id_persona ?>"><?= $pers->nombres . " " . $pers->apellidos ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="mb-3 col-6">
                            <label for="fecha_crea_p" class="form-label pe-2">Fecha Pedido : </label>
                            <span id="fecha_crea_p"></span>
                        </div>
                        <div class="mb-3 col-6">
                            <label for="nit" class="form-label pe-2">Nit : </label>
                            <span id="nit"></span>-<span id="dig_verificacion"></span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="mb-3 row col-6">
                            <label for="orden_compra" class="form-label pe-2 col-2">Orden de Compra : </label>
                            <div class="col-10">
                                <input type="text" class="form-control" id="orden_compra_modifi" name="orden_compra" />
                                <input type="hidden" id="orden_compra_antigua" name="orden_compra_antigua" />
                            </div>
                        </div>
                        <div class="mb-3 row col-6">
                            <label for="pdf_cambio" class="form-label pe-2 col-2">Archivo Orden de Compra : </label>
                            <div class="col-10">
                                <input type="file" class="form-control" id="pdf_cambio" name="orden_compra_file" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="mb-3 col-6">
                            <label for="orden_compra" class="form-label pe-2">Dirección de Entrega : </label>
                            <select class="form-control select_2" style="width: 100%;" id="id_dire_entre" name="id_dire_entre"></select>
                        </div>
                        <div class="mb-3 col-6">
                            <label for="orden_compra" class="form-label pe-2">Dirección de Radicación : </label>
                            <select class="form-control select_2" style="width: 100%;" id="id_dire_radic" name="id_dire_radic"></select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="mb-3 ps-4 col-6">
                            <label>Contacto :</label>
                            <span style="color: #0027d2;" class="fw-bold" id="contacto"></span><br>
                            <label>Cargo : </label>
                            <span style="color: #0027d2;" class="fw-bold" id="cargo"></span><br>
                            <label>E-mail :</label>
                            <span style="color: #0027d2;" class="fw-bold" id="email"></span><br>
                            <label>Celular :</label>
                            <span style="color: #0027d2;" class="fw-bold" id="celular"></span><br>
                        </div>
                        <div class="mb-3 ps-4 col-6">
                            <label>Telefono :</label>
                            <span style="color: #0027d2;" class="fw-bold" id="telefono"></span><br>
                            <label>Horario Repeción :</label>
                            <span style="color: #0027d2;" class="fw-bold" id="horario"></span><br>
                            <label>Condición Pago :</label>
                            <span style="color: #0027d2;" class="fw-bold" id="forma_pago"></span><br>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="mb-3 ps-4 col-4">
                            <label>Recibe Parcial :</label>
                            Si <input type="radio" name="parcial" id="parci_siM" value="1">
                            <span style="padding-left: 5px"></span>
                            No <input type="radio" name="parcial" id="parci_noM" value="2">
                        </div>
                        <div class="mb-3 ps-4 col-2">
                            <div class="input-group mb-3">
                                <label for="porcentaje_modifi" class="pe-2">Diferencia :</label>
                                <input type="text" class="form-control" name="porcentaje" id="porcentaje_modifi" aria-describedby="basic-addon1">
                                <span class="input-group-text" id="basic-addon1">%</span>
                            </div>
                        </div>
                        <div class="mb-3 ps-4 col-6">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="difer_mas" name="difer_mas" value="0">
                                <label class="form-check-label" for="difer_mas">Mas</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="difer_menos" name="difer_menos" value="0">
                                <label class="form-check-label" for="difer_menos">Menos</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="difer_ext" name="difer_ext" value="0">
                                <label class="form-check-label" for="difer_ext">Exacto</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="mb-3 row text-center">
                        <h4>PRODUCTOS</h4>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-4">
                            <select class="form-control select_2 id_clien_produc" style="width: 100%" id="id_clien_produc" name="id_clien_produc">
                            </select>
                            <span id="m55"></span>
                            <p class="help-block">Añadir producto cliente.</p>
                        </div>
                        <div class="col-8 collapse" id="datos_anade">
                            <div class="row">
                                <div class="col-2">
                                    <p class="fw-bold">Valor Venta:<span style="color: green;"> $ <span style="color: black;" id="valor_venta"></span><span id="moneda_venta"></span></span></p>
                                </div>
                                <div class="col-2">
                                    <p class="fw-bold">Valor Autorizado:
                                        <span style="color: green;"> $ <span style="color: black;" id="valor_Autoriza"></span><span id="moneda_autoriza"></span></span></span>
                                    </p>
                                </div>
                                <div class="col-3 collapse" id="trm_cambio">
                                    <div class="row">
                                        <label class="fw-bold col-2">Trm:</label>
                                        <div class="col-10">
                                            <input type="text" class="form-control" id="trm" name="trm" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="input-group">
                                        <input class="form-control" type="text" id="cantidad" name="cantidad" placeholder="Este campo no admite puntos.">
                                        <button class="btn btn-success" type="button" data-product="" id="add_producto">Agregar <i class="fas fa-pencil-alt"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <table id="tabla_item_pedido" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <th>Opción</th>
                                <th>#</th>
                                <th>Item</th>
                                <th>O.P</th>
                                <th style="width: 150px">Codigo</th>
                                <th style="width: 250px">Descripción</th>
                                <th>Cant.</th>
                                <th>Ficha Tec N°</th>
                                <th>Ruta Emb</th>
                                <th>Core</th>
                                <th>Roll. paq X</th>
                                <th>Trm</th>
                                <th>Moneda</th>
                                <th>V.unidad</th>
                                <th>Valor Total</th>
                                <th>Estado</th>
                                <th>Material</th>
                                <th>Fecha Item</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <br>
                    <div class="mb-3 row">
                        <div class="col-2"></div>
                        <div class="col-3" id="subtotalM"></div>
                        <div class="col-3" id="ivaM"></div>
                        <div class="col-3" id="totalM"></div>
                    </div>
                    <div class="mb-3">
                        <label>Observaciones :</label>
                        <textarea class="form-control" rows="6" cols="50" id="observaciones_modifi" name="observaciones"></textarea>
                    </div>
                    <div class="mb-3 row">
                        <div class="mb-3 col-4">
                            <label>Fecha Compromiso <span class="text-rojos">*</span></label>
                            <input type="text" class="form-control date datepicker" name="fecha_compromiso" id="fecha_compromiso_modifi" />
                            <span id="m66"></span>
                        </div>
                        <div class="mb-3 col-4">
                            <label>Fecha Cierre Facturación <span class="text-rojos">*</span></label>
                            <input type="text" class="form-control date datepicker" name="fecha_cierre" id="fecha_cierre_modifi">
                            <span id="m66"></span>
                        </div>
                        <div class="mb-3 col-4">
                            <label>Requiere Iva :</label>
                            Si <input type="radio" name="iva" id="iva_si" value="1">
                            <span style="padding-left: 5px"></span>
                            No <input type="radio" name="iva" id="iva_no" value="2">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="text-center">
                            <button class="btn btn-primary" type="submit" id="modifica_pedido">
                                <i class="fa fa-plus-circle"></i> Modificar Pedido
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar Material Asignado -->

<div class="modal fade" id="AsignarMaterial" aria-labelledby="AsignarMaterialLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modificar_material">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="AsignarMaterialLabel">Asignación Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h1 class="col-md-12 col-md-offset-4 ">Asignación Material</h1>
                </div>
                <div class="mb-3 row">
                    <div class="col-8">
                        <strong>Descripción : </strong><em id="descripcion_modifi"> </em>
                    </div>
                    <div class="col-4">
                        <strong>Código : </strong><em id="codigo_modifi"> </em>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="id_material_modifi" class="form-label">Material : </label>
                    <select class="form-control select_2" style="width: 100%;" id="id_material_modifi" name="id_material">
                        <option value="0">N/A</option>
                        <?php foreach ($productos as $pros) { ?>
                            <option value="<?= $pros->id_productos ?>"><?= $pros->codigo_producto . " BOBINA " . $pros->descripcion_productos ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="submit" class="btn btn-primary" id="modificar_material" data-id="">Modificar</button>
            </div>
        </form>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_modificar_pedido.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12" id="tabla_facturacion_listado">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Facturación</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <div class="panel-heading text-center mb-3">
                        <h3><b>Tabla Facturación</b></h3>
                    </div>
                    <div class="table-responsive">
                        <table id="table_pendientes_facturar" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <tr>
                                    <td>Fecha Compro</td>
                                    <td>Cliente</td>
                                    <td>Orden Compra</td>
                                    <td>Asesor</td>
                                    <td>Reciben Parcial</td>
                                    <td>Facturar Por</td>
                                    <td>Forma Pago</td>
                                    <td>Item</td>
                                    <td>Etiquetas</td>
                                    <td>Tecnologia</td>
                                    <td>Pedido</td>
                                    <td>Estado</td>
                                    <td>Opciones</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-2 py-2 col-lg-12" id="ConsultarPedido" style="display: none;">
        <input type="hidden" id="pertenece">
            <div class="row">
                <div class="form-group col-md-2">
                    <button class="btn btn-success ocultar_ver_pedido" type="button">
                        <i class="fa fa-arrow-alt-circle-left"></i> Regresar
                    </button>
                </div>
                <div class="form-group col-md-2">
                    <button class="btn btn-danger descarga" type="button" data-boton='1' id="num_pedido_modifi">
                        <i class="far fa-arrow-alt-circle-down"></i> Descarga Pedido
                    </button>
                </div>
                <div class="form-group col-md-2">
                    <button class="btn btn-info descarga" type="button" data-boton='2' id="descarga_oc">
                        <i class="fas fa-download"></i> Descarga O.C.
                    </button>
                </div>
            </div>
            <br>
            <form id="form-facturacion">
                <div class="mb-4" style="border:2px solid #bdbdbd;">
                    <div class="container-fluid py-3">
                        <div class="row">
                            <div class="form-group row col-4">
                                <label for="remision_factura" class="col-3">Tipo Documento</label>
                                <div class="col-md-9">
                                    <select id="remision_factura" class="form-control"></select>
                                </div>
                            </div>
                            <div class="form-group row col-4">
                                <label for="nombre_empresa" class="col-3">N° Documento:</label>
                                <div class="col-9">
                                    <span class="form-control" id="numero_factura">2021</span>
                                    <input type="hidden" id="numero_factura_consulta">
                                </div>
                            </div>
                            <div class="form-group row col-4">
                                <label for="nombre_empresa" class="col-3">Facturado Por:</label>
                                <div class="col-md-9">
                                    <span class="form-control"> <?= $usuario ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4" style="border:2px solid #bdbdbd;">
                    <div class="container-fluid">
                        <div class="text-center py-3">
                            <h3><b>DATOS PEDIDO N° <span style="color: red" id="num_pedido"></span></b></h3>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                <div class="form-group row">
                                    <label class="col-2" for="nombre_empresa_modifi">Razon social cliente:</label>
                                    <div class="col-10">
                                        <input type="text" readonly="" id="nombre_empresa_modifi" class="form-control bg-white">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group row">
                                    <label class="col-2" for="id_persona_modifi">Asesor Comercial:</label>
                                    <div class="col-10">
                                        <select disabled="true" class="form-control bg-white" id="id_persona_modifi">
                                            <?php foreach ($personas as $pers) { ?>
                                                <option value="<?= $pers->id_persona ?>"><?= $pers->nombres . " " . $pers->apellidos ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4">
                                <div class="form-group row">
                                    <label class="col-3" for="fecha_crea_p_modifi">Fecha Creación:</label>
                                    <div class="col-9">
                                        <input type="text" readonly="" class="form-control bg-white" id="fecha_crea_p_modifi">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group row">
                                    <label class="col-1" for="nit_modifi">nit:</label>
                                    <div class="col-11">
                                        <input type="text" readonly="" class="form-control bg-white" id="nit_modifi">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group row">
                                    <label class="col-3" for="orden_compra_modifi">Orden de compra:</label>
                                    <div class="col-9">
                                        <input type="text" readonly="" class="form-control bg-white" id="orden_compra_modifi">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6">
                                <div class="form-group row">
                                    <label class="col-2" for="direccion_entrega_modifi">Dirección entrega:</label>
                                    <div class="col-10">
                                        <input type="text" readonly="" id="direccion_entrega_modifi" class="form-control bg-white">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group row">
                                    <label class="col-2" for="direccion_radicacion_modifi">Dir. radicación:</label>
                                    <div class="col-10">
                                        <input type="text" readonly="" id="direccion_radicacion_modifi" class="form-control bg-white">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4">
                                <div class="form-group row">
                                    <label class="col-3" for="contacto_modifi">Nombre Contacto:</label>
                                    <div class="col-9">
                                        <input type="text" readonly="" class="form-control bg-white" id="contacto_modifi">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group row">
                                    <label class="col-2" for="cargo_modifi">Cargo:</label>
                                    <div class="col-10">
                                        <input type="text" readonly="" class="form-control bg-white" id="cargo_modifi">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group row">
                                    <label class="col-2" for="email_modifi">Email:</label>
                                    <div class="col-10">
                                        <input type="text" readonly="" class="form-control bg-white" id="email_modifi">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-3">
                                <div class="form-group row">
                                    <label class="col-4" for="celular_modifi">Celular:</label>
                                    <div class="col-8">
                                        <input type="text" readonly="" class="form-control bg-white" id="celular_modifi">
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group row">
                                    <label class="col-2" for="telefono_modifi">Tel.fijo:</label>
                                    <div class="col-10">
                                        <input type="text" readonly="" class="form-control bg-white" id="telefono_modifi">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group row">
                                    <label class="col-3" for="horario_modifi">Horario recepción:</label>
                                    <div class="col-9">
                                        <input type="text" readonly="" class="form-control bg-white" id="horario_modifi">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4">
                                <div class="form-group row">
                                    <label class="col-3" for="forma_pago_modifi">Condición Pago:</label>
                                    <div class="col-9">
                                        <input type="text" readonly="" class="form-control bg-white" id="forma_pago_modifi">
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-4">
                                        <label for="parcial">Recibe parcial:</label>
                                    </div>
                                    <div class="col-8">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Si </span>
                                            </div>
                                            <div class="input-group-text">
                                                <input type="radio" class="parcial_si" disabled value="1">
                                            </div>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">No </span>
                                            </div>
                                            <div class="input-group-text">
                                                <input type="radio" class="parcial_no bg-white" disabled value="2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-2">
                                        <label for="diferencia">Diferencia:</label>
                                    </div>
                                    <div class="col-10">
                                        <div class="input-group">
                                            <input type="text" readonly="" class="form-control bg-white" id="porcentaje_modifi">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            <div class="input-group-text">
                                                <input type="checkbox" disabled id="difer_mas" value="1">
                                            </div>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Mas</span>
                                            </div>
                                            <div class="input-group-text">
                                                <input type="checkbox" disabled id="difer_menos" value="2">
                                            </div>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Menos</span>
                                            </div>
                                            <div class="input-group-text">
                                                <input type="checkbox" disabled id="difer_ext" value="3">
                                            </div>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Exacta</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4" style="border:2px solid #bdbdbd;">
                    <div class="container-fluid">
                        <div class="text-center mt-2">
                            <h4>PRODUCTOS</h4>
                        </div>
                        <table id="tabla_items_pedido" style="background: white; width: 100%;" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" >
                            <thead style="background:#0d1b50;color:white">
                                <tr>
                                    <th>item #</th>
                                    <th style="width: 130px;">Codigo</th>
                                    <th>Cant. Solicitada</th>
                                    <th>Cant. Por Facturar</th>
                                    <th>Cant. Facturada</th>
                                    <th>Descripción</th>
                                    <th>Trm</th>
                                    <th>Moneda</th>
                                    <th>Valor Unitario</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th style="width: 100px;">Opción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-3" id="subtotalPC"></div>
                            <div class="col-lg-3" id="ivaPC"></div>
                            <div class="col-lg-3" id="totalPC"></div>
                        </div>
                        <hr>
                        <div class="row">
                            <label for="observaciones_modifi">Observaciones :</label>
                            <div class="containt-fluid">
                                <textarea disabled="true" class="form-control bg-white" id="observaciones_modifi" rows="4" cols="50" ></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="fecha_compromiso_modifi">Fecha Compromiso </label>
                                <input disabled="true" type="date" class="form-control bg-white date" id="fecha_compromiso_modifi">
                            </div>
                            <div class="col-lg-4">
                                <label for="fecha_cierre_modifi">Fecha Cierre Facturación </label>
                                <input disabled="true" type="date" class="form-control bg-white date" id="fecha_cierre_modifi">
                            </div>
                            <div class="col-lg-4">
                                <label>Requiere Iva :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Si </span>
                                    </div>
                                    <div class="input-group-text">
                                        <input type="radio" class="iva_si" disabled value="1">
                                    </div>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">No </span>
                                    </div>
                                    <div class="input-group-text">
                                        <input type="radio" class="iva_no" disabled value="2">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <button class="btn btn-primary btn-lg boton-x" type="button" id="genera_lista_de_empaque" >Grabar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/facturacion/js/vista_pendiente_facturacion.js"></script>
<input type="hidden" value='<?= json_encode($clientes) ?>' id="lista_clientes">
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <!--tabla pedidos asesor-->
            <br>
            <div class="table-responsive">
                <div class="container-fluid">
                    <div class="tabla_h_pedido">
                        <form class="mb-5" id="form_consulta_pedidos" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                            <br>
                            <div class="text-center">
                                <h3>Filtro Consulta</h3>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 col-md-5">
                                    <label for="tipo">Tipo Consulta</label>
                                    <select class="form-control" name="tipo" id="tipo">
                                        <option value="0"></option>
                                        <option value="1" selected>Cliente</option>
                                        <option value="2">Fecha Creación</option>
                                        <option value="3">Num Pedido</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-5">
                                    <div id="ver_cliente">
                                        <label for="cliente">Razón Social</label>
                                        <select class="form-control select_2" name="cliente" id="cliente">
                                            <option value="0" selected></option>
                                            <?php foreach ($clientes as $value) { ?>
                                                <option value="<?= $value->id_cli_prov ?>"><?= $value->nombre_empresa ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2">
                                    <br>
                                    <button type="submit" class="btn btn-primary" id="boton_consulta">Consultar</button>
                                </div>
                            </div>
                            <br>
                        </form>
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
                    <div class="collapse detalle_pedido">
                        <br>
                        <!--formu crear pedido-->
                        <div class="card">
                            <div class="card-header">
                                <h3 style="text-align: center;"><b>CONSULTAR PEDIDO N° <span style="color:red" id="num_pedidoPC"></span></b></h3>
                                <div class="row">
                                    <div class="col-12 col-md-4 mb-2">
                                        <button type='button' class='btn btn-success regresa_pedidos'>
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


<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>

<script src="<?= PUBLICO ?>/vistas/Comercial/js/historial_pedidos.js"></script>
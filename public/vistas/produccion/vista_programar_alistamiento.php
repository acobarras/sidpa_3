<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="alistamiento_mp_op-tab" data-bs-toggle="tab" href="#alistamiento_mp_op" role="tab" aria-controls="alistamiento_mp_op" aria-selected="true">Programar Alistamiento O.P.</a>
                </div>
            </nav>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="alistamiento_mp_op" role="tabpanel" aria-labelledby="alistamiento_mp_op-tab">
                    <br>
                    <h3 class="text-center fw-bold">Programar Alistamiento O.P.</h3>
                    <br>
                    <div class="TablaOrden">
                        <table id="dt_alista_ordenes_produccion" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                            <thead style="background:#0d1b50;color:white">
                                <tr>
                                    <td style="text-align: center">Fecha Compromiso</td>
                                    <td style="text-align: center">Núm O.P.</td>
                                    <td>Turno</td>
                                    <td>Maquina</td>
                                    <td>Tamaño Etiq</td>
                                    <td>Ancho </td>
                                    <td>Cantidad O.P.</td>
                                    <td>ML Total</td>
                                    <td>ML Alistados</td>
                                    <td>Material </td>
                                    <td>Fecha Proveedor</td>
                                    <td>Orden Compra</td>
                                    <td>Fecha Producción</td>
                                    <td>Estado</td>
                                    <td>Opción </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <br>
                    <!--------------------------------------------------------------------------------ver orden de producción------------------------------------------------------------------------------------------------------------------>
                    <div class="InfoOrden" style="display: none;">
                        <div class="card card-body" style="border: 1px solid #ccc;padding: 10px">
                            <div class="row">
                                <div class="col-lg-4">
                                    <button class="btn btn-success" id="mostrarTabla" type="button" data-bs-toggle="collapse" data-bs-target=".InfoOrden" aria-expanded="false" aria-controls="collapseExample">
                                        <i class="fa fa-caret-left"></i> Regresar
                                    </button>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="row">
                                <div class="col-lg-4">
                                    <h3>Número de O.P. &nbsp;&nbsp;| <b style="color:#0d1b50 ;" id="numORDEN"></b></h3>
                                    <h3>Tamaño Etiqueta | <b style="color:#0d1b50 ;" id="etiqTAMNIO"></b></h3>
                                    <h3>Total Metros Lineales | <b style="color:#0d1b50 ;" id="mtORDEN"></b> mL</h3>
                                </div>
                                <div class="col-lg-4">
                                    <h3>Ancho &nbsp;&nbsp;| <b style="color:#0d1b50 ;" id="anchoORDEN"></b></h3>
                                    <h3>Cantidad O.P | <b style="color:#0d1b50 ;" id="cantORDEN"></b></h3>
                                </div>
                            </div>
                            <br>
                            <h4 style="text-align: center"><b>ITEMS</b></h4>

                            <table id="dt_items_op" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Descripción</th>
                                        <th>Ubicación</th>
                                        <th>Ficha tecnica</th>
                                        <th>Cantidad</th>
                                        <th>mL</th>
                                        <th>m²</th>
                                        <th>Pedido-Item</th>
                                        <th>Core</th>
                                        <th>Rollo X/paq</th>
                                        <th>Sen/Emb</th>
                                        <th>Estado</th>
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

<!------------------------------- MODAL TABLA METROS LINEALES ALISTADOS -------------------------------------->

<div class="modal fade" id="Modal_materiales" role="dialog" aria-labelledby="LargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal">
                    <p> </p>
                </div>
                <h3 class="modal-title">Material Para la O.P. <span style="color: red;" id="Num_OP"></span></h3>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <div class="recuadro">
                    <div class="container-fluid"><br>
                        <table id="dt_materiales" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" width="100%">
                            <thead style="background: black;color:white">
                                <tr>
                                    <th>Material</th>
                                    <th>Ancho</th>
                                    <th>Metros Lineales</th>
                                </tr>
                            </thead>
                        </table>
                    </div><br>
                </div>
            </div>
        </div>
    </div>
</div>

<!------------------------------------- MODAL CARGAR MATERIAL OP -------------------------->
<div class="modal fade" id="Modal_CARGA_MATERIAL" role="dialog" aria-labelledby="LargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal">
                    <p> </p>
                </div>
                <h6 class="modal-title" id="exampleModalLabel">Alistar Material
                    <b id="nombreM"></b> | Orden de Producción :
                    <b id="ordenP"></b><br>
                    Maquina : <b id="maquinaM"></b>
                    <input id="id_maquinaM" hidden="true" type="text">
                </h6>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <div class="recuadro">
                    <div class="container-fluid"><br>
                        <div class="div_alista_material">
                            <div class="row">
                                <div class="col-8">
                                    <table id="dt_alista_material" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" width="100%">
                                        <thead style="background: black;color:white">
                                            <tr>
                                                <th>Ancho</th>
                                                <th>Cant. M²</th>
                                                <th>Cant. ML</th>
                                                <th>Elija</th>
                                                <th>Cantidad ML</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="col-4">
                                    <label class="fw-bold" style="font-size: 18px;">Total ML Solicitado:</label>
                                    <span class="fw-bold" style="color: blue; font-size: 22px;" id="MLTotal"><span>ML</span></span>
                                    <hr>
                                    <label class="fw-bold" style="font-size: 18px;">Ancho : </label>
                                    <span class="fw-bold" style="color: blue; font-size: 22px;" id="Ancho"></span>
                                    <hr>
                                    <label class="fw-bold" style="font-size: 18px;">Total ML : </label>
                                    <span class="fw-bold" style="color: blue; font-size: 22px;" id="info_ML"><span></span>ML</span>
                                    <hr>
                                    <label class="fw-bold" style="font-size: 18px;">Total M² : </label>
                                    <span class="fw-bold" style="color: blue; font-size: 22px;" id="info_M2"> <span>M²</span></span>
                                    <hr><br><br>
                                    <div>
                                        <button type="button" class="btn btn-success btn-sm alista_mat_completo" id="btn_ok_alista" data-op="">Cargar Material <i class="fa fa-thumbs-up"></i></button>
                                    </div><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Reasignar la maquina de Produccion -->

<div class="modal fade" id="CambioMaquinaModal" aria-labelledby="CambioMaquinaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="form_cambio_maquina">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="CambioMaquinaModalLabel">Cambio Maquina Producción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h1 class="col-form-label">Número producción: <span style="color:red" id="num_produccion_data"></span></h1>
                </div>
                <div class="mb-3 row">
                    <div class="col-6">
                        <label class="col-form-label">Maquina:</label>
                        <select id="maquina_data" class="form-control select_2 turno_maquina" style="width: 100%">
                            <option value="0"></option>
                            <?php foreach ($maquinas as $maquina) {
                                if ($maquina->tipo_maquina == 1 || $maquina->tipo_maquina == 3) {
                            ?>
                                    <option value="<?= $maquina->id_maquina ?>"> <?= $maquina->nombre_maquina ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="col-form-label">Turno:</label>
                        <input type="number" class="form-control" id="turno_data" placeholder="Ingrese Turno">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-form-label">Fecha producción:</label>
                    <input type="text" class="form-control datepicker turno_maquina" id="fecha_produccion_data">
                </div>
                <h4 style="text-align: center">TURNOS</h4>
                <div class=" table-responsive">
                    <table id="fecha_turno_maquina_data" class="table table-hover table-bordered" cellspacing="0" width="100%">
                        <thead class="thead-dark">
                            <tr>
                                <th>Maquina</th>
                                <th>TURNO</th>
                                <th>Núm O.P.</th>
                                <th>mL total</th>
                                <th>Tamaño etiq</th>
                                <th>Cantidad </th>
                                <th>Material</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="3">Total Ml</td>
                                <td></td>
                                <td>Total Q</td>
                                <td></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                        <tbody></tbody>
                    </table>
                    <br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="button" class="btn btn-primary" id="generar_cambio_maquina" data-id="">Continuar</button>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/produccion/js/vista_programar_alistamiento.js"></script>
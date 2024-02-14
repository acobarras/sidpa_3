<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div id="contenido" class="px-2 py-2 col-lg-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="alistamiento_mp_op-tab" data-bs-toggle="tab" href="#alistamiento_mp_op" role="tab" aria-controls="alistamiento_mp_op" aria-selected="true">Alistamiento Materia Prima O.P</a>
                </div>
            </nav>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="alistamiento_mp_op" role="tabpanel" aria-labelledby="alistamiento_mp_op-tab">
                    <br>
                    <h3 class="text-center fw-bold">Alistamiento de Materia Prima O.P</h3>
                    <br>
                    <div class="TablaOrden">
                        <table id="dt_alista_ordenes_produccion" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md table-responsive-lg" cellspacing="0" width="100%">
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
                                    <td>Ubicación Troquel</td>
                                    <td>Ubicación Ficha</td>
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

<!-------------------------------------------------- INICIO MODALEs--------------------------------------------->
<!-------------------------------------------------- MODAL TABLA DE MATERIALES ALISTADOS --------------------------------------------->
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
<!-------------------------------------------------- MODAL CARGAR MATERIAL OP --------------------------------------------->
<div class="modal fade" id="Modal_CARGA_MATERIAL" role="dialog" aria-labelledby="LargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="exampleModalLabel">Alistar Material
                    <b id="nombreM"></b> | Orden de Producción :
                    <b id="ordenP"></b><br>
                    Maquina : <b id="maquinaM"></b>
                    <input id="id_maquinaM" hidden="true" type="text">
                </h5>
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
                                        <button type="button" class="btn btn-primary btn-sm carga_inventario">Cargar Inventario
                                            <i class="fa fa-dolly-flatbed"></i>
                                            <i class="fa fa-sort-amount-up"></i></button>
                                    </div><br><br>
                                    <div>
                                        <button type="button" class="btn btn-warning btn-sm alista_mat_parcial" id="btn_parcial_alista" data-op="">Parcial <i class="fa fa-paper-plane"></i></button>
                                    </div><br><br>
                                    <div>
                                        <button type="button" class="btn btn-success btn-sm alista_mat_completo" id="btn_ok_alista" data-op="">OK <i class="fa fa-thumbs-up"></i></button>
                                    </div><br>
                                </div>
                            </div>
                        </div>
                        <div class="div_agrega_inventario" style="display:none;">
                            <div class="panel panel-primary" style="margin-top: 20px;box-shadow: -4px 4px 4px -4px rgba(0,0,0,0.75); ">
                                <div class="panel-heading header_aco">
                                    <button id="btn_ocultar_inventario" class="btn btn-primary btn-circle"><i class="fa fa-reply"></i>
                                    </button>
                                    <center>
                                        <h3 class="col-md-12 col-md-offset-4 text-center">Ingreso De Mercancia Bobinas</h3>
                                    </center>
                                </div>
                                <br />
                                <div class="container">
                                    <form id="bobina">
                                        <div class="mb-3 row">
                                            <label for="documento" class="col-sm-2 col-form-ablel fw-bold">Factura:</label>
                                            <div class="col-sm-10">
                                                <input class="form-control col-8" type="text" name="documento" id="documento">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="codigo_producto" class="col-sm-2 col-form-ablel fw-bold">Codigo Bobina:</label>
                                            <div class="col-sm-10">
                                                <input class="form-control col-8" type="text" name="codigo_producto" id="codigo_producto">
                                                <input type="hidden" name="id_productos" id="id_producto">
                                                <span class="text-danger" id="respu_codigo_tecno"></span>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="ubicacion" class="col-sm-2 col-form-label fw-bold">Ubicación:</label>
                                            <div class="col-sm-10">
                                                <select class="form-control select_2" name="ubicacion">
                                                    <option value="0"></option>
                                                    <?php foreach ($ubicacion as $ubi) { ?>
                                                        <option value="<?= intval($ubi->ancho) ?>"><?= intval($ubi->ancho) ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="ancho" class="col-sm-2 col-form-ablel fw-bold">Ancho:</label>
                                            <div class="col-sm-10">
                                                <input class="form-control solo-numeros col-8" type="text" name="ancho" id="ancho">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="metros" class="col-sm-2 col-form-ablel fw-bold">Metros Lineales:</label>
                                            <div class="col-sm-10">
                                                <input class="form-control solo-numeros col-8" type="text" name="metros" id="metro_lineales_alista_op" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="entrada" class="col-sm-2 col-form-ablel fw-bold">Metros Cuadrados:</label>
                                            <div class="col-sm-10">
                                                <input class="form-control col-8" type="text" name="entrada" id="m2" readonly>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label fw-bold">Descripcion:</label>
                                            <span class="col-sm-10 fw-bold text-primary" id="respuesta"></span>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary" id="btn_ingresar_bob" data-valida="false">
                                                    <i class="fa fa-plus-circle"></i> Grabar
                                                </button>
                                            </div>
                                        </div>
                                        <br>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="div_imprimir_etiqueta" style="display: none;">
                            <button class="btn btn-primary btn-circle btn_ocultar_imprimir_etiq"><i class="fa fa-reply"></i>
                            </button> <br>
                            <h6 style="text-align: center">
                                <b>OKEY LOS METROS LINEALES ESTAN LISTOS <br>
                                    <br>POR FAVOR IMPRIMA LA ETIQUETA DE REMARCACIÓN Y DE EN EL BOTON GRABAR.</b>
                            </h6>
                            <center>
                                <div id="link">

                                </div><br>
                                <button class="btn btn-success" id="grabar_material_final">Grabar</button>
                                <button class="btn btn-danger  btn_ocultar_imprimir_etiq">Salir</button>
                            </center>
                            <br><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-----------------------------------------------------------------------------------modal para mostrar informacion del boton rojo------------------------------------------------>
<div class="modal fade" id="Modal_motivos" role="dialog" aria-labelledby="LargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="exampleModalLabel">Motivos | Orden de Producción : <b id="ordenPP"></b> <span id="id_maquinap"></span></h3>
                <i class="bi bi-x cerrar" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <div class="recuadro">
                    <div class="container-fluid"><br>
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="table-responsive" style="border: 1px solid #ccc;padding: 10px;border-radius: 5px">
                                    <b>*Falta Ficha Técnica</b> <input type="checkbox" name="" id="sin_ficha" value="1">
                                    <hr>
                                    <b>*Falta Cireles</b> <input type="checkbox" name="" id="sin_cireles" value="2">
                                    <div id="cireles_2" style="display: none">
                                        &nbsp► Total tintas <input type="checkbox" name="" id="total_tintas" value="4"><br>
                                        &nbsp► Cantidad Fotopolímeros <input type="checkbox" name="" id="fotopolimeros">
                                        <input placeholder="1-8" min="1" max="8" class="form-control" style="display: none;width: 50%" type="number" name="" id="cantidad_tintas">
                                    </div>
                                    <hr>
                                    <b>*Falta Troquel </b><input type="checkbox" name="" id="sin_troquel" value="3">
                                    <div id="troquel_2" style="display: none">
                                        &nbsp► Troquel Dañado / No encontrado <input type="checkbox" name="" id="troquel_dañado" value="5"><br>
                                        &nbsp► Troquel No a llegado <input type="checkbox" name="" id="troquelN" value="6">
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <br>
                                <br>
                                <label>Se enviará correo al área de diseño </label><br>
                                <button class="btn btn-lg btn-primary" data-dismiss="modal" id="btn-retener">Guardar</button>
                            </div>
                        </div>

                    </div><br>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/almacen/js/vista_alista_materia_prima_op.js"></script>
<script src="<?= PUBLICO ?>/vistas/almacen/js/vista_ingreso_bobinas.js"></script>
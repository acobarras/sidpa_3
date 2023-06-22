<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <br>
        <div id="datos_orden" class="mx-3 mt-2">
            <div class="text-center">
                <h2>Tabla Ordenes De Producción</h2>
            </div>
            <br>
            <table id="tabla_ordenes_op" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                <thead style="background: #002b5f;color: white">
                    <tr>
                        <td>Fecha</td>
                        <td>Núm O.P.</td>
                        <td>Maquina</td>
                        <td>Tamaño Etiq</td>
                        <td>Ancho</td>
                        <td>Cantidad O.P.</td>
                        <td>ML Total</td>
                        <td>Material</td>
                        <td>Inventario</td>
                        <td>Opción </td>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <br>
        </div>
        <div class="collapse InfoOrden">
            <div class="mb-3 ms-3">
                <button class="btn btn-primary" id="mostrarTabla" type="button" data-toggle="collapse" data-target=".InfoOrden" aria-expanded="false" aria-controls="collapseExample">
                    <i class="fa fa-caret-left"></i> Regresar
                </button>
            </div>
            <br>
            <div class="mb-3 ms-3 row">
                <div class="col-6">
                    <h3>Número de O.P. &nbsp;&nbsp;| <b id="numORDEN"></b></h3>
                    <h3>Tamaño Etiqueta | <b id="tamanoORDEN"></b></h3>
                    <h3>Total Metros Lineales | <b id="mtORDEN"></b> mL</h3>
                </div>
                <div class="col-6">
                    <h3>Ancho &nbsp;&nbsp;| <b id="anchoORDEN"></b></h3>
                    <h3>Cantidad O.P | <b id="cantORDEN"></b></h3>
                </div>
            </div>
            <div class="mb-3 ms-3 text-center">
                <h4><b>ITEMS</b></h4>
            </div>
            <div class="mb-3 ms-3">
                <table id="datos_op" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Descripción</th>
                            <th>ubicación</th>
                            <th>Cantidad</th>
                            <th>mL</th>
                            <th>m²</th>
                            <th>Pedido-Item</th>
                            <th>Core</th>
                            <th>Rollo X/paq</th>
                            <th>Sen/Emb</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Asignar orden de producción -->

<div class="modal fade" id="AlistaMaterialModal" aria-labelledby="AlistaMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modificar_material">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="AlistaMaterialModalLabel">Alistar Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center">
                    <h4>Alistar Material: <b id="codigoM"></b>
                        | Orden de Producción : <b id="ordenP" style="color:darkred"></b>
                        | Maquina : <b id="maquinaL"></b> | <b id="codigoE"></b>
                    </h4>
                </div>
                <div class="mb-3 row">
                    <div class="col-7">
                        <div id="materiales_dos"></div>
                        <br>
                        <table id="dt_anchos" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Ancho</th>
                                    <th>Cant. M2</th>
                                    <th>Cant. ML</th>
                                    <th></th>
                                    <th>Cantidad en ML</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-5">
                        <h4>Solicita ML : <b style="color: blue" id="MLTotal"></b></h4>
                        <h4>Solicita Ancho : <b style="color:blue" id="Ancho"></b></h4>
                        <hr>
                        <br>
                        <h4>Total M2 : <b style="color:blue;" id="info_M2"></b></h4>
                        <h4>Total ML : <b style="color:blue;" id="info_ML"></b></h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                <button type="button" class="btn btn-success" id="pendiente_fecha_op">Pendiente Fecha P.</button>
                <button type="button" class="btn btn-primary" id="compra_orden_produccion">Comprar</button>
            </div>
        </form>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/produccion/js/vista_orden_produccion.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <br>
        <div id="datos_orden">
            <div class="mx-3 mt-2">
                <div class="text-center">
                    <h2>Asignar Fecha Ordenes De Producción</h2>
                </div>
                <br>
                <table id="tabla_ordenes_produccion" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                    <thead style="background: #002b5f;color: white">
                        <tr>
                            <td>Fecha Compromiso</td>
                            <td>Núm O.P.</td>
                            <td>Maquina</td>
                            <td>Tamaño Etiq</td>
                            <td>Ancho </td>
                            <td>Cantidad O.P.</td>
                            <td>ML Total</td>
                            <td>ML Alistados </td>
                            <td>Material </td>
                            <td>Fecha Proveedor</td>
                            <td>Orden Compra</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <br>
            </div>
            <form class="container" id="form_datos" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                <div class="mb-3">
                    <label for="fecha_produccion" class="form-label">Fecha Producción : </label>
                    <input autocomplete="off" type="text" class="form-control" name="fecha_produccion" id="fecha_produccion">
                </div>
                <div class="mb-3">
                    <label for="turno_maquina" class="form-label">Turno : </label>
                    <input autocomplete="off" type="number" class="form-control" name="turno_maquina" id="turno_maquina">
                </div>
                <div class="mb-3">
                    <label for="fecha_compromiso" class="form-label">Fecha Compromiso : </label>
                    <input autocomplete="off" type="text" class="form-control" name="fecha_compromiso" id="fecha_compromiso">
                </div>
                <div class="mb-3 text-center">
                    <button class="btn btn-primary" type="button" id="asignar_fecha_op">
                        <i class="fa fa-plus-circle"></i> Programar
                    </button>
                </div>
                <br>
            </form>
            <br>
            <div class="mx-3">
                <table id="tabla_turnos" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                    <thead style="background: #002b5f;color: white">
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
                    <tbody></tbody>
                </table>
                <br>
            </div>
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
                            <th>Fecha Cierre</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/produccion/js/vista_asignar_fecha_op.js"></script>
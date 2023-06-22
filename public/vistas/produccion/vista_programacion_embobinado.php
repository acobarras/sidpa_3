<div id="principal" class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="mb-3 mt-4">
            <br>
            <div class="text-center">
                <h2>Programación O.P. Embobinado.</h2>
            </div>
            <br>
            <div class="TablaOrden">
                <table id="tabla_programacion_maquina" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                    <thead style="background: #002b5f;color: white">
                        <tr>
                            <td style="text-align: center">Núm O.P.</td>
                            <td>Fecha Compromiso</td>
                            <td>Tamaño Etiq</td>
                            <td>ML Total</td>
                            <td>ML Entregados</td>
                            <td>Estado</td>
                            <td>Opción </td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <br>
            </div>
            <br>
        </div>
    </div>
</div>

<!-- vista para ver la orden de produccion para programar el embobinado -->
<div class="container-fluid mt-3 mb-3" id="permisos_usuario" style="display: none;">
    <form class="recuadro" method="POST" enctype="multipart/form-data" id="form_asigna_embobinado">
        <input type="hidden" id="num_op" name="num_op">
        <div class="px-2 py-2 col-lg-12">
            <button class="btn btn-primary" id="regresar" type="button">
                <i class="fa fa-caret-left"></i> Regresar
            </button>
        </div>
        <br>
        <div class="mb-3 text-center">
            <h2><b>Orden de Producción N° <span id="num_orden" style="color:red"></span> </b></h2>
        </div>
        <div class="container-fluid">
            <hr>
        </div>
        <div class="mb-3 row text-center">
            <div class="col-lg-2">
                <h3>N° Items : <b id="items_orden"></b></h3>
            </div>
            <div class="col-lg-2">
                <h3>Ancho : <b id="ancho_orden"></b></h3>
            </div>
            <div class="col-lg-2">
                <h3>ML Faltantes : <b id="ml_orden"></b></h3>
            </div>
            <div class="col-lg-3">
                <h3>Cant. O.P : <b id="cant_orden"></b></h3>
            </div>
            <div class="col-lg-3">
                <h3>Cant Reportada : <b id="cant_reportada"></b></h3>
            </div>
        </div>
        <br>
        <table id="tabla_embobinado" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
            <thead style="background: #002b5f;color: white">
                <tr>
                    <td>#</td>
                    <td>Pedido Item</td>
                    <td>Fecha Compromiso</td>
                    <td>Codigo Etiqueta</td>
                    <td>Descripción</td>
                    <td>Cantidad O.P.</td>
                    <td>Q Reportada</td>
                    <td>Core</td>
                    <td>Opción </td>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <br>
        <div class="mb-3 row">
            <div class="col-4">
                <label for="maquina_embo" class="form-label">Maquina Embobinado : </label>
                <select class="form-control select_2 ver_programacion" name="maquina_embo" id="maquina_embo" style="width: 100%;">
                    <option value="0"></option>
                    <?php foreach ($maquinas_embobinado as $value) { ?>
                        <option value="<?= $value->id_maquina ?>"><?= $value->nombre_maquina ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-4">
                <label for="fecha_embo" class="form-label">Fecha Embobinado : </label>
                <input class="form-control datepicker ver_programacion" type="text" autocomplete="off" name="fecha_embo" id="fecha_embo">
            </div>
            <div class="col-4">
                <label for="turno_embo" class="form-label">Turno Embobinado : </label>
                <input class="form-control" type="number" autocomplete="off" name="turno_embo" id="turno_embo">
            </div>
        </div>
        <div class="mb-3 text-center">
            <button class="btn btn-primary" id="asignar_maquina" type="submit">Grabar</button>
        </div>
        <br>
        <table id="tabla_prog_total" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
            <thead style="background: #002b5f;color: white">
                <tr>
                    <th>Núm O.P.</th>
                    <th>Maquina</th>
                    <th>TURNO</th>
                    <th>mL total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <br>
    </form>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/produccion/js/vista_programacion_embobinado.js"></script>
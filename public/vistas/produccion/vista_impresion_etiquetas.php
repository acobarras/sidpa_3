<div class="container-fluid mt-3 mb-3">
    <div class="recuadro" id="principal">
        <br>
        <div class="container" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
            <br>
            <div class="mb-3 text-center">
                <h1>DATOS PARA IMPRESIÓN POR NUMERO DE PEDIDO</h1>
            </div>
            <div class="mb-3">
                <div class="row" name="form_reclamacion" id="form_reclamacion">
                    <div class="col-5">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="num_pedido">Numero de Pedido:</label>
                            <input type="text" class="form-control num_pedio" id="num_pedido">
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="input-group mb-3 col-5">
                            <label class="input-group-text" for="slect_items">Item:</label>
                            <select class="form-control select_2" id="slect_items" style="width: 75%;">
                                <option value="0"></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <input type="button" class="btn btn-success" value="Enviar" id="envio" disabled="">
                    </div>
                </div>
            </div>
            <br>
            <form method="POST" enctype="multipart/form-data" id="formulario_remarcacion" style="display:none">
                <div class="mb-3 form-group row">
                    <label for="tamano" class="col-sm-2 col-form-label">Tamaño Etiqueta:</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="tamano" id="tamano">
                            <option value="1" selected="">46x18</option>
                            <option value="3">52x33</option>
                            <option value="2">100x50</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="pasword" class="col-2 col-form-label">Codigo Operario:</label>
                    <div class="col-10">
                        <input class="form-control codigo_operario" type="password" name="pasword" id="pasword">
                        <span class="respu_consulta"></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="lote" class="col-2 col-form-label">Lote:</label>
                    <div class="col-10">
                        <input class="form-control" type="text" name="lote" id="lote">
                    </div>
                </div>
                <div class="mb-3 row cajass" style="display:none;">
                    <label for="caja" class="col-2 col-form-label">Total Caja:</label>
                    <div class="col-10">
                        <input class="form-control" type="text" name="caja" id="caja">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="cantidad" class="col-2 col-form-label">Etiquetas a Imprimir:</label>
                    <div class="col-10">
                        <input class="form-control" type="text" name="cantidad" id="cantidad">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="cant_x" class="col-2 col-form-label">Rollo X:</label>
                    <div class="col-10">
                        <input class="form-control" type="text" name="cant_x" id="cant_x">
                    </div>
                </div>
                <div class="mb-3 text-center">
                    <label for="id_persona" style="display: none;">Codigo Operario:</label>
                    <input type="hidden" id="id_persona" name="id_persona">
                    <button type="submit" class="btn btn-primary btn-lg" id="boton_imprime">Imprimir</button>
                </div>
                <br>
                <div class="div_impresion"></div>
            </form>
            <br>
        </div>
        <br>
    </div>
</div>


<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/produccion/js/vista_impresion_etiquetas.js"></script>
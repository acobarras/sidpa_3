<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <div class="container" id="form_crear_tipo_articulo" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                <br>
                <div class="mb-3 row text-center">
                    <h1 class="col-md-12 col-md-offset-4 ">Descarga De PDF</h1>
                </div>
                <div class="mb-3">
                    <label for="num_pedido" class="form-label">Número de Pedido : </label>
                    <div class="input-group mb-3">
                        <input autocomplete="off" type="text" class="form-control" name="num_pedido" id="num_pedido">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" id="btn-num-pedido">Descargar <i class="fa fa-download boton_cambio_pedido"></i></button>
                        </div>
                    </div>
                </div>
                <br>
                <?php if ($_SESSION['usuario']->getid_roll() == 1) { ?>
                    <div class="mb-3">
                        <label for="orden_produccion" class="form-label">Número Orden de producción : </label>
                        <div class="input-group mb-3">
                            <input autocomplete="off" type="text" class="form-control" name="orden_produccion" id="orden_produccion">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="btn-orden-produccion" type="button">Descargar <i class="fa fa-download boton_cambio_op"></i></button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <br>
                <?php if ($_SESSION['usuario']->getid_roll() == 1 || $_SESSION['usuario']->getid_roll() == 13) { ?>
                    <div class="mb-3">
                        <label for="lista_empaque" class="form-label">Número Lista Empaque : </label>
                        <div class="input-group mb-3">
                            <input autocomplete="off" type="text" class="form-control" name="lista_empaque" id="lista_empaque">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="btn-lista-empaque" type="button">Descargar <i class="fa fa-download boton_cambio_lista"></i></button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($_SESSION['usuario']->getid_roll() == 1 || $_SESSION['usuario']->getid_usuario() == 79) { ?>
                    <div class="mb-3">
                        <label for="num_acta" class="form-label">Número Acta de Entrega : </label>
                        <div class="input-group mb-3">
                            <input autocomplete="off" type="text" class="form-control" name="num_acta" id="num_acta">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="genera_acta" type="button">Descargar <i class="fa fa-download boton_acta"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="num_cotiza" class="form-label">Número Cotización : </label>
                        <div class="input-group mb-3">
                            <input autocomplete="off" type="text" class="form-control" name="num_cotiza" id="num_cotiza">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="genera_cotiza" type="button">Descargar <i class="fa fa-download boton_cotiza"></i></button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <br>
            </div>
        </div>
    </div>
</div>

<div id="respu"></div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_descargar_pdf.js"></script>
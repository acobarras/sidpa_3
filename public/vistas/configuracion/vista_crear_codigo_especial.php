<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <br>
            <form class="container" id="form_crear_codigo_especial" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                <br>
                <div class="mb-3 text-center">
                    <h1 class="col-md-12 col-md-offset-4 ">Crear Nuevo Código Especial</h1>
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <div class="mb-3">
                            <label for="codigo_especial" class="form-label">Cógido Especial : </label>
                            <select class="form-control select_2" name="codigo_especial" id="codigo_especial" style="width: 100%;">
                                <option value="0"></option>
                                <?php foreach ($productos as $pro) {  ?>
                                    <option value="<?= $pro->codigo_producto ?>"><?= $pro->codigo_producto ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="mb-3">
                            <label for="codigo_relacion" class="form-label">Cógido Relación : </label>
                            <select class="form-control select_2" name="codigo_relacion" id="codigo_relacion" style="width: 100%;">
                                <option value="0"></option>
                                <?php foreach ($productos as $pro) {  ?>
                                    <option value="<?= $pro->codigo_producto ?>"><?= $pro->codigo_producto ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 pt-2">
                        <button class="btn btn-primary" type="submit" id="crear_codigo_especial">
                            <i class="fa fa-plus-circle"></i> Crear Codigo
                        </button>
                    </div>
                </div>
                <br>
            </form>
            <br>
            <div class="mx-3 mt-2">
                <div class="text-center">
                    <h2>Tabla de Códigos Especiales</h2>
                </div>
                <br>
                <table id="tabla_codigos_especiales" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                    <thead style="background: #002b5f;color: white">
                        <th>#</th>
                        <th>Código Identificador</th>
                        <th>Código de Producto relación</th>
                        <th>Opción</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_crear_codigo_especial.js"></script>
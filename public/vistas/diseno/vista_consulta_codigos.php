<div class="container-fluid p-4">
    <div class="recuadro p-2">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="consulta_codigo-tab" data-bs-toggle="tab" href="#consulta_codigo" role="tab" aria-controls="consulta_codigo" aria-selected="true">Consulta códigos</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="recuadro p-2">
                <div class="tab-pane fade show active p-3" id="consulta_codigo" role="tabpanel" aria-labelledby="consulta_codigo-tab">
                    <div class="panel panel-default"><br>
                        <div class="panel-heading text-center mb-3">
                            <h2>Consulta códigos etiquetas</h2>
                        </div>
                        <div class="border">
                            <form id="formulario_busqueda" class="shadow row g-3 p-2">
                                <div class="col-md-3 col-12">
                                    <label for="ancho" class="form-label fw-bold">Ancho:</label>
                                    <input class="form-control" type="text" name="ancho" id="ancho" placeholder="Ancho en milimetros">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="alto" class="form-label fw-bold">Alto:</label>
                                    <input class="form-control" type="text" name="alto" id="alto" placeholder="Alto en milimetros">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="cavidad" class="form-label fw-bold">Cavidad:</label>
                                    <input class="form-control" type="number" name="cavidad" id="cavidad" placeholder="Numero de cavidades">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="color" class="form-label fw-bold">Color:</label>
                                    <input class="form-control" type="text" name="color" id="color" placeholder="Color de la descripción">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="forma_material" class="form-label fw-bold">Forma Material:</label>
                                    <select class="form-control select_2" name="forma_material" id="forma_material">
                                        <option value="">Seleccione una forma</option>
                                        <?php foreach ($forma_material as $value) { ?>
                                            <option value="<?= $value->id_forma ?>"><?= $value->nombre_forma ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="tipo_material" class="form-label fw-bold">Tipo Material:</label>
                                    <select class="form-control select_2" name="tipo_material" id="tipo_material">
                                        <option value="">Selecciona un material</option>
                                        <?php foreach ($mat as $material) { ?>
                                            <option value="<?= $material->codigo ?>"><?= $material->nombre_material ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2 col-12">
                                    <label for="adhesivo" class="form-label fw-bold">Adhesivo:</label>
                                    <select class="form-control select_2" name="adhesivo" id="adhesivo">
                                        <option value="">Seleccione una adhesivo</option>
                                        <?php foreach ($adh as $value) { ?>
                                            <option value="<?= $value-> codigo_adh?>"><?= $value->nombre_adh ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2 col-12">
                                    <label for="gaf_cort" class="form-label fw-bold">Grafes y Cortes:</label>
                                    <select class="form-control select_2" name="gaf_cort" id="gaf_cort">
                                        <option value="">Selecciones tipo de grafe</option>
                                        <?php foreach (GRAF_CORTE as $key => $value) { ?>
                                            <option value="<?= $key ?>"><?= $value['nombre'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2 col-12">
                                    <label for="ficha_tecnica" class="form-label fw-bold">Incluir ficha técnica:</label>
                                    <select class="form-control select_2" name="ficha_tecnica" id="ficha_tecnica">
                                        <option value="">No aplica</option>
                                        <option value="1">Si</option>
                                        <option value="2">No</option>
                                    </select>
                                </div>
                                <div class="col-12 text-center">
                                    <button class="btn btn-success" id="buscar_cod" type="submit"><i class="fas fa-search"></i> Buscar</button>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive p-3">
                            <table id="tb_resultados" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                <thead class="bg-layout" style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>ID</th>
                                        <th>Código final</th>
                                        <th>Etiqueta o caja</th>
                                        <th>Ancho</th>
                                        <th>Alto</th>
                                        <th>Medida</th>
                                        <th>Forma</th>
                                        <th>Cavidad</th>
                                        <th>Material</th>
                                        <th>Adhesivo</th>
                                        <th>Grafes y cortes</th>
                                        <th>Descripción real</th>
                                        <th>Ficha técnica</th>
                                        <th>Folder</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/diseno/js/vista_consulta_codigos.js"></script>
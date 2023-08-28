<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="productividad-tab" data-bs-toggle="tab" href="#productividad" role="tab" aria-controls="productividad" aria-selected="true">Productividad</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Primer link -->
                    <div class="tab-pane fade show active" id="productividad" role="tabpanel" aria-labelledby="productividad-tab">
                        <div class="container-fluid">
                            <br>
                            <form id="cons_productividad">
                                <div class="row g-3 align-items-center">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="fecha_desde" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Fecha Desde</label>
                                            <input type="date" class="form-control" id="fecha_desde" name="fecha_desde">
                                        </div>
                                        <div class="col-4">
                                            <label for="fecha_hasta" class="col-form-label" style="font-family: 'gothic'; font-weight: bold; ">Fecha Hasta</label>
                                            <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta">
                                        </div>
                                        <div class="col-4 mt-4">
                                            <button type="submit" class="btn btn-success col-3" id="buscar_productividad">Buscar <i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <table id="tabla_productividad" class="table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                <thead style="background: #002b5f;color: white">
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Ml Total del Mes</th>
                                        <th>Horas Total Programadas del Mes</th>
                                        <th>Opcion</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/indicadores/js/vista_productividad.js"></script>
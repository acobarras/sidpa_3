<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <div class="mx-3 mt-2">
                <div class="text-center">
                    <h2>Tabla Consecutivos</h2>
                </div>
                <br>
                <table id="tabla_consecutivos" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                    <thead style="background: #002b5f;color: white">
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Numero Guardado</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_aumento_consecutivo.js"></script>
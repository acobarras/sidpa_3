<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12" id="tabla_facturacion_listado">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Alistamiento Cargue</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="my-3 panel-heading text-center">
                    <h2>Alistamiento Cargue</h2>
                </div>
                <br>
                <table id="tb_alista_cargue" class="table table-bordered table-responsive table-hover mb-3" cellspacing="0" width="100%">
                    <thead style="background: #002b5f;color: white">
                        <tr>
                            <th>Cliente</th>
                            <th>Forma Pago</th>
                            <th>N° Pedido Item</th>
                            <th>Orden Compra</th>
                            <th>Fecha Compromiso</th>
                            <th>Recibe</th>
                            <th>Direccion</th>
                            <th>Ruta</th>
                            <th>Items finalizados</th>
                            <th>Opción</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div class="row">
                    <span class="text-center text-danger" id="span_estado"></span>
                    <br>
                    <div class="col-md-6 col-12 input-group mb-3 m-auto justify-content-center">
                        <button type="button" id="doc_pedido" class="btn btn-success col-12 col-md-3">Documento <i class="fas fa-clipboard-check"></i></button>&nbsp;&nbsp;
                    </div>
                </div>
            </div>
            <div class="tab-content d-none" id="div_tabla_excel">
                <br>
                <table id="tabla_descarga_excel" class="table table-bordered table-responsive table-hover mb-3" cellspacing="0" width="100%">
                    <thead style="background: #002b5f;color: white">
                        <tr>
                            <th>Cliente</th>
                            <th>Dirección</th>
                            <th>N° Pedido</th>
                            <th>Item</th>
                            <th>Codigo</th>
                            <th>Descripción Productos</th>
                            <th>Ubicación</th>
                            <th>Cant Solicitada</th>
                            <th>Cant Reportada</th>
                            <th>Ruta</th>
                            <th>Modulo</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/logistica/js/alistamiento_cargue.js"></script>
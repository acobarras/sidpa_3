<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="item_producir-tab" data-bs-toggle="tab" href="#item_producir" role="tab" aria-controls="item_producir" aria-selected="true">Consulta Ordenes de Producción</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="materia_prima_op-tab" data-bs-toggle="tab" href="#materia_prima_op" role="tab" aria-controls="materia_prima_op" aria-selected="true">Salida Materia Prima</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="items_op-tab" data-bs-toggle="tab" href="#items_op" role="tab" aria-controls="items_op" aria-selected="true">Entradas A Almacen</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Primer link -->
                    <div class="tab-pane fade show active" id="item_producir" role="tabpanel" aria-labelledby="item_producir-tab">
                        <div class="container-fluid">
                            <br>
                            <div class="recuadro">
                                <br>
                                <h3 class="text-center fw-bolder" style="font-family: 'gothic';">Ordenes de Producción</h3>
                                <br>
                                <form id="form_consulta_fecha">
                                    <div class="row container-fluid">
                                        <div class="form-group col-12 col-md-5">
                                            <label for="fecha_desde">Fecha desde</label>
                                            <input class="form-control" type="date" name="fecha_desde" id="fecha_desde">
                                            <p class="help-block"></p>
                                        </div>
                                        <div class="form-group col-12 col-md-5">
                                            <label for="fecha_hasta">Fecha Hasta</label>
                                            <input class="form-control" type="date" name="fecha_hasta" id="fecha_hasta">
                                            <p class="help-block"></p>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <br>
                                            <button type="submit" class="btn btn-primary">Consultar</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="">
                                    <br>
                                    <table id="tb_op_entregadas_contab" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                        <thead style="background:#0d1b50;color:white">
                                            <tr>
                                                <th>Encab: Empresa</th>
                                                <th>Encab: Tipo Documento</th>
                                                <th>Encab: Prefijo</th>
                                                <th>Encab: Documento Número</th>
                                                <th>Encab: Fecha</th>
                                                <th>Encab: Tercero Interno</th>
                                                <th>Encab: Tercero Externo</th>
                                                <th>Encab: Fecha Inicial</th>
                                                <th>Encab: Fecha Final</th>
                                                <th>Encab: Nota</th>
                                                <th>Encab: Abierta/Cerrada</th>
                                                <th>Encab: Modo Distribución</th>
                                                <th>Encab:Lista Precios Modo Distribución</th>
                                                <th>Encab:Personalizado 1</th>
                                                <th>Encab:Personalizado 2</th>
                                                <th>Encab:Personalizado 3</th>
                                                <th>Encab:Personalizado 4</th>
                                                <th>Encab:Personalizado 5</th>
                                                <th>Encab:Personalizado 6</th>
                                                <th>Encab:Personalizado 7</th>
                                                <th>Encab:Personalizado 8</th>
                                                <th>Encab:Personalizado 9</th>
                                                <th>Encab:Personalizado 10</th>
                                                <th>Encab:Personalizado 11</th>
                                                <th>Encab:Personalizado 12</th>
                                                <th>Encab:Personalizado 13</th>
                                                <th>Encab:Personalizado 14</th>
                                                <th>Encab:Personalizado 15</th>
                                                <th>Detalle:Producto</th>
                                                <th>Detalle:Bodega</th>
                                                <th>Detalle:Unidad Medida</th>
                                                <th>Detalle:Cantidad</th>
                                                <th>Detalle:Cantidad Recibida</th>
                                                <th>Detalle:Nota</th>
                                                <th>Detalle:Porcentaje de Distribución</th>
                                                <th>Detalle:Talla</th>
                                                <th>Detalle:Color</th>
                                                <th>Detalle:Valor Unitario</th>
                                                <th>Detalle:Iva</th>
                                                <th>Detalle:Vencimiento</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="materia_prima_op" role="tabpanel" aria-labelledby="materia_prima_op-tab">
                        <br>
                        <h3 class="text-center fw-bolder" style="font-family: 'gothic';">Salida Materia Prima</h3>
                        <br>
                        <table id="tb_salida_mp_contab" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                            <thead style="background:#0d1b50;color:white">
                                <tr>
                                    <th>Encab: Empresa</th>
                                    <th>Encab: Tipo Documento</th>
                                    <th>Encab: Prefijo</th>
                                    <th>Encab: Documento Número</th>
                                    <th>Encab: Fecha</th>
                                    <th>Encab: Entregado por</th>
                                    <th>Encab: Destino</th>
                                    <th>Encab: Nota</th>
                                    <th>Encab: FormaPago</th>
                                    <th>Encab: Verificado</th>
                                    <th>Encab: Anulado</th>
                                    <th>Encab: Sucursal</th>
                                    <th>Encab: Clasificación</th>
                                    <th>Encab: Personalizado 1</th>
                                    <th>Encab: Personalizado 2</th>
                                    <th>Encab: Personalizado 3</th>
                                    <th>Encab: Personalizado 4</th>
                                    <th>Encab: Personalizado 5</th>
                                    <th>Encab: Personalizado 6</th>
                                    <th>Encab: Personalizado 7</th>
                                    <th>Encab: Personalizado 8</th>
                                    <th>Encab: Personalizado 9</th>
                                    <th>Encab: Personalizado 10</th>
                                    <th>Encab: Personalizado 11</th>
                                    <th>Encab: Personalizado 12</th>
                                    <th>Encab: Personalizado 13</th>
                                    <th>Encab: Personalizado 14</th>
                                    <th>Encab: Personalizado 15</th>
                                    <th>Detalle: Producto</th>
                                    <th>Detalle: Bodega</th>
                                    <th>Detalle: UnidadDeMedida</th>
                                    <th>Detalle: Cantidad</th>
                                    <th>Detalle: IVA</th>
                                    <th>Detalle: Valor Unitario</th>
                                    <th>Detalle: Descuento</th>
                                    <th>Detalle: Vencimiento</th>
                                    <th>Detalle: Nota</th>
                                    <th>Detalle: Centro costos</th>
                                    <th>Detalle: Proceso</th>
                                    <th>Detalle: Lote</th>
                                    <th>Detalle: Seriales</th>
                                    <th>Detalle: Talla</th>
                                    <th>Detalle: Color</th>
                                    <th>Detalle: Inventario a Producir</th>
                                    <th>Detalle: Código Centro Costo</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tab-pane fade show" id="items_op" role="tabpanel" aria-labelledby="items_op-tab">
                        <br>
                        <h3 class="text-center fw-bolder" style="font-family: 'gothic';">Entradas A Almacen</h3>
                        <br>
                        <table id="tb_entrada_producto" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                            <thead style="background:#0d1b50;color:white">
                                <tr>
                                    <th>Encab: Empresa</th>
                                    <th>Encab: Tipo Documento</th>
                                    <th>Encab: Prefijo</th>
                                    <th>Encab: Documento Número</th>
                                    <th>Encab: Fecha</th>
                                    <th>Encab: Prefijo Documento Externo</th>
                                    <th>Encab: Número_Documento_Externo</th>
                                    <th>Encab: Recibido de</th>
                                    <th>Encab: Destino</th>
                                    <th>Encab: Nota</th>
                                    <th>Encab: FormaPago</th>
                                    <th>Encab: Verificado</th>
                                    <th>Encab: Anulado</th>
                                    <th>Encab: Sucursal</th>
                                    <th>Encab: Clasificación</th>
                                    <th>Encab: Personalizado 1</th>
                                    <th>Encab: Personalizado 2</th>
                                    <th>Encab: Personalizado 3</th>
                                    <th>Encab: Personalizado 4</th>
                                    <th>Encab: Personalizado 5</th>
                                    <th>Encab: Personalizado 6</th>
                                    <th>Encab: Personalizado 7</th>
                                    <th>Encab: Personalizado 8</th>
                                    <th>Encab: Personalizado 9</th>
                                    <th>Encab: Personalizado 10</th>
                                    <th>Encab: Personalizado 11</th>
                                    <th>Encab: Personalizado 12</th>
                                    <th>Encab: Personalizado 13</th>
                                    <th>Encab: Personalizado 14</th>
                                    <th>Encab: Personalizado 15</th>
                                    <th>Detalle: Producto</th>
                                    <th>Detalle: Bodega</th>
                                    <th>Detalle: UnidadDeMedida</th>
                                    <th>Detalle: Cantidad</th>
                                    <th>Detalle: IVA</th>
                                    <th>Detalle: Valor Unitario</th>
                                    <th>Detalle: Descuento</th>
                                    <th>Detalle: Vencimiento</th>
                                    <th>Detalle: Nota</th>
                                    <th>Detalle: Centro costos</th>
                                    <th>Detalle: Proceso</th>
                                    <th>Detalle: Lote</th>
                                    <th>Detalle: Seriales</th>
                                    <th>Detalle: Talla</th>
                                    <th>Detalle: Color</th>
                                    <th>Detalle: Inventario a Producir</th>
                                    <th>Detalle: Código Centro Costo</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Contabilidad/js/vista_plantillas.js"></script>
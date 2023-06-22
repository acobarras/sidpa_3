<div class="container-fluid mt-3 mb-3">
   <div class="recuadro">
      <div class="container-fluid">
         <div id="contenido" class="px-2 py-2 col-md-12">
            <nav>
               <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a class="nav-link active" id="nav-pqr-tab" data-bs-toggle="tab" href="#nav-pqr" role="tab" aria-controls="nav-pqr" aria-selected="true">Reporte De PQR</a>
               </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
               <div class="tab-pane fade show active" id="nav-pqr" role="tabpanel" aria-labelledby="nav-pqr-tab">
                  <br>
                  <div class="mb-3 text-center row">
                     <h1 class="col-md-12 col-md-offset-4 ">Reportar una reclamación de un pedido</h1>
                  </div>
                  <div class="row" name="form_reclamacion" id="form_reclamacion">
                     <div class="col-5">
                        <div class="input-group mb-3">
                           <label class="input-group-text" for="num_pedido">Numero de Pedido:</label>
                           <input type="text" class="form-control num_pedio" id="num_pedido" name="num_pedido">
                        </div>
                     </div>
                     <div class="col-5">
                        <div class="input-group mb-3 col-5">
                           <label class="input-group-text" for="slect_items">Item:</label>
                           <select class="form-control select_2" id="slect_items" name="slect_items" style="width: 75%;">
                              <option value="0"></option>
                           </select>
                        </div>
                     </div>
                     <div class="col-2">
                        <input type="button" class="btn btn-success" value="Enviar" id="envio" disabled="">
                     </div>
                  </div>
                  <br>
                  <form id="form_grabar_reclamacion">
                     <div class="row mb-3">
                        <div class="form-group col-2">
                           <label class="fw-bolder" for="motivo_no">Motivo de la PQR :</label>
                           <span class="form-control select_acob">
                              Producto &nbsp;&nbsp;
                              <input type="radio" name="motivo" class="motivo_vs" id="motivo_no" value="1" checked>
                              <span style="padding-left: 30px"></span>
                              Servicio &nbsp;&nbsp;
                              <input type="radio" name="motivo" class="motivo_vs" id="motivo_si" value="2">
                           </span>
                        </div>
                        <div class="form-group col-4">
                           <label class="fw-bolder"> Nit :</label>
                           <span class="form-control" id="nit">N/A</span>
                        </div>
                        <div class="form-group col-6">
                           <label class="fw-bolder"> Cliente :</label>
                           <span class="form-control" id="nombre_empresa">N/A</span>
                        </div>
                     </div>
                     <div class="row mb-3">
                        <div class="form-group col-8">
                           <label class="fw-bolder"> Dirección pedido :</label>
                           <span class="form-control" id="direccion">N/A</span>
                        </div>
                        <div class="form-group col-4">
                           <label class="fw-bolder"> Correo electrónico :</label>
                           <span class="form-control" id="email">N/A</span>
                        </div>
                     </div>
                     <div class="row mb-3">
                        <div class="form-group col-4">
                           <label class="fw-bolder"> Persona de contacto :</label>
                           <span class="form-control" id="contacto">N/A</span>
                        </div>
                        <div class="form-group col-4">
                           <label class="fw-bolder"> Teléfono :</label>
                           <span class="form-control" id="telefono">N/A</span>
                        </div>
                        <div class="form-group col-4">
                           <label class="fw-bolder"> Celular :</label>
                           <span class="form-control" id="celular">N/A</span>
                        </div>
                     </div>
                     <div class="row mb-3">
                        <div class="form-group col-4">
                           <label class="fw-bolder" for="cam_direc_no">¿La dirección de la PQR es distinta a la dirección del pedido ?</label>
                           <span class="form-control select_acob">
                              Si &nbsp;&nbsp;
                              <input type="radio" class="cambio_direc" name="cam_direc" id="cam_direc_si" value="1">
                              <span style="padding-left: 30px"></span>
                              No &nbsp;&nbsp;
                              <input type="radio" class="cambio_direc" name="cam_direc" id="cam_direc_no" value="2" checked>
                           </span>
                        </div>
                        <div class="form-group col-8" id="cambio_direccion" style="display: none;">
                           <label class="fw-bolder" for="cambio_direc"> Seleccione la dirección :</label>
                           <select class="form-control select_2" name="cambio_direc" id="cambio_direc" style="width: 100%;"></select>
                        </div>
                     </div>
                     <div class="mb-3" id="respu_elegido" style="display: none;">
                        <div class="row mb-3">
                           <div class="form-group col-8">
                              <label class="fw-bolder"> Dirección pedido :</label>
                              <span class="form-control" id="direccion_res">N/A</span>
                           </div>
                           <div class="form-group col-4">
                              <label class="fw-bolder"> Correo electrónico :</label>
                              <span class="form-control" id="email_res">N/A</span>
                           </div>
                        </div>
                        <div class="row mb-3">
                           <div class="form-group col-4">
                              <label class="fw-bolder"> Persona de contacto :</label>
                              <span class="form-control" id="contacto_res">N/A</span>
                           </div>
                           <div class="form-group col-4">
                              <label class="fw-bolder"> Teléfono :</label>
                              <span class="form-control" id="telefono_res">N/A</span>
                           </div>
                           <div class="form-group col-4">
                              <label class="fw-bolder"> Celular :</label>
                              <span class="form-control" id="celular_res">N/A</span>
                           </div>
                        </div>
                     </div>
                     <div class="mb-3">
                        <table style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" id="tabla_item_pedido" cellspacing="0" width="100%">
                           <thead style="background:#0d1b50;color:white">
                              <tr>
                                 <th>Pedido Item</th>
                                 <th>Codigo</th>
                                 <th>Descripción</th>
                                 <th>O.P.</th>
                                 <th>Ruta Emb</th>
                                 <th>Core</th>
                                 <th>Roll. paq X</th>
                                 <th>Moneda</th>
                                 <th>V.unidad</th>
                              </tr>
                           </thead>
                           <tbody></tbody>
                        </table>
                     </div>
                     <div class="row mb-3">
                        <div class="form-group col-5">
                           <label class="fw-bolder" for="cam_produc_no">¿La información del producto con el cual se va a gestionar la reclamación es distinta ?</label>
                           <span class="form-control select_acob">
                              Si &nbsp;&nbsp;
                              <input type="radio" class="cambio_produc" name="cam_produc" id="cam_produc_si" value="1">
                              <span style="padding-left: 30px"></span>
                              No &nbsp;&nbsp;
                              <input type="radio" class="cambio_produc" name="cam_produc" id="cam_produc_no" value="2" checked>
                           </span>
                        </div>
                        <div class="form-group col-7" id="cambio_producto" style="display: none;">
                           <label class="fw-bolder" for="cambio_produc"> Seleccione un producto :</label>
                           <select class="form-control select_2" name="cambio_produc" id="cambio_produc" style="width: 100%;"></select>
                        </div>
                     </div>
                     <div class="mb-3" id="respu_elegido_produc" style="display: none;">
                        <table style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" id="tabla_cambio_item_pedido" cellspacing="0" width="100%">
                           <thead style="background:#0d1b50;color:white">
                              <tr>
                                 <th>Codigo</th>
                                 <th>Descripción</th>
                                 <th>Ruta Emb</th>
                                 <th>Core</th>
                                 <th>Roll. paq X</th>
                                 <th>V.unidad</th>
                              </tr>
                           </thead>
                           <tbody></tbody>
                        </table>
                     </div>
                     <div class="row mb-3">
                        <div class="form-group col-3">
                           <label class="fw-bolder" for="cambio_reproceso_si">¿Se debe realizar un cambio o reproceso de material?</label>
                           <span class="form-control select_acob">
                              Si &nbsp;&nbsp;
                              <input type="radio" class="recogida" name="cambio_reproceso" id="cambio_reproceso_si" value="1" checked>
                              <span style="padding-left: 30px"></span>
                              No &nbsp;&nbsp;
                              <input type="radio" class="recogida" name="cambio_reproceso" id="cambio_reproceso_no" value="2">
                           </span>
                        </div>
                        <div class="form-group col-3">
                           <label class="fw-bolder" for="recogida_produc_no">¿La mercancía se debe recoger donde el cliente?</label>
                           <span class="form-control select_acob">
                              Si &nbsp;&nbsp;
                              <input type="radio" class="recogida recoger_produc" name="recogida_produc" id="recogida_produc_si" value="1">
                              <span style="padding-left: 30px"></span>
                              No &nbsp;&nbsp;
                              <input type="radio" class="recogida recoger_produc" name="recogida_produc" id="recogida_produc_no" value="2" checked>
                           </span>
                        </div>
                        <div class="form-group col-3" id="cita_previa" style="display: none;">
                           <label class="fw-bolder" for="requiere_cita_no">¿Se requiere cita previa?</label>
                           <span class="form-control select_acob">
                              Si &nbsp;&nbsp;
                              <input type="radio" class="requiere_cita" name="requiere_cita" id="requiere_cita_si" value="1">
                              <span style="padding-left: 30px"></span>
                              No &nbsp;&nbsp;
                              <input type="radio" class="requiere_cita" name="requiere_cita" id="requiere_cita_no" value="2" checked>
                           </span>
                        </div>
                     </div>
                     <div class="row mb-3">
                        <div class="form-group col-3">
                           <label class="fw-bolder" for="nota_contable">Requiere Nota Contable :</label>
                           <span class="form-control select_acob">
                              Si &nbsp;&nbsp;
                              <input type="radio" class="nota_contable" name="nota_contable" id="nota_contable_si" value="1">
                              <span style="padding-left: 30px"></span>
                              No &nbsp;&nbsp;
                              <input type="radio" class="nota_contable" name="nota_contable" id="nota_contable_no" value="2" checked>
                           </span>
                        </div>
                        <div class="form-group col-6">
                           <label class="fw-bolder" for="cantidad_reclama">Cantidad Reclamación :</label>
                           <input type="text" class="form-control" name="cantidad_reclama" id="cantidad_reclama">
                        </div>
                     </div>
                     <div class="mb-3">
                        <div class="form-group">
                           <label class="fw-bolder" for="observacion">Motivo y descripción detallada de la reclamación:</label>
                           <textarea class="form-control" id="observacion" style="resize: none; height:250px;"></textarea>
                        </div>
                     </div>
                     <div class="mb-3 text-center">
                        <label style="display: none;" for="item_pedido">numero de pedido e item</label>
                        <input type="hidden" name="item_pedido" id="item_pedido">
                        <button class="btn btn-primary btn-lg" type="button" id="generar_reclamacion"><i class="fa fa-check"></i> Grabar PQR</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- <div class="recuadro">
      <div class="container-fluid">
         <center>
            <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSd0RYSGU8ttz3fWg3LaMPyITMU81fdtnBBISp22moQ2Ja6Yjw/viewform?embedded=true" width="1000" height="1300" frameborder="0" marginheight="0" marginwidth="0">Cargando…</iframe>
         </center>
      </div>
   </div> -->

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Comercial/js/vista_pqr.js"></script>
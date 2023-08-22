<div class="container-fluid mt-3 mb-3">
   <div class="recuadro">
      <div id="contenido" class="px-2 py-2 col-lg-12">
         <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
               <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Precios Materiales</a>
            </li>
            <li class="nav-item" role="presentation">
               <a class="nav-link" id="nuevo_precio-tab" data-bs-toggle="tab" href="#nuevo_precio" role="tab" aria-controls="nuevo_precio" aria-selected="false">Nuevo Precio Material</a>
            </li>
         </ul>
         <div class="tab-content" id="myTabContent">
            <!-- Primer link -->
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
               <div class="my-4 text-center">
                  <h2>Tabla Precio Material</h2>
               </div>
               <table id="tabla_precio_materia_prima" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                  <thead class="text-center" style="background: #002b5f;color: white">
                     <tr>
                        <th>#</th>
                        <th>Tipo Material</th>
                        <th>Adhesivo</th>
                        <th>Moneda</th>
                        <th>Valor Material</th>
                        <th>Opción</th>
                     </tr>
                  </thead>
                  <tbody></tbody>
               </table>
            </div>
            <!-- segundo link -->
            <div class="tab-pane fade" id="nuevo_precio" role="tabpanel" aria-labelledby="nuevo_precio-tab">
               <form class="container" id="form_crear_precio_material" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                  <input type="hidden" name="id_precio" id="id_precio" value="0" />
                  <br>
                  <div class="mb-3 text-center">
                     <h1 class="modal-title" id="tituloForm">Nuevo Precio Material</h1>
                  </div>
                  <div class="mb-3">
                     <label for="id_tipo_material" class="form-label">Tipo Material :</label>
                     <select class="form-control select_2" style="width: 100%;" id="id_tipo_material" name="id_tipo_material">
                        <option value="0"></option>
                        <?php foreach ($tipo_material as $value) { ?>
                           <option value="<?= $value->id_tipo_material ?>"><?= $value->nombre_material ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="mb-3">
                     <label for="id_adhesivo" class="form-label">Adhesivo :</label>
                     <select class="form-control select_2" style="width: 100%;" id="id_adhesivo" name="id_adhesivo">
                        <option value="0"></option>
                        <?php foreach ($adhesivo as $value) { ?>
                           <option value="<?= $value->id_adh ?>"><?= $value->nombre_adh ?></option>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="mb-3">
                     <label for="moneda" class="form-label">Moneda Material: </label>
                     <select class="form-control select_2" style="width: 100%;" id="moneda" name="moneda">
                        <option value="0">Elija una opción</option>
                        <option value="1">Pesos</option>
                        <option value="2">Dolar</option>
                     </select>
                  </div>
                  <div class="mb-3">
                     <label for="valor_material" class="form-label">Valor Material : </label>
                     <input autocomplete="off" type="text" class="form-control" name="valor_material" id="valor_material" />
                  </div>
                  <div class="mb-3 ">
                     <div class="text-center" id="oculto1">
                        <button class="btn btn-primary" type="submit" id="boton-enviar">
                           <i class="fa fa-plus-circle"></i> Nuevo Precio
                        </button>
                     </div>
                  </div>
                  <br>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/Compras/js/vista_precio_materia_prima.js"></script>
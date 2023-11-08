<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Cartelera</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="my-3 container">
                        <div class="mb-3 text-center">
                            <h3>Agregar Imagen</h3>
                        </div>
                        <div class="mb-3">
                            <form class="row" id="nuevo_cartel" enctype="multipart/form-data">
                                <div class="col-9">
                                    <label for="nueva_imagen">Nueva Imagen :</label>
                                    <input class="form-control" type="file" id="nueva_imagen">
                                </div>
                                <div class="col-3">
                                    <button type="submit" class="btn btn-success" name="imagen" id="crea_imagen">Agregar Imagen</button>
                                </div>
                            </form>
                        </div>
                        <div class="mb-3 text-center">
                            <h3>Imagenes Que se Muestran</h3>
                        </div>
                        <div class="mb-3 row">
                            <?php
                           $gestor = opendir(CARPETA_IMG . PROYECTO .'/cartelera');
                           while (($archivo = readdir($gestor)) !== false) {
                               if ($archivo != "." && $archivo != ".." && $archivo != 'Thumbs.db') { ?>
                                   <div class="mb-3 col-4 text-center">
                                       <div class="card" style="width: 18rem;">
                                           <h5 class="card-title"><?= $archivo ?></h5>
                                           <img src="<?= CARPETA_IMG . PROYECTO  ?>/cartelera/<?= $archivo ?>" class="card-img-top">
                                           <div class="card-body">
                                               <div class="text-center">
                                                   <button class="btn btn-danger elimina" value="<?= $archivo ?>">Eliminar Imagen</button>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                           <?php }
                           } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_crea_cartelera.js"></script>
<div class="container-fluid mt-3 mb-3">
    <div id="page-wrapper" style="border-radius:4px ;box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.25);">
        <div id="contenido" class="col-lg-12" style="padding: 10px 5px 10px 5px;">
            <div class="row">
                <div class="col-md-7 cols-sm-12">
                    <section class="mb-3 splide" aria-label="Splide Basic HTML Example">
                        <div class="splide__track">
                            <ul class="splide__list">
                                <?php
                                $gestor = opendir(CARPETA_IMG . PROYECTO . '/cartelera');
                                $contador = 0;
                                $posicion = [
                                    1 => ['base' => $copasst, 'titulo' => 'INTEGRANTES COPASST', 'img' => CARPETA_IMG . PROYECTO . '/comites/copastt.jpg'],
                                    3 => ['base' => $comite, 'titulo' => 'INTEGRANTES COMITE', 'img' => CARPETA_IMG . PROYECTO . '/comites/comite.jpg'],
                                    5 => ['base' => $brigada, 'titulo' => 'INTEGRANTES BRIGADA', 'img' => CARPETA_IMG . PROYECTO . '/comites/brigada.jpg'],
                                ];
                                while (($archivo = readdir($gestor)) !== false) {
                                    if (isset($posicion[$contador])) { ?>
                                        <li class="splide__slide">
                                            <div style="background-image: url('<?= $posicion[$contador]['img'] ?>'); background-size: 100%; width: 98%; height: 100%; opacity: 0.2 ">
                                            </div>
                                            <div style="position: absolute; top: 3px">
                                                <div class="header_aco text-center">
                                                    <h6><?= $posicion[$contador]['titulo'] ?></h6>
                                                </div>
                                                <div class="row">
                                                    <?php if (empty($posicion[$contador]['base'])) { ?>
                                                        <h4 class="text-center" style="font-family: 'Francois One', sans-serif; font-size: 18px;">► !Los integrantes de este comité no se han elegido, muy pronto los conocerás¡</h4>
                                                        <?php } else {
                                                        foreach ($posicion[$contador]['base'] as $value) { ?>
                                                            <div class="col-12">
                                                                <h4 style="font-family: 'Francois One', sans-serif; font-size: 18px;">► <?= $value->nombres ?> <?= $value->apellidos ?></h4>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </li>
                                    <?php }
                                    // Se muestran todos los archivos y carpetas excepto "." y ".."
                                    if ($archivo != "." && $archivo != ".." && $archivo != 'Thumbs.db') { ?>
                                        <li class="splide__slide">
                                            <div class="splide__slide__container">
                                                <img src="<?= CARPETA_IMG  . PROYECTO ?>/cartelera/<?= $archivo ?>" style="width: 98%; height: 100%">
                                            </div>
                                        </li>
                                <?php }
                                    $contador = $contador + 1;
                                }
                                ?>
                            </ul>
                        </div>
                    </section>
                </div>
                <!-- contenido de cumpleaños del mes -->
                <?php if (empty($cumpleanios)) { ?>
                    <!-- Lista de Cumpleaños -->
                    <div class="col-md-5 cols-sm-12">
                        <div class="panel panel-primary" style="margin-top: 20px;box-shadow: 4px 4px 4px 4px rgba(0,0,0,0.45); ">
                            <div class="panel-heading header_aco">
                                <h4 class="text-center" style="font-family: 'Acme', sans-serif;">CUMPLEAÑOS DEL MES</h4>
                            </div>
                            <ul>
                                <?php foreach ($lista_cumpleanios as $lista) { ?>
                                    <li>
                                        <h4 style="font-family: 'Francois One', sans-serif; font-size: 18px;"> <?= $lista->nombres ?> <?= $lista->apellidos ?> - <b><?= $lista->dia ?></b> </h4>
                                    </li>
                                <?php } ?>
                            </ul>
                            <br>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="col-md-5 cols-sm-12" style="background: url('<?= CARPETA_IMG ?>/img_sidpa/birthday.jpg') no-repeat center; background-size: 97.5%;">
                        <div class="header_aco text-center">
                            <h6>EN ESTE DIA ESPECIAL TE DESEAMOS UN FELIZ CUMPLEAÑOS</h6>
                        </div>
                        <div class="my-3 row">
                            <?php foreach ($cumpleanios as $cumple) { ?>
                                <div class="col-3">
                                    <div class="text-center">
                                        <img src="<?= CARPETA_IMG . PROYECTO ?>/fotos_persona/<?= $cumple->num_documento ?>.jpg" class="card-img-top">
                                    </div>
                                    <div class="header_aco">
                                        <div class="text-center">
                                            <p class="fs-6"><?= $cumple->nombres ?><br> <?= $cumple->apellidos ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!-- Comienza la seguda parte -->
            <div class="mb-3 row">
                <div class="col-5">
                </div>
                <div class="col-7">
                </div>
            </div>
            <br><br>
        </div>
    </div>
    <?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
    <script src="./public/vistas/inicio/js/carrusel_inicio.js"></script>
<?php
include PUBLICO . '/vistas/plantilla/header.php'; ?>

<div id="page-top" class="masthead">
    <div id="nueva_vista"></div>
    <!-- Masthead -->
    <header class="container">
        <div class="row">
            <div class="col-1 col-sm-4"></div>
            <div class="col-4 col-sm-4">
                <div class="card card_login" style="width:20rem">
                    <div class="card-body">
                        <div>
                            <h2 class="text-center text-grey fw-bold">Bienvenido</h2>
                            <hr>
                        </div>
                        <div>
                            <img src="<?= CARPETA_IMG . PROYECTO ?>/login/sdp_logo.png" style="max-width:100%;max-height:250px" class="mx-auto d-block">
                        </div>

                        <div class="ContentForm" style="padding: 0 20px;">
                            <br>
                            <form method="POST" name="FormEntrar">
                                <div class="input-group mb-3">
                                    <span class="input-group-text entorno" id="basic-addon1"><i class="fa fa-user"></i></span>
                                    <input type="text" class="form-control" name="usu_usuario" id="usu_usuario" placeholder="Nombre Usuario" aria-describedby="basic-addon1">
                                </div>
                                <br>
                                <div class="input-group mb-3">
                                    <span class="input-group-text entorno" id="basic-addon2"><i class="fa fa-lock"></i></span>
                                    <input type="password" name="usu_pasword" id="usu_pasword" class="form-control" placeholder="Clave" aria-describedby="basic-addon2" required="">
                                </div>

                                <br>
                                <div id="mensaje"></div>
                                <div class="text-center">
                                    <button class="btn btn-lg btn-primary azul_aco btn-block btn-signin col-8" id="IngresoLog" type="submit">Entrar</button>
                                </div>
                            </form>
                        </div>
                        <br><br>
                        <img src="<?= CARPETA_IMG ?>/img_sidpa/sidpa.gif" style="width: 230px;" class=" rounded mx-auto d-block">

                    </div>
                </div>
            </div>
            <div class="col-4 col-sm-4"></div>
        </div>
    </header>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>

<script src="./public/vistas/inicio/js/inicio.js"></script>
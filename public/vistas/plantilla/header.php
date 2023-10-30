<!DOCTYPE html>
<html lang="es">

<head>
    <meta name="theme-color" content="#24243e" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= NOMBRE_EMPRESA ?> | Sidpa</title>

    <link rel="shortcut icon" href="<?= CARPETA_IMG ?>/img_sidpa/sidpa_ico.ico">
    <!-- Font Awesome Icons -->
    <link href="<?= CARPETA_LIBRERIAS ?>/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Theme CSS - Includes Bootstrap -->
    <link href="<?= CARPETA_LIBRERIAS ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- Theme CSS - Includes Alertify -->
    <link href="<?= CARPETA_LIBRERIAS ?>/alertify/css/alertify.min.css" rel="stylesheet">
    <link href="<?= CARPETA_LIBRERIAS ?>/alertify/css/themes/bootstrap.min.css" rel="stylesheet">
    <!-- Theme CSS - Includes DataTables -->
    <link href="<?= CARPETA_LIBRERIAS ?>/DataTables/css/datatables.min.css" rel="stylesheet">
    <link href="<?= CARPETA_LIBRERIAS ?>/FixedHeader-3.4.0/css/fixedHeader.dataTables.min.css" rel="stylesheet" type="text/css" />

    <!-- Theme CSS - Includes Select2 -->
    <link href="<?= CARPETA_LIBRERIAS ?>/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="<?= CARPETA_LIBRERIAS ?>/metisMenu/metisMenu.min.css" rel="stylesheet" type="text/css" />
    <!-- datepicker -->
    <link href="<?= CARPETA_LIBRERIAS ?>/jquery-iu/css/jquery-ui.css" rel="stylesheet" type="text/css" />
    <link href="<?= CARPETA_LIBRERIAS ?>/splide-3.6.9/dist/css/splide.min.css" rel="stylesheet" type="text/css" />
    <link href="./public/css/estilos.css" rel="stylesheet">
    <!--FONT -->
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Courgette&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Acme&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Francois+One&display=swap" rel="stylesheet">

</head>

<body>
    <div>
        <input type="hidden" id="fecha_hoy" value="<?= date('Y-m-d'); ?>"><!-- Se usa este input para tener la fecha del dia actual -->
        <input type="hidden" id="hora_hoy" value="<?= date("H:i:s"); ?>"><!-- Se usa este input para tener la hora del dia actual -->
        <!-- Se usa este input para tener la hora del dia actual -->
        <input type="hidden" id="host_port" value="<?= HOST . ':' . PORT ?>">
        <?php

        use MiApp\negocio\util\Validacion;

        if (isset($_SESSION['usuario'])) { ?>
            <input type="hidden" id="sesion" value="<?= $_SESSION['usuario']->getId_usuario() ?>"><!-- Se usa este input para tener la hora del dia actual -->
            <!-- <div id="vista_chat_sidpa"></div> -->
            <?php if ($_SESSION['usuario']->getTipo_clave() == 1) { ?>
                <!-- Modal -->
                <div class="modal fade" id="cambioClave" tabindex="-1" aria-labelledby="cambioClaveLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form class="modal-content">
                            <div class="modal-header header_aco">
                                <img class="img-fluid mx-2" alt="Responsive image" src="<?= CARPETA_IMG ?>/img_sidpa/triangulo_aco.gif" width="75" style="border-right:1px solid #e7e7e7;border-radius:2px">
                                <h5 class="modal-title text-center" id="cambioClaveLabel">Por su seguridad actualice la contraseña </h5>
                                <i class="bi bi-x" data-bs-dismiss="modal" style="font-size: 26px;"></i>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Nueva Contraseña:</label>
                                    <input type="password" class="form-control" id="pasword" name="pasword">
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">Confirmar Contraseña:</label>
                                    <input type="password" class="form-control" id="pasword-conf">

                                </div>
                                <br>
                                <h6>Una vez actualizada la contraseña deberá ingresar nuevamente.</h6>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary azul_aco" id="actualizar_clave">Cambiar Clave</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
    </div>

    <div id="wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light nav-aco">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= RUTA_PRINCIPAL ?>">
                    <img class="img-fluid" alt="Responsive image" src="<?= CARPETA_IMG ?>/img_sidpa/sidpa.gif" width="255" style="border-right:1px solid #e7e7e7;border-radius:2px">
                </a>
                <ul class="navbar-nav mb-2 mb-lg-0 d-flex justify-content-sm-end">
                    <li class="nav-item">
                        <button id="sbuton" type="button" class="btn border border-secondary rounded albondiga">
                            <i class="fas fa-bars"></i>
                        </button>
                    </li>
                </ul>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-none d-sm-block">
                    <li class="nav-item ms-3">

                    </li>
                </ul>
                <!-- Trm -->
                <div class="d-flex me-3">
                    <div class="nav navbar-top-links navbar-right">
                        <div class="col-12">
                            <span style="font-size: 0.9rem;">Día <?= date('d/m/Y', strtotime($trm[0]->fecha_crea)) ?> </span><br>
                            <span style="font-size: 0.9rem;">TRM $ <?= number_format($trm[0]->valor_trm, 2, ',', '.') ?></span>
                        </div>
                    </div>
                </div>
                <!-- /.cerrar sesion-->
                <div class="d-flex">
                    <div class="nav navbar-top-links navbar-right">
                        <div class="col-12">
                            <img class="img-fluid" alt="Responsive image" src="<?= CARPETA_IMG . PROYECTO ?>/login/logo_blanco.svg">
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <a style="color:white" class="col-12" id="cerrar_sesion" href="<?= RUTA_PRINCIPAL ?>/cerrar_sesion">
                                    <strong class="col-10">Cerrar Sesión </strong>
                                    <i class="fa fa-power-off"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- </div> -->
            </div>
        </nav>

        <div id="menu_lateral" class="sidebar">
            <div class="row" style="padding: 15px">
                <div class="col-lg-12">
                    <a data-fancybox="gallery" href="../public/img/usuarios_foto/<?= $usuario->getRuta_foto() ?>">
                        <img src="<?= CARPETA_IMG . PROYECTO ?>/foto_usuarios/<?= $usuario->getRuta_foto() ?>" width="55" height="55" class="rounded-circle" alt="Responsive image">
                    </a>
                    <strong style="font-size: 12px;color:black"> <?= $usuario->getNombre() . ' ' . $usuario->getApellido(); ?> </strong>
                </div>
            </div>
            <div class="accordion" id="accordionFlushExample">
                <?php foreach ($modulo_hojas as $valor) { ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading<?= $valor->id_hoja ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $valor->id_hoja ?>" aria-expanded="false" aria-controls="flush-collapse<?= $valor->id_hoja ?>">
                                <?php
                                if ($valor->nuevo == 1) {
                                    $nuevo = ' <span id="nuevo_area" style="color: green; font-size: 13px;" > ( Nuevo <i class="fas fa-bahai" style="color: #BFB407;"></i>)</span>';
                                } else {
                                    $nuevo = '';
                                }
                                ?>
                                <i class="<?= $valor->icono ?> me-1" style="font-size: 20px; color:<?= $valor->color_icono ?>"></i> <?= ($valor->titulo) . $nuevo ?>

                                <!-- haciendo lo de nuevos modulos -->
                            </button>
                        </h2>
                        <div id="flush-collapse<?= $valor->id_hoja ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $valor->id_hoja ?>" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <ul>
                                    <?php foreach ($todas_hojas as $valores_configuracion) { ?>
                                        <?php if ($valor->referencia_nombre == $valores_configuracion->nombre_hoja) {
                                            if (strpos($valor->url, '#') !== false) {
                                                $dirige = $valores_configuracion->url;
                                            } else {
                                                $dirige = $valores_configuracion->url;
                                            }
                                            $dirige = RUTA_PRINCIPAL . $dirige;
                                        ?>
                                            <li>
                                                <a href="<?= $dirige ?>">
                                                    <?php
                                                    if ($valores_configuracion->nuevo == 1) {
                                                        $nuevo = ' <span id="nuevo_area" style="color: green; font-size: 13px;" > ( Nuevo <i class="fas fa-bahai" style="color: #BFB407;"></i>)</span>';
                                                    } else {
                                                        $nuevo = '';
                                                    }
                                                    ?>
                                                    <i class="<?= $valores_configuracion->icono ?> me-1" style="font-size: 20px; color:<?= $valores_configuracion->color_icono ?>"></i><?= $valores_configuracion->titulo . $nuevo ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <input type="hidden" id="data_prioridad" value='<?= json_encode($consulta_prioridades); ?>'>
        <?php
            if ($modal) { ?>
            <div class="modal fade" id="prioridades" tabindex="-1" aria-labelledby="prioridadesLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <form class="modal-content">
                        <div class="modal-header header_aco">
                            <img class="img-fluid mx-2" alt="Responsive image" src="<?= CARPETA_IMG ?>/img_sidpa/triangulo_aco.gif" width="75" style="border-right:1px solid #e7e7e7;border-radius:2px">
                            <h5 class="modal-title text-center" id="prioridadesLabel">Prioridades </h5>
                            <i class="bi bi-x" data-bs-dismiss="modal" style="font-size: 26px;"></i>
                        </div>
                        <div class="modal-body">
                            <table id="tb_prioridades" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                                <thead style="background:#0d1b50;color:white">
                                    <tr>
                                        <th>Id Prioridad</th>
                                        <th>Prioridad</th>
                                        <th>Pedido</th>
                                        <th>Item</th>
                                        <th>Fecha Creación</th>
                                        <th>Solicitado por:</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>
        <?php
            if ($_SESSION['usuario']->getId_roll() == 11) {
                if (!empty($chequeo)) {
                    $fecha_chequeo = date("d-m-Y", strtotime($chequeo[0]->fecha_chequeo . "+" . DIAS_CHEQUEO_VEHICULO . "days"));
                    $dt = new DateTime($fecha_chequeo);
                    $dt_hoy = new DateTime(date('d-m-Y'));
                    // Si los dias de diferencia son negativos o iguales a 0 aparece el modal 
                    $dias_diferencia = $dt_hoy->diff($dt);
                    if (intval($dias_diferencia->format('%R%a')) <= 0) { ?>
                    <div class="modal fade" id="chequeo" tabindex="-1" data-bs-backdrop="static" aria-labelledby="chequeoLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header header_aco">
                                    <img class="img-fluid mx-2" alt="Responsive image" src="<?= CARPETA_IMG ?>/img_sidpa/triangulo_aco.gif" width="75" style="border-right:1px solid #e7e7e7;border-radius:2px">
                                    <h5 class="modal-title text-center" id="chequeoLabel">ALERTA SIDPA </h5>
                                    <i class="bi bi-x" style="font-size: 26px;"></i>
                                </div>
                                <div class="modal-body">
                                    <h5 class="text-danger text-center">Esta alerta es para realizar el chequeo periódico del vehículo. Por favor solicitar el chequeo para continuar. Recuerde que esta alerta no se cerrara hasta que se haya realizado el procedimiento.</h5>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" id="cerrar_sesion_modal">
                                        <a style="color:white" class="col-12" id="cerrar_sesion" href="<?= RUTA_PRINCIPAL ?>/cerrar_sesion">
                                            <strong class="col-10">Cerrar</strong>
                                        </a>
                                    </button>
                                </div>
                            </div>
                        </div>
                <?php }
                } ?>
                    </div>
                <?php  } ?>
            <?php } ?>
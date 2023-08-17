<!-- Modal -->
<div class="modal fade" id="alert_aco" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header header_aco">
                <div class="img_modal">
                    <p> </p>
                </div>
                <h3 class="modal-title" id="title_modal"></h3>
                <i class="bi bi-x cerrar btn-recarga" data-bs-dismiss="modal" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body" id="content_modal"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-naranja btn-recarga">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="card-footer text-muted bg-light py-4">
    <div class="container">
        <div class="small text-center text-muted">Copyright &copy; 2021 - EDWIN RIOS- Sidpa</div>
    </div>
</footer>
</body>

<!-- Plugin JavaScript -->

<script src="<?= CARPETA_LIBRERIAS ?>/jquery/jquery-3.5.1.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script src="<?= CARPETA_LIBRERIAS ?>/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Plugin js_color -->
<script src="<?= CARPETA_LIBRERIAS ?>/js_color/jscolor.js"></script>
<!-- Plugin alertify -->
<script src="<?= CARPETA_LIBRERIAS ?>/alertify/alertify.min.js"></script>
<!-- <script src="<?= CARPETA_LIBRERIAS ?>/notifyMe/notifyMe.min.js"></script> -->
<!-- Plugin select2 -->
<script src="<?= CARPETA_LIBRERIAS ?>/select2/dist/js/select2.min.js"></script>
<!-- datepicker -->
<script src="<?= CARPETA_LIBRERIAS ?>/jquery-iu/js/jquery-ui.js"></script>
<!-- Plugin dataTables -->
<script src="<?= CARPETA_LIBRERIAS ?>/DataTables/js/datatables.min.js"></script>
<script src="<?= CARPETA_LIBRERIAS ?>/moment/moment.min.js"></script>
<script src="<?= CARPETA_LIBRERIAS ?>/printArea/jquery.PrintArea.js"></script>
<!-- chart js graficos en la pagina -->
<script src="<?= CARPETA_LIBRERIAS ?>/chart3.5.1/dist/chart.js"></script>
<script src="<?= CARPETA_LIBRERIAS ?>/chart3.5.1/dist/chartjs-plugin-datalabels.js"></script>
<!-- signature_pad js canvas para dibujar la firmas -->
<script src="<?= CARPETA_LIBRERIAS ?>/signature_pad/docs/js/signature_pad.umd.js"></script>
<!-- Mis Js -->
<script src="<?= CARPETA_LIBRERIAS ?>/ckeditor_4.18.0_full/ckeditor/ckeditor.js"></script>
<script src="<?= CARPETA_LIBRERIAS ?>/splide-3.6.9/dist/js/splide-extension-auto-scroll.min.js"></script>
<script src="<?= CARPETA_LIBRERIAS ?>/splide-3.6.9/dist/js/splide.min.js"></script>
<script src="<?= PUBLICO ?>/js/ContantesRutaSidpa.js"></script>
<script src="<?= CARPETA_IMG . PROYECTO ?>/Constantes/ConstantesRuta.js"></script>
<script src="<?= PUBLICO ?>/js/constantes.js"></script>
<script src="./public/vistas/inicio/js/menu.js"></script>
<?php if (isset($_SESSION['usuario'])) { ?>
    <!-- <script src="<?= PUBLICO ?>/js/fancywebsocket.js"></script>
    <script src="./public/vistas/inicio/js/chat_sidpa.js"></script> -->
<?php } ?>



</html>
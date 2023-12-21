<div class="modal fade" id="prioridades" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="prioridadesLabel" aria-hidden="true">
    <!-- <div class="modal" id="prioridades" tabindex="-1" aria-labelledby="prioridadesLabel" aria-hidden="true"> -->
    <div class="modal-dialog modal-xl">
        <form class="modal-content">
            <div class="modal-header header_aco">
                <img class="img-fluid mx-2" alt="Responsive image" src="<?= CARPETA_IMG ?>/img_sidpa/triangulo_aco.gif" width="75" style="border-right:1px solid #e7e7e7;border-radius:2px">
                <h5 class="modal-title text-center" id="prioridadesLabel">Prioridades </h5>
                <i class="bi bi-x" style="font-size: 26px;"></i>
            </div>
            <div class="modal-body">
                <div class="text-center" id="mensaje_prioridad">
                    <h3 class="text-danger">Tiene prioridades por responder, por favor, espere a que se carguen <span class="spinner-border" role="status"></span></h3>
                </div>
                <table id="tb_prioridades" style="background: white" class="table table-hover table-condensed table-bordered table-responsive-md  table-responsive-lg" cellspacing="0" width="100%">
                    <thead style="background:#0d1b50;color:white">
                        <tr>
                            <th>Id Prioridad</th>
                            <th>Prioridad</th>
                            <th>Cliente</th>
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
                <button type="button" class="btn btn-secondary" id="cerrar_modal_prioridad" disabled data-bs-dismiss="modal">Cerrar</button>
            </div>
        </form>
    </div>
</div>
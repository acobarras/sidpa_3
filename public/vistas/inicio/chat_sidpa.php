<a class="position-fixed bottom-0 end-0" id="abrir_chat" data-bs-toggle="collapse" data-bs-target="#chatSidpa" role="button" aria-expanded="false" aria-controls="chatSidpa" style="width: 55px; margin-right: 50px; margin-bottom: 50px; z-index: 1; cursor: pointer;"><img src="<?= CARPETA_IMG ?>/img_sidpa/logo_chat.svg">
    <!-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">+0<span class="visually-hidden">unread messages</span></span> -->
</a>
<div class="card collapse col-12 col-md-2 bg-light position-fixed bottom-0 end-0" id="chatSidpa">
    <div class="modal-header header_aco">
        <h5 class="modal-title" id="nombre_chat">Chat Sidpa</h5>
        <button type="button" class="btn-close text-info" id="cerrar_chat" data-bs-toggle="collapse" data-bs-target="#chatSidpa" aria-label="Close"></button>
    </div>
    <div class="modal-body overflow-auto">
        <div id="usuarios">
            <div>
                <input type="text" class="form-control" id="busca_chat" data-list='<?= json_encode($usu_chat) ?>'>
                <hr>
            </div>
            <div id="filtro">
                <?php foreach ($usu_chat as $value) { ?>
                    <div class="conversa position-relative" id-usuario="<?= $value->id_usuario ?>" id="usuario<?= $value->id_usuario ?>" data-nombre="<?= $value->nombre . " " . $value->apellido ?>">
                        <h6><?= $value->nombre . " " . $value->apellido ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $value->msg_pend ?></span>
                        </h6>
                    </div>
                    <hr>
                <?php } ?>
            </div>
        </div>
        <div id="containerMessages" class="d-none conversacion">
            <!-- <div class="row justify-content-start">
                <div class="col-10">
                    <p class="mb-0">Hola Edwin como nos fue en la reunion de indicadores de la semana pasada
                    </p>
                    <sub><small>13/07/2023 10:32 a.m.</small></sub>
                </div>
            </div>
            <hr>
            <div class="row justify-content-end">
                <div class="col-10">
                    <p class="mb-0">No muy bien que digamos es algo que tenemos que implementar
                    </p>
                    <sub><small>13/07/2023 10:32 a.m.</small></sub>
                </div>
            </div>
            <hr> -->
        </div>
    </div>

    <div class="modal-footer conversacion d-none">
        <form class="col-12" id="formChat">
            <div class="input-group">
                <label for="message" class="d-none">Mensaje</label>
                <input type="text" class="form-control" id="message" placeholder="Escribe un mensaje aquí" aria-label="Escribe un mensaje aquí" name="message" aria-describedby="basic-addon2">
                <input type="hidden" name="name" id="name" value="<?= $_SESSION['usuario']->getNombre() ?>">
                <input type="hidden" name="contacto" id="contacto" value="">
                <button class="input-group-text header_aco" id="basic-addon2"><i class="fas fa-paper-plane"></i></button>
            </div>
        </form>
    </div>
</div>
<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="px-2 py-2 col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tabla Clientes</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="first-tab" data-bs-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="false">Nuevo Cliente</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!-- Primer link -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <br>
                    <div class="mx-3 mt-2">
                        <div class="text-center">
                            <h2>Tabla Clientes</h2>
                        </div>
                        <br>
                        <table id="tabla_clientes" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                            <thead style="background: #002b5f;color: white">
                                <th>#</th>
                                <th>Nit Empresa</th>
                                <th>Razón Social</th>
                                <th>Asesores</th>
                                <th>Estado</th>
                                <th>Opción</th>
                                <th>Opción</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <input id="datos_usuarios" type="hidden" value='<?= json_encode($usuarios) ?>'>
                <!-- segundo link -->
                <div class="tab-pane fade" id="first" role="tabpanel" aria-labelledby="first-tab">
                    <br>
                    <form class="container" id="form_crear_cliente" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
                        <br>
                        <div class="mb-3 row">
                            <h1 class="col-md-12 col-md-offset-4 ">Nuevo Cliente - Proveedor</h1>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-5">
                                <label for="tipo_documento" class="form-label">Tipo de documento : </label>
                                <select class="form-control select_2" style="width: 100%" name="tipo_documento" id="tipo_documento">
                                    <option value="0"></option>
                                    <?php foreach ($documento as $doc) { ?>
                                        <option value="<?= $doc->id_tipo_documento ?>"><?= $doc->nombre_documento ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-5">
                                <label for="nit" class="form-label">Número de documento : </label>
                                <input class="form-control" name="nit" id="nit" />
                            </div>
                            <div class="mb-3 col-2">
                                <label for="dig_verificacion" class="form-label">Digito de Verificación : </label>
                                <input class="form-control" name="dig_verificacion" id="dig_verificacion" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="nombre_empresa" class="form-label">Razón Social : </label>
                                <input type="text" class="form-control" name="nombre_empresa" id="nombre_empresa" />
                            </div>
                            <div class="mb-3 col-6">
                                <div class="mb-3 row">
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Cliente : </label>
                                        <input type="checkbox" name="tipo_cli_prov" id="tipo_cli_prov1" class="form-control tipo_cli_prov1" value="1" />
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Proovedor : </label>
                                        <input type="checkbox" name="tipo_cli_prov" id="tipo_cli_prov2" class="form-control tipo_cli_prov2" value="2" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="collapse formaPago">
                            <div class="mb-3 row">
                                <div class="mb-3 col-6">
                                    <label for="forma_pago" class="form-label">Forma de Pago : </label>
                                    <select class="form-control select_2" style="width: 100%;" name="forma_pago" id="forma_pago">
                                        <?php foreach (FORMA_PAGO as $key => $forma_pago) { ?>
                                            <option value="<?= $key ?>"><?= $forma_pago ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="dias_dados" class="form-label">Días Otorgados : </label>
                                    <select class="form-control select_2" style="width: 100%;" name="dias_dados" id="dias_dados">
                                        <?php foreach (DIAS_DADOS as $key => $dias) { ?>
                                            <option value="<?= $key ?>"><?= $dias ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="mb-3 col-6">
                                    <label for="pertenece" class="form-label">Pertenece A : </label>
                                    <select class="form-control select_2" style="width: 100%;" name="pertenece" id="pertenece">
                                        <option value="0"></option>
                                        <?php foreach ($pertenece as $pertenece) { ?>
                                            <option value="<?= $pertenece->id_empresa ?>"><?= $pertenece->nombre_compania ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="lista_precio" class="form-label">Lista Pertenece : </label>
                                    <select class="form-control select_2" style="width: 100%;" name="lista_precio" id="lista_precio">
                                        <?php foreach (LISTA_PERTENECE as $key => $lista_pertenece) { ?>
                                            <option value="<?= $key ?>"><?= $lista_pertenece ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="cupo_cliente" class="form-label">Cupo Otorgado : </label>
                                    <input type="text" class="form-control" name="cupo_cliente" id="cupo_cliente" />
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="dias_max_mora_modifi" class="form-label">Dias Maximo de Mora : </label>
                                    <input type="text" class="form-control" name="dias_max_mora" id="dias_max_mora" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6 collapse tipoProv">
                                <label for="tipo_prove" class="form-label">Tipo Proovedor : </label>
                                <select class="form-control select_2" style="width: 100%;" name="tipo_prove" id="tipo_prove">
                                    <option value="0"></option>
                                    <?php foreach (TIPO_PROVEEDOR as $key => $tipo_provee) { ?>
                                        <option value="<?= $key ?>"><?= $tipo_provee ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-6 collapse formaPago">
                                <label for="id_usuarios_asesor" class="form-label">Asesores : </label>
                                <select class="form-control select_2" multiple style="width: 100%;" name="id_usuarios_asesor" id="id_usuarios_asesor">
                                    <option value="0"></option>
                                    <?php foreach ($usuarios as $usuario) { ?>
                                        <option value="<?= $usuario->id_persona ?>"><?= $usuario->nombre . " " . $usuario->apellido ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 ">
                            <div class="text-center">
                                <input type="hidden" id="logo_etiqueta" value="1" name="logo_etiqueta">
                                <button class="btn btn-primary" type="submit" id="crear_cliente">
                                    <i class="fa fa-plus-circle"></i> Crear Cliente
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

<!-- Modal para editar Tipo Producto -->

<div class="modal fade" id="ModalClientes" aria-labelledby="ModalClientesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="form_modificar_cliente">
            <div class="modal-header header_aco">
                <div class="img_modal mx-2 ">
                    <p> </p>
                </div>
                <h5 class="modal-title" id="ModalClientesLabel">Modificar Cliente</h5>
                <button type="button" class="btn-close  btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="recuadro">
                    <br><br>
                    <div class="container-fluid">
                        <div class="mb-3 row">
                            <div class="mb-3 col-5">
                                <label for="tipo_documento_modifi" class="form-label">Tipo de documento : </label>
                                <select class="form-control select_2" style="width: 100%" name="tipo_documento" id="tipo_documento_modifi">
                                    <?php foreach ($documento as $doc) { ?>
                                        <option value="<?= $doc->id_tipo_documento ?>"><?= $doc->nombre_documento ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-5">
                                <label for="nit_modifi" class="form-label">Número de documento : </label>
                                <input class="form-control" name="nit" id="nit_modifi" />
                            </div>
                            <div class="mb-3 col-2">
                                <label for="dig_verificacion_modifi" class="form-label">Digito de Verificación : </label>
                                <input class="form-control" name="dig_verificacion" id="dig_verificacion_modifi" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="nombre_empresa_modifi" class="form-label">Razón Social : </label>
                                <input type="text" class="form-control" name="nombre_empresa" id="nombre_empresa_modifi" />
                            </div>
                            <div class="mb-3 col-6">
                                <label for="forma_pago_modifi" class="form-label">Forma de Pago : </label>
                                <select class="form-control select_2" style="width: 100%;" name="forma_pago" id="forma_pago_modifi">
                                    <?php foreach (FORMA_PAGO as $key => $forma_pago) { ?>
                                        <option value="<?= $key ?>"><?= $forma_pago ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="dias_dados_modifi" class="form-label">Días Otorgados : </label>
                                <select class="form-control select_2" style="width: 100%;" name="dias_dados" id="dias_dados_modifi">
                                    <?php foreach (DIAS_DADOS as $key => $dias) { ?>
                                        <option value="<?= $key ?>"><?= $dias ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="pertenece_modifi" class="form-label">Pertenece A : </label>
                                <select class="form-control select_2" style="width: 100%;" name="pertenece" id="pertenece_modifi">
                                    <option value="0"></option>
                                    <?php foreach ($pertenece_modifi as $value) { ?>
                                        <option value="<?= $value->id_empresa ?>"><?= $value->nombre_compania ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="lista_precio_modifi" class="form-label">Lista Pertenece : </label>
                                <select class="form-control select_2" style="width: 100%;" name="lista_precio" id="lista_precio_modifi">
                                    <?php foreach (LISTA_PERTENECE as $key => $lista_pertenece) { ?>
                                        <option value="<?= $key ?>"><?= $lista_pertenece ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="tipo_prove_modifi" class="form-label">Tipo Proovedor : </label>
                                <select class="form-control select_2" style="width: 100%;" name="tipo_prove" id="tipo_prove_modifi">
                                    <?php foreach (TIPO_PROVEEDOR as $key => $tipo_provee) { ?>
                                        <option value="<?= $key ?>"><?= $tipo_provee ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 col-6">
                                <label for="id_usuarios_asesor_modifi" class="form-label">Asesores : </label>
                                <select class="form-control select_2" multiple style="width: 100%;" name="id_usuarios_asesor" id="id_usuarios_asesor_modifi">
                                    <option value="0"></option>
                                    <?php foreach ($usuarios as $usuario) { ?>
                                        <option value="<?= $usuario->id_persona ?>"><?= $usuario->nombre . " " . $usuario->apellido ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="id_usuarios_asesor_modifi" class="form-label">Cupo Otorgado : </label>
                                <input type="text" class="form-control" name="cupo_cliente" id="cupo_cliente_modifi" pattern="^[0-9,$]*$" />
                            </div>
                            <div class="mb-3 col-6">
                                <label for="dias_max_mora_modifi" class="form-label">Dias Maximo de Mora : </label>
                                <input type="text" class="form-control" name="dias_max_mora" id="dias_max_mora_modifi" />
                            </div>
                            <div class="mb-3 col-6">
                                <label for="logo_etiqueta_modifi" class="form-label">Logo Etiqueta : </label>
                                <select class="form-control" name="logo_etiqueta" id="logo_etiqueta_modifi">
                                    <option value="0">Elija una opcion</option>
                                    <option value="1">Si</option>
                                    <option value="2">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
                            <button type="submit" class="btn btn-primary" id="modificar_cliente" data-id="">Modificar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    input[type=checkbox] {
        cursor: pointer;
    }

    input[type=checkbox]:checked:before {
        content: "\2713";
        background: #fffed5;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, .2);
        font-size: 30px;
        text-align: center;
        line-height: 8px;
        display: inline-block;
        width: 25px;
        height: 25px;
        color: #001c90;
        border: 3px solid #cdcdcd;
        border-radius: 4px;
        margin: -3px -3px;
        text-indent: 1px;
    }

    input[type=checkbox]:before {
        content: "\202A";
        background: #ffffff;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, .2);
        font-size: 30px;
        text-align: center;
        line-height: 8px;
        display: inline-block;
        width: 25px;
        height: 25px;
        color: #001c90;
        border: 3px solid #cdcdcd;
        border-radius: 4px;
        margin: -3px -3px;
        text-indent: 1px;
    }
</style>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/configuracion/js/vista_creacion_clientes.js"></script>
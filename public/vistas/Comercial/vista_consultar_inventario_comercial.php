<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <div class="container-fluid">
            <div id="contenido" class="px-2 py-2 col-md-12">
                <br>
                <h2 style="text-align: center"> Consulta Inventarios</h2>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="nav-inv-etiq-tab" data-bs-toggle="tab" href="#nav-inv-etiq" role="tab" aria-controls="nav-inv-etiq" aria-selected="true">Etiquetas</a>
                        <a class="nav-link" id="nav-inv-tecno-tab" data-bs-toggle="tab" href="#nav-inv-tecno" role="tab" aria-controls="nav-inv-tecno" aria-selected="true">Tecnología</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-inv-etiq" role="tabpanel" aria-labelledby="nav-inv-etiq-tab">
                        <div class="recuadro">
                            <div class="container-fluid">
                                <!--consultar etiquetas-->
                                <div id="etiquetas">
                                    <div class="col-lg-10">
                                        <br>
                                        <h4>Código Etiquetas</h4>
                                    </div>
                                    <div class="col-lg-8">
                                        <form id="form_consulta_inventario_etiqueta">
                                            <div class="form-group input-group">
                                                <input type="number" value="2" id="tipo_art_etiqueta" hidden="true" />
                                                <input type="text" class="form-control" name="codigo" id="codigo_etiqueta" placeholder="Ingrese el codigo del producto" required="true">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btn_consultar_inventario_etiqueta"> <i class="fa fa-search"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table id="dt_consulta_inventario_etiqueta" class=" table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                                <thead class="bg-layout">
                                                    <tr class="">
                                                        <td><b>Código <i class="fa fa-qrcode"></i></b></td>
                                                        <td><b>Producto</td>
                                                        <td><b>Descripción</td>
                                                        <td><b>Cantidad</td>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="nav-inv-tecno" role="tabpanel" aria-labelledby="nav-inv-tecno-tab">
                        <div class="recuadro">
                            <div class="container-fluid">
                                <!--consultar tecnologia-->
                                <div id="tecnologia">
                                    <div class="col-lg-10">
                                        <br>
                                        <h4>Código de Parte :</h4>
                                    </div>
                                    <div class="col-lg-8">
                                        <form id="form_consulta_inventario_tec">
                                            <div class="form-group input-group">
                                                <input type="number" value="3" id="tipo_art_tec" hidden="true" />
                                                <input type="text" class="form-control" name="codigo" id="codigo_tecnologia" placeholder="Ingrese el número de parte del producto" required="true">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btn_consultar_inventario_tec"> <i class="fa fa-search"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table id="dt_consulta_inventario_tec" class=" table table-bordered table-hover table-responsive-lg table-responsive-md" cellspacing="0" width="100%">
                                                <thead class="bg-layout">
                                                    <tr class="">
                                                        <td><b>Código <i class="fa fa-qrcode"></i></b></td>
                                                        <td><b>Producto</td>
                                                        <td><b>Descripción</td>
                                                        <td><b>Cantidad</td>
                                                        <td><b>Precio Alto Volumen</td>
                                                        <td><b>Precio Bajo Volumen</td>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>

<script src="<?= PUBLICO ?>/vistas/Comercial/js/inventario_consultar.js"></script>

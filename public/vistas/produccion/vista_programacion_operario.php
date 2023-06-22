<div class="container-fluid mt-3 mb-3">
    <div class="recuadro">
        <br>
        <div class="container" style="box-shadow: 7px 9px 19px -9px rgba(0,0,0,0.75);">
            <br>
            <div class="text-center">
                <h1>PROGRAMACION OPERARIO </h1>
            </div>
            <br>
            <form id="form_datos">
                <div class="mb-3 row">
                    <div class="mb-3 col-5">
                        <label for="operario" class="form-label">Operario : </label>
                        <select class="form-control" name="operario" id="operario"></select>
                    </div>
                    <div class="mb-3 col-5">
                        <label for="maquina" class="form-label">Maquina : </label>
                        <select class="form-control" name="maquina" id="maquina"></select>
                    </div>
                    <div class="pt-3 col-2 text-center">
                        <button class="btn btn-primary" type="button" id="agregar_operario">
                            <i class="fa fa-plus-circle"></i> Agregar
                        </button>
                    </div>
                </div>
            </form>
            <table id="tabla_operario" class="table table-bordered table-responsive table-hover" cellspacing="0" width="100%">
                <thead style="background: #002b5f;color: white">
                    <tr>
                        <th>Operario</th>
                        <th>Maquina</th>
                        <th>Borrar</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div class="mt-3 mb-3 row">
                <div class="col-4"></div>
                <div class="col-4">
                    <label for="guia_dias" class="form-label">Numero de días : </label>
                    <input type="number" class="form-control" placeholder="Ingrese numero de días" name="guia_dias" id="guia_dias">
                </div>
                <div class="col-4"></div>
            </div>
            <br>
            <div class="row">
                <div class="col-6">
                    <table class="table table-bordered " id="table-fechas">
                        <thead>
                            <tr>
                                <th style="text-align: center">FECHAS </th>
                            </tr>
                        </thead>
                        <tbody id="fechas-td">
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <label for="turnos" class="form-label">Turno : </label>
                    <select name="turnos" class="form-control select_2" id="turnos" style="width: 100%">
                        <option value=""></option>
                        <option value="8">6 A.M - 2 P.M</option>
                        <option value="8">2 P.M - 10 P.M</option>
                        <option value="8">10 P.M - 6 A.M</option>
                        <option value="10">6 A.M - 4 P.M</option>
                        <option value="10">8 A.M - 6 P.M</option>
                        <option value="11">6 A.M - 6 P.M</option>
                        <option value="11">6 P.M - 6 A.M</option>
                        <option value="1" data-id="comp">Compensatorio</option>
                    </select>
                    <br>
                    <br>
                    <center>
                        <button class="btn btn-primary" id="programar">Programar</button>
                    </center>
                </div>
            </div>
            <br>
        </div>
        <br>
    </div>
</div>

<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script src="<?= PUBLICO ?>/vistas/produccion/js/vista_programacion_operario.js"></script>
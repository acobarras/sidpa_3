$(document).ready(function () {
    select_2();
    consulta_fecha_corte();
    enviar_fecha();
    cambio_fecha();
});

var consulta_fecha_corte = function () {
    var tb_pedidos_permitidos = $('#tabla_fecha').DataTable({
        "ajax": `${PATH_NAME}/contabilidad/consulta_fecha_corte`,
        "columns": [
            { "data": "id" },
            { "data": "mes" },
            { "data": "ano" },
            {
                "data": "opciones", render: (data, type, row) => {
                    return `<input type="date" class="form-control cam_fecha_corte" id="fecha${row.id}" value="${row.corte}">`;
                }
            }

        ],
    });
}

var cambio_fecha = function () {
    $('#tabla_fecha tbody').on("change", "input.cam_fecha_corte", function () {
        var form = $('#tabla_fecha').DataTable().row($(this).parents("tr")).data();
        var valor = $(`#fecha${form['id']}`).val();
        var dateObj = new Date(valor);
        var nuevo_mes = dateObj.toLocaleString("es-ES", { month: "long" });
        if (form['mes'].toLowerCase() != nuevo_mes.toLocaleLowerCase()) {
            alertify.error('Lo sentimos esta fecha no corresponde al mes a modificar');
            $(`#fecha${form['id']}`).val(form['corte']);
            return;
        }
        var id = form['id'];
        $.ajax({
            url: `${PATH_NAME}/contabilidad/enviar_fecha_corte`,
            type: "POST",
            data: { form, id, valor },
            success: function (res) {
                if (res == true) {
                    alertify.success('Modificacion Exitosa');
                    $('#tabla_fecha').DataTable().ajax.reload();
                    limpiar_formulario('form_fecha_corte', 'input');
                } else {
                    alertify.error('Algo a ocurrido');
                }
            }
        });
    });
}

var enviar_fecha = function () {
    $("#form_fecha_corte").submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var id = $('#id_corte').val();
        var valida = validar_formulario(form);
        if (valida) {
            form = $(this).serialize();
            var obj_inicial = $(`#enviar_fecha`).html();
            btn_procesando(`enviar_fecha`);
            $.ajax({
                url: `${PATH_NAME}/contabilidad/enviar_fecha_corte`,
                type: "POST",
                data: { form, id },
                success: function (res) {
                    btn_procesando(`enviar_fecha`, obj_inicial, 1);
                    if (res.status == 1) {
                        alertify.success(res.msg);
                        $('#tabla_fecha').DataTable().ajax.reload();
                        limpiar_formulario('form_fecha_corte', 'input');
                    } else {
                        alertify.error(res.msg)
                    }
                }
            });
        }
    });
}
$(document).ready(function () {
    select_2();
    cargar_tabla_precios_materia_prima();
    agregar_precio();
    precio_editar();
    precio_form();
});

const boton_crea = `<button class="btn btn-primary" type="submit" id="boton-enviar">
<i class="fa fa-plus-circle"></i> Nuevo Precio
</button>`;
const boton_edita = `<button class="btn btn-info" type="submit" id="boton-enviar">
<i class="fa fa-plus-circle"></i> Edita Precio
</button>`;

var cargar_tabla_precios_materia_prima = function () {
    var table = $("#tabla_precio_materia_prima").DataTable({
        "ajax": `${PATH_NAME}/compras/consulta_precio_materia_prima`,
        "columns": [
            { "data": "id_precio" },
            { "data": "nombre_material" },
            { "data": "nombre_adh" },
            { "data": "valor_material", render: $.fn.dataTable.render.number('.', ',', 2, '$') },
            { "defaultContent": '<button type="button" class="btn btn-primary editar_registro"><i class="fa fa-edit"></i></button>',"className": "text-center" }

        ],
    });
}

var precio_editar = function () {
    $("#tabla_precio_materia_prima tbody").on("click", "button.editar_registro", function () {
        var data = $("#tabla_precio_materia_prima").DataTable().row($(this).parents("tr")).data();
        console.log(data);
        rellenarFormulario(data);
        $('#nuevo_precio-tab').tab('show');
        $('#nuevo_precio-tab').empty().html('Editar Precio Material');
        $('#tituloForm').empty().html('Editar Precio Material');
        $('#oculto1').empty().html(boton_edita);
    });
}

var precio_form = function () {
    $('#home-tab').click(function () {
        $('#home-tab').tab('show');
        $('#form_crear_precio_material')[0].reset();
        limpiar_formulario('form_crear_precio_material', 'select');
        $('#nuevo_precio-tab').empty().html('Nuevo Precio Material');
        $('#tituloForm').empty().html('Nuevo Precio Material');
        $('#oculto1').empty().html(boton_crea);
        $('#id_precio').val('0');
    })
}

var agregar_precio = function () {
    $("#form_crear_precio_material").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#boton-enviar').html();
        var form = $(this).serializeArray();
        var id_precio = $('#id_precio').val();
        var ecepcion = ['id_precio'];
        if (id_precio != 0) {
            ecepcion = ['id_precio'];
        }
        valida = validar_formulario(form,ecepcion);
        if (valida) {
            btn_procesando('boton-enviar');
            $.ajax({
                "url": `${PATH_NAME}/compras/crea_precio_material`,
                "type": 'POST',
                "data": form,
                "success": function (respu) {
                    if (respu.status) {
                        $("#tabla_precio_materia_prima").DataTable().ajax.reload(function () {
                            alertify.success(respu.msg);
                            btn_procesando('boton-enviar', obj_inicial, 1);
                            limpiar_formulario('form_crear_precio_material', 'select');
                            limpiar_formulario('form_crear_precio_material', 'input');
                            $('#home-tab').click();
                        });
                    } else {
                        alertify.error(respu.msg);
                        btn_procesando('boton-enviar', obj_inicial, 1);
                    }
                }
            });
        }
    });
}
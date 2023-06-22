$(document).ready(function () {
    datos_tabla();
    consulta_inventario_ubicacion();
    edita_item();
    acepta_canasta();
});

var datos_tabla = function () {
    var table = $('#dt_inv_etiqueta').DataTable({
        ajax: `${PATH_NAME}/inventario_final/inventario_tabla_final`,
        dom: 'Bflrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
            tittleAttr: ' Exportar a exel',
            className: 'btn btn-success',
        }],
        columns: [
            { "data": "ubicacion" },
            { "data": "codigo_producto" },
            { "data": "descripcion" },
            { "data": "entrada" },
            { "data": "nombre_conteo" },
            { "data": "entrada_verificado" },
            { "data": "nombre_verificado" },
            {
                "data": "diferencia",
                render: function (data, type, row) {
                    var diferencia = '';
                    if (row.entrada_verificado != 0 && row.entrada != 0) {
                        if (row.entrada_verificado > row.entrada) {
                            diferencia = '<span style="background-color:red;  color:white;">Conteo menor  que verificado</span>';
                        }
                        if (row.entrada_verificado < row.entrada) {
                            diferencia = '<span style="background-color:red;  color:white;">Conteo mayor  que verificado</span>';
                        }
                        if (row.entrada_verificado == row.entrada) {
                            diferencia = '<span style="background-color:green;  color:white;">Conteo y verificado son iguales</span>';
                        }

                    } else {
                        if (row.entrada_verificado == 0) {
                            diferencia = '<span style="background-color:orange;  color:white;">Sin verificación</span>';
                        }
                        if (row.entrada == 0) {
                            diferencia = '<span style="background-color:orange;  color:white;">Sin Conteo</span>';
                        }
                    }
                    return diferencia;
                }
            },
        ],
    });
}

var consulta_inventario_ubicacion = function () {
    $("#btn_consultar_inventario_ubicacion").on('click', function () {
        var ubicacion = $("#ubicacion_canasta").val();
        $.ajax({
            url: `${PATH_NAME}/inventario_final/inventario_tabla_ubicacion`,
            type: "POST",
            data: { ubicacion },
            success: function (respu) {
                if (respu.data != '') {
                    if (respu.data[0].estado != 4) {
                        $(".acepta_canasta").css('display', 'block');
                        $(".acepta_canasta").attr('data', JSON.stringify(respu.data));

                    } else {
                        alertify.warning("Esta Ubicación ya fue aceptada por contabilidad");
                    }
                    var table1 = $('#dt_modifi_inv').DataTable({
                        "data": respu.data,
                        columns: [
                            { "data": "ubicacion" },
                            { "data": "codigo_producto" },
                            { "data": "descripcion" },
                            { "data": "ancho" },
                            { "data": "metros" },
                            { "data": "entrada" },
                            { "data": "nombre_conteo" },
                            { "data": "ancho_verificado" },
                            { "data": "metros_verificado" },
                            { "data": "entrada_verificado" },
                            { "data": "nombre_verificado" },
                            {
                                "orderable": false,
                                render: function (data, type, row) {
                                    return `<center>
                            <button class="btn btn-success btn-sm edita_item" type="button" title="title="Editar Ubicación">
                                <i class="fa fa-edit"></i>
                            </button>

                            
                            <button class="btn btn-danger btn-sm elimina_item" type="button" title="elimina Ubicación">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                                        </center>`;
                                }
                            }
                        ],

                    });
                    carga_modal_item('#dt_modifi_inv tbody', table1);
                    elimina_item('#dt_modifi_inv tbody', table1);
                } else {
                    $(".acepta_canasta").css('display', 'none');
                    $(".acepta_canasta").attr('data', '');

                }

            }
        });

    });
}
var carga_modal_item = function (tbody) {
    $(tbody).on("click", "button.edita_item", function () {
        var data = $('#dt_modifi_inv').DataTable().row($(this).parents("tr")).data();
        $("#id").val(data.id);
        $("#ubicacion").val(data.ubicacion);
        $("#ubicacion").attr('disabled', 'disabled');
        $("#codigo_producto").val(data.codigo_producto);
        $("#codigo_producto").attr('disabled', 'disabled');
        $("#descripcion").val(data.descripcion);
        $("#descripcion").attr('disabled', 'disabled');
        $("#ancho").val(data.ancho);
        $("#metros").val(data.metros);
        $("#entrada").val(data.entrada);
        $("#nombre_conteo").val(data.nombre_conteo);
        $("#nombre_conteo").attr('disabled', 'disabled');
        $("#ancho_verificado").val(data.ancho_verificado);
        $("#metros_verificado").val(data.metros_verificado);
        $("#entrada_verificado").val(data.entrada_verificado);
        $("#nombre_verificado").attr('disabled', 'disabled');
        $("#nombre_verificado").val(data.nombre_verificado);
        if (data.tipo == 3) {
            calcula_metros();
        }
        $("#modificarUbicacionInv").modal("show");

    });
}
var edita_item = function () {
    $(".btn_editar_item").on('click', function () {
        let form_validation = $("#form_edita_item_inv").serializeArray();
        let entrada = $("#entrada").val();
        let entrada_verificado = $("#entrada_verificado").val();
        for (var i = 0; i < form_validation.length; i++) {
            if (form_validation[i].value == '') {
                $(`#${form_validation[i].name}`).focus();
                alertify.error('Error campos vacíos !!');
                return;
            }
            if (form_validation[i].name == 'entrada') {
                if (!REG_EXP_NUMEROS.test(form_validation[i].value)) {
                    alertify.error('Error cantidad invalida!!');
                    $("#entrada").val('');
                    return;
                }
            }
            if (form_validation[i].name == 'ancho') {
                if (!REG_EXP_NUMEROS.test(form_validation[i].value)) {
                    alertify.error('Error Ancho  invalido!!');
                    $("#ancho").val('');
                    return;
                }
            }
            if (form_validation[i].name == 'metros') {
                if (!REG_EXP_NUMEROS.test(form_validation[i].value)) {
                    alertify.error('Error metros invalidos!!');
                    $("#metros").val('');
                    return;
                }
            }
            if (form_validation[i].name == 'entrada_verificado') {
                if (!REG_EXP_NUMEROS.test(form_validation[i].value)) {
                    alertify.error('Error cantidad verificado  invalida!!');
                    $("#entrada_verificado").val('');
                    return;
                }
            }
            if (form_validation[i].name == 'ancho_verificado') {
                if (!REG_EXP_NUMEROS.test(form_validation[i].value)) {
                    alertify.error('Error Ancho verificado invalido!!');
                    $("#ancho_verificado").val('');
                    return;
                }
            }
            if (form_validation[i].name == 'metros_verificado') {
                if (!REG_EXP_NUMEROS.test(form_validation[i].value)) {
                    alertify.error('Error metros verificado invalidos!!');
                    $("#metros_verificado").val('');
                    return;
                }
            }
        }
        if (entrada == entrada_verificado) {
            let form = $("#form_edita_item_inv").serialize();
            var obj_inicial = $(`#btn_editar_item`).html();
            btn_procesando(`btn_editar_item`);
            $.ajax({
                url: `${PATH_NAME}/inventario_final/inventario_edita_item_inv`,
                type: "POST",
                data: form,
                success: function (res) {
                    btn_procesando(`btn_editar_item`, obj_inicial, 1);
                    $("#modificarUbicacionInv").modal('hide');
                    $('#btn_consultar_inventario_ubicacion').click();
                    $('#dt_inv_etiqueta').DataTable().ajax.reload();
                }
            });
        } else {
            alertify.error("¡¡No es posible modificar porque las entradas no coinciden!!");
        }
    });
}
var calcula_metros = function () {
    $("#metros , #ancho").on('keyup', function () {
        let ancho = $("#ancho").val();
        let metros = $("#metros").val();
        let entrada = '';
        if (ancho != 0 && metros != 0) {
            entrada = (parseFloat(ancho) * parseFloat(metros)) / 1000;
        }
        $('#entrada').val(entrada);
    });
    $("#metros_verificado , #ancho_verificado").on('keyup', function () {
        let ancho_verificado = $("#ancho_verificado").val();
        let metros_verificado = $("#metros_verificado").val();
        let entrada_verificado = '';
        if (ancho_verificado != 0 && metros_verificado != 0) {
            entrada_verificado = (parseFloat(ancho_verificado) * parseFloat(metros_verificado)) / 1000;
        }
        $('#entrada_verificado').val(entrada_verificado);
    });
}
var elimina_item = function (tbody, table) {
    $(tbody).on('click', 'button.elimina_item', function (e) {
        e.preventDefault();
        var data = $('#dt_modifi_inv').DataTable().row($(this).parents("tr")).data();
        let id = data.id;
        alertify.confirm("¿ESTA SEGURO DE ELIMINAR ESTA ESTE ITEM?",
            function () {
                $.ajax({
                    url: `${PATH_NAME}/inventario_final/inventario_elimina_item_inv`,
                    type: "POST",
                    data: { id },
                    success: function (res) {
                        $('#btn_consultar_inventario_ubicacion').click();
                        $('#dt_inv_etiqueta').DataTable().ajax.reload();
                    }
                });
            },
            function () {
                alertify.error('Cancelado');
            });
    });
}

var acepta_canasta = function () {
    $(".acepta_canasta").on('click', function () {
        var obj_inicial = $(`#acepta_canasta`).html();
        btn_procesando(`acepta_canasta`);
        var data = JSON.parse($('.acepta_canasta').attr('data'));
        let entrada = parseFloat(data[0].entrada);
        let entrada_verificado = parseFloat(data[0].entrada_verificado);
        if (entrada == entrada_verificado) {
            $.ajax({
                url: `${PATH_NAME}/inventario_final/inventario_cambio_estado_contab_inv`,
                type: "POST",
                data: { data },
                success: function (res) {
                    btn_procesando(`acepta_canasta`, obj_inicial, 1);
                    alertify.success("¡¡LA CANASTA FUE ACEPTADA CORECTAMENTE!!");
                    $('#dt_inv_etiqueta').DataTable().ajax.reload();
                }
            });
        } else {
            alertify.error("¡¡No es posible modificar porque las entradas no coinciden!!");
        }
    });
}
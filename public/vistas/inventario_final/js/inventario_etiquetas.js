$(document).ready(function () {
    consulta_productos_inv();
    conteo_inventario.valida_operario();
});

var PRODUCTOS = [];
var consulta_productos_inv = function () {
    var tipo_producto = 2;
    $('#cargando').css('display', 'block');
    $.ajax({
        url: `${PATH_NAME}/inventario_final/consultar_codigos`,
        type: "POST",
        data: { tipo_producto },
        success: function (res) {
            $('#cargando').css('display', 'none');
            $('#myTabContent').removeClass('d-none');
            PRODUCTOS = res;
        }
    });
}
var CanastaInventario = [];
var estado = 0;

var conteo_inventario = {
    init: function () {
        $('#id_usuario').blur(conteo_inventario.valida_operario);
        $('#codigo_producto').blur(conteo_inventario.valida_codigoProduct);
        $('#codigo_producto').change(conteo_inventario.cambio_cod);
        $('.envio_conteo').on('click', conteo_inventario.validacion_form_conteo);
        conteo_inventario.cargar_tabla();
        $("#conteo_etiquetas").on('click', '.borrar', conteo_inventario.borrar_item);
        $('.registro_conteo').on('click', conteo_inventario.registro_form_conteo);
        $('#ubicacion').select2();
    },
    cambio_cod: function () {
        let codigo = $("#codigo_producto").val();
        var cant = codigo.split(';');
        var cantidad = cant['1'];
        let num_coma = codigo.indexOf(";");
        if (num_coma != -1) {
            $('#codigo_producto').val(codigo.substr(0, num_coma));
            $('#entrada').val(cantidad);
            conteo_inventario.valida_codigoProduct();
        }
    },
    valida_operario: function () {
        let newUsu = $('#id_usuario').val().replace(/ /g, "");
        $('#id_usuario').val(newUsu);
        let documento = $("#id_usuario").val();
        $.ajax({
            url: `${PATH_NAME}/produccion/validar_operario`,
            type: "POST",
            data: { documento },
            success: function (res) {
                if (res == '') {
                    $("#span_operario_C").empty().html(`<span class="text-center" style="color:red; font-size: 13px;padding: 0 0 0 0;">NO EXISTE ESTE USUARIO!!</span>`);
                    $("#id_persona").val('');
                } else {
                    $("#id_persona").val(res[0].id_persona);
                    $("#span_operario_C").empty().html(`<span class="text-center" style="color:blue; font-size: 13px;padding: 0 0 0 0;">USUARIO: ${res[0].nombres} ${res[0].apellidos}</span>`);
                }
            }
        });
    },
    valida_codigoProduct: function () {
        let newCod = ($('#codigo_producto').val().replace(/ /g, "")).toUpperCase();
        let codigo = $("#codigo_producto").val();
        $('#codigo_producto').val(newCod);
        let num_coma = codigo.indexOf(";");
        if (num_coma != -1) {
            let entrada = codigo.substr(num_coma + 1);
            $('#entrada').val(entrada);
        }
        var existencia = PRODUCTOS.find(element => element.codigo_producto === newCod) ?? false;
        if (existencia != false) {
            if (existencia.id_tipo_articulo == 1) {
                $("#id_producto").val(existencia.id_productos);
                $("#span_codigo_C").empty().css('color', 'blue').html(`CODIGO: ${existencia.descripcion_productos}`);
                $('#entrada').focus();

            } else {
                $("#span_codigo_C").empty().css('color', 'red').html(`ESTE CODIGO DE PRODUCTO NO PERTENECE A UNA ETIQUETA!!`);
                $("#id_producto").val('');
            }
        } else {
            $("#span_codigo_C").empty().css('color', 'red').html(`NO EXISTE ESTE CODIGO DE PRODUCTO!!`);
            $("#id_producto").val('');
        }
    },
    validacion_form_conteo: function () {
        var form_validation = $("#form_conteo").serializeArray();
        var check_conteo = document.getElementById('conteo');
        var check_verificacion = document.getElementById('verificacion');
        if (check_conteo.checked == true || check_verificacion.checked == true) { } else {
            alertify.error('Error elija tipo de inventario!!');
            return;
        }
        if (check_conteo.checked == true && check_verificacion.checked == true) {
            $("#span_check").empty().html('Error elija un solo tipo de inventario!!');
            return;
        } else {
            $("#span_check").empty();
        }
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
        }
        if (check_conteo.checked == true) {
            estado = 1
        }
        if (check_verificacion.checked == true) {
            estado = 2
        }
        if (check_verificacion.checked == true) {
            conteo_inventario.permiso_verificacion();
        }
        if (check_conteo.checked == true) {
            conteo_inventario.permiso_conteo();
        }
    },
    agrega_local_storage: function () {
        var obj_inicial = $(`#envio_conteo`).html();
        btn_procesando(`envio_conteo`);
        var valida_storage = JSON.parse(localStorage.getItem('canasta_etq'));
        if (valida_storage != null) {
            $('#ubicacion').attr('disabled', 'disabled')
            $('#id_usuario').attr('disabled', 'disabled')
            CanastaInventario = valida_storage;
        }
        var datos = {
            num_usuario: $('#id_usuario').val(),
            id_usuario: $('#id_persona').val(),
            codigo_producto: $('#codigo_producto').val(),
            ubicacion: $('#ubicacion').val(),
            entrada: $('#entrada').val(),
            documento: 'INV 2020',
            id_proveedor: 21,
            id_productos: $("#id_producto").val(),
            estado: estado,
            tipo: 1,
        }
        if (CanastaInventario == '') {
            CanastaInventario.push(datos);
        } else {
            var respu = false;
            valida_storage.forEach(element => {
                if (element.codigo_producto == datos.codigo_producto) {
                    var nuevo = parseInt(element.entrada) + parseInt(datos.entrada);
                    element['entrada'] = nuevo;
                    respu = true;
                }
            });
            if (!respu) {
                CanastaInventario.push(datos);
            }
        }
        localStorage.setItem('canasta_etq', JSON.stringify(CanastaInventario));
        btn_procesando(`envio_conteo`, obj_inicial, 1);
        conteo_inventario.cargar_tabla();
        $("#span_codigo_C").empty();
        $("#codigo_producto").val('');
        $("#entrada").val('');
    },
    cargar_tabla: function () {
        var cadena = '';
        let storage = JSON.parse(localStorage.getItem('canasta_etq'));
        if (storage != null) {
            $('#ubicacion').attr('disabled', 'disabled')
            $('#id_usuario').attr('disabled', 'disabled')
            $('#conteo').attr('disabled', 'disabled')
            $('#verificacion').attr('disabled', 'disabled')
            storage.forEach(element => {
                $("#ubicacion").val(element.ubicacion);
                $('#id_usuario').val(element.num_usuario);
                // $('#id_usuario').on('click', conteo_inventario.valida_operario);
                $('#id_usuario').click();
                if (element.estado == 1) {
                    $("#conteo").prop("checked", true);
                }
                if (element.estado == 2) {
                    $("#verificacion").prop("checked", true);
                }
                cadena += /*html*/ `
                    <tr>
                     <td class="text-center">${element.codigo_producto}</td>
                     <td class="text-center">${element.entrada}</td>
                     <td class="text-center">${element.ubicacion}</td>
                     <td class="text-center">
                         <a class="text-danger text-center borrar" data-id='${element.codigo_producto}'>
                            <i class="far fa-trash-alt"></i>
                         </a>
                     </td>
                    </tr>
                    `;
            });
            $('#data_item').empty().html(cadena);


        }
    },
    permiso_verificacion: function () {
        var obj_inicial = $(`#envio_conteo`).html();
        btn_procesando(`envio_conteo`);
        $.ajax({
            url: `${PATH_NAME}/inventario_final/ValidarConteo`,
            type: "POST",
            data: { ubicacion: $('#ubicacion').val() },
            success: function (res) {
                btn_procesando(`envio_conteo`, obj_inicial, 1);

                if (res == '') {
                    $("#modal_no_verify").modal("show");
                    $("#form_conteo")[0].reset();
                    $("#span_operario_C").empty();
                    $("#span_codigo_C").empty();
                    $("#text_modal").empty().html("NO SE PUEDE VERIFICAR ESTA UBICACIÓN PORQUE NO A SIDO CONTADA AÚN.");
                    $('#ubicacion').val(0).trigger('change');
                } else {
                    conteo_inventario.agrega_local_storage();
                }
            }
        });
    },
    permiso_conteo: function () {
        var obj_inicial = $(`#envio_conteo`).html();
        btn_procesando(`envio_conteo`);
        $.ajax({
            url: `${PATH_NAME}/inventario_final/ValidarConteo`,
            type: "POST",
            data: { ubicacion: $('#ubicacion').val() },
            success: function (res) {
                if (res == '') {
                    btn_procesando(`envio_conteo`, obj_inicial, 1);
                    conteo_inventario.agrega_local_storage();
                } else {
                    $("#modal_no_verify").modal("show");
                    $("#form_conteo")[0].reset();
                    $("#span_operario_C").empty();
                    $("#span_codigo_C").empty();
                    $("#text_modal").empty().html("ESTA UBICACION YA FUE CONTADA");
                    $('#ubicacion').val(0).trigger('change');
                }
            }
        });
    },
    borrar_item: function () {
        var id = $(this).attr('data-id');
        let storage = JSON.parse(localStorage.getItem('canasta_etq'));
        storage.forEach(element => {
            if (element.codigo_producto == id) {
                var index = storage.indexOf(element);
                if (index > -1) {
                    storage.splice(index, 1);
                    localStorage.setItem('canasta_etq', JSON.stringify(storage));
                    conteo_inventario.cargar_tabla();

                }
            }
        });
        if (storage == '') {
            localStorage.removeItem('canasta_etq');
            $("#form_conteo")[0].reset();
            $('#ubicacion').attr('disabled', false);
            $('#id_usuario').attr('disabled', false);
            $('#conteo').attr('disabled', false);
            $('#verificacion').attr('disabled', false);
            $("#span_operario_C").empty().html('');;
            $('#ubicacion').val(0).trigger('change');
            CanastaInventario = [];
        }
    },
    registro_form_conteo: function () {
        var check_conteo = document.getElementById('conteo');
        var check_verificacion = document.getElementById('verificacion');
        let storage = JSON.parse(localStorage.getItem('canasta_etq'));
        if (storage == null || storage == '') {
            $("#modal_no_verify").modal("show");
            $("#text_modal").empty().html("¡¡NO HAY INFORMACIÓN PARA REGISTRAR!!");
            $("#form_conteo")[0].reset();
            $("#span_operario_C").empty();
            $("#span_codigo_C").empty();
        } else {
            alertify.confirm("¿ESTA SEGURO DE REGISTRAR ESTA UBICACIÓN?",
                function () {
                    if (check_verificacion.checked == true) {
                        // PARA CUANDO SE SE REALIZA VERIFICACIÓN
                        var obj_inicial = $(`#registro_conteo`).html();
                        btn_procesando(`registro_conteo`);
                        $.ajax({
                            url: `${PATH_NAME}/inventario_final/RegistrarVerificacion`,
                            type: "POST",
                            data: { storage },
                            success: function (res) {
                                btn_procesando(`registro_conteo`, obj_inicial, 1);

                                localStorage.removeItem('canasta_etq');
                                $('#data_item').empty().html('');
                                $("#form_conteo")[0].reset();
                                $('#ubicacion').attr('disabled', false);
                                $('#id_usuario').attr('disabled', false);
                                $('#conteo').attr('disabled', false);
                                $('#verificacion').attr('disabled', false);
                                $("#span_operario_C").empty();
                                $("#span_codigo_C").empty();
                                CanastaInventario = [];
                                alertify.success('¡¡Se Registro Correctamente Esta Ubicación!!');
                                $('#ubicacion').val(0).trigger('change');
                            }
                        });
                    }
                    if (check_conteo.checked == true) {
                        // PARA CUANDO SE SE REALIZA CONTEO
                        var obj_inicial = $(`#registro_conteo`).html();
                        btn_procesando(`registro_conteo`);
                        $.ajax({
                            url: `${PATH_NAME}/inventario_final/RegistrarConteo`,
                            type: "POST",
                            data: { storage },
                            success: function (res) {
                                btn_procesando(`registro_conteo`, obj_inicial, 1);
                                localStorage.removeItem('canasta_etq');
                                $('#data_item').empty().html('');
                                $("#form_conteo")[0].reset();
                                $('#ubicacion').attr('disabled', false);
                                $('#id_usuario').attr('disabled', false);
                                $('#conteo').attr('disabled', false);
                                $('#verificacion').attr('disabled', false);
                                $("#span_operario_C").empty();
                                $("#span_codigo_C").empty();
                                CanastaInventario = [];
                                alertify.success('¡¡Se Registro Correctamente Esta Ubicación!!');
                                $('#ubicacion').val(0).trigger('change');
                            }
                        });
                    }
                },
                function () {
                    alertify.error('Cancelado');
                });
        }
    }
}
conteo_inventario.init();
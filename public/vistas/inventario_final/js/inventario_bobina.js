var CanastaInventario = [];
var estado = 0;
$('#entrada_bob').attr('disabled', 'disabled');
var inventario_bobinas = {
    init: function () {
        $('#id_usuario_bob').blur(inventario_bobinas.valida_operario);
        $('#codigo_producto_bob').blur(inventario_bobinas.valida_codigoProduct);
        $('#codigo_producto_bob').change(inventario_bobinas.cambio_cod);
        $('.envio_conteo_bob').on('click', inventario_bobinas.validacion_form_conteo);
        inventario_bobinas.cargar_tabla();
        $("#conteo_bobinas").on('click', '.borrar', inventario_bobinas.borrar_item);
        $('.registro_conteo_bob').on('click', inventario_bobinas.registro_form_conteo);
        $('#ancho_bob').blur(inventario_bobinas.calcula_entrada);
        $('#metros_bob').blur(inventario_bobinas.calcula_entrada);
        $('#ubicacion_bob').select2();

    },
    cambio_cod: function () {
        var data = $("#codigo_producto_bob").val();
        var cant = data.split(';');
        var codigo = cant['0'];
        var ancho = cant['1'];
        var metros = cant['2'];
        $('#codigo_producto_bob').val(codigo);
        $('#ancho_bob').val(ancho);
        $('#metros_bob').val(metros);
        inventario_bobinas.valida_codigoProduct();
        inventario_bobinas.calcula_entrada();
    },
    valida_operario: function () {
        let newUsu = $('#id_usuario_bob').val().replace(/ /g, "");
        $('#id_usuario_bob').val(newUsu);
        let documento = $("#id_usuario_bob").val();
        $.ajax({
            url: `${PATH_NAME}/produccion/validar_operario`,
            type: "POST",
            data: { documento },
            success: function (res) {
                if (res == '') {
                    $("#id_usuario_bob").focus();
                    $("#span_operario_CB").empty().html(`<span class="text-center" style="color:red; font-size: 13px;padding: 0 0 0 0;">NO EXISTE ESTE USUARIO!!</span>`);
                    $("#id_persona_B").val('');
                } else {
                    $("#id_persona_B").val(res[0].id_persona);
                    $("#span_operario_CB").empty().html(`<span class="text-center" style="color:blue; font-size: 13px;padding: 0 0 0 0;">USUARIO: ${res[0].nombres} ${res[0].apellidos}</span>`);
                }
            }
        });
    },
    valida_codigoProduct: function () {
        let newCod = ($('#codigo_producto_bob').val().replace(/ /g, "")).toUpperCase();
        $('#codigo_producto_bob').val(newCod);
        let codigo = $("#codigo_producto_bob").val();
        $.ajax({
            url: `${PATH_NAME}/inventario_final/consultar_codigos`,
            type: "POST",
            data: { codigo },
            success: function (res) {
                if (res == '') {
                    $("#codigo_producto_bob").focus();
                    $("#span_codigo_CB").empty().css('color', 'red').html(`NO EXISTE ESTE CODIGO DE PRODUCTO!!`);
                    $("#id_producto_bob").val('');
                } else {
                    if (res[0].id_tipo_articulo == 4 || res[0].id_tipo_articulo == 15) {
                        $("#id_producto_bob").val(res[0].id_productos);
                        $("#span_codigo_CB").empty().css('color', 'blue').html(`CODIGO: ${res[0].descripcion_productos}`);

                    } else {
                        $("#codigo_producto_bob").focus();
                        $("#span_codigo_CB").empty().css('color', 'red').html(`ESTE CODIGO DE PRODUCTO NO PERTENECE A UNA BOBINA!!`);
                        $("#id_producto_bob").val('');
                    }
                }
            }
        });
    },
    validacion_form_conteo: function () {
        var form_validation = $("#form_conteo_bob").serializeArray();
        var check_conteo = document.getElementById('conteo_bob');
        var check_verificacion = document.getElementById('verificacion_bob');
        if (check_conteo.checked == true || check_verificacion.checked == true) { } else {
            alertify.error('Error elija tipo de inventario!!');
            return;
        }
        if (check_conteo.checked == true && check_verificacion.checked == true) {
            $("#span_check_bob").empty().html('Error elija un solo tipo de inventario!!');
            return;
        } else {
            $("#span_check_bob").empty();
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
                    $("#entrada_bob").val('');
                    return;
                }
            }
            if (form_validation[i].name == 'ancho') {
                if (!REG_EXP_NUMEROS.test(form_validation[i].value)) {
                    alertify.error('Error Ancho invalido!!');
                    $("#ancho_bob").val('');
                    return;
                }
            }
            if (form_validation[i].name == 'metros') {
                if (!REG_EXP_NUMEROS.test(form_validation[i].value)) {
                    alertify.error('Error metros invalidos!!');
                    $("#metros_bob").val('');
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
            inventario_bobinas.permiso_verificacion();
        }
        if (check_conteo.checked == true) {
            inventario_bobinas.permiso_conteo();
        }
    },
    agrega_local_storage: function () {
        var obj_inicial = $(`#envio_conteo_bob`).html();
        btn_procesando(`envio_conteo_bob`);
        var valida_storage = JSON.parse(localStorage.getItem('canasta_bob'));
        if (valida_storage != null) {
            $('#ubicacion_bob').attr('disabled', 'disabled')
            $('#id_usuario_bob').attr('disabled', 'disabled')
            CanastaInventario = valida_storage;
        }
        var datos = {
            num_usuario: $('#id_usuario_bob').val(),
            id_usuario: $('#id_persona_B').val(),
            codigo_producto: $('#codigo_producto_bob').val(),
            ubicacion: $('#ubicacion_bob').val(),
            ancho: $('#ancho_bob').val(),
            metros: $('#metros_bob').val(),
            entrada: $('#entrada_bob').val(),
            documento: 'INV 2023',
            id_proveedor: 21,
            id_productos: $("#id_producto_bob").val(),
            estado: estado,
            tipo: 3,
        }
        if (CanastaInventario == '') {
            CanastaInventario.push(datos);
        } else {
            var respu = false;
            valida_storage.forEach(element => {
                if (element.codigo_producto == datos.codigo_producto && element.ancho == datos.ancho) {
                    var nuevo = parseFloat(element.entrada) + parseFloat(datos.entrada);
                    var nuevometros = parseFloat(element.metros) + parseFloat(datos.metros);
                    element['entrada'] = nuevo;
                    element['metros'] = nuevometros;
                    respu = true;
                }
            });
            if (!respu) {
                CanastaInventario.push(datos);
            }
        }
        localStorage.setItem('canasta_bob', JSON.stringify(CanastaInventario));
        btn_procesando(`envio_conteo_bob`, obj_inicial, 1);
        inventario_bobinas.cargar_tabla();
        $("#span_codigo_CB").empty();
        $("#codigo_producto_bob").val('');
        $("#entrada_bob").val('');
        $("#ancho_bob").val('');
        $("#metros_bob").val('');
        $("#codigo_producto_bob").focus();
    },
    cargar_tabla: function () {
        var cadena = '';
        let storage = JSON.parse(localStorage.getItem('canasta_bob'));
        if (storage != null) {
            $('#ubicacion_bob').attr('disabled', 'disabled')
            $('#id_usuario_bob').attr('disabled', 'disabled')
            $('#conteo_bob').attr('disabled', 'disabled')
            $('#verificacion_bob').attr('disabled', 'disabled')
            storage.forEach(element => {
                $('#ubicacion_bob').val(element.ubicacion);
                $('#id_usuario_bob').val(element.num_usuario);
                // $('#id_usuario_bob').on('click', inventario_bobinas.valida_operario);
                $('#id_usuario_bob').click();
                if (element.estado == 1) {
                    $("#conteo_bob").prop("checked", true);
                }
                if (element.estado == 2) {
                    $("#verificacion_bob").prop("checked", true);
                }
                cadena += /*html*/ `
                    <tr>
                     <td class="text-center">${element.codigo_producto}</td>
                     <td class="text-center">${element.ancho}</td>
                     <td class="text-center">${element.metros}</td>
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
            $('#data_item_bob').empty().html(cadena);
        }
    },
    permiso_verificacion: function () {
        var obj_inicial = $(`#envio_conteo_bob`).html();
        btn_procesando(`envio_conteo_bob`);
        $.ajax({
            url: `${PATH_NAME}/inventario_final/ValidarConteo`,
            type: "POST",
            data: { ubicacion: $('#ubicacion_bob').val() },
            success: function (res) {
                btn_procesando(`envio_conteo_bob`, obj_inicial, 1);
                if (res == '') {
                    $("#modal_no_verify_bob").modal("show");
                    $("#form_conteo_bob")[0].reset();
                    $("#span_operario_CB").empty();
                    $("#span_codigo_CB").empty();
                    $("#text_modal_bob").empty().html("NO SE PUEDE VERIFICAR ESTA UBICACIÓN PORQUE NO A SIDO CONTADA AÚN.");
                    $('#ubicacion_bob').val(0).trigger('change');
                } else {
                    inventario_bobinas.agrega_local_storage();
                }
            }
        });
    },
    permiso_conteo: function () {
        var obj_inicial = $(`#envio_conteo_bob`).html();
        btn_procesando(`envio_conteo_bob`);
        $.ajax({
            url: `${PATH_NAME}/inventario_final/ValidarConteo`,
            type: "POST",
            data: { ubicacion: $('#ubicacion_bob').val() },
            success: function (res) {
                btn_procesando(`envio_conteo_bob`, obj_inicial, 1);
                if (res == '') {
                    inventario_bobinas.agrega_local_storage();
                } else {
                    $("#modal_no_verify_bob").modal("show");
                    $("#form_conteo_bob")[0].reset();
                    $("#span_operario_CB").empty();
                    $("#span_codigo_CB").empty();
                    $("#text_modal_bob").empty().html("ESTA UBICACION YA FUE CONTADA");
                    $('#ubicacion_bob').val(0).trigger('change');
                }
            }
        });
    },
    borrar_item: function () {
        var id = $(this).attr('data-id');
        let storage = JSON.parse(localStorage.getItem('canasta_bob'));
        storage.forEach(element => {
            if (element.codigo_producto == id) {
                var index = storage.indexOf(element);
                if (index > -1) {
                    storage.splice(index, 1);
                    localStorage.setItem('canasta_bob', JSON.stringify(storage));
                    inventario_bobinas.cargar_tabla();
                }
            }
        });
        if (storage == '') {
            localStorage.removeItem('canasta_bob');
            $("#form_conteo_bob")[0].reset();
            $('#ubicacion_bob').attr('disabled', false);
            $('#id_usuario_bob').attr('disabled', false);
            $('#conteo_bob').attr('disabled', false);
            $('#verificacion_bob').attr('disabled', false);
            $("#span_operario_CB").empty();
            $('#ubicacion_bob').val(0).trigger('change');
            CanastaInventario = [];
        }
    },
    registro_form_conteo: function () {
        var check_conteo = document.getElementById('conteo_bob');
        var check_verificacion = document.getElementById('verificacion_bob');
        let storage = JSON.parse(localStorage.getItem('canasta_bob'));
        if (storage == null || storage == '') {
            $("#modal_no_verify_bob").modal("show");
            $("#text_modal_bob").empty().html("¡¡NO HAY INFORMACIÓN PARA REGISTRAR!!");
            $("#form_conteo_bob")[0].reset();
            $("#span_operario_CB").empty();
            $("#span_codigo_CB").empty();
        } else {
            alertify.confirm("¿ESTA SEGURO DE REGISTRAR ESTA UBICACIÓN?",
                function () {
                    if (check_verificacion.checked == true) {
                        // PARA CUANDO SE SE REALIZA VERIFICACIÓN
                        var obj_inicial = $(`#registro_conteo_bob`).html();
                        btn_procesando(`registro_conteo_bob`);
                        $.ajax({
                            url: `${PATH_NAME}/inventario_final/RegistrarVerificacion`,
                            type: "POST",
                            data: { storage },
                            success: function (res) {
                                btn_procesando(`registro_conteo_bob`, obj_inicial, 1);
                                localStorage.removeItem('canasta_bob');
                                $('#data_item_bob').empty().html('');
                                $("#form_conteo_bob")[0].reset();
                                $('#ubicacion_bob').attr('disabled', false);
                                $('#id_usuario_bob').attr('disabled', false);
                                $('#conteo_bob').attr('disabled', false);
                                $('#verificacion_bob').attr('disabled', false);
                                $("#span_operario_CB").empty();
                                $("#span_codigo_CB").empty();
                                CanastaInventario = [];
                                alertify.success('¡¡Se Registro Correctamente Esta Ubicación!!');
                                $('#ubicacion_bob').val(0).trigger('change');
                            }
                        });
                    }
                    if (check_conteo.checked == true) {
                        // PARA CUANDO SE SE REALIZA CONTEO
                        var obj_inicial = $(`#registro_conteo_bob`).html();
                        btn_procesando(`registro_conteo_bob`);
                        $.ajax({
                            url: `${PATH_NAME}/inventario_final/RegistrarConteo`,
                            type: "POST",
                            data: { storage },
                            success: function (res) {
                                btn_procesando(`registro_conteo_bob`, obj_inicial, 1);
                                localStorage.removeItem('canasta_bob');
                                $('#data_item_bob').empty().html('');
                                $("#form_conteo_bob")[0].reset();
                                $('#ubicacion_bob').attr('disabled', false);
                                $('#id_usuario_bob').attr('disabled', false);
                                $('#conteo_bob').attr('disabled', false);
                                $('#verificacion_bob').attr('disabled', false);
                                $("#span_operario_CB").empty();
                                $("#span_codigo_CB").empty();
                                CanastaInventario = [];
                                alertify.success('¡¡Se Registro Correctamente Esta Ubicación!!');
                                $('#ubicacion_bob').val(0).trigger('change');
                            }
                        });
                    }
                },
                function () {
                    alertify.error('Cancelado');
                });
        }
    },
    calcula_entrada: function () {
        let ancho = $("#ancho_bob").val();
        let metros = $("#metros_bob").val();
        let entrada = '';
        if (ancho != 0 && metros != 0) {
            entrada = (parseFloat(ancho) * parseFloat(metros)) / 1000;
        }
        $('#entrada_bob').val(entrada);
    }
}
inventario_bobinas.init();


var CanastaInventario = [];
var estado = 0;
var inventario_tecnologia = {
    init: function () {
        $('#id_usuario_tec').blur(inventario_tecnologia.valida_operario);
        $('#codigo_producto_tec').blur(inventario_tecnologia.valida_codigoProduct);
        $('#codigo_producto_tec').change(inventario_tecnologia.cambio_cod);
        $('.envio_conteo_tec').on('click', inventario_tecnologia.validacion_form_conteo);
        inventario_tecnologia.cargar_tabla();
        $("#conteo_tecnologia").on('click', '.borrar', inventario_tecnologia.borrar_item);
        $('.registro_conteo_tec').on('click', inventario_tecnologia.registro_form_conteo);
        $('#ubicacion_tec').select2();

    },
    cambio_cod: function () {
        var data = $("#codigo_producto_tec").val();
        var cant = data.split(';');
        var codigo = cant['0'];
        var cantidad = cant['1'];
        $('#codigo_producto_tec').val(codigo);
        $('#entrada_tec').val(cantidad);
        inventario_tecnologia.valida_codigoProduct();
    },
    valida_operario: function () {
        let newUsu = $('#id_usuario_tec').val().replace(/ /g, "");
        $('#id_usuario_tec').val(newUsu);
        let documento = $("#id_usuario_tec").val();
        $.ajax({
            url: `${PATH_NAME}/produccion/validar_operario`,
            type: "POST",
            data: { documento },
            success: function (res) {
                if (res == '') {
                    $("#id_usuario_tec").focus();
                    $("#span_operario_CT").empty().html(`<span class="text-center" style="color:red; font-size: 13px;padding: 0 0 0 0;">NO EXISTE ESTE USUARIO!!</span>`);
                    $("#id_persona_T").val('');
                } else {
                    $("#id_persona_T").val(res[0].id_persona);
                    $("#span_operario_CT").empty().html(`<span class="text-center" style="color:blue; font-size: 13px;padding: 0 0 0 0;">USUARIO: ${res[0].nombres} ${res[0].apellidos}</span>`);
                }
            }
        });

    },
    valida_codigoProduct: function () {
        let newCod = ($('#codigo_producto_tec').val().replace(/ /g, "")).toUpperCase();
        $('#codigo_producto_tec').val(newCod);
        let codigo = $("#codigo_producto_tec").val();
        $.ajax({
            url: `${PATH_NAME}/inventario_final/consultar_codigos`,
            type: "POST",
            data: { codigo },
            success: function (res) {
                if (res == '') {
                    $("#codigo_producto_tec").focus();
                    $("#span_codigo_CT").empty().css('color', 'red').html(`NO EXISTE ESTE CODIGO DE PRODUCTO!!`);
                    $("#id_producto_tec").val('');
                } else {
                    if (res[0].id_tipo_articulo != 1 && res[0].id_tipo_articulo != 4) {
                        $("#id_producto_tec").val(res[0].id_productos);
                        $("#span_codigo_CT").empty().css('color', 'blue').html(`CODIGO: ${res[0].descripcion_productos}`);
                    } else {
                        $("#codigo_producto_tec").focus();
                        $("#span_codigo_CT").empty().css('color', 'red').html(`ESTE CODIGO DE PRODUCTO NO PERTENECE A UNA TECNOLOGIA!!`);
                        $("#id_producto_tec").val('');
                    }
                }
            }
        });
    },
    validacion_form_conteo: function () {
        var form_validation = $("#form_conteo_tec").serializeArray();
        var check_conteo = document.getElementById('conteo_tec');
        var check_verificacion = document.getElementById('verificacion_tec');
        if (check_conteo.checked == true || check_verificacion.checked == true) { } else {
            alertify.error('Error elija tipo de inventario!!');
            return;
        }
        if (check_conteo.checked == true && check_verificacion.checked == true) {
            $("#span_check_tec").empty().html('Error elija un solo tipo de inventario!!');
            return;
        } else {
            $("#span_check_tec").empty();
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
                    $("#entrada_tec").val('');
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
            inventario_tecnologia.permiso_verificacion();
        }
        if (check_conteo.checked == true) {
            inventario_tecnologia.permiso_conteo();
        }
    },
    agrega_local_storage: function () {
        var obj_inicial = $(`#registro_conteo_tec`).html();
        btn_procesando(`registro_conteo_tec`);
        var valida_storage = JSON.parse(localStorage.getItem('canasta_tec'));
        if (valida_storage != null) {
            $('#ubicacion_tec').attr('disabled', 'disabled')
            $('#id_usuario_tec').attr('disabled', 'disabled')
            CanastaInventario = valida_storage;
        }
        var datos = {
            num_usuario: $('#id_usuario_tec').val(),
            id_usuario: $('#id_persona_T').val(),
            codigo_producto: $('#codigo_producto_tec').val(),
            ubicacion: $('#ubicacion_tec').val(),
            entrada: $('#entrada_tec').val(),
            documento: 'INV 2020',
            id_proveedor: 21,
            id_productos: $("#id_producto_tec").val(),
            estado: estado,
            tipo: 2,
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
        localStorage.setItem('canasta_tec', JSON.stringify(CanastaInventario));
        btn_procesando(`registro_conteo_tec`, obj_inicial, 1);
        inventario_tecnologia.cargar_tabla();
        $("#span_codigo_CT").empty();
        $("#codigo_producto_tec").val('');
        $("#entrada_tec").val('');
        $("#codigo_producto_tec").focus();

    },
    cargar_tabla: function () {
        var cadena = '';
        let storage = JSON.parse(localStorage.getItem('canasta_tec'));
        if (storage != null) {
            $('#ubicacion_tec').attr('disabled', 'disabled')
            $('#id_usuario_tec').attr('disabled', 'disabled')
            $('#conteo_tec').attr('disabled', 'disabled')
            $('#verificacion_tec').attr('disabled', 'disabled')
            storage.forEach(element => {
                $('#ubicacion_tec').val(element.ubicacion);
                $('#id_usuario_tec').val(element.num_usuario);
                $('#id_usuario_tec').on('click', inventario_tecnologia.valida_operario);
                $('#id_usuario_tec').click();
                if (element.estado == 1) {
                    $("#conteo_tec").prop("checked", true);
                }
                if (element.estado == 2) {
                    $("#verificacion_tec").prop("checked", true);
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
            $('#data_item_tec').empty().html(cadena);
        }
    },
    permiso_verificacion: function () {
        var obj_inicial = $(`#registro_conteo_tec`).html();
        btn_procesando(`registro_conteo_tec`);
        $.ajax({
            url: `${PATH_NAME}/inventario_final/ValidarConteo`,
            type: "POST",
            data: { ubicacion: $('#ubicacion_tec').val() },
            success: function (res) {
                btn_procesando(`registro_conteo_tec`, obj_inicial, 1);

                if (res == '') {
                    $("#modal_no_verify_tec").modal("show");
                    $("#form_conteo_tec")[0].reset();
                    $("#span_operario_CT").empty();
                    $("#span_codigo_CT").empty();
                    $("#text_modal_tec").empty().html("NO SE PUEDE VERIFICAR ESTA UBICACIÓN PORQUE NO A SIDO CONTADA AÚN.");
                    $('#ubicacion_tec').val(0).trigger('change');
                } else {
                    inventario_tecnologia.agrega_local_storage();
                }
            }
        });
    },
    permiso_conteo: function () {
        var obj_inicial = $(`#registro_conteo_tec`).html();
        btn_procesando(`registro_conteo_tec`);
        $.ajax({
            url: `${PATH_NAME}/inventario_final/ValidarConteo`,
            type: "POST",
            data: { ubicacion: $('#ubicacion_tec').val() },
            success: function (res) {
                btn_procesando(`registro_conteo_tec`, obj_inicial, 1);
                if (res == '') {
                    inventario_tecnologia.agrega_local_storage();
                } else {
                    $("#modal_no_verify_tec").modal("show");
                    $("#form_conteo_tec")[0].reset();
                    $("#span_operario_CT").empty();
                    $("#span_codigo_CT").empty();
                    $("#text_modal_tec").empty().html("ESTA UBICACION YA FUE CONTADA");
                    $('#ubicacion_tec').val(0).trigger('change');
                }
            }
        });
    },
    borrar_item: function () {
        var id = $(this).attr('data-id');
        let storage = JSON.parse(localStorage.getItem('canasta_tec'));
        storage.forEach(element => {
            if (element.codigo_producto == id) {
                var index = storage.indexOf(element);
                if (index > -1) {
                    storage.splice(index, 1);
                    localStorage.setItem('canasta_tec', JSON.stringify(storage));
                    inventario_tecnologia.cargar_tabla();

                }
            }
        });
        if (storage == '') {
            localStorage.removeItem('canasta_tec');
            $("#form_conteo_tec")[0].reset();
            $('#ubicacion_tec').attr('disabled', false);
            $('#id_usuario_tec').attr('disabled', false);
            $('#conteo_tec').attr('disabled', false);
            $('#verificacion_tec').attr('disabled', false);
            $("#span_operario_CT").empty();
            $('#ubicacion_tec').val(0).trigger('change');
            CanastaInventario = [];

        }
    },
    registro_form_conteo: function () {
        var check_conteo = document.getElementById('conteo_tec');
        var check_verificacion = document.getElementById('verificacion_tec');
        let storage = JSON.parse(localStorage.getItem('canasta_tec'));
        if (storage == null || storage == '') {
            $("#modal_no_verify_tec").modal("show");
            $("#text_modal_tec").empty().html("¡¡NO HAY INFORMACIÓN PARA REGISTRAR!!");
            $("#form_conteo_tec")[0].reset();
            $("#span_operario_CT").empty();
            $("#span_codigo_CT").empty();
        } else {
            alertify.confirm("¿ESTA SEGURO DE REGISTRAR ESTA UBICACIÓN?",
                function () {
                    if (check_verificacion.checked == true) {
                        // PARA CUANDO SE SE REALIZA VERIFICACIÓN
                        var obj_inicial = $(`#registro_conteo_tec`).html();
                        btn_procesando(`registro_conteo_tec`);
                        $.ajax({
                            url: `${PATH_NAME}/inventario_final/RegistrarVerificacion`,
                            type: "POST",
                            data: { storage },
                            success: function (res) {
                                btn_procesando(`registro_conteo_tec`, obj_inicial, 1);
                                localStorage.removeItem('canasta_tec');
                                $('#data_item_tec').empty().html('');
                                $("#form_conteo_tec")[0].reset();
                                $('#ubicacion_tec').attr('disabled', false);
                                $('#id_usuario_tec').attr('disabled', false);
                                $('#conteo_tec').attr('disabled', false);
                                $('#verificacion_tec').attr('disabled', false);
                                $("#span_operario_CT").empty();
                                $("#span_codigo_CT").empty();
                                CanastaInventario = [];
                                alertify.success('¡¡Se Registro Correctamente Esta Ubicación!!');
                                $('#ubicacion_tec').val(0).trigger('change');

                            }
                        });
                    }
                    if (check_conteo.checked == true) {
                        // PARA CUANDO SE SE REALIZA CONTEO
                        var obj_inicial = $(`#registro_conteo_tec`).html();
                        btn_procesando(`registro_conteo_tec`);
                        $.ajax({
                            url: `${PATH_NAME}/inventario_final/RegistrarConteo`,
                            type: "POST",
                            data: { storage },
                            success: function (res) {
                                btn_procesando(`registro_conteo_tec`, obj_inicial, 1);
                                localStorage.removeItem('canasta_tec');
                                $('#data_item_tec').empty().html('');
                                $("#form_conteo_tec")[0].reset();
                                $('#ubicacion_tec').attr('disabled', false);
                                $('#id_usuario_tec').attr('disabled', false);
                                $('#conteo_tec').attr('disabled', false);
                                $('#verificacion_tec').attr('disabled', false);
                                $("#span_operario_CT").empty();
                                $("#span_codigo_CT").empty();
                                CanastaInventario = [];
                                alertify.success('¡¡Se Registro Correctamente Esta Ubicación!!');
                                $('#ubicacion_tec').val(0).trigger('change');
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
inventario_tecnologia.init();
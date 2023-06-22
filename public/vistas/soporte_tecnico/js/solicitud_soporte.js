// ESTE ES EL PRIMER JS QUE EJECUTA EL MODULO DE SOPORTE TECNICO
$(document).ready(function () {
    select_2();
    solicitud_soporte();
    seleccion_checkbox();
});
// SE REALIZA UN BLUR CUANDO DIGITEN UN NUMERO DE NIT PARA CONSULTAR SI EXISTE INFORMACION DE LA EMPRESA DIGITADA
var solicitud_soporte = function () {
    $("#nit_empresa").on('blur', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                url: `${PATH_NAME}/soporte_tecnico/consulta_datos_empresa`,
                type: "POST",
                data: form,
                success: function (res) {
                    // DEPENDIENDO LA RESPUESTA SE LE CARGA EL RESTO DE LA INFORMACION DE LA EMPRESA
                    if (res['data_empresa'] == '') {
                        $('.datos_empresa').html('<input class="form-control" name="nombre_empresa" id="nombre_empresa"></input>');
                        $('.dig_veri').html('<input class="form-control" name="dig_verificacion" id="dig_veri"></input>');
                        $('.span_nombre').css('display', 'none');
                        $('.span_digito').css('display', 'none');
                    } else {
                        var nombre_empresa = res['data_empresa'].nombre_empresa;
                        var dig_verificacion = res['data_empresa'].dig_verificacion;
                        $('.span_nombre').css('display', '');
                        $('.span_digito').css('display', '');
                        $('.datos_empresa').html('<span class="form-control span_nombre">N/A</span>');
                        $('.dig_veri').html('<span class="form-control span_digito">N/A</span>');
                        $('.span_nombre').html(nombre_empresa);
                        $('.span_digito').html(dig_verificacion);
                        // SI EXISTEN DIRECCIONES DE LA EMPRESA LAS CARGA 
                    }
                    rellena_direcciones(res['direcciones']);
                    llenar_form_direc();
                }
            });
        }
    });
}
var rellena_direcciones = function (data) {
    // SI LA DATA QUE LLEGA VIENE VACIA NOS DA LA OPCIÓN DE NOSOTROS CARGARLE UNA DIRECCION A ESA EMPRESA SI VIENE LLENA SE CARGAN LOS DATOS  
    if (data != '') {
        var item = "<option value='0'>Elija Una Dirección</option>";
        data.forEach(element => {
            item += /*html*/
                ` <option value='${JSON.stringify(element)}'>${element.direccion}</option>`;
        });
        $('#direccion_soli').css('display', 'none');
        $('#direc_solicitud').css('display', '');
        $('#direc_solicitud').html(item);
        $('.input_activo').attr('readonly', 'readonly');
        $('.select_activo').attr('disabled', 'disabled');
    } else {
        $('#direccion_soli').css('display', '');
        $('#direc_solicitud').css('display', 'none');
        $('.input_activo').removeAttr('readonly');
        $('.select_activo').removeAttr('disabled');
        limpiar_formulario('form_direccion', 'input');
        limpiar_formulario('form_direccion', 'select');

    }
}

var seleccion_checkbox = function () {
    // AQUI SE SELECCIONAN LOS CHECKBOX DEPENDIENDO LOS SELECCIONADOS SE REALIZAN DIFERENTES CAMBIOS DE ESTADO EN LA BASE DE DATOS 
    $('.req_visita').click(function () {
        var valor = $(this).val();
        var form = $('#crea_solicitud_soporte').serializeArray();
        if (valor == 1) {
            $('#requiere_visita').css('display', 'none');
            alertify.confirm('ALERTA ACOBARRAS', '¿Esta seguro que no necesita una visita?', function () {
                $.ajax({
                    "url": `${PATH_NAME}/soporte_tecnico/agregar_datos`,
                    "type": 'POST',
                    "data": form,
                    success: function (res) {
                        if (res.status = 'true') {
                            window.location.href = `${PATH_NAME}/laboratorio_soporte?id=${res.id}`;
                            borrar_form();
                        } else {
                            alertify.error('algo a sucedido');
                        }
                    }
                });
            }, function () { alertify.error('Cancelado'); })
                .set('labels', { ok: 'Si', cancel: 'No' });
        } else {
            $('#requiere_visita').css('display', 'block');
        }
    });
    $('.visita_prese').click(function () {
        var valor = $(this).val();
        var form = $('#crea_solicitud_soporte').serializeArray();
        if (valor == 1) {
            $('#cobro_ser').css('display', 'block');
        } else {
            $('#cobro_ser').css('display', 'none');
            if ($('#visita_prese').val() != null) {
                $('#visita_prese').val(0).change();
            }
            alertify.confirm('ALERTA ACOBARRAS', '¿Esta seguro que no se debe realizar la visita presencial?', function () {
                $.ajax({
                    "url": `${PATH_NAME}/soporte_tecnico/agregar_datos`,
                    "type": 'POST',
                    "data": form,
                    success: function (res) {
                        if (res.status = 'true') {
                            window.location.href = `${PATH_NAME}/vista_caso_remoto?id=${res.id}`;
                            borrar_form();
                        } else {
                            alertify.error('algo a sucedido');
                        }
                    }
                });
            }, function () { alertify.error('Cancelado') })
                .set('labels', { ok: 'Si', cancel: 'No' });
        }
    });
    $('.cobro_ser').click(function () {
        var valor = $(this).val();
        var form = $('#crea_solicitud_soporte').serializeArray();
        if (valor == 1) {
            $('#req_cotiza').css('display', 'block');
        } else {
            $('#req_cotiza').css('display', 'none');
            alertify.confirm('ALERTA ACOBARRAS', '¿Esta seguro que el servicio no posee ningun costo?', function () {
                $.ajax({
                    "url": `${PATH_NAME}/soporte_tecnico/agregar_datos`,
                    "type": 'POST',
                    "data": form,
                    success: function (res) {
                        if (res.status = 'true') {
                            window.location.href = `${PATH_NAME}/vista_cotizacion?id=${res.id}`;
                            borrar_form();
                        } else {
                            alertify.error('algo a sucedido');
                        }
                    }
                });
            }, function () { alertify.error('Cancelado') })
                .set('labels', { ok: 'Si', cancel: 'No' });
        }
    });
    $('.req_cotiza').click(function () {
        var valor = $(this).val();
        var form = $('#crea_solicitud_soporte').serializeArray();
        if (valor == 1) {
            $.ajax({
                "url": `${PATH_NAME}/soporte_tecnico/agregar_datos`,
                "type": 'POST',
                "data": form,
                success: function (res) {
                    if (res.status = 'true') {
                        alertify.success('Se va a generar la cotizacion de la visita');
                        window.location.href = `${PATH_NAME}/vista_cotizacion?id=${res.id}`;
                        borrar_form();
                    } else {
                        alertify.error('algo a sucedido');
                    }
                }
            });
        } else {
            alertify.confirm('ALERTA ACOBARRAS', '¿Esta seguro que el servicio no necesita una cotizacion?', function () {
                $.ajax({
                    "url": `${PATH_NAME}/soporte_tecnico/agregar_datos`,
                    "type": 'POST',
                    "data": form,
                    success: function (res) {
                        if (res.status = 'true') {
                            window.location.href = `${PATH_NAME}/vista_cotizacion?id=${res.id}`;
                            borrar_form();
                        } else {
                            alertify.error('algo a sucedido');
                        }
                    }
                });
            }, function () { alertify.error('Cancelado') })
                .set('labels', { ok: 'Si', cancel: 'No' });
        }
    });
}

var llenar_form_direc = function () {
    // CUANDO HACEN UN CAMBIO DE DRECCION SE VUELVE A CARGAR LA INFORMACION DEL NUEVO DATO A LOS DIFERENTES CAMPOS 
    $("#direc_solicitud").on('change', function () {
        var elegido = JSON.parse($(this).val());
        if (elegido != '') {
            $('#id_pais_soli').val(elegido.id_pais).change();
            $('#id_departamento_soli').val(elegido.id_departamento).change();
            $('#id_ciudad_soli').val(elegido.id_ciudad).change();
            $('#telefono_soli').empty().val(elegido.telefono);
            $('#celular_soli').empty().val(elegido.celular);
            $('#correo_soli').empty().val(elegido.email);
            $('#contacto_soli').empty().val(elegido.contacto);
            $('#cargo_soli').empty().val(elegido.cargo);
            $('#horario_soli').empty().val(elegido.horario);
            $('#link_soli').empty().val(elegido.link_maps);
            $('#ruta_modifi').val(elegido.ruta).change();
        }
    });
}

var borrar_form = function () {
    // ESTA FUNCION SIRVE PARA LIMPIAR TODOS LOS CAMPOS DEL FORMULARIO DE SOLICITUD SOPORTE
    limpiar_formulario('form_direccion', 'select');
    limpiar_formulario('form_direccion', 'input');
    $('#cobro_ser').css('display', 'none');
    $('#requiere_visita').css('display', 'none');
    $('#req_cotiza').css('display', 'none');
    $('.span_nombre').empty().html('N/A');
    $('.span_digito').empty().html('N/A');
    $('#nit_empresa').val('').change();
    $('#direc_solicitud').val(0).change();
}
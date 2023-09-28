$(document).ready(function () {
    $('.select_2').css('width', '100%');
    $('.select_2').select2();
    consulta_cliente();
    envio_creacion_cliente();
    tipo_solicitud();
    enviar_solicitud_diseno();
    cargar_select_();
    input_tintas();
    tipo_codigo()
});

// consultar si el cliente existe - se reutiliza una funcion de soporte que ya exista 
var consulta_cliente = function () {
    $("#nit_cod").on('blur', function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida = validar_formulario(form);
        if (valida) {
            $('#icono_carga_cod').html('<div class="spinner-border spinner-border-sm" role="status"></div>');
            $.ajax({
                url: `${PATH_NAME}/soporte_tecnico/consulta_datos_empresa`,
                type: "POST",
                data: form,
                success: function (res) {
                    if (res['data_empresa'] == '') {// la empresa no existe 
                        $('#icono_carga_cod').html('<i class="fas fa-exclamation"></i>');
                        $('#nombre_cliente_cod').val('')
                        alertify.confirm(`ALERTA SIDPA - CLIENTE NO EXISTE `, `El cliente no existe ¿Quiere solicitar su creación? `,
                            function () {// si enviar solicitud
                                $('#input_crea').removeClass('d-none');
                                $('.codigo').addClass('d-none');// esto se quita cuando habilite diseño
                                $('.solo_codigo').addClass('d-none');// esto se quita cuando habilite diseño 
                                //$('#tipo_solicitud').addClass('d-none'); este va cuando se habilite diseño // no olvidar quitar el checked defaul
                                $('#nombre_cliente_cod').removeAttr('readonly');
                                $('#titulo_cod').html('Correo – solicitud creación de cliente')
                            }, function () {// Cancelar
                                return;
                            }).set({
                                'labels': { ok: 'Si', cancel: 'Cancelar' },
                            });
                    } else {
                        $('#input_crea').addClass('d-none');
                        $('.codigo').removeClass('d-none');// esto se quita cuando habilite diseño
                        $('.solo_codigo').removeClass('d-none');// esto se quita cuando habilite diseño
                        //$('#tipo_solicitud').removeClass('d-none'); este va cuando se habilite diseño
                        $('#nombre_cliente_cod').attr("readonly", 'true');
                        $('#icono_carga_cod').html('<i class="far fa-check-circle"></i>');
                        $('#id_cli_prov').val(res.data_empresa.id_cli_prov);
                        $('#nombre_cliente_cod').val(res.data_empresa.nombre_empresa);
                        $('#titulo_cod').html('Solicitud de diseño')

                    }
                }
            });
        }
    });
}

// Solicitud de creación de cliente - correo contabilidad 
var envio_creacion_cliente = function () {
    $('#enviar_creacion').on('click', function (e) {
        e.preventDefault();
        var obj_inicial = $('#enviar_creacion').html();
        var nit = $("#nit_cod");
        var nombre_ciente = $("#nombre_cliente_cod");
        var rut = $("#rut");
        var validacion = false;
        if (nit.val() == '') {
            nit.focus();
            alertify.error('El Nit es requerido');
        } else if (nombre_ciente.val() == '') {
            nombre_ciente.focus();
            alertify.error('El Nombre del cliente es requerido');
        } else if (rut.val() == '') {
            rut.focus();
            alertify.error('Adjunta un archivo');
        } else {
            validacion = true
        }
        if (validacion) {
            btn_procesando('enviar_creacion');
            var formulario = document.getElementById('form_solicitud_codigo');
            $.ajax({
                url: `${PATH_NAME}/comercial/envio_correo_creacioncliente`,
                type: "POST",
                data: new FormData(formulario),
                cache: false,
                processData: false,
                contentType: false,
                success: function (res) {
                    btn_procesando('enviar_creacion', obj_inicial, 1);
                    if (res.state == 1) {
                        alertify.alert('Alerta SIDPA', 'Su solicitud ha sido enviada correctamente; por favor espere a que este cliente sea creado para poder continuar con la solicitud de código.',
                            function () {
                                $('#input_crea').addClass('d-none');
                                $('#nit_cod').val('');
                                $('#nombre_cliente_cod').attr("readonly", 'true');
                                $('#icono_carga_cod').html('#');
                                $('#nombre_cliente_cod').val('');
                                $('#titulo_cod').html('Solicitud de diseño')
                            });
                    } else {
                        alertify.error('Ocurrio un error intentalo de nuevo')
                    }
                }
            });
        }

    })
}
// definimos por un checked que campos mostrar
function tipo_solicitud() {
    $('.tipo_solicitud').change(function (e) {
        e.preventDefault();
        var tipo_solicitud = $(this).val();
        if (tipo_solicitud == 1) {
            $('.codigo').removeClass('d-none');
            $('.solo_codigo').removeClass('d-none');
            $('.diseno').addClass('d-none');
        } else if (tipo_solicitud == 2) {
            $('.codigo').removeClass('d-none');
            $('.diseno').removeClass('d-none');
            $('.solo_codigo').addClass('d-none');// este pertenece a la cantidad de tintas por select
        }
    })
}

// generamos las input necesarias para la cantidad de tintas del diseño 
function input_tintas() {
    $('#cantida_tintas_dis').blur(function (e) {
        e.preventDefault();
        var cantidad = $('#cantida_tintas_dis').val();
        if (cantidad > 10) {
            alertify.error('Solo puede agregar 10 tintas')
            cantidad = 10;
        }
        var html = '';
        for (let index = 1; index <= cantidad; index++) {
            html += `<div class="col">
            <label for="tinta_${index}" class="form-label fw-bold">Tinta ${index}:</label>
            <input type="text" class="form-control" name="tinta_${index}" id="tinta_${index}" placeholder="Color o tipo">
            </div>`
        }
        $('#input_tintas').empty().html(html);
    })
}
// cargar select de adhesivos
function cargar_select_() {
    $('#tipo_material').on('change', function () {
        var elegido = $(this).val();
        var data_adh = JSON.parse($('#id_adh').attr('data_adh'));
        var data_precio = JSON.parse($('#tipo_material').attr('data_precio'));
        var nuevo = [];
        data_precio.forEach(element => {
            if (element.id_tipo_material == elegido) {
                nuevo.push(element.id_adhesivo);
            }
        });
        var items = '<option value="0">Selecciona un adhesivo</option>';
        if (nuevo == '') {
            data_adh.forEach(element => {
                items += `<option value="${element.codigo_adh}">${element.nombre_adh}</option>`;
            });
        } else {
            data_adh.forEach(element => {
                var indice = nuevo.indexOf(element.id_adh);
                if (indice !== -1) {
                    items += `<option value="${element.codigo_adh}">${element.nombre_adh}</option>`;
                }
            });
        }
        $('#id_adh').empty().html(items);
    })
}

// tipo de codigo - nuevo antiguo 
function tipo_codigo() {
    $('.tipo_codigo').change(function (e) {
        e.preventDefault();
        var tipo_codigo = $(this).val();
        if (tipo_codigo == 1) {// codigo nuevo
            $('#codigo_antiguo').prop("disabled", true);
        } else if (tipo_codigo == 2) {// codigo viejo
            $('#codigo_antiguo').prop("disabled", false)
        }
    });
}

function enviar_solicitud_diseno() {
    $("#form_solicitud_codigo").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#enviar_solicitud_Cod').html();
        var formulario = $("#form_solicitud_codigo").serializeArray();
        var exepcion = ''
        var campos_valida = '';
        var datos_form = new FormData(document.getElementById('form_solicitud_codigo'))// esto permite traer por get cualcuer valor del fomulario
        var tipo_solicitud = datos_form.get("tipo_solicitud_check");
        if (tipo_solicitud == 1) { // solicitud codigo
            exepcion = ['gaf_cort', 'cant_tintas', 'terminados1', 'contacto', 'email_contacto', 'tipo_arte', 'cantidad', 'cantida_tintas_dis', 'tinta_1', 'tinta_2', 'tinta_3', 'tinta_4', 'tinta_5', 'tinta_6', 'tinta_7', 'tinta_8', 'tinta_9', 'tinta_10'];
            campos_valida = ['gaf_cort', 'cant_tintas', 'terminados1'];
        } else if (tipo_solicitud == 2) { // solicitud diseño
            exepcion = ['gaf_cort', 'cant_tintas', 'tipo_arte', 'terminados1'];
            campos_valida = [];
        }

        // validacion de campos vacios
        var valida2 = true
        campos_valida.forEach(element => {
            if (datos_form.get(element) == '' || datos_form.get(element) == null) {
                alertify.error('Ingresa el ' + element + ' para continuar');
                $('#' + element).focus();
                valida2 = false;
            }
        });

        var valida_form = validar_formulario(formulario, exepcion);
        formulario.push({ name: 'terminados', value: $('#terminados').val() }); // para guardar las opciones multiples
        if (valida_form && valida2) {
            btn_procesando('enviar_solicitud_Cod');
            $.ajax({
                url: `${PATH_NAME}/envio_solicitud_diseno`,
                type: "POST",
                data: formulario,
                success: function (res) {
                    alertify.alert('Solicitud de código', '¡Tu solicitud de código fue enviada correctamente!', 
                    function () { 
                        window.location.reload();     
                    });
                }
            })

        }
    })
}
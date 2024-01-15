$(document).ready(function () {
    icono_impresion();//boton de area de trabajo
    imprimir_trasavilidad();
    cajas_impresion();
    boton_imprime();
});
// funciones para el modal de impresion de remarcación
var imprimir_trasavilidad = function () {
    $('#tabla_embobinado tbody').on('click', 'button.imprimir_trasavilidad', function () {
        var data_item = $(this).attr('data-item');
        var data_m = $(this).attr('data-m');
        var item = JSON.parse(data_item);
        limpiar_formulario('formulario_remarcacion', 'input');
        $('.respu_consulta').empty().html('');
        $('.div_impresion').empty().html('');
        $('#lote').val(item.n_produccion);
        $('#cant_x').val(item.cant_x);
        var persona = $("#id_persona.id_persona").data("persona");
        if (persona != '') {// lo usamos para saber si es administrador o no
            $('#id_persona.id_persona').val(persona)
        }
        $('#ImpresionItemsModal').modal('toggle');
        $('#boton_imprime').attr('item', data_item);
    });
}

var boton_imprime = function () {
    $('#formulario_remarcacion').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#boton_imprime').html();
        var form = $(this).serializeArray();
        if (form[0].value == 2) {
            valida = validar_formulario(form);
        } else {
            var exception = ['caja'];
            valida = validar_formulario(form, exception);
        }
        if (valida) {
            btn_procesando('boton_imprime');
            var sistema_operativo = navigator.platform;
            var eswindow = sistema_operativo.includes('Win')
            //var id_maquinas = $('.imprimir_trasavilidad').attr('data-id-m'); Esto no funciona por que trae la maquina de item producir 
            var id_usuario = $('#sesion').val()
            var tamano = $('#tamano').val();
            $.ajax({
                type: "GET",
                url: `${PATH_NAME}/produccion/impresoras_marcacion`,
                data: { id_usuario: id_usuario, id_tamano: tamano, id_estacion_impre: $('#id_estacion_imp').val() },
                success: function (res) {
                    var resolucion = 200;
                    if (res == -1) {// no hay impresoras en base de datos
                        impresion_red = false
                    } else {
                        impresion_red = true
                        var datos_impresora = res['impresora']
                        resolucion = datos_impresora[0]['resolucion'];
                    }
                    var form = $('#formulario_remarcacion').serialize();
                    // form = form+'&id_persona='+res['persona'][0]['id'];
                    var data = $('#boton_imprime').attr('item');
                    // Solicitud de datos impresora 
                    $.post(`${PATH_NAME}/produccion/impresion_etiquetas_marcacion`,
                        {
                            resolucion: resolucion,
                            datos: data,
                            formulario: form,
                        },
                        function (respu) {// recuerda que debemos porner condiciones por proyecto OJO
                            if (IMPRESION_API === 1 && impresion_red == true) { // esta es la condicion para imprimir directo o por controlador depende del proyecto
                                var data_nombre = res['persona'][0]['nombres'] + ' ' + res['persona'][0]['apellidos']
                                var nombre = quitarTildes(data_nombre);
                                const fin_impresion = "^XA ^LL00 ^LS0 ^FT32,53^A0N,33,36^FH\^FD FIN DE IMPRESION!^FS ^FT32,100^A0N,33,36^FH\^FD  " + nombre + "^FS ^XZ";
                                const zplData = respu + fin_impresion;
                                const ip_impresora = datos_impresora[0]['ip'];
                                const xhr = new XMLHttpRequest();

                                xhr.open('POST', SERVIDOR_IMPRESION + '/print', true);// esta es la ip del servidor de desarrollo el cual servira de alojamiento de la api
                                xhr.setRequestHeader('Content-Type', 'application/json');

                                xhr.onreadystatechange = function () {
                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                        respuesta = JSON.parse(xhr.responseText);
                                        btn_procesando('boton_imprime', obj_inicial, 1);
                                        if (respuesta.status == 1) {
                                            $('#ImpresionItemsModal').modal('hide')
                                            alertify.success('Impresión enviada a la estación ' + datos_impresora[0]['id_estacion'])
                                        } else {
                                            alertify.alert('Error de impresión', '¡Verifica la conexión de la impresora!',
                                                function () {
                                                    window.open('https://' + ip_impresora, "_blank")// abrimos la impresora en otra pestaña para ver si esta conectada
                                                }
                                            );
                                        }
                                    }
                                };
                                xhr.onerror = function (e) {// esta funcion la utilizo cuando no tenemos respuesta del servidor; puede ser por dos razones; no esta corriendo la api o no se han aceptado los certificados autofirmados
                                    btn_procesando('boton_imprime', obj_inicial, 1);
                                    alertify.alert('Error de servidor', '¡Verifica el servidor de impresión!',
                                        function () {
                                            window.open(SERVIDOR_IMPRESION, "_blank")// abrimos el servidor en otra pestaña para que acepten el certificado SSL
                                        }
                                    );
                                };

                                const data = JSON.stringify({ zplData: zplData, ip: ip_impresora });
                                xhr.send(data);
                            } else if (impresion_red == false && eswindow == false && IMPRESION_API === 1) {
                                btn_procesando('boton_imprime', obj_inicial, 1);
                                alertify.alert('Alert Impresoras', '¡No hay impresoras configuradas!',
                                    function () { alertify.success('Ok'); });
                            } else {
                                btn_procesando('boton_imprime', obj_inicial, 1);
                                $('.div_impresion').empty().html(respu);
                                var mode = 'iframe'; //popup
                                var close = mode == "popup";
                                var options = { mode: mode, popClose: close };
                                $("div.div_impresion").printArea(options);
                                $('#ImpresionItemsModal').modal('hide')
                            }

                        });
                }
            });
        }

    });
}

var cajas_impresion = function () {
    $('#tamano').on('change', function () {
        if ($(this).val() == 2) {
            $('.cajass').css('display', '');
        } else {
            $('.cajass').css('display', 'none');
            $('.cajass').val('');
            $('#caja').val('');

        }
    });
}


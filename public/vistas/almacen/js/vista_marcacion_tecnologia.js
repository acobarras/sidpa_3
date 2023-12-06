$(document).ready(function () {
    select_2();
    consulta_select_codigo();
    impresion_etiqueta();
});

function consulta_select_codigo() {
    $('#consulta').on('click', function (e) {
        e.preventDefault();
        var obj_inicial = $('#consulta').html();
        var persona = $("#id_persona.id_persona").data("persona");
        if (persona != '') {// lo usamos para saber si es administrador o no
            $('#id_persona.id_persona').val(persona)
        }
        var nparte = $('#nparte').val();
        if (nparte != 0) {
            btn_procesando('consulta');
            $.ajax({
                type: "GET",
                url: `${PATH_NAME}/almacen/consulta_marcacion_bobinas`,
                data: { codigo: nparte },
                success: function (res) {
                    btn_procesando('consulta', obj_inicial, 1);
                    if ($('#contenedor').css('display') == 'none') {
                        $('#contenedor').toggle(500);
                    }
                    $('#nparte_form').val(res[0].codigo_producto)
                    $('#descripcion').val(res[0].descripcion_productos)
                    $('.div_impresion').empty().html();
                }
            })
        } else {
            alertify.error('¡Selecciona un codigo de tecnologia!');
            $('#nparte').focus();
        }
    })

}

function impresion_etiqueta() {
    $('#formulario_tecnologia').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#imprimir').html();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            btn_procesando('imprimir');
            var sistema_operativo = navigator.platform;
            var eswindow = sistema_operativo.includes('Win')
            var id_tamano = $('#tamano').val();
            $.ajax({
                type: "GET",
                url: `${PATH_NAME}/produccion/impresoras_marcacion`,
                data: { id_usuario: $('#sesion').val(), id_tamano: id_tamano },
                success: function (res) {
                    var resolucion = 200;// para eticaribe es de 300 OJO
                    if (res == -1) {// no hay impresoras en base de datos
                        impresion_red = false
                    } else {
                        impresion_red = true
                        var datos_impresora = res['impresora'];
                        resolucion = datos_impresora[0]['resolucion'];
                    }
                    var form = $('#formulario_tecnologia').serialize();
                    $.post(`${PATH_NAME}/almacen/impresoras_marcacion_tecnologia`,
                        {
                            resolucion: resolucion,
                            formulario: form,
                        },
                        function (respu) {
                            if (IMPRESION_API === 1 && impresion_red == true) { // esta es la condicion para imprimir directo o por controlador depende del proyecto

                                const zplData = respu;
                                const ip_impresora = datos_impresora[0]['ip'];
                                const xhr = new XMLHttpRequest();

                                xhr.open('POST', SERVIDOR_IMPRESION + '/print', true);// esta es la ip del servidor de desarrollo el cual servira de alojamiento de la api
                                xhr.setRequestHeader('Content-Type', 'application/json');

                                xhr.onreadystatechange = function () {
                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                        respuesta = JSON.parse(xhr.responseText);
                                        if (respuesta.status == 1) {
                                            $('#contenedor').css('display', 'none');
                                            alertify.success('Impresión enviada a la estación ' + datos_impresora[0]['id_estacion'])
                                            btn_procesando('imprimir', obj_inicial, 1);
                                        } else {
                                            alertify.alert('Error de impresión', '¡Verifica la conexión de la impresora!',
                                                function () {
                                                    window.open('https://' + ip_impresora, "_blank")// abrimos la impresora en otra pestaña para ver si esta conectada
                                                    btn_procesando('imprimir', obj_inicial, 1);
                                                }
                                            );
                                        }
                                    }
                                };
                                xhr.onerror = function (e) {// esta funcion la utilizo cuando no tenemos respuesta del servidor; puede ser por dos razones; no esta corriendo la api o no se han aceptado los certificados autofirmados
                                    alertify.alert('Error de servidor', '¡Verifica el servidor de impresión!',
                                        function () {
                                            window.open(SERVIDOR_IMPRESION, "_blank")// abrimos el servidor en otra pestaña para que acepten el certificado SSL
                                            btn_procesando('imprimir', obj_inicial, 1);
                                        }
                                    );
                                };

                                const data = JSON.stringify({ zplData: zplData, ip: ip_impresora });
                                xhr.send(data);
                            } else if (impresion_red == false && eswindow == false && IMPRESION_API === 1) {
                                alertify.alert('Alerta Impresoras', '¡No hay impresoras configuradas para esta área!',
                                    function () { alertify.success(''); });
                                btn_procesando('imprimir', obj_inicial, 1);
                            } else {
                                $('.div_impresion').empty().html(respu);
                                var mode = 'iframe'; //popup
                                var close = mode == "popup";
                                var options = { mode: mode, popClose: close };
                                $("div.div_impresion").printArea(options);
                                btn_procesando('imprimir', obj_inicial, 1);
                            }
                            $('#operario_digitado').trigger("change")
                            $('#contenedor').css('display', 'none');
                            limpiar_formulario('formulario_tecnologia', 'input' );
                        }
                    )
                }
            })
        }
    })
}
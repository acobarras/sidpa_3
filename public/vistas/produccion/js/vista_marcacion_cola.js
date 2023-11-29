$(document).ready(function () {
    consulta_op();
    impresion_etiqueta();
    consultar_operario();
});

function consulta_op() {// falta poner el boton de procesando 

    $('#consulta_op').on('click', function (e) {
        e.preventDefault();
        var obj_inicial = $('#consulta_op').html();
        var persona = $("#id_persona.id_persona").data("persona");
        if (persona != '') {// lo usamos para saber si es administrador o no
            $('#id_persona.id_persona').val(persona)
        }
        var op = $('#op').val();
        var valida = (op != '');
        if (!valida) {
            alertify.error('¡Ingresa un numero de orden de producción!');
            $('#op').focus();
        } else {
            btn_procesando('consulta_op');
            $.ajax({
                type: "GET",
                url: `${PATH_NAME}/produccion/consulta_marcacion_cola`,
                data: { op },
                success: function (res) {
                    btn_procesando('consulta_op', obj_inicial, 1);
                    if (res.status) {
                        if ($('#contenedor').css('display') == 'none') {
                            $('#contenedor').toggle(500);
                        }
                        $('#codigo').val(res.datos[0].material_op)
                        $('#ancho').val(res.datos[0].ancho_material)
                        $('#descripcion').val(res.datos[0].descripcion_productos)
                        $('.div_impresion').empty().html();
                    } else {
                        if ($('#contenedor').css('display') == 'block') {
                            $('#contenedor').toggle(500);
                            limpiar_formulario('formulario_etiqueta', 'input');
                            $('.div_impresion').empty().html();
                        }
                        alertify.error(res.msg);
                        $('#op').focus();
                    }
                }
            })
        }

    })
}

function consultar_operario() {
    $('#operario_digitado').on('change', function () {
        var documento = $(this).val();
        var persona = $("#id_persona.id_persona").data("persona");
        if (persona != '') {// lo usamos para saber si es administrador o no
            $('#id_persona').val(persona)
        } else {
            $.ajax({
                url: `${PATH_NAME}/produccion/validar_operario`,
                type: "POST",
                data: { documento },
                success: function (respu) {
                    if (respu != '') {
                        var items = `<h4> Nombre :
                        <span style="color: blue">${respu[0].nombres} ${respu[0].apellidos}</span>
                        </h4>
                        `;
                        var atributo = false;
                        var id_persona = respu[0].id_persona;
                    } else {
                        var items = `<h4>
                        <span style="color: red">El código del operario es incorrecto !!</span>
                        </h4>`;
                        var atributo = true;
                        var id_persona = 0;
                    }
                    $('.respu_consulta').empty().html(items);
                    $('#imprimir').prop('disabled', atributo);
                    $('#id_persona').val(id_persona);
                }
            })
        }
    })
}

function impresion_etiqueta() {
    $('#formulario_etiqueta').submit(function (e) {
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
                    var form = $('#formulario_etiqueta').serialize();
                    $.post(`${PATH_NAME}/produccion/impresoras_marcacion_colas`,
                        {
                            resolucion: resolucion,
                            formulario: form,
                            tipo: '1',//1 para cola, 2 remarcacion
                            op: $('#op').val(),
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
                            limpiar_formulario('formulario_etiqueta', 'input');
                        }
                    )
                }
            })
        }
    })
}

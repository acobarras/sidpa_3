$(document).ready(function () {
    icono_impresion();//boton de area de trabajo
    select_2();
    consultar_pedido();
    mostrar_formulario();
    boton_imprime();
    cajas_impresion();
    consulta_operario();
    // operarioGlobal = [];
});


var consulta_operario = function () {
    $('.codigo_operario').on('change', function () {
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
    });
}

var consultar_pedido = function () {
    $('.num_pedio').on('blur', function () {
        var num_pedido = $(this).val();
        $.ajax({
            type: "POST",
            url: `${PATH_NAME}/produccion/consultar_items_pedido_impresion`,
            data: { num_pedido },
            success: function (response) {
                var items = '<option value="0"></option>';
                if (response != -1) {
                    response.forEach(element => {
                        items += `
                            <option value='${JSON.stringify(element)}'>Item ${element.item} Codigo ${element.codigo}</option>                   
                        `;
                    });
                    $('#envio').prop('disabled', false);
                } else {
                    items = `<option value="0"></option>`;
                    $('#envio').prop('disabled', true);
                    $('#formulario_remarcacion').css('display', 'none');
                }
                $('#slect_items').empty().html(items);
            }
        });
    });

}

//  funcion para mostrar el formulario
var mostrar_formulario = function () {
    $('#envio').on('click', function () {
        limpiar_formulario('formulario_remarcacion', 'input');
        $('.respu_consulta').empty().html('');
        $('.div_impresion').empty().html('');
        $('#formulario_remarcacion').css('display', '');
        $('.mensaje_data_proo').empty().html('');
        $('.codigo_operario').val('');
        var valor = $('#slect_items').val();
        $('#boton_imprime').attr('item', valor);
        var item = JSON.parse(valor);
        $('#cant_x').val(item.cant_x);
        var persona = $("#id_persona.id_persona").data("persona");
        if (persona != '') {// lo usamos para saber si es administrador o no
            $('#id_persona.id_persona').val(persona)
        }
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
            var id_usuario = $('#sesion').val()
            var tamano = $('#tamano').val();
            var sistema_operativo = navigator.platform;
            var eswindow = sistema_operativo.includes('Win')
            $.ajax({
                type: "GET",
                url: `${PATH_NAME}/produccion/impresoras_marcacion`,
                data: { id_usuario: id_usuario, id_tamano: tamano, so: sistema_operativo, id_estacion_impre: $('#id_estacion_imp').val() },
                success: function (res) {
                    var resolucion = 200;
                    if (res == -1) {// no hay impresoras en base de datos
                        impresion_red = false
                    } else {
                        impresion_red = true
                        var datos_impresora = res['impresora'];
                        resolucion = datos_impresora[0]['resolucion'];
                    }
                    var form = $('#formulario_remarcacion').serialize();
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
                                            $('#formulario_remarcacion').css('display', 'none');
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
                                alertify.alert('Alerta Impresoras', '¡No hay impresoras configuradas para esta área!',
                                    function () { alertify.success(''); });
                            } else {
                                $('.div_impresion').empty().html(respu);
                                var mode = 'iframe'; //popup
                                var close = mode == "popup";
                                var options = { mode: mode, popClose: close };
                                $("div.div_impresion").printArea(options);
                                $('#formulario_remarcacion').css('display', 'none');
                            }
                            btn_procesando('boton_imprime', obj_inicial, 1);
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




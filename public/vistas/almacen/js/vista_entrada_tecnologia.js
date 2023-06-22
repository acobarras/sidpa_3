$(document).ready(function () {
    select_2();
    crear_tecnologia();
    elimina_espacio('codigo_producto', 'respu_codigo_tecno');
    valida_codigo_tecno();
    nueva_ubicacion();
});


var crear_tecnologia = function () {

    $('#tecnologia').submit(function (e) {
        e.preventDefault(); // Previene la recarga del navegador 
        var obj_inicial = $('#btn_ingresar_tec').html();//obtiene el html del boton
        btn_procesando('btn_ingresar_tec');// funcion para que el boton se muestre procesando
        var form = $(this).serializeArray();// covierte los datos del formulario en unarray
        var valida = validar_formulario(form);//valida campos vacios del formulario
        var descrip = $('#btn_ingresar_tec').attr('data-valida');//obtiene el valor dado en la funcion valida_codigo_tecno
        if (valida) {// verifica que la variable valida se encuentre true
            if (descrip == 'true') {// valida que descripcion sea true
                $.ajax({// declaracion de ajax
                    url: `${PATH_NAME}/almacen/registrar_tecnologia`,// ruta a la que va hacer dirigido el ajax
                    type: 'POST',//tipo de peticion 
                    data: form,//datos que le estamos enviando 
                    success: function (res) {// trae la respuesta del controlador
                        if (res.status) {//verifica que el estado venga venga en true
                            alertify.success('Se ingreso correctamente esta tecnologia.')//mensaje al usuario
                            btn_procesando('btn_ingresar_tec', obj_inicial, 1);//volver el boton a su estado inicial
                            limpiar_formulario('tecnologia', 'select')//limpiar select
                            $("#tecnologia")[0].reset();//limpiar formulario

                        } else {
                            alertify.success('No se pudo ingresar esta tecnologia.')//mensaje de error al usuario
                            btn_procesando('btn_ingresar_tec', obj_inicial, 1);//volver el boton a su estado inicial
                        }
                    }
                });
            } else {
                alertify.error('El codigo del producto no existe');//mensaje de error por que no se encontro producto
                btn_procesando('btn_ingresar_tec', obj_inicial, 1);//volver el boton a su estado inicial
            }
        } else {
            btn_procesando('btn_ingresar_tec', obj_inicial, 1);//volver el boton a su estado inicial
        }
    });
}



var valida_codigo_tecno = function () {//funcion para validar el codigo del producto
    $('#codigo_producto').on('change', function () {//se ejecuta cuando se sale del campo especificado
        var codigo = $(this).val();//obtiene el valor del elemento

        $.ajax({ //declaracion del ajax
            "url": `${PATH_NAME}/almacen/validar_codigo_tecno`,//ruta a la q va hacer dirigido el ajax
            "type": 'POST',//tipo de peticion
            "data": { codigo},//dato que le estamos enviando
            "success": function (respu) {//trae la respuesta del controlador 
                if (respu.estado) {//verifica el estado venga el true
                    if (respu.id_tipo_articulo == 1 || respu.id_tipo_articulo == 4) {
                        $('#respuesta').empty().html("Este producto no pertenece a este modulo.");
                        $('#codigo_producto').focus();//posiciona en el elemento
                        $('#btn_ingresar_tec').attr('data-valida', false);//se le da el valor de respuesta al elemento
                    } else {
                        $('#id_producto').val(respu.id_producto)//se le da el valor de respuesta al elemento
                        $('#respuesta').empty().html(respu.mensaje);//se le da el valor de respuesta al elemento
                        $('#btn_ingresar_tec').attr('data-valida', true);//se le da el valor de respuesta al elemento
                    }
                } else {
                    $('#respuesta').empty().html(respu.mensaje);//se le da el valor de respuesta al elemento
                    $('#codigo_producto').focus();//posiciona en el elemento
                    $('#btn_ingresar_tec').attr('data-valida', false);//se le da el valor de respuesta al elemento
                }
            }
        });
    });
}
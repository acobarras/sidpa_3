$(document).ready(function () {
    select_2();
    cargar_tabla_productos();
    editar_etiqueta();
    elimina_espacio('codigo_producto', 'span_codigo_CE');
    elimina_espacio('codigo_producto_tecno', 'span_codigo_TE');
    elimina_espacio('codigo_producto_bobina', 'span_codigo_MP');
    crear_etiqueta();
    crear_tecnologia();
    crear_materia_prima();
    costo_etiq();
    costo_eti_avance();
    calculo_precios_tecno();
    crear_codigo_etiqueta();
});

var cargar_tabla_productos = function () {
    var table = $("#tabla_productos").DataTable({
        "ajax": `${PATH_NAME}/configuracion/consultar_productos`,
        "columns": [
            { "data": "id_productos" },
            { "data": "codigo_producto" },
            { "data": "nombre_articulo" },
            { "data": "tamano" },
            { "data": "descripcion_productos" },
            {
                "data": "estado_producto",
                "searchable": false,
                "orderable": false,
                "render": function (data, type, row) {

                    if (row["estado_producto"] == 1) {
                        return '<center><button class="btn btn-link estado" value="1"><i style="font-size:25px" class="fa fa-toggle-on text-success"></i></button></center>';
                    } else {

                        return '<center><button class="btn btn-link estado" value="0"><i style="font-size:25px" class="fa fa-toggle-off text-danger"></i></button></center>';

                    }
                }

            },
            { "defaultContent": '<button type="button" class="btn btn-primary agregar_campos" title="Consultar/Modificar" data-bs-toggle="modal" data-bs-target="#ModalProducto"><i class="fa fa-edit"></i></button>' }

        ],
    });
    cambiar_estado("#tabla_productos tbody", table);
    obtener_data_editar("#tabla_productos tbody", table);
}

var cambiar_estado = function (tbody, table) {
    $(tbody).on("click", "button.estado", function () {
        var data = table.row($(this).parents("tr")).data();
        var valor_btn = $(this).val();
        if (valor_btn == 1) {
            //inactivar usuario
            envio = { 'id_productos': data.id_productos, 'estado_producto': 0 };
        } else {
            //activar usuario
            envio = { 'id_productos': data.id_productos, 'estado_producto': 1 };
        }
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_estados_producto`,
            "type": 'POST',
            "data": envio,
            "success": function (respuesta) {
                $("#tabla_productos").DataTable().ajax.reload();
                alertify.success('Estado cambiado Correctamente');
            }
        });
    });
}

var obtener_data_editar = function (tbody, table) {
    $(tbody).on('click', 'button.agregar_campos', function () {
        var data = table.row($(this).parents("tr")).data();
        rellenar_formulario(data);
        $('#modificar_producto').attr('data-id', data.id_productos);
        $('#id_clase_articulo_modifi').val(data.id_clase_articulo);
    });
}

var editar_etiqueta = function () {
    $('#form_modificar_producto').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#modificar_producto').html();
        var form = $(this).serialize();
        if ($('#moneda_producto_modifi').val() == 0) {
            alertify.error('La Moneda se requiere para continuar');
            return;
        }
        var id_producto = $('#modificar_producto').attr('data-id');
        var envio = {
            'form': form,
            'id': id_producto
        };
        btn_procesando('modificar_producto');
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_producto`,
            "type": 'POST',
            "data": envio,
            "success": function (respu) {
                if (respu) {
                    $("#tabla_productos").DataTable().ajax.reload(function () {
                        alertify.success('Modificacion realizada corectamente');
                        $("#ModalProducto").modal("hide");
                        btn_procesando('modificar_producto', obj_inicial, 1);
                    });
                } else {
                    alertify.error('Error inesperado');
                    btn_procesando('modificar_producto', obj_inicial, 1);
                }
            }
        });
    });
}

var crear_etiqueta = function () {
    $("#form_crear_etiqueta").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_etiqueta').html();
        var valor_input = $('#paso_etiqueta').val();
        var id_adh = $('#id_adh').val();
        var id_usuario = $('#id_usuario').val();
        var form = $(this).serializeArray();
        var ecepcion = ['costo', 'precio1', 'precio2', 'precio3', 'consumo'];
        valida = validar_formulario(form, ecepcion);
        if (valida) {
            btn_procesando('crear_etiqueta');
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_producto`,
                "type": 'POST',
                "data": form,
                "success": function (respuesta) {
                    if (respuesta.status) {
                        alertify.success(`Datos ingresados corretamente la posicion insetada es ${respuesta.id}`);
                        btn_procesando('crear_etiqueta', obj_inicial, 1);
                        limpiar_formulario('form_crear_etiqueta', 'input');
                        limpiar_formulario('form_crear_etiqueta', 'textarea');
                        $("#tabla_productos").DataTable().ajax.reload();
                    } else {
                        alertify.error(`Error el codigo ya se encuentra creado.`);
                        btn_procesando('crear_etiqueta', obj_inicial, 1);
                    }
                    $('#paso_etiqueta').val(valor_input);
                    $('#id_adh').val(id_adh);
                    $('#id_usuario').val(id_usuario);
                }
            });
        }
    });
}

var crear_tecnologia = function () {
    $("#form_crear_tecnologia").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_tecnlogia').html();
        var valor_input = $('#paso_tecnologia').val();
        var id_adh = $('#id_adh_tecno').val();
        var id_usuario = $('#id_usuario_tecno').val();
        var form = $(this).serializeArray();
        var excepcion = ['consumo'];
        valida = validar_formulario(form, excepcion);
        if (valida) {
            btn_procesando('crear_tecnlogia');
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_producto`,
                "type": 'POST',
                "data": form,
                "success": function (respuesta) {
                    if (respuesta.status) {
                        alertify.success(`Datos ingresados corretamente la posicion insetada es ${respuesta.id}`);
                        btn_procesando('crear_tecnlogia', obj_inicial, 1);
                        limpiar_formulario('form_crear_tecnologia', 'select');
                        limpiar_formulario('form_crear_tecnologia', 'input');
                        limpiar_formulario('form_crear_tecnologia', 'textarea');
                        $("#tabla_productos").DataTable().ajax.reload();
                    } else {
                        alertify.error(`Error al insertar`);
                        btn_procesando('crear_tecnlogia', obj_inicial, 1);
                    }
                    $('#paso_tecnologia').val(valor_input);
                    $('#id_adh_tecno').val(id_adh);
                    $('#id_usuario_tecno').val(id_usuario);
                }
            });
        }
    });
}

var crear_materia_prima = function () {
    $("#form_crear_materiaP").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_materiaP').html();
        var valor_input = $('#paso_materia').val();
        var id_usuario = $('#id_usuario_bobina').val();
        var form = $(this).serializeArray();
        var excepcion = ['consumo'];
        valida = validar_formulario(form, excepcion);
        if (valida) {
            btn_procesando('crear_materiaP');
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_producto`,
                "type": 'POST',
                "data": form,
                "success": function (respuesta) {
                    if (respuesta.status) {
                        alertify.success(`Datos ingresados corretamente la posicion insetada es ${respuesta.id}`);
                        btn_procesando('crear_materiaP', obj_inicial, 1);
                        limpiar_formulario('form_crear_materiaP', 'select');
                        limpiar_formulario('form_crear_materiaP', 'input');
                        limpiar_formulario('form_crear_materiaP', 'textarea');
                        $("#tabla_productos").DataTable().ajax.reload();
                    } else {
                        alertify.error(`Error al insertar`);
                        btn_procesando('crear_materiaP', obj_inicial, 1);
                    }
                    $('#paso_materia').val(valor_input);
                    $('#id_usuario_bobina').val(id_usuario);
                }
            });
        }
    });
}

var costo_etiq = function () {
    $('#codigo_producto').on('change', function () {
        var codigo = $(this).val();
        var avance = 0;
        $.ajax({
            "url": `${PATH_NAME}/configuracion/valida_precio_codigo`,
            "type": 'POST',
            "data": { codigo, avance },
            "success": function (respu) {
                if (respu.status == -1) {
                    $('#codigo_producto').val('');
                    $('#codigo_producto').focus();
                    alertify.error('No hay precio de materia prima para poder crear el codigo');
                } else {
                    $('#tamano').val(respu.tamano);
                    $('#avance').val(respu.avance);
                    $('#magnetico').val(respu.magnetico);
                    $('#costo').val(respu.costo);
                    $('#precio1').val(respu.precio1);
                    $('#precio2').val(respu.precio2);
                    $('#precio3').val(respu.precio3);
                }
            }
        });
    });
}

var costo_eti_avance = function () {
    $('#avance').on('change', function () {
        var codigo = $('#codigo_producto').val();
        if (codigo == 0 || codigo == '') {
            return;
        }
        var avance = $(this).val();
        $.ajax({
            "url": `${PATH_NAME}/configuracion/valida_precio_codigo`,
            "type": 'POST',
            "data": { codigo, avance },
            "success": function (respu) {
                if (respu.status == -1) {
                    $('#codigo_producto').val('');
                    $('#codigo_producto').focus();
                    alertify.error('No hay precio de materia prima para poder crear el codigo');
                } else {
                    $('#costo').val(respu.costo);
                    $('#magnetico').val('');
                    $('#precio1').val(respu.precio1);
                    $('#precio2').val(respu.precio2);
                    $('#precio3').val(respu.precio3);
                }
            }
        });
    });
}

function calculo_precios_tecno() {
    $('.precios_tecno').on('change', function () {
        var precio = $(this).val();
        var precio1 = parseFloat(precio) / .8;
        var precio2 = parseFloat(precio) / .78;
        var precio3 = parseFloat(precio) / .76;
        $('#precio1_tecno').val(redondear(precio1, 2));
        $('#precio2_tecno').val(redondear(precio2, 2));
        $('#precio3_tecno').val(redondear(precio3, 2));
    });
    // Al modificar el articulo
    $('.precios_tecno_modifi').on('change', function () {
        var id_clase_articulo = $('#id_clase_articulo_modifi').val();
        if (id_clase_articulo == 3) {
            var precio_modifi = $(this).val();
            var precio1_modifi = parseFloat(precio_modifi) / .8;
            var precio2_modifi = parseFloat(precio_modifi) / .78;
            var precio3_modifi = parseFloat(precio_modifi) / .76;
            $('#precio1_modifi').val(redondear(precio1_modifi, 2));
            $('#precio2_modifi').val(redondear(precio2_modifi, 2));
            $('#precio3_modifi').val(redondear(precio3_modifi, 2));
        }
    });
}

var crear_codigo_etiqueta = function () {
    $('#crea_cod_etiq').click(function () {
        var data = 0;
        $.post(`${PATH_NAME}/configuracion/vista_crea_codigo_etiqueta`, {
            datos: data
        },).done(function (respu) {
            $('#respuesta_codigo').empty().html(respu);
            $("#Modal_crea_codigo").modal("show");
        });
    });
}

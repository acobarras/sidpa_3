$(document).ready(function () {
    consulta_codigos();
    cerrar_solicitud();
    creacion_producto();
});

function consulta_codigos() {
    var table = $("#tb_solicitudes").DataTable({
        "ajax": `${PATH_NAME}/diseno/consulta_solicitudes_codigo`,
        "columns": [
            { "data": "id_solicitud" },
            { "data": "nit" },
            { "data": "nombre_empresa" },
            { "data": "asesor" },
            {
                "render": function (data, type, row) {
                    return `${row.ancho}X${row.alto}`
                }
            },// poner tamaño concatenado
            {
                "render": function (data, type, row) {
                    if (row.tipo_producto == 1) {
                        return `Etiqueta`
                    } else {
                        return `Hoja`
                    }
                }
            }, // tipo de etiqueta
            { "data": "nombre_forma" },
            { "data": "nombre_material" },
            { "data": "nombre_adh" },
            { "data": "cavidades" },
            { "data": "cantidad_tintas" },
            { "data": "terminados" },
            { "data": "grafe.nombre" },
            { "data": "observaciones" },
            {
                "render": function (data, type, row) {
                    return `
                    <label for="codigo_final" class="form-label fw-bold">Código final:</label>
                    <input class="form-control" type="text" id="codigo_final" name="codigo_final" placeholder="código creado">`
                }
            }, //esto es un inpuit para diseño
            {
                "render": function (data, type, row) {
                    return `<center>
                                <button type='button' title='Cerrar solicitud' class='btn btn-success btn-circle cerrar_solicitud' >
                                    <span class="fas fa-check"></span>
                                </button>
                                <button type='button' id='ir_producto' title='Ir a crear producto' class='btn btn-info btn-circle ir_producto' >
                                    <span class="fas fa-upload"></span>
                                </button>
                            <center>`

                }
            }// en este boton de opciones vamos a enviar la data por el local storage (Y) para verla en la vista de crear producto
        ]
    })
}
// funcion para ir a crear producto
function creacion_producto() {
    $('#tb_solicitudes tbody').on("click", "button.ir_producto", function (e) {
        e.preventDefault();
        var data = $('#tb_solicitudes').DataTable().row($(this).parents("tr")).data();
        var codigo = $(this).parents("tr").find('#codigo_final').val();
        var btn = $(this).parents("tr").find('.ir_producto').attr('disabled', 'true');// lo deactivamos para crear el codigo en la ota vista 
        var datos_codigo = {
            id_solicitud: data.id_solicitud,
            codigo: codigo,
            tamano: data.ancho + 'X' + data.alto,
            estado: 1,
        }
        datos_codigo = JSON.stringify(datos_codigo)
        localStorage.setItem("codigo", datos_codigo);
        window.open(`${PATH_NAME}/vista_crear_productos?cod=${data.id_solicitud}`, '_blank');
    })
}
// funcion para cerrar casos 
function cerrar_solicitud() {
    $('#tb_solicitudes tbody').on("click", "button.cerrar_solicitud", function (e) {
        e.preventDefault();
        var data = $('#tb_solicitudes').DataTable().row($(this).parents("tr")).data();
        var codigo = $(this).parents("tr").find('#codigo_final').val();// esto trae el valor del select tinta 
        var prueba = $(this).parents("tr").find("#ir_producto");
        var boton = $(this).parents("tr").find(".cerrar_solicitud")
        if (codigo == '') {
            alertify.error('Ingresa el codigo para cerrar el caso');
            $(this).parents("tr").find('#codigo_final').focus();
            return
        } else {
            boton.attr('disabled', 'true');
            boton.html('<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                "url": `${PATH_NAME}/diseno/consulta_estado_solicitud`,
                "type": 'POST',
                "data": { codigo: codigo, data: data },
                "success": function (res) {
                    console.log(res);
                    if (res.status == -1) {
                        alertify.error(res.msg);
                        $("#tb_solicitudes").DataTable().ajax.reload();
                    } else if (res.status == -2) {
                        alertify.alert('Cierre de solicitud', res.msg,
                            function () {
                                prueba.click();
                            }
                        );
                    } else {
                        alertify.alert('Cierre de solicitud', res.msg,
                            function () {
                                $.ajax({
                                    "url": `${PATH_NAME}/diseno/cirre_solicitud_cod`,
                                    "type": 'POST',
                                    "data": { codigo: codigo, id_solicitud: data.id_solicitud },
                                    "success": function (res) {
                                        alertify.alert('Cierre de solicitud', res.msg,
                                            function () {
                                                $("#tb_solicitudes").DataTable().ajax.reload();
                                            }
                                        );
                                    }
                                })
                            }
                        );
                    }
                }
            })
        }
    })
}

$(document).ready(function () {
    consulta_codigos();
    cerrar_solicitud();
    creacion_producto();
    rechazo_solicitud();
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
                    if (row.tipo_codigo == 1) { // codigo nuevo
                        return `<p>Nuevo</p>`
                    } else {
                        return `<p>Actualización</p>`
                    }
                }
            },
            {
                "render": function (data, type, row) {
                    if (row.codigo_antiguo == null) { // codigo nuevo
                        return `<p>No aplica</p>`
                    } else {
                        return row.codigo_antiguo
                    }
                }
            },
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
            // { "data": "cavidades" },
            { "data": "cantidad_tintas" },
            { "data": "terminados" },
            { "data": "grafe.nombre" },
            {
                "render": function (data, type, row) {
                    var metros_lineales = ((row.cantidad * (parseFloat(row.alto) + 4)) / (row.cavidades * 1000));
                    return `<p><b>Cavidades:</b> ${row.cavidades}  <br><b>Cantidad:</b> ${row.cantidad} <br>
                    <b>Precio:</b> ${$.fn.dataTable.render.number('.', ',', 2, '$ ').display(row.precio)}  <br>
                    <b>M-Lineales:</b> ${metros_lineales} Aprox.<br>
                    </p>`
                }
            },
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
                    var res = ` <center>
                                    <button type='button' title='Cerrar solicitud' class='btn btn-success btn-circle cerrar_solicitud' >
                                        <span class="fas fa-check"></span>
                                    </button>
                                    <button type='button' title='Rechazar solicitud' class='btn btn-danger btn-circle rechazar_solicitud' >
                                        <span class="fas fa-times"></span>
                                    </button>
                                <center>`
                    if (row.tipo_codigo == 1) {// codigo nuevo
                        res += `<center>
                                    <button type='button' id='ir_producto' title='Ir a crear producto' class='btn btn-info btn-circle ir_producto' >
                                        <span class="fas fa-upload"></span>
                                    </button>
                                <center>`
                    }
                    return res;
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
                                    "data": { codigo: codigo, id_solicitud: data.id_solicitud, cierre: 'cierre' },
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

// funcion para rechazar solicidudes
function rechazo_solicitud() {// falta poner a cargar el boton de rechazo y recargar
    $('#tb_solicitudes tbody').on("click", "button.rechazar_solicitud", function (e) {
        e.preventDefault();
        var data = $('#tb_solicitudes').DataTable().row($(this).parents("tr")).data();
        var boton = $(this).parents("tr").find(".rechazar_solicitud")
        alertify.confirm('Rechazar solicitud', '¿Desea continuar con el rechazo de esta solicitud?<br></br></div><label for="observaciones"><b>Motivo rechazo solicitud:</b></label><br> <textarea name="observaciones" id="observaciones" class="col-12" rows="7"></textarea><br><br></br>',
            function () {
                var observaciones = $('#observaciones').val();
                if (observaciones != '') {
                    boton.attr('disabled', 'true');
                    boton.html('<span class="spinner-border spinner-border-sm"></span>');
                    $.ajax({
                        "url": `${PATH_NAME}/diseno/cirre_solicitud_cod`,
                        "type": 'POST',
                        "data": { codigo: 'rechazada', id_solicitud: data.id_solicitud, cierre: 'rechazo', observaciones: observaciones },
                        "success": function (res) {
                            alertify.alert('Cierre de solicitud', res.msg,
                                function () {
                                    $('#observaciones').val('');
                                    $("#tb_solicitudes").DataTable().ajax.reload();
                                }
                            );
                        }
                    })
                } else {
                    alertify.error('¡Ingresa un motivo para rechazar la solicitud!');
                }
            },
            function () {
                alertify.error('Operación cancelada');
            });
    })
}

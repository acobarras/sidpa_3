$(document).ready(function () {
    alertify.set('notifier', 'position', 'bottom-left');
    tabla_mis_entregas();
    enviar_datos_modal();
    enviar_entrega();
    imagen();
});
GLOBAL_DATA = [];

const IDPERSONA = $('#id_persona').val();
const ROLL = $('#roll').val();

var tabla_mis_entregas = function () {
    var roll = $('#roll').val();
    if (isMobile.any() && (roll != 1 || roll != 10)) {
        var columnas = [4, 6, 8];
    } else {
        var columnas = [];
    }
    var table = $('#table_mis_entregas').DataTable({
        ajax: {
            "url": `${PATH_NAME}/entregas/consulta_mis_entregas`,
            "type": "POST",
            "data": { IDPERSONA },
        },
        "columnDefs": [
            { "visible": false, "targets": columnas }
        ],
        columns: [
            {
                "data": "option", render: function (data, type, row) {
                    if (row.id_tipo_documento == 10) {
                        boton = `
                            <button type="button" data-id="3" class="btn btn-primary diligencia">
                                <span class="fas fa-check"></span>
                            </button> 
                            <button type="button" data-id="4" class="btn btn-warning diligencia">
                                <span class="fas fa-times"></span>
                            </button>
                        `;
                    } else {
                        if (row.id_tipo_documento == 8 || row.id_tipo_documento == 9 || row.id_tipo_documento == 6) {
                            if (row.estado == 6) {
                                var boton = `<button type="button" data-id="4" class="reporte btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalImagen">
                                <span class="fas fa-check"></span>
                                </button> `;
                            } else {
                                var boton = `<button type="button" data-id="1" class="reporte btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalImagen">
                                <span class="fas fa-check-double"></span>
                                </button> `;
                            }
                        } else {
                            var boton = `<button type="button" data-id="2" class="reporte view btn btn-warning" data-bs-toggle="modal" data-bs-target="#ModalImagen">
                            <span class="fas fa-check"></span>
                            </button> `;
                        }
                        boton += `<button type="button" class="motivo btn btn-danger" data-bs-toggle="modal" data-bs-target="#entregaModal" aria-expanded="false">
                        <span class="fas fa-times"></span>
                        </button>`;
                    }
                    if (ROLL == 10 || ROLL == 1) {
                        if (row.orden_ruta != 'N/A') {
                            boton += `  
                        <button type="button" class="orden_ruta btn btn-secondary" data-bs-toggle="modal" data-bs-target="#orden_rutaModal" aria-expanded="false">
                            <span class="fas fa-route"></span>
                        </button> `
                        }
                    }
                    return boton;
                }
            },
            { "data": "num_pedido" },
            { "data": "documento" },
            { "data": "nombre_empresa" },
            { "data": "orden_ruta" },
            { "data": "ruta" },
            { "data": "direccion" },
            { "data": "transportador" },
            { "data": "forma_pago" },
            { "data": "nombre_estado" },

        ],
        // drawCallback: function (settings) {
        //     var api = this.api();
        //     var firstRow = api.row(0);
        //     // Ocultar los botones de todas las filas
        //     $('tr .diligencia').hide();
        //     $('tr .reporte').hide();
        //     $('tr .motivo').hide();

        //     if (ROLL == 10 || ROLL == 1) {
        //         $(this).find('.diligencia').show();
        //         $(this).find('.reporte').show();
        //         $(this).find('.motivo').show();
        //     }

        //     $('td', firstRow.node()).each(function () {
        //         // Mostrar los botones solo en la primera fila
        //         $(this).find('.diligencia').show();
        //         $(this).find('.reporte').show();
        //         $(this).find('.motivo').show();

        //         if (ROLL == 10 || ROLL == 1) {
        //             $(this).find('.diligencia').show();
        //             $(this).find('.reporte').show();
        //             $(this).find('.motivo').show();
        //         }
        //     });
        // }
    });
    reporte_entregas("#table_mis_entregas tbody", table);
    motivos_entrega("#table_mis_entregas tbody", table);
    diligencia_alterna("#table_mis_entregas tbody", table)
    orden_ruta();
    enviar_orden();
}

var orden_ruta = function () {
    $("#table_mis_entregas tbody").on("click", "button.orden_ruta", function () {
        var data = $('#table_mis_entregas').DataTable().row($(this).parents("tr")).data();
        $('#envio_orden_ruta').attr('data-id', JSON.stringify(data));
    });
}

var enviar_orden = function () {
    $('#envio_orden_ruta').on('click', function () {
        var data_fila = JSON.parse($(this).attr('data-id'));
        var numero = $('#orden_ruta').val();
        var obj_inicial = $('#envio_orden_ruta').html();
        btn_procesando('envio_orden_ruta', obj_inicial);
        $.ajax({
            url: `${PATH_NAME}/entregas/orden_ruta_entrega`,
            type: 'POST',
            data: { data_fila, numero },
            success: function (res) {
                if (res.status == 1) {
                    $('#table_mis_entregas').DataTable().ajax.reload();
                    setTimeout(function () {
                        btn_procesando('envio_orden_ruta', obj_inicial, 1);
                        alertify.success(res.msg);
                        $('#orden_rutaModal').modal('hide');
                        $('#orden_ruta').val('');
                    }, 12000);
                } else {
                    alertify.error('Ocurrio un error comuniquese con su desarrollador');
                }
            }
        });
    })
}
var DATOS = [];

var reporte_entregas = function () {
    $("#table_mis_entregas tbody").on("click", "button.reporte", function () {
        var data = $('#table_mis_entregas').DataTable().row($(this).parents("tr")).data();
        if (DATOS.length != 0) {
            DATOS = [];
        }
        DATOS.push(JSON.stringify(data));
        var id = $(this).attr('data-id');
        $('#enviar_entrega').attr('data-id', id);
    });
}

var imagen = function () {
    $('#foto_entrega').on('change', function (e) {
        // const EXTENCION_PERMI = ['.jpg', '.png'];

        // var reader = new FileReader();
        // reader.readAsDataURL(e.target.files[0]);
        // var nombre_foto = e.target.files[0].name;
        // var extension = nombre_foto.substring(nombre_foto.lastIndexOf('.'), nombre_foto.length);

        // if ($.inArray(extension, EXTENCION_PERMI) == -1) {
        //     alertify.error('no se pueden cargar archivos de este tipo, por favor cargue la imagen correcta');
        //     $('#ModalImagen').modal('hide');
        //     return;
        // }

        reader.onload = function () {
            var dataURL = reader.result;
            $('#imagen_entrega').html('<img class="cuadro_imagenes" style="margin-left: 0%;" src="' + dataURL + '" />');
        };
        reader.readAsDataURL(event.target.files[0]);
    })
}

var enviar_entrega = function () {
    $('#enviar_entrega').on('click', function () {
        var id = $(this).attr('data-id');
        var observacion = '';
        // hacer validacion de la imagen
        var formData = new FormData();
        var img_valida = $('#foto_entrega')[0].files;
        var files = $('#foto_entrega')[0].files[0];
        if (img_valida.length === 0) {
            alertify.error('Se necesita una imagen para continuar');
            return;
        }
        formData.append('file', files);
        formData.append('data', DATOS);
        formData.append('id', id);
        formData.append('observacion', observacion);
        var obj_inicial = $('#enviar_entrega').html();
        btn_procesando('enviar_entrega', obj_inicial);
        $.ajax({
            url: `${PATH_NAME}/entregas/movimiento_entrega`,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                // recargar tabla
                if (res) {
                    btn_procesando('enviar_entrega', obj_inicial, 1);
                    alertify.success('Datos ingredados correctamente');
                    $('#table_mis_entregas').DataTable().ajax.reload();
                    $('#ModalImagen').modal('hide');
                    $("#imagen_entrega").empty().html(`<i class="fas fa-camera camara"></i>`);
                } else {
                    alertify.error('Ocurrio un error comuniquese con su desarrollador');
                }
            }
        });
    })
}

var motivos_entrega = function (tbody, table) {//boton rojo
    $(tbody).on("click", "button.motivo", function () {
        var data = table.row($(this).parents("tr")).data();
        if (data.estado == 6) {
            $('#envio-motivo').attr('data-id', '5');
        } else {
            $('#envio-motivo').attr('data-id', '3');
        }
        GLOBAL_DATA = data;
    });
}

var enviar_datos_modal = function () {
    $('#envio-motivo').on('click', function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');

        var observacion = $('#motivo-eleccion').val();
        if (observacion == 0 || observacion == '') {
            alertify.error('Se debe elegir una opcion para continuar');
            $('#motivo-eleccion').focus();
            return;
        }
        var data = GLOBAL_DATA;
        // console.log(data);
        $.ajax({
            url: `${PATH_NAME}/entregas/movimiento_entrega`,
            type: 'POST',
            data: { data, id, observacion },
            success: function (res) {
                // recargar tabla
                if (res) {
                    alertify.success('Datos ingredados correctamente');
                    $('#table_mis_entregas').DataTable().ajax.reload();
                    $('#motivo-eleccion').val(0);
                } else {
                    alertify.error('Ocurrio un error comuniquese con su desarrollador');
                }
            }
        });
    });
}

var diligencia_alterna = function (tbody, table) {
    $(tbody).on("click", "button.diligencia", function () {
        var data = table.row($(this).parents("tr")).data();
        var estado = $(this).attr('data-id');
        if (estado == 4) {
            alertify.confirm('Confirmación Ejecución', '¿Esta seguro que desea quitar esta encargo asignado.?',
                function () {
                    envio_datos_alterno(data, estado);
                },
                function () {
                    alertify.error('Operación Cancelada.');
                });
        } else {
            envio_datos_alterno(data, estado);
        }
    });
}

var envio_datos_alterno = function (data, estado) {
    $.ajax({
        url: `${PATH_NAME}/entregas/entrega_encargos`,
        type: 'POST',
        data: { data, estado },
        success: function (res) {
            // recargar tabla
            if (res) {
                alertify.success('Datos ingredados correctamente');
                $('#table_mis_entregas').DataTable().ajax.reload();
                $('#motivo-eleccion').val(0);
            } else {
                alertify.error('Ocurrio un error comuniquese con su desarrollador');
            }
        }
    });

}
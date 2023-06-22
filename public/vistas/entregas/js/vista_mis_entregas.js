$(document).ready(function () {
    alertify.set('notifier', 'position', 'bottom-left');
    tabla_mis_entregas();
    enviar_datos_modal();
});
GLOBAL_DATA = [];

const IDPERSONA = $('#id_persona').val();

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
                                var boton = `<button type="button" data-id="4" class="reporte btn btn-success">
                                <span class="fas fa-check"></span>
                                </button> `;
                            } else {
                                var boton = `<button type="button" data-id="1" class="reporte btn btn-success">
                                <span class="fas fa-check-double"></span>
                                </button> `;
                            }
                        } else {
                            var boton = `<button type="button" data-id="2" class="reporte view btn btn-warning">
                            <span class="fas fa-check"></span>
                            </button> `;
                        }
                        boton += `<button type="button" class="motivo btn btn-danger" data-bs-toggle="modal" data-bs-target="#entregaModal" aria-expanded="false">
                        <span class="fas fa-times"></span>
                        </button> `
                    }
                    return boton;
                }
            },
            { "data": "num_pedido" },
            { "data": "documento" },
            { "data": "nombre_empresa" },
            { "data": "ruta" },
            { "data": "direccion" },
            { "data": "transportador" },
            { "data": "forma_pago" },
            { "data": "nombre_estado" },

        ],
    });
    reporte_entregas("#table_mis_entregas tbody", table);
    motivos_entrega("#table_mis_entregas tbody", table);
    diligencia_alterna("#table_mis_entregas tbody", table)
}

var reporte_entregas = function (tbody, table) {
    $(tbody).on("click", "button.reporte", function () {
        var data = table.row($(this).parents("tr")).data();
        var id = $(this).attr('data-id');
        var observacion = '';
        $.ajax({
            url: `${PATH_NAME}/entregas/movimiento_entrega`,
            type: 'POST',
            data: { data, id, observacion },
            success: function (res) {
                // recargar tabla
                if (res) {
                    alertify.success('Datos ingredados correctamente');
                    $('#table_mis_entregas').DataTable().ajax.reload();
                } else {
                    alertify.error('Ocurrio un error comuniquese con su desarrollador');
                }
            }
        });
    });
}

var motivos_entrega = function (tbody, table) {//boton rojo
    $(tbody).on("click", "button.motivo", function () {
        var data = table.row($(this).parents("tr")).data();
        if (data.estado == 6) {
            $('#envio-motivo').attr('data-id','5');
        } else {
            $('#envio-motivo').attr('data-id','3');
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

var diligencia_alterna = function(tbody, table) {
    $(tbody).on("click", "button.diligencia", function () {
        var data = table.row($(this).parents("tr")).data();
        var estado = $(this).attr('data-id');
        if (estado == 4) {
            alertify.confirm('Confirmación Ejecución', '¿Esta seguro que desea quitar esta encargo asignado.?', 
            function() { 
                envio_datos_alterno(data,estado);
            }, 
            function() { 
                alertify.error('Operación Cancelada.');
            });
        } else {
            envio_datos_alterno(data,estado);
        }
    });   
}

var envio_datos_alterno = function (data,estado) {
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
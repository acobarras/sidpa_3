$(document).ready(function () {
    consultar_datos_aprobacion();
    generar_pdf();
});

var consultar_datos_aprobacion = function () {
    var table = $("#tabla_aprobacion").DataTable({
        "ajax": `${PATH_NAME}/soporte_tecnico/consultar_datos_aprobacion`,
        "columns": [
            {
                "render": function (data, type, row) {
                    return `${row.id_diagnostico}-${row.item}`
                },

            },
            { "data": "num_consecutivo" },
            { "data": "nombre_empresa" },
            { "data": "item" },
            { "data": "equipo" },
            { "data": "serial_equipo" },
            { "data": "nombre_estado_soporte" },
            {
                "render": function (data, type, row) {
                    if (row.estado_item == 10) {
                        return `
                                <center>
                                    <button type='button' class='btn btn-success btn-circle aprueba_cotiza'>
                                        <span class='fas fa-check'></span>
                                    </button>
                                    <button type='button' class='btn btn-warning btn-circle recotizar'>
                                        <span class="fas fa-sync-alt"></span>
                                    </button>
                                    <button type='button' class='btn btn-danger btn-circle cancela_cotiza'>
                                        <span class='fas fa-ban'></span>
                                    </button>
                                    <button type='button' class='btn btn-info btn-circle consultar_repuestos'>
                                        <span class="fas fa-search"></span>
                                    </button>
                                </center>`;
                    }
                }
            }
        ]
    });
    aprobacion_cotiza('#tabla_aprobacion tbody', table);
}

var recotizar = function () {
    $('#tabla_recotizar tbody').on("click", "button.recotizar", function () {
        var data = $('#tabla_recotizar').DataTable().row($(this).parents("tr")).data();
        $.ajax({
            "url": `${PATH_NAME}/soporte_tecnico/recotizar_diag`,
            "type": 'POST',
            "data": { data },
            success: function (res) {
                if (res == 1) {
                    alertify.success('Se elimino el repuesto');
                    location.reload();
                } else {
                    alertify.error('Algo a ocurrido');
                }
            }
        });
    });
}

var generar_pdf = function () {
    $('.generar_pdf').on("click", function () {
        var datos = JSON.parse($(".generar_pdf").attr('data'));
        var num_cotiza = datos['repuestos'][0]['num_cotizacion'];
        $.ajax({
            "url": `${PATH_NAME}/soporte_tecnico/generar_cotizacion`,
            "type": 'POST',
            "data": { num_cotiza },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (res) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(res);
                a.href = url;
                a.download = 'Cotizacion' + num_cotiza + '.pdf';
                a.click();
                window.URL.revokeObjectURL(url);
            }
        });
    });
}

var aprobacion_cotiza = function (tbody, table) {
    var array_item = [];
    $(tbody).on("click", "button.cancela_cotiza", function () {
        array_item = table.row($(this).parents("tr")).data();
        var estado_cotiza = 6;
        var estado_item = 11;
        $.ajax({
            "url": `${PATH_NAME}/soporte_tecnico/cambiar_estado_cotiza`,
            "type": 'POST',
            "data": { array_item, estado_cotiza, estado_item },
            success: function (res) {
                if (res == true) {
                    alertify.error('Se Rechazo la cotizacion');
                    window.location.href = `${PATH_NAME}/vista_cierre_diag`;
                } else {
                    alertify.error('Algo a ocurrido');
                }
            }
        });
    })
    $(tbody).on("click", "button.aprueba_cotiza", function () {
        array_item = table.row($(this).parents("tr")).data();
        $("#selec_tecnico").modal("show");
        var estado_cotiza = 3;
        var estado_item = 12;
        $.ajax({
            "url": `${PATH_NAME}/soporte_tecnico/cambiar_estado_cotiza`,
            "type": 'POST',
            "data": { array_item, estado_cotiza, estado_item },
            success: function (res) {
                if (res == true) {
                    alertify.success('Se Acepto la cotizacion');
                    // window.location.href = `${PATH_NAME}/validacion_repuestos`;
                    location.reload();
                } else {
                    alertify.error('Algo a ocurrido');
                }
            }
        });
    });
    $(tbody).on("click", "button.recotizar", function () {
        array_item = table.row($(this).parents("tr")).data();
        var contar = array_item['repuestos'].length;
        $('#modal_recotizar').modal("show");
        $('#nombre_equipo').html(array_item['equipo'] + ' ' + 'S/N' + array_item['serial_equipo']);
        $('.generar_pdf').attr('data', JSON.stringify(array_item));
        $('#tabla_recotizar').DataTable({
            "data": array_item['repuestos'],
            "columns": [
                {
                    "render": function (data, type, row) {
                        return `${row.codigo_producto}-${row.descripcion_productos}`
                    }
                },
                {
                    "render": function (data, type, row) {
                        return `${row.cantidad}`
                    }
                },
                {
                    "render": function (data, type, row) {
                        return `${row.valor}`
                    }
                },
                {
                    "render": function (data, type, row) {
                        if (contar == 1) {
                            return ` <h5 style="color:red;">Este item solo tiene un repuesto y no se puede eliminar</h5>`;
                        } else {
                            return `
                            <center> 
                                <button type='button' class='btn btn-danger btn-circle recotizar'>
                                    <span class='fas fa-ban'></span>
                                </button>
                            </center>`;
                        }
                    }
                },
            ],
        });
        recotizar();
    });
    $(tbody).on("click", "button.consultar_repuestos", function () {
        array_item = table.row($(this).parents("tr")).data();
        $('#tabla_repuestos').DataTable({
            "data": array_item['repuestos'],
            "columns": [
                {
                    "render": function (data, type, row) {
                        return `${row.codigo_producto}-${row.descripcion_productos}`
                    }
                },
                {
                    "render": function (data, type, row) {
                        return `${row.cantidad}`
                    }
                },
            ],
        });
        $("#modal_repuestos").modal("show");
    });
}


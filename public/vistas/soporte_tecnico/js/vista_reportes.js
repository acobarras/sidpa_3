$(document).ready(function () {
    consulta_visita();
    consulta_autorizaciones();
    consulta_pendientes();
    consulta_comisiones();
    remision();
    etiqueta_ingreso();
});

var consulta_visita = function () {
    $('#form_visitas').submit(function (e) {
        e.preventDefault();
        var formulario = $('#form_visitas').serializeArray();
        var valida = validar_formulario(formulario);
        if (valida) {
            var table = $('#tb_indicador_visitas').DataTable({
                "ajax": {
                    "url":`${PATH_NAME}/soporte_tecnico/consultas_reporte`,
                    "type": "POST",
                    "data": {formulario},
                },  
                dom: 'Bflrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                    tittleAttr: ' Exportar a exel',
                    className: 'btn btn-success',
                }],      
                "columns": [
                    { "data": "num_consecutivo" },
                    { "data": "observacion" },
                    { "data": "fecha_crea" },
                    { "data": "nombre_empresa" },
                    { "data": "equipo" },
                    { "data": "serial_equipo" },
                    { 
                        "data": "estado", render: function (data, type, row) {
                            switch (row.id_actividad_area) {
                                case 87:
                                  return '<p class="text-primary">Agendado</p>'
                                case 88:
                                  return '<p class="text-success">Realizado</p>'
                                case 99:
                                    return '<p class="text-success">Realizado</p>'
                                case 92:
                                  return '<p class="text-danger">Reagendado</p>'
                                default:
                                  return '<p></p>'
                            }
                        },"className": "text-center"
                    },                 
                ]
            })
        }
    })
}

var consulta_autorizaciones = function () {
    $('#form_autorizaciones').submit(function (e) {
        e.preventDefault();
        var formulario = $('#form_autorizaciones').serializeArray();
        var valida = validar_formulario(formulario);
        if (valida) {
            var table = $('#tb_indicador_autorizacion').DataTable({
                "ajax": {
                    "url":`${PATH_NAME}/soporte_tecnico/consultas_reporte`,
                    "type": "POST",
                    "data": {formulario},
                },        
                dom: 'Bflrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                    tittleAttr: ' Exportar a exel',
                    className: 'btn btn-success',
                }], 
                "columns": [
                    { "data": "num_consecutivo" },
                    { "data": "observacion" },
                    { "data": "fecha_crea" },
                    { "data": "nombre_empresa" },
                    { "data": "equipo" },
                    { "data": "serial_equipo" },
                    { "data": "estado", render: function (data, type, row) {
                        switch (row.id_actividad_area) {
                            case 79:
                              return '<p class="text-primary">Aceptado</p>'
                            case 82:
                              return '<p class="text-success">Realizado</p>'
                            default:
                              return '<p></p>'
                        }
                    },"className": "text-center" },                 
                ]
            })
        }
    })
}

var consulta_pendientes = function () {
    var formulario =[];
    formulario.push({ name: 'consulta', value: 3 }); // se maneja asi para que todos queden con la misma estructura

    var table = $('#tb_consolidado').DataTable({
        "ajax": {
            "url":`${PATH_NAME}/soporte_tecnico/consultas_reporte`,
            "type": "POST",
            "data": {formulario},
        },        
        "columns": [
            { "data": "num_consecutivo" },
            { "data": "fecha_crea" },
            { "data": "nombre_estado_soporte" },
            { "data": "nombre_empresa" },
          
        ]
    })
}

var consulta_comisiones = function () {
    $('#form_comisiones').submit(function (e) {
        e.preventDefault();
        var formulario = $('#form_comisiones').serializeArray();
        var valida = validar_formulario(formulario);
        console.log(formulario);
        if (valida) {
            var table = $('#tb_comisiones').DataTable({
                "ajax": {
                    "url":`${PATH_NAME}/soporte_tecnico/consultas_reporte`,
                    "type": "POST",
                    "data": {formulario},
                },    
                dom: 'Bflrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                    tittleAttr: ' Exportar a exel',
                    className: 'btn btn-success',
                }],     
                "columns": [
                    { "data": "nombre_empresa" },
                    { "data": "num_consecutivo" },
                    { "data": "equipo" },
                    { "data": "serial_equipo" },
                    { "data": "procedimiento" },
                    { "data": "accesorios" },
                    { "data": "tipo_impacto", render: function (data, type, row) {
                        switch (row.id_actividad_area) {
                            case 78:
                              return '<p>Diagnostico</p>'
                            case 82:
                              return '<p>Mantenimiento</p>'
                            case 84:
                              return '<p>Remoto</p>'
                            case 89:
                              return '<p>Diagnostico</p>'
                            case 98:
                              return '<p>Instalación</p>'
                            default:
                              return '<p></p>'
                        }
                    },"className": "text-center" },
                ]
            })
        }
    })
}

var remision = function () {
    $('#genera_remision').on('click', function () {
        var num_remision = $('#num_diag_remision').val();
        if (num_remision == '') {
            alertify.warning('ingrese un valor');
            $('#num_diag_remision').focus();
            return;
        }
        $.ajax({
            url: `${PATH_NAME}/soporte_tecnico/reimpresion_remision`,
            type: 'POST',
            data: { num_remision},
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: function (res) {
                $('.boton_remision').removeClass('fa fa-download');
                $('.boton_remision').addClass('fas fa-spinner fa-spin');
                $('#num_diag_remision').val('');
            },
            success: function (regreso) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(regreso);
                a.href = url;
                a.download = 'Reporte_DS-' + num_remision + '.pdf';
                a.click();
                window.URL.revokeObjectURL(url);
                $('.boton_remision').addClass('fa fa-download');
                $('.boton_remision').removeClass('fas fa-spinner fa-spin');
                $('#num_diag_remision').val('');
            },
            error: function (err) {
                alertify.error("No existe un PDF con este numero de acta");
                $('#num_diag_remision').val('');
                $('.boton_remision').addClass('fa fa-download');
                $('.boton_remision').removeClass('fas fa-spinner fa-spin');
            }
        });
    });
}

var etiqueta_ingreso = function () {
    $('#genera_zpl').on('click', function () {
        var consecutivo = $('#num_diag_ingreso').val();
        var datos = 'consultar'
        if (consecutivo == '') {
            alertify.warning('ingrese un valor');
            $('#num_diag_ingreso').focus();
            return;
        }
        $.ajax({
            "url": `${PATH_NAME}/soporte_tecnico/impresion_etiqueta_equipo`,
            "type": 'POST',
            "data": { consecutivo, datos},
            beforeSend: function (res) {
                $('.boton_zpl').removeClass('fa fa-download');
                $('.boton_zpl').addClass('fas fa-spinner fa-spin');
                $('#num_diag_ingreso').val('');
                $("div.div_impresion").empty().html();
                $("div.div_impresion").removeClass('d-none');
            },
            success: function (res) {
                if (res.length === 0) {
                    alertify.error('¡Este registro no existe o corresponde a una visita!');
                }else{
                    res = res.toString();
                    $("div.div_impresion").empty().html(res);
                    $("div.div_impresion").printArea();
                    $("div.div_impresion").addClass('d-none');
                }
                $('.boton_zpl').addClass('fa fa-download');
                $('.boton_zpl').removeClass('fas fa-spinner fa-spin');
            }
        });
    });
}
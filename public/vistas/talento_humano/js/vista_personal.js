$(document).ready(function () {
    tabla_solicitud_personal();
    modificar_fecha_publicacion();
    tabla_proceso_seleccion();
    can_descartados();
    editar_proceso_seleccion();
    tabla_entrevista();
    editar_entrevista();
    tabla_pruebas();
    editar_pruebas();
    tabla_contratacion();
    editar_contratacion();
    tabla_final();
});

var tabla_solicitud_personal = function() {
    var table = $('#tabla_personal').DataTable({
        "ajax": `${PATH_NAME}/talento_humano/tabla_personal`,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        "scrollX": true,
        columns: [
            { "defaultContent": `
                <button type="button" class="editar btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fas fa-pen"></i>
                </button>
                <button type="button" class="cancelado btn btn-danger">
                    <i class="fas fa-times"></i>
                </button>`
            },
            { "data": "fecha_crea" },
            { "data": "lider" },
            { "data": "nombre_cargo" },
            { "data": "num_vacantes" },
            { "data": "descripcion", render: function(data,type,row) {
                    var res = `<textarea style="height: 100px; width: 300px; resize: none; text-align: justify; white-space: normal;" >${row.observaciones}</textarea>`;
                    return res;
                } 
            },
        ],
        "deferRender": true,
        "stateSave": true,
    });
    fecha_publicacion("#tabla_personal tbody", table);
    proceso_cancelado("#tabla_personal tbody", table);
}

var fecha_publicacion = function(tbody,table) {
    $(tbody).on("click","button.editar",function () {
        var data = table.row( $(this).parents("tr") ).data();
        $('.boton-x').attr('formulario',data.id_personal);
    });
}

var modificar_fecha_publicacion = function () {
	$('#editar-fecha-publicacion').submit(function(e){
		e.preventDefault();
        var form = $(this).serializeArray();
        var validar = form_valide(form);
        if(validar) {
            var form = $('#editar-fecha-publicacion').serialize();
            var id_personal = $('.boton-x').attr('formulario');
            var estado = 2;
            $.ajax({
                url:`${PATH_NAME}/talento_humano/editar_personal`,
                type:'POST',
                data:{form,id_personal,estado},
                success:function(res){
                    // recargar tabla
                    if (res) {
                        alertify.success('Modificación correcta');
                        $('#tabla_personal').DataTable().ajax.reload();
                        $('#editar-fecha-publicacion')[0].reset();
                    } else {
                        alertify.error('Ocurrio un error');
                    }
                }
            });
        }
    });
}

var proceso_cancelado = function(tbody,table) {
    $(tbody).on("click","button.cancelado",function () {
        var data = table.row( $(this).parents("tr") ).data();
        var form = `estado=7`;
        var id_personal = data.id_personal;
        var estado = 7;
        $.ajax({
            url:`${PATH_NAME}/calidad/editar_personal`,
            type:'POST',
            data:{form,id_personal,estado},
            success:function(res){
                // recargar tabla
                if (res) {
                    alertify.success('Modificación correcta');
                    $('#tabla_personal').DataTable().ajax.reload();
                    $('#tabla_proceso_seleccion').DataTable().ajax.reload();
                    $('#tabla_final').DataTable().ajax.reload();
                } else {
                    alertify.error('Ocurrio un error');
                }
            }
        });
    });
}

var tabla_proceso_seleccion = function() {
    var table = $('#tabla_proceso_seleccion').DataTable({
        "ajax": `${PATH_NAME}/talento_humano/tabla_proceso_seleccion`,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        "scrollX": true,
        columns: [
            { "defaultContent": `
                <button type="button" class="proceso_seleccion btn btn-success" data-bs-toggle="modal" data-bs-target="#procesoSeleccion">
                    <i class="fas fa-check"></i>
                </button>`
            },
            { "data": "nombre_estado" },
            { "data": "fecha_crea" },
            { "data": "lider" },
            { "data": "nombre_cargo" },
            { "data": "num_vacantes" },
            { "data": "descripcion", render: function(data,type,row) {
                    var res = `<textarea style="height: 100px; width: 300px; resize: none; text-align: justify; white-space: normal;" >${row.observaciones}</textarea>`;
                    return res;
                } 
            },
        ],
        "deferRender": true,
        "stateSave": true,
    });    
    proceso_seleccion("#tabla_proceso_seleccion tbody", table);
}

var proceso_seleccion = function(tbody,table) {
    $(tbody).on("click","button.proceso_seleccion",function () {
        var data = table.row( $(this).parents("tr") ).data();
        $('.boton-y').attr('formulario',data.id_personal);
    });    
}

var can_descartados = function() {
    $('.calculo').on('change', function(e) {
        e.preventDefault();
        var can_postu = $('#can_postulados').val();
        var can_entrev = $('#can_entrevista').val();
        var descartados = parseInt(can_postu)-parseInt(can_entrev);
        $('#can_descartados').val(descartados);
    });
}

var editar_proceso_seleccion = function () {
	$('#editar-proceso-seleccion').submit(function(e){
		e.preventDefault();
        var form = $(this).serializeArray();
        var exception = {2:'can_descartados'};
        var validar = form_valide(form,exception);
        if(validar) {
            var negativo = $('#can_descartados').val();
            if(negativo < 0) {
                alertify.error('La cantidad descartada no puede ser menor a cero');
                $('#can_descartados').focus();
            } else {
                var form = $('#editar-proceso-seleccion').serialize();
                var id_personal = $('.boton-y').attr('formulario');
                var estado = 3;
                $.ajax({
                    url:`${PATH_NAME}/calidad/editar_personal`,
                    type:'POST',
                    data:{form,id_personal,estado},
                    success:function(res){
                        // recargar tabla
                        if (res) {
                            alertify.success('Modificación correcta');
                            $('#tabla_proceso_seleccion').DataTable().ajax.reload();
                            $('#tabla_entrevista').DataTable().ajax.reload();
                            $('#editar-proceso-seleccion')[0].reset();
                        } else {
                            alertify.error('Ocurrio un error');
                        }
                    }
                });
            }
        }
    });
}

var tabla_entrevista = function() {
    var table = $('#tabla_entrevista').DataTable({
        "ajax": `${PATH_NAME}/talento_humano/tabla_entrevista`,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        "scrollX": true,
        columns: [
            { "defaultContent": `
                <button type="button" class="proceso_seleccion btn btn-success" data-bs-toggle="modal" data-bs-target="#procesoEntrevista">
                    <i class="fas fa-check"></i>
                </button>`
            },
            { "data": "nombre_estado" },
            { "data": "fecha_crea" },
            { "data": "lider" },
            { "data": "nombre_cargo" },
            { "data": "num_vacantes" },
            { "data": "descripcion", render: function(data,type,row) {
                    var res = `<textarea style="height: 100px; width: 300px; resize: none; text-align: justify; white-space: normal;" >${row.observaciones}</textarea>`;
                    return res;
                } 
            },
        ],
        "deferRender": true,
        "stateSave": true,
    });

    proceso_entrevista("#tabla_entrevista tbody", table);
}

var proceso_entrevista = function(tbody,table) {
    $(tbody).on("click","button.proceso_seleccion",function () {
        var data = table.row( $(this).parents("tr") ).data();
        $('.boton-z').attr('formulario',data.id_personal);
    });    
}

var editar_entrevista = function () {
    $('#editar-proceso-entrevista').submit(function(e){
        e.preventDefault();
        var form = $(this).serializeArray();
        var validar = form_valide(form);
        if(validar) {
            var form = $('#editar-proceso-entrevista').serialize();
            var id_personal = $('.boton-z').attr('formulario');
            var estado = 4;
            $.ajax({
                url:`${PATH_NAME}/calidad/editar_personal`,
                type:'POST',
                data:{form,id_personal,estado},
                success:function(res){
                    // recargar tabla
                    if (res) {
                        alertify.success('Modificación correcta');
                        $('#tabla_entrevista').DataTable().ajax.reload();
                        $('#tabla_pruebas').DataTable().ajax.reload();
                        $('#editar-proceso-entrevista')[0].reset();
                    } else {
                        alertify.error('Ocurrio un error');
                    }
                }
            });
        }
    });
}

var tabla_pruebas = function() {
    var table = $('#tabla_pruebas').DataTable({
        "ajax": `${PATH_NAME}/talento_humano/tabla_pruebas`,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        "scrollX": true,
        columns: [
            { "defaultContent": `
                <button type="button" class="proceso_pruebas btn btn-success" data-bs-toggle="modal" data-bs-target="#procesoPruebas">
                    <i class="fas fa-check"></i>
                </button>`
            },
            { "data": "nombre_estado" },
            { "data": "fecha_crea" },
            { "data": "lider" },
            { "data": "nombre_cargo" },
            { "data": "num_vacantes" },
            { "data": "descripcion", render: function(data,type,row) {
                    var res = `<textarea style="height: 100px; width: 300px; resize: none; text-align: justify; white-space: normal;" >${row.observaciones}</textarea>`;
                    return res;
                } 
            },
        ],
        "deferRender": true,
        "stateSave": true,
    });

    proceso_pruebas("#tabla_pruebas tbody", table);
}

var proceso_pruebas = function(tbody,table) {
    $(tbody).on("click","button.proceso_pruebas",function () {
        var data = table.row( $(this).parents("tr") ).data();
        $('.boton-a').attr('formulario',data.id_personal);
    });    
}

var editar_pruebas = function () {
    $('#editar-proceso-pruebas').submit(function(e){
        e.preventDefault();
        var form = $(this).serializeArray();
        var validar = form_valide(form);
        if(validar) {
            var form = $('#editar-proceso-pruebas').serialize();
            var id_personal = $('.boton-a').attr('formulario');
            var estado = 5;
            $.ajax({
                url:`${PATH_NAME}/talento_humano/editar_personal`,
                type:'POST',
                data:{form,id_personal,estado},
                success:function(res){
                    // recargar tabla
                    if (res) {
                        alertify.success('Modificación correcta');
                        $('#tabla_pruebas').DataTable().ajax.reload();
                        $('#tabla_contratacion').DataTable().ajax.reload();
                        $('#editar-proceso-pruebas')[0].reset();
                    } else {
                        alertify.error('Ocurrio un error');
                    }
                }
            });
        }
    });
}

var tabla_contratacion = function() {
    var table = $('#tabla_contratacion').DataTable({
        "ajax": `${PATH_NAME}/talento_humano/tabla_contratacion`,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        "scrollX": true,
        columns: [
            { "defaultContent": `
                <button type="button" class="proceso_contratacion btn btn-success" data-bs-toggle="modal" data-bs-target="#procesoContratacion">
                    <i class="fas fa-check"></i>
                </button>`
            },
            { "data": "nombre_estado" },
            { "data": "fecha_crea" },
            { "data": "lider" },
            { "data": "nombre_cargo" },
            { "data": "num_vacantes" },
            { "data": "descripcion", render: function(data,type,row) {
                    var res = `<textarea style="height: 100px; width: 300px; resize: none; text-align: justify; white-space: normal;" >${row.observaciones}</textarea>`;
                    return res;
                } 
            },
        ],
        "deferRender": true,
        "stateSave": true,
    });

    proceso_contratacion("#tabla_contratacion tbody", table);
}

var proceso_contratacion = function(tbody,table) {
    $(tbody).on("click","button.proceso_contratacion",function () {
        var data = table.row( $(this).parents("tr") ).data();
        $('.boton-b').attr('formulario',data.id_personal);
    });    
}

var editar_contratacion = function () {
    $('#editar-proceso-contratacion').submit(function(e){
        e.preventDefault();
        var form = $(this).serializeArray();
        var validar = form_valide(form);
        if(validar) {
            var form = $('#editar-proceso-contratacion').serialize();
            var id_personal = $('.boton-b').attr('formulario');
            var estado = 6;
            $.ajax({
                url:`${PATH_NAME}/talento_humano/editar_personal`,
                type:'POST',
                data:{form,id_personal,estado},
                success:function(res){
                    // recargar tabla
                    if (res) {
                        alertify.success('Modificación correcta');
                        $('#tabla_contratacion').DataTable().ajax.reload();
                        $('#tabla_final').DataTable().ajax.reload();
                        $('#editar-proceso-contratacion')[0].reset();
                    } else {
                        alertify.error('Ocurrio un error');
                    }
                }
            });
        }
    });
}

var tabla_final = function() {
    var table = $('#tabla_final').DataTable({
        "ajax": `${PATH_NAME}/talento_humano/tabla_final`,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'excel', 'pdf'
        ],
        "scrollX": true,
        columns: [
            { "data": "nombre_estado" },
            { "data": "fecha_crea" },
            { "data": "lider" },
            { "data": "nombre_cargo" },
            { "data": "num_vacantes" },
            { "data": "descripcion", render: function(data,type,row) {
                    var res = `<textarea style="height: 100px; width: 300px; resize: none; text-align: justify; white-space: normal;" >${row.observaciones}</textarea>`;
                    return res;
                } 
            },
            { "data": "fecha_publicacion" },
            { "data": "can_postulados" },
            { "data": "can_entrevista" },
            { "data": "can_descartados" },
            { "data": "can_ent_ejecu" },
            { "data": "can_ausentes" },
            { "data": "can_preselec" },
            { "data": "can_conv_prueba" },
            { "data": "can_asist_psico" },
            { "data": "can_prueb_medic" },
            { "data": "can_aptos" },
            { "data": "can_contratados" },
            { "data": "fecha_contratacion" },
            { "data": "fecha_induccion" },
            { "data": "fecha_inicio_labores" },
        ],
        "deferRender": true,
        "stateSave": true,
    });

}

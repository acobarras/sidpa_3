$(document).ready(function () {
    tabla_perfil_cargo();
    nuevo_perfil_cargo();
    editar_perfil_cargo();
});

var tabla_perfil_cargo = function() {
    var table = $('#tabla_perfil_cargo').DataTable({
        "ajax": `${PATH_NAME}/configuracion/tabla_perfil_cargo`,
        columns: [
            { "data": "id_perfil" },
            { "data": "nombre_cargo" },
            { "data": "descripcion" },
            { "data": "estado", render: function(data,type,row) {
                var respuesta = 'Inactivo';
                    if (row.estado == 1) {
                        respuesta = 'Activo';
                    } 
                    return respuesta;
                } 
            },
            { "defaultContent": '<button type="button" class="btn btn-primary editar_registro" title="Consultar/Modificar" data-bs-toggle="modal" data-bs-target="#ModalPerfilCargo"><i class="fa fa-edit"></i></button>',"className": "text-center" }
        ],
        "deferRender": true,
        "stateSave": true,
    });
    obtener_data_editar("#tabla_perfil_cargo tbody", table);
}

var nuevo_perfil_cargo = function() {
    $("#form_crear_perfil_cargo").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_perfil_cargo').html();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            btn_procesando('crear_perfil_cargo');
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_perfil_cargo`,
                "type": 'POST',
                "data": form,
                "success": function (respuesta) {
                    if (respuesta.estado) {
                        alertify.success(`Datos ingresados corretamente la posicion insetada es ${respuesta.id}`);
                        btn_procesando('crear_perfil_cargo', obj_inicial, 1);
                        limpiar_formulario('form_crear_perfil_cargo', 'select');
                        limpiar_formulario('form_crear_perfil_cargo', 'input');
                        $("#tabla_perfil_cargo").DataTable().ajax.reload();
                    } else {
                        alertify.error(`Error al insertar`);
                        btn_procesando('crear_perfil_cargo', obj_inicial, 1);
                    }
                }
            });
        }
    });
}

var obtener_data_editar = function (tbody, table) {
	$(tbody).on("click","button.editar_registro",function () {
        var data = table.row( $(this).parents("tr") ).data();
        rellenar_formulario(data);
        $('#modificar_perfil_cargo').attr('data-id', data.id_perfil);
    });
}

var editar_perfil_cargo = function () {
    $('#form_modificar_perfil_cargo').submit(function (e) {
        e.preventDefault();
        var form = $(this).serialize();
        var id_perfil_cargo = $('#modificar_perfil_cargo').attr('data-id');
        var envio = {
            'form': form,
            'id': id_perfil_cargo
        };
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_perfil_cargo`,
            "type": 'POST',
            "data": envio,
            "success": function (respu) {
                if (respu) {
                    $("#ModalPerfilCargo").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                    $("#tabla_perfil_cargo").DataTable().ajax.reload();
                } else {
                    alertify.error('Error inesperado');
                }
            }
        });
    });
}

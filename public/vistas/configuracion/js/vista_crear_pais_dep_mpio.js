$(document).ready(function () {
    listar_pais();//funcion para consultar paises disponibles.
    modificar_pais();
    crear_pais();
    $('#id_pais_form2').select2();
    $('#id_pais_modifi').select2();
    $('#id_departamento2').select2();
    $('#id_depart_modifi').select2();
    crear_departamento();
    listar_departamentos();
    modificar_departamento();
    crear_ciudad();
    listar_ciudad();
    modificar_ciudad();
});

var listar_pais = function () {

    var table = $("#tabla_pais").DataTable({
        "ajax": `${PATH_NAME}/configuracion/consultar_pais`,
        "columns": [
            { "data": "id_pais" },
            { "data": "codigo" },
            { "data": "nombre" },
            { "defaultContent": "<center><button type='button' class='btn btn-primary editar_paises' data-bs-toggle='modal' data-bs-target='#ModalPais'><i class='fa fa-edit'></i></button></center>" },
        ],
    });
    obtener_data_editar_pais("#tabla_pais tbody", table);//funcion para obtener la informacion del pais seleccionado.
};

// Crear un Pais
var crear_pais = function () {
    $("#form_crear_pais").submit(function (e) {
        e.preventDefault();
        var form = $("#form_crear_pais").serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_pais`,
                "type": 'POST',
                "data": form,
                'success': function (respuesta) {
                    $('#tabla_pais').DataTable().ajax.reload();
                    $("#form_crear_pais")[0].reset();
                    alertify.success(`Se agrego el registro ${respuesta.id} corectamente`);
                }
            });
        }
    });
}

// Listar un Pais para modificar
var obtener_data_editar_pais = function (tbody, table) {
    $(tbody).on("click", "button.editar_paises", function () {
        var data = table.row($(this).parents("tr")).data();
        $("#id_pais").val(data.id_pais);
        $("#codigo_pais").val(data.codigo);
        $("#nombre_pais").val(data.nombre);
    });
};

// Modificar un Pais
var modificar_pais = function () {
    $("#from_modificar_pais").submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                "url": `${PATH_NAME}/configuracion/modificar_pais`,
                "type": 'POST',
                "data": form
            }).done(function (respuesta) {
                if (respuesta) {
                    $('#tabla_pais').DataTable().ajax.reload();
                    $("#ModalPais").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                } else {
                    alertify.error('Sucedio un error comuniquese con su desarrollador o intente mas tarde');
                }
            });
        }
    });
};

// Listar departamentos

var listar_departamentos = function () {
    var table = $("#tabla_departamento").DataTable({
        "ajax": `${PATH_NAME}/configuracion/consultar_departamentos`,
        "columns": [
            { "data": "id_departamento" },
            { "data": "nombre_pais" },
            { "data": "nombre" },
            { "defaultContent": "<center><button type='button' class='btn btn-primary editar_departamento' data-bs-toggle='modal' data-bs-target='#ModalDepartamento'><i class='fa fa-edit'></i></button></center>" },
        ],
    });
    obtener_data_editar_departamento("#tabla_departamento tbody", table);//funcion para obtener la informacion del pais seleccionado.
}

// Crear Departamento
var crear_departamento = function () {
    $('#form_crear_departamento').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_departamento`,
                "type": 'POST',
                "data": form,
                'success': function (respu) {
                    $('#tabla_departamento').DataTable().ajax.reload();
                    $(`#id_pais_form2`).val(0).trigger('change');
                    $("#form_crear_departamento")[0].reset();
                    alertify.success(`Se agrego el registro ${respu.id} corectamente`);
                }
            });
        }
    });
}

// Listar un Pais para modificar
var obtener_data_editar_departamento = function (tbody, table) {
    $(tbody).on("click", "button.editar_departamento", function () {
        var data = table.row($(this).parents("tr")).data();
        $("#id_departamento_modifi").val(data.id_departamento);
        $(`#id_pais_modifi`).val(data.id_pais).trigger('change');
        $("#nombre_departamento_modifi").val(data.nombre);
    });
}

// Modificar un Departamento
var modificar_departamento = function () {
    $("#from_modificar_departamento").submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                "url": `${PATH_NAME}/configuracion/modificar_departamento`,
                "type": 'POST',
                "data": form
            }).done(function (respuesta) {
                if (respuesta) {
                    $('#tabla_departamento').DataTable().ajax.reload();
                    $("#ModalDepartamento").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                } else {
                    alertify.error('Sucedio un error comuniquese con su desarrollador o intente mas tarde');
                }
            });
        }
    });
}

// Crear Ciudad o Municipio
var crear_ciudad = function () {
    $('#form_crear_ciudad').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                "url": `${PATH_NAME}/configuracion/insertar_ciudad`,
                "type": 'POST',
                "data": form,
                'success': function (respu) {
                    $('#tabla_ciudad').DataTable().ajax.reload();
                    $(`#id_departamento2`).val(0).trigger('change');
                    $("#form_crear_ciudad")[0].reset();
                    alertify.success(`Se agrego el registro ${respu.id} corectamente`);
                }
            });
        }
    });
}

// Listar ciudades
var listar_ciudad = function () {
    var table = $("#tabla_ciudad").DataTable({
        // processing: true,
        "destroy": true,
        "deferRender": true,
        "stateSave": true,
        "ajax": `${PATH_NAME}/configuracion/consultar_ciudad`,
        "columns": [
            { "data": "id_ciudad" },
            { "data": "nombre_departamento" },
            { "data": "nombre" },
            { "defaultContent": "<center><button type='button' class='btn btn-primary editar_ciudad' data-bs-toggle='modal' data-bs-target='#ModalCiudad'><i class='fa fa-edit'></i></button></center>" },
        ],
    });
    obtener_data_editar_ciudad("#tabla_ciudad tbody", table);//funcion para obtener la informacion del pais seleccionado.
}

// Listar un Ciudad para modificar
var obtener_data_editar_ciudad = function (tbody, table) {
    $(tbody).on("click", "button.editar_ciudad", function () {
        var data = table.row($(this).parents("tr")).data();
        $("#id_ciudad_modifi").val(data.id_ciudad);
        $(`#id_depart_modifi`).val(data.id_departamento).trigger('change');
        $("#nombre_ciudad_modifi").val(data.nombre);
    });
}

// Modificar un Ciudad o Municipio
var modificar_ciudad = function () {
    $("#from_modificar_ciudad").submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        valida = validar_formulario(form);
        if (valida) {
            $.ajax({
                "url": `${PATH_NAME}/configuracion/modificar_ciudad`,
                "type": 'POST',
                "data": form
            }).done(function (respuesta) {
                if (respuesta) {
                    $('#tabla_ciudad').DataTable().ajax.reload();
                    $("#ModalCiudad").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                } else {
                    alertify.error('Sucedio un error comuniquese con su desarrollador o intente mas tarde');
                }
            });
        }
    });
}

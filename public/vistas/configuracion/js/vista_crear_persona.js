$(document).ready(function () {
    select_2();
    $('.id_tipo_documento').select2();
    $('.id_pais').select2();
    $('.id_departamento').select2();
    $('.id_ciudad').select2();
    $('.tipo_usuario').select2();
    $('.jefe_imediato').select2();
    $('#estado_modifi').select2();
    $(".datepicker").datepicker({ minDate: new Date('1960/01/01') });
    listar_personas();
    crear_persona();
    validar_documento();
    valida_correo();
    editar_persona();

});
const EXTENCION_PERMI = ['.jpg'];
var _URL = window.URL || window.webkitURL;

const carga_archivos = function (spam, imagen_file) {
    $(`#${spam}`).change(function (e) {
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function () {
                var wid = this.width;
                var ht = this.height;
                if (wid > 650 || ht > 980) {
                    $(`#${imagen_file}`).html(`<div id="lolo"><img src="https://thumbs.dreamstime.com/b/ninguna-fotograf%C3%ADa-permitida-31268291.jpg" width="192px" height="190px"></div>`);
                    alertify.error('La imagen tiene mas de 980 pixeles de alto o mas de 650 pixeles de ancho');
                }
            };
            img.src = _URL.createObjectURL(file);
            let reader = new FileReader();
            reader.readAsDataURL(e.target.files[0]);
            var nombre_foto = e.target.files[0].name;
            var extension = nombre_foto.substring(nombre_foto.lastIndexOf('.'), nombre_foto.length);
            if ($.inArray(extension, EXTENCION_PERMI) == -1) {
                $(`#${imagen_file}`).html(`<div id="lolo"><img src="https://thumbs.dreamstime.com/b/ninguna-fotograf%C3%ADa-permitida-31268291.jpg" width="192px" height="190px"></div>`);
                alertify.error('no se pueden cargar archivos de este tipo');
            } else {
                reader.onload = function () {
                    let preview = document.getElementById(imagen_file);
                    file = document.createElement('img');
                    file.setAttribute("style", "width:167px; height:250px");
                    file.src = reader.result;
                    preview.innerHTML = '';
                    preview.append(file);
                }
            }
        }
    });

}

var listar_personas = function () {
    $.ajax({
        url: `${PATH_NAME}/configuracion/consultar_personas`,
        type: 'GET',
        success: function (respu) {
            localStorage.setItem('lista-personas', JSON.stringify(respu));
            var table = $("#tabla_personas").DataTable({
                "dom": "Bfrtip",
                "buttons": [
                    "copy", "excel", "pdf"
                ],
                "data": respu,
                "columns": [

                    { "data": "id_persona" },
                    { "data": "num_documento" },
                    { "data": "nombres" },
                    { "data": "apellidos" },
                    { "data": "existe_imagen" },
                    { "data": "fecha_nacimiento" },
                    { "data": "direccion" },
                    { "data": "barrio" },
                    { "data": "celular" },
                    { "data": "correo" },
                    { "data": "nombre_estado" },
                    { "defaultContent": "<center><button type='button' class='btn btn-primary editar_persona' data-bs-toggle='modal' data-bs-target='#ModalPersona'><i class='fa fa-edit'></i></button></center>" },
                ],
            });

            obtener_data_editar_persona();
            carga_archivos('foto_persona', 'imagen_persona');
        }
    });
}

var obtener_data_editar_persona = function (tbody, table) {
    $('#tabla_personas tbody').on("click", "button.editar_persona", function () {
        var data = $("#tabla_personas").DataTable().row($(this).parents("tr")).data();
        var elegidos = '';
        if (data.comite != null) {
            elegidos = data.comite.split(",");
        }
        rellenar_formulario(data);
        $('#imagen_persona_modifi').html(`<div id="lolo"><img src="${IMG}${PROYECTO}/fotos_persona/${data.num_documento + '.jpg'}" width="170" height="250"></div>`);
        carga_archivos('foto_persona_modifi', 'imagen_persona_modifi');
        $('#comite_modifi').val(elegidos).trigger('change');

    });
}

var editar_persona = function () {
    $('#form_editar_persona').submit(function (e) {
        e.preventDefault();
        $.ajax({
            "url": `${PATH_NAME}/configuracion/modificar_persona`,
            "type": 'POST',
            "data": new FormData(this),
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (respu) {
                if (respu) {
                    $("#ModalPersona").modal("hide");
                    alertify.success('Modificacion realizada corectamente');
                    location.reload();
                    listar_personas();
                } else {
                    alertify.error('Error inesperado');
                }
            }
        });

    });
}

var crear_persona = function (table) {
    $("#form_crear_persona").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crear_persona').html();
        var form = $(this).serializeArray();
        var fileInput = $('#foto_persona').get(0).files[0];
        valida = validar_formulario(form);
        if (valida) {
            // if (fileInput == 'undefined') {
            //     alertify.error('el campo foto es requerido');
            // } else {
                btn_procesando('crear_persona');
                $.ajax({
                    "url": `${PATH_NAME}/configuracion/insertar_personas`,
                    "type": 'POST',
                    "data": new FormData(this),
                    "cache": false,
                    "processData": false,
                    "contentType": false,
                    "success": function (respuesta) {
                        if (respuesta['status'] == true) {
                            alertify.success(`Datos ingresados corretamente la posicion insetada es ${respuesta.id}`);
                            btn_procesando('crear_persona', obj_inicial, 1);
                            limpiar_formulario('form_crear_persona', 'select');
                            limpiar_formulario('form_crear_persona', 'input');
                            $("#imagen_persona").empty().html(`<i class="fas fa-camera camara"></i>`);
                            listar_personas();
                        } else {
                            alertify.error(`Error al insertar`);
                            btn_procesando('crear_persona', obj_inicial, 1);
                        }
                    }
                });
            // }
        }
    });
}

var validar_documento = function () {
    $('#num_documento').on('change', function () {
        var documento = $(this).val();
        var lista = JSON.parse(localStorage.getItem('lista-personas'));
        var pasa = true;
        lista.forEach(element => {
            if (element.num_documento === documento) {
                pasa = false;
                alertify.error('Lo sentimos esta persona ya se encuentra creada');
                $('#crear_persona').css('display', 'none');
                $('#num_documento').focus();
                return;
            }
        });
        if (pasa) {
            $('#crear_persona').css('display', '');
        }
    });
}

var valida_correo = function () {
    $('#correo').on('blur', function () {
        var correo = $(this).val();
        var respu = ValidarMail(correo);
        if (!respu) {
            $('#correo').focus();
        }
    });
}
$(document).ready(function () {
    agregar_imagen();
    alertify.set('notifier', 'position', 'bottom-left');
    elimina_archivo();
});

var agregar_imagen = function () {
    $("#nuevo_cartel").submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#crea_imagen').html();
        var formData = new FormData();
        var files = $('#nueva_imagen')[0].files[0];
        formData.append('file', files);
        if (files === undefined) {
            alertify.error('el campo foto es requerido');
            return;
        }
        btn_procesando('crea_imagen');
        $.ajax({
            "url": `${PATH_NAME}/configuracion/insertar_cartelera`,
            "type": 'POST',
            "data": formData,
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                limpiar_formulario('nuevo_cartel', 'input');
                if (res.status == 1) {
                    alertify.success(res.msg);
                } else {
                    alertify.error(res.msg);
                }
                btn_procesando('crea_imagen', obj_inicial, 1);
                location.reload();
            }
        });
    });
}

var elimina_archivo = function () {
    $('.elimina').on('click', function () {
        var archivo = $(this).val();
        $.ajax({
            "url": `${PATH_NAME}/configuracion/insertar_cartelera`,
            "type": 'POST',
            "data": { archivo },
            "success": function (res) {
                if (res.status == 1) {
                    alertify.success(res.msg);
                } else {
                    alertify.error(res.msg);
                }
                location.reload();
            }
        });
    });
}
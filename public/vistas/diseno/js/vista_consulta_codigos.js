$(document).ready(function () {
    $('.select_2').css('width', '100%');
    $('.select_2').select2();
    busueda_codigo();
});
// recuerda que no dejamos marrado el adhesivo al material por que pueden buscar solo las del adhesivo

function busueda_codigo() {
    $('#formulario_busqueda').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $(`#buscar_cod`).html();
        var formulario = $("#formulario_busqueda").serializeArray();
        var validar = false;
        formulario.forEach(element => {
            if (element.value != '') { validar = true }
        });
        if (validar) {
            btn_procesando('buscar_cod');
            $.ajax({
                url: `${PATH_NAME}/busqueda_codigo`,
                type: "GET",
                data: formulario,
                success: function (res) {
                    btn_procesando_tabla(`buscar_cod`, obj_inicial, 1);
                    datos_tabla(res);
                }
            })

        } else {
            alertify.error('¡Debe llenar al menos un campo!')
        }
    })

}

function datos_tabla(data) {
    console.log(data);buscar_cod
    var tabla = $('#tb_resultados').DataTable({
        data: data,
        dom: 'Bflrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
            tittleAttr: ' Exportar a exel',
            className: 'btn btn-success',
        }],
        "columns": [
            { "data": "id_productos" },
            { "data": "codigo_producto" },
            { "data": "tipo_etiqueta" },
            { "data": "ancho" },
            { "data": "alto" },
            { "data": "tamano" },
            { "data": "forma" },
            { "data": "cavidades" },
            { "data": "material" },
            { "data": "adhesivo" },
            { "data": "grafes" },
            { "data": "descripcion_productos" },
            { "data": "ficha_tecnica_produc" },// puede venir vacio
            { "data": "ubica_ficha" },// puede venir vacio
        ]
    })
}


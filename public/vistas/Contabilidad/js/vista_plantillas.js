$(document).ready(function () {
    alertify.set('notifier', 'position', 'bottom-left');
    // select2();
    consulta_produccion();
});

var consulta_produccion = function () {
    $('#form_consulta_fecha').submit(function (e) {
        e.preventDefault();
        var form = $(this).serializeArray();
        var valida_form = validar_formulario(form);
        if (valida_form) {
            form = $(this).serialize();
            $.ajax({
                url: `${PATH_NAME}/contabilidad/consulta_ordenes_embo_entregadas`,
                type: "POST",
                data: form,
                success: function (respu) {
                    var data_materia_prima = [];
                    var data_items_op = [];
                    respu.forEach(element => {
                        element.salida_mp.forEach(element1 => {
                            data_materia_prima.push(element1);
                        });
                        element.items_op.forEach(element2 => {
                            data_items_op.push(element2);

                        });
                    });
                    salida_materia_prima(data_materia_prima);
                    entrada_items_op(data_items_op);
                    $('#tb_op_entregadas_contab').DataTable({
                        "data": respu,
                        dom: 'Bflrtip',
                        buttons: [{
                            extend: 'excelHtml5',
                            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
                            tittleAttr: ' Exportar a exel',
                            className: 'btn btn-success',
                        }],
                        "columns": [
                            { "data": "empresa" },
                            { "data": "encabezado" },
                            { "data": "campo_vacio" },
                            { "data": "num_produccion" },
                            { "data": "fecha_crea_actividad" },
                            { "data": "tercero" },
                            { "data": "nit" },
                            { "data": "fecha_crea_actividad" },
                            { "data": "fecha_crea_actividad" },
                            { "data": "entra_sale" },
                            { "data": "abierto_cerrado" },
                            { "data": "distribucion" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "codigo_producto" },
                            { "data": "principal" },
                            { "data": "unidad" },
                            { "data": "q_etiquetas" },
                            { "data": "q_etiquetas" },
                            { "data": "entra_sale" },
                            { "data": "por_distribucion" },
                            { "data": "campo_vacio" },
                            { "data": "campo_vacio" },
                            { "data": "abierto_cerrado" },
                            { "data": "abierto_cerrado" },
                            { "data": "fecha_crea_actividad" },
                        ]
                    });
                }
            });
        }
    });
}

var salida_materia_prima = function (data) {
    $('#tb_salida_mp_contab').DataTable({
        "data": data,
        dom: 'Bflrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
            tittleAttr: ' Exportar a exel',
            className: 'btn btn-success',
        }],
        "columns": [
            { "data": "empresa" },
            { "data": "tipo_documento" },
            { "data": "campo_vacio" },
            { "data": "documento" },
            { "data": "fecha_crea_actividad" },
            { "data": "responsable" },
            { "data": "nit" },
            { "data": "nota" },
            { "data": "forma_pago" },
            { "data": "abierto_cerrado" },
            { "data": "abierto_cerrado" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "material" },
            { "data": "bodega" },
            { "data": "unidad" },
            { "data": "cantidad" },
            { "data": "abierto_cerrado" },
            { "data": "abierto_cerrado" },
            { "data": "abierto_cerrado" },
            { "data": "fecha_crea_actividad" },
            { "data": "campo_vacio" },
            { "data": "centro_costo" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "codigo" },
            { "data": "campo_vacio" },
        ]
    });
}

var entrada_items_op = function (data) {
    $('#tb_entrada_producto').DataTable({
        "data": data,
        dom: 'Bflrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Descargar Excel <i class="fas fa-file-excel"></i>',
            tittleAttr: ' Exportar a exel',
            className: 'btn btn-success',
        }],
        "columns": [
            { "data": "empresa" },
            { "data": "tipo_documento" },
            { "data": "campo_vacio" },
            { "data": "documento" },
            { "data": "fecha_crea_actividad" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "responsable" },
            { "data": "nit" },
            { "data": "nota" },
            { "data": "forma_pago" },
            { "data": "abierto_cerrado" },
            { "data": "abierto_cerrado" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "codigo" },
            { "data": "principal" },
            { "data": "unidad" },
            { "data": "q_etiq_reporte" },
            { "data": "abierto_cerrado" },
            { "data": "v_unidad" },
            { "data": "abierto_cerrado" },
            { "data": "fecha_crea_actividad" },
            { "data": "n_produccion" },
            { "data": "centro_costo" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
            { "data": "campo_vacio" },
        ]
    });
}
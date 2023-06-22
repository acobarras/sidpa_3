var inicio_portafolio = function (data) {
    var id_cli_prov = data.id_cli_prov;
    var obj_inicial = $(`#crear_pedidos_cli${data.id_cli_prov}`).html();
    btn_procesando_tabla(`crear_pedidos_cli${data.id_cli_prov}`);
    $.ajax({
        url: `${PATH_NAME}/comercial/valida_facturas`,
        type: "POST",
        data: { id_cli_prov },
        success: function (res) {
            var respu = false;
            if (res.status == -1) {
                if (res.facturas_vencidas != undefined) {
                    $(".facturas_vencidas").css('display', '');
                    var facturas_vencidas = res.facturas_vencidas;
                    $('#facturas_vencidas').DataTable({
                        "data": facturas_vencidas,
                        "columns": [
                            { "data": "num_factura" },
                            { "data": "nit" },
                            { "data": "nombre_empresa" },
                            { "data": "fecha_factura" },
                            { "data": "fecha_vencimiento" },
                            {
                                "data": "iva",
                                "render": function (data, type, row) {
                                    if (row["iva"] == 1) {
                                        return 'Si';
                                    } else {
                                        return 'No';
                                    }
                                }
                            },
                            { "data": "total_etiquetas" },
                            { "data": "total_cintas" },
                            { "data": "total_etiq_cint" },
                            { "data": "total_alquiler" },
                            { "data": "total_tecnologia" },
                            { "data": "total_factura" },
                            { "data": "dias_mora" },
                            {
                                "data": "asesor",
                                "render": function (data, type, row) {
                                    var asesor = row["nombre"] + " " + row["apellido"];
                                    return asesor;
                                }
                            },
                        ],
                    });
                } else {
                    $(".facturas_vencidas").css('display', 'none');
                }

                $("#msg").empty().html(res.msg);

                $("#ModalNOTIFICACIONESPEDIDO").modal("show");
            }
            if (res.status == 1) {
                respu = true;
            }
            btn_procesando_tabla(`crear_pedidos_cli${data.id_cli_prov}`, obj_inicial, 1);
            paso_crea_pedido(respu,data);
        }
    });
}

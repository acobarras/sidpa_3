$(document).ready(function () {
    consultar_operario();
    $('.datepicker').datepicker({ minDate: new Date('1999/10/25') });
    consultar_fechas();
    alertify.set('notifier', 'position', 'bottom-left');
});

var consultar_operario = function () {
    var table = $("#tabla_operarios").DataTable({
        "ajax": `${PATH_NAME}/produccion/consulta_operario_programacion`,
        "columnDefs": [
            { className: 'text-center', targets: [0, 1] }
        ],
        "columns": [{
            "data": "operario",
            render: function (date, type, row) {
                return `${row['nombres']} ${row['apellidos']}`;
            }
        },
        {
            "defaultContent":
                `<button type="button" class="seguimiento btn btn-info" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-search"></i>
            </button>`,
            "className": "text-center"
        }
        ],
    });

    consulta_seguimiento("#tabla_operarios tbody", table);
    // mostrar_turnos('#dt_table_operarios tbody', table);
}

function format(d, persona) {
    var respu = `
    <div class="modal-header">
	    <h3 class="modal-title text-danger" style="text-align: center;" id="exampleModalLabel">OPERARIO : <span style="color: #3932a9">${persona}</span></h3>
	</div>
    <table class="table table-bordered table-responsive table-hover tabla_edita" cellspacing="0" width="100%" id="tabla-seguimiento${d}">
        <thead class="thead-dark">
            <tr>
                <th>Horas</th>
                <th>Turno</th>
                <th>Fecha </th>
                <th>Maquina</th>
                <th>Borrar</th>
            </tr>	
        </thead>
        <tbody>
        </tbody>
    </table>`;
    return respu;
}

var detailRows = []; //Cunado se requiere poder ver mas de 

var consulta_seguimiento = function (tbody, table) {
    $(tbody).on('click', 'button.seguimiento', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var idx = $.inArray(tr.attr('id'), detailRows);
        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('details');
            detailRows.splice(idx, 1);
        }
        else {
            var data = $('#tabla_operarios').DataTable().row($(this).parents("tr")).data();
            var persona = `${data.nombres} ${data.apellidos}`;
            row.child(format(data.id_persona, persona)).show(); // Carga el encabezado de la tabla para poder recibir los datos 
            tr.addClass('details');
            var tabla_2 = $(`#tabla-seguimiento${data.id_persona}`).DataTable({
                'data': data.turnos_operario,
                "order": [[2, 'desc']],
                columns: [
                    {
                        data: "turno_hora", render: function (data, type, row) {
                            return `<input class="form-control validate_horas" type="text" value="${row.turno_hora}" style="width: 20%;"><b>Horas</b>`;
                        }
                    },
                    { data: "horario_turno" },
                    { data: "fecha_program" },
                    { data: "nombre_maquina" },
                    {
                        data: "turno_hora", render: function (data, type, row) {
                            return `<button class="btn btn-danger eliminar_fila_turno"><i class="fa fa-trash-alt"></i></button>`;
                        }
                    }
                ]
            });
            // Add to the 'open' array
            if (idx === -1) {
                detailRows.push(tr.attr('id'));
            }
            modificar_turno(`#tabla-seguimiento${data.id_persona} tbody`, tabla_2);
            eliminar_data_turno(`#tabla-seguimiento${data.id_persona} tbody`, tabla_2);
        }
    });
}

var eliminar_data_turno = function (tbody, table) {
    $(tbody).on('click', `tr button.eliminar_fila_turno`, function (e) {
        // e.preventDefault();
        var data = table.row($(this).parents("tr")).data();
        var em = $(this);
        alertify.confirm('ALERTA', 'Esta seguro de eliminar el registro ?',
            function () {
                $.ajax({
                    type: "POST",
                    url: `${PATH_NAME}/produccion/eliminar_registro_operario`,
                    data: { id: data.id_program_operario },
                    success: function (response) {
                        em.parent('td').parent('tr').remove();
                        alertify.success('Registro Eliminado Correctamente');
                    }
                });
            },
            function () {
                alertify.error('Cancel');
            }
        );
    });
}

var modificar_turno = function (tbody, table) {
    $(tbody).on('blur', 'input.validate_horas', function () {
        var data = table.row($(this).parents("tr")).data();
        var dato = $(this).val();
        var envio = {
            'data': data,
            'cambio': dato
        };
        $.ajax({
            type: "POST",
            url: `${PATH_NAME}/produccion/modificar_turno_operario`,
            data: envio,
            success: function (response) {
                console.log(data.id_persona);
                $('#tabla_operarios').DataTable().ajax.reload();
                alertify.success('Modificación correcta');
            }
        });
    });
}

var consultar_fechas = function () {
    $('#consultar_fechas').on('click', function (e) {
        e.preventDefault();
        var fecha_inicio = $('#fecha_inicio').val();
        var fecha_final = $('#fecha_fin').val();
        var datos = {
            fecha_inicio,
            fecha_final
        };
        $.ajax({
            type: "POST",
            url: `${PATH_NAME}/produccion/consultar_fechas_operarios`,
            data: datos,
            success: function (response) {
                $('#tabla_fechas').DataTable({
                    "dom": "Bfrtip",
                    "buttons": [
                        "copy", "excel", "pdf"
                    ],
                    "data": response.data,
                    "columnDefs": [
                        { className: 'text-center', targets: [0, 1, 2, 3, 4, 5] }
                    ],
                    "columns": [
                        { "data": "nombres" },
                        { "data": "apellidos" },
                        { "data": "horario_turno" },
                        { "data": "turno_hora" },
                        { "data": "fecha_program" },
                        { "data": "nombre_maquina" }
                    ],
                });

            }
        });

    });
}
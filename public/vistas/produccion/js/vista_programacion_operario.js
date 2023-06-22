$(document).ready(function () {
    select_2();
    listar_operarios();
    agregar_operario();
    eliminar_data();
    agregar_fechas();
    programar_fechas();
});
DATA_FINAL = [];

var listar_operarios = function () {
    $.ajax({
        type: "POST",
        url: `${PATH_NAME}/produccion/listar_operarios`,
        success: function (response) {
            var items = '<option value="0"></option>';
            var maquinas = '<option value="0"></option>';
            for (let i = 0; i < response.operario.length; i++) {
                items += `<option value='${JSON.stringify(response.operario[i])}'>${response.operario[i].nombres} ${response.operario[i].apellidos}</option>`;
            }
            $('#operario').append(items);
            $('#operario').select2({ theme: "classic" });
            for (let j = 0; j < response.maquinas.length; j++) {
                maquinas += `<option value='${JSON.stringify(response.maquinas[j])}'>${response.maquinas[j].nombre_maquina}</option>`;
            }
            $('#maquina').append(maquinas);
            $('#maquina').select2({ theme: "classic" });
        }
    });
}

var agregar_operario = function () {
    $('#agregar_operario').on('click', function (e) {
        e.preventDefault();
        var operario = JSON.parse($('#operario').val());
        var maquina = JSON.parse($('#maquina').val());
        if (operario == '' || maquina == '') {
            return;
        }
        DATA_FINAL.push(
            {
                id_persona: operario.id_persona,
                id_maquina: maquina.id_maquina,
                nombres: operario.nombres,
                apellidos: operario.apellidos,
                nombre_maquina: maquina.nombre_maquina,
            }
        );
        cargar_tabla();
        limpiar_formulario('form_datos', 'select');
    });
}

var cargar_tabla = function () {
    $("#tabla_operario").DataTable({
        "data": DATA_FINAL,
        "columns": [
            {
                "data": "empleado", render: function (data, type, row) {
                    return `${row.nombres} ${row.apellidos}`;
                }
            },
            { "data": "nombre_maquina" },
            {
                "data": "boton", render: function (data, type, row) {
                    return `<button class="btn btn-danger eliminar_fila" data-id="${row.id_persona}" ><i class="fa fa-times"></i></button>`;
                }
            },
        ],
    });
}

var eliminar_data = function () {
    $('#tabla_operario tbody').on("click", "tr button.eliminar_fila", function () {
        var id = $(this).attr('data-id');
        DATA_FINAL.forEach((element, i) => {
            if (element.id_persona == id) {
                DATA_FINAL.splice(i, 1);
            }
        });
        cargar_tabla();
    });
}

var agregar_fechas = function () {
    $('#guia_dias').on('keyup', function () {
        var valor = $(this).val();
        if (valor > 10) {
            $(this).focus();
            $(this).val('');
            return;
        }
        var fechas = '';
        for (let i = 0; i < valor; i++) {
            fechas += `<tr><td><input autocomplete="off" class="form-control fecha_piker" type="text" ></td></tr>`;
        }
        $('#fechas-td').empty().html(fechas);
        $('.fecha_piker').datepicker({ dateFormat: 'yy-mm-dd' });
    });

}

var programar_fechas = function () {

    $('#programar').on('click', function () {
        DATA_FECHAS = [];
        $('#table-fechas tr').each(function () {
            var fecha = $(this).find('td').eq(0).children('input').val();
            if (fecha != undefined) {
                DATA_FECHAS.push({ fecha });
            }
        });
        var turno = $('#turnos').val();
        var horario = $('select[name="turnos"] option:selected').text();
        // --------------------------------------------------------------
        if (DATA_FINAL.length == 0 || DATA_FECHAS.length == 0) {
            alertify.error('Debe ingresar todos los datos solicitados!!');
            return;
        }
        if(turno == '') {
            alertify.error('Se requiere el turno para continuar');
            return;
        }
        $.ajax({
            type: "POST",
            url: `${PATH_NAME}/produccion/registrar_programacion_operario`,
            data: { DATA_FECHAS, DATA_FINAL, turno, horario },
            success: function (response) {
                var items = '<ul>';
                for (let i = 0; i < response.length; i++) {
                    items += `<li>${response[i]}</li>`;
                }
                items += '</ul>';
                // -----------------
                alertify.alert('INFORMACION ', items);
                DATA_FINAL = [];
                DATA_FECHAS = [];
                cargar_tabla();
                turno = '';
                horario = '';
                // -----------------
                $('#turnos').val('');
                $('#turnos').change();
                // -----------------
                $('#guia_dias').val('');
                // -----------------                
                $('#fechas-td').empty().html('');

            }
        });
    });
}


const REG_EXP_ESPACIOS = /^\S+$/; //expresion regular para validar el que no tenga espacios.
const IVA = 0.19; // constante del iva 
const FECHA_HOY = document.getElementById('fecha_hoy').value;
const HORA_HOY = document.getElementById('hora_hoy').value;
const REG_EXP_NUMEROS = /^([0-9.])*$/; //expresion regular para validar el que solo tenga numeros o puntos.
const INVENTARIO = 1;
const PINONES = 3.175;
const isMobile = {
    Android: function () {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function () {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function () {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function () {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function () {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function () {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};
var array_productos = []; // constante para agregar productos al local storage pertenece al modulo comercial "mis pedidos".
// estilos de alertify 
alertify.defaults.transition = "slide";
alertify.defaults.theme.ok = "btn btn-primary";
alertify.defaults.theme.cancel = "btn btn-danger";
alertify.defaults.theme.input = "form-control";
// Este fracmento de codigo ayuda para que los titulos del datatable se mantengan ajustados en las vistas de link de navegación
$('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});
// Este fracmento de codigo ayuda para que los titulos del datatable se mantengan ajustados en los modales
$(document).on('shown.bs.modal', function (e) {
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});
// funcion de recarga de la pagina del modal que se encuentra en la hoja "footer" para notificar y recargar
$(".btn-recarga").click(function (e) {
    e.preventDefault();
    location.reload();
});
// Globales de chart js para los label de las graficas
Chart.register(ChartDataLabels);
Chart.defaults.set('plugins.datalabels', {
    color: '#FFFF'
});
// idioma de data table
var idioma = {
    "sProcessing": "",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "decimal": ",",
    "thousands": ".",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando registros del _START_ al _END_",
    "sInfoEmpty": "Mostrando registros del 0 al 0  ",
    "sInfoPostFix": "",
    "sInfoFiltered": "",
    "sSearch": "<i class='fa fa-search'></i>",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": `<img class=" border border-1 rounded-circle img-fluid mx-2" alt="Responsive image" src="${IMG}${PROYECTO}/login/cargaload.gif" width="300">`,
    "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};
// extenciones por defecto para datatable
$.extend($.fn.dataTable.defaults, {
    "pageLength": 25,
    "destroy": true,
    "deferRender": true,
    "responsive": true,
    "scrollX": true,
    "stateSave": true,
    "language": idioma
});

// Funcion para validar cualquier formulario con excepcion del name que desea que no sea validado dentro de un array
const validar_formulario = function (form, exception = '') {
    for (var i = 0; i < form.length; i++) {
        var indice = exception.indexOf(form[i].name); // Trae la posicion donde se encuenta en el array
        if (form[i].name != exception[indice]) {
            if (form[i].value == '' || form[i].value == 0) {
                $(`#${form[i].name}`).focus();
                var text = $(`label[for = '${form[i].name}']`).html();
                alertify.error(`El campo ${text} es requerido.`);
                // alert(`${form[i].name}`);
                return false;
            }
        }
    }
    return true;
}

// VALIDACION DEL FORMATO DEL CORREO ELECTRONICO

const ValidarMail = function (nombre) {
    var respu = true;
    var expRegEmail = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    if (!expRegEmail.test(nombre)) {
        alertify.error('Los datos ingresados no son un correo');
        respu = false;
    }
    return respu;
}
// funcion para validar el celular en el formato "(313)1111111".
const ValidarCelular = function (nombre) {
    // funcion que coloca parentesis en el celular
    $(`#${nombre}`).val(function (i, valor) {
        return valor.replace(/\D/g, "")
            .replace(/^([0-9]{3})/g, '($1)').replace(/^([0-9]{3})/g, '-$1-');
    });
}


/* 
El objeto inicial se captura de la siguiente manera:
*se pasa el id del boton 
*ejemp de objeto inicial: var obj_inicial = $(id_del objeto).html();
*la posicion se envia diferente de vacio para dejar el boton inicial
 */
const btn_procesando = function (id_boton, obj_inicial, posicion = '') {
    if (posicion == '') {
        $(`#${id_boton}`).attr("disabled", true);
        $(`#${id_boton}`).html(`<div class="spinner-border text-light spinner-border-sm" role="status"><span class="visually-hidden"></span></div> Procesando...`);
    } else {
        $(`#${id_boton}`).attr("disabled", false);
        $(`#${id_boton}`).html(obj_inicial);
    }
}
const btn_cargando = function (id_boton, obj_inicial, posicion = '') {
    if (posicion == '') {
        $(`#${id_boton}`).attr("disabled", true);
        $(`#${id_boton}`).html(`<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>`);
    } else {
        $(`#${id_boton}`).attr("disabled", false);
        $(`#${id_boton}`).html(obj_inicial);
    }
}
const btn_procesando_tabla = function (id_boton, obj_inicial, posicion = '') {
    if (posicion == '') {
        $(`#${id_boton}`).attr("disabled", true);
        $(`#${id_boton}`).html(`<div class="spinner-border text-light spinner-border-sm" role="status"><span class="visually-hidden"></span></div>`);
    } else {
        $(`#${id_boton}`).attr("disabled", false);
        $(`#${id_boton}`).html(obj_inicial);
    }
}

// funcion pára limpiar el formulario que recibe el id del formulario y el tipo de elemento a vacias ejemplo: "input"
const limpiar_formulario = function (id_formulario, elemento) {
    if (elemento == 'select') {
        $(`#${id_formulario} ${elemento}`).each(function () {
            $(this).val(0).trigger('change');
        });
    } else if (elemento == 'input' || elemento == 'textarea') {
        $(`#${id_formulario} ${elemento}`).each(function () {
            $(this).val('');
        });

    }
}

// El formulario no usa terminaciones en el id de cada elemento a modificar
const rellenarFormulario = function (data) {
    $.each(data, function (name, value) {
        $(`#${name}`).val(value).trigger('change');
    });
}

// El formulario debe tener la terminacion _modifi en el id de cada elemento a modificar
const rellenar_formulario = function (data) {
    $.each(data, function (name, value) {
        $(`#${name}_modifi`).val(value).trigger('change');
    });
}

// El formulario debe tener la terminacion _modifi en el id de cada elemento a modificar
const rellenar_span = function (data) {
    $.each(data, function (name, value) {
        $(`#${name}`).empty().html(value);
    });
}

// Valida que un input no tenga espacios en blanco debe tener un <span></span> con el id donde quiere que se muestre el mensaje 
const elimina_espacio = function (id_input, id_mensaje) {
    $(`#${id_input}`).keyup(function () {
        var codigo = $(`#${id_input}`).val();
        var span_codigo = $(`#${id_mensaje}`);
        var newcod = $(`#${id_input}`).val().replace(/ /g, "");
        $(`#${id_input}`).val(newcod);
        if (!REG_EXP_ESPACIOS.test(codigo)) {
            span_codigo.empty().html('Este campo no debe llevar espacios.');
            return;
        } else {
            valida = true;
            span_codigo.empty();
        }
    });
}

/*
 * cargar la busqueda en los selectores con la clase asignada (select_2)
 */
var select_2 = function () {
    $('.select_2').select2();
};

// Remover los caracteres de un input
const remueve_caracteres = function (id_input) {
    $(`#${id_input}`).keyup(function (e) {
        $(this).val(function (i, value) {
            return value.replace(/[$<>#"'{}¿?¡&%!-]+/g, '');
        });
    });
}

//funcion para quitar puntos de un input
function sin_puntos(element) {
    $(`#${element}`).keyup(function () {
        var n = $(this).val();
        if (n != 0) {
            n = n.replace(/\./g, '');
        }
        $(`#${element}`).val(n);
    });
}
//funcion para quitar puntos de un input
function solo_numeros(element) {
    $(`#${element}`).keyup(function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
}
//funcion para quitar puntos de un input
function solo_numeros_coma(element) {
    $(`#${element}`).keyup(function () {
        this.value = this.value.replace(/[^0-9,]/g, '');
    });
}
//funcion para sacar la hora y fecha actual tipo reloj

function actual() {
    fecha = new Date(); //Actualizar fecha.
    hora = fecha.getHours(); //hora actual
    minuto = fecha.getMinutes(); //minuto actual
    segundo = fecha.getSeconds(); //segundo actual
    if (hora < 10) { //dos cifras para la hora
        hora = "0" + hora;
    }
    if (minuto < 10) { //dos cifras para el minuto
        minuto = "0" + minuto;
    }
    if (segundo < 10) { //dos cifras para el segundo
        segundo = "0" + segundo;
    }
    //ver en el recuadro del reloj:
    mireloj = hora + " : " + minuto + " : " + segundo;
    return mireloj;
}

function actualizar() { //función del temporizador
    mihora = actual(); //recoger hora actual
    $("#reloj").empty().html(mihora);
}
setInterval(actualizar, 1000); //iniciar temporizador
//-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -
/*
 * recargar lista de departamentos traer el departamento especifico de acuerdo al pais
 */
var recargarListadep = function (departamento, element, departamento_elegido) {
    $.ajax({
        url: `${PATH_NAME}/comercial/consultar_departamento_especifico`,
        type: "POST",
        data: { departamento },
        success: function (r) {
            var item = "<option value='0'>Elija Un Departamento</option>";
            r.forEach(element => {
                if (element.id_departamento == departamento_elegido) {
                    item += /*html*/
                        ` <option value="${element.id_departamento}" selected>${element.nombre}</option>`;
                } else {
                    item += /*html*/
                        ` <option value="${element.id_departamento}">${element.nombre}</option>`;
                }
            });
            $(`#${element}`).empty().html(item);
        }
    });
};
/*
 * recargar ciudad al crear direcciones o clientes
 */
var recargarListaCiud = function (ciudad, element, ciudad_elegida) {
    $.ajax({
        url: `${PATH_NAME}/comercial/consultar_ciudad_especifica`,
        type: "POST",
        data: { ciudad },
        success: function (r) {
            var item = "<option value='0'>Elija Un Ciudad</option>";
            r.forEach(element => {
                if (element.id_ciudad == ciudad_elegida) {
                    item += /*html*/
                        ` <option value="${element.id_ciudad}" selected> ${element.nombre}</option>`;
                } else {
                    item += /*html*/
                        ` <option value="${element.id_ciudad}"> ${element.nombre}</option>`;
                }
            });
            $(`#${element}`).empty().html(item);
        }
    });
};

//idioma datapiker 

$.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '< Ant',
    nextText: 'Sig >',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    weekHeader: 'Sm',
    dateFormat: 'yy-mm-dd',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    changeMonth: true, //ACTIVA INPUT MESES
    changeYear: true, //ACTIVA INPUT AÑOS
    yearSuffix: '',
    minDate: 0, // BLOQUE FECHAS ANTERIORES A LA ACTUAL
};
$.datepicker.setDefaults($.datepicker.regional['es']);

// Valida si un radio esta elegido por el name del radio
function RadioElegido(name_radio) {
    var resultado = "ninguno";
    var OpSelecionada = document.getElementsByName(name_radio);
    for (var i = 0; i < OpSelecionada.length; i++) { // Recorremos todos los valores del radio button para encontrar el seleccionado
        if (OpSelecionada[i].checked)
            resultado = OpSelecionada[i].value;
    }
    return resultado;

}

function RadioElegidoAttr(name_radio, atributo) {
    var resultado = "ninguno";
    var OpSelecionada = document.getElementsByName(name_radio);
    for (var i = 0; i < OpSelecionada.length; i++) { // Recorremos todos los valores del radio button para encontrar el seleccionado
        if (OpSelecionada[i].checked)
            resultado = OpSelecionada[i].getAttribute(atributo);
    }
    return resultado;

}

var consulta_asesores = function (data, asesor, elemento) {
    $.ajax({
        url: `${PATH_NAME}/contabilidad/consulta_asesores`,
        type: "POST",
        data: { data },
        success: function (res) {
            var usuarios = '<option value="0">Selecciona un asesor</option>';
            res.forEach(element => {
                if (asesor == element.id_persona) {
                    usuarios += /*html*/ `
    <option value="${element.id_persona}" selected> ${element.nombres} ${element.apellidos}</option> `;
                } else {
                    usuarios += /*html*/ `
    <option value="${element.id_persona}"> ${element.nombres} ${element.apellidos}</option> `;
                }
            });
            $(`#${elemento}`).empty().html(usuarios);
        }
    });
}

const number_format = function (amount, decimals) {
    amount += ''; // por si pasan un numero en vez de un string
    amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto
    decimals = decimals || 0; // por si la variable no fue fue pasada
    // si no es un numero o es igual a cero retorno el mismo cero
    if (isNaN(amount) || amount === 0)
        return parseFloat(0).toFixed(decimals);
    // si es mayor o menor que cero retorno el valor formateado como numero
    amount = '' + amount.toFixed(decimals);
    var amount_parts = amount.split(','),
        regexp = /(\d+)(\d{3})/;
    while (regexp.test(amount_parts[0]))
        amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');

    return amount_parts.join('.');
}

const colorrgb = function (q) {
    var colores = [];
    for (let j = 0; j < q; j++) {
        var rgb = [];
        for (var i = 0; i < 3; i++) {
            rgb.push(Math.floor(Math.random() * 255));
        }
        var color = `rgb(${rgb.join(',')})`;
        colores.push(color);

    }
    return colores;
}

var informa_diferencia = function (row) {

    let array = [];
    if (row['difer_mas'] == 1) {
        array.push('<b> + </b> ');
    }
    if (row['difer_menos'] == 1) {
        array.push('<b> - </b> ');
    }
    if (row['difer_ext'] == 1) {
        array.push('<b> Ext </b>');
    }
    if (row['grupo'] == 'TECNOLOGIA') {
        array = [];
        row['porcentaje'] = '';
    }
    return row['porcentaje'] + array.join(' ');
}


var captura_firma = function (canvas, signaturePad, boton_canvas) {
    /*
    Tener en cuenta que se debe iniciar estas variables en el js que se requiere y que no este dentro del $(document).ready(function () {});
    tenga en cuenta que por cada lienzo se debe crear las variables y a los boton_canvas es una clase la cual obtiene su value donde el 1 es
    el boton de conservar y el 2 es para borrar 
    var canvas = document.getElementById('signature-canvas');
    var signaturePad = new SignaturePad(canvas, { penColor: 'rgb(0, 0, 0)' });
    */
    var respu = '';
    if (boton_canvas == 1) {
        // la firma es un lienzo vacío
        if (signaturePad.isEmpty()) {
            respu = "Lo sentimos se requiere una firma";
        } else {
            var imgStr = signaturePad.toDataURL('image/png');
            imgStr = imgStr.substring(22, imgStr.length);
            function resizeCanvas() {
                var ratio = Math.max(window.devicePixelRatio || 1, 1); // Borrar el lienzo
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                signaturePad.clear();
            }
            window.onresize = resizeCanvas;
            resizeCanvas();
            respu = imgStr;
            // $('#muestro_firma').attr('src',`data:image/png;base64,${imgStr}`);
        }
    } else {
        signaturePad.clear();
    }
    return respu;
}

var redondear = function (num, unidades) {
    var redondea = Math.pow(10, unidades);
    var respu = Math.round(num / redondea) * redondea;
    return respu;
}

var ObservacionPQR = function () {
    $(`.observaciones_pqr tbody`).on('click', `button.codigo_motivo`, function () {
        var data = $(`.observaciones_pqr`).DataTable().row($(this).parents("tr")).data();
        $(`#observaciones`).empty().html(data.observacion);
        $(`#codigoMotivo`).modal('toggle');
        $(`.boton_codigo_motivo`).attr('data', JSON.stringify(data.id_pqr));
        CKEDITOR.instances.observaciones.setData(data.observacion);
    });
}

var nueva_ubicacion = function () {
    $('#ubicacion').on('change', function () {
        var nueva_ubicacion = $(this).val();
        if (nueva_ubicacion == 'nuevo') {
            $.post(`${PATH_NAME}/almacen/vista_ubicaciones`, {
                datos: nueva_ubicacion
            },).done(function (respu) {
                $('#respuesta_vista').empty().html(respu);
                $("#Modal_crea_ubicacion").modal("show");
                crea_ubicacion();
            });
        }
    });
}

var crea_ubicacion = function () {
    $('#form_ubicacion').submit(function (e) {
        e.preventDefault();
        var obj_inicial = $('#btn_crea_ubicacion').html();
        btn_procesando('btn_crea_ubicacion');
        var formu = $(this).serializeArray();
        valida = validar_formulario(formu);
        if (valida) {
            $.ajax({
                "url": `${PATH_NAME}/almacen/crear_ubicacion`,
                "type": 'POST',
                "data": formu,
                "success": function (respu) {
                    if (respu.status) {
                        $("#Modal_crea_ubicacion").modal("show");
                        location.reload();
                    } else {
                        btn_procesando('btn_crea_ubicacion', obj_inicial, 1);
                        alertify.error('Tenemos problemas al insertar comuniquese con su desarrollador');
                    }
                    btn_procesando('btn_crea_ubicacion', obj_inicial, 1);
                }
            });
        }
    });
}

var tamano_codigo = function (cod) {
    var conector = 'X';
    var conector2 = '-';
    var posicion = cod.indexOf(conector);
    var posicion2 = cod.indexOf(conector2);
    var ancho = cod.substr(0, posicion);
    ancho = parseFloat(ancho.replace(/,/g, '.'));
    var alto = cod.substr(posicion + 1, posicion2 - posicion - 1);
    alto = parseFloat(alto.replace(/,/g, '.'));
    var res = {
        'ancho': ancho,
        'alto': alto
    };
    return res;
}
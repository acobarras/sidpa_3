<?php

const IVA = 0.16;
const PORCENTAJE_SOBRE_CUPO = 0.20;
const GAP_LATERAL = 3.5;
const RESPONSABLE = '1013579582';
const NIT_CONTABLE = '830033050';
const CONST_TINTA = 0.93;
const FORM_DISENOCOD = "https://docs.google.com/forms/d/e/1FAIpQLSc3MWzDPRkevsupH8SYKoLIDeellDGpFqWQ2Uv7wggfw2mDHg/viewform?embedded=true"; //formulario para solicitud de codigo
const FORM_DISENODIS = "https://docs.google.com/forms/d/e/1FAIpQLSe4HlUfabpECFOl7AYZXiFvxP02N8ZbuGTHY9OhVnFPQ66QuA/viewform?embedded=true"; //formulario para solicitud de diseño


define(
    'TIPO_USUARIO',
    array(
        0 => "",
        1 => "Usuario",
        2 => "Empleado",
        3 => "Empleado Rotativo",
        4 => "Lider De Proceso"
    )
);

define(
    'ESTADO_SIMPLE',
    array(
        0 => 'Inactivo',
        1 => 'Activo'
    )
);

define(
    'RUTA_ENTREGA',
    array(
        0 => '',
        1 => 'Norte',
        2 => 'Sur',
        3 => 'Calle 80',
        4 => 'Calle 13',
        5 => 'Remesa',
        6 => 'Centro',
        7 => 'Sin Asignar',
    )
);

define(
    'FORMA_PAGO',
    array(
        0 => 'Ninguno',
        1 => 'Contado Efectivo',
        2 => 'Contado Factura',
        3 => 'Cheque Posfechado',
        4 => 'Credito',
    )
);
define(
    'RECIBE_PARCIAL',
    array(
        0 => "",
        1 => "Si",
        2 => "No"
    )
);

define(
    'DIAS_DADOS',
    array(
        0 => 'Ninguno',
        1 => '15 Días',
        2 => '30 Días',
        3 => '45 Días',
        4 => '60 Días',
        5 => '75 Días',
        6 => '90 Días',
        7 => '120 Días',
    )
);
define(
    'SOLO_DIAS_DADOS',
    array(
        0 => '0',
        1 => '15',
        2 => '30',
        3 => '45',
        4 => '60',
        5 => '75',
        6 => '90',
        7 => '120',
    )
);

define(
    'PERTENECE',
    array(
        0 => 'Ninguno',
        1 => 'Eticomex',
        2 => 'N/A',
        3 => 'Eticomex Especial',
    )
);

define(
    'ID_CON_PEDIDO',
    array(
        1 => 3,
        2 => 4,
        3 => 3,
    )
);

define(
    'LISTA_PERTENECE',
    array(
        0 => 'Ninguno',
        1 => 'Cliente Alto Volumen',
        2 => 'Cliente Medio Volumen',
        3 => 'Cliente Bajo Volumen',
    )
);

define(
    'TIPO_PROVEEDOR',
    array(
        99 => 'Ninguno',
        1 => 'Etiquetas',
        2 => 'Tecnologia',
        3 => 'Otros',
    )
);

define(
    'TIPO_MONEDA',
    array(
        0 => 'Sin asignar',
        1 => 'Pesos',
        2 => 'Dolar',
    )
);

define(
    'MODIFICAR_PEDIDO',
    array(
        'num_pedido' => 'Numero de Pedido',
        'orden_compra' => 'Orden de Compra',
        'id_cli_prov' => 'Cliente',
    )
);
define(
    'PERMISO_VENTA_BOBINA',
    array(
        // 1=>'Edwin Rios',
        27 => 'Catalina Molina'
    )
);

define('FECHA_CIERRE_PEDIDOS', '16:00');

define(
    'ESTADO_PORTAFOLIO',
    array(
        1 => 'Relacionada',
        2 => 'Recibida',
        3 => 'Pagada',
        4 => 'Anulada'
    )
);

define(
    'USU_ANULA_FACTURA',
    array(
        16 => 1, //usuario jenny martinez
        57 => 1, //usuario luisa castañeda
    )
);

define(
    'VALORES_COTIZADOR',
    array(
        'costo_desperdicio' => 1.1,
        'utili_inicial' => .7,
        'utili_medio' => .9,
        'utili_alto' => .8,
        'cinta_calor' => 5200,
        'cinta_frio' => 1800,
        'monto_blanco' => 80, //3610,
        'utili_1tintas' => .9,
        'monto_1tinta' => 120,
        'utili_2tintas' => .80,
        'monto_2tinta' => 190,
        'utili_3tintas' => .70,
        'monto_3tinta' => 290,
        'utili_4tintas' => .60,
        'monto_4tinta' => 400,
        'utili_5tintas' => .50,
        'monto_5tinta' => 550,
        'utili_6tintas' => .45,
        'monto_6tinta' => 570,
        'utili_7tintas' => .40,
        'monto_7tinta' => 580,
        'utili_8tintas' => .35,
        'monto_8tinta' => 590,
        'precio_cyrel' => 80000,
        'ml_req_troq_rotativo' => 1500,
        'troquel_rotativo' => 1500000,
        'troquel_plano' => 220000,
        'precio_clice' => 80000, // Cinta estampado al calor
        'laminado_brillante' => 550,
        'laminado_mate' => 890,
    )
);

define(
    'ROLL_DESPERDICIO',
    array(
        2 => 'no',
        11 => 'si',
    )
);

define(
    'ROLL_DESPERDICIO_1',
    array(
        1 => 'no',
        11 => 'si',
    )
);

define(
    'PREFIJO',
    array(
        6 => 'CC',
        8 => 'FEET',
        9 => 'FAC', // Segunda Empresa
        11 => 'LEET',
        12 => 'LEACOL' // Segunda Empresa
    )
);

define(
    'ID_ACTIVIDAD_PARCIAL',
    array(
        6 => 51,
        8 => 21,
        9 => 21,
        11 => 51,
        12 => 51
    )
);

define(
    'ID_ACTIVIDAD',
    array(
        6 => 52,
        8 => 24,
        9 => 24,
        11 => 52,
        12 => 52
    )
);


// definicion para controlar los estados y las actividades de las entregas

define(
    'ENTREGA',
    array(
        1 => array('estado' => 7, 'id_actividad' => 29),
        2 => array('estado' => 5, 'id_actividad' => 27),
        3 => array('estado' => 4, 'id_actividad' => 19),
        4 => array('estado' => 7, 'id_actividad' => 28), // radicado del documento
        5 => array('estado' => 5, 'id_actividad' => 19), // Devolucion del documento cuando no se pudo radicar
    )
);
define(
    'SOLICITUDES_ALMACEN',
    array("2016")
);

define(
    'DIAS_ESP',
    array(
        "Sunday" => 'Domingo',
        "Monday" => 'Lunes',
        "Tuesday" => 'Martes',
        "Wednesday" => 'Miércoles',
        "Thursday" => 'Jueves',
        "Friday" => 'Viernes',
        "Saturday" => 'Sábado',
    )
);

define(
    'MES_ESP',
    array(
        "January" => 'Enero',
        "February" => 'Febrero',
        "March" => 'Marzo',
        "April" => 'Abril',
        "May" => 'Mayo',
        "June" => 'Junio',
        "July" => 'Julio',
        "August" => 'Agosto',
        "September" => 'Septiembre',
        "October" => 'Octubre',
        "November" => 'Noviembre',
        "December" => 'Diciembre',
    )
);

define(
    'PQR_MES',
    array(
        "January" => 'A',
        "February" => 'B',
        "March" => 'C',
        "April" => 'D',
        "May" => 'E',
        "June" => 'F',
        "July" => 'G',
        "August" => 'H',
        "September" => 'I',
        "October" => 'J',
        "November" => 'K',
        "December" => 'L',
    )
);

define(
    'RESTA_DIAS_PQR',
    array(
        "Sunday" => 3, // 'Domingo',
        "Monday" => 4, // 'Lunes',
        "Tuesday" => 5, //'Martes',
        "Wednesday" => 6, // 'Miércoles',
        "Thursday" => 0, //'Jueves',
        "Friday" => 1, //'Viernes',
        "Saturday" => 2, // 'Sábado',
    )
);

define(
    'COMITE',
    array(
        1 => 'COPASST',
        2 => 'Comite Convivencia',
        3 => 'Brigada'
    )
);

define(
    'CILINDROS',
    array(
        0 => ['cilindro' => 72, 'desarrollo' => 228.6],
        1 => ['cilindro' => 80, 'desarrollo' => 254],
        2 => ['cilindro' => 84, 'desarrollo' => 266.7],
        3 => ['cilindro' => 88, 'desarrollo' => 279.4],
        4 => ['cilindro' => 97, 'desarrollo' => 307.975],
    )
);

define(
    'HOMOLOGO',
    array()
);

define(
    'GRAF_CORTE',
    array(
        0 => ['nombre' => 'SIN GRAF SIN CORTES', 'nombre_corto' => ''],
        1 => ['nombre' => 'GRAFADA', 'nombre_corto' => 'GRAF'],
        2 => ['nombre' => 'CORTE', 'nombre_corto' => 'CORT'],
        3 => ['nombre' => 'GRAFE Y CORTE', 'nombre_corto' => 'GRAF Y CORT'],
        4 => ['nombre' => 'PERFORACIÓN', 'nombre_corto' => 'PERF'],
        5 => ['nombre' => 'GRAFE Y CORTE Y PERFORACIÓN', 'nombre_corto' => 'GRAF CORT Y PERF'],
        6 => ['nombre' => 'GRAFE PERFORACIÓN', 'nombre_corto' => 'GRAF Y PERF'],
        7 => ['nombre' => 'CORTE Y PERFORACIÓN', 'nombre_corto' => 'CORT Y PERF']
    )
);
define(
    'PERMISOS_SOPORTE',
    array(
    )
);
// --------------------terminados diseño --------------------------------

define(
    'TERMINADOS_DISENO',
    array(
        0 => ['nombre' => 'SIN TERMINADO'],
        1 => ['nombre' => 'LAMINADO'],
        2 => ['nombre' => 'ESTAMPADO'],
        3 => ['nombre' => 'IMPRESIÓN VARIABLE'],
        4 => ['nombre' => 'UV TOTAL'],
        5 => ['nombre' => 'UV PARCIAL']
    )
);

define(
    'PREG_CHEQUEO',
    array(
        0 => [
            'pregunta' => '¿El nivel de líquido de frenos se encuentra entre el máximo y el mínimo?',
            'tipo' => 'select', 'name' => 'liquido_frenos', 'respu_valida' => '1'
        ],
        1 => [
            'pregunta' => '¿El nivel del líquido de la dirección (hidráulico) se encuentra entre el máximo y el mínimo?',
            'tipo' => 'select', 'name' => 'liquido_direccion', 'respu_valida' => '1'
        ],
        2 => [
            'pregunta' => '¿El nivel del líquido de refrigeración se encuentra entre el máximo y el mínimo?',
            'tipo' => 'select', 'name' => 'liquido_refrigeracion', 'respu_valida' => '1'
        ],
        3 => [
            'pregunta' => '¿El nivel del líquido del limpiabrisas se encuentra entre el máximo y el mínimo?',
            'tipo' => 'select', 'name' => 'liquido_limpiabrisas', 'respu_valida' => '1'
        ],
        4 => [
            'pregunta' => '¿Los limpiaparabrisas funcionan adecuadamente (movimiento, aspersión de líquido y escobillas)?',
            'tipo' => 'select', 'name' => 'funciona_limpiabrisas', 'respu_valida' => '1'
        ],
        5 => [
            'pregunta' => '¿Las luces direccionales derecha e izquierda funcionan en la parte frontal y posterior?',
            'tipo' => 'select', 'name' => 'direccionales', 'respu_valida' => '1'
        ],
        6 => [
            'pregunta' => '¿Las luces de parqueo, freno, bajas, altas y cocuyos funcionan?',
            'tipo' => 'select', 'name' => 'luces', 'respu_valida' => '1'
        ],
        7 => [
            'pregunta' => '¿Todas las puertas del vehículo abren, cierran y cuentan con sistema funcional para asegurarlas?',
            'tipo' => 'select', 'name' => 'puertas', 'respu_valida' => '1'
        ],
        8 => [
            'pregunta' => '¿El freno de mano y de pedal responden al activarlos?',
            'tipo' => 'select', 'name' => 'freno_mano', 'respu_valida' => '1'
        ],
        9 => [
            'pregunta' => '¿La dirección responde al maniobrar el volante?',
            'tipo' => 'select', 'name' => 'direccion_volante', 'respu_valida' => '1'
        ],
        10 => [
            'pregunta' => '¿El pito suena al activarlo en el volante?',
            'tipo' => 'select', 'name' => 'pito', 'respu_valida' => '1'
        ],
        11 => [
            'pregunta' => '¿Se detectan fugas de aceite o agua en la inspección visual del motor o debajo del vehículo?',
            'tipo' => 'select', 'name' => 'fugas', 'respu_valida' => '2'
        ],
        12 => [
            'pregunta' => '¿Se presentan abolladuras o perforaciones en las latas del vehículo?',
            'tipo' => 'select', 'name' => 'abolladuras', 'respu_valida' => '2'
        ],
        13 => [
            'pregunta' => '¿Se identifican peladuras o desprendimientos de pintura mayores a 5 cm de diámetro?',
            'tipo' => 'select', 'name' => 'desprendimientos', 'respu_valida' => '2'
        ],
        14 => [
            'pregunta' => '¿El cinturón se expande y retrae adecuadamente y se asegura la lengüeta metálica en el anclaje?',
            'tipo' => 'select', 'name' => 'cinturon_funcional', 'respu_valida' => '1'
        ],
        15 => [
            'pregunta' => '¿Se presenta separación entre la zona de carga y la zona de conducción?',
            'tipo' => 'select', 'name' => 'separacion', 'respu_valida' => '1'
        ],
        16 => [
            'pregunta' => '¿Se detectan productos alimenticios o químicos en la zona de carga?',
            'tipo' => 'select', 'name' => 'estado_zona_carga', 'respu_valida' => '2'
        ],
        17 => [
            'pregunta' => '¿El vehículo se encuentra aseado (lavado y limpieza general a nivel interno y externo)?',
            'tipo' => 'select', 'name' => 'vehiculo_aseado', 'respu_valida' => '1'
        ],
        18 => [
            'pregunta' => '¿Evaluación visual de las llantas (no se notan desinfladas ni sin labrado)?',
            'tipo' => 'select', 'name' => 'estado_llantas', 'respu_valida' => '1'
        ],
        19 => [
            'pregunta' => '¿Cuenta con llanta de repuesto?',
            'tipo' => 'select', 'name' => 'llanta_repuesto', 'respu_valida' => '1'
        ],
        20 => [
            'pregunta' => '¿Cuenta con gato hidraulico?',
            'tipo' => 'select', 'name' => 'gato_hidraulico', 'respu_valida' => '1'
        ],
        21 => [
            'pregunta' => '¿Cuenta con guantes?',
            'tipo' => 'select', 'name' => 'guantes', 'respu_valida' => '1'
        ],
        22 => [
            'pregunta' => '¿Cuenta con cruceta?',
            'tipo' => 'select', 'name' => 'cruceta', 'respu_valida' => '1'
        ],
        23 => [
            'pregunta' => '¿Cuenta con dos tacos?',
            'tipo' => 'select', 'name' => 'tacos', 'respu_valida' => '1'
        ],
        24 => [
            'pregunta' => '¿Cuenta con señales reflectivas o triángulos?',
            'tipo' => 'select', 'name' => 'señales', 'respu_valida' => '1'
        ],
        25 => [
            'pregunta' => '¿Cuenta con chaleco reflectivo?',
            'tipo' => 'select', 'name' => 'chaleco', 'respu_valida' => '1'
        ],
        26 => [
            'pregunta' => '¿Cuenta con linterna?',
            'tipo' => 'select', 'name' => 'linterna', 'respu_valida' => '1'
        ],
        27 => [
            'pregunta' => '¿Cuenta con botiquín?',
            'tipo' => 'select', 'name' => 'botiquin', 'respu_valida' => '1'
        ],
        28 => [
            'pregunta' => '¿Cuenta con herramienta básica/Llave expansión?',
            'tipo' => 'select', 'name' => 'herramienta', 'respu_valida' => '1'
        ],
        29 => [
            'pregunta' => '¿Fecha de vencimiento del extintor?',
            'tipo' => 'input', 'name' => 'vencimiento_extintor', 'respu_valida' => '1'
        ],
        30 => [
            'pregunta' => '¿Fecha de vencimiento SOAT?',
            'tipo' => 'input', 'name' => 'vencimiento_soat', 'respu_valida' => '1'
        ],
        31 => [
            'pregunta' => '¿Fecha de vencimiento de la revisión tecno mecánica?',
            'tipo' => 'input', 'name' => 'rv_tecnomecanica', 'respu_valida' => '1'
        ],
        32 => [
            'pregunta' => '¿Fecha de vencimiento de la licencia de conducción?',
            'tipo' => 'input', 'name' => 'vencimiento_licencia', 'respu_valida' => '1'
        ],
        33 => [
            'pregunta' => '¿Fecha ultimo mantenimiento?',
            'tipo' => 'input', 'name' => 'mantenimiento', 'respu_valida' => '1'
        ],
    )
);
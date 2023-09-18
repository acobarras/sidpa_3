<?php
const IVA = 0.19;
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
        1 => 'Acobarras S.A.S',
        2 => 'Acobarras Colombia',
        3 => 'Acobarras Especial',
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
        'utili_inicial' => .65,
        'utili_medio' => .9,
        'utili_alto' => .8,
        'cinta_calor' => 5300,
        'cinta_frio' => 2040,
        'monto_blanco' => 80, //80000,
        'utili_1tintas' => .85,
        'monto_1tinta' => 120, //150000,
        'utili_2tintas' => .8,
        'monto_2tinta' => 190, //250000,
        'utili_3tintas' => .75,
        'monto_3tinta' => 290, //400000,
        'utili_4tintas' => .7,
        'monto_4tinta' => 400, //600000,
        'utili_5tintas' => .65,
        'monto_5tinta' => 550, //900000,
        'utili_6tintas' => .6,
        'monto_6tinta' => 570, //1000000,
        'utili_7tintas' => .55,
        'monto_7tinta' => 580, //1100000,
        'utili_8tintas' => .5,
        'monto_8tinta' => 590, //1200000,
        'precio_cyrel' => 80000,
        'ml_req_troq_rotativo' => 1500,
        'troquel_rotativo' => 1500000,
        'troquel_plano' => 220000,
        'precio_clice' => 80000,
        'tiempo_cobro_pre_prensa' => 3,
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
        8 => 'FEAC',
        9 => 'FAC',
        11 => 'LEAC',
        12 => 'LEACOL'
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
        2 => ['cilindro' => 82, 'desarrollo' => 260.35],
        3 => ['cilindro' => 84, 'desarrollo' => 266.7],
        4 => ['cilindro' => 88, 'desarrollo' => 279.4],
        5 => ['cilindro' => 97, 'desarrollo' => 307.975],
        6 => ['cilindro' => 104, 'desarrollo' => 330.2],
        7 => ['cilindro' => 109, 'desarrollo' => 346.075],
        8 => ['cilindro' => 120, 'desarrollo' => 381],
        9 => ['cilindro' => 128, 'desarrollo' => 306.4],
        10 => ['cilindro' => 136, 'desarrollo' => 431.8],
        11 => ['cilindro' => 167, 'desarrollo' => 530.225],
    )
);

define(
    'HOMOLOGO',
    array(
        ['tipo_material' => '13', 'adh' => 2, 'letra' => 'D']
    )
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
 
// ------------------ permisos de soporte ---------------------------------

define(
    'PERMISOS_SOPORTE',
    array(
        79 => 'JEFE SOPORTE',
        90 => 'COORDINADOR SOPORTE',
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
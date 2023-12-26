<?php

const IVA = 0.19;
const FECHA_CIERRE_PQR = false;
const PORCENTAJE_SOBRE_CUPO = 0.20;
const GAP_LATERAL = 3.5;
const RESPONSABLE = '1013579582';
const NIT_CONTABLE = '830033050';
const ISO = 2;
const TRM_FIJA = 4500;
const FORM_DISENOCOD = "https://docs.google.com/forms/d/e/1FAIpQLSe6GN_6AsdubC-u2p9SG0nqSa1odmMxiqCIANOn8z38sWTvTQ/viewform?usp=sf_link"; //formulario para solicitud de codigo
const FORM_DISENODIS = "https://docs.google.com/forms/d/e/1FAIpQLSekEBbOPXsTfy7BYflF_k0m1XMhvzLlPq6Ql63fkEyetRKvOA/viewform?usp=sf_link"; //formulario para solicitud de diseño

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
        1 => 'Eticaribe S.A.S',
        2 => 'N/A',
        3 => 'Eticaribe Especial',
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
        4 => 1, //usuario jenny martinez
        11 => 1, //usuario luisa castañeda
        9 => 1, //usuario liz torregrosa
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
        8 => 'EMC',
        9 => 'FAC',
        11 => 'LEEMC',
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
    array("6", "22")
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
        ['tipo_material' => '13', 'adh' => 2, 'letra' => 'D'],
        ['tipo_material' => '39', 'adh' => 2, 'letra' => 'D'],
        ['tipo_material' => '14', 'adh' => 2, 'letra' => 'D'],
        ['tipo_material' => '36', 'adh' => 1, 'letra' => 'A'],
        ['tipo_material' => '40', 'adh' => 1, 'letra' => 'C'],
        ['tipo_material' => '16', 'adh' => 0, 'letra' => 'J'],
        ['tipo_material' => '30', 'adh' => 0, 'letra' => 'J'],
        ['tipo_material' => '32', 'adh' => 0, 'letra' => 'J'],
        ['tipo_material' => '41', 'adh' => 0, 'letra' => 'J'],
        ['tipo_material' => '42', 'adh' => 0, 'letra' => 'J'],
        ['tipo_material' => '43', 'adh' => 0, 'letra' => 'J'],
        ['tipo_material' => '29', 'adh' => 1, 'letra' => 'C'],
        ['tipo_material' => '11', 'adh' => 2, 'letra' => 'D'],
        ['tipo_material' => '11', 'adh' => 1, 'letra' => 'A'],
        ['tipo_material' => '35', 'adh' => 4, 'letra' => 'E'],
        ['tipo_material' => '44', 'adh' => 1, 'letra' => 'C'],
        ['tipo_material' => '22', 'adh' => 2, 'letra' => 'D'],
        ['tipo_material' => '23', 'adh' => 2, 'letra' => 'D'],
        ['tipo_material' => '04', 'adh' => 2, 'letra' => 'D'],
        ['tipo_material' => '04', 'adh' => 1, 'letra' => 'B'],
        ['tipo_material' => '33', 'adh' => 4, 'letra' => 'E'],
        ['tipo_material' => '05', 'adh' => 4, 'letra' => 'E'],
        ['tipo_material' => '05', 'adh' => 1, 'letra' => 'B'],
        ['tipo_material' => '07', 'adh' => 1, 'letra' => 'C'],
        ['tipo_material' => '07', 'adh' => 4, 'letra' => 'E'],
        ['tipo_material' => '08', 'adh' => 1, 'letra' => 'B'],
        ['tipo_material' => '09', 'adh' => 1, 'letra' => 'B'],
        ['tipo_material' => '02', 'adh' => 1, 'letra' => 'A'],
        ['tipo_material' => '03', 'adh' => 1, 'letra' => 'C'],
        ['tipo_material' => '03', 'adh' => 4, 'letra' => 'E'],
        ['tipo_material' => '01', 'adh' => 1, 'letra' => 'A'],
        ['tipo_material' => '01', 'adh' => 2, 'letra' => 'D'],
        ['tipo_material' => '01', 'adh' => 3, 'letra' => 'F'],
        ['tipo_material' => '19', 'adh' => 0, 'letra' => 'J'],
        ['tipo_material' => '27', 'adh' => 2, 'letra' => 'D'],
        ['tipo_material' => '25', 'adh' => 2, 'letra' => 'D'],
        ['tipo_material' => '26', 'adh' => 2, 'letra' => 'D']
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
            'tipo' => 'select', 'name' => 'liquido_frenos', 'respu_valida' => 'si'
        ],
        1 => [
            'pregunta' => '¿El nivel del líquido de la dirección (hidráulico) se encuentra entre el máximo y el mínimo?',
            'tipo' => 'select', 'name' => 'liquido_direccion', 'respu_valida' => 'si'
        ],
        2 => [
            'pregunta' => '¿El nivel del líquido de refrigeración se encuentra entre el máximo y el mínimo?',
            'tipo' => 'select', 'name' => 'liquido_refrigeracion', 'respu_valida' => 'si'
        ],
        3 => [
            'pregunta' => '¿El nivel del líquido del limpiabrisas se encuentra entre el máximo y el mínimo?',
            'tipo' => 'select', 'name' => 'liquido_limpiabrisas', 'respu_valida' => 'si'
        ],
        4 => [
            'pregunta' => '¿Los limpiaparabrisas funcionan adecuadamente (movimiento, aspersión de líquido y escobillas)?',
            'tipo' => 'select', 'name' => 'funciona_limpiabrisas', 'respu_valida' => 'si'
        ],
        5 => [
            'pregunta' => '¿Las luces direccionales derecha e izquierda funcionan en la parte frontal y posterior?',
            'tipo' => 'select', 'name' => 'direccionales', 'respu_valida' => 'si'
        ],
        6 => [
            'pregunta' => '¿Las luces de parqueo, freno, bajas, altas y cocuyos funcionan?',
            'tipo' => 'select', 'name' => 'luces', 'respu_valida' => 'si'
        ],
        7 => [
            'pregunta' => '¿Todas las puertas del vehículo abren, cierran y cuentan con sistema funcional para asegurarlas?',
            'tipo' => 'select', 'name' => 'puertas', 'respu_valida' => 'si'
        ],
        8 => [
            'pregunta' => '¿El freno de mano y de pedal responden al activarlos?',
            'tipo' => 'select', 'name' => 'freno_mano', 'respu_valida' => 'si'
        ],
        9 => [
            'pregunta' => '¿La dirección responde al maniobrar el volante?',
            'tipo' => 'select', 'name' => 'direccion_volante', 'respu_valida' => 'si'
        ],
        10 => [
            'pregunta' => '¿El pito suena al activarlo en el volante?',
            'tipo' => 'select', 'name' => 'pito', 'respu_valida' => 'si'
        ],
        11 => [
            'pregunta' => '¿Se detectan fugas de aceite o agua en la inspección visual del motor o debajo del vehículo?',
            'tipo' => 'select', 'name' => 'fugas', 'respu_valida' => 'no'
        ],
        12 => [
            'pregunta' => '¿Se presentan abolladuras o perforaciones en las latas del vehículo?',
            'tipo' => 'select', 'name' => 'abolladuras', 'respu_valida' => 'no'
        ],
        13 => [
            'pregunta' => '¿Se identifican peladuras o desprendimientos de pintura mayores a 5 cm de diámetro?',
            'tipo' => 'select', 'name' => 'desprendimientos', 'respu_valida' => 'no'
        ],
        14 => [
            'pregunta' => '¿El cinturón se expande y retrae adecuadamente y se asegura la lengüeta metálica en el anclaje?',
            'tipo' => 'select', 'name' => 'cinturon_funcional', 'respu_valida' => 'si'
        ],
        15 => [
            'pregunta' => '¿Se presenta separación entre la zona de carga y la zona de conducción?',
            'tipo' => 'select', 'name' => 'separacion', 'respu_valida' => 'si'
        ],
        16 => [
            'pregunta' => '¿Se detectan productos alimenticios o químicos en la zona de carga?',
            'tipo' => 'select', 'name' => 'estado_zona_carga', 'respu_valida' => 'no'
        ],
        17 => [
            'pregunta' => '¿El vehículo se encuentra aseado (lavado y limpieza general a nivel interno y externo)?',
            'tipo' => 'select', 'name' => 'vehiculo_aseado', 'respu_valida' => 'si'
        ],
        18 => [
            'pregunta' => '¿Evaluación visual de las llantas (no se notan desinfladas ni sin labrado)?',
            'tipo' => 'select', 'name' => 'estado_llantas', 'respu_valida' => 'si'
        ],
        19 => [
            'pregunta' => '¿Cuenta con llanta de repuesto?',
            'tipo' => 'select', 'name' => 'llanta_repuesto', 'respu_valida' => 'si'
        ],
        20 => [
            'pregunta' => '¿Cuenta con gato hidraulico?',
            'tipo' => 'select', 'name' => 'gato_hidraulico', 'respu_valida' => 'si'
        ],
        21 => [
            'pregunta' => '¿Cuenta con guantes?',
            'tipo' => 'select', 'name' => 'guantes', 'respu_valida' => 'si'
        ],
        22 => [
            'pregunta' => '¿Cuenta con cruceta?',
            'tipo' => 'select', 'name' => 'cruceta', 'respu_valida' => 'si'
        ],
        23 => [
            'pregunta' => '¿Cuenta con dos tacos?',
            'tipo' => 'select', 'name' => 'tacos', 'respu_valida' => 'si'
        ],
        24 => [
            'pregunta' => '¿Cuenta con señales reflectivas o triángulos?',
            'tipo' => 'select', 'name' => 'senales', 'respu_valida' => 'si'
        ],
        25 => [
            'pregunta' => '¿Cuenta con chaleco reflectivo?',
            'tipo' => 'select', 'name' => 'chaleco', 'respu_valida' => 'si'
        ],
        26 => [
            'pregunta' => '¿Cuenta con linterna?',
            'tipo' => 'select', 'name' => 'linterna', 'respu_valida' => 'si'
        ],
        27 => [
            'pregunta' => '¿Cuenta con botiquín?',
            'tipo' => 'select', 'name' => 'botiquin', 'respu_valida' => 'si'
        ],
        28 => [
            'pregunta' => '¿Cuenta con herramienta básica/Llave expansión?',
            'tipo' => 'select', 'name' => 'herramienta', 'respu_valida' => 'si'
        ],
        29 => [
            'pregunta' => '¿Fecha de vencimiento del extintor?',
            'tipo' => 'input', 'name' => 'vencimiento_extintor', 'respu_valida' => 'si'
        ],
        30 => [
            'pregunta' => '¿Fecha de vencimiento SOAT?',
            'tipo' => 'input', 'name' => 'vencimiento_soat', 'respu_valida' => 'si'
        ],
        31 => [
            'pregunta' => '¿Fecha de vencimiento de la revisión tecno mecánica?',
            'tipo' => 'input', 'name' => 'rv_tecnomecanica', 'respu_valida' => 'si'
        ],
        32 => [
            'pregunta' => '¿Fecha de vencimiento de la licencia de conducción?',
            'tipo' => 'input', 'name' => 'vencimiento_licencia', 'respu_valida' => 'si'
        ],
        33 => [
            'pregunta' => '¿Fecha ultimo mantenimiento?',
            'tipo' => 'input', 'name' => 'mantenimiento', 'respu_valida' => 'si'
        ],
    )
);

define(
    'CHEQUEO_MOTO',
    array(
        0 => [
            'pregunta' => '¿El nivel de líquido de frenos se encuentra entre el máximo y el mínimo?',
            'tipo' => 'select', 'name' => 'liquido_frenos_moto', 'respu_valida' => 'si'
        ],
        1 => [
            'pregunta' => '¿Las luces direccionales derecha e izquierda funcionan en la parte frontal y posterior?',
            'tipo' => 'select', 'name' => 'direccionales_moto', 'respu_valida' => 'si'
        ],
        2 => [
            'pregunta' => '¿Las luces de freno, bajas y altas funcionan?',
            'tipo' => 'select', 'name' => 'luces_moto', 'respu_valida' => 'si'
        ],
        3 => [
            'pregunta' => '¿El freno delantero y tracero responden al activarlos?',
            'tipo' => 'select', 'name' => 'freno_moto', 'respu_valida' => 'si'
        ],
        4 => [
            'pregunta' => '¿El pito suena al activarlo?',
            'tipo' => 'select', 'name' => 'pito_moto', 'respu_valida' => 'si'
        ],
        5 => [
            'pregunta' => '¿Se detectan fugas liquido en la inspección visual del motor o debajo del vehículo?',
            'tipo' => 'select', 'name' => 'fugas_moto', 'respu_valida' => 'no'
        ],
        6 => [
            'pregunta' => '¿Se presentan abolladuras o perforaciones en las latas del vehículo?',
            'tipo' => 'select', 'name' => 'abolladuras_moto', 'respu_valida' => 'no'
        ],
        7 => [
            'pregunta' => '¿Se identifican peladuras o desprendimientos de pintura mayores a 5 cm de diámetro?',
            'tipo' => 'select', 'name' => 'peladuras_moto', 'respu_valida' => 'no'
        ],
        8 => [
            'pregunta' => '¿El vehículo se encuentra aseado (lavado y limpieza general a nivel interno y externo)?',
            'tipo' => 'select', 'name' => 'aseo_moto', 'respu_valida' => 'si'
        ],
        9 => [
            'pregunta' => '¿Evaluación visual de las llantas (no se notan desinfladas ni sin labrado)?',
            'tipo' => 'select', 'name' => 'llantas_moto', 'respu_valida' => 'si'
        ],
        10 => [
            'pregunta' => '¿Cuenta con casco y visera?',
            'tipo' => 'select', 'name' => 'casco', 'respu_valida' => 'si'
        ],
        11 => [
            'pregunta' => '¿Cuenta con arnes para carga?',
            'tipo' => 'select', 'name' => 'arnes_moto', 'respu_valida' => 'si'
        ],
        12 => [
            'pregunta' => '¿Fecha de vencimiento SOAT?',
            'tipo' => 'input', 'name' => 'fecha_soat_moto', 'respu_valida' => 'si'
        ],
        13 => [
            'pregunta' => '¿Fecha de vencimiento de la revisión tecno mecánica?',
            'tipo' => 'input', 'name' => 'tecno_moto', 'respu_valida' => 'si'
        ],
        14 => [
            'pregunta' => '¿Fecha de vencimiento de la licencia de conducción?',
            'tipo' => 'input', 'name' => 'vencimiento_licencia', 'respu_valida' => 'si'
        ],
        15 => [
            'pregunta' => '¿Fecha ultimo mantenimiento?',
            'tipo' => 'input', 'name' => 'mantenimiento_moto', 'respu_valida' => 'si'
        ],
    )
);
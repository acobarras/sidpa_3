<?php

namespace MiApp\negocio\controladores\produccion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\Envio_Correo;
use MiApp\persistencia\dao\MaquinasDAO;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\ProgramacionOperarioDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\ActividadAreaDAO;
use MiApp\persistencia\dao\SeguimientoProduccionDAO;
use MiApp\persistencia\dao\MetrosLinealesDAO;
use MiApp\persistencia\dao\DesperdicioOpDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\TintasDAO;

class MaquinasProduccionControlador extends GenericoControlador
{

    private $MaquinasDAO;
    private $ItemProducirDAO;
    private $ProgramacionOperarioDAO;
    private $PersonaDAO;
    private $ActividadAreaDAO;
    private $SeguimientoProduccionDAO;
    private $MetrosLinealesDAO;
    private $DesperdicioOpDAO;
    private $PedidosItemDAO;
    private $TintasDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->MaquinasDAO = new MaquinasDAO($cnn);
        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->ProgramacionOperarioDAO = new ProgramacionOperarioDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->ActividadAreaDAO = new ActividadAreaDAO($cnn);
        $this->SeguimientoProduccionDAO = new SeguimientoProduccionDAO($cnn);
        $this->MetrosLinealesDAO = new MetrosLinealesDAO($cnn);
        $this->DesperdicioOpDAO = new DesperdicioOpDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->TintasDAO = new TintasDAO($cnn);
    }

    public function vista_trabajo_produccion()
    {
        parent::cabecera();
        $persona = $_SESSION['usuario']->getId_persona();
        $id_usuario = $_SESSION['usuario']->getId_usuario();
        $roll = $_SESSION['usuario']->getId_roll();
        $fecha = date('Y-m-d');
        $maquinas = $this->MaquinasDAO->consultar_maquinas_produccion();
        $envio = [];
        $maquina_operario = $this->ProgramacionOperarioDAO->ConsultaPersonaFecha($persona, $fecha);
        $maquina_persona = 0;
        if (!empty($maquina_operario)) {
            $maquina_persona = $maquina_operario[0]->id_maquina;
        }
        $Q_maquinas = [];
        foreach ($maquinas as $value) {
            if ($roll == 1 || $roll == 8 || $roll == 12 || $roll == 9 || $roll == 10) {
                $envio[] = $value;
                $datos = 1;
                if ($id_usuario == 8 || $id_usuario == 13) {
                    $datos = 2;
                }
                $Q_maquinas[] = ['id_maquina' => $value->id_maquina];
            } else {
                if ($value->id_maquina == $maquina_persona) {
                    $envio[] = $value;
                    $Q_maquinas[] = ['id_maquina' => $value->id_maquina];
                    // $Q_maquinas = $Q_maquinas + 1;
                }
                $datos = 2;
            }
        }
        $this->view(
            'produccion/vista_trabajo_produccion',
            [
                'maquinas_pro' => $envio,
                'datos' => $datos,
                'maquinas' => $this->MaquinasDAO->consultar_maquinas(),
                'q_maquinas' => json_encode($Q_maquinas),
            ]
        );
    }

    public function consultar_trabajo_maquinas($id_maquina = '')
    {
        header('Content-Type: application/json');
        if ($id_maquina == '') {
            $id_maquina = $_GET['id_maquina'];
        }
        $estado = '4,5,6,7,8,9,10';
        $op = $this->ItemProducirDAO->consultar_maquina_produccion2($estado,$id_maquina);
        $data = $op;
        echo json_encode($data);
    }

    public function consultar_turno_maquina()
    {
        header('Content-Type: application/json');
        $fecha_produccion = $_POST['fecha_produccion'];
        $maquina = $_POST['id_maquina'];
        $maquinas_turno = $this->ItemProducirDAO->consultar_maquinas($fecha_produccion, $maquina);
        echo json_encode($maquinas_turno);
        return;
    }

    public function cambiar_op_maquina()
    {
        header('Content-Type: application/json');
        $fecha_produccion = $_POST['fecha_produccion'];
        $id_maquina = $_POST['id_maquina'];
        $turno = $_POST['turno'];
        $id_item_producir = $_POST['id_item_producir'];
        //crear array para modificar la orden de produccion 
        $item_producir = [
            'maquina' => $id_maquina,
            'fecha_produccion' => $fecha_produccion,
            'turno_maquina' => $turno,
        ];
        $condicion_item_p = 'id_item_producir =' . $id_item_producir;
        $this->ItemProducirDAO->editar($item_producir, $condicion_item_p);
        //modificar orden de produccion y asignar turno
        $maquinas_turno = $this->ItemProducirDAO->consultar_maquinas($fecha_produccion, $id_maquina);
        //recorrer array para modificar el turno de las ordenes de producción
        foreach ($maquinas_turno as $maquina) {
            if ($id_item_producir != $maquina->id_item_producir) {
                if ($turno == $maquina->turno_maquina) {
                    $maquina_up['turno_maquina'] = $turno + 1;
                    $this->ItemProducirDAO->editar($maquina_up, ' id_item_producir =' . $maquina->id_item_producir);
                    $turno = $maquina_up['turno_maquina'];
                }
            }
        }
        $respu = $this->consultar_trabajo_maquinas($id_maquina);
        return $respu;
    }

    public function validar_operario()
    {
        header('Content-Type: application/json');
        $codigo_operario = $_POST['documento'];
        $operario = $this->PersonaDAO->consultar_personas_cedula($codigo_operario);
        echo json_encode($operario);
        return;
    }

    public function ejecuta_puesta_punto()
    {
        header('Content-Type: application/json');
        $data = $_POST['data'];
        $id_usuario = $_POST['usuario'];
        $estado_item_producir = $_POST['estado_item_producir'];
        $id_actividad_area = $_POST['id_actividad_area'];
        // Cambiar el estado en item producir 
        $edita_item_producir = [
            'estado_item_producir' => $estado_item_producir
        ];
        $condicion_item_producir = 'id_item_producir =' . $data['id_item_producir'];
        $this->ItemProducirDAO->editar($edita_item_producir, $condicion_item_producir);
        // Realizar el seguimiento en la tabla seguimiento_produccion
        $actividad_area = $this->ActividadAreaDAO->consultar_id_actividad_area($id_actividad_area);
        $seguimiento_produccion = [
            'num_produccion' => $data['num_produccion'],
            'id_maquina' => $data['id_maquina'],
            'id_persona' => $id_usuario,
            'id_area' => $actividad_area[0]->id_area_trabajo,
            'id_actividad' => $actividad_area[0]->id_actividad_area,
            'observacion_op' => $actividad_area[0]->nombre_actividad_area,
            'estado_produccion' => 1,
            'fecha_crea' => date('Y-m-d'),
            'hora_crea' => date('H:i:s'),
        ];
        $this->SeguimientoProduccionDAO->insertar($seguimiento_produccion);
        $respu = $this->consultar_trabajo_maquinas($data['id_maquina']);
        return $respu;
    }

    public function consultar_metros_lineales_op()
    {
        header('Content-Type: application/json');
        $id_item_producir = $_POST['id_item_producir'];
        $data = $this->MetrosLinealesDAO->consultar_metros_lineales_especificos($id_item_producir);
        echo json_encode($data);
        return;
    }

    public function produccion_comp_incomp()
    {
        header('Content-Type: application/json');

        // datos que siempre llegan 
        $datos = $_POST['envio'];
        $operario = $datos['operario'];
        $motivo_detencion = $datos['detencion'];
        $data_row = $datos['data_row']; //Son los datos de Item_producir
        $parcial_o_total = $datos['parcial_total'];

        if ($parcial_o_total == 1) { // reporte completo 
            $materiales = $datos['datos_material']; // Son los anchos entregados
            $id_item_producir = $datos['id_item_producir'];
            $cons_actividad_area = 7;
            $motivo_detencion = 'PENDIENTE PROGRAMACIÓN EMBOBINADO';
            $estado_item_producir = 11;
            $grabar_pedido_item = true;
            foreach ($materiales as $value) {
                // Insertar metros lineales utilizados
                $inser_ml_usados = [
                    'id_item_producir' => $id_item_producir,
                    'ancho' => $value['ancho'],
                    'codigo_material' => $value['codigo_material'],
                    'metros_lineales' => 0,
                    'ml_usados' => $value['ml'],
                    'num_troquel' => $datos['num_troquel'],
                    'estado_ml' => 1,
                    'id_persona' => $operario,
                    'fecha_crea' => date('Y-m-d H:i:s'),
                ];
                $id_ml_insertado = $this->MetrosLinealesDAO->insertar($inser_ml_usados);
                // Insertar desperdicio_op 
                $inserta_desperdicio_op = [
                    'num_produccion' => $data_row['num_produccion'],
                    'id_persona' => $operario,
                    'ml_empleado' => $value['ml'],
                    'maquina' => $data_row['id_maquina'],
                    'cantidad_etiquetas' => 0,
                    'id_pedido_item' => 0,
                    'id_metros_lineales' => $id_ml_insertado['id'],
                    'motivo' => 1,
                    'fecha_crea' => date('Y-m-d H:i:s'),
                ];
                $desperdicio = $this->DesperdicioOpDAO->insertar($inserta_desperdicio_op);
            }
        } else { // reporte incompleto 
            $cons_actividad_area = 8;
            $estado_item_producir = 10;
            $id_item_producir = $datos['data_row']['id_item_producir'];
            $grabar_pedido_item = false;
        }

        $area_actividad = $this->ActividadAreaDAO->consultar_id_actividad_area($cons_actividad_area);
        $id_actividad_area = $area_actividad[0]->id_actividad_area;
        $id_area_trabajo = $area_actividad[0]->id_area_trabajo;
        // Editar item_producir para cambiar el estado
        $edita_item_producir = ['estado_item_producir' => $estado_item_producir];
        $condicion_item_producir = 'id_item_producir =' . $id_item_producir;
        $this->ItemProducirDAO->editar($edita_item_producir, $condicion_item_producir);
        // Insertar en seguimiento produccion
        $inser_seguimiento_produccion = [
            'num_produccion' => $data_row['num_produccion'],
            'id_maquina' => $data_row['id_maquina'],
            'id_persona' => $operario,
            'id_area' => $id_area_trabajo,
            'id_actividad' => $id_actividad_area,
            'observacion_op' => $motivo_detencion,
            'estado_produccion' => 1,
            'fecha_crea' => date('Y-m-d'),
            'hora_crea' => date('H:i:s')
        ];
        $this->SeguimientoProduccionDAO->insertar($inser_seguimiento_produccion);

        if ($grabar_pedido_item) {
            $item_op = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($data_row['num_produccion']);
            foreach ($item_op as $res_item_op) {
                if ($res_item_op->id_estado_item_pedido == 10) {
                    $editar_pedido_item = ['id_estado_item_pedido' => 15];
                    $condicion_pedido_item = 'id_pedido_item =' . $res_item_op->id_pedido_item;
                    $this->PedidosItemDAO->editar($editar_pedido_item, $condicion_pedido_item);
                }
            }
        }
        $respu = $this->consultar_trabajo_maquinas($data_row['id_maquina']);
        return $respu;
    }

    public function consulta_items_op()
    {
        header('Content-Type: application/json');
        $num_produccion = $_POST['num_produccion'];
        $respu = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($num_produccion);
        $q_items = 0;
        foreach ($respu as $value) {
            $q_items = $q_items + 1;
        }
        $tintas = $this->TintasDAO->consultar_tintas();
        foreach ($respu as $value) {
            //Ml del item 
            $a = ($value->cant_op * $value->avance);
            $value->metrosl = 0;
            $value->metros2 = 0;
            if ($a != 0) {
                $value->metrosl = $a / ($value->cav_montaje * 1000);
                //m2 del item 
                $value->metros2 = (($value->ancho_material * $value->metrosl) / 1000);
            }
            //obtener el valor de las tintas
            $caracter = "-";
            $posicion_coincidencia = strpos($value->codigo, $caracter); //posicion empezando desde el (-)
            $tinta_codigo = substr($value->codigo, ($posicion_coincidencia + 6), 2);
            foreach ($tintas as $tint) {
                if ($tint->numeros == $tinta_codigo) {
                    $value->tintas = $tint->num_tintas;
                }
            }
            $value->q_items = $q_items;
        }
        $data["data"] = $respu;
        echo json_encode($data);
        return;
    }

    public function cambiar_estado_pedido_item()
    {
        header('Content-Type: application/json');

        $datos = $_POST['envio'];
        $tipo_cierre = $datos['tipo_cierre'];
        $operario = $datos['operario'];
        $materiales = $datos['datos_material']; // Son los anchos entregados
        $data_itemProducir = $datos['data_row']["data_op"];
        $data_pedidosItem =  $datos['data_row']["data_item"];
        $id_item_producir = $datos['id_item_producir'];


        if ($tipo_cierre == 1) { // completo
            // ============= CONSULTA ITEMS FALTANTES ============
            $items = $this->PedidosItemDAO->item_pendientes_troquelado($data_itemProducir['num_produccion']);
            $num_items = count($items);
            if ($num_items <= 1) { // si solo queda un item retornamos con un mensaje 
                $res = -1;
                echo json_encode($res);
                return;
            }

            // ========== Seguimiento completos ===================
            $cons_actividad_area = 105; //TROQUELADO ÍTEM TERMINADO 
            $motivo_detencion = 'PEDIDO ITEM: ' . $data_pedidosItem['num_pedido'] . '-' . $data_pedidosItem['item'] . ' TROQUELADO';

            // ====================== Estado del item ================ 
            $editar_pedido_item = ['id_estado_item_pedido' => 15];
        } else { // incompleto 
            // ========== Seguimiento incompletos ===================
            $cons_actividad_area = 8; //Producción Sin Terminar
            $motivo_detencion = $datos['detencion'];

            // ====================== Estado del item ================
            $editar_pedido_item = ['id_estado_item_pedido' => 10];  //Producción Sin Terminar
            // //================== Editar orden de produccion ==============
            $estado_item_producir = 10; // En Turno Producción
            $edita_item_producir = ['estado_item_producir' => $estado_item_producir];
            $condicion_item_producir = 'id_item_producir =' . $id_item_producir;
            $this->ItemProducirDAO->editar($edita_item_producir, $condicion_item_producir);
        }




        $area_actividad = $this->ActividadAreaDAO->consultar_id_actividad_area($cons_actividad_area);
        $id_actividad_area = $area_actividad[0]->id_actividad_area;
        $id_area_trabajo = $area_actividad[0]->id_area_trabajo;

        // Insertar en seguimiento produccion
        $inser_seguimiento_produccion = [
            'num_produccion' => $data_itemProducir['num_produccion'],
            'id_maquina' => $data_itemProducir['id_maquina'],
            'id_persona' => $operario,
            'id_area' => $id_area_trabajo,
            'id_actividad' => $id_actividad_area,
            'observacion_op' => $motivo_detencion,
            'estado_produccion' => 1,
            'fecha_crea' => date('Y-m-d'),
            'hora_crea' => date('H:i:s')
        ];
        $this->SeguimientoProduccionDAO->insertar($inser_seguimiento_produccion);

        // ============= METROS LINEALES ===============
        foreach ($materiales as $value) {
            // Insertar metros lineales utilizados
            $inser_ml_usados = [
                'id_item_producir' => $id_item_producir,
                'ancho' => $value['ancho'],
                'codigo_material' => $value['codigo_material'],
                'metros_lineales' => 0,
                'ml_usados' => $value['ml'],
                'num_troquel' => $datos['num_troquel'],
                'estado_ml' => 1,
                'id_persona' => $operario,
                'fecha_crea' => date('Y-m-d H:i:s'),
            ];
            $id_ml_insertado = $this->MetrosLinealesDAO->insertar($inser_ml_usados);
            // Insertar desperdicio_op 
            $inserta_desperdicio_op = [
                'num_produccion' => $data_itemProducir['num_produccion'],
                'id_persona' => $operario,
                'ml_empleado' => $value['ml'],
                'maquina' => $data_itemProducir['id_maquina'],
                'cantidad_etiquetas' => 0,
                'id_pedido_item' => 0,
                'id_metros_lineales' => $id_ml_insertado['id'],
                'motivo' => 1,
                'fecha_crea' => date('Y-m-d H:i:s'),
            ];
            $this->DesperdicioOpDAO->insertar($inserta_desperdicio_op);
        }

        // ==================== CAMBIO DE ESTADO PEDIDO ITEM  ===========
        $id_pedido_item = $data_pedidosItem["id_pedido_item"];
        // $editar_pedido_item = ['id_estado_item_pedido' => 15];
        $condicion_pedido_item = 'id_pedido_item =' . $id_pedido_item;
        $this->PedidosItemDAO->editar($editar_pedido_item, $condicion_pedido_item);
        $respu = $this->consultar_trabajo_maquinas($data_itemProducir['id_maquina']);
        return $respu;
    }
}

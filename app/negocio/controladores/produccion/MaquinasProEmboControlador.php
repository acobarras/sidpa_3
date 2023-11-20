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
use MiApp\persistencia\dao\MaquinaEmbobinadoDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\impresorasDAO;
use MiApp\persistencia\dao\impresora_tamanoDAO;
use MiApp\Sabberworm\CSS\Value\Value;

class MaquinasProEmboControlador extends GenericoControlador
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
    private $MaquinaEmbobinadoDAO;
    private $entrada_tecnologiaDAO;
    private $SeguimientoOpDAO;
    private $impresora_tamanoDAO;
    private $impresorasDAO;
    

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
        $this->MaquinaEmbobinadoDAO = new MaquinaEmbobinadoDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->impresora_tamanoDAO = new impresora_tamanoDAO($cnn);
        $this->impresorasDAO = new impresorasDAO($cnn);
    }

    public function vista_trabajo_proembo()
    {
        parent::cabecera();
        $persona = $_SESSION['usuario']->getId_persona();
        $id_usuario = $_SESSION['usuario']->getId_usuario();
        $roll = $_SESSION['usuario']->getId_roll();
        $fecha = date('Y-m-d');
        $maquinas = $this->MaquinasDAO->consultar_maquinas_proembo();
        $envio = [];
        $maquina_operario = $this->ProgramacionOperarioDAO->ConsultaPersonaFecha($persona, $fecha);
        $maquina_persona = 0;
        if (!empty($maquina_operario)) {
            $maquina_persona = $maquina_operario[0]->id_maquina;
        }
        $Q_maquinas = [];
        foreach ($maquinas as $value) {
            if ($roll == 1 || $roll == 9 || $roll == 10 || $roll == 8 || $roll == 12) {
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
            'produccion/vista_trabajo_proembo',
            [
                'maquinas_pro' => $envio,
                'datos' => $datos,
                'maquinas' => $this->MaquinasDAO->consultar_maquinas(),
                'q_maquinas' => json_encode($Q_maquinas),
                'tamano_impresion' => $this->impresora_tamanoDAO->consulta_tamano_impresion(),
            ],
        );
        $this->view('produccion/modal_impresion');
    }

    public function consultar_trabajo_pro_embo($respu = '')
    {
        header('Content-Type: application/json');
        $estado = '4,5,6,7,8,9,10,12';
        $estados_cambio = array(
            12 => '15',
            13 => '16',
            14 => '14',
        );
        $op = $this->ItemProducirDAO->consultar_maquina_produccion($estado);
        $embobinado = $this->MaquinaEmbobinadoDAO->embobinado_maquinas_dk();
        foreach ($op as $value) {
            foreach ($embobinado as $cambio) {
                if ($cambio->num_produccion == $value->num_produccion) {
                    $value->id_maquina = $cambio->maquina;
                    $value->estado_item_producir = $estados_cambio[$cambio->estado_item_producir];
                    $value->fecha_produccion = $cambio->fecha_embobinado;
                    $value->nombre_maquina = $cambio->nombre_maquina;
                    $value->id_maquina_embo = $cambio->id_maquina;
                }
            }
        }
        $data = $op;
        if ($respu != '') {
            return json_encode($data);
        } else {
            echo json_encode($data);
            return;
        }
    }

    public function ejecuta_puesta_punto_pro_embo()
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
        $respu = $this->consultar_trabajo_pro_embo();
        return $respu;
    }

    public function consulta_items_pro_embo($num_produccion = '', $id_maquina = '')
    {
        header('Content-Type: application/json');
        $res = false;
        if ($num_produccion != '') {
            $res = true;
        }
        if ($num_produccion == '' && $id_maquina == '') {
            $num_produccion = $_POST['num_produccion'];
            $id_maquina = $_POST['id_maquina'];
        }
        if ($id_maquina == 0) {
            $respu = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($num_produccion);
        } else {
            $respu = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($num_produccion, $id_maquina);
        }
        foreach ($respu as $value) {
            $cavidad_cliente = Validacion::DesgloceCodigo($value->codigo, 5, 1);
            $value->cav_cliente = $cavidad_cliente;
            $documento = $value->num_pedido."-".$value->item;
            $etiq_bodega = $this->entrada_tecnologiaDAO->salida_producto($documento);
            $value->salida_bodega = $etiq_bodega[0]->salida_bodega;
            $datos_reporte = $this->DesperdicioOpDAO->etiquetas_pedido_item($value->id_pedido_item);
            $q_etiq_reportadas = 0;
            if ($datos_reporte[0]->q_etiq_item != '') {
                $q_etiq_reportadas = $datos_reporte[0]->q_etiq_item;
            }
            $value->q_etiq_reportadas = $q_etiq_reportadas;
        }
        if ($res) {
            return json_encode($respu);
        } else {
            echo json_encode($respu);
            return;
        }
    }

    public function cambiar_op_maquina_pro_embo()
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
        $respu = $this->consultar_trabajo_pro_embo();
        return $respu;
    }

    public function inicio_pro_embo_item()
    {
        header('Content-Type: application/json');
        $datos = $_POST['envio'];
        $id_persona_sesion = $_POST['id_persona_sesion'];
        $maquina_embo = 0;
        $num_produccion = 0;
        foreach ($datos as $value) {
            $num_produccion = $value['n_produccion'];
            if ($value['id_maqui_embo'] != '') {
                $maquina_embo = $value['id_maqui_embo'];
            }
            $edita_pedido_item = [
                'id_estado_item_pedido' => $value['estado_cambio_item_pedido']
            ];
            $condicion_pedido_item = 'id_pedido_item =' . $value['id_pedido_item'];
            $this->PedidosItemDAO->editar($edita_pedido_item, $condicion_pedido_item);
        }
        $json = $this->consulta_items_pro_embo($num_produccion, $maquina_embo);
        $k = json_decode($json, true);
        $respu = $k;
        echo json_encode($respu);
        return $respu;
    }

    public function cambiar_maquina_embo_dk()
    {
        header('Content-Type: application/json');
        $fecha_embobinado = $_POST['fecha_embobinado'];
        $maquina = $_POST['maquina'];
        $turno = $_POST['turno'];
        $id_maquina = $_POST['id_maquina'];
        //crear array para modificar la orden de produccion 
        $maquina_embobinado = [
            'maquina' => $maquina,
            'fecha_embobinado' => $fecha_embobinado,
            'turno_maquina' => $turno,
        ];
        $condicion_maquina_embobinado = 'id_maquina =' . $id_maquina;
        $this->MaquinaEmbobinadoDAO->editar($maquina_embobinado, $condicion_maquina_embobinado);
        //modificar orden de produccion y asignar turno
        $maquinas_turno = $this->MaquinaEmbobinadoDAO->consulta_turno_maquina($maquina, $fecha_embobinado);
        //recorrer array para modificar el turno de las ordenes de producción
        foreach ($maquinas_turno as $maquina) {
            if ($id_maquina != $maquina->id_maquina) {
                if ($turno == $maquina->turno_maquina) {
                    $maquina_up['turno_maquina'] = $turno + 1;
                    $this->MaquinaEmbobinadoDAO->editar($maquina_up, ' id_maquina =' . $maquina->id_maquina);
                    $turno = $maquina_up['turno_maquina'];
                }
            }
        }
        $respu = $this->consultar_trabajo_pro_embo();
        return $respu;
    }

    public function inicio_embo_dk()
    {
        header('Content-Type: application/json');
        $data = $_POST['data'];
        $id_persona_sesion = $_POST['id_persona_sesion'];
        $id_actividad_area = 15;
        $maquina_embobinado = [
            'estado_item_producir' => 13,
        ];
        $condicion_maquina_embo = 'id_maquina =' . $data['id_maquina_embo'];
        $this->MaquinaEmbobinadoDAO->editar($maquina_embobinado, $condicion_maquina_embo);
        // Insertar un seguimiento produccion segun la opcion si es terminada la op o incompleta
        $actividad_area = $this->ActividadAreaDAO->consultar_id_actividad_area($id_actividad_area);
        $seguimiento_produccion = [
            'num_produccion' => $data['num_produccion'],
            'id_maquina' => $data['id_maquina'],
            'id_persona' => $id_persona_sesion,
            'id_area' => $actividad_area[0]->id_area_trabajo,
            'id_actividad' => $actividad_area[0]->id_actividad_area,
            'observacion_op' => $actividad_area[0]->nombre_actividad_area,
            'estado_produccion' => 1,
            'fecha_crea' => date('Y-m-d'),
            'hora_crea' => date('H:i:s'),
        ];
        $this->SeguimientoProduccionDAO->insertar($seguimiento_produccion);
        $respu = $this->consultar_trabajo_pro_embo();
        return $respu;
    }

    public function reporte_item_pro_embo_dk()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $id_item_producir = $datos['data_row']['id_item_producir'];
        $inicio = 2;
        if (isset($datos['data_envio'])) {
            foreach ($datos['data_envio'] as $value) {
                // Cambiar el estado de pedidos_item a 20
                $edita_pedidos_item = [
                    'id_estado_item_pedido' => $value['estado_cambio_item_pedido'],
                ];
                $condicion_pedidos_item = 'id_pedido_item =' . $value['id_pedido_item'];
                $this->PedidosItemDAO->editar($edita_pedidos_item, $condicion_pedidos_item);
            }
            $inicio = 1;
        }
        $operario = $datos['data_items'][0]['operario'];
        $ml_usados = 0;
        foreach ($datos['data_items'][0]['ml_usados'] as $respu_ml) {
            $ml_usados = $ml_usados + $respu_ml['ml'];
        }
        $id_actividad_area = 50;
        foreach ($datos['data_items'] as $value) {
            // Cambiar el estado de pedidos_item segun sea elegido
            $estado_cambio = 16;
            if ($value['estado_envio'] == 1) {
                $id_actividad_area = 49;
                $estado_cambio = 10;
            }
            $edita_pedidos_item = [
                'id_estado_item_pedido' => $estado_cambio,
            ];
            $condicion_pedidos_item = 'id_pedido_item =' . $value['id_pedido_item'];
            $this->PedidosItemDAO->editar($edita_pedidos_item, $condicion_pedidos_item);
            if($value['estado_envio'] == 2) {
                $insertar_seguimiento_item = [
                    'id_persona' => $operario,
                    'id_area' => 2,
                    'id_actividad' => 38,
                    'pedido' => $value['num_pedido'],
                    'item' => $value['item'],
                    'observacion' => '',
                    'estado' => 1,
                    'id_usuario' => $_SESSION['usuario']->getId_usuario(),
                    'fecha_crea' => date('Y-m-d'),
                    'hora_crea' => date('H:i:s')
                ];
                $this->SeguimientoOpDAO->insertar($insertar_seguimiento_item);
            }
            // Insertar en desperdicio_op la cantidad de etiquetas realizadas por el operario 
            $inserta_desperdicio_op_etiquetas = [
                'num_produccion' => $value['n_produccion'],
                'id_persona' => $operario,
                'ml_empleado' => $ml_usados,
                'maquina' => $datos['data_row']['id_maquina'],
                'cantidad_etiquetas' => $value['cant_etiquetas'],
                'id_pedido_item' => $value['id_pedido_item'],
                'id_metros_lineales' => 0,
                'motivo' => 1,
                'fecha_crea' => date('Y-m-d H:i:s'),
            ];
            $this->DesperdicioOpDAO->insertar($inserta_desperdicio_op_etiquetas);
        }
        foreach ($datos['data_items'][0]['ml_usados'] as $value_material) {
            // Insertar metros lineales utilizados
            $inser_ml_usados = [
                'id_item_producir' => $id_item_producir,
                'ancho' => $value_material['ancho'],
                'codigo_material' => $value_material['codigo_material'],
                'metros_lineales' => 0,
                'ml_usados' => $value_material['ml'],
                'num_troquel' => $datos['data_items'][0]['num_troquel'],
                'estado_ml' => 1,
                'id_persona' => $operario,
                'fecha_crea' => date('Y-m-d H:i:s'),
            ];
            $id_ml_insertado = $this->MetrosLinealesDAO->insertar($inser_ml_usados);
            // Insertar desperdicio_op los metros lineales de produccion
            $inserta_desperdicio_op = [
                'num_produccion' => $datos['data_items'][0]['n_produccion'],
                'id_persona' => $operario,
                'ml_empleado' => $value_material['ml'],
                'maquina' => $datos['data_row']['id_maquina'],
                'cantidad_etiquetas' => 0,
                'id_pedido_item' => 0,
                'id_metros_lineales' => $id_ml_insertado['id'],
                'motivo' => 1,
                'fecha_crea' => date('Y-m-d H:i:s'),
            ];
            $this->DesperdicioOpDAO->insertar($inserta_desperdicio_op);
        }
        $items_op = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($datos['data_items'][0]['n_produccion']);
        $q_items = 0;
        $cons_id_actividad = $id_actividad_area;
        $estado_item_producir = 14;
        foreach ($items_op as $res_item_op) {
            if ($res_item_op->id_estado_item_pedido == 10) {
                $q_items = $q_items + 1;
            }
        }
        if ($q_items != 0 && $id_actividad_area == 50) {
            $cons_id_actividad = 49;
            $estado_item_producir = 10;
        }
        if ($q_items != 0 && $id_actividad_area == 49) {
            $estado_item_producir = 10;
        }
        $actividad_area = $this->ActividadAreaDAO->consultar_id_actividad_area($cons_id_actividad);
        if ($inicio == 2) {
            // Insertar el reporte del seguimiento_produccion
            $insertar_seguimiento_pro = [
                'num_produccion' => $datos['data_items'][0]['n_produccion'],
                'id_maquina' => $datos['data_row']['id_maquina'],
                'id_persona' => $operario,
                'id_area' => $actividad_area[0]->id_area_trabajo,
                'id_actividad' => $actividad_area[0]->id_actividad_area,
                'observacion_op' => $actividad_area[0]->nombre_actividad_area,
                'estado_produccion' => 1,
                'fecha_crea' => date('Y-m-d'),
                'hora_crea' => date('H:i:s'),
            ];
            // en item_producir colocar el estado segun la terminacion terminado o incompleto segun corresponda
            $this->SeguimientoProduccionDAO->insertar($insertar_seguimiento_pro);
            $edita_item_producir = [
                'estado_item_producir' => $estado_item_producir
            ];
            $condicion_item_producir = 'id_item_producir =' . $id_item_producir;
            $this->ItemProducirDAO->editar($edita_item_producir, $condicion_item_producir);
            $inicio = false;
        }
        if ($inicio) {
            $json = $this->consulta_items_pro_embo($datos['data_items'][0]['n_produccion']);
            $k = (array)json_decode($json, true);
            $retorno = [
                'status' => 2,
                'respu' => $k
            ];
        } else {
            $json = $this->consultar_trabajo_pro_embo(1);
            $k = (array)json_decode($json, true);
            $retorno = [
                'status' => 1,
                'respu' => $k
            ];
        }
        echo json_encode($retorno);
        return;
    }

    public function reportar_embobinado_etiquetas_dk()
    {
        header('Content-Type: application/json');
        $data_reporte = $_POST['reporte'];
        $id_persona_sesion = $_POST['id_persona_sesion'];
        $data_maquina = $_POST['data_maquina'];
        $inicio_nuevo_trabajo = false;
        if (isset($_POST['envio'])) {
            $inicio_nuevo_trabajo = true;
            $data_envio = $_POST['envio'];
        }

        // Insertar en desperdicio_op la cantidad de etiquetas reportadas del embobinador por cada operario
        $etiquetas_oper_troqu = $data_reporte[0]['datos_etiquetas_embo'];
        foreach ($etiquetas_oper_troqu as $value) {
            $desperdicio_op = [
                'num_produccion' => $data_reporte[0]['n_produccion'],
                'id_persona' => $id_persona_sesion,
                'ml_empleado' => $value['ml_procesados'],
                'maquina' => $data_maquina['maquina'],
                'cantidad_etiquetas' => $value['etiquetas'],
                'id_operario_troquela' => $value['id_persona'],
                'id_pedido_item' => $data_reporte[0]['id_pedido_item'],
                'id_metros_lineales' => 0,
                'motivo' => 0,
                'fecha_crea' => date('Y-m-d H:i:s'),
            ];
            $this->DesperdicioOpDAO->insertar($desperdicio_op);
        }
        // Cambiar el estado en pedidos_item segun corresponda si es completo o incompleto
        $parcial = false;
        $estado_maquina = 14;
        $id_actividad_area = 0;
        foreach ($data_reporte as $repor) {
            $estado_pedido_item = 16;
            if ($repor['estado_envio'] == 1) {
                $parcial = true;
                $estado_maquina = 12;
                $estado_pedido_item = 21;
                $id_actividad_area = 13;
            }
            $edita_pedido_item = [
                'id_estado_item_pedido' => $estado_pedido_item
            ];
            $condicion_pedido_item = 'id_pedido_item =' . $repor['id_pedido_item'];
            $this->PedidosItemDAO->editar($edita_pedido_item, $condicion_pedido_item);
            if($repor['estado_envio'] == 2) {
                $insertar_seguimiento_item = [
                    'id_persona' => $id_persona_sesion,
                    'id_area' => 2,
                    'id_actividad' => 38,
                    'pedido' => $repor['num_pedido'],
                    'item' => $repor['item'],
                    'observacion' => '',
                    'estado' => 1,
                    'id_usuario' => $_SESSION['usuario']->getId_usuario(),
                    'fecha_crea' => date('Y-m-d'),
                    'hora_crea' => date('H:i:s')
                ];
                $this->SeguimientoOpDAO->insertar($insertar_seguimiento_item);
            }
        }
        // se inicia un nuevo trabajo que se encuentra seleccionado
        if ($inicio_nuevo_trabajo) {
            foreach ($data_envio as $envio) {
                $edita_pedido_item = [
                    'id_estado_item_pedido' => $envio['estado_cambio_item_pedido']
                ];
                $condicion_pedido_item = 'id_pedido_item =' . $envio['id_pedido_item'];
                $this->PedidosItemDAO->editar($edita_pedido_item, $condicion_pedido_item);
            }
        }
        $estado_maquina_embobinado = false;
        if ($parcial && !$inicio_nuevo_trabajo) {
            $estado_maquina_embobinado = true;
        }

        if (!$parcial and !$inicio_nuevo_trabajo) {
            $datos_faltantes = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($data_reporte[0]['n_produccion'], $data_maquina['id_maquina_embo']);
            $cuenta_faltante = 0;
            foreach ($datos_faltantes as $dato) {
                if ($dato->id_estado_item_pedido == 20 || $dato->id_estado_item_pedido == 21) {
                    $cuenta_faltante = $cuenta_faltante + 1;
                }
            }
            if ($cuenta_faltante == 0) {
                $id_actividad_area = 12;
                $estado_maquina_embobinado = true;
                $valida_estado = $this->ItemProducirDAO->consultar_item_producir_num($data_reporte[0]['n_produccion']);
                $estado_op = $valida_estado[0]->estado_item_producir;
                if ($estado_op == 12) {
                    $edita_estado_item_producir = ['estado_item_producir' => 14];
                    $condicion_estado_item_producir = 'id_item_producir =' . $valida_estado[0]->id_item_producir;
                    $this->ItemProducirDAO->editar($edita_estado_item_producir, $condicion_estado_item_producir);
                }
            } else {
                $id_actividad_area = 13;
                $estado_maquina_embobinado = true;
                $estado_maquina = 12;
            }
        }

        if ($estado_maquina_embobinado) {
            // Cambiar el estado en maquina embobinado segun corresponda 
            $maquina_embobinado = [
                'estado_item_producir' => $estado_maquina,
            ];
            $condicion_maquina_embo = 'id_maquina =' . $data_maquina['id_maquina_embo'];
            $this->MaquinaEmbobinadoDAO->editar($maquina_embobinado, $condicion_maquina_embo);
            // Insertar un seguimiento produccion segun la opcion si es terminada la op o incompleta
            $actividad_area = $this->ActividadAreaDAO->consultar_id_actividad_area($id_actividad_area);
            $seguimiento_produccion = [
                'num_produccion' => $data_reporte[0]['n_produccion'],
                'id_maquina' => $data_maquina['maquina'],
                'id_persona' => $id_persona_sesion,
                'id_area' => $actividad_area[0]->id_area_trabajo,
                'id_actividad' => $actividad_area[0]->id_actividad_area,
                'observacion_op' => $actividad_area[0]->nombre_actividad_area,
                'estado_produccion' => 1,
                'fecha_crea' => date('Y-m-d'),
                'hora_crea' => date('H:i:s'),
            ];
            $this->SeguimientoProduccionDAO->insertar($seguimiento_produccion);
            $json = $this->consultar_trabajo_pro_embo(1);
            $k = (array)json_decode($json, true);
            $respu_total = [
                'status' => 1,
                'respu' => $k
            ];
        } else {
            $json = $this->consulta_items_pro_embo($data_reporte[0]['n_produccion']);
            $k = json_decode($json, true);
            $respu_total = [
                'status' => 2,
                'respu' => $k
            ];
        }
        echo json_encode($respu_total);
        return;
    }
}

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
use MiApp\Sabberworm\CSS\Value\Value;
use MiApp\persistencia\dao\MaquinaEmbobinadoDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;

class MaquinasEmbobinadoControlador extends GenericoControlador
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
    }

    public function vista_programacion_embobinado()
    {
        parent::cabecera();
        $this->view(
            'produccion/vista_programacion_embobinado',
            [
                'maquinas_embobinado' => $this->MaquinasDAO->consultar_maquinas_embobinado('2,3'),
            ]
        );
    }

    public function consulta_pendiente_embobinar()
    {
        header('Content-Type: application/json');
        $cons = $this->PedidosItemDAO->op_pendientes_embobinado();
        $res['data'] = $cons;
        echo json_encode($res);
        return;
    }

    public function datos_programacion_embobinado($n_produccion = '', $id_maquina = '')
    {
        header('Content-Type: application/json');
        $respu_inrtna = false;
        if ($n_produccion == '') {
            $respu_inrtna = true;
            $n_produccion = $_POST['num_produccion'];
            $id_maquina = $_POST['id_maquina'];
        }
        $cons_items = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($n_produccion, $id_maquina);
        foreach ($cons_items as $value) {
            $documento = $value->num_pedido."-".$value->item;
            $etiq_bodega = $this->entrada_tecnologiaDAO->salida_producto($documento);
            $value->salida_bodega = $etiq_bodega[0]->salida_bodega;
            $cavidad_cliente = Validacion::DesgloceCodigo($value->codigo, 5, 1);
            $value->cav_cliente = $cavidad_cliente;
            $datos_reporte = $this->DesperdicioOpDAO->etiquetas_pedido_item($value->id_pedido_item);
            $q_etiq_reportadas = 0;
            if ($datos_reporte[0]->q_etiq_item != '') {
                $q_etiq_reportadas = $datos_reporte[0]->q_etiq_item;
            }
            $value->q_etiq_reportadas = $q_etiq_reportadas;
        }
        if ($respu_inrtna) {
            echo json_encode($cons_items);
            return;
        } else {
            return json_encode($cons_items);
        }
    }

    public function valida_embobinado()
    {
        header('Content-Type: application/json');
        $id_maquina = $_POST['id_maquina'];
        $fecha_embo = $_POST['fecha_embo'];
        $respu = $this->MaquinaEmbobinadoDAO->consulta_turno_maquina($id_maquina, $fecha_embo);
        echo json_encode($respu);
        return;
    }

    public function programacion_embobinado()
    {
        header('Content-Type: application/json');
        $datos_form = $_POST['form'];
        $datos_form = Validacion::Decodifica($datos_form);
        $datos_item = $_POST['data_envio'];
        $ml_embobinado = 0;
        foreach ($datos_item as $valor) {
            $etiq_por_avance = (floatval($valor['cant_op']) - floatval($valor['q_etiq_reportadas'])) * floatval($valor['avance']);
            $eti_cav = $etiq_por_avance / $valor['cav_cliente'];
            $ml_item = $eti_cav / 1000;
            $ml_embobinado = $ml_embobinado + $ml_item;
        }
        $existe = $this->MaquinaEmbobinadoDAO->consulta_maquina_op($datos_form['num_op']);
        $edita = false;
        $registro = [];
        if (!empty($existe)) {
            foreach ($existe as $value) {
                if ($value->maquina == $datos_form['maquina_embo']) {
                    $edita = true;
                    $registro = $value;
                }
            }
        }
        $id_maquinas_proembo = [];
        $con_maquinas_proembo = $this->MaquinasDAO->consultar_maquinas_proembo();
        foreach ($con_maquinas_proembo as $value) {
           array_push($id_maquinas_proembo, $value->id_maquina);
        }
        if (in_array($datos_form['maquina_embo'],$id_maquinas_proembo)) {
            // Se edita la tabla item_producir
            $valida_estado = $this->ItemProducirDAO->consultar_item_producir_num($datos_form['num_op']);
            $estado_op = $valida_estado[0]->estado_item_producir;
            if($estado_op == 11) {
                $edita_estado_item_producir = ['estado_item_producir' => 12];
                $condicion_estado_item_producir = 'id_item_producir ='.$valida_estado[0]->id_item_producir;
                $this->ItemProducirDAO->editar($edita_estado_item_producir,$condicion_estado_item_producir);
            }

        }
        $estado = 12;
        // Se crea el registro o se edita en la tabla maquina_embobinado
        if ($edita) {
            $ml = $registro->ml_asignados + $ml_embobinado;
            if ($registro->estado_item_producir == 13) {
                $estado = 13;
            }
            $maquina_embobinado = [
                'ml_asignados' => $ml,
                'fecha_embobinado' => $datos_form['fecha_embo'],
                'turno_maquina' => $datos_form['turno_embo'],
                'estado_item_producir' => $estado
            ];
            $condicion_embobinado = 'id_maquina =' . $registro->id_maquina;
            $this->MaquinaEmbobinadoDAO->editar($maquina_embobinado, $condicion_embobinado);
            $id_maquina = $registro->id_maquina;
        } else {
            $maquina_embobinado = [
                'num_produccion' => $datos_form['num_op'],
                'maquina' => $datos_form['maquina_embo'],
                'ml_asignados' => $ml_embobinado,
                'fecha_embobinado' => $datos_form['fecha_embo'],
                'turno_maquina' => $datos_form['turno_embo'],
                'id_persona' => $_SESSION['usuario']->getId_persona(),
                'estado_item_producir' => $estado,
                'id_usuario' => $_SESSION['usuario']->getId_usuario(),
            ];
            $res_id_maquina = $this->MaquinaEmbobinadoDAO->insertar($maquina_embobinado);
            $id_maquina = $res_id_maquina['id'];
        }
        // Cambiamos el estado en pedidos_item 
        foreach ($datos_item as $value_item) {
            $edita_pedido_item = [
                'id_estado_item_pedido' => 21,
                'id_maqui_embo' => $id_maquina
            ];
            $condicion_pedido_item = 'id_pedido_item =' . $value_item['id_pedido_item'];
            $editar_pedido_item = $this->PedidosItemDAO->editar($edita_pedido_item, $condicion_pedido_item);
        }
        // Realizo el seguimiento en produccion
        $id_actividad_area = 14;
        $actividad_area = $this->ActividadAreaDAO->consultar_id_actividad_area($id_actividad_area);
        $inserta_seguimiento_produccion = [
            'num_produccion' => $datos_form['num_op'],
            'id_maquina' => $datos_form['maquina_embo'],
            'id_persona' => $_SESSION['usuario']->getId_persona(),
            'id_area' => $actividad_area[0]->id_area_trabajo,
            'id_actividad' => $actividad_area[0]->id_actividad_area,
            'observacion_op' => 'PROGRAMADO EMBOBINADO',
            'estado_produccion' => 1,
            'fecha_crea' => date('Y-m-d'),
            'hora_crea' => date('H:i:s'),
        ];
        $this->SeguimientoProduccionDAO->insertar($inserta_seguimiento_produccion);
        echo json_encode($editar_pedido_item);
        return;
    }

    // Funciones para la el modulo de el trabajo de reporte de embobinado

    public function vista_trabajo_embobinado()
    {
        parent::cabecera();
        $persona = $_SESSION['usuario']->getId_persona();
        $roll = $_SESSION['usuario']->getId_roll();
        $usuario = $_SESSION['usuario']->getId_usuario();
        $fecha = date('Y-m-d');
        $maquinas = $this->MaquinasDAO->consultar_maquinas_embobinado(2);
        $envio = [];
        $maquina_operario = $this->ProgramacionOperarioDAO->ConsultaPersonaFecha($persona, $fecha);
        $maquina_persona = 0;
        if (!empty($maquina_operario)) {
            $maquina_persona = $maquina_operario[0]->id_maquina;
        }
        $Q_maquinas = [];
        foreach ($maquinas as $value) {
            if ($roll == 1 || $roll == 9 || $roll == 8 || $roll == 12) {
                $envio[] = $value;
                $datos = 1;
                $Q_maquinas[] = ['id_maquina' => $value->id_maquina];
                if ($usuario == 13 || $usuario == 8) {
                    $datos = 2;
                }
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
            'produccion/vista_trabajo_embobinado',
            [
                'maquinas_pro' => $envio,
                'datos' => $datos,
                'maquinas' => $this->MaquinasDAO->consultar_maquinas(),
                'q_maquinas' => json_encode($Q_maquinas),
            ]
        );
        $this->view('produccion/modal_impresion');
    }

    public function consultar_trabajo_embobinado($respuesta = '')
    {
        header('Content-Type: application/json');
        $estado = '12,13';
        $op = $this->MaquinaEmbobinadoDAO->consultar_maquinas_embobinado($estado);
        $data = $op;
        if ($respuesta != '') {
            return json_encode($data);
        } else {
            echo json_encode($data);
            return;
        }
    }

    public function ejecuta_inicio_embo()
    {
        header('Content-Type: application/json');
        $data = $_POST['data'];
        $id_usuario = $_POST['usuario'];
        $estado_item_producir = $_POST['estado_item_producir'];
        $id_actividad_area = $_POST['id_actividad_area'];
        // Cambiar el estado en item producir 
        $edita_maquina_embobinado = [
            'estado_item_producir' => $estado_item_producir
        ];
        $condicion_item_producir = 'id_maquina =' . $data['id_maquina'];
        $this->MaquinaEmbobinadoDAO->editar($edita_maquina_embobinado, $condicion_item_producir);
        // Realizar el seguimiento en la tabla seguimiento_produccion
        $actividad_area = $this->ActividadAreaDAO->consultar_id_actividad_area($id_actividad_area);
        $seguimiento_produccion = [
            'num_produccion' => $data['num_produccion'],
            'id_maquina' => $data['maquina'],
            'id_persona' => $id_usuario,
            'id_area' => $actividad_area[0]->id_area_trabajo,
            'id_actividad' => $actividad_area[0]->id_actividad_area,
            'observacion_op' => $actividad_area[0]->nombre_actividad_area,
            'estado_produccion' => 1,
            'fecha_crea' => date('Y-m-d'),
            'hora_crea' => date('H:i:s'),
        ];
        $this->SeguimientoProduccionDAO->insertar($seguimiento_produccion);
        $respu = $this->consultar_trabajo_embobinado();
        return $respu;
    }

    public function inicio_embo_item()
    {
        header('Content-Type: application/json');
        $datos = $_POST['envio'];
        $id_persona = $_POST['id_persona_sesion'];
        foreach ($datos as $value) {
            $edita_pedido_item = [
                'id_estado_item_pedido' => $value['estado_cambio_item_pedido']
            ];
            $condicion_pedido_item = 'id_pedido_item =' . $value['id_pedido_item'];
            $this->PedidosItemDAO->editar($edita_pedido_item, $condicion_pedido_item);
        }
        $json = $this->datos_programacion_embobinado($datos[0]['n_produccion'], $datos[0]['id_maqui_embo']);
        $respu = (array)json_decode($json, true);
        echo json_encode($respu);
        return;
    }

    public function personas_troquelado()
    {
        header('Content-Type: application/json');
        $n_produccion = $_POST['n_produccion'];
        $dato_item_producir = $this->ItemProducirDAO->consultar_item_producir_num($n_produccion);
        $operarios = $this->MetrosLinealesDAO->empleados_ml_op($dato_item_producir[0]->id_item_producir);
        echo json_encode($operarios);
        return;
    }

    public function reportar_embobinado_etiquetas()
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
        if ($parcial and !$inicio_nuevo_trabajo) {
            $estado_maquina_embobinado = true;
        }
        if (!$parcial and !$inicio_nuevo_trabajo) {
            $datos_faltantes = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($data_reporte[0]['n_produccion'], $data_maquina['id_maquina']);
            $cuenta_faltante = 0;
            foreach ($datos_faltantes as $dato) {
                if ($dato->id_estado_item_pedido == 20 || $dato->id_estado_item_pedido == 21) {
                    $cuenta_faltante = $cuenta_faltante + 1;
                }
            }
            if ($cuenta_faltante == 0) {
                $id_actividad_area = 12;
                $estado_maquina_embobinado = true;
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
            $condicion_maquina_embo = 'id_maquina =' . $data_maquina['id_maquina'];
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
            $json = $this->consultar_trabajo_embobinado(1);
            $k = (array)json_decode($json, true);
            $respu_total = [
                'status' => 1,
                'respu' => $k
            ];
        } else {
            $json = $this->datos_programacion_embobinado($data_reporte[0]['n_produccion'], $data_maquina['id_maquina']);
            $k = json_decode($json, true);
            $respu_total = [
                'status' => 2,
                'respu' => $k
            ];
        }
        echo json_encode($respu_total);
        return;
    }

    public function consultar_turno_embobinado()
    {
        header('Content-Type: application/json');
        $fecha_embo = $_POST['fecha_produccion'];
        $id_maquina = $_POST['id_maquina'];
        $datos = $this->MaquinaEmbobinadoDAO->consulta_turno_maquina($id_maquina, $fecha_embo);
        echo json_encode($datos);
        return;
    }

    public function cambiar_maquina_embo()
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

        $respu = $this->consultar_trabajo_embobinado();
        return $respu;
    }
}

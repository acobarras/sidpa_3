<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\TintasDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\EstadoItemPedidoDAO;
use MiApp\persistencia\dao\SeguimientoProduccionDAO;

class Orden_produccionControlador extends GenericoControlador
{
    private $ItemProducirDAO;
    private $TintasDAO;
    private $PedidosItemDAO;
    private $productosDAO;
    private $EstadoItemPedidoDAO;
    private $SeguimientoProduccionDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->TintasDAO = new TintasDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->EstadoItemPedidoDAO = new EstadoItemPedidoDAO($cnn);
        $this->SeguimientoProduccionDAO = new SeguimientoProduccionDAO($cnn);
    }

    public function modificar_orden_produccion()
    {
        parent::cabecera();
        $this->view(
            'configuracion/modificar_orden_produccion',
            [
                "productos" => $this->productosDAO->consultar_productos_material_compras(),
                "estados_item" => $this->EstadoItemPedidoDAO->consultar_estados_items(),
            ]
        );
    }

    public function consultar_orden()
    {
        header('Content-Type: application/json');
        $datos = $this->ItemProducirDAO->consultar_num_produccion($_POST['num_produccion']);
        $tintas = $this->TintasDAO->consultar_tintas();
        foreach ($datos as $valor) {
            //Ml del item m2 del item
            $a = ($valor->cant_op * str_replace(',', '.', $valor->avance));
            if ($valor->cav_montaje == '' || $valor->cav_montaje == 0) {
                $valor->metrosl = "No Hay Cavidad";
                $valor->metros2 = 0;
                $valor->tintas = 0;
            } else {
                $valor->metrosl = "" . $a / ($valor->cav_montaje * 1000) . "";
                $valor->metros2 = "" . (($valor->ancho_material * $valor->metrosl) / 1000) . "";
                $caracter = "-";
                $posicion_coincidencia = strpos($valor->codigo, $caracter);
                $tinta_codigo = substr($valor->codigo, ($posicion_coincidencia + 6), 2);
                foreach ($tintas as $value) {
                    if ($value->numeros == $tinta_codigo) {
                        $valor->tintas = $value->num_tintas;
                    }
                }
            }
        }
        $respuesta = $datos;
        echo json_encode($respuesta);
    }

    public function editar_cantidad_op()
    {
        header('Content-Type: application/json');
        $datos = $_POST['datos'];
        $nueva_cant = $_POST['valor'];
        $editar = [
            'cant_op' => $nueva_cant,
        ];
        $condicion = 'id_pedido_item =' . $datos['id_pedido_item'];
        $nuevo = $this->PedidosItemDAO->editar($editar, $condicion);
        if ($nuevo == 1) {
            $item_producir = $this->ItemProducirDAO->consultar_item_producir_num($datos['n_produccion']);
            $cantidad_op = $item_producir[0]->cant_op - $datos['cant_op'];
            $cant_nueva = $cantidad_op + $nueva_cant;
            $ml = ($datos['avance'] * $cant_nueva);
            $ml2 = $datos['cav_montaje'] * 1000;
            $ml_total = intval($ml / $ml2);
            $editar_op = [
                'cant_op' => $cant_nueva,
                'mL_total' => $ml_total,
            ];
            $condicion = 'num_produccion =' . $datos['n_produccion'];
            $nueva_edita = $this->ItemProducirDAO->editar($editar_op, $condicion);
        }
        if ($nueva_edita == 1) {
            $respu = ['status' => 1];
        } else {
            $respu = ['status' => -1];
        }
        echo json_encode($respu);
    }

    public function consultar_material()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $item_producir = $this->ItemProducirDAO->consultar_item_producir_num($datos['n_produccion']);
        if ($item_producir == null) {
            $respu = [
                'status' => -1,
                'data' => '',
            ];
        } else {
            $respu = [
                'status' => 1,
                'data' => $item_producir
            ];
        }
        echo json_encode($respu);
    }

    public function cambio_material_op()
    {
        header('Content-Type: application/json');
        $datos = $_POST['envio'];
        $data = $_POST['datos']['data'];
        $editar = [
            'material_solicitado' => $datos['material_nuevo'],
        ];
        $condicion = 'num_produccion =' . $data[0]['num_produccion'];
        $nuevo = $this->ItemProducirDAO->editar($editar, $condicion);
        if ($nuevo == 1) {
            $respu = [
                'status' => 1,
            ];
        }
        echo json_encode($respu);
    }

    public function modificar_estado_op()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $consulta_datos = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($datos['form'][0]['value']);
        $consulta_maquina = $this->ItemProducirDAO->consultar_item_producir_num($datos['form'][0]['value']);
        foreach ($consulta_datos as $value) {
            $value->total_items = count($consulta_datos);
            $value->material = $consulta_maquina[0]->material;
            $value->nombre_maquina = $consulta_maquina[0]->nombre_maquina;
            $value->turno_maquina = $consulta_maquina[0]->turno_maquina;
            $value->id_maquina = $consulta_maquina[0]->id_maquina;
        }
        echo json_encode($consulta_datos);
        return;
    }

    public function modificar_estado_orden()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $observacion_seg = '';
        if (!array_key_exists('selecciona', $datos)) {
            if ($datos['nuevo_estado'] == 0) {
                // EDITA A ESTADO 14 LA ORDEN DE PRODUCCION
                $editar = [
                    'estado_item_producir' => 14,
                ];
                $observacion_seg = 'O.P FINALIZADA INESPERADAMENTE';
            } else {
                // EDITA AL ESTADO SELECCIONADO LA ORDEN DE PRODUCCION
                $editar = [
                    'estado_item_producir' => $datos['nuevo_estado'],
                ];
                $observacion_seg = 'O.P CAMBIADA AL ESTADO ' . strtoupper($datos['texto_estado']) . ' (' . $datos['nuevo_estado'] . ')';
            }
            $condicion = 'num_produccion =' . $datos['num_produccion'];
            $nuevo = $this->ItemProducirDAO->editar($editar, $condicion);

            $seguimiento = [
                'num_produccion' => $datos['num_produccion'],
                'id_maquina' => $datos['id_maquina'],
                'id_persona' => $_SESSION['usuario']->getId_persona(),
                'id_area' => 3, //area produccion
                'id_actividad' => 104,
                'observacion_op' => $observacion_seg,
                'estado_produccion' => 1,
                'fecha_crea' => date('Y-m-d'),
                'hora_crea' => date('H:i:s'),
            ];
            $this->SeguimientoProduccionDAO->insertar($seguimiento);
        } else {
            if (count($datos['selecciona']) == $datos['selecciona'][0]['total_items']) {
                $cadena = '';
                foreach ($datos['selecciona'] as $value) {
                    // SE ELIMINA LA OP DEL ITEM DEL PEDIDO
                    $cadena = $cadena . $value['num_pedido'] . '-' . $value['item'] . ',';
                    $editar_item = [
                        'n_produccion' => 0,
                    ];
                    $condicion_item = 'id_pedido_item =' . $value['id_pedido_item'];
                    $item_edita = $this->PedidosItemDAO->editar($editar_item, $condicion_item);
                }
                $seguimiento_item_op = [
                    'num_produccion' => $datos['num_produccion'],
                    'id_maquina' => $datos['id_maquina'],
                    'id_persona' => $_SESSION['usuario']->getId_persona(),
                    'id_area' => 3, //area produccion
                    'id_actividad' => 104,
                    'observacion_op' => 'SE ELIMINO EL PEDIDO ' . $cadena . ' DE LA O.P',
                    'estado_produccion' => 1,
                    'fecha_crea' => date('Y-m-d'),
                    'hora_crea' => date('H:i:s'),
                ];
                $this->SeguimientoProduccionDAO->insertar($seguimiento_item_op);

                $editar = [
                    'estado_item_producir' => 14,
                ];
                $condicion = 'num_produccion =' . $datos['num_produccion'];
                $nuevo = $this->ItemProducirDAO->editar($editar, $condicion);

                $seguimiento_op = [
                    'num_produccion' => $datos['num_produccion'],
                    'id_maquina' => $datos['id_maquina'],
                    'id_persona' => $_SESSION['usuario']->getId_persona(),
                    'id_area' => 3, //area produccion
                    'id_actividad' => 104,
                    'observacion_op' => 'O.P FINALIZADA INESPERADAMENTE',
                    'estado_produccion' => 1,
                    'fecha_crea' => date('Y-m-d'),
                    'hora_crea' => date('H:i:s'),
                ];
                $this->SeguimientoProduccionDAO->insertar($seguimiento_op);
            } else {
                $cadena = '';
                foreach ($datos['selecciona'] as $value) {
                    $cadena = $cadena . $value['num_pedido'] . '-' . $value['item'] . ',';
                    $editar_item = [
                        'n_produccion' => 0,
                    ];
                    $condicion_item = 'id_pedido_item =' . $value['id_pedido_item'];
                    $nuevo = $this->PedidosItemDAO->editar($editar_item, $condicion_item);
                }

                $seguimiento_item_op = [
                    'num_produccion' => $datos['num_produccion'],
                    'id_maquina' => $datos['id_maquina'],
                    'id_persona' => $_SESSION['usuario']->getId_persona(),
                    'id_area' => 3, //area produccion
                    'id_actividad' => 104,
                    'observacion_op' => 'SE ELIMINO EL PEDIDO ' . $cadena . ' DE LA O.P',
                    'estado_produccion' => 1,
                    'fecha_crea' => date('Y-m-d'),
                    'hora_crea' => date('H:i:s'),
                ];
                $this->SeguimientoProduccionDAO->insertar($seguimiento_item_op);
            }
        }
        echo json_encode($nuevo);
        return;
    }
}

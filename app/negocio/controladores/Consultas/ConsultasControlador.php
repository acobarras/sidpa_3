<?php

namespace MiApp\negocio\controladores\Consultas;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\generico\GenericoDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\SeguimientoProduccionDAO;
use MiApp\persistencia\dao\ActividadAreaDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\SeguimientoPqrDAO;
use MiApp\persistencia\dao\GestionPqrDAO;
use MiApp\persistencia\dao\cliente_productoDAO;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\SoporteTecnicoDAO;

use MiApp\negocio\util\Validacion;

class ConsultasControlador extends GenericoControlador
{
    private $SeguimientoOpDAO;
    private $SeguimientoProduccionDAO;
    private $ActividadAreaDAO;
    private $PedidosItemDAO;
    private $SeguimientoPqrDAO;
    private $GestionPqrDAO;
    private $cliente_productoDAO;
    private $direccionDAO;
    private $SoporteTecnicoDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);;
        $this->SeguimientoProduccionDAO = new SeguimientoProduccionDAO($cnn);;
        $this->ActividadAreaDAO = new ActividadAreaDAO($cnn);;
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);;
        $this->SeguimientoPqrDAO = new SeguimientoPqrDAO($cnn);
        $this->GestionPqrDAO = new GestionPqrDAO($cnn);
        $this->cliente_productoDAO = new cliente_productoDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->SoporteTecnicoDAO = new SoporteTecnicoDAO($cnn);
    }

    public function vista_consultas()
    {
        parent::cabecera();
        $this->view(
            'consultas/vista_consultas',
            [
                "nombre_actividad" => $this->ActividadAreaDAO->NombreActivida()
            ]
        );
    }
    public function consulta_pedido_item()
    {
        header('Content-Type:application/json');
        $num_pedido = $_POST['num_pedido'];
        $respuesta['data'] = $this->SeguimientoOpDAO->consulta_item_pedido($num_pedido);
        echo json_encode($respuesta);
        return;
    }

    public function consulta_detalle_pedido_item()
    {
        header('Content-Type:application/json');
        $form = $_POST;
        $num_pedido = $form['num_pedido'];
        $item = $form['item'];
        $respuesta['data'] = $this->SeguimientoOpDAO->consultar_seguimiento_item($num_pedido, $item);
        echo json_encode($respuesta);
        return;
    }
    public function consulta_op()
    {
        header('Content-Type:application/json');
        $n_produccion = $_POST['n_produccion'];
        $respuesta['data'] = $this->SeguimientoProduccionDAO->consultar_seguimiento_op($n_produccion);
        echo json_encode($respuesta);
        return;
    }
    public function consulta_fecha()
    {
        header('Content-Type:application/json');

        $form = Validacion::Decodifica($_POST['form1']);
        $fecha_desde = $form['fecha_desde'];
        $fecha_hasta = $form['fecha_hasta'];
        $actividad = $form['actividad'];
        $respuesta['data'] = $this->SeguimientoProduccionDAO->consultar_seguimiento_fecha($fecha_desde, $fecha_hasta, $actividad);
        echo json_encode($respuesta);
        return;
    }
    public function numero_pedido_op()
    {
        header('Content-Type:application/json');
        $form = $_POST['form'];
        $datos['data'] = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($form);
        echo json_encode($datos);
        return;
    }
    public function trasavilidad_pedido()
    {
        header('Content-Type: application/json');
        $form = $_POST['form'];
        $form = Validacion::Decodifica($form);
        $fecha_desde = $form['fecha_inicial'];
        $fecha_hasta = $form['fecha_final'];
        $datos['data'] = $this->SeguimientoOpDAO->movimientos_item_pedido($fecha_desde, $fecha_hasta);
        echo json_encode($datos);
        return;
    }

    public function numero_pqr()
    {
        header('Content-Type: application/json');
        $form = $_POST['form'];
        $datos = $this->SeguimientoPqrDAO->consultar_seguimiento_pqr($form);
        foreach ($datos as $value) {
            $value->fecha_segui = date('Y-m-d', strtotime($value->fecha_seguimiento));
            $value->hora_segui = date('H:i:s', strtotime($value->fecha_seguimiento));
        }
        $info = $this->GestionPqrDAO->consultar_num_pqr($form);
        foreach ($info as $value) {
            $datos_item = $this->PedidosItemDAO->ConsultaIdPedidoItem($value->id_pedido_item);
            $value->datos_item = $datos_item;
            $datos_producto = $this->cliente_productoDAO->cliente_producto_id_dell($value->id_clien_produc);
            $value->datos_producto = $datos_producto;
            $datos_direccion = $this->direccionDAO->consultaIdDireccion($value->id_dir_pqr);
            $value->datos_direccion = $datos_direccion;
        }
        $res = [
            'data' => $datos,
            'info' => $info,
        ];
        echo json_encode($res);
        return;
    }
    public function numero_diag()
    {
        header('Content-Type: application/json');
        $form = $_POST['form'];
        $info = $this->SoporteTecnicoDAO->consulta_num_diag($form);
        foreach ($info as $value) {
            if ($value->item == 0) {
                $value->serial_equipo = 'No registrado';
                $value->procedimiento = 'No registrado';
                $value->accesorios = 'No registrado';
                $value->equipo = 'No registrado';
                $value->item = $value->item_segui;
            } else {
                $consulta_item = $this->SoporteTecnicoDAO->consulta_item($value->item, $form);
                foreach ($consulta_item as $value_item) {
                    if ($value->item == $value_item->item) {
                        $value->serial_equipo = $value_item->serial_equipo;
                        $value->procedimiento = $value_item->procedimiento;
                        $value->accesorios = $value_item->accesorios;
                        $value->equipo = $value_item->equipo;
                        $value->item = $value_item->item;
                    }
                }
            }
        }
        $res['data'] = $info;
        echo json_encode($res);
        return;
    }
}

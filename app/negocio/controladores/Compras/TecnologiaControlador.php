<?php

namespace MiApp\negocio\controladores\Compras;

use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\Envio_Correo;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\PersonaDAO;

class TecnologiaControlador extends GenericoControlador
{
    private $PedidosItemDAO;
    private $SeguimientoOpDAO;
    private $PedidosDAO;
    private $PersonaDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
    }

    public function vista_compra_tecnologia()
    {
        parent::cabecera();
        parent::validarSesion();
        $this->view(
            'Compras/vista_compra_tecnologia'
        );
    }
    public function consultar_items_pendientes_compra()
    {
        header('Content-Type: application/json');
        $condicion = 't1.id_estado_item_pedido IN(5,22) AND t10.id_clase_articulo =3  ORDER BY t1.fecha_crea DESC';
        $items = $this->PedidosItemDAO->consultar_items_pendientes_compra($condicion);
        foreach ($items as $valor) {
            //cantidad faltante item
            $valor->cant_faltante = $valor->Cant_solicitada - $valor->cant_bodega;
        }
        $resultado['data'] = $items;
        echo json_encode($resultado);
    }
    public function fecha_pendiente_etiquetas_tecnologia()
    {
        header('Content-Type: application/json');

        $item = $_POST;

        //crear foreach para recorrer los item de cada orden de produccion

        //------------------------------------------------------------------
        //crear el registro (seguimiento_op) de cada item de la orden de produccion 
        $seguimiento_op['id_persona'] = $_SESSION['usuario']->getId_persona();
        $seguimiento_op['id_area'] = 5; //COMPRAS
        $seguimiento_op['id_actividad'] = 43; //espera de fecha
        $seguimiento_op['pedido'] = $item['num_pedido'];
        $seguimiento_op['item'] = $item['item'];
        $seguimiento_op['observacion'] = " ";
        $seguimiento_op['estado'] = 1;
        $seguimiento_op['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $seguimiento_op['fecha_crea'] = date('Y/m/d');
        $seguimiento_op['hora_crea'] = date('H:i:s');
        $this->SeguimientoOpDAO->insertar($seguimiento_op); //insertar el registro

        //modificar el estado de cada item de la orden de produccion
        $estado_item['id_estado_item_pedido'] = 22;
        $condicion = 'id_pedido_item =' . $item['id_pedido_item'];
        $respuesta = $this->PedidosItemDAO->editar($estado_item, $condicion); //modificar estado
        $respu = [];
        if (!empty($respuesta)) {
            $respu = [
                'state' => 1,
                'msg' => 'Se ha reportado la espera de fecha.'
            ];
        } else {
            $respu = [
                'state' => -1,
                'msg' => 'Error interno.'
            ];
        }
        echo json_encode($respu);
    }
    public function asigna_material_tec()
    {
        header('Content-Type: application/json');
        $form = Validacion::Decodifica($_POST['form1']);
        foreach ($_POST['datos'] as $value) {
            $item['orden_compra'] = $form['orden_compra'];
            $item['fecha_proveedor'] = $form['fecha_proveedor'];
            $item['fecha_compro_item'] = Validacion::aumento_fechas(date_create($form['fecha_proveedor']), 2);
            $item['id_estado_item_pedido'] = 16; //VALIDACION LOGISTICA

            $condicion = 'id_pedido_item =' . $value['id_pedido_item'];
            $this->PedidosItemDAO->editar($item, $condicion);

            $fecha_compro = $this->PedidosItemDAO->ValidaFechaCompromiso($value['id_pedido']);

            if ($fecha_compro != '0000-00-00') {
                // Editar el pedido
                $pedido['fecha_compromiso'] = $fecha_compro;
                $condicion_pedido = 'id_pedido =' . $value['id_pedido'];
                $this->PedidosDAO->editar($pedido, $condicion_pedido);
                // Envio del correo fecha de compromiso
                $info_correo = $this->PedidosDAO->consulta_pedidos('t1.id_pedido =' .  $value['id_pedido']);
                $persona = $this->PersonaDAO->consultar_personas_id($info_correo[0]->id_persona);
                // $asesor = 'edwin.rios@acobarras.com';
                $asesor = $persona[0]->correo;
                // $cliente = 'edwin.rios@acobarras.com';
                $cliente = $info_correo[0]->email;
                Envio_Correo::correo_confirmacion_fecha_compromiso($info_correo, $fecha_compro, $cliente, $asesor);
            }
            if ($value['id_clase_articulo'] == 2) {
                $seguimiento_op['id_area'] = 5;
                $seguimiento_op['id_actividad'] = 45;
            } else {
                $seguimiento_op['id_area'] = 5;
                $seguimiento_op['id_actividad'] = 47;
            }
            $seguimiento_op['pedido'] = $value['num_pedido'];
            $seguimiento_op['item'] = $value['item'];
            $seguimiento_op['observacion'] = "";
            $seguimiento_op['estado'] = 1;
            $seguimiento_op['id_usuario'] = $_SESSION['usuario']->getId_usuario();
            $seguimiento_op['fecha_crea'] = date('Y/m/d');
            $seguimiento_op['hora_crea'] = date('H:i:s');
            $respuesta = $this->SeguimientoOpDAO->insertar($seguimiento_op);
        }
        // Hasta cuando termina el ciclo continua
        $respu = [];
        if (!empty($respuesta)) {
            $respu = [
                'state' => 1,
                'msg' => 'Se ha reportado la Fecha Entrega.'
            ];
        } else {
            $respu = [
                'state' => -1,
                'msg' => 'Error interno.'
            ];
        }
        echo json_encode($respu);
    }
}

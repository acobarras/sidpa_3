<?php

namespace MiApp\negocio\controladores\Compras;

use MiApp\negocio\util\Validacion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\SeguimientoProduccionDAO;

class MateriaPrimaControlador extends GenericoControlador
{
    private $ItemProducirDAO;
    private $productosDAO;
    private $PedidosItemDAO;
    private $SeguimientoOpDAO;
    private $SeguimientoProduccionDAO;



    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();


        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->SeguimientoProduccionDAO = new SeguimientoProduccionDAO($cnn);
    }

    public function vista_compra_materia_prima()
    {
        parent::cabecera();
        parent::validarSesion();
        $this->view(
            'Compras/vista_compra_materia_prima',
            [
                "productos" => $this->productosDAO->consultar_productos_material_compras()

            ]
        );
    }
    /**
     * Funcion para consultar las ordenes de produccion que requieren material
     */
    public function consultar_ordenes_produccion()
    {
        header('Content-Type: application/json');
        $op = $this->ItemProducirDAO->consultar_item_producir_ordenes(2);
        $resultado['data'] = $op;
        echo json_encode($resultado);
    }
    /**
     * Funcion para 
     */
    public function espera_por_fecha()
    {
        header('Content-Type: application/json');
        $valor = $_POST;
        //consultar los item de cada orden de produccion 
        $item = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($valor['num_produccion']);
        //crear foreach para recorrer los item de cada orden de produccion
        foreach ($item as $data) {
            //------------------------------------------------------------------
            //crear el registro (seguimiento_op) de cada item de la orden de produccion 
            $seguimiento_op['id_persona'] = $_SESSION['usuario']->getId_persona();
            $seguimiento_op['id_area'] = 5; //COMPRAS
            $seguimiento_op['id_actividad'] = 43; //espera de fecha
            $seguimiento_op['pedido'] = $data->num_pedido;
            $seguimiento_op['item'] = $data->item;
            $seguimiento_op['observacion'] = "PENDIENTE FECHA PROVEEDOR";
            $seguimiento_op['estado'] = 1;
            $seguimiento_op['id_usuario'] = $_SESSION['usuario']->getId_usuario();
            $seguimiento_op['fecha_crea'] = date('Y/m/d');
            $seguimiento_op['hora_crea'] = date('H:i:s');
            $this->SeguimientoOpDAO->insertar($seguimiento_op); //insertar el registro
        }

        $seguimiento_produccion['num_produccion'] = $valor['num_produccion'];
        $seguimiento_produccion['id_maquina'] = $valor['maquina'];
        $seguimiento_produccion['id_persona'] = $_SESSION['usuario']->getId_persona();
        $seguimiento_produccion['id_area'] = 5;
        $seguimiento_produccion['id_actividad'] = 43;
        $seguimiento_produccion['observacion_op'] = "PENDIENTE FECHA PROVEEDOR";
        $seguimiento_produccion['fecha_crea'] = date('Y/m/d');
        $seguimiento_produccion['hora_crea'] = date('H:i:s');
        $this->SeguimientoProduccionDAO->insertar($seguimiento_produccion);

        $obj['espera_material'] = 1; //en espera 
        $respuesta = $this->ItemProducirDAO->editar($obj, ' num_produccion = ' . $valor['num_produccion']);
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

    public function asigna_material()
    {
        header('Content-Type: application/json');

        $form = Validacion::Decodifica($_POST['form1']);
        // foreach para recorrer las ordenes de produccion y editarla
        foreach ($_POST['datos'] as $valor) {
            $orden['estado_item_producir'] = 3;
            $orden['material_solicitado'] = $form['material_solicitado'];
            $orden['precio_material'] = $form['precio_material'];
            $orden['orden_compra'] = $form['orden_compra'];
            $orden['ancho_confirmado'] = $form['ancho_confirmado'];
            $orden['fecha_proveedor'] = $form['fecha_proveedor'];
            $this->ItemProducirDAO->editar($orden, 'id_item_producir =' . $valor['id_item_producir']);

            //consultar los item de cada orden de produccion 
            $item = $this->PedidosItemDAO->ConsultaNumeroPedidoOp($valor['num_produccion']);
            //crear foreach para recorrer los item de cada orden de produccion
            foreach ($item as $data) {
                //------------------------------------------------------------------
                //crear el registro (seguimiento_op) de cada item de la orden de produccion 
                $seguimiento_op['id_persona'] = $_SESSION['usuario']->getId_persona();
                $seguimiento_op['id_area'] = 5; //COMPRAS
                $seguimiento_op['id_actividad'] = 44; //MATERIAL CONFIRMADO
                $seguimiento_op['pedido'] = $data->num_pedido;
                $seguimiento_op['item'] = $data->item;
                $seguimiento_op['observacion'] = " ";
                $seguimiento_op['estado'] = 1;
                $seguimiento_op['id_usuario'] = $_SESSION['usuario']->getId_usuario();
                $seguimiento_op['fecha_crea'] = date('Y/m/d');
                $seguimiento_op['hora_crea'] = date('H:i:s');
                $this->SeguimientoOpDAO->insertar($seguimiento_op); //insertar el registro
                //modificar el estado de cada item de la orden de produccion
                $estado_item['id_pedido_item'] = $data->id_pedido_item;
                $estado_item['id_estado_item_pedido'] = 3;
                $this->PedidosItemDAO->editar($estado_item, ' id_pedido_item =' . $data->id_pedido_item); //modificar estado
            }
            $seguimiento_produccion['num_produccion'] = $valor['num_produccion'];
            $seguimiento_produccion['id_maquina'] =  $valor['id_maquina'];
            $seguimiento_produccion['id_persona'] = $_SESSION['usuario']->getId_persona();
            $seguimiento_produccion['id_area'] = 5; //
            $seguimiento_produccion['id_actividad'] = 44; //material confirmado
            $seguimiento_produccion['fecha_crea'] = date('Y/m/d');
            $seguimiento_produccion['hora_crea'] = date('H:i:s');
            $respuesta = $this->SeguimientoProduccionDAO->insertar($seguimiento_produccion);
        }
        $respu = [];
        if (!empty($respuesta)) {
            $respu = [
                'state' => 1,
                'msg' => 'Se asignó correctamente.'
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

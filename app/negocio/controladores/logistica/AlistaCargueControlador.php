<?php

namespace MiApp\negocio\controladores\logistica;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\negocio\util\PDF;



class AlistaCargueControlador extends GenericoControlador
{
    private $EntregasLogisticaDAO;
    private $PedidosItemDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
    }

    public function alistamiento_cargue()
    {
        parent::cabecera();
        $this->view(
            'logistica/alistamiento_cargue'
        );
    }

    public function consultar_pedidos_alista()
    {
        header('Content-Type: application/json');
        $datos = $this->EntregasLogisticaDAO->consultar_pedidos_alista();
        $data['data'] = $datos;
        echo json_encode($data);
    }
    public function doc_alistamiento_cargue()
    {
        header('Content-Type: application/json');
        $datos = $_POST['datos'];
        foreach ($datos as $key => $value) {
            $datos_items = $this->EntregasLogisticaDAO->consulta_items_alista($value['num_pedido']);
            $datos[$key]['items'] = $datos_items;
        }
        $data = [];
        foreach ($datos as $pedido) {
           
            foreach ($pedido['items'] as $item) {
                $data1 = array(
                    'nombre_empresa' => $pedido['nombre_empresa'],
                    'num_pedido' => $pedido['num_pedido'],
                    'direccion' => $pedido['direccion'],
                    'item' => $item->item,
                    'codigo' => $item->codigo,
                    'descripcion_productos' => $item->descripcion_productos,
                    'nombre_ruta' => $pedido['nombre_ruta'],
                    'ubicacion_material' => $item->ubicacion,
                    'cant_solicitada' => $item->Cant_solicitada,
                    'cantidad_pendiente' => $item->cantidad_pendiente,
                    'modulo' => $item->modulo
                );
                array_push($data, $data1);
            }
        }
        echo json_encode($data);
        return;
    }
}

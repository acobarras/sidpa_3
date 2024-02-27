<?php

namespace MiApp\negocio\controladores\logistica;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\SeguimientoProduccionDAO;


class PedidosAtrasadosControlador extends GenericoControlador
{
    private $PedidosDAO;
    private $EntregasLogisticaDAO;
    private $SeguimientoOpDAO;
    private $SeguimientoProduccionDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->SeguimientoProduccionDAO = new SeguimientoProduccionDAO($cnn);
    }

    public function pedidos_atrasados()
    {
        parent::cabecera();
        $this->view(
            'logistica/pedidos_atrasados'
        );
    }
    public function consulta_pedidos()
    {
        header('Content-Type: application/json');
        $pedidos_atrasados = $this->PedidosDAO->consulta_pedidos_atrasados($_POST);
        foreach ($pedidos_atrasados as $value) {
            $items_pedido = $this->PedidosDAO->conteo_items_pedido($value->id_pedido);
            $value->items_pedido = $items_pedido[0]->items_pedido;
            $value->items_op = $items_pedido[0]->items_op;
        }
        $res['data'] = $pedidos_atrasados;
        // echo json_encode($pedidos_atrasados);
        echo json_encode($res);
        return;
    }
    public function consulta_seguimientos()
    {
        header('Content-Type: application/json');
        $items = $this->PedidosDAO->consulta_items_idpedido($_POST['data']['id_pedido']);

        // foreach ($items as $value) {
        //     $value->pedido = $_POST['data']['num_pedido'];
        //     $reporte_fac = $this->EntregasLogisticaDAO->registro_entregas_logistica($value->id_pedido_item);
        //     if (empty($reporte_fac)) {
        //         $value->cant_reportada = '0';
        //     } else {
        //         $value->cant_reportada = $reporte_fac[0]->cantidad_factura;
        //     }
        // }
        echo json_encode($items);
        return;
    }
    public function movimientos_item()
    {
        header('Content-Type: application/json');
        if ($_POST['data']['n_produccion'] == 0) {
            $movimientos = $this->SeguimientoOpDAO->consultar_seguimiento_item($_POST['data']['pedido'], $_POST['data']['item']);
        } else {
            $movimientos = $this->SeguimientoProduccionDAO->consultar_seguimiento_op($_POST['data']['n_produccion']);
            foreach ($movimientos as $value) {
                $value->observacion = $value->observacion_op;
            }
        }
        $res['data'] = $movimientos;
        echo json_encode($res);
        return;
    }
    public function pedidos_incompletos()
    {
        header('Content-Type: application/json');
        $items_incompletos = $this->PedidosDAO->consulta_items_incompletos();
        $res['data'] = $items_incompletos;
        echo json_encode($res);
    }
}

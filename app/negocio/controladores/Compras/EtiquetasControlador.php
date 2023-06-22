<?php

namespace MiApp\negocio\controladores\Compras;


use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PedidosItemDAO;

class EtiquetasControlador extends GenericoControlador
{
    private $PedidosItemDAO;


    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
    }

    public function vista_compra_etiquetas()
    {
        parent::cabecera();
        parent::validarSesion();

        $this->view(
            'Compras/vista_compra_etiquetas'
        );
    }
    /**
     * Función para consultar items compra etiquetas
     */
    public function consultar_items_pendientes_compra_etiq()
    {
        header('Content-Type: application/json');
        $condicion = 't1.id_estado_item_pedido IN(5,22) AND t10.id_clase_articulo =2  ORDER BY t1.fecha_crea DESC';
        $items = $this->PedidosItemDAO->consultar_items_pendientes_compra($condicion);
        foreach ($items as  $valor) {
            //cantidad faltante item
            $valor->cant_faltante = $valor->Cant_solicitada - $valor->cant_bodega;
        }
        $resultado['data'] = $items;
        echo json_encode($resultado);
    }

}

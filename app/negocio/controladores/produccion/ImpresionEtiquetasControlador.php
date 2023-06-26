<?php

namespace MiApp\negocio\controladores\produccion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\PedidosItemDAO;

class ImpresionEtiquetasControlador extends GenericoControlador
{

    private $PedidosDAO;
    private $PedidosItemDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
    }

    public function vista_impresion_etiquetas()
    {
        parent::cabecera();
        $this->view('produccion/vista_impresion_etiquetas');
    }

    public function consultar_items_pedido_impresion()
    {
        header('Content-Type: application/json');
        $parametro = 't1.num_pedido =' . $_POST['num_pedido'];
        $id_pedido = $this->PedidosDAO->consulta_pedidos($parametro);
        if ($id_pedido != NULL) {
            $items = $this->PedidosItemDAO->ConsultaIdPedido($id_pedido[0]->id_pedido);
            foreach ($items as $value) {
                $caracter = "-";
                $posicion_coincidencia = strpos($value->codigo, $caracter); //posicion empezando desde el (-)
                $cav_presentacion = substr($value->codigo, ($posicion_coincidencia + 5), 1); //obtener la cav presentacion
                $value->cav_cliente = $cav_presentacion;
                $value->nombre_empresa = $id_pedido[0]->nombre_empresa;
                $value->num_pedido = $id_pedido[0]->num_pedido;
                $value->fecha_compromiso  = $id_pedido[0]->fecha_compromiso;
                $value->nombre_empresa = $id_pedido[0]->nombre_empresa;
                $value->id_persona = $id_pedido[0]->id_persona;
            }
        } else {
            $items = -1;
        }

        echo json_encode($items);
        return;
    }

    public function impresion_etiquetas_marcacion()
    {
        $this->zpl('impresion_etiquetas_marcacion');
    }
}

<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\TintasDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\productosDAO;

class Orden_produccionControlador extends GenericoControlador
{
    private $ItemProducirDAO;
    private $TintasDAO;
    private $PedidosItemDAO;
    private $productosDAO;
    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->TintasDAO = new TintasDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
    }

    public function modificar_orden_produccion()
    {
        parent::cabecera();
        $this->view(
            'configuracion/modificar_orden_produccion',
            [
                "productos" => $this->productosDAO->consultar_productos_material_compras()
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
}

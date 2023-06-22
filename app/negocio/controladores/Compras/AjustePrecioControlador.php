<?php

namespace MiApp\negocio\controladores\Compras;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\cliente_productoDAO;
use MiApp\persistencia\dao\productosDAO;

use MiApp\negocio\util\Validacion;

class AjustePrecioControlador extends GenericoControlador
{
    private $cliente_productoDAO;
    private $productosDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->cliente_productoDAO = new cliente_productoDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
    }

    public function vista_ajuste_precio()
    {
        $this->cabecera();
        $this->view(
            'Compras/vista_ajuste_precio',
            [
                "productos" => $this->productosDAO->consultar_productos_material(),
                'asesores' => $this->cliente_productoDAO->asesores_precios(),
            ]
        );
    }

    public function consulta_ajuste_precio()
    {
        header('Content-Type: application/json');
        $respuesta = $this->cliente_productoDAO->consultar_items_sin_precio();
        $res['data'] = $respuesta;
        echo json_encode($res);
    }
    public function ajuste_precio()
    {
        header('Content-Type: application/json');
        $form = Validacion::Decodifica($_POST['form1']);

        foreach ($_POST['datos'] as $value) {
            $ajuste_precio['moneda_autoriza'] = $form['moneda_autoriza'];
            $ajuste_precio['precio_autorizado'] = $form['precio_autorizado'];
            $ajuste_precio['cantidad_minima'] = $form['cantidad_minima'];
            $ajuste_precio['id_material'] = $form['id_material'];
            $condicion = 'id_clien_produc=' . $value['id_clien_produc'];
            $respuesta = $this->cliente_productoDAO->editar($ajuste_precio, $condicion);
        }
        $respu = [];
        if (!empty($respuesta)) {
            $respu = [
                'state' => 1,
                'msg' => 'Se Asignado precio Correctamente.'
            ];
        } else {
            $respu = [
                'state' => -1,
                'msg' => 'Error interno.'
            ];
        }
        echo json_encode($respu);
    }
    public function consulta_asesores_id()
    {
        header('Content-Type: application/json');
        $consulta = $this->cliente_productoDAO->consultar_productos_asesor();
        $productos = [];
        foreach ($consulta as $value) {
            if ($value->moneda_autoriza != 0) {
                if ($value->precio_autorizado != 0.00) {
                    $productos[] = $value;
                }
            }
        }
        $res['data'] = $productos;
        echo json_encode($res);
    }
    public function modificar_producto()
    {
        header('Content-Type: application/json');
        $condicion = 'id_clien_produc=' . $_POST['id_clien_produc'];
        unset( $_POST['id_clien_produc']);
        $respuesta = $this->cliente_productoDAO->editar($_POST, $condicion);
        $respu = [];
        if (!empty($respuesta)) {
            $respu = [
                'state' => 1,
                'msg' => 'Se Modificado producto Correctamente.'
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

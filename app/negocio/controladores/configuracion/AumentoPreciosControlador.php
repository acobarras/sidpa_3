<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\cliente_productoDAO;

class AumentoPreciosControlador extends GenericoControlador
{

    private $clientes_proveedorDAO;
    private $cliente_productoDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->cliente_productoDAO = new cliente_productoDAO($cnn);
    }

    public function vista_aumento_precio()
    {
        $cliente = $this->clientes_proveedorDAO->consultar_clientes();
        parent::cabecera();
        $this->view(
            'configuracion/vista_aumento_precio',
            [
                "clientes" => $cliente
            ]
        );
    }

    public function enviar_aumento_cliente()
    {
        header('Content-type: application/json');
        if ($_POST['clientes'][0] == 0) {
            $condicion = "WHERE t3.id_clase_articulo =" . $_POST['tipo_articulo'] . " AND t4.estado_cli_prov=1 AND t1.estado_client_produc=1";
            $consulta_clientes = $this->clientes_proveedorDAO->consultar_clientes_aumento($condicion);
        } else {
            $clientes = '';
            foreach ($_POST['clientes'] as $value) {
                if ($clientes == '') {
                    $clientes = $value;
                } else {
                    $clientes = $clientes . ',' . $value;
                }
            }
            $condicion = "WHERE t3.id_clase_articulo =" . $_POST['tipo_articulo'] . " AND t4.nit IN($clientes) 
            AND t4.estado_cli_prov=1 AND t1.estado_client_produc=1";
            $consulta_clientes = $this->clientes_proveedorDAO->consultar_clientes_aumento($condicion);
        }
        if (empty($consulta_clientes)) {
            $respu = [
                'status' => -1,
                'msg' => 'Los clientes seleccionados no tienen precios',
            ];
        } else {
            foreach ($consulta_clientes as $value) {
                $precio_autori = $value->precio_autorizado;
                // 1 es aumento y 2 es disminucion
                if ($_POST['aumento'] == 1) {
                    $incremento = ($precio_autori * $_POST['porcentaje']) / 100;
                    $nuevo_valor = $precio_autori + $incremento;
                    $nuevo_valor = number_format($nuevo_valor, 2, '.', '');
                } else {
                    $nuevo_valor = $precio_autori - (($precio_autori * $_POST['porcentaje']) / 100);
                    $nuevo_valor = number_format($nuevo_valor, 2, '.', '');
                }
                if ($_POST['ambos_precios'] == 1) {
                    $envio = [
                        'precio_autorizado' => $nuevo_valor,
                        'precio_venta' => $nuevo_valor,
                    ];
                } else {
                    $envio = [
                        'precio_autorizado' => $nuevo_valor,
                    ];
                }
                $condicion = 'id_clien_produc =' . $value->id_clien_produc;
                $this->cliente_productoDAO->editar($envio, $condicion);
            }
            $respu = [
                'status' => 1,
                'msg' => 'Precios modificados correctamente',
            ];
        }
        echo json_encode($respu);
        return;
    }
}

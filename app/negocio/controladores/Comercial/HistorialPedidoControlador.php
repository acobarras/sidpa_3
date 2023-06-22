<?php

namespace MiApp\negocio\controladores\Comercial;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\EstadoItemPedidoDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\TrazPedidoDAO;
use MiApp\negocio\util\PDF;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\cliente_productoDAO;
use MiApp\persistencia\dao\clientes_proveedorDAO;




class HistorialPedidoControlador extends GenericoControlador
{
    private $pedidosDAO;
    private $EstadoItemPedidoDAO;
    private $SeguimientoOpDAO;
    private $PersonaDAO;
    private $TrazPedidoDAO;
    private $direccionDAO;
    private $cliente_productoDAO;
    private $clientes_proveedorDAO;


    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->pedidosDAO = new PedidosDAO($cnn);
        $this->EstadoItemPedidoDAO = new EstadoItemPedidoDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->TrazPedidoDAO = new TrazPedidoDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->cliente_productoDAO = new cliente_productoDAO($cnn);
        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
    }

    /*  
     * Función para cargar la vista (Mis clientes)
     */
    public function vista_historial_pedidos()
    {
        parent::cabecera();
        // print_r($_SESSION['usuario']->getId_roll());
        if ($_SESSION['usuario']->getId_roll() != 8 && $_SESSION['usuario']->getId_roll() != 1) {
            $clientes = $this->clientes_proveedorDAO->consultar_clientes_asesor($_SESSION['usuario']->getId_persona());
        } else {
            $clientes = $this->clientes_proveedorDAO->consultar_clientes();
        }
        $this->view(
            'Comercial/vista_historial_pedidos',
            [
                'clientes' => $clientes,
            ]
        );
    }

    /*  
     * Función para consultar todos los pedidos especificos ingresados por el asesor.
     */
    public function consultar_pedidos_asesor()
    {
        header('Content-Type: application/json');
        $id_usuario = $_SESSION['usuario']->getId_usuario();
        if ($_POST['tipo'] == 1) {
            $cliente = $_POST['cliente'];
            if ($_SESSION['usuario']->getId_roll() == 8 || $_SESSION['usuario']->getId_roll() == 1) {
                $consulta = 'cliente_proveedor.id_cli_prov = ' . $cliente;
            } else {
                $consulta = "cliente_proveedor.id_cli_prov = '$cliente.' AND pedidos.id_usuario=$id_usuario";
            }
            $resultado = $this->pedidosDAO->consultar_pedidos_cliente($consulta);
        }
        if ($_POST['tipo'] == 2) {
            $fecha = $_POST['fecha'];
            if ($_SESSION['usuario']->getId_roll() == 8 || $_SESSION['usuario']->getId_roll() == 1) {
                $consulta = "pedidos.fecha_crea_p = '$fecha'";
            } else {
                $consulta = "pedidos.fecha_crea_p = '$fecha' AND pedidos.id_usuario=$id_usuario";
            }
            $resultado = $this->pedidosDAO->consultar_pedidos_cliente($consulta);
        }
        if ($_POST['tipo'] == 3) {
            $num_pedido = $_POST['num_pedido'];
            if ($_SESSION['usuario']->getId_roll() == 8 || $_SESSION['usuario']->getId_roll() == 1) {
                $consulta = "pedidos.num_pedido = $num_pedido";
            } else {
                $consulta = "pedidos.num_pedido=$num_pedido AND pedidos.id_usuario=$id_usuario";
            }
            $resultado = $this->pedidosDAO->consultar_pedidos_ase($consulta); //ESTA FUNCION ES PARA CONSULTAR LOS PEDIDOS DE CADA ASESOR
        }
        foreach ($resultado as $value) {
            $dir = $this->direccionDAO->consultaIdDireccion($value->id_dire_entre);
            $value->direccion_entrega = $dir[0]->direccion;
            $value->nombre_ciudad = $dir[0]->nombre_ciudad;
            $dir_radica = $this->direccionDAO->consultaIdDireccion($value->id_dire_radic);
            $value->direccion_radica = $dir_radica[0]->direccion;
            $value->nombre_ciudad_radica = $dir_radica[0]->nombre_ciudad;
        }
        $arreglo = $resultado;
        echo json_encode($arreglo);
    }

    /**
     * Función para consultar los items especificos que contiene cada pedido .
     **/

    public function consultar_items_pedido()
    {
        header('Content-Type: application/json');
        $resultado = $this->pedidosDAO->consultar_items_pedido();
        $estados_item = $this->EstadoItemPedidoDAO->consultar_estados_items();
        foreach ($resultado as $value) {
            $ficha = $this->cliente_productoDAO->cliente_producto_id($value->id_clien_produc);
            $value->estados_item = $estados_item;
            $value->roll = $_SESSION['usuario']->getId_roll();
            if (empty($ficha)) {
                $value->ficha_tecnica = 0;
                $value->id_material = 0;
                $value->id_clien_produc = 0;
            } else {
                $value->ficha_tecnica = $ficha[0]->ficha_tecnica;
                $value->id_material = $ficha[0]->id_material;
                $value->id_clien_produc = $ficha[0]->id_clien_produc;
            }
        }
        $arreglo["data"] = $resultado;
        echo json_encode($arreglo);
    }

    /**
     * Función para cargar EL SEGUIMIENTO DEL ITEM
     **/
    public function consultar_seguimiento_op_item()
    {
        header('Content-Type: application/json');
        $pedido = $_POST['num_pedido'];
        $item = $_POST['item'];

        $seguimiento_item = $this->SeguimientoOpDAO->consultar_seguimiento_item($pedido, $item);
        $data['data'] = $seguimiento_item;
        echo json_encode($data);
        return;
    }

    /**
     * Función para descargar el PDF generado para cada pedido. 
     **/

    public function pdf_pedido()
    {
        header('Content-type: application/pdf');
        $pedido = json_decode($_POST['valores'], true);
        $id_pedido['id_pedido'] = $pedido['id_pedido'];
        $persona = $this->PersonaDAO->consultar_personas_id($pedido['id_persona']);
        $direc_entre = $this->direccionDAO->consultaIdDireccion($pedido['id_dire_entre']);
        $direc_radic = $this->direccionDAO->consultaIdDireccion($pedido['id_dire_radic']);
        $items = $this->pedidosDAO->consultar_items_pedido_liberado($id_pedido);
        PDF::pdf_pedidos($persona[0], $pedido, $items, $direc_entre[0], $direc_radic[0]);
    }
}

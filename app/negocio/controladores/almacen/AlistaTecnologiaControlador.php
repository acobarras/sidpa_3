<?php

namespace MiApp\negocio\controladores\almacen;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\negocio\util\Envio_Correo;

use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\ConsCotizacionDAO;

class AlistaTecnologiaControlador extends GenericoControlador
{
    private $PedidosItemDAO;
    private $entrada_tecnologiaDAO;
    private $EntregasLogisticaDAO;
    private $SeguimientoOpDAO;
    private $PedidosDAO;
    private $PersonaDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
    }

    public function vista_alista_tecnologia()
    {
        parent::cabecera();
        $this->view(
            'almacen/vista_alistar_tecnologia'
        );
    }
    public function reportar_facturacion()
    {
        header('Content-Type: application/json'); //convierte a json
        $form = Validacion::Decodifica($_POST['form1']);
        $data = $_POST['data'];
        /* Registrar entregas_logistica tabla */
        $obj['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $obj['fecha_crea'] = date('Y-m-d');
        $obj['hora_crea'] = date('H:i:s');
        $obj['estado'] = 1;
        $obj['id_pedido_item'] = $_POST['data']['id_pedido_item'];
        $obj['cantidad_factura'] = $form['cantidad_factura'];
        $item_facturacion = $this->EntregasLogisticaDAO->ItemFacturacionId($_POST['data']['id_pedido_item']);
        if (!empty($item_facturacion)) {
            $cantidad_lista = $item_facturacion[0]->cantidad_factura;
            $obj['cantidad_factura'] = $form['cantidad_factura'] + $cantidad_lista;
            $condicion_entrega = 'id_entrega =' . $item_facturacion[0]->id_entrega;
            $this->EntregasLogisticaDAO->editar($obj, $condicion_entrega);
        } else {
            $this->EntregasLogisticaDAO->insertar($obj);
        }

        if ($data['fecha_compro_item'] == '0000-00-00') {
            $date = date("Y-m-d");
            $data['fecha_compro_item'] = strtotime($date . "+ 2 days");
            $data['fecha_compro_item'] = date("Y-m-d", $data['fecha_compro_item']);
        }

        /* Modficiar el item pedido */

        $datos_item = $this->PedidosItemDAO->ConsultaIdPedidoItem($_POST['data']['id_pedido_item']);
        $pedido_item['cant_bodega'] = $form['cantidad_factura'] + $datos_item[0]->cant_bodega;
        $pedido_item['fecha_compro_item'] = $data['fecha_compro_item'];
        if ($pedido_item['cant_bodega'] >= $datos_item[0]->Cant_solicitada) {
            $pedido_item['id_estado_item_pedido'] = 17;
        } else {
            $pedido_item['id_estado_item_pedido'] = 5;
        }
        $condicion = 'id_pedido_item =' . $_POST['data']['id_pedido_item'];
        $this->PedidosItemDAO->editar($pedido_item, $condicion);

        // VALIDA FECHA COMPROMISO
        $fecha_programada = $this->PedidosDAO->consulta_pedidos('t1.id_pedido =' . $data['id_pedido']);
        if ($fecha_programada[0]->fecha_compromiso == '0000-00-00') {
            // Validar que todos los item tengan fecha de compromiso para colocarla en el pedido
            $fecha_compro = $this->PedidosItemDAO->ValidaFechaCompromiso($data['id_pedido']);
            if ($fecha_compro != '0000-00-00') {
                // Editar el pedido
                $pedido = [
                    'fecha_compromiso' => $fecha_compro
                ];
                $condicion_pedido = 'id_pedido =' . $data['id_pedido'];
                $this->PedidosDAO->editar($pedido, $condicion_pedido);
                // Envio del correo fecha de compromiso
                $persona = $this->PersonaDAO->consultar_personas_id($fecha_programada[0]->id_persona);
                $asesor = $persona[0]->correo;
                // $asesor = 'desarrollo@acobarras.com';
                $cliente = $fecha_programada[0]->email; 
                // $cliente = 'mateorozotorres042002@gmail.com';
                Envio_Correo::correo_confirmacion_fecha_compromiso($fecha_programada, $fecha_compro, $cliente, $asesor);
            }
        }

        // /* Registrar seguimiento op tabla */
        $observacion = '';
        $seguimiento['pedido'] = $_POST['data']['num_pedido'];
        $seguimiento['id_area'] = 2; //LOGISTICA
        $seguimiento['id_actividad'] = 17; //PENDIENTE POR FACTURAR
        $seguimiento['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $seguimiento['id_persona'] = $_SESSION['usuario']->getId_persona();
        $seguimiento['item'] = $_POST['data']['item'];
        $seguimiento['fecha_crea'] = date('Y-m-d');
        $seguimiento['hora_crea'] = date('H:i:s');
        $seguimiento['estado'] = 1;
        $seguimiento['observacion'] = $observacion;
        $respuesta = $this->SeguimientoOpDAO->insertar($seguimiento);
        $respu = [];
        if (!empty($respuesta)) {
            $respu = [
                'status' => '1',
                'msg' => 'Se alisto Correctamente.',

            ];
        } else {
            $respu = [
                'status' => '2',
                'msg' => 'Error al procesar.',

            ];
        }
        echo json_encode($respu);
        return;
    }
}

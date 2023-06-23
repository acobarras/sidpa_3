<?php

namespace MiApp\negocio\controladores\diseno;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\negocio\util\Envio_Correo;

class InfoVariableControlador extends GenericoControlador
{

    private $PedidosItemDAO;
    private $entrada_tecnologiaDAO;
    private $SeguimientoOpDAO;
    private $EntregasLogisticaDAO;
    private $PedidosDAO;
    private $PersonaDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
    }

    public function vista_info_variable()
    {
        parent::cabecera();
        $this->view(
            'diseno/vista_info_variable'
        );
    }

    public function consulta_trabajos_diseno()
    {
        header('Content-Type: application/json');
        $condicion = 't1.id_estado_item_pedido=' . 6;
        $res = $this->PedidosItemDAO->consultar_items_pendientes_compra($condicion);
        foreach ($res as $value) {
            $documento = $value->num_pedido . '-' . $value->item;
            $alisto = $this->entrada_tecnologiaDAO->consultar_salida_item_pedido($documento);
            $value->alista_inv = 2;
            if (!empty($alisto)) {
                if ($alisto[0]->estado_inv == 1) {
                    $value->alista_inv = 1;
                }
            } else {
                if ($value->cant_bodega != 0) {
                    $value->alista_inv = 1;
                }
            }
            $value->ruta = RUTA_ENTREGA[$value->ruta];
        }
        $data['data'] = $res;
        echo json_encode($data);
        return;
    }

    public function pasar_a_produccion()
    {
        header('Content-Type: application/json');
        $data = $_POST['data'];
        /* Modficiar el estado item pedido */
        $pedido_item['id_estado_item_pedido'] = 2;
        $condicion = 'id_pedido_item =' . $data['id_pedido_item'];
        $this->PedidosItemDAO->editar($pedido_item, $condicion);
        // /* Registrar seguimiento op tabla */
        $observacion = 'Faltante Información Variable';
        $seguimiento['pedido'] = $data['num_pedido'];
        $seguimiento['id_area'] = 4; //LOGISTICA
        $seguimiento['id_actividad'] = 62; //PENDIENTE POR FACTURAR
        $seguimiento['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $seguimiento['id_persona'] = $_SESSION['usuario']->getId_persona();
        $seguimiento['item'] = $data['item'];
        $seguimiento['fecha_crea'] = date('Y-m-d');
        $seguimiento['hora_crea'] = date('H:i:s');
        $seguimiento['estado'] = 1;
        $seguimiento['observacion'] = $observacion;
        $this->SeguimientoOpDAO->insertar($seguimiento);
        $respu = [
            'status' => '1',
            'msg' => 'Movimiento ejecutado Correctamente.',

        ];
        echo json_encode($respu);
        return;
    }

    public function reportar_etiq_procesadas()
    {
        header('Content-Type: application/json');
        $data = $_POST['data'];
        $form = Validacion::Decodifica($_POST['form']);
        $envio = $_POST['envio'];
        $cant_reporte = $form['cant_reporte'];
        $item_facturacion = $this->EntregasLogisticaDAO->CantidadFactura($data['id_pedido_item']);
        $cantidad_facturada = $item_facturacion[0]->cantidad_facturada;
        $cantidad_por_facturar = $item_facturacion[0]->cantidad_por_facturar;
        $cantidad = $cantidad_facturada + $cantidad_por_facturar + $cant_reporte;
        $continua = false;
        $id_area = 4;
        $id_actividad = 64;
        if ($cantidad == $data['Cant_solicitada']) {
            $estado_pedido_item = 17;
            $continua = true;
        } else if ($cantidad < $data['Cant_solicitada']) {
            $estado_pedido_item = 6;
            $id_actividad = 63;
            $continua = true;
        } else {
            if ($envio == 2) {
                $estado_pedido_item = 17;
                $continua = true;
            } else {
                $continua = false;
            }
        }

        if ($continua) {
            /* Registrar entregas_logistica tabla */
            $obj['id_usuario'] = $_SESSION['usuario']->getId_usuario();
            $obj['fecha_crea'] = date('Y-m-d');
            $obj['hora_crea'] = date('H:i:s');
            $obj['estado'] = 1;
            $obj['id_pedido_item'] = $data['id_pedido_item'];
            $obj['cantidad_factura'] = $cant_reporte;
            $item_facturacion = $this->EntregasLogisticaDAO->ItemFacturacionId($data['id_pedido_item']);
            if (!empty($item_facturacion)) {
                $cantidad_lista = $item_facturacion[0]->cantidad_factura;
                $obj['cantidad_factura'] = $cant_reporte + $cantidad_lista;
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
            $pedido_item['id_estado_item_pedido'] = $estado_pedido_item;
            $pedido_item['fecha_compro_item'] = $data['fecha_compro_item'];
            $condicion = 'id_pedido_item =' . $data['id_pedido_item'];
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
                    $cliente = $fecha_programada[0]->email;
                    Envio_Correo::correo_confirmacion_fecha_compromiso($fecha_programada, $fecha_compro, $cliente, $asesor);
                }
            }

            // /* Registrar seguimiento op tabla */
            $observacion = '';
            $seguimiento['pedido'] = $data['num_pedido'];
            $seguimiento['id_area'] = $id_area; //LOGISTICA
            $seguimiento['id_actividad'] = $id_actividad; //PENDIENTE POR FACTURAR
            $seguimiento['id_usuario'] = $_SESSION['usuario']->getId_usuario();
            $seguimiento['id_persona'] = $_SESSION['usuario']->getId_persona();
            $seguimiento['item'] = $data['item'];
            $seguimiento['fecha_crea'] = date('Y-m-d');
            $seguimiento['hora_crea'] = date('H:i:s');
            $seguimiento['estado'] = 1;
            $seguimiento['observacion'] = $observacion;
            $this->SeguimientoOpDAO->insertar($seguimiento);

            $respu = [
                'status' => 1,
                'msg' => 'Reporte exitoso.'
            ];
        } else {
            $respu = [
                'status' => -1,
                'msg' => 'La cantidad reportada supera la cantidad total del item.'
            ];
        }
        echo json_encode($respu);
        return;
    }
}

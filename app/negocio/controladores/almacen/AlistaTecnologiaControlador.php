<?php

namespace MiApp\negocio\controladores\almacen;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\ubicacionesDAO;
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
    private $ubicacionesDAO;

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
        $this->ubicacionesDAO = new ubicacionesDAO($cnn);
    }

    public function vista_alista_tecnologia()
    {
        parent::cabecera();
        $this->view(
            'almacen/vista_alistar_tecnologia'
        );
    }

    public function consulta_tec_inventario()
    {
        header('Content-Type: application/json');
        $cantidad_req = $_POST['cantidad'];
        $cons_ubica = $this->entrada_tecnologiaDAO->consultar_seguimiento_producto($_POST['id_producto']);
        $mensaje = '';
        if (empty($cons_ubica) || count($cons_ubica) == 0) {
            $respu = [
                'msg' => 'El producto seleccionado no tiene inventario disponible. Por favor, carga inventario para poder realizar el reporte correspondiente',
                'status' => -1
            ];
        } else {
            $conteo_inv = 0;
            foreach ($cons_ubica as $ubicacion) {
                if ($cantidad_req > 0) {
                    if ($ubicacion->total > 0) {
                        if ($cantidad_req <= $ubicacion->total) {
                            $salida = $cantidad_req;
                        } else {
                            $salida = $ubicacion->total;
                        }
                        $conteo_inv += $ubicacion->total;
                        if ($mensaje == '') {
                            $mensaje = $ubicacion->ubicacion . '(' . $salida . ')';
                        } else {
                            $mensaje = $mensaje . ', ' . $ubicacion->ubicacion . '(' . $salida . ')';
                        }
                    }
                }
            }
            if ($conteo_inv >= floatval($cantidad_req)) {
                $respu = [
                    'msg' => $mensaje,
                    'status' => 1
                ];
            } else {
                $respu = [
                    'msg' => 'La cantidad digitada supera la cantidad del inventario. En inventario hay:' . $conteo_inv,
                    'status' => -1,
                ];
            }
        }
        echo json_encode($respu);
        return;
    }

    public function reportar_facturacion()
    {
        header('Content-Type: application/json'); //convierte a json
        $form = $_POST['form1'];
        $data = $_POST['data'];
        $condicion = $_POST['condi'];
        $cantidad_req = $form['cantidad_factura'];
        // DESCONTAR DEL INVENTARIO
        $cons_ubica = $this->entrada_tecnologiaDAO->consultar_seguimiento_producto($data['id_producto']);
        if (empty($cons_ubica)) {
            $respu = [
                'msg' => 'Lo sentimos, la mercancia ya fue descontada',
                'status' => -1
            ];
            echo json_encode($respu);
            return;
        } else {
            $conteo_inv = 0;
            foreach ($cons_ubica as $ubicacion) {
                if ($cantidad_req > 0) {
                    if ($ubicacion->total > 0) {
                        if ($cantidad_req <= $ubicacion->total) { //se decuenta porque en la ubicacion esta la cantidad requerida    
                            $salida = $cantidad_req;
                            $cantidad_req = $cantidad_req - $cantidad_req;
                        } else { //descuenta de esa ubicacion y sigue buscando
                            $salida = $ubicacion->total;
                            $cantidad_req = $cantidad_req - $ubicacion->total;
                        }
                        $data_descuento[] = [
                            'documento' => $data['num_pedido'] . "-" . $data['item'],
                            'ubicacion' => $ubicacion->ubicacion,
                            'codigo_producto' => $data['codigo'],
                            'id_productos' => $data['id_producto'],
                            'salida' => $salida,
                            'estado_inv' => 1,
                            'fecha_crea' => date('Y-m-d H:i:s'),
                            'fecha_alista' => date('Y-m-d H:i:s'),
                            'id_usuario' => $_SESSION['usuario']->getId_usuario()
                        ];
                    }
                }
            }
            if ($conteo_inv >= floatval($cantidad_req)) {
                if ($condicion == 1) {
                    foreach ($data_descuento as $items) {
                        $this->entrada_tecnologiaDAO->insertar($items);
                    }
                }
            } else {
                $respu = [
                    'msg' => 'Lo sentimos parte de la mercancia ya fue alistada.En inventario quedan:' . $conteo_inv,
                    'status' => -1,
                ];
                echo json_encode($respu);
                return;
            }
        }
        /* Registrar entregas_logistica tabla */
        $obj['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $obj['fecha_crea'] = date('Y-m-d');
        $obj['hora_crea'] = date('H:i:s');
        $obj['estado'] = 1;
        $obj['id_pedido_item'] = $_POST['data']['id_pedido_item'];
        $obj['cantidad_factura'] = $form['cantidad_factura'];
        $obj['ubicacion_material'] = $form['ubicacion_material'];
        $item_facturacion = $this->EntregasLogisticaDAO->ItemFacturacionId($_POST['data']['id_pedido_item']);
        if (!empty($item_facturacion)) {
            $cantidad_lista = $item_facturacion[0]->cantidad_factura;
            $ubicacion_material = $item_facturacion[0]->ubicacion_material;
            if ($ubicacion_material == '') {
                $nueva_ubicacion = $form['ubicacion_material'];
            } else {
                $nueva_ubicacion =  $ubicacion_material . ',' . $form['ubicacion_material'];
            }
            $obj['ubicacion_material'] = $nueva_ubicacion;
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
                $cliente = $fecha_programada[0]->email;
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

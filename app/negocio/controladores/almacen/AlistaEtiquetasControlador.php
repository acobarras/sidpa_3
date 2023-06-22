<?php

namespace MiApp\negocio\controladores\almacen;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\SeguimientoProduccionDAO;
use MiApp\persistencia\dao\TintasDAO;
use MiApp\persistencia\dao\ClaseArticuloDAO;
use MiApp\persistencia\dao\ubicacionesDAO;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\negocio\util\Envio_Correo;
use MiApp\negocio\util\Validacion;

class AlistaEtiquetasControlador extends GenericoControlador
{
    private $PedidosItemDAO;
    private $entrada_tecnologiaDAO;
    private $EntregasLogisticaDAO;
    private $ItemProducirDAO;
    private $SeguimientoOpDAO;
    private $SeguimientoProduccionDAO;
    private $TintasDAO;
    private $ClaseArticuloDAO;
    private $ubicacionesDAO;
    private $PedidosDAO;
    private $PersonaDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->SeguimientoProduccionDAO = new SeguimientoProduccionDAO($cnn);
        $this->TintasDAO = new TintasDAO($cnn);
        $this->ClaseArticuloDAO = new ClaseArticuloDAO($cnn);
        $this->ubicacionesDAO = new ubicacionesDAO($cnn);
        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
    }

    public function vista_alistar_etiquetas()
    {
        parent::cabecera();

        $this->view(
            'almacen/vista_alistar_etiquetas'
        );
    }
    public function consultar_items_op()
    {
        header('Content-Type: application/json'); //convierte a json

        $condicion = 't1.id_estado_item_pedido=' . 16;
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
        }
        $tb_etiq = [];
        $tb_tecno = [];

        foreach ($res as  $value) {
            $value->ruta = RUTA_ENTREGA[$value->ruta];
            if ($value->id_clase_articulo == 2) {
                $tb_etiq[] = $value;
            }
            if ($value->id_clase_articulo == 3) {

                $tb_tecno[] = $value;
            }
        }

        if ($_REQUEST['dato'] == 1) {
            $data['data'] = $tb_etiq;
        } else {
            $data['data'] = $tb_tecno;
        }
        echo json_encode($data);
    }

    public function reportar_facturacion_etiq()
    {
        header('Content-Type: application/json'); //convierte a json
        $form = Validacion::Decodifica($_POST['form1']);
        $data = $_POST['data'];
        if ($data['fecha_compro_item'] == '0000-00-00') {
            $date = date("Y-m-d");
            $data['fecha_compro_item'] = strtotime($date . "+ 2 days");
            $data['fecha_compro_item'] = date("Y-m-d", $data['fecha_compro_item']);
        }
        $codigo = $_POST['data']['codigo'];
        //si es info variable saca valores para validar la informacion  variable
        $caracter = "-";
        $posicion_coincidencia = strpos($codigo, $caracter);
        $tinta_codigo = substr($codigo, ($posicion_coincidencia + 6), 2);
        $tintas = $this->TintasDAO->consultar_tintas_valiables();
        foreach ($tintas as $tinta) {
            if ($tinta_codigo == $tinta->numeros) {
                // codigo de producto es valiable entonces va a estado"6" 
                $this->reporte_impresion_variable($form, $data);
                return;
            }
        }
        if ($form['tipo_envio'] == 1) { //envio reporte completo
            $this->reporte_factu_etiq_completo($form, $data);
        } else {
            $this->reporte_factu_etiq_incompleto($form, $data);
        }
    }
    public function reporte_factu_etiq_completo($form, $data)
    {
        /* Registrar entregas_logistica tabla */
        $obj['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $obj['fecha_crea'] = date('Y-m-d');
        $obj['hora_crea'] = date('H:i:s');
        $obj['estado'] = 1;
        $obj['id_pedido_item'] = $data['id_pedido_item'];
        $obj['cantidad_factura'] = $form['cantidad_factura'];
        $item_facturacion = $this->EntregasLogisticaDAO->ItemFacturacionId($data['id_pedido_item']);
        if (!empty($item_facturacion)) {
            $cantidad_lista = $item_facturacion[0]->cantidad_factura;
            $obj['cantidad_factura'] = $form['cantidad_factura'] + $cantidad_lista;
            $condicion_entrega = 'id_entrega =' . $item_facturacion[0]->id_entrega;
            $this->EntregasLogisticaDAO->editar($obj, $condicion_entrega);
        } else {
            $this->EntregasLogisticaDAO->insertar($obj);
        }

        /* Modficiar el item pedido */
        $datos_item = $this->PedidosItemDAO->ConsultaIdPedidoItem($data['id_pedido_item']);
        $pedido_item['id_estado_item_pedido'] = 17;
        $pedido_item['cant_bodega'] = $form['cantidad_factura'] + $datos_item[0]->cant_bodega;
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
                // $asesor = 'desarrollo@acobarras.com';
                $cliente = $fecha_programada[0]->email; 
                // $cliente = 'mateorozotorres042002@gmail.com';
                Envio_Correo::correo_confirmacion_fecha_compromiso($fecha_programada, $fecha_compro, $cliente, $asesor);
            }
        }

        // /* Registrar seguimiento op tabla */
        $observacion = '';
        $seguimiento['pedido'] = $data['num_pedido'];
        $seguimiento['id_area'] = 2; //LOGISTICA
        $seguimiento['id_actividad'] = 17; //PENDIENTE POR FACTURAR
        $seguimiento['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $seguimiento['id_persona'] = $_SESSION['usuario']->getId_persona();
        $seguimiento['item'] = $data['item'];
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

    public function reporte_factu_etiq_incompleto($form, $data)
    {
        /* Registrar entregas_logistica tabla */
        $obj['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $obj['fecha_crea'] = date('Y-m-d');
        $obj['hora_crea'] = date('H:i:s');
        $obj['estado'] = 1;
        $obj['id_pedido_item'] = $data['id_pedido_item'];
        $obj['cantidad_factura'] = $form['cantidad_factura'];
        $item_facturacion = $this->EntregasLogisticaDAO->ItemFacturacionId($data['id_pedido_item']);
        if (!empty($item_facturacion)) {
            $cantidad_lista = $item_facturacion[0]->cantidad_factura;
            $obj['cantidad_factura'] = $form['cantidad_factura'] + $cantidad_lista;
            $condicion_entrega = 'id_entrega =' . $item_facturacion[0]->id_entrega;
            $this->EntregasLogisticaDAO->editar($obj, $condicion_entrega);
        } else {
            $this->EntregasLogisticaDAO->insertar($obj);
        }

        /* Modficiar el item pedido */
        $datos_item = $this->PedidosItemDAO->ConsultaIdPedidoItem($data['id_pedido_item']);
        $pedido_item['id_estado_item_pedido'] = 10;
        $pedido_item['cant_bodega'] = $form['cantidad_factura'] + $datos_item[0]->cant_bodega;
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
                // $asesor = $persona[0]->correo;
                $asesor = 'desarrollo@acobarras.com';
                // $cliente = $fecha_programada[0]->email; 
                $cliente = 'mateorozotorres042002@gmail.com';
                Envio_Correo::correo_confirmacion_fecha_compromiso($fecha_programada, $fecha_compro, $cliente, $asesor);
            }
        }

        /* Modficiar el item producir */
        $item_producir['estado_item_producir'] = 10;
        $condicion = 'num_produccion =' . $data['n_produccion'];
        $this->ItemProducirDAO->editar($item_producir, $condicion);

        // /* Registrar seguimiento op tabla */
        $observacion = '';
        $seguimiento['pedido'] = $data['num_pedido'];
        $seguimiento['id_area'] = 2; //LOGISTICA
        $seguimiento['id_actividad'] = 17; //PENDIENTE POR FACTURAR
        $seguimiento['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $seguimiento['id_persona'] = $_SESSION['usuario']->getId_persona();
        $seguimiento['item'] = $data['item'];
        $seguimiento['fecha_crea'] = date('Y-m-d');
        $seguimiento['hora_crea'] = date('H:i:s');
        $seguimiento['estado'] = 1;
        $seguimiento['observacion'] = $observacion;
        $this->SeguimientoOpDAO->insertar($seguimiento);

        // /* Registrar seguimiento produccion tabla */
        $seguimiento_procuccion['num_produccion'] = $data['n_produccion'];
        $seguimiento_procuccion['id_persona'] = $_SESSION['usuario']->getId_persona();
        $seguimiento_procuccion['id_area'] = 1;
        $seguimiento_procuccion['id_actividad'] = 56;
        $seguimiento_procuccion['observacion_op'] = 'PRODUCCIÓN INCOMPLETA';
        $seguimiento_procuccion['fecha_crea'] = date('Y-m-d');
        $seguimiento_procuccion['hora_crea'] = date('H:i:s');
        $respuesta = $this->SeguimientoProduccionDAO->insertar($seguimiento_procuccion);
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
    public function reporte_impresion_variable($form, $data)
    {
        /* Modficiar el item pedido */
        $datos_item = $this->PedidosItemDAO->ConsultaIdPedidoItem($data['id_pedido_item']);
        $pedido_item['id_estado_item_pedido'] = 6;
        $pedido_item['cant_bodega'] = $form['cantidad_factura'] + $datos_item[0]->cant_bodega;
        $condicion = 'id_pedido_item =' . $data['id_pedido_item'];
        $this->PedidosItemDAO->editar($pedido_item, $condicion);

        // /* Registrar seguimiento op tabla */
        $observacion = '';
        $seguimiento['pedido'] = $data['num_pedido'];
        $seguimiento['id_area'] = 2; //LOGISTICA
        $seguimiento['id_actividad'] = 61; //PENDIENTE POR FACTURAR
        $seguimiento['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $seguimiento['id_persona'] = $_SESSION['usuario']->getId_persona();
        $seguimiento['item'] = $data['item'];
        $seguimiento['fecha_crea'] = date('Y-m-d');
        $seguimiento['hora_crea'] = date('H:i:s');
        $seguimiento['estado'] = 1;
        $seguimiento['observacion'] = $observacion;
        $respuesta = $this->SeguimientoOpDAO->insertar($seguimiento);
        $respu = [
            'status' => '1',
            'msg' => 'Se alisto y envio a Información variable.',

        ];
        echo json_encode($respu);
        return;
    }

    public function vista_ubicaciones()
    {
        $this->view(
            'almacen/vista_ubicaciones',
            [
                'tipo_producto' => $this->ClaseArticuloDAO->consulta_clase_articulo()
            ]
        );
    }

    public function crear_ubicacion()
    {
        header('Content-Type: application/json'); //convierte a json
        $crea_ubica = $_POST;
        $crea_ubica['carga_inv'] = 1;
        $crea_ubica['estado'] = 1;
        $crea_ubica['fecha_crea'] = date('Y-m-d H:i:s');
        $respu = $this->ubicacionesDAO->insertar($crea_ubica);
        echo json_encode($respu);
        return;
    }
}

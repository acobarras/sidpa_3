<?php

namespace MiApp\negocio\controladores\Consultas;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\clientes_proveedorDAO;

use MiApp\persistencia\generico\GenericoDAO;

use MiApp\negocio\util\Validacion;


class ConsultasDetalladaControlador extends GenericoControlador
{
    private $PedidosItemDAO;
    private $ItemProducirDAO;
    private $EntregasLogisticaDAO;
    private $clientes_proveedorDAO;

    public function __construct(&$cnn)
    {
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        parent::__construct($cnn);
    }

    public function vista_consulta_detallada()
    {
        parent::cabecera();
        
        if ($_SESSION['usuario']->getId_roll() == 4) {
            $cliente = $this->clientes_proveedorDAO->consultar_clientes_asesor($_SESSION['usuario']->getId_persona());
        }else{
            $cliente = $this->clientes_proveedorDAO->consultar_clientes();
        }
        
        $this->view(
            'consultas/vista_consulta_detallada',
            [                
                "clientes" => $cliente
            ]
        );
    }
    public function consulta_detallada_fecha()
    {
        header('Content-Type: application/json');
        $datos = $_POST['form'];
        $datos = Validacion::Decodifica($_POST['form']);
        $id_persona = $_SESSION['usuario']->getId_persona();
        $id_rol = $_SESSION['usuario']->getId_roll();
        if ($id_rol == 4) {
            $condicion = 'AND t2.id_persona ='.$id_persona;
        }else{
            $condicion = '';
        }
        if ($datos['fecha'] == 1) {
            $fecha_consulta = 'fecha_crea_p';
        } else {
            $fecha_consulta = 'fecha_compromiso';
        }
        $respu = $this->PedidosItemDAO->ConsultaRangoFecha($datos['desde'], $datos['hasta'], $fecha_consulta, $condicion);
        foreach ($respu as $value) {
            if ($value->n_produccion == 0) {
                $value->material = '';
                $value->material_solicitado = '';
                $value->ancho_op = '';
                $value->ancho_confirmado = '';
                $value->fecha_proveedor = '';
            } else {
                $datos_op = $this->ItemProducirDAO->consultar_item_producir_num($value->n_produccion);
                $value->material = $datos_op[0]->material;
                $value->material_solicitado = $datos_op[0]->material_solicitado;
                $value->ancho_op = $datos_op[0]->ancho_op;
                $value->ancho_confirmado = $datos_op[0]->ancho_confirmado;
                $value->fecha_proveedor = $datos_op[0]->fecha_proveedor;
            }

            $value->forma_pago = FORMA_PAGO[$value->forma_pago];
            $entregas = $this->EntregasLogisticaDAO->ConsultaIdPedidoItem($value->id_pedido_item);
            if (empty($entregas)) {
                $value->numero_factura = '';
                $value->estado_entrega = '';
            } else {
                $value->numero_factura = '';
                $value->estado_entrega = '';
                foreach ($entregas as $entrega) {
                    $num_documento = $entrega->num_factura;
                    if ($entrega->num_factura == 0) {
                        $num_documento = $entrega->num_remision;
                    }
                    $value->numero_factura .= $entrega->tipo_documento . " " . $num_documento . "/";
                    $value->estado_entrega .= $entrega->nombre_estado . "/";
                }
            }
        }
        $retorno['data'] = $respu;
        echo json_encode($retorno);
        return;
    }
    public function consulta_detallada_codigo()
    {
        header('Content-Type: application/json');
        $datos = $_POST['form'];
        $datos = Validacion::Decodifica($datos);
        $id_persona = $_SESSION['usuario']->getId_persona();
        $id_rol = $_SESSION['usuario']->getId_roll();
        $texto = $datos['codigo_producto'];
        if ($id_rol == 4) {
            $condicion = "t1.codigo = '$texto' AND t2.id_persona =".$id_persona;
        }else{
            $condicion = "t1.codigo = '$texto'";
        }
        $respu = $this->PedidosItemDAO->ConsultaDetallePedido($condicion);
        foreach ($respu as $value) {
            if ($value->n_produccion == 0) {
                $value->material = '';
                $value->material_solicitado = '';
                $value->ancho_op = '';
                $value->ancho_confirmado = '';
                $value->fecha_proveedor = '';
            } else {
                $datos_op = $this->ItemProducirDAO->consultar_item_producir_num($value->n_produccion);
                $value->material = $datos_op[0]->material;
                $value->material_solicitado = $datos_op[0]->material_solicitado;
                $value->ancho_op = $datos_op[0]->ancho_op;
                $value->ancho_confirmado = $datos_op[0]->ancho_confirmado;
                $value->fecha_proveedor = $datos_op[0]->fecha_proveedor;
            }

            $value->forma_pago = FORMA_PAGO[$value->forma_pago];
            $entregas = $this->EntregasLogisticaDAO->ConsultaIdPedidoItem($value->id_pedido_item);
            if (empty($entregas)) {
                $value->numero_factura = '';
                $value->estado_entrega = '';
            } else {
                $value->numero_factura = '';
                $value->estado_entrega = '';
                foreach ($entregas as $entrega) {
                    $num_documento = $entrega->num_factura;
                    if ($entrega->num_factura == 0) {
                        $num_documento = $entrega->num_remision;
                    }
                    $value->numero_factura .= $entrega->tipo_documento . " " . $num_documento . "/";
                    $value->estado_entrega .= $entrega->nombre_estado . "/";
                }
            }
        }
        $retorno['data'] = $respu;
        echo json_encode($retorno);
        return;
    }

    public function consulta_detallada_numero_pedido()
    {
        header('Content-Type: application/json');
        $datos = $_POST['form'];
        $datos = Validacion::Decodifica($datos);
        $id_persona = $_SESSION['usuario']->getId_persona();
        $id_rol = $_SESSION['usuario']->getId_roll();
        if ($id_rol == 4) {
            $condicion = "t2.num_pedido = " . $datos['numero_pedido'] . " AND t2.id_persona = ".$id_persona;
        }else{
            $condicion = "t2.num_pedido = " . $datos['numero_pedido'];
        }
        $respu = $this->PedidosItemDAO->ConsultaDetallePedido($condicion);
        foreach ($respu as $value) {
            if ($value->n_produccion == 0) {
                $value->material = '';
                $value->material_solicitado = '';
                $value->ancho_op = '';
                $value->ancho_confirmado = '';
                $value->fecha_proveedor = '';
            } else {
                $datos_op = $this->ItemProducirDAO->consultar_item_producir_num($value->n_produccion);
                $value->material = $datos_op[0]->material;
                $value->material_solicitado = $datos_op[0]->material_solicitado;
                $value->ancho_op = $datos_op[0]->ancho_op;
                $value->ancho_confirmado = $datos_op[0]->ancho_confirmado;
                $value->fecha_proveedor = $datos_op[0]->fecha_proveedor;
            }

            $value->forma_pago = FORMA_PAGO[$value->forma_pago];
            $entregas = $this->EntregasLogisticaDAO->ConsultaIdPedidoItem($value->id_pedido_item);

            if (empty($entregas)) {
                $value->numero_factura = '';
                $value->estado_entrega = '';
            } else {
                $value->numero_factura = '';
                $value->estado_entrega = '';
                foreach ($entregas as $entrega) {
                    $num_documento = $entrega->num_factura;
                    if ($entrega->num_factura == 0) {
                        $num_documento = $entrega->num_remision;
                    }
                    $value->numero_factura .= $entrega->tipo_documento . " " . $num_documento . "/";
                    $value->estado_entrega .= $entrega->nombre_estado . "/";
                }
            }
        }
        $retorno['data'] = $respu;
        echo json_encode($retorno);
        return;
    }

    public function consulta_detallada_cliente()
    {
        header('Content-Type: application/json');
        $datos = $_POST['form'];
        $texto= implode(', ', $datos);
        $condicion = "t2.id_cli_prov in($texto)";
        $respu = $this->PedidosItemDAO->ConsultaDetallePedido($condicion);
        foreach ($respu as $value) {
            if ($value->n_produccion == 0) {
                $value->material = '';
                $value->material_solicitado = '';
                $value->ancho_op = '';
                $value->ancho_confirmado = '';
                $value->fecha_proveedor = '';
            } else {
                $datos_op = $this->ItemProducirDAO->consultar_item_producir_num($value->n_produccion);
                $value->material = $datos_op[0]->material;
                $value->material_solicitado = $datos_op[0]->material_solicitado;
                $value->ancho_op = $datos_op[0]->ancho_op;
                $value->ancho_confirmado = $datos_op[0]->ancho_confirmado;
                $value->fecha_proveedor = $datos_op[0]->fecha_proveedor;
            }

            $value->forma_pago = FORMA_PAGO[$value->forma_pago];
            $entregas = $this->EntregasLogisticaDAO->ConsultaIdPedidoItem($value->id_pedido_item);

            if (empty($entregas)) {
                $value->numero_factura = '';
                $value->estado_entrega = '';
            } else {
                $value->numero_factura = '';
                $value->estado_entrega = '';
                foreach ($entregas as $entrega) {
                    $num_documento = $entrega->num_factura;
                    if ($entrega->num_factura == 0) {
                        $num_documento = $entrega->num_remision;
                    }
                    $value->numero_factura .= $entrega->tipo_documento . " " . $num_documento . "/";
                    $value->estado_entrega .= $entrega->nombre_estado . "/";
                }
            }
        }
        $retorno['data'] = $respu;
        echo json_encode($retorno);
        return;
    }
}

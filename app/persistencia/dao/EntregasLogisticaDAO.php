<?php

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;
use MiApp\negocio\util\insertar_generico;
use PDO;

/**
 * Description of entregas_logisticaDAO
 *
 * @author erios
 */
class EntregasLogisticaDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'entregas_logistica');
    }

    public function consultar_entregas_logistica_id($id)
    {
        $sql = "SELECT * FROM entregas_logistica WHERE id_pedido_item = " . $id;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ConsultarPedidosFacturar()
    {
        $sql = "SELECT t3.fecha_compromiso, t5.nombre_empresa, t6.nombres, t6.apellidos, t3.parcial, t5.pertenece, 
        t5.nit, t3.orden_compra, t5.forma_pago, t3.num_pedido, t3.total_etiq, t3.total_tec, t4.nombre_estado, 
        t2.id_pedido, t3.fecha_crea_p, t3.hora_crea, t8.direccion AS direccion_entrega, t9.direccion AS direccion_radicacion,
        t8.contacto, t8.cargo, t8.email, t8.celular, t8.telefono, t8.horario, t3.id_persona, t3.fecha_cierre, t3.porcentaje,
        t3.difer_mas, t3.difer_menos, t3.difer_ext, t3.observaciones, t3.iva
            FROM entregas_logistica t1 
            INNER JOIN pedidos_item t2 ON t1.id_pedido_item = t2.id_pedido_item 
            INNER JOIN pedidos t3 ON t2.id_pedido = t3.id_pedido
            INNER JOIN estado_entregas t4 ON t1.estado = t4.id_estado_entrega 
            INNER JOIN cliente_proveedor t5 ON t3.id_cli_prov = t5.id_cli_prov 
            INNER JOIN persona t6 ON t3.id_persona = t6.id_persona
            INNER JOIN direccion t8 ON t3.id_dire_entre = t8.id_direccion
            INNER JOIN direccion t9 ON t3.id_dire_radic = t9.id_direccion
            WHERE t1.estado = 1
            GROUP BY t3.num_pedido";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function CantidadItemPedido($id_pedido, $pocicion = '')
    {
        $sql = "SELECT id_pedido, SUM(q_item) AS q_item, SUM(q_reporte) AS q_reporte 
            FROM 
                ( SELECT id_pedido, COUNT(*) AS q_item, 0 AS q_reporte 
                    FROM pedidos_item 
                    WHERE id_pedido = $id_pedido 
            UNION 
                SELECT id_pedido, 0 AS q_item, COUNT(*) AS q_reporte 
                    FROM pedidos_item t1 
                    WHERE EXISTS 
                        (SELECT NULL FROM entregas_logistica t3 WHERE t3.id_pedido_item = t1.id_pedido_item) 
                    AND t1.id_pedido = $id_pedido 
                ) p";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado[0];
    }

    public function CantidadFactura($id_pedido_item)
    {
        $sql = "SELECT SUM(cantidad_facturada) AS cantidad_facturada, SUM(cantidad_por_facturar) AS cantidad_por_facturar 
            FROM (
                SELECT SUM(cantidad_factura) AS cantidad_facturada, 0 AS cantidad_por_facturar 
                    FROM entregas_logistica 
                    WHERE id_pedido_item = $id_pedido_item AND estado != 1
            UNION
            SELECT 0 AS cantidad_facturada, SUM(cantidad_factura) AS cantidad_por_facturar 
                FROM entregas_logistica 
                WHERE id_pedido_item = $id_pedido_item AND estado = 1 
            ) p";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ItemFacturacionId($id)
    {
        $sql = "SELECT * FROM entregas_logistica WHERE id_pedido_item = '$id' AND estado = 1";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_mis_emtregas($id_persona = '')
    {
        $consulta = "AND t1.entre_por = $id_persona";
        if ($_SESSION['usuario']->getId_roll() == 1 || $_SESSION['usuario']->getId_roll() == 10) {
            $consulta = '';
        }
        $sql = "SELECT t1.fecha_factura, t1.fecha_cargue, t1.estado, t1.entre_por, t1.tipo_documento, t2.num_factura, t2.num_remision, 
            t2.tipo_documento AS id_tipo_documento, t4.fecha_compromiso, t4.num_pedido, t4.parcial, t4.id_dire_entre, t4.id_dire_radic,
            t5.forma_pago, t5.nombre_empresa, t6.nombre_estado
            FROM entregas_logistica t1 
            INNER JOIN control_facturas t2 ON t1.id_factura = t2.id_control_factura
            INNER JOIN pedidos_item t3 ON t1.id_pedido_item = t3.id_pedido_item
            INNER JOIN pedidos t4 ON t3.id_pedido = t4.id_pedido
            INNER JOIN cliente_proveedor t5 ON t4.id_cli_prov = t5.id_cli_prov
            INNER JOIN estado_entregas t6 ON t1.estado = t6.id_estado_entrega
            WHERE t1.estado IN(3) $consulta GROUP BY t2.num_factura, t2.num_remision";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ItemFactura($id_factura)
    {
        $sql = "SELECT t1.*, t2.item, t3.num_pedido
            FROM entregas_logistica t1 
            INNER JOIN pedidos_item t2 ON t1.id_pedido_item = t2.id_pedido_item 
            INNER JOIN pedidos t3 ON t2.id_pedido = t3.id_pedido
            WHERE t1.id_factura ='$id_factura'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ModificacionDocumento()
    {
        $sql = "SELECT t1.tipo_documento, t1.entre_por, t1.id_factura, t2.*, t3.fecha_compromiso, t3.num_pedido, 
                t3.parcial, t3.id_dire_entre, t3.id_dire_radic, t4.forma_pago, t4.nombre_empresa, 
                t5.nombre_estado, t6.num_factura, t6.num_remision, t6.id_control_factura, t6.num_lista_empaque, 
                t6.tipo_documento AS id_tipo_documento
                FROM entregas_logistica t1 
                INNER JOIN pedidos_item t2 ON t1.id_pedido_item = t2.id_pedido_item 
                INNER JOIN pedidos t3 ON t2.id_pedido = t3.id_pedido 
                INNER JOIN cliente_proveedor t4 ON t3.id_cli_prov = t4.id_cli_prov 
                INNER JOIN estado_entregas t5 ON t1.estado = t5.id_estado_entrega 
                INNER JOIN control_facturas t6 ON t1.id_factura = t6.id_control_factura
                WHERE t1.estado IN (2,4)  
                GROUP BY t6.num_factura, t6.num_remision";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function valida_edicion_item($id_pedido_item)
    {
        $sql = "SELECT t1.*, t2.id_pedido FROM entregas_logistica t1 
            INNER JOIN pedidos_item t2 ON t1.id_pedido_item = t2.id_pedido_item
            WHERE t1.id_pedido_item = $id_pedido_item AND t1.estado = 1";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function ConsultaIdPedidoItem($id_pedido_item)
    {
        $sql = "SELECT t1.id_entrega, t1.tipo_documento, t2.nombre_estado, t3.num_factura, t3.num_remision,
            t3.num_lista_empaque
            FROM entregas_logistica t1 
            INNER JOIN estado_entregas t2 ON t1.estado = t2.id_estado_entrega
            INNER JOIN control_facturas t3 ON t1.id_factura = t3.id_control_factura
            WHERE id_pedido_item = $id_pedido_item";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function informe_colombia($param)
    {
        $sql = "SELECT t1.*,t3.orden_compra,t3.num_pedido, t5.descripcion_productos, t5.codigo_producto, SUM(t1.cantidad_factura) AS total_factura, MAX(t2.v_unidad) AS precio_venta FROM entregas_logistica t1 
            INNER JOIN pedidos_item t2 ON t1.id_pedido_item = t2.id_pedido_item 
            INNER JOIN pedidos t3 ON t2.id_pedido = t3.id_pedido 
            INNER JOIN cliente_proveedor t4 ON t3.id_cli_prov = t4.id_cli_prov 
            INNER JOIN productos t5 ON t2.codigo = t5.codigo_producto 
            INNER JOIN control_facturas t6 ON t1.id_factura = t6.id_control_factura 
            WHERE t4.pertenece = 2 AND t1.fecha_factura = '$param' AND t6.num_factura != 0 GROUP BY t5.codigo_producto";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

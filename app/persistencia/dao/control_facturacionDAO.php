<?php

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;
use PDO;

class control_facturacionDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'control_facturas');
    }

    public function ConsultarNumFactura($num_factura)
    {
        $sql = "SELECT t1.num_factura,t1.tipo_documento,t2.cantidad_factura,
                    t2.fecha_factura,t3.*,t4.num_pedido,t4.iva,t8.nombre_estado,t2.id_factura,
                    t5.id_cli_prov,t5.nombre_empresa,t5.nit,t5.forma_pago,t5.dias_dados,t5.id_usuarios_asesor,t5.pertenece,t7.descripcion_productos,t9.id_tipo_articulo,t10.id_persona  FROM control_facturas t1 
                    INNER JOIN entregas_logistica t2 ON t2.id_factura=t1.id_control_factura 
                    INNER JOIN pedidos_item t3 ON t2.id_pedido_item = t3.id_pedido_item 
                    INNER JOIN pedidos t4 ON t3.id_pedido = t4.id_pedido
                    INNER JOIN cliente_proveedor t5 ON t4.id_cli_prov = t5.id_cli_prov 
                    INNER JOIN cliente_producto t6 ON t3.id_clien_produc = t6.id_clien_produc 
                    INNER JOIN productos t7 ON t7.id_productos = t6.id_producto
                    INNER JOIN estado_entregas t8 ON t1.estado = t8.id_estado_entrega
                    INNER JOIN tipo_articulo t9 ON t7.id_tipo_articulo = t9.id_tipo_articulo
                    INNER JOIN usuarios t10 ON t4.id_usuario = t10.id_usuario
                    WHERE t1.num_factura = $num_factura AND (t1.tipo_documento=8 OR t1.tipo_documento=9)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    public function consulta_lista_empaque($num_lista_empaque)
    {
        $sql = "SELECT t1.*, t2.fecha_factura, t2.id_entrega, t2.cantidad_factura, t2.tipo_documento AS tipo_documento_letra, t2.estado AS estado_entrega_logistica, t2.fact_por, t3.*, t4.num_pedido, t4.parcial, t4.porcentaje, t4.difer_mas, t4.difer_menos, t4.difer_ext, t4.orden_compra, t4.iva, t4.observaciones, t4.id_dire_entre, t5.nit, t5.dig_verificacion, t5.nombre_empresa, t5.forma_pago, t5.dias_dados, t5.id_cli_prov
            FROM control_facturas t1 
            INNER JOIN entregas_logistica t2 ON t1.id_control_factura = t2.id_factura
            INNER JOIN pedidos_item t3 ON t2.id_pedido_item = t3.id_pedido_item
            INNER JOIN pedidos t4 ON t3.id_pedido = t4.id_pedido
            INNER JOIN cliente_proveedor t5 ON t4.id_cli_prov = t5.id_cli_prov
            WHERE t1.num_lista_empaque = $num_lista_empaque";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ConsultaEspecifica($condicion)
    {
        $sql = "SELECT * FROM control_facturas $condicion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_documento_reporte_guia($num_documento)
    {
        $sql = "SELECT t1.*, t2.cantidad_factura, t2.tipo_documento AS letra_tipo_documento, t5.nombre_empresa, t4.num_pedido, t3.item, t6.descripcion_productos 
            FROM control_facturas t1 
            INNER JOIN entregas_logistica t2 ON t1.id_control_factura = t2.id_factura 
            INNER JOIN pedidos_item t3 ON t2.id_pedido_item = t3.id_pedido_item 
            INNER JOIN pedidos t4 ON t3.id_pedido = t4.id_pedido 
            INNER JOIN cliente_proveedor t5 ON t4.id_cli_prov = t5.id_cli_prov 
            INNER JOIN productos t6 ON t3.codigo = t6.codigo_producto 
            WHERE t1.num_lista_empaque = '$num_documento'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_documento($num_documento, $consulta_por)
    {
        $sql = "SELECT t1.id_control_factura, t1.num_lista_empaque,t1.num_remision, t1.num_factura, t2.cantidad_factura, 
            t2.tipo_documento, t3.codigo, t5.descripcion_productos 
            FROM control_facturas t1 
            INNER JOIN entregas_logistica t2 ON t2.id_factura=t1.id_control_factura 
            INNER JOIN pedidos_item t3 ON t2.id_pedido_item = t3.id_pedido_item 
            INNER JOIN cliente_producto t4 ON t3.id_clien_produc = t4.id_clien_produc 
            INNER JOIN productos t5 ON t5.id_productos = t4.id_producto
            INNER JOIN tipo_articulo t6 ON t5.id_tipo_articulo = t6.id_tipo_articulo
            WHERE t1.$consulta_por = $num_documento";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_pendientes_por_facturar()
    {
        $sql = "SELECT t1.*, t5.nombre_empresa, t4.num_pedido, t4.id_cli_prov, t2.tipo_documento, t6.id_persona, t6.nombres, t6.apellidos
        FROM control_facturas t1 
        INNER JOIN entregas_logistica t2 ON t1.id_control_factura = t2.id_factura
        INNER JOIN pedidos_item t3 ON t2.id_pedido_item = t3.id_pedido_item
        INNER JOIN pedidos t4 ON t3.id_pedido = t4.id_pedido
        INNER JOIN cliente_proveedor t5 ON t4.id_cli_prov = t5.id_cli_prov
        INNER JOIN persona t6 ON t4.id_persona = t6.id_persona
        WHERE t1.num_factura = 0 AND t1.estado = 1 GROUP BY t1.id_control_factura";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    // public function ConsultaPendienteReporte()
    // {
    //     $sql = "SELECT t1.tipo_documento, t1.num_factura, t1.num_lista_empaque, t5.nombre_empresa,
    //         t6.nombre_estado, t2.fecha_factura
    //         FROM control_facturas t1 
    //         INNER JOIN entregas_logistica t2 ON t1.id_control_factura = t2.id_factura 
    //         INNER JOIN pedidos_item t3 ON t2.id_pedido_item = t3.id_pedido_item 
    //         INNER JOIN pedidos t4 ON t3.id_pedido = t4.id_pedido 
    //         INNER JOIN cliente_proveedor t5 ON t4.id_cli_prov = t5.id_cli_prov 
    //         INNER JOIN estado_entregas t6 ON t2.estado = t6.id_estado_entrega
    //         WHERE t1.estado != 3 AND t1.tipo_documento IN (8,9) GROUP BY t1.num_factura";

    //     $sentencia = $this->cnn->prepare($sql);
    //     $sentencia->execute();
    //     $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
    //     return $resultado;
    // }

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;
use PDO;

/**
 * Description of pedidos_itemDAO
 *
 * @author erios
 */
class PedidosItemDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'pedidos_item');
    }

    public function ConsultaIdPedido($id_pedido)
    {
        $sql = "SELECT t1.*, t2.cav_montaje, t2.descripcion_productos, t2.tamano, t3.ficha_tecnica, t3.id_material, t3.id_clien_produc, t4.nombre_r_embobinado, 
        t5.nombre_core, t6.nombre_estado_item,t8.nombre_empresa,t8.logo_etiqueta,t7.id_dire_entre,t7.id_cli_prov,t9.ruta, t10.id_clase_articulo
            FROM pedidos_item t1
            INNER JOIN productos t2 ON t1.codigo = t2.codigo_producto
            INNER JOIN cliente_producto t3 ON t1.id_clien_produc = t3.id_clien_produc
            INNER JOIN ruta_embobinado t4 ON t1.ruta_embobinado = t4.id_ruta_embobinado
            INNER JOIN core t5 ON t1.core = t5.id_core
            INNER JOIN estado_item_pedido t6 ON t1.id_estado_item_pedido = t6.id_estado_item_pedido
            INNER JOIN pedidos t7 ON t1.id_pedido = t7.id_pedido 
            INNER JOIN cliente_proveedor t8 ON t7.id_cli_prov=t8.id_cli_prov
            INNER JOIN direccion t9 ON t7.id_dire_entre = t9.id_direccion
            INNER JOIN tipo_articulo t10 ON t2.id_tipo_articulo = t10.id_tipo_articulo
            WHERE t1.id_pedido = '$id_pedido' ORDER BY t1.item ASC";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function ValidaFechaCompromiso($id_pedido)
    {
        $sql = "SELECT * FROM pedidos_item WHERE id_pedido = $id_pedido";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        $fecha_compro = '0000-00-00';
        foreach ($resultado as $value) {
            if ($value->fecha_compro_item == '0000-00-00') {
                $fecha_compro = '0000-00-00';
                break;
            } else {
                if ($fecha_compro < $value->fecha_compro_item) {
                    $fecha_compro = $value->fecha_compro_item;
                }
            }
        }
        return $fecha_compro;
    }


    public function ConsultaNumeroPedidoOp($n_produccion, $id_maquina = '')
    {
        if ($id_maquina == '') {
            $consulta = "t1.n_produccion = '$n_produccion' ORDER BY t1.item ASC";
        } else {
            $consulta = "t1.n_produccion = '$n_produccion' AND t1.id_maqui_embo = '$id_maquina' ORDER BY t1.item ASC";
        }
        $sql = "SELECT t1.id_pedido_item, t1.item, t1.Cant_solicitada, t1.cant_op, t1.n_produccion, t1.codigo, t1.cant_x, t1.total, t1.trm, 
            t1.moneda, t1.v_unidad, t1.cant_bodega, t1.id_estado_item_pedido, t1.id_pedido, t1.fecha_compro_item, t1.id_maqui_embo, 
            t2.fecha_crea_p, t2.hora_crea, t2.fecha_compromiso, t2.num_pedido, t2.orden_compra, t2.porcentaje, t2.difer_mas, t2.difer_menos, 
            t2.difer_ext, t3.nombre_r_embobinado, t4.nombre_estado_item, t5.nombre_core, t6.descripcion_productos, t6.magnetico, 
            t6.avance, t6.ancho_material, t6.ubi_troquel, t6.cav_montaje, t6.consumo,t6.ubica_ficha,t6.ficha_tecnica_produc, t7.nombre_articulo, t8.nombre_empresa, t8.forma_pago, t8.logo_etiqueta, 
            t9.nombres, t9.apellidos, t10.ficha_tecnica, t10.id_material, t10.id_clien_produc,t10.observaciones_ft,t2.fecha_cierre  
            FROM pedidos_item t1 
            INNER JOIN pedidos t2 ON t1.id_pedido = t2.id_pedido 
            INNER JOIN ruta_embobinado t3 ON t1.ruta_embobinado = t3.id_ruta_embobinado
            INNER JOIN estado_item_pedido t4 ON t1.id_estado_item_pedido = t4.id_estado_item_pedido 
            INNER JOIN core t5 ON t1.core = t5.id_core 
            INNER JOIN productos t6 ON t1.codigo = t6.codigo_producto 
            INNER JOIN tipo_articulo t7 ON t6.id_tipo_articulo = t7.id_tipo_articulo 
            INNER JOIN cliente_proveedor t8 ON t2.id_cli_prov = t8.id_cli_prov 
            INNER JOIN persona t9 ON t2.id_persona = t9.id_persona 
            INNER JOIN cliente_producto t10 ON t1.id_clien_produc = t10.id_clien_produc
            WHERE $consulta ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ConsultaIdPedidoItem($id_item)
    {
        $sql = "SELECT t1.*, t3.descripcion_productos, t4.nombre_articulo, t5.porcentaje, t5.id_estado_pedido, t5.num_pedido, t5.iva, t3.avance, 
            t3.cav_montaje, t3.ancho_material
            FROM pedidos_item t1 
            INNER JOIN cliente_producto t2 ON t1.id_clien_produc = t2.id_clien_produc 
            INNER JOIN productos t3 ON t2.id_producto = t3.id_productos 
            INNER JOIN tipo_articulo t4 ON t3.id_tipo_articulo = t4.id_tipo_articulo
            INNER JOIN pedidos t5 ON t1.id_pedido = t5.id_pedido
            WHERE id_pedido_item = '$id_item'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function SumaVentaProducto($id_pedido, $clase_articulo)
    {
        $sql = "SELECT SUM(t1.total) AS total FROM pedidos_item t1 
            INNER JOIN productos t2 ON t1.codigo = t2.codigo_producto 
            INNER JOIN tipo_articulo t3 ON t2.id_tipo_articulo = t3.id_tipo_articulo 
            WHERE t1.id_pedido = '$id_pedido' AND t3.id_clase_articulo $clase_articulo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ConsultaUltimoItem($id_pedido)
    {
        $sql = "SELECT MAX(item) AS ultimo_item FROM pedidos_item WHERE id_pedido = $id_pedido";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function op_pendientes_embobinado()
    {
        $sql = "SELECT t2.*, t3.nombre_estado FROM pedidos_item t1 
            INNER JOIN item_producir t2 ON t1.n_produccion = t2.num_produccion 
            INNER JOIN estado_item_producir t3 ON t2.estado_item_producir = t3.id_estado_item_producir
            WHERE t1.id_estado_item_pedido = 15 
            AND t1.n_produccion != 0 GROUP BY t1.n_produccion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ConsultaIdPedidoIdItem($id_pedido, $item)
    {
        $sql = "SELECT t1.*, t2.cav_montaje, t2.descripcion_productos, t3.ficha_tecnica, t3.id_material, t3.id_clien_produc, t4.nombre_r_embobinado, t5.nombre_core,
         t6.nombre_estado_item,t10.nombre_clase_articulo AS grupo
            FROM pedidos_item t1
            INNER JOIN productos t2 ON t1.codigo = t2.codigo_producto
            INNER JOIN cliente_producto t3 ON t1.id_clien_produc = t3.id_clien_produc
            INNER JOIN ruta_embobinado t4 ON t1.ruta_embobinado = t4.id_ruta_embobinado
            INNER JOIN core t5 ON t1.core = t5.id_core
            INNER JOIN estado_item_pedido t6 ON t1.id_estado_item_pedido = t6.id_estado_item_pedido
            INNER JOIN tipo_articulo t9 ON t2.id_tipo_articulo = t9.id_tipo_articulo
            INNER JOIN clase_articulo t10 ON t9.id_clase_articulo = t10.id_clase_articulo
            WHERE t1.id_pedido = '$id_pedido' AND t1.item = '$item'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function ConsultaIdPedidoIdEstadoItemPedido($id_estado_item_pedido)
    {
        $sql = "SELECT t1.*, t2.cav_montaje, t2.descripcion_productos,t3.id_producto, t3.ficha_tecnica, t3.id_material, t3.id_clien_produc,t4.*,t5.nombre_empresa,
         t6.nombre_estado_item,t7.ruta,t10.nombre_clase_articulo AS grupo
            FROM pedidos_item t1
            INNER JOIN productos t2 ON t1.codigo = t2.codigo_producto
            INNER JOIN cliente_producto t3 ON t1.id_clien_produc = t3.id_clien_produc
            INNER JOIN pedidos t4 ON t1.id_pedido = t4.id_pedido
            INNER JOIN cliente_proveedor t5 ON t4.id_cli_prov = t5.id_cli_prov 
            INNER JOIN estado_item_pedido t6 ON t1.id_estado_item_pedido = t6.id_estado_item_pedido
            INNER JOIN direccion t7 ON t4.id_dire_entre = t7.id_direccion
            INNER JOIN tipo_articulo t9 ON t2.id_tipo_articulo = t9.id_tipo_articulo
            INNER JOIN clase_articulo t10 ON t9.id_clase_articulo = t10.id_clase_articulo
            WHERE t1.id_estado_item_pedido = $id_estado_item_pedido";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_items_pendientes_compra($condicion)
    {
        $sql = "SELECT t1.*, t2.cav_montaje, t2.descripcion_productos,t3.id_producto, t3.ficha_tecnica, t3.id_material, t3.id_clien_produc,t4.*,t5.nombre_empresa,
         t6.nombre_estado_item,t7.ruta,t8.nombre_core,t10.id_clase_articulo,t10.nombre_clase_articulo
            FROM pedidos_item t1
            INNER JOIN productos t2 ON t1.codigo = t2.codigo_producto
            INNER JOIN cliente_producto t3 ON t1.id_clien_produc = t3.id_clien_produc
            INNER JOIN pedidos t4 ON t1.id_pedido = t4.id_pedido
            INNER JOIN cliente_proveedor t5 ON t4.id_cli_prov = t5.id_cli_prov 
            INNER JOIN estado_item_pedido t6 ON t1.id_estado_item_pedido = t6.id_estado_item_pedido
            INNER JOIN direccion t7 ON t4.id_dire_entre = t7.id_direccion
            INNER JOIN core t8 ON t1.core = t8.id_core
            INNER JOIN tipo_articulo t9 ON t2.id_tipo_articulo = t9.id_tipo_articulo
            INNER JOIN clase_articulo t10 ON t9.id_clase_articulo = t10.id_clase_articulo
            WHERE $condicion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function AvanceOp($op)
    {
        $sql = "SELECT t2.* FROM pedidos_item t1 
            INNER JOIN productos t2 ON t1.codigo = t2.codigo_producto 
            WHERE t1.n_produccion = '$op' LIMIT 1";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consulta_avance_op($fecha_crea, $fecha_fin)
    {
        $sql = "SELECT t1.n_produccion,t2.*,t3.*,t4.nombres,t4.apellidos,t5.nombre_maquina,t6.tamanio_etiq FROM pedidos_item t1 
        INNER JOIN productos t2 ON t1.codigo=t2.codigo_producto 
        INNER JOIN desperdicio_op t3 ON t3.num_produccion=t1.n_produccion 
        INNER JOIN persona t4 ON t4.id_persona =t3.id_persona 
        INNER JOIN maquinas t5 ON t3.maquina=t5.id_maquina 
        INNER JOIN item_producir t6 ON t3.num_produccion=t6.num_produccion
         WHERE t1.fecha_crea >='$fecha_crea 00:00:00' and t1.fecha_crea < '$fecha_fin 00:00:00'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ValorItemVentaOp($num_produccion)
    {
        $sql = "SELECT MAX(v_unidad) AS v_unidad_max, MIN(v_unidad) AS v_unidad_min, AVG(v_unidad) AS v_unidad_prom 
            FROM `pedidos_item` WHERE n_produccion = $num_produccion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ConsultaItemOp($num_produccion)
    {
        $sql = "SELECT * FROM pedidos_item WHERE n_produccion = $num_produccion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ConsultaItemPedido($id_pedido)
    {
        $sql = "SELECT t5.cav_montaje, t1.n_produccion, t1.id_estado_item_pedido, t3.id_core, t4.id_ruta_embobinado, t1.id_pedido_item, 
        t1.item, t1.codigo, t5.descripcion_productos, t1.Cant_solicitada, t4.nombre_r_embobinado, t3.nombre_core, t1.cant_x, 
        t1.trm, t1.moneda, t1.v_unidad, t1.total, t7.nombre_estado_item, t2.num_pedido,
        t2.difer_mas, t2.difer_menos, t2.difer_ext, t2.porcentaje
            FROM pedidos_item t1 
            INNER JOIN pedidos t2 ON t1.id_pedido = t2.id_pedido
            INNER JOIN core t3 ON t1.core = t3.id_core
            INNER JOIN ruta_embobinado t4 ON t1.ruta_embobinado = t4.id_ruta_embobinado
            INNER JOIN productos t5 ON t1.codigo = t5.codigo_producto
            INNER JOIN estado_item_pedido t7 ON t1.id_estado_item_pedido = t7.id_estado_item_pedido
            WHERE t1.id_pedido = $id_pedido ORDER BY t1.item ASC";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function ConsultaRangoFecha($desde, $hasta, $fecha_consulta)
    {
        $sql = "SELECT t1.cant_op, t2.fecha_crea_p, t2.hora_crea, t2.fecha_compromiso, 
            t2.num_pedido,t2.difer_mas,t2.difer_menos,t2.difer_ext, t1.item, t1.Cant_solicitada, t1.n_produccion, t1.codigo, t1.id_pedido, 
            t5.nombre_core, t1.cant_x, t7.nombre_articulo, t6.ubi_troquel,t6.descripcion_productos, 
            (t6.ancho_material/1000)*((t1.cant_op*t6.avance)/(t6.cav_montaje*1000)) AS m2, 
            t2.orden_compra, t8.nombre_empresa, t9.nombres, t9.apellidos, t8.forma_pago, t1.total, t4.nombre_estado_item,
            t1.id_pedido_item,t10.ficha_tecnica
            FROM pedidos_item t1 
            INNER JOIN pedidos t2 ON t1.id_pedido = t2.id_pedido 
            INNER JOIN estado_item_pedido t4 ON t1.id_estado_item_pedido = t4.id_estado_item_pedido 
            INNER JOIN core t5 ON t1.core = t5.id_core 
            INNER JOIN productos t6 ON t1.codigo = t6.codigo_producto 
            INNER JOIN tipo_articulo t7 ON t6.id_tipo_articulo = t7.id_tipo_articulo 
            INNER JOIN cliente_proveedor t8 ON t2.id_cli_prov = t8.id_cli_prov 
            INNER JOIN persona t9 ON t2.id_persona = t9.id_persona
            INNER JOIN cliente_producto t10 ON t10.id_clien_produc=t1.id_clien_produc 
            WHERE t2.$fecha_consulta >= '$desde' AND t2.$fecha_consulta <= '$hasta'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function ConsultaDetallePedido($condicion)
    {
        $sql = "SELECT t1.cant_op, t2.fecha_crea_p, t2.hora_crea, t2.fecha_compromiso, t2.num_pedido,t2.difer_mas,t2.difer_menos,t2.difer_ext, t1.item, t1.Cant_solicitada, t1.n_produccion, t1.codigo, 
            t5.nombre_core, t1.cant_x, t7.nombre_articulo, t6.ubi_troquel,t6.descripcion_productos, 
            (t6.ancho_material/1000)*((t1.cant_op*t6.avance)/(t6.cav_montaje*1000)) AS m2, t2.orden_compra, 
            t8.nombre_empresa, t9.nombres, t9.apellidos, t8.forma_pago, t1.total, t4.nombre_estado_item,
            t1.id_pedido_item,t10.ficha_tecnica
            FROM pedidos_item t1 
            INNER JOIN pedidos t2 ON t1.id_pedido = t2.id_pedido 
            INNER JOIN estado_item_pedido t4 ON t1.id_estado_item_pedido = t4.id_estado_item_pedido 
            INNER JOIN core t5 ON t1.core = t5.id_core 
            INNER JOIN productos t6 ON t1.codigo = t6.codigo_producto 
            INNER JOIN tipo_articulo t7 ON t6.id_tipo_articulo = t7.id_tipo_articulo 
            INNER JOIN cliente_proveedor t8 ON t2.id_cli_prov = t8.id_cli_prov 
            INNER JOIN persona t9 ON t2.id_persona = t9.id_persona 
            INNER JOIN cliente_producto t10 ON t10.id_clien_produc=t1.id_clien_produc
            WHERE $condicion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function recalcularValorPedido($id_pedido)
    {
        $sql = "SELECT SUM(t1.total) AS total, t3.id_clase_articulo FROM pedidos_item t1 
            INNER JOIN productos t2 ON t1.codigo = t2.codigo_producto 
            INNER JOIN tipo_articulo t3 ON t2.id_tipo_articulo = t3.id_tipo_articulo 
            WHERE t1.id_pedido = $id_pedido GROUP BY t3.id_clase_articulo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        $total_etiq = 0;
        $total_tecn = 0;
        foreach ($resultado as $value) {
            if ($value->id_clase_articulo == 1 || $value->id_clase_articulo == 2) {
                $total_etiq = $total_etiq + $value->total;
            } else {
                $total_tecn = $total_tecn + $value->total;
            }
        }
        $res = [
            'total_etiq' => $total_etiq,
            'total_tec' => $total_tecn
        ];
        return $res;
    }
}

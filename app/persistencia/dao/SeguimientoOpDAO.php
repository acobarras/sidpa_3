<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class SeguimientoOpDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, 'seguimiento_op');
    }

    /**
     * 
     * @return type
     */
    public function consultar_entrada_tecnologia() {
        $sql = "SELECT * FROM seguimiento_op";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_seguimiento_item($pedido,$item)
    {
        if($item != '') {
            $sql = "SELECT seguimiento_op.pedido,seguimiento_op.item,seguimiento_op.fecha_crea,seguimiento_op.hora_crea,per.nombres,per.apellidos,ar_t.nombre_area_trabajo,
                        act_a.nombre_actividad_area,seguimiento_op.observacion FROM seguimiento_op
                INNER JOIN actividad_area act_a ON seguimiento_op.id_actividad=act_a.id_actividad_area
                INNER JOIN area_trabajo ar_t ON seguimiento_op.id_area = ar_t.id_area_trabajo
                INNER JOIN persona per ON seguimiento_op.id_persona = per.id_persona WHERE pedido=$pedido AND item=$item";
        } else {
            $sql = "SELECT seguimiento_op.pedido,seguimiento_op.item,seguimiento_op.fecha_crea,seguimiento_op.hora_crea,per.nombres,per.apellidos,ar_t.nombre_area_trabajo,
                        act_a.nombre_actividad_area,seguimiento_op.observacion FROM seguimiento_op
                INNER JOIN actividad_area act_a ON seguimiento_op.id_actividad=act_a.id_actividad_area
                INNER JOIN area_trabajo ar_t ON seguimiento_op.id_area = ar_t.id_area_trabajo
                INNER JOIN persona per ON seguimiento_op.id_persona = per.id_persona WHERE pedido=$pedido AND item=$item";
        } 
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_item_pedido($num_pedido) {
        $sql = "SELECT t2.fecha_crea, t2.codigo, t2.Cant_solicitada, t6.nombre_estado_item, t5.nombre_articulo,
        t4.descripcion_productos, t1.num_pedido, t2.item 
        FROM pedidos t1 
        INNER JOIN pedidos_item t2 ON t1.id_pedido = t2.id_pedido 
        INNER JOIN cliente_producto t3 ON t2.id_clien_produc = t3.id_clien_produc 
        INNER JOIN productos t4 ON t3.id_producto = t4.id_productos 
        INNER JOIN tipo_articulo t5 ON t4.id_tipo_articulo = t5.id_tipo_articulo 
        INNER JOIN estado_item_pedido t6 ON t2.id_estado_item_pedido = t6.id_estado_item_pedido 
        WHERE t1.num_pedido = ".$num_pedido;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function movimientos_item_pedido($fecha_desde,$fecha_hasta)
    {
        $sql = "SELECT t1.*, t2.nombres, t2.apellidos, t3.nombre_area_trabajo, t4.nombre_actividad_area 
            FROM seguimiento_op t1 
            INNER JOIN persona t2 ON t1.id_persona = t2.id_persona 
            INNER JOIN area_trabajo t3 ON t1.id_area = t3.id_area_trabajo 
            INNER JOIN actividad_area t4 ON t1.id_actividad = t4.id_actividad_area 
            WHERE t1.fecha_crea >= '$fecha_desde' AND t1.fecha_crea <= '$fecha_hasta'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;    
    }

}

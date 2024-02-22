<?php

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class ItemProducirDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'item_producir');
    }

    public function consultar_item_producir_num($num)
    {
        $sql = "SELECT t1.* ,t2.id_maquina, t2.nombre_maquina, t3.*
            FROM item_producir t1 
            INNER JOIN maquinas t2 ON t1.maquina = t2.id_maquina
            INNER JOIN estado_item_producir t3 ON t1.estado_item_producir = t3.id_estado_item_producir
            WHERE num_produccion = $num";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_item_fechas($fecha_crea, $fecha_fin)
    {
        $sql = "SELECT t1.* ,t2.id_maquina, t2.nombre_maquina, t3.*,t4.ancho,t4.codigo_material,t4.metros_lineales,
        t4.id_persona,t4.ml_usados,t4.num_troquel,t4.estado_ml,t5.nombres,t5.apellidos,t5.estado AS estado_persona 
        FROM item_producir t1 
        INNER JOIN maquinas t2 ON t1.maquina = t2.id_maquina 
        INNER JOIN estado_item_producir t3 ON t1.estado_item_producir = t3.id_estado_item_producir 
        INNER JOIN metros_lineales t4 ON t4.id_item_producir = t1.id_item_producir 
        INNER JOIN persona t5 ON t5.id_persona=t4.id_persona 
        WHERE t1.fecha_crea >='$fecha_crea 00:00:00' and t1.fecha_crea < '$fecha_fin 00:00:00'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_item_producir_ordenes($estado)
    {
        $sql = "SELECT t1.*, t2.nombre_maquina, t2.id_maquina, t3.* FROM item_producir t1
            INNER JOIN maquinas t2 ON t1.maquina = t2.id_maquina
            INNER JOIN estado_item_producir t3 ON t1.estado_item_producir = t3.id_estado_item_producir
            WHERE t1.estado_item_producir = $estado";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_maquinas($fecha_produccion, $maquina)
    {
        $sql = "SELECT t1.*, t2.id_maquina, t2.nombre_maquina, t3.nombre_estado 
            FROM item_producir t1 
            INNER JOIN maquinas t2 ON t1.maquina = t2.id_maquina
            INNER JOIN estado_item_producir t3 ON t1.estado_item_producir = t3.id_estado_item_producir
            WHERE t1.fecha_produccion = '$fecha_produccion'  AND t1.maquina = '$maquina' 
            ORDER BY t1.turno_maquina ASC";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_maquina_produccion($estado)
    {

        $sql = "SELECT t1.*, t2.id_maquina, t2.nombre_maquina, t3.nombre_estado, t5.ubi_troquel, 
        IF (t5.ficha_tecnica_produc IS NULL , t6.ficha_tecnica, t5.ficha_tecnica_produc) AS ficha_tecnica
            FROM item_producir t1 
            INNER JOIN maquinas t2 ON t1.maquina = t2.id_maquina
            INNER JOIN estado_item_producir t3 ON t1.estado_item_producir = t3.id_estado_item_producir
            INNER JOIN pedidos_item t4 ON t1.num_produccion = t4.n_produccion 
            INNER JOIN productos t5 ON t4.codigo = t5.codigo_producto
            INNER JOIN cliente_producto t6 ON t4.id_clien_produc = t6.id_clien_produc
            WHERE t1.estado_item_producir IN ($estado) GROUP BY t1.id_item_producir";

        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_maquina_produccion2($estado,$id_maquina)
    {
        $sql = "SELECT t1.*, t2.id_maquina, t2.nombre_maquina, t3.nombre_estado 
            FROM item_producir t1 
            INNER JOIN maquinas t2 ON t1.maquina = t2.id_maquina
            INNER JOIN estado_item_producir t3 ON t1.estado_item_producir = t3.id_estado_item_producir
            WHERE t1.estado_item_producir IN ($estado) AND t2.id_maquina = $id_maquina 
            ORDER BY t1.fecha_produccion ASC, t1.turno_maquina ASC";

        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ConsultaFechaProduccion($fecha_desde, $fecha_hasta)
    {
        $sql = "SELECT * FROM item_producir 
            WHERE fecha_produccion >= '$fecha_desde' AND fecha_produccion <= '$fecha_hasta'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_num_produccion($form)
    {
        $sql = "SELECT t2.fecha_compromiso, t2.num_pedido,t1.n_produccion,  t2.observaciones, t1.item, t1.codigo, t1.id_pedido_item,t1.cant_bodega, t1.cant_op, t3.magnetico, 
        t3.descripcion_productos, t8.id_ruta_embobinado, t8.nombre_r_embobinado, t2.porcentaje, t2.difer_mas, t2.difer_menos, t2.difer_ext, 
        t4.nombre_empresa, t5.id_core, t6.nombre_core, t3.ubi_troquel, t3.cav_montaje, t3.ancho_material, t3.avance, t1.Cant_solicitada, 
        t1.cant_x, t5.id_material, t1.id_estado_item_pedido, t7.nombre_estado_item
            FROM pedidos_item t1
            INNER JOIN pedidos t2 ON t1.id_pedido = t2.id_pedido
            INNER JOIN productos t3 ON t1.codigo = t3.codigo_producto
            INNER JOIN cliente_proveedor t4 ON t2.id_cli_prov = t4.id_cli_prov
            INNER JOIN cliente_producto t5 ON t1.id_clien_produc = t5.id_clien_produc
            INNER JOIN core t6 ON t5.id_core = t6.id_core
            INNER JOIN estado_item_pedido t7 ON t1.id_estado_item_pedido= t7.id_estado_item_pedido
            INNER JOIN ruta_embobinado t8 ON t5.id_ruta_embobinado = t8.id_ruta_embobinado
            WHERE t1.n_produccion=$form";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function participa_op($num_op)
    {
        $sql = "SELECT  t1.id_item_producir,t1.num_produccion,t1.cant_op,t1.precio_material,t1.tamanio_etiq,t1.ancho_op,t1.material,t1.material_solicitado,t2.ancho,t2.ml_usados,t2.id_persona,t3.cantidad_etiquetas,t3.id_operario_troquela,t3.id_persona AS id_embobina,t4.nombres,t4.apellidos 
        FROM item_producir t1        
        INNER JOIN metros_lineales t2 ON t2.id_item_producir=t1.id_item_producir
        INNER JOIN desperdicio_op t3 ON t3.num_produccion=t1.num_produccion AND t3.id_operario_troquela=t2.id_persona
        INNER JOIN persona t4 ON t4.id_persona=t3.id_operario_troquela
        WHERE t1.num_produccion=$num_op AND t2.ml_usados!=0.00";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_marcacion_cola($num_op)
    {
        $sql = "SELECT t1.num_produccion, 
        (CASE WHEN t1.material_solicitado = '' THEN t1.material ELSE t1.material_solicitado END) AS material_op, 
        (CASE WHEN t1.ancho_confirmado = '0' THEN t1.ancho_op ELSE t1.ancho_confirmado END) AS ancho_material, 
        t2.descripcion_productos, t1.num_produccion
        FROM item_producir t1
        INNER JOIN productos t2 ON (CASE WHEN t1.material_solicitado = '' THEN t1.material ELSE t1.material_solicitado END) = t2.codigo_producto
        WHERE t1.num_produccion = '$num_op'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

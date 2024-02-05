<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class entrada_tecnologiaDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'entrada_tecnologia');
    }


    public function consultar_inv_product($id_product)
    {
        $sql = "SELECT SUM(entrada) AS entrada, 
            SUM(salida) AS salida,
            SUM(entrada)-SUM(salida) AS total 
            FROM entrada_tecnologia WHERE id_productos=" . $id_product;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_seguimiento_producto($id_productos)
    {
        $sql = "SELECT  t1.ubicacion, SUM(entrada) AS entrada, SUM(salida) AS salida, (SUM(entrada)-SUM(salida)) AS total 
            FROM entrada_tecnologia t1
            WHERE t1.id_productos =  $id_productos GROUP BY t1.ubicacion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_salida_item_pedido($documento)
    {
        $sql = "SELECT * FROM entrada_tecnologia WHERE documento = '$documento'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_inv_product_codigo($codigo)
    {
        $sql = "SELECT sum(entrada) - sum(salida) AS cantidad_inventario FROM entrada_tecnologia
            WHERE  codigo_producto LIKE '%$codigo%'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado[0]->cantidad_inventario;
    }

    public function consultar_seguimiento_bobina($id_productos)
    {
        $sql = "SELECT t1.id_ingresotec, t1.ancho,t1.ubicacion, SUM(entrada) AS entrada, SUM(salida)AS salida,
                    ((SUM(entrada)-SUM(salida))/t1.ancho) * 1000 AS ML, 
                    (SUM(entrada)-SUM(salida)) AS M2
                FROM entrada_tecnologia t1
                WHERE t1.id_productos =  $id_productos GROUP BY t1.ancho";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_cantidad($id)
    {
        $sql = "SELECT  sum(entrada) - sum(salida) AS cantidad FROM entrada_tecnologia
                               WHERE  id_productos = '$id'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado[0]->cantidad;
    }
    public function consultar_items_pendientes_bodega($parametro)
    {
        $sql = "SELECT t5.id_pedido, t5.fecha_compromiso, t7.nombre_empresa, t9.nombre_ruta AS ruta, t10.nombre_estado_item, t5.difer_mas, t5.difer_menos, t5.difer_ext, t5.porcentaje, t1.id_ingresotec, t1.documento, t1.codigo_producto, t1.estado_inv ,SUM(t1.salida) AS salida, t3.nombre_articulo,t3.id_clase_articulo,t2.descripcion_productos,t4.nombre_estado, t5.num_pedido, t6.core, t11.nombre_core, t6.cant_x,t12.nombre_clase_articulo AS grupo,t6.n_produccion,t6.id_pedido_item,t6.item,t6.codigo
        FROM entrada_tecnologia AS t1 
        INNER JOIN productos AS t2 ON t1.id_productos=t2.id_productos 
        INNER JOIN tipo_articulo AS t3 ON t2.id_tipo_articulo=t3.id_tipo_articulo 
        INNER JOIN estados_alistamiento AS t4 ON t1.estado_inv=t4.id 
        INNER JOIN pedidos t5 ON t5.num_pedido = SUBSTRING_INDEX(t1.documento,'-',1)
        INNER JOIN pedidos_item t6 ON t6.id_pedido = t5.id_pedido
        INNER JOIN cliente_proveedor t7 on t7.id_cli_prov = t5.id_cli_prov
        INNER JOIN direccion t8 ON t8.id_direccion = t5.id_dire_entre
        INNER JOIN ruta_entrega t9 ON t9.id_ruta = t8.ruta
        INNER JOIN estado_item_pedido t10 ON t10.id_estado_item_pedido = t6.id_estado_item_pedido
        INNER JOIN core t11 ON t6.core = t11.id_core
        INNER JOIN clase_articulo t12 ON t3.id_clase_articulo = t12.id_clase_articulo
        WHERE t1.estado_inv in ($parametro) AND t3.id_clase_articulo!=1 AND t1.documento LIKE '%-%' AND SUBSTRING_INDEX(t1.documento,'-',-1) = t6.item  GROUP BY t1.documento;";
        // $sql = "SELECT t1.id_ingresotec, t1.documento, t1.codigo_producto,t1.estado_inv ,SUM(t1.salida) AS salida, t3.nombre_articulo,t3.id_clase_articulo,t2.descripcion_productos,t4.nombre_estado 
        //     FROM entrada_tecnologia AS t1 
        //     INNER JOIN productos AS t2 ON t1.id_productos=t2.id_productos 
        //     INNER JOIN tipo_articulo AS t3 ON t2.id_tipo_articulo=t3.id_tipo_articulo 
        //     INNER JOIN estados_alistamiento AS t4 ON t1.estado_inv=t4.id 
        //     WHERE t1.estado_inv in ($parametro) AND t3.id_clase_articulo!=1 GROUP BY t1.documento";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consulta_producto_ubicacion($id)
    {
        $sql = "SELECT t1.ubicacion, t2.id_productos, t2.id_tipo_articulo, t2.codigo_producto,t2.descripcion_productos,sum(t1.entrada) - sum(t1.salida) AS cantidad  
            FROM entrada_tecnologia t1
            INNER JOIN productos t2 ON t1.id_productos= t2.id_productos
            WHERE ubicacion = '$id' GROUP BY t2.id_productos ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_seguimiento_bobina_ancho($id_productos, $ancho)
    {
        $sql = "SELECT t1.id_ingresotec,  t1.ancho,t1.ubicacion, SUM(entrada) AS entrada, SUM(salida)AS salida,
        ((SUM(entrada)-SUM(salida))/t1.ancho) * 1000 AS ML, 
        (SUM(entrada)-SUM(salida)) AS M2
    FROM entrada_tecnologia t1
    WHERE t1.id_productos =  $id_productos  AND t1.ancho= $ancho GROUP BY t1.ubicacion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_ubicacion_bobina_ancho()
    {
        $sql = "SELECT DISTINCT ancho FROM `entrada_tecnologia`WHERE ancho!=0  
        ORDER BY `entrada_tecnologia`.`ancho` ASC";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function salida_producto($documento)
    {
        $sql = "SELECT SUM(salida) AS salida_bodega FROM entrada_tecnologia WHERE documento = '$documento'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_producto_diag($id_producto, $num_consecutivo, $item)
    {
        $sql = "SELECT * FROM `entrada_tecnologia` 
        WHERE documento='$num_consecutivo-$item' AND id_productos=$id_producto";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_ingreso($id_producto, $ubicacion, $fecha)
    {
        $sql = "SELECT * FROM entrada_tecnologia
        WHERE id_productos=$id_producto AND ubicacion='$ubicacion' AND DATE(fecha_crea)='$fecha' AND entrada != 0";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_alistamiento_ubicacion($ubicacion, $id_producto)
    {
        $sql = "SELECT t1.ubicacion, t1.id_productos, t1.codigo_producto
        FROM entrada_tecnologia t1
        WHERE t1.ubicacion = '$ubicacion' AND t1.id_productos = $id_producto AND t1.estado_inv = 2";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
}

<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class productosDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'productos');
    }

    public function consultar_productos($id_clase_articulo = '')
    {
        if ($id_clase_articulo == '') {
            $sql = "SELECT * FROM productos AS t1 
            INNER JOIN tipo_articulo AS t2 ON t1.id_tipo_articulo = t2.id_tipo_articulo 
            INNER JOIN clase_articulo AS class_art ON t2.id_clase_articulo = class_art.id_clase_articulo";
        } else {
            $sql = "SELECT * FROM productos AS t1 
            INNER JOIN tipo_articulo AS t2 ON t1.id_tipo_articulo = t2.id_tipo_articulo 
            INNER JOIN clase_articulo AS class_art ON t2.id_clase_articulo = class_art.id_clase_articulo
            WHERE class_art.id_clase_articulo=" . $id_clase_articulo;
        }
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_productos_clase_articulo($clase)
    {
        $sql = "SELECT t1.*, t2.nombre_articulo FROM productos t1 
            INNER JOIN tipo_articulo t2 ON t1.id_tipo_articulo = t2.id_tipo_articulo 
            WHERE t2.id_clase_articulo = '$clase'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_productos_clase_articulo_tres($tipo)
    {

        $sql = "SELECT t1.*, t2.nombre_articulo FROM productos t1 INNER JOIN tipo_articulo t2 ON t1.id_tipo_articulo = t2.id_tipo_articulo WHERE t2.id_tipo_articulo = '$tipo'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    public function consultar_productos_inventario_comercial()
    {
        $parametro = "";
        if ($_GET['codigo'] != "") {
            $parametro = "AND t1.codigo_producto LIKE '%" . $_GET['codigo'] . "%'";
        } else {
            $parametro = "";
        }

        $sql = "SELECT t1.*, t2.nombre_articulo FROM productos t1
		INNER JOIN tipo_articulo t2 ON t1.id_tipo_articulo = t2.id_tipo_articulo 
        		WHERE t2.id_clase_articulo = " . $_GET['clase_articulo'] . " AND t1.estado_producto != 0 $parametro";


        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    public function consultar_productos_mat()
    {

        $sql = "SELECT DISTINCT tamano FROM productos WHERE id_tipo_articulo = 4 AND costo != 0 ORDER BY tamano ASC";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_productos_costo($adh, $material)
    {

        $sql = "SELECT max(costo) FROM productos WHERE id_adh = '$adh' AND tamano = '$material'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll();
        return $resultado;
    }

    public function consultar_productos_costo_especifico($material)
    {

        $sql = "SELECT costo FROM productos WHERE codigo_producto like '%" . $material . "%'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_productos_especifico($codigo_producto)
    {
        $sql = "SELECT t1.*,t2.nombre_articulo,t2.id_clase_articulo FROM productos t1 
        INNER JOIN tipo_articulo t2 ON t2.id_tipo_articulo=t1.id_tipo_articulo 
        WHERE codigo_producto = '$codigo_producto'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_productos_id($id_material)
    {
        $sql = "SELECT codigo_producto, costo, ancho_material, precio1 FROM productos WHERE id_productos = trim($id_material)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_productos_material()
    {
        $sql = "SELECT * FROM productos WHERE id_tipo_articulo = 4  ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ConsultaProductoId($id_productos)
    {
        $sql = "SELECT * FROM productos t1 
                    INNER JOIN tipo_articulo t2 ON t1.id_tipo_articulo = t2.id_tipo_articulo 
                    INNER JOIN clase_articulo t3 ON t2.id_clase_articulo = t3.id_clase_articulo
                    WHERE t1.id_productos = $id_productos";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_productos_material_compras()
    {
        $sql = "SELECT * FROM productos t1
            WHERE NOT EXISTS 
            (SELECT t2.codigo_especial 
                FROM codigos_especiales t2 
                WHERE t2.codigo_especial = t1.codigo_producto ) 
            AND t1.id_tipo_articulo = 4";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ConsultaProductoCodigo($codigo)
    {
        $sql = "SELECT * FROM productos t1 
                    INNER JOIN tipo_articulo t2 ON t1.id_tipo_articulo = t2.id_tipo_articulo 
                    INNER JOIN clase_articulo t3 ON t2.id_clase_articulo = t3.id_clase_articulo
                    WHERE t1.codigo_producto = '$codigo'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_producto_inventario($codigo_producto, $tipo_articulo)
    {
        $sql = "SELECT DISTINCT   pro.id_productos,pro.codigo_producto,ta.nombre_articulo,
                   pro.id_tipo_articulo,pro.descripcion_productos, 0 as cantidad 
                            FROM  productos AS pro 
                             	INNER JOIN tipo_articulo as ta ON ta.id_tipo_articulo= pro.id_tipo_articulo	
                                	INNER JOIN clase_articulo AS ca ON ca.id_clase_articulo = ta.id_clase_articulo
                                      WHERE pro.codigo_producto LIKE '%" . $codigo_producto . "%'  AND ca.id_clase_articulo =" . $tipo_articulo;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_product_descripcion($descripcion)
    {
        $sql = "SELECT * FROM productos 
            WHERE REPLACE(descripcion_productos,' ','') = REPLACE('$descripcion',' ','')";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function cons_prod_codigo($codigo)
    {
        $sql = "SELECT * FROM productos 
            WHERE codigo_producto LIKE '%$codigo%' ORDER BY id_productos DESC LIMIT 1";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consulta_fichas($id_product)
    {
        $sql = "SELECT t1.img_ficha FROM productos t1
            WHERE t1.id_productos=$id_product";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function busqueda_codigos_comercial($condicion)
    {
        $sql = "SELECT * FROM productos t1 $condicion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;   
    }
    public function consulta_bobinas()
    {
        $sql = "SELECT * FROM productos t1 WHERE t1.id_tipo_articulo = 4 AND t1.estado_producto != 0;";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado; 
        
    }
    public function consulta_marcacion_bobinas($codigo) 
    {
        $sql = "SELECT * FROM `productos` WHERE codigo_producto = '$codigo'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
}

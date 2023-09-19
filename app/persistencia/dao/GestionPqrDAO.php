<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class GestionPqrDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'gestion_pqr');
    }

    public function valida_numero_pqr($num_pqr)
    {
        $sql = "SELECT * FROM gestion_pqr WHERE num_pqr = '$num_pqr'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_num_pqr($num_pqr)
    {
        $sql = "SELECT t1.*, t2.nombre_estado_pqr FROM gestion_pqr t1 
            INNER JOIN estados_pqr t2 ON t1.estado = t2.id_estado 
            WHERE t1.num_pqr ='$num_pqr'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_pqr($estado)
    {
        $sql = "SELECT t1.*, t2.nombre_estado_pqr,t3.codigo,t3.analisis_pqr,t3.descripcion,t3.accion FROM gestion_pqr t1 
            INNER JOIN estados_pqr t2 ON t1.estado = t2.id_estado 
            LEFT JOIN codigo_respuesta_pqr t3 ON t3.id_respuesta_pqr=t1.id_respuesta_pqr
            WHERE t1.estado IN($estado)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_direccion_pedido($num_pedido)
    {
        $sql = "SELECT t2.* FROM pedidos t1 
        INNER JOIN direccion t2 ON t1.id_dire_entre = t2.id_direccion
        WHERE t1.num_pedido=$num_pedido";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_direccion_cliente($id_cli_prov)
    {
        $sql = "SELECT t2.* FROM direccion t2 
        WHERE t2.id_cli_prov=$id_cli_prov";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function repite_motivo_cliente($id_cli_prov, $id_respuesta_pqr)
    {
        $sql = "SELECT COUNT(id_respuesta_pqr) AS cantidad_misma_respuesta 
            FROM gestion_pqr 
            WHERE id_cli_prov = $id_cli_prov AND id_respuesta_pqr = $id_respuesta_pqr";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_id_pedido_item($id_pedido_item)
    {
        $sql = "SELECT * FROM gestion_pqr 
            WHERE id_pedido_item = $id_pedido_item";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function repite_motivo($id_respuesta_pqr, $ano, $mes)
    {
        if ($mes == '0') {
            $sql = "SELECT t1.*, t2.nombre_empresa, t3.nombre_estado_pqr
            FROM gestion_pqr t1
            INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov
            INNER JOIN estados_pqr t3 ON t1.estado = t3.id_estado
            WHERE t1.id_respuesta_pqr = $id_respuesta_pqr  AND YEAR(t1.fecha_crea) = '$ano'";
        } else {
            $sql = "SELECT t1.*, t2.nombre_empresa, t3.nombre_estado_pqr
            FROM gestion_pqr t1
            INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov
            INNER JOIN estados_pqr t3 ON t1.estado = t3.id_estado
            WHERE t1.id_respuesta_pqr = $id_respuesta_pqr  AND YEAR(t1.fecha_crea) = '$ano' AND substring(num_pqr,5,1) = '$mes'";
        }
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function lista_tabla_pqr($ano)
    {
        $sql = "SELECT t1.*, t2.nombre_empresa, t3.nombre_estado_pqr, t4.codigo, t4.item, t5.nombres, t5.apellidos, t6.direccion, t6.contacto, t7.descripcion_productos, t8.num_pedido
            FROM gestion_pqr t1
            INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov
            INNER JOIN estados_pqr t3 ON t1.estado = t3.id_estado
            INNER JOIN pedidos_item t4 ON t1.id_pedido_item = t4.id_pedido_item
            INNER JOIN persona t5 ON t1.id_persona = t5.id_persona
            INNER JOIN direccion t6 ON t1.id_dir_pqr = t6.id_direccion
            INNER JOIN productos t7 ON t4.codigo = t7.codigo_producto
            INNER JOIN pedidos t8 ON t4.id_pedido = t8.id_pedido
            WHERE YEAR(t1.fecha_crea) = '$ano'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function pqr_produccion($num_pedido_cambio)
    {
        $sql = "SELECT * FROM gestion_pqr WHERE num_pedido_cambio = '$num_pedido_cambio'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

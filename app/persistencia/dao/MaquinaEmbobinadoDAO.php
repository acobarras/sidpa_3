<?php

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class MaquinaEmbobinadoDAO extends GenericoDAO
{


    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'maquina_embobinado');
    }

    public function consultar_maquinas_embobinado($estado)
    {
        $sql = "SELECT t1.*, t2.fecha_comp, t2.tamanio_etiq, t3.nombre_maquina, t4.nombre_estado 
            FROM maquina_embobinado t1 
            INNER JOIN item_producir t2 ON t1.num_produccion = t2.num_produccion
            INNER JOIN maquinas t3 ON t1.maquina = t3.id_maquina
            INNER JOIN estado_item_producir t4 ON t1.estado_item_producir= t4.id_estado_item_producir
            WHERE t1.estado_item_producir in ($estado)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_maquina_op($num_op)
    {
        $sql = "SELECT * FROM maquina_embobinado WHERE num_produccion = '$num_op' AND estado_item_producir != 14";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_maquina_op_estado($num_op, $estado)
    {
        $sql = "SELECT * FROM maquina_embobinado WHERE num_produccion = '$num_op' AND estado_item_producir IN($estado)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_turno_maquina($id_maquina, $fecha_embo)
    {
        $sql = "SELECT t1.*, t2.nombre_maquina, t3.nombre_estado 
            FROM maquina_embobinado t1 
            INNER JOIN maquinas t2 ON t1.maquina = t2.id_maquina 
            INNER JOIN estado_item_producir t3 ON t1.estado_item_producir = t3.id_estado_item_producir 
            WHERE t1.maquina = $id_maquina AND t1.fecha_embobinado = '$fecha_embo' AND t1.estado_item_producir != 14";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function embobinado_maquinas_dk()
    {
        $sql = "SELECT t1.*, t2.nombre_maquina FROM maquina_embobinado t1 
            INNER JOIN maquinas t2 ON t1.maquina = t2.id_maquina 
            WHERE t2.tipo_maquina = 3";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function suma_ml_alistado($num_op)
    {
        $sql = "SELECT SUM(ml_asignados)as ml_emb FROM maquina_embobinado where num_produccion = $num_op";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
}

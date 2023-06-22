<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;


class DesperdicioOpDAO extends GenericoDAO
{

    //put your code here
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'desperdicio_op');
    }

    public function cantidad_etiquetas_op($num_produccion)
    {
        $sql = "SELECT SUM(cantidad_etiquetas) AS q_etiquetas FROM desperdicio_op 
            WHERE num_produccion = '$num_produccion' AND cantidad_etiquetas != 0";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function etiquetas_pedido_item($id_pedido_item)
    {
        $sql = "SELECT SUM(cantidad_etiquetas) AS q_etiq_item FROM desperdicio_op 
            WHERE id_pedido_item = '$id_pedido_item'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_desperdicio_op()
    {
        $sql = "SELECT * FROM desperdicio_op";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_entrega_ml($num_op)
    {
        $sql = "SELECT SUM(ml_empleado) as entrega_ml FROM desperdicio_op WHERE num_produccion = $num_op";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_entrega_etiq($num_op)
    {
        $sql = "SELECT SUM(cantidad_etiquetas)as total_etiquetas FROM desperdicio_op WHERE num_produccion= $num_op";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ReporteOperarioMaquina($id_persona, $id_maquina)
    {
        $sql = "SELECT * FROM desperdicio_op 
            WHERE id_persona = $id_persona AND maquina = $id_maquina";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ReportesEmbobinadoOp($num_produccion)
    {
        $sql = "SELECT t1.*, t2.nombres, t2.apellidos, t3.nombre_maquina, t4.tamanio_etiq,t6.num_pedido,t5.item 
        FROM desperdicio_op t1 
        INNER JOIN persona t2 ON t1.id_persona = t2.id_persona 
        INNER JOIN maquinas t3 ON t1.maquina = t3.id_maquina 
        INNER JOIN item_producir t4 ON t1.num_produccion = t4.num_produccion 
        INNER JOIN pedidos_item t5 ON t5.id_pedido_item=t1.id_pedido_item 
        INNER JOIN pedidos t6 ON t6.id_pedido=t5.id_pedido 
        WHERE t1.num_produccion = $num_produccion AND t1.cantidad_etiquetas != 0";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ConsultaIdMetrosLineales($id_metros_lineales)
    {
        $sql = "SELECT * FROM desperdicio_op WHERE id_metros_lineales = '$id_metros_lineales'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function EtiqOperarioTroq($num_op, $id_operario)
    {
        $sql = "SELECT SUM(cantidad_etiquetas)as total_etiquetas FROM desperdicio_op 
        WHERE num_produccion= $num_op AND id_operario_troquela = $id_operario";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
}

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
        $sql = "SELECT SUM(t1.cantidad_etiquetas)as total_etiquetas 
            FROM desperdicio_op t1 
            INNER JOIN maquinas t2 ON t1.maquina = t2.id_maquina 
            WHERE t1.num_produccion = $num_op AND IF (t2.tipo_maquina = 3, t1.id_persona, t1.id_operario_troquela) = $id_operario";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function metros_lineales_productividad($id_persona, $fecha_desde, $fecha_hasta)
    {
        $sql = "SELECT SUM(CASE WHEN t2.tipo_maquina = 3 AND t1.cantidad_etiquetas != 0 THEN t1.ml_empleado WHEN t2.tipo_maquina !=3 THEN t1.ml_empleado END)as total_ml FROM desperdicio_op t1 INNER JOIN maquinas t2 ON t1.maquina = t2.id_maquina WHERE id_persona = $id_persona AND SUBSTRING_INDEX(fecha_crea,' ',1) >='$fecha_desde' AND SUBSTRING_INDEX(fecha_crea,' ',1)<='$fecha_desde'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function detalles_productividad($id_persona, $fecha_desde, $fecha_hasta)
    {
        $sql = "SELECT t1.*,t2.nombre_maquina,t2.tipo_maquina,t3.turno_hora,t3.horario_turno, DAY(t1.fecha_crea) AS dia,SUM(CASE WHEN t2.tipo_maquina = 3 AND t1.cantidad_etiquetas != 0 THEN t1.ml_empleado WHEN t2.tipo_maquina !=3 THEN t1.ml_empleado END) AS total_dia,t4.nombres,t4.apellidos 
        FROM desperdicio_op t1 
        INNER JOIN maquinas t2 ON t1.maquina=t2.id_maquina 
        LEFT JOIN programacion_operario t3 ON SUBSTRING_INDEX(t1.fecha_crea,' ',1)=t3.fecha_program AND t1.id_persona=t3.id_persona
        INNER JOIN persona t4 ON t4.id_persona=t1.id_persona
        WHERE t1.id_persona=$id_persona AND SUBSTRING_INDEX(t1.fecha_crea,' ',1) >='$fecha_desde' AND SUBSTRING_INDEX(t1.fecha_crea,' ',1)<='$fecha_hasta'
        GROUP BY dia,t1.maquina";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
}

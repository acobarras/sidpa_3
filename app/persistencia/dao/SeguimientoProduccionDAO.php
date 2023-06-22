<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;

/**
 * Description of seguimiento_produccionDAO
 *
 * @author erios
 */
class SeguimientoProduccionDAO extends GenericoDAO
{

    //put your code here
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'seguimiento_produccion');
    }

    public function consultar_seguimiento_produccion()
    {
        $sql = "SELECT * FROM seguimiento_produccion";

        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    public function consultar_seguimiento_op($num_produccion)
    {
        $sql = "SELECT t4.nombres,t4.apellidos,t0.num_produccion,t3.nombre_maquina,t0.observacion_op,t0.fecha_crea,t0.hora_crea,t1.nombre_area_trabajo,t2.nombre_actividad_area
                 FROM seguimiento_produccion t0
                 INNER JOIN area_trabajo t1 ON t0.id_area = t1.id_area_trabajo
                    INNER JOIN persona t4 ON t0.id_persona = t4.id_persona
                    INNER JOIN actividad_area t2 ON t0.id_actividad = t2.id_actividad_area
                    INNER JOIN maquinas t3 ON t0.id_maquina=t3.id_maquina
                             WHERE num_produccion= '$num_produccion'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_seguimiento_fecha($fecha_desde, $fecha_hasta, $actividad)
    {
        $sql = "SELECT t1.*, t2.nombre_actividad_area, t3.nombres FROM seguimiento_produccion t1 
                    INNER JOIN actividad_area t2 ON t1.id_actividad = t2.id_actividad_area 
                        INNER JOIN persona t3 ON t1.id_persona = t3.id_persona 
                            WHERE t1.fecha_crea >= '$fecha_desde' AND t1.fecha_crea <= '$fecha_hasta' AND t2.nombre_actividad_area = '$actividad'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function SeguimienrtoIndicadorProductividad($fecha_desde, $fecha_hasta,$id_actividades)
    {
        $sql = "SELECT t1.*, t2.nombres, t2.apellidos 
            FROM seguimiento_produccion t1 
            INNER JOIN persona t2 ON t1.id_persona = t2.id_persona 
            WHERE t1.fecha_crea >= '$fecha_desde' 
            AND t1.fecha_crea <= '$fecha_hasta' 
            AND t1.id_actividad 
            IN($id_actividades) GROUP BY t1.num_produccion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function ConsultaOpEntregadasContabilidad($fecha_desde, $fecha_hasta)
    {
        $sql = "SELECT t1.id_maquina, t1.num_produccion, t1.fecha_crea AS fecha_crea_actividad, t1.hora_crea, t2.nombre_actividad_area, t3.*
            FROM seguimiento_produccion t1 
            INNER JOIN actividad_area t2 ON t1.id_actividad = t2.id_actividad_area
            INNER JOIN item_producir t3 ON t1.num_produccion = t3.num_produccion
            WHERE t1.fecha_crea >= '$fecha_desde' AND t1.fecha_crea <= '$fecha_hasta' 
            AND id_actividad IN (15,48) GROUP BY t1.num_produccion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

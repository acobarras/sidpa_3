<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class ProgramacionOperarioDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'programacion_operario');
    }

    public function consultar_programacion_opeario()
    {

        $sql = "SELECT * FROM programacion_operario";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    public function consultar_horario_fecha($horario, $fecha, $maquina)
    {

        $sql = "SELECT * FROM programacion_operario t1
                    INNER JOIN maquinas t2 ON t1.id_maquina= t2.id_maquina
                 WHERE t1.horario_turno='" . $horario . "' AND t1.fecha_program='" . $fecha . "'  AND t1.id_maquina=$maquina ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    public function consultar_turno_operario($id_operario)
    {

        $sql = "SELECT * FROM programacion_operario t1
                    INNER JOIN maquinas t2 ON t1.id_maquina = t2.id_maquina
                        WHERE t1.id_persona=$id_operario AND t1.horario_turno !='Compensatorio'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    public function consultar_fechas_operarios($data)
    {
        $sql = "SELECT t1.*,t2.nombres,t2.apellidos,t3.nombre_maquina FROM programacion_operario t1 
        INNER JOIN persona t2 ON t1.id_persona=t2.id_persona		
            INNER JOIN maquinas t3 ON t1.id_maquina= t3.id_maquina
            WHERE t1.fecha_program >= '" . $data['fecha_inicio'] . "' AND t1.fecha_program <= '" . $data['fecha_final'] . "' ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    public function ConsultaOperarioMaquina($fecha_desde,$fecha_hasta)
    {
        $sql = "SELECT t1.id_persona, t1.id_maquina, t2.nombres, t2.apellidos, t3.nombre_maquina 
            FROM programacion_operario t1
            INNER JOIN persona t2 ON t1.id_persona = t2.id_persona
            INNER JOIN maquinas t3 ON t1.id_maquina = t3.id_maquina
            WHERE t1.fecha_program >= '$fecha_desde' AND t1.fecha_program <= '$fecha_hasta'
            GROUP BY t1.id_persona, t1.id_maquina";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;

    }
    
    public function ConsultaHoras($fecha_desde,$fecha_hasta,$id_persona,$id_maquina)
    {
        $sql = "SELECT SUM(turno_hora) AS total_horas FROM programacion_operario 
            WHERE fecha_program >= '$fecha_desde' AND fecha_program <= '$fecha_hasta' 
            AND id_persona = $id_persona AND id_maquina = $id_maquina ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;

    }

    public function HorasRangoTotal($fecha_desde,$fecha_hasta)
    {
        $sql = "SELECT SUM(turno_hora) horas_total 
            FROM programacion_operario 
            WHERE fecha_program >= '$fecha_desde' AND fecha_program <= '$fecha_hasta'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;    
    }
    
    public function HorasMaquinaTotal($fecha_desde,$fecha_hasta,$id_maquina)
    {
        $sql = "SELECT t1.*, t2.nombres, t2.apellidos 
            FROM programacion_operario t1 
            INNER JOIN persona t2 ON t1.id_persona = t2.id_persona 
            WHERE t1.fecha_program >= '$fecha_desde' AND t1.fecha_program <= '$fecha_hasta' AND t1.id_maquina = $id_maquina";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;    
    }

    public function ConsultaPersonaFecha($id_persona,$fecha_program)
    {
        $sql = "SELECT * FROM programacion_operario WHERE id_persona = $id_persona AND fecha_program = '$fecha_program'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function horas_productividad($id_persona,$fecha)
    {
        $sql = "SELECT SUM(turno_hora)as total_horas FROM `programacion_operario` WHERE id_persona=$id_persona AND fecha_program LIKE '%".$fecha."%'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function detalle_horas_productividad($id_persona,$fecha)
    {
        $sql = "SELECT * FROM `programacion_operario` WHERE id_persona=$id_persona AND fecha_program LIKE '%".$fecha."%'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

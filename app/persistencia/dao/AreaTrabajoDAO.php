<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class AreaTrabajoDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'area_trabajo');
    }

    public function consultar_area_trabajo()
    {
        $sql = "SELECT * FROM area_trabajo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_area_sistema()
    {
        $sql = "SELECT t1.id_area_trabajo,t1.nombre_area_trabajo,t1.cant_personas_res 
        FROM area_trabajo t1 
        INNER JOIN persona t2 ON t2.id_area_trabajo=t1.id_area_trabajo 
        INNER JOIN usuarios t3 ON t3.id_persona=t2.id_persona 
        WHERE t1.estado_area_trabajo=2 AND t3.res_prioridad=1 GROUP BY t1.id_area_trabajo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_areas()
    {
        $sql = "SELECT t1.id_area_trabajo,t1.nombre_area_trabajo,t1.cant_personas_res 
        FROM area_trabajo t1 
        WHERE t1.estado_area_trabajo=2";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function responde_prio($id_area_trabajo)
    {
        $sql = "SELECT t3.id_usuario,t3.nombre,t3.apellido FROM area_trabajo t1 
        INNER JOIN persona t2 ON t2.id_area_trabajo=t1.id_area_trabajo 
        INNER JOIN usuarios t3 ON t3.id_persona=t2.id_persona 
        WHERE t1.id_area_trabajo=$id_area_trabajo AND t3.res_prioridad=1";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

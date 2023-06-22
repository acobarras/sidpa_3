<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class ActividadAreaDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'actividad_area');
    }

    public function consultar_actividad_area_trabajo($trabajo)
    {
        $sql = "SELECT * FROM actividad_area Where id_area_trabajo =" . $trabajo;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_actividad_area()
    {
        $sql = "SELECT t1.*, t2.nombre_area_trabajo FROM actividad_area t1 
            INNER JOIN area_trabajo t2 ON t1.id_area_trabajo = t2.id_area_trabajo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_id_actividad_area($id_actividad)
    {
        $sql = "SELECT * FROM actividad_area t1 
            INNER JOIN area_trabajo t2 ON t1.id_area_trabajo = t2.id_area_trabajo
            WHERE t1.id_actividad_area='$id_actividad'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function NombreActivida()
    {
        $sql = "SELECT DISTINCT(nombre_actividad_area) FROM actividad_area";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

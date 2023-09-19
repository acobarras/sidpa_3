<?php

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class VehiculosDAO extends GenericoDAO
{
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'vehiculos');
    }
    public function consultar_vehiculos()
    {
        $sql = 'SELECT t1.*,t2.nombre,t2.apellido FROM vehiculos t1
        INNER JOIN usuarios t2 ON t1.id_usuario=t2.id_usuario';
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_usu_vehiculos($condi)
    {
        $sql = "SELECT * FROM `vehiculos` $condi";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_todos_vehiculos()
    {
        $sql = "SELECT t1.*,t2.nombre,t2.apellido FROM vehiculos t1
        INNER JOIN usuarios t2 ON  t1.id_usuario=t2.id_usuario";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

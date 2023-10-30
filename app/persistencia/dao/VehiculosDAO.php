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
    public function consultar_chequeos($id_user)
    {
        $sql = "SELECT t1.nombre,t1.apellido,t2.*,t3.id_chequeo,t3.fecha_crea AS fecha_chequeo FROM usuarios t1 
        INNER JOIN vehiculos t2 ON t2.id_usuario=t1.id_usuario 
        INNER JOIN chequeo_vehicular t3 ON t2.id_vehiculo=t3.id_vehiculo 
        WHERE t1.id_roll=11 AND t1.id_usuario=$id_user AND t3.id_chequeo=(SELECT MAX(chequeo_vehicular.id_chequeo) FROM chequeo_vehicular 
        WHERE t3.id_vehiculo=chequeo_vehicular.id_vehiculo);";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

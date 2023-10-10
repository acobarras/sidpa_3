<?php

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;


class ubicacionesDAO extends GenericoDAO
{
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'ubicaciones');
    }
    /**
     * 
     * @return type
     */

    public function tabla_ubicaciones()
    {
        $sql = "SELECT * FROM ubicaciones";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function tipo_producto_ubicaciones($tipo)
    {
        $sql = "SELECT * FROM ubicaciones WHERE tipo_producto='$tipo' AND estado!=2";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function valida_ubicacion($nombre_ubicacion)
    {
        $sql = "SELECT * FROM ubicaciones WHERE nombre_ubicacion='$nombre_ubicacion'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function ubicacion_despacho()
    {
        $sql = "SELECT * FROM ubicaciones WHERE estado=2";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

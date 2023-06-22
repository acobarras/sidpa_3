<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;
use MiApp\persistencia\vo\PaisVO;


class CiudadDAO extends GenericoDAO
{


    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'ciudad');
    }


    public function consultar_ciudad()
    {
        $sql = "SELECT t1.id_ciudad, t1.nombre, t2.nombre AS nombre_departamento, t2.id_departamento
            FROM ciudad t1 
            INNER JOIN departamento t2 ON t1.id_departamento = t2.id_departamento ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_ciudad_especifica()
    {
        $sql = "select * from ciudad  where id_departamento=" . $_POST['ciudad'];
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }
}

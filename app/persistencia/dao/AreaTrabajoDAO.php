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
        $sql = "SELECT * FROM area_trabajo WHERE estado_area_trabajo=2";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

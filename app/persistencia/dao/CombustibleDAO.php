<?php

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;
use PDO;

class CombustibleDAO extends GenericoDAO
{
    public function __construct($cnn)
    {
        parent::__construct($cnn, 'combustible');
    }

    public function consulta_combusti($id_user)
    {
        $sql = "SELECT t1.*,t2.nombre,t2.apellido FROM combustible t1 
        INNER JOIN usuarios t2 ON t2.id_usuario=t1.id_user 
        WHERE t1.id_user=$id_user";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consulta_ultimo_combusti($id_user)
    {
        $sql = "SELECT * FROM combustible WHERE id_combustible = (SELECT MAX(id_combustible) FROM combustible)AND id_user=$id_user";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

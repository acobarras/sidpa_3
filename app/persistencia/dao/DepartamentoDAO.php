<?php

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class DepartamentoDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'departamento');
    }

    public function consultar_departamento()
    {
        $sql = "SELECT t1.id_departamento, t1.nombre, t2.nombre AS nombre_pais, t2.id_pais 
            FROM departamento t1 
            INNER JOIN pais t2 ON t1.id_pais = t2.id_pais";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_departamento_especifico()
    {
        $sql = "select * from departamento where id_pais = " . $_POST['departamento'] . "";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

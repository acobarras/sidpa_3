<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class coreDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, '');
    }

    public function consultar_core() {
        $sql = "SELECT * FROM core";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_core_id($id_core)
    {
        $sql = "SELECT * FROM core WHERE id_core = $id_core";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        $res = $resultado[0]->nombre_core;
        return $res;
    }

}

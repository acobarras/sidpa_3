<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class RollDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, '');
    }

    public function consultar_roll() {

        $sql = "select * from roll";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

}

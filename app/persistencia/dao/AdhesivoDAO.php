<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class AdhesivoDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, 'adhesivo');
    }

    public function consultar_adhesivo() {
        $sql = "SELECT * FROM adhesivo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function validar_adhesivo($codigo)
    {
        $sql = "SELECT * FROM adhesivo WHERE codigo_adh = '$codigo'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

}

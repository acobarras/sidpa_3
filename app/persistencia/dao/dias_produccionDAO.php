<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class dias_produccionDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, '');
    }

    public function consultar_dias_produccion() {

        $sql = "SELECT * FROM dias_produccion ORDER BY id_dia DESC";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    // public function insertar_dias($param) {
    //     $tabla = "dias_produccion";
    //     $nombres = array_keys($param);
    //     $valor = $param;
    //     $sql = insertar_generico::insert($tabla, $nombres, $valor);
    //     $sentencia = $this->cnn->prepare($sql);
    //     $sentencia->execute();

    //     return $this->cnn->lastInsertId();
    // }


}

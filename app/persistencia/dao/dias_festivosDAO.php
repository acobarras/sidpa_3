<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class dias_festivosDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, 'dias_festivo');
    }

    public function consultar_dias_festivos() {


        $sql = "SELECT * FROM dias_festivos";

        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    public function insertar_festivos() {
        // $tabla = "core";
        // $nombres = array_keys($_POST);
        // $valor = $_POST;
        // $sql = insertar_generico::insert($tabla, $nombres, $valor);
        // $sentencia = $this->cnn->prepare($sql);
        // $resultado = $sentencia->execute();

        // return $resultado;
    }


}

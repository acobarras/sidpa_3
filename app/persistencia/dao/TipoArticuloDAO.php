<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class TipoArticuloDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, 'tipo_articulo');
    }

    public function consultar_tipo_articulo() {


        $sql = "SELECT * FROM tipo_articulo";

        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }
     public function consultar_articulo_tecnologia() {


        $sql = "SELECT * FROM tipo_articulo where id_clase_articulo = 3";

        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

}

<?php

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;
use PDO;

class TipoDocumentoDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, 'tipo_documento');
    }

    public function consultar_tipo_documento() {

        $sql = 'SELECT * FROM tipo_documento';
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

}

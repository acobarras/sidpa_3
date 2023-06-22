<?php

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;
use MiApp\persistencia\vo\PaisVO;

class PaisDAO extends GenericoDAO {

    private $PaisVO;

    public function __construct(&$cnn) {
        parent::__construct($cnn, 'pais');
        $this->PaisVO = new PaisVO($cnn);
    }

    public function consultar_pais() {
        $sql = "SELECT * FROM pais";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

 

}

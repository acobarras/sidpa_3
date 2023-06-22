<?php

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class FormaMaterialDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, 'forma_material');
    }

    public function consultar_forma_material() {
        $sql = "SELECT * FROM forma_material";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_id_forma($id_forma)
    {
        $sql = "SELECT * FROM forma_material WHERE id_forma = $id_forma";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

}

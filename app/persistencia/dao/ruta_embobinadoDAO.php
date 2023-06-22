<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class ruta_embobinadoDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, '');
    }

    public function consultar_ruta_embobinado() {
        $sql = "SELECT * FROM ruta_embobinado";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_ruta_embobinado_id($id_embobinado)
    {
        $sql = "SELECT * FROM ruta_embobinado WHERE id_ruta_embobinado = $id_embobinado";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        $res = $resultado[0]->nombre_r_embobinado;
        return $res;
    }

}

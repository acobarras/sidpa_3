<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class TrazPedidoDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, '');
    }

    public function consultar_traz_pedido() {


        $sql = "SELECT * FROM traz_pedido";

        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }
    public function consultar_traz_pedido_id($id) {

        $sql = "SELECT * FROM traz_pedido WHERE id_pedido = '$id' ORDER BY id_traz DESC";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

}

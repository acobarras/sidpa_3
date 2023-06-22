<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class EstadoItemPedidoDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, 'estado_item_pedido');
    }

    public function consultar_estados_items() {

        $sql = "SELECT * FROM estado_item_pedido";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

}

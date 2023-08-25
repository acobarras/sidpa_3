<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class PrioridadesDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'prioridades_produccion');
    }

    public function consultar_datos_op($num_produccion)
    {
        $sql = "SELECT t1.num_produccion,t1.cant_op,t1.fecha_comp ,t2.item,t3.num_pedido,t2.codigo,t2.Cant_solicitada FROM item_producir t1 
        INNER JOIN pedidos_item t2 on t2.n_produccion=t1.num_produccion 
        INNER JOIN pedidos t3 ON t3.id_pedido=t2.id_pedido 
        WHERE t1.num_produccion=$num_produccion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consulta_prioridad($condicion)
    {
        $sql = "SELECT t1.*,t2.* FROM prioridades_produccion t1
        INNER JOIN estado_prioridad t2 ON t2.id_estado_prioridad=t1.estado
        $condicion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
}

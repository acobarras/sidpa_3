<?php

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;
use PDO;

class SoporteItemDAO extends GenericoDAO
{
    public function __construct($cnn)
    {
        parent::__construct($cnn, 'diagnostico_item');
    }
    public function consultar_item($estado, $estado_item)
    {
        $sql = "SELECT t1.id_diagnostico_item,t1.item,t1.num_consecutivo,t1.id_diagnostico,t1.equipo,t1.serial_equipo,t1.procedimiento,t1.accesorios, t1.estado AS estado_item, t2.estado AS estado_diag, t3.nombre_empresa,t4.nombre_estado_soporte 
        FROM diagnostico_item t1 
        INNER JOIN diagnostico_soporte_tecnico t2 ON t2.id_diagnostico=t1.id_diagnostico 
        INNER JOIN cliente_proveedor t3 ON t3.id_cli_prov=t1.id_cli_prov 
        INNER JOIN estado_soporte t4 ON t4.id_estado_soporte=t2.estado 
        WHERE t1.estado IN($estado_item) AND t2.estado IN($estado)
        AND t1.num_consecutivo=t2.num_consecutivo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_repuestos($estado_cotiza, $estado_item)
    {
        $sql = "SELECT t1.*,t1.estado AS estado_item,t2.*,t2.estado AS estado_cotizacion,t4.nombre_empresa,t2.item AS item_cotiza,t5.nombre_estado AS nombre_estado_item,t6.*
         FROM diagnostico_item t1 
         INNER JOIN cotizacion_item_soporte t2 ON t2.id_diagnostico=t1.id_diagnostico 
         INNER JOIN cliente_proveedor t4 ON t4.id_cli_prov=t1.id_cli_prov 
         INNER JOIN estados_item_soporte t5 ON t5.id_estado_item = t2.estado 
         INNER JOIN productos t6 ON t6.id_productos=t2.id_producto 
         WHERE t2.estado in($estado_cotiza) AND t1.item=t2.item AND t1.estado in($estado_item)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_aprobacion($estado_item, $sentencia)
    {
        $sql = "SELECT t1.*, t1.estado AS estado_item,t2.nombre_empresa,t3.nombre_estado_soporte, t4.estado AS estado_diagnostico
        FROM diagnostico_item t1 
        INNER JOIN diagnostico_soporte_tecnico t4 ON t1.id_diagnostico = t4.id_diagnostico
        INNER JOIN cliente_proveedor t2 ON t2.id_cli_prov=t1.id_cli_prov 
        INNER JOIN estado_soporte t3 ON t1.estado=t3.id_estado_soporte WHERE t1.estado in($estado_item) $sentencia";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_repuestos_item($id_diagnostico, $item, $sentencia)
    {
        $sql = "SELECT t1.*,estado AS estado_cotiza,t2.*,t3.nombre_estado 
        FROM cotizacion_item_soporte t1 
        INNER JOIN productos t2 ON t2.id_productos=t1.id_producto 
        INNER JOIN estados_item_soporte t3 ON t3.id_estado_item=t1.estado 
        WHERE id_diagnostico=$id_diagnostico $sentencia AND item=$item";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function cantidad_items($id_diagnostico, $num_consecutivo)
    {
        $sql = "SELECT COUNT(t1.item) AS cantidad_items FROM diagnostico_item t1 
        WHERE id_diagnostico=$id_diagnostico AND t1.num_consecutivo='$num_consecutivo'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_tecno()
    {
        $sql = "SELECT t1.codigo_producto,t1.id_productos,t1.tamano,t1.descripcion_productos,t2.nombre_articulo FROM productos t1 
        INNER JOIN tipo_articulo t2 ON t1.id_tipo_articulo=t2.id_tipo_articulo 
        WHERE t2.id_clase_articulo=3 && t1.estado_producto !=0";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_items_diag($id_diagnostico, $item)
    {
        $sql = "SELECT * FROM `cotizacion_item_soporte` 
        WHERE id_diagnostico=$id_diagnostico AND item=$item";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

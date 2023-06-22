<?php

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;
use PDO;

class CotizacionItemSoporteDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'cotizacion_item_soporte');
    }

    public function consulta_cotiza($num_cotizacion, $estado)
    {
        if ($estado == 1) {
            $tabla = ',t9.*';
            $condicion = 'INNER JOIN diagnostico_item t9 ON t9.id_diagnostico=t2.id_diagnostico';
        } else {
            $tabla = '';
            $condicion = '';
        }
        $sql = "SELECT t1.*,t2.num_consecutivo,t3.nombre_empresa,t5.contacto,t5.cargo,t6.nombre AS nombre_pais,t7.nombre AS nombre_departa,t8.nombre AS nombre_ciudad $tabla
        FROM cotizacion_item_soporte t1
        INNER JOIN diagnostico_soporte_tecnico t2 ON t1.id_diagnostico=t2.id_diagnostico 
        INNER JOIN cliente_proveedor t3 ON t3.id_cli_prov=t2.id_cli_prov 
        INNER JOIN direccion t5 ON t5.id_direccion=t2.id_direccion 
        INNER JOIN pais t6 ON t5.id_pais= t6.id_pais 
        INNER JOIN departamento t7 ON t7.id_departamento=t5.id_departamento 
        INNER JOIN ciudad t8 ON t8.id_ciudad =t5.id_ciudad 
        $condicion
        WHERE t1.num_cotizacion=$num_cotizacion GROUP BY t1.item";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_item_cotiza($item)
    {
        $sql = "SELECT t1.id_diagnostico,t1.item,t1.num_consecutivo,t1.equipo,t1.serial_equipo,t1.procedimiento,t1.accesorios
        FROM diagnostico_item t1 
        INNER JOIN cotizacion_item_soporte t2 ON t2.id_diagnostico=t1.id_diagnostico
        WHERE t1.item=$item AND t1.item=t2.item GROUP BY t1.item";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_acta_entrega($num_acta)
    {
        $sql = "SELECT t1.id_diagnostico,t1.item,t2.item AS item_cotizacion,t2.valor AS valor_cotizacion,t2.cantidad AS cantidad_cotizacion,
        t2.estado AS estado_cotiza,t2.num_cotizacion,t2.id_producto,t1.num_consecutivo,t1.equipo,t1.serial_equipo,t1.procedimiento,
        t1.accesorios,t1.firma_cli,t3.nombre_empresa,t5.contacto,t5.cargo,t6.nombre AS nombre_pais,t7.nombre AS nombre_departa,
        t8.nombre AS nombre_ciudad 
        FROM diagnostico_item t1 
        INNER JOIN cotizacion_item_soporte t2 ON t2.id_diagnostico=t1.id_diagnostico
        INNER JOIN cliente_proveedor t3 ON t3.id_cli_prov=t1.id_cli_prov
        INNER JOIN diagnostico_soporte_tecnico t4 ON t2.id_diagnostico=t4.id_diagnostico
        INNER JOIN direccion t5 ON t4.id_direccion=t5.id_direccion
        INNER JOIN pais t6 ON t5.id_pais= t6.id_pais
        INNER JOIN departamento t7 ON t7.id_departamento=t5.id_departamento
        INNER JOIN ciudad t8 ON t8.id_ciudad =t5.id_ciudad 
        WHERE t2.num_acta='$num_acta' AND t1.item=t2.item GROUP BY t2.item";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consulta_repuesto($num_cotizacion, $item, $estado)
    {
        if ($estado == 1) {
            $sql = "SELECT * FROM cotizacion_item_soporte 
            WHERE num_cotizacion=$num_cotizacion AND item=$item AND estado=2";
        } else {
            $sql = "SELECT t1.id_producto,t1.valor_cotiza_visita AS valor,t1.cobro_ser AS moneda,t1.num_cotizacion,t1.num_consecutivo
            FROM diagnostico_soporte_tecnico t1
            INNER JOIN productos t2 ON t2.id_productos=t1.id_producto
            WHERE t1.id_diagnostico=$item";
        }
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_datos($id_diagnostico, $item)
    {
        $sql = "SELECT * FROM `cotizacion_item_soporte`
        WHERE id_diagnostico=$id_diagnostico AND item = $item";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

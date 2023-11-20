<?php

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class EmpresasDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'empresas');
    }

    public function consultar_empresas()
    {
        $sql = "SELECT * FROM empresas";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_empresa_id($id_empresa)
    {
        $sql = "SELECT t1.*, 
        t2.numero_guardado AS num_cons_pedido, t2.img_cabecera AS img_cabeza_pedido, t2.img_pie AS img_pie_pedido, t2.img_lateral AS img_lateral_pedido, 
        t3.numero_guardado AS num_cons_op, t3.img_cabecera AS img_cabeza_op, t3.img_pie AS img_pie_op, t3.img_lateral AS img_lateral_op, 
        t4.numero_guardado AS num_cons_cc, t4.img_cabecera AS img_cabeza_cc, t4.img_pie AS img_pie_cc, t4.img_lateral AS img_lateral_cc 
        FROM empresas t1 
        LEFT JOIN cons_cotizacion t2 ON t1.id_cons_pedido = t2.id_consecutivo 
        LEFT JOIN cons_cotizacion t3 ON t1.id_cons_op = t3.id_consecutivo 
        LEFT JOIN cons_cotizacion t4 ON t1.id_cons_cc = t4.id_consecutivo 
            WHERE t1.id_empresa = $id_empresa";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function empresa_portafolio()
    {
        $sql = "SELECT * FROM `empresas` WHERE req_portafolio=1;";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

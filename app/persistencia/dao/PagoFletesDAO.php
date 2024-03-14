<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class PagoFletesDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, 'pago_fletes');
    }

    public function ruta_adicional_transportador($id_transportador)
    {
        $sql = "SELECT * FROM pago_fletes WHERE id_transportador IN ($id_transportador) AND estado IN(2)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    
    public function confirmar_pago_flete($estado)
    {
        $sql = "SELECT t1.*, t2.nombres, t2.apellidos 
            FROM pago_fletes t1 
            INNER JOIN persona t2 ON t1.id_transportador = t2.id_persona 
            WHERE t1.estado IN($estado)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_flete_transportador($id_transportador, $fecha_desde, $fecha_hasta)
    {
        $sql = "SELECT t1.*, t2.nombres, t2.apellidos 
            FROM pago_fletes t1 
            INNER JOIN persona t2 ON t1.id_transportador = t2.id_persona 
            WHERE t1.id_transportador = $id_transportador AND t1.fecha_cargue >= '$fecha_desde' AND t1.fecha_cargue <= '$fecha_hasta'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }


}

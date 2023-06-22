<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class SeguimientoPqrDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, 'seguimiento_pqr');
    }

    public function consultar_seguimiento() {
        $sql = "SELECT * FROM seguimiento_pqr";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_seguimiento_pqr($num_pqr)
    {
        $sql = "SELECT t1.num_pedido_cambio, t2.fecha_crea AS fecha_seguimiento, t3.nombre_actividad_area, t5.nombres, t5.apellidos
            FROM gestion_pqr t1 
            INNER JOIN seguimiento_pqr t2 ON t1.id_pqr = t2.id_pqr 
            INNER JOIN actividad_area t3 ON t2.id_actividad_area = t3.id_actividad_area 
            INNER JOIN usuarios t4 ON t2.id_usuario = t4.id_usuario
            INNER JOIN persona t5 ON t4.id_persona = t5.id_persona
            WHERE t1.num_pqr = '$num_pqr'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    
}

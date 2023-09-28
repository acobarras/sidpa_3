<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class ChequeoVehiculoDAO extends GenericoDAO
{
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'chequeo_vehicular');
    }

    public function consultar_chequeo($id_chequeo)
    {
        $sql = "SELECT t1.*,t2.*,t3.nombre, t3.apellido,t4.nombre AS nombre_rev, t4.apellido AS apellido_rev FROM chequeo_vehicular t1 
        INNER JOIN vehiculos t2 ON t1.id_vehiculo=t2.id_vehiculo 
        INNER JOIN usuarios t3 ON t2.id_usuario=t3.id_usuario 
        INNER JOIN usuarios t4 ON t1.id_user_chequeo=t4.id_usuario WHERE id_chequeo=$id_chequeo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

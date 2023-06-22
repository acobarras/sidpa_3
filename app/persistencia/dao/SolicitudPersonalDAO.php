<?php
namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;
use PDO;

class SolicitudPersonalDAO extends GenericoDAO
{
    public function __construct($cnn)
    {
        parent::__construct($cnn, 'solicitud_personal');
    }

    public function ConsultaSolicitudPersonal($estado)
    {
        $sql = "SELECT t1.*, t2.*, t3.nombre_estado  FROM solicitud_personal t1 
            INNER JOIN perfil_cargo t2 ON t1.id_perfil_cargo = t2.id_perfil 
            INNER JOIN estados_solicitud_personal t3 ON t1.estado = t3.id_estado
            WHERE t1.estado = '$estado'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    
    public function SolicitudPersonal()
    {
        $sql = "SELECT t1.*, t2.*, t3.nombre_estado  FROM solicitud_personal t1 
            INNER JOIN perfil_cargo t2 ON t1.id_perfil_cargo = t2.id_perfil 
            INNER JOIN estados_solicitud_personal t3 ON t1.estado = t3.id_estado";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    
}

<?php
namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;
use PDO;

class DescargosDAO extends GenericoDAO
{
    public function __construct($cnn)
    {
        parent::__construct($cnn, 'descargos');
    }

    public function ConsultaSolicitudDescargos($estado)
    {
        $sql = "SELECT t1.*, t2.nombre_estado FROM descargos t1 
            INNER JOIN estados_descargos t2 ON t1.estado = t2.id_estado_descargo 
            WHERE t1.estado IN ($estado)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    
}

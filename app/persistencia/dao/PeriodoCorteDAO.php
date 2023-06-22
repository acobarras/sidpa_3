<?php
namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;
use PDO;

class PeriodoCorteDAO extends GenericoDAO
{
    public function __construct($cnn)
    {
        parent::__construct($cnn, 'periodo_corte');
    }

    public function ConsultaPeriodoCorte()
    {
        $sql = "SELECT * FROM periodo_corte";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    
    public function ConsultaPeriodoId($id_periodo)
    {
        $sql = "SELECT * FROM periodo_corte WHERE id = $id_periodo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    
}

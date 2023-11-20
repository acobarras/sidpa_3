<?php

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;

final class impresora_tamanoDAO extends GenericoDAO 
{
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'impresora_tamano');
    }

    public function consulta_tamano_impresion()
    {
        $sql = "SELECT * FROM `impresora_tamano`";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class ArticuloDAO extends GenericoDAO
{
    
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'tipo_articulo');
    }
    
    public function consultar_articulo()
    {
        $sql = "SELECT t1.*, t2.nombre_clase_articulo FROM tipo_articulo t1 
            INNER JOIN clase_articulo t2 ON t1.id_clase_articulo = t2.id_clase_articulo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    
    public function validar_articulo($nombre_articulo)
    {
        $sql = "SELECT * FROM tipo_articulo WHERE nombre_articulo = '$nombre_articulo'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

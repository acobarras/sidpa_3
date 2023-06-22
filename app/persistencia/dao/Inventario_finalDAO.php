<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class Inventario_finalDAO extends GenericoDAO
{
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'inventario_final');
    }
    /**
     * 
     * @return type
     */
    public function validar_conteo($ubicacion)
    {
        $sql = "SELECT * FROM inventario_final WHERE ubicacion= '$ubicacion'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }
    public function verificacion_conteo($ubicacion, $id_productos)
    {
        $sql = "SELECT * FROM inventario_final WHERE ubicacion= '$ubicacion' AND id_productos='$id_productos'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consulta_tabla_final()
    {
        $sql = "SELECT * FROM inventario_final WHERE estado!=4";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

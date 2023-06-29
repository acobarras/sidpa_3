<?php

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;


class ClaseArticuloDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'clase_articuloDAO');
    }


    public function consultar_clase_articulo()
    {
        foreach (PERMISO_VENTA_BOBINA as $key=>$value) {
            if ($key == $_SESSION['usuario']->getId_usuario()||$_SESSION['usuario']->getId_roll()==1) {
                $sql = "SELECT * FROM clase_articulo";
            }else{
                $sql = "SELECT * FROM clase_articulo WHERE id_clase_articulo!=1";
            }
        }
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    
    public function consulta_clase()
    {
        $sql = "SELECT * FROM clase_articulo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_clase_articulo()
    {
        $sql = "SELECT * FROM clase_articulo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

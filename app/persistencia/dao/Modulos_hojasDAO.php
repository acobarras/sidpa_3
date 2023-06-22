<?php

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;
use MiApp\persistencia\vo\Modulos_hojasVO;


class Modulos_hojasDAO extends GenericoDAO
{

    private $modulos_hojasVO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, '');
        $this->modulos_hojasVO = new Modulos_hojasVO($cnn);
    }


    public function Consultar_modulo_hojas($parametro)
    {

        $sql = "SELECT * FROM modulo_hoja WHERE nombre_hoja = '$parametro' ORDER BY posicion ASC";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    
    public function consultar_hojas_url($url)
    {
        $sql = "SELECT * FROM modulo_hoja WHERE url = '$url'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function todas_hojas()
    {
        $sql = "SELECT * FROM modulo_hoja  WHERE estado = 1 ORDER BY posicion ASC ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function todas_hojas_especifica()
    {
        $sql = "SELECT t1.* FROM modulo_hoja t1 
                INNER JOIN permisos t2 ON t1.id_hoja = t2.id_modulo_hoja 
                INNER JOIN usuarios t3 ON t2.id_usuario = t3.id_usuario
                WHERE t1.id_hoja =t2.id_modulo_hoja
                AND t2.estado_permisos = 1
                AND t3.id_usuario='" . $_SESSION['usuario']->getId_usuario() . "' 
                ORDER BY t1.posicion ASC ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
}

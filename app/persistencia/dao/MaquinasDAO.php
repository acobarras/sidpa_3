<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;

/**
 * Description of maquinasDAO
 *
 * @author erios
 */
class MaquinasDAO extends GenericoDAO
{

    //put your code here
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'maquinas');
    }

    public function consultar_maquinas()
    {
        $sql = "SELECT * FROM maquinas";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_maquinas_produccion()
    {
        $sql = "SELECT * FROM maquinas WHERE tipo_maquina =1 ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_maquinas_embobinado($maquinas)
    {
        $sql = "SELECT * FROM maquinas WHERE tipo_maquina in ($maquinas) ORDER BY tipo_maquina ASC";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    
    public function consultar_maquinas_proembo()
    {
        $sql = "SELECT * FROM maquinas WHERE tipo_maquina in (3) ORDER BY tipo_maquina ASC";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_maquina_id($id_maquina)
    {
        $sql = "SELECT * FROM maquinas WHERE id_maquina = '$id_maquina' ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
}

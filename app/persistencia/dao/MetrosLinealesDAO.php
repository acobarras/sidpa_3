<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;


class MetrosLinealesDAO extends GenericoDAO
{

    //put your code here
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'metros_lineales');
    }

    public function consultar_metros_lineales()
    {
        $sql = "SELECT * FROM metros_lineales";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_metros_lineales_especificos($id)
    {
        $sql = "SELECT *,sum(metros_lineales)AS metros_lineales_dispo,sum(ml_usados)AS suma_ml 
            FROM metros_lineales 
            WHERE id_item_producir=$id GROUP BY ancho";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function DatosOp($id_item_producir)
    {
        $sql = "SELECT * FROM metros_lineales WHERE id_item_producir = '$id_item_producir'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function MetrosLinealesRetorno($id_item_producir, $estado_ml)
    {
        if ($estado_ml == 2) {
            $sql = "SELECT SUM(ml_usados) AS suma_ml FROM metros_lineales WHERE id_item_producir = '$id_item_producir' AND estado_ml = 2";
        } else {
            $sql = "SELECT SUM(metros_lineales) AS suma_ml FROM metros_lineales WHERE id_item_producir = '$id_item_producir' AND estado_ml = 1";
        }
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function empleados_ml_op($n_produccion)
    {
        $sql = "SELECT SUM(t1.ml_usados) AS ml_usados, t1.id_persona, t1.id_item_producir, t2.nombres, t2.apellidos FROM metros_lineales t1 
            INNER JOIN persona t2 ON t1.id_persona = t2.id_persona
            WHERE t1.id_item_producir = $n_produccion AND t1.estado_ml = 1 AND metros_lineales = 0 GROUP BY t1.id_persona";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function metros_lineales_operario_op($id_item_producir)
    {
        $sql = "SELECT t1.id_persona, t1.ancho, t1.codigo_material, SUM(t1.ml_usados) AS ml_usados, 
        ((t1.ancho)*(SUM(t1.ml_usados))/1000) AS m2_item, t2.nombres, t2.apellidos 
        FROM metros_lineales t1 INNER JOIN persona t2 ON t1.id_persona = t2.id_persona
        WHERE t1.id_item_producir = $id_item_producir AND t1.estado_ml = 1 AND t1.ml_usados != 0 GROUP BY t1.ancho, t1.id_persona";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function metros_lineales_maquina($id_item_producir)
    {
        $sql = "SELECT t1.ancho, t1.codigo_material, SUM(t1.ml_usados) AS ml_usados, ((t1.ancho)*(SUM(t1.ml_usados))/1000) AS m2_item 
            FROM metros_lineales t1 
            WHERE t1.id_item_producir = $id_item_producir AND t1.estado_ml = 1 AND t1.ml_usados != 0 GROUP BY t1.ancho";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function MetrosCuadrados($id_item_producir, $estado_ml)
    {
        if ($estado_ml == 2) {
            $sql = "SELECT (ancho*SUM(ml_usados))/1000 AS m2 FROM metros_lineales WHERE id_item_producir = '$id_item_producir' AND estado_ml = 2";
        } else {
            $sql = "SELECT (ancho*SUM(metros_lineales))/1000 AS m2 FROM metros_lineales WHERE id_item_producir = '$id_item_producir' AND estado_ml = 1";
        }
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    
}

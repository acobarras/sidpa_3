<?php

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;

final class impresorasDAO extends GenericoDAO 
{
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'impresoras');
    }
    
    public function consulta_impresoras()
    {
        $sql = 'SELECT t1.*, t2.tamano 
        FROM impresoras t1
        INNER JOIN impresora_tamano t2 ON t1.id_impresora_tamano = t2.id';
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_impresoras_maquina($id_maquina,$id_tamano)
    {
       // esto debe cambiar recuerda que es por estaciones 
        $sql = "SELECT t1.* 
        FROM impresoras t1
        INNER JOIN maquinas t2 ON t1.id_estacion = t2.estacion_impresora
        WHERE t2.id_maquina = $id_maquina AND t1.id_impresora_tamano = $id_tamano";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function impresoras_por_area($id_usuario,$id_tamano)
    {
        $sql = "SELECT t1.* 
        FROM impresoras t1 
        INNER JOIN impresora_tamano t2 ON t1.id_impresora_tamano = t2.id
        INNER JOIN persona t3 ON t1.id_area_trabajo = t3.id_area_trabajo
        INNER JOIN usuarios t4 ON t3.id_persona = t4.id_persona
        WHERE t4.id_usuario = $id_usuario AND t2.id = $id_tamano";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function impresoras_predeterminadas($id_tamano)
    {
        $sql = "SELECT * FROM impresoras WHERE id_impresora_tamano = $id_tamano AND predeterminado = 1;";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

   

}

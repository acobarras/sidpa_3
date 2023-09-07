<?php

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class PrioridadesComercialDAO extends GenericoDAO
{


    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'prioridades_comercial');
    }

    function consulta_mensajes($id_prioridad, $id_usuario)
    {
        $sql = "SELECT * FROM `seguimiento_prioridades` WHERE id_prioridad=$id_prioridad AND id_usuario=$id_usuario AND estado=2";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    function cant_mensajes($id_contacto, $id_usuario)
    {
        $sql = "SELECT COUNT(mensaje) AS cant_pend FROM mensajes_chat 
            WHERE id_contacto = $id_contacto AND id_usuario = $id_usuario AND estado = 1";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    function consultar_prioridades()
    {
        $sql = "SELECT t1.*,t2.*, t2.fecha_crea AS fecha_mensaje,t3.nombre,t3.apellido,t1.estado AS estado_prioridad,t2.estado AS estado_mensaje 
        FROM prioridades_comercial t1 
        INNER JOIN seguimiento_prioridades t2 ON t2.id_prioridad=t1.id_prioridad 
        INNER JOIN usuarios t3 ON t3.id_usuario=t1.id_user_remite 
        WHERE t1.estado=1 AND t2.estado=1";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    function consulta_cant_mensajes($id_prioridad)
    {
        $sql = "SELECT COUNT(t1.id_prioridad) AS cant_respuestas,t2.id_user_recibe FROM seguimiento_prioridades t1 
        INNER JOIN prioridades_comercial t2 ON t2.id_prioridad=t1.id_prioridad 
        WHERE t1.id_prioridad=$id_prioridad AND t1.estado=2";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    function consulta_prioridades($condicion)
    {
        $sql = "SELECT t1.*,t2.mensaje AS prioridad FROM prioridades_comercial t1
        INNER JOIN seguimiento_prioridades t2 ON t2.id_prioridad=t1.id_prioridad
        $condicion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    function mensajes_prioridad($id_prioridad)
    {
        $sql = "SELECT t2.nombre,t2.apellido,t1.mensaje,t1.fecha_crea 
        FROM seguimiento_prioridades t1 
        INNER JOIN usuarios t2 ON t2.id_usuario=t1.id_usuario 
        WHERE t1.id_prioridad=$id_prioridad AND t1.estado=2";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
}

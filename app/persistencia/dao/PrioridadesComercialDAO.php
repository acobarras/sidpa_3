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

    function consulta_mensajes($id_prioridad, $id_usuario, $num)
    {
        if ($num == 1) {
            // CONSULTA DE TODAS LAS RESPUESTAS
            $sql = "SELECT t2.nombre,t2.apellido,t1.*,t4.nombre_area_trabajo,t4.id_area_trabajo FROM seguimiento_prioridades t1
            INNER JOIN usuarios t2 ON t2.id_usuario=t1.id_usuario
            INNER JOIN persona t3 ON t3.id_persona=t2.id_persona
            INNER JOIN area_trabajo t4 ON t4.id_area_trabajo=t3.id_area_trabajo
            WHERE id_prioridad=$id_prioridad";
        } else {
            // CONSULTA DE LAS RESPUESTAS DEL USUARIO 
            $sql = "SELECT * FROM `seguimiento_prioridades` WHERE id_prioridad= $id_prioridad AND id_usuario= $id_usuario AND estado=2";
        }
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
        $sql = "SELECT t1.*,t2.*, t2.fecha_crea AS fecha_mensaje,t3.nombre,t3.apellido, t1.estado AS estado_prioridad,t2.estado AS estado_mensaje,
        t5.nombre_area_trabajo,t5.id_area_trabajo,t7.nombre_empresa 
        FROM prioridades_comercial t1 
        INNER JOIN seguimiento_prioridades t2 ON t2.id_prioridad=t1.id_prioridad 
        INNER JOIN usuarios t3 ON t3.id_usuario=t1.id_user_remite 
        INNER JOIN persona t4 ON t4.id_persona=t3.id_persona 
        INNER JOIN area_trabajo t5 ON t5.id_area_trabajo=t4.id_area_trabajo 
        LEFT JOIN pedidos t6 ON t1.pedido=t6.num_pedido 
        LEFT JOIN cliente_proveedor t7 ON t7.id_cli_prov=t6.id_cli_prov
         WHERE t1.estado=1 AND t2.estado=1;";
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
        $sql = "SELECT t1.*,t2.mensaje AS prioridad,t4.nombre_empresa FROM prioridades_comercial t1
        INNER JOIN seguimiento_prioridades t2 ON t2.id_prioridad=t1.id_prioridad
        LEFT JOIN pedidos t3 ON t3.num_pedido=t1.pedido
        LEFT JOIN cliente_proveedor t4 ON t4.id_cli_prov=t3.id_cli_prov
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
        WHERE t1.id_prioridad=$id_prioridad AND t1.estado IN(2,3)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    function consulta_mensajes_user($id_prioridad, $id_usuarios)
    {
        $sql = "SELECT * FROM `seguimiento_prioridades` 
        WHERE id_prioridad=$id_prioridad AND id_usuario in($id_usuarios) AND estado=2";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
}

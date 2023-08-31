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

    function consulta_mensajes($id_usuario, $id_contacto)
    {
        $sql = "SELECT * FROM `mensajes_chat` 
            WHERE id_usuario IN($id_usuario,$id_contacto) AND id_contacto IN($id_usuario,$id_contacto)";
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
}

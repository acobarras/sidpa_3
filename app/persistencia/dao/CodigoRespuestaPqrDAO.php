<?php
namespace MiApp\persistencia\dao;
use MiApp\persistencia\generico\GenericoDAO;
use PDO;

class CodigoRespuestaPqrDAO extends GenericoDAO
{
    public function __construct($cnn)
    {
        parent::__construct($cnn, 'codigo_respuesta_pqr');
    }

    public function consultar_codigos_pqr()
    {
        $sql = "SELECT * FROM codigo_respuesta_pqr";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_codigos_pqr_id($id_respuesta_pqr)
    {
        $sql = "SELECT * FROM codigo_respuesta_pqr WHERE id_respuesta_pqr = $id_respuesta_pqr";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

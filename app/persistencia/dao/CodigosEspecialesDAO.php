<?php
namespace MiApp\persistencia\dao;
use MiApp\persistencia\generico\GenericoDAO;
use PDO;

class CodigosEspecialesDAO extends GenericoDAO
{
    public function __construct($cnn)
    {
        parent::__construct($cnn, 'codigos_especiales');
    }

    public function consultar_codigos($param)
    {
        $sql = "SELECT * FROM codigos_especiales WHERE codigo_especial = '$param'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    public function consultar_todos_codigos()
    {
        $sql = "SELECT * FROM codigos_especiales";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }
}

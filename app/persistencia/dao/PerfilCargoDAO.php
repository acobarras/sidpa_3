<?php
namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;
use PDO;

class PerfilCargoDAO extends GenericoDAO
{
    public function __construct($cnn)
    {
        parent::__construct($cnn, 'perfil_cargo');
    }

    public function ConsultaPerfilCargo()
    {
        $sql = "SELECT * FROM perfil_cargo WHERE estado != 0";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    
}

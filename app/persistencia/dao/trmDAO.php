<?php
namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class trmDAO extends GenericoDAO {

    public function __construct(&$cnn) {
        parent::__construct($cnn, 'trm');
    }

    // public function ConsultaFecha($fecha_consulta)
    // {
    //     $sql = "SELECT * FROM trm WHERE fecha_crea='$fecha_consulta'";
    //     $sentencia = $this->cnn->prepare($sql);
    //     $sentencia->execute();
    //     $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
    //     return $resultado;
    // }

    public function ConsultaUltimoRegistro()
    {
        $sql = "SELECT * FROM trm WHERE id_trm = (SELECT MAX(id_trm) from trm)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

}
?>
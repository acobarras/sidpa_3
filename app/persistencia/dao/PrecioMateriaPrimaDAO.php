<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class PrecioMateriaPrimaDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'precio_materia_prima');
    }

    public function consultar_precio_materia_prima()
    {
        $sql = "SELECT t1.*, UCASE(t2.nombre_material) AS nombre_material, UCASE(t3.nombre_adh) AS nombre_adh 
            FROM precio_materia_prima t1 
            INNER JOIN tipo_material t2 ON t1.id_tipo_material = t2.id_tipo_material 
            INNER JOIN adhesivo t3 ON t1.id_adhesivo = t3.id_adh";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function valida_precio($id_tipo_material,$id_adhesivo)
    {
        $sql = "SELECT * FROM precio_materia_prima 
            WHERE id_tipo_material = $id_tipo_material AND id_adhesivo = $id_adhesivo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}
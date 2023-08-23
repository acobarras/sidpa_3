<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class PersonaDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'persona');
    }

    public function consultar_personas()
    {
        $sql = "SELECT * FROM persona";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function jefe_imediato()
    {
        $sql = "SELECT * FROM persona WHERE tipo = 4 AND estado != 0";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_cumpleanios($date)
    {

        $sql = "SELECT nombres,apellidos,num_documento FROM persona 
            WHERE fecha_nacimiento LIKE '%" . $date . "%' AND estado = 1 ORDER BY id_persona DESC";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function lista_cumpleanios($date)
    {
        $sql = "SELECT *,date_format(fecha_nacimiento, '%d') as dia  FROM persona 
            WHERE date_format(fecha_nacimiento, '%m') = '" . $date . "' AND estado = 1 ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_personas_id($id)
    {
        $sql = "SELECT * FROM persona WHERE id_persona =" . $id;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function PersonalRotativo()
    {
        $sql = "SELECT * FROM persona WHERE tipo = 3 AND estado = 1";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_personas_cedula($codigo_operario)
    {
        $sql = "SELECT id_persona,nombres,apellidos from persona WHERE num_documento ='$codigo_operario'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function PersonasJejeImediato($id_jefe_imediato)
    {
        $sql = "SELECT * FROM persona WHERE id_jefe_imediato = '$id_jefe_imediato'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    function personas_comite($id_comite)
    {
        $sql = "SELECT * FROM `persona` WHERE comite IS NOT NULL AND comite != '' AND estado = 1";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        $comite = [];
        foreach ($resultado as $value1) {
            $array = explode(",", $value1->comite);
            $carga = [
                'nombres' => $value1->nombres,
                'apellidos' => $value1->apellidos,
            ];
            if (in_array($id_comite, $array)) {
                array_push($comite, $value1);
            }
        }
        return $comite;
    }
    function personal_produccion()
    {
        $sql = "SELECT * FROM persona WHERE tipo = 3 AND estado = 1 AND id_jefe_imediato=" . ID_JEFE_PRODUCCION;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

<?php

namespace MiApp\persistencia\dao;

use MiApp\negocio\util\insertar_generico;
use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class direccionDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'direccion');
    }

    public function consultar_direccion()
    {
        $sql = "SELECT t1.*, t2.nombre_empresa, t3.nombre AS nombre_ciudad, t4.nombre AS nombre_departamento, t6.nombres, t6.apellidos, t7.nombre AS nombre_pais
        FROM direccion t1
        INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov    
        INNER JOIN ciudad t3 ON t3.id_ciudad = t1.id_ciudad
        INNER JOIN departamento t4 ON t4.id_departamento = t1.id_departamento
        INNER JOIN usuarios t5 ON t1.id_usuario = t5.id_usuario
        INNER JOIN pais t7 ON t1.id_pais = t7.id_pais
        INNER JOIN persona t6 ON t5.id_persona = t6.id_persona ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }


    public function consultaIdDireccion($id_direccion)
    {
        $sql = "SELECT t1.*, t2.*, t3.nombre AS nombre_ciudad, t4.nombre AS nombre_departamento, 
            t5.nombre AS nombre_pais 
            FROM direccion t1 
            INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov 
            INNER JOIN ciudad t3 ON t1.id_ciudad = t3.id_ciudad 
            INNER JOIN departamento t4 ON t1.id_departamento = t4.id_departamento 
            INNER JOIN pais t5 ON t1.id_pais = t5.id_pais 
            WHERE t1.id_direccion = '$id_direccion'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_direccion_cliente($nit)
    {
        $sql = "SELECT t1.*, t2.forma_pago, t2.dias_dados, t2.nombre_empresa, t3.nombre AS nombre_ciudad, t4.nombre AS nombre_departamento, t6.nombres, t6.apellidos, t7.nombre AS nombre_pais
            FROM direccion t1
            INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov    
            INNER JOIN ciudad t3 ON t3.id_ciudad = t1.id_ciudad
            INNER JOIN departamento t4 ON t4.id_departamento = t1.id_departamento
            INNER JOIN usuarios t5 ON t1.id_usuario = t5.id_usuario
            INNER JOIN pais t7 ON t1.id_pais = t7.id_pais
            INNER JOIN persona t6 ON t5.id_persona = t6.id_persona
            WHERE t2.nit = $nit";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }
}

<?php

namespace MiApp\persistencia\dao;
use MiApp\persistencia\generico\GenericoDAO;

final class SolicitudesDisenoDAO extends GenericoDAO
{
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'solicitudes_diseno');
    }

    public function consulta_solicitudes($condicion) {
        $sql = "SELECT t1.*, 
        t2.nit, t2.nombre_empresa, 
        CONCAT(t3.nombres,' ', t3.apellidos) AS asesor, 
        t4.nombre_forma, 
        t5.nombre_material, 
        t6.nombre_adh
        FROM solicitudes_diseno t1
        INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov
        INNER JOIN persona t3 ON t1.id_usuario_asesor = t3.id_persona
        INNER JOIN forma_material t4 ON t1.id_forma = t4.id_forma
        INNER JOIN tipo_material t5 ON t1.codigo_tipo_material = t5.codigo
        INNER JOIN adhesivo t6 ON t1.codigo_adh = t6.codigo_adh
        $condicion";// falta agregar el where del estado y el tipo de solicitud
       $sentencia = $this->cnn->prepare($sql);
       $sentencia->execute();
       $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
       return $resultado;
    }

    public function  consulta_data_solicitud($id_solicitud) 
    {
        $sql = "SELECT t1.*, 
        t3.correo, CONCAT(t3.nombres,' ', t3.apellidos) AS asesor,
        t4.nombre_empresa, t4.nit 
        FROM solicitudes_diseno t1
        INNER JOIN usuarios t2 ON t1.id_usuario_asesor = t2.id_usuario
        INNER JOIN persona t3 ON t2.id_persona = t3.id_persona
        INNER JOIN cliente_proveedor t4 ON t1.id_cli_prov = t4.id_cli_prov
        WHERE id_solicitud = $id_solicitud";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

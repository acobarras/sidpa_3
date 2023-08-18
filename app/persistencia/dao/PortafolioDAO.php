<?php

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;
use PDO;

class PortafolioDAO extends GenericoDAO
{
    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'portafolio');
    }
    public function ConsultarNumFactura_portafolio($num_factura)
    {
        $sql = "SELECT t1.*,t2.nombre_empresa,t2.nit,t2.id_usuarios_asesor FROM portafolio t1
                    INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov 
                    WHERE t1.num_factura = $num_factura";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }
    public function ConsultarPortafolioEmpresa($empresa)
    {
        $sql = "SELECT t1.*,t2.nombre_empresa,t2.nit,t2.id_usuarios_asesor,t3.nombres,t3.apellidos FROM portafolio t1
                    INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov 
                    INNER JOIN persona t3 ON t1.asesor = t3.id_persona 
                    WHERE t1.empresa= $empresa  AND t1.estado_portafolio IN (1,2)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }
    public function ConsultarPortafolioIdCliProv($id_cli_prov)
    {
        $sql = "SELECT t1.*,t2.nombre_empresa,t2.nit,t2.id_usuarios_asesor FROM portafolio t1
                    INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov 
                    WHERE t1.id_cli_prov=$id_cli_prov AND t1.estado_portafolio IN (1,2)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    public function Consultaportafolio($condicion)
    {

        $sql = "SELECT t1.*,t2.nombre_empresa,t2.nit,t2.id_usuarios_asesor,t3.nombres,t3.apellidos FROM portafolio t1
                INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov 
                INNER JOIN persona t3 ON t1.asesor = t3.id_persona 
                WHERE   $condicion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }
    // public function ConsultarPortafolioAsesor($asesor = '')
    // {
    //     $consulta = 'WHERE t1.asesor = ' . $asesor;
    //     if ($asesor == '') {
    //         $consulta = '';
    //     }
    //     $sql = "SELECT t1.*,t2.nombre_empresa,t2.nit,t2.id_usuarios_asesor,t3.nombres,t3.apellidos FROM portafolio t1
    //                 INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov 
    //                 INNER JOIN persona t3 ON t1.asesor = t3.id_persona 
    //                 $consulta";
    //     $sentencia = $this->cnn->prepare($sql);
    //     $sentencia->execute();
    //     $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

    //     return $resultado;
    // }

    // public function ConsultaPorFecha($fecha_inicio, $fecha_fin)
    // {
    //     $sql = "SELECT t1.*,t2.nombre_empresa,t2.nit,t2.id_usuarios_asesor,t3.nombres,t3.apellidos FROM portafolio t1
    //                 INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov 
    //                 INNER JOIN persona t3 ON t1.asesor = t3.id_persona 
    //                 WHERE t1.fecha_factura >= '$fecha_inicio' AND t1.fecha_factura <= '$fecha_fin'";
    //     $sentencia = $this->cnn->prepare($sql);
    //     $sentencia->execute();
    //     $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
    //     return $resultado;
    // }
}

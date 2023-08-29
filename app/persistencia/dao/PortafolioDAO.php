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
                $condicion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    // CARTER COMERCIAL

    public function consulta_cartera($id_persona, $condicion) {
        $id_rol= $_SESSION['usuario']->getId_roll();
        $id_usu = $_SESSION['usuario']->getId_usuario();
        $asesor = 'AND asesor ='. $id_persona;
        if ($id_rol == 1 || $id_usu == ID_COMISIONES_CARTERA ) {
            $asesor = '';
        }
        $sql = "SELECT t2.nit, t2.nombre_empresa,
        COUNT(t1.num_factura) AS cantidad_facturas, t1.id_cli_prov, SUM(t1.total_etiquetas) AS etiquetas, SUM(t1.total_cintas) AS cintas, SUM(t1.total_etiq_cint) AS etiquetas_cintas, SUM(t1.total_alquiler) AS alquiler, SUM(t1.total_tecnologia) AS tecnologia, SUM(t1.total_soporte) AS soporte, SUM(t1.total_fletes) AS fletes,SUM(t1.total_m_prima) AS m_prima,SUM(t1.total_factura) AS total_facturas, MIN(t1.dias_dados) AS dias_credito, MIN(t1.fecha_vencimiento) AS factura_masantigua, TIMESTAMPDIFF(DAY, MIN(t1.fecha_vencimiento) , CURDATE()) as dias_mora
        FROM portafolio t1
        INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov
        WHERE t1.fecha_vencimiento $condicion CURDATE() AND t1.estado_portafolio IN(1,2) $asesor AND NOT t1.id_cli_prov = 2606 AND NOT t1.id_cli_prov = 21 
        GROUP BY t1.id_cli_prov";// 2606 ACOBARRAS COLOMBIA, 21 ACOBARRAS 
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function detalle_facturasVencidas($id_cliente, $id_persona, $condicion) {
        $id_rol= $_SESSION['usuario']->getId_roll();
        $asesor = 'AND asesor ='. $id_persona;
        if ($id_rol == 1) {
            $asesor = '';
        }
        $sql = "SELECT t2.nit, t2.nombre_empresa,
        t1.*, TIMESTAMPDIFF(DAY, t1.fecha_vencimiento , CURDATE()) as dias_mora
        FROM portafolio t1
        INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov
        WHERE t1.fecha_vencimiento $condicion CURDATE() AND t1.estado_portafolio IN(1,2)  AND t1.id_cli_prov = $id_cliente $asesor
        ORDER BY t1.fecha_vencimiento";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
        
    }

}

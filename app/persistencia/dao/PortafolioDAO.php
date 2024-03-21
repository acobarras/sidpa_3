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

    public function Consultaportafolio($condicion, $fecha_inicial = '', $fecha_fin = '')
    {
        if ($condicion == '') {
            $sql = "SELECT SUM(t1.total_cintas+t1.total_alquiler+t1.total_tecnologia+t1.total_soporte) AS totalTecnologia, SUM(t1.total_fletes+t1.total_m_prima) AS total_sin_comision, t1.*,t2.nombre_empresa,t2.nit,t2.id_usuarios_asesor,t3.nombres,t3.apellidos, t3.comi_etiq, t3.comi_tecn, (CASE WHEN t2.pertenece != 0 THEN t4.nombre_compania ELSE 'SIN ASIGNAR' END) AS nombre_compania, ABS(DATEDIFF(t1.fecha_vencimiento, t1.fecha_pago)) AS dias_vencimiento, SUM(t1.total_cintas + t1.total_etiquetas + t1.total_alquiler + t1.total_tecnologia + t1.total_soporte) AS subtotal, SUM(t1.total_tecnologia + t1.total_soporte) AS sumatoria_tecsop, t5.nombre_estado 
            FROM portafolio t1 
            INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov 
            INNER JOIN persona t3 ON t1.asesor = t3.id_persona 
            LEFT JOIN empresas t4 ON t2.pertenece = t4.id_empresa OR t2.pertenece = 0
            INNER JOIN estados_portafolio t5 ON t1.estado_portafolio = t5.id_estado_portafolio
            $condicion GROUP BY t1.id_portafolio  ORDER BY t1.num_factura ASC, t2.pertenece ASC, t1.asesor DESC";
        } else {
            $sql = "SELECT SUM(t1.total_cintas+t1.total_alquiler+t1.total_tecnologia+t1.total_soporte) AS totalTecnologia, SUM(t1.total_fletes+t1.total_m_prima) AS total_sin_comision, t1.*, t2.nombre_empresa, t2.nit, t2.id_usuarios_asesor, t3.nombres, t3.apellidos, t3.comi_etiq, t3.comi_tecn, (CASE WHEN t2.pertenece != 0 THEN t4.nombre_compania ELSE 'SIN ASIGNAR' END) AS nombre_compania, COALESCE(t5.venta_mes, 0) AS venta_mes, COALESCE(t5.recaudo_mes, 0) AS recaudo_mes, ABS(DATEDIFF(t1.fecha_vencimiento, t1.fecha_pago)) AS dias_vencimiento, SUM(t1.total_cintas + t1.total_etiquetas + t1.total_alquiler + t1.total_tecnologia + t1.total_soporte) AS subtotal, SUM(t1.total_tecnologia + t1.total_soporte) AS sumatoria_tecsop, t5.nombre_estado FROM portafolio t1 
            INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov 
            INNER JOIN persona t3 ON t1.asesor = t3.id_persona 
            LEFT JOIN empresas t4 ON t2.pertenece = t4.id_empresa OR t2.pertenece = 0
            INNER JOIN estados_portafolio t5 ON t1.estado_portafolio = t5.id_estado_portafolio
            LEFT JOIN ( SELECT asesor, SUM(venta_mes) AS venta_mes, SUM(recaudo_mes) AS recaudo_mes FROM ( SELECT asesor, SUM(total_etiquetas+total_cintas+total_alquiler+total_tecnologia+total_soporte+total_fletes+total_m_prima) AS venta_mes, NULL AS recaudo_mes FROM portafolio 
            WHERE fecha_factura >= '$fecha_inicial' AND fecha_factura <= '$fecha_fin' 
            GROUP BY asesor 
            UNION SELECT asesor, NULL AS venta_mes, SUM(total_etiquetas+total_cintas+total_alquiler+total_tecnologia+total_soporte+total_fletes+total_m_prima) AS recaudo_mes FROM portafolio 
            WHERE fecha_pago >= '$fecha_inicial' AND fecha_pago <= '$fecha_fin' GROUP BY asesor) AS combined_data GROUP BY asesor) AS t5 ON t1.asesor = t5.asesor 
            $condicion  
    GROUP BY 
        t1.id_portafolio 
    ORDER BY 
        t1.num_factura ASC, t2.pertenece ASC, t1.asesor DESC;";
        }
        // $sql = "SELECT t1.*,t2.nombre_empresa,t2.nit,t2.id_usuarios_asesor,t3.nombres,t3.apellidos, (CASE WHEN t2.pertenece != 0 THEN t4.nombre_compania ELSE 'SIN ASIGNAR' END) AS nombre_compania 
        //         FROM portafolio t1
        //         INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov 
        //         INNER JOIN persona t3 ON t1.asesor = t3.id_persona
        //         LEFT JOIN empresas t4 ON t2.pertenece = t4.id_empresa OR t2.pertenece = 0
        //         $condicion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    // CARTER COMERCIAL

    public function consulta_cartera($id_persona, $condicion)
    {
        $id_rol = $_SESSION['usuario']->getId_roll();
        $id_usu = $_SESSION['usuario']->getId_usuario();
        $asesor = 'AND asesor =' . $id_persona;
        if ($id_rol == 1 || $id_usu == ID_COMISIONES_CARTERA) {
            $asesor = '';
        }
        $sql = "SELECT t2.nit, t2.nombre_empresa,
        COUNT(t1.num_factura) AS cantidad_facturas, t1.id_cli_prov, SUM(t1.total_etiquetas) AS etiquetas, SUM(t1.total_cintas) AS cintas, SUM(t1.total_etiq_cint) AS etiquetas_cintas, SUM(t1.total_alquiler) AS alquiler, SUM(t1.total_tecnologia) AS tecnologia, SUM(t1.total_soporte) AS soporte, SUM(t1.total_fletes) AS fletes,SUM(t1.total_m_prima) AS m_prima,SUM(t1.total_factura) AS total_facturas, MIN(t1.dias_dados) AS dias_credito, MIN(t1.fecha_vencimiento) AS factura_masantigua, TIMESTAMPDIFF(DAY, MIN(t1.fecha_vencimiento) , CURDATE()) as dias_mora
        FROM portafolio t1
        INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov
        WHERE t1.fecha_vencimiento $condicion CURDATE() AND t1.estado_portafolio IN(1,2) $asesor AND NOT t1.id_cli_prov = 2606 AND NOT t1.id_cli_prov = 21 
        GROUP BY t1.id_cli_prov"; // 2606 ACOBARRAS COLOMBIA, 21 ACOBARRAS 
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function detalle_facturasVencidas($id_cliente, $id_persona, $condicion)
    {
        $id_rol = $_SESSION['usuario']->getId_roll();
        $id_usu = $_SESSION['usuario']->getId_usuario();
        $asesor = 'AND asesor =' . $id_persona;
        if ($id_rol == 1 || $id_usu == ID_COMISIONES_CARTERA) {
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

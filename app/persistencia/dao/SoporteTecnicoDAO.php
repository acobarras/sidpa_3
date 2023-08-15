<?php

namespace MiApp\persistencia\dao;

use MiApp\persistencia\generico\GenericoDAO;
use PDO;

class SoporteTecnicoDAO extends GenericoDAO
{
    public function __construct($cnn)
    {
        parent::__construct($cnn, 'diagnostico_soporte_tecnico');
    }

    public function consultar_id_persona($id_usuario)
    {
        $sql = "SELECT t1.id_persona FROM usuarios t1 WHERE t1.id_usuario= '$id_usuario'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_casos($estado, $id_usuario)
    {
        $estado2 = 3;
        $estado3 = 4;
        $estado4 = 6;
        $sql = "SELECT t1.*, t2.id_cli_prov AS id_cliente, t2.tipo_documento, t2.nit, t2.dig_verificacion, t2.nombre_empresa, t2.forma_pago, t3.id_direccion, t3.id_pais, t3.id_departamento, t3.id_ciudad, t3.direccion, t3.telefono, t3.celular, t3.email, t3.contacto, t3.cargo, t4.nombre_estado_soporte, t1.fecha_crea AS fecha_creacion_diag
        FROM diagnostico_soporte_tecnico t1
        INNER JOIN cliente_proveedor t2 ON t2.id_cli_prov = t1.id_cli_prov
        INNER JOIN direccion t3 ON t3.id_direccion = t1.id_direccion
        INNER JOIN estado_soporte t4 ON t4.id_estado_soporte = t1.estado
        WHERE t1.estado in($estado,$estado2,$estado3,$estado4)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function caso_remoto($estado)
    {
        $sql = "SELECT t1.estado,t1.id_diagnostico,t2.*,t3.*,t3.id_cli_prov AS id_cliente,t4.nombre_estado_soporte, t6.nombre AS nombre_ciudad,t7.nombre AS nombre_departa,t8.nombre AS nombre_pais FROM diagnostico_soporte_tecnico t1 
        INNER JOIN cliente_proveedor t2 ON t2.id_cli_prov=t1.id_cli_prov
        INNER JOIN direccion t3 ON t3.id_direccion=t1.id_direccion
        INNER JOIN estado_soporte t4 ON t4.id_estado_soporte=t1.estado
        INNER JOIN ciudad t6 ON t3.id_ciudad=t6.id_ciudad
        INNER JOIN departamento t7 ON t7.id_departamento=t3.id_departamento
        INNER JOIN pais t8 ON t8.id_pais=t3.id_pais
        WHERE t1.estado in($estado)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consulta_productos_serman()
    {
        $id_tipo_articulo = 14;
        $sql = "SELECT * FROM `productos` WHERE id_tipo_articulo=$id_tipo_articulo";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consul_personal()
    {
        $id_jefe_inmediato = 10;
        $sql = "SELECT * FROM `persona` 
        WHERE id_jefe_imediato=$id_jefe_inmediato AND estado =1 || id_persona=$id_jefe_inmediato";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consulta_producto($id_producto)
    {
        $sql = "SELECT * FROM `productos` WHERE id_productos=$id_producto";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function visitas_agendadas($estado, $estado2)
    {
        $sql = "SELECT t1.*,t2.*,t9.nombres,t9.apellidos,t9.id_persona,
        t3.*,t3.id_cli_prov 
        AS id_cliente,t4.nombre_estado_soporte, t6.nombre 
        AS nombre_ciudad,t7.nombre 
        AS nombre_departa,t8.nombre 
        AS nombre_pais
        FROM diagnostico_soporte_tecnico t1 
        INNER JOIN cliente_proveedor t2 ON t2.id_cli_prov=t1.id_cli_prov
        INNER JOIN direccion t3 ON t3.id_direccion=t1.id_direccion
        INNER JOIN estado_soporte t4 ON t4.id_estado_soporte=t1.estado
        INNER JOIN ciudad t6 ON t3.id_ciudad=t6.id_ciudad
        INNER JOIN departamento t7 ON t7.id_departamento=t3.id_departamento
        INNER JOIN pais t8 ON t8.id_pais=t3.id_pais
        INNER JOIN persona t9 ON t9.id_persona=t1.id_usuario_visita
        WHERE t1.estado in($estado,$estado2)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function datos_laboratorio($estado)
    {
        $sql = "SELECT t1.*,t2.*,t3.*,t3.id_cli_prov 
        AS id_cliente,t4.nombre_estado_soporte, t6.nombre 
        AS nombre_ciudad,t7.nombre 
        AS nombre_departa,t8.nombre 
        AS nombre_pais 
        FROM diagnostico_soporte_tecnico t1 
        INNER JOIN cliente_proveedor t2 ON t2.id_cli_prov=t1.id_cli_prov 
        INNER JOIN direccion t3 ON t3.id_direccion=t1.id_direccion 
        INNER JOIN estado_soporte t4 ON t4.id_estado_soporte=t1.estado 
        INNER JOIN ciudad t6 ON t3.id_ciudad=t6.id_ciudad 
        INNER JOIN departamento t7 ON t7.id_departamento=t3.id_departamento 
        INNER JOIN pais t8 ON t8.id_pais=t3.id_pais 
        WHERE t1.estado =$estado";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_diag($id_diag)
    {
        $sql = "SELECT * FROM `diagnostico_soporte_tecnico` WHERE id_diagnostico=$id_diag";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consulta_num_diag($id_diag)
    {
        $sql = "SELECT t2.num_consecutivo,t1.*,t3.nombre_empresa,t1.item AS item_segui 
		FROM seguimiento_diag_soporte t1 
        LEFT JOIN diagnostico_item t2 ON t1.id_diagnostico=t2.id_diagnostico 
        LEFT JOIN cliente_proveedor t3 ON t2.id_cli_prov=t3.id_cli_prov 
        WHERE t1.id_diagnostico = $id_diag GROUP BY t1.id_seguimiento";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consulta_item($item, $id_diag)
    {
        $sql = "SELECT * FROM `diagnostico_item`
        WHERE id_diagnostico = $id_diag AND item = $item";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    // consultas de vista reportes 
    public function consulta_casospendientes()
    {
        $sql = "SELECT t1.num_consecutivo, t1.tipo_cobro, t1.fecha_agendamiento, t1.estado, t1.fecha_crea, t2.nombre_estado_soporte, t3.nombre_empresa
        FROM diagnostico_soporte_tecnico t1
        INNER JOIN estado_soporte t2 ON t1.estado = t2.id_estado_soporte
        INNER JOIN cliente_proveedor t3 ON t1.id_cli_prov = t3.id_cli_prov
        WHERE NOT estado = 14";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_reportecomisiones($id_tecnico, $mes, $year)
    {
        $sql = "SELECT t1.id_usuario, t1.id_actividad_area, t1.fecha_crea,t1.id_seguimiento, t1.observacion, t4.nombre_empresa, t4.nit, t2.*
        FROM seguimiento_diag_soporte t1
        LEFT JOIN diagnostico_item t2 ON t1.id_diagnostico = t2.id_diagnostico AND t1.item = t2.item
        INNER JOIN diagnostico_soporte_tecnico t3 ON t1.id_diagnostico = t3.id_diagnostico
        INNER JOIN cliente_proveedor t4 ON t3.id_cli_prov = t4.id_cli_prov
        WHERE ((t1.id_actividad_area = 78 AND t3.visita_laboratorio = 2 AND t1.observacion LIKE '%REPUESTO 1') OR t1.id_actividad_area IN (82,84,89,99)) AND t1.id_usuario = $id_tecnico AND MONTH(t1.fecha_crea) = $mes AND YEAR(t1.fecha_crea) = $year;";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_indivisitas($mes, $year)
    {
        $sql = "SELECT t1.*, t4.nombre_empresa, t4.nit, t3.equipo, t3.serial_equipo, t2.num_consecutivo
        FROM seguimiento_diag_soporte t1
        LEFT JOIN diagnostico_soporte_tecnico t2 ON t1.id_diagnostico = t2.id_diagnostico
        LEFT JOIN diagnostico_item t3 ON t1.id_diagnostico = t3.id_diagnostico AND t1.item = t3.item
        LEFT JOIN cliente_proveedor t4 ON t2.id_cli_prov = t4.id_cli_prov
        WHERE id_actividad_area IN (87,88,99,92) AND MONTH(t1.fecha_crea) = $mes AND YEAR(t1.fecha_crea) = $year 
        ORDER by t1.id_diagnostico";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultas_indiautorizaciones($mes, $year)
    {
        $sql = "SELECT t1.*,t2.num_consecutivo, t4.nombre_empresa, t4.nit, t3.equipo, t3.serial_equipo
        FROM seguimiento_diag_soporte t1
        INNER JOIN diagnostico_soporte_tecnico t2 ON t1.id_diagnostico = t2.id_diagnostico
        INNER JOIN diagnostico_item t3 ON t1.id_diagnostico = t3.id_diagnostico AND t1.item = t3.item
        INNER JOIN cliente_proveedor t4 ON t2.id_cli_prov = t4.id_cli_prov
        WHERE id_actividad_area IN (79,82) AND MONTH(t1.fecha_crea) = $mes AND YEAR(t1.fecha_crea) = $year";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function constultar_idusuario($id_persona)
    {
        $sql = "SELECT id_usuario FROM `usuarios` WHERE id_persona = $id_persona";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function reimpresion_etiquetas($num_consecutivo)
    {
        $sql = "SELECT t1.num_consecutivo, t1.fecha_crea, (SELECT count(*) FROM diagnostico_item t2 WHERE t1.id_diagnostico = t2.id_diagnostico) AS cantidad_item, t3.nombre_empresa
        FROM diagnostico_soporte_tecnico t1
        INNER JOIN cliente_proveedor t3 ON t1.id_cli_prov = t3.id_cli_prov
        WHERE t1.num_consecutivo = '$num_consecutivo' AND t1.visita_laboratorio = 2";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_reimpresionremision($num_remision) {
        $sql = "SELECT t1.num_consecutivo, t1.fecha_crea AS fecha_ingreso, t2.nombre_empresa, t3.direccion, t4.nombre AS pais, t5.nombre AS departamento, t6.nombre AS ciudad, t7.equipo, t7.serial_equipo, t7.procedimiento, t7.accesorios, t7.firma_cli, t1.req_visita AS estado, t8.nombre AS nombre_usuario, t8.apellido AS apellido_usuario, (SELECT t3.contacto FROM direccion t3 WHERE t3.id_cli_prov = t1.id_cli_prov AND t3.id_direccion = t1.id_direccion) AS recibido
        FROM diagnostico_soporte_tecnico t1 
        INNER JOIN cliente_proveedor  t2 ON t1.id_cli_prov = t2.id_cli_prov
        INNER JOIN direccion t3 ON t1.id_direccion = t3.id_direccion
        INNER JOIN pais t4 ON t3.id_pais = t4.id_pais
        INNER JOIN departamento t5 ON t3.id_departamento = t5.id_departamento
        INNER JOIN ciudad t6 ON t3.id_ciudad = t6.id_ciudad
        INNER JOIN diagnostico_item t7 ON t1.id_diagnostico = t7.id_diagnostico
        LEFT JOIN usuarios t8 ON t1.id_usuario_visita = t8.id_persona
        WHERE t1.id_diagnostico = $num_remision";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

}

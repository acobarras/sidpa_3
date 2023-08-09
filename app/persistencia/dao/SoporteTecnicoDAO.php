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
        if (array_key_exists($id_usuario, PERMISOS_SOPORTE) || $_SESSION['usuario']->getid_roll() == 1) {// validacion de usuario admin, coordinador y jefe de soporte 
            $boton = true;
        }else{
            $boton = false;
        }
        $sql = "SELECT t1.*, t2.id_cli_prov AS id_cliente, t2.tipo_documento, t2.nit, t2.dig_verificacion, t2.nombre_empresa, t2.forma_pago, t3.id_direccion, t3.id_pais, t3.id_departamento, t3.id_ciudad, t3.direccion, t3.telefono, t3.celular, t3.email, t3.contacto, t3.cargo, t4.nombre_estado_soporte, t1.fecha_crea AS fecha_creacion_diag
        FROM diagnostico_soporte_tecnico t1
        INNER JOIN cliente_proveedor t2 ON t2.id_cli_prov = t1.id_cli_prov
        INNER JOIN direccion t3 ON t3.id_direccion = t1.id_direccion
        INNER JOIN estado_soporte t4 ON t4.id_estado_soporte = t1.estado
        WHERE t1.estado in($estado,$estado2,$estado3,$estado4)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        if ($resultado != []) {
            $resultado[0]->{"boton"} = $boton;
        }
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
}

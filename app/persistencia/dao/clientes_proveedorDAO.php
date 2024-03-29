<?php

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class clientes_proveedorDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'cliente_proveedor');
    }

    public function cliente_pertenece($param)
    {
        $sql = "SELECT * FROM cliente_proveedor WHERE id_cli_prov = '$param'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_clientes($roll = '')
    {
        if ($roll == 1) {
            $sql = "SELECT t1.*, (CASE WHEN t1.pertenece != 0 THEN t2.nombre_compania ELSE 'SIN ASIGNAR' END) AS nombre_compania,'0' AS bloqueo_pedido 
            FROM cliente_proveedor t1 
            LEFT JOIN empresas t2 ON t1.pertenece = t2.id_empresa OR t1.pertenece = 0;";
        } else {
            $sql = "SELECT t1.*, (CASE WHEN t1.pertenece != 0 THEN t2.nombre_compania ELSE 'SIN ASIGNAR' END) AS nombre_compania,'0' AS bloqueo_pedido
                FROM cliente_proveedor t1 
                LEFT JOIN empresas t2 ON t1.pertenece = t2.id_empresa OR t1.pertenece = 0
                WHERE t1.estado_cli_prov != 0";
        }
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_clientes_asesor($id_persona, $id_usuario = '')
    {
        $sql = "SELECT t1.*, (CASE WHEN t1.pertenece != 0 THEN t2.nombre_compania ELSE 'SIN ASIGNAR' END) AS nombre_compania,t3.bloqueo_pedido
            FROM cliente_proveedor t1 
            LEFT JOIN empresas t2 ON t1.pertenece = t2.id_empresa OR t1.pertenece = 0
            INNER JOIN usuarios t3 ON t3.id_usuario=$id_usuario
            WHERE id_usuarios_asesor LIKE '%$id_persona%'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_proovedor()
    {
        $sql = "SELECT * FROM cliente_proveedor  WHERE tipo_prove ='2'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_direccion_cliente($id_prov, $id_usuario)
    {

        // if ($_SESSION['usuario']->getId_roll() == 1) {
        $sql = "SELECT  *  from direccion AS  t1
            INNER JOIN ciudad AS t2 ON t1.id_ciudad=t2.id_ciudad
            INNER JOIN cliente_proveedor AS t3 ON t1.id_cli_prov=t3.id_cli_prov
            WHERE  t1.id_cli_prov =" . $id_prov . "  AND t1.estado_direccion = 1";
        // } else {
        //     $sql = "SELECT  *  from direccion AS  t1
        //     INNER JOIN ciudad AS t2 ON t1.id_ciudad=t2.id_ciudad
        //     INNER JOIN cliente_proveedor AS t3 ON t1.id_cli_prov=t3.id_cli_prov
        //                           WHERE  t1.id_cli_prov =" . $id_prov . " AND t1.id_usuario=" . $id_usuario . " AND t1.estado_direccion = 1";
        // }
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    // public function consultar_direccion_cliente_modificar() {


    //     $sql = "SELECT  *  from direccion as dir
    // 		INNER JOIN cliente_proveedor AS clit 
    //                         ON dir.id_cli_prov=clit.id_cli_prov 
    //                         INNER JOIN ciudad as ciud  
    //         			ON ciud.id_ciudad = dir.id_ciudad
    //                                WHERE clit.nit =" . $_POST['nit'];

    //     $sentencia = $this->cnn->prepare($sql);
    //     $sentencia->execute();
    //     $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

    //     return $resultado;
    // }

    // public function consultar_clientes_especificos()
    // {
    //     $sql = "SELECT *  from direccion as dir
    // 		INNER JOIN cliente_proveedor AS clit 
    //                         ON dir.id_cli_prov=clit.id_cli_prov WHERE dir.id_usuario =" . $_SESSION['usuario']->getId_usuario() . " AND estado_cli_prov = 1 GROUP BY clit.nombre_empresa";

    //     $sentencia = $this->cnn->prepare($sql);
    //     $sentencia->execute();
    //     $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

    //     return $resultado;
    // }



    public function consultar_clientes_proveedor($id_prov)
    {
        $sql = "SELECT * FROM cliente_proveedor 
            	WHERE id_cli_prov = " . $id_prov;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_nit_clientes($nit)
    {
        $sql = "SELECT * FROM cliente_proveedor 
            	WHERE nit = '$nit'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_clientes_aumento($condicion)
    {
        $sql = "SELECT t4.nombre_empresa ,t3.nombre_articulo, t4.nit,t1.*,t2.codigo_producto
        FROM cliente_producto t1 
        INNER JOIN productos t2 ON t1.id_producto = t2.id_productos 
        INNER JOIN tipo_articulo t3 ON t2.id_tipo_articulo = t3.id_tipo_articulo 
        INNER JOIN cliente_proveedor t4 ON t1.id_cli_prov= t4.id_cli_prov 
        $condicion";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        return $resultado;
    }
}

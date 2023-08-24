<?php

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;

class cliente_productoDAO extends GenericoDAO
{

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'cliente_producto');
    }
    public function consultar_productos_clientes_asesor()
    {
        $sql = "SELECT *,client_proc.id_usuario,tipo_Art.id_clase_articulo FROM cliente_producto AS client_proc 
                INNER JOIN ruta_embobinado AS r ON client_proc.id_ruta_embobinado = r.id_ruta_embobinado
                INNER JOIN core AS core ON client_proc.id_core = core.id_core
		        INNER JOIN productos AS produc ON client_proc.id_producto = produc.id_productos   
                INNER JOIN cliente_proveedor as client_provee on  client_proc.id_cli_prov= client_provee.id_cli_prov      
                INNER JOIN tipo_articulo as tipo_Art on  produc.id_tipo_articulo= tipo_Art.id_tipo_articulo
                INNER JOIN usuarios AS usua ON usua.id_usuario = client_proc.id_usuario

            	WHERE client_proc.id_cli_prov =" . $_REQUEST['id'] . " AND client_proc.estado_client_produc = 1";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }

    public function valida_codigo_cliente($data)
    {
        $sql = "SELECT * FROM cliente_producto WHERE id_cli_prov=" . $data['id_cli_prov'] . "  AND id_producto=" . $data['id_producto'] . " AND id_ruta_embobinado=" . $data['id_ruta_embobinado'] . " AND id_core=" . $data['id_core'] . " AND presentacion=" . $data['presentacion'] . "";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->rowCount();
        if ($resultado == 0) {
            $respu = $resultado;
        } else {
            $respu = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        }

        return $respu;
    }
    public function cliente_producto_id($data)
    {
        $sql = "SELECT t1.*, t2.codigo_producto, t2.descripcion_productos, t2.ubi_troquel, t3.nombre_empresa 
            FROM cliente_producto AS t1
            INNER JOIN productos AS t2 ON t1.id_producto=t2.id_productos
            INNER JOIN cliente_proveedor AS t3 ON t1.id_cli_prov=t3.id_cli_prov
            WHERE t1.id_clien_produc=" . $data;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_productos_cliente($id_cliente)
    {
        $sql = "SELECT t1.*, t2.*,t3.nombre_core,t4.nombre_r_embobinado FROM cliente_producto t1 
        INNER JOIN productos t2 ON t2.id_productos=t1.id_producto 
        INNER JOIN core t3 ON t3.id_core=t1.id_core
        INNER JOIN ruta_embobinado t4 ON t4.id_ruta_embobinado=t1.id_ruta_embobinado
        WHERE id_cli_prov=$id_cliente AND t2.estado_producto!=0  ";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_items_sin_precio()
    {
        $sql = "SELECT *, SUBSTRING_INDEX(t2.codigo_producto,'-' , 1) AS tamano, t7.id_clase_articulo 
            FROM cliente_producto AS t1
            INNER JOIN productos AS t2 ON t1.id_producto = t2.id_productos
            INNER JOIN cliente_proveedor AS t3 ON t1.id_cli_prov = t3.id_cli_prov
            INNER JOIN usuarios AS t4 ON t4.id_usuario = t1.id_usuario
            INNER JOIN ruta_embobinado AS t5 ON t1.id_ruta_embobinado = t5.id_ruta_embobinado
            INNER JOIN core AS t6 ON t1.id_core = t6.id_core
            INNER JOIN tipo_articulo as t7 on  t2.id_tipo_articulo= t7.id_tipo_articulo
            WHERE t1.precio_autorizado = 0.00 OR t1.moneda_autoriza =0 AND t1.estado_client_produc= 1";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }
    public function asesores_precios()
    {
        $sql = "SELECT nombre,apellido,id_usuario FROM usuarios where id_roll IN(4,8)";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);

        return $resultado;
    }
    public function consultar_productos_asesor()
    {
        $sql = "SELECT t1.*,t2.codigo_producto,SUBSTRING_INDEX(t2.codigo_producto,'-' , 1) AS tamano, t2.descripcion_productos,t3.nombre_empresa,t4.nombre,t4.apellido,t5.nombre_core, t6.id_clase_articulo 
            FROM cliente_producto AS t1 
            INNER JOIN productos AS t2 ON t1.id_producto = t2.id_productos
            INNER JOIN cliente_proveedor AS t3 ON t1.id_cli_prov = t3.id_cli_prov
            INNER JOIN usuarios AS t4 ON t4.id_usuario = t1.id_usuario
            INNER JOIN core AS t5 ON t1.id_core = t5.id_core
            INNER JOIN tipo_articulo as t6 on  t2.id_tipo_articulo= t6.id_tipo_articulo
            WHERE t1.id_usuario =" . $_REQUEST['id'] . " AND t1.estado_client_produc = 1";

        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }
    public function consultar_productos_clientes_id_prov($id_cli_prov)
    {
        $sql = "SELECT *,client_proc.id_usuario,tipo_Art.id_clase_articulo FROM cliente_producto AS client_proc 
                INNER JOIN ruta_embobinado AS r ON client_proc.id_ruta_embobinado = r.id_ruta_embobinado
                INNER JOIN core AS core ON client_proc.id_core = core.id_core
		        INNER JOIN productos AS produc ON client_proc.id_producto = produc.id_productos   
                INNER JOIN cliente_proveedor as client_provee on  client_proc.id_cli_prov= client_provee.id_cli_prov      
                INNER JOIN tipo_articulo as tipo_Art on  produc.id_tipo_articulo= tipo_Art.id_tipo_articulo
                INNER JOIN usuarios AS usua ON usua.id_usuario = client_proc.id_usuario

            	WHERE client_proc.id_cli_prov =" . $id_cli_prov . " AND client_proc.estado_client_produc = 1";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        return $resultado;
    }
    public function cliente_producto_id_dell($data)
    {
        $sql = "SELECT *,client_proc.id_usuario,tipo_Art.id_clase_articulo FROM cliente_producto AS client_proc 
        INNER JOIN ruta_embobinado AS r ON client_proc.id_ruta_embobinado = r.id_ruta_embobinado
        INNER JOIN core AS core ON client_proc.id_core = core.id_core
        INNER JOIN productos AS produc ON client_proc.id_producto = produc.id_productos   
        INNER JOIN cliente_proveedor as client_provee on  client_proc.id_cli_prov= client_provee.id_cli_prov      
        INNER JOIN tipo_articulo as tipo_Art on  produc.id_tipo_articulo= tipo_Art.id_tipo_articulo
        INNER JOIN usuarios AS usua ON usua.id_usuario = client_proc.id_usuario
                WHERE client_proc.id_clien_produc=" . $data;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_produc_cli($id_cli_prov, $id_produc)
    {
        $sql = "SELECT * FROM `cliente_producto` WHERE id_producto=$id_produc AND id_cli_prov=$id_cli_prov";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    //     public function consultar_productos_clientes_config() {
    //         $sql = "SELECT * FROM cliente_producto AS client_proc 
    //             INNER JOIN ruta_embobinado AS r ON client_proc.id_ruta_embobinado = r.id_ruta_embobinado
    //             INNER JOIN core AS core ON client_proc.id_core = core.id_core
    // 		    INNER JOIN productos AS produc ON client_proc.id_producto = produc.id_productos
    //             WHERE client_proc.id_cli_prov = " . $_REQUEST['id']." AND client_proc.estado_client_produc = 1";

    //         $sentencia = $this->cnn->prepare($sql);
    //         $sentencia->execute();
    //         $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

    //         return $resultado;
    //     }

    //     public function modificar_cliente_producto() {
    //         $tabla = "cliente_producto";
    //         $nombres = array_keys($_POST);
    //         $datos = $_POST;
    //         $sql = insertar_generico::update($tabla, $nombres, $datos);

    //         $sql .= "id_clien_produc =" . ($_POST['id_clien_produc']) . "";

    //         $sentencia = $this->cnn->prepare($sql);
    //         $resultado = $sentencia->execute();

    //         return $resultado;
    //     }

    //     public function cambiar_estado_pedido() {
    //         $tabla = "pedidos";
    //         $nombres = array_keys($_POST);
    //         $datos = $_POST;
    //         $sql = insertar_generico::update($tabla, $nombres, $datos);

    //         $sql .= "id_pedido =" . ($_POST['id_pedido']) . "";
    // //
    //         $sentencia = $this->cnn->prepare($sql);
    //         $resultado = $sentencia->execute();

    //         return $sql;
    //     }

    //     public function ConsultaAsesores($id_cli_prov) {
    //         $sql = "SELECT t2.id_usuario, t3.id_persona, t3.nombres, t3.apellidos 
    //             FROM cliente_producto t1 
    //             INNER JOIN usuarios t2 ON t1.id_usuario = t2.id_usuario 
    //             INNER JOIN persona t3 ON t2.id_persona = t3.id_persona 
    //             WHERE t1.id_cli_prov = '$id_cli_prov' GROUP BY t1.id_usuario";
    //             $sentencia = $this->cnn->prepare($sql);
    //             $sentencia->execute();
    //             $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);

    //             return $resultado;
    //     }

    //     public function ProductosClienteAsesor($id_cli_prov, $id_usuario) {
    //         $sql = "SELECT t1.*, t2.codigo_producto, t2.tamano, t2.descripcion_productos, t3.nombre_articulo 
    //             FROM cliente_producto t1 
    //             INNER JOIN productos t2 ON t1.id_producto = t2.id_productos 
    //             INNER JOIN tipo_articulo t3 ON t2.id_tipo_articulo = t3.id_tipo_articulo 
    //             WHERE t1.id_cli_prov = '$id_cli_prov' AND t1.id_usuario = '$id_usuario'";
    //             $sentencia = $this->cnn->prepare($sql);
    //             $sentencia->execute();
    //             $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);

    //             return $resultado;
    //     }



    //     public function consultar_productos_asesor() {

    //         $sql = "SELECT * FROM cliente_producto AS client_proc 
    //                INNER JOIN ruta_embobinado AS r ON client_proc.id_ruta_embobinado = r.id_ruta_embobinado
    //                 INNER JOIN core AS core ON client_proc.id_core = core.id_core
    // 		            INNER JOIN productos AS produc ON client_proc.id_producto = produc.id_productos
    // 		            INNER JOIN usuarios AS usu ON client_proc.id_usuario = usu.id_usuario
    //                     INNER JOIN cliente_proveedor AS cli_p ON client_proc.id_cli_prov = cli_p.id_cli_prov 
    //                     WHERE client_proc.id_usuario = " . $_REQUEST['id_usuario']." AND client_proc.estado_client_produc = 1";
    //         $sentencia = $this->cnn->prepare($sql);
    //         $sentencia->execute();
    //         $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);

    //         return $resultado;
    //     }

    //     public function ConsultaProductoId($id_clien_produc)
    //     {
    //         $sql = "SELECT * FROM cliente_producto 
    //                     WHERE id_clien_produc = '$id_clien_produc'";
    //         $sentencia = $this->cnn->prepare($sql);
    //         $sentencia->execute();
    //         $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
    //         return $resultado;
    //     }

}

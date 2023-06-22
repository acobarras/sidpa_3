<?php

namespace MiApp\persistencia\dao;

use PDO;
use MiApp\persistencia\generico\GenericoDAO;
use MiApp\persistencia\vo\UsuarioVO;

class PedidosDAO extends GenericoDAO
{

    private $usuario;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn, 'pedidos');
        $this->usuario = new UsuarioVO();
    }

    public function consultar_descarga_pedido($num_pedido)
    {
        $sql = "SELECT * FROM pedidos WHERE num_pedido = '$num_pedido'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consulta_pedidos($parametro = '')
    {
        if ($parametro == '') {
            $sql = 'SELECT * FROM pedidos t1   
            INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov 
            INNER JOIN direccion t3 ON t1.id_dire_entre = t3.id_direccion
            INNER JOIN estado_pedido t4 ON t1.id_estado_pedido = t4.id_estado_pedido';
        } else {
            $sql = "SELECT * FROM pedidos t1   
            INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov = t2.id_cli_prov 
            INNER JOIN direccion t3 ON t1.id_dire_entre = t3.id_direccion
            INNER JOIN estado_pedido t4 ON t1.id_estado_pedido = t4.id_estado_pedido
            WHERE $parametro";
        }

        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_items_pendientes_op()
    {
        $sql = "SELECT t2.fecha_compromiso, t2.num_pedido, t2.observaciones, t1.item, t1.codigo, t1.id_pedido_item, t1.cant_bodega, t1.cant_op, t3.magnetico, 
                t3.descripcion_productos, t8.id_ruta_embobinado, t8.nombre_r_embobinado, t2.porcentaje, t2.difer_mas, t2.difer_menos, t2.difer_ext, 
                t4.nombre_empresa, t5.id_core, t6.nombre_core, t3.ubi_troquel, t3.cav_montaje, t3.ancho_material, t3.avance, t1.Cant_solicitada, 
                t1.cant_x, t5.id_material, t1.id_estado_item_pedido, t7.nombre_estado_item,t2.fecha_cierre  
            FROM pedidos_item t1
            INNER JOIN pedidos t2 ON t1.id_pedido = t2.id_pedido
            INNER JOIN productos t3 ON t1.codigo = t3.codigo_producto
            INNER JOIN cliente_proveedor t4 ON t2.id_cli_prov = t4.id_cli_prov
            INNER JOIN cliente_producto t5 ON t1.id_clien_produc = t5.id_clien_produc
            INNER JOIN core t6 ON t5.id_core = t6.id_core
            INNER JOIN estado_item_pedido t7 ON t1.id_estado_item_pedido= t7.id_estado_item_pedido
            INNER JOIN ruta_embobinado t8 ON t5.id_ruta_embobinado = t8.id_ruta_embobinado
            WHERE t1.id_estado_item_pedido = 2 ORDER BY t1.fecha_crea DESC";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);

        return $resultado;
    }
    public function consultar_nombre_pdf_oc($id_cli_prov, $nom_pdf)
    {
        $sql = "SELECT * FROM pedidos
                WHERE id_cli_prov=$id_cli_prov AND orden_compra LIKE '%$nom_pdf%'";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);

        return $resultado;
    }

    public function consultar_pedidos_cliente($consulta)
    {
        $sql = "SELECT *,'1' AS roll FROM pedidos   
                INNER JOIN cliente_proveedor ON pedidos.id_cli_prov=cliente_proveedor.id_cli_prov 
                INNER JOIN direccion dir ON pedidos.id_dire_entre = dir.id_direccion
                INNER JOIN estado_pedido est ON pedidos.id_estado_pedido=est.id_estado_pedido
                INNER JOIN usuarios usu ON pedidos.id_usuario=usu.id_usuario
                WHERE $consulta" ;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consultar_pedidos_ase($consulta)
    {
        $sql = "SELECT *,'1' AS roll FROM pedidos   
                INNER JOIN cliente_proveedor ON pedidos.id_cli_prov=cliente_proveedor.id_cli_prov 
                INNER JOIN direccion dir ON pedidos.id_dire_entre = dir.id_direccion
                INNER JOIN estado_pedido est ON pedidos.id_estado_pedido=est.id_estado_pedido
                INNER JOIN usuarios usu ON pedidos.id_usuario=usu.id_usuario
                WHERE $consulta" ;
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_pedidos_asesor()
    {

        if ($_SESSION['usuario']->getId_roll() == 1 || $_SESSION['usuario']->getId_roll() == 8) {
            $sql = 'SELECT *,"1" AS roll FROM pedidos   
                INNER JOIN cliente_proveedor ON pedidos.id_cli_prov=cliente_proveedor.id_cli_prov 
                INNER JOIN direccion dir ON pedidos.id_dire_entre = dir.id_direccion
                INNER JOIN estado_pedido est ON pedidos.id_estado_pedido=est.id_estado_pedido
                INNER JOIN usuarios usu ON pedidos.id_usuario=usu.id_usuario
                -- WHERE pedidos.fecha_crea_p >= DATE_SUB(CURDATE() ,INTERVAL 6 MONTH) AND pedidos.fecha_crea_p <= CURDATE()
                ';
        } else {
            $sql = 'SELECT *,"00" AS roll FROM pedidos   
                INNER JOIN cliente_proveedor ON pedidos.id_cli_prov=cliente_proveedor.id_cli_prov 
                INNER JOIN direccion dir ON pedidos.id_dire_entre = dir.id_direccion
                INNER JOIN estado_pedido est ON pedidos.id_estado_pedido=est.id_estado_pedido
                INNER JOIN usuarios usu ON pedidos.id_usuario=usu.id_usuario
                WHERE pedidos.id_usuario =' . $_SESSION['usuario']->getId_usuario();
        }


        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);

        return $resultado;
    }
    public function consultar_items_pedido($id_pedido = '')
    {
        if ($id_pedido == '') {
            $id_pedido = $_POST['id_pedido'];
        }
        $sql = "SELECT t5.cav_montaje, t1.n_produccion, t1.id_estado_item_pedido, t3.id_core, t4.id_ruta_embobinado, t1.id_pedido_item, 
            t1.item, t1.codigo, t5.descripcion_productos, t1.Cant_solicitada, t1.id_clien_produc, t4.nombre_r_embobinado, 
            t3.nombre_core, t1.cant_x, t1.trm, t1.moneda, t1.v_unidad, t1.total, t7.nombre_estado_item, t2.num_pedido,
            t2.difer_mas, t2.difer_menos, t2.difer_ext, t2.porcentaje
            FROM pedidos_item t1 
            INNER JOIN pedidos t2 ON t1.id_pedido = t2.id_pedido
            INNER JOIN core t3 ON t1.core = t3.id_core
            INNER JOIN ruta_embobinado t4 ON t1.ruta_embobinado = t4.id_ruta_embobinado
            INNER JOIN productos t5 ON t1.codigo = t5.codigo_producto
            INNER JOIN estado_item_pedido t7 ON t1.id_estado_item_pedido = t7.id_estado_item_pedido
            WHERE t1.id_pedido = $id_pedido ORDER BY t1.item ASC";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public function consultar_items_pedido_liberado($param)
    {


        $sql = "SELECT pedidos_item.id_pedido_item,pedidos_item.item,pedidos_item.codigo,p.descripcion_productos,
		pedidos_item.Cant_solicitada,cp.ficha_tecnica,cp.id_material,cp.id_clien_produc,rta.nombre_r_embobinado,
                 co.nombre_core,pedidos_item.cant_x,pedidos_item.trm,pedidos_item.moneda,pedidos_item.v_unidad,pedidos_item.total
                FROM `pedidos_item` 
		INNER JOIN core co ON pedidos_item.core = co.id_core
                 INNER JOIN ruta_embobinado rta ON pedidos_item.ruta_embobinado = rta.id_ruta_embobinado
                    INNER JOIN productos p ON pedidos_item.codigo = p.codigo_producto
                       INNER JOIN cliente_producto cp ON pedidos_item.id_clien_produc = cp.id_clien_produc
			WHERE id_pedido =" . $param['id_pedido'] . " ORDER BY pedidos_item.item ASC";

        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);

        return $resultado;
    }
    public function consulta_ped_permitidos()
    {
        $sql = "SELECT t1.id_cli_prov, t2.nit, t2.nombre_empresa,COUNT(t1.id_cli_prov) as paso_pedido FROM pedidos t1
        INNER JOIN cliente_proveedor t2 ON t1.id_cli_prov=t2.id_cli_prov 
        WHERE t1.paso_pedido = 1 GROUP BY t2.nombre_empresa";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
}

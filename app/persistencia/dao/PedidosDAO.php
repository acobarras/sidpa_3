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
                t4.nombre_empresa, t5.id_core, t6.nombre_core, t3.troquel,t3.ubi_troquel, t3.cav_montaje, t3.ancho_material, t3.avance, t1.Cant_solicitada, 
                t1.cant_x, t5.id_material, t1.id_estado_item_pedido, t7.nombre_estado_item,t2.fecha_cierre,t5.observaciones_ft  
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
                WHERE $consulta";
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
                WHERE $consulta";
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
    public function consulta_pedido_direccion($num_pedido)
    {
        $sql = "SELECT t1.num_pedido,t2.direccion,t2.celular,t2.telefono,t2.email,t2.contacto,t2.cargo,t2.link_maps,t2.ruta,t3.nombre AS nombre_pais,t4.nombre AS nombre_ciudad,t5.nombre AS nombre_depa,t6.nombre_empresa 
        FROM pedidos t1 
        INNER JOIN direccion t2 ON t2.id_direccion=t1.id_dire_entre 
        INNER JOIN pais t3 ON t2.id_pais=t3.id_pais 
        INNER JOIN ciudad t4 ON t4.id_ciudad=t2.id_ciudad 
        INNER JOIN departamento t5 ON t5.id_departamento=t2.id_departamento 
        INNER JOIN cliente_proveedor t6 ON t6.id_cli_prov=t1.id_cli_prov
        WHERE t1.num_pedido=$num_pedido";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consulta_pedidos_atrasados($param)
    {
        if ($param['tipo'] == "1") {
            $condicion = "t4.forma_pago=4 AND t3.fecha_compromiso !='0000-00-00'";
        } else if ($param['tipo'] == "2") {
            $condicion = "t4.forma_pago!=4 AND t3.fecha_compromiso !='0000-00-00'";
        } else {
            $condicion = "t3.fecha_compromiso ='0000-00-00'";
        }
        $sql = "SELECT t3.id_pedido, t3.num_pedido, t3.fecha_crea_p, t3.hora_crea , t3.fecha_compromiso, t3.fecha_cierre, 
        COUNT(t2.item) AS items_reportados, t4.nombre_empresa, t4.forma_pago, t5.nombres, t5.apellidos 
        FROM entregas_logistica t1 
        INNER JOIN pedidos_item t2 ON t1.id_pedido_item = t2.id_pedido_item 
        INNER JOIN pedidos t3 ON t2.id_pedido = t3.id_pedido 
        INNER JOIN cliente_proveedor t4 ON t3.id_cli_prov = t4.id_cli_prov 
        INNER JOIN persona t5 ON t3.id_persona = t5.id_persona 
        WHERE t1.id_factura = 0 AND t1.fecha_cargue IS NULL AND $condicion GROUP BY t3.num_pedido ORDER BY t3.fecha_compromiso ASC;";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function conteo_items_pedido($id_pedido)
    {
        $sql = "SELECT COUNT(t1.item) as items_pedido, COUNT(CASE WHEN t1.n_produccion<>0 THEN 1 END) AS items_op 
        FROM pedidos_item t1 WHERE id_pedido=$id_pedido";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consulta_items_idpedido($id_pedido)
    {
        $sql = "SELECT t1.id_pedido_item, t1.n_produccion,t1.item,t1.Cant_solicitada,t1.cant_bodega,t1.cant_op,t1.total,t1.id_estado_item_pedido,t1.orden_compra,t3.codigo_producto,t3.tamano,
        t3.descripcion_productos,t4.nombre_estado_item
        FROM pedidos_item t1 
        INNER JOIN cliente_producto t2 ON t1.id_clien_produc=t2.id_clien_produc 
        INNER JOIN productos t3 ON t3.id_productos=t2.id_producto 
        INNER JOIN estado_item_pedido t4 ON t4.id_estado_item_pedido=t1.id_estado_item_pedido
        WHERE t1.id_pedido=$id_pedido;";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
    public function consulta_items_incompletos()
    {
        // $sql = "SELECT t1.id_pedido_item,t1.cantidad_factura,t1.id_factura,t2.item,t2.codigo,t2.Cant_solicitada,t3.num_pedido,t3.parcial,t3.porcentaje,t3.difer_mas,t3.difer_menos,t3.difer_ext 
        // FROM entregas_logistica t1 
        // INNER JOIN pedidos_item t2 ON t2.id_pedido_item=t1.id_pedido_item 
        // INNER JOIN pedidos t3 on t3.id_pedido=t2.id_pedido 
        // WHERE t1.id_factura!=0 AND t1.cantidad_factura<t2.Cant_solicitada";
        $sql = "SELECT t1.id_pedido_item,t1.id_factura,t2.item,t2.codigo,t2.Cant_solicitada,t3.num_pedido,t3.parcial,t3.porcentaje,t3.difer_mas,t3.difer_menos,t3.difer_ext, 
        SUM(CASE WHEN t1.id_factura = 0 THEN t1.cantidad_factura ELSE 0 END) AS cant_reportada, 
        SUM(CASE WHEN t1.id_factura != 0 THEN t1.cantidad_factura ELSE 0 END) AS cant_facturada, 
        (CASE WHEN t3.difer_menos = 1 THEN (t2.Cant_solicitada-((t3.porcentaje/100)*t2.Cant_solicitada)) 
        ELSE t2.Cant_solicitada END) AS cant_minima
        FROM entregas_logistica t1 
        INNER JOIN pedidos_item t2 ON t2.id_pedido_item=t1.id_pedido_item 
        INNER JOIN pedidos t3 on t3.id_pedido=t2.id_pedido  
        GROUP BY t1.id_pedido_item
        HAVING cant_facturada<cant_minima;";
        $sentencia = $this->cnn->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }
}

<?php

namespace MiApp\negocio\controladores;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\dias_festivosDAO;
use MiApp\persistencia\dao\dias_produccionDAO;
use MiApp\persistencia\dao\Modulos_hojasDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\trmDAO;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\control_facturacionDAO;
use MiApp\persistencia\dao\PortafolioDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\cliente_productoDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\negocio\util\Envio_Correo;
use MiApp\negocio\util\Validacion;


class PruebasControlador extends GenericoControlador
{
    private $dias_produccionDAO;
    private $dias_festivosDAO;
    private $personaDAO;
    private $trmDAO;
    private $clientes_proveedorDAO;
    private $control_facturacionDAO;
    private $PortafolioDAO;
    private $SeguimientoOpDAO;
    private $PedidosItemDAO;
    private $PedidosDAO;
    private $cliente_productoDAO;
    private $productosDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->dias_festivosDAO = new dias_festivosDAO($cnn);
        $this->dias_produccionDAO = new dias_produccionDAO($cnn);
        $this->personaDAO = new PersonaDAO($cnn);
        $this->trmDAO = new trmDAO($cnn);
        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
        $this->PortafolioDAO = new PortafolioDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->cliente_productoDAO = new cliente_productoDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
    }

    public function pruebas()
    {
        set_time_limit(3200);
        header('Content-Type: application/json');
        Envio_Correo::pruebas();
        // COLOCAR EL PRECIO AUTORIZADO DE LA TECNOLOGIA EN 0 
        // $sql = "SELECT * FROM cliente_producto t1
        // INNER JOIN productos t2 ON t2.id_productos=t1.id_producto
        // INNER JOIN tipo_articulo t3 ON t2.id_tipo_articulo=t3.id_tipo_articulo
        // INNER JOIN clase_articulo t4 ON t4.id_clase_articulo=t3.id_clase_articulo
        // WHERE t4.id_clase_articulo=3 AND t1.estado_client_produc=1 AND t2.estado_producto=1";
        // $sentencia = $this->cnn->prepare($sql);
        // $sentencia->execute();
        // $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        // foreach ($resultado as $value) {
        //     $nuevo_valor = 0.00;
        //     $envio = [
        //         'precio_autorizado' => $nuevo_valor,
        //     ];
        //     $condicion = 'id_clien_produc =' . $value->id_clien_produc;
        //     $this->cliente_productoDAO->editar($envio, $condicion);
        // }
        // echo 'Proceso Terminado .....';

        // DISMINUCION DE PRECIOS PARA CLIENTES EN ESPECIFICO
        // $sql = "SELECT t4.nombre_empresa ,t3.nombre_articulo, t4.nit,t1.*,t2.codigo_producto
        // FROM cliente_producto t1 
        // INNER JOIN productos t2 ON t1.id_producto = t2.id_productos 
        // INNER JOIN tipo_articulo t3 ON t2.id_tipo_articulo = t3.id_tipo_articulo 
        // INNER JOIN cliente_proveedor t4 ON t1.id_cli_prov= t4.id_cli_prov 
        // WHERE t3.id_clase_articulo = 2 AND t4.nit IN(800141506,901236507,860353641, 800214937,800020274,811021607, 830093741,830042322) AND t4.estado_cli_prov=1";
        // $sentencia = $this->cnn->prepare($sql);
        // $sentencia->execute();
        // $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        // foreach ($resultado as $value) {
        //     $precio_autori = $value->precio_autorizado;
        //     $nuevo_valor = $precio_autori - (($precio_autori * 2) / 100);
        //     $nuevo_valor = number_format($nuevo_valor, 2, '.', '');
        //     $envio = [
        //         'precio_autorizado' => $nuevo_valor,
        //         'precio_venta' => $nuevo_valor,
        //     ];
        //     $condicion = 'id_clien_produc =' . $value->id_clien_produc;
        //     $this->cliente_productoDAO->editar($envio, $condicion);
        // }
        // echo 'Proceso Terminado .....';


        // $cambio = [
        //     28385 => ['fecha_pago' => '2023-01-26', 'fecha_recibe' => '2023-02-07']
        // ];
        // foreach ($cambio as $key => $value) {
        //     $sql = "SELECT * FROM portafolio WHERE num_factura IN ($key)";
        //     $sentencia = $this->cnn->prepare($sql);
        //     $sentencia->execute();
        //     $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        //     $edita = [
        //         'id_usu_pago' => 57,
        //         'fecha_usu_pago' => $value['fecha_recibe'],
        //         'fecha_pago' => $value['fecha_pago'],
        //         'estado_portafolio' => 3
        //     ];
        //     $condicion = 'num_factura = ' . $key;
        //     $this->PortafolioDAO->editar($edita, $condicion);
        // }

        // Envio_Correo::pruebas();
        // $sql = "SELECT * FROM `productos` WHERE id_tipo_articulo = 1 AND consumo IS NULL";
        // $sentencia = $this->cnn->prepare($sql);
        // $sentencia->execute();
        // $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        // foreach ($resultado as $key => $value) {
        //     if ($value->consumo == null) {
        //         $consumo_auto = Validacion::consumo_etiqueta($value->codigo_producto);
        //         $edita_registro = ['consumo' => $consumo_auto];
        //         $condicion = 'id_productos = ' . $value->id_productos;
        //         $this->productosDAO->editar($edita_registro, $condicion);
        //     } 
        // }
        // echo 'Proceso Terminado ....';
        // $edita_consumo = [
        //     195 => '0.011139',
        //     400 => '0.011669',
        //     1127 => '0.011669'
        // ];
        // $sql = "SELECT * FROM productos WHERE id_tipo_articulo = 1";
        // $sentencia = $this->cnn->prepare($sql);
        // $sentencia->execute();
        // $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        // foreach ($resultado as $key => $value) {
        //     foreach ($edita_consumo as $key => $value_consumo) {
        //         if ($value->id_productos == $key) {
        //             $edita_registro = ['consumo' => $value_consumo];
        //             $condicion = 'id_productos = '.$key;
        //             $this->productosDAO->editar($edita_registro,$condicion);
        //         }
        //     }
        // }
        // echo 'Proceso Terminado .....';

        // Este codigo realiza el incremento de precios de todos los clientes
        // $sql = "SELECT t4.nombre_empresa ,t3.nombre_articulo, t4.nit,t1.* 
        // FROM cliente_producto t1 INNER JOIN productos t2 ON t1.id_producto = t2.id_productos 
        // INNER JOIN tipo_articulo t3 ON t2.id_tipo_articulo = t3.id_tipo_articulo 
        // INNER JOIN cliente_proveedor t4 ON t1.id_cli_prov= t4.id_cli_prov 
        // WHERE t3.id_clase_articulo = 2 AND t4.nit NOT IN(800020274,901236507,830042322,830093741,811021607,
        // 900570211,860353641,800141506,800214937, 208068971,890938755,830010738,800251569,555555555,860516806,860003216) 
        // AND t4.estado_cli_prov=1";
        // $sentencia = $this->cnn->prepare($sql);
        // $sentencia->execute();
        // $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        // foreach ($resultado as $value) {
        //     $precio_autori = $value->precio_autorizado;
        //     $incremento = ($precio_autori * 10) / 100;
        //     $nuevo_valor = $precio_autori + $incremento;
        //     $envio = ['precio_autorizado' => $nuevo_valor];
        //     $condicion = 'id_clien_produc =' . $value->id_clien_produc;
        //     $this->cliente_productoDAO->editar($envio, $condicion);
        // }
        // echo 'Proceso Terminado .....';


        // Este codigo realiza el incremento de precios de clientes seleccionados
        // $sql = "SELECT t4.nombre_empresa ,t3.nombre_articulo, t4.nit,t1.* FROM cliente_producto t1 
        // INNER JOIN productos t2 ON t1.id_producto = t2.id_productos 
        // INNER JOIN tipo_articulo t3 ON t2.id_tipo_articulo = t3.id_tipo_articulo 
        // INNER JOIN cliente_proveedor t4 ON t1.id_cli_prov= t4.id_cli_prov 
        // WHERE t3.id_clase_articulo = 2 AND t4.nit IN(208068971,890938755,830010738) AND t4.estado_cli_prov=1";
        // $sentencia = $this->cnn->prepare($sql);
        // $sentencia->execute();
        // $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        // foreach ($resultado as $value) {
        //     $precio_autori = $value->precio_autorizado;
        //     $incremento = ($precio_autori * 8) / 100;
        //     $nuevo_valor = $precio_autori + $incremento;
        //     $envio = ['precio_autorizado' => $nuevo_valor];
        //     $condicion = 'id_clien_produc =' . $value->id_clien_produc;
        //     $this->cliente_productoDAO->editar($envio, $condicion);
        // }
        // echo 'Proceso Terminado .....';


        // Este es el codigo de portafolio
        // $sql = "SELECT * FROM `portafolio` ";
        // $sentencia = $this->cnn->prepare($sql);
        // $sentencia->execute();
        // $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        // $datos = [];
        // foreach ($resultado as $value) {
        //     $sql = "SELECT * FROM `cliente_proveedor`WHERE id_cli_prov=" . $value->id_cli_prov;
        //     $sentencia = $this->cnn->prepare($sql);
        //     $sentencia->execute();
        //     $resultado1 = $sentencia->fetchAll(\PDO::FETCH_OBJ);

        //     $fecha = date_create($value->fecha_factura);
        //     $fecha_venci = '';
        //     if (DIAS_DADOS[$resultado1[0]->dias_dados] == 0) {
        //         $fecha_venci = $value->fecha_factura;
        //     }else{
        //         $fecha_venci = Validacion::aumento_fechas($fecha, intval(SOLO_DIAS_DADOS[$resultado1[0]->dias_dados]));
        //     }
        //     $datos = [
        //         'dias_dados' => SOLO_DIAS_DADOS[$resultado1[0]->dias_dados],
        //         'fecha_factura' => $value->fecha_factura,
        //         'fecha_vencimiento' =>$fecha_venci ,
        //     ];
        //    
        // }

        // // Actualizar la fecha de compromiso de los pedidos 
        // $sql = "SELECT * FROM `pedidos` WHERE fecha_compromiso='0000-00-00'";
        // $sentencia = $this->cnn->prepare($sql);
        // $sentencia->execute();
        // $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        // // $datos = [];
        // foreach ($resultado as  $value) {
        //     $fecha_compro = $this->PedidosItemDAO->ValidaFechaCompromiso($value->id_pedido);
        //     if ($fecha_compro != '0000-00-00') {
        //         // Editar el pedido
        //         $pedido['fecha_compromiso'] = $fecha_compro;
        //         $condicion_pedido = 'id_pedido =' . $value->id_pedido;
        //         $this->PedidosDAO->editar($pedido, $condicion_pedido);
        //     }
        // }

        // Colocar los documentos remision en la columna remision y dejar la factura en 0
        // $sql = "SELECT * FROM control_facturas";
        // $sentencia = $this->cnn->prepare($sql);
        // $sentencia->execute();
        // $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        // foreach ($resultado as $value) {
        //     if ($value->id_control_factura == 11 || $value->id_control_factura == 12) {
        //         $edita = [
        //             'num_factura' => 0,
        //             'num_remision' => $value->num_factura
        //         ];
        //         $condicion = 'id_control_factura = '. $value->id_control_factura;
        //         $this->control_facturacionDAO->editar($edita,$condicion);

        //     }
        // }
        // $sql = "SELECT t1.*, t2.id_persona FROM direccion t1 INNER JOIN usuarios t2 ON t1.id_usuario = t2.id_usuario
        // WHERE t1.estado_direccion != 0 AND t1.id_usuario != 0 AND t1.id_usuario != 1 
        // AND t1.id_cli_prov != 0 AND t1.id_cli_prov GROUP BY t1.id_cli_prov, t1.id_usuario";

        // $sentencia = $this->cnn->prepare($sql);
        // $sentencia->execute();
        // $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        // $asesores = [];
        // foreach ($resultado as $value) {
        //     if (empty($asesores)) {
        //         $asesores[] = [
        //             'id_cli_prov' => $value->id_cli_prov,
        //             'asesor' => $value->id_persona
        //         ];
        //     } else {
        //         $agrega = false;
        //         foreach ($asesores as $key => $value1) {
        //             if (intval($value1['id_cli_prov']) == intval($value->id_cli_prov)) {
        //                 $agrega = false;
        //             } else {
        //                 $agrega = true;
        //             }
        //         }
        //         if ($agrega) {
        //             $asesores[] = [
        //                 'id_cli_prov' => $value->id_cli_prov,
        //                 'asesor' => $value->id_persona
        //             ];
        //         }
        //     }
        // }
        // $asesores2 = [];
        // foreach ($asesores as $ase) {
        //     $total = '';
        //     $id = $ase['id_cli_prov'];
        //     foreach ($resultado as $dupli) {
        //         if ($ase['id_cli_prov'] == $dupli->id_cli_prov) {
        //             if ($total == '') {
        //                 $total .= $dupli->id_persona;
        //             } else {
        //                 $total .= ",".$dupli->id_persona;
        //             }
        //         }
        //     }
        //     $asesores2[] = [
        //         'id_cli_prov' => $id,
        //         'id_usuarios_asesor' => $total
        //     ];
        // }

        // // Editar la tabla cliente proveedor
        // foreach ($asesores2 as $edit) {
        //     $edita = ['id_usuarios_asesor' => $edit['id_usuarios_asesor']];
        //     $condicion = 'id_cli_prov ='.$edit['id_cli_prov'];
        //     $this->clientes_proveedorDAO->editar($edita,$condicion);

        // }
        // print_r('Proceso terminado....');
        // print_r($asesores2);
        // print_r('<br>');
        // echo json_encode($asesores2);

        // $cupo = [
        //     900285666 => 13000000,
        //     800230708 => 3000000,
        // ];
        // $contador = 0;
        // foreach ($cupo as $key => $value) {
        //     $sql = "SELECT * FROM cliente_proveedor WHERE nit =" . $key;
        //     $sentencia = $this->cnn->prepare($sql);
        //     $sentencia->execute();
        //     $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        //     if (!empty($resultado)) {
        //         $contador = $contador + 1;
        //         $edita = ['cupo_cliente' => $value];
        //         $condicion = 'id_cli_prov ='.$resultado[0]->id_cli_prov;
        //         $this->clientes_proveedorDAO->editar($edita,$condicion);
        //     } 
        // }
        // print_r('Proceso terminado....');

        // $vence = [
        //             17788 => '2022-01-26',
        // ];
        // foreach ($vence as $key => $value) {
        //     $sql = "SELECT * FROM portafolio WHERE num_factura =" . $key;
        //     $sentencia = $this->cnn->prepare($sql);
        //     $sentencia->execute();
        //     $resultado = $sentencia->fetchAll(\PDO::FETCH_OBJ);
        //     if (!empty($resultado)) {
        //         $edita = ['fecha_pago' => $value, 'fecha_usu_pago' => $value, 'estado_portafolio' => 3, 'id_usu_pago' => 16];
        //         $condicion = 'id_portafolio =' . $resultado[0]->id_portafolio;
        //         $this->PortafolioDAO->editar($edita, $condicion);
        //     }
        // }

    }
}

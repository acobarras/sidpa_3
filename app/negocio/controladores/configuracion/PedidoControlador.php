<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\util\Archivo;
use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\EstadoItemPedidoDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\cliente_productoDAO;

class PedidoControlador extends GenericoControlador
{

    private $PedidosDAO;
    private $clientes_proveedorDAO;
    private $PedidosItemDAO;
    private $EntregasLogisticaDAO;
    private $PersonaDAO;
    private $direccionDAO;
    private $EstadoItemPedidoDAO;
    private $entrada_tecnologiaDAO;
    private $ItemProducirDAO;
    private $productosDAO;
    private $cliente_productoDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->EstadoItemPedidoDAO = new EstadoItemPedidoDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->cliente_productoDAO = new cliente_productoDAO($cnn);
    }

    public function vista_modificar_pedido()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_modificar_pedido',
            [
                "clientes" => $this->clientes_proveedorDAO->consultar_clientes(),
                "personas" => $this->PersonaDAO->consultar_personas(),
                "productos" => $this->productosDAO->consultar_productos_material(),
            ]
        );
    }

    public function consultar_pedido($id = '')
    {
        header('Content-Type: application/json');
        if ($id == '') {
            if ($_POST['tipo_consulta'] == '' and $_POST['dato_consulta'] == '') {
                $pedidos = $this->PedidosDAO->consulta_pedidos();
            } else {
                if ($_POST['tipo_consulta'] == 'num_pedido') {
                    $parametro = "t1." . $_POST['tipo_consulta'] . " = " . $_POST['dato_consulta'];
                }
                if ($_POST['tipo_consulta'] == 'id_cli_prov') {
                    $parametro = "t2." . $_POST['tipo_consulta'] . " = " . $_POST['dato_consulta'];
                } else {
                    $parametro = "t1." . $_POST['tipo_consulta'] . " LIKE '%" . $_POST['dato_consulta'] . "%'";
                }
                $pedidos = $this->PedidosDAO->consulta_pedidos($parametro);
            }
        } else {
            $parametro = "t1.id_pedido =" . $id;
            $pedidos = $this->PedidosDAO->consulta_pedidos($parametro);
        }

        $respuesta = $pedidos;
        echo json_encode($respuesta);
    }

    public function eliminar_pedido()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        // ELIMINAR LOS REGISTROS DE FACTURACION
        $item_factura = $this->PedidosItemDAO->ConsultaIdPedido($datos['id_pedido']);
        foreach ($item_factura as $elimina_entrega) {
            $condicion_elimina = 'id_pedido_item =' . $elimina_entrega->id_pedido_item;
            $this->EntregasLogisticaDAO->eliminar($condicion_elimina);
        }
        // ELIMINAR ITEM PEDIDO
        $condicion_elimina_item = 'id_pedido =' . $datos['id_pedido'];
        $this->PedidosItemDAO->eliminar($condicion_elimina_item);
        // ELIMINACION PEDIDO
        $condicion_elimina_pedido = 'id_pedido =' . $datos['id_pedido'];
        $eliminar = $this->PedidosDAO->eliminar($condicion_elimina_pedido);
        echo json_encode($eliminar);
        return;
    }

    public function consultar_direccion_cliente()
    {
        header('Content-Type: application/json');
        $resultado = $this->direccionDAO->consulta_direccion_cliente($_POST['nit']);
        foreach ($resultado as $value) {
            $value->nombre_forma_pago = FORMA_PAGO[$value->forma_pago];
        }
        echo json_encode($resultado);
    }

    public function consultar_items_pedido()
    {
        header('Content-Type: application/json');
        $id_pedido = $_POST['id_pedido'];
        $resultado = $this->PedidosItemDAO->ConsultaIdPedido($id_pedido);
        $estados_item = $this->EstadoItemPedidoDAO->consultar_estados_items();
        foreach ($resultado as $value) {
            $value->estados_item = $estados_item;
            $value->roll = $_SESSION['usuario']->getId_roll();
        }
        $respu['data'] = $resultado;
        echo json_encode($respu);
        return;
    }

    public function eliminar_item_pedido()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $estado = $this->PedidosItemDAO->ConsultaIdPedidoItem($_POST['id_pedido_item']);
        $num_pedido = $estado[0]->num_pedido;
        $item = $estado[0]->item;
        $documento_consulta = $num_pedido . "-" . $item;
        $salida_inv = $this->entrada_tecnologiaDAO->consultar_salida_item_pedido($documento_consulta);
        $registros = count($salida_inv);
        $es1 = 0;
        $noes1 = 0;
        foreach ($salida_inv as $salida) {
            if ($salida->estado_inv == 2 || $salida->estado_inv == 3) {
                $noes1 = $noes1 + 1;
            } else {
                $es1 = $es1 + 1;
            }
        }
        $elimina_item_pedido = false;
        $ajuste_inventario = 0;
        $editar_op = false;
        $produccion = $this->ItemProducirDAO->consultar_item_producir_num($datos['n_produccion']);
        if ($registros == $es1) {
            if ($datos['n_produccion'] == 0) {
                $ajuste_inventario = 2; // No se elimina pero si se edita al estado 4
                $elimina_item_pedido = true;
            } else {
                if ($produccion[0]->estado_item_producir <= 5) {
                    $ajuste_inventario = 2; // No se elimina pero si se edita al estado 4
                    $elimina_item_pedido = true;
                    $editar_op = true;
                } else {
                    $envio = [
                        'estado' => -1,
                        'mensaje' => 'Lo sentimos este item se encuentra en proceso de Producción y no puede ser anulado.'
                    ];
                    echo json_encode($envio);
                    $respu = $envio;
                }
            }
        } else if ($registros == $noes1) {
            if ($datos['n_produccion'] == 0) {
                $ajuste_inventario = 1; // Que se puede eliminar el registro
                $elimina_item_pedido = true;
            } else {
                if ($produccion[0]->estado_item_producir <= 5) {
                    $ajuste_inventario = 1; // Que se puede eliminar el registro
                    $elimina_item_pedido = true;
                    $editar_op = true;
                } else {
                    $envio = [
                        'estado' => -1,
                        'mensaje' => 'Lo sentimos este item se encuentra en proceso de Producción y no puede ser anulado.'
                    ];
                    echo json_encode($envio);
                    $respu = $envio;
                }
            }
        } else {
            $envio = [
                'estado' => -1,
                'mensaje' => 'Lo sentimos este item se encuentra en proceso de alistamiento.'
            ];
            echo json_encode($envio);
            $respu = $envio;
        }
        if ($elimina_item_pedido) {
            $id = $_POST['id_pedido_item'];
            $condicion = 'id_pedido_item =' . $id;
            $this->PedidosItemDAO->eliminar($condicion);
            $id_pedido = $estado[0]->id_pedido;
            $total_etiq = $this->PedidosItemDAO->SumaVentaProducto($id_pedido, '!=3');
            $total_tecno = $this->PedidosItemDAO->SumaVentaProducto($id_pedido, '=3');
            $editar_pedido = array(
                'total_tec' => $total_tecno[0]->total,
                'total_etiq' => $total_etiq[0]->total
            );
            $condicion = 'id_pedido =' . $id_pedido;
            $this->PedidosDAO->editar($editar_pedido, $condicion);
            $envio = $this->consultar_pedido($estado[0]->id_pedido);
            $respu = $envio;
        }

        if ($ajuste_inventario != 0) {
            foreach ($salida_inv as $ejecuta) {
                if ($ajuste_inventario == 1) {
                    $condicion = 'id_ingresotec =' . $ejecuta->id_ingresotec;
                    $this->entrada_tecnologiaDAO->eliminar($condicion);
                } else {
                    $editar_registro = array('estado_inv' => 1);
                    $condicion = 'id_ingresotec =' . $ejecuta->id_ingresotec;
                    $this->entrada_tecnologiaDAO->editar($editar_registro, $condicion);
                }
            }
        }
        if ($editar_op) {
            $datos_op = $this->ItemProducirDAO->consultar_item_producir_num($datos['n_produccion']);
            $id_item_producir = $datos_op[0]->id_item_producir;
            $avance = $estado[0]->avance;
            $cav_montaje = $estado[0]->cav_montaje;
            $nueva_cantidad_op = $datos_op[0]->cant_op - $estado[0]->cant_op;
            $nuevo_ml = round(($avance * $nueva_cantidad_op) / ($cav_montaje * 1000));
            $edita_op = array('mL_total' => $nuevo_ml, 'cant_op' => $nueva_cantidad_op);
            $condicion_op = 'id_item_producir =' . $id_item_producir;
            $this->ItemProducirDAO->editar($edita_op, $condicion_op);
        }
        return $respu;
    }

    public function agregar_item_pedido()
    {
        header('Content-Type: application/json');
        $datos = $_POST['datos'];
        $cantidad = $_POST['cantidad'];
        $trm = $_POST['trm'];
        $id_pedido = $_POST['id_pedido'];
        $cons_item = $this->PedidosItemDAO->ConsultaUltimoItem($id_pedido);
        $parametro = 't1.id_pedido = ' . $id_pedido;
        $cons_pedido = $this->PedidosDAO->consulta_pedidos($parametro);
        $item = $cons_item[0]->ultimo_item + 1;
        $total_item = $datos['precio_venta'] * $cantidad;
        if ($datos['moneda'] == 2) {
            $total_item = ($datos['precio_venta'] * $trm) * $cantidad;
        }
        $datos['num_pedido'] = $cons_pedido[0]->num_pedido;
        $datos['item'] = $item;
        $ingresa_item = false;
        $correo = false;
        if ($datos['id_clase_articulo'] == 1) { // Esto es bobinas
            $datos['cant_bodega'] = 0;
            $datos['cant_op'] = 0;
            $datos['id_estado_item_pedido'] = 1;
            $ingresa_item = true;
        } else { // Solamente ingresa etiquetas y tecnologia
            $cant_inven = $this->entrada_tecnologiaDAO->consultar_inv_product($datos['id_productos']);
            if (floatval($cant_inven[0]->total) <= 0) { // La cantidad del inventario es menor que 0
                $datos['cant_bodega'] = 0;
                $datos['cant_op'] = $cantidad;
                $datos['id_estado_item_pedido'] = 2;
                if ($datos['id_clase_articulo'] == 3) {
                    $datos['cant_op'] = 0;
                    $datos['id_estado_item_pedido'] = 5;
                    $correo = true;
                }
                $ingresa_item = true;
            } elseif (floatval($cant_inven[0]->total) <= floatval($cantidad)) { // La cantidad del inventario es menor a lo solicitado
                $this->descuenta_inventario($datos, $cant_inven[0]->total, 3);
                $pendiente_op = floatval($cantidad) - floatval($cant_inven[0]->total);
                $datos['cant_bodega'] = $cant_inven[0]->total;
                $datos['cant_op'] = $pendiente_op;
                $datos['id_estado_item_pedido'] = 2;
                if ($datos['id_clase_articulo'] == 3) {
                    $datos['cant_op'] = 0;
                    $datos['id_estado_item_pedido'] = 5;
                    $correo = true;
                }
                $ingresa_item = true;
            } else { // la cantidad del inventario es mayor a la solicitada
                $this->descuenta_inventario($datos, $cantidad, 2);
                $datos['cant_bodega'] = $cantidad;
                $datos['cant_op'] = 0;
                $datos['id_estado_item_pedido'] = 17;
                $ingresa_item = true;
            }
        }

        $agregar_item_pedido = array(
            'id_pedido' => $id_pedido,
            'item' => $item,
            'id_clien_produc' => $datos['id_clien_produc'],
            'codigo' => $datos['codigo_producto'],
            'Cant_solicitada' => $cantidad,
            'cant_bodega' => $datos['cant_bodega'],
            'cant_op' => $datos['cant_op'],
            'ruta_embobinado' => $datos['id_ruta_embobinado'],
            'core' => $datos['id_core'],
            'cant_x' => $datos['presentacion'],
            'trm' => $trm,
            'moneda' => $datos['nom_mon_venta'],
            'v_unidad' => $datos['precio_venta'],
            'total' => $total_item,
            'id_estado_item_pedido' => $datos['id_estado_item_pedido'],
            'id_usuario' => $_SESSION['usuario']->getid_usuario(),
            'fecha_crea' => date('Y-m-d'),
        );
        if ($ingresa_item) {
            $this->PedidosItemDAO->insertar($agregar_item_pedido);
            $total_etiq = $this->PedidosItemDAO->SumaVentaProducto($id_pedido, '!=3');
            $total_tecno = $this->PedidosItemDAO->SumaVentaProducto($id_pedido, '=3');
            $editar_pedido = array(
                'total_tec' => $total_tecno[0]->total,
                'total_etiq' => $total_etiq[0]->total,
                'id_estado_pedido' => 4
            );
            $condicion = 'id_pedido =' . $id_pedido;
            $this->PedidosDAO->editar($editar_pedido, $condicion);
            $respu = $this->consultar_pedido($id_pedido);
            return $respu;
        }
    }

    public function descuenta_inventario($datos, $cantidad, $estado)
    {
        $ubicaciones = $this->entrada_tecnologiaDAO->consultar_seguimiento_producto($datos['id_productos']);
        $descuento = [];
        foreach ($ubicaciones as $value) {
            if ($cantidad > 0) {
                if ($value->total > 0) {
                    if ($cantidad >= $value->total) {
                        $salida = $value->total;
                        $cantidad = $cantidad - $value->total;
                    } else {
                        $salida = $cantidad;
                        $cantidad = $cantidad - $cantidad;
                    }
                    $descuento[] = [
                        'documento' => $datos['num_pedido'] . "-" . $datos['item'],
                        'ubicacion' => $value->ubicacion,
                        'codigo_producto' => $datos['codigo_producto'],
                        'id_productos' => $datos['id_productos'],
                        'salida' => $salida,
                        'estado_inv' => $estado,
                        'fecha_crea' => date('Y-m-d H:i:s')
                    ];
                }
            }
        }
        foreach ($descuento as $items) {
            $this->entrada_tecnologiaDAO->insertar($items);
        }
        return true;
    }

    public function editar_item_pedido()
    {
        header('Content-Type: application/json');
        $datos = $_POST['datos'];
        $data_exist = $_POST['data_exist'];
        $form_envio = $_POST['form_envio'];
        $editar_item = array();
        if ($form_envio == 1) {
            $editar_item['id_estado_item_pedido'] = $datos;
        }
        $condicion = 'id_pedido_item =' . $data_exist['id_pedido_item'];
        $editar = $this->PedidosItemDAO->editar($editar_item, $condicion);
        $respu = $this->consultar_pedido($data_exist['id_pedido']);
        return $respu;
    }

    public function modificar_tipo_material()
    {
        header("Content-type: application/json; charset=utf-8");
        $id_clien_produc = $_POST['id'];
        $formulario = Validacion::Decodifica($_POST['form']);
        $condicion = 'id_clien_produc =' . $id_clien_produc;
        $resultado = $this->cliente_productoDAO->editar($formulario, $condicion);
        echo json_encode($resultado);
    }

    public function modificar_pedido()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $id_pedido = $datos['id_pedido'];
        $orden_compra = $datos['orden_compra'];
        $orden_compra_antigua = $datos['orden_compra_antigua'];
        $condicion = 'id_pedido =' . $id_pedido;
        unset($datos['id_clien_produc'], $datos['trm'], $datos['cantidad'], $datos['id_pedido'], $datos['orden_compra_antigua']);
        if (!empty($_FILES['orden_compra_file']['name'])) {
            $nombreFinal = $orden_compra_antigua . "_" . $id_pedido . '.pdf';
            $ruta = CARPETA_IMG . PROYECTO . '/PDF/ocompra/' . $nombreFinal;
            if (file_exists($ruta)) {
                unlink($ruta);
            }
            Archivo::moverArchivos_ocompra('orden_compra_file', $id_pedido, $orden_compra);
        }
        $this->PedidosDAO->editar($datos, $condicion);
        $respu = $this->consultar_pedido($id_pedido);
        return $respu;
    }

    public function modificar_valor_item()
    {
        header('Content-Type: application/json');
        $valor_item = $_POST['valor_item'];
        $data = $_POST['data'];
        $nuevo_total = $valor_item * $data['Cant_solicitada'];
        if ($data['moneda'] != 'Pesos') {
            $nuevo_total = $valor_item * $data['trm'] * $data['Cant_solicitada'];
        }
        $edita_item_valor = [
            'v_unidad' => $valor_item,
            'total' => $nuevo_total
        ];
        $condicion_item_valor = 'id_pedido_item =' . $data['id_pedido_item'];
        $this->PedidosItemDAO->editar($edita_item_valor, $condicion_item_valor);
        $recalcular_valor = $this->PedidosItemDAO->recalcularValorPedido($data['id_pedido']);
        $edita_pedido = [
            'total_etiq' => $recalcular_valor['total_etiq'],
            'total_tec' => $recalcular_valor['total_tec']
        ];
        $condicion_edita_pedido = 'id_pedido =' . $data['id_pedido'];
        $this->PedidosDAO->editar($edita_pedido, $condicion_edita_pedido);
        $envio = $this->consultar_pedido($data['id_pedido']);
        return $envio;
    }
}

<?php

namespace MiApp\negocio\controladores\facturacion;

use MiApp\negocio\generico\GenericoControlador;

use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\cliente_productoDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\ruta_embobinadoDAO;
use MiApp\persistencia\dao\coreDAO;
use MiApp\persistencia\dao\trmDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\ActividadAreaDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\control_facturacionDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;





class PendidosDirectosControlador extends GenericoControlador
{
    private $clientes_proveedorDAO;
    private $direccionDAO;
    private $cliente_productoDAO;
    private $productosDAO;
    private $ruta_embobinadoDAO;
    private $coreDAO;
    private $trmDAO;
    private $ConsCotizacionDAO;
    private $PedidosDAO;
    private $PedidosItemDAO;
    private $ActividadAreaDAO;
    private $SeguimientoOpDAO;
    private $control_facturacionDAO;
    private $EntregasLogisticaDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();
        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->cliente_productoDAO = new cliente_productoDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->ruta_embobinadoDAO = new ruta_embobinadoDAO($cnn);
        $this->coreDAO = new coreDAO($cnn);
        $this->trmDAO = new trmDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->ActividadAreaDAO = new ActividadAreaDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
    }

    public function vista_pedidos_directos()
    {
        parent::cabecera();
        $clientes_proveedor = $this->clientes_proveedorDAO->consultar_clientes();

        $this->view(
            'facturacion/vista_pedidos_directos',
            [
                "productos" => $this->productosDAO->consultar_productos(),
                "ruta_em" => $this->ruta_embobinadoDAO->consultar_ruta_embobinado(),
                "core" => $this->coreDAO->consultar_core(),
                "trm" => $this->trmDAO->ConsultaUltimoRegistro(),
                "usuario" => $_SESSION['usuario']->getnombres() . " " . $_SESSION['usuario']->getapellidos()

            ]
        );
    }
    public function info_cliente_producto()
    {
        header('Content-Type: application/json');

        $cliente = $this->clientes_proveedorDAO->consultar_nit_clientes($_POST['nit']);
        if (!empty($cliente)) {
            $direccion = $this->direccionDAO->consulta_direccion_cliente($_POST['nit']);
            if (!empty($direccion) ) {
                $cliente[0]->direccion = $direccion[0];
            } else {
                $cliente[0]->direccion = [];
            }            
        } else {
            $cliente = [];
        }

        echo json_encode($cliente);
        return;
    }
    public function busca_producto()
    {
        header('Content-Type: application/json');
        $productos_clinte = $this->cliente_productoDAO->consultar_productos_clientes_id_prov($_POST['id_cli_prov']);
        $product_encontrado = [];
        foreach ($productos_clinte as  $value) {
            if ($value->id_producto == $_POST['id_producto']) {
                array_push($product_encontrado, $value);
            }
        }
        echo json_encode($product_encontrado);
        return;
    }
    public function crea_cliente_producto_galan()
    {
        header('Content-Type: application/json');
        $datos = $_POST['data'][0];
        $datos['estado_client_produc'] = 1;
        $datos['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $datos['fecha_crea'] = date('Y-m-d');
        $respu = $this->cliente_productoDAO->insertar($datos);
        $producto = $this->cliente_productoDAO->cliente_producto_id_dell($respu['id']);
        echo json_encode($producto);
        return;
    }
    public function crear_pedido_directo()
    {
        header('Content-Type: appliation/json');
        // Validar si el consecutivo ya fue utilizado
        $documento_relacionado = $this->ConsCotizacionDAO->consultar_cons_especifico($_POST['tipo_documento']);
        if ($documento_relacionado[0]->numero_guardado == $_POST['numero_factura_consulta'] && $documento_relacionado[0]->id_consecutivo == $_POST['tipo_documento']) {
            //Aumento el consecutivo para evitar sea utilizado el documento y la lista de empaque
            $lista_empaque_numero = $this->ConsCotizacionDAO->consultar_cons_especifico(10);
            $num_lista_empaque = $lista_empaque_numero[0]->numero_guardado;
            $nuevo_num_lista = $num_lista_empaque + 1;
            $edita_lista_empaque = [
                'numero_guardado' => $nuevo_num_lista
            ];
            $condicion_lista_empaque = 'id_consecutivo =10';
            $this->ConsCotizacionDAO->editar($edita_lista_empaque, $condicion_lista_empaque);

            $doc_relacionado = [
                'numero_guardado' => $documento_relacionado[0]->numero_guardado + 1
            ];
            $condicion_documento = ' id_consecutivo =' . $_POST['tipo_documento'];
            $this->ConsCotizacionDAO->editar($doc_relacionado, $condicion_documento);
            // SE REGISTRA EL NUMERO DE DOCUMENTO PARA SU CONTROL
            $num_factura = $documento_relacionado[0]->numero_guardado;






            $pertenece = $this->clientes_proveedorDAO->consultar_clientes_proveedor($_POST['id_cli_prov']); //consulta el id_cli_prov para saber que tipo de cliente <es class=""></es>
            if ($pertenece[0]->pertenece == 1) { // si el cliente pertenece a 1 es de acobarras s.a.s
                $id = 3;
                $consecutivo = $this->ConsCotizacionDAO->consultar_cons_especifico($id);
                $num_pedido =  $consecutivo[0]->numero_guardado;
            } elseif ($pertenece[0]->pertenece == 2) { // si el cliente pertenece a 2 es de acobarras colombia
                $id = 4;
                $consecutivo = $this->ConsCotizacionDAO->consultar_cons_especifico($id);
                $num_pedido =  $consecutivo[0]->numero_guardado;
            } else { //de lo contrario seria una cuenta de cobro
                $id = 6;
                $consecutivo = $this->ConsCotizacionDAO->consultar_cons_especifico($id);
                $num_pedido =  $consecutivo[0]->numero_guardado;
            }
            $nuevo_cons['numero_guardado'] = $num_pedido + 1; // aumentamos el consecutivo en 1
            $condicion = 'id_consecutivo=' . $id;
            $this->ConsCotizacionDAO->editar($nuevo_cons, $condicion); // subimos el nuevo consecutivo

            $products = json_decode($_POST['data_product']);
            $total_etq = 0;
            $total_tecn = 0;
            foreach ($products as $value) {
                $clase_Art = $value->id_clase_articulo;
                if ($clase_Art == 1) { //si es 1 clase articulo es bobinas
                    $total_etq += $value->valor_total;
                }
                if ($clase_Art == 2) { //si es 2 clase articulo es etiquetas
                    $total_etq += $value->valor_total;
                }
                if ($clase_Art == 3) { //si es 3 clase articulo es tecnologia
                    $total_tecn = $value->valor_total;
                }
            }
            $data_pedido = [
                'num_pedido' => $num_pedido,
                'id_cli_prov' => $_POST['id_cli_prov'],
                'id_persona' =>  $_SESSION['usuario']->getId_persona(),
                'id_dire_entre' => $_POST['id_dire_entre'],
                'id_dire_radic' => $_POST['id_dire_radic'],
                'fecha_compromiso' => date('Y-m-d'),
                'fecha_cierre' => date('Y-m-d'),
                'iva' => $_POST['iva'],
                'observaciones' =>  $_POST['observaciones'],
                'total_etiq' => $total_etq,
                'total_tec' => $total_tecn,
                'id_estado_pedido' => 4,
                'id_usuario' => $_SESSION['usuario']->getId_usuario(),
                'fecha_crea_p' => date('Y-m-d'),
                'hora_crea' => date('h:i:s'),

            ];
            $respu = $this->PedidosDAO->insertar($data_pedido); // inserta los datos a la tabla pedidos
            $parametro = 't1.id_pedido = ' . $respu['id'];
            $cons_pedido = $this->PedidosDAO->consulta_pedidos($parametro); //consultamos la tabla pedido


            $crea_items_pedido = [];
            $crea_seguimiento_items = [];
            $crea_control_facturas = [];
            $crea_entregas_logostica = [];


            $ConsultaUltimoRegistro = $this->trmDAO->ConsultaUltimoRegistro();
            $trm = $ConsultaUltimoRegistro[0]->valor_trm;

            $item = 0;

            foreach ($products as $product) {
                $item += 1;
                $product->item = $item;
                $product->num_pedido = $cons_pedido[0]->num_pedido; //asignamos a cada producto el numero de pedido asignado
                $product->id_pedido = $respu['id']; //asignamos a cada producto el numero de pedido asignado.
                $product->fecha_crea_p = date('Y-m-d'); //asignamos a cada producto la fecha del pedido.
                $product->fecha_compro_pedido = date('Y-m-d');

                $total_item = floatval($product->precio_venta) * floatval($product->cantidad_requerida);
                if ($product->moneda == 2) {
                    $total_item = (floatval($product->precio_venta) * floatval($trm)) * floatval($product->cantidad_requerida);
                }

                $crea_items_pedido[] = array(
                    'id_pedido' => $product->id_pedido,
                    'item' => $product->item,
                    'id_clien_produc' => $product->id_clien_produc,
                    'codigo' => $product->codigo_producto,
                    'Cant_solicitada' => $product->cantidad_requerida,
                    'cant_bodega' => $product->cantidad_requerida,
                    'cant_op' => 0,
                    'ruta_embobinado' => $product->id_ruta_embobinado,
                    'core' => $product->id_core,
                    'cant_x' => $product->presentacion,
                    'trm' =>  $trm,
                    'moneda' => TIPO_MONEDA[$product->moneda],
                    'v_unidad' => $product->precio_venta,
                    'total' => $total_item,
                    'fecha_compro_item' => date('Y-m-d'),
                    'orden_compra' => null,
                    'id_estado_item_pedido' => 17,
                    'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                    'fecha_crea' => date('Y-m-d'),
                );

                $actividad_area = $this->ActividadAreaDAO->consultar_id_actividad_area(60);
                $crea_seguimiento_items[] = array(
                    'id_persona' => 202,
                    'id_area' => $actividad_area[0]->id_actividad_area,
                    'id_actividad' => $actividad_area[0]->id_area_trabajo,
                    'pedido' => $product->num_pedido,
                    'item' => $product->item,
                    'observacion' => $actividad_area[0]->nombre_actividad_area,
                    'estado' => 1,
                    'id_usuario' => 10,
                    'fecha_crea' =>  date('Y-m-d'),
                    'hora_crea' =>  date('H:i:s'),
                );
            }


            foreach ($crea_seguimiento_items as $items) {
                $this->SeguimientoOpDAO->insertar($items);
            }

            $crea_control_facturas = array(
                'tipo_documento' => $_POST['tipo_documento'],
                'num_factura' => $num_factura,
                'num_remision' => 0,
                'num_lista_empaque' => $num_lista_empaque,
                'estado' => 1,
                'fecha_crea' => date('Y-m-d'),
                'id_usuario' => $_SESSION['usuario']->getid_usuario(),
            );
            $respu_control_factura = $this->control_facturacionDAO->insertar($crea_control_facturas);
            $id_control_factura = $respu_control_factura['id'];

            foreach ($crea_items_pedido as $items) {
                $respu = $this->PedidosItemDAO->insertar($items);
                $items['id_pedido_item'] = $respu['id'];
                $crea_items_pedido = $items; // inserta los items en pedidos item


                $crea_entregas_logostica[] = array(
                    'id_pedido_item' =>  $crea_items_pedido['id_pedido_item'],
                    'cantidad_factura' => $crea_items_pedido['Cant_solicitada'],
                    'id_usuario' =>  $_SESSION['usuario']->getid_usuario(),
                    'tipo_documento' =>  PREFIJO[$_POST['tipo_documento']],
                    'id_factura' => $id_control_factura,
                    'fact_por' => $_SESSION['usuario']->getid_usuario(),
                    'fecha_factura' => date('Y-m-d'),
                    'entre_por' => $_SESSION['usuario']->getid_usuario(),
                    'fecha_cargue' => date('Y-m-d'),
                    'fecha_entrega' => date('Y-m-d'),
                    'estado' => 7,
                    'fecha_crea' =>  date('Y-m-d'),
                    'hora_crea' => date('H:i:s'),
                );
            }

            foreach ($crea_entregas_logostica as $items) {
                $this->EntregasLogisticaDAO->insertar($items);
            }
            $respuesta_final = [
                'status' => 1,
                'num_lista_empaque' => $num_lista_empaque,
                'msg' => "Pedido creado correctamente."
            ];
        } else {
            $respuesta_final = [
                'status' => -1,
                'num_lista_empaque' => 0,
                'msg' => "Este numero de documento ya fue usado recargue la pagina para asignar un nuevo numero."
            ];
        }
        echo json_encode($respuesta_final);
        return;
    }
}

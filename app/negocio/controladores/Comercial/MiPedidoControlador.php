<?php

namespace MiApp\negocio\controladores\Comercial;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Archivo;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\trmDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\negocio\util\Envio_Correo;
use MiApp\persistencia\dao\TintasDAO;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\ActividadAreaDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\UsuarioDAO;
use MiApp\persistencia\dao\PortafolioDAO;
use MiApp\persistencia\dao\IntentoPedidoDAO;




class MiPedidoControlador extends GenericoControlador
{
    private $pedidosDAO;
    private $clientes_proveedorDAO;
    private $cons_cotizacionDAO;
    private $entrada_tecnologiaDAO;
    private $trmDAO;
    private $PedidosItemDAO;
    private $tintasDAO;
    private $ActividadAreaDAO;
    private $SeguimientoOpDAO;
    private $UsuarioDAO;
    private $PortafolioDAO;
    private $IntentoPedidoDAO;


    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->pedidosDAO = new PedidosDAO($cnn);
        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->cons_cotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
        $this->trmDAO = new trmDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->tintasDAO = new tintasDAO($cnn);
        $this->ActividadAreaDAO = new ActividadAreaDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->UsuarioDAO = new UsuarioDAO($cnn);
        $this->PortafolioDAO = new PortafolioDAO($cnn);
        $this->IntentoPedidoDAO = new IntentoPedidoDAO($cnn);
    }

    public function crear_pedido()
    {
        header('Content-Type:application/json');

        $cliente_prov = $this->clientes_proveedorDAO->consultar_clientes_proveedor($_POST['id_cli_prov']);
        $factura_portafolio = $this->PortafolioDAO->ConsultarPortafolioIdCliProv($_POST['id_cli_prov']);
        $cupo_cliente = $cliente_prov[0]->cupo_cliente;
        $forma_pago = $cliente_prov[0]->forma_pago;
        $paso_pedido = $cliente_prov[0]->paso_pedido;
        $monto_total = 0;
        $product_total = 0;
        $valida_crea_pedido = false;
        $no_cupo = '';
        $respuesta = [];

        $products = json_decode($_POST['data_product']);
        if (in_array($_POST['id_cli_prov'], SOLICITUDES_ALMACEN, true)) {
            $valida_crea_pedido = true;
        } else {
            if ($forma_pago != 1) { // valida que la forma de pago sea siempre diferente de "contado".
                if ($paso_pedido == 0) { // valida si tiene permiso de subir pedido por contabilidad

                    // saca valor de las facturas pendientes
                    foreach ($factura_portafolio as $value) {
                        $monto_total = $monto_total + $value->total_factura;
                    }
                    //saca el valor del pedido que se quiere montar
                    foreach ($products as $value1) {
                        $product_total = $product_total + $value1->valor_total;
                    }
                    $cupo_restante = $cupo_cliente - $monto_total; //variable que tiene el cupo que le sobra al cliente

                    $sobre_cupo = $cupo_cliente * PORCENTAJE_SOBRE_CUPO; //saca el valor que puede exederse en el pedido

                    $monto_permitido = $cupo_restante + $sobre_cupo; // saca el valor total que tendria permitido para poder montar el pedido

                    if ($product_total > $monto_permitido) { // valida que el cliente no halla supedado el cupo otorgado.
                        $no_cupo = "► Este Pedido supera el cupo restante del crédito de este cliete.";
                        $valida_crea_pedido = false;
                    } else {
                        $valida_crea_pedido = true;
                    }
                } else {
                    $valida_crea_pedido = true;
                }
            } else {
                $valida_crea_pedido = true;
            }
        }

        if ($valida_crea_pedido) {
            //agregando a tabla pedidos
            $data_pedido = $_POST;
            // $products = json_decode($_POST['data_product']);
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
            $pertenece = $this->clientes_proveedorDAO->consultar_clientes_proveedor($_POST['id_cli_prov']); //consulta el id_cli_prov para saber que tipo de cliente <es class=""></es>
            if ($pertenece[0]->paso_pedido == 1) {
                $edita_cli_prov = ['paso_pedido' => 0];
                $condicion_cli_prov = 'id_cli_prov=' . $_POST['id_cli_prov'];
                $this->clientes_proveedorDAO->editar($edita_cli_prov, $condicion_cli_prov);
            }
            if ($pertenece[0]->pertenece == 1) { // si el cliente pertenece a 1 es de acobarras s.a.s
                $id = 3;
                $consecutivo = $this->cons_cotizacionDAO->consultar_cons_especifico($id);
                $num_pedido =  $consecutivo[0]->numero_guardado;
            } elseif ($pertenece[0]->pertenece == 2) { // si el cliente pertenece a 2 es de acobarras colombia
                $id = 4;
                $consecutivo = $this->cons_cotizacionDAO->consultar_cons_especifico($id);
                $num_pedido =  $consecutivo[0]->numero_guardado;
            } else { //de lo contrario seria una cuenta de cobro
                $id = 3;
                $consecutivo = $this->cons_cotizacionDAO->consultar_cons_especifico($id);
                $num_pedido =  $consecutivo[0]->numero_guardado;
            }
            $data_pedido['id_persona'] = $_SESSION['usuario']->getId_persona();
            $data_pedido['num_pedido'] = $num_pedido;
            $data_pedido['total_etiq'] = $total_etq;
            $data_pedido['total_tec'] = $total_tecn;
            $data_pedido['id_estado_pedido'] = 4;
            $data_pedido['id_usuario'] = $_SESSION['usuario']->getId_usuario();
            $data_pedido['paso_pedido'] = 0;
            if ($paso_pedido == 1) {
                $data_pedido['paso_pedido'] = 1;
            }
            // valida si la hora de creacion del pedio supera a FECHA_CIERRE_PEDIDOS-> constante php por si la supera el pedido quedara con fecha del dia siguiente
            $hora_actual = date('H:i');
            if ($hora_actual >= FECHA_CIERRE_PEDIDOS) {
                $fecha = date_create(date('Y-m-d'));
                $data_pedido['fecha_crea_p'] = Validacion::aumento_fechas($fecha, 1);
                $data_pedido['hora_crea'] =  ('06:00:00');
            } else {
                $data_pedido['fecha_crea_p'] = date('Y-m-d');
                $data_pedido['hora_crea'] =  date('h:i:s');
            }
            unset($data_pedido['data_product']);
            $respu = $this->pedidosDAO->insertar($data_pedido); // inserta los datos a la tabla pedidos
            $parametro = 't1.id_pedido = ' . $respu['id'];
            $cons_pedido = $this->pedidosDAO->consulta_pedidos($parametro); //consultamos la tabla pedido
            if (!empty($_FILES)) { // guarda pdf de orden de compra si lo trae
                Archivo::moverArchivos_ocompra('PDF_compra', $respu['id'], $data_pedido['orden_compra']);
            }
            $fecha_compro_item = '';
            if (!empty($data_pedido['fecha_compromiso'])) {
                $fecha_compro_item = $data_pedido['fecha_compromiso'];
            }

            foreach ($products as $product) {
                $product->num_pedido = $cons_pedido[0]->num_pedido; //asignamos a cada producto el numero de pedido asignado
                $product->id_pedido = $respu['id']; //asignamos a cada producto el numero de pedido asignado.
                $product->fecha_crea_p = $data_pedido['fecha_crea_p']; //asignamos a cada producto la fecha del pedido.
                $product->fecha_compro_pedido = $fecha_compro_item;
            }
            $nuevo_cons['numero_guardado'] = $num_pedido + 1; // aumentamos el consecutivo en 1
            $condicion = 'id_consecutivo=' . $id;
            $this->cons_cotizacionDAO->editar($nuevo_cons, $condicion); // subimos el nuevo consecutivo
            $this->agrega_items_pedido($products, $_POST['id_cli_prov']); // llamamos al metodo agrega_items_pedido y le pasamos los items
            $items = $this->PedidosItemDAO->ConsultaIdPedido($respu['id']);
            $this->correos_confirmacion_pedido_cliente_asesor($respu['id'], $items);
            $respuesta = [
                'id_cli_prov' => $data_pedido['id_cli_prov'],
                'msg' => "Pedido creado exitosamente, enviamos un correo de notificación para ti y para el cliente.",
                'status' => 1
            ];
        } else {
            $respuesta = [
                'id_cli_prov' => $_POST['id_cli_prov'],
                'msg' => $no_cupo,
                'status' => -1
            ];
        }

        if ($respuesta['status'] == -1) {
            $intento_pedido = [
                'id_cli_prov' => $respuesta['id_cli_prov'],
                'asesor' => $_SESSION['usuario']->getId_usuario(),
                'observacion' =>  $respuesta['msg'],
                'fecha_crea' => date('y-m-d h:i:s'),
            ];
            $this->IntentoPedidoDAO->insertar($intento_pedido);
        }
        echo json_encode($respuesta);
    }

    public function agrega_items_pedido($products, $id_cli_prov)
    {
        $item = 0;
        foreach ($products as $value) {
            $correo = false;
            $id_actividad = ''; //variable para determinar a que area va a ir en seguimiento items
            $item += 1;
            $value->item = $item;
            $inventario = $this->entrada_tecnologiaDAO->consultar_inv_product($value->id_producto);
            if ($inventario[0]->total == '') {
                $existencia = 0;
            } else {
                $existencia = $inventario[0]->total;
            }
            if ($value->id_clase_articulo == 1) { //es bobinas
                // si es bobina  print_r("para tb_ pedidos item estado 1");
                $value->cant_bodega = 0;
                $value->cant_op = 0;
                $value->id_estado_item_pedido = 1;
                $id_actividad = 1; //variable para determinar a que area va a ir en seguimiento items
                $value->fecha_compro_item = '';

                $this->crea_items_pedido($value); //para tb entrada_tecnologia estado_inv 2 y descontar
            } else {
                //si es info variable saca valores para validar la informacion  variable
                $caracter = "-";
                $posicion_coincidencia = strpos($value->codigo_producto, $caracter);
                $tinta_codigo = substr($value->codigo_producto, ($posicion_coincidencia + 6), 2);
                $tintas = $this->tintasDAO->consultar_tintas_valiables();

                //saca valores para validar si es in producto externo
                $cadena_de_texto = $value->ubi_troquel;
                $cadena_buscada   = 'ExTeRnO';
                //variable para fecha de compromiso del item
                $value->fecha_compro_item = '';
                //--------0--------------0---------------0------------0-------------0------------0-------------0-------------------0-------------

                if (floatval($existencia) <= 0 || in_array($id_cli_prov, SOLICITUDES_ALMACEN, true)) { // La cantidad del inventario es menor o igual que 0
                    $value->cant_bodega = 0;
                    $value->cant_op = $value->cantidad_requerida;
                    $value->id_estado_item_pedido = 2;
                    $id_actividad = 5; //variable para determinar a que area va a ir en seguimiento items
                    if ($value->id_clase_articulo == 3) {
                        $value->cant_op = 0;
                        $value->id_estado_item_pedido = 5;
                        $id_actividad = 39; //variable para determinar a que area va a ir en seguimiento items
                        $correo = true; // envio correo area de compra de tecnologia
                    }
                    $value->fecha_compro_item = '';
                    if (strcasecmp($cadena_de_texto, $cadena_buscada) == 0) {
                        $value->cant_op = 0;
                        // producto externo para compras "5" y correo a compras materia prima
                        $value->id_estado_item_pedido = 5;
                        $id_actividad = 39; //variable para determinar a que area va a ir en seguimiento items
                        $correo = true; // envio correo area de compra de tecnologia
                    }
                    foreach ($tintas as $tinta) {
                        if ($tinta_codigo == $tinta->numeros && $value->id_clase_articulo == 2) {
                            // codigo de producto es valiable entonces va a estado"6" 
                            $value->id_estado_item_pedido = 6;
                            $id_actividad = 58; //variable para determinar a que area va a ir en seguimiento items
                        }
                    }
                    $this->crea_items_pedido($value); //para tb entrada_tecnologia estado_inv 2 y descontar
                } elseif (intval($existencia) >= intval($value->cantidad_requerida)) { //inventario con cantidad necesaria
                    $value->cant_bodega = $value->cantidad_requerida;
                    $value->cant_op = 0;
                    $value->id_estado_item_pedido = 17;
                    $value->estado_inv = 2;
                    $id_actividad = 59; //variable para determinar a que area va a ir en seguimiento items
                    foreach ($tintas as $tinta) {
                        if ($tinta_codigo == $tinta->numeros && $value->id_clase_articulo == 2) {
                            // codigo de producto es valiable entonces va a estado"6" 
                            $value->id_estado_item_pedido = 6;
                            $id_actividad = 58; //variable para determinar a que area va a ir en seguimiento items
                        }
                    }

                    $fecha = date_create($fecha = $value->fecha_crea_p);
                    $value->fecha_compro_item = Validacion::aumento_fechas($fecha, 2);
                    $this->descuento_inventario($value); //para tb entrada_tecnologia estado_inv 2 y descontar
                    $this->crea_items_pedido($value); //para tb_ pedidos item estado 17
                    $correo = false;
                } elseif (intval($existencia) < intval($value->cantidad_requerida)) { // inventario sin cantidad necesaria
                    $pendiente_op = floatval($value->cantidad_requerida) - floatval($existencia);
                    $value->cant_bodega = $existencia;
                    if ($value->id_clase_articulo != 3) { //es diferente de tecnologia
                        $value->cant_op = $pendiente_op;
                        $value->id_estado_item_pedido = 2;
                        $value->estado_inv = 3;
                        $id_actividad = 41; //variable para determinar a que area va a ir en seguimiento items
                        if (strcasecmp($cadena_de_texto, $cadena_buscada) == 0) {
                            // producto externo para compras "5" y correo a compras materia prima
                            $value->id_estado_item_pedido = 5;
                            $id_actividad = 46; //variable para determinar a que area va a ir en seguimiento items
                            $correo = true; // envio correo area de compra de tecnologia
                        }
                        foreach ($tintas as $tinta) {
                            if ($tinta_codigo == $tinta->numeros && $value->id_clase_articulo == 2) {
                                // codigo de producto es valiable entonces va a estado"6" 
                                $value->id_estado_item_pedido = 6;
                                $id_actividad = 58; //variable para determinar a que area va a ir en seguimiento items
                            }
                        }

                        $value->fecha_compro_item = '';
                        $this->descuento_inventario($value); //para tb entrada_tecnologia estado_inv 3 y descontar
                        $this->crea_items_pedido($value); //para tb_ pedidos item estado 2
                        $correo = false;
                    } else { // si es tecnologia
                        $value->cant_op = 0;
                        $value->id_estado_item_pedido = 5;
                        $value->estado_inv = 3;
                        $value->fecha_compro_item = '';
                        $id_actividad = 46; //variable para determinar a que area va a ir en seguimiento items
                        $this->descuento_inventario($value); //para tb entrada_tecnologia  estado_inv 2 y descontar
                        $this->crea_items_pedido($value); //para tb_ pedidos item estado 2
                        $correo = true; //enviar correo a marcela
                    }
                }
            }

            $this->crea_seguimiento_items($value, $id_actividad);
            if ($correo) {
                $this->envio_correos($value);
            }
        }
    }

    public function descuento_inventario($datos)
    {
        $data_descuento = [];
        $cantidad_req = $datos->cantidad_requerida;
        $ubicaciones = $this->entrada_tecnologiaDAO->consultar_seguimiento_producto($datos->id_productos);
        foreach ($ubicaciones as $ubicacion) {
            if ($cantidad_req > 0) {
                if ($ubicacion->total > 0) {
                    if ($cantidad_req <= $ubicacion->total) { //se decuenta porque en la ubicacion esta la cantidad requerida    
                        $salida = $cantidad_req;
                        $cantidad_req = $cantidad_req - $cantidad_req;
                    } else { //descuenta de esa ubicacion y sigue buscando
                        $salida = $ubicacion->total;
                        $cantidad_req = $cantidad_req - $ubicacion->total;
                    }
                    $data_descuento[] = [
                        'documento' => $datos->num_pedido . "-" . $datos->item,
                        'ubicacion' => $ubicacion->ubicacion,
                        'codigo_producto' => $datos->codigo_producto,
                        'id_productos' => $datos->id_productos,
                        'salida' => $salida,
                        'estado_inv' => $datos->estado_inv,
                        'fecha_crea' => date('Y-m-d H:i:s')
                    ];
                }
            }
        }
        foreach ($data_descuento as $items) {
            $this->entrada_tecnologiaDAO->insertar($items);
        }
    }
    public function crea_items_pedido($datos)
    {

        if (!empty($datos->fecha_compro_pedido)) {
            $compro_item = $datos->fecha_compro_pedido;
            $datos->fecha_compro_item = $compro_item;
        }
        //     $datos->fecha_compro_item = '0000-00-00';

        $crea_items_pedido = [];
        $ConsultaUltimoRegistro = $this->trmDAO->ConsultaUltimoRegistro();
        $trm = $ConsultaUltimoRegistro[0]->valor_trm;
        $total_item = floatval($datos->precio_venta) * floatval($datos->cantidad_requerida);
        if ($datos->moneda == 2) {
            $total_item = (floatval($datos->precio_venta) * floatval($trm)) * floatval($datos->cantidad_requerida);
        }
        $crea_items_pedido[] = array(
            'id_pedido' => $datos->id_pedido,
            'item' => $datos->item,
            'id_clien_produc' => $datos->id_clien_produc,
            'codigo' => $datos->codigo_producto,
            'Cant_solicitada' => $datos->cantidad_requerida,
            'cant_bodega' => $datos->cant_bodega,
            'cant_op' => $datos->cant_op,
            'ruta_embobinado' => $datos->id_ruta_embobinado,
            'core' => $datos->id_core,
            'cant_x' => $datos->presentacion,
            'trm' =>  $trm,
            'moneda' => TIPO_MONEDA[$datos->moneda],
            'v_unidad' => $datos->precio_venta,
            'total' => $total_item,
            'fecha_compro_item' => $datos->fecha_compro_item,
            'orden_compra' => null,
            'id_estado_item_pedido' => $datos->id_estado_item_pedido,
            'id_usuario' => $_SESSION['usuario']->getid_usuario(),
            'fecha_crea' => date('Y-m-d'),
        );
        foreach ($crea_items_pedido as $items) {
            $respu = $this->PedidosItemDAO->insertar($items);
            $items['id_pedido_item'] = $respu['id'];
            $crea_items_pedido = $items;
        }
    }
    public function crea_seguimiento_items($datos, $id_actividad_area)
    {
        $actividad_area = $this->ActividadAreaDAO->consultar_id_actividad_area($id_actividad_area);
        $crea_seguimiento_items[] = array(
            'id_persona' => 202,
            'id_area' => $actividad_area[0]->id_area_trabajo,
            'id_actividad' => $actividad_area[0]->id_actividad_area,
            'pedido' => $datos->num_pedido,
            'item' => $datos->item,
            'observacion' => $actividad_area[0]->nombre_actividad_area,
            'estado' => 1,
            'id_usuario' => 10,
            'fecha_crea' =>  date('Y-m-d'),
            'hora_crea' =>  date('H:i:s'),
        );
        foreach ($crea_seguimiento_items as $items) {
            $this->SeguimientoOpDAO->insertar($items);
        }
    }

    public function envio_correos($data)
    {
        $parametro = 't1.id_pedido = ' . $data->id_pedido;
        $cons_pedido = $this->pedidosDAO->consulta_pedidos($parametro); //consultamos la tabla pedido
        if ($data->id_clase_articulo == 2) {
            $nombre_comprador = "Paola";
            $correo = 'paola.castaneda@acobarras.com';
            $TipoCompra = 'Materia Prima';
        } elseif ($data->id_clase_articulo == 3) {
            $nombre_comprador = "Marcela";
            $correo = 'marcela.rodriguez@acobarras.com';
            $TipoCompra = 'Tecnologia';
        }
        if ($cons_pedido[0]->forma_pago == 4) {
            $forma_pago = $cons_pedido[0]->dias_dados . ' Días';
        } else {
            $forma_pago = FORMA_PAGO[$cons_pedido[0]->forma_pago];
        }
        $asesor = $_SESSION['usuario']->getNombre() . " " . $_SESSION['usuario']->getApellido();
        $fecha_compromiso = $cons_pedido[0]->fecha_compromiso;
        $data->forma_pago = $forma_pago;
        $data->asesor = $asesor;
        $data->fecha_compromiso = $fecha_compromiso;
        $data->nombre_comprador = $nombre_comprador;
        $correo1 = Envio_Correo::SolicitudesCompras($TipoCompra, $correo, $data);
        return $correo1;
    }
    public function correos_confirmacion_pedido_cliente_asesor($data, $items_correo)
    {
        //si la variable $envio viene como 1 envia el correo al asesor y al cliente de lo contrario solo al asesor
        $parametro = 't1.id_pedido = ' . $data;
        $cons_pedido = $this->pedidosDAO->consulta_pedidos($parametro); //consultamos la tabla pedido
        $user = $this->UsuarioDAO->consultarIdUsuario($_SESSION['usuario']->getId_usuario());
        $asesor = $user[0]->correo;
        $cliente = $cons_pedido[0]->email;

        Envio_Correo::correo_confimacion_pedido_client($cons_pedido, $user, $cliente, $items_correo);
        Envio_Correo::correo_confimacion_pedido_asesor($cons_pedido, $user, $asesor, $items_correo);
        $this->correo_fecha_compromiso($data, $cons_pedido, $cliente, $asesor);
    }
    public function valida_nombre_pdf_oc()
    {
        header('Content-Type:application/json');
        $consulta_nombre_oc = $this->pedidosDAO->consultar_nombre_pdf_oc($_POST['id_cliv_prov'], $_POST['nom_pdf']);
        echo json_encode($consulta_nombre_oc);
    }
    public function correo_fecha_compromiso($data, $cons_pedido, $asesor, $cliente)
    {
        // Validar que todos los item tengan fecha de compromiso para colocarla en el pedido
        $fecha_compro = $this->PedidosItemDAO->ValidaFechaCompromiso($data);
        if ($fecha_compro != '0000-00-00') {
            // Editar el pedido
            $pedido['fecha_compromiso'] = $fecha_compro;
            $condicion_pedido = 'id_pedido =' . $data;
            $this->pedidosDAO->editar($pedido, $condicion_pedido);
            // Envio del correo fecha de compromiso
            Envio_Correo::correo_confirmacion_fecha_compromiso($cons_pedido, $fecha_compro, $cliente, $asesor);
        }
    }
}

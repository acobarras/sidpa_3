<?php

namespace MiApp\negocio\controladores\almacen;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\ubicacionesDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\UsuarioDAO;
use MiApp\negocio\util\Validacion;

class AlmacenTecnologiaControlador extends GenericoControlador
{
    private $clientes_proveedorDAO;
    private $ubicacionesDAO;
    private $productosDAO;
    private $entrada_tecnologiaDAO;
    private $PedidosDAO;
    private $PedidosItemDAO;
    private $ItemProducirDAO;
    private $EntregasLogisticaDAO;
    private $SeguimientoOpDAO;
    private $UsuarioDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->ubicacionesDAO = new ubicacionesDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->UsuarioDAO = new UsuarioDAO($cnn);
    }

    /**
     * Función para cargar metodos de inicio de sesion con las opciones correspondientes.
     */
    public function vista_entrada_tecnologia()
    {
        parent::cabecera();
        $this->view(
            'almacen/vista_entrada_tecnologia',
            [
                "proovedor" => $this->clientes_proveedorDAO->consultar_proovedor(),
                //esta funcion trae el tipo de ubicasion de las tecnologias 
                "ubicacion" => $this->ubicacionesDAO->tipo_producto_ubicaciones(3)
            ]
        );
    }


    public function validar_codigo_tecno()
    {
        header('Content-Type: application/json'); //convierte a json
        $codigo_tecno = $_REQUEST['codigo']; //igualacion de datos enviados a la variable datos
        $valida = $this->productosDAO->consultar_productos_especifico($codigo_tecno); //consulta la existencia del codigo en la tabla producto
        if (empty($valida)) { // valida si esta vacia la variable 
            $respu = array( //crea variable respuesta
                'estado' => false,
                'mensaje' => 'Lo sentimos este producto no existe'
            );
        } else {
            $ubicacion = $this->entrada_tecnologiaDAO->consultar_seguimiento_producto($valida[0]->id_productos);
            $respu = array( //crea variable respuesta
                'estado' => true,
                'id_producto' => $valida[0]->id_productos,
                'id_tipo_articulo' => $valida[0]->id_tipo_articulo,
                'mensaje' => $valida[0]->descripcion_productos,
                'ubicacion' => $ubicacion,
            );
        }
        echo json_encode($respu); //envio de variable respuesta
        return;
    }

    public function registrar_tecnologia()
    {
        header('Content-Type: application/json'); //convierte a json
        $datos = $_POST; // igualacion de datos enviados a la varianle datos
        unset($datos['conocido']); //eliminar dato que no se encuentra en la base de datos
        $datos['id_usuario'] = $_SESSION['usuario']->getId_usuario(); //obtener informacion del usurio
        $datos['fecha_crea'] = date('Y-m-d-H-i-s'); //obtener la fecha de creacion
        $respu = $this->entrada_tecnologiaDAO->insertar($datos); //es la variable que le devolvemos al ajax cuando se insertaron los datos a la base de datos 
        echo json_encode($respu); //envio de variable respuesta

    }
    /**
     * Función para cargar metodos de inicio de sesion con las opciones correspondientes.
     */
    public function vista_salida_tecnologia()
    {
        parent::cabecera();

        $this->view(
            'almacen/vista_salida_tecnologia',
            [
                "ubicacion" => $this->ubicacionesDAO->tipo_producto_ubicaciones(3)
            ]

        );
    }
    /**
     * Función para cargar metodos de inicio de sesion con las opciones correspondientes.
     */

    public function vista_ingreso_etiquetas()
    {
        parent::cabecera();

        $this->view(
            'almacen/vista_ingreso_etiquetas',
            [
                "ubicacion" => $this->ubicacionesDAO->tipo_producto_ubicaciones(2)
            ]

        );
    }
    /**
     * Función para cargar metodos de inicio de sesion con las opciones correspondientes.
     */

    public function vista_salida_etiquetas()
    {
        parent::cabecera();

        $this->view(
            'almacen/vista_salida_etiquetas',
            [
                "ubicacion" => $this->ubicacionesDAO->tipo_producto_ubicaciones(2)
            ]

        );
    }

    /**
     * Función para cargar metodos de inicio de sesion con las opciones correspondientes.
     */

    public function vista_ingreso_bobinas()
    {
        parent::cabecera();

        $this->view(
            'almacen/vista_ingreso_bobinas',
            [
                //esta funcion trae el tipo de ubicasion de las tecnologias 
                "ubicacion" => $this->entrada_tecnologiaDAO->consultar_ubicacion_bobina_ancho()
            ]

        );
    }
    public function vista_salida_bobinas()
    {
        parent::cabecera();

        $this->view(
            'almacen/vista_salida_bobinas',
            [
                //esta funcion trae el tipo de ubicasion de las tecnologias 
                "ubicacion" => $this->entrada_tecnologiaDAO->consultar_ubicacion_bobina_ancho()
            ]

        );
    }
    /*
     * Función para cargar vista vista_alitar_items
    */
    public function vista_alistar_items()
    {
        parent::cabecera();

        $this->view(
            'almacen/vista_alistar_items'
        );
    }
    /*
     * Función para cargar vista vista_alitar_items
    */
    public function consultar_items_completos()
    {
        header('Content-Type: application/json');
        $completo = $_GET['data'];
        $items_bodega = $this->entrada_tecnologiaDAO->consultar_items_pendientes_bodega($completo);
        foreach ($items_bodega as $value) {
            $caracter = "-";
            $posicion_coincidencia = strpos($value->documento, $caracter);
            $num_pedido = substr($value->documento, 0, $posicion_coincidencia);
            $item = substr($value->documento, ($posicion_coincidencia + 1));
            $pedido = $this->PedidosDAO->consultar_descarga_pedido($num_pedido); //nos sirve esta funcion para consultar
            $id_pedido = 0;
            foreach ($pedido as $value1) {
                $pedidos_item = $this->PedidosItemDAO->ConsultaIdPedido($value1->id_pedido); //nos sirve esta funcion para consultar
                $id_pedido = $pedidos_item[0]->id_pedido;
                $value->fecha_compromiso = $value1->fecha_compromiso;
                $value->nombre_empresa = $pedidos_item[0]->nombre_empresa;
                $value->ruta =  RUTA_ENTREGA[$pedidos_item[0]->ruta];
                $value->nombre_estado_item =  $value->nombre_estado;
                $value->difer_mas = $value1->difer_mas;
                $value->difer_menos = $value1->difer_menos;
                $value->difer_ext = $value1->difer_ext;
                $value->porcentaje = $value1->porcentaje;
                $value->num_pedido = $num_pedido;
            }
            $datos_alistar = $this->PedidosItemDAO->ConsultaIdPedidoIdItem($id_pedido, $item);
            $value->datos_item = $datos_alistar;
            if ($value->estado_inv == 4 || $value->estado_inv == 5) {
                $estado_inventario = $value->estado_inv;
            } else {
                $estado_inventario = $value->estado_inv;
            }
            $value->estado_inv = $estado_inventario;
        }
        $data['data'] = $items_bodega;
        echo json_encode($data);
    }

    public function crea_entrega_logistica()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $valida_reporte = $this->EntregasLogisticaDAO->valida_edicion_item($datos['datos_item'][0]['id_pedido_item']);
        if (empty($valida_reporte)) {
            $data = [
                'id_pedido_item' => $datos['datos_item'][0]['id_pedido_item'],
                'cantidad_factura' => $_POST['salida'],
                'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                'estado' => 1,
                'fecha_crea' => date('y-m-d'),
                'hora_crea' => date('H:i:s'),
            ];
            $this->EntregasLogisticaDAO->insertar($data);
        } else {
            $nueva_cantidad = $_POST['salida'] + $valida_reporte[0]->cantidad_factura;
            $data['cantidad_factura'] = $nueva_cantidad;
            $condicion = 'id_entrega=' . $valida_reporte[0]->id_entrega;
            $this->EntregasLogisticaDAO->editar($data, $condicion);
        }

        if ($_POST['alistamiento'] == 2) {
            $observacion = "ALISTAMIENTO COMPLETO";
        } else {
            $observacion = "ALISTAMIENTO INCOMPLETO";
        }
        $seguimiento = [
            'id_persona' => $_SESSION['usuario']->getId_persona(),
            'id_area' => 1,
            'id_actividad' => 17,
            'pedido' => $_POST['num_pedido'],
            'item' => $datos['datos_item'][0]['item'],
            'observacion' => $observacion,
            'estado' => 1,
            'id_usuario' => $_SESSION['usuario']->getid_usuario(),
            'fecha_crea' => date('y-m-d'),
            'hora_crea' => date('H:i:s'),
        ];
        $this->SeguimientoOpDAO->insertar($seguimiento);
        $items_bodega = $this->entrada_tecnologiaDAO->consultar_salida_item_pedido($datos['documento']);
        foreach ($items_bodega as $value) {
            $condicion = 'id_ingresotec=' . $value->id_ingresotec;
            $entrada_tecno = [
                'estado_inv' => 1, //ponemos en estado 6 el inventario para que estado de "alistado."
                'fecha_alista' => date('y-m-d'),
                'id_usuario' => $_SESSION['usuario']->getid_usuario()

            ];
            $this->entrada_tecnologiaDAO->editar($entrada_tecno, $condicion);
        }
        echo json_encode($observacion);
    }
    public function consulta_ubicaciones_item()
    {
        header('Content-Type: application/json');

        $items_bodega = $this->entrada_tecnologiaDAO->consultar_salida_item_pedido($_POST['documento']);
        $data['data'] = $items_bodega;
        echo json_encode($data);
    }
    public function creacion_reproceso_logistica()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $items_bodega = $this->entrada_tecnologiaDAO->consultar_salida_item_pedido($datos['documento']);
        foreach ($items_bodega as $value) {
            $condicion = 'id_ingresotec=' . $value->id_ingresotec;
            $entrada_tecno = [
                'estado_inv' => $datos['alistamiento'], //ponemos en estado 4 el que es 
            ];
            $this->entrada_tecnologiaDAO->editar($entrada_tecno, $condicion);
        }
        $observacion = "PUESTO EN REPROCESO ";
        $responsable = $this->UsuarioDAO->consultarIdUsuario($_SESSION['usuario']->getid_usuario());
        // LA ETIQUETA DE REPROCESO ES IGUAL PARA TODOS LOS PROYECTOS YA QUE NO CONTIENEN LOGOS NI DATOS INDIVIDUALES DE EMPRESAS
        $etiqueta_reproceso = '
                    ^XA
                    ^FO342,317^GB56,56,2^FS
                    ^FO239,317^GB55,55,2^FS
                    ^FO543,452^GB55,56,2^FS
                    ^FO335,453^GB55,56,2^FS
                    ^FO204,452^GB56,56,2^FS
                    ^FO41,453^GB55,55,2^FS
                    ^FO511,385^GB56,55,2^FS
                    ^FO311,385^GB56,56,2^FS
                    ^FO134,317^GB56,56,2^FS
                    ^FT600,491^A0N,34,33^FH\^FDCalle 13^FS
                    ^FT403,354^A0N,34,33^FH\^FD3"^FS
                    ^FT392,492^A0N,34,33^FH\^FDCalle 80^FS
                    ^FT41,167^A0N,34,33^FH\^FDNo. Pedido:^FS
                    ^FT^A0N,34,33^FH\^FD ' . $datos['documento'] . ' ^FS
                    ^FT271,491^A0N,34,33^FH\^FDSur^FS
                    ^FT449,351^A0N,34,33^FH\^FDCavidades:^FS
                    ^FT98,491^A0N,34,33^FH\^FDNorte^FS
                    ^FT299,353^A0N,34,33^FH\^FD2"^FS
                    ^FT585,423^A0N,34,33^FH\^FDContado^FS
                    ^FT195,354^A0N,34,33^FH\^FD1"^FS
                    ^FT378,424^A0N,34,33^FH\^FDCredito^FS
                    ^FT38,419^A0N,34,33^FH\^FDForma de Pago:^FS
                    ^FT399,287^A0N,34,33^FH\^FDRollos X:^FS
                    ^FT41,546^A0N,34,33^FH\^FDResponsable:^FS
                    ^FT^A0N,34,33^FH\^FD     ' . $responsable[0]->nombre . ' ' . $responsable[0]->apellido . ' ^FS
                    ^FT39,352^A0N,34,33^FH\^FDCore:^FS
                    ^FT39,288^A0N,34,33^FH\^FDCantidad:^FS
                    ^FT^A0N,34,33^FH\^FD              ' . round($_POST['salida']) . ' ^FS
                    ^FT41,232^A0N,34,33^FH\^FDT. Material:^FS
                    ^FT353,59^A0N,34,33^FH\^FDFecha:^FS
                    ^FT^A0N,34,33^FH\^FD    ' .  date('y-m-d') . ' ^FS
                    ^FO601,356^GB137,0,3^FS
                    ^FT39,105^A0N,34,33^FH\^FDCliente:^FS
                    ^FT^A0N,34,33^FH\^FD ' . $_POST['nombre_empresa'] . ' ^FS
                    ^FO205,172^GB534,0,2^FS
                    ^FO527,290^GB212,0,3^FS
                    ^FO176,293^GB222,0,3^FS
                    ^FO226,552^GB518,0,3^FS
                    ^FO204,237^GB538,0,2^FS
                    ^FO447,63^GB295,0,3^FS
                    ^FO146,109^GB599,0,2^FS
                    ^FT36,62^A0N,45,45^FH\^FDREPROCESO^FS
                    ^PQ1,0,1,Y^XZ';
        $respuesta = [
            'estado_inv' => $_POST['estado_inv'],
            'observacion' => $observacion,
            'etiqueta' => $etiqueta_reproceso,
            'state' => 1,
        ];
        echo json_encode($respuesta);
    }
    public function reportar_cant_reproceso()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $valida_reporte = $this->EntregasLogisticaDAO->valida_edicion_item($datos['datos_item'][0]['id_pedido_item']);
        if (empty($valida_reporte)) {
            $data = [
                'id_pedido_item' => $datos['datos_item'][0]['id_pedido_item'],
                'cantidad_factura' => $_POST['salida'],
                'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                'estado' => 1,
                'fecha_crea' => date('y-m-d'),
                'hora_crea' => date('H:i:s'),
            ];
            $this->EntregasLogisticaDAO->insertar($data);
        } else {
            $nueva_cantidad = $_POST['salida'] + $valida_reporte[0]->cantidad_factura;
            $data['cantidad_factura'] = $nueva_cantidad;
            $condicion = 'id_entrega=' . $valida_reporte[0]->id_entrega;
            $this->EntregasLogisticaDAO->editar($data, $condicion);
        }
        if ($_POST['estado_inv'] == 4) {
            $observacion = "ALISTAMIENTO COMPLETO CON REPROCESO";
        } else {
            $observacion = "ALISTAMIENTO INCOMPLETO CON REPROCESO";
        }
        $seguimiento = [
            'id_persona' => $_SESSION['usuario']->getId_persona(),
            'id_area' => 1,
            'id_actividad' => 17,
            'pedido' => $_POST['num_pedido'],
            'item' => $datos['datos_item'][0]['item'],
            'observacion' => $observacion,
            'estado' => 1,
            'id_usuario' => $_SESSION['usuario']->getid_usuario(),
            'fecha_crea' => date('y-m-d'),
            'hora_crea' => date('H:i:s'),
        ];
        $this->SeguimientoOpDAO->insertar($seguimiento);
        $items_bodega = $this->entrada_tecnologiaDAO->consultar_salida_item_pedido($datos['documento']);
        foreach ($items_bodega as $value) {
            $condicion = 'id_ingresotec=' . $value->id_ingresotec;
            $entrada_tecno = [
                'estado_inv' => 1, //ponemos en estado 6 el inventario para que estado de "alistado."
                'fecha_alista' => date('y-m-d'),
                'id_usuario' => $_SESSION['usuario']->getid_usuario()
            ];
            $this->entrada_tecnologiaDAO->editar($entrada_tecno, $condicion);
        }

        $respuesta = [
            'estado_inv' => $_POST['estado_inv'],
            'observacion' => $observacion,
            'state' => 1,
        ];
        echo json_encode($respuesta);
    }
    public function vista_consulta_inv()
    {
        parent::cabecera();
        $this->view(
            'almacen/vista_consulta_inv'
        );
    }
    public function consulta_producto_inventario()
    {
        header('Content-Type: application/json');
        $data = Validacion::Decodifica($_POST['form']);
        $producto = $this->productosDAO->consultar_producto_inventario($data['codigo'], $data['tipo_art']);
        foreach ($producto as $value) {
            $value->cantidad = $this->entrada_tecnologiaDAO->consultar_cantidad($value->id_productos);
            if ($value->cantidad == NULL) {
                $value->cantidad = 0;
            }
        }
        $data['data'] = $producto;
        echo json_encode($data);
    }
    /**
     * Función para consultar los seguimientos de cada producto , Consultar inventario
     */
    public function consultar_seguimiento()
    {
        header('Content-Type: application/json');
        $producto = $this->entrada_tecnologiaDAO->consultar_seguimiento_producto($_POST['id']);
        $data['data'] = $producto;
        echo json_encode($data);
    }
    /**
     * Función para consultar el ubicacion producto  .
     */
    public function consulta_producto_ubicacion()
    {
        header('Content-Type: application/json');
        $data = Validacion::Decodifica($_POST['form']);
        $producto = $this->entrada_tecnologiaDAO->consulta_producto_ubicacion($data['ubicacion']);
        $envio = array();
        foreach ($producto as $value) {
            if ($value->cantidad != 0)
                $envio[] = $value;
        }
        $res['data'] = $envio;
        echo json_encode($res);
    }
    /**
     * Función para consultar items bobinas .
     */
    public function consultar_items_bobina()
    {
        header('Content-Type: application/json');
        $completo = $_GET['data'];
        $productos = $this->PedidosItemDAO->ConsultaIdPedidoIdEstadoItemPedido($completo);
        if (!empty($productos)) {
            $productos[0]->ruta =  RUTA_ENTREGA[$productos[0]->ruta];
        }
        $data['data'] = $productos;
        echo json_encode($data);
    }
    /**
     * Función para consultar inventario items bobinas .
     */
    public function consultar_inventario_items_bobina()
    {
        header('Content-Type: application/json');
        $completo = $_POST['id_producto'];
        $inventario = $this->entrada_tecnologiaDAO->consultar_seguimiento_bobina($completo);
        $envio = [];
        foreach ($inventario as $value) {
            $codigo = $this->productosDAO->consultar_productos_id($completo);
            $value->codigo_producto =  $codigo[0]->codigo_producto;
            $value->id_producto =  $completo;
            if (intval($value->ML) != 0) {
                $envio[] = $value;
            }
        }
        $data['data'] = $envio;
        echo json_encode($data);
    }

    /**
     * Función para consulta ubicacion inventario  .
     */
    public function consulta_ubicacion_inventario()
    {
        header('Content-Type: application/json');
        $completo = $_POST['id_producto'];
        $ancho = $_POST['ancho'];
        $inventario = $this->entrada_tecnologiaDAO->consultar_seguimiento_bobina_ancho($completo, $ancho);
        $envio = [];
        foreach ($inventario as $value) {
            $codigo = $this->productosDAO->consultar_productos_id($completo);
            $value->codigo_producto =  $codigo[0]->codigo_producto;
            $value->id_producto =  $completo;
            if (intval($value->ML) != 0) {
                $envio[] = $value;
            }
        }
        $data['data'] = $envio;
        echo json_encode($data);
    }

    /**
     * Función para descuentoinventario items bobinas .
     */
    public function descuento_inv_bob()
    {
        header('Content-Type: application/json');
        $cantidad_total = 0;
        foreach ($_POST['data'] as $value) {
            $cantidad_total += $value['cantidad'];

            $data_descuento = [
                'documento' => $value['documento'],
                'ubicacion' => $value['ubicacion'],
                'codigo_producto' => $value['codigo'],
                'id_productos' => $value['id_producto'],
                'salida' => $value['cantidad'] * $value['ancho'] / 1000,
                'ancho' => $value['ancho'],
                'estado_inv' => 1,
                'fecha_crea' => date('Y-m-d H:i:s')
            ];
            $this->entrada_tecnologiaDAO->insertar($data_descuento);
        }
        $data = [
            'id_pedido_item' => $_POST['data'][0]['id_pedido_item'],
            'cantidad_factura' => $cantidad_total,
            'id_usuario' => $_SESSION['usuario']->getid_usuario(),
            'estado' => 1,
            'fecha_crea' => date('y-m-d'),
            'hora_crea' => date('H:i:s'),
        ];
        $this->EntregasLogisticaDAO->insertar($data);
        $seguimiento = [
            'id_persona' => $_SESSION['usuario']->getId_persona(),
            'id_area' => 1,
            'id_actividad' => 17,
            'pedido' => $_POST['data'][0]['num_pedido'],
            'item' => $_POST['data'][0]['item'],
            'observacion' => 'ALISTAMIENTO COMPLETO',
            'estado' => 1,
            'id_usuario' => $_SESSION['usuario']->getid_usuario(),
            'fecha_crea' => date('y-m-d'),
            'hora_crea' => date('H:i:s'),
        ];
        $seguimiento = $this->SeguimientoOpDAO->insertar($seguimiento);

        $datos['id_estado_item_pedido'] = 17;
        $condicion = 'id_pedido_item=' . $_POST['data'][0]['id_pedido_item'];
        $respuesta = $this->PedidosItemDAO->editar($datos, $condicion);

        $respu = [];
        if (!empty($respuesta)) {
            $respu = [
                'status' => '1',
                'msg' => 'Se ha descontado bobina y enviado a facruración.',

            ];
        } else {
            $respu = [
                'status' => '2',
                'msg' => 'Error al procesar.',

            ];
        }
        echo json_encode($respu);
        return;
    }

    /**
     * Función para enviar a compras las bobinas de pedidos terceros
     */
    public function envia_bob_compras_terceros()
    {
        header('Content-Type: application/json');
        $datos['id_estado_item_pedido'] = 5;
        $condicion = 'id_pedido_item=' . $_POST['id_pedido_item'];
        $respu = $this->PedidosItemDAO->editar($datos, $condicion);
        if (!empty($respu)) {
            $respu = [
                'status' => '1',
                'msg' => 'Se ha enviado a compras este item.',

            ];
        } else {
            $respu = [
                'status' => '2',
                'msg' => 'Error al procesar.',

            ];
        }
        echo json_encode($respu);
        return;
    }

    public function consultar_inv_bobinas()
    {
        header('Content-Type: application/json');
        $producto = $this->entrada_tecnologiaDAO->consultar_seguimiento_bobina($_POST['id']);
        $data['data'] = $producto;
        echo json_encode($data);
    }
}

<?php

namespace MiApp\negocio\controladores\pqr;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\coreDAO;
use MiApp\persistencia\dao\ruta_embobinadoDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\UsuarioDAO;
use MiApp\persistencia\dao\GestionPqrDAO;
use MiApp\persistencia\dao\SeguimientoPqrDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\control_facturacionDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;

use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\PDF;
use MiApp\negocio\util\Envio_Correo;


class GestionarPqrControlador extends GenericoControlador
{
    private $PedidosDAO;
    private $coreDAO;
    private $ruta_embobinadoDAO;
    private $ConsCotizacionDAO;
    private $UsuarioDAO;
    private $GestionPqrDAO;
    private $SeguimientoPqrDAO;
    private $PedidosItemDAO;
    private $control_facturacionDAO;
    private $EntregasLogisticaDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->coreDAO = new coreDAO($cnn);
        $this->ruta_embobinadoDAO = new ruta_embobinadoDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->UsuarioDAO = new UsuarioDAO($cnn);
        $this->GestionPqrDAO = new GestionPqrDAO($cnn);
        $this->SeguimientoPqrDAO = new SeguimientoPqrDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
    }

    public function vista_gestionar_pqr()
    {
        parent::cabecera();
        $this->view('pqr/vista_gestionar_pqr');
    }

    public function cambio_estado()
    {
        header('Content-Type: application/json'); //convierte a json
        $data = $_POST['data'];
        $data_product = $data['datos_producto'];
        $data_direc = $data['datos_direccion'];
        $estado = $_POST['estado'];
        if ($estado == 5) {
            $num_pqr = $data['num_pqr'];
            $codigo_produc = $data_product[0]['codigo_producto'] . ' ' . $data_product[0]['descripcion_productos'];
            $empresa = $data_direc[0]['nombre_empresa'];
            $direccion = $data_direc[0]['direccion'] . ' ' . $data_direc[0]['nombre_ciudad'] . ' ,' . $data_direc[0]['nombre_departamento'];
            $cantidad = $data['cantidad_reclama'];
            $contacto = $data_direc[0]['contacto'] . ' ' . $data_direc[0]['cargo'];
            $telefono = $data_direc[0]['celular'];
            $correo = CORREO_LOSGISTICA;
            $correo_envio = Envio_Correo::correo_solicitud_logistica($num_pqr, $codigo_produc, $empresa, $direccion, $cantidad, $contacto, $telefono, $correo);
        }
        $id_actividad_area = $_POST['id_actividad_area'];
        $edita_gestion_pqr = [
            'estado' => $estado
        ];
        $condicion_gestion_pqr = 'id_pqr =' . $data['id_pqr'];
        $this->GestionPqrDAO->editar($edita_gestion_pqr, $condicion_gestion_pqr);
        // Seguimiento a la pqr
        $inserta_seguimiento = [
            'id_pqr' => $data['id_pqr'],
            'id_actividad_area' => $id_actividad_area,
            'id_usuario' => $_SESSION['usuario']->getid_usuario(),
            'fecha_crea' => date('Y-m-d H:i:s')
        ];
        $this->SeguimientoPqrDAO->insertar($inserta_seguimiento);
        $respu = [
            'status' => 1,
            // 'data' => $repuesta,
            'msg' => 'Datos Grabados correctamente'
        ];
        echo json_encode($respu);
        return;
    }

    public function consultar_direc_pedido()
    {
        header('Content-Type: application/json'); //convierte a json
        $num_pedido = $_POST['num_pedido'];
        $id_cli_prov = $_POST['id_cli_prov'];
        $direc_cliente = $this->GestionPqrDAO->consultar_direccion_cliente($id_cli_prov);
        $direc_pedido = $this->GestionPqrDAO->consultar_direccion_pedido($num_pedido);
        $data = [
            'direcciones_cliente' => $direc_cliente,
            'direccion_pedido' => $direc_pedido
        ];
        echo json_encode($data);
        return;
    }

    public function analisis_mercancia()
    {
        header('Content-Type: application/json'); //convierte a json
        $data = $_POST['data'];
        $repro_produc = $_POST['repro_produc']; // si es 1 quiere decir que se debe producir de nuevo y si es 2 es un reproceso
        $estado = $_POST['estado'];
        $id_actividad_area = $_POST['id_actividad_area'];
        $id_consecutivo = ID_CON_PEDIDO[$data['datos_producto'][0]['pertenece']];
        $con_pedido = $this->ConsCotizacionDAO->consecutivoPedido($id_consecutivo);
        if ($repro_produc == 1) {
            if ($con_pedido != 0) {
                $num_pedido = $con_pedido;
                $fecha = new \DateTime();
                $fecha->modify('last day of this month');
                $ultimo_dia = $fecha->format('Y-m-d');
                $observacion = 'Este pedido pertenece a la reclamación No ' . $data['num_pqr'] . ' Al momento de facturar preguntar al área encargada que tramite se debe hacer con este pedido' . ', ' . $_POST['observacion'];
                $total_etiq = $data['cantidad_reclama'] * $data['datos_producto'][0]['precio_venta'];
                if ($data['datos_producto'][0]['moneda'] == 2) {
                    $total_etiq = $data['cantidad_reclama'] * ($data['datos_producto'][0]['precio_venta'] * $data['datos_item'][0]['trm']);
                }

                // Generamos un pedido para generar una orden de producción nueva
                $crea_pedido = [
                    'num_pedido' => $num_pedido,
                    'id_cli_prov' => $data['id_cli_prov'],
                    'id_persona' => $data['id_persona'],
                    'id_dire_entre' => $data['id_dir_pqr'],
                    'id_dire_radic' => $data['id_dir_pqr'],
                    'parcial' => 0,
                    'porcentaje' => 0,
                    'difer_mas' => null,
                    'difer_menos' => null,
                    'difer_ext' => 1,
                    'fecha_cierre' => $ultimo_dia,
                    'iva' => $data['datos_item'][0]['iva'],
                    'observaciones' => $observacion,
                    'total_tec' => 0,
                    'total_etiq' => $total_etiq,
                    'id_estado_pedido' => 4,
                    'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                    'fecha_crea_p' => date('Y-m-d'),
                    'hora_crea' => date('H:i:s'),
                ];
                $id_pedido = $this->PedidosDAO->insertar($crea_pedido);
                // Insertamos el item del pedido
                $estado_item_pedido = 2;
                if ($data['datos_producto'][0]['id_clase_articulo'] != 2) {
                    $estado_item_pedido = 5;
                }
                $crea_items_pedido = array(
                    'id_pedido' => $id_pedido['id'],
                    'item' => 1,
                    'id_clien_produc' => $data['datos_producto'][0]['id_clien_produc'],
                    'codigo' => $data['datos_producto'][0]['codigo_producto'],
                    'Cant_solicitada' => $data['cantidad_reclama'],
                    'cant_bodega' => 0,
                    'cant_op' => $data['cantidad_reclama'],
                    'ruta_embobinado' => $data['datos_producto'][0]['id_ruta_embobinado'],
                    'core' => $data['datos_producto'][0]['id_core'],
                    'cant_x' => $data['datos_producto'][0]['presentacion'],
                    'trm' =>  $data['datos_item'][0]['trm'],
                    'moneda' => TIPO_MONEDA[$data['datos_producto'][0]['moneda']],
                    'v_unidad' => $data['datos_producto'][0]['precio_venta'],
                    'total' => $total_etiq,
                    'orden_compra' => null,
                    'id_estado_item_pedido' => $estado_item_pedido,
                    'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                    'fecha_crea' => date('Y-m-d'),
                );
                $this->PedidosItemDAO->insertar($crea_items_pedido);
                $edita_gestion_pqr = [
                    'num_pedido_cambio' => $num_pedido
                ];
                $condicion_gestion_pqr = 'id_pqr =' . $data['id_pqr'];
                $this->GestionPqrDAO->editar($edita_gestion_pqr, $condicion_gestion_pqr);
                //Se cambia el estado y se genera el seguimiento de la pqr
                $estado = $this->cambio_estado();
                $respu = $estado;
            } else {
                $respu = [
                    'status' => -1,
                    'msg' => 'Lo sentimos se produjo un error al momento de grabar porfavor intente nuevamente'
                ];
                echo json_encode($respu);
            }
        } else {
            // se genera una etiqueta para el reproceso
            $cavidad = Validacion::DesgloceCodigo($data['datos_producto'][0]['codigo_producto'], 5, 1);
            $marcar = $data['datos_item'][0]['num_pedido'] . '-' . $data['datos_item'][0]['item'];
            $core = $this->coreDAO->consulta_core_id($data['id_core']);
            $ruta_embobinado = $this->ruta_embobinadoDAO->consulta_ruta_embobinado_id($data['ruta_embobinado']);
            $repuesta = '^XA
            ^MMT
            ^PW799
            ^LL0599
            ^LS0
            ^FT38,91^A0N,46,45^FH\^FDREPROCESO PQR No.^FS
            ^FT450,91^A0N,46,45^FH\^FD' . $data['num_pqr'] . '^FS
            ^FT38,157^A0N,34,33^FH\^FDCliente:^FS
            ^FT149,157^A0N,34,33^FH\^FD' . $data['datos_direccion'][0]['nombre_empresa'] . '^FS
            ^FT38,210^A0N,34,33^FH\^FDCantidad Total:^FS
            ^FT258,209^A0N,34,33^FH\^FD' . number_format($data['cantidad_reclama'], 0, ',', '.') . '^FS
            ^FT410,210^A0N,34,33^FH\^FDFecha:^FS
            ^FT510,211^A0N,34,33^FH\^FD' . date('d/m/Y H:i') . '^FS
            ^FT38,270^A0N,34,33^FH\^FDRollos X:^FS
            ^FT173,270^A0N,34,33^FH\^FD' . number_format($data['cant_x'], 0, ',', '.') . '^FS
            ^FT299,270^A0N,34,33^FH\^FDCore:^FS
            ^FT385,270^A0N,34,33^FH\^FD' . $core . '^FS
            ^FT513,270^A0N,34,33^FH\^FDCavidades:^FS
            ^FT675,270^A0N,34,33^FH\^FD' . $cavidad . '^FS
            ^FT38,330^A0N,34,33^FH\^FDSentido:^FS
            ^FT163,330^A0N,34,33^FH\^FD' . $ruta_embobinado . '^FS
            ^FT315,330^A0N,34,33^FH\^FDCodigo:^FS
            ^FT429,330^A0N,34,33^FH\^FD' . $data['datos_producto'][0]['codigo_producto'] . '^FS
            ^FT38,386^A0N,34,33^FH\^FDMarcar Con:^FS
            ^FT224,385^A0N,34,33^FH\^FD' . $marcar . '^FS
            ^FT38,444^A0N,34,33^FH\^FDObservaciones:^FS
            ^FO33,504^GB717,0,3^FS
            ^FO258,450^GB488,0,4^FS
            ^FO33,564^GB717,0,3^FS
            ^PQ1,0,1,Y^XZ';
            $edita_gestion_pqr = [
                'estado' => $estado
            ];
            $condicion_gestion_pqr = 'id_pqr =' . $data['id_pqr'];
            $this->GestionPqrDAO->editar($edita_gestion_pqr, $condicion_gestion_pqr);
            // Seguimiento a la pqr
            $inserta_seguimiento = [
                'id_pqr' => $data['id_pqr'],
                'id_actividad_area' => $id_actividad_area,
                'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                'fecha_crea' => date('Y-m-d H:i:s')
            ];
            $this->SeguimientoPqrDAO->insertar($inserta_seguimiento);
            $respu = [
                'status' => 2,
                'data' => $repuesta,
                'msg' => 'Datos Grabados correctamente'
            ];
            echo json_encode($respu);
        }
        return $respu;
    }

    public function documento_envio()
    {
        header("Content-Type: application/pdf");
        $data = $_POST['data'];
        $estado = $_POST['estado'];
        $id_actividad_area = $_POST['id_actividad_area'];
        $pertenece = $data['datos_producto'][0]['pertenece'];
        $tipo_documento = 11;
        if ($pertenece == 2) {
            $tipo_documento = 12;
        }
        $prefijo = PREFIJO[$tipo_documento];
        $total_documento = 0;
        $documento_relacionado = $this->ConsCotizacionDAO->consultar_cons_especifico($tipo_documento);
        $numero_remision = $documento_relacionado[0]->numero_guardado;
        $condicion = 'WHERE num_remision =' . $numero_remision;
        $valida_remision = $this->control_facturacionDAO->ConsultaEspecifica($condicion);
        if (empty($valida_remision)) {
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
                'numero_guardado' => $numero_remision + 1
            ];
            $condicion_documento = ' id_consecutivo =' . $tipo_documento;
            $this->ConsCotizacionDAO->editar($doc_relacionado, $condicion_documento);
            // SE REGISTRA EL NUMERO DE DOCUMENTO PARA SU CONTROL
            $num_remision = $numero_remision;
            $datos_factura = [
                'tipo_documento' => $tipo_documento,
                'num_factura' => 0,
                'num_remision' => $num_remision,
                'num_lista_empaque' => $num_lista_empaque,
                'estado' => 1,
                'fecha_crea' => date('Y-m-d H:i:s'),
                'id_usuario' => $_SESSION['usuario']->getid_usuario()
            ];
            $respu_control_factura = $this->control_facturacionDAO->insertar($datos_factura);
            $id_control_factura = $respu_control_factura['id'];

            $inserta_entrega_pqr = [
                'id_pedido_item' => $data['id_pedido_item'],
                'cantidad_factura' => $data['cantidad_reclama'],
                'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                'tipo_documento' => $prefijo,
                'id_factura' => $id_control_factura,
                'fact_por' => $_SESSION['usuario']->getid_usuario(),
                'fecha_factura' => date('Y-m-d'),
                'estado' => 2,
                'fecha_crea' => date('Y-m-d H:i:s'),
                'hora_crea' => date('H:i:s'),
            ];
            $this->EntregasLogisticaDAO->insertar($inserta_entrega_pqr);
            $cabecera = [
                'fecha_elaboracion' => date('d/m/Y'),
                'cliente' => $data['datos_direccion'][0]['nombre_empresa'],
                'orden_compra' => 'N/A',
                'pais' => $data['datos_direccion'][0]['nombre_pais'],
                'departamento' => $data['datos_direccion'][0]['nombre_departamento'],
                'ciudad' => $data['datos_direccion'][0]['nombre_ciudad'],
                'direccion' => $data['datos_direccion'][0]['direccion'],
                'numero_pedido' => $data['datos_item'][0]['num_pedido'],
                'numero_lista_empaque' => $num_lista_empaque,
                'numero_doc_relacionado' => $prefijo . " " . $numero_remision,
                'tipo_documento' => $tipo_documento,
                'usuario_facturacion' => $_SESSION['usuario']->getnombres() . " " . $_SESSION['usuario']->getapellidos(),
                'total_documento' => $total_documento,
            ];
            $item_pqr = [
                'codigo' => $data['datos_producto'][0]['codigo_producto'],
                'v_unidad' => $data['valor_unitario'],
                'cantidad_por_facturar' => $data['cantidad_reclama'],
                'descripcion_productos' => $data['datos_producto'][0]['descripcion_productos'],
            ];
            $edita_gestion_pqr = [
                'estado' => $estado
            ];
            $condicion_gestion_pqr = 'id_pqr =' . $data['id_pqr'];
            $this->GestionPqrDAO->editar($edita_gestion_pqr, $condicion_gestion_pqr);
            // Seguimiento a la pqr
            $inserta_seguimiento = [
                'id_pqr' => $data['id_pqr'],
                'id_actividad_area' => $id_actividad_area,
                'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                'fecha_crea' => date('Y-m-d H:i:s')
            ];
            $this->SeguimientoPqrDAO->insertar($inserta_seguimiento);
            // $this->cambio_estado();
            $envio_item = [$item_pqr];
            $respu = PDF::listaEmpaquePdf($cabecera, $envio_item);
            Validacion::DELETE_QR();
        } else {
            $respu = '';
        }
        echo json_encode($respu);
        return;
    }
    public function cambiar_cantidad_reclamacion()
    {
        header("Content-Type: application/pdf");
        $id_pqr = $_POST['id_pqr'];
        $cantidad = $_POST['cantidad_reclama'];
        $edita_gestion_pqr = [
            'cantidad_reclama' => $cantidad
        ];
        $condicion = 'id_pqr =' . $id_pqr;
        $respu = $this->GestionPqrDAO->editar($edita_gestion_pqr, $condicion);
        echo json_encode($respu);
        return;
    }
}

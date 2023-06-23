<?php

namespace MiApp\negocio\controladores\pqr;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\cliente_productoDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\UsuarioDAO;
use MiApp\persistencia\dao\GestionPqrDAO;
use MiApp\persistencia\dao\SeguimientoPqrDAO;

use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\Envio_Correo;

class PqrControlador extends GenericoControlador
{
    private $PedidosDAO;
    private $direccionDAO;
    private $cliente_productoDAO;
    private $ConsCotizacionDAO;
    private $UsuarioDAO;
    private $GestionPqrDAO;
    private $SeguimientoPqrDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->cliente_productoDAO = new cliente_productoDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->UsuarioDAO = new UsuarioDAO($cnn);
        $this->GestionPqrDAO = new GestionPqrDAO($cnn);
        $this->SeguimientoPqrDAO = new SeguimientoPqrDAO($cnn);
    }

    public function consulta_datos_pedido()
    {
        header('Content-Type: application/json'); //convierte a json
        $parametro = 't1.num_pedido =' . $_POST['num_pedido'];
        $datos = $this->PedidosDAO->consulta_pedidos($parametro);
        $direcciones = $this->direccionDAO->consulta_direccion_cliente($datos[0]->nit);
        $productos = $this->cliente_productoDAO->consultar_productos_clientes_id_prov($datos[0]->id_cli_prov);
        $res = [
            'datos' => $datos,
            'direcciones' => $direcciones,
            'productos' => $productos,
        ];
        echo json_encode($res);
        return;
    }

    public function consultar_producto_cliente()
    {
        header('Content-Type: application/json'); //convierte a json
        $id_cliente = $_POST['id_cliente'];
        $productos = $this->cliente_productoDAO->consultar_productos_cliente($id_cliente);
        echo json_encode($productos);
        return;
    }

    public function grabar_reclamacion()
    {
        header('Content-Type: application/json'); //convierte a json
        $form = $_POST['form'];
        $form = Validacion::Decodifica($form);
        $item_pedido = json_decode($form['item_pedido']);
        $cambio_produc = json_decode($form['cambio_produc']);
        $motivo = $form['motivo'];
        $datos_asesor = $this->UsuarioDAO->consultarIdUsuario($item_pedido->id_usuario);
        $cam_direc = 2;
        if (isset($form['cam_direc'])) {
            $cam_direc = $form['cam_direc'];
            $cambio_direc = json_decode($form['cambio_direc']);
        }
        $cam_produc = 2;
        if (isset($form['cam_produc'])) {
            $cam_produc = $form['cam_produc'];
        }
        $cambio_reproceso = 2;
        if (isset($form['cam_produc'])) {
            $cambio_reproceso = $form['cambio_reproceso'];
        }
        $recogida_produc = 2;
        if (isset($form['recogida_produc'])) {
            $recogida_produc = $form['recogida_produc'];
        }
        $nota_contable = $form['nota_contable'];
        $cita_previa = $form['requiere_cita'];
        $cantidad_reclama = 0;
        if (isset($form['cantidad_reclama'])) {
            $cantidad_reclama = $form['cantidad_reclama'];
        }
        $observacion = $_POST['observacion'];
        // Sacamos un consecutivo de la reclamacion
        $fecha_dia = date('Y-m-d'); //'2022-04-27';
        $numero_pqr = $this->ConsCotizacionDAO->consecutivoPqr($fecha_dia);
        if ($numero_pqr != '') {
            $id_persona = $item_pedido->id_persona;
            $id_dir_pqr = $item_pedido->id_dire_entre;
            if ($cam_direc == 1) {
                $id_dir_pqr = $cambio_direc->id_direccion;
            }
            $id_clien_produc = $item_pedido->id_clien_produc;
            $id_core = $item_pedido->core;
            $cant_x = $item_pedido->cant_x;
            $ruta_embobinado = $item_pedido->ruta_embobinado;
            $valor_unitario = $item_pedido->v_unidad;
            if ($cam_produc == 1) {
                $id_clien_produc = $cambio_produc->id_clien_produc;
                $id_core = $cambio_produc->id_core;
                $cant_x = $cambio_produc->presentacion;
                $ruta_embobinado = $cambio_produc->id_ruta_embobinado;
                $valor_unitario = $cambio_produc->precio_venta;
            }
            $carga_pqr  = [
                'num_pqr' => $numero_pqr,
                'id_cli_prov' => $item_pedido->id_cli_prov,
                'id_dir_pqr' => $id_dir_pqr,
                'id_persona' => $id_persona,
                'motivo_pqr' => $motivo,
                'id_pedido_item' => $item_pedido->id_pedido_item,
                'descripcion_pqr' => $observacion,
                'recoger_producto' => $recogida_produc, // 1 Si 2 No
                'cita_previa' => $cita_previa, // 1 Si 2 No
                'nota_contable' => $nota_contable, // 1 Si 2 No
                'cambio_reproceso' => $cambio_reproceso, // 1 Si 2 No
                'cantidad_reclama' => $cantidad_reclama,
                'id_clien_produc' => $id_clien_produc,
                'id_core' => $id_core,
                'cant_x' => $cant_x,
                'ruta_embobinado' => $ruta_embobinado,
                'valor_unitario' => $valor_unitario,
                'estado' => 1,
                'fecha_crea' => date('Y-m-d H:i:s'),
            ];

            $repuesta = $this->GestionPqrDAO->insertar($carga_pqr);
            // Seguimiento a la pqr
            $inserta_seguimiento = [
                'id_pqr' => $repuesta['id'],
                'id_actividad_area' => 65,
                'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                'fecha_crea' => date('Y-m-d H:i:s')
            ];
            $this->SeguimientoPqrDAO->insertar($inserta_seguimiento);
            $correo = $datos_asesor[0]->correo;
            $correo2 = "";
            $correo_envio = Envio_Correo::correos_apertura_pqr($numero_pqr, $observacion, $correo);
            $respu = [
                'status' => 1,
                'data' => $numero_pqr,
                'msg' => 'Datos Grabados correctamente el numero de la PQR es ' . '<span class="text-danger">' . $numero_pqr . '</span> Tenga en cuenta que este número lo debe guardar para cualquier consulta.'
            ];
        } else {
            $respu = [
                'status' => -1,
                'msg' => 'Lo sentimos se esta procesando una pqr intente nuevamente'
            ];
        }

        echo json_encode($respu);
        return;
    }
}

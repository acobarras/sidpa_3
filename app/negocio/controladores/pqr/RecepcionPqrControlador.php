<?php

namespace MiApp\negocio\controladores\pqr;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\Envio_Correo;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\GestionPqrDAO;
use MiApp\persistencia\dao\cliente_productoDAO;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\SeguimientoPqrDAO;

class RecepcionPqrControlador extends GenericoControlador
{

    private $PedidosDAO;
    private $PedidosItemDAO;
    private $GestionPqrDAO;
    private $cliente_productoDAO;
    private $direccionDAO;
    private $SeguimientoPqrDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->GestionPqrDAO = new GestionPqrDAO($cnn);
        $this->cliente_productoDAO = new cliente_productoDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->SeguimientoPqrDAO = new SeguimientoPqrDAO($cnn);
    }

    public function vista_recepcion_pqr()
    {
        parent::cabecera();
        $this->view('pqr/vista_recepcion_pqr');
    }

    public function consultar_pqr()
    {
        header("Content-type: application/json; charset=utf-8");
        $consulta = $_GET['consulta'];
        $datos = $this->GestionPqrDAO->consultar_pqr($consulta);
        foreach ($datos as $value) {
            $datos_item = $this->PedidosItemDAO->ConsultaIdPedidoItem($value->id_pedido_item);
            $value->datos_item = $datos_item;
            $datos_producto = $this->cliente_productoDAO->cliente_producto_id_dell($value->id_clien_produc);
            $value->datos_producto = $datos_producto;
            $datos_direccion = $this->direccionDAO->consultaIdDireccion($value->id_dir_pqr);
            $value->datos_direccion = $datos_direccion;
        }
        echo json_encode($datos);
        return;
    }

    public function acepta_pqr()
    {
        header("Content-type: application/json; charset=utf-8");
        $data = $_POST['data'];
        $redaccion = $_POST['redaccion'];
        $estado_pqr = 2; // Este estado es para continuar con un producto
        if ($data['motivo_pqr'] == 2) {
            $estado_pqr = 15; // Este estado es para que aparesca en respuesta comite debido a que es un servicio
        }
        $edita_pqr = [
            'apertura_pqr' => $redaccion,
            'estado' => $estado_pqr
        ];
        $condicion_edita_pqr = 'id_pqr =' . $data['id_pqr'];
        $this->GestionPqrDAO->editar($edita_pqr, $condicion_edita_pqr);
        // Seguimiento a la pqr
        $inserta_seguimiento = [
            'id_pqr' => $data['id_pqr'],
            'id_actividad_area' => 66,
            'id_usuario' => $_SESSION['usuario']->getid_usuario(),
            'fecha_crea' => date('Y-m-d H:i:s')
        ];
        $this->SeguimientoPqrDAO->insertar($inserta_seguimiento);
        // Realizamos el envio de la aceptacion
        // $cliente = $data['datos_direccion'][0]['email'];
        // $cliente = 'edwin.rios@acobarras.com';
        $cliente = 'desarrollo@acobarras.com';
        $correo = Envio_Correo::correos_apertura_pqr($data['num_pqr'], $redaccion, $cliente);
        echo json_encode($correo);
        return;
    }

    public function eliminar()
    {
        header("Content-type: application/json; charset=utf-8");
        $data = $_POST['data'];
        $id_pqr = $data['id_pqr'];
        $condicion_elimina_pqr = 'id_pqr =' . $id_pqr;
        $this->SeguimientoPqrDAO->eliminar($condicion_elimina_pqr);
        $res = $this->GestionPqrDAO->eliminar($condicion_elimina_pqr);
        echo json_encode($res);
        return;
    }
    public function modificar_pqr()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $_POST['dato'];
        if ($_POST['campo'] == 'direccion') {

            $edita_pqr = [
                'id_dir_pqr' => $datos['id_direccion'],
            ];
        } else {
            if ($_POST['opcion'] == 1) {
                $core = $datos['id_core'];
                $presentacion = $datos['presentacion'];
                $ruta_em = $datos['id_ruta_embobinado'];
            } else {
                $core = $datos['core'];
                $presentacion = $datos['cant_x'];
                $ruta_em = $datos['ruta_embobinado'];
            }
            $edita_pqr = [
                'id_clien_produc' => $datos['id_clien_produc'],
                'id_core' => $core,
                'cant_x' => $presentacion,
                'ruta_embobinado' => $ruta_em,
            ];
        }
        $condicion_edita_pqr = 'id_pqr =' . $_POST['id_pqr'];
        $modificar = $this->GestionPqrDAO->editar($edita_pqr, $condicion_edita_pqr);
        if ($modificar == 1) {
            $res = [
                'status' => 1
            ];
        } else {
            $res = [
                'status' => -1
            ];
        }
        echo json_encode($res);
        return;
    }

    public function observaciones_pqr()
    {
        header("Content-type: application/json; charset=utf-8");
        $observacion = $_POST['observacion'];
        $edita_pqr = [
            'observacion' => $observacion,
        ];
        $condicion_edita_pqr = 'id_pqr =' . $_POST['id_pqr'];
        $modificar = $this->GestionPqrDAO->editar($edita_pqr, $condicion_edita_pqr);
        if ($modificar == 1) {
            $res = [
                'status' => 1
            ];
        } else {
            $res = [
                'status' => -1
            ];
        }
        echo json_encode($res);
        return;
    }
}

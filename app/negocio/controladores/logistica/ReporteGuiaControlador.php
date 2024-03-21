<?php

namespace MiApp\negocio\controladores\logistica;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\control_facturacionDAO;
use MiApp\persistencia\dao\SeguimientoOpDAO;
use MiApp\persistencia\dao\SeguimientoFacturaDAO;
use MiApp\persistencia\dao\EntregasLogisticaDAO;

class ReporteGuiaControlador extends GenericoControlador
{

    private $control_facturacionDAO;
    private $SeguimientoOpDAO;
    private $SeguimientoFacturaDAO;
    private $EntregasLogisticaDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
        $this->SeguimientoOpDAO = new SeguimientoOpDAO($cnn);
        $this->SeguimientoFacturaDAO = new SeguimientoFacturaDAO($cnn);
        $this->EntregasLogisticaDAO = new EntregasLogisticaDAO($cnn);
    }

    public function vista_reporte_guia()
    {
        parent::cabecera();
        $this->view(
            'logistica/vista_reporte_guia'
        );
    }

    public function consultar_lista_empaque()
    {
        header('Content-Type: application/json');
        $num_lista_empaque = $_POST['num_lista_empaque'];
        $items_lista_empaque = $this->control_facturacionDAO->consulta_documento_reporte_guia($num_lista_empaque);
        echo json_encode($items_lista_empaque);
        return;
    }

    public function insertar_num_guia()
    {
        header('Content-Type: application/json');
        $item_documento = $_POST['DATA'];
        $datos_guia = $_POST['form'][0]['value'];
        foreach ($item_documento as $value) {
            $seguimiento_op = [
                'id_persona' => $_SESSION['usuario']->getId_persona(),
                'id_area' => '2',
                'id_actividad' => '18',
                'pedido' => $value['num_pedido'],
                'item' => $value['item'],
                'observacion' => $datos_guia,
                'estado' => '1',
                'id_usuario' => $_SESSION['usuario']->getId_usuario(),
                'fecha_crea' => date('Y-m-d'),
                'hora_crea' => date('H:i:s'),
            ];
            $this->SeguimientoOpDAO->insertar($seguimiento_op);
        }
        // insertar registro factura
        $seguimiento_factura = [
            'id_control_factura' => $item_documento[0]['id_control_factura'],
            'actividad' => 18, //reporte de guia
            'id_usuario' => $_SESSION['usuario']->getId_usuario(),
            'fecha_crea' => date('Y-m-d H:i:s')
        ];
        $this->SeguimientoFacturaDAO->insertar($seguimiento_factura);
        echo json_encode(true);
        return;
    }

    public function consultar_ubi_despacho()
    {
        header('Content-Type: application/json');
        $pedidos_item = $this->EntregasLogisticaDAO->consulta_pedidos_ubicacion($_POST['num_ubicacion']);
        echo json_encode($pedidos_item);
        return;
    }

    public function cambio_ubicacion_despacho()
    {
        header('Content-Type: application/json');
        $edita = [
            'ubicacion_material' => $_POST['nueva_ubi'],
        ];
        $condicion = "id_entrega =" . $_POST['data']['id_entrega'];
        $editar = $this->EntregasLogisticaDAO->editar($edita, $condicion);
        echo json_encode($editar);
        return;
    }
}

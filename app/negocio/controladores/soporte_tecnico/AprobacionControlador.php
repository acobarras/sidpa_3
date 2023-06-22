<?php

namespace MiApp\negocio\controladores\soporte_tecnico;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\SoporteItemDAO;
use MiApp\persistencia\dao\CotizacionItemSoporteDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\SoporteTecnicoDAO;
use MiApp\persistencia\dao\MiPedidoControlador;
use MiApp\negocio\util\Envio_Correo;
use MiApp\negocio\util\PDF;

class AprobacionControlador extends GenericoControlador
{
    private $SoporteItemDAO;
    private $CotizacionItemSoporteDAO;
    private $entrada_tecnologiaDAO;
    private $ConsCotizacionDAO;
    private $SoporteTecnicoDAO;

    public function __construct(&$cnn)
    {
        $this->SoporteItemDAO = new SoporteItemDAO($cnn);
        $this->CotizacionItemSoporteDAO = new CotizacionItemSoporteDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->SoporteTecnicoDAO = new SoporteTecnicoDAO($cnn);
        parent::__construct($cnn);
        parent::validarSesion();
    }

    public function vista_aprobacion()
    {
        parent::cabecera();
        $this->view(
            'soporte_tecnico/vista_aprobacion'
        );
    }

    public function cambio_estado()
    {
        header('Content-Type: application/json');
        $datos = $_POST['array_item'];
        for ($i = 0; $i < count($datos); $i++) {
            $id_diagnostico = $datos[0]['id_diagnostico'];
            $item = $datos[0]['item'];
            $datos_cotizacion = $this->CotizacionItemSoporteDAO->consultar_datos($id_diagnostico, $item);
            for ($a = 0; $a < $datos_cotizacion; $a++) {
                $formulario_cotiza = [
                    'estado' => $_POST['estado'],
                ];
                $condicion = 'id_cotizacion =' . $datos_cotizacion[$a]->id_cotizacion;
                $modificacion = $this->CotizacionItemSoporteDAO->editar($formulario_cotiza, $condicion);
            }
        }
        if ($modificacion == 1) {
            $respu = [
                'status' => 1,
            ];
        } else {
            $respu = [
                'status' => -1,
            ];
        }
        echo json_encode($respu);
        return;
    }

    public function consultar_datos_aprobacion()
    {
        header('Content-Type: application/json');
        $estado_item = '10';
        $datos_items = $this->SoporteItemDAO->consultar_aprobacion($estado_item, '');
        $sentencia = 'AND t1.num_acta=0';
        foreach ($datos_items as $value) {
            $repuestos = $this->SoporteItemDAO->consultar_repuestos_item($value->id_diagnostico, $value->item, $sentencia);
            $value->repuestos = $repuestos;
        }
        $res['data'] = $datos_items;
        echo json_encode($res);
        return;
    }

    public function cambiar_estado_cotiza()
    {
        header('Content-Type: application/json');
        $datos = $_POST['array_item'];
        $repuestos = $datos['repuestos'];
        $editar_item = [
            'estado' => $_POST['estado_item'],
        ];
        $condicion_item = 'id_diagnostico_item=' . $datos['id_diagnostico_item'];
        $editar = $this->SoporteItemDAO->editar($editar_item, $condicion_item);

        if ($_POST['estado_cotiza'] == 6) {
            $observacion = 'COTIZACION DE REPUESTOS CANCELADA';
            $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos['id_diagnostico'], $datos['item'], $observacion, $_SESSION['usuario']->getid_usuario());
        } else {
            $observacion = 'COTIZACION DE REPUESTOS ACEPTADA';
            $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos['id_diagnostico'], $datos['item'], $observacion, $_SESSION['usuario']->getid_usuario());
        }
        if ($editar == 1) {
            foreach ($repuestos as $value) {
                $id_cotizacion = ($value['id_cotizacion']);
                $formulario_cotiza = [
                    'estado' => $_POST['estado_cotiza'],
                ];
                $condicion = 'id_cotizacion =' . $id_cotizacion;
                $modificacion = $this->CotizacionItemSoporteDAO->editar($formulario_cotiza, $condicion);
            }
        } else {
            $modificacion = false;
        }
        echo json_encode($modificacion);
        return;
    }
    public function recotizar_diag()
    {
        header('Content-Type: application/json');
        $datos = $_POST['data'];
        $condicion = 'id_cotizacion =' . $datos['id_cotizacion'];
        $modificacion = $this->CotizacionItemSoporteDAO->eliminar($condicion);
        echo json_encode($modificacion);
        return;
    }
}

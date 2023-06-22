<?php

namespace MiApp\negocio\controladores\soporte_tecnico;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\SoporteItemDAO;
use MiApp\persistencia\dao\CotizacionItemSoporteDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\MiPedidoControlador;
use MiApp\negocio\util\Envio_Correo;
use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\PDF;

class ReparacionControlador extends GenericoControlador
{
    private $SoporteItemDAO;
    private $CotizacionItemSoporteDAO;
    private $entrada_tecnologiaDAO;
    private $ConsCotizacionDAO;
    private $PersonaDAO;

    public function __construct(&$cnn)
    {
        $this->SoporteItemDAO = new SoporteItemDAO($cnn);
        $this->CotizacionItemSoporteDAO = new CotizacionItemSoporteDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
        parent::__construct($cnn);
        parent::validarSesion();
    }

    public function ejecucion_reparacion()
    {
        parent::cabecera();
        $this->view(
            'soporte_tecnico/ejecucion_reparacion'
        );
    }

    public function asignar_tecnico()
    {
        header('Content-Type: application/json');
        $form = Validacion::Decodifica($_POST['form']);
        $data = $_POST['data'];
        $id_diagnostico_item = $_POST['id_diagnostico_item'];
        $persona = $this->PersonaDAO->consultar_personas_id($form['id_persona_reparacion']);

        // SE REALIZA EL INGRESO DEL SEGUIMIENTO
        $observacion = 'TÉCNICO ' . $persona[0]->nombres . ' ' . $persona[0]->apellidos . ' ASIGNADO PARA REPARACION';
        $seguimiento = GenericoControlador::agrega_seguimiento_diag($data['id_diagnostico'], $data['item'], $observacion, $_SESSION['usuario']->getid_usuario());

        $editar_item = [
            'id_persona_reparacion' => $form['id_persona_reparacion'],
            'fecha_ejecucion' => $form['fecha_ejecucion'],
            'estado' => 13,
        ];
        $condicion_item = 'id_diagnostico_item=' . $id_diagnostico_item;
        $editar = $this->SoporteItemDAO->editar($editar_item, $condicion_item);
        echo json_encode($editar);
        return;
    }
    public function consultar_datos_ejecucion()
    {
        header('Content-Type: application/json');
        $estado_item = '12,13';
        $id_persona = $_SESSION['usuario']->getId_persona();
        $roll = $_SESSION['usuario']->getid_roll();
        if ($roll != 1 && $id_persona != 104) {
            $sentencia = "AND t1.id_persona_reparacion= $id_persona";
        } else {
            $sentencia = '';
        }
        $datos_items = $this->SoporteItemDAO->consultar_aprobacion($estado_item, $sentencia);
        foreach ($datos_items as $value) {
            $repuestos_listos = 0;
            $sentencia = 'AND t1.num_acta=0';
            $repuestos = $this->SoporteItemDAO->consultar_repuestos_item($value->id_diagnostico, $value->item, $sentencia);
            foreach ($repuestos as $value_repu) {
                if ($value_repu->item == $value->item) {
                    if ($value_repu->estado_cotiza == 5) {
                        $repuestos_listos = $repuestos_listos + 1;
                    }
                }
            }
            $value->total_repuestos = count($repuestos);
            $value->repuestos_listos = $repuestos_listos;
            $value->repuestos = $repuestos;
        }
        $res['data'] = $datos_items;
        echo json_encode($res);
        return;
    }
    public function reparacion_ejecutada()
    {
        header('Content-Type: application/json');
        $datos = $_POST['data'];
        // SE REALIZA EL INGRESO DEL SEGUIMIENTO
        if ($_POST['estado_item'] == 16) {
            $observacion = 'REPARACION NO EJECUTADA';
            $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos['id_diagnostico'], $datos['item'], $observacion, $_SESSION['usuario']->getid_usuario());
        } else {
            $observacion = 'REPARACION EJECUTADA';
            $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos['id_diagnostico'], $datos['item'], $observacion, $_SESSION['usuario']->getid_usuario());
        }

        $repuestos = $datos['repuestos'];
        $editar_item = [
            'estado' => $_POST['estado_item'],
        ];
        $condicion_item = 'id_diagnostico_item=' . $datos['id_diagnostico_item'];
        $editar = $this->SoporteItemDAO->editar($editar_item, $condicion_item);
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
}

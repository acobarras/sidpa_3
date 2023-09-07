<?php

namespace MiApp\negocio\controladores\Comercial;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\AreaTrabajoDAO;
use MiApp\persistencia\dao\PrioridadesComercialDAO;
use MiApp\persistencia\dao\SeguimientoPrioridadesDAO;

class SolicitudPrioritariaControlador extends GenericoControlador
{
    private $AreaTrabajoDAO;
    private $PrioridadesComercialDAO;
    private $SeguimientoPrioridadesDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->AreaTrabajoDAO = new AreaTrabajoDAO($cnn);
        $this->PrioridadesComercialDAO = new PrioridadesComercialDAO($cnn);
        $this->SeguimientoPrioridadesDAO = new SeguimientoPrioridadesDAO($cnn);
    }

    public function vista_solicitud_prioritaria()
    {
        parent::cabecera();
        $this->view(
            'Comercial/vista_solicitud_prioritaria',
            [
                "area" => $this->AreaTrabajoDAO->consultar_area_sistema(),
            ]
        );
    }
    public function enviar_solicitud_prioritaria()
    {
        header('Content-Type:application/json');
        $formulario = Validacion::Decodifica($_POST['form']);
        $id_usuario = '';
        foreach ($_POST['id_areas'] as $value) {
            $person = $this->AreaTrabajoDAO->responde_prio($value); //Consulta para traer los id de los usuario de las areas que responden las prioridades
            for ($i = 0; $i < count($person); $i++) {
                if ($id_usuario == '') {
                    $id_usuario = $person[$i]->id_usuario;
                } else {
                    $id_usuario = $id_usuario . ',' . $person[$i]->id_usuario;
                }
            }
        }
        $crea = [
            'id_user_remite' => $_SESSION['usuario']->getid_usuario(),
            'id_user_recibe' => $id_usuario,
            'pedido' => $formulario['pedido'],    //Si el pedido viene en 0 es por que la prioridad no es para un pedido
            'item' => $formulario['item'],    //si el item esta en 0 es por que la prioridad va para el pédido completo
            'fecha_crea' => date('Y-m-d'),
            'hora_crea' => date('H:i:s'),
            'estado' => 1, //estado de prioridad creada
        ];
        $respu = $this->PrioridadesComercialDAO->insertar($crea);
        if ($respu['status'] == 1) {
            $crea_seg = [
                'id_prioridad' => $respu['id'],
                'mensaje' => $formulario['observacion'],
                'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                'estado' => 1,
                'fecha_crea' => date('Y-m-d H:i:s'),
            ];
            $seg = $this->SeguimientoPrioridadesDAO->insertar($crea_seg);
        }
        echo json_encode($respu);
        return;
    }
    public function mensaje_prioridad()
    {
        header('Content-Type:application/json');
        $formulario = Validacion::Decodifica($_POST['form']);
        $id_prioridad = $_POST['id_prioridad'];
        $crea_seg = [
            'id_prioridad' => $id_prioridad,
            'mensaje' => $formulario['observacion'],
            'id_usuario' => $_SESSION['usuario']->getid_usuario(),
            'estado' => 2,
            'fecha_crea' => date('Y-m-d'),
        ];
        $respu = $this->SeguimientoPrioridadesDAO->insertar($crea_seg);
        if ($respu['status'] == 1) {
            $cant_mensajes = $this->PrioridadesComercialDAO->consulta_cant_mensajes($id_prioridad);
            $id = explode(",", $cant_mensajes[0]->id_user_recibe);
            $cant_id = count($id);
            if ($cant_mensajes[0]->cant_respuestas >= $cant_id) {
                $edita = [
                    'estado' => 2, //estado de prioridad creada
                ];
                $condicion = 'id_prioridad=' . $id_prioridad;
                $edita = $this->PrioridadesComercialDAO->editar($edita, $condicion);
            }
        }
        echo json_encode($respu);
        return;
    }
    public function consultar_prioridades()
    {
        header('Content-Type:application/json');
        $condicion = '';
        if (isset($_POST['id_prioridad'])) {
            if ($_SESSION['usuario']->getId_roll() == 1) {
                $condicion = 'WHERE t2.estado=1';
            } else {
                $condicion = 'WHERE t1.id_user_remite=' . $_SESSION['usuario']->getid_usuario() . ' AND t2.estado=1';
            }
        } else {
            $formulario = Validacion::Decodifica($_POST['form']);
            $condicion = "WHERE t1.fecha_crea >='" . $formulario['fecha_desde'] . "' AND t1.fecha_crea <= '" . $formulario['fecha_hasta'] . "' AND t2.estado=1";
        }
        $prioridades = $this->PrioridadesComercialDAO->consulta_prioridades($condicion);
        foreach ($prioridades as $value) {
            $mensajes = $this->PrioridadesComercialDAO->mensajes_prioridad($value->id_prioridad);
            $mensaje_fin = '';
            foreach ($mensajes as $respu) {
                $mensaje_fin .= '<b>' . $respu->nombre . ' ' . $respu->apellido . ': ' . $respu->fecha_crea . '</b><br> ' . $respu->mensaje . '<br>';
            }
            $value->respuestas = $mensaje_fin;
        }
        echo json_encode($prioridades);
        return;
    }
}

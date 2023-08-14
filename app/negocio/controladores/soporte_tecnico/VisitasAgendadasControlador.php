<?php

namespace MiApp\negocio\controladores\soporte_tecnico;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\SoporteTecnicoDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\SoporteItemDAO;
use MiApp\persistencia\dao\CotizacionItemSoporteDAO;
use MiApp\negocio\util\PDF;
use MiApp\negocio\util\Validacion;



class VisitasAgendadasControlador extends GenericoControlador
{
    private $SoporteTecnicoDAO;
    private $PersonaDAO;
    private $ConsCotizacionDAO;
    private $SoporteItemDAO;
    private $CotizacionItemSoporteDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->SoporteTecnicoDAO = new SoporteTecnicoDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
        $this->SoporteItemDAO = new SoporteItemDAO($cnn);
        $this->CotizacionItemSoporteDAO = new CotizacionItemSoporteDAO($cnn);
    }

    public function visitas_agendadas()
    {
        parent::cabecera();
        $this->view(
            'soporte_tecnico/visitas_agendadas',
            [
                'personas' => $this->PersonaDAO->consultar_personas(),
            ]
        );
    }
    public function carga_visita_agendada()
    {
        header('Content-Type: application/json');
        $estado = 5;
        $estado2 = 8;
        $id_persona = $_SESSION['usuario']->getId_persona();
        $roll = $_SESSION['usuario']->getid_roll();
        $data = [];
        $visita_agendada = $this->SoporteTecnicoDAO->visitas_agendadas($estado, $estado2);
        foreach ($visita_agendada as $value) {
            if ($roll == 1 || $id_persona == 104) {
                array_push($data, $value);
            } else {
                if ($value->id_persona == $id_persona) {
                    array_push($data, $value);
                }
            }
        }
        $arreglo["data"] = $data;
        echo json_encode($arreglo);
    }

    public function generar_instalacion()
    {
        header('Content-type: application/pdf');
        $form = Validacion::Decodifica($_POST['form']);
        $firma = $_POST['firma'];
        $datos = $_POST['data'];
        // SE REGISTRA EL EQUIPO EN LA BASE DE DATOS 
        $formulario = [
            'id_diagnostico' => $datos['id_diagnostico'],
            'num_consecutivo' => $datos['num_consecutivo'],
            'item' => 1,
            'sede' => 1,
            'fecha_ingreso' => date('Y-m-d'),
            'id_cli_prov' => $datos['id_cli_prov'],
            'equipo' => $form['equipo'],
            'serial_equipo' => $form['serial'],
            'procedimiento' => 'INSTALACION DE EQUIPO',
            'accesorios' => null,
            'firma_cli' => $firma,
            'id_persona_reparacion' => 0,
            'fecha_ejecucion' => '0000-00-00',
            'id_persona_recibe' => $_SESSION['usuario']->getid_usuario(),
            'estado' => 2, //se envia en estado 2 para que no aparezca en el modulo de gestion diagnostico
            'fecha_crea' => date('Y-m-d'),
            'hora_crea' => date('H:i:s'),
        ];
        $agregar_equipo = $this->SoporteItemDAO->insertar($formulario);

        // SE REALIZA EL INGRESO DEL SEGUIMIENTO
        $id_actividad = 99; //INSTALACION DE EQUIPO NUEVO
        $observacion = 'INSTALACION DE EQUIPO NUEVO';
        $seguimiento = GenericoControlador::agrega_seguimiento_diag($datos['id_diagnostico'], 1, $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());

        if ($agregar_equipo['status'] == 1) {
            $num_cotizacion = $this->ConsCotizacionDAO->consultar_cons_especifico(16);
            $nuevo_cons = $num_cotizacion[0]->numero_guardado + 1;
            $consecutivo_cotiza = [
                'numero_guardado' => $nuevo_cons
            ];
            $condicion_produc = 'id_consecutivo =16';
            $this->ConsCotizacionDAO->editar($consecutivo_cotiza, $condicion_produc);

            $num_acta = $this->ConsCotizacionDAO->consultar_cons_especifico(17);
            $nuevo_cons = $num_acta[0]->numero_guardado + 1;
            $edita_acta = [
                'numero_guardado' => $nuevo_cons
            ];
            $condicion_acta = 'id_consecutivo =17';
            $this->ConsCotizacionDAO->editar($edita_acta, $condicion_acta);

            // SE REGISTRA LA COTIZACION DEL REPUESTO
            $formulario_producto = [
                'id_diagnostico' => $datos['id_diagnostico'],
                'num_cotizacion' => $num_cotizacion[0]->numero_guardado,
                'num_acta' => 'ENT' . $num_acta[0]->numero_guardado,
                'item' => 1,
                'moneda' => 1,
                'valor' => 0,
                'cantidad' => 1,
                'id_producto' => $form['codigo_producto'],
                'estado' => 8,
                'fecha_crea' => date('Y-m-d'),
                'hora_crea' => date('H:i:s'),
            ];
            $agregar_item = $this->CotizacionItemSoporteDAO->insertar($formulario_producto);
            // SE REALIZA EL CAMBIO DE ESTADO PARA QUE QUEDE CERRADO EL DIAGNOSTICO
            $formulario_diag = [
                'estado' => 14,
            ];
            $condicion_diag = 'id_diagnostico =' . $datos['id_diagnostico'];
            $this->SoporteTecnicoDAO->editar($formulario_diag, $condicion_diag);

            // SE REGISTRA EL SEGUIMIENTO DE CIERRE
            $id_actividad = 100; //CIERRE DIAGNOSTICO POR INSTALACION
            $observacion_cierre = 'CIERRE DIAGNOSTICO POR INSTALACION';
            $seguimiento_cierre = GenericoControlador::agrega_seguimiento_diag($datos['id_diagnostico'], 1, $id_actividad, $observacion_cierre, $_SESSION['usuario']->getid_usuario());
            $numero_acta = 'ENT' . $num_acta[0]->numero_guardado;
            // SE GENERA EL ACTA DE ENTREGA
            $crea_acta_entrega = GenericoControlador::crear_acta_entrega($numero_acta, 1, $firma);
        }
    }
}

<?php

namespace MiApp\negocio\controladores\talento_humano;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\PerfilCargoDAO;
use MiApp\persistencia\dao\DescargosDAO;
use MiApp\persistencia\dao\SolicitudPersonalDAO;

class TalentoHumanoControlador extends GenericoControlador
{

    private $PersonaDAO;
    private $PerfilCargoDAO;
    private $DescargosDAO;
    private $SolicitudPersonalDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->PerfilCargoDAO = new PerfilCargoDAO($cnn);
        $this->DescargosDAO = new DescargosDAO($cnn);
        $this->SolicitudPersonalDAO = new SolicitudPersonalDAO($cnn);
    }

    public function vista_solicitud()
    {
        parent::cabecera();
        $this->view(
            'talento_humano/vista_solicitud',
            [
                'liderProceso' => $this->PersonaDAO->jefe_imediato(),
                'perfilCargo' => $this->PerfilCargoDAO->ConsultaPerfilCargo(),
            ]
        );
    }

    public function colaborador_lider()
    {
        header("Content-type: application/json; charset=utf-8");
        $dato = $_POST['dato'];
        $datos = $this->PersonaDAO->PersonasJejeImediato($dato);
        echo json_encode($datos);
        return;
    }

    public function add_descargo()
    {
        header("Content-type: application/json; charset=utf-8");
        $form = $_POST;
        $form['estado'] = 1;
        $form['fecha_crea'] = date('Y-m-d');
        $grabo = $this->DescargosDAO->insertar($form);
        echo json_encode($grabo);
        return;
    }

    // FUNCIONES PARA EL TRAMITE DE DESCARGOS

    public function vista_descargos()
    {
        parent::cabecera();
        $this->view(
            'talento_humano/vista_descargos'
        );
    }

    public function tabla_descargos()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $this->DescargosDAO->ConsultaSolicitudDescargos(1);
        $soporte = array(0 => '', 1 => 'Si', 2 => 'No');
        foreach ($datos as $value) {
            $datos_lider = $this->PersonaDAO->consultar_personas_id($value->id_lider_proceso);
            $value->lider = $datos_lider[0]->nombres . " " . $datos_lider[0]->apellidos;
            $datos_colaborador = $this->PersonaDAO->consultar_personas_id($value->id_colaborador);
            $value->colaborador = $datos_colaborador[0]->nombres . " " . $datos_colaborador[0]->apellidos;
            $value->soporte = $soporte[$value->soporte_falla];
        }
        $respu['data'] = $datos;
        echo json_encode($respu);
        return;
    }

    public function add_personal()
    {
        header("Content-type: application/json; charset=utf-8");
        $form = $_POST;
        $form['estado'] = 1;
        $form['fecha_crea'] = date('Y-m-d H:i:s');
        $grabo = $this->SolicitudPersonalDAO->insertar($form);
        echo json_encode($grabo);
        return;
    }

    public function vista_personal()
    {
        parent::cabecera();
        $this->view(
            'talento_humano/vista_personal'
        );
    }

    public function tabla_personal()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $this->SolicitudPersonalDAO->ConsultaSolicitudPersonal(1);
        foreach ($datos as $value) {
            $datos_lider = $this->PersonaDAO->consultar_personas_id($value->id_lider_proceso);
            $value->lider = $datos_lider[0]->nombres . " " . $datos_lider[0]->apellidos;
        }
        $respu['data'] = $datos;
        echo json_encode($respu);
        return;
    }

    public function editar_descargos()
    {
        header("Content-type: application/json; charset=utf-8");
        $form = $_POST['form'];
        $form = Validacion::Decodifica($form);
        $form['estado'] = $_POST['estado'];
        $id_descargo = $_POST['id_descargo'];
        $condicion = 'id_descargo =' . $id_descargo;
        $respu = $this->DescargosDAO->editar($form, $condicion);
        echo json_encode($respu);
        return;
    }

    public function tabla_ejecucion_descargos()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $this->DescargosDAO->ConsultaSolicitudDescargos('2,3');
        $soporte = array(0 => '', 1 => 'Si', 2 => 'No');
        foreach ($datos as $value) {
            $datos_lider = $this->PersonaDAO->consultar_personas_id($value->id_lider_proceso);
            $value->lider = $datos_lider[0]->nombres . " " . $datos_lider[0]->apellidos;
            $datos_colaborador = $this->PersonaDAO->consultar_personas_id($value->id_colaborador);
            $value->colaborador = $datos_colaborador[0]->nombres . " " . $datos_colaborador[0]->apellidos;
            $value->soporte = $soporte[$value->soporte_falla];
        }
        $respu['data'] = $datos;
        echo json_encode($respu);
        return;
    }

    public function tabla_pendiente_respuesta_descargos()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $this->DescargosDAO->ConsultaSolicitudDescargos('4');
        $soporte = array(0 => '', 1 => 'Si', 2 => 'No');
        foreach ($datos as $value) {
            $datos_lider = $this->PersonaDAO->consultar_personas_id($value->id_lider_proceso);
            $value->lider = $datos_lider[0]->nombres . " " . $datos_lider[0]->apellidos;
            $datos_colaborador = $this->PersonaDAO->consultar_personas_id($value->id_colaborador);
            $value->colaborador = $datos_colaborador[0]->nombres . " " . $datos_colaborador[0]->apellidos;
            $value->soporte = $soporte[$value->soporte_falla];
        }
        $respu['data'] = $datos;
        echo json_encode($respu);
        return;
    }

    public function tabla_todos_descargos()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $this->DescargosDAO->ConsultaSolicitudDescargos('1,2,3,4,5');
        $soporte = array(0 => '', 1 => 'Si', 2 => 'No');
        foreach ($datos as $value) {
            $datos_lider = $this->PersonaDAO->consultar_personas_id($value->id_lider_proceso);
            $value->lider = $datos_lider[0]->nombres . " " . $datos_lider[0]->apellidos;
            $datos_colaborador = $this->PersonaDAO->consultar_personas_id($value->id_colaborador);
            $value->colaborador = $datos_colaborador[0]->nombres . " " . $datos_colaborador[0]->apellidos;
            $value->soporte = $soporte[$value->soporte_falla];
        }
        $respu['data'] = $datos;
        echo json_encode($respu);
        return;
    }

    public function editar_personal()
    {
        header("Content-type: application/json; charset=utf-8");
        $form = $_POST['form'];
        $form = Validacion::Decodifica($form);
        $form['estado'] = $_POST['estado'];
        $id_personal = $_POST['id_personal'];
        $condicion = 'id_personal =' . $id_personal;
        $respu = $this->SolicitudPersonalDAO->editar($form, $condicion);
        echo json_encode($respu);
        return;
    }

    public function tabla_proceso_seleccioneditar_personal()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $this->SolicitudPersonalDAO->ConsultaSolicitudPersonal(2);
        foreach ($datos as $value) {
            $datos_lider = $this->PersonaDAO->consultar_personas_id($value->id_lider_proceso);
            $value->lider = $datos_lider[0]->nombres . " " . $datos_lider[0]->apellidos;
        }
        $respu['data'] = $datos;
        echo json_encode($respu);
        return;
    }

    public function tabla_entrevista()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $this->SolicitudPersonalDAO->ConsultaSolicitudPersonal(3);
        foreach ($datos as $value) {
            $datos_lider = $this->PersonaDAO->consultar_personas_id($value->id_lider_proceso);
            $value->lider = $datos_lider[0]->nombres . " " . $datos_lider[0]->apellidos;
        }
        $respu['data'] = $datos;
        echo json_encode($respu);
        return;
    }

    public function tabla_pruebas()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $this->SolicitudPersonalDAO->ConsultaSolicitudPersonal(4);
        foreach ($datos as $value) {
            $datos_lider = $this->PersonaDAO->consultar_personas_id($value->id_lider_proceso);
            $value->lider = $datos_lider[0]->nombres . " " . $datos_lider[0]->apellidos;
        }
        $respu['data'] = $datos;
        echo json_encode($respu);
        return;
    }

    public function tabla_contratacion()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $this->SolicitudPersonalDAO->ConsultaSolicitudPersonal(5);
        foreach ($datos as $value) {
            $datos_lider = $this->PersonaDAO->consultar_personas_id($value->id_lider_proceso);
            $value->lider = $datos_lider[0]->nombres . " " . $datos_lider[0]->apellidos;
        }
        $respu['data'] = $datos;
        echo json_encode($respu);
        return;
    }

    public function tabla_final()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $this->SolicitudPersonalDAO->SolicitudPersonal();
        foreach ($datos as $value) {
            $datos_lider = $this->PersonaDAO->consultar_personas_id($value->id_lider_proceso);
            $value->lider = $datos_lider[0]->nombres . " " . $datos_lider[0]->apellidos;
        }
        $respu['data'] = $datos;
        echo json_encode($respu);
        return;
    }
}

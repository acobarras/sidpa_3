<?php

namespace MiApp\negocio\controladores\indicadores;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\DesperdicioOpDAO;
use MiApp\persistencia\dao\ProgramacionOperarioDAO;


class ProductividadControlador extends GenericoControlador
{
    private $PersonaDAO;
    private $DesperdicioOpDAO;
    private $ProgramacionOperarioDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();
        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->DesperdicioOpDAO = new DesperdicioOpDAO($cnn);
        $this->ProgramacionOperarioDAO = new ProgramacionOperarioDAO($cnn);
    }

    public function vista_productividad()
    {
        parent::cabecera();
        $this->view(
            'indicadores/vista_productividad'
        );
    }

    public function consulta_productividad()
    {
        header("Content-type: application/json; charset=utf-8");
        $data_operario = $this->PersonaDAO->personal_produccion();
        $fecha_desde = $_POST['fecha_desde'];
        $fecha_hasta = $_POST['fecha_hasta'];
        foreach ($data_operario as $value) {
            $metros = $this->DesperdicioOpDAO->metros_lineales_productividad($value->id_persona, $fecha_desde, $fecha_hasta);
            $horas = $this->ProgramacionOperarioDAO->horas_productividad($value->id_persona, $fecha_desde, $fecha_hasta);
            $value->total_ml = 0;
            $value->fecha_desde = $fecha_desde;
            $value->fecha_hasta = $fecha_hasta;
            $value->total_horas = 0;
            if ($horas[0]->total_horas != '') {
                $value->total_horas = $horas[0]->total_horas;
            }
            if ($metros[0]->total_ml != '') {
                $value->total_ml = $metros[0]->total_ml;
            }
        }
        echo json_encode($data_operario);
        return;
    }
    public function detalle_productividad()
    {
        header("Content-type: application/json; charset=utf-8");
        $id_persona = $_POST['data']['id_persona'];
        $fecha_desde = $_POST['data']['fecha_desde'];
        $fecha_hasta = $_POST['data']['fecha_hasta'];
        $detalle = $this->DesperdicioOpDAO->detalles_productividad($id_persona, $fecha_desde, $fecha_hasta);
        echo json_encode($detalle);
        return;
    }
}

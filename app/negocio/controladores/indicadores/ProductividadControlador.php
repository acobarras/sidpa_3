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
        $fecha = $_POST['mes'];
        foreach ($data_operario as $value) {
            $metros = $this->DesperdicioOpDAO->metros_lineales_productividad($value->id_persona, $fecha);
            $horas = $this->ProgramacionOperarioDAO->horas_productividad($value->id_persona, $fecha);
            $value->total_ml = 0;
            $value->mes_consulta = $fecha;
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
        $fecha = $_POST['data']['mes_consulta'];
        $detalle = $this->DesperdicioOpDAO->detalles_productividad($id_persona, $fecha);
        echo json_encode($detalle);
        return;
    }
}

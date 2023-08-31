<?php

namespace MiApp\negocio\controladores\Comercial;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\AreaTrabajoDAO;

class SolicitudPrioritariaControlador extends GenericoControlador
{
    private $AreaTrabajoDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->AreaTrabajoDAO = new AreaTrabajoDAO($cnn);
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
        $id_areas = '';
        foreach ($_POST['id_areas'] as $value) {
            if ($id_areas == '') {
                $id_areas .= $value;
            } else {
                $id_areas .= ',' . $value;
            }
        }
        $formulario['id_user_recibe'] = $id_areas;
        print_r($_POST);
        print_r($formulario);
    }
}
